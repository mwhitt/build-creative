<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Frame
 */

get_header(); ?>

<?php $is_portfolio_post = is_singular( 'portfolio' ); ?>

<div id="main" <?php if ( $is_portfolio_post ) echo 'class="portfolio-post"'; ?>>

    <?php while ( have_posts() ) : the_post();

      if ( $is_portfolio_post ) :
        get_template_part( 'content', 'portfolio' );
      else :
        get_template_part( 'content' );
      endif;

    endwhile; // end of the loop.

    frame_content_nav( 'nav-below' );

    if ( ! $is_portfolio_post )
      get_sidebar();

get_footer(); ?>
