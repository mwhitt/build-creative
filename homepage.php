<?php
/*
 * Template Name: Homepage
 * */

get_header();

?>

<script type="text/javascript">
  $ = jQuery;

  $(window).on("orientationchange",function(){
    setMobileHeaderHeight();
  });

  $( document ).ready(function() {
    setMobileHeaderHeight();
  });

  var setMobileHeaderHeight = function() {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      var viewportHeight = $(window).height();
      if (viewportHeight > 500) {
        $(".site-header").css('height', viewportHeight + "px");
      } else {
        $(".site-header").css('height', 650 + "px");
      };
    }
  }
</script>

<main id="main">

  <div id="gallery" class="direct-link">
    <div class="gallery-inner clearfix">
      <?php home_print_portfolio_grid(); ?>
    </div>
  </div>

<?php get_footer(); ?>
