<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Date_Modified
 */
class Woo_Order_Date_Modified extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Date modified';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by date modified';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'date_modified';
	}

	public static function get_supported_filters() {
		return array( 'time_date' );
	}
}
