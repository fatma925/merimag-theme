<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = get_transient('merimag_customizer_options');

if( !is_array($options) || empty($options) && !is_rtl()) {
    $options['typography'] = array(
        'title' => __('Typography', 'merimag'),
        'help' => __('Customize typography of every text in your site, choose form 500 google fonts, customize size, weight, and everything', 'merimag'),
        'options' => merimag_get_theme_typography_settings(),
    );
    $options['styling'] = array(
        'title' => __('Styling', 'merimag'),
        'help' => __('Customize every section in your site, header, footer, content, widgets, menus and every thing else easily', 'merimag'),
        'options' => merimag_get_theme_areas_settings(),
    );
    set_transient('merimag_customizer_options', $options );
}

