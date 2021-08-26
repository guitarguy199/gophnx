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
	'show_read_time' => Bunyad::options()->meta_read_time,
	'show_comments'  => Bunyad::options()->meta_comments,
	'title_class' => 'post-title-alt',
	'title_tag'   => '',
	'add_class'   => ''
), EXTR_SKIP);

$class = array('post-meta', 'post-meta-b', $add_class);

// Choose heading tag for SEO
if (!$title_tag) {
	$title_tag = $is_single ? 'h1' : 'h2';
}

?>
	<div <?php Bunyad::markup()->attribs('post-meta-wrap', array('class' => $class)); ?>>
		
		<?php if ($show_cat): ?>
		
			<span class="post-cat">	
				<span class="text-in"><?php esc_html_e('In', 'contentberg'); ?></span> 
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
				else: 
				?>
			
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					
				<?php endif; ?>
			</<?php echo esc_attr($title_tag); ?>>
			
		<?php endif; ?>
		
		<div class="below">
		
			<?php if ($show_date): ?>
				<a href="<?php the_permalink(); ?>" class="meta-item date-link"><time class="post-date" datetime="<?php 
					echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time></a>

				<span class="meta-sep"></span>
			<?php endif; ?>
			
			<?php if ($show_comments): ?>
				<span class="meta-item comments"><a href="<?php echo esc_url(get_comments_link()); ?>"><?php comments_number(); ?></a></span>

				<span class="meta-sep"></span>
			<?php endif; ?>

			<?php if ($show_read_time): ?>
				<span class="meta-item read-time"><?php echo esc_html(Bunyad::helpers()->reading_time()); ?></span>
			<?php endif; ?>
		
		</div>
		
	</div>