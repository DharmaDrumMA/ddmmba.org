<?php

/**
 * Set the archive page content inside auxin content wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action('woocommerce_before_main_content', 'auxin_wc_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'auxin_wc_wrapper_end', 10);
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);



/**
 * Start wrapper for woocommerce
 */
function auxin_wc_wrapper_start() {
    ?>
    <main id="main" <?php auxin_content_main_class(); ?> >
        <div class="aux-wrapper">
            <div class="container aux-fold">
                <div id="primary" class="aux-primary" >
                    <div class="content" role="main"  >
<!-- @TODO: MJ should check if these are no neccesarry remove it -->
                        <!-- <div class="container block"> -->
    <?php
}

/**
 * End wrapper for woocommerce
 */
function auxin_wc_wrapper_end() {
  ?>
  <!-- @TODO: MJ should check if these are no neccesarry remove it -->
                        <!-- </div> -->
                    </div>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </main>
    <?php
}



/**
 * Replace WooCommerce Default Pagination with auxin pagination
 *
 */
remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);
add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10);
function woocommerce_pagination() {
    auxin_the_paginate_nav(
        array( 'css_class' => auxin_get_option('archive_pagination_skin') )
    );
}




// Change default template directory for woocommerce to ''templates/woocommerce/''
function auxin_woocommerce_template_path(){
  return 'templates/woocommerce/';
}

add_filter( 'woocommerce_template_path', 'auxin_woocommerce_template_path' );



/**
 * Make sure cart contents update when products are added to the cart via AJAX
 */
function auxin_wc_add_to_cart_fragment( $fragments ) {

    ob_start();
    $count = WC()->cart->cart_contents_count;
    ?>
        <a class="aux-cart-contents aux-header-cart auxicon-shopping-cart-1-1" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'phlox' ); ?>">
            <?php if ( $count > 0 ) echo '<span>' . $count . '</span>'; ?>
        </a>
    <?php
    $fragments['a.aux-cart-contents'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'auxin_wc_add_to_cart_fragment' );

/*-----------------------------------------------------------------------------------*/



