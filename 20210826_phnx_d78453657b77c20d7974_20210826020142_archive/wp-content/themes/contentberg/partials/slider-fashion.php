<?php
/**
 * Partial: Slider for the featured area
 */

$attrs = array(
	'class'          => 'slides wrap',
	'data-slider'    => 'fashion',
	'data-autoplay'  => Bunyad::options()->slider_autoplay,
	'data-speed'     => Bunyad::options()->slider_delay,
	'data-animation' => Bunyad::options()->slider_animation,
	'data-parallax'  => Bunyad::options()->slider_parallax
);

?>
	
	<section class="common-slider fashion-slider">
	
		<div <?php Bunyad::markup()->attribs('slider-slides', $attrs); ?>>
		
			<?php while ($query->have_posts()): $query->the_post(); ?>
		
				<div class="item">
					<a href="<?php the_permalink(); ?>" class="image-link">
					<?php 
						the_post_thumbnail(
							Bunyad::media()->image_size('contentberg-slider-fashion', 'large'),
							array('alt' => strip_tags(get_the_title()), 'title' => '')
						); 
					?>
					</a>
					
					<div class="overlay-wrap cf">

						<div class="overlay">
						<?php 
							Bunyad::core()->partial(
								'partials/post-meta-b', 
								array('title_class' => 'post-title')
							); 
						?>
						</div>
						
					</div>
					
				</div>
				
			<?php endwhile; ?>
		</div>

	</section>
	
	<?php wp_reset_postdata(); ?>