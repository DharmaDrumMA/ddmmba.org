<?php global $post, $more; $more = 0; // to enable read more tag

    $post_vars   = auxin_get_post_format_media( $post, array( 'request_from' => 'archive' ) );
    extract( $post_vars );
?>
                        <article <?php post_class(); ?> >
                            <?php if ( $has_attach ) : ?>
                            <div class="entry-media">

                                <?php echo $the_media; ?>

                            </div>
                            <?php endif; ?>

                            <div class="entry-main">

                                <header class="entry-header">
                                <?php
                                if( $show_title ) {
                                    if( 'quote' == $post_format ) { echo '<p class="quote-format-excerpt">'. $excerpt .'</p>'; } ?>

                                    <h3 class="entry-title">
                                        <a href="<?php echo !empty( $the_link ) ? $the_link : get_permalink(); ?>">
                                            <?php echo !empty( $the_name ) ? $the_name : get_the_title(); ?>
                                        </a>
                                    </h3>
                                <?php
                                } ?>
                                    <div class="entry-format">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="post-format format-<?php echo $post_format; ?>"> </div>
                                        </a>
                                    </div>
                                </header>

                                <?php if( 'quote' !== $post_format ) { ?>
                                <div class="entry-info">
                                    <div class="entry-date">
                                        <a href="<?php the_permalink(); ?>">
                                            <time datetime="<?php the_time('Y-m-d')?>" title="<?php the_time('Y-m-d')?>" ><?php the_time('F j, Y'); ?></time>
                                        </a>
                                    </div>
                                    <span class="meta-sep"><?php _e("by", 'phlox'); ?></span>
                                    <span class="author vcard">
                                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'phlox'), get_the_author() ); ?>" >
                                            <?php the_author(); ?>
                                        </a>
                                    </span>
                                    <?php if( comments_open() ){ /* just display comments number if the comments is not closed. */?>
                                    <span class="meta-sep"><?php _e("with", 'phlox'); ?></span>
                                    <a href="<?php the_permalink(); ?>#comments" class="meta-comment" ><?php comments_number( __('No Comment', 'phlox'), __('One Comment', 'phlox'), __('% Comments', 'phlox') );?></a>
                                    <?php } ?>
                                    <span class="entry-tax">
                                        <?php // the_category(' '); we can use this template tag, but customizable way is needed! ?>
                                        <?php $tax_name = 'category';
                                              if( $cat_terms = wp_get_post_terms( $post->ID, $tax_name ) ){
                                                  foreach( $cat_terms as $term ){
                                                      echo '<a href="'. get_term_link( $term->slug, $tax_name ) .'" title="'.__("View all posts in ", 'phlox'). $term->name .'" rel="category" >'. $term->name .'</a>';
                                                  }
                                              }
                                        ?>
                                    </span>
                                    <?php edit_post_link(__("Edit", 'phlox'), '<i> | </i>', ''); ?>
                                </div>
                                <?php } ?>

                                <?php if( 'quote' !== $post_format ) { ?>
                                <div class="entry-content">
                                    <?php
                                    if( 'link' == $post_format ) {
                                        echo '<a href="'. $the_link .'" class="link-format-excerpt">' . $the_link . '</a>';

                                    } else {
                                        $content_listing_type   = is_category() || is_tag() ? auxin_get_option( 'post_taxonomy_archive_content_on_listing' ) : auxin_get_option( 'blog_content_on_listing' );
                                        $content_listing_length = is_category() || is_tag() ? auxin_get_option( 'post_taxonomy_archive_on_listing_length', 255 ) :
                                                                  auxin_get_option( 'blog_content_on_listing_length', 255 );

                                        if( has_excerpt() ){
                                            the_excerpt();
                                        } else if( $content_listing_type == 'full' ) {
                                            the_content( __( 'Continue Reading', 'phlox' ) );
                                        } else {
                                            auxin_the_trim_excerpt( null, $content_listing_length, null, false, 'p' );
                                        }


                                        // clear the floated elements at the end of content
                                        echo '<div class="clear"></div>';

                                        // create pagination for page content
                                        wp_link_pages( array( 'before' => '<div class="page-links"><span>' . __( 'Pages:', 'phlox') .'</span>', 'after' => '</div>' ) );
                                    }
                                    ?>
                                </div>
                                <?php } ?>

                                <footer class="entry-meta">
                                    <div class="readmore">
                                        <a href="<?php the_permalink(); ?>" class="aux-read-more aux-outline aux-large"><?php _e("Read More", 'phlox'); ?></a>
                                    </div>
                                </footer>

                            </div>

                        </article>
