<?php
/**
 * Partial: Defaut Slider
 */

// setup attribs
$attrs = array(
	'class'          => 'slides',
	'data-slider'    => 'main',
	'data-autoplay'  => Bunyad::options()->slider_autoplay,
	'data-speed'     => Bunyad::options()->slider_delay,
	//'data-animation' => Bunyad::options()->slider_animation,
	'data-parallax'  => Bunyad::options()->slider_parallax
);


$extra_class = '';
$image       = 'contentberg-main';
$type        = empty($type) ? '' : $type;

// Stylish slider variant?
if ($type == 'stylish') {
	$extra_class = ' stylish-slider';
	$image = 'contentberg-slider-stylish';
}

?>
	
	<section class="main-slider common-slider<?php echo esc_attr($extra_class); ?>">

		<div class="slides" data-autoplay="<?php echo esc_attr(Bunyad::options()->slider_autoplay); ?>" data-speed="<?php 
			echo esc_attr(Bunyad::options()->slider_delay); ?>" data-slider="main">
		
			<?php while ($query->have_posts()): $query->the_post(); ?>
		
				<div class="item">
					<a href="<?php the_permalink(); ?>"><?php 
						the_post_thumbnail($image, array('alt' => strip_tags(get_the_title()), 'title' => '')); 
					?></a>
					
					 <?php 
					 // Primary category
					 if (($cat_label = Bunyad::posts()->meta('cat_label'))) {
					 	$category = get_category($cat_label);
					 }
					 else {
					 	$category = current(get_the_category());
					 }
					 
					 ?>
					
					<div class="slider-overlay">
						<a href="<?php 
							echo esc_url(get_category_link($category)); ?>" class="category"><?php echo esc_html($category->name); 
						?></a>
						
						<h2 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="text excerpt"><?php echo Bunyad::posts()->excerpt(null, 5, array('add_more' => false)); ?></div>
						
						<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read More', 'contentberg'); ?></a>	
					</div>
				</div>
				
			<?php endwhile; ?>
		</div>

	</section>
	
	<?php wp_reset_postdata(); ?>