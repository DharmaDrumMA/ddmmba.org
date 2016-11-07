        <article class="media-land">
            <?php  if( $show_media == true && $entry_media = auxin_get_the_post_thumbnail( null, 160, 160, true ) ) { ?>
            <?php //if( $entry_media = auxin_get_the_post_thumbnail( null, 160, 160, true ) ) { ?>
            <div class="entry-media">
                <div class="aux-media-frame aux-media-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $entry_media; ?>
                    </a>
                </div>
            </div>
            <?php } if( $show_format ) { ?>
            <div class="entry-format">
                <a href="<?php the_permalink(); ?>" class="post-format format-<?php echo get_post_format(); ?>"></a>
            </div>
            <?php } ?>
            <div>
                <header class="entry-header">
                    <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo auxin_get_trimmed_string( get_the_title(), 40, '...'); ?></a></h4>
                </header>

                <div class="entry-content">
                    <?php if($show_date != false ) { ?>
                    <time datetime="<?php the_time('Y-m-d')?>" title="<?php the_time('Y-m-d')?>" ><?php the_time('F j, Y'); ?></time>
                    <?php } if( $show_excerpt != false ) { ?>
                    <p><?php auxin_the_trim_excerpt( null, (int) $excerpt_len, null, true ); ?></p>
                    <?php } ?>
                </div>
            </div>
        </article>
