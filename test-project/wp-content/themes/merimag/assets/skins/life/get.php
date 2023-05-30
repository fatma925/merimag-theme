<?php
$principal_color = merimag_get_principal_color();

	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#eeeeee'), 'body' );

	$style 			.= merimag_get_skin_css( 'light', array('background' => '#eeeeee'), 'site_container' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#eeeeee'), 'site_container', true );
	$style 			.= merimag_get_skin_css( 'light', array('background' => $principal_color), 'block_logo_header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#30344a'), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'main_menu' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#30344a'), 'sticky_header' );
	

	$style 			.= merimag_get_skin_css( 'light', array('background' => '#d4dae0'), 'ticker' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#252838'), 'footer' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color), 'mobile_header' );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => '#30344a'), 'mobile_menu_panel' );
	

if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}