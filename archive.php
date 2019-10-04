<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package renard
 */

get_header();

renard_do_location( 'archive', 'template-parts/archive' );

get_footer();
