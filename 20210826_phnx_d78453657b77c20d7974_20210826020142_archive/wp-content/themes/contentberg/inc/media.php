<?php
/**
 * Methods related to media functionalities (images, audio and videos)
 */
class Bunyad_Theme_Media
{
	public function __construct()
	{
		add_filter('max_srcset_image_width', array($this, '_max_size'), 10, 2);
	}
	
	/**
	 * Callback: Register max width for srcset
	 */
	public function _max_size($width, $size = array())
	{
		// 2x for 1170px images should be allowed
		if (!empty($size[0]) && $size[0] >= 1170) {
			return 2400;
		}
		
		return 1800;
	}
	
	/**
	 * Get post thumbnail image size if it exists or a fallback
	 * 
	 * @param string $size
	 */
	public function image_size($size, $fallback = '') 
	{
		$id = get_post_thumbnail_id();
		
		if (image_get_intermediate_size($id, $size)) {
			return $size;
		}
		
		// Sane size for full
		if ($fallback == 'full') {

			// Get featured image dimensions
			list($url, $width, $height) = wp_get_attachment_image_src($id, 'full');
			
			// Tall images need more larger allowance
			if ($width > 2400 OR $height > 3000) {
				$fallback = 'large';
			}
		}
		
		return $fallback;
	}
}

// init and make available in Bunyad::get('media')
Bunyad::register('media', array(
	'class' => 'Bunyad_Theme_Media',
	'init' => true
));