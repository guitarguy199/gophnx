<?php
/**
 * Partial: Featured image/video/gallery part of single post
 */

if (Bunyad::posts()->meta('featured_disable')) {
	return;
}

extract(array(
	'show_social' => true,
	'image_attrs' => array('title' => strip_tags(get_the_title())),
), EXTR_SKIP);

?>

	<div class="featured">
	
		<?php if (get_post_format() == 'gallery'): // get gallery template ?>
		
			<?php Bunyad::core()->partial('partials/gallery-format', compact('image')); ?>
			
		<?php elseif (Bunyad::posts()->meta('featured_video')): // featured video available? ?>
		
			<div class="featured-vid">
				<?php echo apply_filters('bunyad_featured_video', esc_html(Bunyad::posts()->meta('featured_video'))); ?>
			</div>
			
		<?php elseif (has_post_thumbnail()): ?>
		
			<?php 
				/**
				 * Normal featured image when no post format
				 */
				$caption = get_post(get_post_thumbnail_id())->post_excerpt;
				$url     = get_permalink();
				
				// On single page? Link to image
				if (is_single()):
					$url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); 
					$url = $url[0];
				endif;				
			?>
		
			<a href="<?php echo esc_url($url); ?>" class="image-link"><?php 
					the_post_thumbnail($image, $image_attrs
				); ?>
			</a>
			
		<?php endif; // normal featured image ?>
		
		<div class="overlay post-meta the-post-meta">
		
			<span class="post-cat"><?php Bunyad::get('helpers')->meta_cats(); ?></span>
			
			<h1 class="post-title"><?php the_title(); ?></h1> 

			<span class="post-by meta-item"><?php echo esc_html_x('By', 'Post Meta', 'contentberg'); ?> 
				<span><?php the_author_posts_link(); ?></span>
			</span>
			<span class="meta-sep"></span>
			
			<time class="post-date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php 
				echo esc_html(get_the_date()); ?></time>

			<?php if (Bunyad::options()->meta_read_time): ?>

				<span class="meta-sep"></span>
				<span class="meta-item read-time"><?php echo esc_html(Bunyad::helpers()->reading_time()); ?></span>

			<?php endif; ?>
			
			<?php if ($show_social && class_exists('ContentBerg_Core')): ?>
				<?php 
					// See plugins/contentberg-core/social-share/views/social-share.php
					Bunyad::get('cb_social')->render('social-share'); 
				?>
			<?php endif;?>
			
		</div>
		
	</div>