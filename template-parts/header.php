<?php
/**
 * Template part for displaying the header.
 *
 * @package renard
 */

?>
<header id="masthead" class="site-header" role="banner">
	<div class="site-branding">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="site-logo-wrap">
						<?php renard_logo(); ?>
						<div class="site-description"><?php bloginfo( 'description' ); ?></div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<?php renard_follow_list(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="site-nav">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<?php esc_html_e( 'Primary Menu', 'renard' ); ?>
						</button>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu'
							)
						);
						?>
					</nav><!-- #site-navigation -->
				</div>
			</div>
		</div>
	</div>
</header><!-- #masthead -->

<?php do_action( 'renard_showcase_area' ); ?>
