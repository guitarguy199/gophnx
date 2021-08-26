<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Sold_Individually
 */
class Woo_Product_Sold_Individually extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Sold individually';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by whether the product is sold individually or not';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_sold_individually';
	}

	public static function get_supported_filters() {
		return array( 'boolean' );
	}
}
