<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package renard
 */

if ( ! function_exists( 'renard_logo' ) ) :
/**
 * Print site logo
 */
function renard_logo() {

	$custom_logo = null;

	if ( is_home() || is_front_page() ) {
		$tag = 'h1';
	} else {
		$tag = 'h2';
	}

	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$content = get_custom_logo();
	} else {
		$content = reanrd_get_text_logo();
	}

	printf( '<%1$s class="site-title">%2$s</%1$s>', $tag, $content );
}
endif;

/**
 * Returns text logo HTML
 *
 * @param  string $class Additional HTML class
 * @return string
 */
function reanrd_get_text_logo( $class = '' ) {

	return sprintf(
		'<a href="%2$s" class="text-logo %3$s" rel="home">%1$s</a>',
		get_bloginfo( 'name' ),
		esc_url( home_url( '/' ) ),
		esc_attr( $class )
	);

}

/**
 * Show post author
 */
function renard_post_author() {

	$id = get_the_author_meta( 'ID' );

	$author = sprintf(
		'<span class="author"><a href="%1$s">%2$s</a></span>',
		esc_url( get_author_posts_url( $id ) ),
		esc_html( get_the_author() )
	);

	echo '<span class="entry-meta-item author">' . $author . '</span>';
}

/**
 * Show post author avatar
 */
function renard_post_author_avatar() {
	$id = get_the_author_meta( 'ID' );
	echo '<span class="entry-meta-item avatar">' . get_avatar( $id, 40 ) . '</span>';
}

/**
 * Prints HTML with meta information for the current post-date.
 */
function renard_post_date() {

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

	echo '<span class="entry-meta-item posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}

/**
 * Prints HTML with meta information for the current post-date.
 */
function renard_post_comments() {

	if ( post_password_required() || ! comments_open() ) {
		return;
	}

	echo '<span class="entry-meta-item comments">';
	comments_popup_link( esc_html__( 'Leave a comment', 'renard' ), esc_html__( '1 Comment', 'renard' ), esc_html__( '% Comments', 'renard' ) );
	echo '</span>';

}

function renard_post_categories() {

	// Hide category and tag text for pages.
	if ( 'post' != get_post_type() ) {
		return;
	}

	$categories_list = get_the_category_list( esc_html__( ', ', 'renard' ) );
	if ( $categories_list && renard_categorized_blog() ) {
		printf( '<span class="entry-meta-item cat-links"><i class="fa fa-folder-open"></i> ' . esc_html__( 'Posted in %1$s', 'renard' ) . '</span>', $categories_list ); // WPCS: XSS OK.
	}

}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function renard_post_tags() {
	// Hide category and tag text for pages.
	if ( 'post' != get_post_type() ) {
		return;
	}

	$tags_list = get_the_tag_list( '', esc_html__( ', ', 'renard' ) );
	if ( $tags_list ) {
		printf( '<span class="entry-meta-item tags-links"><i class="fa fa-tags"></i> ' . esc_html__( 'Tagged %1$s', 'renard' ) . '</span>', $tags_list ); // WPCS: XSS OK.
	}

}

if ( ! function_exists( 'renard_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function renard_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'renard' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'renard' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'renard_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function renard_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'renard' ) );
		if ( $categories_list && renard_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'renard' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'renard' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'renard' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'renard' ), esc_html__( '1 Comment', 'renard' ), esc_html__( '% Comments', 'renard' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'renard' ), '<span class="edit-link">', '</span>' );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function renard_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'renard_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'renard_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so renard_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so renard_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in renard_categorized_blog.
 */
function renard_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'renard_categories' );
}
add_action( 'edit_category', 'renard_category_transient_flusher' );
add_action( 'save_post',     'renard_category_transient_flusher' );

/**
 * Print social follow list
 */
function renard_follow_list() {

	$is_enabled = renard_get_option( 'follow_enabled', 1 );

	if ( ! $is_enabled ) {
		return;
	}

	$social_list = wp_nav_menu( array(
		'theme_location'   => 'social',
		'container'        => 'div',
		'container_class'  => 'follow-list-wrap',
		'menu_class'       => 'follow-list-items',
		'depth'            => 1,
		'echo'             => false,
		'fallback_cb'      => '__return_empty_string',
	) );

	printf( '<div class="follow-list">%s</div>', $social_list );
}

/**
 * Show post featured image
 * @param  boolean $is_linked liked image or not
 */
function renard_post_thumbnail( $is_linked = true ) {

	if ( ! has_post_thumbnail() ) {
		return;
	}

	$is_enabled = true;

	if ( is_single() ) {
		$is_enabled = renard_get_option( 'blog_single_image', true );
	} else {
		$is_enabled = renard_get_option( 'blog_loop_image', true );
	}

	$is_enabled = (bool)$is_enabled;

	if ( ! $is_enabled ) {
		return;
	}

	if ( $is_linked ) {
		$format = '<figure class="entry-thumbnail"><a href="%2$s">%1$s<span class="link-marker"></span></a></figure>';
		$link   = esc_url( get_permalink() );
	} else {
		$format = '<figure class="entry-thumbnail">%1$s</figure>';
		$link   = false;
	}

	$size = 'post-thumbnail';

	$image = get_the_post_thumbnail( get_the_id(), $size, array( 'alt' => get_the_title() ) );

	printf( $format, $image, $link );

}

/**
 * Show posts listing content depending from options
 */
function renard_blog_content() {

	$blog_content = renard_get_option( 'blog_content', 'excerpt' );

	if ( 'excerpt' == $blog_content ) {
		the_excerpt();
		return;
	}

	/* translators: %s: Name of current post */
	the_content( sprintf(
		wp_kses(
			__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'renard' ),
			array( 'span' => array( 'class' => array() ) )
		),
		the_title( '<span class="screen-reader-text">"', '"</span>', false )
	) );

	wp_link_pages( array(
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'renard' ),
		'after'  => '</div>',
	) );

}

/**
 * Show post meta data
 *
 * @param string $page     page, meta called from
 * @param string $position position, meta called from
 * @param string $disable  disabled meta keys array
 */
function renard_post_meta( $page = 'loop', $position = 'header', $disable = array() ) {

	$default_meta = array(
		'author_avatar' => array(
			'page'     => $page,
			'position' => array( 'single' => 'header', 'loop' => 'footer' ),
			'callback' => 'renard_post_author_avatar',
			'priority' => 2,
		),
		'author' => array(
			'page'     => $page,
			'position' => array( 'single' => 'header', 'loop' => 'footer' ),
			'callback' => 'renard_post_author',
			'priority' => 2,
		),
		'date' => array(
			'page'     => $page,
			'position' => array( 'single' => 'header', 'loop' => 'footer' ),
			'callback' => 'renard_post_date',
			'priority' => 5,
		),
		'comments' => array(
			'page'     => 'single',
			'position' => 'header',
			'callback' => 'renard_post_comments',
			'priority' => 1,
		),
		'categories' => array(
			'page'     => 'single',
			'position' => 'footer',
			'callback' => 'renard_post_categories',
			'priority' => 1,
		),
		'tags' => array(
			'page'     => 'single',
			'position' => 'footer',
			'callback' => 'renard_post_tags',
			'priority' => 5,
		)
	);

	/**
	 * Get 3rd party meta items to show in meta block (or disable default from child theme)
	 */
	$meta_items = apply_filters( 'renard_meta_items_data', $default_meta, $page, $position );
	$disable    = apply_filters( 'renard_disabled_meta', $disable );

	foreach ( $meta_items as $meta_key => $data ) {

		if ( is_array( $disable ) && in_array( $meta_key, $disable ) ) {
			continue;
		}
		if ( empty( $data['page'] ) || $page != $data['page'] ) {
			continue;
		}

		if ( empty( $data['position'] ) ) {
			continue;
		}

		if ( ! is_array( $data['position'] ) ) {
			$data['position'] = array(
				'single' => $data['position'],
				'loop'   => $data['position'],
			);
		}

		if ( $position != $data['position'][ $page ] ) {
			continue;
		}

		if ( empty( $data['callback'] ) || ! function_exists( $data['callback'] ) ) {
			continue;
		}

		$priority = ( ! empty( $data['priority'] ) ) ? absint( $data['priority'] ) : 10;

		add_action( 'renard_post_meta_' . $page . '_' . $position, $data['callback'], $priority );
	}

	do_action( 'renard_post_meta_' . $page . '_' . $position );

}

/**
 * Show read more button if enabled
 */
function renard_read_more() {

	if ( post_password_required() ) {
		return;
	}

	$is_enabled = renard_get_option( 'blog_more', true );

	if ( ! $is_enabled ) {
		return;
	}

	$text = renard_get_option( 'blog_more_text', __( 'Read More', 'renard' ) );

	printf(
		'<div class="entry-more-btn"><a href="%1$s" class="read-more">%2$s</a></div>',
		esc_url( get_permalink() ),
		esc_html( $text )
	);

}

/**
 * Custom posts navigation function
 */
function renard_posts_navigation( $args ) {

	$format     = '<span class="nav-links-label">%s</span>';
	$prev_label = sprintf( $format, __( 'Previos', 'renard' ) );
	$next_label = sprintf( $format, __( 'Next', 'renard' ) );

	$args = wp_parse_args( $args, array(
		'prev_text'          => $prev_label . '<span class="nav-links-title">%title</span>',
		'next_text'          => $next_label . '<span class="nav-links-title">%title</span>',
		'screen_reader_text' => __( 'Post navigation', 'renard' ),
	) );

	$navigation = '';
	$previous   = get_previous_post_link( '%link', $args['prev_text'] );
	$next       = get_next_post_link( '%link', $args['next_text'] );

	// Only add markup if there's somewhere to navigate to.
	if ( ! $previous && ! $next ) {
		return;
	}

	$navigation = _navigation_markup( $previous . $next, 'post-navigation', $args['screen_reader_text'] );

	echo $navigation;
}

/**
 * Show footer sidebars block
 */
function renard_footer_sidebars() {

	$sidebars = array(
		'footer-sidebar-1',
		'footer-sidebar-2',
		'footer-sidebar-3',
		'footer-sidebar-4',
	);

	$active_sidebars = array();

	foreach ( $sidebars as $sidebar ) {

		if ( ! is_active_sidebar( $sidebar ) ) {
			continue;
		}

		$active_sidebars[] = $sidebar;
	}

	if ( empty( $active_sidebars ) ) {
		return;
	}

	$md_index = 12 / count( $active_sidebars );

	echo '<div class="footer-sidebars">';
	echo '<div class="container">';
	echo '<div class="row">';

	foreach ( $active_sidebars as $sidebar ) {
		echo '<div class="col-md-' . $md_index . ' col-sm-12 col-xs-12">';
		dynamic_sidebar( $sidebar );
		echo '</div>';
	}

	echo '</div>';
	echo '</div>';
	echo '</div>';

}
