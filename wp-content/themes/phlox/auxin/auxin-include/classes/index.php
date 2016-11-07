<?php
/**
 * Include classes
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

if( is_admin() ){

    // Assigning capabilities and option on theme install
    new Auxin_Install();
    new Auxin_Theme_Screen_Help();

    // custom permalink setting fields for custom post types
    $axi_permalink = new Auxin_Permalink();
    $axi_permalink->setup();

    // Register required assets (scripts & styles)
    new Auxin_Admin_Assets();

    // Load admin assets on demand
    // new Auxin_Admin_Dashboard();

    // Parse and load fonts
    function AXI_FONT(){ return Auxin_Fonts::get_instance(); }


    /*  Include update notifier
    /*------------------------------------------*/
    if( AUXIN_UPDATE_NOTIFIER && class_exists('Auxin_Theme_Check_Update') ){
        // Init theme auto-update class
        $theme_update_check = new Auxin_Theme_Check_Update (
            THEME_VERSION,                          // theme version
            'http://api.averta.net/envato/items/',  // API URL
            THEME_ID,                               // template slug name
            'phlox'                                 // item request name
        );
        $theme_update_check->theme_id = '2754927';
    }

    $auxin_importer = new Auxin_Import();
}

// Init Master Menu navigation
Auxin_Master_Nav_Menu::get_instance();

// If DOING AJAX
if ( defined('DOING_AJAX') && DOING_AJAX ){
    new Auxin_Front_Ajax();
}
