<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Frame
 */

if ( ! function_exists( 'frame_sharing_and_likes' ) ) :
  function frame_sharing_and_likes() { ?>
    <div class="sharedaddy-wrap">
      <div class="share-button"></div>
      <div class="like-button"></div>
    <?php if ( function_exists( 'sharing_display' ) ) { ?>
        <div class="share-wrap">
          <?php sharing_display( '', true ); ?>
          <span class="arrow"></span>
        </div>
    <?php }

    if ( class_exists( 'Jetpack_Likes' ) ) {
      $custom_likes = new Jetpack_Likes; ?>
      <div class="likes-wrap">
        <?php echo $custom_likes->post_likes( '' ); ?>
        <span class="arrow"></span>
      </div>
    <?php } ?>
    </div>
<?php }
endif;

if ( ! function_exists( 'frame_get_featured_image' ) ) :
function frame_get_featured_image() {
  global $post;
  $featured_image = '';
  if ( wp_get_attachment_url( get_post_thumbnail_id() ) ) {
    $featured_image = wp_get_attachment_url( get_post_thumbnail_id() );
  } else {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if ( $output ) {
      $featured_image = $matches [1] [0];
    }
  }
  return $featured_image;
}
endif;

if ( ! function_exists( 'frame_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function frame_content_nav( $nav_id ) {
  global $wp_query, $post;

  // Don't print empty markup on single pages if there's nowhere to navigate.
  if ( is_single() ) {
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
      return;
  }

  // Don't print empty markup in archives if there's only one page.
  if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
    return;

  ?>
  <?php if ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
    <nav role="navigation" class="navigation">

      <?php if ( get_next_posts_link() ) : ?>
      <div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'frame' ) ); ?></div>
      <?php endif; ?>

      <?php if ( get_previous_posts_link() && get_next_posts_link() ) : ?>
      <div class="divider"> / </div>
      <?php endif; ?>

      <?php if ( get_previous_posts_link() ) : ?>
      <div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'frame' ) ); ?></div>
      <?php endif; ?>

    </nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
  <?php endif; ?>

  <?php
}
endif; // frame_content_nav

if ( ! function_exists( 'frame_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function frame_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;

  if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

  <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
    <div class="comment-body">
      <?php _e( 'Pingback:', 'frame' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'frame' ), '<span class="edit-link">', '</span>' ); ?>
    </div>

  <?php else : ?>

  <li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
      <footer class="comment-meta">
        <div class="comment-author vcard">
          <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
          <?php printf( __( '%s <span class="says">says:</span>', 'frame' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
        </div><!-- .comment-author -->

        <div class="comment-metadata">
          <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
            <time datetime="<?php comment_time( 'c' ); ?>">
              <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'frame' ), get_comment_date(), get_comment_time() ); ?>
            </time>
          </a>
          <?php edit_comment_link( __( 'Edit', 'frame' ), '<span class="edit-link">', '</span>' ); ?>
        </div><!-- .comment-metadata -->

        <?php if ( '0' == $comment->comment_approved ) : ?>
        <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'frame' ); ?></p>
        <?php endif; ?>
      </footer><!-- .comment-meta -->

      <div class="comment-content">
        <?php comment_text(); ?>
      </div><!-- .comment-content -->

      <div class="reply">
        <?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
      </div><!-- .reply -->
    </article><!-- .comment-body -->

  <?php
  endif;
}
endif; // ends check for frame_comment()

if ( ! function_exists( 'frame_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function frame_posted_on() {
  $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
  /* translators: used between list items, there is a space after the comma */
  $categories_list = get_the_category_list( __( ', ', 'frame' ) );

  if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
    $time_string = '<time class="updated" datetime="%3$s">%4$s</time>';

  $time_string = sprintf( $time_string,
    esc_attr( get_the_modified_date( 'c' ) ),
    esc_html( get_the_modified_date() ),
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() )
  );

  printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'frame' ),
    sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
      esc_url( get_permalink() ),
      esc_attr( get_the_time() ),
      $time_string
    ),

    sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
      esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
      esc_attr( sprintf( __( 'View all posts by %s', 'frame' ), get_the_author() ) ),
      esc_html( get_the_author() )
        )
    );
    echo '<span class="cat-links"> in ' . $categories_list . '</span>';
}
endif;

/**
 * Returns true if a blog has more than 1 category
 */
function frame_categorized_blog() {
  if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
    // Create an array of all the categories that are attached to posts
    $all_the_cool_cats = get_categories( array(
      'hide_empty' => 1,
    ) );

    // Count the number of categories that are attached to the posts
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( 'all_the_cool_cats', $all_the_cool_cats );
  }

  if ( '1' != $all_the_cool_cats ) {
    // This blog has more than 1 category so frame_categorized_blog should return true
    return true;
  } else {
    // This blog has only 1 category so frame_categorized_blog should return false
    return false;
  }
}

/**
 * Flush out the transients used in frame_categorized_blog
 */
function frame_category_transient_flusher() {
  // Like, beat it. Dig?
  delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'frame_category_transient_flusher' );
add_action( 'save_post',   'frame_category_transient_flusher' );


if ( ! function_exists( 'frame_post_thumbnail' ) ) :
/**
 * Print the HTML for a responsive featured image
 *
 * @package Frame
 * @since Frame 1.0
 */
function frame_post_thumbnail( $post_id, $thumb_type = 'full' ) {
  $thumb_data = frame_get_thumb_data( $post_id );
  echo frame_responsive_image( $thumb_data, $thumb_type );
}
endif;


if ( ! function_exists( 'zilla_post_gallery' ) ) :
/**
 * Print the HTML for galleries
 *
 * @since 1.0
 *
 * @param int $post_id ID of the post
 * @param string $layout Optional layout format
 * @return void
 */
function zilla_post_gallery( $post_id ) {
  $attachments = frame_get_gallery_data( $post_id );

  $transition = get_post_meta( $post_id, '_zilla_frame_gallery_transition', true );
  $transition = $transition ? $transition : 'fade';

  $timeout = get_post_meta( $post_id, '_zilla_frame_gallery_timeout', true );
  $timeout = $timeout ? $timeout : '0';

  $layout = get_post_meta( $post_id, '_zilla_frame_gallery_layout', true );
  $layout = $layout ? $layout : 'slideshow';

  $caption_style = get_post_meta( $post_id, '_zilla_frame_gallery_caption_style', true );
  $caption_style = $caption_style ? $caption_style : 'dark';

  if ( ! empty( $attachments ) ) {

    printf(
      '<div id="zilla-gallery-%1$s"
        class="zilla-gallery %4$s"
        data-gallery-fx="%2$s"
        data-gallery-timeout="%3$s">',
      $post_id,
      $transition,
      $timeout,
      $layout
    );

    echo '<div class="cycle-prev"><i class="icon-angle-left-thin"></i></div>';
    echo '<div class="cycle-next"><i class="icon-angle-right-thin"></i></div>';

    foreach ( $attachments as $attachment ) {
      // Check if the image has a title or caption
      $has_caption = ( $attachment['thumb_title'] || $attachment['thumb_caption'] )
        ? null
        : 'no-caption';

      printf(
        '<figure class="zilla-slideshow-slide">
          %1$s
          <figcaption class="zilla-gallery-caption %2$s %5$s">
            <strong class="title">%3$s</strong>
            <span class="description">%4$s</span>
          </figcaption>
        </figure>',
        frame_responsive_image( $attachment ),
        $has_caption,
        $attachment['thumb_title'],
        $attachment['thumb_caption'],
        $caption_style
      );
    }

    echo '</div>';
  }
}
endif;


if ( ! function_exists( 'frame_print_audio_html' ) ) :
/**
 * Prints the WP Audio Shortcode to output the HTML for audio
 * @param  int $post_id The post ID
 * @return string         The "hmtl" for printing audio elements
 */
function frame_print_audio_html( $post_id ) {
  $output = '<div class="entry-audio">';

  $posttype = get_post_type( $post_id );

  $keys = array(
    'post' => array(
      'mp3' => '_zilla_audio_mp3',
      'ogg' => '_zilla_audio_ogg'
    ),
    'portfolio' => array(
      'mp3' => '_tzp_audio_file_mp3',
      'ogg' => '_tzp_audio_file_ogg'
    )
  );

  // Build the "shortcode"
  $mp3 = get_post_meta( $post_id, $keys[$posttype]['mp3'], true );
  $ogg = get_post_meta( $post_id, $keys[$posttype]['ogg'], true );
  $attr = array();

  if ( $mp3 ) { $attr['mp3'] = $mp3; }
  if ( $ogg) { $attr['ogg'] = $ogg; }

  if ( $mp3 || $ogg ) {
    $output .= wp_audio_shortcode( $attr );
  }

  $output .= '</div>';

  return $output;
}
endif;


if ( ! function_exists( 'frame_print_video_html' ) ) :
/**
 * Prints the WP Vidio Shortcode to output the HTML for video
 * @param  int $post_id The post ID
 * @return string The "html" for printing video elements
 */
function frame_print_video_html( $post_id ) {
  $output = '';

  $posttype = get_post_type( $post_id );

  $keys = array(
    'post' => array(
      'embed'  => '_zilla_video_embed_code',
      'poster' => '_zilla_video_poster_url',
      'm4v'    => '_zilla_video_m4v',
      'ogv'    => '_zilla_video_ogv',
      'mp4'    => '_zilla_video_mp4'
    ),
    'portfolio' => array(
      'embed'  => '_tzp_video_embed',
      'poster' => '_tzp_video_poster_url',
      'm4v'    => '_tzp_video_file_m4v',
      'ogv'    => '_tzp_video_file_ogv',
      'mp4'    => '_tzp_video_file_mp4'
    )
  );

  $embed = get_post_meta( $post_id, $keys[$posttype]['embed'], true);

  if ( $embed ) {
    // Output the embed code if provided
    $output .= html_entity_decode( esc_html( $embed ) );
  } else {
    // Build the video "shortcode"
    $poster = get_post_meta( $post_id, $keys[$posttype]['poster'], true );
    $m4v = get_post_meta( $post_id, $keys[$posttype]['m4v'], true );
    $ogv = get_post_meta( $post_id, $keys[$posttype]['ogv'], true );
    $mp4 = get_post_meta( $post_id, $keys[$posttype]['mp4'], true );

    $attr = array( 'width' => '2000' );

    if ( $poster ) { $attr['poster'] = $poster; }
    if ( $m4v )    { $attr['m4v'] = $m4v; }
    if ( $ogv )    { $attr['ogv'] = $ogv; }
    if ( $mp4 )    { $attr['mp4'] = $mp4; }

    if ( $poster || $m4v || $ogv || $mp4 ) {
      $output .= wp_video_shortcode( $attr );
    }
  }

  return $output;
}
endif;


if ( ! function_exists('frame_print_post_format_media') ) :
/**
 * Prints the custom field content based on post format that is set
 * @param  int $post_id The post ID
 *
 */
function frame_print_post_format_media( $post_id ) {
  $format = get_post_format( $post_id );
  switch ( $format ) {
    case 'audio':
      echo frame_print_audio_html( $post_id );
      break;
    case 'video':
      echo frame_print_video_html( $post_id );
      break;
    case 'link':
      $link = get_post_meta( $post_id, '_zilla_link_url', true );
      printf(
        '<p><a href="%1$s" target="_blank">%1$s</a></p>',
        $link
      );
      break;
    case 'quote':
      $quote        = get_post_meta( $post_id, '_zilla_quote_quote', true );
      $quote_author = get_post_meta( $post_id, '_zilla_quote_author', true );
      printf(
        '<blockquote><p>%1$s <br><cite>%2$s</cite></p></blockquote>',
        $quote,
        $quote_author
      );
      break;
    case 'gallery':
      zilla_post_gallery( $post_id );
      break;
  }
}
endif;
