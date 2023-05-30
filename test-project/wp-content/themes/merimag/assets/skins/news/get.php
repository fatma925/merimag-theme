<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'body' );

	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff', 'background_gradient' => array('#ffffff', merimag_adjustBrightness($principal_color, 89))), 'header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color), 'main_menu' );

	$style 			.= merimag_get_skin_css( 'dark', array('background' => merimag_adjustBrightness( $principal_color, 60 )), 'secondary_menu' );

	$style 			.= merimag_get_skin_css( 'light', array('background' => '#d4dae0'), 'ticker' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#092035'), 'footer' );
	
 
if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}