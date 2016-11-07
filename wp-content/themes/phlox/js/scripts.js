/*! auxin WordPress Framework - v2.1.0 (2016-10-06)
 *  Scripts for initializing plugins 
 *  http://averta.net
 *  (c) 2010-2016 averta;
 */



/* ================== js/src/elements.js =================== */


jQuery(function($){

    // messagebox script
    $('.msgbox').each(function(i){

        $(this).find("a.close").on("click", function(event){
            event.preventDefault();
            var $block = $(this).closest('.msgbox');

            $block.slideUp(300, function(){
                $block.remove();
            });
        });

    });

    // init timeline
    $('.aux-timeline').AuxinTimeline();
});


// position callout button in safari
;(function($) {
    // if (!(navigator.userAgent.indexOf('Safari') !== -1 && navigator.userAgent.indexOf('Chrome') === -1)) return;

    // var $callout = $('div.callout');
    // var $btn     = $callout.find('.featured_btn');
    // var $label   = $btn.find('span');

    // function updateCalloutBtnPosition(){
    //     var topPos   = ($btn.height() - $label.height()) * 0.5;
    //     $label.css('top', topPos);
    // }
    // updateCalloutBtnPosition();
    // $(window).bind("resize", updateCalloutBtnPosition );

})(jQuery);


/* ================== js/src/functions.js =================== */


/*--------------------------------------------
 *  Functions
 *--------------------------------------------*/

function auxin_is_rtl(){
    return ((typeof auxin !== 'undefined') && (auxin.is_rtl == "1" || auxin.wpml_lang == "fa") )?true:false;
}
;


/* ================== js/src/generals.js =================== */


/* ------------------------------------------------------------------------------ */
// General javascripts
/* ------------------------------------------------------------------------------ */

;(function ( $, window, document, undefined ) {
    "use strict";

    var $window = $(window),
        $siteHeader = $('#site-header'),
        headerStickyHeight = $('#site-header').data('sticky-height') || 0;

    if ( ( $siteHeader ).find( '.secondary-bar' ).length ) {
        headerStickyHeight += 35; // TODO: it should changed to a dynamic way in future
    }



    /**
     * opens or closes the overlay container in page
     * @param  {jQuery Element} $overlay
     * @param  {Boolean}        close              Is it closed right now?
     * @param  {Number}         animDuration
     */
    window.auxToggleOverlayContainer = function( $overlay, close, animDuration ) {
        var anim = $overlay.data( 'anim' ),
            overlay = $overlay[0],
            animDuration = animDuration || 800;

        if ( anim ) {
            anim.stop( true );
        }

        if ( close ) {
            $overlay.css( {
                opacity: 0,
                display: 'block'
            } );

            overlay.style[ window._jcsspfx + 'Transform' ] = 'perspective(200px) translateZ(30px)';
            anim = CTween.animate($overlay, animDuration, {
                transform: 'none', opacity: 1
            }, {
                ease: 'easeOutQuart'
            });

        } else {
            anim = CTween.animate($overlay, animDuration / 2, {
                transform: 'perspective(200px) translateZ(30px)',
                opacity: 0
            }, {
                ease: 'easeInQuad',
                complete: function() {
                    $overlay.css( 'display', 'none' );
                }
            });
        }

        $overlay.data( 'anim', anim );

    };

/* ------------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------------ */
    // fullscreen/overlay search
    var overlaySearchIsClosed = true,
        overlaySearchContainer = $('#fs-search'),
        searchField = overlaySearchContainer.find( 'input[type="text"]' );

    $('.aux-overlay-search').click( toggleOverlaySearch );
    overlaySearchContainer.find( '.aux-panel-close' ).click( toggleOverlaySearch );

    $(document).keydown( function(e) {
        if ( e.keyCode == 27 && !overlaySearchIsClosed ) {
            toggleOverlaySearch();
        }
    });

    function toggleOverlaySearch() {
        auxToggleOverlayContainer( overlaySearchContainer, overlaySearchIsClosed );
        overlaySearchIsClosed = !overlaySearchIsClosed;
        if ( !overlaySearchIsClosed ) {
            searchField.focus();
        }
    };

/* ------------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------------ */
    // burger mobile menu and search intraction
    // TODO: selectors should be more accurate in future
    var $burger         = $('#nav-burger'),
        $burgerIcon     = $burger.find('>.aux-burger'),
        isClosed        = true,
        animDuration    = 600,
        $menu           = $('header .aux-master-menu'),
        anim, $menuContainer;

    /* ------------------------------------------------------------------------------ */
    function toggleExpnadableMenu() {
        $burgerIcon.toggleClass( 'aux-close' );

        if ( anim ) {
            anim.stop( true );
        }

        if ( isClosed ) {
            anim = CTween.animate($menuContainer, animDuration, { height: $menu.outerHeight() + 'px' }, {
                ease: 'easeInOutQuart',
                complete: function() {
                    $menuContainer.css( 'height', 'auto' );
                }
            } );
        } else {
            $menuContainer.css( 'height', $menu.outerHeight() + 'px' );
            anim = CTween.animate($menuContainer, animDuration, { height: 0 }, { ease: 'easeInOutQuart' } );
        }

        isClosed = !isClosed;
    }

    /* ------------------------------------------------------------------------------ */
    function toggleOffcanvasMenu() {
        $burgerIcon.toggleClass( 'aux-close' );
        $menuContainer.toggleClass( 'aux-open' );
        isClosed = !isClosed;
        // if ( !isClosed ) {
        //     touchSwipe.enable();
        // } else {
        //     touchSwipe.disable();
        // }
    }

    /* ------------------------------------------------------------------------------ */
    function toggleOverlayMenu() {
        $burgerIcon.toggleClass( 'aux-close' );
        if ( isClosed ) {
            $menuContainer.show();
        }
        auxToggleOverlayContainer( $menuContainer, isClosed );
        isClosed = !isClosed;
    }
    /* ------------------------------------------------------------------------------ */
    function closeOnEsc( toggleFunction ) {
        $(document).keydown( function(e) {
            if ( e.keyCode == 27 && !isClosed ) {
                toggleFunction();
            }
        });
    }

    /* ------------------------------------------------------------------------------ */

    switch ( $burger.data( 'target-panel' ) ) {
        case 'toggle-bar':
            $menuContainer  = $('header .aux-toggle-menu-bar');
            $burger.click( toggleExpnadableMenu );
            break;
        case 'offcanvas':
            $menuContainer  = $('#offmenu')
            $burger.click( toggleOffcanvasMenu );
            $menuContainer.find('.aux-close').click( toggleOffcanvasMenu );

            // setup swipe
            //var touchSwipe = new averta.TouchSwipe( $(document) );
            var activeWidth = $menu.data( 'switch-width' ),
                dir = ( $menuContainer.hasClass( 'aux-pin-right' ) ? 'right' : 'left' );

            //touchSwipe.disable();

            // touchSwipe.onSwipe = function( status ) {
            //     if ( !isClosed && status.speed > 0.7 && dir === status.direction ) {
            //          toggleOffcanvasMenu();
            //     }
            // }

            if ( activeWidth !== undefined ) {
                $window.on( 'resize', function() {
                    if ( window.innerWidth > activeWidth ) {
                        //touchSwipe.disable();
                        $menuContainer.hide();
                    } else {
                        if ( !isClosed ) {
                            //touchSwipe.enable();
                        }
                        $menuContainer.show();
                    }
                });
            }

            closeOnEsc( toggleOffcanvasMenu );
            break;

        case 'overlay':
            var activeWidth = $menu.data( 'switch-width' ),
                oldSkinClassName = $menu.attr( 'class' ).match( /aux-skin-\w+/ )[0];
            $menuContainer = $('#fs-menu-search');
            $burger.click( toggleOverlayMenu );
            $menuContainer.find( '.aux-panel-close' ).click( toggleOverlayMenu );

            var checkForHide = function() {
                if ( window.innerWidth > activeWidth ) {
                    $menuContainer.hide();
                    $menu.addClass( oldSkinClassName );
                } else {
                    if ( !isClosed ) {
                        $menuContainer.show();
                    }
                    $menu.removeClass( oldSkinClassName );
                }
            }

            if ( activeWidth !== undefined ) {
                checkForHide();
                $window.on( 'resize', checkForHide );
            }

            closeOnEsc( toggleOverlayMenu );
    }

    /* ------------------------------------------------------------------------------ */
    // scroll to bottom in title bar
    if ( jQuery.fn.scrollTo ) {
        var $scrollToTarget = $('#site-title');
        $('.aux-title-scroll-down .aux-arrow-nav').click( function(){
            var target = $scrollToTarget.offset().top + $scrollToTarget.height() - headerStickyHeight;
            $window.scrollTo( target , {duration: 1500, easing:'easeInOutQuart'}  );
        } );
    }

    /* ------------------------------------------------------------------------------ */
    // goto top
    var gotoTopBtn = $('.aux-goto-top-btn'), distToFooter, footerHeight;

    $( function() {
        if ( gotoTopBtn.length && jQuery.fn.scrollTo ) {
            footerHeight = $('#sitefooter').outerHeight();

            gotoTopBtn.on( 'click touchstart', function() {
                $window.scrollTo( 0, {duration: gotoTopBtn.data('animate-scroll') ? 1500 : 0,  easing:'easeInOutQuart'});
            } );

            gotoTopBtn.css('display', 'block');
            scrollToTopOnScrollCheck();
            $window.on('scroll', scrollToTopOnScrollCheck);
        }


        function scrollToTopOnScrollCheck() {
            if ( $window.scrollTop() > 200 ) {
                gotoTopBtn[0].style[window._jcsspfx + 'Transform'] = 'translateY(0)';
                distToFooter = document.body.scrollHeight - $window.scrollTop() - window.innerHeight - footerHeight;

                if ( distToFooter < 0 ) {
                    gotoTopBtn[0].style[window._jcsspfx + 'Transform'] = 'translateY('+distToFooter+'px)';
                }
            } else {
                gotoTopBtn[0].style[window._jcsspfx + 'Transform'] = 'translateY(150px)';
            }
        }

        /* ------------------------------------------------------------------------------ */
        // animated goto
        if ( $.fn.scrollTo ) {
            $('a[href^="\#"]:not([href="\#"])').click( function(e) {
                e.preventDefault();
                var $this = $(this);
                $window.scrollTo( $( $this.attr( 'href' ) ).offset().top - headerStickyHeight, $this.hasClass( 'aux-jump' )  ? 0 : 1500,  {easing:'easeInOutQuart'});
            });
        }

        /* ------------------------------------------------------------------------------ */
        // add space above sticky header if we have the wp admin bar in the page

        var $adminBar = $('#wpadminbar');
        if ( $adminBar.length ) {
            $('#site-header').on( 'sticky', function(){
                if ( $adminBar.hasClass('mobile') || window.innerWidth <= 600 ) {
                    return;
                }
                $(this).css( 'top', $adminBar.height() + 'px' );
            }).on( 'unsticky', function(){
                $(this).css( 'top', '' );
            });
        }

        /* ------------------------------------------------------------------------------ */
        // disable search submit if the field is empty

        $('.aux-search-field, #searchform #s').each(function(){
            var $this = $(this);
            $this.parent('form').on( 'submit', function( e ){
                if ( $this.val() === '' ) {
                    e.preventDefault();
                }
            });
        });

        /* ------------------------------------------------------------------------------ */
        // fix megamenu width for middle aligned menu in header
        var $headerContainer = $siteHeader.find('.container'),
            $headerMenu = $('#menu-main-menu');
        var calculateMegamenuWidth = function(){
            var $mm = $siteHeader.find( '.aux-middle .aux-megamenu' );
            if ( $mm.length ) {
                $mm.width( $headerContainer.innerWidth() );
                $mm.css( 'left', -( $headerMenu.offset().left - $headerContainer.offset().left ) + 'px' );
            } else {
                $headerMenu.find( '.aux-megamenu' ).css('width', '').css( 'left', '' );
            }
        };
        calculateMegamenuWidth();
        $window.on( 'resize', calculateMegamenuWidth );
    });

})(jQuery, window, document);


/* ================== js/src/init.averta.js =================== */


;(function($){

/*--------------------------------------------
 *  Averta plugins
 *--------------------------------------------*/

    // on document ready
    $(function(){

        $('.widget-tabs .widget-inner').avertaLiveTabs({
            tabs:            'ul.tabs > li',            // Tabs selector
            tabsActiveClass: 'active',                  // A Class that indicates active tab
            contents:        'ul.tabs-content > li',    // Tabs content selector
            contentsActiveClass: 'active',              // A Class that indicates active tab-content
            transition:      'fade',                    // Animation type white swiching tabs
            duration :       '500'                      // Animation duration in mili seconds
        });

        $('.widget-toggle .widget-inner').each( function( index ) {
            $(this).avertaAccordion({
                itemHeader : '.toggle-header',
                itemContent: '.toggle-content',
                oneVisible : $(this).data("toggle") ,
            });
        });

        // parallax extions
        $('.aux-parallax-box').AvertaParallaxBox();

   });


    // $(".scroll2top").avertaScroll2top({ ease:'easeInOutQuint', speed:800 });

    /* ------------------------------------------------------------------------------ */
    var isResp = $('body').hasClass( 'aux-resp' );

    // master menu init
    if ( !isResp && $('.aux-master-menu').data( 'switch-width' ) < 7000 ) {
        // disable switch if layout is not responsive
        $('.aux-master-menu').data( 'switch-width', 0 );
    }

    // init matchHeight
    $('.aux-match-height > .aux-col').matchHeight();

    // init Master Menu
    $('.aux-master-menu').mastermenu( /*{openOn:'press'}*/ );

    // float layout init
    $('.aux-float-layout').AuxinFloatLayout({ autoLocate: isResp });

    // header sticky position
    if ( $('body').hasClass( 'aux-top-sticky' ) ) {
        $('#site-header').AuxinStickyPosition();
    }

    // fullscreen header
    $('.page-header.aux-full-height').AuxinFullscreenHero();

    $('input, textarea').placeholder();

    // init gallery
    $(".aux-gallery .aux-layout-justify-rows, .aux-gallery .aux-layout-masonry,.aux-gallery .aux-layout-grid").AuxIsotope({
        itemSelector:'.gallery-item',
        justifyRows: {maxHeight: 340, gutter:0},
        masonry: { gutter:0 },
        transitionDuration  : 1000,
        betweenDelay        : 600,
        transitionDelay     : 100,
        hideTransitionDelay : 100
    });

    // init fitvids
    $('main').fitVids();
    $('main').fitVids({ customSelector: 'iframe[src^="http://w.soundcloud.com"], iframe[src^="https://w.soundcloud.com"]'});


})(jQuery);


/* ================== js/src/init.carousel.js =================== */



(function($, window, document, undefined){
    "use strict";

    $('.master-carousel-slider').AuxinCarousel({
        autoplay: false,
        columns: 1,
        speed: 15,
        inView: 15,
        autohight: false
    });

    // all other master carousel instances
    $('.master-carousel').AuxinCarousel({
        speed: 30
    });

})(jQuery, window, document);


/* ================== js/src/init.chart.js =================== */


/*--------------------------------------------
 *  Animate Progress chart
 *--------------------------------------------*/

jQuery(function($){

    $chart = $('.widget-chart');
    if(!$chart.length) return;

    $bars  = $chart.find('.chart-bar');

    $.each($bars, function(i){
        $this = $(this);
        $slider = $this.children('div');
        percent = parseInt($slider.find('em').text());

        $slider.width(0);
        $slider.delay(i * 150).animate(
            { 'width': (percent+"%") },
            { duration:2000,
              easing: 'easeOutQuad'
            }
        );
    });

});


/* ================== js/src/init.highlightjs.js =================== */


;jQuery(function($){
     if(typeof hljs !== 'undefined') {
         $('pre code').each(function(i, block) {
             hljs.highlightBlock(block);
         });
     }
});


/* ================== js/src/init.lightbox.js =================== */


;(function($){

    $('.aux-lightbox-frame').photoSwipe(
        '.aux-lightbox-btn',
        {
            bgOpacity: 0.8,
            shareEl: true
        }
    );


})(jQuery);


/* ================== js/src/init.map.js =================== */


;(function($){
    
    
    
})(jQuery);


/* ================== js/src/init.superfish.js =================== */


// ;(function($){

/*--------------------------------------------
 *   superfish menu init
 *--------------------------------------------*/

//     function init_superfish(speed, delay, fade){

//         var animEff = { opacity:'show', height:'show' };
//         if(fade) animEff.opacity = 'show';

//         $('.aux-master-menu').superfish({
//             delay:       delay,    // one second delay on mouseout
//             animation:   animEff,  // fade-in and slide-down animation
//             speed:       speed,    // faster animation speed
//             autoArrows:  true,     // disable generation of arrow mark-up

//             dropShadows: false     // disable drop shadows
//         })
//         .find("a.sf-with-ul")
//              .after("<div class=\"sf-sub-indicator icon-angle-down\" ></div>")
//         .end()
//         .find('.sf-sub-indicator')
//             .click( function () {
//                 $(this).parent().toggleClass("axi_popdrop");
//             });
//     }

//     init_superfish('fast', 100, true);

// })(jQuery);


/* ================== js/src/init.videobox.js =================== */


/*--------------------------------------------
 *  Init Auxin Video Box
 *--------------------------------------------*/

jQuery( function($) {
    $('.aux-video-box').AuxinVideobox();
});


/* ================== js/src/polyfills.js =================== */


// jQuery('[type="color"]').avertaColorpicker();
// jQuery('input, textarea').placeholder();


/* ================== js/src/resize.js =================== */



/*--------------------------------------------
 *  on resize
 *--------------------------------------------*/

;(function($){

    var $_window                = $(window),
        screenWidth             = $_window.width(),
        $main_content           = $('#main'),
        breakpoint_tablet       = 768,
        breakpoint_desktop      = 1024,
        breakpoint_desktop_plus = 1140,
        original_page_layout    = '',
        layout_class_names      = {
            'right-left-sidebar' : 'right-sidebar',
            'left-right-sidebar' : 'left-sidebar',
            'left2-sidebar'      : 'left-sidebar',
            'right2-sidebar'     : 'right-sidebar'
        };


    function updateSidebarsHeight() {

        screenWidth = window.innerWidth;

        var $content   = $('.aux-primary');
        var $sidebars  = $('.aux-sidebar');

        var max_height = $('.aux-sidebar .sidebar-inner').map(function(){
            return $(this).outerHeight();
        }).get();

        max_height = Math.max.apply(null, max_height);
        max_height = Math.max( $content.outerHeight(), max_height );
        $sidebars.height( screenWidth >= breakpoint_tablet ? max_height : 'auto' );

        // Switching 2 sidebar layouts on mobile and tablet size
        // ------------------------------------------------------------

        // if it was not on desktop size
        if( screenWidth <= breakpoint_desktop_plus ){

            for ( original in layout_class_names) {
                if( $main_content.hasClass( original ) ){
                    original_page_layout =  original;
                    $main_content.removeClass( original ).addClass( layout_class_names[ original ] );
                    return;
                }
            }

        // if it was on desktop size
        } else if( '' !== original_page_layout ) {
            $main_content.removeClass('left-sidebar')
                         .removeClass('right-sidebar')
                         .addClass( original_page_layout );

            original_page_layout = '';
        }
    };


    // overrides instagram feed class and updates sidebar height on instagram images load
    if ( window.instagramfeed ) {
        var _run = instagramfeed.prototype.run;
        instagramfeed.prototype.run = function() {
            var $target = $(this.options.target);
            if ( $target.parents( '.aux-sidebar' ).length > 0 ) {
                var _after = this.options.after;
                this.options.after = function() {
                    _after.apply( this, arguments );
                    $target.find('img').one( 'load', updateSidebarsHeight );
                };
            }
            _run.apply( this, arguments );
        };
    }

    $_window.on("debouncedresize", updateSidebarsHeight).trigger('debouncedresize');

})(jQuery);

/*--------------------------------------------*/


;