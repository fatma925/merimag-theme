<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#EFAF4F'; 
});
add_filter('merimag_post_template', function($template) {
	return '5';
});
add_filter('header_style', function( $header_style ) { return 2; } );
add_filter('default_grid_style', function($grid_style) {
	return 'two-column-5';
});
add_filter('ticker_position', function($template) {
	return 'before_header';
});
add_filter('footer_tags_title', function($title) {
	return esc_html__('# TRENDING', 'merimag');
});
add_filter('footer_about_layout', function($layout) {
	return 'centered';
});
add_filter('main_menu_items_hover_effect_style', function($effect) {
	return 'background-close-border-top';
});
add_filter('container_layout', function($layout) {
	return 'wide';
});
add_filter('main_menu_social', function($layout) {
	return 'no';
});
add_filter('enable_top_menu', function($layout) {
	return 'yes';
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
	return 'style-12';
});
add_filter('general_widget_block_title_style', function( $style) {
	return 'style-8';
});
add_filter('logo_height', function() {
	return 50;
});
add_filter('mobile_logo_height', function() {
	return 40;
});
add_filter('footer_instagram_usertag', function() {
	return '@amouddou_travel_club';
});