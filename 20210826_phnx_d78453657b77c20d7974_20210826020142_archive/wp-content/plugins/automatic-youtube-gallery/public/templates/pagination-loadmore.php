<?php

/**
 * Pagination: loadmore
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */
?>

<div class="ayg-pagination" data-nonce="<?php echo wp_create_nonce( 'ayg_pagination_nonce' ); ?>" data-params='<?php echo wp_json_encode( $attributes ); ?>'>
    <span class="ayg-pagination-more">
        <span class="ayg-btn ayg-pagination-more-btn"><?php esc_html_e( 'More Videos', 'automatic-youtube-gallery' ); ?></span>
    </span>
</div>
