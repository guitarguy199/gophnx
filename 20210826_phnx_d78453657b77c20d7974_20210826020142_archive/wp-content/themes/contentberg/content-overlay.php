<?php
/**
 * Content template to be used for large overlay posts in listings
 */

// Which image to use
if (Bunyad::get('helpers')->relative_width() == 100) {
	$image = 'contentberg-main-full';
}
else {
	$image = 'contentberg-main';
}

?>

<article <?php
	// Setup article attributes
	Bunyad::markup()->attribs('overlay-post-wrapper', array(
		'id'     => 'post-' . get_the_ID(),
		'class'  => join(' ', get_post_class('overlay-post')), 
	)); ?>>
	
	<a href="<?php echo esc_url(get_permalink()); ?>" class="image-link"><?php the_post_thumbnail(
				$image,
				array('title' => strip_tags(get_the_title()))
			); ?>
	</a>
		
	<?php get_template_part('partials/post-meta-alt'); ?>
		
</article>