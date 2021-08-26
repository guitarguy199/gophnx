<?php
/**
 * Partial: Social Share Counters for Single Page
 */

// Nothing to show?
if (!Bunyad::options()->posts_likes && !Bunyad::options()->single_share) {
	return;
}

$services = Bunyad::get('cb_social')->share_services();
$services['pinterest']['icon'] = 'pinterest-p';

$active = apply_filters(
	'bunyad_social_share_active',
	array('facebook', 'twitter', 'linkedin', 'pinterest')
);

?>
		<div class="post-share">
					
			<?php if (Bunyad::options()->single_share): ?>
			
			<div class="post-share-icons cf">
			
				<span class="counters">

					<?php if (Bunyad::options()->posts_likes && is_object(Bunyad::get('likes'))): ?>
						<?php Bunyad::get('likes')->heart_link(); ?>
					<?php endif; ?>
					
				</span>

				<?php 
					// Output all the services icons.
					foreach ($active as $key): 

						if (!isset($services[$key])) {
							continue;
						}
						
						$service = $services[$key];
				?>
				
					<a href="<?php echo esc_url($service['url']); ?>" class="link <?php echo esc_attr($key); ?>" target="_blank" title="<?php 
						echo esc_attr($service['label'])?>"><i class="fa fa-<?php echo esc_attr($service['icon']); ?>"></i></a>
						
				<?php endforeach; ?>
					
				<?php 
				/**
				 * A filter to programmatically add more icons
				 */
				do_action('bunyad_post_social_icons'); 
				?>
				
			</div>
			
			<?php endif; ?>
			
		</div>