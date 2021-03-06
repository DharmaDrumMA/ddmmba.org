<?php /* Loops through all items to displaying search results. */

if ( have_posts() ){

    get_template_part('templates/theme-parts/entry/search' );

    auxin_the_search_paginate_nav(
        array( 'css_class' => auxin_get_option('archive_pagination_skin') )
    );

} else {
    get_template_part('templates/theme-parts/content', 'no-results' );
}

