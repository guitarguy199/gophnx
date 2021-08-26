<?php
/**
 * Title and meta for single posts
 */
?>
	<?php 
		/**
		 * Set h1 tag on single post page
		 */
		$tag = 'h1';
		
		if (!is_single() OR is_front_page()) {
			$tag = 'h2';
		}
	?>
	
	<div class="post-meta">
	
		<span class="post-cat"><?php Bunyad::get('helpers')->meta_cats(); ?></span>
		
		<<?php echo esc_attr($tag); ?> class="post-title" itemprop="name headline">
	
		<?php 
			if (is_single()): 
				the_title(); 
			else: ?>
		
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="post-title-link"><?php the_title(); ?></a>
				
		<?php endif;?>
		
		</<?php echo esc_attr($tag); ?>>			
		

		<time class="post-date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
							
	</div>