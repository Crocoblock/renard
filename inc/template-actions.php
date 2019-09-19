<?php
/**
 * Functions hooked to custom theme actions and related functions
 *
 * @package renard
 */

/**
 * Attach defined actions
 */

// slider in showcase area
add_action( 'renard_showcase_area', 'renard_slider' );
// modify default comment form
add_filter( 'comment_form_default_fields', 'renard_comment_form_fields' );
// modify excerpt more symbols
add_filter( 'excerpt_more', 'renard_excerpt_more' );
// Wrap comment fields into separaate row
add_action( 'comment_form_before_fields', 'renard_open_comments_fields_wrap' );
add_action( 'comment_form_after_fields', 'renard_close_comments_fields_wrap' );
// about box in sidebar
add_action( 'renard_before_sidebar', 'renard_about_box' );
// Fix preview for text logo in customizer
add_filter( 'get_custom_logo', 'renard_fix_logo_preview' );
// Remove slides from main query
add_action( 'pre_get_posts', 'renard_maybe_remove_slides_from_query' );

/**
 * Maybe remove slides from main query
 *
 * @return void
 */
function renard_maybe_remove_slides_from_query( $query ) {

	if ( ! $query->is_main_query() || is_admin() ) {
		return;
	}

	if ( ! $query->is_home() ) {
		return;
	}

	$slides = renard_get_slides();

	if ( empty( $slides ) ) {
		return;
	}

	$query->set( 'post__not_in', $slides );

}

/**
 * Return slides list
 *
 * @return array
 */
function renard_get_slides() {

	$slides_from = renard_get_option( 'slides_from', 'recent_posts' );
	$num         = renard_get_option( 'slides_num', 4 );

	$query_args = array(
		'posts_per_page'      => absint( $num ),
		'ignore_sticky_posts' => 1
	);

	switch ( $slides_from ) {
		case 'category':
			$category = renard_get_option( 'slides_cat' );
			if ( $category ) {
				$query_args['category_name'] = esc_attr( $category );
			}
			break;

		case 'sticky':
			$sticky = get_option( 'sticky_posts' );
			if ( ! empty( $sticky ) ) {
				$query_args['post__in'] = $sticky;
			}
			break;
	}

	/**
	 * Allow to rewrite slider query arguments from child theme/3rd party plugins
	 */
	$query_args = apply_filters( 'renard_slider_query_args', $query_args );

	$slider_query = new WP_Query( $query_args );

	if ( ! $slider_query->have_posts() ) {
		return array();
	}

	$shown_slides = array();

	while ( $slider_query->have_posts() ) {
		$slider_query->the_post();
		if ( ! has_post_thumbnail( $slider_query->post->ID ) ) {
			continue;
		}
		$shown_slides[] = $slider_query->post->ID;
	}

	wp_reset_postdata();

	wp_cache_set( 'slides', $shown_slides, 'renard' );

	return $shown_slides;
}

/**
 * Fix preview for text logo in customizer
 */
function renard_fix_logo_preview( $logo ) {

	if ( ! is_customize_preview() ) {
		return $logo;
	}

	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( ! $custom_logo_id ) {
		return reanrd_get_text_logo( 'custom-logo-link' );
	} else {
		return $logo;
	}

}

/**
 * Get standard slider output
 */
function renard_slider() {

	// do nothing if slider disabled from options
	$show_slider = renard_get_option( 'slider_enabled', true );
	if ( ! $show_slider ) {
		return '';
	}

	// check, if slider enabled for current page
	$pages_to_show = renard_get_option( 'slider_visibility', 'front' );
	if ( 'front' == $pages_to_show && ! is_front_page() ) {
		return '';
	}

	$show_banner  = renard_get_option( 'slider_banner', true );
	$btn_text     = renard_get_option( 'slider_btn_text', __( 'Read', 'renard' ) );
	$result       = '';

	$slides = wp_cache_get( 'slides', 'renard' );

	if ( empty( $slides ) ) {
		$slides = renard_get_slides();
	}

	if ( empty( $slides ) ) {
		return '';
	}

	foreach ( $slides as $slide_id ) {

		$image_args = array( 'alt' => get_the_title( $slide_id ), 'class' => 'sp-image' );
		$image      = get_the_post_thumbnail( $slide_id, 'renard-slider-thumbnail', $image_args );
		$banner     = '';

		if ( $show_banner ) {
			$banner = renard_get_slider_banner( $slide_id, esc_html( $btn_text ) );
		}

		$result .= '<div class="slider-item sp-slide">' . $image . $banner . '</div>';
	}



	global $_wp_additional_image_sizes;

	if ( isset( $_wp_additional_image_sizes['renard-slider-thumbnail'] ) ) {
		$width  = $_wp_additional_image_sizes['renard-slider-thumbnail']['width'];
		$height = $_wp_additional_image_sizes['renard-slider-thumbnail']['height'];
	} else {
		$width  = 2000;
		$height = 600;
	}

	$slider_defaults = apply_filters(
		'renard_slider_default_args',
		array(
			'fade'          => false,
			'arrows'        => true,
			'buttons'       => true,
			'width'         => '100%',
			'height'        => $height,
			'forceSize'     => 'fullWidth',
			'slideDistance' => 0,
			'aspectRatio'   => round( $width / $height, 3 ),
		)
	);

	$fade   = ( 'fade' == renard_get_option( 'slider_animation', 'fade' ) );
	$arrows = renard_get_option( 'slider_arrows', true );
	$pager  = renard_get_option( 'slider_pager', true );

	$slider_args = wp_parse_args(
		array(
			'fade'    => (bool)$fade,
			'arrows'  => (bool)$arrows,
			'buttons' => (bool)$pager
		),
		$slider_defaults
	);

	$slider_args = json_encode( $slider_args );

	if ( empty( $result ) ) {
		return;
	}

	/**
	 * Filter slider output before printing
	 */
	$result = apply_filters(
		'renard_slider_output',
		sprintf(
			'<div class="slider-box slider-pro" data-args=\'%2$s\'><div class="sp-slides">%1$s</div></div>',
			$result, $slider_args
		)
	);

	echo $result;

}

/**
 * Get slider banner content by post ID
 *
 * @param  int    $post_id  post ID to get banner for
 * @param  string $btn_text banner button text
 */
function renard_get_slider_banner( $post_id, $btn_text ) {

	$format = '<div class="slider-banner"><div class="slider-banner-content">%1$s%2$s</div>%3$s</div>';

	$title = '<h2 class="slider-banner-title">' . get_the_title( $post_id ) . '</h2>';

	if ( has_excerpt( $post_id ) ) {
		$excerpt = get_the_excerpt();
	} else {
		$content = get_the_content();
		$excerpt = strip_shortcodes( $content );
		$excerpt = str_replace( ']]>', ']]&gt;', $content );
		$excerpt = wp_trim_words( $excerpt, 20, '' );
	}
	$excerpt = '<div class="slider-banner-excerpt">' . $excerpt . '</div>';

	$button = '<div class="slider-banner-button-box"><a href="' . esc_url( get_permalink( $post_id ) ) . '" class="slider-banner-button">' . $btn_text . '</a></div>';

	return sprintf( $format, $title, $excerpt, $button );
}

/**
 * Modify comment form default fields
 */
function renard_comment_form_fields( $fields ) {

	$req       = get_option( 'require_name_email' );
	$html5     = 'html5';
	$commenter = wp_get_current_commenter();
	$aria_req  = ( $req ? " aria-required='true'" : '' );

	$fields = array(
		'author' => '<p class="comment-form-author"><input class="comment-form-input" id="author" name="author" type="text" placeholder="' . __( 'Name', 'renard' ) . ( $req ? '*' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><input class="comment-form-input" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . __( 'Email', 'renard' ) . ( $req ? '*' : '' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><input class="comment-form-input" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . __( 'Website', 'renard' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'
	);

	return $fields;
}

/**
 * Replace default excerpt more symbols
 */
function renard_excerpt_more($more) {
	return ' &hellip;';
}

/**
 * Wrap comment fields into separaate row
 */
function renard_open_comments_fields_wrap() {
	echo '<div class="comment-fields-wrap">';
}

/**
 * Wrap comment fields into separaate row
 */
function renard_close_comments_fields_wrap() {
	echo '</div>';
}

/**
 * Show about box in sidebar
 */
function renard_about_box() {

	$is_enabled = renard_get_option( 'about_enabled', false );

	if ( ! $is_enabled ) {
		return;
	}

	// prepare data
	$image   = renard_get_option( 'about_img' );
	$message = renard_get_option( 'about_message', __( 'Hello! And welcome to my personal website!', 'renard' ) );

	$image_html = ( ! empty( $image ) )
				? sprintf( '<div class="custom-box-about-img"><img src="%1$s" alt=""></div>', esc_url( $image ) )
				: '';

	$message_html = ( ! empty( $message ) )
					? sprintf( '<div class="custom-box-about-message">%s</div>', wp_kses_post( $message ) )
					: '';

	?>
	<div class="widget sidebar-widget custom-box-about">
		<?php echo $image_html; ?>
		<?php echo $message_html; ?>
	</div>
	<?php
}
