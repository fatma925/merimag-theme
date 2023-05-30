<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

    $options['general'] = array(
        'title' => __('General', 'merimag'),
        'type' => 'tab',
        'help' => __('General settings', 'merimag'),
        'options' => merimag_get_theme_general_settings(),
    );
    $options['layout'] = array(
        'title' => __('Layout', 'merimag'),
        'type' => 'tab',
        'help' => __('Can be left sidebar, right sidebar or no sidebar', 'merimag'),
        'options' => merimag_get_theme_layout_settings(),
    );
    $options['listing'] = array(
        'title' => __('Listing', 'merimag'),
        'type' => 'tab',
        'help' => __('More than 30 grid style to choose from, custom grid style for every page like category archives, tag archives, search archives...', 'merimag'),
        'options' => merimag_get_theme_grid_settings(),
    );
    $options['social_networks'] = array(
        'title' => __('Social networks', 'merimag'),
        'type' => 'tab',
        'help' => __('Display your social networks in your site, it shows up in top menu, it will also display in footer about section, also can be displayed in the social networks element that you can add in elementor pages', 'merimag'),
        'options' => merimag_social_options(),
    );
    $options['contact_infos'] = array(
        'title' => __('Contact infos', 'merimag'),
        'type' => 'tab',
        'help' => __('Add your site contact infos, you can display them by adding contact infos widget in a sidebar or in an elementor column', 'merimag'),
        'options' => array(
            'contact_infos' => array(
              'type' => 'addable-box',
              'label' => __('Elements', 'merimag'),
              'box-options' => merimag_contact_item_options(),
              'template' => '{{- title }}',
              'title_field' => '{{{ text }}}',
            ),
        ),
    );
    $options['footer_about'] = array(
        'title' => __('Footer about', 'merimag'),
        'type' => 'tab',
        'help' => __('A nice section in footer that display the site a logo a brief description and your social networks, it has two layouts inline or centered', 'merimag'),
        'options' => merimag_get_theme_footer_about_settings(),
    );
    $options['footer_tags'] = array(
        'title' => __('Footer tags', 'merimag'),
        'type' => 'tab',
        'help' => __('A nice section in footer that display the site trending tags', 'merimag'),
        'options' => merimag_get_theme_footer_tags_settings(),
    );

    $options['footer_copyrights'] = array(
        'title' => __('Footer copyrights', 'merimag'),
        'type' => 'tab',
        'help' => __('Add your copyrights text here', 'merimag'),
        'options' => merimag_get_theme_footer_copyrights_settings(),
    );
    $options['news_ticker'] = array(
        'title' => __('News ticker', 'merimag'),
        'type' => 'tab',
        'help' => __('News ticker options that can be displayed before header, after header or in top menu', 'merimag'),
        'options' => merimag_ticker_settings(),
    );
    $options['post_settings'] = array(
        'title' => __('Post settings', 'merimag'),
        'type' => 'tab',
        'help' => __('Discover nice things in your posts, more than 13 templates, with special featured like author box, related posts, inline related posts, read also, next / prev, and more...', 'merimag-backend'),
        'options' => merimag_get_theme_post_settings(),
    );
    $options['ads'] = array(
        'title' => __('Advertisement', 'merimag'),
        'type' => 'tab',
        'help' => __('Install ads for wp plugin create group ads and a list of places will show up after that you can select group ad that you have create it and assign it to a place in theme', 'merimag-backend'),
        'options' => merimag_get_theme_ads_settings(),
    );
    $options['builder_sections'] = array(
        'title' => __('Builder sections ( Experimental )', 'merimag'),
        'type' => 'tab',
        'help' => __('Theme have a custom areas that you can put custom things, create pages with elementor and display it in your choosen area, before header or after header, before footer and more', 'merimag-backend'),
        'options' => merimag_get_theme_builder_sections_settings(),
    );
  

