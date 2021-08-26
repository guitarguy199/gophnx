<?php

/**
 * The public-facing functionality of the plugin.
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
 * AYG_Public class.
 *
 * @since 1.0.0
 */
class AYG_Public {

	/**
	 * Get things started.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode( 'automatic_youtube_gallery', array( $this, 'shortcode_automatic_youtube_gallery' ) );
	}

	/**
	 * Enqueue styles for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function register_styles() {
		wp_register_style( 
			AYG_SLUG . '-public', 
			AYG_URL . 'public/assets/css/public.css', 
			array(), 
			AYG_VERSION, 
			'all' 
		);
	}

	/**
	 * Enqueue scripts for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function register_scripts() {
		wp_register_script( 
			AYG_SLUG . '-public', 
			AYG_URL . 'public/assets/js/public.js', 
			array( 'jquery' ), 
			AYG_VERSION, 
			false 
		);

		$top_offset = apply_filters( 'ayg_gallery_scrolltop_offset', 10 );

		$script_args = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'i18n' => array(
				'show_more' => __( 'Show More', 'automatic-youtube-gallery' ),
				'show_less' => __( 'Show Less', 'automatic-youtube-gallery' )
			),
			'top_offset' => $top_offset,
			'players' => array()
		);

		wp_localize_script( 
			AYG_SLUG . '-public', 
			'ayg_public', 
			$script_args
		);
	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * @since 1.6.1
	 */
	public function enqueue_block_editor_assets() {
		// Styles
		$this->register_styles();
		wp_enqueue_style( AYG_SLUG . '-public' );

		// Scripts
		$this->register_scripts();
		wp_enqueue_script( AYG_SLUG . '-public' );
	}

	/**
	 * Process the shortcode [automatic_youtube_gallery].
	 *
	 * @since  1.0.0
	 * @param  array  $atts An associative array of attributes.
	 * @return string       Shortcode HTML output.
	 */
	public function shortcode_automatic_youtube_gallery( $atts ) {
		return ayg_build_gallery( $atts );
	}

	/**
	 * Load more videos.
	 *
	 * @since 1.0.0
	 */
	public function ajax_callback_load_more_videos() {
		// Security check
		check_ajax_referer( 'ayg_pagination_nonce', 'nonce' );

		// Proceed safe
		$json        = array();
		$attributes  = $_POST;
		$source_type = sanitize_text_field( $attributes['type'] );

		$api_params = array(
			'type'       => $source_type,
			'id'         => sanitize_text_field( $attributes['id'] ),
			'order'      => sanitize_text_field( $attributes['order'] ), // works only when type=search
			'maxResults' => (int) $attributes['per_page'],
			'cache'      => (int) $attributes['cache'],
			'pageToken'  => sanitize_text_field( $attributes['pageToken'] )
		);

		$youtube_api = new AYG_YouTube_API();
		$response = $youtube_api->get_videos( $api_params );

		if ( ! isset( $response->error ) ) {
			if ( isset( $response->page_info ) ) {
				$json = $response->page_info;
			}

			if ( isset( $response->videos ) ) {
				$videos = $response->videos;

				ob_start();
				the_ayg_gallery( $videos, $attributes ); 
				$json['html'] = ob_get_clean();
			}	

			wp_send_json_success( $json );			
		} else {
			$json['message'] =  $response->error_message;
			wp_send_json_error( $json );			
		}		
	}

	/**
	 * [SMUSH] Skip YouTube iframes from lazy loading.
	 *
	 * @since  1.5.0
	 * @param  bool   $skip Should skip? Default: false.
	 * @param  string $src  Iframe url.
	 * @return bool
	 */
	public function smush( $skip, $src ) {
		return false !== strpos( $src, 'youtube' );
	}

}
