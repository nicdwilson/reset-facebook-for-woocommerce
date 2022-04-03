<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Test_Products {

	protected static $instance = null;

	public static function init(): ?Test_Products {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		add_action( 'admin_post_test_a_facebook_product', array( $this, 'test_a_facebook_product' ) );
	}


	public function test_a_facebook_product() {

		if ( ! current_user_can( 'manage_options' ) ) {
			header( 'Location:' . $_SERVER["HTTP_REFERER"] . '&error=unauthenticated' );
			exit();
		}

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'test_a_product' ) ) {
			die( 'Cheatin\' huh?' );
		}

		if ( ! function_exists( 'facebook_for_woocommerce' ) ) {
			die( 'Facebook for WooCommerce needs to be active for this to run. Please go back and activate the extension. <a href="' . $_SERVER["HTTP_REFERER"] . '">Go back</a>' );
		}

		$product_id = ( isset( $_REQUEST['product_id'] ) ) ? sanitize_text_field( $_REQUEST['product_id'] ) : '';

		if ( empty( $product_id ) ) {
			header( 'Location:' . $_SERVER["HTTP_REFERER"] . '&error=needs_product_id' );
			exit();
		}

		echo 'Starting test...';

		$instance = new \WC_Facebookcommerce_Integration();
		$product  = wc_get_product( $product_id );

		if ( ! empty( $product ) ) {
			$logger = new Validate_Product( $instance, $product );
			$logger->validate_and_log();
		}

		echo '<br><br><a href="' . $_SERVER["HTTP_REFERER"] . '">Go back</a>';
	}

}
