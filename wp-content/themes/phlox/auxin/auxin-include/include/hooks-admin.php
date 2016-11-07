<?php
/**
 * Admin hooks
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/
function auxin_update_font_icons_list(){
    // parse and cache the list of fonts
    $fonts = Auxin()->font_icons;
    $fonts->update();
}
add_action( 'after_setup_theme', 'auxin_update_font_icons_list' );


// make the customizer avaialble while requesting via ajax
if ( defined('DOING_AJAX') && DOING_AJAX ){
    Auxin_Customizer::get_instance();
}


global $pagenow;
// redirect to welcome page after theme activation
if ( isset( $_GET['activated'] ) && $pagenow == "themes.php"){
    wp_redirect( admin_url('themes.php?page=auxin-welcome&about=1') );
}


/*-----------------------------------------------------------------------------------*/
/*  Include the Welcome page
/*-----------------------------------------------------------------------------------*/

function auxin_register_theme_menu() {

    $root_menu_name = AUXIN_NO_BRAND ? __( 'Theme Setting', 'phlox') : THEME_NAME_I18N;
    $root_menu_name = apply_filters( 'auxin_theme_setting_menu_name', $root_menu_name );

    $welcome_root_slug = 'auxin-welcome';

    //

    /*  Register welcome sumenu
    /*------------------------------------------------------------------------*/
    add_theme_page(
        __('Welcome', 'phlox'),                    // [Title]    The title to be displayed on the corresponding page for this menu
        $root_menu_name,                                // [Text]     The text to be displayed for this actual menu item
        apply_filters( 'auxin_theme_welcome_capability', 'manage_options' ),
                                                        // [User]     Which type of users can see this menu
        $welcome_root_slug,                             // [ID/slug]  The unique ID - that is, the slug - for this menu item
        array( Auxin_About::get_instance(), 'render')   // [Callback] The name of the function to call when rendering the menu for this page
    );
}

add_action( 'admin_menu', 'auxin_register_theme_menu' );

/*-----------------------------------------------------------------------------------*/
/*  Assign menus on start or after demo import
/*-----------------------------------------------------------------------------------*/

/**
 * Automatically assigns the appropriate menus to menu locations
 * Known Locations:
 *  - header-primary  : There should be a menu with the word "Primary" Or "Mega" in its name
 *  - header-secondary: There should be a menu with the word "Secondary" in its name
 *  - footer          : There should be a menu with the word "Footer" in its name
 *
 * @return bool         True if at least one menu was assigned, false otherwise
 */
function auxin_assign_default_menus(){

    $assinged = false;
    $locations = get_theme_mod('nav_menu_locations');
    $nav_menus = wp_get_nav_menus();

    foreach ( $nav_menus as $nav_menu ) {
        $menu_name = strtolower( $nav_menu->name );

        if( empty( $locations['header-secondary'] ) && preg_match( '(secondary)', $menu_name ) ){
            $locations['header-secondary'] = $nav_menu->term_id;
            $assinged = true;
        } else if( empty( $locations['header-primary'] ) && preg_match( '(primary|mega|header)', $menu_name ) ){
            $locations['header-primary'] = $nav_menu->term_id;
            $assinged = true;
        } else if( empty( $locations['footer'] ) && preg_match( '(footer)', $menu_name ) ){
            $locations['footer'] = $nav_menu->term_id;
            $assinged = true;
        }
    }

    set_theme_mod( 'nav_menu_locations', $locations );
    return $assinged;
}

add_action( 'after_switch_theme', 'auxin_assign_default_menus' ); // triggers when theme will be actived, WP 3.3
add_action( 'import_end', 'auxin_assign_default_menus' ); // triggers when the theme data was imported
