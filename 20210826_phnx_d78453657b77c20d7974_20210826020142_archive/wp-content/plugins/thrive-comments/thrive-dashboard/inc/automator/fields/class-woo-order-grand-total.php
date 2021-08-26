<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Grand_Total
 */
class Woo_Order_Grand_Total extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Grand total';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the grand total';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'grand_total';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
