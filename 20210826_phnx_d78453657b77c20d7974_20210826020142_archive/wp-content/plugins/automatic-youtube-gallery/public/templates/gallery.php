<?php

/**
 * Gallery.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */
?>

<?php foreach ( $videos as $index => $video ) : ?>
    <div class="ayg-col ayg-col-<?php echo esc_attr( $attributes['columns'] ); ?>">
        <?php the_ayg_gallery_thumbnail( $video, $attributes, ( $video->id === $active_id ? true : false ) ); ?>
    </div>
<?php endforeach;