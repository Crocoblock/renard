<?php
/**
 * renard Theme Customizer.
 *
 * @package renard
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function renard_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'renard_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function renard_customize_preview_js() {
	wp_enqueue_script( 'renard_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'renard_customize_preview_js' );

/**
 * Adds theme-related customizer elements
 *
 * WordPress 3.4 Required
 */
add_action( 'customize_register', 'renard_add_customizer' );

if( ! function_exists( 'renard_add_customizer' ) ) {

	function renard_add_customizer( $wp_customize ) {

		/* Slider section
		----------------------------------------------------*/
		$wp_customize->add_section( 'renard_slider' , array(
			'title'      => __('Slider','renard'),
			'priority'   => 61,
		) );

		/* Enable slider */
		$wp_customize->add_setting( 'renard[slider_enabled]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_slider_enabled', array(
				'label'    => __( 'Enable/disable slider', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slider_enabled]',
				'type'     => 'checkbox',
				'priority' => 1
		) );

		/* Slider visibility */
		$wp_customize->add_setting( 'renard[slider_visibility]', array(
				'default'           => 'front',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_select'
		) );
		$wp_customize->add_control( 'renard_slider_visibility', array(
				'label'    => __( 'Slider visibility', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slider_visibility]',
				'type'     => 'select',
				'priority' => 2,
				'choices'  => array(
						'front' => __( 'Only on Front page', 'renard' ),
						'all'   => __( 'On all pages', 'renard' )
					)
		) );

		/* Get slides from */
		$wp_customize->add_setting( 'renard[slides_from]', array(
				'default'           => 'recent_posts',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_select'
		) );
		$wp_customize->add_control( 'renard_slides_from', array(
				'label'    => __( 'Get slides from', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slides_from]',
				'type'     => 'select',
				'priority' => 2,
				'choices'  => apply_filters(
					'renard_slides_from_choices',
					array(
						'recent_posts' => __( 'Recent Posts (Default)', 'renard' ),
						'sticky'       => __( 'Sticky Posts (Recommended)', 'renard' ),
						'category'     => __( 'Specific Category', 'renard' )
					)
				)
		) );

		/* Category to get from */
		$wp_customize->add_setting( 'renard[slides_cat]', array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control( 'renard_slides_cat', array(
				'label'       => __( 'Category slug to get slides from (only if Specific Category selected)', 'renard' ),
				'section'     => 'renard_slider',
				'settings'    => 'renard[slides_cat]',
				'type'        => 'text',
				'priority'    => 3
		) );

		/* Slides number */
		$wp_customize->add_setting( 'renard[slides_num]', array(
				'default'           => 4,
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_num'
		) );
		$wp_customize->add_control( 'renard_slides_num', array(
				'label'       => __( 'Slides number to show', 'renard' ),
				'section'     => 'renard_slider',
				'settings'    => 'renard[slides_num]',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				),
				'priority'    => 4
		) );

		/* Show/hide slides banners */
		$wp_customize->add_setting( 'renard[slider_banner]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_slider_banner', array(
				'label'    => __( 'Show/hide slider banner', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slider_banner]',
				'type'     => 'checkbox',
				'priority' => 5
		) );

		/* Banner button text */
		$wp_customize->add_setting( 'renard[slider_btn_text]', array(
				'default'           => __( 'Read', 'renard' ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control( 'renard_slider_btn_text', array(
				'label'       => __( 'Banner read more button text', 'renard' ),
				'section'     => 'renard_slider',
				'settings'    => 'renard[slider_btn_text]',
				'type'        => 'text',
				'priority'    => 6
		) );

		/* Show arrows */
		$wp_customize->add_setting( 'renard[slider_arrows]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_slider_arrows', array(
				'label'    => __( 'Show/hide control arrows', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slider_arrows]',
				'type'     => 'checkbox',
				'priority' => 7
		) );

		/* Show pager */
		$wp_customize->add_setting( 'renard[slider_pager]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_slider_pager', array(
				'label'    => __( 'Show/hide pager control', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slider_pager]',
				'type'     => 'checkbox',
				'priority' => 8
		) );

		/* Animation type */
		$wp_customize->add_setting( 'renard[slider_animation]', array(
				'default'           => 'fade',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_select'
		) );
		$wp_customize->add_control( 'renard_slider_animation', array(
				'label'    => __( 'Select animation type', 'renard' ),
				'section'  => 'renard_slider',
				'settings' => 'renard[slider_animation]',
				'type'     => 'select',
				'priority' => 9,
				'choices'  => array(
					'fade'  => __( 'Fade', 'renard' ),
					'slide' => __( 'Slide', 'renard' ),
				)
		) );

		/* Blog section
		----------------------------------------------------*/
		$wp_customize->add_section( 'renard_blog' , array(
			'title'      => __('Blog','renard'),
			'priority'   => 62,
		) );

		/* Blog content */
		$wp_customize->add_setting( 'renard[blog_content]', array(
				'default'           => 'excerpt',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_select'
		) );
		$wp_customize->add_control( 'renard_blog_content', array(
				'label'    => __( 'Blog content shows:', 'renard' ),
				'section'  => 'renard_blog',
				'settings' => 'renard[blog_content]',
				'type'     => 'select',
				'priority' => 1,
				'choices'  => array(
					'excerpt' => __( 'Only excerpt', 'renard' ),
					'content' => __( 'Full content', 'renard' )
				)
		) );

		/* Loop featured image */
		$wp_customize->add_setting( 'renard[blog_loop_image]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_blog_loop_image', array(
				'label'    => __( 'Loop page: show featured image', 'renard' ),
				'section'  => 'renard_blog',
				'settings' => 'renard[blog_loop_image]',
				'type'     => 'checkbox',
				'priority' => 2
		) );

		/* Single featured image */
		$wp_customize->add_setting( 'renard[blog_single_image]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_blog_single_image', array(
				'label'    => __( 'Single page: show featured image', 'renard' ),
				'section'  => 'renard_blog',
				'settings' => 'renard[blog_single_image]',
				'type'     => 'checkbox',
				'priority' => 3
		) );

		/* Loop show button */
		$wp_customize->add_setting( 'renard[blog_more]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_blog_more', array(
				'label'    => __( 'Loop page: show read more button', 'renard' ),
				'section'  => 'renard_blog',
				'settings' => 'renard[blog_more]',
				'type'     => 'checkbox',
				'priority' => 4
		) );

		/* Read button text */
		$wp_customize->add_setting( 'renard[blog_more_text]', array(
				'default'           => __( 'Read', 'renard' ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control( 'renard_blog_more_text', array(
				'label'       => __( 'Loop page: read more button text', 'renard' ),
				'section'     => 'renard_blog',
				'settings'    => 'renard[blog_more_text]',
				'type'        => 'text',
				'priority'    => 5
		) );

		/* Sidebar position */
		$wp_customize->add_setting( 'renard[sidebar_position]', array(
				'default'           => 'right',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_select'
		) );
		$wp_customize->add_control( 'renard_sidebar_position', array(
				'label'    => __( 'Sidebar position', 'renard' ),
				'section'  => 'renard_blog',
				'settings' => 'renard[sidebar_position]',
				'type'     => 'select',
				'priority' => 6,
				'choices'  => array(
					'right' => __( 'Right', 'renard' ),
					'left'  => __( 'Left', 'renard' )
				)
		) );

		/* Footer section
		----------------------------------------------------*/
		$wp_customize->add_section( 'renard_footer' , array(
			'title'      => __('Footer','renard'),
			'priority'   => 63,
		) );

		/* Custom copyright */
		$wp_customize->add_setting( 'renard[footer_copyright]', array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_filter_post_kses'
		) );
		$wp_customize->add_control( 'renard_footer_copyright', array(
				'label'       => __( 'Set custom copyright text', 'renard' ),
				'section'     => 'renard_footer',
				'settings'    => 'renard[footer_copyright]',
				'type'        => 'textarea',
				'priority'    => 1
		) );

		/* About section
		----------------------------------------------------*/
		$wp_customize->add_section( 'renard_about' , array(
			'title'      => __('About box','renard'),
			'priority'   => 81,
		) );

		/* Enable about */
		$wp_customize->add_setting( 'renard[about_enabled]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_about_enabled', array(
				'label'    => __( 'Enable About box in sidebar', 'renard' ),
				'section'  => 'renard_about',
				'settings' => 'renard[about_enabled]',
				'type'     => 'checkbox',
				'priority' => 1
		) );

		/* About image */
		$wp_customize->add_setting( 'renard[about_img]', array(
				'default'           => '',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_image'
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'renard_about_img', array(
			'label'    => __( 'About Image', 'renard' ),
			'section'  => 'renard_about',
			'settings' => 'renard[about_img]',
			'priority' => 3
		) ) );

		/* About message */
		$wp_customize->add_setting( 'renard[about_message]', array(
				'default'           => __( 'Hello! And welcome to my personal website!', 'renard' ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'wp_filter_post_kses'
		) );
		$wp_customize->add_control( 'renard_about_message', array(
				'label'       => __( 'Set about box message text', 'renard' ),
				'section'     => 'renard_about',
				'settings'    => 'renard[about_message]',
				'type'        => 'textarea',
				'priority'    => 4
		) );

		/* Follow section
		----------------------------------------------------*/
		$wp_customize->add_section( 'renard_follow' , array(
			'title'      => __('Follow box in header','renard'),
			'priority'   => 82,
		) );

		$wp_customize->add_setting( 'renard[follow_enabled]', array(
				'default'           => '1',
				'type'              => 'theme_mod',
				'sanitize_callback' => 'renard_sanitize_checkbox'
		) );
		$wp_customize->add_control( 'renard_follow_enabled', array(
				'label'    => __( 'Enable Follow box in sidebar', 'renard' ),
				'section'  => 'renard_follow',
				'settings' => 'renard[follow_enabled]',
				'type'     => 'checkbox',
				'priority' => 1
		) );

	}

}

/**
 * Sanitize URL function for customizer
 *
 * @copyright Copyright (c) 2015, WordPress Theme Review Team
 */
function renard_sanitize_url( $url ) {
	return esc_url_raw( $url );
}

/**
 * Sanitize image URL
 *
 * @copyright Copyright (c) 2015, WordPress Theme Review Team
 */
function renard_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon'
	);
	// Return an array with file extension and mime_type.
	$file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
	return ( $file['ext'] ? $image : $setting->default );
}

/**
 * Sanitize checkbox for customizer
 *
 * @copyright Copyright (c) 2015, WordPress Theme Review Team
 */
function renard_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize callback select input
 *
 * @copyright Copyright (c) 2015, WordPress Theme Review Team
 */
function renard_sanitize_select( $input, $setting ) {

	// Ensure input is a slug.
	$input = sanitize_key( $input );

	$control = str_replace( '[', '_', trim( $setting->id, ']' ) );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $control )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize numeric value
 *
 * @copyright Copyright (c) 2015, WordPress Theme Review Team
 */
function renard_sanitize_num( $number ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );

	// If the input is an absolute integer, return it; otherwise, return the default
	return ( $number ? $number : $setting->default );
}
