<?php
/**
 * Render the CSS customizations using Bunyad Framework options
 */
class Bunyad_Custom_Css
{
	public $elements;
	public $css_options;
	
	public $css = array();
	public $google_fonts = array();
	
	/**
	 * Stores options relevant to parsing the current CSS
	 * 
	 * @var array
	 */
	public $args = array();
	
	
	/**
	 * Initialize and get all the Custom CSS options
	 */
	public function init()
	{
		// Get option elements
		// We only need default elements here - values aren't used from this array
		$this->elements = Bunyad::options()->defaults;
		
		// Get all css options
		$this->css_options = Bunyad::options()->get_all('css_');
	}
	
	/**
	 * Process the main CSS changes and construct the basic CSS 
	 * for colors, typography etc.
	 */
	public function process()
	{
		$this->init();

		/**
		 * Rendering of all custom CSS
		 */
		foreach ($this->css_options as $key => $value)
		{
			if (!array_key_exists($key, $this->elements)) {
				continue;
			}
			
			$element = $this->elements[$key];
			
			// default? skip!
			if (!$element OR ($element['type'] != 'typography' && $element['value'] == $value) OR empty($value)) {
				continue;
			}
			
			/**
			 * Typography css options: font, size, color
			 */
			if ($element['type'] == 'typography')
			{
				$attribs = array();
				
				$element['fallback_stack'] = !empty($element['fallback_stack']) ? $element['fallback_stack'] : 'Arial, sans-serif';
				$default = $element['value'];
				
				// skip if same as default
				if ($default != $value) {
										
					/**
					 * Have a custom font weight
					 */
					$font_weight = '';
					if (!empty($value['font_weight']) && $value['font_weight'] != $default['font_weight'])  {

						// Italic style - perhaps in 400italic format?
						if (stristr($value['font_weight'], 'italic')) {
							$attribs[] = 'font-style: italic';
						}
						
						$font_weight = intval($value['font_weight']);
						$attribs[]   = 'font-weight: ' . $font_weight;	
					}
					
					/**
					 * Font name - include the google font
					 */
					if (!empty($value['font_name']) && $value['font_name'] != $default['font_name']) {
		
						// Default font weights for families
						if (!empty($element['typeface'])) {
							
							if (!empty($element['font_weights'])) {
								
								// Manually specified
								$font_weights = explode(',', $element['font_weights']);
								$weights = array();
								
								foreach ($font_weights as $weight) {
									$weights[] = $value['font_name'] . ':' .  $weight;
								}
							}
							else {
								
								// Defaults
								$weights = array(
									$value['font_name'] . ':400', 
									$value['font_name'] . ':600', 
									$value['font_name'] . ':700'
								);
							}
						}
						else {
							
							// Not a typeface, just a single font weight
							$weights = array($value['font_name'] . ':' . $font_weight);
						}
						
						// Register all necessary weights
						$this->google_fonts = array_merge($this->google_fonts, $weights);
						
						// Add the font-family CSS
						$attribs[] = 'font-family: "' . esc_attr($value['font_name']) . '", ' . esc_attr($element['fallback_stack']); 
					}
					
					
					/**
					 * Font size handler
					 */
					if (!empty($value['font_size']) && $value['font_size'] != $default['font_size'])  {
						
						$attribs[] = 'font-size: ' . absint($value['font_size']) . 'px';						
					}
					
				}

				// Add the rules to the CSS selectors
				if (count($attribs)) {
					$this->css[] = $element['css']['selectors'] . ' { ' . implode('; ', $attribs) . '; }';
				}
				
			}
			// Array of selectors to process
			else if (isset($element['css']) && is_array($element['css']['selectors'])) 
			{
				foreach ($element['css']['selectors'] as $css_key => $format)
				{
					// ignore media querie selectors if responsive is disabled
					if (Bunyad::options()->no_responsive && strstr($css_key, '@media')) {
						continue;
					}
					
					if (isset($element['bg_type'])) {
						$element['the_bg_type'] = isset($this->css_options[$key . '_bg_type']) ? $this->css_options[$key . '_bg_type'] : $element['bg_type']['value'];
					}
					
					// create the selector
					$the_css = str_replace("\t", '', $css_key) . ' { ' . $this->create_rules($format, $value, $element); 
					
					// close media queries
					if (strstr($css_key, '@media')) {
						$the_css .= ' }';
					}
					
					$this->css[] = $the_css ." }\n";
				}
			}	
		
		} // end main loop
		
	} // end process()
	
	/**
	 * Create inner CSS rules based on provided format and value
	 * 
	 * @param string $format
	 * @param mixed $value
	 */
	public function create_rules($format, $value, $element = null)
	{
		
		/**
		 * Get value off another element and replace - format: {css_my_key}
		 */
		$format = preg_replace_callback('/{([a-z\_]+?)}/i', array($this, '_interpolate'), $format);

		
		// Add in the current element value
		$format = sprintf($format, $value) . ';';
		
		/** 
		 * RGBA color? Supported formats:
		 * 
		 * 	rgba(%s, 0.6) - indirectly, substituted above
		 *  rgba(#000, %s)
		 *  rgba({css_key}, %s)
		 */
		if (preg_match('/rgba\(([^,]+?),([^,]+?)\)/', $format, $match)) {
			
			if (!$match[1]) {
				$match[1] = $value;
			}

			$rgb = $this->hex2rgb($match[1]);
			$color = $rgb['red'] . ',' . $rgb['green'] . ',' . $rgb['blue'];
			
			// add in the rgb color part in rgba
			$format = str_replace($match[1], $color, $format);
			
		}
		
		$the_css = $format;

		/**
		 * Background image cover or repeat setting
		 */
		if (!empty($element['the_bg_type'])) {

			// get default if not specified
			$bg_type = $element['the_bg_type'];
			
			if ($bg_type == 'cover') {
				$the_css .= 'background-repeat: no-repeat; background-attachment: fixed; background-position: center center; '  
		 		. '-webkit-background-size: cover; -moz-background-size: cover;-o-background-size: cover; background-size: cover;';
			}
			else if ($bg_type == 'cover-nonfixed') {
				$the_css .= 'background-repeat: no-repeat; background-position: center center; background-size: cover;';
			}
			else {
				$the_css .= 'background-repeat: ' . esc_attr($bg_type) .';';
			}
		}
		
		return $the_css;
	}
	
	/**
	 * Helper Callback: String interopolation 
	 */
	private function _interpolate($match) {
		return $this->css_options[ $match[1] ];
	}
	
	/**
	 * Convet hex to rgb
	 * 
	 * @param array $color
	 */
	public function hex2rgb($color) 
	{
		if ($color[0] == '#') {
			$color = substr($color, 1);	
		}
	
		// convert 3 to 6 char hex
		if (strlen($color) == 3) {
			$color = str_repeat($color[0], 2) . str_repeat($color[1], 2) . str_repeat($color[2], 2);
		}
	
		return array(
			'red' => hexdec($color[0] . $color[1]),
			'green' => hexdec($color[2] . $color[3]),
			'blue' => hexdec($color[4] . $color[5])
		);
	}
	
	/**
	 * Get output without the cache part
	 * 
	 * @see get_transient()
	 * @see set_transient()
	 * @see self::process()
	 * 
	 * @param string $key  google_fonts or output
	 * @return mixed
	 */
	public function get_processed($key = '')
	{
		$anchor   = (!empty($this->args['anchor_obj']) ? '_' . $this->args['anchor_obj'] : 0);
		$in_cache = false;

		// Default
		$data   = array(
			'google_fonts' => array(), 
			'output'       => ''
		);

		// Only use cache if not on preview and not disabled
		if (!is_customize_preview() && apply_filters('bunyad_custom_css_nocache', false) !== true) {
			
			// Have data in cache?
			$cache = get_transient('bunyad_custom_css_cache');
			if (is_array($cache) && !empty($cache[$anchor])) {
				$in_cache = true;
				$data     = $cache[$anchor];
			}
		}

		// Process if not cached
		if (!$in_cache) {

			/**
			 * Process to create CSS and enqueues
			 */
			$this->process();
			
			$output = implode("\n", $this->css) . "\n\n" . 
				(!empty($this->css_options['css_custom']) ? wp_specialchars_decode($this->css_options['css_custom']) : '');
			
			// Remove excessive tabs
			$output = str_replace("\t", '', $output);
				
			$data = array(
				'google_fonts' => (array) $this->google_fonts,
				'output'       => $output
			);

			// Cache it
			set_transient('bunyad_custom_css_cache', array($anchor => $data));
		}

		return (!empty($key) ? $data[$key] : $data);
	}

	/**
	 * Add google fonts to the top of CSS
	 */
	public function get_google_fonts_url()
	{
		$fonts = $this->get_processed('google_fonts');

		if (!$fonts) {
			return false;
		}

		$args = array(
			'family' => implode('|', $fonts)
		);

		if (Bunyad::options()->font_charset) {
			$args['subset'] = implode(',', array_filter(Bunyad::options()->font_charset));
		}

		return add_query_arg(
			urlencode_deep($args), 
			'https://fonts.googleapis.com/css'
		);
	}

	/**
	 * Render and return the output
	 * 
	 * @return string
	 */
	public function render()
	{
		return $this->get_processed('output');
	}
}