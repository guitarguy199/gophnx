<?php

/**
 * The admin-specific functionality of the plugin.
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
 * AYG_Admin class.
 *
 * @since 1.0.0
 */
class AYG_Admin {

	/**
	 * Enqueue styles for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style( 
			AYG_SLUG . '-admin', 
			AYG_URL . 'admin/assets/css/admin.css', 
			array(), 
			AYG_VERSION, 
			'all' 
		);
	}

	/**
	 * Enqueue scripts for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script( 
			AYG_SLUG . '-admin', 
			AYG_URL . 'admin/assets/js/admin.js', 
			array( 'jquery' ), 
			AYG_VERSION, 
			false 
		);

		wp_localize_script( 
			AYG_SLUG . '-admin', 
			'ayg_admin', 
			array(
				'i18n' => array(					
					'invalid_api_key' => __( 'Invalid API Key', 'automatic-youtube-gallery' ),
					'cleared'         => __( 'Cleared', 'automatic-youtube-gallery' )
				),
				'ajax_nonce' => wp_create_nonce( 'ayg_admin_ajax_nonce' )	
			)
		);		
	}	

	/**
	 * Add dashboard page link on the plugins menu.
	 *
	 * @since  1.0.0
	 * @param  array  $links An array of plugin action links.
	 * @return string $links Array of filtered plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$dashboard_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=automatic-youtube-gallery' ), __( 'Build Gallery', 'automatic-youtube-gallery' ) );
        array_unshift( $links, $dashboard_link );
		
    	return $links;
	}

	/**
	 * Add "Dashboard" menu.
	 *
	 * @since 1.3.0
	 */
	public function admin_menu() {	
		add_menu_page( 
			__( 'Automatic YouTube Gallery', 'automatic-youtube-gallery' ), 
			__( 'YouTube Gallery', 'automatic-youtube-gallery' ),
			'manage_options', 
			'automatic-youtube-gallery', 
			array( $this, 'display_dashboard_content' ),
			'dashicons-video-alt3', 
			10 
		);

		add_submenu_page(
			'automatic-youtube-gallery',
			__( 'Dashboard', 'automatic-youtube-gallery' ),
			__( 'Dashboard', 'automatic-youtube-gallery' ),
			'manage_options',
			'automatic-youtube-gallery',
			array( $this, 'display_dashboard_content' )
		);
	}

	/**
	 * Display dashboard content.
	 *
	 * @since 1.3.0
	 */
	public function display_dashboard_content() {
		$general_settings = get_option( 'ayg_general_settings', array() );

		$tabs = array(
			'dashboard' => __( 'Build Gallery', 'automatic-youtube-gallery' )
		);
		
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'dashboard';

		require_once AYG_DIR . 'admin/templates/dashboard.php';				
	}

	/**
	 * Save API Key.
	 *
	 * @since 1.3.0
	 */
	public function ajax_callback_save_api_key() {	
		check_ajax_referer( 'ayg_admin_ajax_nonce', 'security' );
		
		$general_settings = get_option( 'ayg_general_settings', array() );
		$general_settings['api_key'] = sanitize_text_field( $_POST['api_key'] );

		update_option( 'ayg_general_settings', $general_settings );

		wp_die();	
	}

}
