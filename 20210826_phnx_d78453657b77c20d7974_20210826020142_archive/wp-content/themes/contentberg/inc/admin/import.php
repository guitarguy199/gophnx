<?php
/**
 * Demo Importer - Requires Bunyad Demo Import plugin
 * 
 * @see Bunyad_Demo_Import
 */
class Bunyad_Theme_Admin_Import
{
	public $demos = array();
	public $admin_page;
	public $importer;
	
	public function __construct()
	{
		
		add_filter('bunyad_import_demos', array($this, 'import_source'));
		add_filter('pt-ocdi/importer_options', array($this, 'importer_options'));
		add_action('tgmpa_register', array($this, 'register_plugins'));

		// Known plugin slugs and names.
		$plugins = [
			'js_composer'           => 'WPBakery Page Builder',
			'sphere-core'           => 'Sphere Core',
			'contentberg-core'      => 'ContentBerg Core',
			'regenerate-thumbnails' => 'Regenerate Thumbnails',
		];

		// Base required plugins for all demos.
		$required_plugins = [
			'sphere-core'  => $plugins['sphere-core'],
			'contentberg-core' => $plugins['contentberg-core'],
			'regenerate-thumbnails' => $plugins['regenerate-thumbnails'],
		];
		
		// Demo configs
		$this->demos = array(
				
			'main' => array(
				'demo_name'             => "ContentBerg",
				'demo_description'      => 'The main demo of ContentBerg theme.',
				'demo_url'              => 'https://contentberg.theme-sphere.com',
				'demo_image'			=> get_template_directory_uri() . '/inc/demos/main.jpg',
				'local_import_file'            => get_template_directory() . '/inc/demos/main.xml',
				'local_import_widget_file'     => get_template_directory() . '/inc/demos/main-widgets.json',
				'local_import_customizer_file' => get_template_directory() . '/inc/demos/main-customizer.dat',
				'depends'                      => $required_plugins,
			),

			'alt-2' => array(
				'demo_name'             => "Alt Home 2",
				'demo_description'      => 'Alt Home 2 of ContentBerg theme.',
				'demo_url'              => 'https://contentberg.theme-sphere.com/homepage-2/',
				'demo_image'			=> get_template_directory_uri() . '/inc/demos/alt-2.jpg',
				'local_import_file'            => get_template_directory() . '/inc/demos/main.xml',
				'local_import_widget_file'     => get_template_directory() . '/inc/demos/main-widgets.json',
				'local_import_customizer_file' => get_template_directory() . '/inc/demos/main-customizer.dat',
				'demo_home'             =>  'Alt Homepage 2',
				'depends'                      => $required_plugins + [
					'js_composer' => $plugins['js_composer'],
				]

			),

			'alt-3' => array(
				'demo_name'             => "Alt Home 3",
				'demo_description'      => 'Alt Home 3 of ContentBerg theme.',
				'demo_url'              => 'https://contentberg.theme-sphere.com/homepage-3/',
				'demo_image'			=> get_template_directory_uri() . '/inc/demos/alt-3.jpg',
				'local_import_file'            => get_template_directory() . '/inc/demos/main.xml',
				'local_import_widget_file'     => get_template_directory() . '/inc/demos/main-widgets.json',
				'local_import_customizer_file' => get_template_directory() . '/inc/demos/main-customizer.dat',
				'demo_home'             =>  'Alt Homepage 3',
				'depends'                      => $required_plugins + [
					'js_composer' => $plugins['js_composer'],
				]
			),

			'alt-4' => array(
				'demo_name'             => "Alt Home 4",
				'demo_description'      => 'Alt Home 4 of ContentBerg theme.',
				'demo_url'              => 'https://contentberg.theme-sphere.com/homepage-4/',
				'demo_image'			=> get_template_directory_uri() . '/inc/demos/alt-4.jpg',
				'local_import_file'            => get_template_directory() . '/inc/demos/main.xml',
				'local_import_widget_file'     => get_template_directory() . '/inc/demos/main-widgets.json',
				'local_import_customizer_file' => get_template_directory() . '/inc/demos/main-customizer.dat',
				'demo_home'             =>  'Alt Homepage 4',
				'depends'                      => $required_plugins + [
					'js_composer' => $plugins['js_composer'],
				]
			),

			'bold' => array(
				'demo_name'             => "Bold Home",
				'demo_description'      => 'Bold Home ContentBerg theme.',
				'demo_url'              => 'https://contentberg.theme-sphere.com/?header=simple&slider=bold&subscribe=1',
				'demo_image'			=> get_template_directory_uri() . '/inc/demos/bold.jpg',
				'local_import_file'            => get_template_directory() . '/inc/demos/main.xml',
				'local_import_widget_file'     => get_template_directory() . '/inc/demos/main-widgets.json',
				'local_import_customizer_file' => get_template_directory() . '/inc/demos/main-customizer.dat',
				'depends'                      => $required_plugins,
			),

			'classic' => array(
				'demo_name'             => "Classic Home",
				'demo_description'      => 'Classic Home ContentBerg theme.',
				'demo_url'              => 'https://contentberg.theme-sphere.com/?layout=classic',
				'demo_image'			=> get_template_directory_uri() . '/inc/demos/classic.jpg',
				'local_import_file'            => get_template_directory() . '/inc/demos/main.xml',
				'local_import_widget_file'     => get_template_directory() . '/inc/demos/main-widgets.json',
				'local_import_customizer_file' => get_template_directory() . '/inc/demos/main-customizer.dat',
				'depends'                      => $required_plugins,
			),

		);
		
		// Disable thumbnail creation to be done at the end
		add_filter('pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false');
		add_action('bunyad_import_done', array($this, 'post_import'), 10, 2);
		
		// Register an informational section on customizer
		add_action('customize_register', array($this, 'customizer_info'), 12);
	}

	public function import_source() {
		return $this->demos;
	}
	
	public function importer_options($options) {
		return $options;
	}
	
	/**
	 * Register a few extra plugins with TGMPA
	 */
	public function register_plugins()
	{	
		tgmpa(array(
			array(
				'name'     => esc_html_x('Bunyad Demo Import', 'Admin', 'contentberg'),
				'slug'     => 'bunyad-demo-import',
				'required' => false,
				'version'  => '1.0.6',
				'source'   => get_template_directory() . '/lib/vendor/plugins/bunyad-demo-import.zip', // The plugin source

			),
		
			array(
				'name'     => esc_html_x('Regenerate Thumbnails', 'Admin', 'contentberg'),
				'slug'     => 'regenerate-thumbnails',
				'required' => false,
				'force_activation' => false, 
			),
		), array('is_automatic' => true));
	}
	
	/**
	 * Post-import
	 * 
	 * @param string $demo_id
	 * @param OCDI_WXR_Importer $import
	 */
	public function post_import($demo_id, $import)
	{	
		// Additional settings per demo
		$this->post_import_settings($demo_id);

		// Don't continue unless a full import
		if ($_POST['import_type'] !== 'full') {
			return;
		}

		// Set to show latest posts
		update_option('show_on_front', 'posts');

		// Unpublish hello world post
		wp_update_post(array('ID' => 1, 'post_status' => 'draft'));

		// Set main menu
		$main_menu = get_term_by('name', 'Main Menu', 'nav_menu');
		
		set_theme_mod('nav_menu_locations', array(
			'contentberg-main' => $main_menu->term_id
		));

		/**
		 * Set homepages
		 */
		if (in_array($demo_id, array('alt-2', 'alt-3', 'alt-4'))) {

			$home = get_page_by_title($this->demos[$demo_id]['demo_home']);

			if (is_object($home)) {
				update_option('show_on_front', 'page');
				update_option('page_on_front', $home->ID);
				
				// Visual Composer home changes
				$this->post_process_vc($home->ID, $import);
			}

			// Homepage widgets are not necessary - using VC
			$this->remove_home_widgets();
		}

		// Classic - remove home widgets
		if ($demo_id == 'classic') {
			$this->remove_home_widgets();
		}
	}

	/**
	 * Post import: Settings only 
	 */
	public function post_import_settings($demo_id) 
	{
		// Re-init options and update
		Bunyad::options()->init();

		// Alt home options
		if (in_array($demo_id, array('alt-2', 'alt-3', 'alt-4'))) {

			// Remove separator
			Bunyad::options()
				->set(array(
					'header_nosep_home'  => 0,
				))
				->update();
		}

		// Bold home
		if ($demo_id == 'bold') {

			Bunyad::options()
				->set(array(
					'header_layout'  => 'simple',
					'home_slider'    => 'bold',
					'home_subscribe' => 1
				))
				->update();
		}

		// Classic
		if ($demo_id == 'classic') {
			
			Bunyad::options()
				->set(array(
					'home_widgets' => 0,
					'home_layout'  => ''
				))
				->update();
		}
	}

	/**
	 * Remove widgets from home widget area
	 */
	protected function remove_home_widgets()
	{
		$widgets = get_option('sidebars_widgets');
		$widgets['contentberg-home'] = array();
		update_option('sidebars_widgets', $widgets);
	}

	/**
	 * Remap Visual Composer block categories
	 * 
	 * @param integer  $page_id
	 * @param OCDI_WXR_Importer $import
	 */
	public function post_process_vc($page_id, $import) 
	{
		$import_data = $import->get_importer_data();
		$mapping     = $import_data['mapping'];
		
		// Get page content
		$page    = get_page($page_id);
		$content = $page->post_content;
		
		// Find all instances of cat="1" and replace as necessary
		preg_match_all('/cat="(\d+)"/', $content, $match);
		foreach ($match[1] as $key => $cat) {
			$new_id = $mapping['term_id'][$cat];
			
			if (empty($new_id)) {
				continue;
			}
			
			$content = str_replace($match[0][$key], 'cat="'. $new_id .'"', $content);
		}
		
		// Update the home
		wp_update_post(array(
			'ID' => $page_id,
			'post_content' => $content
		));
	}
	
	/**
	 * Customizer information
	 */
	public function customizer_info($wp_customizer)
	{
		/* @var $wp_customizer WP_Customize_Manager */
		$control = $wp_customizer->get_control('import_info');
		
		// Plugin active
		if (class_exists('Bunyad_Demo_Import')) {
			$control->text = sprintf(
				esc_html_x('You can import demo settings or full demo content from %1$s this page %2$s.', 'Admin', 'contentberg'), 
				'<a href="' . esc_url(admin_url('themes.php?page=bunyad-demo-import')) .'">',
				'</a>'
			);
			
			return;
		}
		
		// Prompt for plugin activation
		$control->text = sprintf(
			esc_html_x('Please install and activate the required plugin "Bunyad Demo Import" from %1$sthis page%2$s.', 'Admin', 'contentberg'), 
			'<a href="' . esc_url(admin_url('themes.php?page=tgmpa-install-plugins')) .'">',
			'</a>'
		);
	}
		
}


// init and make available in Bunyad::get('admin_import')
Bunyad::register('admin_import', array(
	'class' => 'Bunyad_Theme_Admin_Import',
	'init' => true
));