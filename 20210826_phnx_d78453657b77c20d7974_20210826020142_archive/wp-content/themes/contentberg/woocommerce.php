<?php
/**
 * WooCommerce Main Template Catch-All 
 */


// Change sidebar for shop page
Bunyad::registry()->sidebar = 'contentberg-shop';

?>

<?php get_header(); ?>

<?php 
/**
 * An expansion of woocommerce_content() using same structure and filters
 */
?>
		
<?php if (is_singular('product')): ?>
		
	<div class="main wrap">
	
		<div class="ts-row cf">
			<div class="col-8 main-content cf">
			
				<?php woocommerce_content(); ?>
				
			</div>
			
			<?php Bunyad::core()->theme_sidebar(); ?>
			
		</div> <!-- .ts-row -->
	</div> <!-- .main -->
			

<?php else: // An archive ?>
		
	<?php if (apply_filters('woocommerce_show_page_title', true)): ?>
	
	<div class="archive-head">

		<span class="sub-title"><?php esc_html_e('Browsing', 'contentberg'); ?></span>
		<h2 class="title"><?php woocommerce_page_title(); ?></h2>

		<i class="background"><?php esc_html_e('Browsing', 'contentberg'); ?></i>
		
		<div class="text description"><?php do_action('woocommerce_archive_description'); ?></div>

	</div>
	
	<?php endif; ?>
	
	<div class="main wrap">

		<div class="ts-row cf">
			<div class="col-8 main-content cf">

			<?php if (have_posts()) : ?>

				<?php do_action('woocommerce_before_shop_loop'); ?>

				<?php woocommerce_product_loop_start(); ?>

					<?php woocommerce_product_subcategories(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php do_action('woocommerce_after_shop_loop'); ?>

			<?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>

				<?php wc_get_template('loop/no-products-found.php'); ?>

			<?php endif; ?>

		</div>
		
		<?php Bunyad::core()->theme_sidebar(); ?>
		
		</div> <!-- .ts-row -->
	</div> <!-- .main -->
		
<?php endif; // archive ?>


<?php get_footer(); ?>