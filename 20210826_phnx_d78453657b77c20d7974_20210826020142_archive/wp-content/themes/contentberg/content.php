<?php
/**
 * Content Template is used for every post format and used on single posts
 * 
 * It is also used on archives called via loop.php
 */

$classes = array_merge((array) $classes, array('the-post'));

?>

<article <?php
	// Setup article attributes
	Bunyad::markup()->attribs('post-wrapper', array(
		'id'        => 'post-' . get_the_ID(),
		'class'     => join(' ', get_post_class($classes)),
	)); ?>>
	
	<header class="post-header the-post-header cf">
			
		<?php 
			Bunyad::helpers()->post_meta(
				'single', 
				array(
					'enable_cat' => 1, 
					'is_single'  => 1,
					'add_class'  => 'the-post-meta'
				)
			); 
		?>

		<?php get_template_part('partials/single/featured'); ?>
		
	</header><!-- .post-header -->

	<?php get_template_part('partials/single/post-content'); ?>
		
</article> <!-- .the-post -->