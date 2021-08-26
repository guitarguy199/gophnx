<?php
/**
 * Grid posts style used for several loops
 */

extract(array(
	'show_excerpt' => true,
	'show_footer'  => true,
	'excerpt_length' => Bunyad::options()->post_excerpt_grid
), EXTR_SKIP);

$image = 'contentberg-grid';
if ($grid_cols !== 3 && Bunyad::helpers()->relative_width() == 100) {
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
		'class'  => array_merge(get_post_class('grid-post'), array($show_excerpt ? 'has-excerpt' : 'no-excerpt')) 
	)); ?>>
	
	<div class="post-header cf">
			
		<div class="post-thumb">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="image-link">
			
				<?php the_post_thumbnail(
					$image,
					array('title' => strip_tags(get_the_title()))
				); ?>
					
				<?php get_template_part('partials/post-format'); ?>
			</a>
			
			<?php Bunyad::helpers()->meta_cat_label(); ?>
		</div>
		
		<div class="meta-title">
		
			<?php Bunyad::helpers()->post_meta('grid'); ?>
		
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
	
	<?php if ($show_footer): ?>
	<div class="post-footer">

		<?php if (class_exists('ContentBerg_Core')): ?>
			<?php 
				// See plugins/contentberg-core/social-share/views/social-share-inline.php
				Bunyad::get('cb_social')->render('social-share-inline'); 
			?>
		<?php endif;?>
		
	</div>
	<?php endif; ?>
	
		
</article>
