<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Version
 */
class Woo_Order_Version extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Order version';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Target an individual order version';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'order_version';
	}

	public static function get_supported_filters() {
		return array( 'string_ec' );
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
