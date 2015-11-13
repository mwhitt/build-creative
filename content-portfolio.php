<?php
/**
 * @package Frame
 */
?>

<?php

  // setup 'Filed under:'
  $portfolios = frame_portfolio_page_ids( $post->ID );
  $portfolioTitles = '';
  foreach ($portfolios as $index => $portfolioID) {
    $newTitle = "<a href='" . get_permalink($portfolioID) . "'>" . get_the_title($portfolioID) . "</a>";
    if( $index > 0 ) {
      $newTitle = ", $newTitle";
    }
    $portfolioTitles .= $newTitle;
  }

  $usevideo = get_post_meta($post->ID, 'frame_portfolio_featurevideo', true);

  $has_gallery = ( get_post_meta( $post->ID, '_tzp_gallery_images_ids', true ) );
  $is_slideshow = ( get_post_meta( $post->ID, '_zilla_frame_gallery_layout', true ) === 'slideshow' );

?>

<?php zilla_post_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-post clearfix' . ($usevideo == 1 ? ' use-video' : '') ); ?> <?php if ( is_single() ) echo 'style="display:block";'; echo $usevideo; ?>>

  <?php zilla_post_start(); ?>

  <header class="post-header">
    <h1 class="post-title">
      <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
    </h1>
  </header><!-- .post-header -->

  <section class="post-content">
    <?php if ( is_single() ) : ?>

      <div class="post-meta-wrap clearfix">
        <footer class="portfolio-meta">
          <?php frame_portfolio_meta( $post->ID ); ?>
        </footer>

        <?php frame_sharing_and_likes(); ?>
        <?php edit_post_link( __( 'Edit', 'frame' ), '<span class="edit-link">', '</span>' ); ?>
      </div>

      <div class="post-summary post-content">
        <?php $more = 0; ?>
        <?php the_content( __( 'Continue Reading', 'frame' ) ); ?>
      </div>


    <?php endif; ?>
  </section><!-- .post-content -->

  <section class="featured-media">
    <?php echo frame_print_video_html( $post->ID ); ?>

    <?php if ( $has_gallery && $is_slideshow ) : ?>
      <?php zilla_post_gallery( $post->ID ); ?>
    <?php endif; ?>

    <?php echo frame_print_audio_html( $post->ID ); ?>
  </section><!-- .featured-media -->

  <?php zilla_post_end(); ?>
</article>
<?php zilla_post_after(); ?>
