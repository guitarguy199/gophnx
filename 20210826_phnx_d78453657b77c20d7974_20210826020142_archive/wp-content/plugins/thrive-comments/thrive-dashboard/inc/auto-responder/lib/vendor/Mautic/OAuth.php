<?php

/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

/**
 * OAuth Client modified from https://code.google.com/p/simple-php-oauth/
 */
class Thrive_Dash_Api_Mautic_OAuth extends Thrive_Dash_Api_Mautic_ApiAuth implements Thrive_Dash_Api_Mautic_AuthInterface {

	/**
	 * Consumer or client key
	 *
	 * @var string
	 */
	protected $_client_id;

	/**
	 * Consumer or client secret
	 *
	 * @var string
	 */
	protected $_client_secret;

	/**
	 * Callback or Redirect URL
	 *
	 * @var string
	 */
	protected $_callback;

	/**
	 * Access token returned by OAuth server
	 *
	 * @var string
	 */
	protected $_access_token;

	/**
	 * Access token secret returned by OAuth server
	 *
	 * @var string
	 */
	protected $_access_token_secret;

	/**
	 * Unix timestamp for when token expires
	 *
	 * @var string
	 */
	protected $_expires;

	/**
	 * OAuth2 refresh token
	 *
	 * @var string
	 */
	protected $_refresh_token;

	/**
	 * OAuth2 token type
	 *
	 * @var string
	 */
	protected $_token_type;

	/**
	 * Set to true if a refresh token was used to update an access token
	 *
	 * @var bool
	 */
	protected $_access_token_updated = false;

	/**
	 * OAuth2 redirect type
	 *
	 * @var string
	 */
	protected $_redirect_type = 'code';

	/**
	 * OAuth2 scope
	 *
	 * @var string
	 */
	protected $_scope = array();

	/**
	 * Authorize URL
	 *
	 * @var string
	 */
	protected $_authorize_url;

	/**
	 * Access token URL
	 *
	 * @var string
	 */
	protected $_access_token_url;

	/**
	 * Request token URL for OAuth1
	 *
	 * @var string
	 */
	protected $_request_token_url;

	/**
	 * If set to true, $_SESSION['debug'] will be populated
	 *
	 * @var bool
	 */
	protected $_debug = false;

	/**
	 * @param string $baseUrl URL of the Mautic instance
	 * @param string $version ['OAuth1a', ''OAuth2'']. 'OAuth2' is default value
	 * @param string $clientKey
	 * @param string $clientSecret
	 * @param string $accessToken
	 * @param string $accessTokenSecret
	 * @param string $accessTokenExpires
	 * @param string $callback
	 * @param string $scope
	 * @param string $refreshToken
	 */
	public function setup(
		$baseUrl = null,
		$version = 'OAuth2',
		$clientKey = null,
		$clientSecret = null,
		$accessToken = null,
		$accessTokenSecret = null,
		$accessTokenExpires = null,
		$callback = null,
		$scope = null,
		$refreshToken = null
	) {
		$this->_client_id           = $clientKey;
		$this->_client_secret       = $clientSecret;
		$this->_access_token        = $accessToken;
		$this->_access_token_secret = $accessTokenSecret;
		$this->_callback            = $callback;

		if ( $baseUrl ) {
			if ( $version == 'OAuth1a' ) {
				if ( ! $this->_access_token_url ) {
					$this->_access_token_url = $baseUrl . '/oauth/v1/access_token';
				}
				if ( ! $this->_request_token_url ) {
					$this->_request_token_url = $baseUrl . '/oauth/v1/request_token';
				}
				if ( ! $this->_authorize_url ) {
					$this->_authorize_url = $baseUrl . '/oauth/v1/authorize';
				}
			} else {
				if ( ! $this->_access_token_url ) {
					$this->_access_token_url = $baseUrl . '/oauth/v2/token';
				}
				if ( ! $this->_authorize_url ) {
					$this->_authorize_url = $baseUrl . '/oauth/v2/authorize';
				}
			}
		}

		if ( ! empty( $scope ) ) {
			$this->setScope( $scope );
		}

		if ( ! empty( $accessToken ) ) {
			$this->setAccessTokenDetails(
				array(
					'access_token'        => $accessToken,
					'access_token_secret' => $accessTokenSecret,
					'expires'             => $accessTokenExpires,
					'refresh_token'       => $refreshToken,
				)
			);
		}
	}

	/**
	 * Set authorization URL
	 *
	 * @param $url
	 *
	 * @return $this
	 */
	public function setAuthorizeUrl( $url ) {
		$this->_authorize_url = $url;

		return $this;
	}

	/**
	 * Set request token URL
	 *
	 * @param $url
	 *
	 * @return $this
	 */
	public function setRequestTokenUrl( $url ) {
		$this->_request_token_url = $url;

		return $this;
	}

	/**
	 * Set access token URL
	 *
	 * @param $url
	 *
	 * @return $this
	 */
	public function setAccessTokenUrl( $url ) {
		$this->_access_token_url = $url;

		return $this;
	}

	/**
	 * Set redirect type for OAuth2
	 *
	 * @param $type
	 *
	 * @return $this
	 */
	public function setRedirectType( $type ) {
		$this->_redirect_type = $type;

		return $this;
	}

	/**
	 * Set OAuth2 scope
	 *
	 * @param array|string $scope
	 *
	 * @return $this
	 */
	public function setScope( $scope ) {
		if ( ! is_array( $scope ) ) {
			$this->_scope = explode( ',', $scope );
		} else {
			$this->_scope = $scope;
		}

		return $this;
	}

	/**
	 * Set an existing/already retrieved access token
	 *
	 * @param array $accessTokenDetails
	 *
	 * @return $this
	 */
	public function setAccessTokenDetails( array $accessTokenDetails ) {
		$this->_access_token        = isset( $accessTokenDetails['access_token'] ) ? $accessTokenDetails['access_token'] : null;
		$this->_access_token_secret = isset( $accessTokenDetails['access_token_secret'] ) ? $accessTokenDetails['access_token_secret'] : null;
		$this->_expires             = isset( $accessTokenDetails['expires'] ) ? $accessTokenDetails['expires'] : null;
		$this->_refresh_token       = isset( $accessTokenDetails['refresh_token'] ) ? $accessTokenDetails['refresh_token'] : null;

		return $this;
	}

	/**
	 * Returns access token data
	 *
	 * @return array
	 */
	public function getAccessTokenData() {
		if ( $this->isOauth1() ) {
			return array(
				'access_token'        => $this->_access_token,
				'access_token_secret' => $this->_access_token_secret,
				'expires'             => $this->_expires,
			);
		}

		return array(
			'access_token'  => $this->_access_token,
			'expires'       => $this->_expires,
			'token_type'    => $this->_token_type,
			'refresh_token' => $this->_refresh_token,
		);
	}

	/**
	 * Enables debug mode
	 *
	 * @return $this
	 */
	public function enableDebugMode() {
		$this->_debug = true;

		return $this;
	}

	/**
	 * Check to see if the access token was updated from a refresh token
	 *
	 * @return bool
	 */
	public function accessTokenUpdated() {
		return $this->_access_token_updated;
	}

	/**
	 * Returns $_SESSION['oauth']['debug'] if $this->_debug = true
	 *
	 * @return array
	 */
	public function getDebugInfo() {
		return ( $this->_debug && ! empty( $_SESSION['oauth']['debug'] ) ) ? $_SESSION['oauth']['debug'] : array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function isAuthorized() {
		//Check for existing access token
		if ( ! empty( $this->_request_token_url ) ) {
			if ( strlen( $this->_access_token ) > 0 && strlen( $this->_access_token_secret ) > 0 ) {
				return true;
			}
		}

		//Check to see if token in session has expired
		if ( ! empty( $this->_expires ) && $this->_expires < time() ) {
			return false;
		}

		if ( strlen( $this->_access_token ) > 0 ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate existing access token
	 *
	 * @return bool
	 */
	public function validateAccessToken() {
		$this->log( 'validateAccessToken()' );

		//Check to see if token in session has expired
		if ( ! empty( $this->_expires ) && $this->_expires < time() ) {
			$this->log( 'access token expired so reauthorize' );

			if ( strlen( $this->_refresh_token ) > 0 ) {
				//use a refresh token to get a new token
				return $this->requestAccessToken();
			}

			//Reauthorize
			return $this->authorize( $this->_scope );
		}

		//Check for existing access token
		if ( strlen( $this->_access_token ) > 0 ) {
			$this->log( 'has access token' );

			return true;
		}

		//Reauthorize if no token was found
		if ( strlen( $this->_access_token ) == 0 ) {
			$this->log( 'access token empty so authorize' );

			//OAuth flows
			if ( $this->isOauth1() ) {
				//OAuth 1.0
				$this->log( 'authorizing with OAuth1.0a spec' );

				//Request token and authorize app
				if ( ! isset( $_GET['oauth_token'] ) && ! isset( $_GET['oauth_verifier'] ) ) {
					$this->log( 'initializing authorization' );

					//Request token
					$this->requestToken();

					//Authorize token
					return $this->authorize();
				}

				//Request access token
				if ( $_GET['oauth_token'] != get_option( 'tvd_mautic_token' ) ) {
					delete_option( 'tvd_mautic_token' );
					delete_option( 'tvd_mautic_token_secret' );

					return false;
				}

				$_SESSION['oauth']['token']        = get_option( 'tvd_mautic_token' );
				$_SESSION['oauth']['token_secret'] = get_option( 'tvd_mautic_token_secret' );

				$this->requestAccessToken();
				delete_option( 'tvd_mautic_token' );
				delete_option( 'tvd_mautic_token_secret' );

				return true;
			}

			//OAuth 2.0
			$this->log( 'authorizing with OAuth2 spec' );

			//Authorize app
			if ( ! isset( $_GET['state'] ) && ! isset( $_GET['code'] ) ) {

				return $this->authorize( $this->_scope );
			}

			if ( $this->_debug ) {
				$_SESSION['oauth']['debug']['received_state'] = $_GET['state'];
			}

			//Request an access token
			if ( $_GET['state'] != get_option( 'tvd_mautic_state' ) ) {
				delete_option( 'tvd_mautic_state' );

				return false;
			}

			$_SESSION['oauth']['state'] = get_option( 'tvd_mautic_state' );
			delete_option( 'tvd_mautic_state' );
			$this->requestAccessToken( 'POST', array(), 'json' );

			return true;
		}
	}

	/**
	 * Request token for OAuth1
	 *
	 * @param string $responseType
	 *
	 * @throws Thrive_Dash_Api_Mautic_IncorrectParametersReturnedException
	 */
	protected function requestToken( $responseType = 'flat' ) {
		$this->log( 'requestToken()' );

		//Make the request
		$settings = array(
			'responseType'    => $responseType,
			'includeCallback' => true,
			'includeVerifier' => false,
		);

		$params = $this->makeRequest( $this->_request_token_url, array(), 'POST', $settings );

		//Add token and secret to the session
		if ( is_array( $params ) && isset( $params['oauth_token'] ) && isset( $params['oauth_token_secret'] ) ) {

			$this->log( 'token set as ' . $params['oauth_token'] );
			update_option( 'tvd_mautic_token', $params['oauth_token'] );
			update_option( 'tvd_mautic_token_secret', $params['oauth_token_secret'] );
			$_SESSION['oauth']['token']        = $params['oauth_token'];
			$_SESSION['oauth']['token_secret'] = $params['oauth_token_secret'];

			if ( $this->_debug ) {
				$_SESSION['oauth']['debug']['token']        = $params['oauth_token'];
				$_SESSION['oauth']['debug']['token_secret'] = $params['oauth_token_secret'];
			}
		} else {
			//Throw exception if the required parameters were not found
			$this->log( 'request did not return oauth tokens' );

			if ( $this->_debug ) {
				$_SESSION['oauth']['debug']['response'] = $params;
			}

			if ( is_array( $params ) ) {
				if ( isset( $params['error'] ) ) {
					$response = $params['error'];
					if ( is_array( $response ) && isset( $response['message'] ) ) {
						$response = $response['message'];
					}
				} else {
					$response = '???';
				}
			} else {
				$response = $params;
			}

			throw new Thrive_Dash_Api_Mautic_IncorrectParametersReturnedException( 'Incorrect access token parameters returned: ' . $response );
		}
	}

	/**
	 * Request access token
	 *
	 * @param string $method
	 * @param array  $params
	 * @param string $responseType
	 *
	 * @return bool
	 * @throws Thrive_Dash_Api_Mautic_IncorrectParametersReturnedException
	 */
	protected function requestAccessToken( $method = 'POST', array $params = array(), $responseType = 'flat' ) {
		$this->log( 'requestAccessToken()' );

		//Set OAuth flow parameters
		if ( $this->isOauth1() ) {
			//OAuth 1.0
			$this->log( 'using OAuth1.0a spec' );

			$parameters = array( 'oauth_verifier' => $_GET['oauth_verifier'] );
			$parameters = array_merge( $parameters, $params );
		} else {
			//OAuth 2.0
			$this->log( 'using OAuth2 spec' );

			$parameters = array(
				'client_id'     => $this->_client_id,
				'redirect_uri'  => $this->_callback,
				'client_secret' => $this->_client_secret,
				'grant_type'    => 'authorization_code',
			);

			if ( isset( $_GET['code'] ) ) {
				$parameters['code'] = $_GET['code'];
			}

			if ( strlen( $this->_refresh_token ) > 0 ) {
				$this->log( 'Using refresh token' );
				$parameters['grant_type']    = 'refresh_token';
				$parameters['refresh_token'] = $this->_refresh_token;
			}

			$parameters = array_merge( $parameters, $params );
		}

		//Make the request
		$settings = array(
			'responseType'    => $responseType,
			'includeCallback' => true,
			'includeVerifier' => true,
		);

		$params = $this->makeRequest( $this->_access_token_url, $parameters, $method, $settings );

		//Add the token and secret to session
		if ( is_array( $params ) ) {
			if ( $this->isOauth1() ) {
				//OAuth 1.0a
				if ( isset( $params['oauth_token'] ) && isset( $params['oauth_token_secret'] ) ) {
					$this->log( 'access token set as ' . $params['oauth_token'] );

					$this->_access_token         = $params['oauth_token'];
					$this->_access_token_secret  = $params['oauth_token_secret'];
					$this->_access_token_updated = true;

					if ( $this->_debug ) {
						$_SESSION['oauth']['debug']['tokens']['access_token']        = $params['oauth_token'];
						$_SESSION['oauth']['debug']['tokens']['access_token_secret'] = $params['oauth_token_secret'];
					}

					return true;
				}
			} else {
				//OAuth 2.0
				if ( isset( $params['access_token'] ) && isset( $params['expires_in'] ) ) {
					$this->log( 'access token set as ' . $params['access_token'] );

					$this->_access_token         = $params['access_token'];
					$this->_expires              = time() + $params['expires_in'];
					$this->_token_type           = ( isset( $params['token_type'] ) ) ? $params['token_type'] : null;
					$this->_refresh_token        = ( isset( $params['refresh_token'] ) ) ? $params['refresh_token'] : null;
					$this->_access_token_updated = true;

					if ( $this->_debug ) {
						$_SESSION['oauth']['debug']['tokens']['access_token']  = $params['access_token'];
						$_SESSION['oauth']['debug']['tokens']['expires_in']    = $params['expires_in'];
						$_SESSION['oauth']['debug']['tokens']['token_type']    = $params['token_type'];
						$_SESSION['oauth']['debug']['tokens']['refresh_token'] = $params['refresh_token'];
					}

					return true;
				}
			}
		}

		$this->log( 'response did not have an access token' );

		if ( $this->_debug ) {
			$_SESSION['oauth']['debug']['response'] = $params;
		}

		if ( is_array( $params ) ) {
			if ( isset( $params['errors'] ) ) {
				$errors = array();
				foreach ( $params['errors'] as $error ) {
					$errors[] = $error['message'];
				}
				$response = implode( "; ", $errors );
			} elseif ( isset( $params['error'] ) ) {
				// @deprecated support for pre Mautic 2.6.0
				if ( is_array( $params['error'] ) ) {
					if ( isset( $params['error']['message'] ) ) {
						$response = $params['error']['message'];
					} else {
						$response = print_r( $params['error'], true );
					}
				} elseif ( isset( $params['error_description'] ) ) {
					$response = $params['error_description'];
				} else {
					$response = $params['error'];
				}
			} else {
				$response = print_r( $params, true );
			}
		} else {
			$response = $params;
		}

		throw new Thrive_Dash_Api_Mautic_IncorrectParametersReturnedException( 'Incorrect access token parameters returned: ' . $response );
	}

	/**
	 * Authorize app
	 *
	 * @param array  $scope
	 * @param string $scope_separator
	 * @param string $attach
	 *
	 * @return string $authUrl
	 */
	protected function authorize( array $scope = array(), $scope_separator = ',', $attach = null ) {
		$authUrl = $this->_authorize_url;

		//Build authorization URL
		if ( $this->isOauth1() ) {
			//OAuth 1.0
			$authUrl .= '?oauth_token=' . $_SESSION['oauth']['token'];

			if ( ! empty( $this->_callback ) ) {
				$authUrl .= '&oauth_callback=' . urlencode( $this->_callback );
			}

		} else {
			//OAuth 2.0
			$authUrl .= '?client_id=' . $this->_client_id . '&redirect_uri=' . urlencode( $this->_callback );
			$state   = md5( time() . mt_rand() );

			update_option( 'tvd_mautic_state', $state );
			$_SESSION['oauth']['state'] = $state;
			if ( $this->_debug ) {
				$_SESSION['oauth']['debug']['generated_state'] = $state;
			}

			$authUrl .= '&state=' . $state . '&scope=' . implode( $scope_separator, $scope ) . $attach;
			$authUrl .= '&response_type=' . $this->_redirect_type;
		}

		$this->log( 'redirecting to auth url ' . $authUrl );

		return $authUrl;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws Thrive_Dash_Api_Mautic_UnexpectedResponseFormatException
	 */
	public function makeRequest( $url, array $parameters = array(), $method = 'GET', array $settings = array() ) {
//		$this->log( 'makeRequest(' . $url . ', ' . http_build_query( $parameters ) . ', ' . $method . ',...)' );

		list( $url, $parameters ) = $this->separateUrlParams( $url, $parameters );

		$includeCallback = ( isset( $settings['includeCallback'] ) ) ? $settings['includeCallback'] : false;
		$includeVerifier = ( isset( $settings['includeVerifier'] ) ) ? $settings['includeVerifier'] : false;

		//make sure $method is capitalized for congruency
		$method = strtoupper( $method );

		//Set OAuth parameters/headers
		if ( $this->isOauth1() ) {
			//OAuth 1.0
			$this->log( 'making request using OAuth1.0a spec' );

			//Get standard OAuth headers
			$headers = $this->getOauthHeaders( $includeCallback );

			if ( $includeVerifier && isset( $_GET['oauth_verifier'] ) ) {
				$headers['oauth_verifier'] = $_GET['oauth_verifier'];

				if ( $this->_debug ) {
					$_SESSION['oauth']['debug']['oauth_verifier'] = $_GET['oauth_verifier'];
				}
			}

			//Add the parameters
			$headers                    = array_merge( $headers, $parameters );
			$base_info                  = $this->buildBaseString( $url, $method, $headers );
			$composite_key              = $this->getCompositeKey();
			$headers['oauth_signature'] = base64_encode( hash_hmac( 'sha1', $base_info, $composite_key, true ) );
			$header                     = array(
				"Authorization" => $this->buildAuthorizationHeader( $headers ),
				"Expect"        => '',
			);

			if ( $this->_debug ) {
				$_SESSION['oauth']['debug']['basestring'] = $base_info;
				$_SESSION['oauth']['debug']['headers']    = $headers;
			}
		} else {
			//OAuth 2.0
			$this->log( 'making request using OAuth2 spec' );

			$parameters['access_token'] = $this->_access_token;
		}

		//Create a querystring for GET/DELETE requests
		if ( count( $parameters ) > 0 && in_array( $method, array( 'GET', 'DELETE' ) ) && strpos( $url, '?' ) === false ) {
			$url = $url . '?' . http_build_query( $parameters );
			$this->log( 'URL updated to ' . $url );
		}

		$args = array(
			'headers' => isset( $header ) ? $header : '',
			'body'    => http_build_query( $parameters ),
		);

		switch ( $method ) {
			case 'POST':
				$result = tve_dash_api_remote_post( $url, $args );
				break;
			case 'GET':
			default:
				$url .= ( strpos( $url, '?' ) !== false ? '&' : '?' ) . http_build_query( $parameters );


				$get_args = array(
					'headers' => isset( $header ) ? $header : '',
				);
				$result   = tve_dash_api_remote_get( $url, $get_args );

				break;
		}

		if ( $result instanceof WP_Error ) {
			throw new Thrive_Dash_Api_Mautic_UnexpectedResponseFormatException( $result->get_error_message() );
		}

		$body = $result['body'];

		if ( is_string( $body ) && ! is_array( json_decode( $body, true ) ) ) {
			parse_str( $result['body'], $body );

		}

		$the_headers = array();
		if ( is_object( $result['headers'] ) ) {
			foreach ( $result['headers'] as $k => $v ) {
				$the_headers[ $k ] = $v;
			}
		} else {
			$the_headers = $result['headers'];
		}
		$header = implode( "\r\n\r\n", $the_headers );

		if ( $this->_debug ) {
//			$_SESSION['oauth']['debug']['info']            = $info;
			$_SESSION['oauth']['debug']['returnedHeaders'] = $header;
			$_SESSION['oauth']['debug']['returnedBody']    = $body;
		}

		$responseGood = false;

		//Check to see if the response is JSON
		$parsed = ! is_array( $body ) ? json_decode( $body, true ) : $body;

		if ( $parsed === null ) {
			if ( strpos( $body, '=' ) !== false ) {
				parse_str( $body, $parsed );
				$responseGood = true;
			}
		} else {
			$responseGood = true;
		}

		//Show error when http_code is not appropriate
		if ( ! in_array( $result['response'], array( 200, 201 ) ) ) {
			if ( $responseGood ) {
				return $parsed;
			}

			throw new Thrive_Dash_Api_Mautic_UnexpectedResponseFormatException( $body );
		}

		return ( $responseGood ) ? $parsed : $body;
	}

	/**
	 * Get composite key for OAuth 1 signature signing
	 *
	 * @return string
	 */
	private function getCompositeKey() {
		if ( isset( $this->_access_token_secret ) && strlen( $this->_access_token_secret ) > 0 ) {
			$composite_key = $this->encode( $this->_client_secret ) . '&' . $this->encode( $this->_access_token_secret );
		} elseif ( isset( $_SESSION['oauth']['token_secret'] ) ) {
			$composite_key = $this->encode( $this->_client_secret ) . '&' . $this->encode( $_SESSION['oauth']['token_secret'] );
		} else {
			$composite_key = $this->encode( $this->_client_secret ) . '&';
		}

		return $composite_key;
	}

	/**
	 * Get OAuth 1.0 Headers
	 *
	 * @param bool $includeCallback
	 *
	 * @return array
	 */
	private function getOauthHeaders( $includeCallback = false ) {
		$oauth = array(
			'oauth_consumer_key'     => $this->_client_id,
			'oauth_nonce'            => $this->generateNonce(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp'        => time(),
			'oauth_version'          => '1.0',
		);

		if ( isset( $this->_access_token ) ) {
			$oauth['oauth_token'] = $this->_access_token;
		} elseif ( isset( $_SESSION['oauth']['token'] ) ) {
			$oauth['oauth_token'] = $_SESSION['oauth']['token'];
		}

		if ( $includeCallback ) {
			$oauth['oauth_callback'] = $this->_callback;
		}

		return $oauth;
	}

	/**
	 * Build base string for OAuth 1 signature signing
	 *
	 * @param string $baseURI
	 * @param string $method
	 * @param array  $params
	 *
	 * @return string
	 */
	private function buildBaseString( $baseURI, $method, $params ) {
		$r = $this->normalizeParameters( $params, true );

		return $method . '&' . $this->encode( $baseURI ) . '&' . $this->encode( $r );
	}

	/**
	 * Build header for OAuth 1 authorization
	 *
	 * @param $oauth
	 *
	 * @return string
	 */
	private function buildAuthorizationHeader( $oauth ) {
		$r      = 'OAuth ';
		$values = $this->normalizeParameters( $oauth, true, true );
		$r      .= implode( ', ', $values );

		return $r;
	}

	/**
	 * Normalize parameters
	 *
	 * @param array  $parameters
	 * @param bool   $encode
	 * @param bool   $returnarray
	 * @param array  $normalized
	 * @param string $key
	 *
	 * @return string
	 */
	private function normalizeParameters( $parameters, $encode = false, $returnarray = false, $normalized = array(), $key = '' ) {
		// December 2016 - Fix for issue #75
		//
		// recursive call identified by these 2 conditions.
		if ( $returnarray && ( '' != $key ) ) {
			// Ref: Spec: 9.1.1 (1)
			// If two or more parameters share the same name, they are sorted by their value
			sort( $parameters, SORT_STRING );
		} else {
			// Sort by key
			ksort( $parameters );
		}
		foreach ( $parameters as $k => $v ) {
			if ( is_array( $v ) ) {
				$normalized = $this->normalizeParameters( $v, $encode, true, $normalized, $k );
			} else {
				if ( $key ) {
					//Multidimensional array; using foo=baz&foo=bar rather than foo[bar]=baz&foo[baz]=bar as this is
					//what the server expects when creating the signature
					$k = $key;
				}
				if ( $encode ) {
					$normalized[] = $this->encode( $k ) . '=' . $this->encode( $v );
				} else {
					$normalized[] = $k . '=' . $v;
				}
			}
		}

		return $returnarray ? $normalized : implode( '&', $normalized );
	}

	/**
	 * Returns an encoded string according to the RFC3986.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function encode( $string ) {
		return str_replace( '%7E', '~', rawurlencode( $string ) );
	}

	/**
	 * OAuth1.0 nonce generator
	 *
	 * @param int $bits
	 *
	 * @return string
	 */
	private function generateNonce( $bits = 64 ) {
		$result          = '';
		$accumulatedBits = 0;
		$random          = mt_getrandmax();

		for ( $totalBits = 0; $random != 0; $random >>= 1 ) {
			++ $totalBits;
		}

		$usableBits = intval( $totalBits / 8 ) * 8;

		while ( $accumulatedBits < $bits ) {
			$bitsToAdd = min( $totalBits - $usableBits, $bits - $accumulatedBits );
			if ( $bitsToAdd % 4 != 0 ) {
				// add bits in whole increments of 4
				$bitsToAdd += 4 - $bitsToAdd % 4;
			}

			// isolate leftmost $bits_to_add from mt_rand() result
			$moreBits = mt_rand() & ( ( 1 << $bitsToAdd ) - 1 );

			// format as hex (this will be safe)
			$format_string   = '%0' . ( $bitsToAdd / 4 ) . 'x';
			$result          .= sprintf( $format_string, $moreBits );
			$accumulatedBits += $bitsToAdd;
		}

		return $result;
	}

	/**
	 * @param string $message
	 */
	protected function log( $message ) {
		if ( $this->_debug ) {
			$_SESSION['oauth']['debug']['flow'][ date( 'm-d H:i:s' ) ][] = $message;
		}
	}

	/**
	 * @return bool
	 */
	protected function isOauth1() {
		return strlen( $this->_request_token_url ) > 0;
	}

	/**
	 * Separates parameters from base URL
	 *
	 * @return array
	 */
	protected function separateUrlParams( $url, $params ) {
		$a = parse_url( $url );

		if ( ! empty( $a['query'] ) ) {
			parse_str( $a['query'], $qparts );
			foreach ( $qparts as $k => $v ) {
				$cleanParams[ $k ] = $v ? $v : '';
			}
			$params = array_merge( $params, $cleanParams );
			$url    = explode( '?', $url, 2 )[0];
		}

		return array( $url, $params );
	}
}
