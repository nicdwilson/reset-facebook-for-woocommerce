<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Product_Data_Actions {

	protected static $instance = null;

	public static function init(): ?Product_Data_Actions {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		add_action( 'admin_post_delete_facebook_product_data', array( $this, 'delete_product_data' ) );
		add_action( 'admin_post_reset_facebook_product_data', array( $this, 'reset_product_data' ) );
		add_action( 'admin_post_delete_orphaned_facebook_data', array( $this, 'delete_orphaned_data' ) );
	}

	/**
	 * Delete orphaned products from catalogue using a manually supplied Facebook retailer ID.
	 *
	 * @return void
	 */
	public function delete_orphaned_data() {

		$wpnonce = $_REQUEST['_wpnonce'];
		$this->do_security_checks( $wpnonce );

		$retailer_id = ( isset( $_REQUEST['content_id'] ) ) ? array( sanitize_text_field( $_REQUEST['content_id'] ) ) : array();

		/**
		 * todo handle returned empty content_id
		 */
		if ( empty( $retailer_id ) ) {
			header( 'Location:' . $_SERVER["HTTP_REFERER"] . '&missing_content_id=1' );
			exit();
		}

		facebook_for_woocommerce()->get_products_sync_handler()->delete_products( $retailer_id );

		header( 'Location:' . $_SERVER["HTTP_REFERER"] );
		exit();
	}

	/**
	 * todo reset product meta on Facebook
	 *
	 * @return void
	 */
	public function reset_product_data() {

		$wpnonce = $_REQUEST['_wpnonce'];
		$this->do_security_checks( $wpnonce );

	}

	/**
	 * Deletes all product data from Facebook (we hope)
	 *
	 * @return void
	 */
	public function delete_product_data() {

		$wpnonce = $_REQUEST['_wpnonce'];
		$this->do_security_checks( $wpnonce );

		echo '<pre>';
		echo 'Deleting product data...<br>';
		/**
		 * A lock is set to prevent two jobs running simultaneously. This is renewed while the loop runs.
		 * Currently set to 60 seconds
		 * todo reset to 300
		 */
		$is_running = get_transient( 'tfb4wc_joblock' );

		if ( $is_running === '1' ) {
			die( 'A Facebook products troubleshooting job is running and has not completed or may have stalled. Please wait a few minutes and try again.<br>' . $this->get_backlink() );
		} else {
			echo 'Setting lock.<br>';
			set_transient( 'tfb4wc_joblock', 1, 60 );
		}

		$offset         = (int) get_option( 'tfb4wc_delete_offset', 0 );
		$posts_per_page = ( isset( $_POST['delete_posts_per_page'] ) ) ? (int) $_POST['delete_posts_per_page'] : 10;
		$product_cat    = ( isset( $_POST['delete_product_cat'] ) ) ? (int) $_POST['delete_product_cat'] : 0;
		$arguments      = $this->build_query_arguments( $posts_per_page, $product_cat );

		echo 'Found ' . $this->count_products() . ' products on site<br>';

		do {

			$arguments['offset'] = $offset;
			$product_ids         = get_posts( $arguments );
			$retailer_ids        = array();

			if ( ! empty( $product_ids ) ) {

				foreach ( $product_ids as $product_id ) {

					$product = wc_get_product( $product_id );

					if ( $product ) {
						$retailer_id = \WC_Facebookcommerce_Utils::get_fb_retailer_id( $product );
						echo 'Adding: ID: ' . $product_id . ' | SKU: ' . $product->get_sku() . ' | Facebook ID: ' . $retailer_id . '<br>';
						$retailer_ids[] = $retailer_id;
					}

					// todo we have no feedback loop so cannot delete this, but what is the effect? Probably horrible.
					//delete_post_meta( $product_id, self::FB_PRODUCT_ITEM_ID );
					//delete_post_meta( $product_id, self::FB_PRODUCT_GROUP_ID );
				}
			}

			/**
			 * Enqueue background deletion
			 */
			echo 'Delete batch contains ' . count( $retailer_ids ) . ' products.<br>';
			facebook_for_woocommerce()->get_products_sync_handler()->delete_products( $retailer_ids );

			/**
			 * Increment the offset
			 */
			$offset += $posts_per_page;

			/**
			 * Keep the job lock current
			 */
			echo 'Resetting lock.<br>';
			set_transient( 'tfb4wc_joblock', 1, 60 );

			/**
			 * Keep track of how far we made it in case we hit a script timeout
			 */
			echo 'Resetting offset<br>';
			update_option( 'tfb4wc_delete_offset', $offset );

		} while ( count( $product_ids ) == $posts_per_page );

		/**
		 * Clean up job lock and reset the offset
		 */
		echo 'Removing lock.<br>';
		delete_transient( 'tfb4wc_joblock' );
		echo 'Deleting offset.<br>';
		delete_option( 'tfb4wc_delete_offset' );

		/**
		 * Provide a back link
		 */
		echo $this->get_backlink();

	}

	/**
	 * Returns an HTML link to the http_referrer
	 *
	 * @return string
	 */
	private function get_backlink() {
		return '<a href="' . $_SERVER["HTTP_REFERER"] . '">Go back</a>';
	}

	/**
	 * Builds the query arguments for the product selection
	 *
	 * @param $posts_per_page
	 * @param $product_cat
	 *
	 * @return array
	 */
	private function build_query_arguments( $posts_per_page = 10, $product_cat = 0 ) {

		$arguments['post_type']      = 'product';
		$arguments['post_status']    = 'any';
		$arguments['fields']         = 'ids';
		$arguments['posts_per_page'] = $posts_per_page;

		if ( ! empty( $product_cat ) ) {

			$tax_query['tax_query'] = 'product_cat';
			$tax_query['field']     = 'product_cat';
			$tax_query['terms']     = array_merge( array( $product_cat ) );

			$arguments['tax_query'] = $tax_query;
		}

		return $arguments;
	}

	/**
	 * Returns the total number of products on the site, in case we need
	 * a handbrake later
	 *
	 * @return int
	 */
	private function count_products() {

		$args = array(
			'post_type'      => 'product',
			'fields'         => 'ids',
			'posts_per_page' => 1
		);

		$products = new \WP_Query( $args );

		return $products->found_posts;
	}

	/**
	 * Do nonce checks, because DRY.
	 *
	 * @return void
	 */
	function do_security_checks( $wpnonce ) {

		/*
		 * Check user caps
		 */
		if ( ! current_user_can( 'manage_options' ) ) {
			header( 'Location:' . $_SERVER["HTTP_REFERER"] . '?error=unauthenticated' );
			exit();
		}

		/*
		 * Check nonce
		 */
		if ( ! wp_verify_nonce( $wpnonce, 'product_data_job' ) ) {
			die( 'Cheatin\' huh?' );
		}

		/*
		 * We need Facebook for WooCommerce to be active to do any of this
		 */
		if ( ! function_exists( 'facebook_for_woocommerce' ) ) {
			die( 'Facebook for WooCommerce needs to be active for this to run. Please go back and activate the extension.<br>' . $this->get_backlink() );
		}
	}

}