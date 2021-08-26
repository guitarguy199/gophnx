<?php

/**
 * Player
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */
?>

<div class="ayg-player-wrapper" style="padding-bottom: <?php echo esc_attr( ayg_get_player_ratio( $attributes ) ); ?>;">
    <iframe id="ayg-player-<?php echo esc_attr( ayg_get_uniqid() ); ?>" class="ayg-player-iframe" width="100%" height="100%" src="<?php echo esc_url( ayg_get_player_embed_url( $video, $attributes ) ); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
