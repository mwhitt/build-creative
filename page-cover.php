<?php
/*
 * Template Name: Cover Page
 *
 * @package Frame
 */

$display_tagline = get_theme_mod( 'tagline_toggle');

$num_posts       = get_theme_mod( 'portfolios_per_cover_page', 1 );

get_header();

?>

<div id="cover-content" class="">
  <header>
    <h2 class="site-title"><?php bloginfo( 'name' ); ?></h2>
  <?php if( $display_tagline ) { ?>
    <h2 class="site-description <?php if ( ! empty( $logo_image ) ) echo 'centered'; ?>"><?php bloginfo( 'description' ); ?></h3>
  <?php } ?>
  </header>
  <a href="#" class="btn-scroll"><i class="icon-angle-left-thin"></i></a>
</div>

<main id="main" data-number-posts="<?php echo esc_attr( $num_posts ); ?>">
  <?php if ( $num_posts > 0 ) : ?>
  <div id="gallery">
    <?php
    $args = array(
      'post_type'      => 'portfolio',
      'orderby'        => 'menu_order',
      'order'          => 'ASC',
      'posts_per_page' => $num_posts,
      'meta_key'       => '_zilla_frame_featured_portfolio',
      'meta_value'     => 1
    );

    // Set up the custom query
    $wp_query = new WP_Query($args);

    // If there's no featured posts, use the newest one instead
    if ( ! $wp_query->have_posts() ) {
      unset( $args['meta_value'], $args['meta_key']);
      $wp_query = new WP_Query($args);
    }

    if ( $wp_query->have_posts() ) :
      while ( $wp_query->have_posts() ) :
        $wp_query->the_post();

        $has_gallery = ( get_post_meta( $post->ID, '_tzp_gallery_images_ids', true ) );
        $is_slideshow = ( get_post_meta( $post->ID, '_zilla_frame_gallery_layout', true ) === 'slideshow' );
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php if ( $has_gallery && $is_slideshow ) : ?>
            <?php zilla_post_gallery( $post->ID ); ?>
          <?php else : ?>
            <figure class="featured-image">
              <?php frame_post_thumbnail( $post->ID ); ?>
            </figure>
          <?php endif; ?>

          <?php echo frame_print_audio_html( $post->ID ); ?>

          <section class="post-bottom">
            <a href="<?php echo get_permalink() ?>"><h1 class="post-title"><?php the_title(); ?></h1></a>
            <div class="post-summary post-content">
              <?php the_content( __( 'Continue Reading', 'frame' ) ); ?>
            </div><!-- .post-summary -->
          </section>
        </article>

      <?php
      endwhile;
    endif;
    ?>
  </div><!-- #gallery -->
  <?php endif; ?>

  <section id="cover-widgets" class="cover-widgets">
    <?php wp_nav_menu( array(
      'depth' => 1,
      'theme_location' => 'cover',
      'fallback_cb' => 'false'
    ) ); ?>
  </section>

<?php get_footer(); ?>
