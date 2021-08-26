<?php
/**
 * Single Post Template: Creative Large Layout
 */

the_post();
rewind_posts(); // the_post() is used again by layout-cover

get_header();

if (have_posts()):
	the_post();
endif;

$classes = (array) $classes;

$image = Bunyad::media()->image_size('contentberg-large-cover', 'full');
$image_attrs = array(
	'title' => strip_tags(get_the_title()), 
	'sizes' => '100vw'
);

// For cover part, no social
$show_social = false;

?>

<div class="single-creative">

	<div class="cf">
		<?php 
			Bunyad::lazyload()->disable();
			Bunyad::core()->partial('partials/single/featured-overlay', compact('image', 'image_attrs', 'show_social')); 
			Bunyad::lazyload()->enable();
		?>
	</div>
	
	<div class="main wrap">
	
		<div <?php
			// Setup article attributes
			Bunyad::markup()->attribs('post-cover-wrapper', array(
				'id'        => 'post-' . get_the_ID(),
				'class'     => join(' ', get_post_class($classes)),
			)); ?>>
	
		<div class="ts-row cf">
			<div class="col-8 main-content cf">
				
				<article class="the-post">
					
					<?php Bunyad::core()->partial('partials/single/post-content'); ?>
					
				</article> <!-- .the-post -->
	
			</div>
			
			<?php Bunyad::core()->theme_sidebar(); ?>
			
		</div> <!-- .ts-row -->
		
		</div>
	</div> <!-- .wrap -->

</div>

<?php get_footer(); ?>