<?php

namespace TSFB4WC;

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

		add_action( 'admin_ajax_delete_product_data', array( $this, 'delete_product_data' ) );
		add_action( 'admin_ajax_reset_product_data', array( $this, 'reset_product_data' ) );
	}

	public function reset_product_data() {

	}

	public function delete_product_data() {

		if ( ! function_exists( 'facebook_for_woocommerce' ) ) {
			return;
		}

		if ( get_option( 'tsfb4wc_facebook_delete_all_products', false ) || ! is_admin() ) {
			return;
		}

		$offset         = (int) get_option( 'tsfb4wc_delete_product_data_offset', 0 );
		$posts_per_page = 500;

		do {
			$products = get_posts( array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'fields'         => 'ids',
				'offset'         => $offset,
				'posts_per_page' => $posts_per_page,
				// uncomment and update the lines below to select specific taxonomy terms to update
				// 'tax_query'      => array(
				// 	array(
				// 		'taxonomy' => 'product_cat',
				// 		'field'    => 'term_id',
				// 		'terms'    => array_merge( array( 849, 850, 851 ) ),
				// 	),
				// ),
			) );

			if ( ! empty( $products ) ) {

				foreach ( $products as $product_id ) {

					$product = wc_get_product( $product_id );

					if ( $product ) {
						$retailer_ids[] = \WC_Facebookcommerce_Utils::get_fb_retailer_id( $product );
					}

					facebook_for_woocommerce()->get_products_sync_handler()->delete_products( $retailer_ids );
				}
			}

			// increment offset
			$offset += $posts_per_page;

			// and keep track of how far we made it in case we hit a script timeout
			update_option( 'tsfb4wc_delete_product_data_offset', $offset );

		} while ( count( $products ) == $posts_per_page );

		if ( count( $products ) !== $posts_per_page ) {
			update_option( 'tsfb4wc_facebook_delete_all_products', 1 );
		}
	}

}