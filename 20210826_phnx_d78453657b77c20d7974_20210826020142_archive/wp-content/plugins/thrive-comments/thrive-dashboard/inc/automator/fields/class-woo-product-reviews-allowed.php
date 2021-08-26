<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Reviews_Allowed
 */
class Woo_Product_Reviews_Allowed extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Reviews allowed';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by whether reviews are allowed';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_reviews_allowed';
	}

	public static function get_supported_filters() {
		return array( 'boolean' );
	}
}
