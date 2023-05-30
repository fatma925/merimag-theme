<?php

function merimag_enqueue_styles() {
    wp_enqueue_style( 'merimag-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'merimag-theme-css' ) );
}
add_action( 'wp_enqueue_scripts', 'merimag_enqueue_styles' );

function merimag_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'merimag', $lang );
}
add_action( 'after_setup_theme', 'merimag_lang_setup' );