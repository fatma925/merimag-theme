<?php
$principal_color = merimag_get_principal_color();
$style 			 = merimag_get_cached_skin_css();
if( !$style ) {
	$style 			 = '';
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'body' );

	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'site_container' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'site_container', true );
	$style 			.= merimag_get_skin_css( 'dark', array('background' => $principal_color), 'secondary_menu' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#f9e5f6'), 'ticker' );
	$style 			.= merimag_get_skin_css( 'light', array('background' => '#ffffff'), 'footer' );
	if( !is_customize_preview() ) {
		merimag_set_cached_skin_css($style);
	}
}

if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );

} else {
	wp_add_inline_style( 'merimag-skin-dynamic-css', $style );
}