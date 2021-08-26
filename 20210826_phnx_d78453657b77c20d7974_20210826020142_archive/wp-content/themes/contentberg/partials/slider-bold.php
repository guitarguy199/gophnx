<?php
/**
 * Partial: Slider for the featured area
 */

$attrs = array(
	'class'          => 'slides',
	'data-slider'    => 'bold',
	'data-autoplay'  => Bunyad::options()->slider_autoplay,
	'data-speed'     => Bunyad::options()->slider_delay,
	'data-animation' => Bunyad::options()->slider_animation,
	'data-parallax'  => Bunyad::options()->slider_parallax
);

?>
	
	<section class="common-slider bold-slider">
	
		<div <?php Bunyad::markup()->attribs('slider-slides', $attrs); ?>>
		
			<?php while ($query->have_posts()): $query->the_post(); ?>
			
				<?php 
				
				$image = Bunyad::media()->image_size('contentberg-large-cover', 'full');
				
				if ($query->current_post >= 1) {
					//Bunyad::lazyload()->enable();
				}
				
				?>
		
				<div class="item">
					<a href="<?php the_permalink(); ?>"><?php 
						the_post_thumbnail($image, array('alt' => strip_tags(get_the_title()), 'title' => '', 'sizes' => '100vw')); 
					?></a>
					
					<div class="overlay cf">
					
						<span class="cats"><?php Bunyad::get('helpers')->meta_cats(); ?></span>
						
						<h2 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="author"><?php printf(esc_html_x('by %s', 'Post Meta', 'contentberg'), get_the_author_posts_link()); ?></div>
						
					</div>
					
				</div>

			<?php endwhile; $query->rewind_posts(); ?>
		</div>
		
		<div class="thumbs-wrap">		
			<div class="thumbs">
			
				<?php 
					// Disable for thumbs again
					Bunyad::lazyload()->disable();
				?>
			
				<?php while ($query->have_posts()): $query->the_post(); ?>
				
				<a href="#" class="post-thumb"><?php 
						the_post_thumbnail('contentberg-slider-bold-sm', array('alt' => strip_tags(get_the_title()), 'title' => '')); 
				?></a>
				
				<?php endwhile; ?>
	
			</div>
		</div>

	</section>
	
	<?php wp_reset_postdata(); ?>