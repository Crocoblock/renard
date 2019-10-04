<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package renard
 */

get_header();

renard_do_location( 'archive', 'template-parts/archive' );

get_footer();
