<?php
/**
 * Partial: Top bar template - displayed above logo
 */

if (Bunyad::options()->header_layout == 'full-top') {
	$navigation = true;
	$social_icons = true;
}

if (Bunyad::options()->header_layout == 'alt') {
	$social_icons = true;
}

$navigation   = isset($navigation) ? $navigation : true;
$social_icons = isset($social_icons) ? $social_icons : false;


$is_dark = '';
if (Bunyad::options()->topbar_style == 'dark') {
	$is_dark = ' dark';
}

?>

	<div class="top-bar<?php echo esc_attr($is_dark); ?> cf">
	
		<div class="top-bar-content" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">
			<div class="wrap cf">
			
			<span class="mobile-nav"><i class="fa fa-bars"></i></span>
			
			<?php 
			
			// Output social icons from paritals/header/social-icons.php if enabled
			Bunyad::core()->partial('partials/header/social-icons', compact('social_icons')); 
			
			?>

			<?php if ($navigation): ?>
				
				<?php if (has_nav_menu('contentberg-main')): ?>
						
				<nav class="navigation<?php echo esc_attr($is_dark); ?>">					
					<?php 
						wp_nav_menu(array(
							'theme_location' => 'contentberg-main',
							'fallback_cb' => '', 
							'walker' => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : '')
						)); 
					?>
				</nav>
				
				<?php endif; ?>
				
			<?php endif; ?>
				
			
				<div class="actions">
					
					<?php do_action('bunyad_top_bar_right'); ?>
					
					<?php if (Bunyad::options()->topbar_cart && class_exists('Bunyad_Theme_WooCommerce')): ?>
					
					<div class="cart-action cf">
						
						<?php echo Bunyad::get('woocommerce')->cart_link(); ?>
						
					</div>
					
					<?php endif; ?>
					
					<?php if (Bunyad::options()->topbar_search): ?>
					
					<div class="search-action cf">
					
						<?php Bunyad::helpers()->search_form('alt'); ?>
								
					</div>
					
					<?php endif; ?>
				
				</div>
				
			</div>			
		</div>
		
	</div>
