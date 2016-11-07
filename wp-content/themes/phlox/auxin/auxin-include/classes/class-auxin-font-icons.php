<?php
/**
 * Class for parsing and managing icon fonts
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

class Auxin_Font_Icons {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    protected $fonts_list;

    // protected $json_paths = array();



    function __construct(){

    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
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

        return null;
    }

    /**
     * Magic method to set value for properties
     *
     * @param   string    The property name
     * @param   string   The property value
     * @return  bool    True, if the value was set to property successfully, False on failure.
     */
    public function __set( $name, $value ){
        if( isset( $this->$name ) ){
            $this->$name = $value;
            return true;
        }
        return false;
    }

    function get_icons_list( $font_name ){
        return get_option( THEME_ID . '_font_icons_list_' . $font_name, false );
    }

    function update(){
        $font_name = 'fontastic';
        $font_path = THEME_URL . 'css/fonts/fontastic/auxin-front.json';
        $this->fetch_and_store_font_icons_list( $font_name, $font_path );
    }

    function fetch_and_store_font_icons_list( $font_name, $font_path ){
        $this->fonts_list[ $font_name ] = $this->get_font_json_contents( $font_path );
        update_option( THEME_ID . '_font_icons_list_' . $font_name, $this->fonts_list[ $font_name ] );
    }

    function get_font_json_contents( $font_path ){
        $parsed_data = $this->fetch_parse_json_contents( $font_path );
        return $parsed_data;
    }

    function fetch_parse_json_contents( $file_path = '' ){

        $response = wp_remote_get( $file_path );

        if( $response && $json_content = wp_remote_retrieve_body( $response ) ){
            $parsed_icons_list = json_decode( $json_content );

            return $parsed_icons_list;
        }

        return false;
    }


    /**
     * Retrieves the icons list for an specific font name
     *
     * @param  string $font_name  The font name that we are willing to get its icons list
     * @return Array              A list containing the info of all icons
     */
    function get_font_icons_list( $font_name = '' ){
        if( isset( $this->fonts_list[ $font_name ] ) ){
            return $this->fonts_list[ $font_name ];
        }
        return false;
    }

}
