<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>Troubleshoot Facebook for WooCommerce</h2>

	<?php $active_tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'delete-options'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=delete-options"
           class="nav-tab <?php echo 'delete-options' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Delete options
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=connection-test"
           class="nav-tab <?php echo 'connection-test' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Connection test
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=reset-product-data"
           class="nav-tab <?php echo 'reset-product-data' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Reset product data
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=delete-product-data"
           class="nav-tab <?php echo 'delete-product-data' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Delete product data
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=remove-orphans"
           class="nav-tab <?php echo 'remove-orphans' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Remove orphans
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=clear-log"
           class="nav-tab <?php echo 'clear-log' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Clear logs
        </a>

        <a href="?page=troubleshoot-facebook-for-woocommerce&tab=test-products"
           class="nav-tab <?php echo 'test-products' === $active_tab ? 'nav-tab-active' : ''; ?>">
            Test products
        </a>


    </h2>

	<?php if ( isset( $_GET['error'] ) && $_GET['error'] === 'unauthenticated' ): ?>
        <h3>You need administrator priviliges to run this plugin.</h3>
	<?php else: ?>

		<?php if ( 'delete-options' === $active_tab ) : ?>

			<?php include( 'tab-delete-options.php' ); ?>

		<?php endif; ?>

		<?php if ( 'connection-test' === $active_tab ) : ?>

			<?php include( 'tab-connection-test.php' ); ?>

		<?php endif; ?>

		<?php if ( 'reset-product-data' === $active_tab ) : ?>

			<?php include( 'tab-reset-product-data.php' ); ?>

		<?php endif; ?>

		<?php if ( 'delete-product-data' === $active_tab ) : ?>

			<?php include( 'tab-delete-product-data.php' ); ?>

		<?php endif; ?>

		<?php if ( 'remove-orphans' === $active_tab ): ?>

			<?php include( 'tab-remove-orphans.php' ); ?>

		<?php endif; ?>

		<?php if ( 'clear-log' === $active_tab ): ?>

			<?php include( 'tab-clear-logs.php' ); ?>

		<?php endif ?>

		<?php if ( 'test-products' === $active_tab ): ?>

			<?php include( 'tab-test-products.php' ); ?>

		<?php endif; ?>

	<?php endif; ?>

</div>

