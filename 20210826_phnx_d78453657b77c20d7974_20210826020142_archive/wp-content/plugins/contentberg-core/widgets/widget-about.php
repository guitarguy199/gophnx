<?php
/**
 * About Me Widget
 */
class ContentBerg_Widgets_About extends WP_Widget
{
	/**
	 * Setup the widget
	 * 
	 * @see WP_Widget::__construct()
	 */
	public function __construct()
	{
		parent::__construct(
			'bunyad-widget-about',
			esc_html_x('ContentBerg - About Widget', 'Admin', 'contentberg-core'),
			array('description' => esc_html_x('"About" widget suitable for sidebar.', 'Admin', 'contentberg-core'), 'classname' => 'widget-about')
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
		$title = apply_filters('widget_title', esc_html($instance['title']));
		
		?>

		<?php echo $args['before_widget']; ?>
		
			<?php if (!empty($title)): ?>
				
				<?php
					echo $args['before_title'] . esc_html($title) . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
				?>
				
			<?php endif; ?>
		
			<?php if (!empty($instance['image'])): ?>
				
				<div class="author-image<?php echo (!empty($instance['image_circle']) ? ' image-circle' : ''); ?>">
					<img src="<?php echo esc_url($instance['image']); ?>" alt="<?php esc_attr_e('About Me', 'contentberg-core'); ?>" />
				</div>
				
			<?php endif; ?>
			
			<div class="text about-text"><?php echo wp_kses_post(
				do_shortcode(apply_filters('shortcode_cleanup', wpautop($instance['text']))
			)); ?></div>
			
			<?php if (!empty($instance['read_more'])): ?>
			
			<div class="about-footer cf">
			
				<?php if (!empty($instance['read_more'])): ?>
					<a href="<?php echo esc_url($instance['read_more']); ?>" class="more"><?php esc_html_e('Read More', 'contentberg-core'); ?></a>
				<?php endif; ?>
				
			</div>
			
			<?php endif; ?>
			
			
			<?php if (!empty($instance['social'])): ?>
			<div class="social-icons">
				
				<?php 
				
				if (is_object(Bunyad::get('social'))):
				
					/**
					 * Show Social icons
					 */
					$services = Bunyad::get('social')->get_services();
					$links    = Bunyad::options()->social_profiles;
					
					foreach ( (array) $instance['social'] as $icon):
						$social = $services[$icon];
						$url    = !empty($links[$icon]) ? $links[$icon] : '#';
					?>
						<a href="<?php echo esc_url($url); ?>" class="social-btn" target="_blank"><i class="fa fa-<?php echo esc_attr($social['icon']); ?>"></i>
							<span class="visuallyhidden"><?php echo esc_html($social['label']); ?></span></a>
					
				<?php
					endforeach;
				endif;
				?>
				
			</div>
			<?php endif; ?>
			
			
			<?php if (!empty($instance['text_below'])): ?>
			
			<div class="text about-text below">
				<?php echo wp_kses_post(
					do_shortcode(apply_filters('shortcode_cleanup', wpautop($instance['text_below']))
				)); ?>
			</div>
			
			<?php endif; ?>
		
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
			
			// Social just needs intval
			if ($key == 'social') {
				
				array_walk($val, 'intval');
				$new[$key] = $val;

				continue;
			}
			
			// Filter disallowed html 			
			$new[$key] = wp_kses_post($val);
		}
		
		// Spaces left in commonly
		$new['read_more'] = trim($new['read_more']);
		$new['image_circle'] = !empty($new['image_circle']) ? 1 : 0;
		
		return $new;
	}
	
	/**
	 * The widget form
	 */
	public function form($instance)
	{
		$defaults = array(
			'title' => 'About', 
			'image' => '', 
			'image_circle' => '', 
			'text' => '', 
			'read_more' => '', 
			'social' => array(), 
			'text_below' => ''
		);

		$instance = array_merge($defaults, (array) $instance);
		
		// Social options
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
		
		extract($instance);

		?>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html_x('Title:', 'Admin', 'contentberg-core'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php echo esc_html_x('About:', 'Admin', 'contentberg-core'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('text')); ?>" rows="5"><?php echo esc_textarea($text); ?></textarea>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('image')); ?>"><?php echo esc_html_x('Image URL: (optional)', 'Admin', 'contentberg-core'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('image')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('image')); ?>" type="text" value="<?php echo esc_attr($image); ?>" />
			<small><?php echo esc_html_x('You can add an image above title. Recommended size: 370px.', 'Admin', 'contentberg-core'); ?></small>
		</p>
		
		<p>
			<input class="widefat" type="checkbox" id="<?php echo esc_attr($this->get_field_id('image_circle')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('image_circle')); ?>" value="1" <?php checked($image_circle); ?>/>
				
			<label for="<?php echo esc_attr($this->get_field_id('image_circle')); ?>"><?php echo esc_html_x('Rounded / Circular Image?', 'Admin', 'contentberg-core'); ?></label>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('read_more')); ?>"><?php echo esc_html_x('Read More Link: (optional)', 'Admin', 'contentberg-core'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('read_more')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('read_more')); ?>" type="text" value="<?php echo esc_attr($read_more); ?>" />
		</p>
	
		<div>
			<label for="<?php echo esc_attr($this->get_field_id('social')); ?>"><?php echo esc_html_x('Social Icons: (optional)', 'Admin', 'contentberg-core'); ?></label>
			
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
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('text_below')); ?>"><?php echo esc_html_x('Text Below (optional):', 'Admin', 'contentberg-core'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text_below')); ?>" name="<?php 
				echo esc_attr($this->get_field_name('text_below')); ?>" rows="3"><?php echo esc_textarea($text_below); ?></textarea>
		</p>
	
		<?php
	}
}