<?php
/**
 * Chic Lite Template Hooks.
 *
 * @package Chic_Lite 
 */

/** Import content data*/
if ( ! function_exists( 'chic_lite_import_files' ) ) :
function chic_lite_import_files() {
  $upload_dir = wp_upload_dir();
    return array(
        array(
            'import_file_name'             => 'Chic Lite Demo',
            'local_import_file'            => $upload_dir['basedir'] . '/rara-demo-pack/chic-lite-demo-content/content/chiclite.xml',
            'local_import_widget_file'     => $upload_dir['basedir'] . '/rara-demo-pack/chic-lite-demo-content/content/chiclite.wie',
            'local_import_customizer_file' => $upload_dir['basedir'] . '/rara-demo-pack/chic-lite-demo-content/content/chiclite.dat',
            'import_preview_image_url'     => get_template_directory() .'/screenshot.png',
            'import_notice'                => __( 'Please waiting for a few minutes, do not close the window or refresh the page until the data is imported.', 'chic-lite' ),
        ),
    );       
}
add_filter( 'rrdi/import_files', 'chic_lite_import_files' );
endif;

/** Programmatically set the front page and menu */
if ( ! function_exists( 'chic_lite_after_import' ) ) :

function chic_lite_after_import( $selected_import ) {
      
    //Set Menu
    $primary    = get_term_by( 'name', 'Primary', 'nav_menu' );
    $secondary  = get_term_by( 'name', 'Quick Link', 'nav_menu' );
    $footer     = get_term_by( 'name', 'Quick Link', 'nav_menu' );
    set_theme_mod( 'nav_menu_locations' , array( 
            'primary'   => $primary->term_id,
            'secondary' => $secondary->term_id,
            'footer'    => $footer->term_id
        ) 
    );
      
}
add_action( 'rrdi/after_import', 'chic_lite_after_import' );
endif;

function chic_lite_import_msg(){
    return __( 'Before you begin, make sure all recommended plugins are activated.', 'chic-lite' );
}
add_filter( 'rrdi_before_import_msg', 'chic_lite_import_msg' );