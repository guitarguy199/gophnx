/**
 * Custom control handling of customizer
 */

(function($) {
	"use strict";
	
	var api = wp.customize;
	
	/**
	 * Setup multiple checkboxes
	 */
	api.controlConstructor['checkboxes'] = api.Control.extend({
		
		ready: function() 
		{
			var control = this;

			this.container.on('change', 'input:checkbox', function() {

				// get the checkbox value objects and used get() to retrieve an array of values
				var values = $('input[type="checkbox"]:checked', control.container).map(function() {
						return this.value;
				}).get();
				
				control.setting.set(values || '');
				
			});
		}
	});
	
	/**
	 * Typography control
	 */
	api.controlConstructor['typography'] = api.Control.extend({
		
		ready: function() 
		{
			var control = this, 
			    values = {},
				container = this.container;
			
			// Select may not use or show selected value while dynamically inserted
			container.find('select').each(function() {
				var selected = $(this).find('option[selected]');
				if (selected.length) {
					$(this).val(selected.val());
				}
			});
			
			// init selectize
			$(this.container.find('.font_name')).selectize({
				create: (this.params.add_custom ? true : false),
				allowEmptyOption: true
			});

			this.container.on('change', '.font_name, .font_weight, .font_size', function() {
				
				// Update value object
				$.each(['.font_name', '.font_weight', '.font_size'], function(i, key) {
					var element = $(container).find(key);

					if (element.length) {
						values[key.replace('.', '')] = element.val();
					}
				});
		
				// Signal a refresh
				control.setting.set('').set(values);
			});
		}
	});

	/**
	 * Reset settings
	 */
	$(document).on('click', '.reset-customizer', function(e) {
		
		e.preventDefault();
		
		if (!confirm('WARNING: All settings will reset to default.')) {
			return;
		}
		
		var data = {
			'action': 'reset_customizer',
			'nonce':  api.settings.nonce.save
		};
		
		$.post(ajaxurl, data, function(resp) {
			
			if (!resp.success) {
				return;
			}
			
			wp.customize.state('saved').set(true);
			location.reload();
		}, 'json');
	});
	
	/**
	 * Focus links
	 */
	$(document).on('click', '.focus-link', function() {
		
		var section = $(this).data('section');
		if (section) {
			wp.customize.section(section).focus();
		}
		
		var panel = $(this).data('panel');
		if (panel) {
			wp.customize.panel(panel).focus();
		}
		
		return false;
	});
	
	
	/**
	 * Mailchimp parse
	 */
	$(document).on('input change', '#customize-control-home_subscribe_url input', function() {
		
		var code = $(this).val(),
		    match = code.match(/action=\"([^\"]+)\"/);
		
		if (match) {
			$(this).val(match[1]);
		}
	});
	
	
	/**
	 * Magazine settings change effect 
	 */
	api('contentberg_theme_options[predefined_style]', function(setting) {
		setting.bind(function(new_val, old_val) {
			
			// Changing to magazine
			if (new_val == 'magazine') {
				
				api.control('meta_style').setting.set('style-c');
				api.control('post_footer_list').setting.set(0);
				api.control('post_footer_grid').setting.set(0);
				api.control('meta_cat_labels').setting.set(1);
				
			}
			
			// Changing from magazine
			if (old_val == 'magazine') {
				api.control('meta_style').setting.set('');
				api.control('post_footer_list').setting.set(1);
				api.control('post_footer_grid').setting.set(1);
				api.control('meta_cat_labels').setting.set(0);
			}
		});
	});
})(jQuery);