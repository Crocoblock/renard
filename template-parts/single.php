<?php
/**
 * Template part for displaying all single posts.
 *
 * @package renard
 */

?>
<div class="container">
	<div class="row">
		<main id="main" class="site-main col-md-8 col-sm-12 col-xs-12 <?php renard_sidebar_class(); ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'single' ); ?>

				<?php the_post_navigation(); ?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->

		<?php get_sidebar(); ?>

	</div>
</div>
