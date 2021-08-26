<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Prices_Include_Tax
 */
class Woo_Order_Prices_Include_Tax extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Price includes tax';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by whether the price includes tax or not';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'prices_include_tax';
	}

	public static function get_supported_filters() {
		return array( 'boolean' );
	}
}
