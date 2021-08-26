<?php
/**
 * Options for posts metabox
 */
$options = array(
	array(
		'label' => esc_html_x('Layout Style', 'Admin', 'contentberg-core'),
		'name'  => 'layout_style', // will be _bunyad_layout_style
		'desc'  => esc_html_x('Default uses the site-wide general layout setting set in Appearance > Customize.', 'Admin', 'contentberg-core'),
		'type'  => 'radio',
		'options' => array(
			'' => esc_html_x('Default', 'Admin', 'contentberg-core'),
			'right' => esc_html_x('Right Sidebar', 'Admin', 'contentberg-core'),
			'full' => esc_html_x('Full Width', 'Admin', 'contentberg-core')),
		'value' => '' // default
	),
	
	array(
		'label' => esc_html_x('Post Style', 'Admin', 'contentberg-core'),
		'name'  => 'layout_template', // will be _bunyad_featured_slider
		'desc'  => esc_html_x('Default uses the global settings set in Appearance > Customize', 'Admin', 'contentberg-core'),
		'type'  => 'radio',
		'options' => array(
			''  => esc_html_x('Default', 'Admin', 'contentberg-core'),
			'creative' => esc_html_x('Creative - Large Style', 'Admin', 'contentberg-core'),
			'cover' => esc_html_x('Cover - Overlay Style ', 'Admin', 'contentberg-core'),
			'dynamic'  => esc_html_x('Dynamic (Affects Full Width Layout Only)', 'Admin', 'contentberg-core'),
			'classic'  => esc_html_x('Classic', 'Admin', 'contentberg-core'),
			'magazine' => esc_html_x('Magazine/News Style', 'Admin', 'contentberg-core'),
		),
		'value' => '' // default
	),

	array(
		'label' => esc_html_x('Spacious Style?', 'Admin', 'contentberg-core'),
		'name'  => 'layout_spacious',
		'desc' => esc_html_x('Enable to add extra left/right spacing to text to create a dynamic spacious feel. Especially great when used with Full Width.', 'Admin', 'contentberg-core'),
		'type'  => 'select',
		'options' => array(
			'_default' => esc_html_x('Default', 'Admin', 'contentberg-core'),
			'1' =>  esc_html_x('Yes', 'Admin', 'contentberg-core'),
			'0' =>  esc_html_x('No', 'Admin', 'contentberg-core')
		),
		'value' => '_default',
	),
		
	array(
		'label' => esc_html_x('Sub Title', 'Admin', 'contentberg-core'),
		'name'  => 'sub_title',
		'type'  => 'text',
		'input_size' => 90,
		'desc' => esc_html_x('Optional Sub-title/text thats displayed below main post title.', 'Admin', 'contentberg-core')
	),
	
	array(
		'label' => esc_html_x('Primary Category', 'Admin', 'contentberg-core'),
		'name'  => 'cat_label',
		'type'  => 'html',
		'html' =>  wp_dropdown_categories(array(
			'show_option_all' => esc_html_x('-- Auto Detect--', 'Admin', 'contentberg-core'), 
			'hierarchical' => 1, 'order_by' => 'name', 'class' => '', 
			'name' => '_bunyad_cat_label', 'echo' => false,
			'selected' => Bunyad::posts()->meta('cat_label')
		)),
		'desc' => esc_html_x('When you have multiple categories for a post, auto detection chooses one in alphabetical order. This setting is used for selecting the correct category in post meta.', 'Admin', 'contentberg-core')
	),
		
	array(
		'label_left' => esc_html_x('Disable Featured?', 'Admin', 'contentberg-core'),
		'label' => esc_html_x('Do not show featured Image, Video, or Gallery at the top for this post, on post page.', 'Admin', 'contentberg-core'),
		'name'  => 'featured_disable', // _bunyad_featured_post
		'type'  => 'checkbox',
		'value' => 0
	),

	array(
		'label' => esc_html_x('Featured Video/Audio Link', 'Admin', 'contentberg-core'),
		'name'  => 'featured_video', 
		'type'  => 'text',
		'input_size' => 90,
		'value' => '',
		'desc'  => esc_html_x('When using Video or Audio post format, enter a link of the video or audio from a service like YouTube, Vimeo, SoundCloud. ', 'Admin', 'contentberg-core'),
	),
);
