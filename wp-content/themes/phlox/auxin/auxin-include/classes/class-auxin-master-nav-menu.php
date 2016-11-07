<?php
/**
 * Main class for adding configurations and hooks for extending WordPress navigation menu
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */
class Auxin_Master_Nav_Menu {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance  = null;

    /**
     * List of custom meta fields for menu items
     *
     * @var array
     */
    public $menu_item_fields = array();



    function __construct(){

        // Defining the custom fields for menu items
        $this->menu_item_fields = array(

            'megamenu' => array(
                'label'     => __( 'Submenu as Mega menu', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'megamenu',
                'default'   => '0',
                'max_depth' => 0
            ),
            'nolink' => array(
                'label'     => __( 'Disable Link', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'nolink',
                'default'   => '0'
            ),
            'hide_label' => array(
                'label'     => __( 'Hide Label', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'hide_label',
                'default'   => '0'
            ),
            'icon' => array(
                'label'     => __( 'Icon', 'phlox' ),
                'type'      => 'icon',
                'id'        => 'icon',
                'default'   => ''
            ),
            'icon_align' => array(
                'label'     => __( 'Icon Alignment', 'phlox' ),
                'type'      => 'select',
                'id'        => 'icon_align',
                'choices'   => array(
                    ''       => __( 'Auto', 'phlox' ),
                    'left'   => __( 'Left'    , 'phlox' ),
                    'right'  => __( 'Right'   , 'phlox' ),
                    'top'    => __( 'Top'     , 'phlox' ),
                    'bottom' => __( 'Bottom'  , 'phlox' )
                ),
                'default'   => ''
            ),
            'row_start' => array(
                'label'     => __( 'Start Row From Here', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'row_start',
                'default'   => '0',
                'min_depth' => 1,
                'max_depth' => 1
            ),
            'hide_title' => array(
                'label'     => __( 'Hide Title', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'hide_title',
                'default'   => '0',
                'min_depth' => 1,
                'max_depth' => 1
            ),
            'col_num' => array(
                'label'     => __( 'Number of Columns', 'phlox' ),
                'type'      => 'select',
                'id'        => 'col_num',
                'choices'   => array(
                    //'auto'  => __( 'Auto', 'phlox' ),
                    1       => 1,
                    2       => 2,
                    3       => 3,
                    4       => 4,
                    5       => 5,
                    6       => 6
                ),
                'default'   => 2,
                'max_depth' => 0
            ),
            'hide_desktop' => array(
                'label'     => __( 'Hide on Desktop', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'hide_desktop',
                'default'   => '0'
            ),
            'hide_tablet' => array(
                'label'     => __( 'Hide on Tablet', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'hide_tablet',
                'default'   => '0'
            ),
            'hide_mobile' => array(
                'label'     => __( 'Hide on Mobile', 'phlox' ),
                'type'      => 'switch',
                'id'        => 'hide_mobile',
                'default'   => '0'
            ),
            'custom_style' => array(
                'label'     => __( 'Custom Style', 'phlox' ),
                'type'      => 'textarea',
                'id'        => 'custom_style',
                'default'   => ''
            )

        );


        if( is_admin() ){ // Back-end modification hooks

            // Add extra fields to menu items in back-end menu editor
            add_filter( 'wp_setup_nav_menu_item' , array( $this, 'add_custom_nav_item_fields'     ) );

            // Save and update the value of custom menu fields
            add_action( 'wp_update_nav_menu_item', array( $this, 'update_backend_nav_menu_fields' ), 9, 3 );

            // Change the walker class for back-end  menu editor
            add_filter( 'wp_edit_nav_menu_walker', array( $this, 'change_nav_menu_backend_walker' ), 9, 1 );

            // Register stylesheet and javascript for edit menu page
            add_action( 'admin_menu'             , array( $this, 'enqueue_edit_menu_assests'      ) );

        } else { // Front-end modification hooks

            // Change the walker class and default args for front end navigation menu
            add_filter( 'wp_nav_menu_args'      , array( $this, 'change_nav_menu_frontend_walker' ), 9, 1 );
            // Add HTML comment before and after of menu section
            add_filter( 'wp_nav_menu'           , array( $this, 'add_html_comment_nav_menu_front' ), 9, 1 );
        }
    }

    /* Back End
    ==========================================================================*/

    /**
     * Adds all custom fields to menu item object in back-end
     *
     * @param object $menu_item The menu item object
     */
    public function add_custom_nav_item_fields( $menu_item ){

        // Loop through all custom fields and add them to menu item object
        foreach ( $this->menu_item_fields as $field_id => $field_info ) {
            $menu_item->{$field_id} = get_post_meta( $menu_item->ID, '_menu_item_' . $field_id , true );
        }

        return $menu_item;
    }

    /**
     * Saves and updates the value of custom menu item fields
     */
    public function update_backend_nav_menu_fields( $menu_id, $menu_item_db_id, $args ){

        foreach ( $this->menu_item_fields as $field_id => $field_info ){

            // considering exception for checkbox field
            if( 'switch' == $field_info['type'] ) {

                if( ! isset( $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] ) ){
                    $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] = '0';
                } else {
                    $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] = '1';
                }

            } elseif( ! isset( $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] ) ){
                $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] = '';
            }

            // save custom style in custom css file
            if( 'custom_style' == $field_id ){

                if( $custom_style = $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] ){
                    auxin_save_custom_css( ".menu-item-$menu_item_db_id { $custom_style }", 'menu-item-' . $menu_item_db_id );
                } else {
                    auxin_remove_custom_css( 'menu-item-' . $menu_item_db_id );
                }

            }

            update_post_meta( $menu_item_db_id, '_menu_item_'. $field_id , $_POST['menu-item-'. $field_id ][ $menu_item_db_id ] );
        }


    }

    /**
     * Modifies the walker class of back-end menu editor
     */
    public function change_nav_menu_backend_walker( $walker ){
        return 'Auxin_Walker_Nav_Menu_Back';
    }

    /**
     * Loads specific asset files only on edit menu page
     */
    public function enqueue_edit_menu_assests(){
        global $pagenow;

        if( 'nav-menus.php' == $pagenow ){
            wp_enqueue_style ( 'auxin-edit-menus-css' , ADMIN_CSS_URL . 'other/edit-menus.css' , NULL, '1.1' );
            wp_enqueue_script( 'auxin-edit-menus-js'  , ADMIN_JS_URL  . 'solo/edit-menus.js'   , array('jquery'), '1.1', true );
        }
    }

    /* Front End
    ==========================================================================*/

    /**
     * Modifies the walker class and default args for front end menu
     */
    public function change_nav_menu_frontend_walker( $args ){
        $args['container']  = 'nav';
        $args['before']     = "\n";
        $args['after']      = "\n";

        // only init master menu in following theme locations
        $master_menu_theme_locations = array( 'header-primary', 'header-secondary' );

        if( in_array( $args['theme_location'], $master_menu_theme_locations ) ){
            $args['menu_class'] = 'aux-master-menu aux-no-js';
            $args['walker']     = new Auxin_Walker_Nav_Menu_Front();
        }


        $menu_direction       = 'horizontal';           // the menu type on desktop size (toggle, accordion, horizontal, vertical, cover)

        if( 'header-secondary' == $args['theme_location'] ){
            // insert menu direction classname
            $args['menu_class'] .= ' aux-' . $menu_direction;
        }

        // the mobile menu options for header-primary menu
        // ---------------------------------------------------------------------
        $data_switch_attr = '';
        if( 'header-primary' == $args['theme_location'] ){

            // Define the skin of submenu for header menu
            $args['menu_class'] .= ' aux-skin-' . auxin_get_option( 'site_header_navigation_sub_skin', 'default' );

            // Specifies the submenu opening effect
            $submenu_effect = auxin_get_option( 'site_header_navigation_sub_effect', '' );
            if ( !empty( $submenu_effect ) ) {
                $args['menu_class'] .= ' aux-' . $submenu_effect . '-nav';
            }

            // insert menu direction classname
            $args['menu_class'] .= ' aux-' . $menu_direction;


            // Add splitter and indicator if they are enabled
            if( auxin_get_option( 'site_header_navigation_with_indicator' ) ){
                $args['menu_class'] .= ' aux-with-indicator';
            }
            if( auxin_get_option( 'site_header_navigation_with_splitter' ) ){
                $args['menu_class'] .= ' aux-with-splitter';
            }

            // under what width we have to move the menu to switch parent
            if( ! isset( $args['mobile_under'] ) ){
                $args['mobile_under'] = 767;
            }

            // the menu position on mobile and tablet size (toggle-bar, offcanvas, overlay, none)
            $mobile_menu_position = isset( $args['mobile_menu_position'] ) ? $args['mobile_menu_position'] : auxin_get_option( 'site_header_mobile_menu_position' );

            // the menu type on mobile and tablet size (toggle, accordion, horizontal, vertical, cover)
            $mobile_menu_type     = isset( $args['mobile_menu_type'] ) ? $args['mobile_menu_type'] : auxin_get_option( 'site_header_mobile_menu_type' );

            $mobile_menu_parent_selectors = array(
                'toggle-bar' => '.aux-header .aux-toggle-menu-bar',
                'offcanvas'  => '.aux-offcanvas-menu .offcanvas-content',
                'overlay'    => '.aux-fs-popup .aux-fs-menu',
                'none'       => ''
            );

            // where to move menu on mobile and tablet size
            $mobile_menu_target   = isset( $mobile_menu_parent_selectors[ $mobile_menu_position ] ) ? $mobile_menu_parent_selectors[ $mobile_menu_position ] : '';

            $data_switch_attr = ' data-switch-type="'. $mobile_menu_type .'" data-switch-parent="'. $mobile_menu_target .'" data-switch-width="'. $args['mobile_under'] .'" ';
        }
        // ---------------------------------------------------------------------

        $args['items_wrap'] = "\n\n\t" . '<ul id="%1$s" class="%2$s" data-type="' . $menu_direction . '" '. $data_switch_attr ." >\n" .'%3$s'. "\t</ul>\n\n";

        return $args;
    }

    /**
     * Adds HTML comment before and after of menu section
     * @param string $output
     */
    public function add_html_comment_nav_menu_front( $output ){
        return "<!-- start master menu -->\n" . $output. "\n<!-- end master menu -->\n";
    }

    /* Get methods
    ==========================================================================*/

    /**
     * Retrieves the list of menu items
     *
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @return               Returns the list of menu items
     */
    public function get_menu_items( $args ){

        $menu_items = array();

        // Get the nav menu based on the requested menu
        $menu = wp_get_nav_menu_object( $args->menu );

        // Get the nav menu based on the theme_location
        if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) )
            $menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );

        // get the first menu that has items if we still can't find a menu
        if ( ! $menu && !$args->theme_location ) {
            $menus = wp_get_nav_menus();
            foreach ( $menus as $menu_maybe ) {
                if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) ) ) {
                    $menu = $menu_maybe;
                    break;
                }
            }
        }

        if ( empty( $args->menu ) ) {
            $args->menu = $menu;
        }

        // If the menu exists, get its items.
        if ( $menu && ! is_wp_error($menu) && !isset($menu_items) )
            $menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

        return $menu_items;
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

}
