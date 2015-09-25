<?php
# =================================== #
# ==== Responsive Featured Image ==== #
# =================================== #

if ( ! function_exists( 'frame_set_thumb_transient' ) ) :
/**
 *
 * @package Frame
 * @since Frame 1.0
 *
 * Create image transient to avoid looping through multiple image queries every time a post loads.
 * Called any time a post is saved or updated right after existing transient is flushed.
 * Called by frame_get_thumb_data when no transient is set.
 *
 * - Get the featured image ID
 * - Get the alt text (if no alt text is defined, set the alt text to the post title)
 * - Build an array with each of the available image sizes + the alt text
 * - Set a transient with the label "featured_image_[post_id] that expires in 12 months
 */
function frame_set_thumb_transient( $post_id ) {
  $attachment_id = get_post_thumbnail_id( $post_id );
  $alt_text      = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
  $title         = get_the_title( $attachment_id );
  $caption       = get_post_field( 'post_excerpt', $attachment_id );

  //add a default alt tag
  if ( ! $alt_text ) {
    $alt_text = esc_html( get_the_title( $post_id ) );
  }

  $thumb_original = wp_get_attachment_image_src( $attachment_id, 'full' );
  $thumb_xxl      = wp_get_attachment_image_src( $attachment_id, 'xxl-thumb' ); // 2560
  $thumb_xl       = wp_get_attachment_image_src( $attachment_id, 'xl-thumb' );  // 1920
  $thumb_l        = wp_get_attachment_image_src( $attachment_id, 'l-thumb' );   // 1440
  $thumb_m        = wp_get_attachment_image_src( $attachment_id, 'm-thumb' );   // 960
  $thumb_s        = wp_get_attachment_image_src( $attachment_id, 's-thumb' );   // 480

  $thumb_data = array(
    'thumb_original' => $thumb_original[0],
    'thumb_xxl'      => $thumb_xxl[0],
    'thumb_xl'       => $thumb_xl[0],
    'thumb_l'        => $thumb_l[0],
    'thumb_m'        => $thumb_m[0],
    'thumb_s'        => $thumb_s[0],
    'thumb_alt'      => $alt_text,
    'thumb_title'    => $title,
    'thumb_caption'  => $caption
  );

  set_transient( 'featured_image_' . $post_id, $thumb_data, 52 * WEEK_IN_SECONDS );
}
endif;


if ( ! function_exists( 'frame_reset_thumb_transient' ) ) :
/**
 *
 * Reset the thumbnail transient when a post is saved
 *
 * @package Frame
 * @since Frame 1.0
 */
function frame_reset_thumb_transient( $post_id ) {
  delete_transient( 'featured_image_' . $post_id );

  if ( has_post_thumbnail( $post_id ) ) {
    frame_set_thumb_transient( $post_id );
  }
}
endif;
add_action( 'save_post', 'frame_reset_thumb_transient' );


if ( ! function_exists( 'frame_get_thumb_data' ) ) :
/**
 *
 * Get the post thumbnail data.
 *
 * @package Frame
 * @since Frame 1.0
 *
 * @uses: frame_set_image_transient()
 *
 * @return array - the responsive image urls and alt tag
 */
function frame_get_thumb_data( $post_id ) {
  // Check to see if there is a transient available.
  // If there is, use it, otherwise set a new transient and use it.
  if ( false === ( $thumb_data = get_transient( 'featured_image_' . $post_id ) ) ) {
    frame_set_thumb_transient( $post_id );
    $thumb_data = get_transient( 'featured_image_' . $post_id );
  }

  return $thumb_data;
}
endif;


if ( ! function_exists( 'frame_responsive_image' ) ) :
/**
 * Print the HTML for a responsive featured image
 *
 * @param $thumb_data - array: image attributes
 * @param $thumb_type - string: 'full' or 'thumb'
 *
 * @package Frame
 * @since Frame 1.0
 */
function frame_responsive_image( $thumb_data, $thumb_type = 'full' ) {
  $disable_retina = get_theme_mod('retina_images');

  // Unless disable retina is true, use retina types
  if ( ! $disable_retina && $thumb_type === 'full' ) {
    $thumb_type = 'full-retina';
  } elseif ( ! $disable_retina && $thumb_type === 'thumb' ) {
    $thumb_type = 'thumb-retina';
  }

  // Set up different srcset combinations
  switch ( $thumb_type ) {
    case 'thumb':
      $thumb_src = sprintf(
        '<source srcset="%1$s" media="(min-width: 480px)">',
        $thumb_data['thumb_m']
      );
      break;
    case 'thumb-retina':
      $thumb_src = sprintf(
        '<source srcset="%2$s, %1$s 2x" media="(min-width: 480px)">',
        $thumb_data['thumb_l'],
        $thumb_data['thumb_m']
      );
      break;
    case 'full':
      $thumb_src = sprintf(
        '<source srcset="%1$s" media="(min-width: 1921px)">
        <source srcset="%2$s" media="(min-width: 1440px)">
        <source srcset="%3$s" media="(min-width: 960px)">
        <source srcset="%4$s" media="(min-width: 480px)">',
        $thumb_data['thumb_xxl'],
        $thumb_data['thumb_xl'],
        $thumb_data['thumb_l'],
        $thumb_data['thumb_m']
      );
      break;
    case 'full-retina':
    default:
      $thumb_src = sprintf(
        '<source srcset="%1$s" media="(min-width: 1921px)">
        <source srcset="%2$s, %1$s 2x" media="(min-width: 1440px)">
        <source srcset="%3$s, %2$s 2x" media="(min-width: 960px)">
        <source srcset="%4$s, %3$s 2x" media="(min-width: 480px)">',
        $thumb_data['thumb_xxl'],
        $thumb_data['thumb_xl'],
        $thumb_data['thumb_l'],
        $thumb_data['thumb_m']
      );
      break;
  }

  // Return the responsive image
  // <video> as fallback for IE9: http://scottjehl.github.io/picturefill/
  return sprintf(
    '<picture class="thumb-responsive">
      <!--[if IE 9]><video style="display: none;"><![endif]-->
      %1$s
      <source srcset="%2$s">
      <!--[if IE 9]></video><![endif]-->
      <img srcset="%2$s" alt="%3$s" data-title="%4$s" data-desc="%5$s">
    </picture>',
    $thumb_src,
    $thumb_data['thumb_m'],
    $thumb_data['thumb_alt'],
    $thumb_data['thumb_title'],
    $thumb_data['thumb_caption']
  );
}
endif;

# ============================================ #
# ==== Responsive Zilla Portfolio Gallery ==== #
# ============================================ #

if ( ! function_exists( 'frame_set_gallery_transient' ) ) :
/**
 *
 * @package Frame
 * @since Frame 1.0
 *
 * Create transient of gallery images to avoid looping through multiple image queries every time a post loads.
 * Called any time a gallery is saved or a post is saved / updated right after existing transient is flushed.
 * Called by frame_get_gallery_data when no transient is set.
 *
 * - Get the attachment image IDs
 * - Get the alt text (if no alt text is defined, set the alt text to the post title)
 * - Build a multidimensional array with each of the available image sizes + the alt text
 * - Set a transient with the label "image_gallery_[post_id] that expires in 12 months
 */
function frame_set_gallery_transient( $post_id ) {
  // Get a list of gallery image IDs
  $image_ids_raw = ( 'post' == get_post_type( $post_id ) )
    ? get_post_meta( $post_id, '_zilla_image_ids', true )
    : get_post_meta( $post_id, '_tzp_gallery_images_ids', true );

  $image_ids = explode( ',', $image_ids_raw );
  $attachment_data = array();

  // get all the gallery images
  $args = array(
    'include'        => $image_ids,
    'numberposts'    => -1,
    'orderby'        => 'post__in',
    'order'          => 'ASC',
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
  );
  $attachments = get_posts( $args );

  foreach ( $attachments as $attachment ) {
    // fetch each attachment's meta data
    $alt_text = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
    // default alt tag if alt not set
    if ( ! $alt_text ) {
      $alt_text = esc_html( get_the_title( $post_id ) );
    }

    $title          = $attachment->post_title;
    $caption        = $attachment->post_excerpt;
    $thumb_original = wp_get_attachment_image_src( $attachment->ID, 'full' );
    $thumb_xxl      = wp_get_attachment_image_src( $attachment->ID, 'xxl-thumb' );   // 2560
    $thumb_xl       = wp_get_attachment_image_src( $attachment->ID, 'xl-thumb' );    // 1920
    $thumb_l        = wp_get_attachment_image_src( $attachment->ID, 'l-thumb' );     // 1260
    $thumb_m        = wp_get_attachment_image_src( $attachment->ID, 'm-thumb' );     // 960
    $thumb_s        = wp_get_attachment_image_src( $attachment->ID, 's-thumb' );     // 400

    // create an array of the attachment data
    $thumb_data = array(
      'thumb_original' => $thumb_original[0],
      'thumb_xxl'      => $thumb_xxl[0],
      'thumb_xl'       => $thumb_xl[0],
      'thumb_l'        => $thumb_l[0],
      'thumb_m'        => $thumb_m[0],
      'thumb_s'        => $thumb_s[0],
      'thumb_alt'      => $alt_text,
      'thumb_title'    => $title,
      'thumb_caption'  => $caption
    );

    // push this attachment's meta data array to the array of all attachments on the page
    $attachment_data[] = $thumb_data;
  }
  // set the transient
  set_transient( 'image_gallery_' . $post_id, $attachment_data, 52 * WEEK_IN_SECONDS );
}
endif;


if ( ! function_exists( 'frame_reset_gallery_transient' ) ) :
/**
 *
 * Reset the gallery transient when a post is saved or gallery is updated, or the customizer is saved.
 *
 * @package Frame
 * @uses frame_set_gallery_transient
 * @since Frame 1.0
 */
function frame_reset_gallery_transient( $post_id ) {
  delete_transient( 'image_gallery_' . $post_id );
  $attachments = ( 'post' == get_post_type( $post_id ) )
    ? get_post_meta( $post_id, '_zilla_image_ids', true )
    : get_post_meta( $post_id, '_tzp_gallery_images_ids', true );

  if ( $attachments != '' ) {
    frame_set_gallery_transient( $post_id );
  }
}
endif;
add_action( 'save_post', 'frame_reset_gallery_transient' );
add_action( 'wp_ajax_zilla_save_images', 'frame_reset_gallery_transient' );


if ( ! function_exists( 'frame_get_gallery_data' ) ) :
/**
 *
 * Get the gallery image data data.
 *
 * @package Frame
 * @since Frame 1.0
 *
 * Uses: frame_set_image_transient()
 *
 * @return array - the responsive image urls and alt tag
 */
function frame_get_gallery_data( $post_id ) {
  // Check to see if there is a transient available.
  // If there is, use it, otherwise set a new transient and use it.
  if ( false === ( $thumb_data = get_transient( 'image_gallery_' . $post_id ) ) ) {
    frame_set_gallery_transient( $post_id );
    $thumb_data = get_transient( 'image_gallery_' . $post_id );
  }

  return $thumb_data;
}
endif;
