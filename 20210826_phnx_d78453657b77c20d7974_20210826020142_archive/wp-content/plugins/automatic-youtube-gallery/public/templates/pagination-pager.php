<?php

/**
 * Pagination: pager
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */
?>

<div class="ayg-pagination" data-nonce="<?php echo wp_create_nonce( 'ayg_pagination_nonce' ); ?>" data-params='<?php echo wp_json_encode( $attributes ); ?>'>
    <span class="ayg-pagination-prev">
        <span class="ayg-btn ayg-pagination-prev-btn" style="display: none;"><?php esc_html_e( 'Prev', 'automatic-youtube-gallery' ); ?></span>
    </span>

    <span class="ayg-pagination-info">
        <span class="ayg-pagination-current-page-number">1</span>
        <?php esc_html_e( 'of', 'automatic-youtube-gallery' ); ?>
        <span class="ayg-pagination-total-pages"><?php echo (int) $attributes['num_pages']; ?></span>
    </span>

    <span class="ayg-pagination-next">
        <span class="ayg-btn ayg-pagination-next-btn"><?php esc_html_e( 'Next', 'automatic-youtube-gallery' ); ?></span>
    </span>
</div>
