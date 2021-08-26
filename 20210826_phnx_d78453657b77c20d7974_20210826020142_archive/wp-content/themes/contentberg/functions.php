<?php
/**
 * ContentBerg Theme!
 * 
 * This is the typical theme initialization file. Sets up the Bunyad Framework
 * and the theme functionality.
 * 
 * ----
 * 
 * Code Locations:
 * 
 *  /          -  WordPress default template files.
 *  lib/       -  Contains the Bunyad Framework files.
 *  inc/       -  Theme related functionality and some HTML helpers.
 *  admin/     -  Admin-only content.
 *  partials/  -  Template parts (partials) called via get_template_part().
 *  
 * Note: If you're looking to edit HTML, look for default WordPress templates in
 * top-level / and in partials/ folder.
 * 
 * Main Theme file:  inc/theme.php 
 */

// Already initialized?
if (class_exists('Bunyad_Core')) {
	return;
}

// Require PHP 5.3.2+
if (version_compare(phpversion(), '5.6.0', '<')) {

	function contentberg_php_notice() {
		$message = sprintf(esc_html__('ContentBerg requires %1$sPHP 5.6.0+%2$s. Please ask your webhost to upgrade to at least PHP 5.6.0. Recommended: %1$sPHP 7+%2$s%3$s', 'contentberg'), '<strong>', '</strong>', '<br>');
		printf('<div class="notice notice-error"><h3>Important:</h3><p>%1$s</p></div>', wp_kses_post($message));
	}

	add_action('admin_notices', 'contentberg_php_notice');	
	return;
}

// Initialize Main Framework
require_once get_theme_file_path('lib/bunyad.php');
require_once get_theme_file_path('inc/bunyad.php');

/**
 * Main Theme File: Contains most theme-related functionality
 * 
 * See file:  inc/theme.php
 */
require_once get_theme_file_path('inc/theme.php');

// Fire up the theme - make available in Bunyad::get('theme')
Bunyad::register('theme', array(
	'class' => 'Bunyad_Theme_ContentBerg',
	'init' => true
));

// Main Framework Configuration
$bunyad_core = Bunyad::core()->init(apply_filters('bunyad_init_config', array(
	'theme_name'    => 'contentberg',
	'theme_version' => '2.0.0',

	// Supported formats
	'post_formats' => array('gallery', 'image', 'video', 'audio'),
	'customizer'   => true,
)));
