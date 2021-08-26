<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Product_Backorders
 */
class Woo_Product_Backorders extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Product backorders';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by the backorders';
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
		return array(
			'yes'    => array(
				'id'    => 'yes',
				'label' => 'Yes',
			),
			'no'     => array(
				'id'    => 'no',
				'label' => 'No',
			),
			'notify' => array(
				'id'    => 'notify',
				'label' => 'Notify',
			),
		);
	}

	public static function get_id() {
		return 'product_backorders';
	}

	public static function get_supported_filters() {
		return array( 'dropdown' );
	}

	public static function is_ajax_field() {
		return true;
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
