<?php

namespace RFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Menu {

	/**
	 * Handbrake
	 *
	 * @var array
	 */
	private $options_whitelist = array(
		'wc_facebook_for_woocommerce_is_active',
		'wc_facebook_for_woocommerce_lifecycle_events',
		'wc_facebook_for_woocommerce_version',
		'wc_facebook_feed_url_secret',
		'wc_facebook_external_business_id',
		'wc_facebook_access_token',
		'wc_facebook_merchant_access_token',
		'wc_facebook_system_user_id',
		'wc_facebook_enable_messenger',
		'wc_facebook_page_id',
		'wc_facebook_page_access_token',
		'wc_facebook_pixel_id',
		'wc_facebook_product_catalog_id',
		'wc_facebook_business_manager_id',
		'wc_facebook_commerce_merchant_settings_id',
		'wc_facebook_has_connected_fbe_2',
		'wc_facebook_has_authorized_pages_read_engagement',
		'wc_facebook_pixel_install_time',
		'wc_facebook_enable_product_sync',
		'wc_facebook_excluded_product_category_ids',
		'wc_facebook_excluded_product_tag_ids',
		'wc_facebook_product_description_mode',
		'wc_facebook_google_product_category_id',
	);

	protected static $instance = null;

	public static function init(): ?Menu {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {
		$this->add_submenu_page();
	}

	/*
	* Add the page to the tools menu
	*/
	public function add_submenu_page() {

		add_submenu_page(
			'tools.php',
			'Reset Facebook for WooCommerce',
			'Reset Facebook for WooCommerce',
			'manage_options',
			'reset-facebook-for-woocommerce',
			array(
				$this,
				'render_page',
			)
		);
	}


	/*
	 * Render the menu page
	 */
	public function render_page() {

		ob_start();
		include 'views/render-page.php';
		$html = ob_get_clean();
		echo $html;

	}

	/**
	 * Grab array of options starting with 'wc_facebook_'
	 *
	 * @return array|object|null
	 */
	private function scan_options_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'options';
		$sql        = "SELECT option_name FROM $table_name WHERE option_name LIKE 'wc_facebook_%'";
		$options    = $wpdb->get_results( $sql, ARRAY_A );

		return $options;

	}

}