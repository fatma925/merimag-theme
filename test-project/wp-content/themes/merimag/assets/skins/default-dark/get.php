<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'dark' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#101010'), 'header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#101010'), 'site_container' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#000000'), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#000000'), 'sticky_header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#1f1f1f'), 'main_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#1f1f1f'), 'general_content_area' );
	$style 			.= merimag_get_skin_css('dark', array('background' => '#000000'), 'mobile_menu_panel');
	$style 			.= merimag_get_skin_css('dark', array('background' => '#1f1f1f'), 'footer');
	

if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );
} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}

	
