<?php
    $s_content = get_the_content();
    if(!empty($s_content)) {
?>
                                    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" >

                                        <section class="entry-content clearfix">
                                            <?php the_content(); ?>
                                        </section> <!-- end article section -->

                                    </article> <!-- end article -->
<?php } ?>
