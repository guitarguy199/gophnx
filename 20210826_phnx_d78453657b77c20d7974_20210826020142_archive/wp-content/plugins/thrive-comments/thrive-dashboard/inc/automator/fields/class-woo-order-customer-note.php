<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Customer_Note
 */
class Woo_Order_Customer_Note extends \Thrive\Automator\Items\Data_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Customer note';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by customer note';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'customer_note';
	}

	public static function get_supported_filters() {
		return array( 'string_ec' );
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
