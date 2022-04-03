<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3>Delete all products in the catalogue</h3>

<p>This will delete all products in the catalogue <i>if they were created by Facebook for WooCommerce</i>
</p>
<p>
    If you have orphaned data - products that are not removed from Facebook by this function, and that Facebook refuses to delete manually because they are created by WooCommerce, please use <a href="<?php echo esc_url( admin_url() ); ?>tools.php?page=troubleshoot-facebook-for-woocommerce&tab=remove-orphans">Remove orphans</a>
</p>


<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">

	<?php wp_nonce_field( 'product_data_job' ); ?>
	<input type="hidden" name="action" value="delete_facebook_product_data">

	<label for="delete_posts_per_page">Batch size:</label>
	<select id="delete_posts_per_page" name="posts_per_page">
		<option value="20">10</option>
		<option value="20">25</option>
		<option value="20" selected>50</option>
		<option value="20">100</option>
		<option value="20">20</option>
	</select>

	<?php
	echo submit_button(
		$text = "Delete all products",
		$type = 'delete button-primary',
		null,
		$wrap = true,
		array( 'style' => 'background: #d63638;border: #d63638;' )
	);
	?>

</form>

