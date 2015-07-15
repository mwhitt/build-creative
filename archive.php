<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Frame
 */

get_header();

?>

<?php if ( have_posts() ) : ?>

<div id="main" class="has-sidebar">
  <header class="page-header">
    <h1 class="page-title">
      <?php
        if ( is_category() ) :
          single_cat_title();

        elseif ( is_tag() ) :
          single_tag_title();

        elseif ( is_author() ) :
          /* Queue the first post, that way we know
           * what author we're dealing with (if that is the case).
          */
          the_post();
          printf( __( 'Author: %s', 'frame' ), '<span class="vcard">' . get_the_author() . '</span>' );
          /* Since we called the_post() above, we need to
           * rewind the loop back to the beginning that way
           * we can run the loop properly, in full.
           */
          rewind_posts();

        elseif ( is_day() ) :
          printf( __( 'Day: %s', 'frame' ), '<span>' . get_the_date() . '</span>' );

        elseif ( is_month() ) :
          printf( __( 'Month: %s', 'frame' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

        elseif ( is_year() ) :
          printf( __( 'Year: %s', 'frame' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

        elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
          _e( 'Asides', 'frame' );

        elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
          _e( 'Images', 'frame');

        elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
          _e( 'Videos', 'frame' );

        elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
          _e( 'Quotes', 'frame' );

        elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
          _e( 'Links', 'frame' );

        else :
          _e( 'Archives', 'frame' );

        endif;
      ?>
    </h1>
  </header><!-- .page-header -->

  <div id="posts">

    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>

      <?php
        get_template_part( 'content', 'archive' );
      ?>

    <?php endwhile; ?>

    <?php frame_content_nav( 'nav-below' ); ?>

  </div><!-- #posts -->

<?php else : ?>

  <?php get_template_part( 'no-results', 'archive' ); ?>

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
