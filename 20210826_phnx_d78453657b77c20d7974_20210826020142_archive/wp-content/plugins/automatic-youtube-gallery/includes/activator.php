<?php

/**
 * Fired during plugin activation
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * AYG_Activator class.
 *
 * @since 1.0.0
 */
class AYG_Activator {

	/**
	 * Called when the plugin is activated.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		// Insert the general settings
		if ( false == get_option( 'ayg_general_settings' ) ) {		
			$defaults = array(
				'api_key' => ''
			);
				
        	add_option( 'ayg_general_settings', $defaults );			
		}

		// Insert the gallery settings
		if ( false == get_option( 'ayg_gallery_settings' ) ) {		
			$defaults = array(
				'theme'                => 'classic',
				'columns'              => 3,
				'per_page'             => 12,
				'thumb_ratio'          => 56.25,
				'thumb_title'          => 1,
				'thumb_title_length'   => 0,
				'thumb_excerpt'        => 1,
				'thumb_excerpt_length' => 75,
				'pagination'           => 1,
				'pagination_type'      => 'load_more'
			);
				
        	add_option( 'ayg_gallery_settings', $defaults );			
		}

		// Insert the player settings
		if ( false == get_option( 'ayg_player_settings' ) ) {		
			$defaults = array(
				'player_ratio'       => 56.25,
				'player_title'       => 1,
				'player_description' => 1,
				'autoplay'           => 0,
				'autoadvance'        => 0,
				'loop'               => 0,
				'controls'           => 1,
				'modestbranding'     => 1,
				'cc_load_policy'     => 0,
				'iv_load_policy'     => 0,
				'hl'                 => '',
				'cc_lang_pref'       => ''
			);
				
        	add_option( 'ayg_player_settings', $defaults );			
		}
		
		// Insert the plugin version
		add_option( 'ayg_version', AYG_VERSION );
	}

}
