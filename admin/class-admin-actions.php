<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Admin_Actions {

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

	public $options_data = array();

	protected static $instance = null;

	public static function init(): ?Admin_Actions {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		$this->options_data = $this->scan_options_table();
		add_action( 'admin_post_delete_facebook_options', array( $this, 'delete_facebook_options' ) );

	}


	/**
	 * Post admin action that deletes all existing facebook options, checked against the whitelist
	 * (we don't want to delete anything at random)
	 *
	 * @return void
	 */
	public function delete_facebook_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			header( 'Location:' . $_SERVER["HTTP_REFERER"] . '?error=unauthenticated' );
			exit();
		}

		if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'deletefacebook_' . get_current_user_id() ) ) {

			$options_inDB = $this->scan_options_table();

			$options = array_intersect( $this->options_whitelist, $options_inDB );

			if ( ! empty( $options ) ) {

				foreach ( $options as $option ) {
					delete_option( $option );
				}

				//Pesky leftover pixel_id
				delete_option( 'facebook_config' );
			}

			/**
			 * Pick up options from the DB, check them against the whitelist
			 */
			$options_inDB = $this->scan_options_table();
			$options      = array_intersect( $this->options_whitelist, $options_inDB );

			/**
			 * redirecto referrer
			 */
			header( 'Location:' . $_SERVER["HTTP_REFERER"] );
			exit();

		} else {
			die( 'Cheatin\' huh?' );
		}

	}

	/**
	 * Grab array of options starting with 'wc_facebook_'
	 *
	 * @return array
	 */
	private
	function scan_options_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'options';
		$sql        = "SELECT option_name FROM $table_name WHERE option_name LIKE 'wc_facebook_%'";
		$options    = $wpdb->get_col( $sql, 0 );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		return $options;

	}

}

add_action( 'init', array( 'TFB4WC\Admin_Actions', 'init' ) );
