<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Clear_Facebook_Logs {

	public static $instance;

	public static function init(): ?Clear_Facebook_Logs {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		add_action( 'admin_post_clear_facebook_logs', array( $this, 'clear_all_scheduled_jobs' ) );
	}

	public function clear_all_scheduled_jobs() {

		if ( ! current_user_can( 'manage_options' ) ) {
			header( 'Location:' . $_SERVER["HTTP_REFERER"] . '?error=unauthenticated' );
			exit();
		}

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'clear_facebook_logs' ) ) {
			die( 'Cheatin\' huh?' );
		}

		$jobs = $this->get_scheduled_jobs();

			foreach ( $jobs as $job ) {
				delete_option( $job );
			}

		header( 'Location:' . $_SERVER["HTTP_REFERER"] );
		exit();
	}

	private function get_scheduled_jobs() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'options';
		$sql        = "
						SELECT option_name
						FROM $table_name
						WHERE
						option_name LIKE 'wc_facebook_background_product_sync_job_%'
						ORDER BY
						option_id ASC
						LIMIT 250
					";

		$jobs = $wpdb->get_col( $sql, 0 );

		if ( ! is_array( $jobs ) ) {
			$jobs = array();
		}

		return $jobs;
	}

	public function get_scheduled_jobs_count() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'options';
		$sql        = "SELECT count(option_id) FROM $table_name WHERE option_name LIKE 'wc_facebook_background_product_sync_job_%'";
		$job_count  = $wpdb->get_var( $sql );

		return $job_count;

	}

}