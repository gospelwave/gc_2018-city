<?php
function gc_city_theme_enqueue_styles() {

	$parent_style = 'gc_2018-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', false, wp_get_theme()->get( 'Version' ) );
	wp_enqueue_style( 'gc_2018-city-style',
		get_stylesheet_directory_uri() . '/css/style_v0.1.css',
		array( $parent_style ),
		wp_get_theme()->get( 'Version' )
	);
}

add_action( 'wp_enqueue_scripts', 'gc_city_theme_enqueue_styles' );

function gc_city_theme_load_theme_textdomain() {
	load_theme_textdomain( 'gc_2018', get_stylesheet_directory() . '/languages' );
	load_child_theme_textdomain( 'gc_2018-city', get_template_directory() . '/languages' );

}

add_action( 'after_setup_theme', 'gc_city_theme_load_theme_textdomain' );


require_once( __DIR__ . '/options.php' );

require_once( __DIR__ . '/includes/gc_service.php' );

require_once( __DIR__ . '/includes/gc_event.php' );

require_once( __DIR__ . '/includes/gc_location.php' );

require_once( __DIR__ . '/includes/acf_fields.php' );