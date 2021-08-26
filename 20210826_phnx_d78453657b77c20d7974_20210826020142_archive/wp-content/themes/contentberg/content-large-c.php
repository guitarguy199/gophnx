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
	Bunyad::markup()->attribs('large-post-wrapper', array(
		'id'     => 'post-' . get_the_ID(),
		'class'  => join(' ', get_post_class('post-main large-post large-post-c')),
	)); ?>>

	<header class="post-header cf">

		<?php get_template_part('partials/content/featured'); ?>

		<div class="meta-title">
			<?php Bunyad::helpers()->post_meta(); ?>
		</div>

	</header><!-- .post-header -->
		
</article>