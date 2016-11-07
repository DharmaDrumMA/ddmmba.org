<?php
/**
 * Functions for users wanting to upgrade to premium
 *
 * @package TopShop
 */

/**
 * Display the upgrade to Premium page & load styles.
 */
function topshop_premium_admin_menu() {
    global $topshop_upgrade_page;
    $topshop_upgrade_page = add_theme_page( __( 'About TopShop', 'topshop' ), '<span class="premium-link">' . __( 'About TopShop', 'topshop' ) . '</span>', 'edit_theme_options', 'theme_info', 'topshop_render_upgrade_page' );
}
add_action( 'admin_menu', 'topshop_premium_admin_menu' );

/**
 * Enqueue admin stylesheet only on upgrade page.
 */
function topshop_load_upgrade_page_scripts( $hook ) {
    global $topshop_upgrade_page;
    if ( $hook != $topshop_upgrade_page )
        return;
    
    wp_enqueue_style( 'topshop-upgrade-css', get_template_directory_uri() . '/upgrade/css/upgrade-admin.css' );
    wp_enqueue_script( 'caroufredsel', get_template_directory_uri() . '/js/jquery.carouFredSel-6.2.1-packed.js', array( 'jquery' ), TOPSHOP_THEME_VERSION, true );
    wp_enqueue_script( 'topshop-upgrade-js', get_template_directory_uri() . '/upgrade/js/upgrade-custom.js', array( 'jquery' ), TOPSHOP_THEME_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'topshop_load_upgrade_page_scripts' );

/**
 * Render the premium upgrade/order page
 */
function topshop_render_upgrade_page() {
	get_template_part( 'upgrade/tpl/upgrade-page' );
}
