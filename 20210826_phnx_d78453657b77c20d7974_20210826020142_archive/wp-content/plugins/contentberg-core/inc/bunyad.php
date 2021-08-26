<?php
/**
 * Bunyad framework factory extension
 * 
 * The main reason it's being extended is to offer shorter syntax that's not 
 * possible to offer on versions older than PHP 5.3 (lack of __callStatic).
 * 
 * This also aids in better code completion for most IDEs.
 * 
 * Most methods here are simply a wrapper for Bunyad_Base::get() method.
 * 
 * @see Bunyad_Base
 * @see Bunyad_Base::get()
 */
class Bunyad extends Bunyad_Base {
	
	public static $fallback_path;

	/**
	 * Media methods
	 *  
	 * @return Bunyad_Theme_Media
	 */
	public static function media() {
		return self::get('media');
	}
	
	/**
	 * Other general helper methods
	 *  
	 * @return Bunyad_Theme_Helpers
	 */
	public static function helpers() {
		return self::get('helpers');
	}
	
	/**
	 * Other general helper methods
	 *  
	 * @return Bunyad_Theme_Lazyload
	 */
	public static function lazyload() {
		return self::get('lazyload');
	}

	/**
	 * Build the required object instance
	 * 
	 * @param string  $object
	 * @param boolean $fresh 	Whether to get a fresh copy; will not be cached and won't override 
	 * 							current copy in cache.
	 */
	public static function factory($object = 'core', $fresh = false)
	{
		if (isset(self::$_cache[$object]) && !$fresh) {
			return self::$_cache[$object];
		}
		
		// Pre-defined class relation?
		if (!empty(self::$_registered[$object]['class'])) {
			$class = self::$_registered[$object]['class'];
		}
		else {

			// Convert short-codes to Bunyad_ShortCodes; core to Bunyad_Core etc.
			$class = str_replace('/', '_', $object);
			$class = apply_filters('bunyad_factory_class', 'Bunyad_' . self::file_to_class_name($class));
		}
		
		// Try auto-loading the class
		if (!class_exists($class)) {
			
			// locate file in child theme or parent theme lib
			$file = locate_template('lib/' . $object . '.php');

			if (!$file && self::$fallback_path) {
				$file = self::$fallback_path . $object . '.php';
			}

			if ($file) {
				require_once $file;
			}			
		}
		
		// Class not found
		if (!class_exists($class)) {
			return false;
		}
		
		// Don't cache fresh objects
		if ($fresh) {
			return new $class; 
		}
		
		self::$_cache[$object] = new $class;
		return self::$_cache[$object];
	}
}