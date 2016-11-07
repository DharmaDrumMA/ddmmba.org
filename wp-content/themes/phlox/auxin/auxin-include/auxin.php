<?php
/**
 * Auxin is a powerful and exclusive framework which powers averta themes
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


if( ! class_exists( 'Auxin' ) ){

    class Auxin {

        /**
         * Instance of this class.
         *
         * @var      object
         */
        public static $instance = null;

        /**
         * Auxin version
         *
         * @var      string
         */
        public $version = '2.2.3';


        public $config = null;


        /**
         * Return an instance of this class.
         *
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {

            if ( null == self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }


        public function __construct(){
            $this->define_constants();
            $this->include_files();
        }


        private function define_constants(){

            // core version
            $this->define( 'AUXIN_VERSION', $this->version );

            // domain name for tranlation file
            $this->define( 'THEME'.'_DOMAIN' , 'auxin' );

            // this id is used as prefix in database option field names - specific for each theme
            $this->define( 'AUXIN' , 'auxin_' );



            // Server path for current theme directory
            $this->define( 'THEME_DIR' , get_template_directory() . '/' );

            // Http url of current theme directory
            $this->define( 'THEME_URL' , get_template_directory_uri() . '/' );



            // Load config file if it was available in root of theme parent/child folder
            locate_template( 'aux-config.php', true );


            // Server path for theme inc directory
            $this->define( 'THEME_INC_DIR' , THEME_DIR. 'inc/' );

            // Http url of theme inc directory
            $this->define( 'THEME_INC_URL' , THEME_URL. 'inc/' );



            // Server path for admin folder
            $this->define( 'AUX_DIR' , THEME_DIR. 'auxin/' );

            // relative path to auxin folder from theme root dir
            $this->define( 'AUX_DIR_REL' , 'auxin/' );

            // Http url of admin folder
            $this->define( 'AUX_URL' , THEME_URL. 'auxin/' );



            // Server path for admin > include folder
            $this->define( 'AUX_INC' , AUX_DIR. 'auxin-include/' );

            // relative path to auxin-include folder from theme root dir
            $this->define( 'AUX_INC_REL' , AUX_DIR_REL . 'auxin-include/' );

            // Http url of admin > include folder
            $this->define( 'AUX_INC_URL' , AUX_URL. 'auxin-include/' );



            // Server path for admin > include folder
            $this->define( 'AUX_CON' , THEME_DIR. 'auxin-content/' );

            // relative path to auxin-content folder from theme root dir
            $this->define( 'AUX_CON_REL' , AUX_DIR_REL . 'auxin-content/' );

            // Http url of admin > include folder
            $this->define( 'AUX_CON_URL' , THEME_URL. 'auxin-content/' );



            // Server path for admin > css folder
            $this->define( 'ADMIN_CSS' , AUX_DIR. 'css/' );

            // Http url of admin > css folder
            $this->define( 'ADMIN_CSS_URL' , AUX_URL. 'css/' );



            // Server path for admin > js folder
            $this->define( 'ADMIN_JS' , AUX_DIR. 'js/' );

            // Http url of admin > js folder
            $this->define( 'ADMIN_JS_URL' , AUX_URL. 'js/' );



            // theme name
            $theme_data = wp_get_theme();

            $this->define( 'THEME_NAME'      , $theme_data->Name );
            $this->define( 'THEME_NAME_I18N' , $theme_data->Name );

            $this->define( 'THEME_VERSION'   , $theme_data->Version );

            if( ! $_theme_id = get_option( "stylesheet" ) ){
                if( ! $_theme_id = get_option( "current_theme" ) ){
                    $_theme_id = $theme_data->Name;
                }
            }

            $this->define( 'THEME_ID' , strtolower( $_theme_id ) );

            // To display or hide support information in help panel
            $this->define( 'AUXIN_SUPPORT'  , true  );
            $this->define( 'AUXIN_NO_BRAND' , false );

            // set this to false to disable update notifier
            $this->define( 'AUXIN_UPDATE_NOTIFIER' , true );

            // a custom directory in uploads directory for storing custom files. Default uploads/{THEME_ID}
            $uploads = wp_get_upload_dir();
            $this->define( 'THEME_CUSTOM_DIR' , $uploads['basedir'] . '/' . THEME_ID );
        }


        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }


        private function include_files(){

            /*----------------------------------------------*/
            /*  Inculde essential functions
            /*----------------------------------------------*/
            require_once( 'init/global.php' );

            /*----------------------------------------------*/
            /*  Setup general configs
            /*----------------------------------------------*/
            require_once( 'init/config.php' );

            /*----------------------------------------------*/
            /*  Include general functions
            /*----------------------------------------------*/
            require_once( AUX_INC . 'include/functions.php' );

            if( is_admin() )
                include( AUX_INC . 'include/functions-admin.php' );

            do_action( 'auxin_functions_ready' );

            /*----------------------------------------------*/
            /*  Theme specific functions and configs
            /*----------------------------------------------*/
            include( AUX_CON . 'functions.php' );

            /*----------------------------------------------*/
            /*  Include all template functions
            /*----------------------------------------------*/
            require_once( AUX_INC.'include/templates/index.php' );

            /*----------------------------------------------*/
            /*  Include general settings
            /*----------------------------------------------*/
            require_once( AUX_INC . 'include/hooks-general.php' );

            if( is_admin() )
                require_once( AUX_INC . 'include/hooks-admin.php' );

            /*----------------------------------------------*/
            /*  Inculde classes
            /*----------------------------------------------*/
            require_once( 'classes/index.php' );

            do_action( 'auxin_classes_loaded' );

            /*----------------------------------------------*/
            /*  Add Modules
            /*----------------------------------------------*/
            include( AUX_INC . 'modules/index.php' );

            do_action( 'auxin_ready' );

            /*----------------------------------------------*/
            /*  Widgets and shortcodes
            /*----------------------------------------------*/
            include( AUX_INC . 'sidebars/index.php' );

            /*----------------------------------------------*/
            /*  Compatibility for third party plugins
            /*----------------------------------------------*/
            include( AUX_INC . 'compatibility/index.php' );

            /*----------------------------------------------*/
            /*  Init Option Panel
            /*----------------------------------------------*/
            if( is_admin() )
                include_once( AUX_INC . 'options/index.php' );

            /*----------------------------------------------*/
            do_action( 'auxin_loaded' );


            if( is_admin() )
                do_action( 'auxin_admin_loaded' );

        }


        /**
         * Magic method to get the value of accessible properties
         *
         * @param   string   The property name
         * @return  string  The value of property
         */
        public function __get( $name ){

            if( property_exists( $this, $name ) ){
                return $this->$name;
            }

            if( 'font_icons' == $name ){
                return Auxin_Font_Icons::get_instance();
            }

            return null;
        }

    }


    /**
     * Returns the instance of Auxin
     *
     * @return Auxin
     */
    function Auxin() {
        return Auxin::get_instance();
    }
    Auxin();

}
