<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Date_Created
 */
class Woo_Order_Date_Created extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Date created';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by date created';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'date_created';
	}

	public static function get_supported_filters() {
		return array( 'time_date' );
	}
}
