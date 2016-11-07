<?php
/**
 * Add page Option meta box
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

/*======================================================================*/

function auxin_push_metabox_models_page( $models ){

    // Load general metabox models
    include_once( 'metabox-fields-general-slider-setting.php' );
    include_once( 'metabox-fields-general-bg-setting.php'     );
    include_once( 'metabox-fields-general-title-setting.php'  );
    // include_once( 'metabox-fields-general-custom-siderbar.php');
    // include_once( 'metabox-fields-general-custom-menu.php');
    include_once( 'metabox-fields-general-advanced.php'       );
    include_once( 'metabox-fields-general-layout.php'         );

    // Attach general common metabox models to hub
    $models[] = array(
        'model'     => auxin_metabox_fields_general_layout(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_title() ,
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_background(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_slider(),
        'priority'  => 10
    );
    $models[] = array(
        'model'     => auxin_metabox_fields_general_advanced(),
        'priority'  => 10
    );
    // $models[] = array(
    //     'model'     => auxin_metabox_fields_custom_sidebar(),
    //     'priority'  => 10
    // );
    // $models[] = array(
    //     'model'     => auxin_metabox_fields_custom_menu(),
    //     'priority'  => 10
    // );

    return $models;
}

// @TODO: It creates JS errors of Visual Composer and it should fix.
add_filter( 'auxin_admin_metabox_models_page', 'auxin_push_metabox_models_page' );
