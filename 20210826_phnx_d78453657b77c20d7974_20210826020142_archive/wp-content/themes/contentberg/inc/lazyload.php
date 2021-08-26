<?php
/**
 * Lazyload images for speed
 */
class Bunyad_Theme_Lazyload
{
	public $image_sizes;
	public $svg_cache;

	/**
	 * Flag to enable/disable images that follow in queue
	 */
	public $disabled = false;
	
	
	public function __construct()
	{
		// Bunyad::options() isn't initialized yet, wait for it
		add_action('after_setup_theme', array($this, 'init'));
	}
	
	/**
	 * All core ready - initialize
	 */
	public function init()
	{
		if (!Bunyad::options()->lazyload_enabled) {
			return;
		}
		
		add_action('wp_get_attachment_image_attributes', array($this, 'image_attribs'), 10, 3);
		
		// Aggressive lazyload for sidebar and footer
		if (Bunyad::options()->lazyload_aggressive) {
			
			// WPBakery conflicts in preview with footer buffer and widgets won't update.
			// vc_is_page_editable() isn't reliable at this point.
			if (!empty($_GET['vc_editable'])) { 
				return;
			}

			add_action('dynamic_sidebar_before', array($this, 'start_buffer'), 10);
			add_action('dynamic_sidebar_after', 'ob_end_flush', 10, 0);
		
			add_action('bunyad_pre_footer', array($this, 'start_buffer'), 10);
			add_action('wp_footer', 'ob_end_flush', 2, 0);
		}
		
		// Cache image sizes - used by image_attribs()
		$this->image_sizes = $this->get_registered_sizes();
		
		add_action('wp_enqueue_scripts', array($this, 'register_assets'));
		Bunyad::core()->add_body_class('lazy-' . Bunyad::options()->lazyload_type);
	}
	
	/**
	 * Setup the JS file
	 */
	public function register_assets()
	{
		wp_enqueue_script('lazysizes', get_template_directory_uri() . '/js/lazysizes.js', array(), Bunyad::options()->get_config('theme_version'));
	}
	
	/**
	 * Disable lazy flags addition on images that follow
	 */
	public function disable() {
		$this->disabled = true;
		return $this;
	}
	
	/**
	 * Re-enable lazy load (enabled by default)
	 */
	public function enable() {
		$this->disabled = false;
		return $this;
	}
	
	/**
	 * Start capturing content to filter later
	 */
	public function start_buffer()
	{
		// Capture sidebar input
		ob_start(array($this, 'process_content'));
	}

	/**
	 * Process Raw HTML to find and replace images
	 */
	public function process_content($content = '')
	{
		if (!$this->should_lazy()) {
			return $content;
		}
		
		preg_match_all('#<img[^>]*>#is', $content, $matches);
		$images = $matches[0];
		
		foreach ($images as $image) {
			
			$updated = '';
			
			// @todo: More testing on regex
			preg_match_all('#(?P<name>[a-z\-]+)=("|\')(?P<value>.*?)\2#is', $image, $match);
			if (empty($match['name'])) {
				continue;
			}
			
			$attr = array_combine((array) $match['name'], (array) $match['value']);
			
			// Extend some defaults
			$attr = array_merge(array(
				'class' => ''
			), $attr);

			// Already lazyloaded?
			if (strstr($attr['class'], 'lazyload')) {
				continue;
			}
				
			// Add class
			$attr['class'] .= ' lazyload';
			
			$width  = (!empty($attr['width']) ? $attr['width'] : 1);
			$height = (!empty($attr['height']) ? $attr['height'] : 1);
			
			// Generate src
			$attr['data-src'] = $attr['src'];
			$attr['src'] = $this->svg_placeholder($width, $height);
			
			// Set srcset if exists
			if (!empty($attr['srcset'])) {
				$attr['data-srcset'] = $attr['srcset'];
				unset($attr['srcset']);
			}

			// Set sizes
			if (!empty($attr['sizes'])) {
				$attr['data-sizes'] = $attr['sizes'];
				unset($attr['sizes']);
			}
			
			$updated = '<img ' . Bunyad::markup()->attribs('lazy', $attr, array('esc_src_url' => 0, 'echo' => 0)) . ' />';
			$content = str_replace($image, $updated, $content);
		}
		
		return $content;
	}
	
	/**
	 * Check if lazy load should be applied
	 */
	public function should_lazy()
	{
		if ($this->disabled OR is_feed() OR is_preview() OR is_admin()) {
			return false;
		}
		
		if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
			return false;
		}

		// WPBakery page builder loads shortcode this way.
		// vc_is_page_editable() isn't reliable at this point.
		if (!empty($_GET['vc_editable'])) { 
			return false;
		}
		
		// WooCommerce image zoom is bugged
		if (function_exists('is_product') && is_product()) {
			return false;
		}
		
		// Filter that can disable lazyload by returning false
		if (!apply_filters('bunyad_lazyload_enabled', true)) {
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * Add image attributes 
	 * 
	 * @see wp_get_attachment_image()
	 * @param string $size
	 */
	public function image_attribs($attr, $attachment, $size) 
	{
		if (!$this->should_lazy()) {
			return $attr;
		}
				
		/**
		 * Get width and height
		 */
		$width  = 1;
		$height = 1;
		
		$attachment = wp_get_attachment_metadata($attachment->ID);
		
		if (is_string($size)) { 
			
			// From attachment metadata first - fallback to global setting next
			if (!empty($attachment['sizes']) && array_key_exists($size, $attachment['sizes'])) {
				$info   = $attachment['sizes'][$size];
			}
			else if (array_key_exists($size, $this->image_sizes)) {
				$info   = $this->image_sizes[$size];

			}
			
			if (!empty($info)) {
				$width  = $info['width'];
				$height = $info['height'];
			}
		}
		
		if (!empty($attr['srcset'])) {
			
			$attr['data-srcset'] = $attr['srcset'];
			$attr['data-src'] = $attr['src'];

			// Use a placeholder SVG with same width and height as registered to prevent jumps
			$attr['src'] = esc_attr($this->svg_placeholder($width, $height));
			$attr['data-sizes'] = $attr['sizes'];
			
			unset($attr['srcset'], $attr['sizes']);
		}
		
		$attr['class'] .= ' lazyload';
		
		return $attr;
	}
	
	/**
	 * Create an SVG placeholder for data URI
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @return string Data URI format svg
	 * 
	 */
	public function svg_placeholder($width = 1, $height = 1)
	{
		$id = "{$width}x{$height}";
		
		if (!empty($this->svg_cache[$id])) {
			return $this->svg_cache[$id];
		}
		
		$svg = "<svg viewBox='0 0 {$width} {$height}' xmlns='http://www.w3.org/2000/svg'></svg>";
		
		// For saving few bytes: $svg = str_replace('%20', ' ', rawurlencode($svg));
		// But the browser support might be limited
		$svg = rawurlencode($svg);
		
		return ($this->svg_cache[$id] = 'data:image/svg+xml,' . $svg); 
	}
	
	/**
	 * Get all registered image sizes, including default ones
	 * 
	 * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
	 */
	public function get_registered_sizes()
	{
		global $_wp_additional_image_sizes;
	
		$default_sizes = array('thumbnail', 'medium', 'medium_large', 'large');
		 
		foreach ($default_sizes as $size) {
			$image_sizes[$size]['width']  = intval( get_option("{$size}_size_w"));
			$image_sizes[$size]['height'] = intval( get_option("{$size}_size_h"));
			$image_sizes[$size]['crop']   = get_option("{$size}_crop") ? get_option("{$size}_crop") : false;
		}
		
		if (!empty($_wp_additional_image_sizes)) {
			$image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
		}
			
		return $image_sizes;
	}
}

// init and make available in Bunyad::get('lazyload')
Bunyad::register('lazyload', array(
	'class' => 'Bunyad_Theme_Lazyload',
	'init' => true
));