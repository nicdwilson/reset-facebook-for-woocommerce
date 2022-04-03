<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">


				<?php wp_nonce_field( 'run_connection_test' ); ?>
                <input type="hidden" name="action" value="run_connection_test">

				<?php
				echo submit_button(
					$text = "Run connection test",
					$type = 'disabled button-primary',
					null,
					$wrap = true,
					//array( 'style' => 'background: #d63638;border: #d63638;' )
				);
				?>

            </form>