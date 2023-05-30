<?php
$principal_color = merimag_get_principal_color();

add_filter('principal_color', function( $principal_color ) { 
	return '#199354'; 
});
add_filter('enable_top_menu', function() { 
	return 'no'; 
});
add_filter('ticker_position', function() { 
	return 'hide'; 
});
add_filter('header_style', function( $header_style ) { return 3; } );

add_filter('main_menu_items_hover_effect_style', function( $hover_effect_style ) { 
	return 'background-radius'; 
});
add_filter('block_title_style', function( $block_title_style) {
	return 'style-8';
});

add_filter('block_title_style_widget', function( $block_title_style) {
	return 'style-8';
});
add_filter('secondary_menu_text_size', function( $defaults ) {
	$defaults['size'] = 12;
	return $defaults;
});
add_filter('main_menu_text_size', function( $defaults ) {
	$defaults['size'] = 14;
	return $defaults;
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
add_filter('merimag_get_default_grid_data', function( $defaults ) {
	$defaults['grid_style'] = 'bordered';
	$defaults['columns'] = 3;
	$defaults['title_ellipsis'] = 2;
	$defaults['show_description'] = true;
	$defaults['show_read_more'] = true;
	return $defaults;
});
add_filter('logo_height', function() {
	return 104;
});
add_filter('mobile_logo_height', function() {
	return 44;
});


add_filter('footer_instagram_usertag', function() {
	return '#adventure';
});