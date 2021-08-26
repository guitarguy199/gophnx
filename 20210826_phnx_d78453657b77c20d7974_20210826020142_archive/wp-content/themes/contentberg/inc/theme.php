<?php
/**
 * ContentBerg Theme!
 * 
 * Anything theme-specific that won't go into the core framework goes here.
 */
class Bunyad_Theme_ContentBerg
{

	public function __construct() 
	{	
		// Perform the after_setup_theme 
		add_action('after_setup_theme', array($this, 'theme_init'), 12);
		
		// Init skins
		add_action('bunyad_core_post_init', array($this, 'init_skins'));
		
		/**
		 * Load theme functions and helpers.
		 * 
		 * Note: Bunyad::options() isn't ready yet. Bunyad_Core::init() enables it later.
		 * Use filters:
		 *   'bunyad_core_post_init' OR 'after_setup_theme'
		 */

		// Admin only
		if (is_admin()) {
		
			// Admin (backend) functionality 
			include get_template_directory() . '/inc/admin.php';
		}
		
		// Ready up the custom css handlers
		include get_template_directory() . '/inc/custom-css.php';
		
		// Customizer features
		include get_template_directory() . '/inc/customizer.php';
		
		// Sphere Core Plugin: Likes / heart functionality
		include get_template_directory() . '/inc/likes.php';
		
		// Extend Sphere Core Plugin social features
		include get_template_directory() . '/inc/social.php';
		
		// Template tags related to general layout
		include get_template_directory() . '/inc/helpers.php';
		include get_template_directory() . '/inc/media.php';
		include get_template_directory() . '/inc/lazyload.php';

		// Updates check - WP has hooks that fire regardless of wp-admin, hence placed here
		include get_template_directory() . '/inc/admin/theme-updates.php';

		// AMP features
		include get_template_directory() . '/inc/amp/amp.php';

		// Have WooCommerce?
		if (function_exists('is_woocommerce')) {
			include get_template_directory() . '/inc/woocommerce.php';
		}
	}
	
	/**
	 * Setup enque data and actions
	 */
	public function theme_init()
	{
		/**
		 * Enqueue assets (css, js)
		 * 
		 * Register Custom CSS at a lower priority for CSS specificity
		 */
		add_action('wp_enqueue_scripts', array($this, 'register_assets'));
		
		/**
		 * Set theme image sizes used in different areas, blocks and posts
		 * 
		 * contentberg-main        -  Used for main featured images
		 * contentberg-main-full   -  Featured image for the full width posts
		 * contentberg-grid        -  Used on the grid posts layout
		 * contentberg-list        -  Used on list posts layout
		 * contentberg-list-b      -  For list layout in alt style
		 * contentberg-thumb       -  Smaller thumbnail for widgets
		 * 
		 * NOTE: 
		 * 	 - All these images are NOT ALWAYS generated - some are simply Aliases.
		 *  
		 * Total Generated Images: 11 (rest of them are Aliases)
		 */
		
		$image_sizes = apply_filters('bunyad_image_sizes', array(
				
			'post-thumbnail' => array('width'=> 270, 'height' => 180),
			
			// Single, large, and overlay posts
			'contentberg-main'      => array('width'=> 770, 'height' => 515),
			'contentberg-main-full' =>  array('width' => 1170, 'height' => 508),
			
			// Slider images
			'contentberg-slider-stylish'   => array('width' => 900, 'height' => 515),
			'contentberg-slider-carousel'  => array('width' => 370, 'height' => 370),
			'contentberg-slider-grid-b'    => array('width' => 554, 'height' => 466),
			'contentberg-slider-grid-b-sm' => array('width' => 306, 'height' => 466),
			'contentberg-slider-bold-sm'   => array('width' => 150, 'height' => 150), // Alias for thumbnail
			
			// Grid Posts
			'contentberg-grid'    => array('width' => 370, 'height' => 245),

			// List Posts
			'contentberg-list'   => array('width' => 260, 'height' => 200),
			'contentberg-list-b' => array('width' => 370, 'height' => 305),

			// Thumbs for sidebar
			'contentberg-thumb'     => array('width' => 87, 'height' => 67),
			'contentberg-thumb-alt' => array('width' => 150, 'height' => 150), // Alias for thumbnail
			
		));
		
		foreach ($image_sizes as $key => $size) {
			
			// Set default crop to true
			$size['crop'] = (!isset($size['crop']) ? true : $size['crop']);
			
			add_image_size($key, $size['width'], $size['height'], $size['crop']);
			
		}

		// i18n
		load_theme_textdomain('contentberg', get_template_directory() . '/languages');
		
		// Setup navigation menu
		register_nav_menu('contentberg-main', esc_html_x('Main Navigation', 'Admin', 'contentberg'));
		register_nav_menu('contentberg-mobile', esc_html_x('Mobile Menu (Optional)', 'Admin', 'contentberg'));
		
		// Optional topbar menu if enabled
		if (Bunyad::options()->topbar_top_menu) {
			register_nav_menu('contentberg-top-menu', esc_html_x('Topbar Menu (Optional)', 'Admin', 'contentberg'));
		}
		
		// Optional footer menu
		if (Bunyad::options()->footer_links) {
			register_nav_menu('contentberg-footer-links', esc_html_x('Footer Links (Style 9 and 10 Only)', 'Admin', 'contentberg'));
		}
		
		/**
		 * Additional Theme support not defined in Bunyad Core
		 */
		add_theme_support('html5', array(
			'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'
		));

		// WordPress native <title> tag
		add_theme_support('title-tag');

		// Add support for site background
		add_theme_support('custom-background');

		// Gutenberg
		add_theme_support('align-wide');

		// This is an awkward mix of blocks into widgets.
		remove_theme_support('widgets-block-editor');
			
		// Add content width for oEmbed and similar
		global $content_width;
		
		if (!isset($content_width)) {
			$content_width = 770;
		}
		
		/**
		 * Register Sidebars and relevant filters
		 */
		add_action('widgets_init', array($this, 'register_sidebars'));
		
		// Category widget settings
		add_filter('widget_categories_args', array($this, 'category_widget_query'));
		
		/**
		 * Posts related filter
		 */
		
		// Add the orig_offset for offset support in blocks
		add_filter('bunyad_block_query_args', array(Bunyad::posts(), 'add_query_offset'), 10, 1);
		
		// Video format auto-embed
		add_filter('bunyad_featured_video', array($this, 'video_auto_embed'));
		add_filter('embed_defaults', array($this, 'soundcloud_embed'), 10, 2);
		
		// Remove hentry microformat, we use schema.org/Article
		add_action('post_class', array($this, 'fix_post_class'));
		
		// Fix content_width for full-width posts
		add_filter('wp_head', array($this, 'content_width_fix'));
		
		// Limit search to posts?
		if (Bunyad::options()->search_posts_only) {
			add_filter('pre_get_posts', array($this, 'limit_search'));
		}
		
		// Limit number of posts on homepage via a separate setting 
		// Additionally, fix hanging posts for assorted layout
		add_filter('pre_get_posts', array($this, 'home_posts_limit'));
		
		// Read more common html
		Bunyad::posts()->more_text = esc_html__('Read More', 'contentberg');
		Bunyad::posts()->more_html = ' ';
		
		// Default comment fields re-order
		add_filter('comment_form_fields', array($this, 'comment_form_order'), 20);

		
		/**
		 * Admin And editor styling
		 */
		if (is_admin()) {
			
			// Add editor styles
			$styles = array(get_stylesheet_uri());
			$skin   = $this->get_style();
			
			// Add skin css second
			if (isset($skin['css'])) {
				array_push($styles, get_template_directory_uri() . '/css/' . $skin['css'] . '.css');
			}
			
			$styles = array_merge($styles, array(
				get_template_directory_uri() . '/css/admin/editor-style.css',
				$this->get_fonts_enqueue()
			));
						
			add_editor_style($styles);
		}
		
		/**
		 * Navigation changes
		 */
		add_filter('bunyad_mega_menu_end_lvl', array($this, 'attach_mega_menu'));
		add_filter('wp_nav_menu_items', array($this, 'add_navigation_icons'), 10, 2);
		
		add_action('wp_footer', array($this, 'add_pinterest'), 2);
		
		/**
		 * Misc
		 */
		add_filter('body_class', array($this, 'the_body_class'));	
			
		/**
		 * Sphere Core plugins
		 */
		if (class_exists('Sphere_Plugin_Core')) {
			Bunyad::register(
				'social-follow', 
				array('object' => Sphere_Plugin_Core::get('social-follow'))
			);
		}

		// Filter via Contentberg Core plugin
		add_filter('bunyad_social_share_float_active', function() { 
			return Bunyad::options()->share_float_services; 
		});
		
		/**
		 * 3rd Party plugins fixes
		 */
		add_action('init', array($this, 'jetpack_fix'));
		add_filter('jp_carousel_force_enable', '__return_true');

		// Disable activation notice for Self-hosted Google Fonts plugin
		add_filter('sgf/admin/active_notice', '__return_false');
	}
	

	/**
	 * Setup any skin data and configs
	 */
	public function init_skins()
	{
		// Include our skins constructs
		if (Bunyad::options()->predefined_style) {
			
			$style = $this->get_style();
			
			if (!empty($style['bootstrap'])) {
				locate_template($style['bootstrap'], true);
			}
		}
	}

	/**
	 * Register and enqueue theme CSS and JS files
	 */
	public function register_assets()
	{
		// Theme version
		$version = Bunyad::options()->get_config('theme_version');
		
		// Only add to front-end
		if (!is_admin()) {
			
			/**
			 * Add CSS styles
			 */
			
			// Get style configs for current style
			$style = $this->get_style(Bunyad::options()->predefined_style);
			
			// Add Typekit Kit
			if (Bunyad::options()->typekit_id) {
				wp_enqueue_style('contentberg-typekit', esc_url('https://use.typekit.net/' . Bunyad::options()->typekit_id . '.css'), [], null);
			}
	
			// Add Google fonts
			if (!empty($style['font_args'])) {
				wp_enqueue_style('contentberg-fonts', $this->get_fonts_enqueue(), array(), null);
			}
			
			// Add extra CSS if any
			if (!empty($style['extra_css'])) {
				foreach ($style['extra_css'] as $id => $file) {
					wp_enqueue_style($id, get_template_directory_uri() . $file);
				}
			}
				
			// Add core css
			if (apply_filters('bunyad_enqueue_core_css', true)) {
				wp_enqueue_style('contentberg-core', get_stylesheet_uri(), array(), $version);
			}

			// Add lightbox
			if (!Bunyad::amp()->active()) {
				wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/js/magnific-popup.js', array(), $version, true);
			
				// Our lightbox CSS
				wp_enqueue_style('contentberg-lightbox', get_template_directory_uri() . '/css/lightbox.css', array(), $version);
			}
			
			/**
			 * Load all the required JS scripts.
			 * 
			 * Third party assets without prefix in compliance with /wp-standard-handles
			 */
			
			 // 3rd Party: FontAwesome
			wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/fontawesome/css/font-awesome.min.css', array(), $version);

			// 3rd Party: FitVids
			wp_enqueue_script('jquery-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), $version, true);
			wp_enqueue_script('imagesloaded');

			// 3rd Party: https://github.com/bfred-it/object-fit-images
			wp_enqueue_script('object-fit-images', get_template_directory_uri() . '/js/object-fit-images.js', array(), $version, true);

			// Main Theme Script - included after others
			wp_enqueue_script('contentberg-theme', get_template_directory_uri() . '/js/theme.js', array('jquery'), $version, true);
			
			// 3rd Party: https://github.com/WeCodePixels/theia-sticky-sidebar
			wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.js', array('jquery'), $version, true);

			// 3rd Party: jQuery Slick
			wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/js/jquery.slick.js', array('jquery'), $version, true);

			// 3rd Party: https://github.com/nk-o/jarallax
			wp_enqueue_script('jarallax', get_template_directory_uri() . '/js/jarallax.js', array('jquery'), $version, true);
			
			// Masonry from Core if needed
			if (Bunyad::options()->post_grid_masonry) {
				wp_enqueue_script('jquery-masonry');
			}
		}
	}
	
	/**
	 * Setup the sidebars
	 */
	public function register_sidebars()
	{
	
		// Main Sidebar
		register_sidebar(array(
			'name' => esc_html_x('Main Sidebar', 'Admin', 'contentberg'),
			'id'   => 'contentberg-primary',
			'description' => esc_html_x('Widgets in this area will be shown in the default sidebar.', 'Admin', 'contentberg'),
			'before_title' => '<h5 class="widget-title"><span>',
			'after_title'  => '</span></h5>',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => "</li>\n"
		));

		// Homepage Widgets
		register_sidebar(array(
			'name' => esc_html_x('Homepage Widgets', 'Admin', 'contentberg'),
			'id'   => 'contentberg-home',
			'description' => esc_html_x('Add widgets here to show on your Home. NOTE: Set your homepage to be blog, not a static homepage.', 'Admin', 'contentberg'),
			'before_title' => '<div class="block-head-c widget-title"><h5 class="title">',
			'after_title'  => '</h5></div>',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => "</section>\n"
		));

		// Homepage Sidebar 1
		register_sidebar(array(
			'name' => esc_html_x('Home Sidebar 1', 'Admin', 'contentberg'),
			'id'   => 'contentberg-home-1',
			'description' => esc_html_x('Extra sidebar to use when creating a homepage using widgets/pagebuilder.', 'Admin', 'contentberg'),
			'before_title' => '<h5 class="widget-title"><span>',
			'after_title'  => '</span></h5>',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => "</li>\n"
		));

		// Homepage Sidebar 2
		register_sidebar(array(
			'name' => esc_html_x('Home Sidebar 2', 'Admin', 'contentberg'),
			'id'   => 'contentberg-home-2',
			'description' => esc_html_x('Extra sidebar to use when creating a homepage using widgets/pagebuilder.', 'Admin', 'contentberg'),
			'before_title' => '<h5 class="widget-title"><span>',
			'after_title'  => '</span></h5>',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => "</li>\n"
		));
		
		// Call to Action boxes
		register_sidebar(array(
			'name' => esc_html_x('Home Call To Action Boxes', 'Admin', 'contentberg'),
			'id'   => 'contentberg-home-cta',
			'description' => esc_html_x('Use the "ContentBerg - Call To Action" in this area to show CTAs below slider.', 'Admin', 'contentberg'),
			'before_title' => '<h5 class="widget-title">',
			'after_title'  => '</h5>',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => "</div>\n"
		));

		// Footer Widgets / Upper Footer
		register_sidebar(array(
			'name' => esc_html_x('Footer Widgets', 'Admin', 'contentberg'),
			'id'   => 'contentberg-footer',
			'description' => esc_html_x('Add three widgets for the footer area. (Optional)', 'Admin', 'contentberg'),
			'before_title' => '<h5 class="widget-title">',
			'after_title'  => '</h5>',
			'before_widget' => '<li id="%1$s" class="widget column col-4 %2$s">',
			'after_widget' => '</li>'
		));
		
		// Middle Footer / Instagram Footer
		register_sidebar(array(
			'name' => esc_html_x('Footer Instagram', 'Admin', 'contentberg'),
			'id'   => 'contentberg-instagram',
			'description' => esc_html_x('Simply add a single widget using "Bunyad Instagram Widget" plugin. (Optional)', 'Admin', 'contentberg'),
			'before_title' => '',
			'after_title'  => '',
			'before_widget' => '',
			'after_widget' => ''
		));
	}
	
	/**
	 * Styles and skins
	 */
	public function get_style($style = '')
	{
		// Get from settings
		if (empty($style)) {
			$style = Bunyad::options()->predefined_style;
		}
		
		if (empty($style)) {
			$style = 'default';
		}
		
		$styles = array(

			'default' => array(
				'font_args' => array('family' => 'Roboto:400,500,700|PT Serif:400,400i,600|IBM Plex Serif:500'),
				'font_args_typekit' => array('family' => 'Roboto:400,500,700|IBM Plex Serif:500'),
				
				// Can define custom CSS file like so: 
				//    'css' => 'skin-general'
			),
		);

		// Load up TypeKit modifications for default if it's active and disable google fonts
		if ($style == 'default' && Bunyad::options()->typekit_id) {
			$styles['default'] = array(
				'font_args' => $styles['default']['font_args_typekit'],
			);
		}
		
		if (empty($styles[$style])) {
			return array();
		}
		
		return $styles[$style];
	}
	
	/**
	 * Get Google Fonts Embed URL
	 * 
	 * @return string URL for enqueue
	 */
	public function get_fonts_enqueue()
	{
		// Add google fonts
		$style = $this->get_style(Bunyad::options()->predefined_style);
		$args  = $style['font_args'];
	
		if (Bunyad::options()->font_charset) {
			$args['subset'] = implode(',', array_filter(Bunyad::options()->font_charset));
		}

		return add_query_arg(
			urlencode_deep($args), 
			'https://fonts.googleapis.com/css'
		);
	}
	
	/**
	 * Filter callback: Modify category widget for only top-level categories, if 
	 * enabled in customizer.
	 * 
	 * @param array $query
	 */
	public function category_widget_query($query)
	{
		if (!Bunyad::options()->widget_cats_parents) {
			return $query;
		}
		
		// Set to display top-level only
		$query['parent'] = 0;
		
		return $query;
	}
	
	/**
	 * Filter callback: Auto-embed video using a link
	 * 
	 * @param string $content
	 */
	public function video_auto_embed($content) 
	{
		global $wp_embed;
		
		if (!is_object($wp_embed)) {
			return $content;
		}
		
		return $wp_embed->autoembed($content);
	}
	
	/**
	 * Filter callback: Adjust dimensions for soundcloud auto-embed. A height of 
	 * width * 1.5 isn't ideal for the theme.
	 * 
	 * @param array  $dimensions
	 * @param string $url
	 * @see wp_embed_defaults()
	 */
	public function soundcloud_embed($dimensions, $url)
	{
		if (!strstr($url, 'soundcloud.com')) {
			return $dimensions;
		}
		
		$dimensions['height'] = 300;
		
		return $dimensions;
	}

	/**
	 * Filter callback: Remove unnecessary classes
	 */
	public function fix_post_class($classes = array())
	{
		// remove hentry, we use schema.org
		$classes = array_diff($classes, array('hentry'));
		
		return $classes;
	}
	
	/**
	 * Adjust content width for full-width posts
	 */
	public function content_width_fix()
	{
		global $content_width;
	
		if (Bunyad::core()->get_sidebar() == 'none') {
			$content_width = 1170;
		}
	}	
	
	/**
	 * Filter callback: Fix search by limiting to posts
	 * 
	 * @param object $query
	 */
	public function limit_search($query)
	{
		if (!$query->is_search OR !$query->is_main_query()) {
			return $query;
		}

		// ignore if on bbpress and woocommerce - is_woocommerce() cause 404 due to using get_queried_object()
		if (is_admin() OR (function_exists('is_bbpress') && is_bbpress()) OR (function_exists('is_shop') && is_shop())) {
			return $query;
		}
		
		// limit it to posts
		$query->set('post_type', 'post');
		
		return $query;
	}
	
	/**
	 * Limit number of posts shown on the home-page
	 * 
	 * @param object $query
	 */
	public function home_posts_limit($query)
	{
		// bail out if incorrect query
		if (is_admin() OR !$query->is_home() OR !$query->is_main_query()) {
			return $query;
		}
		
		$posts_per_page = Bunyad::options()->home_posts_limit;

		$query->set('posts_per_page', $posts_per_page);
		
		return $query;
	}
	
	/**
	 * Adjust comment form fields order 
	 * 
	 * @param array $fields
	 */
	public function comment_form_order($fields)
	{

		// Un-necessary for WooCommerce
		if (function_exists('is_woocommerce') && is_woocommerce()) {
			return $fields;
		}
		
		// From Justin Tadlock's plugin
		if (isset($fields['comment'])) {
			
			// Grab the comment field.
			$comment_field = $fields['comment'];
			
			// Remove the comment field from its current position.
			unset($fields['comment']);
			
			// Put the comment field at the end but before consent
			if (!empty($fields['cookies'])) {

				$offset = array_search('cookies', $fields);

				$fields = array_merge(
					array_slice($fields, 0, $offset - 1),
					array('comment' => $comment_field),
					array_slice($fields, $offset)
				);
			}
			else {
				$fields['comment'] = $comment_field;
			}
		}
		
		return $fields;
	}
	
	/**
	 * Filter Callback: Add our custom mega-menus
	 *
	 * @param array $args
	 */
	public function attach_mega_menu($args)
	{
		extract($args);

		// Have a mega menu?
		if (empty($item->mega_menu)) {
			return $sub_menu;
		}
		
		ob_start();
		
		// Get our partial
		Bunyad::core()->partial('partials/mega-menu', compact('item', 'sub_menu', 'sub_items', 'args'));
		
		// Return template output
		return ob_get_clean();
	}
	
	/**
	 * Add icons for header nav-below-b
	 * 
	 * @param string $items
	 * @param array  $args
	 */
	public function add_navigation_icons($items, $args)
	{
		if (!in_array(Bunyad::options()->header_layout, array('nav-below-b', 'compact')) OR $args->theme_location != 'contentberg-main') {
			return $items;
		}
		
		ob_start();
		?>
		
		<li class="nav-icons">
			
			<?php if (Bunyad::options()->topbar_cart && class_exists('Bunyad_Theme_WooCommerce')): ?>
			
			<div class="cart-action cf">
				<?php echo Bunyad::get('woocommerce')->cart_link(); ?>
			</div>
			
			<?php endif; ?>

			<?php if (Bunyad::options()->topbar_search): ?>
			
			<a href="#" title="<?php esc_attr_e('Search', 'contentberg'); ?>" class="search-link"><i class="fa fa-search"></i></a>
			
			<div class="search-box-overlay">
				<?php
				Bunyad::helpers()->search_form('alt', array(
					'text' => esc_html__('Type and press enter', 'contentberg')
				));
				?>
			</div>
			
			<?php endif; ?>
		</li>
		
		<?php
		
		$items .= ob_get_clean();
		
		return $items;
	}

	/**
	 * Filter callback: Add slider and home to the body if activated on home
	 * 
	 * @param array $classes
	 */
	public function the_body_class($classes) 
	{	
		if (Bunyad::options()->predefined_style) {
			$classes[] = 'skin-' . Bunyad::options()->predefined_style;	
		}

		if (Bunyad::options()->enable_lightbox) {
			$classes[] = 'has-lb';
		}
		
		/**
		 * The classes below are only for home
		 */
		if (!is_home() && !is_front_page()) {
			return $classes; 
		}
		
		if (Bunyad::options()->home_slider OR (is_page() && Bunyad::posts()->meta('featured_slider'))) {
			
			$slider = Bunyad::posts()->meta('featured_slider') ? Bunyad::posts()->meta('featured_slider') : Bunyad::options()->home_slider;
			
			$classes[] = 'has-slider';
			$classes[] = 'has-slider-' . $slider;
		}
		
		// Add home layout class
		if (Bunyad::options()->home_layout) {
			$classes[] = 'home-' . Bunyad::options()->home_layout;
		}
		
		return $classes;
	}

	/**
	 * Add Pinterest hover button template
	 */
	public function add_pinterest()
	{
		if (!Bunyad::options()->pinit_button) {
			return;
		}
		
		$heading = '';
		$title   = Bunyad::options()->pinit_button_text;
		$show_on = implode(',', Bunyad::options()->pinit_show_on);

		if (is_single()) {
			$heading = get_post_field('post_title', get_the_ID(), 'raw');
		}
		
		?>
		
		<a href="https://www.pinterest.com/pin/create/bookmarklet/?url=%url%&media=%media%&description=%desc%" class="pinit-btn" target="_blank" title="<?php 
			echo esc_html($title); ?>" data-show-on="<?php echo esc_attr($show_on); ?>" data-heading="<?php echo esc_attr($heading); ?>">
			<i class="fa fa-pinterest-p"></i>
			
			<?php if (Bunyad::options()->pinit_button_label): ?>
				<span class="label"><?php echo esc_html($title); ?></span>
			<?php endif; ?>
			
		</a>
		<?php
	}
	
	/**
	 * Fix JetPack polluting the excerpts
	 */
	public function jetpack_fix()
	{
		remove_filter('the_excerpt', 'sharing_display', 19); // Fix JetPack adding sharing to excerpts
	}
}
