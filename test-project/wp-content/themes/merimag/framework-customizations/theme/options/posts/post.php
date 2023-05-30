<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
    'general_settings' => array(
        'title' => __('General', 'merimag'),
        'desc' => __('General post settings', 'merimag'),
        'type' => 'box',
        'options' => merimag_get_theme_single_general_settings(),
    ),
	'video_settings' => array(
        'title' => __('Featured Video', 'merimag'),
        'desc' => __('Select featured video', 'merimag'),
        'type' => 'box',
        'options' => merimag_get_theme_single_post_video_settings(),
    ),
    'audio_settings' => array(
        'title' => __('Featured Audio', 'merimag'),
        'desc' => __('Select featured audio', 'merimag'),
        'type' => 'box',
        'options' => merimag_get_theme_single_post_audio_settings(),
    ),
    'gallery_settings' => array(
        'title' => __('Featured Gallery', 'merimag'),
        'desc' => __('Select featured gallery', 'merimag'),
        'type' => 'box',
        'options' => merimag_get_theme_single_post_gallery_settings(),
    ),
    'single_settings' => array(
        'title' => __('Theme settings', 'merimag'),
        'desc' => __('Replace theme settings', 'merimag'),
        'type' => 'box',
        'options' => merimag_get_theme_single_post_settings(),
    ),
);