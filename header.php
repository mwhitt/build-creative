<?php
/**
 * The Header for our theme.
 *
 *
 * @package Frame
 */
?><!DOCTYPE html>
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="ie9 lt-ie10 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php zilla_meta_head(); ?>
  <title><?php wp_title( '|', true, 'right' ); ?></title>
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

  <?php wp_head(); ?>
  <?php zilla_head(); ?>
</head>
<?php $has_sidebar = 'has-sidebar';
if ( is_singular( 'portfolio' ) || is_page_template( 'portfolio.php' ) || is_page_template( 'page-cover.php' ) || is_tax( 'portfolio-type' ) || ! is_dynamic_sidebar() ) :
  $has_sidebar = '';
endif ?>

<body <?php body_class( $has_sidebar ); ?>>
<?php zilla_body_start(); ?>

<div id="page">
  <?php do_action( 'before' ); ?>
  <?php $header_image    = get_theme_mod( 'custom_sidebar_background' ); ?>
  <?php $sidebar_opacity = get_theme_mod( 'sidebar_opacity' ); ?>
  <?php $logo_image      = get_theme_mod( 'custom_logo_image' ); ?>
  <?php $display_tagline = get_theme_mod('tagline_toggle'); ?>

  <?php zilla_header_before(); ?>
  <header class="site-header <?php if( ! empty( $header_image ) ) { echo 'inverted'; } ?>" style="background-image:url('<?php echo esc_attr( $header_image ); ?>');">
    <div class="site-header-dimmer <?php echo esc_attr( $sidebar_opacity ); ?>" <?php if( empty( $header_image ) ) { echo 'style="background-color:transparent;"'; } ?>>
      <?php zilla_header_start(); ?>
      <div class="site-header-inner clearfix">

        <?php zilla_nav_before(); ?>
        <nav class="primary-navigation expanded">
          <?php wp_nav_menu( array(
              'depth' => 3,
              'theme_location' => 'primary',
          ) ); ?>
        </nav>
        <?php zilla_nav_after(); ?>

        <div class="site-branding">
          <h1 class="site-title">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
              <?php if( ! empty( $logo_image ) ) { ?>
                <img class="logo-image" src="<?php echo esc_attr( $logo_image ) ?>" alt="<?php bloginfo( 'name' ) ?>">
              <?php } else { bloginfo( 'name' ); } ?>
            </a>
          </h1>
          <?php if ( $display_tagline ) { ?>
            <h2 class="site-description <?php if( ! empty( $logo_image ) ) echo 'centered'?>"><?php bloginfo( 'description' ); ?></h2>
          <?php } ?>
        </div><!-- .site-branding -->

        <?php $social_icons = array(
          'bandcamp',
          'behance',
          'delicious',
          'deviantart',
          'digg',
          'dribbble',
          'etsy',
          'facebook',
          'flickr',
          'foursquare',
          'github',
          'google-plus',
          'instagram',
          'lastfm',
          'linkedin',
          'myspace',
          'pinboard',
          'pinterest',
          'rdio',
          'soundcloud',
          'spotify',
          'steam',
          'stumbleupon',
          'svpply',
          'twitter',
          'vimeo',
          'youtube',
          );

          $iconCount = 0;
          if ( get_theme_mod( 'skype' ) ) { $iconCount++; }
          foreach ( $social_icons as $icon ) {
            if ( get_theme_mod( $icon ) ) { $iconCount++; }
          }

          if ( $iconCount > 0 || get_theme_mod( 'email' ) != '' || get_theme_mod( 'phone' ) != '' ) : ?>

          <div class="contact">

            <?php if ( $iconCount > 0 ) : ?>
            <div class="social-icons clearfix">
              <?php if ( get_theme_mod( 'skype' ) ) : ?>
                <a class="skype" href="<?php echo esc_url( '"skype:" . get_theme_mod("skype")' ); ?>?userinfo" target="_blank"><?php _e( 'Skype', 'frame' ); ?></a>
              <?php endif;
              foreach ( $social_icons as $icon ) :
                if ( get_theme_mod( $icon ) ) : ?>
                  <a class="<?php echo esc_html( $icon ); ?>" href="<?php echo esc_url( get_theme_mod( $icon ) ); ?>" target="_blank"><?php echo esc_html( $icon ); ?></a>
                <?php endif;
              endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ( get_theme_mod( 'email' ) != '' ) : ?>
              <div class="contact-field email">
                <span class="contact-field-label"><?php _e( 'Email:', 'frame' ); ?></span> <a class="email-link <?php if( ! empty( $header_image ) ) { echo 'inverted'; } ?>" href="mailto:<?php echo sanitize_email( get_theme_mod('email') ); ?>"><?php echo esc_html( get_theme_mod('email') ); ?></a>
              </div>
            <?php endif; ?>

            <?php if ( get_theme_mod( 'phone' ) != '' ) : ?>
              <div class="contact-field phone">
                <span class="contact-field-label"><?php _e( 'Phone:', 'frame' ); ?></span> <?php echo esc_html( get_theme_mod('phone') ); ?>
              </div>
            <?php endif; ?>

            </div>

            <?php endif; ?>

      </div>
      <?php zilla_header_end(); ?>
    </div><!-- .site-header-dimmer -->
  </header>
  <?php zilla_header_after(); ?>

  <div id="content" class="site-content <?php if ( frame_portfolio_post_is_active( $post->ID ) && is_single() ) : ?>portfolio<?php endif; ?>">
  <?php zilla_content_start(); ?>
