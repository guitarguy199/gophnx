<?php
/**
 * Partial: Common post meta template
 */

// Defaults - can be overridden
extract(array(
	'is_single'  => false,
	'show_title' => true,
	'show_cat'   => Bunyad::options()->meta_category,
	'show_date'  => Bunyad::options()->meta_date,
	'title_class' => 'post-title-alt',
	'title_tag'   => '',
	'add_class'   => '',
), EXTR_SKIP);

$class = array('post-meta post-meta-a', $add_class);

// Choose heading tag for SEO
if (!$title_tag) {
	$title_tag = $is_single ? 'h1' : 'h2';
}

?>
	<div <?php Bunyad::markup()->attribs('post-meta-wrap', array('class' => $class)); ?>>
		
		<?php if ($show_cat): ?>
		
			<span class="post-cat">	
				<?php Bunyad::get('helpers')->meta_cats(); ?>
			</span>
			
			<span class="meta-sep"></span>
			
		<?php endif; ?>
			
		<?php if ($show_date): ?>
			<a href="<?php the_permalink(); ?>" class="date-link"><time class="post-date" datetime="<?php 
				echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time></a>
		<?php endif; ?>
		
		<?php 
			// Show title?
			if ($show_title): 
		?>			
			
			<<?php echo esc_attr($title_tag); ?> class="<?php echo esc_attr($title_class); ?>">
				<?php 
				if ($is_single): 
					the_title(); 
				else: ?>
			
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					
				<?php endif; ?>
			</<?php echo esc_attr($title_tag); ?>>
			
		<?php endif; ?>

	</div>