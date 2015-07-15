<?php
/*
 * Template Name: Homepage
 * */

get_header();

?>

<main id="main">

  <div id="gallery" class="direct-link">
    <div class="gallery-inner clearfix">
      <?php home_print_portfolio_grid(); ?>
    </div>
  </div>

<?php get_footer(); ?>
