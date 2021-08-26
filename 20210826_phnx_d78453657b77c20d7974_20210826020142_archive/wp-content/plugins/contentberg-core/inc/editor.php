<?php
/**
 * Features and modifications for the TinyMCE editor in the backend
 */
class ContentBerg_Editor
{
	public function __construct() 
	{
		// Only to be used for logged in users
		if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
			return;
		}
		
		// Special class to target
		add_filter('tiny_mce_before_init', array($this, 'editor_class'), 1);
		
		add_filter('mce_buttons', array($this, 'editor_button'));
		add_filter('mce_external_plugins', array($this, 'editor_plugin_init'));
		
		// TinyMCE Plugin fetchted via ajax
		add_action('wp_ajax_bunyad_editor_plugin', array($this, 'editor_plugin_js'));
	}
	
	/**
	 * Callback: Add shortcode list button to tinymce
	 * 
	 * @param array $buttons
	 */
	public function editor_button($buttons)
	{
		array_push($buttons, 'separator', 'bunyad_formats');
		return $buttons;
	}
	
	/**
	 * Callback: Register tinyMCE custom plugin
	 * 
	 * @param array $plugins
	 */
	public function editor_plugin_init($plugins)
	{
		$plugins['bunyad_formats'] = admin_url('admin-ajax.php') . '?action=bunyad_editor_plugin';
		return $plugins;
	}
	
	/**
	 * Filter callback: Add a class to TinyMCE editor for our custom editor styling
	 * 
	 * @param array $settings
	 */
	public function editor_class($settings)
	{
		$settings['body_class'] = 'post-content entry-content';
		
		return $settings;
	}
	
	/**
	 * AJAX Callback - Outputs TinyMCE editor plugin 
	 */
	public function editor_plugin_js()  
	{	
		
		// Add menu items to show
		$items = array(
		
			'contentberg_quote' => array(
				'text'   => esc_html_x('Modern Quote', 'Admin', 'contentberg-core'),
				'block'   => 'blockquote',
				'classes' => 'modern-quote',
				'wrapper' => true,
			),
			
			'contentberg_sep_dots' => array(
				'text'   => esc_html_x('Separator: Dots', 'Admin', 'contentberg-core'),
				'block'   => 'hr',
				'classes' => 'wp-block-separator is-style-dots',
				'wrapper' => false,
			),
			
			'contentberg_cite' => array(
				'text'  => esc_html_x('Citation (for quote)', 'Admin', 'contentberg-core'),
				'inline' => 'cite',
			),
		);
		
		$button_label = _x('More Styles', 'Admin', 'contentberg-core');
		
		// Check auth
		if (!is_user_logged_in() OR !current_user_can('edit_posts')) {
			wp_die('You do not have the right type of authorization. You must be logged in and be able to edit pages and posts.');
		}
		
		header('Content-type: application/x-javascript');
		
		?>
		
		(function($) {
		
			tinymce.PluginManager.add('bunyad_formats', function(editor, url) {
			
					var list = [],
					    items = <?php echo json_encode($items); ?>;
					    					    
					/**
					 * Prepare items for rendering
					 */
					$.each(items, function(id, item) {

						var format = $.extend({}, item);
					
						item.onClick = function() {
							editor.execCommand('mceToggleFormat', false, id);
						};
						
						item.onPostRender = function() {
					    
						    var self = this, setup = function() {
								editor.formatter.formatChanged(id, function(state) {
									self.active(state);
								});
							};

							editor.formatter ? setup() : editor.on('init', setup);
						};

						
						editor.on('init', function() {
							editor.formatter.register(id, format);
						});
						
						list.push(item);
					});
					
					editor.addButton('bunyad_formats', {
						type: 'menubutton',
						text: '<?php echo esc_js($button_label); ?>', 
						icon: false,
						menu: list
					});
			});
			
		})(jQuery);
			
		<?php
		
		wp_die();
		
	}

}

// init and make available in Bunyad::get('editor')
Bunyad::register('cb_admin_editor', array(
	'class' => 'ContentBerg_Editor',
	'init' => true
));