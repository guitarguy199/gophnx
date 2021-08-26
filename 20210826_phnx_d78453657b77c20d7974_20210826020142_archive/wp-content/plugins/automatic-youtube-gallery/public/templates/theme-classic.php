<?php

/**
 * Theme: Classic.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package Automatic_YouTube_Gallery
 */

$data_params = array(    
    'autoplay'           => (int) $attributes['autoplay'],
    'loop'               => (int) $attributes['loop'],
    'controls'           => (int) $attributes['controls'],
    'modestbranding'     => (int) $attributes['modestbranding'],
    'cc_load_policy'     => (int) $attributes['cc_load_policy'],
    'iv_load_policy'     => (int) $attributes['iv_load_policy'],
    'hl'                 => sanitize_text_field( $attributes['hl'] ),
    'cc_lang_pref'       => sanitize_text_field( $attributes['cc_lang_pref'] ),
    'autoadvance'        => (int) $attributes['autoadvance'],
    'player_title'       => (int) $attributes['player_title'],
    'player_description' => (int) $attributes['player_description']
);

$featured_video = $videos[0]; // Featured Video
$featured_video_title = ayg_get_player_title( $featured_video, $attributes ); // Featured Video Title
$featured_video_description = ayg_get_player_description( $featured_video, $attributes ); // Featured Video Description
?>

<div class="ayg ayg-theme-classic" data-params='<?php echo wp_json_encode( $data_params ); ?>'>
    <!-- Player -->
    <div class="ayg-player">
        <?php the_ayg_player( $featured_video, $attributes ); ?>

        <?php if ( ! empty( $featured_video_title ) || ! empty( $featured_video_description ) ) : ?>
            <div class="ayg-player-caption">
                <?php if ( ! empty( $featured_video_title ) ) : ?>    
                    <h2 class="ayg-player-title"><?php echo esc_html( $featured_video_title ); ?></h2>  
                <?php endif; ?>

                <?php if ( ! empty( $featured_video_description ) ) : ?>  
                    <div class="ayg-player-description"><?php echo wp_kses_post( make_clickable( $featured_video_description ) ); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Gallery -->
    <div class="ayg-gallery ayg-row">
        <?php the_ayg_gallery( $videos, $attributes, $featured_video->id ); ?>
    </div>

    <!-- Pagination -->    
    <?php the_ayg_pagination( $attributes ); ?>
</div>
