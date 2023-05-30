<?php
add_filter('principal_color', function( $hover_effect_style ) { 
	return '#000'; 
});
add_filter('merimag_post_template', function($template) {
	return '7';
});
add_filter('main_menu_items_hover_effect_style', function( $hover_effect_style ) { 
	return 'background-close-border-top'; 
});
add_filter('header_style', function( $header_style ) { return 2; } );

add_filter('instagram_feed', function($template) {
	return 'no';
});
add_filter('footer_tags_title', function($title) {
	return esc_html__('# TRENDING', 'merimag');
});
add_filter('default_grid_style', function($grid_style) {
	return 'one-column-5';
});
add_filter('container_layout', function($layout) {
	return 'wide';
});
add_filter('main_menu_social', function($layout) {
	return 'no';
});
add_filter('enable_top_menu', function($layout) {
	return 'no';
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
	return 'style-8';
});
add_filter('footer_instagram_usertag', function() {
	return '@apple';
});