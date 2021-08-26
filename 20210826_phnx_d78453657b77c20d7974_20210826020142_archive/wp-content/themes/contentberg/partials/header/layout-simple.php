<?php
/**
 * Header Layout Style 9: Simple Full Width header
 */

$classes = (!empty($classes) ? $classes : 'simple');

if (is_front_page() && Bunyad::options()->header_nosep_home) {
	$classes .= ' no-separator';
}

?>

<header id="main-head" class="main-head head-nav-below has-search-modal <?php echo esc_attr($classes); ?>">

	<div class="inner inner-head" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">
	
		<div class="wrap cf wrap-head">
		
			<div class="left-contain">
				<span class="mobile-nav"><i class="fa fa-bars"></i></span>	
			
				<?php get_template_part('partials/header/logo'); ?>
			
			</div>
				
				
			<div class="navigation-wrap inline">
				<?php if (has_nav_menu('contentberg-main')): ?>
				
				<nav class="navigation inline simple <?php echo esc_attr(Bunyad::options()->nav_style); ?>" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">
					<?php 
						wp_nav_menu(array(
							'theme_location' => 'contentberg-main',
							'fallback_cb' => '', 
							'walker' => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : ''),
							'link_before' => '<span>',
							'link_after'  => '</span>',
						)); 
					?>
				</nav>
				
				<?php endif; ?>
			</div>
			
			<div class="actions">
			
				<?php 
				
				// Output social icons from paritals/header/social-icons.php if enabled
				Bunyad::core()->partial('partials/header/social-icons', array('social_icons' => true)); 
				
				?>
				
				<?php if (Bunyad::options()->topbar_search): ?>
				
					<a href="#" title="<?php esc_attr_e('Search', 'contentberg'); ?>" class="search-link"><i class="fa fa-search"></i></a>
									
				<?php endif; ?>

				<?php if (Bunyad::options()->topbar_cart && class_exists('Bunyad_Theme_WooCommerce')): ?>
				
					<div class="cart-action cf">
						<?php echo Bunyad::get('woocommerce')->cart_link(); ?>
					</div>
				
				<?php endif; ?>
			
			</div>

		</div>
	</div>

</header> <!-- .main-head -->