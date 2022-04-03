<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3>Validate products</h3>

<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">


	<?php wp_nonce_field( 'validate_products' ); ?>
    <input type="hidden" name="action" value="test_all_facebook_products">

	<?php
	echo submit_button(
		$text = "Validate all products",
		$type = 'disabled button-primary',
		null,
		$wrap = true,
	//array( 'style' => 'background: #d63638;border: #d63638;' )
	);
	?>

</form>

<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">


	<?php wp_nonce_field( 'test_a_product' ); ?>
    <input type="hidden" name="action" value="test_a_facebook_product">

    <label for="product_id"><strong>Product ID (post id):</strong></label>
    <input id="product_id" type="text" name="product_id" />

	<?php
	echo submit_button(
		$text = "Validate a product",
		$type = 'button-primary',
		null,
		$wrap = true,
	//array( 'style' => 'background: #d63638;border: #d63638;' )
	);
	?>

</form>
