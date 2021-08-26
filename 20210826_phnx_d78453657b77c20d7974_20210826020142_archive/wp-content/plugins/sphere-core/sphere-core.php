<?php
/**
 * Plugin Name: Sphere Core
 * Plugin URI: https://theme-sphere.com
 * Description: Core plugin for ThemeSphere themes.
 * Version: 1.2.2
 * Author: ThemeSphere
 * Author URI: https://theme-sphere.com
 * License: GPL2
 */

namespace Sphere\Core;

// Note: This class name will NOT change due to dependencies
class Plugin
{
	/**
	 * @since 1.0.2
	 */
	const VERSION = '1.2.2';
	
	public $components;
	public $registry;
	
	protected static $instance;

	/**
	 * Path to plugin folder, trailing slashed.
	 */
	public $path;
	public $path_url;
	
	public function __construct() 
	{
		$this->path = plugin_dir_path(__FILE__);

		// URL for the plugin dir
		$this->path_url = plugin_dir_url(__FILE__);

		add_action('bunyad_core_pre_init', array($this, 'setup'));

		/**
		 * Directory path to components
		 * @deprecated 1.1.4
		 */
		define('SPHERE_COMPONENTS', plugin_dir_path(__FILE__) . 'components/');
		define('SPHERE_LIBS_VENDOR', plugin_dir_path(__FILE__) . 'components/vendor/');

		/**
		 * Register autoloader. Usually uses the loader from theme if present.
		 */
		if (!class_exists('\Bunyad_Loader')) {
			require_once $this->path . 'lib/loader.php';
		}

		$loader = new \Bunyad_Loader([
			'Sphere\Core\\' => $this->path . 'components',
		]);
	}
	
	/**
	 * Initialize and include the components
	 * 
	 * Note: Setup is called before after_setup_theme and Bunyad::options()->init() 
	 * at the hook bunyad_core_pre_init.
	 */
	public function setup()
	{
		/**
		 * Registered components can be filtered with a hook at bunyad_core_pre_init or in the 
		 * Bunyad::core()->init() bootstrap function via the key sphere_components.
		 */
		$this->components = apply_filters('sphere_plugin_components', array(
			'social-share', 'likes', 'social-follow'
		));
		
		foreach ($this->components as $component) {
			
			$module_name = implode('', array_map('ucfirst', explode('-', $component)));
			$class = '\Sphere\Core\\' . $module_name . '\Module';

			if (class_exists($class)) {
				$this->registry[$component] = new $class;

				// Legacy: Aliases for legacy versions. Deprecated.
				class_alias($class, 'Sphere_Plugin_' . $module_name);
			}
		}
	}
	
	/**
	 * Static shortcut to retrieve component object from registry
	 * 
	 * @param  string $component
	 * @return object|boolean 
	 */
	public static function get($component)
	{
		$object = self::instance();
		if (isset($object->registry[$component])) {
			return $object->registry[$component];
		}
		
		return false;
	}
	
	/**
	 * Get singleton object
	 * 
	 * @return self
	 */
	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
}

// Legacy: Aliases for legacy versions. Deprecated.
class_alias('\Sphere\Core\Plugin', 'Sphere_Plugin_Core');

Plugin::instance();