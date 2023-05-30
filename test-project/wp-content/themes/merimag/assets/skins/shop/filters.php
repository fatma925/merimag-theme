<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#3994fa'; 
});
add_filter('merimag_post_template', function($template) {
	return '18';
});
add_filter('header_style', function( $header_style ) { return 8; } );

add_filter('footer_tags_title', function($title) {
	return esc_html__('# TRENDING', 'merimag');
});
add_filter('main_menu_items_hover_effect_style', function($effect) {
	return 'background-close-border-top';
});
add_filter('container_layout', function($layout) {
	return 'wide';
});
add_filter('header_contact_infos', function( $header_style ) { return 'yes'; } );
add_filter('header_search', function( $header_style ) { return 'form'; } );

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
	return 'style-16';
});

add_filter('ticker_position', function( $ticker_position) {
	return 'hide';
});
add_filter('merimag_post_template', function($template) {
	return '3';
});
add_filter('review_display_style', function( $style ) {
	return 'circle';
});
add_filter('logo_height', function() {
	return 40;
});
add_filter('footer_instagram_usertag', function() {
	return '@jackandjones';
});
