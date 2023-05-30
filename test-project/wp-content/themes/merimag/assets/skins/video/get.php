<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'body' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#242424'), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'sticky_header' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'main_menu' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'general_content_area' );
	$style 			.= merimag_get_skin_css('dark', array('background' => '#404040'), 'footer');
	
if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}

	
