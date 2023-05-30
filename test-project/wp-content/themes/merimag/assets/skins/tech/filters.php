<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#82b01f'; 
});
add_filter('merimag_post_template', function($template) {
	return '7';
});
add_filter('header_style', function( $header_style ) { return 2; } );
add_filter('default_grid_style', function($grid_style) {
	return 'two-column-4';
});

add_filter('footer_tags_title', function($title) {
	return esc_html__('# TRENDING', 'merimag');
});
add_filter('main_menu_items_hover_effect_style', function($effect) {
	return 'border-top';
});
add_filter('container_layout', function($layout) {
	return 'wide';
});

add_filter('ticker_position', function( $ticker_position) {
	return 'after_header';
});
if( is_rtl() ) {
	add_filter('navigation_main_menu_typography', function( $defaults ) {
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
add_filter('review_display_style', function( $style ) {
	return 'circle';
});
add_filter('block_title_style', function( $style) {
	return 'style-3';
});
add_filter('logo_height', function() {
	return 40;
});

add_filter('footer_instagram_usertag', function() {
	return '@apple';
});