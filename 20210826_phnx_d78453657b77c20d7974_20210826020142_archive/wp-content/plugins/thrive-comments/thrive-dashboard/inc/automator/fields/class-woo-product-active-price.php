<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Active_Price
 */
class Woo_Product_Active_Price extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product active price';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the product active price';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_active_price';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
