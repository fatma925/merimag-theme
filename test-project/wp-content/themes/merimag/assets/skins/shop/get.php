<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'body' );

	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#1f1f1f'), 'footer' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#f5f5f5'), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color, 'principal_color' => '#41a3e8' ), 'ticker' );
	
if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}