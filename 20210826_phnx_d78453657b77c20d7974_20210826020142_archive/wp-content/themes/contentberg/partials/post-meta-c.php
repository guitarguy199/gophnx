<?php
/**
 * Partial: Common post meta template
 */

// Defaults - can be overriden
extract(array(
	'is_single'   => false,
	'show_title'  => true,
	'title_class' => 'post-title-alt',
	'title_tag'   => '',
	'show_author' => true,
	'enable_cat'  => false,
	'add_class'   => ''
), EXTR_SKIP);

// Post Meta C is not supposed to show category at all, by default
if (!empty($enable_cat)) {
	$show_cat = (isset($show_cat) ? $show_cat : Bunyad::options()->meta_category);
}
else {
	$show_cat = false;
}

$class = array('post-meta', 'post-meta-c', $add_class);

// Choose heading tag for SEO
if (!$title_tag) {
	$title_tag = $is_single ? 'h1' : 'h2';
}

?>
	<div <?php Bunyad::markup()->attribs('post-meta-wrap', array('class' => $class)); ?>>
		
		<?php if ($show_cat): ?>
		
			<span class="cat-label cf">
				<?php Bunyad::get('helpers')->meta_cats(); ?>
			</span>
			
		<?php endif; ?>

		
		<?php 
			// Show title? Choose heading tag for SEO
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
		
		
		<?php if ($show_author): ?>
		
			<span class="post-author"><?php printf(esc_html_x('%sBy%s', 'Post Meta', 'contentberg'), '<span class="by">', '</span> ' . get_the_author_posts_link()); ?></span>
			<span class="meta-sep"></span>
			
		<?php endif;?>
		
		
		<?php if (Bunyad::options()->meta_date): ?>
			<a href="<?php the_permalink(); ?>" class="date-link"><time class="post-date" datetime="<?php 
				echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time></a>
		<?php endif; ?>

		
	</div>