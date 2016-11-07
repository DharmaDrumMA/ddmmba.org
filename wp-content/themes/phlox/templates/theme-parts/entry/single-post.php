<?php global $post;

    $post_vars = auxin_get_post_format_media( $post, array( 'request_from' => 'single' ) );
    extract( $post_vars );

    // Get the alignment of the title in page content
    $title_alignment_default = auxin_get_option( 'post_single_title_alignment', 'default' );
    $title_alignment         = auxin_get_post_meta( $post, 'page_content_title_alignment', $title_alignment_default );
    $title_alignment         = 'default' == $title_alignment ? '' : 'aux-text-align-' .$title_alignment;

    $post_extra_classes      = auxin_get_option( 'post_single_narrow_context' ) ? 'aux-narrow-context' : '';
?>
                                    <article <?php post_class( $post_extra_classes ); ?> >

                                            <?php if ( $has_attach ) : ?>
                                            <div class="entry-media">
                                                <?php echo $the_media; ?>
                                            </div>
                                            <?php endif; ?>

                                            <div class="entry-main">

                                                <header class="entry-header <?php echo $title_alignment; ?>">
                                                <?php
                                                if( $show_title ) {
                                                    if( 'quote' == $post_format ) { echo '<p class="quote-format-excerpt">'. $excerpt .'</p>'; } ?>

                                                    <h3 class="entry-title">
                                                        <?php
                                                        $post_title = !empty( $the_name ) ? $the_name : get_the_title();

                                                        if( ! empty( $the_link ) ){
                                                            echo '<cite><a href="'.$the_link.'" title="'.$post_title.'">'.$post_title.'</a></cite>';
                                                        } else {
                                                            echo $post_title;
                                                        }

                                                        if( "link" == $post_format ){ echo '<br/><cite><a href="'.$the_link.'" title="'.$post_title.'">'.$the_link.'</a></cite>'; }
                                                        ?>
                                                    </h3>
                                                <?php
                                                } ?>
                                                    <div class="entry-format">
                                                        <div class="post-format"> </div>
                                                    </div>
                                                </header>

                                                <div class="entry-info">
                                                    <div class="entry-date"><time datetime="<?php the_time('Y-m-d')?>" ><?php the_date(); ?></time></div>
                                                    <span class="meta-sep"><?php _e("by", 'phlox'); ?></span>
                                                    <span class="author vcard">
                                                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'phlox'), get_the_author() ); ?>" >
                                                            <?php the_author(); ?>
                                                        </a>
                                                    </span>
                                                    <span class="meta-sep"><?php _e("with", 'phlox'); ?></span>
                                                    <span class="meta-comment"><?php comments_number( __('No Comment', 'phlox'), __('One Comment', 'phlox'), __('% Comments', 'phlox') );?></span>
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
                                                    <?php
                                                        edit_post_link(__("Edit", 'phlox'), '<i> | </i>', '');

                                                        if( auxin_get_option( 'show_blog_post_like_button', 1 ) ){
                                                            if(function_exists('wp_ulike')) wp_ulike('get');
                                                        }
                                                    ?>
                                                </div>

                                                <div class="entry-content">
                                                    <?php if( 'quote' == $post_format ) {
                                                        echo $the_attach;
                                                    } else {
                                                        the_content( __( 'Continue reading', 'phlox') );
                                                        // clear the floated elements at the end of content
                                                        echo '<div class="clear"></div>';
                                                        // create pagination for page content
                                                        wp_link_pages( array( 'before' => '<div class="page-links"><span>' . __( 'Pages:', 'phlox') .'</span>', 'after' => '</div>' ) );
                                                    } ?>
                                                </div>

                                                <?php
                                                $show_share_links = auxin_get_option( 'blog_show_share_links', true );
                                                $the_tags         = get_the_tag_list('<span>'. __("Tags: ", 'phlox'). '</span>', '<i>, </i>', '');

                                                if( $show_share_links || $the_tags ){
                                                ?>
                                                <footer class="entry-meta">
                                                <?php
                                                    if( $show_share_links ){
                                                        echo '<div class="aux-post-share"><span>'. __("Share: ", 'phlox'). '</span>';
                                                        auxin_the_socials( array(
                                                            'css_class' => ' aux-post-socials',
                                                            'size'      => 'medium',
                                                            'direction' => 'horizontal',
                                                            'social_list'   => array(
                                                                'facebook'   => 'http://www.facebook.com/sharer.php?u=' . urlencode(get_permalink()).'&t='.urlencode(get_the_title()),
                                                                'twitter'    => 'http://www.twitter.com/share?url=' . urlencode(get_permalink()).'&t='.urlencode(get_the_title()),
                                                                'pinterest'  => 'https://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()).'&media='.urlencode(auxin_get_auto_post_thumbnail_src()).'&description='.urlencode(get_the_title()),
                                                                'googleplus' => 'https://plus.google.com/share?url=' . urlencode(get_permalink()),
                                                            ),

                                                            'social_list_type'   => 'site',
                                                            'fill_social_values' => false
                                                        ));
                                                        echo '</div>';
                                                    }
                                                    if( $the_tags ){ ?>
                                                        <div class="entry-tax">
                                                            <?php echo $the_tags; ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="entry-tax"><span><?php echo __("Tags: No tags", 'phlox')?></span></div>
                                                    <?php }?>
                                                </footer>
                                                <?php } ?>
                                            </div>


                                            <?php // get related posts
                                            auxin_single_page_navigation( array(
                                                'prev_text'      => __( 'Previous Post', 'phlox' ),
                                                'next_text'      => __( 'Next Post'    , 'phlox' ),
                                                'taxonomy'       => 'category',
                                                'skin'           => auxin_get_option( 'post_single_next_prev_nav_skin' ) // minimal, thumb-no-arrow, thumb-arrow, boxed-image
                                            ));

                                            if( auxin_get_option( 'show_blog_related_posts' ) ){
                                                $related_posts_title = auxin_get_option( 'blog_related_posts_title' );
                                                $related_posts_size  = auxin_get_option( 'blog_related_posts_size' );

                                                do_shortcode( '[related_items title="'.$related_posts_title.'" mode="none" posttype="post" tax="post_tag" num="6" col="'.$related_posts_size.'" ]' );
                                            }
                                            ?>




                                            <?php if( auxin_get_option( 'show_blog_author_section', 1 ) ) { ?>
                                            <div class="entry-author-info">
                                                    <div class="author-avatar">
                                                        <?php echo get_avatar( get_the_author_meta("user_email"), 100 ); ?>
                                                    </div><!-- #author-avatar -->
                                                    <div class="author-description">
                                                        <dl>
                                                            <dt>
                                                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'phlox'), get_the_author() ); ?>" >
                                                                    <?php the_author(); ?>
                                                                </a>
                                                            </dt>
                                                            <dd>
                                                            <?php if( get_the_author_meta('skills') ) { ?>
                                                                <span><?php the_author_meta('skills');?></span>
                                                            <?php }
                                                            if( auxin_get_option( 'show_blog_author_section_text' ) && ( get_the_author_meta('user_description') ) ) {
                                                                ?>
                                                                <p><?php the_author_meta('user_description');?>.</p>
                                                                <?php } ?>
                                                            </dd>
                                                        </dl>
                                                        <?php if( auxin_get_option( 'show_blog_author_section_social' ) ) {
                                                            auxin_the_socials( array(
                                                                'css_class' => ' aux-author-socials',
                                                                'size'      => 'medium',
                                                                'direction' => 'horizontal',
                                                                'social_list'   => array(
                                                                    'facebook'   => get_the_author_meta('facebook'),
                                                                    'twitter'    => get_the_author_meta('twitter'),
                                                                    'googleplus' => get_the_author_meta('googleplus'),
                                                                    'flickr'     => get_the_author_meta('flickr'),
                                                                    'dribbble'   => get_the_author_meta('dribbble'),
                                                                    'delicious'  => get_the_author_meta('delicious'),
                                                                    'pinterest'  => get_the_author_meta('pinterest'),
                                                                    'github'     => get_the_author_meta('github')
                                                                ),
                                                                'social_list_type'   => 'site',
                                                                'fill_social_values' => false
                                                            ));
                                                        }
                                                        ?>
                                                    </div><!-- #author-description -->

                                            </div> <!-- #entry-author-info -->
                                            <?php } ?>

                                       </article>
