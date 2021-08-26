<?php 
/**
 * Partial: Fixed blocks to display below slider on home
 */
?>

	<?php
		/**
		 * Show subscribe box at home?
		 */
		if (Bunyad::options()->home_subscribe):
	?>
	
	<div class="blocks">
		<?php get_template_part('partials/home/block-subscribe'); ?>
	</div>
		
	<?php endif; ?>


	<?php
		/**
		 * Show subscribe box at home?
		 */
		if (Bunyad::options()->home_cta && is_active_sidebar('contentberg-home-cta')):
	?>
	
	<div class="blocks">
		<?php dynamic_sidebar('contentberg-home-cta'); ?>
	</div>
		
	<?php endif; ?>