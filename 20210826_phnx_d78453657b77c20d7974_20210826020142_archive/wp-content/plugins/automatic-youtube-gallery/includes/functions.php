<?php

/**
 * Helper Functions.
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
 * Build gallery HTML output.
 *
 * @since  1.0.0
 * @param  array $atts An associative array of attributes.
 * @return mixed
 */
function ayg_build_gallery( $atts ) {
	// Vars
	$fields   = ayg_get_editor_fields();
	$defaults = array();

	foreach ( $fields as $key => $value ) {
		foreach ( $value['fields'] as $field ) {
			$defaults[ $field['name'] ] = $field['value'];
		}
	}

	$attributes = shortcode_atts( $defaults, $atts );

	$attributes['columns'] = min( 12, (int) $attributes['columns'] );
	if ( empty( $attributes['columns'] ) ) {
		$attributes['columns'] = 3;
	}

	$attributes['limit'] = min( 500, (int) $attributes['limit'] );
	if ( empty( $attributes['limit'] ) ) {
		$attributes['limit'] = 500;
	}
	
	$attributes['per_page'] = min( 50, (int) $attributes['per_page'] );
	if ( empty( $attributes['per_page'] ) ) {
		$attributes['per_page'] = 50;
	}

	// Get Videos
	$source_type = sanitize_text_field( $attributes['type'] );

	if ( 'video' == $source_type ) {
		$attributes['theme'] = 'classic';
	}

	$api_params = array(
		'type'       => $source_type,
		'id'         => sanitize_text_field( $attributes[ $source_type ] ),
		'order'      => sanitize_text_field( $attributes['order'] ), // works only when type=search
		'maxResults' => $attributes['per_page'],
		'cache'      => (int) $attributes['cache']
	);

	$youtube_api = new AYG_YouTube_API();
	$response = $youtube_api->get_videos( $api_params );

	// Process output
	if ( ! isset( $response->error ) ) {
		// Enqueue dependencies
		wp_enqueue_style( AYG_SLUG . '-public' );
		wp_enqueue_script( AYG_SLUG . '-public' );
		
		// Gallery
		$videos = array();
		
		if ( isset( $response->videos ) ) {
			$videos = $response->videos;
		}

		// Pagination
		if ( isset( $response->page_info ) ) {
			$attributes = array_merge( $attributes, $response->page_info, $api_params );
		}

		// Output
		ob_start();
		include ayg_get_template( AYG_DIR . 'public/templates/theme-classic.php', $attributes['theme'] );
		return ob_get_clean();
	} else {
		return '<p class="ayg-error">' . $response->error_message . '</p>';
	}
}

/**
 * Get editor fields.
 *
 * @since  1.0.0
 * @return array Array of fields.
 */
function ayg_get_editor_fields() {	
	$fields = array(
		'source' => array(
			'label'  => __( 'General', 'automatic-youtube-gallery' ),
			'fields' => array(
				array(
					'name'              => 'type',
					'label'             => __( 'Source Type', 'automatic-youtube-gallery' ),
					'description'       => '',
					'type'              => 'select',
					'options'           => array(
						'playlist' => __( 'Playlist', 'automatic-youtube-gallery' ),
						'channel'  => __( 'Channel', 'automatic-youtube-gallery' ),
						'username' => __( 'Username', 'automatic-youtube-gallery' ),
						'search'   => __( 'Search Terms', 'automatic-youtube-gallery' ),
						'videos'   => __( 'Custom Videos List', 'automatic-youtube-gallery' ),
						'video'    => __( 'Single Video', 'automatic-youtube-gallery' )
					),
					'value'             => 'playlist',
					'sanitize_callback' => 'sanitize_key'
				),
				array(
					'name'              => 'playlist',
					'label'             => __( 'YouTube Playlist ID (or) URL', 'automatic-youtube-gallery' ),					
					'description'       => sprintf( '%s: https://www.youtube.com/playlist?list=XXXXXXXXXX', __( 'Example', 'automatic-youtube-gallery' ) ),
					'type'              => 'url',
					'value'             => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'channel',
					'label'             => __( 'YouTube Channel ID (or) URL', 'automatic-youtube-gallery' ),
					'description'       => sprintf( '%s: https://www.youtube.com/channel/XXXXXXXXXX', __( 'Example', 'automatic-youtube-gallery' ) ),
					'type'              => 'url',
					'value'             => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'username',
					'label'             => __( 'YouTube Account Username', 'automatic-youtube-gallery' ),
					'description'       => sprintf( '%s: SanRosh', __( 'Example', 'automatic-youtube-gallery' ) ),
					'type'              => 'text',
					'value'             => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'search',
					'label'             => __( 'Search Terms', 'automatic-youtube-gallery' ),
					'description'       => sprintf( '%s: Cartoon (space:AND , -:NOT , |:OR)', __( 'Example', 'automatic-youtube-gallery' ) ),
					'type'              => 'text',
					'value'             => '',
					'sanitize_callback' => 'sanitize_text_field'
				),				
				array(
					'name'              => 'videos',
					'label'             => __( 'YouTube Video IDs (or) URLs', 'automatic-youtube-gallery' ),
					'description'       => sprintf( '%s: https://www.youtube.com/watch?v=XXXXXXXXXX', __( 'Example', 'automatic-youtube-gallery' ) ),
					'type'              => 'textarea',					
					'placeholder'       => __( 'Enter one video per line', 'automatic-youtube-gallery' ),
					'value'             => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'video',
					'label'             => __( 'YouTube Video ID (or) URL', 'automatic-youtube-gallery' ),
					'description'       => sprintf( '%s: https://www.youtube.com/watch?v=XXXXXXXXXX', __( 'Example', 'automatic-youtube-gallery' ) ),
					'type'              => 'url',
					'value'             => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'order',
					'label'             => __( 'Order Videos by', 'automatic-youtube-gallery' ),
					'description'       => '',
					'type'              => 'select',
					'options' => array(
						'date'      => __( 'Date', 'automatic-youtube-gallery' ),
						'rating'    => __( 'Rating', 'automatic-youtube-gallery' ),
						'relevance' => __( 'Relevance', 'automatic-youtube-gallery' ),
						'title'     => __( 'Title', 'automatic-youtube-gallery' ),
						'viewCount' => __( 'View Count', 'automatic-youtube-gallery' )
					),
					'value'             => 'relevance',
					'sanitize_callback' => 'sanitize_key'
				),
				array(
					'name'              => 'limit',
					'label'             => __( 'Number of Videos', 'automatic-youtube-gallery' ),					
					'description'       => __( 'Specifies the maximum number of videos that will appear in this gallery. Set to 0 for the maximum amount (500).', 'automatic-youtube-gallery' ),
					'type'              => 'number',					
					'min'               => 0,
					'max'               => 500,
					'value'             => 0,
					'sanitize_callback' => 'intval'
				),
				array(
					'name'              => 'cache',
					'label'             => __( 'Refresh the data every (Cache time)', 'automatic-youtube-gallery' ),
					'description'       => __( 'IMPORTANT! Specifies how frequently the plugin should check your YouTube source for any new update. We recommend keeping this value as "Page load" when you test the gallery and strongly suggest to change this value as "Day" or to a greater value before pushing the gallery live.', 'automatic-youtube-gallery' ),
					'type'              => 'select',
					'options' => array(
						'0'       => __( 'Page load', 'automatic-youtube-gallery' ),
						'900'     => __( '15 minutes', 'automatic-youtube-gallery' ),
						'1800'    => __( '30 minutes', 'automatic-youtube-gallery' ),
						'3600'    => __( 'Hour', 'automatic-youtube-gallery' ),
						'86400'   => __( 'Day', 'automatic-youtube-gallery' ),
						'604800'  => __( 'Week', 'automatic-youtube-gallery' ),
						'2419200' => __( 'Month', 'automatic-youtube-gallery' )
					),
					'value'             => 0,
					'sanitize_callback' => 'intval'
				)
			)			
		),
		'gallery' => array(
			'label'  => __( 'Gallery (optional)', 'automatic-youtube-gallery' ),
			'fields' => ayg_get_gallery_settings_fields()
		),
		'player' => array(
			'label'  => __( 'Player (optional)', 'automatic-youtube-gallery' ),
			'fields' => ayg_get_player_settings_fields()
		)
	);

	return apply_filters( 'ayg_editor_fields', $fields );
}

/**
 * Get gallery thumbnail video excerpt (short description).
 *
 * @since  1.0.0
 * @param  stdClass $video      YouTube video object.
 * @param  array    $attributes Array of user attributes.
 * @return string               Video excerpt.
 */
function ayg_get_gallery_thumb_excerpt( $video, $attributes ) {
	$char_length = (int) $attributes['thumb_excerpt_length'];
	$char_length++;

	$content = '';
	if ( ! empty( $attributes['thumb_excerpt'] ) ) {
		$content = ( $char_length > 1 ) ? wp_strip_all_tags( $video->description, true ) : nl2br( $video->description );
	}

	$excerpt = '';

	if ( $char_length > 1 && mb_strlen( $content ) > $char_length ) {
		$subex = mb_substr( $content, 0, $char_length - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$excerpt = mb_substr( $subex, 0, $excut );
		} else {
			$excerpt = $subex;
		}
		$excerpt .= '[...]';
	} else {
		$excerpt = $content;
	}

	return apply_filters( 'ayg_gallery_thumb_excerpt', $excerpt, $video, $attributes );	
}

/**
 * Get gallery thumbnail image.
 *
 * @since  1.0.0
 * @param  stdClass $video      YouTube video object.
 * @param  array    $attributes Array of user attributes.
 * @return string               Video thumbnail image.
 */
function ayg_get_gallery_thumb_image( $video, $attributes ) {
	$image = '';

	if ( isset( $video->thumbnails->default ) ) {
		$image = $video->thumbnails->default->url;
	}

	// 4:3 ( default - 120x90, high - 480x360, standard - 640x480 )
	if ( 75 == (int) $attributes['thumb_ratio'] ) {
		if ( isset( $video->thumbnails->high ) ) {
			$image = $video->thumbnails->high->url;
		}
	}

	// 16:9 ( medium - 320x180, maxres - 1280x720 )
	if ( 56.25 == (float) $attributes['thumb_ratio'] ) {
		if ( isset( $video->thumbnails->medium ) ) {
			$image = $video->thumbnails->medium->url;
		}
	}

	return apply_filters( 'ayg_gallery_thumb_image', $image, $video, $attributes );	
}

/**
 * Get gallery thumbnail video title.
 *
 * @since  1.0.0
 * @param  stdClass $video      YouTube video object.
 * @param  array    $attributes Array of user attributes.
 * @return string               Video title.
 */
function ayg_get_gallery_thumb_title( $video, $attributes ) {
	$text = ! empty( $attributes['thumb_title'] ) ? $video->title : '';

	$char_length = (int) $attributes['thumb_title_length'];
	$char_length++;

	$title = '';

	if ( $char_length > 1 && mb_strlen( $text ) > $char_length ) {
		$subex = mb_substr( $text, 0, $char_length - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			$title = mb_substr( $subex, 0, $excut );
		} else {
			$title = $subex;
		}
		$title .= '[...]';		
	} else {
		$title = $text;
	}

	return apply_filters( 'ayg_gallery_thumb_title', $title, $video, $attributes );	
}

/**
 * Get gallery settings fields.
 *
 * @since  1.0.0
 * @return array $fields Array of fields.
 */
function ayg_get_gallery_settings_fields() {
	$gallery_settings = get_option( 'ayg_gallery_settings' );

	$fields = array(
		array(
			'name'              => 'theme',
			'label'             => __( 'Select Theme', 'automatic-youtube-gallery' ),
			'description'       => ( ayg_fs()->is_not_paying() ? sprintf( __( '<a href="%s">Upgrade Pro</a> for more themes (Popup, Slider, Playlister).', 'automatic-youtube-gallery' ), esc_url( ayg_fs()->get_upgrade_url() ) ) : '' ),
			'type'              => 'select',
			'options'           => array( 
				'classic' => __( 'Classic', 'automatic-youtube-gallery' )
			),
			'value'             => $gallery_settings['theme'],
			'sanitize_callback' => 'sanitize_key'
		),		
		array(
			'name'              => 'columns',
			'label'             => __( 'Columns', 'automatic-youtube-gallery' ),
			'description'       => __( 'Enter the number of columns you like to have in the gallery. Maximum of 12.', 'automatic-youtube-gallery' ),			
			'type'              => 'number',
			'min'               => 0,
			'max'               => 12,
			'value'             => $gallery_settings['columns'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'per_page',
			'label'             => __( 'Videos per page', 'automatic-youtube-gallery' ),
			'description'       => __( 'Enter the number of videos to show per page. Maximum of 50.', 'automatic-youtube-gallery' ),			
			'type'              => 'number',
			'min'               => 0,
			'max'               => 50,
			'value'             => $gallery_settings['per_page'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'thumb_ratio',
			'label'             => __( 'Thumbnail Ratio', 'automatic-youtube-gallery' ),			
			'type'              => 'radio',
			'options'           => array(
				'56.25' => '16:9',
				'75'    => '4:3'				
			),
			'value'             => $gallery_settings['thumb_ratio'],
			'sanitize_callback' => 'floatval'
		),
		array(
			'name'              => 'thumb_title',
			'label'             => __( 'Show Video Title', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Check this option to show the video title in each gallery item.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $gallery_settings['thumb_title'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'thumb_title_length',
			'label'             => __( 'Video Title Length', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Enter the number of characters you like to show in the title. Set 0 to show the whole title.', 'automatic-youtube-gallery' ),
			'type'              => 'number',
			'min'               => 0,
			'max'               => 500,
			'value'             => $gallery_settings['thumb_title_length'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'thumb_excerpt',
			'label'             => __( 'Show Video Excerpt (Short Description)', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Check this option to show the short description of a video in each gallery item.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $gallery_settings['thumb_excerpt'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'thumb_excerpt_length',
			'label'             => __( 'Video Excerpt Length', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Enter the number of characters you like to have in the video excerpt. Set 0 to show the whole description.', 'automatic-youtube-gallery' ),
			'type'              => 'number',
			'min'               => 0,
			'max'               => 500,
			'value'             => $gallery_settings['thumb_excerpt_length'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'pagination',
			'label'             => __( 'Pagination', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Check this option to show the pagination.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $gallery_settings['pagination'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'pagination_type',
			'label'             => __( 'Pagination Type', 'automatic-youtube-gallery' ),			
			'type'              => 'select',
			'options'           => array(
				'pager'     => __( 'Pager', 'automatic-youtube-gallery' ),
				'load_more' => __( 'Load More', 'automatic-youtube-gallery' )			
			),
			'value'             => $gallery_settings['pagination_type'],
			'sanitize_callback' => 'sanitize_key'
		)
	);

	return apply_filters( 'ayg_gallery_settings_fields', $fields );
}

/**
 * Get video description to show on top of the player.
 *
 * @since  1.0.0
 * @param  stdClass $video       YouTube video object.
 * @param  array    $attributes  Array of user attributes.
 * @param  int      $words_count Number of words to show by default.
 * @return string                Video description.
 */
function ayg_get_player_description( $video, $attributes, $words_count = 30 ) {
	$description = ! empty( $attributes['player_description'] ) ? $video->description : '';

	$words_array = explode( ' ', strip_tags( $description ) );
	
	if ( count( $words_array ) > $words_count ) {
		$words_array[ $words_count ] = '<span class="ayg-player-description-dots">...</span></span><span class="ayg-player-description-more">' . $words_array[ $words_count ];

		$description  = '<span class="ayg-player-description-less">' . implode( ' ', $words_array ) . '</span>';
		$description .= '<button type="button" class="ayg-player-description-toggle-btn">' . __( 'Show More', 'automatic-youtube-gallery' ) . '</button>';
	}

	return apply_filters( 'ayg_player_description', nl2br( $description ), $video, $attributes, $words_count );	
}

/**
 * Get YouTube player embed URL.
 *
 * @since  1.0.0
 * @param  stdClass $video      YouTube video object.
 * @param  array    $attributes Array of user attributes.
 * @return string               YouTube video embed URL.
 */
function ayg_get_player_embed_url( $video, $attributes ) {
	$url = "https://www.youtube-nocookie.com/embed/{$video->id}";

    if ( ! empty( $attributes['autoplay'] ) ) { // autoplay
        $url = add_query_arg( 'autoplay', 1, $url );
    }

    if ( ! empty( $attributes['loop'] ) ) { // loop
        $url = add_query_arg( 'loop', 1, $url );
    }

    if ( empty( $attributes['controls'] ) ) { // controls
        $url = add_query_arg( 'controls', 0, $url );
    }    

    if ( ! empty( $attributes['modestbranding'] ) ) { // modestbranding
        $url = add_query_arg( 'modestbranding', 1, $url );
    }

    if ( ! empty( $attributes['cc_load_policy'] ) ) { // cc_load_policy
        $url = add_query_arg( 'cc_load_policy', 1, $url );
    }

    if ( empty( $attributes['iv_load_policy'] ) ) { // iv_load_policy
        $url = add_query_arg( 'iv_load_policy', 3, $url );
    }

    if ( ! empty( $attributes['hl'] ) ) { // hl
        $url = add_query_arg( 'hl', $attributes['hl'], $url );
    }

    if ( ! empty( $attributes['cc_lang_pref'] ) ) { // cc_lang_pref
        $url = add_query_arg( 'cc_lang_pref', $attributes['cc_lang_pref'], $url );
    }

    $url = add_query_arg( 'rel', 0, $url ); // rel
    $url = add_query_arg( 'playsinline', 1, $url ); // playsinline
	$url = add_query_arg( 'enablejsapi', 1, $url ); // enablejsapi
	
	return apply_filters( 'ayg_player_embed_url', $url, $video, $attributes );
}

/**
 * Get player ratio.
 *
 * @since  1.0.0
 * @param  array  $attributes Array of user attributes.
 * @return string             Player ratio.
 */
function ayg_get_player_ratio( $attributes ) {
	$ratio = ! empty( $attributes['player_ratio'] ) ? $attributes['player_ratio'] . '%' : '56.25%';
	return apply_filters( 'ayg_player_ratio', $ratio, $attributes );	
}

/**
 * Get player settings fields.
 *
 * @since  1.0.0
 * @return array $fields Array of fields.
 */
function ayg_get_player_settings_fields() {
	$player_settings = get_option( 'ayg_player_settings' );

	$fields = array(
		array(
			'name'              => 'player_ratio',
			'label'             => __( 'Player Ratio', 'automatic-youtube-gallery' ),			
			'type'              => 'radio',
			'options'           => array(
				'56.25' => '16:9',
				'75'    => '4:3'				
			),
			'value'             => $player_settings['player_ratio'],
			'sanitize_callback' => 'floatval'
		),
		array(
			'name'              => 'player_title',
			'label'             => __( 'Show Video Title', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Check this option to show the current playing video title on the bottom of the player.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['player_title'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'player_description',
			'label'             => __( 'Show Video Description', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Check this option to show the current playing video description on the bottom of the player.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['player_description'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'autoplay',
			'label'             => __( 'Autoplay', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Specifies whether the initial video will automatically start to play when the player loads.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['autoplay'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'autoadvance',
			'label'             => __( 'Autoplay Next Video', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Specifies whether to play the next video in the list automatically after previous one end.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['autoadvance'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'loop',
			'label'             => __( 'Loop', 'automatic-youtube-gallery' ),			
			'description'       => __( 'In the case of a single video player, plays the initial video again and again. In the case of a gallery, plays the entire list in the gallery and then starts again at the first video.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['loop'],
			'sanitize_callback' => 'intval'
		),		
		array(
			'name'              => 'controls',
			'label'             => __( 'Show Player Controls', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Uncheck this option to hide the video player controls.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['controls'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'modestbranding',
			'label'             => __( 'Hide YouTube Logo', 'automatic-youtube-gallery' ),			
			'description'       => __( "Lets you prevent the YouTube logo from displaying in the control bar. Note that a small YouTube text label will still display in the upper-right corner of a paused video when the user's mouse pointer hovers over the player.", 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['modestbranding'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'cc_load_policy',
			'label'             => __( 'Force Closed Captions', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Show captions by default, even if the user has turned captions off. The default behavior is based on user preference.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['cc_load_policy'],
			'sanitize_callback' => 'intval'
		),		
		array(
			'name'              => 'iv_load_policy',
			'label'             => __( 'Show Annotations', 'automatic-youtube-gallery' ),			
			'description'       => __( 'Choose whether to show annotations or not.', 'automatic-youtube-gallery' ),
			'type'              => 'checkbox',
			'value'             => $player_settings['iv_load_policy'],
			'sanitize_callback' => 'intval'
		),
		array(
			'name'              => 'hl',
			'label'             => __( 'Player Language', 'automatic-youtube-gallery' ),			
			'description'       => sprintf( 
				__( 'Specifies the player\'s interface language. Set the field\'s value to an <a href="%s" target="_blank">ISO 639-1 two-letter language code.</a>', 'automatic-youtube-gallery' ),
				'http://www.loc.gov/standards/iso639-2/php/code_list.php'
			),
			'type'              => 'text',
			'value'             => $player_settings['hl'],
			'sanitize_callback' => 'sanitize_text_field'
		),
		array(
			'name'              => 'cc_lang_pref',
			'label'             => __( 'Default Captions Language', 'automatic-youtube-gallery' ),			
			'description'       => sprintf( 
				__( 'Specifies the default language that the player will use to display captions. Set the field\'s value to an <a href="%s" target="_blank">ISO 639-1 two-letter language code.</a>', 'automatic-youtube-gallery' ),
				'http://www.loc.gov/standards/iso639-2/php/code_list.php'
			),
			'type'              => 'text',
			'value'             => $player_settings['cc_lang_pref'],
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	return $fields;
}

/**
 * Get video title to show on bottom of the player.
 *
 * @since  1.0.0
 * @param  stdClass $video      YouTube video object.
 * @param  array    $attributes Array of user attributes.
 * @return string               Video title.
 */
function ayg_get_player_title( $video, $attributes ) {
	$title = ! empty( $attributes['player_title'] ) ? $video->title : '';
	return apply_filters( 'ayg_player_title', $title, $video, $attributes );	
}

/**
 * Get player width.
 *
 * @since  1.0.0
 * @param  array  $attributes Array of user attributes.
 * @return string             Player width.
 */
function ayg_get_player_width( $attributes ) {
	$width = ! empty( $attributes['player_width'] ) ? $attributes['player_width'] . 'px' : '100%';
	return apply_filters( 'ayg_player_width', $width, $attributes );
}

/**
 * Get filtered php template file path.
 *
 * @since  1.0.0
 * @param  array  $template PHP file path.
 * @param  string $theme    Automatic YouTube Gallery Theme.
 * @return string           Filtered file path.
 */
function ayg_get_template( $template, $theme = '' ) {
	return apply_filters( 'ayg_load_template', $template, $theme );
}

/**
 * Get unique ID.
 *
 * @since  1.0.0
 * @return string Unique ID.
 */
function ayg_get_uniqid() {
	global $ayg_uniqid;

	if ( ! $ayg_uniqid ) {
		$ayg_uniqid = 0;
	}

	return uniqid() . ++$ayg_uniqid;
}

/**
 * Sanitize the integer inputs, accepts empty values.
 *
 * @since  1.0.0
 * @param  string|int $value Input value.
 * @return string|int        Sanitized value.
 */
function ayg_sanitize_int( $value ) {
	$value = intval( $value );
	return ( 0 == $value ) ? '' : $value;	
}

/**
 * Gallery HTML output.
 *
 * @since  1.0.0
 * @param  array $videos     Array of YouTube video object.
 * @param  array $attributes Array of user attributes.
 * @param  int   $active_id  Current active/selected video ID in the gallery.
 */
function the_ayg_gallery( $videos, $attributes, $active_id = 0 ) {
	if ( 'video' != $attributes['type'] ) {
		include ayg_get_template( AYG_DIR . 'public/templates/gallery.php', $attributes['theme'] );
	}
}

/**
 * Gallery HTML output.
 *
 * @since  1.0.0
 * @param  array   $video      YouTube video object.
 * @param  array   $attributes Array of user attributes.
 * @param  boolean $is_active  True if active video item, false if not.
 */
function the_ayg_gallery_thumbnail( $video, $attributes, $is_active = false ) {
	include ayg_get_template( AYG_DIR . 'public/templates/gallery-thumbnail.php', $attributes['theme'] );
}

/**
 * Pagination HTML output.
 *
 * @since  1.0.0
 * @param  array $atts Array of user attributes.
 */
function the_ayg_pagination( $atts ) {
	if ( ! empty( $atts['pagination'] ) ) {
		// Build attributes		
		$attributes = array();

		$fields = ayg_get_editor_fields();

		foreach ( $fields['gallery']['fields'] as $field ) {
			$field_name = $field['name'];

			if ( ! in_array( $field_name, array( 'per_page', 'pagination' ) ) ) {
				$attributes[ $field_name ] = sanitize_text_field( $atts[ $field_name ] );
			}
		}

		$source_type = sanitize_text_field( $atts['type'] );
		
		$attributes = array_merge(
			$attributes,
			array(								
				'type'               => $source_type,
				'id'                 => sanitize_text_field( $atts[ $source_type ] ),
				'order'              => sanitize_text_field( $atts['order'] ), // works only when type=search
				'per_page'           => (int) $atts['per_page'],
				'cache'              => (int) $atts['cache'],
				'player_description' => ! empty( $atts['player_description'] ) ? (int) $atts['player_description'] : 0,	
				'num_pages'          => 1,		
				'paged'              => ! empty( $atts['paged'] ) ? (int) $atts['paged'] : 1,	
				'next_page_token'    => ! empty( $atts['next_page_token'] ) ? sanitize_text_field( $atts['next_page_token'] ) : '',
				'prev_page_token'    => ! empty( $atts['prev_page_token'] ) ? sanitize_text_field( $atts['prev_page_token'] ) : ''
			)
		);

		// Find total number of pages
		$videos_found = ! empty( $atts['videos_found'] ) ? (int) $atts['videos_found'] : 0;

		if ( $videos_found > 0 ) {
			if ( 'search' == $source_type ) {
				$limit = min( (int) $atts['limit'], $videos_found );
				$attributes['num_pages'] = ceil( $limit / $attributes['per_page'] );
			} else {
				$attributes['num_pages'] = ceil( $videos_found / $attributes['per_page'] );
			}
		}		

		// Process output
		if ( $attributes['num_pages'] > 1 ) {
			$pagination_type = ( 'pager' == $atts['pagination_type'] ) ? 'pager' : 'loadmore';
			include ayg_get_template( AYG_DIR . 'public/templates/pagination-' . $pagination_type. '.php', $attributes['theme'] );
		}
	}	
}

/**
 * Player HTML output.
 *
 * @since  1.0.0
 * @param  stdClass $video      YouTube video object.
 * @param  array    $attributes Array of user attributes.
 */
function the_ayg_player( $video, $attributes ) {
	include ayg_get_template( AYG_DIR . 'public/templates/player.php', $attributes['theme'] );
}
