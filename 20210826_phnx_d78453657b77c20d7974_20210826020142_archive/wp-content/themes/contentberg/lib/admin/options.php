<?php
/**
 * Theme Options handler on admin side
 */
class Bunyad_Admin_Options
{
	public $option_key;
	public $options;
	
	public function __construct()
	{
		$this->option_key = Bunyad::options()->get_config('theme_prefix') .'_theme_options';

		// setup menu on init
		add_action('admin_menu', array($this, 'init'));
		
		// check theme version info
		add_action('admin_init', array($this, 'check_version'));
		
	}
	
	/**
	 * Register a single option using Setting API and use sanitization hook
	 */
	public function init()
	{
		// current user can edit?
		if (!current_user_can('edit_theme_options')) {
			return;
		}
	}
	
	/**
	 * Check current theme version and run an update hook if necessary
	 */
	public function check_version()
	{
		$stored_version = Bunyad::options()->theme_version;
		
		// update version if necessary
		if ($stored_version != Bunyad::options()->get_config('theme_version')) {
			
			// fire up the hook
			do_action('bunyad_theme_version_change');
			
			// update the theme version
			Bunyad::options()->set('theme_version', Bunyad::options()->get_config('theme_version'));
				
			if ($stored_version) {
				Bunyad::options()->set('theme_version_previous', $stored_version);
			}
			
			// updated changes in database
			Bunyad::options()->update();
		}
	}
	
	/**
	 * Load options locally for the class
	 */
	public function set_options($options = null)
	{
		if ($options) {
			$this->options = $options;
		}
		else if (!$this->options) { 
			
			// Get default options if empty
			$this->options = include get_template_directory() . '/admin/options.php';
		}
		
		return $this;
	}

	/**
	 * Extract elements/fields from the options hierarchy
	 * 
	 * @param array $options
	 * @param boolean $sub  add partial sub-elements in the list
	 * @param string  $tab_id  filter using a tab id
	 */
	public function get_elements_from_tree(array $options, $sub = false, $tab_id = null)
	{
		$elements = array();
		
		foreach ($options as $tab) {
			
			if ($tab_id != null && $tab['id'] != $tab_id) {
				continue;
			}
			
			foreach ($tab['sections'] as $section) 
			{
				foreach ($section['fields'] as $element) 
				{
					// pseudo element?
					if (empty($element['name'])) {
						continue;
					}
					
					$elements[$element['name']] = $element;
					
					// special treatment for typography section - it has sub-options
					if ($sub === true && $element['type'] == 'typography') {
						
						if (!empty($element['color'])) {
							// over-write 'value' key from the one in color - to set proper default
							$elements[$element['name'] . '_color'] = array_merge($element, $element['color']);
						}
						
						if (!empty($element['size'])) {
							$elements[$element['name'] . '_size'] = array_merge($element, $element['size']);
						}
					}
					
					// special treatment for typography section - it has sub-options
					if ($sub === true && $element['type'] == 'upload') {
						
						if (!empty($element['bg_type'])) {
							// over-write 'value' key from the one in color - to set proper default
							$elements[$element['name'] . '_bg_type'] = array_merge($element, $element['bg_type']);
						}
					}
					
				} // end fields loop
				
			} // end sections
		}
		
		return $elements;
	}
	
	/**
	 * Delete / reset options - security checks done outside
	 */
	public function delete_options($type = null)
	{
		// get options object
		$options_obj = Bunyad::options();
		
		if ($type == 'colors') 
		{
			$elements = $this->get_elements_from_tree($this->options, true, 'options-style-color');
			
			// preserve this
			unset($elements['predefined_style']);
		}
		else 
		{
			$elements = $this->get_elements_from_tree($this->options, true);
		}
		
		// loop through all elements and reset them
		foreach ($elements as $key => $element) {
			$options_obj->set($key, null); // unset / reset
		}

		// save in database
		$options_obj->update();
		
		return true;
	}
	
}