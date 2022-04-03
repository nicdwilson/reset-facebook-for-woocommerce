<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Connection_Test {

	protected static $instance = null;

	public static function init(): ?Connection_Test {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		add_action( 'admin_post_run_connection_test', array( $this, 'run_connection_test' ) );
	}

	public function run_connection_test(){

		/**
		 *
		$integration = new \WC_Facebookcommerce_Integration();
		$test = new \WC_Facebook_Integration_Test( $integration );
		$post_ids = $test->create_data();
		$test->sleep_til_upload_complete(60);
		foreach( $test::$retailer_ids as $retailer_id ){
			$test->check_product_info( $retailer_id, null, null );
		}
		 *
		 */

	}

}