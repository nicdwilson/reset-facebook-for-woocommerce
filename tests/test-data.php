<?php

add_action( 'init', 'load_test_data' );
function load_test_data() {
	$data = array(
		array( 'wc_facebook_for_woocommerce_is_active', 'yes', 'yes' ),
		array(
			'wc_facebook_for_woocommerce_lifecycle_events',
			'[{\"name\":\"install\",\"time\":1625351462,\"version\":\"2.6.1\"}]',
			'no'
		),
		array( 'wc_facebook_for_woocommerce_version', '2.6.1', 'yes' ),
		array( 'wc_facebook_feed_url_secret', '952ee8fee7cfe0bc654372d594e65ddd', 'yes' ),
		array( 'wc_facebook_external_business_id', 'shop-60e0e52e02090', 'yes' ),
		array(
			'wc_facebook_access_token',
			'EAAGvQJc4N',
			'yes'
		),
		array(
			'wc_facebook_merchant_access_token',
			'EAAGvQJc4NAQ',
			'yes'
		),
		array( 'wc_facebook_system_user_id', '111457407861984', 'yes' ),
		array( 'wc_facebook_enable_messenger', 'no', 'yes' ),
		array( 'wc_facebook_page_id', '106311535051673', 'yes' ),
		array(
			'wc_facebook_page_access_token',
			'EAAGvQJc4NAQBAA8nvG2s36',
			'yes'
		),
		array( 'wc_facebook_pixel_id', '973777643384394', 'yes' ),
		array( 'wc_facebook_product_catalog_id', '519813615893065', 'yes' ),
		array( 'wc_facebook_business_manager_id', '196196962317341', 'yes' ),
		array( 'wc_facebook_commerce_merchant_settings_id', '311750590689647', 'yes' ),
		array( 'wc_facebook_has_connected_fbe_2', 'yes', 'yes' ),
		array( 'wc_facebook_has_authorized_pages_read_engagement', 'yes', 'yes' ),
		array( 'wc_facebook_pixel_install_time', '1625351763', 'yes' ),
		array( 'wc_facebook_enable_product_sync', 'no', 'yes' ),
		array( 'wc_facebook_excluded_product_category_ids', 'a:0:{}', 'yes' ),
		array( 'wc_facebook_excluded_product_tag_ids', 'a:0:{}', 'yes' ),
		array( 'wc_facebook_product_description_mode', 'standard', 'yes' ),
		array( 'wc_facebook_google_product_category_id', '', 'yes' )
	);

	foreach ( $data as $option ) {
		update_option( $option[0], $option[1], $option[2] );
	}
}
