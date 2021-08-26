<?php

namespace TVA\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Manage_Stock
 */
class Woo_Product_Manage_Stock extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Stock managed';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by if product manage stock';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_manage_stock';
	}

	public static function get_supported_filters() {
		return array( 'boolean' );
	}
}
