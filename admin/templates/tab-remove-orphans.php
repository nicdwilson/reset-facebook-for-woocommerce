<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>


<h3>Remove orphaned data</h3>


<div>
    <h4>At times you will find products that cannot be deleted from Facebook when their metadata goes out of synch</h4>

    <image src="<?php echo plugins_url(); ?>/troubleshoot-facebook-for-woocommerce/assets/images/facebook-product-image.png" style="width:100%;" />

    <p>To remove this, enter the content id of your orphaned product from WooCommerce  below</p>
</div>

<div style="padding-top:20px;">
<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">


	<?php wp_nonce_field( 'product_data_job' ); ?>
    <input type="hidden" name="action" value="delete_orphaned_facebook_data">
<p>
    <label label-for="content_id"><strong>Content ID:</strong></label>
    <input type="text" id="content_id" name="content_id"  class="regular-text code" />
</p>


	<?php
	echo submit_button(
		$text = "Remove orphan from Facebook",
		$type = 'delete button-primary',
		null,
		$wrap = true,
		array( 'style' => 'background: #d63638;border: #d63638;' )
	);
	?>

</form>

</div>