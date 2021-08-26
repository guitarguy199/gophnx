<?php
/**
 * Content Template is used for every post format and used on single posts
 * 
 * It is also used on archives called via loop.php
 */

 // Set default excerpt value, if not set
if (!isset($excerpt_length)) {

	// Full width requires more words in the excerpt
	$excerpt = Bunyad::options()->post_excerpt_list;
	$words   = Bunyad::get('helpers')->relative_width() > 67 ? round($excerpt * 2) : $excerpt;
}

// Set defaults if not set
extract(array(
	'show_excerpt'  => true,
	'excerpt_length' => $words
), EXTR_SKIP);

?>

<article <?php
	// setup the tag attributes
	Bunyad::markup()->attribs('list-post-wrapper', array(
		'id'        => 'post-' . get_the_ID(),
		'class'     => 'list-post'
	)); ?>>
	
	<div class="post-thumb">
		<a href="<?php echo esc_url(get_permalink()); ?>" class="image-link">
			
			<?php the_post_thumbnail(
				(Bunyad::core()->get_sidebar() == 'none' ? 'contentberg-list' : 'contentberg-list'), // small image if there's a sidebar
				array('title' => strip_tags(get_the_title()))
			); ?>
			
			<?php get_template_part('partials/post-format'); ?>
		</a>
		
		<?php //Bunyad::get('helpers')->meta_cat_label(); ?>
	</div>


	<div class="content">
	
		<?php Bunyad::helpers()->post_meta(null, array('title_class' => 'post-title')); ?>
		
		<?php if ($show_excerpt): ?>
		
		<div class="post-content post-excerpt cf">
					
			<?php
		
			// Get excerpt with read more button added
			echo Bunyad::posts()->excerpt(null, $excerpt_length, array('add_more' => false));
			
			?>
				
		</div>
		
		<?php endif; ?>

			
		<?php if (Bunyad::options()->post_footer_list): ?>
		<div class="post-footer">
			
			<?php if (class_exists('ContentBerg_Core')): ?>
				<?php 
					// See plugins/contentberg-core/social-share/views/social-share-inline.php
					Bunyad::get('cb_social')->render('social-share-inline'); 
				?>
			<?php endif;?>
					
		</div>
		<?php endif; ?>
		
	</div> <!-- .content -->

	
		
</article>