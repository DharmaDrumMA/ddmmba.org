<?php
/**
 * Primary sidebar
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

  /* The sidebar widget area is triggered if any of the areas have widgets. */
    if( is_404() ){
        return;
    }

    global $this_page;

    // if no result
    if( ! isset( $this_page ) || is_search() ) {
?>
            <aside class="aux-sidebar aux-sidebar-primary">
                <div class="sidebar-inner">
                    <div class="sidebar-content">
<?php if ( is_active_sidebar( 'auxin-search-sidebar-widget-area' ) ) : ?>
                        <div class="widget-area">
                            <?php
                            if( is_active_sidebar( 'auxin-search-sidebar-widget-area' ) ){
                                dynamic_sidebar( 'auxin-search-sidebar-widget-area' );
                            } else {
                                echo '<div>' . __('Search widget area is empty.', 'phlox' ) . '</div>';
                            }
                            ?>

                        </div>
<?php endif; ?>
                    </div>
                </div><!-- end sidebar wrapper -->
            </aside><!-- end siderbar -->

<?php return;
    }




    $this_temp_name = get_post_meta( $this_page->ID, '_wp_page_template', TRUE );
    $this_page_type = $this_page->post_type;

    if( auxin_has_sidebar( $this_page ) ) { ?>

            <aside class="aux-sidebar aux-sidebar-primary">
                <div class="sidebar-inner">
                    <div class="sidebar-content">

    <?php if ( ( $this_page_type == 'post' || strpos( $this_temp_name, 'blog' ) !== false ) && is_active_sidebar( 'auxin-blog-primary-sidebar-widget-area' ) ) { ?>
                        <div class="widget-area">
                            <?php
                            if( is_active_sidebar( 'auxin-blog-primary-sidebar-widget-area' ) ){
                                dynamic_sidebar( 'auxin-blog-primary-sidebar-widget-area' );
                            } else {
                                echo '<div>' . __('Blog widget area is empty.', 'phlox' ) . '</div>';
                            }
                            ?>
                        </div>
    <?php } elseif( $sidebar_id = auxin_get_page_sidebar_id( $this_page ) ) {

            $sidebar_id = esc_attr( $sidebar_id );
            if( ! empty( $sidebar_id ) && is_active_sidebar( $sidebar_id ) && function_exists( 'dynamic_sidebar' ) ){
    ?>
                        <div class="widget-area">
                            <?php dynamic_sidebar( $sidebar_id ); ?>
                        </div>
    <?php   }

        } else { ?>
                        <div class="widget-area">
                            <?php dynamic_sidebar( 'auxin-global-primary-sidebar-widget-area' ); ?>
                        </div>
    <?php
        }
    ?>
                    </div>

                </div><!-- end sidebar wrapper -->
            </aside><!-- end primary siderbar -->
<?php
    }
