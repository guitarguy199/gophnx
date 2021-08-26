<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Length
 */
class Woo_Product_Length extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product length';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the product length';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_length';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
