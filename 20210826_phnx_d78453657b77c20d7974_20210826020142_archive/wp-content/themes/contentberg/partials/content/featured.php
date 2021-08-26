<?php 
/**
 * Partial: Featured area of post - image, gallery etc.
 */

if (is_single() && Bunyad::posts()->meta('featured_disable')) {
	return;
}

?>				
		<div class="featured">
		
			<?php if (get_post_format() == 'gallery'): // get gallery template ?>
			
				<?php get_template_part('partials/gallery-format'); ?>
				
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
					
					// Which image?
					if (Bunyad::helpers()->relative_width() > 67) {
						$image = 'contentberg-main-full';
					}
					else if (!Bunyad::options()->featured_crop) { // use uncropped image, but if there's a sidebar - not for full-width mode in listings
						$image = 'large';
					}
					else {
						$image = 'contentberg-main';
					}
				
				?>
			
				<a href="<?php echo esc_url($url); ?>" class="image-link"><?php the_post_thumbnail(
						$image,  // larger image if no sidebar
						array('title' => strip_tags(get_the_title()))
					); ?>
				</a>
				
			<?php endif; // normal featured image ?>
		</div>