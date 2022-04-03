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
		
	}




	/**
	 * Render the menu page
	 */
	public function render_page() {

		$delete_action = new Delete_Options();
		$options = $delete_action->options_data;

		ob_start();
        /**
         * Reset options
         */
		include 'templates/menu.php';
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
		include 'templates/render_' . $field['input'] . '_field.php';
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