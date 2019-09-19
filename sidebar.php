<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package renard
 */
?>

<div id="secondary" class="widget-area col-md-4 col-sm-12 col-xs-12" role="complementary">
	<?php
		/**
		 * Hook fires before main sidebar output started
		 */
		do_action( 'renard_before_sidebar' );
		dynamic_sidebar( 'sidebar-1' );
	?>
</div><!-- #secondary -->
