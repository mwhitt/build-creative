<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Frame
 */

if ( ! function_exists( 'frame_page_menu_args' ) ) :
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function frame_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
endif;
add_filter( 'wp_page_menu_args', 'frame_page_menu_args' );


if ( ! function_exists( 'frame_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 */
function frame_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }

  if ( is_page_template( 'portfolio.php' ) || is_page_template( 'page-cover.php' ) || is_singular( 'portfolio' ) || is_tax( 'portfolio-type' ) ) {
    $classes[] = 'portfolio';

    if ( get_theme_mod( 'gapless_portfolio' ) ) {
      $classes[] = 'gapless-portfolio';
    }

    if ( ! get_terms( 'portfolio-type' ) ) {
      $classes[] = 'no-filters';
    }
  }

  if ( is_page_template( 'page-cover.php' ) ) {
    $classes[] = get_theme_mod( 'portfolios_per_cover_page', 1 ) ? 'has-scroll' : 'static';
    $classes[] = get_theme_mod( 'custom_sidebar_background' ) ? 'has-bg' : '';
  }

  return $classes;
}
endif;
add_filter( 'body_class', 'frame_body_classes' );


if ( ! function_exists( 'frame_custom_taxonomy_post_class' ) ) :
/**
 * Add Custom Taxonomy Terms To The Post Class
 *
 * Mimics the core behaviour from WP 4.2+
 */
function frame_custom_taxonomy_post_class( $classes ) {
  global $post;

  $terms = get_the_terms( $post->ID, 'portfolio-type' );

  if ( ! empty( $terms ) ) {
      foreach ( $terms as $order => $term ) {
          $prefixed_term = $term->taxonomy . '-' . $term->slug;
          if ( ! in_array( $prefixed_term, $classes ) ) {
              $classes[] = $prefixed_term;
          }
      }
  }

  return $classes;
}
endif;
add_filter( 'post_class', 'frame_custom_taxonomy_post_class', 10, 3 );


if ( ! function_exists( 'frame_enhanced_image_navigation' ) ) :
/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function frame_enhanced_image_navigation( $url, $id ) {
  if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
    return $url;

  $image = get_post( $id );
  if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
    $url .= '#main';

  return $url;
}
endif;
add_filter( 'attachment_link', 'frame_enhanced_image_navigation', 10, 2 );


if ( ! function_exists( 'frame_wp_title' ) ) :
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function frame_wp_title( $title, $sep ) {
  global $page, $paged;

  if ( is_feed() )
    return $title;

  // Add the blog name
  $title .= get_bloginfo( 'name' );

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) )
    $title .= " $sep $site_description";

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 )
    $title .= " $sep " . sprintf( __( 'Page %s', 'frame' ), max( $paged, $page ) );

  return $title;
}
endif;
add_filter( 'wp_title', 'frame_wp_title', 10, 2 );


if ( ! function_exists( 'tzp_metabox_sanitize_select' ) ) :
/**
 * Sanitize Select fields before saving
 * @param  string $field The select value to be sanitized
 * @return string $field Sanitized select value string
 */
function tzp_metabox_sanitize_select( $field ) {
  return sanitize_text_field( $field );
}
endif;
add_filter( 'tzp_metabox_save_select', 'tzp_metabox_sanitize_select' );
