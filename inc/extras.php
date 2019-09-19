<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package renard
 */

/**
 * Get necessary Google fonts URL
 */
function renard_fonts_url() {

	$fonts_url = '';

	$fonts = array();

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Yesteryear, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$fonts['Yesteryear:400'] = _x( 'on', 'Yesteryear font: on or off', 'renard' );

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Roboto, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$fonts['Roboto:300,400,700,400italic,700italic'] = _x( 'on', 'Roboto font: on or off', 'renard' );

	/**
	 * Translators: Set fonts subset for your language.
	 */
	$subset = _x( 'latin,latin-ext', 'Set subset for you language more info here - https://www.google.com/fonts/', 'renard' );

	if ( false == strpos( $subset , 'latin' ) ) {
		$subset = 'latin,' . $subset;
	}

	$font_families = array();

	foreach ( $fonts as $font => $trigger ) {
		if ( 'off' !== $trigger ) {
			$font_families[] = $font;
		}
	}

	if ( empty( $font_families ) ) {
		return $fonts_url;
	}

	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( $subset ),
	);

	$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

	return $fonts_url;
}

/**
 * Get theme option by name
 *
 * @param  string $name    option name
 * @param  mixed  $default default option value
 */
function renard_get_option( $name, $default = false ) {

	$all_options = get_theme_mod( 'renard' );

	if ( is_array( $all_options ) && isset( $all_options[ $name ] ) ) {
		return $all_options[ $name ];
	}

	return $default;

}

/**
 * Print options-related class to determine sidebar position
 */
function renard_sidebar_class() {
	$sidebar_position = renard_get_option( 'sidebar_position', 'right' );
	printf( '%s-sidebar', esc_attr( $sidebar_position ) );
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function renard_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'renard_body_classes' );

/**
 * Custom comment output
 */
function renard_comment( $comment, $args, $depth ) {

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'renard' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'renard' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-meta">
				<?php
					comment_reply_link(
						array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						) ),
						$comment
					);
				?>
				<div class="comment-author-thumb">
					<?php echo get_avatar( $comment, 40 ); ?>
				</div><!-- .comment-author -->
				<?php printf( '<div class="comment-author">%s</div>', get_comment_author_link() ); ?>
				<time datetime="<?php comment_time( 'c' ); ?>">
					<?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ) . ' ' . __( 'ago', 'renard' ); ?>
				</time>
			</div>
			<div class="comment-content">
				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'renard' ); ?></p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->
		</article><!-- .comment-body -->

	<?php
	endif;

}

/**
 * Get additional classes for posts loop
 *
 * @return array
 */
function renard_loop_classes() {

	$is_enabled = renard_get_option( 'blog_loop_image', true );
	$is_enabled = (bool) $is_enabled;

	if ( has_post_thumbnail() && $is_enabled ) {
		$thumb = 'has-thumb';
	} else {
		$thumb = 'no-thumb';
	}

	return array( 'is-loop', $thumb );
}

/**
 * Get additional classes for single post
 *
 * @return array
 */
function renard_single_classes() {

	$is_enabled = renard_get_option( 'blog_single_image', true );
	$is_enabled = (bool) $is_enabled;

	if ( has_post_thumbnail() && $is_enabled ) {
		$thumb = 'has-thumb';
	} else {
		$thumb = 'no-thumb';
	}

	return array( 'is-single', $thumb );
}
