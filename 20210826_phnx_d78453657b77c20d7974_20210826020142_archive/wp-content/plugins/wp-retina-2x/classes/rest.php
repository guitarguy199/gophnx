<?php

class Meow_WR2X_Rest
{
  private $core;
	private $namespace = 'wp-retina-2x/v1';

	public function __construct( $core ) {
    $this->core = $core;

		// FOR DEBUG
		// For experiencing the UI behavior on a slower install.
		// sleep(1);
		// For experiencing the UI behavior on a buggy install.
		// trigger_error( "Error", E_USER_ERROR);
		// trigger_error( "Warning", E_USER_WARNING);
		// trigger_error( "Notice", E_USER_NOTICE);
		// trigger_error( "Deprecated", E_USER_DEPRECATED);

		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	function rest_api_init() {
		if ( !current_user_can( 'upload_files' ) ) {
			return;
		} 

		// SETTINGS
		register_rest_route( $this->namespace, '/update_option/', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_update_option' )
		) );
		register_rest_route( $this->namespace, '/all_settings/', array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_all_settings' )
		) );
		register_rest_route( $this->namespace, '/easy_io_link/', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_easy_io_link' )
		) );
		register_rest_route( $this->namespace, '/easy_io_unlink/', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_easy_io_unlink' )
		) );
		register_rest_route( $this->namespace, '/easy_io_stats/', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_easy_io_stats' )
		) );

		// STATS & LISTING
		register_rest_route( $this->namespace, '/stats', array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_get_stats' )
		) );
		register_rest_route( $this->namespace, '/media', array(
			'methods' => 'GET',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_media' ),
			'args' => array(
				'limit' => array( 'required' => false, 'default' => 10 ),
				'skip' => array( 'required' => false, 'default' => 20 ),
				'filterBy' => array( 'required' => false, 'default' => 'all' ),
				'orderBy' => array( 'required' => false, 'default' => 'id' ),
				'order' => array( 'required' => false, 'default' => 'desc' ),
				'search' => array( 'required' => false ),
				'offset' => array( 'required' => false ),
				'order' => array( 'required' => false ),
				'search' => array( 'required' => false )
			)
		) );
		register_rest_route( $this->namespace, '/get_all_ids', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_get_all_ids' )
		) );

		// ACTIONS
		register_rest_route( $this->namespace, '/refresh', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_refresh' )
		) );
		register_rest_route( $this->namespace, '/details', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_get_details' )
		) );
		register_rest_route( $this->namespace, '/build_retina', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_build_retina' )
		) );
		register_rest_route( $this->namespace, '/regenerate', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_regenerate' )
		) );
		register_rest_route( $this->namespace, '/delete_retina', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_delete_retina' )
		) );
		register_rest_route( $this->namespace, '/ignore', array(
			'methods' => 'POST',
			'permission_callback' => '__return_true',
			'callback' => array( $this, 'rest_ignore' )
		) );
  }

	function rest_all_settings() {
		return new WP_REST_Response( [ 'success' => true, 'data' => $this->get_all_options() ], 200 );
	}

	function count_issues() {
		return count( $this->core->get_issues() );
	}

	function count_ignored() {
		return count( $this->core->get_ignores() );
	}

	function count_all() {
		global $wpdb;
		$wpml = $this->core->create_sql_if_wpml_original();
		return (int)$wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts p 
			WHERE post_type='attachment'
			AND post_type = 'attachment' ${wpml}
			AND post_status='inherit'
			AND ( post_mime_type = 'image/jpeg' OR
			post_mime_type = 'image/png' OR
			post_mime_type = 'image/gif' )"
		);
	}

	function rest_get_stats() {
		return new WP_REST_Response( [ 'success' => true, 'data' => array(
			'issues' => $this->count_issues(),
			'ignored' => $this->count_ignored(),
			'all' => $this->count_all()
		) ], 200 );
	}

	function rest_get_all_ids( $request ) {
		global $wpdb;
		$params = $request->get_json_params();
		$issuesOnly = isset( $params['issuesOnly'] ) ? (bool)$params['issuesOnly'] : false;
		if ( $issuesOnly ) {
			$ids = array_values( $this->core->get_issues() );
		}
		else {
			$wpml = $this->core->create_sql_if_wpml_original();
			$ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts p 
				WHERE post_type='attachment'
				AND post_type = 'attachment' ${wpml}
				AND post_status='inherit'
				AND ( post_mime_type = 'image/jpeg' OR
				post_mime_type = 'image/png' OR
				post_mime_type = 'image/gif' )"
			);
		}
		return new WP_REST_Response( [ 'success' => true, 'data' => $ids ], 200 );
	}

	function rest_refresh() {
		$this->core->calculate_issues();
		return new WP_REST_Response( [ 'success' => true ], 200 );
	}

	/**
	 * Get the status for many Media IDs.
	 *
	 * @param integer $skip
	 * @param integer $limit
	 * @return void
	 */
	function get_media_status( $skip = 0, $limit = 10, $filterBy = 'all', $orderBy = 'id', $order = 'desc', $search = '' ) {
		global $wpdb;
		$whereIsIn = '';
		if ( $filterBy === 'issues' ) {
			$in = $this->core->get_issues();
			if ( empty( $in ) ) {
				return array();
			}
			$whereIsIn = 'AND p.ID IN (' . implode( ',', $in ) . ')';
		}
		else if ( $filterBy === 'ignored' ) {
			$in = $this->core->get_ignores();
			if ( empty( $in ) ) {
				return array();
			}
			$whereIsIn = 'AND p.ID IN (' . implode( ',', $in ) . ')';
		}
		$orderSql = 'ORDER BY p.ID DESC';
		if ($orderBy === 'post_title') {
			$orderSql = 'ORDER BY post_title ' . ( $order === 'asc' ? 'ASC' : 'DESC' );
		}
		else if ($orderBy === 'current_filename') {
			$orderSql = 'ORDER BY current_filename ' . ( $order === 'asc' ? 'ASC' : 'DESC' );
		}
		$entries = [];
		if ( empty( $search ) ) {
			$entries = $wpdb->get_results( 
				$wpdb->prepare( "SELECT p.ID, p.post_title, 
					MAX(CASE WHEN pm.meta_key = '_wp_attachment_metadata' THEN pm.meta_value END) AS metadata
					FROM $wpdb->posts p
					INNER JOIN $wpdb->postmeta pm ON pm.post_id = p.ID
					WHERE post_type='attachment'
					AND pm.meta_key = '_wp_attachment_metadata'
					$whereIsIn
					GROUP BY p.ID
					$orderSql
					LIMIT %d, %d", $skip, $limit 
				)
			);
		}
		else {
			$entries = $wpdb->get_results( 
				$wpdb->prepare( "SELECT p.ID, p.post_title, 
					MAX(CASE WHEN pm.meta_key = '_wp_attachment_metadata' THEN pm.meta_value END) AS metadata
					FROM $wpdb->posts p
					INNER JOIN $wpdb->postmeta pm ON pm.post_id = p.ID
					WHERE post_type='attachment'
					AND pm.meta_key = '_wp_attachment_metadata'
					$whereIsIn
					AND p.post_title LIKE %s
					GROUP BY p.ID
					$orderSql
					LIMIT %d, %d", ( '%' . $search . '%' ), $skip, $limit 
				)
			);
		}
		foreach ( $entries as $entry ) {
			$entry->ID = (int)$entry->ID;
			$entry->info = $this->core->retina_info( $entry->ID, ARRAY_A );
			$entry->thumbnail_url = wp_get_attachment_thumb_url( $entry->ID );
			$entry->metadata = unserialize( $entry->metadata );
			$entry->metadata = $this->core->postprocess_metadata( $entry->metadata );
			$attached_file = get_attached_file( $entry->ID );
			$entry->filesize = $attached_file ? size_format( filesize( $attached_file ), 2 ) : 0;
			$version = get_post_meta( $entry->ID, '_media_version', true );
			$entry->version = (int)$version;
		}
		return $entries;
	}

	function rest_media( $request ) {
		$limit = trim( $request->get_param('limit') );
		$skip = trim( $request->get_param('skip') );
		$filterBy = trim( $request->get_param('filterBy') );
		$orderBy = trim( $request->get_param('orderBy') );
		$order = trim( $request->get_param('order') );
		$search = sanitize_text_field( $request->get_param('search') );
		$entries = $this->get_media_status( $skip, $limit, $filterBy, $orderBy, $order, $search );
		$total = 0;
		if ( $filterBy == 'issues' ) {
			$total = $this->count_issues();
		}
		else if ( $filterBy == 'ignored' ) {
			$total = $this->count_ignored();
		}
		else if ( $filterBy == 'all' ) {
			$total = $this->count_all();
		}
		return new WP_REST_Response( [ 'success' => true, 'data' => $entries, 'total' => $total ], 200 );
	}

	function rest_get_details( $request ) {
		// Check errors
		$params = $request->get_json_params();
		$mediaId = isset( $params['mediaId'] ) ? (int)$params['mediaId'] : null;
		if ( empty( $mediaId ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => "The Media ID is required." ] );
		}

		// Prepare result
		$info = $this->core->retina_info( $mediaId, ARRAY_A );
		return new WP_REST_Response( [ 'success' => true, 'data' => $info ], 200 );
	}

	// Regenerate the Thumbnails
	function regenerate_thumbnails( $mediaId ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		do_action( 'wr2x_before_generate_thumbnails', $mediaId );
		$file = get_attached_file( $mediaId );
		$meta = wp_generate_attachment_metadata( $mediaId, $file );
		wp_update_attachment_metadata( $mediaId, $meta );
		do_action( 'wr2x_generate_thumbnails', $mediaId );
	}

	function rest_build_retina( $request ) {
		// Check errors
		$params = $request->get_json_params();
		$mediaId = isset( $params['mediaId'] ) ? (int)$params['mediaId'] : null;
		if ( empty( $mediaId ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => "The Media ID is required." ] );
		}

		// Build retina
		do_action( 'wr2x_before_regenerate', $mediaId );
		$this->core->delete_attachment( $mediaId, false );
		$meta = wp_get_attachment_metadata( $mediaId );
		$this->core->generate_images( $meta );
		do_action( 'wr2x_regenerate', $mediaId );

		// Prepare result
		$info = $this->core->get_media_status_one( $mediaId );
		return new WP_REST_Response( [ 'success' => true, 'data' => $info  ], 200 );
	}

	function rest_delete_retina( $request ) {
		if ( !current_user_can( 'upload_files' ) ) {
			$this->core->log( "You do not have permission to upload files." );
			return __( "You do not have permission to upload files.", 'wp-retina-2x' );
		}
		$params = $request->get_json_params();

		// Check errors
		$mediaId = isset( $params['mediaId'] ) ? (int)$params['mediaId'] : null;
		if ( empty( $mediaId ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => "The Media ID is required." ] );
		}

		// Delete Retina
		$this->core->delete_retina_fullsize( $mediaId );
		$this->core->delete_attachment( $mediaId, true );
		$meta = wp_get_attachment_metadata( $mediaId );
		$info = $this->core->get_media_status_one( $mediaId );
		return new WP_REST_Response( [ 'success' => true, 'data' => $info  ], 200 );
	}

	function rest_ignore( $request ) {
		$params = $request->get_json_params();
		$mediaId = isset( $params['mediaId'] ) ? (int)$params['mediaId'] : null;

		// Check errors
		if ( empty( $mediaId ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => "The Media ID is required." ] );
		}

		// Ignore
		if ( $this->core->is_ignore( $mediaId ) ) {
			$info = $this->core->remove_ignore( $mediaId );
		}
		else {
			$info = $this->core->add_ignore( $mediaId );
		}
		return new WP_REST_Response( [ 'success' => true, 'data' => $info  ], 200 );
	}

	function rest_regenerate( $request ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$params = $request->get_json_params();
		$mediaId = isset( $params['mediaId'] ) ? (int)$params['mediaId'] : null;

		// Check errors
		if ( empty( $mediaId ) ) {
			return new WP_REST_Response( [ 'success' => false, 'message' => "The Media ID is required." ] );
		}

		// Regenerate
		$this->regenerate_thumbnails( $mediaId );
		$info = $this->core->get_media_status_one( $mediaId );
		return new WP_REST_Response( [ 'success' => true, 'data' => $info  ], 200 );
	}

	function get_all_options() {

		return array(

			// OPTIONS
			'wr2x_sizes' => $this->core->get_image_sizes( ARRAY_A ),
			'wr2x_retina_sizes' => array_values( get_option( 'wr2x_retina_sizes' ) ),
			'wr2x_disabled_sizes' => array_values( get_option( 'wr2x_disabled_sizes' ) ),
			'wr2x_method' => get_option( "wr2x_method" ),
			'wr2x_full_size' => get_option( 'wr2x_full_size', false ),
			'wr2x_picturefill_keep_src' => get_option( 'wr2x_picturefill_keep_src', false ),
			'wr2x_picturefill_lazysizes' => get_option( 'wr2x_picturefill_lazysizes', false ),
			'wr2x_picturefill_css_background' => get_option( 'wr2x_picturefill_css_background', false ),
			'wr2x_picturefill_noscript' => get_option( 'wr2x_picturefill_noscript', false ),
			'wr2x_auto_generate' => get_option( 'wr2x_auto_generate', false ),
			'wr2x_over_http_check' => get_option( 'wr2x_over_http_check', false ),
			'wr2x_debug' => get_option( 'wr2x_debug', false ),
			'wr2x_disable_responsive' => get_option( 'wr2x_disable_responsive', false ),
			'wr2x_image_replace' => get_option( 'wr2x_image_replace', false ),
			'wr2x_cdn_domain' => get_option( 'wr2x_cdn_domain', '' ),
			'wr2x_easyio_domain' => get_option( 'wr2x_easyio_domain', '' ),
			'wr2x_easyio_lossless' => get_option( 'wr2x_easyio_lossless', '' ),
			'wr2x_big_image_size_threshold' => get_option( 'wr2x_big_image_size_threshold', false ),
			'wr2x_hide_retina_column' => get_option( 'wr2x_hide_retina_column', false ),
			'wr2x_hide_retina_dashboard' => get_option( 'wr2x_hide_retina_dashboard', false )
		);
	}

	function rest_update_option( $request ) {
		$params = $request->get_json_params();
		try {
			$name = $params['name'];
			$value = is_bool( $params['value'] ) ? ( $params['value'] ? '1' : '' ) : $params['value'];
			$success = update_option( $name, $value );
			if ( !$success ) {
				return new WP_REST_Response([ 'success' => false, 'message' => 'Could not update option.' ], 200 );
			}
			return new WP_REST_Response([ 'success' => true, 'data' => $value ], 200 );
		} 
		catch (Exception $e) {
			return new WP_REST_Response([ 'success' => false, 'message' => $e->getMessage() ], 500 );
		}
	}

	function rest_easy_io_unlink( $request ) {
		delete_option( 'wr2x_easyio_domain' );
		delete_option( 'wr2x_easyio_plan' );
		return new WP_REST_Response([ 'success' => true ], 200 );
	}

	function rest_easy_io_link( $request ) {
		try {
			$error_message = null;
			$site_url = get_site_url();
			$home_url = get_home_url();
			$url = 'http://optimize.exactlywww.com/exactdn/activate.php';
			$ssl = wp_http_supports( array( 'ssl' ) );
			if ( $ssl ) {
				$url = set_url_scheme( $url, 'https' );
			}
			//add_filter( 'http_headers_useragent', 'perfect_images', PHP_INT_MAX );
			$result = wp_remote_post( $url, array( 'timeout' => 10, 'body'    => array( 'site_url' => $site_url, 'home_url' => $home_url ) ) );

			if ( is_wp_error( $result ) ) {
				$error_message = $result->get_error_message();
			} 
			else if ( !empty( $result['body'] ) && strpos( $result['body'], 'domain' ) !== false ) {
				$response = json_decode( $result['body'], true );
				if ( !empty( $response['domain'] ) ) {
					update_option( 'wr2x_easyio_domain', $response['domain'] );
					if ( !empty( $response['plan_id'] ) ) {
						update_option( 'wr2x_easyio_plan', (int)$response['plan_id'] );
					}
					
					// Clear cache
					// From https://github.com/nosilver4u/ewww-image-optimizer/blob/master/classes/class-exactdn.php#L298
					if ( 'external' === get_option( 'elementor_css_print_method' ) ) {
						update_option( 'elementor_css_print_method', 'internal' );
					}
					if ( function_exists( 'et_get_option' ) && function_exists( 'et_update_option' ) && 
						'on' === et_get_option( 'et_pb_static_css_file', 'on' ) ) {
						et_update_option( 'et_pb_static_css_file', 'off' );
						et_update_option( 'et_pb_css_in_footer', 'off' );
					}
					if ( function_exists( 'envira_flush_all_cache' ) ) {
						envira_flush_all_cache();
					}
				}
			} 
			else if ( !empty( $result['body'] ) && false !== strpos( $result['body'], 'error' ) ) {
				$response = json_decode( $result['body'], true );
				$error_message = $response['error'];
			}
			if ( $error_message ) {
				return new WP_REST_Response([ 'success' => false, 'message' => $error_message ], 200 );
			}
			return new WP_REST_Response([ 'success' => true, 'logs' => json_decode( $result['body'] ) ], 200 );
		} 
		catch ( Exception $e ) {
			return new WP_REST_Response([ 'success' => false, 'message' => $e->getMessage() ], 500 );
		}
	}

	function rest_easy_io_stats( $request ) {
		try {
			$error_message = null;
			$stats = null;
			$url = 'http://optimize.exactlywww.com/exactdn/savings.php';
			$ssl = wp_http_supports( array( 'ssl' ) );
			if ( $ssl ) {
				$url = set_url_scheme( $url, 'https' );
			}
			$easyio_domain = get_option( 'wr2x_easyio_domain' );
			$result = wp_remote_post( $url, array( 'timeout' => 10, 'body' => array( 'alias' => $easyio_domain ) ) );
			if ( is_wp_error( $result ) ) {
				$error_message = $result->get_error_message();
			} 
			else if ( !empty( $result['body'] ) ) {
				$stats = json_decode( $result['body'], true );
			} 
			else if ( !empty( $result['body'] ) && false !== strpos( $result['body'], 'error' ) ) {
				$response = json_decode( $result['body'], true );
				$error_message = $response['error'];
			}
			if ( $error_message ) {
				return new WP_REST_Response([ 'success' => false, 'message' => $error_message ], 200 );
			}
			return new WP_REST_Response([ 'success' => true, 'stats' => $stats ], 200 );
		} 
		catch ( Exception $e ) {
			return new WP_REST_Response([ 'success' => false, 'message' => $e->getMessage() ], 500 );
		}
	}

}

?>