<?php
/**
 * Supports multiple checkboxes for the customizer.
 * 
 * Based on: http://justintadlock.com/archives/2015/05/26/multiple-checkbox-customizer-control
 */
class Bunyad_Customizer_Control_Checkboxes extends WP_Customize_Control
{

	public $type = 'checkboxes';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() 
	{
		parent::to_json();

		$this->json['value']   = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['id']      = $this->id;
	}

	public function enqueue()
	{
    	wp_enqueue_script('contentberg-customize-controls', get_template_directory_uri() . '/js/admin/customizer-controls.js', array('jquery'));
	}
	
	/**
	 * Render a JS template
	 */
	public function content_template() 
	{ 
	
	?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<ul>
			<# _.each( data.choices, function( label, choice ) { #>
				<li>
					<label>
						<input type="checkbox" value="{{ choice }}" <# if ( -1 !== data.value.indexOf( choice ) ) { #> checked="checked" <# } #> />
						{{ label }}
					</label>
				</li>
			<# } ) #>
		</ul>
	<?php 
	
	}
}