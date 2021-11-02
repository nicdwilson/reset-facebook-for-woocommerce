<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Pick up options from the DB, check them against the whitelist
 */
$options_inDB = $this->scan_options_table();
$options      = array_intersect( $this->options_whitelist, $options_inDB );

/**
 * If this is the postback for reset
 */
if ( isset( $_POST['reset'] ) && $_POST['reset'] === 'true' && check_admin_referer( 'deletefacebook_' . get_current_user_id() ) ) {

	if( $options ){
		foreach ( $options as $option ) {
			delete_option( $option );
		}
		//Pesky leftover pixel_id
		delete_option('facebook_config');
    }

	/**
	 * Pick up options from the DB, check them against the whitelist
	 */
	$options_inDB = $this->scan_options_table();
	$options      = array_intersect( $this->options_whitelist, $options_inDB );

}

?>

<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>Troubleshoot Facebook for WooCommerce</h2>

	<?php $active_tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'delete-options'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=delete-options"
           class="nav-tab <?php echo 'delete-options' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Delete options
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=connection-test"
           class="nav-tab <?php echo 'connection-test' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Connection test
        </a>

    </h2>

	<?php if ( 'delete-options' === $active_tab ) : ?>

        <h3>Delete all WooCommerce for Facebook options</h3>
        <h4>This is only for when you want to completely reset your connection with Facebook, after all else has
            failed.</h4>

        <h4>You have <?php echo esc_html( count( $options ) ); ?> Facebook for WooCommerce options stored in your
            database.</h4>

		<?php
		/**
		 * Only if Facebook for WooCommerce is not active, and there are options loaded
		 */
		if ( ! is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) && count( $options ) > 0 && user_can( get_current_user_id(), 'manage_options' ) ): ?>

            <form method="post">

                <input type="hidden" name="reset" value="true">
				<?php wp_nonce_field( 'deletefacebook_' . get_current_user_id() ); ?>

				<?php
                    echo submit_button(
				        $text = "Delete all options",
                        $type = 'delete button-primary',
                        null,
                        $wrap = true,
                        array( 'style' => 'background: #d63638;border: #d63638;' )
                    );
				?>

            </form>

		<?php endif; ?>

		<?php
		/**
		 * Disable if Facebook for WooCommerce is active
		 */
		if ( is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) ): ?>

            <h3>Please deactivate WooCommerce for Facebook if you want to delete options.</h3>
	
	    <?php
		$facebook_config = get_option(facebook_config);
		if( is_array( $facebook_config ):
 	     ?>
	    <pre>
		Previously stored Facebook Config data
		<?php print_r( $facebook_config ); ?>
	    </pre>
            <?php endif; ?>

            <button class="button button-disabled">
                Delete all options
            </button>

		<?php endif; ?>

	<?php endif; ?>

	<?php if ( 'connection-test' === $active_tab ) : ?>

		<?php
		/**
		 * Connection test requires Facebook for WooCommerce to be active
		 */
		if ( ! is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) ): ?>

            <form method="post" action="options.php">

	            <?php settings_fields( 'facebook_connection_test' ); ?>
	            <?php do_settings_sections( 'troubleshoot-facebook-connection-test' ); ?>
				<?php echo submit_button( 'Save proxy settings' ); ?>

            </form>

		<?php endif; ?>

	<?php endif; ?>

</div>

