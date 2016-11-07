<?php
/**
 * Theme specific functions and configs
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

/*------------------------------------------------------------------------------------------------*/

include_once('include/functions.php');
include_once( 'options/aux-options.php' );
//include_once( 'include/shortcodes.php' );

if( is_admin() ){
    include_once( 'include/hooks-admin.php' );
    include_once( 'admin/index.php' );

} else {
    include_once( 'include/class-auxin-frontend-assets.php' );
    include_once( 'include/hooks-public.php' );

    if( file_exists( THEME_CUSTOM_DIR.'/custom.php' ) ){
        include_once( THEME_CUSTOM_DIR.'/custom.php' );
    }
}

