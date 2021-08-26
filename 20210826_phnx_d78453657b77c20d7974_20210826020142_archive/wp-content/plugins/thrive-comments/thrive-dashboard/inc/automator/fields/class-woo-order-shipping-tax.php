<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Shipping_Tax
 */
class Woo_Order_Shipping_Tax extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Shipping tax';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the shipping tax';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'shipping_tax';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
