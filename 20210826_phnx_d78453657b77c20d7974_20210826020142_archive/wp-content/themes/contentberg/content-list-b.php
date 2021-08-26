<?php
/**
 * Content Template is used for every post format and used on single posts
 * 
 * It is also used on archives called via loop.php
 */

$show_excerpt = isset($show_excerpt) ? $show_excerpt : 1;

?>

<article <?php
	// setup the tag attributes
	Bunyad::markup()->attribs('list-post-wrapper', array(
		'id'        => 'post-' . get_the_ID(),
		'class'     => 'list-post list-post-b'
	)); ?>>
	
	<div class="post-thumb">
		<a href="<?php echo esc_url(get_permalink()); ?>" class="image-link">
			<?php the_post_thumbnail(
				'contentberg-list-b',
				array('title' => strip_tags(get_the_title()))
			); ?>
				
			<?php get_template_part('partials/post-format'); ?>
		</a>
		
		<?php //Bunyad::get('helpers')->meta_cat_label(); ?>

	</div>

	<div class="content">
		
		<?php Bunyad::helpers()->post_meta('list-b', array('title_class' => 'post-title')); ?>
		
		<?php if ($show_excerpt): ?>
		<div class="post-content post-excerpt cf">
					
			<?php
			
			if ($show_excerpt):
				// Full width requires more words in the excerpt
				$excerpt = Bunyad::options()->post_excerpt_list;
				$words   = Bunyad::get('helpers')->relative_width() > 67 ? round($excerpt * 2) : $excerpt;
		
				// Get excerpt with read more button added
				echo Bunyad::posts()->excerpt(null, $words, array('add_more' => false));
			endif;
			
			?>
				
		</div>
		<?php endif; ?>
			
		<?php if (Bunyad::options()->post_footer_list): ?>
		<div class="post-footer">
			
			<a href="<?php the_permalink(); ?>" class="read-more-btn"><?php echo esc_html(Bunyad::posts()->more_text); ?></a>
					
		</div>
		<?php endif; ?>
		
	</div> <!-- .content -->

	
		
</article>