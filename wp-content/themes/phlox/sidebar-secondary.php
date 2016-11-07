<?php
/**
 * Secondary sidebar
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

    if( is_404() ){
        return;
    }

    global $this_page;

    // if no result
    if( ! isset( $this_page ) || is_search() ) {
        return;
    }




    $this_temp_name = get_post_meta( $this_page->ID, '_wp_page_template', TRUE );
    $this_page_type = $this_page->post_type;

    // always generate secondary sidebar on customizer (useful for previewing live changes in sidebar positions)
    if( 2 === auxin_has_sidebar( $this_page ) || is_customize_preview() ) { ?>

            <aside class="aux-sidebar aux-sidebar-secondary">
                <div class="sidebar-inner">
                    <div class="sidebar-content">

    <?php if ( ( $this_page_type == 'post' || strpos( $this_temp_name, 'blog' ) !== false ) && is_active_sidebar( 'auxin-blog-secondary-sidebar-widget-area' ) ) { ?>
                        <div class="widget-area">
                            <?php dynamic_sidebar( 'auxin-blog-secondary-sidebar-widget-area' ); ?>
                        </div>
    <?php } elseif( $sidebar_id = auxin_get_page_sidebar_id( $this_page ) ) {

                if( isset( $sidebar_id ) && ! empty( $sidebar_id ) && is_active_sidebar( $sidebar_id ) ){
    ?>
                        <div class="widget-area">
                            <?php dynamic_sidebar( $sidebar_id ); ?>
                        </div>
    <?php       }

          } else { ?>
                        <div class="widget-area">
                            <?php dynamic_sidebar( 'auxin-global-secondary-sidebar-widget-area' ); ?>
                        </div>
    <?php
          }
    ?>
                    </div>

                </div><!-- end sidebar wrapper -->
            </aside><!-- end secondary siderbar -->

<?php
    }
