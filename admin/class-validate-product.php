<?php

namespace TFB4WC;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( '\SkyVerge\WooCommerce\Facebook\ProductSync\ProductValidator' ) ) {
	include_once( ABSPATH . '/wp-content/plugins/facebook-for-woocommerce/includes/fbutils.php' );
	include_once( ABSPATH . '/wp-content/plugins/facebook-for-woocommerce/includes/ProductSync/ProductValidator.php' );
}


use \SkyVerge\WooCommerce\Facebook\ProductSync;
use SkyVerge\WooCommerce\Facebook\ProductSync\ProductExcludedException;
use SkyVerge\WooCommerce\Facebook\ProductSync\ProductInvalidException;
use \SkyVerge\WooCommerce\Facebook\ProductSync\ProductValidator;

class  Validate_Product extends \SkyVerge\WooCommerce\Facebook\ProductSync\ProductValidator {

	public $start_error = '<span style="color:red">';
	public $end_error = '</span>';

	public static $instance;

	public static function init(): ?Logger {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function __construct( $integration, $product ) {
		parent::__construct( $integration, $product );
	}

	public function validate_and_log() {


		$product = $this->product_parent ? $this->product_parent : $this->product;
		echo '<pre>';
		echo 'Validating Product ID: ' . $product->get_id() . '/' . $product->get_sku() . '/' . $product->get_title() . '<br>';
		echo $this->this_validate_product_status() . '<br>';
		echo $this->this_validate_product_stock_status() . '<br>';
		echo $this->this_validate_variation_structure() . '<br>';
		echo $this->this_passes_product_terms_check() . '<br>';
		echo $this->this_passes_product_sync_field_check() . '<br>';
		echo $this->this_validate_sync_enabled_globally() . '<br>';
		echo $this->this_validate_product_visibility() . '<br>';
		echo $this->this_validate_product_terms() . '<br>';
		echo $this->this_validate_product_sync_field();
		echo $this->this_validate_product_price() . '<br>';
		echo $this->this_validate_product_description() . '<br>';
		echo $this->this_validate_product_title() . '<br>';

	}

	public function this_validate_product_status(): string {

		$product = $this->product_parent ? $this->product_parent : $this->product;

		if ( 'publish' !== $product->get_status() ) {
			return $this->start_error . 'Product is not published.' . $this->end_error;
		}

		return 'Status: published';
	}

	public function this_validate_product_stock_status() {

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $this->product->is_in_stock() ) {
			return $this->start_error . 'Product must be in stock.' . $this->end_error;
		}

		return 'Stock: has stock.';
	}

	public function this_passes_product_terms_check(): string {
		try {
			$this->validate_product_terms();
		} catch ( ProductExcludedException $e ) {
			return $this->start_error . 'Product is in an excluded term.' . $this->end_error;
		} catch ( ProductInvalidException $e ) {
			return $this->start_error . 'Product terms check failed. Reason unknown.' . $this->end_error;
		}

		return 'Product terms check passed';
	}

	public function this_passes_product_sync_field_check(): string {
		try {
			$this->validate_product_sync_field();
		} catch ( ProductExcludedException $e ) {
			return $this->start_error . 'Product failed synch field check.' . $this->end_error;
		} catch ( ProductInvalidException $e ) {
			return $this->start_error . 'Product synch field check error.' . $this->end_error;
		}

		return 'Product passed synch field check';
	}

	protected function this_validate_sync_enabled_globally() {
		if ( ! $this->integration->is_product_sync_enabled() ) {
			return $this->start_error . 'Product sync is globally disabled.' . $this->end_error;
		}
		return 'Product synch is globally enabled';
	}

	protected function this_validate_product_visibility() {
		$product = $this->product_parent ? $this->product_parent : $this->product;

		if ( 'visible' !== $product->get_catalog_visibility() ) {
			return $this->start_error . 'Product is hidden from catalog and search.' . $this->end_error;
		}
		return 'Product is not hidden from catalogue and search';
	}

	protected function this_validate_product_terms() {
		$product = $this->product_parent ? $this->product_parent : $this->product;

		$excluded_categories = $this->integration->get_excluded_product_category_ids();
		if ( $excluded_categories ) {
			if ( ! empty( array_intersect( $product->get_category_ids(), $excluded_categories ) ) ) {
				return $this->start_error . 'Product excluded because of categories.' . $this->end_error;
			}
		}

		$excluded_tags = $this->integration->get_excluded_product_tag_ids();
		if ( $excluded_tags ) {
			if ( ! empty( array_intersect( $product->get_tag_ids(), $excluded_tags ) ) ) {
				return $this->start_error . 'Product excluded because of tags.' . $this->end_error;
			}
		}

		return 'Tag and category check passed';
	}

	protected function this_validate_product_sync_field() {
		$invalid_exception = $this->start_error . 'Sync disabled in product field.' . $this->end_error;
		$output = '';

		if ( $this->product->is_type( 'variable' ) ) {
			foreach ( $this->product->get_children() as $child_id ) {
				$child_product = wc_get_product( $child_id );
				if ( $child_product && 'no' !== $child_product->get_meta( self::SYNC_ENABLED_META_KEY ) ) {
					$output .= 'Variation is synch enabled.<br>';
				}else{
					$output .= $this->start_error . 'Variation is synch disabled' . $this->end_error . '<br>';
				}
			}

			// Variable product has no variations with sync enabled so it shouldn't be synced.
			return $output;
		} else {
			if ( 'no' === $this->product->get_meta( self::SYNC_ENABLED_META_KEY ) ) {
				return $invalid_exception . '<br>';
			}else{
				return 'Product is synch enabled.<br>';
			}
		}
	}

	protected function this_validate_product_price() {
		$primary_product = $this->product_parent ? $this->product_parent : $this->product;

		// Variable and simple products are allowed to have no price.
		if ( in_array( $primary_product->get_type(), array( 'simple', 'variable' ), true ) ) {
			return 'Variable and simple products are allowed to have no price.';
		}

		if ( ! Products::get_product_price( $this->product ) ) {
			return $this->start_error . 'If product is not simple, variable or variation it must have a price.' . $this->end_error;
		}
	}

	protected function this_validate_product_description() {
		/*
		 * First step is to select the description that we want to evaluate.
		 * Main description is the one provided for the product in the Facebook.
		 * If it is blank, product description will be used.
		 * If product description is blank, shortname will be used.
		 */
		$description = $this->facebook_product->get_fb_description();

		/*
		 * Requirements:
		 * - No all caps descriptions.
		 * - Max length 5000.
		 * - Min length 30 ( tested and not required, will not enforce until this will become a hard requirement )
		 */
		if ( \WC_Facebookcommerce_Utils::is_all_caps( $description ) ) {
			return $this-> start_error . 'Product description is all capital letters. Please change the description to sentence case.' . $this->end_error;
		}
		if ( strlen( $description ) > self::MAX_DESCRIPTION_LENGTH ) {
			return $this->start_error . 'Product description is too long. Maximum allowed length is 5000 characters.' . $this->end_error;
		}

		return 'Product description passes check';
	}

	protected function this_validate_product_title() {
		$title = $this->product->get_title();

		/*
		 * Requirements:
		 * - No all caps title.
		 * - Max length 150.
		 */
		if ( \WC_Facebookcommerce_Utils::is_all_caps( $title ) ) {
			return $this->start_error . 'Product title is all capital letters. Please change the title to sentence case.'. $this->end_error;
		}
		if ( strlen( $title ) > self::MAX_TITLE_LENGTH ) {
			return $this->start_error . 'Product title is too long. Maximum allowed length is 150 characters.' . $this->end_error;
		}

		return 'Title check passed';
	}

	public function this_validate_variation_structure() {
		// Check if we are dealing with a variation.
		if ( ! $this->product->is_type( 'variation' ) ) {
			return 'This is not a variation, so we will not check the structure.';
		}
		$attributes = $this->product->get_attributes();

		$used_attributes_count = count(
			array_filter(
				$attributes
			)
		);

		// No more than MAX_NUMBER_OF_ATTRIBUTES_IN_VARIATION ar allowed to be used.
		if ( $used_attributes_count > self::MAX_NUMBER_OF_ATTRIBUTES_IN_VARIATION ) {
			return $this->start_error . 'Too many attributes selected for product. Use 4 or less.' . $this->end_error;
		}

		return 'Variation attributes: Uses less than ' . self::MAX_NUMBER_OF_ATTRIBUTES_IN_VARIATION . ' attributes';
	}

}