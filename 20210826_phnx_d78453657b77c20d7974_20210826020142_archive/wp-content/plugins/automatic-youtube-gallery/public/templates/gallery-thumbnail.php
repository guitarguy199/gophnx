<?php

/**
 * Gallery: Thumbnail.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */

$title = ayg_get_gallery_thumb_title( $video, $attributes );
$excerpt = ayg_get_gallery_thumb_excerpt( $video, $attributes );
$description = ayg_get_player_description( $video, $attributes );

$container_class = 'ayg-thumbnail';

if ( ! empty( $title ) || ! empty( $excerpt ) ) {
    $container_class .= ' ayg-has-caption';
}

if ( true === $is_active ) {
    $container_class .= ' ayg-active';
}
?>

<div class="<?php echo $container_class; ?>" data-id="<?php echo esc_attr( $video->id ); ?>" data-title="<?php echo esc_attr( $video->title ); ?>">
    <div class="ayg-thumbnail-image-wrapper">
        <img src="<?php echo esc_url( ayg_get_gallery_thumb_image( $video, $attributes ) ); ?>" class="ayg-thumbnail-image" />
        <div class="ayg-thumbnail-play-icon"></div>
        <div class="ayg-thumbnail-active" style="display: none;"><?php esc_html_e( 'active', 'automatic-youtube-gallery' ); ?></div>
    </div>

    <div class="ayg-thumbnail-caption">
        <?php if ( ! empty( $title ) ) : ?> 
            <div class="ayg-thumbnail-title"><?php echo esc_html( $title ); ?></div>
        <?php endif; ?> 

        <?php if ( ! empty( $excerpt ) ) : ?>
            <div class="ayg-thumbnail-excerpt"><?php echo wp_kses_post( $excerpt ); ?></div>
        <?php endif; ?>

        <?php if ( ! empty( $description ) ) : ?>  
            <div class="ayg-thumbnail-description" style="display: none;"><?php echo wp_kses_post( make_clickable( $description ) ); ?></div>
        <?php endif; ?>
    </div>           
</div>
