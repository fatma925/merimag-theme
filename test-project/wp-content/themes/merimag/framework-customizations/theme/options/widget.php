<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'styling' => array(
		'label' => __('Styling settings', 'merimag'),
		'type' => 'popup',
		'button' => __('Widget style', 'merimag'),
	    'popup-options' => merimag_get_block_style_options(),
	    'popup-title' => __('Widget style', 'merimag'),
	    'modal-size' => 'medium', // small, medium, large
	),
);