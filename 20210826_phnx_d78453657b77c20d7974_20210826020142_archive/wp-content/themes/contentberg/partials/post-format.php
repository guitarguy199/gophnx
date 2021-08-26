<?php
/**
 * Post Format icon
 */

if (!Bunyad::options()->post_format_icons) {
	return;
}

?>

<?php if (get_post_format() == 'video'): ?>

	<span class="format-icon format-video">
		<i class="icon fa fa-play"></i>
	</span>

<?php elseif (get_post_format() == 'gallery'): ?>

	<span class="format-icon format-gallery">
		<i class="icon fa fa-clone"></i>
	</span>

<?php endif; ?>