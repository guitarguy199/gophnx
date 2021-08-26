<?php
/**
 * Partial: Classic Dark layout
 */
?>

	
	<footer class="main-footer dark classic">
	
		
		<?php if (is_active_sidebar('contentberg-instagram')): ?>
		
		<section class="mid-footer cf">
			<?php dynamic_sidebar('contentberg-instagram'); ?>
		</section>
		
		<?php endif; ?>
		
		<div class="bg-wrap">

			<?php if (Bunyad::options()->footer_upper): ?>	
			
			<section class="upper-footer">
			
				<div class="wrap">
					<?php if (is_active_sidebar('contentberg-footer')): ?>
					
					<ul class="widgets ts-row cf">
						<?php dynamic_sidebar('contentberg-footer'); ?>
					</ul>
					
					<?php endif; ?>
				</div>
			</section>
			
			<?php endif; ?>
			
	
			<?php if (Bunyad::options()->footer_lower): ?>
			
			<section class="lower-footer cf">
				<div class="wrap">
				
					<div class="bottom cf">
						<p class="copyright"><?php 
							echo do_shortcode(
								wp_kses_post(Bunyad::options()->footer_copyright) 
							); ?>
						</p>

						
						<?php if (Bunyad::options()->footer_back_top): ?>
							<div class="to-top">
								<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i> <?php esc_html_e('Top', 'contentberg'); ?></a>
							</div>
						<?php endif; ?>
						
					</div>
				</div>
			</section>
			
			<?php endif; ?>
		
		</div>
		
	</footer>