<?php

// Prevent archives for the portfolio plugin; will use a custom page template
if ( ! defined( 'TZP_DISABLE_ARCHIVE' ) ) define( 'TZP_DISABLE_ARCHIVE', TRUE );
// Prevent Zilla Portfolio CSS from loading
if ( ! defined( 'TZP_DISABLE_CSS' ) ) define( 'TZP_DISABLE_CSS', TRUE );

// Remove filters on the content that adds portfolio content to the_content output
remove_filter( 'the_content', 'tzp_add_portfolio_post_meta' );
remove_filter( 'the_content', 'tzp_add_portfolio_post_media' );


if ( ! function_exists( 'frame_portfolio_usevideo' ) ) :
/**
 * Returns if the usevideo flag is set on the given post.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @return  bool
 */
function frame_portfolio_usevideo( $post_id ) {
  return get_post_meta( $post_id, 'frame_portfolio_featurevideo', true ) == 1;
}
endif;


if ( ! function_exists( 'frame_portfolio_set_usevideo' ) ) :
/**
 * Sets the usevideo flag on a given post.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @param   bool $usevideo
 *          True to set the usevideo flag, false to delete it.
 */
function frame_portfolio_set_usevideo( $post_id, $usevideo ) {
  if ( $usevideo ) {
    update_post_meta( $post_id, 'frame_portfolio_featurevideo', 1, true );
  } else {
    delete_post_meta( $post_id, 'frame_portfolio_featurevideo' );
  }
}
endif;


if ( ! function_exists( 'frame_portfolio_post_is_active' ) ) :
/**
 * Returns if the post is linked to a portfolio page.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @return  bool
 */
function frame_portfolio_post_is_active( $post_id ) {
  return get_post_meta( $post_id, 'frame_portfolio_active', true ) == 1;
}
endif;


if ( ! function_exists( 'frame_portfolio_pages' ) ) :
/**
 * Get all pages marked as portfolio pages.
 *
 * @param   $post_id optional
 *          Only return portfolio pages which have this post.
 *
 * @return  array
 *          An array of WP_Post objects that have been set as portfolio pages.
 */
function frame_portfolio_pages( $post_id = null ) {
  $pages = get_pages( array(
    'meta_key'      => '_wp_page_template',
    'meta_value'    => 'portfolio.php',
    'hierarchical'  => 0,
  ) );

  // Return all pages
  if ( is_null( $post_id ) ) {
    return ( $pages )
      ? $pages
      : array();
  }

  // Return only pages that are linked to a given post
  $matching = array();

  foreach ( $pages as $page ) {
    $page_id = $page->ID;

    if ( frame_portfolio_has_post( $page_id, $post_id ) ) {
      $matching[] = $page;
    }
  }

  return $matching;
}
endif;


if ( ! function_exists( 'frame_portfolio_page_ids' ) ) :
/**
 * Get IDs for all pages marked as portfolio pages.
 *
 * If a post ID is given, only pages that are linked to that post are returned.
 *
 * @param   $post_id optional
 */
function frame_portfolio_page_ids( $post_id = null ) {
  $pages = frame_portfolio_pages( $post_id );
  $ids = array();

  foreach ( $pages as $page ) {
    $ids[] = $page->ID;
  }

  return $ids;
}
endif;


if ( ! function_exists( 'frame_portfolio_post_ids' ) ) :
/**
 * Get a list of post IDs that belong to a portfolio page.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @return  array
 *          An array of post IDs, empty if none.
 */
function frame_portfolio_post_ids( $page_id ) {
  $meta = get_post_meta( $page_id, 'frame_portfolio_posts', true );
  return ( is_array( $meta ) )
    ? $meta
    : array();
}
endif;


if ( ! function_exists( 'frame_filter_portfolio_posts' ) ) :
/**
 * Filter out portfolio posts from the index page.
 */
function frame_filter_portfolio_posts( $query ) {
  if ( ! $query->is_home() || ! $query->is_main_query() ) {
    return;
  }

  $query->set( 'meta_query', array(
    array(
      'key'     => 'frame_portfolio_active',
      'value'   => true,
      'compare' => 'NOT EXISTS'
    )
  ));

}
endif;
add_action( 'pre_get_posts', 'frame_filter_portfolio_posts' );


if ( ! function_exists( 'frame_portfolio_has_post' ) ) :
/**
 * Returns true if the portfolio page is linked to the given post.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @return  bool
 */
function frame_portfolio_has_post( $page_id, $post_id ) {
  $post_ids = frame_portfolio_post_ids( $page_id );
  return in_array( $post_id, $post_ids );
}
endif;


if ( ! function_exists( 'frame_portfolio_add_post' ) ) :
/**
 * Add a post to a portfolio page.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @param   $post_id
 *          The post ID.
 */
function frame_portfolio_add_post( $page_id, $post_id ) {
  $post_ids = frame_portfolio_post_ids( $page_id );
  $post_ids[] = $post_id;
  $post_ids = array_unique( $post_ids );

  update_post_meta( $page_id, 'frame_portfolio_posts', $post_ids );
  add_post_meta( $post_id, 'frame_portfolio_active', true, true );
}
endif;


if ( ! function_exists( 'frame_portfolio_remove_post' ) ) :
/**
 * Remove a post from a portfolio page.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @param   $post_id
 *          The post ID.
 */
function frame_portfolio_remove_post( $page_id, $post_id ) {
  // Remove post_id from page
  $post_ids = frame_portfolio_post_ids( $page_id );
  $key = array_search( $post_id, $post_ids );

  if ( $key !== false ) {
    unset( $post_ids[$key] );
  }

  update_post_meta( $page_id, 'frame_portfolio_posts', $post_ids );

  // Update active flag
  $page_ids = frame_portfolio_page_ids( $post_id );

  if ( empty( $page_ids ) ) {
    delete_post_meta( $post_id, 'frame_portfolio_active' );
  }
}
endif;


if ( ! function_exists( 'frame_portfolio_upgrade' ) ) :
/**
 * Upgrade post_meta table to new portfolio implementation.
 */
function frame_portfolio_upgrade() {
  // Already upgraded
  if ( get_theme_mod( 'frame_portfolio_upgrade' ) === true ) {
    return;
  }

  // Get all posts with old meta
  $posts = get_posts( array(
    'posts_per_page'  => -1,
    'post_status'     => 'any',
    'meta_query'      => array(
      array(
        'key'         => 'frame_portfolio_ids',
        'compare'     => 'EXISTS',
      )
    )
  ) );

  // Update posts to new meta
  foreach ( $posts as $post ) {
    $meta = get_post_meta( $post->ID, 'frame_portfolio_ids' );

    if ( ! isset( $meta[0] ) ) {
      continue;
    }

    $ids = $meta[0];

    foreach ( $ids as $id ) {
      frame_portfolio_add_post( $id, $post->ID );
    }

    delete_post_meta( $post->ID, 'frame_portfolio_ids' );
  }

  // Mark that we have upgraded
  set_theme_mod( 'frame_portfolio_upgrade', true );
}
endif;
add_action( 'admin_init', 'frame_portfolio_upgrade' );


if ( ! function_exists( 'frame_add_portfolio_to_rss' ) ) :
/**
 * Adds portfolios to RSS feed
 *
 * @param obj $request
 * @return obj Updated request
 */
function frame_add_portfolio_to_rss( $request ) {
  if ( isset( $request['feed'] ) && ! isset( $request['post_type'] ) ) {
    $request['post_type'] = array( 'post', 'portfolio' );
  }

  return $request;
}
endif;
add_filter( 'request', 'frame_add_portfolio_to_rss' );


if ( ! function_exists( 'frame_set_archive_order' ) ) :
/**
 * Set the order for portfolio type taxonomy archives
 *
 * @param  obj $query the query object
 * @return void
 */
function frame_set_archive_order( $query ) {
  if ( $query->is_tax( 'portfolio-type' ) && $query->is_main_query() ) {
    $query->set( 'orderby', 'menu_order' );
    $query->set( 'order', 'ASC' );
  }
}
endif;
add_action( 'pre_get_posts', 'frame_set_archive_order' );


if ( ! function_exists( 'frame_render_portfolio_settings_fields' ) ) :
/**
* Add meta field to general portfolio settings fields
*
* @param  int $post_id the post id
* @return void
*/
function frame_render_portfolio_settings_fields( $post_id ) { ?>
  <div class="tzp-field">
    <div class="tzp-left">
      <p><?php _e( 'Featured:', 'frame' ); ?></p>
    </div>
    <div class="tzp-right">
      <ul class="tzp-inline-checkboxes">
        <li>
          <input
            type="checkbox"
            name="_zilla_frame_featured_portfolio"
            id="_zilla_frame_featured_portfolio"<?php checked( 1, get_post_meta( $post_id, '_zilla_frame_featured_portfolio', true ) ); ?> />
          <label for='_zilla_frame_featured_portfolio'><?php _e( 'Feature Project?', 'frame' ); ?></label>
        </li>
      </ul>
      <p class='tzp-desc howto'><?php _e( 'Featured projects are displayed full width at the top of a portfolio page.', 'frame' ); ?></p>
    </div>
  </div>
<?php }
endif;
add_action( 'tzp_portfolio_settings_meta_box_fields', 'frame_render_portfolio_settings_fields', 8 );


if ( ! function_exists( 'frame_render_gallery_settings_fields' ) ) :
/**
* Add meta fields to portfolio gallery settings fields
*
* @param  int $post_id the post id
* @return void
*/
function frame_render_gallery_settings_fields( $post_id ) { ?>
  <div class="tzp-field">
    <div class="tzp-left">
      <p><?php _e( 'Gallery Layout:', 'frame' ); ?></p>
    </div>
    <div class="tzp-right">
      <?php $layout = get_post_meta( $post_id, '_zilla_frame_gallery_layout', true ); ?>
      <select name="_zilla_frame_gallery_layout" id="_zilla_frame_gallery_layout">
        <option <?php selected( $layout, 'slideshow' ); ?> value="slideshow"><?php _e( 'Slideshow', 'frame' ); ?></option>
        <option <?php selected( $layout, 'stacked' ); ?> value="stacked"><?php _e( 'Stacked', 'frame' ); ?></option>
      </select>
      <p class='tzp-desc howto'><?php _e('Set the layout for the gallery.', 'frame'); ?></p>
    </div>
  </div>

  <div class="tzp-field" id="gallery-transition-metabox">
    <div class="tzp-left">
      <p><?php _e('Gallery Transition:', 'frame'); ?></p>
    </div>
    <div class="tzp-right">
      <?php $transition = get_post_meta( $post_id, '_zilla_frame_gallery_transition', true ); ?>
      <select name="_zilla_frame_gallery_transition" id="_zilla_frame_gallery_transition">
        <option <?php selected( $transition, 'fade' ); ?> value="fade"><?php _e( 'Fade', 'frame' ); ?></option>
        <option <?php selected( $transition, 'fadeout' ); ?> value="fadeout"><?php _e( 'Fadeout', 'frame' ); ?></option>
        <option <?php selected( $transition, 'scrollHorz' ); ?> value="scrollHorz"><?php _e( 'Slide', 'frame' ); ?></option>
      </select>
      <p class='tzp-desc howto'><?php _e('Set the transition style for the gallery images.', 'frame'); ?></p>
    </div>
  </div>

  <div class="tzp-field" id="gallery-timeout-metabox">
    <div class="tzp-left">
      <p><?php _e( 'Gallery Timeout:', 'frame' ); ?></p>
    </div>
    <div class="tzp-right">
      <?php $timeout = get_post_meta( $post_id, '_zilla_frame_gallery_timeout', true ); ?>
      <select name="_zilla_frame_gallery_timeout" id="_zilla_frame_gallery_timeout">
        <option <?php selected( $timeout, '0' ); ?> value="0"><?php _e( '0 Seconds (manual transition)', 'frame' ); ?></option>
        <?php for ( $i = 1; $i <= 20; $i++ ) {
          $label = ( $i === 1 )
            ? sprintf( __( '%d Second', 'frame' ), $i )
            : sprintf( __( '%d Seconds', 'frame' ), $i );
          printf(
            '<option %1$s value="%2$s000">%3$s</option>',
            selected( $timeout, $i . '000' ),
            $i,
            $label
          );
        } ?>

      </select>
      <p class='tzp-desc howto'><?php _e( 'Set the time between slides.', 'frame' ); ?></p>
    </div>
  </div>

  <div class="tzp-field" id="gallery-caption-style">
    <div class="tzp-left">
      <p><?php _e( 'Caption Style:', 'frame' ); ?></p>
    </div>
    <div class="tzp-right">
      <?php $style = get_post_meta( $post_id, '_zilla_frame_gallery_caption_style', true ); ?>
      <select name="_zilla_frame_gallery_caption_style" id="_zilla_frame_gallery_caption_style">
        <option <?php selected( $timeout, 'dark' ); ?> value="dark"><?php _e( 'Dark text', 'frame' ); ?></option>
        <option <?php selected( $timeout, 'light' ); ?> value="light"><?php _e( 'Light text', 'frame' ); ?></option>
      </select>
      <p class='tzp-desc howto'><?php _e( 'Set the style for image captions.', 'frame' ); ?></p>
    </div>
  </div>
<?php }
endif;
add_action( 'tzp_portfolio_gallery_meta_box_fields', 'frame_render_gallery_settings_fields', 8 );


if ( ! function_exists( 'frame_render_portfolio_audio_fields' ) ) :
/**
 * Portfolio Audio Fields - remove the poster image so we can use the feaured image instead
 *
 * @param int $post_id The ID of the portfolio post
 */
function frame_render_portfolio_audio_fields( $post_id ) { ?>
  <div class="tzp-field">
    <div class="tzp-left">
      <label for="_tzp_audio_file_mp3"><?php _e( '.mp3 File:', 'frame' ); ?></label>
    </div>
    <div class="tzp-right">
      <input type="text" name="_tzp_audio_file_mp3" id="_tzp_audio_file_mp3" value="<?php echo esc_attr( get_post_meta( $post_id, '_tzp_audio_file_mp3', true ) ); ?>" class="file" />
      <input type="button" class="tzp-upload-file-button button" name="_tzp_audio_file_mp3_button" data-post-id="<?php echo $post_id; ?>" id="_tzp_audio_file_mp3_button" value="<?php esc_attr_e( 'Browse', 'frame' ); ?>" />
      <p class='tzp-desc howto'><?php _e( 'Insert an .mp3 file, if desired.', 'frame' ); ?></p>
    </div>
  </div>

  <div class="tzp-field">
    <div class="tzp-left">
      <label for='_tzp_audio_file_ogg'><?php _e( '.ogg File:', 'frame' ); ?></label>
    </div>
    <div class="tzp-right">
      <input type="text" name="_tzp_audio_file_ogg" id="_tzp_audio_file_ogg" value="<?php echo esc_attr( get_post_meta( $post_id, '_tzp_audio_file_ogg', true ) ); ?>" class="file" />
      <input type="button" class="tzp-upload-file-button button" name="_tzp_audio_file_ogg_button" data-post-id="<?php echo $post_id; ?>" id="_tzp_audio_file_ogg_button" value="<?php esc_attr_e( 'Browse', 'frame' ); ?>" />
      <p class='tzp-desc howto'><?php _e( 'Insert an .ogg file, if desired.', 'frame' ); ?></p>
    </div>
  </div>
<?php }
endif;
remove_action( 'tzp_portfolio_audio_meta_box_fields', 'tzp_render_portfolio_audio_fields', 10 );
add_action( 'tzp_portfolio_audio_meta_box_fields', 'frame_render_portfolio_audio_fields', 10 );


if ( ! function_exists( 'frame_save_added_portfolio_post_meta' ) ) :
/**
 * Add the new meta fields to the array of values to be saved
 * The 'select' type is not standard and sanitization is added in extras.php - tzp_metabox_sanitize_select()
 *
 * @param  array $array Array of the fields to be sanitized and saved
 * @return array        The updated array
 */
function frame_save_added_portfolio_post_meta( $array ) {
  $array['_zilla_frame_featured_portfolio']    = 'checkbox';
  $array['_zilla_frame_gallery_transition']    = 'select';
  $array['_zilla_frame_gallery_timeout']       = 'select';
  $array['_zilla_frame_gallery_layout']        = 'select';
  $array['_zilla_frame_gallery_caption_style'] = 'select';

  return $array;
}
endif;
add_filter( 'tzp_metabox_fields_save', 'frame_save_added_portfolio_post_meta' );


if ( ! function_exists( 'frame_portfolio_meta' ) ) :
/**
 * Build and echo the portfolio meta information
 *
 * @param  int $post_id The post id
 * @since  1.0
 * @return void
 */
function frame_portfolio_meta( $post_id ) {
  $output = '';

  $url    = get_post_meta( $post_id, '_tzp_portfolio_url', true );
  $date   = get_post_meta( $post_id, '_tzp_portfolio_date', true );
  $client = get_post_meta( $post_id, '_tzp_portfolio_client', true );
  $terms  = get_the_terms( $post_id, 'portfolio-type' );

  if ( $url || $date || $client ) {
    $output .= '<dl class="portfolio-entry-meta">';

    if ( $date ) {
      $output .= sprintf( '<dt>%1$s</dt> <dd class="portfolio-project-date">%2$s</dd>', __( 'Date:', 'frame' ), esc_html( $date ) );
    }
    if ( $client ) {
      $output .= sprintf( '<dt>%1$s</dt> <dd class="portfolio-project-client">%2$s</dd>', __( 'Client:', 'frame' ), esc_html( $client ) );
    }
    if ( $url ) {
      $output .= sprintf( '<dt>%1$s</dt> <dd><a class="portfolio-project-url" href="%2$s">%2$s</a></dd>', __( 'Link:', 'frame' ), esc_url( $url ) );
    }

    $output .= '</dl>';
  }
  echo $output;

  if ( $terms ) : ?>
      <div class="filed-under">
        <?php _e( 'Filed under: ', 'frame' );
        if ( ! empty( $terms ) ) {
          foreach( $terms as $term ) {
            echo '<a href="'. get_term_link( $term ) .'">'. $term->name .'</a>' . ' ';
          }
        } ?>
      </div>
  <?php endif;

}
endif;


if ( ! function_exists( 'frame_get_portfolio_posts' ) ) :
/**
 * Query used to get posts for the portfolio pages
 *
 * @return $wp_query
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function frame_get_portfolio_posts( $ajax_load = false, $page_number = 0, $terms = array() ) {
  global $wp_query;
  global $post;

  // Are we enabling the load more button?
  $portfolio_pagination = get_theme_mod( 'portfolio_pagination' );

  // Set the number of posts per page
  if ( $portfolio_pagination ) {
    $posts_per_page = get_theme_mod( 'portfolios_per_page' );
  } else {
    $posts_per_page = -1;
  }

  // Set the page number to fetch
  if ( $ajax_load ) {
    $paged = $page_number;
  } else {
    if ( is_front_page() ) {
      $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
    } else {
      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    }
  }

  $args = array(
    'post_type'              => 'portfolio',
    'orderby'                => 'menu_order',
    'order'                  => 'ASC',
    'posts_per_page'         => $posts_per_page,
    'update_post_meta_cache' => false,
    'paged'                  => $paged,
  );

  if ( ! empty( $terms ) ) {
    $args['tax_query'] = array(
      'relation' => 'AND',
        array(
          'taxonomy' => 'portfolio-type',
          'field' => 'id',
          'terms' => $terms,
          'include_children' => true,
          'operator' => 'IN'
        )
    );
  }

  // Set up the custom query
  $wp_query = null;
  $wp_query = new WP_Query($args);

  return $wp_query;
}
endif;


if ( ! function_exists( 'frame_get_featured_portfolio_posts' ) ) :
/**
 * Query used to get posts for the portfolio pages
 *
 * @return $wp_query
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function frame_get_featured_portfolio_posts() {
  global $wp_query;
  global $post;

  $args = array(
    'post_type'              => 'portfolio',
    'orderby'                => 'menu_order',
    'order'                  => 'ASC',
    'meta_key'               => '_zilla_frame_featured_portfolio',
    'meta_value'             => 1,
    'posts_per_page'         => 6,
  );

  // Set up the custom query
  $wp_query = null;
  $wp_query = new WP_Query($args);

  return $wp_query;
}
endif;


if ( ! function_exists( 'home_print_portfolio_grid' ) ) :
/**
 * Prints the featured posts in a grid of six for home page
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function home_print_portfolio_grid() {
  global $post;
  $wp_query = frame_get_featured_portfolio_posts();

  if ( $wp_query->have_posts() ) :
    while ( $wp_query->have_posts() ) :
      $wp_query->the_post(); ?>

      <div <?php post_class(); ?>>
        <div class="gallery-tile">
          <?php frame_post_thumbnail( $post->ID, 'thumb' ); ?>
          <a class="dimmer" href="<?php echo get_permalink() ?>"><h1 class="post-title"><?php the_title(); ?></h1></a>
          <div class="gallery-tile-padder <?php echo esc_attr( get_theme_mod( 'thumbnail_aspect' ) ); ?>"></div>
        </div>
      </div>

      <?php
    endwhile;

    wp_reset_postdata();

  else :

    printf( '<p>%s</p>', __( 'You can add items to your portfolio through the Portfolio Posts menu after installing the Zilla Portfolio plugin.', 'frame' ) );

  endif;
}
endif;


if ( ! function_exists( 'frame_print_portfolio_grid' ) ) :
/**
 * Prints the posts in the grid of a portfolio page
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function frame_print_portfolio_grid( $ajax_load = false, $page_number = 0, $terms = array() ) {
  global $post;
  // Are we enabling the load more button?
  $portfolio_pagination = get_theme_mod( 'portfolio_pagination' );
  $wp_query = frame_get_portfolio_posts( $ajax_load, $page_number, $terms );

  if ( $wp_query->have_posts() ) :
    while ( $wp_query->have_posts() ) :
      $wp_query->the_post(); ?>

      <div <?php post_class(); ?>>
        <div class="gallery-tile">
          <?php frame_post_thumbnail( $post->ID, 'thumb' ); ?>
          <a class="dimmer" href="<?php echo get_permalink() ?>"><h1 class="post-title"><?php the_title(); ?></h1></a>
          <div class="gallery-tile-padder <?php echo esc_attr( get_theme_mod( 'thumbnail_aspect' ) ); ?>"></div>
        </div>
      </div>

      <?php
    endwhile;

    if ( is_front_page() ) {
      $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
    } else {
      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    }

    // Show the load more button if it's enabled, there's more than one page of posts, and we're not on the last page
    if ( $portfolio_pagination && ( $wp_query->max_num_pages > 1 && $paged < $wp_query->max_num_pages ) ) {
      $nextpage = intval( $paged ) + 1;
      printf(
        '<div class="type-portfolio btn-wrap"><button class="btn-load-more" data-nextpage="%1$d">%2$s</button></div>',
        $nextpage,
        __( 'Load More +', 'frame' )
      );
    }

    wp_reset_postdata();

  else :

    printf( '<p>%s</p>', __( 'You can add items to your portfolio through the Portfolio Posts menu after installing the Zilla Portfolio plugin.', 'frame' ) );

  endif;
}
endif;


if ( ! function_exists( 'frame_print_portfolio_filters' ) ) :
/**
 * Prints the markup for the portfolio filters
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function frame_print_portfolio_filters() {
  $terms = get_terms( 'portfolio-type' );
?>
  <?php if ( ! empty( $terms ) ) : ?>
  <div class="portfolio-filters">
      <ul class="filter-terms-list">
      <?php foreach ( $terms as $term ) {
        printf(
          '<li><input type="checkbox" name="%1$s" id="portfolio-type-%1$s" value=".portfolio-type-%1$s"><label for="portfolio-type-%1$s">%2$s</label></li>',
          $term->slug,
          $term->name
        );
      }
      ?>
      </ul>

    <button class="filter-toggle">
      <span class="open"><?php _e( 'Filter Projects', 'frame' ); ?></span>
      <span class="close"><?php _e( 'Hide Filters', 'frame' ); ?></span>
    </button>
  </div>
  <?php endif; ?>

<?php }
endif;


if ( ! function_exists( 'frame_print_portfolio_permalinks' ) ) :
/**
 * Prints the permalink posts (details) on the portfolio page
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function frame_print_portfolio_permalinks( $ajax_load = false, $page_number = 0, $terms = array() ) {
  $wp_query = frame_get_portfolio_posts( $ajax_load, $page_number, $terms );

  if ( $wp_query->have_posts() ) {
    while ( $wp_query->have_posts() ) {
      $wp_query->the_post();
      get_template_part( 'content', 'portfolio' );
    }
  }
}
endif;


if ( ! function_exists( 'frame_portfolio_grid_load_more' ) ) :
/**
 * Loads more posts on the homepage
 *
 * @uses: frame_print_portfolio_grid()
 * @uses: frame_print_portfolio_permalinks()
 *
 * @package Frame
 * @since Frame 1.0
 *
 */
function frame_portfolio_grid_load_more() {
  // Which page to load? Data passed from jquery.custom.js
  $page_number = intval( $_POST['page'] ); ?>
  <div class="grid-items">
    <?php frame_print_portfolio_grid( true, $page_number ); ?>
  </div>
  <div class="permalink-items">
    <?php frame_print_portfolio_permalinks( true, $page_number ); ?>
  </div>
  <?php die();
}
endif;
add_action( 'wp_ajax_nopriv_frame_portfolio_grid_load_more', 'frame_portfolio_grid_load_more' );
add_action( 'wp_ajax_frame_portfolio_grid_load_more', 'frame_portfolio_grid_load_more' );

