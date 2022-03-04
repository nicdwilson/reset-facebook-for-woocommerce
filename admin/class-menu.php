<?php

namespace TFB4WC;

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
		'facebook_config',
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
			'Troubleshoot Facebook for WooCommerce',
			'Troubleshoot Facebook for WooCommerce',
			'manage_options',
			'troubleshoot-facebook-for-woocommerce',
			array(
				$this,
				'render_page',
			)
		);

		add_settings_section( 'facebook_connection_test', 'Proxy connection test', null, 'troubleshoot-facebook-connection-test' );
		register_setting( 'facebook_connection_test', 'tsfb4wc_connection_test' );

		$this->fb_proxy_app_fields();

	}

	public function fb_proxy_app_fields(){

		$fields = array();

		/*
		* Set proxy app connection on
		*/
		array_push(
			$fields,
			array(
				'id'           => 'tsfb4wc_connection_test',
				'title'        => 'Enable proxy',
				'callback'     => 'render_field',
				'page'         => 'troubleshoot-facebook-connection-test',
				'input'        => 'tickbox',
				'section'      => 'facebook_connection_test',
				'supplemental' => 'Use the proxy app to test the connection to Facebook',
				'data'         => array( 'value' => get_option('tsfb4wc_connection_test') ),
			)
		);

		$this->register_fields( $fields );

	}


	/**
	 * Render the menu page
	 */
	public function render_page() {

		ob_start();
        /**
         * Reset options
         */
		include 'views/render-reset.php';
		$html = ob_get_clean();
		echo $html;

	}

	/**
	 * Grab array of options starting with 'wc_facebook_'
	 *
	 * @return array
	 */
	private function scan_options_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'options';
		$sql        = "SELECT option_name FROM $table_name WHERE option_name LIKE 'wc_facebook_%'";
		$options    = $wpdb->get_col( $sql, 0);

		if( !is_array( $options)){
		    $options = array();
        }

		return $options;

	}

	/**
	 * Echoes out the field views html based on the data in the $field array
	 *
	 * @param $field
	 */
	public function render_field( $field ) {

		$white_list = array( 'text', 'select', 'tickbox' );
		if ( ! in_array( $field['input'], $white_list, true ) ) {
			echo '';
		}

		ob_start();
		include 'views/render_' . $field['input'] . '_field.php';
		$html = ob_get_clean();
		echo $html;

	}

	/**
	 * Registers fields using array values in the $fields array
	 *
	 * @param $fields
	 *
	 * @return void
	 */
	public function register_fields( $fields ) {

		foreach ( $fields as $field ) {
			add_settings_field(
				$field['id'],
				$field['title'],
				array( $this, $field['callback'] ),
				$field['page'],
				$field['section'],
				$field
			);
		}

	}

}