<?php

add_action( 'wp_enqueue_scripts', 'scripts_styles' );
function scripts_styles()
{
	$ver = '1.10';
	
	global $temp_dir;
	$temp_dir = get_template_directory_uri();
	
	/*
	 *
	 *
		Libraries
	 *
	 *
	 */
	
	// Swiper JS
	wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], $ver );
	wp_enqueue_script( 'swiper-scripts', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [ 'jquery' ], $ver, true );
	
	// Select 2
	wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', [], $ver );
	wp_enqueue_script( 'select2-scripts', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', [ 'jquery' ], $ver, true );
	
	/*
	 *
	 * Main
	 *
	 */
	
	// General
	wp_enqueue_style( 'stylesheet', get_stylesheet_uri(), [], $ver );
	wp_enqueue_script( 'scripts', $temp_dir . '/js/scripts.js', [ 'jquery', 'swiper-scripts' ], $ver, true );
	
	// Ajax
	wp_enqueue_script( 'ajax', $temp_dir . '/js/ajax.js', [ 'jquery', 'select2-scripts' ], $ver, true );
	wp_localize_script( 'ajax', 'variables', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
	] );
}