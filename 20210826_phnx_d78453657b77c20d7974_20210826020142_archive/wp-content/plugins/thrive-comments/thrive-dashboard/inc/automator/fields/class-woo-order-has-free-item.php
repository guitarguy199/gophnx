<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Has_Free_Item
 */
class Woo_Order_Has_Free_Item extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Has free item';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by whether the order contains a free item or not';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'has_free_item';
	}

	public static function get_supported_filters() {
		return array( 'boolean' );
	}
}
