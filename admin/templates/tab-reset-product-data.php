<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>This will reset the metadata of all products in the catalogue <i>if they were created by Facebook for
        WooCommerce </i></p>

<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">

	<?php wp_nonce_field( 'product_data_job' ); ?>
    <input type="hidden" name="action" value="reset_facebook_product_data">

    <table>
        <tbody>
        <tr>
            <td>
                <label for="reset_posts_per_page">Batch size:</label>
            </td>
            <td>
                <select id="reset_posts_per_page" name="posts_per_page">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50" selected>50</option>
                    <option value="100">100</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <label for="reset_product_cat">Category:</label>
            </td>
            <td>
	            <?php
	            $args = array(
		            'name'              => 'reset_product_cat',
		            'value'             => 'term_id',
		            'taxonomy'          => 'product_cat',
		            'option_none_value' => 0,
		            'show_option_none'  => '',
		            'show_option_all'   => 'All'
	            );
	            wp_dropdown_categories( $args );
	            ?>
            </td>
        </tr>
        </tbody>
    </table>



	<?php
	echo submit_button(
		$text = "Reset all metadata",
		$type = 'delete button-primary',
		null,
		$wrap = true,
		array( 'style' => 'background: #d63638;border: #d63638;' )
	);
	?>

</form>
