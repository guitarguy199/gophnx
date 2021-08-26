<?php

class ContentBerg_Blocks
{
	public $cached;

	public function get_blocks($process = true)
	{
		// If processing is not required
		if ($process) {

			/**
			 * Get categories list for dropdown options 
			 */
			$categories = get_terms('category', array(
				'hide_empty' => 0,
				'hide_if_empty' => false,
				'hierarchical' => 1, 
				'order_by' => 'name' 
			));
		
			$categories = array_merge(
				array(esc_html_x('-- None / Not Limited --', 'Admin', 'contentberg-core') => ''), 
				$this->_recurse_terms_array(0, $categories)
			);
		}
		else {
			$categories = array();
		}

		// Not processing and have cache?
		if (!$process && $this->cached) {
			return $this->cached;
		}
		
		/**
		 * The default options generally shared between blocks 
		 */
		$common = array(
	
			'posts' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('Number of Posts', 'Admin', 'contentberg-core'),
				'value'  => 5,
				'param_name' => 'posts',
				'admin_label' => false,
			),
			
			'sort_by' => array(
				'type' => 'dropdown',
				'heading' => esc_html_x('Sort By', 'Admin', 'contentberg-core'),
				'value'  => array(
					esc_html_x('Published Date', 'Admin', 'contentberg-core') => '',
					esc_html_x('Modified Date', 'Admin', 'contentberg-core') => 'modified',
					esc_html_x('Random', 'Admin', 'contentberg-core') => 'random',
					esc_html_x('Comment Count', 'Admin', 'contentberg-core') => 'comments',
					esc_html_x('Alphabetical', 'Admin', 'contentberg-core') => 'alphabetical',
					esc_html_x('Most Liked', 'Admin', 'contentberg-core') => 'liked'
				),
				'param_name' => 'sort_by',
				'admin_label' => false,
			),
			
			'sort_order' => array(
				'type' => 'dropdown',
				'heading' => esc_html_x('Sort Order', 'Admin', 'contentberg-core'),
				'value'  => array(
					esc_html_x('Descending - Higher to lower (Latest First)', 'Admin', 'contentberg-core') => 'desc',
					esc_html_x('Ascending - Lower to Higher (Oldest First)', 'Admin', 'contentberg-core')  => 'asc',
				),
				'param_name' => 'sort_order',
				'admin_label' => false,
			),
			
			'heading' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('Heading  (Optional)', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('By default, the main selected category\'s name is used as the title.', 'Admin', 'contentberg-core'),
				'value'  => '',
				'param_name' => 'heading',
				'admin_label' => true,
			),
			
			'heading_type' => array(
				'type' => 'dropdown',
				'heading' => esc_html_x('Heading Type', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('Use Small Headings for 1/3 columns. Default headings are good for full-width and half column blocks.', 'Admin', 'contentberg-core'),
				'value'  => array(
					esc_html_x('Magazine Block - Simple', 'Admin', 'contentberg-core') => 'head-c',
					esc_html_x('Magazine Block', 'Admin', 'contentberg-core') => 'modern',
					esc_html_x('Blog Style', 'Admin', 'contentberg-core') => 'blog',
					esc_html_x('Disabled', 'Admin', 'contentberg-core') => 'none',
				),
				'param_name' => 'heading_type',
			),
			
			'view_all' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('View All Text (Optional)', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('If not empty, this text will show with heading link.', 'Admin', 'contentberg-core'),
				'value' => '',
				'param_name' => 'view_all',
			),
			
			'link' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('Heading Link (Optional)', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('By default, the main selected category\'s link is used.', 'Admin', 'contentberg-core'),
				'value'  => '',
				'param_name' => 'link',
			),
				
			'offset' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('Advanced: Offset', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('An offset can be used to skip first X posts from the results.', 'Admin', 'contentberg-core'),
				'value'  => '',
				'param_name' => 'offset',
			),
				
			'cat' => array(
				'type' => 'dropdown',
				'heading' => esc_html_x('From Category', 'Admin', 'contentberg-core'),
				'description' => __('Posts will be limited to this category', 'contentberg-core'),
				'value'  => $categories,
				'param_name' => 'cat',
				'admin_label' => true,
				'group' => esc_html_x('Refine Posts', 'Admin', 'contentberg-core'),
			),
				
			'terms' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('From Multiple Categories', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('If you need posts from more categories. Enter cat slugs separated by commas. Example: beauty,world-news', 'Admin', 'contentberg-core'),
				'value'  => '',
				'param_name' => 'terms',
				'group' => esc_html_x('Refine Posts', 'Admin', 'contentberg-core'),
			),
				
			'tags' => array(
				'type' => 'textfield',
				'heading' => esc_html_x('Posts From Tags', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('A single or multiple tags. Enter tag slugs separated by commas. Example: food,sports', 'Admin', 'contentberg-core'),
				'value'  => '',
				'param_name' => 'tags',
				'group' => esc_html_x('Refine Posts', 'Admin', 'contentberg-core'),
			),

			
			'post_format' => array(
				'type' => 'dropdown',
				'heading' => esc_html_x('Post Format', 'Admin', 'contentberg-core'),
				'description' => '',
				'value'  => array(
					esc_html_x('All', 'Admin', 'contentberg-core') => '',
					esc_html_x('Video', 'Admin', 'contentberg-core') => 'video',
					esc_html_x('Gallery', 'Admin', 'contentberg-core') => 'gallery',
					esc_html_x('Image', 'Admin', 'contentberg-core') => 'image',
				),
				'param_name' => 'post_format',
				'group' => esc_html_x('Refine Posts', 'Admin', 'contentberg-core'),
			),
				
			'post_type' => array(
				'type' => 'posttypes',
				'heading' => esc_html_x('Advanced: Post Types', 'Admin', 'contentberg-core'),
				'description' => esc_html_x('Use this feature if Custom Post Types are needed.', 'Admin', 'contentberg-core'),
				'value'  => '',
				'param_name' => 'post_type',
				'group' => esc_html_x('Refine Posts', 'Admin', 'contentberg-core'),
			),
		);
		
		$common = apply_filters('contentberg_block_common_params', $common);
		
		// Pagination types for the blocks
		$pagination_types = array(
			'load-more' => esc_html_x('Load More (AJAX)', 'Admin', 'contentberg-core'),
			'numbers' => esc_html_x('Page Numbers (AJAX)', 'Admin', 'contentberg-core'),
			''  => esc_html_x('Older / Newer (Only if one or last block)', 'Admin', 'contentberg-core'),
		);
				
		/**
		 * Highlights block
		 */
		$blocks['highlights'] = array(
			'name' => esc_html_x('Highlights Block', 'Admin', 'contentberg-core'),
			'base' => 'highlights',
			'description' => esc_html_x('Run-down of news from a category.', 'Admin', 'contentberg-core'),
			'class' => 'sphere-icon',
			'icon' => 'tsb-highlights',
			'category' => esc_html_x('Home Blocks', 'Admin', 'contentberg-core'),
			'weight' => 1,
			'params' => $common,
		);
		
		
		/**
		 * News Grid block
		 */
		$blocks['news_grid'] = array(
			'name' => esc_html_x('News Grid Block', 'Admin', 'contentberg-core'),
			'base' => 'news_grid',
			'description' => esc_html_x('News in a compact grid.', 'Admin', 'contentberg-core'),
			'class' => 'sphere-icon',
			'icon' => 'tsb-news-grid',
			'category' => esc_html_x('Home Blocks', 'Admin', 'contentberg-core'),
			'weight' => 1,
			'params' => $common,
		);
		
		
		/**
		 * Blog/Listing block
		 */	
		
		// Blog listing types
		$listings = array(
			''     => esc_html_x('Default (Category Loop from Customizer)', 'Admin', 'contentberg-core'),
			'loop-classic' => esc_html_x('Classic Large Posts', 'Admin', 'contentberg-core'),
			'loop-1st-large' => esc_html_x('One Large Post + Grid', 'Admin', 'contentberg-core'),
			'loop-1st-large-list' => esc_html_x('One Large Post + List', 'Admin', 'contentberg-core'),
			'loop-1st-overlay' => esc_html_x('One Overlay Post + Grid', 'Admin', 'contentberg-core'),
			'loop-1st-overlay-list' => esc_html_x('One Overlay Post + List', 'Admin', 'contentberg-core'),
				
			'loop-1-2' => esc_html_x('Mixed: Large Post + 2 Grid ', 'Admin', 'contentberg-core'),
			'loop-1-2-list' => esc_html_x('Mixed: Large Post + 2 List ', 'Admin', 'contentberg-core'),

			'loop-1-2-overlay' => esc_html_x('Mixed: Overlay Post + 2 Grid ', 'Admin', 'contentberg-core'),
			'loop-1-2-overlay-list' => esc_html_x('Mixed: Overlay Post + 2 List ', 'Admin', 'contentberg-core'),
				
			'loop-list' => esc_html_x('List Posts', 'Admin', 'contentberg-core'),
			'loop-grid' => esc_html_x('Grid Posts', 'Admin', 'contentberg-core'),
			'loop-grid-3' => esc_html_x('Grid Posts (3 Columns)', 'Admin', 'contentberg-core'),
		);
		

		// Block settings
		$blog = array_merge(array(
				'type' => array(
					'type' => 'dropdown',
					'heading' => esc_html_x('Listing Type', 'Admin', 'contentberg-core'),
					'description' => '',
					'value'  => array_flip($listings),
					'param_name' => 'type',
				),
				
				'show_excerpt' => array(
					'type' => 'checkbox',
					'heading' => esc_html_x('Show Excerpts?', 'Admin', 'bunyad'),
					'param_name' => 'show_excerpt',
					'value' => array(
						esc_html_x('Yes', 'Admin', 'contentberg-core') => 1
					),
					'std' => 1,
					'dependency' => array(
						'element' => 'type',
						'value'   => array_values(array_diff(
							array_keys($listings), 
							array('loop-1st-overlay', 'loop-1st-overlay-list', 'loop-1-2-overlay', 'loop-1-2-overlay-list')
						)),
					)
				),

				'show_footer' => array(
					'type' => 'checkbox',
					'heading' => esc_html_x('Show Posts Footer?', 'Admin', 'bunyad'),
					'description' => esc_html_x('Enable to show Social icons or Read More depending on grid post style chosen in customizer.', 'bunyad'),
					'param_name' => 'show_footer',
					'value' => array(
						esc_html_x('Yes', 'Admin', 'contentberg-core') => 1,
					),
					'std' => 1,
					'dependency' => array(
						'element' => 'type',
						'value'   => array('loop-grid', 'loop-grid-3', 'loop-1st-large'),
					)
				),
				
				'pagination' => array(
					'type' => 'dropdown',
					'heading' => esc_html_x('Pagination', 'Admin', 'contentberg-core'),
					'value'  => array(
						esc_html_x('Disabled', 'Admin', 'contentberg-core') => '',
						esc_html_x('Enabled', 'Admin', 'contentberg-core') => '1',
					),
					'param_name' => 'pagination',
				),
				
				'pagination_type' => array(
					'type' => 'dropdown',
					'heading' => esc_html_x('Pagination Type', 'Admin', 'contentberg-core'),
					'value'   => array_flip($pagination_types),
					'param_name' => 'pagination_type',
					'dependency' => array(
						'element' => 'pagination',
						'value'   => array('1')
					),
					'std' => 'load-more'
				),
			), $common
		);

		$blog['posts']['value'] = 6;
		
		$blocks['blog'] = array(
			'name' => sprintf(esc_html_x('Posts (%s Layouts)', 'Admin', 'contentberg-core'), count($listings) - 1),
			'description' => esc_html_x('For blog/category style listings. Multiple listing styles.', 'Admin', 'contentberg-core'),
			'base' => 'blog',
			'icon' => 'tsb-post-listings',
			'class' => 'sphere-icon',
			'weight' => 1,
			'category' => esc_html_x('Home Blocks', 'Admin', 'contentberg-core'),
			'params' => $blog,
		);
		
		foreach ($listings as $id => $text) {
			
			// Skip the default
			if (empty($id)) {
				continue;
			}
			
			$params = $blog;
			$params['type'] = array_merge($params['type'], array(
				'value' => $id,
				'type'  => 'hidden'
			));

			// Excerpt length only applies to these 3
			if (in_array($id, array('loop-list', 'loop-grid', 'loop-grid-3'))) {
				$params['excerpt_length'] = array(
					'type' => 'textfield',
					'heading' => esc_html_x('Excerpt Length', 'Admin', 'bunyad'),
					'description' => esc_html_x('Leave empty for default.', 'Admin', 'contentberg-core'),
					'value'  => '',
					'param_name' => 'excerpt_length',
					'dependency' => array(
						'element' => 'show_excerpt',
						'value'   => array('1')
					),
				);
			}
			
			// Shortcodes are registered as loop_grid_ - using Hyphens in shortcodes is dangerous
			$block_id = str_replace('-', '_', $id);

			$blocks[$block_id] = array(
				'name' => $text,
				'description' => '',
				'base' => $block_id,
				'icon' => 'tsb-' . $id . '',
				'class' => 'sphere-icon',
				'weight' => 1,
				'category' => esc_html_x('Home Blocks', 'Admin', 'contentberg-core'),
				'params' => $params,
			);
		}
		
		/**
		 * Ads block
		 */
		$blocks['ts_ads'] = array(
			'name' => esc_html_x('Advertisement Block', 'Admin', 'contentberg-core'),
			'description' => esc_html_x('Advertisement code block.', 'Admin', 'contentberg-core'),
			'base' => 'ts_ads',
			'icon' => 'icon-wpb-wp',
			'category' => esc_html_x('Home Blocks', 'Admin', 'contentberg-core'),
			'weight' => 0,
			'params' => array(
				'code' => array(
					'type' => 'textarea_raw_html',
					'heading' => esc_html_x('Ad Code', 'Admin', 'contentberg-core'),
					'description' => esc_html_x('Enter your ad code here.', 'Admin', 'contentberg-core'),
					'param_name' => 'code',
				),
			),
		);

		$this->cached = $blocks;

		return $blocks;
	}
	
	/**
	 * Create category drop-down via recursion on parent-child relationship
	 * 
	 * @param integer  $parent
	 * @param object   $terms
	 * @param integer  $depth
	 */
	public function _recurse_terms_array($parent, $terms, $depth = 0)
	{	
		$the_terms = array();
			
		$output = array();
		foreach ($terms as $term) {
			
			// add tab to children
			if ($term->parent == $parent) {
				$output[str_repeat(" - ", $depth) . $term->name] = $term->term_id;			
				$output = array_merge($output, $this->_recurse_terms_array($term->term_id, $terms, $depth+1));
			}
		}
		
		return $output;
	}
}

// init and make available in Bunyad::get('cb_blocks')
Bunyad::register('cb_blocks', array(
	'class' => 'ContentBerg_Blocks',
	'init' => true
));
