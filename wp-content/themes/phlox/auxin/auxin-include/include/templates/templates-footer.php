<?php
/**
 * Template parts for hidden panels
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

if( ! function_exists('auxin_add_hidden_blocks') ){

    function auxin_add_hidden_blocks(){

    ?>
    <div class="aux-hidden-blocks">

        <section id="offmenu" class="aux-offcanvas-menu aux-pin-<?php echo auxin_get_option( 'site_header_mobile_menu_offcanvas_alignment', 'left' ); ?>" >
            <div class="aux-panel-close">
                <div class="aux-close aux-cross-symbol aux-thick-medium"></div>
            </div>
            <div class="offcanvas-header">
            </div>
            <div class="offcanvas-content">
            </div>
            <div class="offcanvas-footer">
            </div>
        </section>
        <!-- offcanvas section -->

        <section id="fs-menu-search" class="aux-fs-popup <?php echo ' ' . auxin_get_option( 'site_menu_full_screen_skin' ); ?>">
            <div class="aux-panel-close">
                <div class="aux-close aux-cross-symbol aux-thick-medium"></div>
            </div>
            <div class="aux-fs-menu">
            <?php
            /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
            //wp_nav_menu( array( 'container_id' => 'master-menu-main-header', 'theme_location' => 'header-primary' ) );
            ?>
            </div>
            <div class="aux-fs-search">
            <?php echo auxin_get_search_box( array( 'has_toggle_icon' => false ) ); ?>
            </div>
        </section>
        <!-- fullscreen search and menu -->

        <section id="fs-search" class="aux-fs-popup aux-search-overlay <?php echo ' ' . auxin_get_option( 'header_fullscreen_search_skin' ); ?>">
            <div class="aux-panel-close">
                <div class="aux-close aux-cross-symbol aux-thick-medium"></div>
            </div>
            <div class="aux-search-field">
            <?php echo auxin_get_search_box( array(
                    'has_form'        => true,
                    'css_class'       => 'aux-404-search',
                    'has_submit_icon' => true,
                    'has_toggle_icon' => false
                )); ?>
            </div>
        </section>
        <!-- fullscreen search-->

        <div class="aux-scroll-top"></div>

    </div>

    <?php
    }

}

