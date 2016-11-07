<?php


class Auxin_About {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * The sections (tabs) info
     *
     * @var      array()
     */
    protected $sections = array();


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
     * Retrieves the status and action link for a plugin
     * @param  array $item The plugin info
     * @return array       An array containing the status and action link for a plugin
     */
    function get_plugin_action( $item ) {

        $installed_plugins        = get_plugins();
        $item['sanitized_plugin'] = $item['name'];

        $item['version']          = isset( $item['version'] ) ? $item['version'] : '';
        $item['url']              = isset( $item['source'] ) && ! empty( $item['source'] ) ? $item['source'] : 'repo';

        // We need to display the 'Install' hover link.
        if ( ! isset( $installed_plugins[$item['file_path']] ) ) {
            $action_link = sprintf(

                '<a class="button button-primary aux-button" href="%1$s" title="' . __( 'Install Plugin', 'phlox' ) . ' %2$s">' . __( 'Install Plugin', 'phlox' ) . '</a>',

                add_query_arg(
                    array(
                        'page'          => TGM_Plugin_Activation::$instance->menu,
                        'plugin'        => $item['slug'],
                        'plugin_source' => $item['url'],
                        'tgmpa-install' => 'install-plugin',
                        'tgmpa-nonce'   => wp_create_nonce( 'tgmpa-install' )
                    ),
                    admin_url( 'themes.php' )
                ),

                $item['sanitized_plugin']
            );

            return array( 'status' => 'install', 'link' => $action_link );
        }

        /** plugin needs to activate */
        elseif ( is_plugin_inactive( $item['file_path'] ) ) {

            $action_link = sprintf(

                '<a href="%1$s" title="' . __( 'Activate', 'phlox' ) . ' %2$s">' . __( 'Activate Plugin', 'phlox' ) . '</a>',

                add_query_arg(
                    array(
                        'page'           => TGM_Plugin_Activation::$instance->menu,
                        'plugin'         => $item['slug'],
                        'plugin_source'  => $item['url'],
                        'tgmpa-activate' => 'activate-plugin',
                        'tgmpa-nonce'    => wp_create_nonce( 'tgmpa-activate' )
                    ),
                    admin_url( 'themes.php' )
                ),
                $item['sanitized_plugin']
            );

            return array( 'status' => 'active', 'link' => $action_link );
        }

        /** plugin needs to update */
        elseif ( version_compare( $installed_plugins[$item['file_path']]['Version'], $item['version'], '<' ) ) {
            return array( 'status' => 'update', 'link' => '' );
        }

        /** plugin needs to deactivate */
        elseif ( is_plugin_active( $item['file_path'] ) ) {
            return array( 'status' => 'deactive', 'link' => __('installed', 'phlox') );
        }

        return array( 'status' => '', 'link' => '' );
    }


    public function get_sections(){

        if( empty( $this->sections ) ){
            $this->sections = apply_filters( 'auxin_admin_welcome_sections', array() );
        }

        return $this->sections;
    }


    public function render(){

        $sections = array_keys( $this->get_sections() );

        $tab      = ! empty( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : '';
        $tab      = in_array( $tab, $sections ) ? $tab : 'features';

        ?>
        <div class="wrap about-wrap aux-about aux-<?php echo $tab; ?>">
            <?php $this->the_header( $tab ); ?>
            <?php $this->the_nav( $tab ); ?>
            <?php $this->the_content( $tab ); ?>
        </div>
        <?php
    }




    protected function the_header( $type ){

        $sections = $this->get_sections();

        $welcome_description = ! empty( $sections[ $type ]['description'] ) ? $sections[ $type ]['description'] : '';


        $welcome_page_title  = AUXIN_NO_BRAND ? __('Welcome','phlox') : sprintf( __( 'Welcome to %s', 'phlox' ), '<span class="theme-name">' . THEME_NAME_I18N  . '</span>');
        /**
        * Filter the "Welcome to theme name" text displayed in the welcome page.
        *
        * @param string $welcome_page_title The title that will be printed .
        */
        $welcome_page_title = apply_filters( 'auxin_welcome_page_title', $welcome_page_title, $type );

        ?>
        <h1 class="aux-welcome-title"><?php echo $welcome_page_title; ?></h1>
        <div class="about-text"><?php echo  $welcome_description; ?></div>

        <a href="http://averta.net/phlox/" target="_blank">
            <div class="wp-badge aux-theme-badge">
            <?php $my_theme = wp_get_theme(); echo __('VERSION: ', 'phlox') . $my_theme->get( 'Version' ); ?>
            </div>
        </a>
        <?php
    }


    protected function the_nav( $type ){

        $nav_tabs = $this->get_sections();

        echo '<h2 class="nav-tab-wrapper">';

        foreach( $nav_tabs as $tab_id => $tab_info ) {
            $feature_tab_class = $type === $tab_id ? 'nav-tab nav-tab-active' : 'nav-tab';

            if( empty( $tab_info['url'] ) ){
                $tab_info['url'] = admin_url( 'themes.php?page=auxin-welcome&tab='. $tab_id );
            }

            echo '<a href="' . esc_url( $tab_info['url'] ) . '" class="' . $feature_tab_class . '">';
                echo $tab_info['label'];
            echo '</a>';
        }

        echo '</h2>';
    }


    protected function the_content( $type ){

        $sections = $this->get_sections();

        if( ! empty( $sections[ $type ]['callback'] ) ){
            if( method_exists( $this, $sections[ $type ]['callback'] ) ){
                $this->$sections[ $type ]['callback']();
                return;
            }
            if( function_exists( $sections[ $type ]['callback'] ) ){
                call_user_func( $sections[ $type ]['callback'] );
                return;
            }
        }

        $auto_method_name = 'content_'.$type;
        if( method_exists( $this, $auto_method_name ) ){
            $this->$auto_method_name();
            return;
        }
    }



}
