<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Review_Count
 */
class Woo_Product_Review_Count extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product review count';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the product review count';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_review_count';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
