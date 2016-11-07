<?php
    /* The footer sidebar widget area is triggered if any of the areas have widgets. */

    do_action('auxin_before_the_subfooter');
?>

<?php
$is_customize_preview = is_customize_preview();
$show_subfooter_bar   = auxin_get_option('show_subfooter_bar');

if( $show_subfooter_bar || $is_customize_preview ) {
    $layout  = auxin_get_option('subfooter_bar_layout');
    // if it is previewed in customizer, generate the markup but hide it (for live manipulation in customizer)
    $visibility  = $is_customize_preview  && ! $show_subfooter_bar ? ' aux-hide ' : '';
?>
    <aside class="aux-subfooter-bar <?php echo $layout . $visibility; ?>">
        <div class="aux-wrapper">
            <div class="container <?php echo in_array( $layout, array( 'vertical-none-boxed', 'vertical-small-boxed' ) ) ? 'aux-fold' : ''; ?>">
                <div class="widget-area">
                    <?php
                    if( is_active_sidebar( 'auxin-subfooter-bar-widget-area' ) ){
                        dynamic_sidebar( 'auxin-subfooter-bar-widget-area' );
                    } else {
                        echo '<div>' . __('Empty widget area.', 'phlox' ) . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </aside>
<?php
}
unset( $show_subfooter_bar );




if( auxin_get_option('show_subfooter') ) { ?>

    <aside class="subfooter aux-subfooter">
        <div class="aux-wrapper">
            <div class="container aux-fold">

                <div class="aux-row">

<?php
    $layout    = auxin_get_option( 'subfooter_layout' );
    $grid_cols = explode( '_', $layout);
    $col_nums  = count( $grid_cols );

    for ( $i = 1; $i <= $col_nums; $i++ ) {
        $grid_tablet_class = 'aux-tb-' . ( $col_nums > 2 ? 3 : $col_nums );
?>
                <div class="widget-area <?php echo 'aux-' . $grid_cols[ $i-1 ] . ' ' . $grid_tablet_class; ?> aux-mb-1">
                    <?php
                    dynamic_sidebar( 'auxin-footer'.$i.'-sidebar-widget-area' );
                    if ( ! is_active_sidebar( 'auxin-footer'.$i.'-sidebar-widget-area' ) ) {
                        echo '<p>' . __('Empty widget area.', 'phlox' ) . '</p>';
                    }
                    ?>
                </div>
<?php
    }
?>
                </div>

            </div><!-- end of container -->

            <?php do_action('auxin_in_the_subfooter'); ?>

        </div><!-- end of wrapper -->

    </aside><!-- end footer widget -->

<?php
}
?>
