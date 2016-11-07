<?php
/**
 * The Header for theme.
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */
 global $page, $post, $this_page; $this_page = $post;
?>
<!DOCTYPE html>
<!--[if IE 7]>    <html class="no-js oldie ie7 ie" <?php language_attributes(); ?> > <![endif]-->
<!--[if IE 8]>    <html class="no-js oldie ie8 ie" <?php language_attributes(); ?> > <![endif]-->
<!--[if IE 9 ]>   <html class="no-js       ie9 ie" <?php language_attributes(); ?> > <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?> > <!--<![endif]-->
<head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" >
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php echo masterslider( 1 ); ?>
<?php if( auxin_get_option( 'enable_site_reponsiveness', 1 ) ) { ?>
        <!-- devices setting -->
        <meta name="viewport"           content="initial-scale=1,user-scalable=no,width=device-width">
<?php } else { ?>
        <!-- devices setting -->
        <meta name="viewport"           content="initial-scale=1">
<?php } ?>

        <!-- pingback -->
        <link rel="pingback"            href="<?php bloginfo( 'pingback_url' ); ?>" />

        <!-- enables HTML5 elements & feature detects -->
        <script src="<?php echo get_template_directory_uri(); ?>/js/solo/modernizr-custom.js"></script>

<!-- outputs by wp_head -->
<?php wp_head(); ?>

<!-- end wp_head -->

</head>

<body <?php body_class(); ?>>

<?php  do_action( "auxin_after_body_open", $post ); ?>
<div id="inner-body">

<?php
    $site_header_class  = $header_max_width = 'aux-territory aux-' . auxin_get_option('site_header_width') . '-container'; 
    $site_header_class .= auxin_get_option('site_header_border_bottom') ? ' aux-add-border' : ''; 
    $site_header_class .= 'logo-left-menu-right-over' === auxin_get_top_header_layout() ? ' aux-over-content' : '';

if( auxin_get_option('show_topheader') ) {    
?> 

    <div id="top-header" class="aux-top-header <?php echo $header_max_width; ?>">
        <div class="aux-wrapper aux-float-layout">

            <?php echo auxin_get_top_header_markup(); ?>

        </div><!-- end wrapper -->
    </div><!-- end top header -->

<?php
    }

    do_action( 'auxin_before_the_header', $post ); ?>

    <header id="site-header" class="site-header-section <?php echo $site_header_class; ?>" data-sticky-height="<?php echo auxin_get_option('site_header_container_scaled_height','60')?>">
        <div class="aux-wrapper">
            <div class="container aux-fold">

            <?php echo auxin_get_header_layout();  ?>

            </div><!-- end of container -->
        </div><!-- end of wrapper -->
    </header><!-- end header -->

<?php echo masterslider( 1 ); ?>

<?php do_action( 'auxin_before_the_content' );
