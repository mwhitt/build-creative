<?php
/**
 * Frame functions and definitions
 *
 * @package Frame
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 700; /* pixels */

if ( ! function_exists( 'frame_setup' ) ) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which runs
   * before the init hook. The init hook is too late for some features, such as indicating
   * support post thumbnails.
   */
  function frame_setup() {

    load_theme_textdomain( 'frame', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );

    /**
     * Enable support for Post Thumbnails on posts and pages
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );

    if ( function_exists( 'add_image_size' ) ) {
      add_image_size( 'portfolio', 680, 9999 );
      add_image_size( 'xxl-thumb', 2560, 9999 );
      add_image_size( 'xl-thumb', 1920, 9999 );
      add_image_size( 'l-thumb', 1440, 9999 );
      add_image_size( 'm-thumb', 960, 9999 );
      add_image_size( 's-thumb', 480, 9999 );
    }

    /**
     * This theme uses wp_nav_menu() in two locations.
     */
    register_nav_menus( array(
      'primary' => __( 'Primary Menu', 'frame' ),
      'cover' => __( 'Cover Page Menu', 'frame' ),
    ) );

    // Add post formats support
    add_theme_support( 'post-formats', array( 'audio', 'gallery', 'image', 'link', 'quote', 'video' ) );

    add_theme_support( 'custom-background', apply_filters( 'frame_custom_background_args', array(
      'default-color' => 'ffffff',
      'default-image' => '',
   ) ) );
  }
endif; // frame_setup
add_action( 'after_setup_theme', 'frame_setup' );


if ( ! function_exists( 'frame_widgets_init' ) ) :
/**
 * Register widgetized area and update sidebar with default widgets
 */
function frame_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Sidebar', 'frame' ),
    'id'            => 'sidebar-1',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );
}
endif;
add_action( 'widgets_init', 'frame_widgets_init' );


if ( ! function_exists( 'frame_remove_share' ) ) :
/**
 * Register widgetized area and update sidebar with default widgets
 */
function frame_remove_share() {
  if ( function_exists( 'sharing_display' ) ) {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
  }
  if ( class_exists( 'Jetpack_Likes' ) ) {
    remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
  }
}
endif;
add_action( 'loop_start', 'frame_remove_share' );


if ( ! function_exists( 'frame_write_css' ) ) :
function frame_write_css() {
  wp_enqueue_style( 'frame-style', get_stylesheet_uri() );

  $color = get_background_color();

  $custom_css = "
    .portfolio-filters ,
    .portfolio-filters .filter-toggle {
      background: #{$color};
    }
  ";

  wp_add_inline_style( 'frame-style', $custom_css );
}
endif;
add_action( 'wp_enqueue_scripts', 'frame_write_css' );


if ( ! function_exists( 'frame_scripts' ) ) :
/**
 * Enqueue scripts and styles
 */
function frame_scripts() {

  wp_register_script( 'modernizr', get_template_directory_uri() . '/scripts/modernizr.custom.58600.js', array(), '', false );
  wp_enqueue_script( 'modernizr' );

  if ( is_page_template( 'page-cover.php' ) || is_page_template( 'portfolio.php' ) ) {
    wp_enqueue_script( 'TweenMax', get_template_directory_uri() . '/scripts/TweenMax.min.js', 'jquery', '1.16.1', true );
    wp_enqueue_script( 'ScrollMagic', get_template_directory_uri() . '/scripts/ScrollMagic.min.js', 'jquery', '2.0.3', true );
    wp_enqueue_script( 'ScrollMagicGSAP', get_template_directory_uri() . '/scripts/animation.gsap.js', 'jquery', '2.0.3', true );
  }

  if ( is_page_template( 'portfolio.php' ) || is_tax( 'portfolio-type' ) ) {
    wp_enqueue_script( 'imagesLoaded', get_template_directory_uri() . '/scripts/imagesloaded.pkgd.min.js', 'jquery', '3.1.8', true );
    wp_enqueue_script( 'isotope', get_template_directory_uri() . '/scripts/isotope.pkgd.min.js', 'jquery', '2.2.0', true );
  }

  if ( is_page_template( 'page-cover.php' ) || is_page_template( 'portfolio.php' ) || is_tax( 'portfolio-type' ) ) {
    wp_enqueue_script( 'pace', get_template_directory_uri() . '/scripts/pace.min.js', '', '1.0.0', false );
  }

  wp_register_script( 'objectFit', get_template_directory_uri() . '/scripts/polyfill.object-fit.min.js', '', '0.4.1', true );

  wp_enqueue_style( 'frame-style', get_stylesheet_uri() );

  wp_enqueue_script( 'frame-navigation', get_template_directory_uri() . '/scripts/navigation.js', array(), '20120206', true );

  wp_enqueue_script( 'frame-skip-link-focus-fix', get_template_directory_uri() . '/scripts/skip-link-focus-fix.js', array(), '20130115', true );

  wp_enqueue_script( 'pictureFill', get_template_directory_uri() . '/scripts/picturefill.min.js', '', '2.3.1', true );

  wp_enqueue_script( 'cycle2', get_template_directory_uri() . '/scripts/jquery.cycle2.min.js', 'jquery', '2.1.6', true );

  wp_register_script( 'frame-tz', get_template_directory_uri() . '/scripts/_tz.js', array( 'jquery' ), '', true );
  wp_enqueue_script( 'frame-tz' );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

  if ( is_singular() && wp_attachment_is_image() ) {
    wp_enqueue_script( 'studio-keyboard-image-navigation', get_template_directory_uri() . '/javascripts/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
  }

  /* Add localization to use theme vars in our JS --- */
  wp_localize_script( 'frame-tz', 'zillaFrame', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' )
  ) );
}
endif;
add_action( 'wp_enqueue_scripts', 'frame_scripts' );


if ( ! function_exists( 'frame_enqueue_admin_scripts' ) ) :
/**
 * Enqueues scripts for back end
 *
 * @since Frame 1.0
 */
function frame_enqueue_admin_scripts() {
  wp_register_script( 'zilla-admin', get_template_directory_uri() . '/scripts/admin/jquery.custom.admin.js', 'jquery' );
  wp_enqueue_script( 'zilla-admin' );
}
endif;
add_action( 'admin_enqueue_scripts', 'frame_enqueue_admin_scripts' );


if ( ! function_exists( 'frame_add_async_to_picturefill' ) ) :
/**
 * Appends async attribute to script
 *
 * @since 1.0
 *
 * @return the script url with the async attribute appended to it
 */
function frame_add_async_to_picturefill( $url ) {
  if ( FALSE === strpos( $url, 'picturefill' ) ) { // bail - not our file
    return $url;
  }
  // Must be a ', not "!
  return "$url' async='async";
}
add_filter( 'clean_url', 'frame_add_async_to_picturefill', 11, 1 );
endif;


if ( ! function_exists( 'frame_url_of_registered_script' ) ) :
/**
 * Get a registered script src from its handle
 *
 * @since 1.0
 *
 * @return the src of the script
 */
function frame_url_of_registered_script( $handle ) {
  global $wp_scripts;

  // Create an array of registered scripts - handle:src
  foreach ( $wp_scripts->registered as $registered ) {
    $script_urls[ $registered->handle ] = $registered->src;
  }

  // If the target handle is in the array, return its src
  if ( ! empty( $script_urls[ $handle ] ) ) {
    return $script_urls[ $handle ];
  }
}
endif;


if ( ! function_exists( 'frame_enqueue_objectfit' ) ) :
/**
 * Print Modernizr.load for objectFit polyfill
 *
 * @since 1.0
 */
function frame_enqueue_objectfit() {
  if ( frame_url_of_registered_script( 'objectFit' ) ) :
    printf(
      '<script>
      Modernizr.load({
        test: Modernizr.objectFit,
        nope: "%1$s"
      });
      </script>',
      frame_url_of_registered_script( 'objectFit' )
    );
  endif;
}
add_action( 'wp_footer', 'frame_enqueue_objectfit', 10 );
endif;


if ( ! function_exists( 'frame_full_image_shortcode_init' ) ) :
// Add full sized image shortcode
function frame_full_image_shortcode_init( $atts, $content = null ) {
  $return_string = '<div class="full-image">'.$content.'</div>';
  return $return_string;
}
endif;
add_shortcode( 'full-image', 'frame_full_image_shortcode_init' );


if ( ! function_exists( 'frame_new_excerpt_more' ) ) :
function frame_new_excerpt_more( $more ) {
  return '...';
}
endif;
add_filter( 'excerpt_more', 'frame_new_excerpt_more' );


if ( ! function_exists( 'frame_custom_excerpt_length' ) ) :
function frame_custom_excerpt_length( $length ) {
  return 22;
}
endif;
add_filter( 'excerpt_length', 'frame_custom_excerpt_length', 999 );


if ( ! function_exists( 'frame_google_fonts' ) ) :
/**
 * Register Google Fonts
 */
function frame_google_fonts() {
  $protocol = is_ssl() ? 'https' : 'http';

  /* httptranslators: If there are characters in your language that are not supported
    by any of the following fonts, translate this to 'off'. Do not translate into your own language. */

  if ( 'off' !== _x( 'on', 'Karla font: on or off', 'frame' ) ) {
    wp_register_style( 'frame-karla', "$protocol://fonts.googleapis.com/css?family=Karla:400,400italic,700,700italic" );
  }

  wp_enqueue_style( 'frame-karla' );
}
endif;
add_action( 'init', 'frame_google_fonts' );


/**
 * Load Theme Components
 */
require get_template_directory() . '/includes/init.php';

/**
 * Load Zillaframework
 */
require get_template_directory() . '/framework/init.php';


// CUSTOM CONSTANTS FOR PORTFOLIO ZILLA
if( !defined('TZP_SLUG') ) define('TZP_SLUG', 'work');
