<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Frame
 */
?>

<?php if ( is_dynamic_sidebar() ) { ?>
  <?php zilla_sidebar_before(); ?>
  <div id="sidebar" class="clearfix">
    <?php zilla_sidebar_start(); ?>

    <?php do_action( 'before_sidebar' ); ?>
    <?php dynamic_sidebar(); ?>

    <?php zilla_sidebar_end(); ?>
  </div>
  <?php zilla_sidebar_after(); ?>
<?php } ?>
