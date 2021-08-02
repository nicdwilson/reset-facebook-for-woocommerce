<?php
/**
 * Created by PhpStorm.
 * User: nicdw
 * Date: 11/3/2018
 * Time: 2:48 PM
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<fieldset>
    <legend class="screen-reader-text"><span><?php echo esc_html( $field['title'] ); ?>></span></legend>
    <label for="proxyconnection">
        <input name="<?php echo esc_attr( $field['id'] ); ?>" type="checkbox"
               id="<?php echo esc_attr( $field['id'] ); ?>"
               value="1" <?php checked( $field['data']['value'], 1 ); ?> />
        <span><?php echo esc_html( $field['supplemental'] ); ?></span>
    </label>
</fieldset>