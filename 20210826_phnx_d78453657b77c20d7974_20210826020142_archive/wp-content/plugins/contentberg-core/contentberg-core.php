<?php
/**
 * ContentBerg Core
 *
 * Plugin Name:       ContentBerg Core
 * Description:       Elements and core functionality for ContentBerg Theme.
 * Version:           1.1.2
 * Author:            ThemeSphere
 * Author URI:        http://theme-sphere.com
 * License:           ThemeForest Split
 * License URI:       http://themeforest.net/licenses/standard
 * Text Domain:       contentberg-core
 * Domain Path:       /languages
 * Requires PHP:      5.4
 */

defined('WPINC') || exit;

class ContentBerg_Core 
{
	const VERSION = '1.1.2';

	protected static $instance;

	/**
	 * Path to plugin folder
	 */
	public $path;
	public $path_url;

	public function __construct()
	{
		$this->path = plugin_dir_path(__FILE__);

		// URL for the plugin dir
		$this->path_url = plugin_dir_url(__FILE__);
	}
	
	/**
	 * Set it up
	 */
	public function init()
	{		
		$lib_path   = $this->path . 'lib/';
		
		/**
		 * When one of our themes isn't active, use shims
		 */
		if (!class_exists('Bunyad_Core')) {
			require_once $this->path . 'lib/bunyad.php';
			require_once $this->path . 'inc/bunyad.php';

			// Set path to local as theme isn't active
			Bunyad::$fallback_path = $lib_path;

			Bunyad::options()->set_config(array(
				'theme_prefix' => 'contentberg',
				'meta_prefix'  => '_bunyad'
			));
		}

		// Outdated Bunyad from an old theme? Bail.
		if (!property_exists('Bunyad', 'fallback_path')) {
			return;
		}

		// Set local fallback for some components not packaged with theme
		Bunyad::$fallback_path = $lib_path;

		/**
		 * Setup filters and data
		 */

		// Setup widgets at proper hook
		add_action('widgets_init', array($this, 'setup_widgets'));

		// Admin related actions
		add_action('admin_init', array($this, 'admin_init'));

		// User profile fields
		add_filter('user_contactmethods', array($this, 'add_profile_fields'));

		// Register assets
		// add_action('wp_enqueue_scripts', array($this, 'register_assets'));
		add_action('admin_enqueue_scripts', array($this, 'admin_assets'));

		// Setup blocks
		$this->setup_blocks();

		// Optimizations
		require_once $this->path . 'inc/optimize.php';

		// Social Share
		require_once $this->path . 'inc/social-share.php';

		// Editor changes
		if (is_admin()) {
			require_once $this->path . 'inc/editor.php';
		}

		// Init menu helper classes
		Bunyad::menus();
		add_filter('bunyad_custom_menu_fields', array($this, 'custom_menu_fields'));

		// Translation
		load_plugin_textdomain(
			'contentberg-core',
			false,
			basename($this->path) . '/languages'
		);

		// Set metaboxes options directory path.
		add_filter('bunyad_metabox_options_dir', function() {
			return $this->path . 'metaboxes/options';
		});
	}

	/**
	 * Admin side only
	 */
	public function admin_init()
	{
		// Set active metaboxes
		Bunyad::options()->set_config('meta_boxes', array(
			// Enabled metaboxes and prefs - id is prefixed with _bunyad_ in init() method of lib/admin/meta-boxes.php
			array(
				'id' => 'post-options', 
				'title' => esc_html_x('Post Options', 'Admin: Meta', 'contentberg-core'), 
				'priority' => 'high', 
				'page' => array('post'),
				'file'  => $this->path . 'metaboxes/post-options.php',
			),

			array(
				'id' => 'page-options', 
				'title' => esc_html_x('Page Options', 'Admin: Meta', 'contentberg-core'),
				'priority' => 'high', 
				'page' => array('page'),
				'file'  => $this->path . 'metaboxes/page-options.php',
			),
		));

		Bunyad::factory('admin/meta-boxes');
	}

	/**
	 * Register assets
	 */
	public function admin_assets($hook)
	{
		wp_enqueue_style('contentberg-core', $this->path_url . '/css/admin/common.css', array(), self::VERSION);
	}

	/**
	 * Setup Widgets
	 */
	public function setup_widgets()
	{
		$widgets = apply_filters('bunyad_active_widgets', array(
			'about', 'posts', 'cta', 'ads', 'social', 'subscribe', 'social-follow', 'twitter', 'slider'
		));
		
		// Activate widgets
		foreach ($widgets as $widget) {

			$file  = $this->path . 'widgets/widget-'. sanitize_file_name($widget) .'.php';
			$class = 'ContentBerg_Widgets_' . implode('', array_map('ucfirst', explode('-', $widget)));
			
			// Skip if already included or if file is missing
			if (class_exists($class) OR !file_exists($file)) {
				continue;
			}
			
			// Include the widget class
			require_once $file;
			
			if (!class_exists($class)) {
				continue;
			}
			
			/**
			 * Use register widget method of the ContentBerg_Widgets_XYZ class if available.
			 * Fallback to register_widget.
			 * 
			 * @see register_widget()
			 */
			if (method_exists($class, 'register_widget')) {
				$caller = new $class;
				$caller->register_widget(); 
			}
			else {
				register_widget($class);
			}
		}

		// Setup block widgets (which are mapped to shortcodes)
		Bunyad::get('cb-shortcodes')->create_block_widgets();
	}

	/**
	 * Setup blocks
	 */
	public function setup_blocks()
	{
		require_once $this->path . 'inc/shortcodes.php';
		require_once $this->path . 'inc/block.php';
		require_once $this->path . 'inc/blocks.php';

		// AJAX pagination handler for blocks
		require_once $this->path . 'inc/blocks-ajax.php';

		if (class_exists('Vc_Manager')) {
			require_once $this->path . 'inc/visual-composer.php';
		}

		// Default attributes shared amongst blocks
		$attribs = apply_filters('bunyad_default_block_attribs', array(
			'posts'   => 4,
			'offset'  => '',
			'heading' => '',
			'heading_type' => '',
			'link'    => '',
			'cat'     => '',
			'cats'    => '', 
			'tags'    => '',
			'terms'   => '',
			'pagination' => '',
			'pagination_type' => '',
			'view_all'   => '',
			'taxonomy'   => '',
			'sort_order' => '',
			'sort_by'    => '',
			'post_format' => '',
			'post_type'   => '',
			'filters' => false
		));
		
		$attribs_blog = array_merge(
			$attribs,
			array(
				'type' => '', 
				'show_excerpt' => 1,
				'show_footer'  => '',
				'excerpt_length' => ''
			)
		);
		
		/**
		 * Setup loop blocks - aliases for blog shortcode
		 */
		$listings = array(
			'loop-classic',
			'loop-default',  // legacy
			'loop-1st-large',
			'loop-1st-large-list',
			'loop-1st-overlay',
			'loop-1st-overlay-list',
			'loop-1-2',
			'loop-1-2-list',
			'loop-1-2-overlay',
			'loop-1-2-overlay-list',
			'loop-list',
			'loop-grid',
			'loop-grid-3'
		);
		
		$loop_blocks = array();
		$loop_params = array(
			'render'  => locate_template('partials/blocks/blog.php'), 
			'attribs' => $attribs_blog,
		);
		
		foreach ($listings as $block) {
			// Hyphens are dangerous in shortcodes
			$block = str_replace('-', '_', $block);
			
			$loop_blocks[$block] = $loop_params;
		}

		Bunyad::get('cb-shortcodes')->add(array_merge($loop_blocks, array(
			'blog' => array(
				'render'  => locate_template('partials/blocks/blog.php'), 
				'attribs' => $attribs_blog,
			),
				
			'highlights' => array(
				'render'  => locate_template('partials/blocks/highlights.php'), 
				'attribs' => $attribs
			),
			
			'news_grid' => array(
				'render'  => locate_template('partials/blocks/news-grid.php'), 
				'attribs' => $attribs
			),
				
			'ts_ads' => array(
				'render'  => locate_template('partials/blocks/ts-ads.php'),
				'attribs' => array(
					'code'  => '', 
					'title' => ''		
				)
			)
		)));
	}

	/**
	 * Filter callback: Custom menu fields.
	 *
	 * Required for both back-end and front-end.
	 *
	 * @see Bunyad_Menus::init()
	 */
	public function custom_menu_fields($fields)
	{
		$fields = array(
			'mega_menu' => array(
				'label' => esc_html_x('Mega Menu', 'Admin', 'contentberg-core'),
				'element' => array(
					'type' => 'select',
					'class' => 'widefat',
					'options' => array(
						0 => esc_html_x('Disabled', 'Admin', 'contentberg-core'),
						'category' => esc_html_x('Enabled', 'Admin', 'contentberg-core'),
					)
				),
				'parent_only' => true,
			)
		);
	
		return $fields;
	}

    /**
	 * Filter callback: Add theme-specific profile fields
	 */
	public function add_profile_fields($fields)
	{
		$fields = array_merge((array) $fields, array(
			'bunyad_facebook'  => esc_html_x('Facebook URL', 'Admin', 'contentberg-core'),	
			'bunyad_twitter'   => esc_html_x('Twitter URL', 'Admin', 'contentberg-core'),
			'bunyad_gplus'     => esc_html_x('Google+ URL', 'Admin', 'contentberg-core'),
			'bunyad_instagram' => esc_html_x('Instagram URL', 'Admin', 'contentberg-core'),
			'bunyad_pinterest' => esc_html_x('Pinterest URL', 'Admin', 'contentberg-core'),
			'bunyad_bloglovin' => esc_html_x('BlogLovin URL', 'Admin', 'contentberg-core'),
			'bunyad_dribble'   => esc_html_x('Dribble URL', 'Admin', 'contentberg-core'),
			'bunyad_linkedin'  => esc_html_x('LinkedIn URL', 'Admin', 'contentberg-core'),
			'bunyad_tumblr'    => esc_html_x('Tumblr URL', 'Admin', 'contentberg-core'),
		));
		
		return $fields;
	}

	/**
	 * Singleton instance
	 * 
	 * @return ContentBerg_Core
	 */
	public static function instance() 
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
}

$contentberg = ContentBerg_Core::instance();
add_action('after_setup_theme', array($contentberg, 'init'));