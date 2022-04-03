<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>This will reset the metadata of all products in the catalogue <i>if they were created by Facebook for
		WooCommerce </i></p>

<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">

	<input type="hidden" name="action" value="reset_product_data">

	<label for="reset_posts_per_page">Batch size:</label>
	<select id="reset_posts_per_page" name="posts_per_page">
		<option value="20">10</option>
		<option value="20">25</option>
		<option value="20" selected>50</option>
		<option value="20">100</option>
		<option value="20">20</option>
	</select>

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
