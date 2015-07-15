<?php get_header(); ?>

<main id="main">

  <?php
  // Should the grid items link directly to the permalink page?
  $direct_link  = get_theme_mod( 'link_portfolio_items' );
  $term = get_queried_object()->term_id;
  ?>

  <div id="gallery" <?php if ( $direct_link ) { echo 'class="direct-link"'; } ?>>
    <div class="gallery-inner clearfix">
      <?php frame_print_portfolio_grid( false, false, $term ); ?>
    </div>
  </div>

  <?php if ( ! $direct_link ) : ?>
  <div id="permalinks">
    <?php frame_print_portfolio_permalinks( false, false, $term ); ?>
  </div>
  <?php endif; ?>

<?php get_footer(); ?>
