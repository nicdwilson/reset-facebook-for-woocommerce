<?php
/*
Plugin name: Reset Facebook for WooCommerce
Plugin URI: https://solutiondesign.co.za/afpfeedreader/plugin-readme/
Description: Remove options stored by Facebook for WooCommerce
Author: nicw
Version: Beta
Author URI:
*/

namespace RFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-menu.php';
}

class Reset_FB4WC {

	/*
	 * Version is a habit
	 */
	public $version = '1';

	protected static $instance = null;

	/**
	 * @return Reset_FB4WC|null
	 */
	public static function init(): ?Reset_FB4WC {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {

	    if( is_admin() ){
		    add_action( 'admin_menu', array( 'RFB4WC\Menu', 'init' ) );
		    add_action( 'admin_notices', array( $this, 'check_plugins' ) );
        }
	}

	/**
	 * Add an admin warning if Facebook for WooCommerce is active
	 */
	public function check_plugins(){

		if( is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) ): ?>

			<div class="notice notice-error error-alt">
				<p>Facebook for WooCommerce is active. Please deactivate it before using Reset Facebook for WooCommerce.</p>
			</div>

		<?php endif;

	}

}

add_action( 'init', array( 'RFB4WC\Reset_FB4WC', 'init' ) );