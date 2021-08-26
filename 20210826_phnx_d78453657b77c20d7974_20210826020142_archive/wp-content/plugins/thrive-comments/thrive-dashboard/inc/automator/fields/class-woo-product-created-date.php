<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Created_Date
 */
class Woo_Product_Created_Date extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product created date';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by product created date';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_created_date';
	}

	public static function get_supported_filters() {
		return array( 'time_date' );
	}
}
