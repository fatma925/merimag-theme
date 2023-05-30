<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#be6b9f'; 
});
add_filter('merimag_post_template', function($template) {
	return '2';
});
add_filter('header_style', function( $header_style ) { return 3; } );

add_filter('default_grid_style', function($grid_style) {
	return 'two-column-5';
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
add_filter('footer_about_layout', function($layout) {
	return 'centered';
});

add_filter('ticker_position', function( $ticker_position) {
	return 'hide';
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
		$defaults['family'] = 'Antic Didone';
		$defaults['transform'] = 'uppercase';
		$defaults['weight'] = '600';
		return $defaults;
	});
	add_filter('basic_headings_typography', function( $defaults ) {
		$defaults['family'] = 'Antic Didone';
		$defaults['weight'] = '600';
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
	return 'style-4';
});

add_filter('footer_instagram_usertag', function() {
	return '@holyburgersp';
});