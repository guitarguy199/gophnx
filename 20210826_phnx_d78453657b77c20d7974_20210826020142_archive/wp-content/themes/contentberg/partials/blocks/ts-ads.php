<?php
/**
 * Call Advertisement Block for Visual Composer
 */

// This shortcode can be rendered after import when VC is inactive, but relies on it.
if (!Bunyad::get('cb-vc')) {
	return;
}

if (!empty($code)) {
	$atts['ad_code'] = Bunyad::get('cb-vc')->textarea_decode($code);
}

$type = 'ContentBerg_Widgets_Ads';
$args = array();

?>

<div class="block">
	<?php the_widget($type, $atts, $args); ?>
</div>

