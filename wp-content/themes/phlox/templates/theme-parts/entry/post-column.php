                        <?php $post_classes = ( ! empty($post_classes) )? $post_classes . ' column-entry' :  'column-entry'; ?>
                        <?php $no_media = ( ! ( $has_attach && $show_media ) )? ' no-media' :  '' ?>

                        <article <?php post_class( $post_classes . $no_media ); ?> >
                            <?php if ( $has_attach && $show_media ) : ?>
                            <div class="entry-media">

                                <?php echo $the_media; ?>

                            </div>
                            <?php endif; ?>

                            <div class="entry-main">

                                <header class="entry-header">
                                <?php
                                if( $show_title ) {
                                    if( 'quote' == $post_format ) { echo '<p class="quote-format-excerpt">'. $excerpt .'</p>'; } ?>

                                    <h4 class="entry-title">
                                        <a href="<?php echo !empty( $the_link ) ? $the_link : get_permalink(); ?>">
                                            <?php echo !empty( $the_name ) ? $the_name : get_the_title(); ?>
                                        </a>
                                    </h4>
                                <?php
                                } ?>
                                    <div class="entry-format">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="post-format format-<?php echo $post_format; ?>"> </div>
                                        </a>
                                    </div>
                                </header>

                                <?php if( 'quote' !== $post_format && $show_info ) { ?>
                                <div class="entry-info">
                                    <div class="entry-date">
                                        <a href="<?php the_permalink(); ?>">
                                            <time datetime="<?php the_time('Y-m-d')?>" title="<?php the_time('Y-m-d')?>" ><?php the_time('F j, Y'); ?></time>
                                        </a>
                                    </div>
                                    <?php if ( !$show_author_footer ) { ?>
                                    <span class="meta-sep"><?php _e("by", 'phlox'); ?></span>
                                    <span class="author vcard">
                                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'phlox'), get_the_author() ); ?>" >
                                            <?php the_author(); ?>
                                        </a>
                                    </span>
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

                                <?php if( 'quote' !== $post_format && $show_excerpt ) { ?>
                                <div class="entry-content">
                                    <?php
                                    if( 'link' == $post_format ) {
                                        echo '<a href="'. $the_link .'" class="link-format-excerpt">' . $the_link . '</a>';

                                    } else {
                                        echo '<p>';
                                            auxin_the_trim_excerpt( null, (int) $excerpt_len, null, true );
                                        echo '</p>';

                                        // clear the floated elements at the end of content
                                        echo '<div class="clear"></div>';
                                    }
                                    ?>
                                </div>
                                <?php } ?>

                                <footer class="entry-meta">
                                    <?php if( $show_readmore && !$show_author_footer ) {?>
                                    <div class="readmore">
                                        <a href="<?php the_permalink(); ?>" class="aux-read-more"><?php _e("Read More", 'phlox'); ?></a>
                                    </div>
                                    <?php
                                    } elseif ( $show_author_footer ) { ?>
                                    <div class="author vcard">
                                        <?php echo get_avatar( get_the_author_meta("user_email"), 32 ); ?>
                                        <span class="meta-sep"><?php _e("by", 'phlox'); ?></span>
                                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'phlox'), get_the_author() ); ?>" >
                                            <?php the_author(); ?>
                                        </a>
                                    </div>
                                    <?php }

                                    if ( 'quote' !== $post_format && 'link' !== $post_format && $show_comments && comments_open() ) {
                                    ?>
                                    <div class="comments-iconic">
                                        <?php 
                                            if( $display_like ){
                                                if(function_exists('wp_ulike')) wp_ulike('get');
                                            } 
                                        ?>
                                        <a href="<?php the_permalink(); ?>#comments" class="meta-comment" ><span class="auxicon-comment"></span><span class="comments-number"><?php echo get_comments_number(); ?></span></a>
                                    </div>
                                    <?php } ?>
                                </footer>

                            </div>

                        </article>
