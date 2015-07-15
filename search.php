<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Frame
 */

get_header(); ?>

<div id="main" class="has-sidebar">

  <?php if ( have_posts() ) : ?>

    <header class="page-header">
      <h1 class="page-title"><?php printf( __( 'Search results matching: %s', 'frame' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    </header>

    <div id="posts">

      <?php while ( have_posts() ) : the_post();

        get_template_part( 'content', 'archive' );

      endwhile;

      frame_content_nav( 'nav-below' ); ?>

    </div>

  <?php else :

    get_template_part( 'no-results', 'search' );

  endif;

  get_sidebar();

  get_footer(); ?>
