<?php 
/**
 * Page for WooCommerce - Cart, Checkout, My Account 
 */

get_header();

// Init the page
the_post();

$sub_title  = esc_html_x('Browsing', 'woocommerce', 'contentberg');
$background = get_the_title();

if (is_cart()) {
	$sub_title  = esc_html_x('Viewing', 'woocommerce', 'contentberg');
	$background = esc_html_x('Shopping', 'woocommerce', 'contentberg');
}

if (is_checkout()) {
	$sub_title  = esc_html_x('Finishing', 'woocommerce', 'contentberg');
}

if (is_account_page()) {
	$background = esc_html_x('Account', 'woocommerce', 'contentberg');
}

?>
<div class="archive-head">

	<span class="sub-title"><?php echo esc_html($sub_title); ?></span>
	<h2 class="title"><?php the_title(); ?></h2>

	<i class="background"><?php echo esc_html($background); ?></i>

</div>
	
<div class="main wrap">

	<div class="ts-row cf">
		<div class="col-8 main-content cf">
			
		<?php if (is_cart()): ?>
		
			<?php the_content(); ?>
		
		<?php else: ?>

			<div class="post-content">
				<?php the_content(); ?>
			</div>

		<?php endif; ?>

		</div>
		
		<?php Bunyad::core()->theme_sidebar(); ?>
		
	</div> <!-- .ts-row -->
</div> <!-- .main -->

<?php get_footer(); ?>