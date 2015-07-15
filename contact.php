<?php
/*
 * Template Name: Contact
 * */

get_header();

?>

<main id="main">
  <div class="clearfix">
    <div class="contact-container">
      <?php if (have_posts()) : while (have_posts()) : the_post();?>
      <?php the_content(); ?>
      <?php endwhile; endif; ?>
    </div>

    <div id="gallery" class="direct-link">
      <div class="gallery-inner clearfix">
        <?php home_print_portfolio_grid(); ?>
      </div>
    </div>
  </div>

<?php get_footer(); ?>
