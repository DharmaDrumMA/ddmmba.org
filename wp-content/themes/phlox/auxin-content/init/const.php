<?php
/**
 * Defining Constants.
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

/*-----------------------------------------------------------------------------------*/
/*  Define Global Vars
/*-----------------------------------------------------------------------------------*/

// theme name
$theme_data = wp_get_theme();
if( ! defined('THEME_NAME')      ) define('THEME_NAME'      , $theme_data->Name    );
if( ! defined('THEME_NAME_I18N') ) define('THEME_NAME_I18N' , __('Phlox', 'phlox') );


// this id is used as prefix in database option field names - specific for each theme
if(! defined('THEME_ID')      ) define('THEME_ID'      ,  "phlox" );



// dummy gettext call to translate theme name
__("Phlox", 'phlox');
__("PHLOX", 'phlox');

/*-----------------------------------------------------------------------------------*/
