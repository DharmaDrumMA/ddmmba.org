<?php
/**
 * Class to add content to screen help
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



class Auxin_Theme_Screen_Help {


  function __construct() {

        // Adds support tab under help screen
        add_action( 'contextual_help', array( $this, 'display_support_info' ) , 10, 3 );

        add_action( 'contextual_help', array( $this, 'display_technical_info' ) , 10, 3 );

        // if debaug mode is enabled display screen information in help tabs
        if( ! ( defined( 'WP_DEBUG' ) && ! WP_DEBUG ) ){
            add_action( 'contextual_help', array( $this, 'display_screen_info' ) , 10, 3 );
        }

        // if display support info is not disabled, show them
        if ( AUXIN_SUPPORT  && ! AUXIN_NO_BRAND ){
            // add_action( 'contextual_help', array( $this, 'display_support_info' ), 10, 3 );
        }
  }


    /**
     * Display support info
     *
     */
    public function display_support_info( $contextual_help, $screen_id, $screen ) {

        if ( ! method_exists( $screen, 'add_help_tab' ) ) return $contextual_help;


        // List screen properties
        $help_content  = '<p> <strong>Below sources are available to help you in using Phlox.</strong></p>';
        $help_content .= '<ul>';
        $help_content .= '<li>A complete <a target="_blank" href="http://docs.averta.net/display/PD/Phlox+Documentation">Documentation</a>, which updates regularly.</li>';
        $help_content .= '<li>An easy to use <a target="_blank" href="http://support.averta.net/envato/faq/phlox-wordpress-theme/">FAQ</a>, which grows by the time.</li>';
        $help_content .= '<li>A dedicated <a target="_blank" href="http://support.averta.net/envato/support/item/phlox-wordpress-theme/">Support forum</a>, with expert staff waiting fro you.</li>';
        $help_content .= '<li>Series of <a target="_blank" href="http://support.averta.net/envato/Videos/phlox-video-tutorials/">video tutorials</a>, which will be available soon.</li>';
        $help_content .= '<li>You want more?<a target="_blank" href="http://averta.net/phlox/wordpress-theme/?utm_source=theme-dashboard&utm_medium=help-tab&utm_content=help-link&utm_campaign=dashboard#subscribe"> sing up our Newsletter</a> to be the first one to get new features of Phlox.</li>';
        $help_content .= '<li>Leave us a <a href="' . admin_url( 'admin.php?page=auxin-welcome&tab=feedback') . '">Feedback</a>, we always love hearing from our clients.</li>';

        $help_content .= '</ul>';

        // Add help panel
        $screen->add_help_tab(
            array('id' => 'auxin-support-info',
                'title'=> 'Phlox Support',
                'content' => $help_content,
            )
        );

        return $contextual_help;
    }


    /**
     * Display Current admin screen information
     *
     */
    public function display_screen_info( $contextual_help, $screen_id, $screen ) {

        if ( ! method_exists( $screen, 'add_help_tab' ) )
            return $contextual_help;

        global $hook_suffix;

        // List screen properties
        $variables = '<ul style="width:50%;float:left;"> <strong>Screen variables </strong>'
            . sprintf( '<li> Screen id : %s</li>' , $screen_id )
            . sprintf( '<li> Screen base : %s</li>', $screen->base )
            . sprintf( '<li> Parent base : %s</li>', $screen->parent_base )
            . sprintf( '<li> Parent file : %s</li>', $screen->parent_file )
            . sprintf( '<li> Hook suffix : %s</li>', $hook_suffix )
            . '</ul>';

        // Append global $hook_suffix to the hook stems
        $hooks = array(
            "load-$hook_suffix",
            "admin_print_styles-$hook_suffix",
            "admin_print_scripts-$hook_suffix",
            "admin_head-$hook_suffix",
            "admin_footer-$hook_suffix"
        );

        // If add_meta_boxes or add_meta_boxes_{screen_id} is used, list these too
        if ( did_action( 'add_meta_boxes_' . $screen_id ) )
            $hooks[] = 'add_meta_boxes_' . $screen_id;

        if ( did_action( 'add_meta_boxes' ) ) $hooks[] = 'add_meta_boxes';

        $hooks = '<ul style="width:50%;float:left;"> <strong>Hooks </strong> <li>' . implode( '</li><li>', $hooks ) . '</li></ul>';

        // Combine $variables list with $hooks list.
        $help_content = $variables . $hooks;

        // Add help panel
        $screen->add_help_tab(
            array('id' => 'auxin-screen-help',
                'title'=> 'Screen Information',
                'content' => $help_content,
            )
        );

        return $contextual_help;
    }


    /**
     * Display technical info about web server configs
     *
     */
    public function display_technical_info( $contextual_help, $screen_id, $screen ) {

        if ( ! method_exists( $screen, 'add_help_tab' ) ) return $contextual_help;


        // List screen properties
        $help_content  = '<ul style="width:50%;float:left;"> <strong>Web server</strong>';
        $help_content .= '<li>php version : ' . phpversion(). '</li>';
        $help_content .= $this->get_ini_val('max_execution_time'     , 'max_execution_time');
        $help_content .= $this->get_ini_val('max_file_uploads'       , 'max_file_uploads');
        $help_content .= $this->get_ini_val('max_input_nesting_level', 'max_input_nesting_level');
        $help_content .= $this->get_ini_val('max_input_time'         , 'max_input_time');
        $help_content .= $this->get_ini_val('max_input_vars'         , 'max_input_vars');
        $help_content .= $this->get_ini_val('memory_limit'           , 'memory_limit');
        $help_content .= $this->get_ini_val('post_max_size'          , 'post_max_size');
        $help_content .= $this->get_ini_val('upload_max_filesize'    , 'upload_max_filesize');
        $help_content .= $this->get_ini_val('output_buffering'       , 'output_buffering');
        $help_content .= $this->get_ini_val('short_open_tag'         , 'short_open_tag');

        if( function_exists('get_filesystem_method') )
            $help_content .= sprintf( '<li> FileSystem : %s</li>', get_filesystem_method(array(), THEME_DIR) );

        $help_content .= '</ul>';


        $help_content .= '<ul style="width:50%;float:left;"> <strong>Web server</strong>';

        if(function_exists("get_loaded_extensions"))
        $help_content .= sprintf( '<li> Extentions : %s</li>', implode(' ,', get_loaded_extensions()));

        if(extension_loaded('suhosin'))
        $help_content .= '<li> Suhosin : Available</li>';

        if(!function_exists('mb_strwidth'))
        $help_content .= '<li> mb_* package : Unavailable. You need to enable it.</li>';

        $help_content .= $this->get_ini_val('disable_functions'      , 'disable_functions');
        $help_content .= '</ul>';


        // Add help panel
        $screen->add_help_tab(
            array('id' => 'auxin-technical-info',
                'title'=> 'Technical Information',
                'content' => $help_content,
            )
        );

        return $contextual_help;
    }


    private function get_ini_val( $label , $var ){
        $value = ini_get($var);
        return $value === false?"":sprintf( '<li> %s : %s</li>', $label, $value );
    }

}
