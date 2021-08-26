<?php
/**
 * Options for page metabox
 */
$options = array(
	array(
		'label' => esc_html_x('Layout Type', 'Admin', 'contentberg-core'),
		'name'  => 'layout_style', // will be _bunyad_layout_style
		'desc'  => esc_html_x('Default uses the site-wide general layout setting set in Appearance > Customize.', 'Admin', 'contentberg-core'),
		'type'  => 'radio',
		'options' => array(
			'' => esc_html_x('Default', 'Admin', 'contentberg-core'),
			'right' => esc_html_x('Right Sidebar', 'Admin', 'contentberg-core'),
			'full'  => esc_html_x('Full Width', 'Admin', 'contentberg-core')),
		'value' => '' // default
	),

	array(
		'label' => esc_html_x('Show Page Title?', 'Admin', 'contentberg-core'),
		'name'  => 'page_title', 
		'type'  => 'select',
		'options' => array(
			''    => esc_html_x('Default', 'Admin', 'contentberg-core'),
			'yes' => esc_html_x('Yes', 'Admin', 'contentberg-core'),
			'no' => esc_html_x('No', 'Admin', 'contentberg-core')
		),
		'value' => '' // default
	),
	
	array(
		'label' => esc_html_x('Featured Grid/Slider', 'Admin', 'contentberg-core'),
		'name'  => 'featured_slider', // will be _bunyad_featured_slider
		'desc'  => esc_html_x('For home-page, can also be set from use Appearance > Customize > Homepage > Home Slider.', 'Admin', 'contentberg-core'),
		'type'  => 'select',
		'options' => array(
			0 => esc_html_x('Disabled', 'Admin', 'contentberg-core'),
			1 => esc_html_x('Enabled', 'Admin', 'contentberg-core'),
		),
		'value' => 0 // default
	),

	array(
		'label' => esc_html_x('Featured Style', 'Admin', 'contentberg-core'),
		'name'  => 'slider_type',
		'type'  => 'select',
		'options' => array(
			'stylish' => esc_html_x('Stylish (3 images)', 'Admin', 'contentberg-core'),
			'fashion' => esc_html_x('Fashion (Single Image)', 'Admin', 'contentberg-core'),
			'grid-tall' => esc_html_x('Tall Grid (1 Large + 2 small)', 'Admin', 'contentberg-core'),
			'default'   => esc_html_x('Classic Slider (3 Images)', 'Admin', 'contentberg-core'),
			'carousel'  => esc_html_x('Carousel (3 Small Posts)', 'Admin', 'contentberg-core'),
			'bold'    => esc_html_x('Bold Full Width', 'Admin', 'contentberg-core'),
		),
		'value' => '' // default
	),

	array(
		'label' => esc_html_x('Slider Post Count', 'Admin', 'contentberg-core'),
		'name'  => 'slider_number',
		'type'  => 'text',
		'desc'  => esc_html_x('Total number of posts for slider.', 'Admin', 'contentberg-core'),
		'value' => 6, // default
	),
	
	array(
		'label' => esc_html_x('Slider Posts Tag', 'Admin', 'contentberg-core'),
		'name'  => 'slider_tags',
		'desc'  => esc_html_x('Posts with this tag will be shown in the slider. Leaving it empty will show latest posts.', 'Admin', 'contentberg-core'),
		'type'  => 'text',
		'value' => 'featured' // default
	),
		
	array(
		'name' => 'slider_post_ids',
		'label'   => esc_html_x('Slider Post IDs', 'Admin', 'contentberg-core'),
		'value'   => '',
		'desc'    => esc_html_x('Advance Usage: Enter post ids separated by comma you wish to show in the slider, in order you wish to show them in. Example: 11, 105, 2', 'Admin', 'contentberg-core'),
		'type'    => 'text',
	),

);