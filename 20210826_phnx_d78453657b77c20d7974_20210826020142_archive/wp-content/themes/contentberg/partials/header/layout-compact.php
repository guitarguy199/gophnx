<?php
/**
 * Header Layout Style 8: Top Bar + Compact Header
 */
?>

<header id="main-head" class="main-head head-nav-below has-search-overlay compact">

	<?php Bunyad::core()->partial('partials/header/top-bar-b', array('search_icon' => true)); ?>

	<div class="inner inner-head" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">	
		<div class="wrap cf">
		
			<?php get_template_part('partials/header/logo'); ?>
	
			<div class="navigation-wrap inline">
				<?php if (has_nav_menu('contentberg-main')): ?>
				
				<nav class="navigation inline <?php echo esc_attr(Bunyad::options()->nav_style); ?>" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">
					<?php 
						wp_nav_menu(array(
							'theme_location' => 'contentberg-main',
							'fallback_cb' => '', 
							'walker' => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : '')
						)); 
					?>
				</nav>
				
				<?php endif; ?>
			</div>
			
		</div>
	</div>

</header> <!-- .main-head -->

<?php if (Bunyad::options()->header_ad): ?>

<div class="widget-a-wrap">
	<div class="the-wrap head">
		<?php echo do_shortcode(Bunyad::options()->header_ad); ?>
	</div>
</div>

<?php endif; ?>