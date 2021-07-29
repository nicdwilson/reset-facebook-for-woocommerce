<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Pick up options from the DB, check them against the whitelist
 */
$options_inDB = $this->scan_options_table();
$options = array_intersect($this->options_whitelist, $options_inDB);

var_dump('<pre>');
var_dump($_POST);
var_dump('</pre>');

/**
 * If this is the postback for reset
 */
if (isset($_POST['reset']) && $_POST['reset'] === 'true' && check_admin_referer('deletefacebook_' . get_current_user_id())) {

    foreach ($this->options_whitelist as $option) {
        delete_option($option);
    }

    /**
     * Pick up options from the DB, check them against the whitelist
     */
    $options_inDB = $this->scan_options_table();
    $options = array_intersect($this->options_whitelist, $options_inDB);

}

/**
 * If this is the postback for proxy
 */
if (isset($_POST['proxy']) && $_POST['proxy'] === 'true' && check_admin_referer('proxyconnection_' . get_current_user_id())) {

    /**
     * Pick up options from the DB, check them against the whitelist
     */
    $set_proxy = ( isset( $_POST['proxyconnection'] ) ) ? intval( $_POST['proxyconnection'] ) : 0;
    update_option('tfb4wc_set_proxy', $set_proxy);

}else{

}


?>

<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>Troubleshoot Facebook for WooCommerce</h2>

    <hr>

    <h3>Delete all WooCommerce for Facebook options</h3>
    <h4>This is only for when you want to completely reset your connection with Facebook, after all else has
        failed.</h4>

    <h4>You have <?php echo esc_html(count($options)); ?> Facebook for WooCommerce options stored in your database.</h4>

    <?php
    /**
     * Only if Facebook for WooCommerce is not active, and there are options loaded
     */
    if (!is_plugin_active('facebook-for-woocommerce/facebook-for-woocommerce.php') && count($options) > 0 && user_can(get_current_user_id(), 'manage_options')): ?>

        <form method="post">

            <input type="hidden" name="reset" value="true">
            <?php wp_nonce_field('deletefacebook_' . get_current_user_id()); ?>


            <?php echo submit_button($text = "Delete all options", $type = 'delete button-primary', $name = 'delete_options', $wrap = true, array('style' => 'background: #d63638;border: #d63638;')); ?>

        </form>

    <?php endif; ?>

    <?php
    /**
     * Disable if Facebook for WooCommerce is active
     */
    if (is_plugin_active('facebook-for-woocommerce/facebook-for-woocommerce.php')): ?>

        <h3>Please deactivate WooCommerce for Facebook if you want to delete options.</h3>

        <button class="button button-disabled">
            Delete all options
        </button>

    <?php endif; ?>

    <br>
    <br>
    <hr>

    <?php
    /**
     * Connection test requires Facebook for WooCommerce to be active
     */
    if (!is_plugin_active('facebook-for-woocommerce/facebook-for-woocommerce.php')): ?>

        <h3>Run a connection test</h3>


        <form method="post">

            <input type="hidden" name="proxy" value="true">
            <?php wp_nonce_field('proxyconnection_' . get_current_user_id()); ?>


            <fieldset>
                <legend class="screen-reader-text"><span>Proxy Facebook Connectione</span></legend>
                <label for="proxyconnection">
                    <input name="proxyconnection" type="checkbox" id="proxyconnection"
                           value="1" <?php checked(get_option('tfb4wc_set_proxy'), 1); ?> />
                    <span>Enable the proxy connection test.</span>
                </label>
            </fieldset>

            <?php echo submit_button($text = "Save proxy settings", $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null); ?>

        </form>


    <?php endif; ?>


</div>

