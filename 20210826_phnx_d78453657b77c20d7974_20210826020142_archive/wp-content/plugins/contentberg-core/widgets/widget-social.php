<?php
/**
 * Social Icons Widget
 */
class ContentBerg_Widgets_Social extends WP_Widget 
{

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() 
	{
		parent::__construct(
			'bunyad-widget-social',
			esc_html_x('ContentBerg - Social Icons', 'Admin', 'contentberg-core'),
			array('description' => esc_html_x('Social icons widget.', 'Admin', 'contentberg-core'),  'classname' => 'widget-social')
		);
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) 
	{
		// No icons to show?
		if (empty($instance['social'])) {
			return;
		}
		
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];

		if (!class_exists('Bunyad_Core')) {
			return;
		}
		
		?>
		
			<?php if (!empty($title)): ?>
				
				<?php
					echo $args['before_title'] . esc_html($title) . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
				?>
				
			<?php endif; ?>
		
			<div class="social-icons">
				
				<?php 
				
				/**
				 * Show Social icons
				 */
				$services = Bunyad::get('social')->get_services();
				$links    = Bunyad::options()->social_profiles;
				
				foreach ( (array) $instance['social'] as $icon):
					$social = $services[$icon];
					$url    = !empty($links[$icon]) ? $links[$icon] : '#';
				?>
					<a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank"><i class="fa fa-<?php echo esc_attr($social['icon']); ?>"></i>
						<span class="visuallyhidden"><?php echo esc_html($social['label']); ?></span></a>
				
				<?php
				endforeach;
				?>
				
			</div>
		
		<?php

		echo $args['after_widget'];
	}
	
	/**
	 * Save widget.
	 * 
	 * Strip out all HTML using wp_kses
	 * 
	 * @see wp_kses_post()
	 */
	public function update($new, $old)
	{
		foreach ($new as $key => $val) {

			// Social just needs intval
			if ($key == 'social') {
				
				array_walk($val, 'intval');
				$new[$key] = $val;

				continue;
			}
			
			// Filter disallowed html 			
			$new[$key] = wp_kses_post($val);
		}
		
		return $new;
	}
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$defaults = array('title' => '', 'social' => array());
		$instance = array_merge($defaults, (array) $instance);
		
		extract($instance);
				
		$icons = array(
			'facebook'  => esc_html_x('Facebook', 'Admin', 'contentberg-core'),
			'twitter'   => esc_html_x('Twitter', 'Admin', 'contentberg-core'),
			'gplus'     => esc_html_x('Google Plus', 'Admin', 'contentberg-core'),
			'instagram' => esc_html_x('Instagram', 'Admin', 'contentberg-core'),
			'pinterest' => esc_html_x('Pinterest', 'Admin', 'contentberg-core'),
			'vimeo'     => esc_html_x('Vimeo', 'Admin', 'contentberg-core'),	
			'tumblr'    => esc_html_x('Tumblr', 'Admin', 'contentberg-core'),
			'rss'       => esc_html_x('RSS', 'Admin', 'contentberg-core'),
			'bloglovin' => esc_html_x('BlogLovin', 'Admin', 'contentberg-core'),
			'youtube'   => esc_html_x('Youtube', 'Admin', 'contentberg-core'),
			'dribbble'  => esc_html_x('Dribbble', 'Admin', 'contentberg-core'),
			'linkedin'  => esc_html_x('LinkedIn', 'Admin', 'contentberg-core'),
			'flickr'    => esc_html_x('Flickr', 'Admin', 'contentberg-core'),
			'soundcloud' => esc_html_x('SoundCloud', 'Admin', 'contentberg-core'),
			'lastfm'     => esc_html_x('Last.fm', 'Admin', 'contentberg-core'),
			'vk'         => esc_html_x('VKontakte', 'Admin', 'contentberg-core'),
			'steam'      => esc_html_x('Steam', 'Admin', 'contentberg-core'),
		);
		
		?>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html_x('Title:', 'Admin', 'contentberg-core'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		
		<div>
			<label for="<?php echo esc_attr($this->get_field_id('social')); ?>"><?php echo esc_html_x('Social Icons:', 'Admin', 'contentberg-core'); ?></label>
			
			<?php foreach ($icons as $icon => $label): ?>
			
				<p>
					<label>
						<input class="widefat" type="checkbox" name="<?php echo esc_attr($this->get_field_name('social')); ?>[]" value="<?php echo esc_attr($icon); ?>"<?php 
						echo (in_array($icon, $social) ? ' checked' : ''); ?> /> 
					<?php echo esc_html($label); ?></label>
				</p>
			
			<?php endforeach; ?>
			
			<p class="bunyad-note"><strong><?php echo esc_html_x('Note:', 'Admin', 'contentberg-core'); ?></strong>
				<?php echo esc_html_x('Configure URLs from Customize > General Settings > Social Media Links.', 'Admin', 'contentberg-core'); ?></p>
			
		</div>
	
	
		<?php
	}
}
