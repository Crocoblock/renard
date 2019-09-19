<?php
/**
 * Template part for displaying posts.
 *
 * @package renard
 */

the_posts_pagination(
	array(
		'prev_text' => sprintf( __( '%s Prev', 'renard' ), '<i class="fa fa-angle-left"></i>' ),
		'next_text' => sprintf( __( 'Next %s', 'renard' ), '<i class="fa fa-angle-right"></i>' ),
	)
);
