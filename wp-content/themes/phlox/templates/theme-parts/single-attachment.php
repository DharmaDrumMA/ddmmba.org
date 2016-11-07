
       <?php while ( have_posts() ) : the_post(); ?>

       <?php locate_template('templates/theme-parts/entry/single-attachment.php', true ); ?>

       <?php endwhile; // end of the loop. ?>
