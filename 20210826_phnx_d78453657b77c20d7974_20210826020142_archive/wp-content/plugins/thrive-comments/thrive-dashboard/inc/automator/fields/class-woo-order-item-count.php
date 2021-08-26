<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Order_Item_Count
 */
class Woo_Order_Item_Count extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Item count';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the item count';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'item_count';
	}

	public static function get_supported_filters() {
		return array( 'number_comparison' );
	}

}
