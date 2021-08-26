<?php
/**
 * Grid Style 2 - used for several loops
 */

extract(array(
	'show_excerpt' => true,
	'show_footer'  => true,
	'excerpt_length' => Bunyad::options()->post_excerpt_grid
), EXTR_SKIP);

$image = 'contentberg-grid';

if ($grid_cols !== 3 && Bunyad::get('helpers')->relative_width() == 100) {
	$image = 'contentberg-main';
}

if (Bunyad::options()->post_grid_masonry) {
	
	$image = Bunyad::media()->image_size('contentberg-masonry', 'large');
	
	if ($grid_cols !== 3 && Bunyad::helpers()->relative_width() == 100) {
		$image = 'large';
	}
}

?>

<article <?php
	// hreview has to be first class because of rich snippet classes limit 
	Bunyad::markup()->attribs('grid-post-wrapper', array(
		'id'     => 'post-' . get_the_ID(),
		'class'  => join(' ', get_post_class('grid-post grid-post-b')),
	)); ?>>
	
	<div class="post-header cf">
		
		<div class="post-thumb">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="image-link"><?php the_post_thumbnail(
						$image,
						array('title' => strip_tags(get_the_title()))
					); ?>
					
				<?php get_template_part('partials/post-format'); ?>					
			</a>
			
			<?php Bunyad::get('helpers')->meta_cat_label(); ?>
			
		</div>
		
		<div class="meta-title">
		
			<?php Bunyad::get('helpers')->post_meta('grid'); ?>
		
		</div>
		
	</div><!-- .post-header -->

	<?php if (!empty($show_excerpt)): ?>
	<div class="post-content post-excerpt cf">
		<?php

		// Excerpts or main content?
		echo Bunyad::posts()->excerpt(null, $excerpt_length, array('add_more' => false));
		 
		?>
			
	</div><!-- .post-content -->
	<?php endif; ?>
	
	<?php if (!empty($show_footer)): ?>
	
		<a href="<?php the_permalink(); ?>" class="read-more-btn">
			<?php echo esc_html(Bunyad::posts()->more_text); ?>
		</a>
	
	<?php endif; ?>
	
		
</article>
