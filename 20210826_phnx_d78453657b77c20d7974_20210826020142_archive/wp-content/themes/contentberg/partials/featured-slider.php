<?php
/**
 * Featured/Slider Area
 */

if (empty($options_src)) {
	extract(array(
		'slider_number' => Bunyad::options()->slider_posts,
		'slider_tags'   => Bunyad::options()->slider_tag,
		'slider_type'   => Bunyad::options()->home_slider,
		'slider_post_ids' => trim(Bunyad::options()->slider_post_ids),
	));
}
else if ($options_src == 'meta') {
	extract(array(
		'slider_number' => Bunyad::posts()->meta('slider_number'),
		'slider_tags'   => Bunyad::posts()->meta('slider_tags'),
		'slider_type'   => Bunyad::posts()->meta('slider_type'),
		'slider_post_ids' => trim(Bunyad::posts()->meta('slider_post_ids')),
	));
}


// Get latest featured posts
$args = array(
	'order' => 'date', 
	'posts_per_page' => $slider_number, 
	'ignore_sticky_posts' => 1
);

// Limit to tag
$tags = trim($slider_tags);
if ($tags) {
	$args['tag_slug__in'] = array_map('trim', explode(',', $tags));
}

// Limit to certain post ids?
if (!empty($slider_post_ids)) {
	$args['post__in'] = array_map('trim', explode(',', $slider_post_ids));
	$args['orderby']  = 'post__in';
}

$query = new WP_Query(apply_filters('bunyad_slider_args', $args));

// No posts to show? Quit.
if (!$query->have_posts()) {
	return;
}

/**
 * Get slider template
 */

$template = 'partials/slider';
if ($slider_type != 'default') {
	$template .= '-' . sanitize_file_name($slider_type);
}

// Disable lazyload for slider
Bunyad::lazyload()->disable();

Bunyad::core()->partial($template, compact('query'));

Bunyad::lazyload()->enable();