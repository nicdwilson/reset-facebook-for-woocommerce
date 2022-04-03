<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<?php $job_count = ( new \TFB4WC\Clear_Facebook_Logs() )->get_scheduled_jobs_count(); ?>

<h3>Clear old log entries</h3>

<p>
	Clear all old log entries from the options table. This should run automatically every 24 hours, but
	where
	WordPress Cron was not running correctly, these entries can build up.
</p>
<p>
    See <a href="https://github.com/woocommerce/facebook-for-woocommerce/issues/2179"> Github issue</a>
</p>
<p> There are <?php echo $job_count; ?> job logs in your database.</p>

<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">

	<?php wp_nonce_field( 'clear_facebook_logs' ); ?>
	<input type="hidden" name="action" value="clear_facebook_logs">

	<?php
	if ( 0 < $job_count ) {

		echo submit_button(
			$text = "Clear 250 logs",
			$type = 'delete button-primary',
			null,
			$wrap = true,
			array( 'style' => 'background: #d63638;border: #d63638;' )
		);

	}
	?>

</form>

