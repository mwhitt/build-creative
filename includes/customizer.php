<?php
/**
 * Frame Theme Customizer
 *
 * @package Frame
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function frame_customize_preview_js() {
  wp_enqueue_script( 'frame_customizer', get_template_directory_uri() . '/scripts/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'frame_customize_preview_js' );

function frame_sanitize_checkbox( $value ) {
  if ( ! isset( $value ) || ! in_array( $value, array( true, false ) ) )
    $value = true;

  return $value;
}

function frame_sanitize_opacity( $value ) {
  if ( ! isset( $value ) || ! in_array( $value, array( 'zero', 'twenty', 'forty', 'sixty', 'eighty' ) ) )
    $value = 'zero';

  return $value;
}

function frame_sanitize_aspect( $value ) {
  if ( ! isset( $value ) || ! in_array( $value, array( 'square', 'portrait', 'landscape' ) ) )
    $value = 'landscape';

  return $value;
}

add_action( 'customize_register', 'frame_customize_register' );
function frame_customize_register( $wp_customize ) {

  $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
  $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

  $wp_customize->add_section( 'social_icons', array(
        'title' => __( 'Social Icons', 'frame' ),
        'description' => ( 'Link to your social media' ),
        'priority'   => 30
        ) );

  $wp_customize->add_setting( 'bandcamp', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'bandcamp', array(
          'label' => __( 'Bandcamp URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'bandcamp'
          ) ) );

  $wp_customize->add_setting( 'behance', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'behance', array(
          'label' => __( 'Behance URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'behance'
          ) ) );

  $wp_customize->add_setting( 'delicious', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'delicious', array(
          'label' => __( 'Delicious URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'delicious'
          ) ) );

  $wp_customize->add_setting( 'deviantart', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'deviantart', array(
          'label' => __( 'Deviantart URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'deviantart'
          ) ) );

  $wp_customize->add_setting( 'digg', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'digg', array(
          'label' => __( 'Digg URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'digg'
          ) ) );

  $wp_customize->add_setting( 'dribbble', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'dribbble', array(
          'label' => __( 'Dribbble URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'dribbble'
          ) ) );

  $wp_customize->add_setting( 'etsy', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'etsy', array(
          'label' => __( 'Etsy URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'etsy'
          ) ) );

  $wp_customize->add_setting( 'facebook', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'facebook', array(
          'label' => __( 'Facebook URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'facebook'
          ) ) );

  $wp_customize->add_setting( 'flickr', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'flickr', array(
          'label' => __( 'Flickr URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'flickr'
          ) ) );

  $wp_customize->add_setting( 'foursquare', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'foursquare', array(
          'label' => __( 'Foursquare URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'foursquare'
          ) ) );

  $wp_customize->add_setting( 'github', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'github', array(
          'label' => __( 'Github URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'github'
          ) ) );

  $wp_customize->add_setting( 'google-plus', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'google-plus', array(
          'label' => __( 'Google Plus URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'google-plus'
          ) ) );

  $wp_customize->add_setting( 'instagram', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'instagram', array(
          'label' => __( 'Instagram URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'instagram'
          ) ) );

  $wp_customize->add_setting( 'lastfm', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'lastfm', array(
          'label' => __( 'Lastfm URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'lastfm'
          ) ) );

  $wp_customize->add_setting( 'linkedin', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'linkedin', array(
          'label' => __( 'Linkedin URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'linkedin'
          ) ) );

  $wp_customize->add_setting( 'myspace', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'myspace', array(
          'label' => __( 'Myspace URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'myspace'
          ) ) );

  $wp_customize->add_setting( 'pinboard', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'pinboard', array(
          'label' => __( 'Pinboard URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'pinboard'
          ) ) );

  $wp_customize->add_setting( 'pinterest', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'pinterest', array(
          'label' => __( 'Pinterest URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'pinterest'
          ) ) );

  $wp_customize->add_setting( 'rdio', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'rdio', array(
          'label' => __( 'Rdio URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'rdio'
          ) ) );

  $wp_customize->add_setting( 'skype', array(
        'default' => '',
        'sanitize_callback' => 'esc_attr',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'skype', array(
          'label' => __( 'Skype Username', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'skype'
          ) ) );

  $wp_customize->add_setting( 'soundcloud', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'soundcloud', array(
          'label' => __( 'Soundcloud URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'soundcloud'
          ) ) );

  $wp_customize->add_setting( 'spotify', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'spotify', array(
          'label' => __( 'Spotify URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'spotify'
          ) ) );

  $wp_customize->add_setting( 'steam', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'steam', array(
          'label' => __( 'Steam URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'steam'
          ) ) );

  $wp_customize->add_setting( 'steam', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'steam', array(
          'label' => __( 'Steam URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'steam'
          ) ) );

  $wp_customize->add_setting( 'stumbleupon', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'stumbleupon', array(
          'label' => __( 'Stumbleupon URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'stumbleupon'
          ) ) );

  $wp_customize->add_setting( 'svpply', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'svpply', array(
          'label' => __( 'Svpply URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'svpply'
          ) ) );

  $wp_customize->add_setting( 'twitter', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'twitter', array(
          'label' => __( 'Twitter URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'twitter'
          ) ) );

  $wp_customize->add_setting( 'vimeo', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'vimeo', array(
          'label' => __( 'Vimeo URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'vimeo'
          ) ) );

  $wp_customize->add_setting( 'youtube', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'youtube', array(
          'label' => __( 'Youtube URL', 'frame' ),
          'section' => 'social_icons',
          'settings' => 'youtube'
          ) ) );

  $wp_customize->add_section( 'contact_info', array(
        'title' => __( 'Contact Information', 'frame' ),
        'description' => ( 'Put in your email and phone number' ),
        'priority'   => 30
        ) );

  $wp_customize->add_setting( 'email', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
        ) );
  $wp_customize->add_control( new WP_customize_control($wp_customize, 'email', array(
        'label' => __( 'Your email address', 'frame' ),
        'section' => 'contact_info',
        'settings' => 'email'
        ) ) );

  $wp_customize->add_setting( 'phone', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        ) );
  $wp_customize->add_control( new WP_customize_control($wp_customize, 'phone', array(
          'label' => __( 'Your phone number', 'frame' ),
          'section' => 'contact_info',
          'settings' => 'phone'
          ) ) );

  $wp_customize->add_section( 'sidebar_options' , array(
          'title' => __( 'Left Sidebar Options','frame' ),
          ) );

  $wp_customize->add_setting( 'custom_logo_image', array(
    'default'        => 'images/build_creative_logo.jpg',
    'sanitize_callback' => 'esc_url_raw',
    ) );

  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'custom_logo_image', array(
    'label'   => 'Custom logo',
    'section' => 'sidebar_options',
    'settings'   => 'custom_logo_image',
    ) ) );

  $wp_customize->add_setting('tagline_toggle', array(
    'default' => false,
  ));

  $wp_customize->add_control('enable_tagline', array(
    'settings' => 'tagline_toggle',
    'label'    => __('Display tagline', 'frame'),
    'section'  => 'title_tagline',
    'type'     => 'checkbox',
  ));

  $wp_customize->add_setting( 'custom_sidebar_background', array(
    'default'        => '',
    'sanitize_callback' => 'esc_url_raw',
    ) );

  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'custom_sidebar_background', array(
    'label'   => 'Sidebar Background',
    'section' => 'sidebar_options',
    'settings'   => 'custom_sidebar_background',
    ) ) );

  $wp_customize->add_setting( 'sidebar_opacity', array(
    'default'        => 'zero',
    'sanitize_callback' => 'frame_sanitize_opacity',
  ) );
  $wp_customize->add_control('sidebar_opacity', array(
          'label'      => __( 'Sidebar Darkness', 'Frame' ),
          'section'    => 'sidebar_options',
          'settings'   => 'sidebar_opacity',
          'type'       => 'select',
          'choices'    => array(
                  'zero'   => '0%',
                  'twenty' => '20%',
                  'forty'  => '40%',
                  'sixty'  => '60%',
                  'eighty' => '80%',
                  ),
          ) );

  $wp_customize->add_section( 'portfolio_options' , array(
    'title' => __( 'Portfolio Options','frame' ),
  ) );

  $wp_customize->add_setting( 'thumbnail_aspect', array(
    'default'        => 'landscape',
    'sanitize_callback' => 'frame_sanitize_aspect',
  ) );
  $wp_customize->add_control( 'thumbnail_aspect', array(
          'label'      => __( 'Thumbnail Aspect', 'frame' ),
          'section'    => 'portfolio_options',
          'settings'   => 'thumbnail_aspect',
          'type'       => 'select',
          'choices'    => array(
                  'square'   => 'Square',
                  'portrait'  => 'Portrait',
                  'landscape'  => 'Landscape',
                  ),
          ) );

  $wp_customize->add_setting( 'gapless_portfolio', array(
    'default' => false,
  ) );

  $wp_customize->add_control( 'show_gapless_portfolio', array(
    'settings' => 'gapless_portfolio',
    'label'    => __('Enable gapless portfolio style', 'frame'),
    'section'  => 'portfolio_options',
    'type'     => 'checkbox',
  ) );

  $wp_customize->add_setting( 'portfolio_pagination', array(
    'default' => false,
  ) );

  $wp_customize->add_control( 'show_portfolio_pagination', array(
    'settings' => 'portfolio_pagination',
    'label'    => __('Enable "load more" on portfolio pages', 'frame'),
    'section'  => 'portfolio_options',
    'type'     => 'checkbox',
  ) );

  $wp_customize->add_setting( 'link_portfolio_items', array(
    'default' => false,
  ) );

  $wp_customize->add_control( 'enable_link_portfolio_items', array(
    'settings' => 'link_portfolio_items',
    'label'    => __('Link portfolio items directly to permalink page', 'frame'),
    'section'  => 'portfolio_options',
    'type'     => 'checkbox',
  ) );

  $wp_customize->add_setting( 'portfolios_per_page', array(
      'default' => '12',
      'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'portfolios_per_page', array(
      'label' => __( 'Portfolio items per page', 'frame' ),
      'section' => 'portfolio_options',
      'settings' => 'portfolios_per_page'
  ) ) );

  $wp_customize->add_setting( 'portfolios_per_cover_page', array(
      'default' => '1',
      'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( new wp_customize_control( $wp_customize, 'portfolios_per_cover_page', array(
      'label' => __( 'Featured portfolio items on the cover page. Set to 0 for static cover page.', 'frame' ),
      'section' => 'portfolio_options',
      'settings' => 'portfolios_per_cover_page'
  ) ) );

  $wp_customize->add_section( 'general_options' , array(
      'title' => __( 'General Options','frame' ),
  ) );

  $wp_customize->add_setting( 'retina_images', array(
    'default' => false,
  ) );

  $wp_customize->add_control( 'disable_retina_images', array(
    'settings' => 'retina_images',
    'label'    => __( 'Disable HDPI images for HDPI / Retina displays.', 'frame' ),
    'section'  => 'general_options',
    'type'     => 'checkbox',
  ) );

}
