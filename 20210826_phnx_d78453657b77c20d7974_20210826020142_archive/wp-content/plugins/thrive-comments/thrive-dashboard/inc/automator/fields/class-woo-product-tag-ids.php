<?php

namespace TVE\Dashboard\Automator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Woo_Product_Tag_Ids
 */
class Woo_Product_Tag_Ids extends \Thrive\Automator\Items\Data_Field {
	/**
	 * Field name
	 */
	public static function get_name() {
		return 'Tag ID';
	}

	/**
	 * Field description
	 */
	public static function get_description() {
		return 'Filter by specific product tag';
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
		$tags = array();

		$cat_args = array(
			'orderby' => 'name',
			'order'   => 'asc',
		);

		$product_tags = get_terms( 'product_tag', $cat_args );

		foreach ( $product_tags as $tag ) {
			if ( ! empty( $tag ) ) {
				$tags[ $tag->term_id ] = array(
					'label' => $tag->name,
					'id'    => $tag->term_id,
				);
			}

		}

		return $tags;
	}

	public static function get_id() {
		return 'product_tag_ids';
	}

	public static function get_supported_filters() {
		return array( 'autocomplete' );
	}

	public static function is_ajax_field() {
		return true;
	}

	public static function get_validators() {
		return array( 'required' );
	}
}
