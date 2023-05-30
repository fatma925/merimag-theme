<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#e12222'; 
});
add_filter('main_menu_items_hover_effect_style', function( $hover_effect_style ) { 
	return 'background'; 
});
add_filter('header_style', function( $header_style ) { return 2; } );
add_filter('main_menu_cart', function( $hover_effect_style ) { 
	return 'no'; 
});
add_filter('instagram_feed', function( $hover_effect_style ) { 
	return 'no'; 
});
add_filter('enable_top_menu', function( $hover_effect_style ) { 
	return 'no'; 
});
add_filter('ticker_position', function( $hover_effect_style ) { 
	return 'hide'; 
});
add_filter('merimag_post_template', function($template) {
	return '9';
});
add_filter('default_grid_style', function($grid_style) {
	return 'three-column-4';
});
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
		$defaults['size'] = '12';
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
	return 'style-11';
});



add_filter('footer_about_social', function($atts) {
	$atts['icons_color'] = merimag_get_principal_color();
	$atts['icons_theme'] = 'theme-5';
	return $atts;
});
add_filter('footer_instagram_usertag', function() {
	return '#funnyvideo';
});