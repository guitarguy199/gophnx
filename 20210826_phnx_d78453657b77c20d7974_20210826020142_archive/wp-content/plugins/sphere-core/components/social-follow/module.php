<?php
namespace Sphere\Core\SocialFollow;

/**
 * Social followers counter for several services
 */
class Module
{
	/**
	 * The settings related to this plugin
	 * @var array
	 */
	public $options;
	
	/**
	 * Timeout for remote connections
	 * @var integer
	 */
	public $timeout = 10;
	
	/**
	 * Constructor called at hook: bunyad_core_pre_init
	 */
	public function __construct()
	{
		// Add relevant options
		add_filter('bunyad_theme_options', [$this, 'add_theme_options']);
		
		// Flush cache on options save
		add_action('bunyad_options_saved', [$this, 'flush_cache']);
		add_action('customize_save', [$this, 'flush_cache']);
		
		// Initialize after bunyad frameowrk has run core setup
		add_action('after_setup_theme', [$this, 'init'], 12);
		
		define('SPHERE_SF_DIR', plugin_dir_path(__FILE__));
	}
	
	/**
	 * Initialize and setup settings
	 */
	public function init()
	{
		if (class_exists('\Bunyad')) {
			$this->options = \Bunyad::options()->get_all('sf_');
		}
		
			
		if (!is_admin()) {
			// DEBUG:
			//echo $this->count('facebook');
			//echo $this->count('gplus');
			//echo $this->count('youtube');
			//echo $this->count('vimeo');
			//echo $this->count('twitter');
			//echo $this->count('instagram');
			//echo $this->count('pinterest');
			//exit;
		}
	}
	
	/**
	 * Add to theme options array
	 * 
	 * @param  array $options
	 * @return array
	 */
	public function add_theme_options($options) 
	{
		$doc_link = apply_filters('sphere_theme_docs_url', 'https://theme-sphere.com/smart-mag/documentation/') . '#social-follow';
		
		$extra_options = [
			'title'    => __('Social Followers', 'bunyad'),
			'id'       => 'sphere-social-followers',
			'priority' => 40,
			'sections' => [
				'general' => [
					'title'  => __('General', 'bunyad'),
					'fields' => [
						'sf_counters' => [
							'name' 	  => 'sf_counters',
							'label'   => __('Enable Follower Counters?', 'bunyad'),
							'value'   => 1,
							'desc'    => __('If follower counters/numbers are enabled, refer to <a href="'. esc_url($doc_link) .'" target="_blank">documentation</a> to learn how to set it up.', 'bunyad'),
							'type'    => 'checkbox',
						],
					]
				],

				'facebook' => [
					'title'  => 'Facebook',
					'desc'   => __('If follower counters/numbers are enabled, refer to <a href="'. esc_url($doc_link) .'"  target="_blank">documentation</a> to learn how to set it up.', 'bunyad'),
					'fields' => [
						[
							'name' 	  => 'sf_facebook_id',
							'label'   => __('Page Name / ID', 'bunyad'),
							'value'   => '',
							'desc'    => __('If your page URL is https://facebook.com/themesphere enter themesphere as the id here.', 'bunyad'),
							'type'    => 'text',
						],
							
						'sf_facebook_label' => [
							'name' 	  => 'sf_facebook_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => __('Facebook', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_facebook_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('This will force this number to be used as counter. Useful if cannot use API.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
						// array(
						// 	'name' 	  => 'sf_facebook_app',
						// 	'label'   => __('App ID', 'bunyad'),
						// 	'value'   => '',
						// 	'desc'    => '',
						// 	'type'    => 'text',
						// ),
							
						// array(
						// 	'name' 	  => 'sf_facebook_secret',
						// 	'label'   => __('App Secret', 'bunyad'),
						// 	'value'   => '',
						// 	'desc'    => '',
						// 	'type'    => 'text',
						// ),
					]
				],
					
				// 'gplus' => array(
				// 	'title'  => 'Google Plus',
				// 	'desc'   => __('If follower counters/numbers are enabled, refer to <a href="'. esc_url($doc_link) .'" target="_blank">documentation</a> to learn how to set it up.', 'bunyad'),
				// 	'fields' => array(
				// 		array(
				// 			'name' 	  => 'sf_gplus_id',
				// 			'label'   => __('Page Name / ID', 'bunyad'),
				// 			'value'   => '',
				// 			'desc'    => __('If your page URL is https://plus.google.com/+themesphere enter +themesphere as the id here.', 'bunyad'),
				// 			'type'    => 'text',
				// 		),
							
				// 		'sf_gplus_label' => array(
				// 			'name' 	  => 'sf_gplus_label',
				// 			'label'   => __('Button Label', 'bunyad'),
				// 			'value'   => __('Follow on Google+', 'bunyad'),
				// 			'desc'    => __('The text to use on the widget.', 'bunyad'),
				// 			'type'    => 'text',
				// 		),
							
				// 		array(
				// 			'name' 	  => 'sf_gplus_key',
				// 			'label'   => __('Google API Key', 'bunyad'),
				// 			'value'   => '',
				// 			'desc'    => '',
				// 			'type'    => 'text',
				// 		),
				// 	)
				// ),

				'youtube' => [
					'title'  => 'YouTube',
					'desc'   => __('If follower counters/numbers are enabled, refer to <a href="'. esc_url($doc_link) .'" target="_blank">documentation</a> to learn how to set it up.', 'bunyad'),
					'fields' => [
						[
							'name' 	  => 'sf_youtube_id',
							'label'   => __('Channel ID', 'bunyad'),
							'value'   => '',
							'desc'    => __('You can get the id from <a href="https://www.youtube.com/account_advanced" target="_blank">https://www.youtube.com/account_advanced</a>.', 'bunyad'),
							'type'    => 'text',
						],
							
						'sf_youtube_label' => [
							'name' 	  => 'sf_youtube_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => __('YouTube', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_youtube_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('This will force this number to be used as counter. Useful if cannot use API.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
							
						[
							'name' 	  => 'sf_youtube_url',
							'label'   => __('Channel URL', 'bunyad'),
							'value'   => '',
							'desc'    => __('Full link to your YouTube channel.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_youtube_key',
							'label'   => __('Google API Key', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
					]
				],
					
				'vimeo' => [
					'title'  => 'Vimeo',
					'fields' => [
						[
							'name' 	  => 'sf_vimeo_id',
							'label'   => __('Vimeo Username / Channel', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_vimeo_url',
							'label'   => __('Vimeo URL', 'bunyad'),
							'value'   => '',
							'desc'    => __('Full link to your Vimeo channel or profile.', 'bunyad'),
							'type'    => 'text',
						],
							
						'sf_vimeo_label' => [
							'name' 	  => 'sf_vimeo_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => __('Vimeo', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_vimeo_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('This will force this number to be used as counter. Useful if cannot use API.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],

						[
							'name' 	  => 'sf_vimeo_type',
							'label'   => __('Channel or User?', 'bunyad'),
							'value'   => 'user',
							'desc'    => '',
							'type'    => 'select',
							'options' => [
								'user'    => __('User', 'bunyad'),
								'channel' => __('Channel', 'bunyad')
							]
						],
					]
				],
					
				'twitter' => [
					'title'  => 'Twitter',
					'desc'   => __('If follower counters/numbers are enabled, refer to <a href="'. esc_url($doc_link) .'" target="_blank">documentation</a> to learn how to set it up.', 'bunyad'),
					'fields' => [
							
						[
							'name' 	  => 'sf_twitter_id',
							'label'   => __('Twitter Username', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_twitter_label' => [
							'name' 	  => 'sf_twitter_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => __('Twitter', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_twitter_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('This will force this number to be used as counter. Useful if cannot use API.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
							
						[
							'name' 	  => 'sf_twitter_key',
							'label'   => __('Consumer Key', 'bunyad'),
							'value'   => '',
							'desc'    => __('', 'bunyad'),
							'type'    => 'text',
						],
							
						[
							'name' 	  => 'sf_twitter_secret',
							'label'   => __('Consumer Secret', 'bunyad'),
							'value'   => '',
							'desc'    => __('', 'bunyad'),
							'type'    => 'text',
						],
							
						[
							'name' 	  => 'sf_twitter_token',
							'label'   => __('Access Token', 'bunyad'),
							'value'   => '',
							'desc'    => __('', 'bunyad'),
							'type'    => 'text',
						],
							
						[
							'name' 	  => 'sf_twitter_token_secret',
							'label'   => __('Access Token Secret', 'bunyad'),
							'value'   => '',
							'desc'    => __('', 'bunyad'),
							'type'    => 'text',
						],
					]
				],
					
				'instagram' => [
					'title'  => 'Instagram',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_instagram_id',
							'label'   => __('Instagram Username', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_instagram_label' => [
							'name' 	  => 'sf_instagram_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('Instagram', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_instagram_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('This will force this number to be used as counter. Useful if cannot use API.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],
					
				'pinterest' => [
					'title'  => 'Pinterest',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_pinterest_id',
							'label'   => __('Pinterest Username', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_pinterest_label' => [
							'name' 	  => 'sf_pinterest_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('Pinterest', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_pinterest_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('This will force this number to be used as counter. Useful if cannot use API.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'linkedin' => [
					'title'  => 'LinkedIn',
					'desc'   => '',
					'fields' => [
						// [
						// 	'name' 	  => 'sf_linkedin_id',
						// 	'label'   => __('LinkedIn ID', 'bunyad'),
						// 	'value'   => '',
						// 	'desc'    => '',
						// 	'type'    => 'text',
						// ],

						[
							'name' 	  => 'sf_linkedin_url',
							'label'   => __('LinkedIn URL', 'bunyad'),
							'value'   => '',
							'desc'    => __('Full link to your LinkedIn company or profile.', 'bunyad'),
							'type'    => 'text',
						],
							
						'sf_linkedin_label' => [
							'name' 	  => 'sf_linkedin_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('LinkedIn', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_linkedin_type',
							'label'   => __('Company or Profile?', 'bunyad'),
							'value'   => 'company',
							'desc'    => '',
							'type'    => 'select',
							'options' => [
								'company' => __('Company', 'bunyad'),
								'profile' => __('Profile', 'bunyad'),
							]
						],

						[
							'name' 	  => 'sf_linkedin_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for linkedin. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'linkedin' => [
					'title'  => 'LinkedIn',
					'desc'   => '',
					'fields' => [
						// [
						// 	'name' 	  => 'sf_linkedin_id',
						// 	'label'   => __('LinkedIn ID', 'bunyad'),
						// 	'value'   => '',
						// 	'desc'    => '',
						// 	'type'    => 'text',
						// ],

						[
							'name' 	  => 'sf_linkedin_url',
							'label'   => __('LinkedIn URL', 'bunyad'),
							'value'   => '',
							'desc'    => __('Full link to your LinkedIn company or profile.', 'bunyad'),
							'type'    => 'text',
						],
							
						'sf_linkedin_label' => [
							'name' 	  => 'sf_linkedin_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('LinkedIn', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_linkedin_type',
							'label'   => __('Company or Profile?', 'bunyad'),
							'value'   => 'company',
							'desc'    => '',
							'type'    => 'select',
							'options' => [
								'company' => __('Company', 'bunyad'),
								'profile' => __('Profile', 'bunyad'),
							]
						],

						[
							'name' 	  => 'sf_linkedin_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for linkedin. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'soundcloud' => [
					'title'  => 'Soundcloud',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_soundcloud_id',
							'label'   => __('Soundcloud ID', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_soundcloud_label' => [
							'name' 	  => 'sf_soundcloud_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('Soundcloud', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_soundcloud_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for this network. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'twitch' => [
					'title'  => 'Twitch',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_twitch_id',
							'label'   => __('Twitch Channel ID', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_twitch_label' => [
							'name' 	  => 'sf_twitch_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('Twitch', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_twitch_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for this network. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'tiktok' => [
					'title'  => 'TikTok',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_tiktok_id',
							'label'   => __('TikTok User', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_tiktok_label' => [
							'name' 	  => 'sf_tiktok_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('TikTok', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_tiktok_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for this network. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'telegram' => [
					'title'  => 'Telegram',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_telegram_id',
							'label'   => __('Telegram Channel ID', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],
							
						'sf_telegram_label' => [
							'name' 	  => 'sf_telegram_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('Telegram', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_telegram_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for this network. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],

				'whatsapp' => [
					'title'  => 'WhatsApp',
					'desc'   => '',
					'fields' => [
						[
							'name' 	  => 'sf_whatsapp_id',
							'label'   => __('WhatsApp Number', 'bunyad'),
							'value'   => '',
							'desc'    => '',
							'type'    => 'text',
						],

						'sf_whatsapp_label' => [
							'name' 	  => 'sf_whatsapp_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('WhatsApp', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],
					]
				],

				'reddit' => [
					'title'  => 'Reddit',
					'desc'   => '',
					'fields' => [
						// [
						// 	'name' 	  => 'sf_reddit_id',
						// 	'label'   => __('Reddit ID', 'bunyad'),
						// 	'value'   => '',
						// 	'desc'    => '',
						// 	'type'    => 'text',
						// ],

						[
							'name' 	  => 'sf_reddit_url',
							'label'   => __('Reddit URL', 'bunyad'),
							'value'   => '',
							'desc'    => __('Full link to your sub-reddit or user profile.', 'bunyad'),
							'type'    => 'text',
						],
							
						'sf_reddit_label' => [
							'name' 	  => 'sf_reddit_label',
							'label'   => __('Button Label', 'bunyad'),
							'value'   => esc_html__('Reddit', 'bunyad'),
							'desc'    => __('The text to use on the widget.', 'bunyad'),
							'type'    => 'text',
						],

						[
							'name' 	  => 'sf_reddit_type',
							'label'   => __('Sub-Reddit or User', 'bunyad'),
							'value'   => 'sub',
							'desc'    => '',
							'type'    => 'select',
							'options' => [
								'sub' => __('Sub-reddit', 'bunyad'),
								'user' => __('User Profile', 'bunyad'),
							]
						],

						[
							'name' 	  => 'sf_reddit_count',
							'label'   => esc_html__('Manual Count', 'bunyad'),
							'desc'    => esc_html__('Automatic counters are not supported for this network. A manual number is needed.', 'bunyad'),
							'value'   => '',
							'type'    => 'number',
						],
					]
				],
			]
		];
		
		/**
		 * @deprecated 1.1.6 Use sphere/social-follow/options instead.
		 */
		$extra_options = apply_filters('sphere_social_follow_options', $extra_options);

		/**
		 * New filter for the options.
		 * 
		 * @param array $extra_options Customizer section options.
		 */
		$extra_options = apply_filters('sphere/social-follow/options', $extra_options);
		
		$options['options-tab-social-followers'] = $extra_options;
		return $options;
	}
	
	/**
	 * Get share count for a specific service
	 * 
	 * @param string $type The service name
	 */
	public function count($type)
	{
		// Use manually forced counter numbers.
		if (isset($this->options['sf_' . $type . '_count'])) {
			$manual_count = $this->options['sf_' . $type . '_count'];

			if ($manual_count) {
				return $manual_count;
			}
		}

		// Method exists?
		$method = 'get_' . $type;
		if (!method_exists($this, $method)) {
			return 0;
		}

		// Get the cache transient
		$cache = (array) get_transient('sphere_plugin_social_followers');
		$key   = $type;
		$count = isset($cache[$key]) ? $cache[$key] : '';

		if (empty($cache) || !isset($cache[$key])) {
		
			try {
				$latest = call_user_func([$this, $method]);
			} catch (\Exception $e) {
				// don't be verbose about connection errors
			}

			// Only update if latest count is valid or cache is empty
			if ($latest OR empty($cache[$key])) {
				$cache[$key] = $latest;
			}
			
			// Cache the results for a day
			set_transient(
				'sphere_plugin_social_followers', 
				$cache, 
				apply_filters('sphere_plugin_social_followers_cache', DAY_IN_SECONDS)
			);
		}

		return $cache[$key];
	}

	/** 
	 * Remove transient cache
	 */
	public function flush_cache()
	{
		delete_transient('sphere_plugin_social_followers');
	}
	
	/**
	 * Get facebook followers count
	 */
	public function get_facebook()
	{
		if (empty($this->options['sf_facebook_id'])) {
			return false;
		}
	
		$url = 'https://www.facebook.com/v3.2/plugins/page.php?' . http_build_query([
			'href'          => 'https://facebook.com/' . $this->options['sf_facebook_id'],
			'tabs'          => '',
			'show_facepile' => 'false',
			'small_header'  => 'true',
			'locale'        => 'en_US'
		]);
	
		// Get data from API
		$data = $this->remote_get($url);
		if ($data) {
			preg_match('/<\/?(?:[a-z]+)>([\d,\.]+(K|M|<|\s)).*?likes/is', $data, $match);

			if (!empty($match[1])) {

				$string     = strip_tags($match[1]);
				$multiplier = 1;

				if (strstr($string, 'M')) {
					$multiplier = 1000000;
				}
				else if (strstr($string, 'K')) {
					$multiplier = 1000;
				}

				$count  = filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				$count  = abs(intval($count * $multiplier));
			}
		}
		
		return !empty($count) ? $count : 0;
	}
	
	/**
	 * Get Google+ followers count
	 * 
	 * @deprecated 1.1.6
	 */
	public function get_gplus()
	{
		// Options required
		if (empty($this->options['sf_gplus_id']) OR empty($this->options['sf_gplus_key'])) {
			return false;
		}
		
		$url = 'https://www.googleapis.com/plus/v1/people/' . urlencode($this->options['sf_gplus_id']) 
			 . '?key=' . urlencode($this->options['sf_gplus_key']);
		
		// Get data from API
		$data = $this->remote_get($url);
		$data = json_decode($data, true);
		
		return !empty($data['circledByCount']) ? intval($data['circledByCount']) : 0;
	}
	
	/**
	 * Get YouTube followers count
	 */
	public function get_youtube()
	{
		// Options required
		if (empty($this->options['sf_youtube_id']) OR empty($this->options['sf_youtube_key'])) {
			return false;
		}
		
		$url = 'https://www.googleapis.com/youtube/v3/channels?' . http_build_query([
			'part' => 'statistics',
			'id'   => $this->options['sf_youtube_id'],
			'key'  => $this->options['sf_youtube_key']
		]);
		
		// Get data from API
		$data = $this->remote_get($url);
		$data = json_decode($data, true);
		$count = 0;
		
		if (!empty($data['items'][0]['statistics']['subscriberCount'])) {
			$count = $data['items'][0]['statistics']['subscriberCount'];
		}
		
		return intval($count);
	}
	
	/**
	 * Get YouTube followers count
	 */
	public function get_vimeo()
	{
		// Options required
		if (empty($this->options['sf_vimeo_id'])) {
			return false;
		}
		
		$base = 'https://vimeo.com/api/v2/';
		$key  = 'total_contacts';
		
		// Is it a channel?
		$type = !empty($this->options['sf_vimeo_type']) ? $this->options['sf_vimeo_type'] : '';
		if ($type == 'channel') {
			$base = 'https://vimeo.com/api/v2/channel/';
			$key  = 'total_subscribers';
		}
		
		$url = $base . urlencode($this->options['sf_vimeo_id']) .'/info.json';
		
		// Get data from API
		$data = $this->remote_get($url);
		$data = json_decode($data, true);
		
		return !empty($data[$key]) ? $data[$key] : 0;
	}
	
	
	/**
	 * Get Twitter follower count
	 */
	public function get_twitter()
	{
		if (!$this->_check_options(['id', 'key', 'secret', 'token', 'token_secret'], 'sf_twitter_')) {
			return false;
		}
		
		// Twitter API class
		require_once SPHERE_SF_DIR . '../vendor/twitter-api.php';
		
		$settings = [
			'oauth_access_token'        => $this->options['sf_twitter_token'],
			'oauth_access_token_secret' => $this->options['sf_twitter_token_secret'],
			'consumer_key'              => $this->options['sf_twitter_key'],
			'consumer_secret'           => $this->options['sf_twitter_secret']
		];
		
		$url = 'https://api.twitter.com/1.1/users/show.json';
		$twitter = new \TwitterAPIExchange($settings);
		
		// Perform request and get data
		$data = $twitter
					->setGetfield('?screen_name=' . $this->options['sf_twitter_id'])
					->buildOauth($url, 'GET')
					->performRequest();
		
		$data = json_decode($data, true);
		
		return !empty($data['followers_count']) ? $data['followers_count'] : 0;
	}
	
	/**
	 * Get Instagram follower count
	 */
	public function get_instagram()
	{
		if (empty($this->options['sf_instagram_id'])) {
			return false;
		}
		
		// Scrape it from the live site's JSON
		$url   = 'https://www.instagram.com/' . urlencode($this->options['sf_instagram_id']) . '/';
		$data  = $this->remote_get($url);
		$count = 0;

		// Have a match
		if (preg_match('/"edge_followed_by"[^{]+{"count"\:\s?([0-9]+)/', $data, $match)) {
			$count = $match[1];
		}
		
		return intval($count);
	}
	
	/**
	 * Get Pinterest followers
	 */
	public function get_pinterest()
	{
		if (empty($this->options['sf_pinterest_id'])) {
			return false;
		}
		
		$data = $this->remote_get('https://www.pinterest.com/' . urlencode($this->options['sf_pinterest_id']) . '/');
		preg_match('#property\=.?pinterestapp:followers([^>]+?)content\=.?(\d*)#i', $data, $match);
		
		$count = 0;
		
		if (!empty($match[2])) {
			$count = $match[2];
		}
		
		return intval($count);	
	}
	
	/**
	 * Check required data is available in options
	 * 
	 * @param  array $keys
	 * @return bool  True if all exist
	 */
	public function _check_options($keys, $prefix = 'sf_') 
	{
		foreach ($keys as $key) {
			if (!array_key_exists($prefix . $key, $this->options)) {
				return false;
			}
		}
		
		return true;
	}
		
	/**
	 * A wrapper for wp_remote_get()
	 * 
	 * @see wp_remote_get()
	 * @param string $url
	 * @param array  $args
	 * @return string
	 */
	private function remote_get($url, $args = []) 
	{
		$params = array_merge([
			'timeout'    => $this->timeout,
			'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36',
			'headers'    => [
				'Accept-language' => 'en-US,en;q=0.9',
			],
		], $args);

		$response = wp_remote_get($url, $params);
		
		if (is_wp_error($response)) {
			return '';
		}
		
		return $response['body'];
	}
	
}