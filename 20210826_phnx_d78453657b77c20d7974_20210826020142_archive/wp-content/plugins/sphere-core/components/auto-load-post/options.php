<?php
namespace Sphere\Core\AutoLoadPost;

/**
 * Autoload post options.
 */
class Options
{
	public function register_hooks()
	{
		// Add relevant options
		add_filter('bunyad_theme_options', [$this, 'add_options']);
	}

	/**
	 * Callback: Add options to theme customizer.
	 *
	 * @param  array $options
	 * @return array
	 */
	public function add_options($options)
	{
		$add_options = [
			'priority' => 40,
			'sections' => [[
				'title'    => esc_html__('Auto-load Next Post', 'sphere-core'),
				'id'       => 'sphere-auto-load-post',
				'fields' => [
					[
						'name'    => 'alp_enabled',
						'label'   => esc_html__('Enable Auto-load Posts', 'sphere-core'),
						'desc'    => esc_html__('Activate the auto-load post on scroll for single post pages.', 'sphere-core'),
						'value'   => 0,
						'classes' => 'sep-bottom',
						'type'    => 'toggle',
					],
					[
						'name'  => 'alp_posts',
						'label' => esc_html__('Number of Posts', 'sphere-core'),
						'desc'  => esc_html__('Max number of posts to auto-load on scroll.', 'sphere-core'),
						'value' => 6,
						'type'  => 'number',
						'input_attrs' => ['min' => 1, 'max' => 25],
					],
					[
						'name'  => 'alp_load_type',
						'label' => esc_html__('Load Type', 'sphere-core'),
						'desc'  => '',
						'value' => 'previous',
						'type'  => 'radio',
						'options' => [
							'previous' => esc_html__('Previous By Date', 'sphere-core'),
							'next'     => esc_html__('Next By Date', 'sphere-core'),
							'random'   => esc_html_x('Random', 'sphere-core'),
						]
					],
					[
						'name'  => 'alp_same_term',
						'label' => esc_html__('From Same Category', 'sphere-core'),
						'desc'  => esc_html__('Select posts from same category(s) only as the original post.', 'sphere-core'),
						'value' => 0,
						'type'  => 'toggle',
					],
				]
			]]
		];

		$options['sphere-auto-load-post'] = apply_filters('sphere/alp/options', $add_options);
		return $options;
	}
}