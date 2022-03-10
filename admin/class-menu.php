<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Menu {

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

		$actions = new Admin_Actions();
		$options = $actions->options_data;

		ob_start();
        /**
         * Reset options
         */
		include 'views/render-reset.php';
		$html = ob_get_clean();
		echo $html;

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