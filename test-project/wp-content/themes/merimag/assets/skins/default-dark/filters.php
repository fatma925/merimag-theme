<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#2b8ecc'; 
});
add_filter('main_menu_items_hover_effect_style', function( $hover_effect_style ) { 
	return 'background'; 
});
add_filter('header_style', function( $header_style ) { return 4; } );

if( is_rtl() ) {
	add_filter('navigation_main_navigation_typography', function( $defaults ) {
		$defaults['family'] = 'Tajawal';
		$defaults['transform'] = 'uppercase';
		return $defaults;
	});
	add_filter('basic_headings_typography', function( $defaults ) {
		$defaults['family'] = 'Tajawal';
		return $defaults;
	});
	add_filter('basic_body_typography', function( $defaults ) {
		$defaults['family'] = 'Tajawal';
		return $defaults;
	});
} else {
	add_filter('navigation_main_navigation_typography', function( $defaults ) {
		$defaults['family'] = 'Inter';
		$defaults['transform'] = 'uppercase';
		$defaults['weight'] = '600';
		return $defaults;
	});
	add_filter('basic_headings_typography', function( $defaults ) {
		$defaults['family'] = 'Inter';
		$defaults['weight'] = '700';
		return $defaults;
	});
	add_filter('basic_body_typography', function( $defaults ) {
		$defaults['family'] = 'Inter';
		return $defaults;
	});
	
}
add_filter('block_title_style', function() {
	return 'style-14';
});
add_filter('ticker_position', function( $ticker_position) {
	return 'in_menu';
});
add_filter('merimag_post_template', function($template) {
	return '3';
});
add_filter( 'body_class', function($classes) {
	$classes[] = 'dark-skin';
	return $classes;
}, 10, 1);
add_filter('footer_about_social', function($atts) {
	$atts['icons_color'] = merimag_get_principal_color();
	$atts['icons_theme'] = 'theme-5';
	return $atts;
});
add_filter('logo_height', function() {
	return 45;
});
add_filter('footer_instagram_usertag', function() {
	return '@apple';
});