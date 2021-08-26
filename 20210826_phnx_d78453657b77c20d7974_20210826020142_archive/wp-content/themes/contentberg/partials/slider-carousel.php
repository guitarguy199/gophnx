<?php
/**
 * Partial: "Carousel" Slider for the featured area
 */

$attrs = array(
	'class'          => 'slides wrap',
	'data-slider'    => 'carousel',
	'data-autoplay'  => Bunyad::options()->slider_autoplay,
	'data-speed'     => Bunyad::options()->slider_delay,
	//'data-animation' => Bunyad::options()->slider_animation,
	'data-parallax'  => Bunyad::options()->slider_parallax
);

?>
	
	<section class="common-slider carousel-slider">
	
		<div <?php Bunyad::markup()->attribs('slider-slides', $attrs); ?>>
		
			<?php while ($query->have_posts()): $query->the_post(); ?>
		
				<div class="item">
					<a href="<?php the_permalink(); ?>"><?php 
						the_post_thumbnail('contentberg-slider-carousel', array('alt' => strip_tags(get_the_title()), 'title' => '')); 
					?></a>
					
					<div class="overlay cf">

						<?php Bunyad::get('helpers')->meta_cats(); ?>
							
						<h2 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						
					</div>
					
				</div>
				
			<?php endwhile; ?>
		</div>

	</section>
	
	<?php wp_reset_postdata(); ?>