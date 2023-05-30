<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
    'single_settings' => array(
        'title' => __('General', 'merimag'),
        'type' => 'box',
        'options' => merimag_get_theme_single_page_settings(),
    ),
    
);