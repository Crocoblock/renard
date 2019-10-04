<?php
/**
 * Template part for displaying the footer.
 *
 * @package renard
 */

?>
<footer id="colophon" class="site-footer" role="contentinfo">
	<?php renard_footer_sidebars(); ?>
	<div class="site-info">
		<div class="container">
			<?php
			$renard_custm_copyright = renard_get_option( 'footer_copyright' );
			if ( ! empty( $renard_custm_copyright ) ) {
				echo wp_kses_post( $renard_custm_copyright );
			} else {
				?>
				<div class="footer-logo">
					<a class="footer-logo-link" href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a>
				</div>
				<a rel="nofollow" href="<?php echo esc_url( __( 'http://wordpress.org/', 'renard' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'renard' ), 'WordPress' ); ?></a>
				<br>
				<?php
				printf(
					__( '%1$s WordPress Theme, &copy; 2016 <a href="%2$s" rel="nofollow">Crocoblock</a>.', 'renard' ),
					'Renard',
					'https://crocoblock.com/'
				);
				?>
			<?php } ?>
		</div>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
