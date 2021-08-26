<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Billing_Last_Name
 */
class Woo_Order_Billing_Last_Name extends \Thrive\Automator\Items\Data_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Billing last name';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by billing last name';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'billing_last_name';
	}

	public static function get_supported_filters() {
		return array( 'string_ec' );
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
