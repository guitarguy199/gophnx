<?php
/**
 * Our custom control for Google fonts typorgraphy with selectize.
 */
class Bunyad_Customizer_Control_Typography extends WP_Customize_Control
{

	public $type = 'typography';
	public $add_custom = false;

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() 
	{
		parent::to_json();

		// Add custom added value to the choices
		$value = $this->value();
		
		if ($this->add_custom && !empty($value['font_name'])) {
			
			$this->choices = array_merge(	
				$this->choices, 
				array($value['font_name'] => $value['font_name'])
			);
		}
		
		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;
		$this->json['add_custom'] = $this->add_custom;
		$this->json['defaults'] = $this->settings['default']->default;
		
		// Available font weights
		$this->json['font_weights'] = array(
			'300'       => 'Light',
			'300italic' => 'Light Italic',
			'400'       => 'Normal',
			'400italic' => 'Normal Italic',
			'500'       => 'Medium',
			'500italic' => 'Medium Italic',
			'600'       => 'Semi-bold',
			'600italic' => 'Semi-bold Italic',
			'700'       => 'Bold',
			'700italic' => 'Bold Italic',
			'800'       => 'Extra Bold',
			'800italic' => 'Extra Bold Italic',
		);
		
		// Font sizes from 10 to 30px
		$this->json['font_sizes'] = array_combine(range(9, 45), range(9, 45));
	}

	public function enqueue()
	{
    	wp_enqueue_script(
			'contentberg-customize-controls', 
			get_template_directory_uri() . '/js/admin/customizer-controls.js', 
			array('jquery'), 
			Bunyad::options()->get_config('theme_version'), 
			true
		);

		wp_enqueue_script(
			'contentberg-customize-selectbox', 
			get_template_directory_uri() . '/js/admin/selectize.js', 
			array('jquery'),
			Bunyad::options()->get_config('theme_version')
		);
		
    	wp_enqueue_style('contentberg-customize-selectbox', get_template_directory_uri() . '/css/admin/selectize.css');
	}
	
	/**
	 * Render a JS template
	 */
	public function content_template() 
	{ 
	
	?>
		<label>	
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>
			
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<# if ( data.defaults.hasOwnProperty('font_name') ) { #>
			
			<select {{{ data.link }}} class="font_name">
				<# _.each( data.choices, function( label, choice ) { #>
					<option value="{{ choice }}" <# if ( choice === data.value.font_name ) { #> selected="selected" <# } #>>{{ label }}</option>
				<# } ) #>
			</select>
			
			<# } #>
			
			<# if ( data.defaults.hasOwnProperty('font_weight') ) { #>
			
			<div>
				<span class="sub-label"><?php echo esc_html_x('Weight:', 'Admin', 'contentberg'); ?></span>
				<select class="font_weight">
					<# _.each( data.font_weights, function( label, choice ) { #>
						<option value="{{ choice }}"<# if ( choice == data.value.font_weight ) { #>selected<# } #>>{{ label }}</option>
					<# } ) #>
				</select>
			</div>
			
			<# } #>
			
			
			<# if ( data.defaults.hasOwnProperty('font_size') ) { #>
			
			<div>
				<span class="sub-label"><?php echo esc_html_x('Size:', 'Admin', 'contentberg'); ?></span>
				<select class="font_size">
					<# _.each( data.font_sizes, function( label, choice ) { #>
						<option value="{{ choice }}"<# if ( choice == data.value.font_size ) { #>selected<# } #>>{{ label }}</option>
					<# } ) #>
				</select>
			</div>
			
			<# } #>
			
		</label>
	<?php
	
	}
	
	/**
	 * Empty render as per the docs for JS templating
	 */
	public function render_content() {}
		
}