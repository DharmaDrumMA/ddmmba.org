<?php
/**
 * Pointers (Tooltips) to introduce new theme features or display notifications in admin area
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

/*-----------------------------------------------------------------------------------*/
/*  Install theme required plugins
/*-----------------------------------------------------------------------------------*/


add_action( 'tgmpa_register', 'auxin_theme_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function auxin_theme_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'      => __('Auxin Elements', 'phlox'),
            'slug'      => 'auxin-elements',
            'required'  => false
        ),

        array(
            'name'      => __('Page Builder', 'phlox'),
            'slug'      => 'siteorigin-panels',
            'required'  => false
        ),

        array(
            'name'      => __('Page Builder Widgets Bundle', 'phlox'),
            'slug'      => 'so-widgets-bundle',
            'required'  => false
        ),

        array(
            'name'      => __('Instagram Feed', 'phlox'),
            'slug'      => 'instagram-feed',
            'required'  => false
        ),

        array(
            'name'      => __('WordPress SEO', 'phlox'),
            'slug'      => 'wordpress-seo',
            'required'  => false
        ),

        array(
            'name'      => __('Recent Tweets Widget', 'phlox'),
            'slug'      => 'recent-tweets-widget',
            'required'  => false
        ),

        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false
        ),

        array(
            'name'      => 'WordPress Importer',
            'slug'      => 'wordpress-importer',
            'required'  => false
        ),

        array(
            'name'      => 'Related Posts for WordPress',
            'slug'      => 'related-posts-for-wp',
            'required'  => false
        ),

        array(
            'name'      => 'WP ULike',
            'slug'      => 'wp-ulike',
            'required'  => false
        ),

        array(
            'name'      => 'Autoptimize',
            'slug'      => 'autoptimize',
            'required'  => false
        ),

        array(
            'name'      => 'Image Optimization',
            'slug'      => 'wp-smushit',
            'required'  => false
        )

    );

    // Add master slider as requirement if none of masterslider versions is installed
    if( ! ( defined( 'MSWP_SLUG' ) && 'masterslider' == MSWP_SLUG ) ){
        $master = array(
            array(
                'name'      => __('MasterSlider by averta', 'phlox'),
                'slug'      => 'master-slider',
                'required'  => false
            )
        );
        $plugins = array_merge( $master, $plugins );
    }

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'phlox',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        /*
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'phlox' ),
            'menu_title'                      => __( 'Install Plugins', 'phlox' ),
            /* translators: %s: plugin name. * /
            'installing'                      => __( 'Installing Plugin: %s', 'phlox' ),
            /* translators: %s: plugin name. * /
            'updating'                        => __( 'Updating Plugin: %s', 'phlox' ),
            'oops'                            => __( 'Something went wrong with the plugin API.', 'phlox' ),
            'notice_can_install_required'     => _n_noop(
                /* translators: 1: plugin name(s). * /
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'phlox'
            ),
            'notice_can_install_recommended'  => _n_noop(
                /* translators: 1: plugin name(s). * /
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'phlox'
            ),
            'notice_ask_to_update'            => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'phlox'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                /* translators: 1: plugin name(s). * /
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'phlox'
            ),
            'notice_can_activate_required'    => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'phlox'
            ),
            'notice_can_activate_recommended' => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'phlox'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'phlox'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'phlox'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'phlox'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'phlox' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'phlox' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'phlox' ),
            /* translators: 1: plugin name. * /
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'phlox' ),
            /* translators: 1: plugin name. * /
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'phlox' ),
            /* translators: 1: dashboard link. * /
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'phlox' ),
            'dismiss'                         => __( 'Dismiss this notice', 'phlox' ),
            'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'phlox' ),
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'phlox' ),

            'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
        ),
        */
    );

    tgmpa( $plugins, $config );
}

/*-----------------------------------------------------------------------------------*/
/*  Get and inject generate styles in content of custom css file
/*-----------------------------------------------------------------------------------*/

/**
 * Get generated styles by option panel
 *
 * @return string    return generated styles
 */
function auxin_add_option_styles( $css ){

    $sorted_sections = Auxin_Option::api()->data->sorted_sections;
    $sorted_fields   = Auxin_Option::api()->data->sorted_fields;


    foreach ( $sorted_fields as $section_id => $fields ) {
        foreach ( $fields as $field_id => $field ) {
            if( isset( $field['style_callback'] ) && ! empty( $field['style_callback'] ) ){
                $css[ $field_id ] = call_user_func( $field['style_callback'], null );
            } else {
                unset( $css[ $field_id ] );
            }
        }
    }

    return $css;
}

add_filter( 'auxin_custom_css_file_content', 'auxin_add_option_styles' );

/*-----------------------------------------------------------------------------------*/
/*  Add allowed custom mieme types
/*-----------------------------------------------------------------------------------*/

function auxin_mime_types( $mimes ) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}

add_filter('upload_mimes', 'auxin_mime_types');

/*-----------------------------------------------------------------------------------*/
/*  Populate some widgets on global sidebar after theme activation
/*-----------------------------------------------------------------------------------*/

function auxin_populate_widgets_on_theme_activation(){

    if( get_option( THEME_ID.'_are_default_widgets_populated' ) ){
        return;
    }

    $sidebars_widgets = get_option( 'sidebars_widgets' );

    if( empty( $sidebars_widgets['auxin-global-primary-sidebar-widget-area'] ) ){
        $sidebars_widgets['auxin-global-primary-sidebar-widget-area'] = array(
            'recent-posts-1',
            'categories-1',
            'archives-1'
        );
        update_option('widget_recent-posts', array(
            1 => array('title' => '')
        ));

        update_option('widget_categories', array(
            1 => array('title' => '')
        ));

        update_option('widget_archives', array(
            1 => array('title' => '')
        ));
        update_option( 'sidebars_widgets', $sidebars_widgets );
    }

    update_option( THEME_ID.'_are_default_widgets_populated', 1 );
}

add_action('after_switch_theme', 'auxin_populate_widgets_on_theme_activation');

/*-----------------------------------------------------------------------------------*/
/*  Adds welcome tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_features(){
    ?>
    <div class="feature-section one-col">
        <a href="http://docs.averta.net/display/PD/Phlox+Documentation" class="aux-media-link" target="_blank"><img src="<?php  echo AUX_URL . 'images/welcome/laptop.png'; ?>" alt="Get Started"></a>
    </div>
    <h2 class="aux-featur"><?php _e('Features for sweet blogging', 'phlox'); ?></h2>
    <div class="changelog feature-section three-col">
        <div class="col">
           <img class="welcome-icon" src="<?php  echo AUX_URL . 'images/welcome/page-builder-icon.png'; ?>" alt="">
           <h3><?php _e('INTEGRATED PAGE BUILDER', 'phlox'); ?></h3>
           <p><?php _e('Create your awesome pages easily. Only use your mouse and build your page visually. PHLOX is 100% compatible with SiteOrigin Page builder.', 'phlox'); ?></p>
        </div>
        <div class="col">
           <img class="welcome-icon" src="<?php  echo AUX_URL . 'images/welcome/theme-options.png'; ?>" alt="">
           <h3><?php _e('THEME OPTIONS IN CUSTOMIZE', 'phlox'); ?></h3>
           <p><?php _e('Experience the next level of WordPress in PHLOX, All the theme options are available in customize and you can see your changes real-time.', 'phlox'); ?></p>
        </div>
        <div class="col last-feature">
           <img class="welcome-icon" src="<?php  echo AUX_URL . 'images/welcome/custom-widgets.png'; ?>" alt="">
           <h3><?php _e('CUSTOM WIDGETS', 'phlox'); ?></h3>
           <p><?php _e('PHLOX can satisfy your taste in terms of widgets, there is built-in widgets for almost any needs which you can simply use them.', 'phlox'); ?> </p>
        </div>
    </div>

    <h2 class="aux-featur"><?php _e('Changelog', 'phlox'); ?></h2>

    <div class="welcome-changelog">
    <?php
    $changelog = auxin_get_remote_changelog( THEME_ID );
    if( is_wp_error( $changelog ) ){
        echo $changelog->get_error_message();
    } else {
        echo $changelog;
    }
    ?>
    </div>

    <?php
}

function auxin_welcome_add_section_features( $sections ){

    $sections['features'] = array(
        'label'       => __( 'Welcome', 'phlox' ),
        'description' => sprintf(__( 'We wish you experience a happy journey with %s theme, and we are trying our best to make this happen.', 'phlox'), THEME_NAME_I18N ),
        'callback'    => 'auxin_welcome_page_display_section_features'
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_features', 20 );

/*-----------------------------------------------------------------------------------*/
/*  Adds plugins tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_plugins(){
    require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Need for upgrade classes

    $essential_plugins = TGM_Plugin_Activation::$instance->plugins;
    $installed_plugins = get_plugins();
    ?>

    <div class="feature-section three-col aux-essential-plugins changelog" >
        <?php
        foreach( $essential_plugins as $plugin ):
            $class_attr    = '';

            $plugin_action = Auxin_About::get_instance()->get_plugin_action( $plugin );

            $class_attr   .= 'aux-plugin-to-' . $plugin_action['status'];
            $class_attr .= $plugin['required'] ? ' aux-is-required': '';

            $thumbnail_size = '256x256';
            if( 'instagram-feed' == $plugin['slug'] ){
                $thumbnail_size = '128x128';
            }
            $thumbnail = 'https://ps.w.org/'. $plugin['slug'] .'/assets/icon-'. $thumbnail_size .'.png';
        ?>

        <div class="col <?php echo $class_attr; ?>">
            <img src="<?php echo $thumbnail; ?>" alt="">
            <h3 class="theme-name"><?php echo $plugin['name']; ?></h3>

            <?php if( 'install' !== $plugin_action['status'] ){ ?>
            <div class="plugin-info">
            <?php echo sprintf('Version %s | %s', $installed_plugins[$plugin['file_path']]['Version'], $installed_plugins[$plugin['file_path']]['Author'] ); ?>
            </div>
            <?php } ?>

            <div class="theme-actions">
                <?php echo $plugin_action['link']; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

function auxin_welcome_add_section_plugins( $sections ){

    $sections['plugins'] = array(
        'label'       => __( 'Required Plugins', 'phlox' ),
        'description' => sprintf(__( 'The required and recommended list of plugins for %s theme.', 'phlox'), THEME_NAME_I18N ),
        'callback'    => 'auxin_welcome_page_display_section_plugins'
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_plugins', 40 );

/*-----------------------------------------------------------------------------------*/
/*  Adds demos tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_demos(){
    // all the demos information should add into this array
    $demos_list = auxin_get_demo_info_list();

    if( ! auxin_is_plugin_active( 'auxin-elements/auxin-elements.php' ) ){
        printf(
            __('In order to use this feature, you have to %sinstall and activate%s the <strong>auxin elements</strong> plugin.', 'phlox' ),
            '<a href="' . admin_url( 'plugin-install.php?s=auxin+averta&tab=search&type=term' ) . '">',
            '</a>'
        );

    } else if( ! empty( $demos_list ) ){
        $wpnonce = wp_create_nonce( 'auxin-import' );
    ?>
        <h2 class="aux-featur"><?php _e('Choose the demo you want.', 'phlox'); ?></h2>
        <h4 class="aux-featur demos-subtitle"><?php _e('Please note that, it is recommended to import a demo on a clean WordPress installation.', 'phlox'); ?></h4>
        <div class="changelog feature-section three-col">
    <?php
        foreach( $demos_list as $demo_id => $demo_info ){
    ?>
            <div class="col" id="<?php echo $demo_info['id']; ?>">
                <img class="demos-img" src="<?php echo $demo_info['thumb_url']; ?>" alt="<?php echo $demo_info['title']; ?>">
                <h3><?php echo $demo_info['title']; ?></h3>
                <p><?php  echo $demo_info['desc' ]; ?></p>
                <a href="<?php echo $demo_info['preview_url']; ?>" class="button button-primary aux-button" target="_blank"><?php _e('Preview', 'phlox'); ?></a>
                <a href="<?php echo admin_url( 'import.php?import=auxin-importer&demo-id=' . $demo_id. '&_wpnonce=' . $wpnonce ); ?>" class="button button-primary aux-button import-demo">
                    <?php _e( 'Import Demo', 'phlox' ); ?>
                </a>
            </div>
    <?php
        }
        echo '</div>';
    }
}

function auxin_welcome_add_section_demos( $sections ){

    $sections['demos'] = array(
        'label'       => __( 'Demos', 'phlox' ),
        'description' => sprintf(__( 'you can see and import the  %s demos in this section.', 'phlox'), THEME_NAME_I18N ),
        'callback'    => 'auxin_welcome_page_display_section_demos'
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_demos', 60 );

/*-----------------------------------------------------------------------------------*/
/*  Adds customize tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_add_section_customize( $sections ){

    $sections['customize'] = array(
        'label'       => __( 'Customize Theme', 'phlox' ),
        'description' => '',
        'url'         => admin_url( 'customize.php' ), // optional
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_customize', 70 );

/*-----------------------------------------------------------------------------------*/
/*  Adds support tab in theme about (welcome) page
/*-----------------------------------------------------------------------------------*/

function auxin_welcome_page_display_section_support(){
    ?>
    <div class="feature-section two-col">
        <div class="col">
            <div class="media-container">
                <img src="<?php  echo AUX_URL . 'images/welcome/documentation.png'; ?>" alt="">
            </div>
        </div>
        <div class="col">
            <h3><?php _e('Documentation', 'phlox'); ?></h3>
            <p><?php _e('There is a complete documentation for PHLOX. It will be always up to date. You can easily find out how to create you awesome in PHLOX by having look at this documentation.', 'phlox'); ?></p>
            <a href="http://docs.averta.net/display/PD/Phlox+Documentation" class="button button-primary aux-button" target="_blank"><?php _e('Visit Documentation', 'phlox'); ?></a>
        </div>
    </div>
     <div class="feature-section two-col">
         <div class="col">
            <div class="media-container">
                <img src="<?php  echo AUX_URL . 'images/welcome/support-forum.png'; ?>" alt="">
            </div>
        </div>

        <div class="col">
            <h3><?php _e('Support Forum', 'phlox'); ?></h3>
            <p><?php _e('There is a dedicated support forum with in case you have any issue. Please do not hesitate to submit a ticket our expert support staff would be happy to help you.', 'phlox'); ?></p>
            <a href="https://wordpress.org/support/theme/phlox" class="button button-primary aux-button" target="_blank"><?php _e('Submit a Ticket', 'phlox'); ?></a>
        </div>
    </div>
    <div class="feature-section two-col">
        <div class="col">
            <div class="media-container">
                <img src="<?php  echo AUX_URL . 'images/welcome/video-tutorials.png'; ?>" alt="">
            </div>
        </div>
        <div class="col">
            <h3><?php _e('Video Tutorial (Coming Soon)', 'phlox'); ?></h3>
            <p><?php _e('We are making a series of video tutorial on how to use PHLOX, and we hope it will be available soon.', 'phlox'); ?></p>
        </div>
    </div>
    <?php
}

function auxin_welcome_add_section_support( $sections ){

    $sections['support'] = array(
        'label'       => __( 'Help', 'phlox' ),
        'description' => sprintf(__( 'References and tutorials for %s theme.', 'phlox'), THEME_NAME_I18N ),
        'callback'    => 'auxin_welcome_page_display_section_support'
    );

    return $sections;
}

add_filter( 'auxin_admin_welcome_sections', 'auxin_welcome_add_section_support', 80 );
