<?php
/**
 * Base widget class for blocks
 */
class ContentBerg_Widgets_Base_Block extends WP_Widget
{
	protected $block_options;
	protected $block_data;

	/** 
	 * @var Bunyad_Admin_OptionRenderer 
	 */
	private $option_renderer;

	public function __construct()
	{
		$blocks   = Bunyad::get('cb_blocks')->get_blocks(false);
		$block_id = $this->get_block_id();
		$block    = $blocks[ $block_id ];

		$this->block_data    = $block;
		$this->block_options = $block['params'];

		parent::__construct(
			'contentberg-block-' . $block_id,
			sprintf(
				esc_html_x('ContentBerg Home - %s', 'Admin', 'contentberg-core'),
				$block['name']
			),
			array(
				'description' => $block['description'], // pre-escaped 
				'classname' => 'ts-block-widget contentberg-widget-' . $block_id
			)
		);
	}

	/**
	 * Deduce block id from class name
	 */
	public function get_block_id() 
	{
		$class = explode('_', get_called_class());
		$id    = end($class);

		// Convert FooBar to Foo_Bar and FooBar3 to Foo_Bar_3
		$id    = preg_replace('/(.)(?=[A-Z])/u', '$1_', $id);
		$id    = preg_replace('/(.)(?=[0-9])/u', '$1_', $id);

		return strtolower($id);
	}

	/**
	 * @inheritDoc
	 */
	public function widget($args, $instance)
	{

		// Use widget area default heading/title?
		if (!empty($instance['heading']) && $instance['heading_type'] == 'widget-area') {
			$title = apply_filters('widget_title', $instance['heading']);

			// Widget default heading style will be used
			$instance['heading_type'] = 'none';

			if (!empty($instance['link'])) {
				$title = '<a href="' . esc_url($instance['link']) . '">'. esc_html($title) .'</a>';
			}
		}

		$column      = 'col-12';
		$has_sidebar = false;
		$layout      = (array) Bunyad::registry()->layout;

		// Set only if it's not inside VC - we don't want to override VC's current column
		if (empty($layout['row_open'])) {
			$layout['col_relative_width'] = 1;
		}

		if (!empty($instance['sidebar'])) {
			$column      = 'col-8';
			$has_sidebar = true;

			// Set column relative width to account for sidebar
			$layout['col_relative_width'] = 8/12;
		}

		Bunyad::registry()->layout = $layout;

		// Execute!
		$shortcode = do_shortcode(
			"[{$this->block_data['base']} " . implode(' ', $this->params_to_shortcode($instance)) . ' /]'
		);

		?>

		<?php echo $args['before_widget']; ?>

		<div class="ts-row cf">
			<div class="<?php echo esc_attr($column); ?>">
				<?php
					if (!empty($title)) {
						echo $args['before_title'] . $title . $args['after_title']; // before_title/after_title are built-in WordPress sanitized
					}
				?>
				<div class="blocks">
					<?php echo $shortcode; ?>
				</div>
			</div>

			<?php 
			if ($has_sidebar):

				// Sidebar container attributes
				$attribs = array('class' => array('col-4 sidebar'));

				$sticky  = 0;
				if ($instance['sidebar_sticky']) {
					$attribs['data-sticky'] = 1;
					$sticky = 1;
				}

				if (!empty($instance['sidebar_class'])) {
					$attribs['class'][] = $instance['sidebar_class'];
				}

			?>
			
			<aside <?php Bunyad::markup()->attribs('sidebar', $attribs); ?>>

				<div class="inner<?php echo ($sticky ? ' theiaStickySidebar' : ''); ?>">
				
				<?php if (is_active_sidebar($instance['sidebar'])) : ?>
					<ul>
						<?php dynamic_sidebar($instance['sidebar']); ?>
					</ul>
				<?php endif; ?>

				</div>

			</aside>

			<?php endif; ?>
		</div>

		<?php echo $args['after_widget']; ?>

		<?php
	}

	/**
	 * Convert params to shortcodes args
	 */
	public function params_to_shortcode($params)
	{
		$sc_attrs = array();
		foreach ($params as $key => $attr) {

			if (is_array($attr)) {				
				$attr = implode(',', $attr);
			}

			$sc_attrs[] = "{$key}=\"" . esc_attr($attr) . '"';
		}
		
		return $sc_attrs;
	}

	/**
	 * @inheritDoc
	 */
	public function update($new, $old)
	{
		// Sanitize all
		array_walk_recursive($new, array($this, 'sanitize_callback'));

		return $new;
	}

	/**
	 * Sanitize all submitted options
	 */
	public function sanitize_callback(&$value, $key) {
		$value = wp_kses_post($value);
	}
	
	/**
	 * Backend form
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) 
	{
		// Get processed blocks here unlike the ones initially
		$blocks   = Bunyad::get('cb_blocks')->get_blocks();
		$block_id = $this->get_block_id();

		// Convert to array with groups
		$options  = $this->tabbed_options($blocks[ $block_id ]['params']);
		$this->option_renderer = Bunyad::factory('admin/option-renderer');

		// Find default values
		$values = array();
		foreach ($this->block_options as $key => $option) {

			$default_key = 'value';
			if (in_array($option['type'], array('dropdown', 'checkbox'))) {
				$default_key = 'std';
			}

			if (!empty($option[$default_key])) {
				$values[$key] = $option[$default_key];
			}
		}

		$values = array_merge($values, $instance);

		foreach ($options as $group => $options) {
			echo '<div class="bunyad-widget-tab"><div class="heading"><span>' . esc_html($group) . '</span></div>';

			foreach ($options as $key => $option) {	

				$this->_render_option(
					$key, 
					$option, 
					isset($values[$key]) ? $values[$key] : null
				);
			}

			echo '</div>';
		}
	}
	
	/**
	 * Convert block options to tabbed and add additional defaults
	 */
	public function tabbed_options($options)
	{
		$new_options = array();

		foreach ($options as $key => $option) {
			$group = !isset($option['group']) ? esc_html__('General', 'Admin', 'contentberg-core') : $option['group'];
			$new_options[$group][$key] = $option;
		}
		
		/**
		 * Add some more default options of sidebar for each block
		 */
		$sidebars = array(
			'' => esc_html_x('Disabled', 'Admin', 'contentberg-core')
		);

		foreach ((array) $GLOBALS['wp_registered_sidebars'] as $sidebar) {

			// Skip - internal only
			if ($sidebar['id'] == 'wp_inactive_widgets') {
				continue;
			}

			$sidebars[$sidebar['id']] = $sidebar['name'];
		}

		$sidebar = esc_html__('Sidebar', 'Admin', 'contentberg-core');
		$new_options[$sidebar] = array(
			'sidebar' => array(
				'heading'     => esc_html__('Show A Sidebar', 'Admin', 'contentberg-core'),
				'type'        => 'dropdown',
				'value'       => array_flip($sidebars),
				'param_name'  => 'sidebar',
				'description' => esc_html__('You can show a sidebar next to the block. Do not enable this if your homepage is already using a sidebar.', 'Admin', 'contentberg-core'),
			),

			'sidebar_sticky' => array(
				'heading'     => esc_html__('Sticky Sidebar?', 'Admin', 'contentberg-core'),
				'type'        => 'checkbox',
				'std'         => 1,
				'param_name'  => 'sidebar_sticky',
				'description' => ''
			),

			'sidebar_class' => array(
				'heading'     => esc_html__('Sidebar Extra Class', 'Admin', 'contentberg-core'),
				'type'        => 'textfield',
				'value'       => '',
				'param_name'  => 'sidebar_class',
				'description' => ''
			)
		);

		return $new_options;
	}

	/**
	 * Render a field for the widget
	 *
	 * @param string $key
	 * @param array $option
	 */
	public function _render_option($key, $option, $value = null)
	{
		$show_label = true;

		switch ($option['type']) {
			case 'textfield':
				$option['type'] = 'text';
				$option['input_class'] = 'widefat';
				
				break;

			case 'dropdown':
				$option['type'] = 'select';
				$option['options'] = array_flip($option['value']);
				$option['class']   = 'widefat';

				break;

			case 'checkbox':
				// Checkbox renderer has a label built-in
				$show_label = false;
				$option['label'] = $option['heading'];

				break;

			case 'posttypes':
				$option['type'] = 'text';
				$option['input_class'] = 'widefat';

				break;
		}

		$option = array_merge($option, array(
			'name'    => $this->get_field_name($option['param_name']),
			'value'   => $value !== null ? $value : '',
			'no_wrap' => true
		));

		/**
		 * Some element specific changes
		 */
		if ($key === 'heading_type') {
			$option['options'] = array_merge($option['options'], array(
				'widget-area' => esc_html_x('Widget Area Default', 'Admin', 'contentberg-core')
			));
		}

		?>

		<div class="bunyad-widget-option">

			<?php if ($show_label): ?>
				
				<label for="<?php echo esc_attr($this->get_field_name($key)); ?>" class="label">
					<?php echo esc_html($option['heading']); ?>
				</label>

			<?php endif; ?>

				<?php echo $this->option_renderer->render($option); ?>

			<?php if (!empty($option['description'])): ?> 

				<p class="small-desc"><?php echo esc_html($option['description']); ?></p>

			<?php endif; ?>

		</div>

		<?php
	}

}