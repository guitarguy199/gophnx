<?php
/**
 * Widget to show posts in a list format in sidebar
 */
class ContentBerg_Widgets_Slider extends WP_Widget
{
	/**
	 * Setup the widget
	 * 
	 * @see WP_Widget::__construct()
	 */
	public function __construct()
	{
		parent::__construct(
			'bunyad-slider-widget',
			esc_html_x('ContentBerg - Posts Slider', 'Admin', 'contentberg-core'),
			array('description' => esc_html_x('Show a posts slider.', 'Admin', 'contentberg-core'), 'classname' => 'widget-slider')
		);
	}
	
	/**
	 * Widget output 
	 * 
	 * @see WP_Widget::widget()
	 */
	public function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		// Setup the query
		$query_args  = array('posts_per_page' => $instance['number'], 'ignore_sticky_posts' => 1);
		
		// Popular by comments
		if ($instance['type'] == 'popular') {
			$query_args = array_merge($query_args, array('orderby' => 'comment_count'));
		}
		
		// Most liked
		if ($instance['type'] == 'liked') {
			$query_args = array_merge($query_args, array(
		 		'meta_key' => '_sphere_user_likes_count', 'orderby' => 'meta_value_num'
			));
		}
		
		// Most Viewed (WP-PostViews plugin)
		if ($instance['type'] == 'views') {
			$query_args = array_merge($query_args, array(
				'meta_key' => 'views', 
				'orderby' => 'meta_value_num', 
				'order' => 'DESC'
			));
		}
		
		// Limited by tag?
		if (!empty($instance['limit_tag'])) {
			$query_args = array_merge($query_args, array('tag' => $instance['limit_tag']));
		}
		
		// Limited by category?
		if (!empty($instance['limit_cat'])) {
			$query_args = array_merge($query_args, array('cat' => $instance['limit_cat']));
		}

		if (!class_exists('Bunyad_Core')) {
			return;
		}
		
		$query = new WP_Query(apply_filters('bunyad_widget_slider_query_args', $query_args));		
		
		?>

		<?php echo $args['before_widget']; ?>
		
			<?php if (!empty($title)): ?>
				
				<?php
					echo $args['before_title'] . esc_html($title) . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
				?>
				
			<?php endif; ?>
			
			<div class="slides">
			
				<?php while ($query->have_posts()): $query->the_post(); ?>
			
					<div class="item">
						<a href="<?php the_permalink(); ?>"><?php 
							the_post_thumbnail(
								Bunyad::media()->image_size('contentberg-widget-slider', 'large'), 
								array('alt' => strip_tags(get_the_title()), 'title' => '')
							); 
						?></a>
						
						<div class="content cf">
						
							<?php Bunyad::core()->partial('partials/post-meta-b', array('show_comments' => 0, 'title_class' => 'post-title')); ?>
							
						</div>
						
					</div>
					
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		
		<?php echo $args['after_widget']; ?>
		
		<?php
	}
	
	/**
	 * Save widget
	 * 
	 * Strip out all HTML using wp_kses
	 * 
	 * @see wp_filter_post_kses()
	 */
	public function update($new, $old)
	{
		foreach ($new as $key => $val) {
			$new[$key] = wp_kses_post($val);
		}
		
		return $new;
	}
	
	/**
	 * The widget form
	 */
	public function form($instance)
	{
		$defaults = array(
			'title' => '', 
			'type' => '', 
			'number' => 4,
			'limit_tag' => '', 
			'limit_cat' => ''
		);
		
		$instance = array_merge($defaults, (array) $instance);
		extract($instance);
		
		?>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html_x('Title:', 'Admin', 'contentberg-core'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('type')); ?>"><?php echo esc_html_x('Sorting:', 'Admin', 'contentberg-core'); ?></label>
			
			<select id="<?php echo esc_attr($this->get_field_id('type')); ?>" name="<?php echo esc_attr($this->get_field_name('type')); ?>" class="widefat">
				<option value="" <?php selected($type, ''); ?>><?php echo esc_html_x('Latest Posts', 'Admin', 'contentberg-core') ?></option>
				<option value="popular" <?php selected($type, 'popular'); ?>><?php echo esc_html_x('Most Commented', 'Admin', 'contentberg-core'); ?></option>
				<option value="liked" <?php selected($type, 'liked'); ?>><?php echo esc_html_x('Most Liked', 'Admin', 'contentberg-core'); ?></option>
				
				<?php if (function_exists('get_most_viewed')): ?>
					<option value="views" <?php selected($type, 'views'); ?>><?php echo esc_html_x('By Views (WP-PostViews Plugin)', 'Admin', 'contentberg-core'); ?></option>
				<?php endif; ?>
				
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php echo esc_html_x('Number of posts to show:', 'Admin', 'contentberg-core'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" />
		</p>		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('limit_tag')); ?>"><?php echo esc_html_x('From this Tag (Optional):', 'Admin', 'contentberg-core'); ?></label>
			
			<input id="<?php echo esc_attr($this->get_field_id('limit_tag')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('limit_tag')); ?>" type="text" class="widefat" value="<?php echo esc_attr($limit_tag); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('limit_cat')); ?>"><?php echo esc_html_x('Limit to Category (Optional):', 'Admin', 'contentberg-core'); ?></label>
			<?php wp_dropdown_categories(array(
					'show_option_all' => esc_html_x('-- Not Limited --', 'Admin', 'contentberg-core'), 
					'hierarchical' => 1,
					'hide_empty' => 0,
					'order_by' => 'name', 
					'class' => 'widefat', 
					'name' => $this->get_field_name('limit_cat'), 
					'selected' => $limit_cat
			)); ?>
		</p>	
	
	
		<?php
	}
}