<?php
/**
 * Functions relating to the social functionality (mostly extends Sphere Core plugin)
 */
class Bunyad_Theme_Social
{	
	public function __construct() 
	{
		// Plugin: "Sphere Core" modifications
		add_filter('sphere_social_follow_options', array($this, 'follow_options'));
		add_filter('sphere_theme_docs_url', array($this, 'docs_url'));
	}
	
	/**
	 * Filter: Modify default customizer options for social follow from Sphere Core Plugin.
	 * 
	 * Used when Sphere Core plugin is active.
	 * 
	 * @see Sphere_Plugin_SocialFollow::add_theme_options()
	 */
	public function follow_options($options)
	{
		$supported = [
			'general',
			'facebook',
			'twitter',
			'pinterest',
			'instagram',
			'youtube',
			'vimeo'
		];

		foreach ($options['sections'] as $key => $section) {
			if (!in_array($key, $supported)) {
				unset($options['sections'][$key]);
			}
		}

		$options['title'] = esc_html_x('Social Counters', 'Admin', 'contentberg');
		$options['desc']  = sprintf(
			'Note: These settings are for Social Follow widget. For normal social settings, go to %sGeneral Settings%s',
			'<a href="#" class="focus-link" data-section="general-social">', '</a>'
		);
		$options['sections']['general']['fields']['sf_counters']['value'] = 0;
	
		// Change default labels
		$labels = $this->get_services();
		foreach (array_keys($options['sections']) as $id) {
			
			if (!array_key_exists($id, $labels)) {
				continue;
			}
			
			$options['sections'][$id]['fields']["sf_{$id}_label"]['value'] = $labels[$id]['label'];
		}
		
		return $options;
	}
	
	public function docs_url($url) {
		return 'https://contentberg.theme-sphere.com/documentation/';
	}
	
	/**
	 * Get an array of services supported at different locations
	 * such as Top bar social icons.
	 */
	public function get_services()
	{
		$services = array(
			'facebook' => array(
				'icon' => 'facebook',
				'label' => esc_html__('Facebook', 'contentberg')
			),
			
			'twitter' => array(
				'icon' => 'twitter',
				'label' => esc_html__('Twitter', 'contentberg')
			),
			
			'instagram' => array(
				'icon' => 'instagram',
				'label' => esc_html__('Instagram', 'contentberg')
			),
			
			'pinterest' => array(
				'icon' => 'pinterest-p',
				'label' => esc_html__('Pinterest', 'contentberg')
			),
			
			'bloglovin' => array(
				'icon' => 'heart',
				'label' => esc_html__('BlogLovin', 'contentberg')
			),
			
			'rss' => array(
				'icon' => 'rss',
				'label' => esc_html__('RSS', 'contentberg')
			),
			
			'gplus' => array(
				'icon' => 'google-plus',
				'label' => esc_html__('Google Plus', 'contentberg')
			),
			
			'youtube' => array(
				'icon' => 'youtube',
				'label' => esc_html__('YouTube', 'contentberg')
			),
			
			'dribbble' => array(
				'icon' => 'dribbble',
				'label' => esc_html__('Dribbble', 'contentberg')
			),
			
			'tumblr' => array(
				'icon' => 'tumblr',
				'label' => esc_html__('Tumblr', 'contentberg')
			),
			
			'linkedin' => array(
				'icon' => 'linkedin',
				'label' => esc_html__('LinkedIn', 'contentberg')
			),
			
			'flickr' => array(
				'icon' => 'flickr',
				'label' => esc_html__('Flickr', 'contentberg')
			),
			
			'soundcloud' => array(
				'icon' => 'soundcloud',
				'label' => esc_html__('SoundCloud', 'contentberg')
			),
			
			'vimeo' => array(
				'icon' => 'vimeo',
				'label' => esc_html__('Vimeo', 'contentberg')
			),
				
			'lastfm' => array(
				'icon' => 'lastfm',
				'label' => esc_html__('Last.fm', 'contentberg')
			),
				
			'steam' => array(
				'icon' => 'steam',
				'label' => esc_html__('Steam', 'contentberg')
			),
				
			'vk' => array(
				'icon' => 'vk',
				'label' => esc_html__('VKontakte', 'contentberg')
			),
		);
		
		return apply_filters('bunyad_social_services', $services);
	}
}

// init and make available in Bunyad::get('social')
Bunyad::register('social', array(
	'class' => 'Bunyad_Theme_Social',
	'init' => true
));