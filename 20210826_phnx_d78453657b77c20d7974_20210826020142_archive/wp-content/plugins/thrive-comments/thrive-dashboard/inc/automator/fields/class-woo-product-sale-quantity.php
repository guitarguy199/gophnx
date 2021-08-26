<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Sale_Quantity
 */
class Woo_Product_Sale_Quantity extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Items available for sale';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the number of items available for sale';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_sale_quantity';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
