<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Frame
 */

get_header(); ?>

<main id="main" class="is-portfolio-permalink">

    <?php while ( have_posts() ) : the_post();

      get_template_part( 'content', 'portfolio' );

    endwhile; // end of the loop.

    frame_content_nav( 'nav-below' );

get_footer(); ?>
