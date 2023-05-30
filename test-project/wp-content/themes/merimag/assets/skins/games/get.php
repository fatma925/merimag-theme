<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'dark' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#1f1f1f'), 'body' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => 'rgba(0,0,0,0.8)'), 'site_container' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => 'rgba(0,0,0,.2)'), 'main_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => 'rgba(0,0,0,.2)'), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color, 'principal_color' => '#907c7c', 'background_gradient' => array($principal_color, '#4f207f','to right')), 'header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color, 'principal_color' => '#907c7c', 'background_gradient' => array($principal_color, '#4f207f','to right')), 'main_menu_sub_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color, 'principal_color' => '#907c7c', 'background_gradient' => array($principal_color, '#4f207f','to right')), 'secondary_menu_sub_menu' );

	$style 			.= merimag_get_skin_css('dark', array('background' => $principal_color, 'principal_color' => '#907c7c', 'background_gradient' => array($principal_color, '#4f207f','to right')), 'mobile_menu_panel');
	$style 			.= merimag_get_skin_css('dark', array('background' => $principal_color, 'principal_color' => '#907c7c', 'background_gradient' => array($principal_color, '#4f207f','to right')), 'footer');
	
if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}

	
