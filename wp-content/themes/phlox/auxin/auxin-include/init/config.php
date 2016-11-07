<?php
/**
 * Auxin configurations
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



class AuxinConfig {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    public $config = array();


    function __construct(){
        $this->init_config();
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

        if( isset( $this->config[ $name ] ) ){
            return $this->config[ $name ];
        }

        return null;
    }


    private function init_config(){

        /**
         * Image Sizes
         */
        $this->config['image_sizes'] = array(
            'i6'    => array(150, null, null),
            'i6_1'  => array(150,   80, true),
            'i6_2'  => array(150,  282, true),

            'i5'    => array(190, null, null),
            'i5_1'  => array(190,  115, true),
            'i5_2'  => array(190,  232, true),

            'i4'    => array(238, null, null),
            'i4_1'  => array(238,  144, true),
            'i4_2'  => array(238,  290, true),

            'i3'    => array(318, null, null),
            'i3_1'  => array(318,  192, true),
            'i3_2'  => array(318,  386, true),

            'i2'    => array(479, null, null),
            'i2_1'  => array(479,  290, true),
            'i2_2'  => array(479,  582, true),

            'i1'    => array(960, null, null),
            'i1_1'  => array(960,  550, null),

            'full'  => array(1200, 800, true),
            'side'  => array(1050, 700, true),

            '635'   => array(635, null, null),
            '635_1' => array(635,  635, true)
        );

        $this->config['image_sizes'] = apply_filters( 'auxin_image_sizes', $this->config['image_sizes'] );

        /**
         * Theme support features
         */
        $this->config['theme_support'] = apply_filters( 'auxin_theme_support',
            array(
                'post-thumbnails',
                'post-formats',
                'automatic-feed-links',
                'woocommerce',
                'html5',
                'gallery',
                'customize-selective-refresh-widgets',
                'title-tag'
            )
        );

        /**
         * Theme custom post types
         */
        $this->config['active_post_types'] = apply_filters( 'auxin_active_post_types',
            array(
                'portfolio'   => false,
                'aux-news'    => false,
                'service'     => false,
                'faq'         => false,
                'staff'       => false,
                'testimonial' => false,
                'events'      => false,
                'feedback'    => false,
                'client'      => false
            )
        );

        $this->config['theme_width'] = apply_filters( 'auxin_theme_width_list',
            array(
                'nd'    => 1000,
                'hd'    => 1200,
                'xhd'   => 1400,
                's-fhd' => 1600,
                'fhd'   => 1900
            )
        );

        $this->config['theme_gutter'] = 70;
    }

}


/*---------------------------------------------*/
/*  Functions
/*---------------------------------------------*/

/**
 * Quick access to get image sizes
 *
 * @param  string $key Image size ID
 * @return array       Returns an array containing image sizes
 */
function auxin_get_image_size( $key ){
    $image_sizes = AuxinConfig::get_instance()->image_sizes;
    return isset( $image_sizes[ $key ] ) ? $image_sizes[ $key ]: array();
}

function auxin_get_image_sizes( $key ){
    return AuxinConfig::get_instance()->image_sizes;
}


/**
 * Returns all possible/allowed post types in theme framework
 *
 * @param  $only_allowed  whether to return only allowed post types or not
 * @return array  An array containing list of all post types
 */
function auxin_get_possible_post_types( $only_allowed = false ){
    // list of all custom post types
    $all_custom_post_types = AuxinConfig::get_instance()->active_post_types;

    return $only_allowed ? array_filter( $all_custom_post_types ) : $all_custom_post_types;
}

/**
 *  Is post type allowed or not
 *
 * @param  string $post_type  Post type name
 * @return boolean
 */
function auxin_is_post_type_allowed( $post_type ){
    $auxin_active_post_types = auxin_get_possible_post_types();
    return isset( $auxin_active_post_types[ $post_type ] ) && $auxin_active_post_types[ $post_type ];
}


/**
 * Returns all active post types in theme framework (the registered ones)
 * You are expected to call this function when all post types are registered
 *
 * @param  $only_actives  whether to return only active post types or not
 * @return array  An array containing list of all post types
 */
function auxin_registered_post_types( $only_actives = false ){
    // list of all custom poat types
    $all_custom_post_types = auxin_get_possible_post_types( $only_actives );

    return array_filter( array_keys( $all_custom_post_types), 'post_type_exists' );
}


/**
 * Retrieves list of theme support features
 *
 * @return array    An array containing list of all theme support features
 */
function auxin_theme_support(){
    return AuxinConfig::get_instance()->theme_support;
}


/**
 * Retrieves list of possible theme width
 *
 * @return array    An array containing list of all possible theme width
 */
function auxin_theme_width_list(){
    return AuxinConfig::get_instance()->theme_width;
}
