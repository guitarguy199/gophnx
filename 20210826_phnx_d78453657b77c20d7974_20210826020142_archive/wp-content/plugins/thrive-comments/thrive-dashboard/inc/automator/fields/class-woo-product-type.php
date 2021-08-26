<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Type
 */
class Woo_Product_Type extends \Thrive\Automator\Items\Data_Field {

	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product type';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by product type';
	}

	/**
	 * Field input placeholder
	 */
	public static function get_placeholder() {
		return '';
	}

	/**
	 * For multiple option inputs, name of the callback function called through ajax to get the options
	 */
	public static function get_options_callback() {
		$types = array();

		foreach ( \wc_get_product_types() as $key => $type ) {
			$types[ $key ] = array(
				'label' => $type,
				'id'    => $key,
			);
		}

		return $types;
	}

	public static function get_id() {
		return 'product_type';
	}

	public static function get_supported_filters() {
		return array( 'checkbox' );
	}

	public static function is_ajax_field() {
		return true;
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
