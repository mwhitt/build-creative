<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Frame
 */
?>

      <?php $header_image = get_theme_mod( 'custom_sidebar_background' ); ?>
      <?php zilla_footer_before(); ?>
      <footer class="site-footer <?php if( ! empty( $header_image ) ) { echo 'inverted'; } ?>" role="contentinfo">
        <?php zilla_footer_start(); ?>
        <div class="site-info-wrap">
          <div class="site-info">
            <?php do_action( 'frame_credits' ); ?>
            The Build Creative <?php echo date('Y'); ?> <span class="sep"> | </span> SF &amp; NYC
          </div><!-- .site-info -->
        </div>
        <?php zilla_footer_end(); ?>
      </footer><!-- #colophon -->
      <?php zilla_footer_after(); ?>
    </div>

    <?php zilla_content_end(); ?>
  </div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
