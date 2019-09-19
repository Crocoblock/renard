<?php
/**
 * Template part for displaying posts.
 *
 * @package renard
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( renard_loop_classes() ); ?>>
	<header class="entry-header">
		<?php renard_post_thumbnail(); ?>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		<?php if ( 'post' == get_post_type() ) : ?>
			<?php renard_post_meta(); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php renard_blog_content(); ?>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
		<?php
			renard_read_more();
			renard_post_meta( 'loop', 'footer' );
		?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
