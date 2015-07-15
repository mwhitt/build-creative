<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Frame
 */

get_header(); ?>

<div id="main">
  <div id="posts">

    <?php
    while ( have_posts() ) : the_post();

      get_template_part( 'content' );

    endwhile;

    frame_content_nav( 'nav-below' ); ?>

  </div><!-- #posts -->

  <?php get_template_part( 'sidebar' ); ?>

  <?php get_footer(); ?>
