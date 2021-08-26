<?php
/**
 * Theme update notifications for critical security updates
 */
class Bunyad_Theme_Updates
{
	private $theme;
	
	/**
	 * In-memory update info from transient
	 *  
	 * @var array
	 */
	public $update = array();
	
	public function __construct()
	{
		// Set curent theme name at right hook. Legacy: get_template() can be incorrect.
		add_action('bunyad_core_post_init', function() {
			$this->theme = Bunyad::options()->get_config('theme_name');
		});

		add_filter('pre_set_site_transient_update_themes', array($this, 'check_update'));
		add_action('admin_init', array($this, 'admin_init'));
	}
	
	/**
	 * Investigate transients to check for theme version
	 */
	public function admin_init()
	{
		$transient = '_' . $this->theme . '_update_theme';
		$this->update = $update = get_site_transient($transient);
		
		if ($update) {
			
			// Already updated?
			if (version_compare(Bunyad::options()->get_config('theme_version'), $update['version'], '>=')) {
				delete_site_transient($transient);
				return;
			}
			
			add_action('admin_notices', array($this, 'notice_critical'));
		}	
	}
	
	/**
	 * Critical update notice
	 */
	public function notice_critical()
	{
		?>
		
		<div class="update-nag">
		
				
			<p><strong>WARNING:</strong> Your theme version requires a critical security update. Please update your theme to latest version immediately.</p>
				
			<?php if (!empty($this->update['info'])): ?>
				
				<?php echo wp_kses_post($this->update['info']); ?>
			
			<?php endif; ?>
		</div>
		
		<?php
	}
	
	/**
	 * Checks for theme update
	 */
	public function check_update($transient)
	{
		if (empty($transient->checked)) {
			return $transient;
		}
		
		$this->check_critical();
		
		return $transient;
	}
	
	/**
	 * Checks for critical theme security updates
	 * 
	 * A secure HTTPS request is sent with data in POST to ensure version number isn't 
	 * exposed to MITM.
	 */
	public function check_critical()
	{
		$url  = 'https://system.theme-sphere.com/wp-json/api/v1/update';
		$args = array(
			'body' => array(
				'theme' => $this->theme,
				'ver'   => Bunyad::options()->get_config('theme_version'),
				'skin'  => Bunyad::options()->predefined_style ? Bunyad::options()->predefined_style : 'general',
			)
		);
		
		$api_key = Bunyad::core()->get_license();
		if (!empty($api_key)) {
			$args['headers'] = array('X-API-KEY' => $api_key);
		}
		
		// Fire up the request
		$response = wp_remote_post($url, $args);
		
		if (is_wp_error($response)) {
			return;
		}
		
		$body = json_decode($response['body'], true);
		$transient = '_' . $this->theme . '_update_theme';
		
		// Safe is not true? Store in transient to add a notice later
		if (empty($body['safe'])) {
			set_site_transient($transient, $body);
		}
		else {
			
			// Delete it if it's been marked safe
			delete_site_transient($transient);	
		}
	}
}

// init and make available in Bunyad::get('theme_updates')
Bunyad::register('theme_updates', array(
	'class' => 'Bunyad_Theme_Updates',
	'init'  => true
));