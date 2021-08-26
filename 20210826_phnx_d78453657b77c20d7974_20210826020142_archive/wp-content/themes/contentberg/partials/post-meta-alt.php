<?php
/**
 * Partial: Common alternate post meta template
 */

extract(array(
	'add_class' => ''
), EXTR_SKIP);

if (empty($tag)) {
	
	/**
	 * Set h1 tag on single post page
	 */
	
	$tag =  'h1';
	if (!is_single() OR is_front_page()) {
		$tag = 'h2';
	}
}

$class = array('post-meta', $add_class);

?>
	<div <?php Bunyad::markup()->attribs('post-meta-wrap', array('class' => $class)); ?>>
		
		<?php if (Bunyad::options()->meta_category): ?>
		
			<span class="post-cat">	
				<?php Bunyad::get('helpers')->meta_cats(); ?>
			</span>
			
		<?php endif; ?>
		
		
		<<?php echo esc_attr($tag); ?> class="post-title">
			<?php 
			if (is_single()):
				the_title(); 
			else: ?>
		
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				
			<?php endif; ?>
			
		</<?php echo esc_attr($tag); ?>>
		
			
		<?php if (Bunyad::options()->meta_date): ?>
			<a href="<?php the_permalink(); ?>" class="date-link"><time class="post-date"><?php echo esc_html(get_the_date()); ?></time></a>
		<?php endif; ?>
		
	</div>
