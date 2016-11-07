<?php
/**
 * Add metaboxes for posts
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

/*======================================================================*/

function auxin_push_metabox_models_post( $models ){

    // Load general metabox models

    include_once( 'metabox-fields-post-sidebar-layout.php'  );
    include_once( 'metabox-fields-general-title-setting.php');

    include_once( 'metabox-fields-general-bg-setting.php'     );
    include_once( 'metabox-fields-general-advanced.php'       );


    $models[] = array(
        'model'     => auxin_metabox_fields_post_sidebar_layout(),
        'priority'  => 10
    );


    // hide title bar by default on single posts
    $title_model = auxin_metabox_fields_general_title();
    if( isset( $title_model->fields[1]['default'] ) )  $title_model->fields[1]['default'] = 0;

    $models[] = array(
        'model'     => $title_model,
        'priority'  => 10
    );

    $models[] = array(
        'model'     => auxin_metabox_fields_general_background(),
        'priority'  => 30
    );

    $models[] = array(
        'model'     => auxin_metabox_fields_general_advanced(),
        'priority'  => 40
    );

    return $models;
}

add_filter( 'auxin_admin_metabox_models_post', 'auxin_push_metabox_models_post' );
