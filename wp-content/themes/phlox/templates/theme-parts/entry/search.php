<?php /* Finds and displays search results. */?>

                        <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" >

                            <?php
                                global $post;
                                $has_attach = has_post_thumbnail();
                                $the_attach = auxin_get_first_image_from_string( get_the_content() );

                                if( $has_attach = has_post_thumbnail() ){
                                    $size  = auxin_get_image_size('side');
                                    $the_attach = auxin_get_the_post_thumbnail( $post->ID, $size[0], $size[1], true );
                                } else {
                                    $the_attach = auxin_get_first_image_from_string( get_the_content() );
                                    $has_attach = ! empty( $the_attach );
                                }

                                if( $has_attach ) {
                            ?>
                            <div class="entry-media">
                                <div class="aux-media-frame aux-media-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo $the_attach; ?>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="entry-main">

                                <div class="entry-header">
                                    <h3 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                                </div>

                                <div class="entry-content">
                                    <?php the_excerpt(); ?>
                                </div>

                                <div class="entry-info">
                                    <div class="entry-date"><time datetime="<?php the_time('Y-m-d')?>" title="<?php the_time('Y-m-d')?>" ><?php the_date(); ?></time></div>

                                        <?php // the_category(' '); we can use this template tag, but customizable way is needed!
                                            $tax_name = 'category';
                                            if( $cat_terms = wp_get_post_terms( $post->ID, $tax_name ) ){
                                        ?>
                                            <span class="meta-sep"><?php _e("in", 'phlox'); ?></span>
                                            <span class="entry-tax">
                                        <?php
                                        foreach( $cat_terms as $term ){
                                            echo '<a href="'. get_term_link( $term->slug, $tax_name ) .'" title="'.__("View all posts in ", 'phlox'). $term->name .'" rel="category" >'. $term->name .'</a>';
                                        }
                                        ?>
                                            </span>
                                        <?php
                                            }
                                        ?>
                                </div>

                            </div><!-- end entry-main -->

                        </article> <!-- end article -->


                        <?php endwhile; ?>
