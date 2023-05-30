<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'body' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#082b4a'), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#11436f'), 'header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#fab713', 'principal_color' => '#0f263a'), 'main_menu' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff', 'principal_color' => $principal_color ), 'main_menu_sub_menu' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff', 'principal_color' => $principal_color ), 'sticky_header_sub_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#11436f'), 'mobile_menu_panel' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#092035'), 'footer' );



if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}