<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#1f8a22'; 
});
add_filter('main_menu_items_hover_effect_style', function( $hover_effect_style ) { 
	return 'background'; 
});
add_filter('container_layout', function( $hover_effect_style ) { 
	return 'wide'; 
});
add_filter('header_style', function( $header_style ) { return 5; } );

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
		$defaults['weight'] = '400';
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
add_filter('default_grid_style', function($grid_style) {
	return 'two-column-3';
});
add_filter('ticker_position', function( $ticker_position) {
	return 'in_menu';
});

add_filter('merimag_post_template', function($template) {
	return '4';
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
add_filter('block_title_style', function( $style) {
	return 'style-13';
});
add_filter('footer_instagram_usertag', function() {
	return '@adidas';
});