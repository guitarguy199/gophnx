<?php
namespace Sphere\Core\AutoLoadPost;

use \Sphere\Core\Plugin;

/**
 * Auto load next post.
 */
class Module 
{
	protected $path_url;

	public function __construct()
	{
		$this->path_url = Plugin::instance()->path_url . 'components/auto-load-post/';
		
		add_action('wp', [$this, 'setup']);

		$options = new Options;
		$options->register_hooks();	
	}

	public function setup()
	{
		// Only for ThemeSphere themes.
		if (!class_exists('Bunyad', false)) {
			return;
		}

		// Auto-load next post is disabled. The filter can return true to force enable.
		$is_enabled = apply_filters('sphere/alp/enabled', $this->get_option('alp_enabled'));
		if (!$is_enabled) {
			return;
		}

		// Only needed for iframe.
		// add_action('wp_head', [$this, 'add_head_js']);
		add_action('wp_enqueue_scripts', [$this, 'register_assets']);

		$supported_types = apply_filters('sphere/alp/post_types', ['post']);
		if (is_single() && in_array(get_post_type(), $supported_types)) {
			add_action('wp_footer', [$this, 'add_next_post_ref']);
		}
	}

	/**
	 * Registe frontend assets.
	 *
	 * @return void
	 */
	public function register_assets()
	{
		// Only enqueue on single.
		if (is_single()) {
			wp_enqueue_script(
				'spc-auto-load-post',
				$this->path_url . 'js/auto-load-post.js',
				[],
				Plugin::VERSION,
				true
			);
		}
	}

	public function add_head_js()
	{
		$css_link = $this->path_url . 'css/iframe.css';

		?>
		
		<script data-cfasync="false">
			<?php // var instead of let for window.BunyadIsIframe / global context. ?>
			var BunyadIsIframe;
			(() => {
				if (location.hash && location.hash.indexOf('auto-load-post') !== -1 && self !== top) {
					BunyadIsIframe = true;
					document.documentElement.style.opacity = 0; <?php // For race-conditions where header maybe rendered first and give a flash, before iframe.css renders. ?> 
					document.head.append(
						Object.assign(document.createElement('link'), {rel: 'stylesheet', href: '<?php echo esc_url($css_link); ?>'}),
						Object.assign(document.createElement('base'), {target: '_top'})
					);
				}
			})();
		</script>

		<?php
	}

	/**
	 * Get an option from the theme customizer options if available.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	public function get_option($key)
	{
		if (class_exists('\Bunyad') && \Bunyad::options()) {
			return \Bunyad::options()->get($key);
		}

		$defaults = [
			'alp_enabled'   => 0,
			'alp_posts'     => 5,
			'alp_load_type' => 'previous',
			'alp_same_term' => false,
		];

		return isset($defaults[$key]) ? $defaults[$key] : null;
	}

	/**
	 * Add reference data for the next post.
	 *
	 * @return void
	 */
	public function add_next_post_ref()
	{
		$posts = $this->get_adjacent_posts(
			$this->get_option('alp_posts'),
			$this->get_option('alp_load_type'),
			$this->get_option('alp_same_term')
		);

		if (!$posts) {
			return;
		}

		$final_posts  = [];
		$have_gallery = false;
		foreach ($posts as $post) {
			$final_posts[] = [
				'id'    => $post->ID,
				'title' => $post->post_title,
				'url'   => get_permalink($post)
			];

			if (get_post_format($post) === 'gallery') {
				$have_gallery = true;
			}
		}

		// Add slickslider if needed and available.
		if ($have_gallery) {
			$script = \Bunyad::options()->get_config('theme_name') . '-slick';
			if (wp_script_is($script, 'registered')) {
				wp_enqueue_script($script);
			}
		}

		do_action('sphere/alp/next_post_ref', $final_posts, $posts);

		printf(
			'<script data-cfasync="false">SphereCore_AutoPosts = %s;</script>', 
			json_encode($final_posts)
		);
	}

	/**
	 * Get adjacent posts to the current post.
	 *
	 * @param integer $count Number of posts.
	 * @param string  $type  Selection type: 'previous', 'next' and 'random'.
	 * @param boolean $same_term 
	 * 
	 * @return array
	 */
	public function get_adjacent_posts($count, $type = 'previous', $same_term = false)
	{
		wp_reset_query();
		$current_post = get_queried_object();

		if (!$current_post || !$current_post->ID) {
			return;
		}

		$query_args = [
			'post_type'           => $current_post->post_type,
			'posts_per_page'      => $count,
			'no_found_rows'       => true,
			'supress_filters'     => true,
			'ignore_sticky_posts' => true
		];

		/**
		 * Additional query params based on type of posts needed.
		 */
		if ($type !== 'random') {
			$adjacent = $type === 'previous' ? 'before' : 'after';
			$query_args += [
				'date_query' => [
					[
						$adjacent   => $current_post->post_date,
						'inclusive' => false
					]
				],

				// For previous posts, order by date desc. asc for next.
				'order' => $adjacent === 'before' ? 'DESC' : 'ASC'
			];
		}
		else {
			$query_args += [
				'orderby'      => 'rand',
				'post__not_in' => [$current_post->ID],
			];
		}

		/**
		 * Posts from the same term.
		 */
		if ($same_term) {

			$terms = wp_get_post_terms($current_post->ID, 'category', ['fields' => 'ids']);
			if ($terms) {
				$query_args['tax_query'] = [
					[
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $terms
					]
				];
			}
		}

		$posts = get_posts(
			apply_filters('sphere/alp/posts_query_args', $query_args)
		);

		return $posts;
	}
}