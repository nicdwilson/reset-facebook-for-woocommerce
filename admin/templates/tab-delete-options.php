<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
            <h3>Delete all WooCommerce for Facebook options</h3>
            <h4>This is only for when you want to completely reset your connection with Facebook, after all else has
                failed.</h4>

            <h4>You have <?php echo esc_html( count( $options ) ); ?> Facebook for WooCommerce options stored in your
                database.</h4>

			<?php /**
			 * <form action="/wp-admin/admin-post.php" method="post">
			 *
			 * <input type="hidden" name="action" value="load_test_data">
			 *
			 * <?php
			 * echo submit_button(
			 * $text = "Load test data",
			 * $type = 'delete button-primary',
			 * null,
			 * $wrap = true,
			 * array( 'style' => 'background: #d63638;border: #d63638;' )
			 * );
			 * ?>
			 *
			 * </form>
			 */
			?>


            <form action="<?php echo esc_url( admin_url() ); ?>admin-post.php" method="post">


				<?php wp_nonce_field( 'delete_facebook_options' ); ?>
                <input type="hidden" name="action" value="delete_facebook_options">

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

            <div class="postbox">
                <div class="inside">

					<?php if ( count( $options ) > 0 ): ?>

                        <h3>Facebook configuration data</h3>

						<?php if ( is_plugin_active( 'facebook-for-woocommerce/facebook-for-woocommerce.php' ) ): ?>

                            <p>
                                Facebook for WooCommerce is active This means you will not be able to remove <i>all</i>
                                options and
                                some options may remain, even if you run <code>Delete all options</code>.<br>
                                The most important data to remove to reset your connection will be removed. Only a
                                handful of
                                options will remain.
                            </p>

						<?php endif; ?>

					<?php else: ?>

                        <h3>There are no stored options</h3>

					<?php endif; ?>

                    <table class="widefat">
                        <tbody>
						<?php
						$i = 0;
						foreach ( $options

						as $option ): ?>
                        <tr <?php echo ( $i % 2 == 0 ) ? 'class="alternate"' : ''; ?> >
                            <td class="row-title">

								<?php echo esc_textarea( $option ); ?>

                            </td>
                            <td>

								<?php
								$option_value = get_option( $option, '' );
								echo esc_textarea( $option_value ); ?>

                            </td>
							<?php $i ++;
							endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>