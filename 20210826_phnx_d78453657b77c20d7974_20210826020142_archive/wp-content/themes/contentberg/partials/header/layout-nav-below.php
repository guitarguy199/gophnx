<?php
/**
 * Header Layout: Navigation Below Logo
 */

$top_bar = (!empty($top_bar) ? $top_bar : 'top-bar');
$classes = (!empty($classes) ? $classes : 'nav-below');

?>

<header id="main-head" class="main-head head-nav-below <?php echo esc_attr($classes); ?>">

<?php 
	// Get Top Bar - top-bar.php or top-bar-b.php depending on setting
	Bunyad::core()->partial(
			'partials/header/' . sanitize_file_name($top_bar), 
			array('navigation' => false, 'social_icons' => true)
	); 
?>
	<div class="inner">
		<div class="wrap logo-wrap cf">

			<?php get_template_part('partials/header/logo'); ?>

		</div>
	</div>
	
	<div class="navigation-wrap">
		<?php if (has_nav_menu('contentberg-main')): ?>
		
		<nav class="navigation below <?php echo esc_attr(Bunyad::options()->nav_style); ?>" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">					
			<div class="wrap">
				<?php 
					wp_nav_menu(array(
						'theme_location' => 'contentberg-main',
						'fallback_cb' => '', 
						'walker' => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : '')
					)); 
				?>
			</div>
		</nav>
		
		<?php endif; ?>
	</div>
	
</header> <!-- .main-head -->