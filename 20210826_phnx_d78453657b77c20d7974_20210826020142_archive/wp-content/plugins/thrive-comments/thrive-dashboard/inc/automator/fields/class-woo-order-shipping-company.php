<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Shipping_Company
 */
class Woo_Order_Shipping_Company extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Shipping company';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by shipping company';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'shipping_company';
	}

	public static function get_supported_filters() {
		return array( 'string_ec' );
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
