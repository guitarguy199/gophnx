<?php
/**
 * Partial: Top bar template - alternate style with latest posts and icons 
 */

// Defaults, can be overriden
extract(array(
	'social_icons' => true,
	'search_icon'  => false,
	'posts_ticker' => true
), EXTR_SKIP);

$top_menu = Bunyad::options()->topbar_top_menu;

$is_dark = '';
if (Bunyad::options()->topbar_style == 'dark') {
	$is_dark = ' dark';
}


// Force disable ticker if top menu is enabled
if ($top_menu) {
	$posts_ticker = false;
}

?>

	<div class="top-bar<?php echo esc_attr($is_dark); ?> top-bar-b cf">
	
		<div class="top-bar-content" data-sticky-bar="<?php echo esc_attr(Bunyad::options()->topbar_sticky); ?>">
			<div class="wrap cf">
			
			<span class="mobile-nav"><i class="fa fa-bars"></i></span>
			
			<?php if ($posts_ticker): ?>
			
			<div class="posts-ticker">
				<span class="heading"><?php echo esc_html(Bunyad::options()->topbar_ticker_text); ?></span>

				<ul>
					<?php $query = new WP_Query(apply_filters('bunyad_ticker_query_args', array('orderby' => 'date', 'order' => 'desc', 'posts_per_page' => 8))); ?>
					
					<?php while($query->have_posts()): $query->the_post(); ?>
					
						<li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
					
					<?php endwhile; ?>
					
					<?php wp_reset_postdata(); ?>
				</ul>
			</div>
			
			<?php endif; ?>
			
			<?php if ($top_menu): ?>
			
				<?php if (has_nav_menu('contentberg-top-menu')): ?>
						
				<nav class="navigation<?php echo esc_attr($is_dark); ?>">					
					<?php 
						wp_nav_menu(array(
							'theme_location' => 'contentberg-top-menu',
							'fallback_cb' => '', 
							'walker' => (class_exists('Bunyad_Menus') ? 'Bunyad_MenuWalker' : '')
						)); 
					?>
				</nav>
				
				<?php endif; ?>
			
			<?php endif; ?>
			
			
			<?php if ($search_icon): ?>
				
			<div class="actions">
				<div class="search-action cf">
				
					<?php Bunyad::helpers()->search_form('alt'); ?>
					
				</div>
			</div>
			
			<?php endif; ?>
			
			
			<?php 
			
			// Output social icons from paritals/header/social-icons.php if enabled
			Bunyad::core()->partial('partials/header/social-icons', compact('social_icons')); 
			
			?>
				
			</div>			
		</div>
		
	</div>
