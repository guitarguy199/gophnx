<?php
/**
 * General Admin functionality - hooks, methods.
 *  
 * This file serves to be the functions.php for admin functionality. Any
 * non-specific functionality is contained here.
 * 
 * Also see admin/ folder in the root.
 *
 */
class Bunyad_Theme_Admin
{
	public function __construct()
	{
		// Setup plugins before init
		$this->setup_plugins();
		
		/**
		 * Include relevant admin files
		 */
		
		// Dashboard, importer and editor
		include get_template_directory() . '/inc/admin/dashboard.php';
		include get_template_directory() . '/inc/admin/import.php';

		// Packaged plugin updates
		include get_template_directory() . '/inc/admin/plugins-update.php';

		// Hook at after_setup_theme for add_theme_support()
		add_action('after_setup_theme', array($this, 'setup'));

		// Set up hooks
		add_action('bunyad_core_post_init', array($this, 'init'));
	}
	
	public function init()
	{
		// Since 5.8+, block widgets can cause issues.
		add_action('after_switch_theme', [$this, 'disable_unsupported_widgets']);
	}

	/**
	 * Setup at after_setup_theme hook
	 */
	public function setup() 
	{
		/**
		 * Gutenberg
		 */
		if (Bunyad::options()->guten_styles) {
			add_action('enqueue_block_editor_assets', array($this, 'add_new_editor_style'));
			
			add_filter(
				// block_editor_settings_all for WP 5.8+
				class_exists('WP_Block_Editor_Context') ? 'block_editor_settings_all' : 'block_editor_settings',
				array($this, 'guten_styles'), 
				11
			);
		}

		// Custom line-height and units for 5.5+
		add_theme_support('custom-line-height');
		add_theme_support('custom-units');

		// Guten editor font sizes
		add_theme_support('editor-font-sizes', array(
			array(
				'name' => esc_html_x('small', 'Admin', 'contentberg'),
				'shortName' => esc_html_x('S', 'Admin', 'contentberg'),
				'size' => 14,
				'slug' => 'small'
			),
			array(
				'name' => esc_html_x('regular', 'Admin', 'contentberg'),
				'shortName' => esc_html_x('M', 'Admin', 'contentberg'),
				'size' => 19,
				'slug' => 'regular'
			),
			array(
				'name' => esc_html_x('large', 'Admin', 'contentberg'),
				'shortName' => esc_html_x('L', 'Admin', 'contentberg'),
				'size' => 24,
				'slug' => 'large'
			),
			array(
				'name' => esc_html_x('larger', 'Admin', 'contentberg'),
				'shortName' => esc_html_x('XL', 'Admin', 'contentberg'),
				'size' => 30,
				'slug' => 'larger'
			)
		));
	}

	/**
	 * Add editor styles for gutenberg
	 */
	public function add_new_editor_style()
	{
		wp_enqueue_style(
			'contentberg-editor-styles', 
			get_theme_file_uri('css/admin/guten-editor.css'), 
			false, 
			Bunyad::options()->get_config('theme_version')
		);

		// Overwrite Core theme styles with empty styles - we provide these
		wp_deregister_style('wp-block-library-theme');
		wp_register_style('wp-block-library-theme', '');

		// Add Google Fonts
		wp_enqueue_style('contentberg-editor-gfonts', Bunyad::get('theme')->get_fonts_enqueue());

		// Add Typekit Kit
		if (Bunyad::options()->typekit_id) {
			wp_enqueue_style('contentberg-editor-typekit', 'https://use.typekit.net/' . Bunyad::options()->typekit_id . '.css');
		}
	}

	/**
	 * Filter gutenberg settings
	 */
	public function guten_styles($setting)
	{
		if (empty($setting['styles'])) {
			return $setting;
		}

		// This is the default editor-styles.css file which isn't needed as we provide the necessary.
		if (!empty($setting['styles'][0]['css'])) {
			unset($setting['styles'][0]);
		}

		if (
			!empty($setting['styles'][1]['css']) 
			&& strstr($setting['styles'][1]['css'], 'Noto Serif')
		) {
			unset($setting['styles'][1]);
		}

		return $setting;
	}

	/**
	 * Setup and recommend plugins
	 */
	public function setup_plugins()
	{
		if (!is_admin()) {
			return;
		}
		
		// Load the plugin activation class and plugin updater
		require_once get_template_directory() . '/lib/vendor/class-tgm-plugin-activation.php';
		require_once get_template_directory() . '/inc/admin/dash-plugins.php';
		
		// Recommended and required plugins
		$plugins = array(
			
			array(
				'name'     => esc_html_x('ContentBerg Core', 'Admin', 'contentberg'),
				'slug'     => 'contentberg-core',
				'required' => true,
				'source'   => get_template_directory() . '/lib/vendor/plugins/contentberg-core.zip', // The plugin source
				'version'  => '1.1.2'
			),

			array(
				'name'     => esc_html_x('Sphere Core', 'Admin', 'contentberg'),
				'slug'     => 'sphere-core',
				'required' => true,
				'source'   => get_template_directory() . '/lib/vendor/plugins/sphere-core.zip', // The plugin source
				'version'  => '1.2.2'
			),

			array(
				'name'     => esc_html_x('WPBakery Page Builder (Optional)', 'Admin', 'contentberg'),
				'slug'     => 'js_composer',
				'required' => false,
				'source'   => get_template_directory() . '/lib/vendor/plugins/js_composer.zip', // The plugin source
				'version'  => '6.7'
			),

			array(
				'name'     => esc_html_x('Contact Form 7 (Optional)', 'Admin', 'contentberg'),
				'slug'     => 'contact-form-7',
				'required' => false,
			),

			array(
				'name'     => esc_html_x('WP Retina 2x', 'Admin', 'contentberg'),
				'slug'     => 'wp-retina-2x',
				'required' => false,
			),

			array(
				'name'     => esc_html_x('Bunyad Widget for Instagram (Optional)', 'Admin', 'contentberg'),
				'slug'     => 'bunyad-instagram-widget',
				'required' => false,
				'source'   => get_template_directory() . '/lib/vendor/plugins/bunyad-instagram-widget.zip', // The plugin source
				'version'  => '1.2.4',
			),

			array(
				'name'     => esc_html_x('Bunyad AMP (Optional)', 'Admin', 'contentberg'),
				'slug'     => 'bunyad-amp',
				'required' => false,
				'optional' => true,
				'source'   => get_template_directory() . '/lib/vendor/plugins/bunyad-amp.zip', // The plugin source
				'version'  => '2.1.3',
				'external_url' => 'https://contentberg.theme-sphere.com/documentation/#amp'
			),

			array(
				'name'     => esc_html_x('Easy GDPR Consent Forms for MailChimp', 'Admin', 'contentberg'),
				'slug'     => 'easy-gdpr-consent-mailchimp',
				'required' => false,
				'optional' => true,
			),

			array(
				'name'     => esc_html_x('Self-Hosted Google Fonts', 'Admin', 'contentberg'),
				'slug'     => 'selfhost-google-fonts',
				'required' => false,
				'optional' => true,
			),
		);
		
		// Set for update checking
		Bunyad::registry()->set('packaged_plugins', $plugins);

		tgmpa($plugins, array(
			'parent_slug' => 'sphere-dash'
		));
	}

	/**
	 * Deactivate unsupported block widgets.
	 */
	public function disable_unsupported_widgets()
	{
		// Not a first install. Widgets were previously set. Don't have to do anything.
		if (get_theme_mod('sidebars_widgets')) {
			return;
		}

		$sidebar_widgets = get_option('sidebars_widgets', []);
		if (empty($sidebar_widgets)) {
			return;
		}

		foreach ($sidebar_widgets as $sidebar => $widgets) {
			foreach ($widgets as $key => $id) {

				// Move to inactive.
				if (substr($id, 0, 6) === 'block-') {
					$sidebar_widgets['wp_inactive_widgets'][] = $id;
					unset($sidebar_widgets[$sidebar][$key]);
				}
			}
		}

		update_option('sidebars_widgets', $sidebar_widgets);
	}
}

// init and make available in Bunyad::get('admin')
Bunyad::register('admin', array(
	'class' => 'Bunyad_Theme_Admin',
	'init' => true
));