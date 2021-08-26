<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Average_Rating
 */
class Woo_Product_Average_Rating extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product average rating';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the product average rating';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_average_rating';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
