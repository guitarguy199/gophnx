<?php

/**
 * A wrapper class for the Youtube Data API v3.
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
 * AYG_YouTube_API class.
 *
 * @since 1.0.0
 */
class AYG_YouTube_API {

	/**
     * The YouTube API Key.
	 * 
	 * @since  1.0.0
	 * @access protected
     * @var    string
     */
	protected $api_key;

	/**
	 * The YouTube API URLs.
	 * 
	 * @since  1.0.0
	 * @access protected
     * @var    array
     */
    public $api_urls = array(       
		'playlistItems.list' => 'https://www.googleapis.com/youtube/v3/playlistItems',
		'channels.list'      => 'https://www.googleapis.com/youtube/v3/channels',
		'search.list'        => 'https://www.googleapis.com/youtube/v3/search',
		'videos.list'        => 'https://www.googleapis.com/youtube/v3/videos'
	);

	/**
	 * Get videos.
	 * 
	 * @since  1.0.0
     * @param  array  $params Array of query params.
     * @return mixed
     */
    public function get_videos( $params = array() ) {
		// Get YouTube API Key
		$general_settings = get_option( 'ayg_general_settings' );

		if ( empty( $general_settings['api_key'] ) ) {
			return $this->get_error( __( 'YouTube API Key Missing.', 'automatic-youtube-gallery' ) . ' ' . sprintf( __( 'Follow <a href="%s" target="_blank">this guide</a> to get your own API key.', 'automatic-youtube-gallery' ), 'https://plugins360.com/automatic-youtube-gallery/how-to-get-youtube-api-key/' ) );
		}

		$this->api_key = $general_settings['api_key'];

		// Process response
		$response = array();

		switch ( $params['type'] ) {
			case 'playlist':
				if ( empty( $params['id'] ) ) {
					return $this->get_error( __( 'YouTube Playlist ID (or) URL is required.', 'automatic-youtube-gallery' ) );
				}
				
				$response = $this->request_api_playlist_items( $params );
				break;

			case 'channel':
				if ( empty( $params['id'] ) ) {
					return $this->get_error( __( 'YouTube Channel ID (or) URL is required.', 'automatic-youtube-gallery' ) );
				}

				// Find playlistId from the channel
				$params['id'] = $this->parse_youtube_id_from_url( $params['id'], 'channel' );
				$response = $this->request_api_channels( $params );

				// Get videos using the playlistId
				if ( ! isset( $response->error ) ) {

					$params['id'] = $response->playlistId;
					$response = $this->request_api_playlist_items( $params );

				}
				break;

			case 'username':
				if ( empty( $params['id'] ) ) {
					return $this->get_error( __( 'YouTube Account Username is required.', 'automatic-youtube-gallery' ) );
				}

				// Find playlistId from the channel 
				$params['forUsername'] = $this->parse_youtube_id_from_url( $params['id'], 'username' );
				unset( $params['id'] );

				$response = $this->request_api_channels( $params );

				// Get videos using the playlistId
				if ( ! isset( $response->error ) ) {

					$params['id'] = $response->playlistId;
					$response = $this->request_api_playlist_items( $params );

				}
				break;

			case 'search':
				if ( empty( $params['id'] ) ) {
					return $this->get_error( __( 'Cannot search an empty string. A search term is required.', 'automatic-youtube-gallery' ) );
				}
				
				$response = $this->request_api_search( $params );						
				break;

			case 'videos':			
				if ( empty( $params['id'] ) ) {
					return $this->get_error( __( 'Atleast one YouTube Video ID (or) URL is required.', 'automatic-youtube-gallery' ) );
				}

				$response = $this->request_api_videos( $params );
				break;

			default: // video
				if ( empty( $params['id'] ) ) {
					return $this->get_error( __( 'YouTube Video ID (or) URL is required.', 'automatic-youtube-gallery' ) );
				}
				
				$response = $this->request_api_video( $params );
				break;
		}

		return $response;
	}

	/**
	 * Grab the playlist, channel or video ID using the YouTube URL given.
	 * 
	 * @since  1.0.0
	 * @access private
     * @param  string  $url  YouTube URL.
	 * @param  string  $type Type of the URL (playlist|channel|video).
     * @return mixed
     */
    private function parse_youtube_id_from_url( $url, $type = 'video' ) {
		$id = $url;

		switch ( $type ) {
			case 'playlist':
				if ( preg_match( '/list=(.*)&?\/?/', $url, $matches ) ) {
					$id = $matches[1];
				}
				break;

			case 'channel':
				$url = parse_url( rtrim( $url, '/' ) );

				if ( isset( $url['path'] ) && preg_match( '/^\/channel\/(([^\/])+?)$/', $url['path'], $matches ) ) {
					$id = $matches[1];
				}
				break;

			case 'username':
				$url = parse_url( rtrim( $url, '/' ) );

				if ( isset( $url['path'] ) && preg_match( '/^\/user\/(([^\/])+?)$/', $url['path'], $matches ) ) {
					$id = $matches[1];
				}
				break;
			
			default: // video
				$url = parse_url( $url );
			
				if ( array_key_exists( 'host', $url ) ) {				
					if ( 0 === strcasecmp( $url['host'], 'youtu.be' ) ) {
						$id = substr( $url['path'], 1 );
					} elseif ( 0 === strcasecmp( $url['host'], 'www.youtube.com' ) ) {
						if ( isset( $url['query'] ) ) {
							parse_str( $url['query'], $url['query'] );

							if ( isset( $url['query']['v'] ) ) {
								$id = $url['query']['v'];
							}
						}
							
						if ( empty( $id ) ) {
							$url['path'] = explode( '/', substr( $url['path'], 1 ) );

							if ( in_array( $url['path'][0], array( 'e', 'embed', 'v' ) ) ) {
								$id = $url['path'][1];
							}
						}
					}
				}
		}

		return $id;
	}

	/**
	 * Get videos using playlistItems API.
	 * 
	 * @since  1.0.0
	 * @access private
     * @param  array    $params Array of query params.
     * @return stdClass
     */
    private function request_api_playlist_items( $params = array() ) {
		$api_url = $this->get_api_url( 'playlistItems.list' );
		
		$params['playlistId'] = $this->parse_youtube_id_from_url( $params['id'], 'playlist' );

        $api_params = $this->safe_merge_params(
			array(
				'playlistId' => '',
				'part'       => 'id,snippet,contentDetails,status',
				'maxResults' => 50,
				'pageToken'  => '',
				'cache'      => 0
			),
			$params
		);
		
		$api_response = $this->request_api( $api_url, $api_params );
		if ( isset( $api_response->error ) ) {
			return $api_response;
		}

		$videos = $this->parse_videos( $api_response );
		if ( isset( $videos->error ) ) {
			return $videos;
		}

		// Process result
		$response = new stdClass();
		$response->page_info = $this->parse_page_info( $api_response );
		$response->videos = $videos;

		return $response;		
	}

	/**
	 * Find playlistId using channels API.
	 * 
	 * @since  1.0.0
	 * @access private
     * @param  array   $params Array of query params.
     * @return mixed
     */
    private function request_api_channels( $params = array() ) {
		$api_url = $this->get_api_url( 'channels.list' );

		$api_params = $this->safe_merge_params(
			array(
				'id'          => '',
				'forUsername' => '',
				'part'        => 'contentDetails',
				'cache'       => 0
			),
			$params
		);

		$api_response = $this->request_api( $api_url, $api_params );
		if ( isset( $api_response->error ) ) {
			return $api_response;
		}

		$items = $api_response->items;
		if ( ! is_array( $items ) || count( $items ) == 0 ) {
			return $this->get_error( __( 'No videos found matching your query.', 'automatic-youtube-gallery' ) );
		}

		// Process result
		$response = new stdClass();
		$response->playlistId = $items[0]->contentDetails->relatedPlaylists->uploads;

		return $response;
	}

	/**
	 * Get videos using search API.
	 * 
	 * @since  1.0.0
	 * @access private
     * @param  array    $params Array of query params.
     * @return stdClass
     */
    private function request_api_search( $params = array() ) {
		$api_url = $this->get_api_url( 'search.list' );

		$params['q'] = $params['id'];		

		if ( ! empty( $params['q'] ) ) {
			$params['q'] = str_replace( '|', '%7C', $params['q'] );
		}

		$params['type'] = 'video'; // Overrides user defined type value 'search'

		$api_params = $this->safe_merge_params(
			array(
				'q'               => '',
				'channelId'       => '',
				'type'            => 'video',
				'videoEmbeddable' => true,
				'part'            => 'id,snippet',
				'order'           => 'date',
				'maxResults'      => 50,
				'pageToken'       => '',
				'cache'           => 0
			),
			$params
		);		
		
		$api_response = $this->request_api( $api_url, $api_params );
		if ( isset( $api_response->error ) ) {
			return $api_response;
		}

		$videos = $this->parse_videos( $api_response );
		if ( isset( $videos->error ) ) {
			return $videos;
		}

		// Process result
		$response = new stdClass();
		$response->page_info = $this->parse_page_info( $api_response );
		$response->videos = $videos;

		return $response;		
	}

	/**
	 * Get details of the given video IDs.
	 * 
	 * @since  1.0.0
	 * @access private
     * @param  array    $params Array of query params.
     * @return stdClass
     */
    private function request_api_videos( $params = array() ) {
		$api_url = $this->get_api_url( 'videos.list' );		

		$urls = str_replace( "\n\r", ',', $params['id'] );
		$urls = str_replace( ' ', ',', $params['id'] );
		$urls = explode( ',', $urls );
		$urls = array_filter( $urls );

		$all_ids = array();
		foreach ( $urls as $url ) {
			$all_ids[] = $this->parse_youtube_id_from_url( $url, 'video' );
		}
		$total_videos = count( $all_ids );
		$total_pages  = ceil( $total_videos / $params['maxResults'] );

		$current_page = isset( $params['pageToken'] ) ? (int) $params['pageToken'] : 1;
		$current_page = max( $current_page, 1 );
		$current_page = min( $current_page, $total_pages );

		$offset = ( $current_page - 1 ) * $params['maxResults'];
		if ( $offset < 0 ) {
			$offset = 0;
		}

		$current_ids  = array_slice( $all_ids, $offset, $params['maxResults'] );
		$params['id'] = implode( ',', $current_ids );

		$api_params = $this->safe_merge_params(
			array(
            	'id'    => '',
				'part'  => 'id,snippet,contentDetails,statistics,status',
				'cache' => 0
			), 
			$params
		);

		$api_response = $this->request_api( $api_url, $api_params );
		if ( isset( $api_response->error ) ) {
			return $api_response;
		}

		$videos = $this->parse_videos( $api_response );

		// Process result
		$response = new stdClass();
		$response->videos = $videos;

		$response->page_info = array(
			'videos_found' => $total_videos
		);

		if ( $current_page > 1 ) {
			$response->page_info['prev_page_token'] = $current_page - 1;
		}

		if ( $current_page < $total_pages ) {
			$response->page_info['next_page_token'] = $current_page + 1;
		}

		return $response;		
	}

	/**
	 * Get details of the given video ID.
	 * 
	 * @since  1.0.0
	 * @access private
     * @param  array    $params Array of query params.
     * @return stdClass
     */
    private function request_api_video( $params = array() ) {
		$api_url = $this->get_api_url( 'videos.list' );
		
		$params['id'] = $this->parse_youtube_id_from_url( $params['id'], 'video' );
		
		$api_params = $this->safe_merge_params(
			array(
            	'id'    => '',
				'part'  => 'id,snippet,contentDetails,statistics,status',
				'cache' => 0
			), 
			$params
		);

		$api_response = $this->request_api( $api_url, $api_params );
		if ( isset( $api_response->error ) ) {
			return $api_response;
		}

		$videos = $this->parse_videos( $api_response );

		// Process result
		$response = new stdClass();
		$response->videos = $videos;

		return $response;		
	}
	
	/**
     * Get API URL by request.
	 *
	 * @since  1.0.0
	 * @access private
     * @param  array   $name
     * @return string
     */
    private function get_api_url( $name ) {
        return $this->api_urls[ $name ];
	}	

	/**
     * Request data from the API server.
     *
	 * @since  1.0.0
	 * @access private
     * @param  string  $url    YouTube API URL.
     * @param  array   $params Array of query params.
     * @return mixed     
     */
    private function request_api( $url, $params ) {
		$params['key'] = $this->api_key;	

		// Request data from transients
		$transient_url  = $url . ( strpos( $url, '?' ) === false ? '?' : '' ) . http_build_query( $params );
		$transient_key  = 'ayg_' . md5( $transient_url );		
		$transient_data = get_transient( $transient_key );

		if ( ! empty( $transient_data ) ) {
			return $transient_data;
		}

		$transient_expiration = 0;
		
		if ( isset( $params['cache'] ) ) {
			$transient_expiration = (int) $params['cache'];
			unset( $params['cache'] );
		}

		// Request data from API server		
		$url = $url . ( strpos( $url, '?' ) === false ? '?' : '' ) . http_build_query( $params );

		$request = wp_remote_get( $url, array(
			'headers' => [ 'referer' => home_url() ],
		) );

		if ( is_wp_error( $request ) ) {
			return $this->get_error( $request->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $request );

		$data = json_decode( $body );

		if ( isset( $data->error ) ) {
			$message = "Error " . $data->error->code . " " . $data->error->message;
			
			if ( isset( $data->error->errors[0] ) ) {
				$message .= " : " . $data->error->errors[0]->reason;
			}
			
			return $this->get_error( $message );			
		}

		// Store data in transients
		if ( $transient_expiration > 0 ) {			
			set_transient( $transient_key, $data, $transient_expiration );

			// Get the current list of transients
			$transient_keys = get_option( 'ayg_transient_keys', array() );

			// Append our new one
			if ( ! in_array( $transient_key, $transient_keys ) ) {
				$transient_keys[] = $transient_key;
			}

			// Save it to the DB
			update_option( 'ayg_transient_keys', $transient_keys );
		}		

		// Finally return the data
		return $data;
	}

	/**
     * Parse videos from the YouTube API response object.
     *
	 * @since  1.0.0
	 * @access private
     * @param  object  $data YouTube API response object.
     * @return mixed
     */
    private function parse_videos( $data ) {
		$items = $data->items;		

		if ( ! is_array( $items ) || 0 == count( $items ) ) {
			return $this->get_error( __( 'No videos found matching your query.', 'automatic-youtube-gallery' ) );
		}

		$videos = array();

		foreach ( $items as $item ) {
			$video = new stdClass();

			// Video ID
			$video->id = '';	

			if ( isset( $item->snippet->resourceId ) && isset( $item->snippet->resourceId->videoId ) ) {
				$video->id = $item->snippet->resourceId->videoId;
			} elseif ( isset( $item->contentDetails ) && isset( $item->contentDetails->videoId ) ) {
				$video->id = $item->contentDetails->videoId;
			} elseif ( isset( $item->id ) && isset( $item->id->videoId ) ) {
				$video->id = $item->id->videoId;
			} elseif ( isset( $item->id ) ) {
				$video->id = $item->id;
			}				

			// Video title
			$video->title = $item->snippet->title;

			// Video description
			$video->description = $item->snippet->description;

			// Video thumbnails
			if ( isset( $item->snippet->thumbnails ) ) {
				$video->thumbnails = $item->snippet->thumbnails;
			}		

			// Video publish date
			$video->published_at = $item->snippet->publishedAt;

			// Push resulting object to the main array
			$status = 'private';
			
			if ( isset( $item->status ) && ( 'public' == $item->status->privacyStatus || 'unlisted' == $item->status->privacyStatus ) ) {
				$status = 'public';				
			}

			if ( isset( $item->snippet->status ) && ( 'public' == $item->snippet->status->privacyStatus || 'unlisted' == $item->snippet->status->privacyStatus ) ) {
				$status = 'public';				
			}

			if ( 'youtube#searchResult' == $item->kind ) {
				$status = 'public';				
			}

			if ( 'public' == $status ) {
				$videos[] = $video;
			}
		}

		return $videos;		
	}

	/**
     * Parse page info from the YouTube API response object.
     *
	 * @since  1.0.0
	 * @access private
     * @param  object  $data YouTube API response object.
     * @return array
     */
    private function parse_page_info( $data ) {
		$page_info = array();

		// Total count of videos found
		if ( isset( $data->pageInfo ) && isset( $data->pageInfo->totalResults ) ) {
			$page_info['videos_found'] = $data->pageInfo->totalResults;
		}		

		// Token for the previous page
		if ( isset( $data->prevPageToken ) ) {
			$page_info['prev_page_token'] = $data->prevPageToken;
		}
		
		// Token for the next page
		if ( isset( $data->nextPageToken ) ) {
			$page_info['next_page_token'] = $data->nextPageToken;
		}

		return $page_info;
	}

	/**
	 * Combine user params with known params and fill in defaults when needed.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  array   $pairs  Entire list of supported params and their defaults.
	 * @param  array   $params User defined params.
	 * @return array   $out    Combined and filtered params array.
	*/
	private function safe_merge_params( $pairs, $params ) {
		$params = (array) $params;
		$out = array();
		
    	foreach ( $pairs as $name => $default ) {
        	if ( array_key_exists( $name, $params ) ) {
				$out[ $name ] = $params[ $name ];
			} else {
				$out[ $name ] = $default;
			}

			if ( empty( $out[ $name ] ) ) {
				unset( $out[ $name ] );
			}
		}
		
		return $out;
	}

	/**
	 * Build error object.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string  $message Error message.
	 * @return object           Error object.
	*/
	private function get_error( $message ) {
		$obj = new stdClass();
		$obj->error = 1;
		$obj->error_message = $message;

		return $obj;
	}
	
}
