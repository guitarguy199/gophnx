<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Date_On_Sale_From
 */
class Woo_Product_Date_On_Sale_From extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Date on sale from';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the date on sale from';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	public static function get_id() {
		return 'product_date_on_sale_from';
	}

	public static function get_supported_filters() {
		return array( 'time_date' );
	}
}
