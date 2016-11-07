<?php $is_pass_protected = post_password_required(); ?>

                <?php if ( ! $is_pass_protected ) : ?>

                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <section class="entry-content clearfix" >

                                <?php the_content(); ?>

                            </section> <!-- end article section -->

                            <div class="clear"></div>

                            <footer>
                                <?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags", 'phlox') . ':</span> ', ', ', '</p>'); ?>
                            </footer> <!-- end article footer -->

                        </article> <!-- end article -->


                        <?php endwhile; ?>

                        <div class="clear"></div>

                        <?php else : ?>

                        <article id="post-not-found">
                            <header>
                                <h1><?php _e("Not Found", 'phlox'); ?></h1>
                            </header>

                            <section class="entry-content">
                                <p><?php _e("Sorry, but the requested resource was not found on this site.", 'phlox'); ?></p>
                            </section>

                            <footer>
                            </footer>
                        </article>

                        <?php endif; ?>

                        <div class="clear"></div>

                        <?php comments_template(); ?>

                <?php else : ?>

                    <?php echo get_the_password_form(); ?>

                <?php endif; ?>
