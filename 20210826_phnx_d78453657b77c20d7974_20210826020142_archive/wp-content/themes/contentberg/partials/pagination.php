<?php
/**
 * Partial: Common pagination for loops
 */

// Set defaults unless already set
extract(array(
	'query' => $wp_query,
	'pagination' => true,
	'pagination_type' => Bunyad::options()->pagination_style,
	
	// Add ?first_normal to next page URL to make sure 1st post isn't large on load more
	'first_normal' => false,
), EXTR_SKIP);

// Disabled?
if (!$pagination) {
	return;
}

// Set for WP core functions
$wp_query = $query;

?>

<?php if (!$pagination_type): ?>

	<nav class="main-pagination">
		<div class="previous"><?php next_posts_link('<i class="fa fa-angle-double-left"></i>' . esc_html__('Older Posts', 'contentberg')); ?></div>
		<div class="next"><?php previous_posts_link(esc_html__('Newer Posts', 'contentberg') . '<i class="fa fa-angle-double-right"></i>'); ?></div>
	</nav>
	
<?php elseif ($pagination_type == 'numbers'): ?>

	<nav class="main-pagination number">
		
		<?php 
		
		$pagination = Bunyad::posts()->paginate(array(
			'always_prev_next' => 1,
			'prev_text' => '<i class="fa fa-long-arrow-left"></i>'  . esc_html__('Previous', 'contentberg'),
			'next_text' =>  esc_html__('Next', 'contentberg') . '<i class="fa fa-long-arrow-right"></i>',
		), $query);
		
		?>
		
		<?php if (!empty($pagination)): ?>
		
		<div class="inner">
			<?php echo $pagination; // XSS ok. Generated above by Bunyad::posts()->paginate() / paginate_links() ?>
		</div>
		
		<?php endif; ?>
		
	</nav>

<?php elseif ($pagination_type == 'load-more'): ?>

	<?php
		/**
		 * Fix paged for static front page and for next_posts() function
		 */
		global $paged;
		
		$paged = max(1, (get_query_var('paged') ? get_query_var('paged') : get_query_var('page')));
		$max_page = $wp_query->max_num_pages;
		
		$next_url = get_next_posts_page_link($max_page);
		
		if (!empty($first_normal)) {
			$next_url = add_query_arg(array('first_normal' => true), $next_url);
		}
		
	?>

	<?php if ($paged < $max_page): ?>
	
	<div class="main-pagination load-more">
		<a href="<?php echo esc_url($next_url); ?>" class="load-button" data-page="<?php echo intval($paged); ?>">
			<?php esc_html_e('Load More', 'contentberg'); ?> <i class="fa fa-repeat"></i>
		</a>
	</div>	
	
	<?php endif; ?>

<?php endif; ?>

<?php wp_reset_query(); ?>