<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Pickup options */
$options = $this->scan_options_table();

/**
 * If this is the postback
 */
if ( isset( $_POST['reset'] ) && $_POST['reset'] === 'true' && check_admin_referer( 'deletefacebook_' . get_current_user_id() ) ) {

	foreach ( $this->options_whitelist as $option ) {
		delete_option( $option );
	}

	$options = $this->scan_options_table();

}

?>

<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>Reset Facebook for WooCommerce</h2>

    <h3>Found <?php echo esc_html( count( $options ) ); ?> Facebook for WooCommerce options.</h3>

	<?php
	/**
	 * Only if Facebook for WooCommerce is not active, and there are options loaded
	 */
    if ( ! is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) && count( $options ) > 0 && user_can( get_current_user_id(), 'manage_options' ) ): ?>

        <form method="post">

            <input type="hidden" name="reset" value="true">
			<?php wp_nonce_field( 'deletefacebook_' . get_current_user_id() ); ?>

            <button type="submit" class="button button-primary error" value="Reset Facebook for WooCommerce">
                Reset Facebook for WooCommerce
            </button>

        </form>

	<?php endif; ?>

	<?php
	/**
	 * Disable if Facebook for WooCommerce is active
	 */
    if ( is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) ): ?>

        <h3>Please deactivate WooCommerce for Facebook before continuing.</h3>

        <button class="button button-disabled">
            Reset Facebook for WooCommerce
        </button>

	<?php endif; ?>

</div>

