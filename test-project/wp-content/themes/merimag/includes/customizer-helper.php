<?php if ( ! defined( 'THEME_VERSION' ) ) {
  die( 'Forbidden' );
}
/**
 * Theme general settings.
 *
 * @param bool $single
 * @return array
 */
function merimag_get_theme_general_settings( $slug = '' ) {
  $theme_colors = merimag_get_theme_colors( true );
  $theme_colors_options = array();
  foreach( $theme_colors as $theme_color ) {
    $theme_colors_options['theme_color_' . $theme_color] = array(
        'label' => sprintf(__('Theme color %s', 'merimag-backend'), $theme_color ),
        'type' => 'color-picker-v2',
        'help' => sprintf(__('Define custom color for theme %s badge', 'merimag-backend'), $theme_color ),
    );
  }
  $theme_colors_options['header_search_icon_color'] = array(
    'label' => __('Header cart icon color', 'merimag-backend'),
     'type' => 'color-picker-v2',
     'help' => __('Define custom color for search icon', 'merimag-backend'),
  );
  $theme_colors_options['header_cart_icon_color'] = array(
    'label' => __('Header cart icon color', 'merimag-backend'),
     'type' => 'color-picker-v2',
     'help' => __('Define custom color for cart icon', 'merimag-backend'),
  );
  $theme_colors_options['header_account_icon_color'] = array(
    'label' => __('Header account icon color', 'merimag-backend'),
    'help' => __('Define custom color for account icon', 'merimag-backend'),
     'type' => 'color-picker-v2',
  );
  $options = array(
    'general' => array(
      'type' => 'tab',
      'title' => __('General', 'merimag-backend'),
      'options' => array(
          'container_layout'    => array(
            'type'  => 'select',
            'label' => esc_html__('Container layout', 'merimag-backend'),
            'help' => __( 'Container layout can be wide or boxed', 'merimag-backend' ),
            'choices' => array(
               'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ), 
               'wide'  => __('Wide', 'merimag-backend'),
               'boxed' => __('Boxed', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'loading_animation'    => array(
            'type'  => 'select',
            'label' => esc_html__('Loading animation', 'merimag-backend'),
            'help' => __( 'Choose from 12 loading animations, it appears on ajax tabs loading or ajax pagination', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              '1' => 'Style 1',
              '2' => 'Style 2',
              '3' => 'Style 3',
              '4' => 'Style 4',
              '5' => 'Style 5',
              '6' => 'Style 6',
              '7' => 'Style 7',
              '8' => 'Style 8',
              '9' => 'Style 9',
              '11' => 'Style 11',
              '12' => 'Style 12',
            ),
          ),
          'block_title_style' => array(
            'type'  => 'select',
            'label' => esc_html__('Block heading style', 'merimag-backend'),
            'help' => __( 'Choose from over 14 block title styles', 'merimag-backend' ),
            'value' => 'default',
            'choices' => merimag_get_recognized_block_title_styles(),
          ),
          'enable_breadcrumbs'    => array(
              'label' => __( 'Enable breadcrumbs', 'merimag-backend' ),
              'type'  => 'select',
              'help' => __( 'Breadcrumbs appears above page title in all posts, pages, archives, activate it may help in seo and also can optimize user experience in your site', 'merimag-backend' ),
              'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                'yes' => __('Yes', 'merimag-backend'),
                'no' => __('No', 'merimag-backend'),
              ),
          ),
          'sticky_sidebar'    => array(
              'label' => __( 'Sticky sidebar', 'merimag-backend' ),
              'type'  => 'select',
              'help' => __( 'Activate sticky sidebar can improve user experience and improve sidebar ads visibilty', 'merimag-backend' ),
              'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                'yes' => __('Yes', 'merimag-backend'),
                'no' => __('No', 'merimag-backend'),
              ),
          ),
          'sticky_header'    => array(
              'label' => __( 'Sticky header', 'merimag-backend' ),
              'type'  => 'select',
              'help' => __( 'Activate sticky header to improve user experience', 'merimag-backend' ),
              'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                'yes' => __('Yes', 'merimag-backend'),
                'no' => __('No', 'merimag-backend'),
              ),
          ),
          'sticky_mobile_header'    => array(
              'label' => __( 'Sticky header on mobile', 'merimag-backend' ),
              'type'  => 'select',
              'help' => __( 'Activate sticky header to improve user experience', 'merimag-backend' ),
              'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                'yes' => __('Yes', 'merimag-backend'),
                'no' => __('No', 'merimag-backend'),
              ),
          ),
          'enable_cache'    => array(
              'label' => __( 'Enable cache', 'merimag-backend' ),
              'type'  => 'select',
              'help' => __( 'Activate cache for high site performance, this is a theme part cache option to cache all queries and css parse', 'merimag-backend' ),
              'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                'yes' => __('Yes', 'merimag-backend'),
                'no' => __('No', 'merimag-backend'),
              ),
          ),
          'show_author'    => array(
              'label' => __( 'Show author meta', 'merimag-backend' ),
              'type'  => 'select',
              'help' => __( 'Show or hide post authors', 'merimag-backend' ),
              'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                'yes' => __('Yes', 'merimag-backend'),
                'no' => __('No', 'merimag-backend'),
              ),
          ),
       )
    ),
    'site_logo' => array(
        'type' => 'tab',
        'title' => __('Logo', 'merimag-backend'),
        'help' => __( 'You can set the main logo for your site, there is also options to set different logos for sticky header, mobile header and google amp logo', 'merimag-backend' ),
        'options' => array(
            'logo'    => array(
                'label' => __( 'Site logo', 'merimag-backend' ),
                'help' => __( 'The main logo', 'merimag-backend' ),
                'type'  => 'upload',
                'only_images' => true,
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'logo_height'    => array(
                'label' => __( 'Site logo maximum height', 'merimag-backend' ),
                'help' => __( 'The main logo maximum height', 'merimag-backend' ),
                'type'  => 'number',
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'sticky_header_logo'    => array(
                'label' => __( 'Sticky header logo', 'merimag-backend' ),
                'help' => __( 'The sticky header logo', 'merimag-backend' ),
                'type'  => 'upload',
                'only_images' => true,
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'sticky_header_logo_height'    => array(
                'label' => __( 'Sticky header logo height', 'merimag-backend' ),
                'help' => __( 'The sticky header logo maximum height', 'merimag-backend' ),
                'type'  => 'number',
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'mobile_logo'    => array(
                'label' => __( 'Mobile header logo', 'merimag-backend' ),
                'desc'  => __( 'Choose your logo', 'merimag-backend' ),
                'help' => __( 'The mobile header logo', 'merimag-backend' ),
                'type'  => 'upload',
                'only_images' => true,
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'mobile_logo_height'    => array(
                'label' => __( 'Mobile header logo maximum height', 'merimag-backend' ),
                'help' => __( 'The mobile header logo maximum height', 'merimag-backend' ),
                'type'  => 'number',
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'amp_logo'    => array(
                'label' => __( 'AMP logo', 'merimag-backend' ),
                'help' => __( 'The mobile amp header logo', 'merimag-backend' ),
                'type'  => 'upload',
                'only_images' => true,
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
            'amp_logo_height'    => array(
                'label' => __( 'AMP logo maximum height', 'merimag-backend' ),
                'help' => __( 'The mobile amp header logo maximum height', 'merimag-backend' ),
                'type'  => 'number',
                'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
            ),
        ),
    ),
    'header' => array(
      'type' => 'tab',
      'title' => __('Header', 'merimag-backend'),
      'options' => array(
          'header_style' => array(
            'type'  => 'select',
            'label' => __('Header style', 'merimag-backend'),
            'help' => __( 'Choose from 10 header styles, beautifuly designed for all site purposes', 'merimag-backend' ),
            'value' => 'default',
            'choices' => array(
                'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                '1' => __('Style 1', 'merimag-backend'),
                '2' => __('Style 2', 'merimag-backend'),
                '3' => __('Style 3', 'merimag-backend'),
               '4' => __('Style 4', 'merimag-backend'),
               '5' => __('Style 5', 'merimag-backend'),
               '6' => __('Style 6', 'merimag-backend'),
               '7' => __('Style 7', 'merimag-backend'),
               '8' => __('Style 8', 'merimag-backend'),
               '9' => __('Style 9', 'merimag-backend'),
               '10' => __('Style 10', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'header_spacing' => array(
            'type'  => 'select',
            'value' => 'default',
            'help' => __( 'Set the spacing before and after the site logo', 'merimag-backend' ),
            'label' => __('Header Spacing', 'merimag-backend'),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'small'   => __('Small', 'merimag-backend'),
              'medium'  => __('Medium', 'merimag-backend'),
              'big'     => __('Big', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'header_contact_infos' => array(
            'label' => __('Header contact infos', 'merimag-backend'),
            'help' => __( 'Activate header contact infos, very useful if your site is an online shop, you can add your contact infos in the contact infos section', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),

          'header_search' => array(
                'label' => __('Header search', 'merimag-backend'),
                'help' => __( 'Activate header search, very useful if your site is an online shop to help customers find your products', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'form'   => __('Form', 'merimag-backend'),
                  'icon'  => __('Icon', 'merimag-backend'),
                  'hide'  => __('Hide', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
          'header_cart' => array(
            'label' => __('Header cart icon', 'merimag-backend'),
            'help' => __( 'Activate header cart icon, very useful if your site is an online shop to help customers view what they actualy added to there cart easily', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'header_account' => array(
            'label' => __('Header account link', 'merimag-backend'),
            'help' => __( 'Activate header cart icon, very useful if your site is an online shop to help customers access there account to view there orders', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'header_social' => array(
            'label' => __('Header social links', 'merimag-backend'),
            'type'  => 'select',
            'help' => __( 'Show your social links in the header to improve visibilty of your social profiles, you can set your social profiles in the social links section', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'header_stacked_icons' => array(
            'label' => __('Header stacked icons', 'merimag-backend'),
            'help' => __( 'Show header icons in another nice way to make them more attractive', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          $slug ? 'header_ad' : 'default_header_ad' => array(
            'type' => 'multi-picker',
            'picker' => array(
              'ad' => array(
                'type' => 'select',
                'label'   => __('Show ad', 'merimag-backend'),
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'show' => __('Yes', 'merimag-backend'),
                  'hide' => __('No', 'merimag-backend'),
                )
              )
            ),
            'choices' => array(
              'show' => merimag_get_ad_options(),
            ),
            'title' => __('Header ad', 'merimag-backend'),
          ),
      )
    ),
    'sticky_header' => array(
          'type' => 'tab',
          'title' => __('Sticky header', 'merimag-backend'),
          'options' => array(
              'sticky_header_spacing' => array(
                'type'  => 'select',
                'value' => 'default',
                'help' => __( 'Set the spacing before and after the site logo', 'merimag-backend' ),
                'label' => __('Sticky Header Spacing', 'merimag-backend'),
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'small'   => __('Small', 'merimag-backend'),
                  'medium'  => __('Medium', 'merimag-backend'),
                  'big'     => __('Big', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'sticky_header_contact_infos' => array(
                'label' => __('Sticky Header contact infos', 'merimag-backend'),
                'help' => __( 'Activate sticky header contact infos, very useful if your site is an online shop, you can add your contact infos in the contact infos section', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
 
              'sticky_header_search' => array(
                'label' => __('Sticky Header search', 'merimag-backend'),
                'help' => __( 'Activate sticky header search, very useful if your site is an online shop to help customers find your products', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'form'   => __('Form', 'merimag-backend'),
                  'icon'  => __('Icon', 'merimag-backend'),
                  'hide'  => __('Hide', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),

              'sticky_header_cart' => array(
                'label' => __('Sticky Header cart icon', 'merimag-backend'),
                'help' => __( 'Activate sticky header cart icon, very useful if your site is an online shop to help customers view what they actualy added to there cart easily', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'sticky_header_account' => array(
                'label' => __('Sticky Header account link', 'merimag-backend'),
                'help' => __( 'Activate sticky header cart icon, very useful if your site is an online shop to help customers access there account to view there orders', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'sticky_header_social' => array(
                'label' => __('Sticky social links', 'merimag-backend'),
                'type'  => 'select',
                'help' => __( 'Show your social links in the sticky header to improve visibilty of your social profiles, you can set your social profiles in the social links section', 'merimag-backend' ),
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'sticky_header_stacked_icons' => array(
                'label' => __('Sticky Header stacked icons', 'merimag-backend'),
                'help' => __( 'Show sticky header icons in another nice way to make them more attractive', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'sticky_header_display_logo' => array(
                'label' => __('Sticky header logo', 'merimag-backend'),
                'help' => __( 'Active this option to show the logo in the sticky header, if you choose no only menu and other elements that you have activated will show', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
          )
    ),
    'main_menu' => array(
      'type' => 'tab',
      'title' => __('Main menu', 'merimag-backend'),
      'options' => array(
          'enable_main_menu' => array(
            'type'  => 'select',
            'label' => __( 'Enable main menu', 'merimag-backend' ),
            'help' => __( 'You can disable main menu', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_search' => array(
            'label' => __('Main menu search', 'merimag-backend'),
            'help' => __( 'Show search form or icon in the main menu bar', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'form'   => __('Form', 'merimag-backend'),
              'icon'  => __('Icon', 'merimag-backend'),
              'hide'  => __('Hide', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_contact_infos' => array(
            'label' => __('Main menu contact infos', 'merimag-backend'),
            'help' => __( 'Show your contact infos in the main menu bar, you can set your contact infos in the contact infos section', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_cart' => array(
            'label' => __('Main menu cart icon', 'merimag-backend'),
            'help' => __( 'Show woocommerce cart icon in the main menu bar', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_account' => array(
            'label' => __('Main menu account link', 'merimag-backend'),
            'help' => __( 'Show account link in the main menu bar', 'merimag-backend' ),
            'type'  => 'select',
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_social' => array(
            'label' => __('Main social links', 'merimag-backend'),
            'type'  => 'select',
            'help' => __( 'Show social icons in the main menu bar, you can set social profiles in social links section', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_stacked_icons' => array(
            'label' => __('Main menu stacked icons', 'merimag-backend'),
            'type'  => 'select',
            'help' => __( 'Stacked icons is a nice way to show search, cart and account icons in the main menu bar', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes'   => __('Yes', 'merimag-backend'),
              'no'  => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'main_menu_items_hover_effect_style' => array(
            'type'    => 'image-picker',
            'label'   => esc_html__('Main menu items hover effect', 'merimag-backend'),
            'help' => __( 'Choose from 4 ways to display items when the user pass the mouse over a main menu item', 'merimag-backend' ),
            'value'   => 'default',
            'choices' => merimag_get_recognized_menu_items_styles(),
            'wp-customizer-setting-args' => array(
              'transport' => 'postMessage',
            ),
          ),
        )
    ),
    'top_menu' => array(
      'type' => 'tab',
      'title' => __('Top menu', 'merimag-backend'),
      'options' => array(
          'enable_top_menu' => array(
            'type'  => 'select',
            'label' => __( 'Enable top menu', 'merimag-backend' ),
            'help' => __( 'Show to menu', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'show_top_menu_date' => array(
            'type'  => 'select',
            'label' => __( 'Show date', 'merimag-backend' ),
            'help' => __( 'Show today\'s date, a nice thing if your site is news site', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),

          'show_top_menu_social_icons' => array(
            'type'  => 'select',
            'label' => __( 'Show social icons', 'merimag-backend' ),
            'help' => __( 'By default social links will show at the end of the top menu, you can set your social profiles in the social links section', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'top_menu_text' => array(
            'type'  => 'wp-editor',
            'label' => __( 'Top menu text', 'merimag-backend' ),
            'help' => __( 'You can display custom text in the top menu bar, maybe useful for you', 'merimag-backend' ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'secondary_menu_items_hover_effect_style' => array(
            'type'    => 'image-picker',
            'label'   => esc_html__('Top menu items hover effect', 'merimag-backend'),
            'help' => __( 'Choose from 4 ways to display items when the user pass the mouse over a top menu item', 'merimag-backend' ),
            'value'   => 'default',
            'choices' => merimag_get_recognized_menu_items_styles(),
            'wp-customizer-setting-args' => array(
              'transport' => 'postMessage',
            ),
          ),
          'show_top_menu_mobile' => array(
            'type'  => 'select',
            'label' => __( 'Show top menu on mobile', 'merimag-backend' ),
            'help' => __( 'Enable this option if you want to display top menu in mobile screens', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'show_top_menu_date_mobile' => array(
            'type'  => 'select',
            'help' => __( 'Enable this option if you want to display top menu date in mobile screens', 'merimag-backend' ),
            'label' => __( 'Show date on mobile', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'show_top_menu_social_icons_mobile' => array(
            'type'  => 'select',
            'help' => __( 'Enable this option if you want to display top menu social icons in mobile screens', 'merimag-backend' ),
            'label' => __( 'Show social icons on mobile', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'show_top_menu_text_mobile' => array(
            'type'  => 'select',
            'help' => __( 'Enable this option if you want to display top menu text in mobile screens', 'merimag-backend' ),
            'label' => __( 'Show custom text on mobile', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),

        )
    ),
    'mobile_header' => array(
          'type' => 'tab',
          'title' => __('Mobile header', 'merimag-backend'),
          'options' => array(
              'mobile_header_search' => array(
                'label' => __('Mobile Header search', 'merimag-backend'),
                'help' => __( 'Show search icon or from in mobile header', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'form'   => __('Form', 'merimag-backend'),
                  'icon'  => __('Icon', 'merimag-backend'),
                  'hide'  => __('Hide', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'mobile_header_cart' => array(
                'label' => __('Mobile Header cart icon', 'merimag-backend'),
                'help' => __( 'Show cart icon in mobile header', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'mobile_header_account' => array(
                'label' => __('Mobile Header account link', 'merimag-backend'),
                'help' => __( 'Show account link in mobile header', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'mobile_header_stacked_icons' => array(
                'label' => __('Mobile Header stacked icons', 'merimag-backend'),
                'help' => __( 'Show mobile header icons in another nice style', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
              'mobile_header_center_logo' => array(
                'label' => __('Mobile header center logo', 'merimag-backend'),
                'help' => __( 'Center logo in mobile header', 'merimag-backend' ),
                'type'  => 'select',
                'choices' => array(
                  'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
                  'yes'   => __('Yes', 'merimag-backend'),
                  'no'  => __('No', 'merimag-backend'),
                ),
                'wp-customizer-setting-args' => array(
                      'transport' => 'postMessage'
                    ),
              ),
          )
    ),
    'mobile_menu' => array(
      'type' => 'tab',
      'title' => __('Mobile menu panel', 'merimag-backend'),
      'options' => array(
          'mobile_menu_social' => array(
            'type'  => 'select',
            'label' => __( 'Show social icons', 'merimag-backend' ),
            'help' => __( 'Show social icons in mobile menu panel, you can set your social profiles in the social links section', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'mobile_menu_logo' => array(
            'type'  => 'select',
            'label' => __( 'Show Logo', 'merimag-backend' ),
            'help' => __( 'Show logo in mobile menu panel, you can set a custom logo for this area, in the section General -> Logo -> Mobile logo', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'mobile_menu_search' => array(
            'type'  => 'select',
            'label' => __( 'Show search form', 'merimag-backend' ),
            'help' => __( 'Show search form at the bottom of the mobile menu panel', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
        )
    ),
    'footer' => array(
      'type' => 'tab',
      'title' => __('Footer', 'merimag-backend'),
      'options' => array(

          'enable_footer_menu' => array(
            'type'  => 'select',
            'label' => __( 'Enable footer menu', 'merimag-backend' ),
            'help' => __( 'Enable footer menu will show a menu that you have created in wordpress menus editor at the bottom of the footer beside copyrights, you can edit menus via Dashboard -> Appearance -> Menus', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'enable_footer_widgets' => array(
            'type' => 'select',
            'label' => __('Enable footer widgets area', 'merimag-backend'),
            'help' => __( 'Enable this area at the top of the footer, you can customize this area by adding any available widget, you can add widgets via Dashboard -> Appearance -> Widgets', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'enable_footer_about' => array(
            'type' => 'select',
            'label' => __('Enable footer about', 'merimag-backend'),
            'help' => __( 'Footer about is a section that show your logo or a custom logo with a text and your social links, you can customize it in the Footer about section', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
          ),
          'enable_footer_trending' => array(
            'type' => 'select',
            'label' => __('Enable footer trending tags', 'merimag-backend'),
            'help' => __( 'Show the popular tags in the footer may have a nice look and improve site seo, you can find more options for this area in the Footer tags section', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),
            'wp-customizer-setting-args' => array(
               'transport' => 'postMessage'
            ),
          ),
        )
    ),
    'extra' => array(
      'type' => 'tab',
      'title' => __('Extra', 'merimag-backend'),
      'options' => array(
        'lazy_image_loading' => array(
          'type' => 'select',
          'label' => __('Enable lazy image loading', 'merimag-backend'),
          'help' => __( 'Enable this options may improve page loading', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
        ),
        'fake_post_views' => array(
          'type' => 'select',
          'label' => __('Fake post views', 'merimag-backend'),
          'help' => __( 'Enable this to display fake post views...', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
        ),
        'post_views_hot_badge' => array(
          'type' => 'number',
          'help' => __( 'Set the minimum number of the views for a post to have a hot badge', 'merimag-backend' ),
          'label' => __('Views Hot badge min number', 'merimag-backend'),
        ),
        'post_views_popular_badge' => array(
          'type' => 'number',
          'help' => __( 'Set the minimum number of the views for a post to have a popular badge', 'merimag-backend' ),
          'label' => __('Views Popular badge min number', 'merimag-backend'),
        ),
        'post_views_trending_badge' => array(
          'type' => 'number',
          'help' => __( 'Set the minimum number of the views for a post to have a trending badge', 'merimag-backend' ),
          'label' => __('Views Trending badge min number', 'merimag-backend'),
        ),
      ),
    ),
  );
  $options['extra']['options'] = array_merge( $options['extra']['options'], $theme_colors_options );
  return $options;
}
/**
 * Theme ad settings.
 *
 * @return array
 */
function merimag_get_ad_options( $slug = '') {
  $slug = $slug ? $slug . '_' : $slug;
  $options = array(
    $slug . 'title' => array(
      'type' => 'text',
      'label' => __('Title', 'actunews-backend'),
    ),
    $slug . 'image' => array(
      'type' => 'upload',
      'label' => __('Image', 'actunews-backend'),
      'only_images' => true,
    ),
    $slug . 'link' => array(
      'type' => 'text',
      'label' => __('Ad link', 'actunews-backend'),
    ),
   $slug .  'code' => array(
      'type' => 'textarea',
      'label' => __('Ad code', 'actunews-backend'),
    ),
  );
  return $options;
}
/**
 * Theme ticker settings.
 *
 * @param bool $single
 * @return array
 */
function merimag_ticker_settings() {
  $ticker_options = merimag_get_ticker_options('ticker');
  $options = array(
    'ticker_position' => array(
        'type'  => 'select',
        'label' => __( 'Enable ticker', 'merimag-backend' ),
        'help' => __( 'The news ticker is a nice option for news sites, it can show up before header, after header, inside top menu or you can hide it if you want', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'after_header' => __('After header', 'merimag-backend'),
          'before_header' => __('Before header', 'merimag-backend'),
          'in_menu' => __('With top menu elements', 'merimag-backend'),
          'hide' => __('Hide', 'merimag-backend'),
        ),
        'wp-customizer-setting-args' => array(
          'transport' => 'postMessage'
        )
    ),
    'custom_ticker' => array(
        'label'        => __( 'Custom ticker settings', 'merimag-backend' ),
        'type'         => 'switch',
        'help' => __( 'You can configure ticker posts and icon', 'merimag-backend' ),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'        => 'no',
        'wp-customizer-setting-args' => array(
           'transport' => 'postMessage'
        ),
    ),
    'custom_ticker_settings' => array(
      'type' => 'multi-picker',
      'picker' => 'custom_ticker',
      'choices' => array(
        'yes' => $ticker_options
      ),
      'wp-customizer-setting-args' => array(
           'transport' => 'postMessage'
        ),
    ),
  );
  
  return $options;
}
/**
 * Layout options.
 *
 * @param bool $single
 * @return array
 */
function merimag_layout_settings( $slug = '' ) {
    $box          = $slug;
    $slug         = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options      = array();
    $options_slug = 'layout' . $slug;
    $options[ $options_slug ] = array(
      'label'   => __('Select layout', 'merimag-backend'),
      'type'    => 'image-picker',
      'help' => __( 'Layout can be with or without sidebar, in left or right', 'merimag-backend' ),
      'choices' => merimag_get_recognized_page_layouts(),
    );
    return $options;
}
/**
 * Theme layout settings.
 *
 * @param bool $single
 * @return array
 */
function merimag_get_theme_layout_settings() {
  $boxes = array( 
    'default_layout'     => esc_html__('Default layout', 'merimag-backend'),
    'page_layout'        => esc_html__('Page layout', 'merimag-backend'),
    'single_layout'      => esc_html__('Article layout', 'merimag-backend'),
    'index_layout'       => esc_html__('Index layout', 'merimag-backend'), 
    'category_layout'    => esc_html__('Category layout', 'merimag-backend'), 
    'tag_layout'         => esc_html__('Tag layout', 'merimag-backend'), 
    'search_layout'      => esc_html__('Search layout', 'merimag-backend'), 
    'archive_layout'     => esc_html__('Archives layout', 'merimag-backend'), 
    'tax_layout'         => esc_html__('Taxonomy layout', 'merimag-backend'),
    'shop_layout'        => esc_html__('Shop archive layout', 'merimag-backend'),
    'woocommerce_layout' => esc_html__('Woocommerce pages layout', 'merimag-backend'),
    'product_layout'     => esc_html__('Product layout', 'merimag-backend'),
  );
  $options = array();
  foreach( $boxes as $box => $box_name ) {
      $slug    = explode('_', $box);
      $slug    = isset( $slug[0] ) && is_string( $slug[0] ) ? $slug[0] : '';
      $options[ $box . '-box' ] = array(
        'title'   => $box_name,
        'type' => 'tab',
        'help' => __( 'Every page can have a custom layout', 'merimag-backend' ),
        'options' => $box !== 'default_layout' ? merimag_layout_settings( $slug, true ) : merimag_layout_settings( $slug ),
      );
  }
  return $options;
}
/**
 * Theme footer settings.
 *
 * @return array
 */
function merimag_get_theme_footer_about_settings() {
  $options = array(
     'footer_about_layout'    => array(
          'label' => __( 'Footer about layout', 'merimag-backend' ),
          'type'  => 'select',
          'help' => __( 'Footer about can have two styles, select the one you find nice for your site', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf('-- %s --', esc_html__('Default', 'merimag-backend')),
            'inline' => __('Inline', 'merimag-backend'),
            'centered' => __('Centered', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_about_logo'    => array(
          'label' => __( 'Footer about logo', 'merimag-backend' ),
          'help' => __( 'Choose a custom logo for this area, very helpful because by default footer have a dark background so the default logo may not appear nice here', 'merimag-backend' ),
          'type'  => 'upload',
          'only_images' => true,
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_about_text' => array(
          'type' => 'textarea',
          'label' => __('About site', 'merimag-backend'),
          'help' => __( 'Write something about your site or activity', 'merimag-backend' ),
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
  );
  $social_options_data = merimag_get_social_icons_options( true, false);
  foreach( $social_options_data as $option_id => $option ) {
    $social_options_data[esc_attr($option_id)] = $option;
    $social_options_data[esc_attr($option_id)]['wp-customizer-setting-args'] = array(
       'transport' => 'postMessage'
    );
  }
  $social_options = array(
    'custom_footer_about_social' => array(
        'label'        => __( 'Custom social icons style', 'merimag-backend' ),
        'type'         => 'switch',
        'help' => __( 'The theme comes with a nice social icons settings, you can choose your style', 'merimag-backend' ),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'        => 'no',
        'wp-customizer-setting-args' => array(
           'transport' => 'postMessage'
        ),
    ),
    'footer_about_social' => array(
      'type' => 'multi-picker',
      'picker' => 'custom_footer_about_social',
      'choices' => array(
        'yes' => $social_options_data
      ),
      'wp-customizer-setting-args' => array(
           'transport' => 'postMessage'
        ),
    ),

  );
  
  $options = array_merge( $options, $social_options );
  return $options;
}
/**
 * Theme ads settings.
 *
 * @return array
 */
function merimag_get_theme_ads_settings() {
  $ads = merimag_get_posts('adsforwp-groups', true);
  
  if( !defined('ADSFORWP_VERSION') ) {
    $install_plugins_page = admin_url('themes.php?page=tgmpa-install-plugins');
    $form_plugin_link = admin_url('plugin-install.php?tab=plugin-information&plugin=ads-for-wp');
    $options = array(
      'theme_ads' => array(
        'type'  => 'html',
        'value' => false,
        'label' => __('Form', 'merimag-backend'),
        
        'html'  => sprintf('<p>Please visit <a href="%s" target="_blank">Install plugins page</a> to make sure you have installed and activated <a href="%s" target="_blank">Ads For WP</a>.!</p>', esc_url( $install_plugins_page ), esc_url( $form_plugin_link ) ),
      ),
    );
    return $options;
  }
  if( count( $ads ) > 0 ) {

    $options[ 'header-content-ad' ] = array(
      'title'   => __('Header content ad', 'merimag-backend'),
      'type' => 'tab',
      'help' => __( 'Ad that will show up beside logo', 'merimag-backend' ),
      'options' => array(
        'header_content_ad' => array(
          'type' => 'select',
          'label' => __('Select ad', 'merimag-backend'),
          'choices' => $ads,
        ),
      ),
    );
    $options[ 'before-header-ad' ] = array(
      'title'   => __('Before header ad', 'merimag-backend'),
      'help' => __( 'Ad that will show before header', 'merimag-backend' ),
      'type' => 'tab',
      'options' => array(
        'before_header_ad' => array(
          'type' => 'select',
          'label' => __('Select ad', 'merimag-backend'),
          'choices' => $ads,
        ),
        'before_header_ad_background' => array(
          'type' => 'color-picker-v2',
          'label' => __('Section background', 'merimag-backend'),
          'rgba'  => true,
        ),
        'before_header_ad_padding' => array(
            'type' => 'spacing',
            'label' => esc_html__('Padding', 'merimag-backend'),
            'properties' => array(
              'min' => 0,
              'max' => 400,
              'step' => 1,
            ),
        ),
      ),
    );
    $options[ 'after-header-ad' ] = array(
      'title'   => __('After header ad', 'merimag-backend'),
      'type' => 'tab',
      'help' => __( 'Ad that will show after header', 'merimag-backend' ),
      'options' => array(
        'after_header_ad' => array(
          'type' => 'select',
          'label' => __('Select ad', 'merimag-backend'),
          'choices' => $ads,
        ),
        'after_header_ad_background' => array(
          'type' => 'color-picker-v2',
          'label' => __('Section background', 'merimag-backend'),
          'rgba'  => true,
        ),
        'after_header_ad_padding' => array(
            'type' => 'spacing',
            'label' => esc_html__('Padding', 'merimag-backend'),
            'properties' => array(
              'min' => 0,
              'max' => 400,
              'step' => 1,
            ),
        ),
      ),
    );
    $options[ 'before-footer-ad' ] = array(
      'title'   => __('Before footer ad', 'merimag-backend'),
      'help' => __( 'Ad that will show before footer', 'merimag-backend' ),
      'type' => 'tab',
      'options' => array(
        'before_footer_ad' => array(
          'type' => 'select',
          'label' => __('Select ad', 'merimag-backend'),
          'choices' => $ads,
        ),
        'before_footer_ad_background' => array(
          'type' => 'color-picker-v2',
          'label' => __('Section background', 'merimag-backend'),
          'rgba'  => true,
        ),
        'before_footer_ad_padding' => array(
            'type' => 'spacing',
            'label' => esc_html__('Padding', 'merimag-backend'),
            'properties' => array(
              'min' => 0,
              'max' => 400,
              'step' => 1,
            ),
        ),
      ),
    );
  } else {
    $add_form_link = admin_url('edit.php?post_type=adsforwp-groups');
    $options = array(
      'theme_ads' => array(
        'type'  => 'html',
        'value' => false,
        'label' => __('Select group ad', 'merimag-backend'),
        'html'  => sprintf('<p>Please make sure you have added group ads <a target="_blank" href="%s">here</a><p/>', esc_url( $add_form_link ) ),
      ),
    );
  }
  return $options;
}
/**
 * Theme promo bar settings.
 *
 * @return array
 */
function merimag_get_theme_promo_bar_settings() {
  $options = array(
    'promo_bar_position' => array(
        'type'  => 'select',
        'label' => __( 'Promo bar position', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'after_header' => __('After header', 'merimag-backend'),
          'before_header' => __('Before header', 'merimag-backend'),
          'hide' => __('Hide', 'merimag-backend'),
        ),
        'wp-customizer-setting-args' => array(
          'transport' => 'postMessage'
        )
    ),
    'promo_bar_text'    => array(
        'label' => __( 'Text', 'merimag-backend' ),
        'type'  => 'wp-editor',
        'wp-customizer-setting-args' => array(
          'transport' => 'postMessage'
        )
    ),
    'promo_bar_link'    => array(
        'label' => __( 'Link', 'merimag-backend' ),
        'type'  => 'wplink',
        'wp-customizer-setting-args' => array(
          'transport' => 'postMessage'
        )
    ),
    'promo_bar_home' => array(
        'label'        => __( 'Only in home page', 'merimag-backend' ),
        'type'         => 'switch',
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'        => 'no',
        'wp-customizer-setting-args' => array(
           'transport' => 'postMessage'
        ),
    ),
  );
  return $options;
}

/**
 * Theme builder sections settings.
 *
 * @return array
 */
function merimag_get_theme_builder_sections_settings() {
  $choices = merimag_get_posts('builder_section', true );
  $choices = is_array( $choices) ? $choices : array();
  $builder_sections = merimag_recognized_builder_sections( false );
  foreach ($builder_sections as $section => $label ) {
    $options['builder_section_' . $section] = array(
      'label'   => is_string( $label ) ? $label : '',
      'type'    => 'select',
      'choices' => $choices,
      'wp-customizer-setting-args' => array(
            'transport' => 'postMessage'
      )
    );
  }
  return $options;
}
/**
 * Theme footer tags settings.
 *
 * @return array
 */
function merimag_get_theme_footer_tags_settings() {
  $options = array(
     'footer_tags_layout'    => array(
          'label' => __( 'Footer tags layout', 'merimag-backend' ),
          'type'  => 'select',
          'help' => __( 'Choose the footer tags layout that find nice for your site', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf('-- %s --', esc_html__('Default', 'merimag-backend')),
            'inline' => __('Inline', 'merimag-backend'),
            'centered' => __('Centered', 'merimag-backend'),
            'hide' => __('Hide', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_tags_title'    => array(
          'label' => __( 'Title', 'merimag-backend' ),
          'help' => __( 'Set a title for this section, by default it\'s #trending', 'merimag-backend' ),
          'type'  => 'text',
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_tags_number'    => array(
          'label' => __( 'Maximum number of tags to show', 'merimag-backend' ),
          'help' => __( 'Set a maximum number of tags that will show up here', 'merimag-backend' ),
          'type'  => 'number',
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
  );
  return $options;
}
/**
 * Theme footer instagram settings.
 *
 * @return array
 */
function merimag_get_theme_footer_instagram_settings() {
  $options = array(
     'footer_instagram_usertag'    => array(
          'label' => __( '@username or #tag', 'merimag-backend' ),
          'type'  => 'text',
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_instagram_scroll'    => array(
          'label' => __( 'Scroll images', 'merimag-backend' ),
          'type'  => 'select',
          'choices' => array(
            'default' => sprintf('-- %s --', esc_html__('Default', 'merimag-backend')),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_instagram_number'    => array(
          'label' => __( 'Number of images to load', 'merimag-backend' ),
          'type'  => 'number',
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_instagram_only_images'    => array(
          'label' => __( 'Ignore videos', 'merimag-backend' ),
          'type'  => 'select',
          'choices' => array(
            'default' => sprintf('-- %s --', esc_html__('Default', 'merimag-backend')),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
  );
  return $options;
}
/**
 * Theme footer copyrights settings.
 *
 * @return array
 */
function merimag_get_theme_footer_copyrights_settings() {
  $options = array(
     'footer_copyrights_layout'    => array(
          'label' => __( 'Footer copyrights layout', 'merimag-backend' ),
          'help' => __( 'The copyrights bar is the copyrights text of your site and the footer menu, you can choose inline layout or centered layout', 'merimag-backend' ),
          'type'  => 'select',
          'choices' => array(
            'default' => sprintf('-- %s --', esc_html__('Default', 'merimag-backend')),
            'inline' => __('Inline', 'merimag-backend'),
            'centered' => __('Centered', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
      'footer_copyrights_text'    => array(
          'label' => __( 'Copyrights text', 'merimag-backend' ),
          'help' => __( 'Set copyrights text in this field', 'merimag-backend' ),
          'type'  => 'textarea',
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
      ),
  );
  return $options;
}
/**
 * Theme grid settings.
 *
 * @return array
 */
function merimag_get_theme_grid_settings() {
  $boxes = array( 
    'default_listing'  => esc_html__('Default listing', 'merimag-backend'),
    'index_listing'    => esc_html__('Index listing', 'merimag-backend'), 
    'category_listing' => esc_html__('Category listing', 'merimag-backend'), 
    'tag_listing'      => esc_html__('Tag listing', 'merimag-backend'), 
    'search_listing'   => esc_html__('Search listing', 'merimag-backend'), 
    'archive_listing'  => esc_html__('Archives listing', 'merimag-backend'), 
    'tax_listing'      => esc_html__('Taxonomy listing', 'merimag-backend'),
  );
  $options = array();
  foreach( $boxes as $box => $box_name ) {
    $slug    = explode('_', $box);
    $slug    = isset( $slug[0] ) && is_string( $slug[0] ) ? $slug[0] : '';
    $options[ $box . '-box' ] = array(
      'title'   => $box_name,
      'type' => 'tab',
      'help' => __( 'Choose from over 30 grid styles, every page can have it\'s own style', 'merimag-backend' ),
      'options' => merimag_get_simple_grid_options( $slug ),
    );
  }
  $options[ 'shop_listing-box' ] = array(
    'title'   => __('Shop listing', 'merimag-backend'),
    'type' => 'tab',
    'options' => merimag_grid_settings( 'shop', true, 'product', 'general' ),
  );
  return $options;
}

/**
 * Theme typography settings.
 *
 * @return array
 */
function merimag_get_theme_typography_settings() {
 
  $elements = merimag_get_typography_elements();
  $element_options = array();
  $options[ 'load-fonts' ] = array(
      'title'   => __('Load fonts', 'merimag-backend'),
      'type' => 'box',
      'options' => array(
        'load_fonts_array' => array(
            'type' => 'multi-select',
            'label' => __('Load fonts for use in css', 'merimag-backend'),
            'help' => __( 'Add google fonts that you want to load, this option is very helpful if you want to use them in a custom css', 'merimag-backend' ),
            'population' => 'array',
            'choices' => merimag_get_recognized_font_families(),
            'limit' => 40,
            'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            )
        ),
        'local_host_fonts' => array(
            'type' => 'select',
            'label' => __('Local host fonts', 'merimag-backend'),
            'help' => __( 'Enable this option may help you improve page loading by hosting google fonts in your server', 'merimag-backend' ),
            'choices' => array(
              'default' => sprintf('-- %s --', __('Default', 'merimag-backend')),
              'yes' => __('Yes', 'merimag-backend'),
              'no' => __('No', 'merimag-backend'),
            ),

        ),
      ),
  );
  foreach( $elements as $box => $element ) {
    if( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
      $element_options = array();
      foreach( $element['elements'] as $location => $typography_element ) {
        $element_options[$box . '_' . $location  . '_typography'] = array(
          'label' => isset( $typography_element['label'] ) ? $typography_element['label'] : strtoupper( $location ),
          'type' => 'typography-v3',
          'components' => array(
            'family'         => isset( $typography_element['components']['family'] ) ? $typography_element['components']['family'] : true,
            'size'           => isset( $typography_element['components']['size'] ) ? $typography_element['components']['size'] : true,
            'line-height'    => isset( $typography_element['components']['line-height'] ) ? $typography_element['components']['line-height'] : true,
            'letter-spacing' => isset( $typography_element['components']['letter-spacing'] ) ? $typography_element['components']['letter-spacing'] : true,
            'color'          => isset( $typography_element['components']['color'] ) ? $typography_element['components']['color'] : false,
            'weight'         => isset( $typography_element['components']['weight'] ) ? $typography_element['components']['weight'] : true,
            'style'          => isset( $typography_element['components']['style'] ) ? $typography_element['components']['style'] : true,
            'transform'      => isset( $typography_element['components']['transform'] ) ? $typography_element['components']['transform'] : true,
            'decoration'     => isset( $typography_element['components']['decoration'] ) ? $typography_element['components']['decoration'] : true,
          ),
          'wp-customizer-setting-args' => array(
            'transport' => 'postMessage'
          )
        );
      }
    }
    $options[ $box . '-typography' ] = array(
        'title'   => isset( $element['label'] ) ? $element['label'] : strtoupper( $box ),
        'type' => 'box',
        'help' => __(' Custom typography for', 'merimag-backend' ) . ' ' . isset( $element['label'] ) ? $element['label'] : strtoupper( $box ),
        'options' => $element_options,
    );
  }
  return $options;
}
/**
 * Areas styling settings.
 *
 * @return array
 */
function merimag_area_settings( $area, $area_name ) {
  $options  = array();
  $slug = !empty( $area ) && is_string( $area ) ? $area . '_' : '';

  
  $options[ $slug . 'background_color' ] = array(
    'type'  => 'color-picker-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Background color', 'merimag-backend'),
    'rgba'  => true,
  );
  $options[ $slug . 'background_gradient' ] = array(
    'type'  => 'gradient-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Background gradient', 'merimag-backend'),
  );
  $options[ $slug . 'background_image' ] = array(
    'type'  => 'upload',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Background image', 'merimag-backend'),
  );
  $options[ $slug . 'background_position' ] = array(
    'type'    => 'select',
    'label'   => esc_attr( $area_name ) . ' ' . esc_html__('Background position', 'merimag-backend'),
    'choices' => merimag_get_recognized_background('background_position'),
  );
  $options[ $slug . 'background_repeat' ] = array(
    'type'    => 'select',
    'label'   => esc_attr( $area_name ) . ' ' . esc_html__('Background repeat', 'merimag-backend'),
    'choices' => merimag_get_recognized_background('background_repeat'),
  );
  $options[ $slug . 'background_attachment' ] = array(
    'type'    => 'select',
    'label'   => esc_attr( $area_name ) . ' ' . esc_html__('Background attachment', 'merimag-backend'),
    'choices' => merimag_get_recognized_background('background_attachment'),
  );
  $options[ $slug . 'background_size' ] = array(
    'type'    => 'select',
    'label'   => esc_attr( $area_name ) . ' ' . esc_html__('Background size', 'merimag-backend'),
    'choices' => merimag_get_recognized_background('background_size'),
  );
  $options[ $slug . 'text_color' ] = array(
    'type'  => 'color-picker-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Text color', 'merimag-backend'),
  );
  $options[ $slug . 'links_color' ] = array(
    'type'  => 'color-picker-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Links color', 'merimag-backend'),
  );
  $options[ $slug . 'links_hover_color' ] = array(
    'type'  => 'color-picker-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Links hover color', 'merimag-backend'),
  );
  $options[ $slug . 'buttons_color' ] = array(
    'type'  => 'color-picker-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Buttons color', 'merimag-backend'),
  );
  $options[ $slug . 'borders_color' ] = array(
    'type'  => 'color-picker-v2',
    'label' => esc_attr( $area_name ) . ' ' . esc_html__('Borders color', 'merimag-backend'),
    'rgba'  => true,
  );
  # selective refresh support
  foreach( $options as $option_name => $option ) {
      $options[$option_name]['wp-customizer-setting-args'] = array(
        'transport' => 'postMessage'
      );
  }

  return $options;
}
/**
 * Theme areas settings.
 *
 * @return array
 */
function merimag_get_theme_areas_settings( $single = false ) {
  $box_type = $single === false ? 'box' : 'tab';
  $slugs   = array( 
    'body'           => esc_html__('Body', 'merimag-backend'),
    'site_container' => esc_html__('Site container', 'merimag-backend'),
    'content_container' => esc_html__('Content container', 'merimag-backend'),
    'article_content' => esc_html__('Article content', 'merimag-backend'),
    'secondary_menu' => esc_html__('Top menu', 'merimag-backend'),
    'secondary_menu_sub_menu' => esc_html__('Top menu sub menus', 'merimag-backend'),
    'header'         => esc_html__('Header', 'merimag-backend'),
    'header_inner'   => esc_html__('Header content', 'merimag-backend'),
    'main_menu'      => esc_html__('Main menu', 'merimag-backend'),
    'main_menu_sub_menu'=> esc_html__('Main menu sub menus', 'merimag-backend'),
    'sticky_header'    => esc_html__('Sticky header', 'merimag-backend'),
    'sticky_header_sub_menu' => esc_html__('Sticky header sub menus', 'merimag-backend'),
    'mobile_header' => esc_html__('Mobile header', 'merimag-backend'),
    'ticker'         => esc_html__('News ticker', 'merimag-backend'),
    'footer_tags'    => esc_html__('Footer Tags', 'merimag-backend'),
    'footer_about'   => esc_html__('Footer about', 'merimag-backend'),
    'footer'         => esc_html__('Footer', 'merimag-backend'),
    'footer_infos'   => esc_html__('Footer infos', 'merimag-backend'),
    'mobile_menu_panel'    => esc_html__('Mobile menu panel', 'merimag-backend'),
  );

  $options = array();
  $options['general-box'] = array(
    'title'   => __('General styling','merimag-backend'),
      'type' => $box_type,
      'help' => __( 'Set principal color, theme skin and other general colors', 'merimag-backend' ),
    'options' => array(
      'principal_color'    => array(
          'label' => __( 'Principal color', 'merimag-backend' ),
          'help'  => __( 'Site principal color for styling elements', 'merimag-backend' ),
          'type'  => 'color-picker-v2',
          'wp-customizer-setting-args' => array(
              'transport' => 'postMessage'
            ),
      ),
      'theme_skin'    => array(
          'label' => __( 'Skin', 'merimag-backend' ),
          'help'  => __( 'Choose from over 14 skins if you don\'t want to change a lot of things in options', 'merimag-backend' ),
          'type'  => 'select',
          'choices' => merimag_get_recognized_skins(),
      ),
    ),
  );
  foreach( $slugs as $area => $area_name ) {
    $options[ $area . '-box' ] = array(
      'title'   => $area_name,
      'type' => $box_type,
      'help' => sprintf(__( 'Customize the background, text color, links color... of %s ', 'merimag-backend' ), $area_name ),
      'options' => merimag_area_settings( $area, $area_name ),
    );
  }
  if( $single === false ) {
    $options[ 'block-style-box' ] = array(
      'title'   => esc_html__('Elementor Block style', 'merimag-backend'),
      'type' => $box_type,
      'help' => __( 'Customize the background, text color, links color... of blocks that you add in elementor page builder', 'merimag-backend' ),
      'options' => merimag_get_block_style_options( 'general_block' ),
    );
    $options[ 'widget-style-box' ] = array(
      'title'   => esc_html__('Widget style', 'merimag-backend'),
      'help' => __( 'Customize the background, text color, links color... of widgets', 'merimag-backend' ),
      'type' => $box_type,
      'options' => merimag_get_block_style_options( 'general_widget' ),
    );
    $options[ 'footer-widget-style-box' ] = array(
      'title'   => esc_html__('Footer widget style', 'merimag-backend'),
      'help' => __( 'Customize the background, text color, links color... of footer widgets', 'merimag-backend' ),
      'type' => $box_type,
      'options' => merimag_get_block_style_options( 'general_footer_widget' ),
    );
    $options[ 'content-area-style-box' ] = array(
      'title'   => esc_html__('Content area style', 'merimag-backend'),
      'help' => __( 'Customize the background, text color, links color... of content area that contains things like recent posts list and post content', 'merimag-backend' ),
      'type' => $box_type,
      'options' => merimag_get_block_style_options( 'general_content_area' ),
    );
    $options[ 'sidebar-area-style-box' ] = array(
      'title'   => esc_html__('Sidebar area style', 'merimag-backend'),
      'help' => __( 'Customize the background, text color, links color... of sidebar area that contains widgets', 'merimag-backend' ),
      'type' => $box_type,
      'options' => merimag_get_block_style_options( 'general_sidebar_area' ),
    );
  }
  return $options;
}
/**
 * Theme post settings settings.
 *
 * @param bool $single
 * @return array
 */
function merimag_get_theme_post_settings( $single = false ) {
  if( function_exists('yikes_easy_mailchimp_extender_get_form_interface') ) {
    $form_interface   = yikes_easy_mailchimp_extender_get_form_interface();
    $all_forms        = $form_interface->get_all_forms();
    foreach( (array) $all_forms as $id => $form ) {
      $choices[ $id ] = isset( $form['form_name'] ) ? $form['form_name'] : sprintf(__('Form %s', 'merimag-backend'), $id );
    }
  }
  
  $options[ 'general-settings' ] = array(
    'title'   => esc_html__('General settings', 'merimag-backend'),
    'type' => 'tab',
    'options' => array(
      'post_template' => array(
         'label'   => __('Select template', 'merimag-backend'),
         'type'    => 'image-picker',
         'help' => __( 'Choose from over 12 nicely designed templates that change the look of the article pages', 'merimag-backend' ),
         'value'   => 'default',
         'choices' => merimag_get_recognized_post_templates(),
      ),
      'author_box' => array(
          'type'    => 'switch',
          'label' => __('Enable author box', 'merimag-backend'),
          'help' => __( 'Highlight post authors by enabling this box, it display the author infos and social profiles in a nice way', 'merimag-backend' ),
          'value' => 'yes',
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'content_index' => array(
          'type'    => 'switch',
          'label' => __('Enable table of contents', 'merimag-backend'),
          'help' => __( 'Table of contents can improve seo and user experience, especialy for very long articles', 'merimag-backend' ),
          'value' => 'yes',
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'next_prev' => array(
          'type'    => 'switch',
          'label' => __('Enable next / prev', 'merimag-backend'),
          'help' => __( 'Improve seo and user experience by showing links for the next and previous post, it may also improve the time that users stay in your site', 'merimag-backend' ),
          'value' => 'yes',
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'subscribe_form' => array(
          'label'        => __( 'Enable mailchimp form', 'merimag-backend' ),
          'help' => __( 'Enable subscribe form in the article pages, help increase your subscribers', 'merimag-backend' ),
          'type'         => 'switch',
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'        => 'yes',
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      
    ),
  );
  if( isset( $choices ) && is_array( $choices ) ) {
    $subscribe = array(
      'type' => 'multi-picker',
      'picker' => 'subscribe_form',
      'choices' => array(
        'yes' => merimag_get_mailchimp_options(),
      ),
      'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
    );
    $options['general-settings']['options']['subscribe_form_atts'] =  $subscribe;
  }
  // get options 
  $grid_options = merimag_posts_grid_settings();
  $query_options = merimag_get_query_options();

  // related posts

  $related_posts_options = $grid_options;
  $related_posts_options['get_by'] = array(
    'type'    => 'select',
    'label' => __('Get posts by', 'merimag-backend'),
    'help' => __( 'Relation between posts can be category or tag, choose the one the you find helpful in your situation', 'merimag-backend' ),
    'value' => 'grid',
    'choices' => array(
      'category' => __('Category', 'merimag-backend'),
      'tag' => __('Tag', 'merimag-backend'),
    ),
  );
  $related_posts_options['number'] = array(
    'type' => 'slider',
    'label' => __('Number of posts', 'merimag-backend'),
    'help' => __( 'Number of posts to show', 'merimag-backend' ),
    'properties' => array(
        'min' => 1,
        'max' => 12,
        'step' => 1,
    ),
  );
  $inline_related_posts_options['number'] = array(
    'type' => 'slider',
    'label' => __('Number of posts', 'merimag-backend'),
    'help' => __( 'Number of posts to show', 'merimag-backend' ),
    'properties' => array(
        'min' => 1,
        'max' => 12,
        'step' => 1,
    ),
  );
  $inline_related_posts_options['order_by'] = array(
        'type'  => 'select',
        'value' => 'rand',
        'label' => __('Order by', 'merimag-backend'),
        'choices' => merimag_get_recognized_order_by_options(),
        'desc' => __('Select how you want to order items', 'merimag-backend'),
    );
  $inline_related_posts_options['order'] = array(
        'type'  => 'select',
        'value' => 'desc',
        'label' => __('Order', 'merimag-backend'),
        'choices' => merimag_get_recognized_order_options(),
        'desc' => __('Ascending or descending order can be selected from here', 'merimag-backend'),
    );
  $options[ 'related-posts' ] = array(
    'title'   => esc_html__('Related posts', 'merimag-backend'),
    'type' => 'tab',
    'options' => array(
      
      'related_posts_style' => array(
          'type'    => 'select',
          'label' => __('Related posts', 'merimag-backend'),
          'help' => __( 'Related posts suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
          'value' => 'default_grid',
          'choices' => array(
            'default_grid' => __('Default settings', 'merimag-backend'),
            'grid' => __('Custom settings', 'merimag-backend'),
            'hide' => __('Don\'t show', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'related_posts' => array(
        'type' => 'multi-picker',
        'picker' => 'related_posts_style',
        'choices' => array(
          'grid' => $related_posts_options,
        ),
        'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      )
    ),
    
  );
  $options[ 'inline-related-posts' ] = array(
    'title'   => esc_html__('Inline related posts', 'merimag-backend'),
    'type' => 'tab',
    'options' => array(
      'inline_related_posts' => array(
          'type'    => 'select',
          'label' => __('Inline related posts before paragraph', 'merimag-backend'),
          'help' => __( 'Inline related posts suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'automatic' => __('Automatically', 'merimag-backend'),
            'hide' => __('Disable', 'merimag-backend'),
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
          ),
      ),
      'inline_related_posts_style' => array(
          'type'    => 'select',
          'label' => __('Inline related posts settings', 'merimag-backend'),
          'help' => __( 'Related posts suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
          'value' => 'default_grid',
          'choices' => array(
            'default_grid' => __('Default settings', 'merimag-backend'),
            'grid' => __('Custom settings', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'inline_related_posts_settings' => array(
        'type' => 'multi-picker',
        'picker' => 'inline_related_posts_style',
        'choices' => array(
          'grid' => $inline_related_posts_options,
        ),
        'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      )
      
    ),
    
  );
  // read also
  $read_also_options = array_merge( $grid_options, $query_options);
  $options[ 'read-also' ] = array(
    'title'   => esc_html__('Read also box', 'merimag-backend'),
    'type' => 'tab',
    'options' => array(
      'read_also_style' => array(
          'type'    => 'select',
          'label' => __('Read also', 'merimag-backend'),
          'help' => __( 'Read also suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
          'value' => 'default_grid',
          'choices' => array(
            'default_grid' => __('Default settings', 'merimag-backend'),
            'grid' => __('Custom grid', 'merimag-backend'),
            'hide' => __('Don\'t show', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'read_also' => array(
        'type' => 'multi-picker',
        'picker' => 'read_also_style',
        'choices' => array(
          'grid' => $read_also_options,
        ),
        'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      )
    ),
    
  );
  $options[ 'share-buttons' ] = array(
    'title'   => esc_html__('Share  buttons', 'merimag-backend'),
    'type' => 'tab',
    'options' => array(
      'show_share_buttons_before_content' => array(
          'type'    => 'select',
          'label' => __('Show before content', 'merimag-backend'),
          'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show before article content', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'show_share_buttons_after_content' => array(
          'type'    => 'select',
          'label' => __('Show after content', 'merimag-backend'),
          'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show after article content', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
      'show_share_buttons_on_meta' => array(
          'type'    => 'select',
          'label' => __('Show on meta line', 'merimag-backend'),
          'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show beside the post date and author', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'yes' => __('Yes', 'merimag-backend'),
            'no' => __('No', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
             'transport' => 'postMessage'
          ),
      ),
    )
  );
  return $options;
}
/**
 * Theme single post settings.
 *
 * @param bool $single
 * @return array
 */
function merimag_get_theme_category_settings() {
  $g_options = array(
    'category_layout' => array(
       'label'   => __('Select layout', 'merimag-backend'),
       'type'    => 'image-picker',
       'value'   => 'default',
       'choices' => merimag_get_recognized_page_layouts(),
       'help' => __('Can be left sidebar, right sidebar or no sidebar', 'merimag-backend'),
    ),
    'category_featured_image' => array(
      'label' => __( 'Featured Image', 'merimag' ),
      'type'  => 'upload',
      'help' => __( 'Selecting featured image for the category will change the layout, because it will display as a cover and the title of the category will show over it, nice!', 'merimag-backend' ),
      'only_images' => true,
    ),
  );
  $grid_options = merimag_get_simple_grid_options( 'category_category' );
  
  $options[ 'layout' ] = array(
      'title'   => __('Layout', 'merimag-backend'),
      'type' => 'tab',
      'options' => array_merge( $g_options,  $grid_options),
    );


  $site_options = merimag_get_theme_general_settings('category');
  
  foreach( $site_options as $k => $site_option ) {
    $site_options[$k]['type'] = 'tab';
    foreach( $site_options[$k]['options'] as $option_id => $option ) {
      $site_options[$k]['options']['category_' . esc_attr( $option_id ) ] = $option;
      unset( $site_options[$k]['options'][$option_id]);
    }
  }
  $options = array_merge( $options, $site_options );
  return $options;
}
/**
 * Theme single post settings.
 *
 * @param bool $single
 * @return array
 */
function merimag_get_theme_single_post_settings() {
  $options = array(
    'single_layout_single' => array(
       'label'   => __('Select layout', 'merimag-backend'),
       'type'    => 'image-picker',
       'help' => __( 'Post can have a sidebar on left or right, or no sidebar', 'merimag-backend' ),
       'value'   => 'default',
       'choices' => merimag_get_recognized_page_layouts(),
    ),
    'single_post_template' => array(
       'label'   => __('Select template', 'merimag-backend'),
       'help' => __( 'Choose from over 12 nicely designed templates that change the look of the article pages', 'merimag-backend' ),
       'type'    => 'image-picker',
       'value'   => 'default',
       'choices' => merimag_get_recognized_post_templates(),
    ),
    'single_content_index' => array(
        'type' => 'select',
        'label' => __('Enable table of contents', 'merimag-backend'),
        'help' => __( 'Table of contents can improve seo and user experience, especialy for very long articles', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_author_box' => array(
        'type' => 'select',
        'label' => __('Enable author box', 'merimag-backend'),
        'help' => __( 'Highlight post authors by enabling this box, it display the author infos and social profiles in a nice way', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_next_prev' => array(
        'type' => 'select',
        'label' => __('Enable next / prev', 'merimag-backend'),
        'help' => __( 'Improve seo and user experience by showing links for the next and previous post, it may also improve the time that users stay in your site', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_subscribe_form' => array(
        'type' => 'select',
        'label' => __('Enable subscribe form', 'merimag-backend'),
        'help' => __( 'Enable subscribe form in the article pages, help increase your subscribers', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_related_posts_style' => array(
        'type' => 'select',
        'label' => __('Enable related posts', 'merimag-backend'),
        'help' => __( 'Related posts suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'hide' => __('Hide', 'merimag-backend'),
        ),
    ),
    'single_inline_related_posts' => array(
          'type'    => 'select',
          'label' => __('Inline related posts before paragraph', 'merimag-backend'),
          'help' => __( 'Inline related posts suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
          'choices' => array(
            'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
            'automatic' => __('Automatically', 'merimag-backend'),
            'hide' => __('Disable', 'merimag-backend'),
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
          ),
    ),
    'single_related_posts_include' => array(
        'type' => 'multi-select',
        'label' => __('Set specific inline related posts', 'merimag-backend'),
        'help' => __( 'You can set specific inline related posts for each article', 'merimag-backend' ),
        'source' => 'post',
        'population' => 'posts',
    ),
    'single_read_also_style' => array(
        'type' => 'select',
        'label' => __('Enable read also', 'merimag-backend'),
        'help' => __( 'Read also suggest more articles to your visitors, it will improve the time that users pass in your site', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'hide' => __('Hide', 'merimag-backend'),
        ),
    ),
    'single_show_share_buttons_before_content' => array(
        'type' => 'select',
        'label' => __('Enable share buttons before content', 'merimag-backend'),
        'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show before article content', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_show_share_buttons_after_content' => array(
        'type' => 'select',
        'label' => __('Enable share buttons after content', 'merimag-backend'),
        'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show after article content', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_show_share_buttons_on_meta' => array(
        'type' => 'select',
        'label' => __('Enable share buttons on meta line', 'merimag-backend'),
        'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show beside the post date and author', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
  );

  $options = array(
    'post_general' => array(
      'type' => 'tab',
      'title' => __('Layout', 'merimag-backend'),
      'options' => $options,
    ),
  );
  $review_options = merimag_get_review_options();

  $options['post_review'] =  array(
      'type' => 'tab',
      'title' => __('Review', 'merimag-backend'),
      'help' => __( 'Produt reviews is nice feature that help create full produt reviews fast and easy', 'merimag-backend' ),
      'options' => array(
        'single_enable_review' => array(
          'type' => 'switch',
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'fw-storage' => array(
            'type' => 'post-meta',
            'post-meta' => 'fw_option:single_enable_review',
          )
        ),
        'single_review_position' => array(
          'type' => 'select',
          'value' => 'after_content',
          'choices' => array(
            'after_content' => __('After content', 'merimag-backend'),
            'before_content' => __('Before content', 'merimag-backend'),
            'custom' => __('Add via shortcode','merimag-backend'),
          ),
        ),
        'single_review' => array(
          'type' => 'multi-picker',
          'picker' => 'single_enable_review',
          'choices' => array(
            'yes' => $review_options,
          ),
        ),
      ),
  );
  $site_options = merimag_get_theme_general_settings('single');
  
  foreach( $site_options as $k => $site_option ) {
    $site_options[$k]['type'] = 'tab';
    foreach( $site_options[$k]['options'] as $option_id => $option ) {
      $site_options[$k]['options']['single_' . esc_attr( $option_id ) ] = $option;
      unset( $site_options[$k]['options'][$option_id]);
      if( $option_id === 'enable_cache' ) {
        unset( $site_options[$k]['options'][$option_id] );
      }
    }
  }
  $options = array_merge( $options, $site_options );
  return $options;
}
/**
 * Theme single video settings.
 *
 * @return array
 */
function merimag_get_theme_single_post_video_settings() {
  $options = array(

    'post_video_upload' => array(
       'label'   => __('Video upload', 'merimag-backend'),
       'help' => __( 'Upload custom video and make it featured video', 'merimag-backend' ),
       'type'    => 'upload',
       'images_only' => false,
    ),
    'post_video_url' => array(
       'label'   => __('Video url', 'merimag-backend'),
       'help' => __( 'Put external video url from any video plateform and it will show up as a featured video', 'merimag-backend' ),
       'type'    => 'text',
    ),
  );
  return $options;
}
/**
 * Theme single general settings.
 *
 * @return array
 */
function merimag_get_theme_single_general_settings() {
  $options = array(
    'post_subtitle' => array(
       'label'   => __('Post subtitle', 'merimag-backend'),
       'help' => __( 'Add subtitle to your post', 'merimag-backend' ),
       'type'    => 'text',
    ),
    'featured_image_copyrights' => array(
       'label'   => __('Featured image copyrights', 'merimag-backend'),
       'help' => __( 'Featured image copyrights', 'merimag-backend' ),
       'type'    => 'text',
    ),
    
  );
  return $options;
}

/**
 * Theme single audio settings.
 *
 * @return array
 */
function merimag_get_theme_single_post_audio_settings() {
  $options = array(

    'post_audio_url' => array(
       'label'   => __('Audio url', 'merimag-backend'),
       'help' => __( 'Put external audio url from any audio plateform and it will show up as a featured audio', 'merimag-backend' ),
       'type'    => 'text',
    ),
    'post_audio_upload' => array(
       'label'   => __('Audio upload', 'merimag-backend'),
       'help' => __( 'Upload custom audio and make it featured audio, the audio player will show nicely above the featured image that you have selected', 'merimag-backend' ),
       'type'    => 'upload',
       'images_only' => false,
    ),
  );
  return $options;
}
/**
 * Theme single gallery settings.
 *
 * @return array
 */
function merimag_get_theme_single_post_gallery_settings() {
  $options = array(
    'gallery_items' => array(
      'type' => 'multi-upload',
      'label' => __('Images', 'merimag-backend'),
      'images_only' => true,
      'help' => __( 'Select images to make a featured gallery', 'merimag-backend' ),
    ),
    'gallery_theme' => array(
      'type' => 'select',
      'label' => __('Theme', 'merimag-backend'),
      'help' => __( 'There is nice gallery styles that you can choose from, test them all!', 'merimag-backend' ),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'compact' => __('Compact', 'merimag-backend'),
        'slider' => __('Slider', 'merimag-backend'),
        'tiles' => __('Tiles', 'merimag-backend'),
      ),
    ),
  );
  return $options;
}
/**
 * Theme single page settings.
 *
 * @return array
 */
function merimag_get_theme_single_page_settings() {
  $options = array(
    'single_layout_page' => array(
       'label'   => __('Select layout', 'merimag-backend'),
       'type'    => 'image-picker',
       'help' => __( 'Page can have a sidebar on left or right, or no sidebar', 'merimag-backend' ),
       'value'   => 'default',
       'choices' => merimag_get_recognized_page_layouts(),
    ),
    'single_thumbnail_title_cover' => array(
       'label'   => __('Title cover image', 'merimag-backend'),
       'help' => __( 'Enable this will make a nice layout of your page, by showing the title above the image that you select in a cover style', 'merimag-backend' ),
       'type' => 'switch',
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),

    'single_title_cover_image' => array(
       'label'   => __('Title cover image', 'merimag-backend'),
       'help' => __( 'Select the image that want to make it as a cover', 'merimag-backend' ),
       'type'    => 'upload',
       'images_only' => true,
    ),
    'single_title_cover_color' => array(
       'label'   => __('Title cover color', 'merimag-backend'),
       'help' => __( 'Add nice gradient effect to title cover section', 'merimag-backend' ),
       'type'    => 'gradient-v2',
    ),
    'single_title_cover_text_color' => array(
      'label'   => __('Title cover text color', 'merimag-backend'),
      'help' => __( 'Choose the text color of the page title that display above the image cover if you enable title cover option', 'merimag-backend' ),
       'type'    => 'select',
       'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'white' => __('White', 'merimag-backend'),
          'dark' => __('Dark', 'merimag-backend'),
        ),
    ),
    'single_show_share_buttons_before_content' => array(
        'type' => 'select',
        'label' => __('Enable share buttons before content', 'merimag-backend'),
        'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show before page content', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_show_share_buttons_after_content' => array(
        'type' => 'select',
        'label' => __('Enable share buttons after content', 'merimag-backend'),
        'help' => __( 'Share buttons suggest visitors to share your articles, enable it will help sharing your content, enable this if your want them to show after page content', 'merimag-backend' ),
        'choices' => array(
          'default' => sprintf( '-- %s --', __('Default', 'merimag-backend') ),
          'yes' => __('Yes', 'merimag-backend'),
          'no' => __('No', 'merimag-backend'),
        ),
    ),
    'single_overlay_header' => array(
       'label'   => __('Overlay header', 'merimag-backend'),
       'help' => __( 'Enable this option if your want the header to be above page content', 'merimag-backend' ),
       'type' => 'switch',
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),
    'single_transparent_header' => array(
       'label'   => __('Transparent header', 'merimag-backend'),
       'help' => __( 'Enable this option if your want the header to be transparent', 'merimag-backend' ),
       'type' => 'switch',
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),
  );
  
  $options = array(
    'post_general' => array(
      'type' => 'tab',
      'title' => __('Layout', 'merimag-backend'),
      'options' => $options,
    ),
  );
 
  $site_options = merimag_get_theme_general_settings('page');
  
  foreach( $site_options as $k => $site_option ) {
    $site_options[$k]['type'] = 'tab';
    foreach( $site_options[$k]['options'] as $option_id => $option ) {
      $site_options[$k]['options']['single_' . esc_attr( $option_id ) ] = $option;
      unset( $site_options[$k]['options'][$option_id]);
      if( $option_id === 'enable_cache' ) {
        unset( $site_options[$k]['options'][$option_id] );
      }
    }
  }
  $options = array_merge( $options, $site_options );
  return $options;
}
/**
 * Dynamic css args
 *
 * @return array
 */
function merimag_get_dynamic_css_args() {

  $args                                       = array();

  # typography

  $args['body_typography']                    = merimag_get_typography_defaults( merimag_get_db_live_customizer_option( 'body_typography'), 'body' );

  $args['headings_typography']                = merimag_get_typography_defaults( merimag_get_db_live_customizer_option( 'headings_typography'), 'headings' );

  $args['main_menu_typography']               = merimag_get_typography_defaults( merimag_get_db_live_customizer_option( 'main_menu_typography'), 'main_menu' );

  $args['secondary_menu_typography']         = merimag_get_typography_defaults( merimag_get_db_live_customizer_option( 'secondary_menu_typography'), 'secondary_menu' ); 

  $args['paragraphs_typography']              = merimag_get_typography_defaults( merimag_get_db_live_customizer_option( 'paragraphs_typography'), 'paragraphs' );

  $args['logo_typography']                    = merimag_get_typography_defaults( merimag_get_db_live_customizer_option( 'logo_typography'), 'logo', 'logo' );

  # text size

  $body_text_size                             = merimag_get_text_size_defaults( merimag_get_db_live_customizer_option( 'body_text_size'), 'body' );

  $paragraphs_listing_text_size               = merimag_get_text_size_defaults( merimag_get_db_live_customizer_option( 'paragraphs_listing_text_size'), 'paragraphs_listing' );

  $paragraphs_single_text_size                = merimag_get_text_size_defaults( merimag_get_db_live_customizer_option( 'paragraphs_single_text_size'), 'paragraphs_single' );

  $main_menu_text_size                        = merimag_get_text_size_defaults( merimag_get_db_live_customizer_option( 'main_menu_text_size'), 'main_menu' );

  $secondary_menu_text_size                   = merimag_get_text_size_defaults( merimag_get_db_live_customizer_option( 'secondary_menu_text_size'), 'secondary_menu' );

  $args['body_text_size']                     = isset( $body_text_size['size'] ) ? $body_text_size['size'] : $body_text_size;

  $args['paragraphs_listing_text_size']       = isset( $paragraphs_listing_text_size['size'] ) ? $paragraphs_listing_text_size['size'] : $paragraphs_listing_text_size;

  $args['paragraphs_single_text_size']        = isset( $paragraphs_single_text_size['size'] ) ? $paragraphs_single_text_size['size'] : $paragraphs_single_text_size;

  $args['main_menu_text_size']                = isset( $main_menu_text_size['size'] ) ? $main_menu_text_size['size'] : $main_menu_text_size;

  $args['secondary_menu_text_size']           = isset( $secondary_menu_text_size['size'] ) ? $secondary_menu_text_size['size'] : $secondary_menu_text_size;

  for( $i = 1; $i <= 6; $i++ ) {

    ${'h' . esc_attr($i) . '_text_size'}  = merimag_get_text_size_defaults( merimag_get_db_live_customizer_option( 'h' . esc_attr($i) . '_text_size' ), 'h' . esc_attr($i) );

    $args['h' . esc_attr($i) . '_text_size']  = isset( ${'h' . esc_attr($i) . '_text_size'}['size'] ) ? ${'h' . esc_attr($i) . '_text_size'}['size'] : ${'h' . esc_attr($i) . '_text_size'};

  }

  # colors

  $areas         = array( 'body', 'site_container', 'content_container', 'article_content', 'header', 'header_inner', 'main_menu', 'main_menu_sub_menu', 'secondary_menu', 'secondary_menu_sub_menu', 'ticker', 'footer_tags', 'footer_about', 'footer', 'footer_infos', 'sticky_header', 'sticky_header_sub_menu', 'mobile_menu_panel', 'mobile_header');

  foreach( $areas as $area ) {
    $areas_options = merimag_get_area_options( $area );
    foreach( $areas_options as $area_option ) {
      $args[ $area . '_' . $area_option ] = merimag_get_area_option_value( $area_option, $area );
    }
  }
  $args['principal_color'] = merimag_get_principal_color();

  return $args;
}
/**
 * Area options
 *
 * @param string $for area
 * @return array
 */
function merimag_get_area_options( $area = 'body' ) {
  $options = array( 'background_color', 'background_gradient', 'background_image', 'background_position','background_repeat', 'background_attachment', 'background_size', 'text_color', 'links_color', 'links_hover_color', 'buttons_color', 'borders_color' );
  return $options;
}
/**
 * Dynamic css args
 *
 * @param string $key array key to search
 * @param string $for area
 * @return array
 */
function merimag_get_area_option_value( $key, $for = 'body' ) {
  $value = merimag_get_db_live_customizer_option( $for . '_' . $key );
  $secondary_gradient = isset( $value['secondary'] ) && !empty( $value['secondary'] ) ? $value['secondary'] : 'transparent';
  $primary_gradient = isset( $value['primary'] ) && !empty( $value['primary'] ) ? $value['primary'] : 'transparent';
  $value = $secondary_gradient !== 'transparent' || $primary_gradient !== 'transparent' && $key === 'background_gradient' && isset( $value['degree'] ) &&  in_array( $value['degree'], array('to bottom', 'to right') ) ? 'linear-gradient( ' . esc_attr( $value['degree'] ) . ',' . esc_attr( $primary_gradient ) . ',' . esc_attr( $secondary_gradient ) . ')' : $value;
  $value = isset( $value['url'] ) && $key === 'background_image' ? $value['url'] : $value;

  $value = isset( $value['height'] ) &&  $value['height'] === 'yes' && isset(  $value['yes']['height'] ) && is_numeric(  $value['yes']['height'] ) ?  $value['yes']['height'] : $value;
  $value = is_array( $value ) ? '' : $value;
  return $value;
}
/**
 * Generate typography css
 *
 * @return array
 */
function merimag_get_typography_css() {
  $selectors = merimag_get_typography_selectors();
  $components = merimag_get_typography_components();
  $css = '';
  foreach ($selectors as $option => $selector ) {
    $values = merimag_get_db_live_customizer_option( $option );
    if( is_array($values) ) {
      $element_css = '';
      foreach( $values as $key => $value ) {
        if( !isset($components[$key] ) ) continue;
        if( $key === 'family' ) {
          $value = str_replace('-', ' ', $value );
        }
        $property = $components[$key];
        $maybe_px = $components[$key] === 'font-size' ? 'px' : '';
        $element_css .= !empty( $value ) && $value !== 'default' ? sprintf('%s : %s%s;', $property, $value, $maybe_px ) : '';
      }
      if( !empty( $element_css ) ) {
        $css .= sprintf('%s { %s }', $selector, $element_css );
      }
    }
  }
  return $css;
}
/**
 * Generate dynamic css
 *
 * @return array
 */
function merimag_get_dynamic_css() {

  if( defined('DOING_AJAX') && DOING_AJAX ) {
    merimag_check_ajax_referer( 'merimag_options', 'nonce' );
  }

  $cache_id = 'merimag_customizer_css_cache_' . merimag_get_demo_slug();
  $cached_css = get_transient($cache_id);
  $enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
  if( $cached_css && !is_customize_preview() && !is_category() && !is_single() && $enable_cache === 'yes' ) {
    $style = $cached_css;

  } else {
    $args = merimag_get_dynamic_css_args();

    extract( $args );
    
    $style  = '';
    $style .= merimag_get_typography_css();
    $areas = merimag_get_locations_selectors();

    foreach( $areas as $area => $location ) {

      $location = $location !== 'body' ? 'body.site-body ' . esc_attr( $location ) : 'body.site-body';

      if( isset( ${$area . '_background_color'} ) && !empty( ${$area . '_background_color'} ) ) {
        $style .= esc_attr( $location ) . " {
          background-color: " . esc_attr( ${$area . '_background_color'} ) . ";
        }\n";
        $style .= merimag_get_custom_selector_css('from_background_color', ${$area . '_background_color'}, $location );
      }

      if( isset( ${$area . '_background_image'} ) && !empty( ${$area . '_background_image'} ) ) {
         $style .= esc_attr( $location ) . " {
          background-image: url(" . esc_url( ${$area . '_background_image'} ) . ");
        }\n";
      }
      if( isset( ${$area . '_background_gradient'} ) && !empty( ${$area . '_background_gradient'} ) ) {
        $style .= esc_attr( $location ) . " {
          background-image: " . esc_attr( ${$area . '_background_gradient'} ) . ", url( " . esc_url( ${$area . '_background_image'} ) . ");
        }\n";
      }
      if( isset( ${$area . '_background_gradient'} ) && !empty( ${$area . '_background_gradient'} ) && isset( ${$area . '_background_image'} ) && !empty( ${$area . '_background_image'} ) ) {
         $style .= esc_attr( $location ) . " {
          background-image: " . esc_attr( ${$area . '_background_gradient'} ) . ", url( " . esc_url( ${$area . '_background_image'} ) . ");
        }\n";
      }
      if( isset( ${$area . '_background_attachment'} ) && !empty( ${$area . '_background_attachment'} ) ) {
         $style .= esc_attr( $location ) . " {
          background-attachment: " . esc_attr( ${$area . '_background_attachment'} ) . ";
        }\n";
      }
      if( isset( ${$area . '_background_size'} ) && !empty( ${$area . '_background_size'} ) ) {
         $style .= esc_attr( $location ) . " {
          background-size: " . esc_attr( ${$area . '_background_size'} ) . ";
        }\n";
      }
      if( isset( ${$area . '_background_repeat'} ) && !empty( ${$area . '_background_repeat'} ) ) {
         $style .= esc_attr( $location ) . " {
          background-repeat: " . esc_attr( ${$area . '_background_repeat'} ) . ";
        }\n";
      }
      if( isset( ${$area . '_background_position'} ) && !empty( ${$area . '_background_position'} ) ) {
         $style .= esc_attr( $location ) . " {
          background-position: " . esc_attr( ${$area . '_background_position'} ) . ";
        }\n";
      }


      # get text color

      ${$area . '_text_color'}        = isset( ${$area . '_text_color'} ) ? ${$area . '_text_color'} : '';

      ${$area . '_borders_color'}     = isset( ${$area . '_borders_color'} ) ? ${$area . '_borders_color'} : ${$area . '_text_color'} ;

      ${$area . '_links_color'}       = isset( ${$area . '_links_color'} ) ? ${$area . '_links_color'} : '';

      ${$area . '_links_hover_color'} = isset( ${$area . '_links_hover_color'} ) ? ${$area . '_links_hover_color'} : '';

      ${$area . '_buttons_color'}     = isset( ${$area . '_buttons_color'} ) ? ${$area . '_buttons_color'} : false;

      $style .= merimag_get_text_color_css( ${$area . '_text_color'}, ${$area . '_links_color'}, ${$area . '_links_hover_color'}, $location );

      $style .= merimag_get_buttons_css( ${$area . '_buttons_color'},  $location );
      $style .= merimag_get_borders_color_css( ${$area . '_borders_color'}, $location );

   
    }


    $style .= merimag_get_principal_color_css( $principal_color );

    $style .= merimag_get_theme_colors_css();

    $style .= '.merimag-demo-ad:after { background-image: linear-gradient(to right, rgba(0,0,0,0), ' . esc_attr($principal_color) .'); } ';

    $style .= '.horizontal-ad.merimag-demo-ad:after { background-image: linear-gradient(to bottom, rgba(0,0,0,0), ' . esc_attr($principal_color) .'); } ';

    /* BLOCK CONTAINER STYLE */

    $style .= merimag_get_dynamic_block_style('general_block', 'body.site-body .general-box-container:not(.ignore-general-style)');

    $style .= merimag_get_dynamic_block_style( 'general_widget', 'body.site-body .sidebar-widget:not(.ignore-general-style)');

    $style .= merimag_get_dynamic_block_style( 'general_footer_widget', 'body.site-body .footer-widget:not(.ignore-general-style)');

    $style .= merimag_get_dynamic_block_style('general_section', 'body.site-body .merimag-section-container:not(.ignore-general-style)');

    $style .= merimag_get_dynamic_block_style('general_content_area', 'body.site-body .merimag-site-container .site-content-area-style');

    $style .= merimag_get_dynamic_block_style('general_sidebar_area', '.merimag-widget-area');

    $style .= merimag_generate_principal_color_category_css();

    if ( get_header_image() ) :
        $style .= sprintf('.merimag-custom-header-content { background-image: url("%s"); background-size:cover; background-repeat:no-repeat; min-height:%spx; display: flex; align-items: center; background-position:center-center; }', esc_url( get_header_image() ), absint( get_custom_header()->height ) );
    endif;
    if(!is_customize_preview() && $enable_cache === 'yes' ) {
      set_transient($cache_id, $style );
    }

  }

  return $style;


}
/**
 * Get customizer css
 * 
 * @param $sv selective refresh call
 * @return void
 */ 
function merimag_get_general_style( $sv = false ) {
  $style = merimag_get_dynamic_css();
  if( $sv == false ) {
    echo '<style type="text/css" id="merimag-styling-wrapper-back">';
    echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );
    echo '</style>';
    echo '<style type="text/css" id="merimag-styling-wrapper">';
    echo '</style>';
  } else {
    echo wp_specialchars_decode( wp_kses_post( $style ), ENT_QUOTES );
  }
}
add_action('wp_head', 'merimag_get_general_style', 9999 );
/**
 * Get header additional css
 * Add required assets of the selected header style
 * 
 * @param $header_style style id
 * @return false
 */ 
function merimag_header_style_css() {
  $style = '';
  $header_spacing = merimag_get_header_spacing_value();
  $logo_height    = merimag_get_logo_height();
  if( $logo_height ) {
    $style  .= '.merimag-site-header:not(.merimag-sticky-header):not(.merimag-mobile-header) .merimag-header-content .menu-item  > .merimag-header-icon, .merimag-site-header:not(.merimag-sticky-header):not(.merimag-mobile-header)  .merimag-header-content .menu > .menu-item  > a {
      height:' . esc_attr( $logo_height + ( $header_spacing * 2 ) ) . 'px;
    }';
  }
  $sticky_header_logo = merimag_get_db_customizer_option('sticky_header_display_logo', 'yes');
  if( $sticky_header_logo === 'yes' ) {
    $header_spacing = merimag_get_header_spacing_value('sticky_header');
    $logo_height    = merimag_get_logo_height('sticky_header');
    if( $logo_height ) {
      $style  .= '.merimag-sticky-header .merimag-header-content .menu-item  > .merimag-header-icon, .merimag-sticky-header .merimag-header-content .menu > .menu-item  > a {
        height:' . esc_attr( $logo_height + ( $header_spacing * 2 ) ) . 'px;
      }';
    }
  }
  
  return $style;
}
/**
 * Header spacing value
 *
 * @return array
 */
function merimag_get_header_spacing_value( $location = 'header') {
  $header_spacing = merimag_get_db_customizer_option( $location .'_spacing', 'medium' );
  switch ($header_spacing) {
    case 'small':
      return 10;
      break;
    case 'medium':
      return 20;
      break;
    case 'big':
      return 40;
      break;
    default:
     return 20;
  }
}
/**
 * Load filter fonts
 *
 * @return array
 */
function merimag_load_filter_fonts() {
  $cache_id = 'merimag_customizer_fonts_cache' . merimag_get_demo_slug();
  $cached_families = get_transient($cache_id);
  $enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
  if( $cached_families && !is_customize_preview() && $enable_cache === 'yes' ) {
    $families = $cached_families;

  } else {
    $areas = merimag_get_typography_selectors( true );
    $families = array();
    foreach( $areas as $area ) {
      $typo_value = merimag_get_db_live_customizer_option( $area );
      if( isset( $typo_value['family'] ) && !empty( $typo_value['family'] ) ) {
        $typography[] = str_replace(' ', '-', $typo_value['family'] );
      } 
      
    }
    $load_fonts_array = merimag_get_db_live_customizer_option('load_fonts_array');

    foreach((array) $load_fonts_array as $font ) {
      $typography[] = str_replace(' ', '-', $font );
    }
    $recognized_families = merimag_get_recognized_font_families();
    foreach($typography as $typo ) {
      if( !empty( $typo ) && array_key_exists( $typo, $recognized_families ) )  {
        $families[] = str_replace(' ', '-', $typo );
      }
    }
    if( $enable_cache === 'yes' ) {
      set_transient($cache_id, $families );
    }
  }
  add_filter('merimag_load_fonts', function($fonts) use( $families ) {
    return is_array( $families ) ? array_unique( array_merge($fonts, $families )) : $fonts;
  });
}
add_action('init', 'merimag_load_filter_fonts', 1);

function merimag_generate_principal_color_category_css() {
  $terms = merimag_get_terms( 'category', true);

  $css = '';
  foreach( (array) $terms as $term ) {
    $principal_color = merimag_get_db_term_option( $term->term_id, 'category', 'category_principal_color' );
    $color = merimag_get_text_color_from_background( $principal_color );
    if( $principal_color && !empty( $principal_color ) ) {
      $slug = 'body.site-body .merimag-site-container .category-' . $term->slug;
      $css_propeties = array('background-color', 'color', 'border-right-color', 'border-left-color', 'border-top-color', 'border-bottom-color', 'border-color' );
      foreach( $css_propeties as $property ) {
        $css .= esc_attr( $slug ) . '.principal-color-' . esc_attr( $property ) . ' { ' . esc_attr( $property ) . ' : ' . esc_attr( $principal_color ) . '!important; }';
        $css .= esc_attr( $slug ) . '.merimag-term-count.principal-color-' . esc_attr( $property ) . ' { ' . esc_attr( $property ) . ' : ' . esc_attr( $principal_color ) . '!important; }';
      }
      $css .= esc_attr( $slug ) . '.principal-color-background-color { color : ' . esc_attr( $color ) . '!important; }';
      $css .= esc_attr( $slug ) . '.merimag-term-count.principal-color-background-color { color  : ' . esc_attr( $color ) . '!important; }';
    }
  }
  return $css;
}
function merimag_get_typography_components() {
  $components = array(
      'family'         => 'font-family',
      'size'           => 'font-size',
      'line-height'    => 'line-height',
      'letter-spacing' => 'letter-spacing',
      'color'          => 'color',
      'weight'         => 'font-weight',
      'style'          => 'font-style',
      'transform'      => 'text-transform',
      'decoration'     => 'text-decoration',
  );
  return $components;
}
function merimag_get_typography_selectors( $only_ids = false ) {
  $elements = merimag_get_typography_elements();
  $selectors = array();
  foreach((array) $elements as $box => $element ) {
    if( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
      foreach( $element['elements'] as $typography_id => $typography_element ) {
        $selectors[ $box . '_' . $typography_id . '_typography' ] = isset( $typography_element['selectors'] ) ? $typography_element['selectors'] : '';
        $ids[] = $box . '_' . $typography_id . '_typography';
      }
    }
  }
  return $only_ids === false ? $selectors : $ids;
}
function merimag_get_typography_elements() {

  $typography = array(
    'basic' => array(
      'label' => __('Base typography', 'merimag-backend'),
      'elements' => array(
        'body' => array(
          'label'       => esc_html__( 'Base Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your content.', 'merimag-backend' ),
          'selectors'   => 'body.site-body, #content, .entry-content, .post-content, .page-content, .post-excerpt, .entry-summary, .entry-excerpt, .widget-area, .widget, .sidebar, #sidebar, footer, .footer, #footer, .site-footer',
        ),
        'headings' => array(
          'label'       => esc_html__( 'Headings Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h1, body.site-body h2, body.site-body h3, body.site-body h4, body.site-body h5, body.site-body h6, body.site-body .block-title-wrapper, body.site-body h1 > a, body.site-body h2 > a, body.site-body h3 > a, body.site-body h4 > a, body.site-body h5 > a, body.site-body h6 > a, body.site-body .block-title-wrapper',
          'components' => array(
            'size' => false,
          ),
        ),
        'inputs' => array(
          'label'       => esc_html__( 'Buttons and Inputs Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your input fields and buttons.', 'merimag-backend' ),
          'selectors'   => 'button, input, select, textarea, .yikes-mailchimp-submit-button-span-text',
          'components' => array(
            'size' => false,
          ),
        ),
      ),
    ),
    'headings' => array(
      'label' => __('Heading typography', 'merimag-backend'),
      'elements' => array(
        'h1' => array(
          'label'       => esc_html__( 'Title and H1 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your title and H1 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h1, body.site-body h1 > a',
        ),
        'h2' => array(
          'label'       => esc_html__( 'H2 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H2 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h2, body.site-body h2 > a',
        ),
        'h3' => array(
          'label'       => esc_html__( 'H3 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H3 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h3, body.site-body h3 > a',
        ),
        'h4' => array(
          'label'       => esc_html__( 'H4 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H4 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h4, body.site-body h4 > a',
        ),
        'h5' => array(
          'label'       => esc_html__( 'H5 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H5 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h5, body.site-body h5 > a',
        ),
        'h6' => array(
          'label'       => esc_html__( 'H6 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H6 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body h6, body.site-body h6 > a',
        ),
      ),
      
    ),
    'navigation' => array(
      'label' => __('Navigation typography', 'merimag-backend'),
      'elements' => array(
        'main_navigation' => array(
          'label'       => esc_html__( 'Main Navigation Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your site navigation.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .merimag-main-navigation-typgraphy .vertical-menu > div > ul > li > a',
          'components' => array(
            'line-height' => false,
          ),
        ),
        'top_navigation' => array(
          'label'       => esc_html__( 'Top Navigation Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your site navigation.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .merimag-top-navigation .vertical-menu > div > ul > li > a',
          'components' => array(
            'line-height' => false,
          ),
        ),
        'mobile_sidebar_navigation' => array(
          'label'       => esc_html__( 'Mobile Sidebar Navigation Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your site navigation.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .merimag-mobile-sidebar-menu',
          'components' => array(
            'line-height' => false,
          ),
        ),
        'footer_navigation' => array(
          'label'       => esc_html__( 'Footer Navigation Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your site navigation.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .merimag-footer-menu li, body.site-body .merimag-footer-menu li a',
          'components' => array(
            'line-height' => false,
          ),
        ),
      ),
    ),
    'branding' => array(
      'label' => __('Branding and logo typography', 'merimag-backend'),
      'elements' => array(
          'site_title' => array(
            'label'       => esc_html__( 'Site Title Typography', 'merimag-backend' ),
            'help' => esc_html__( 'Select and configure the font for your site title.', 'merimag-backend' ),
            'selectors'   => 'body.site-body .site-title, body.site-body h1.site-title, body.site-body h1.site-title a',
          ),
          'site_description' => array(
            'label'       => esc_html__( 'Site Description Typography', 'merimag-backend' ),
            'help' => esc_html__( 'Select and configure the font for your site description.', 'merimag-backend' ),
            'selectors'   => 'body.site-body .site-description',
          ),
      ), 
    ),
    'content' => array(
      'label' => __('Content typography', 'merimag-backend'),
      'elements' => array(
        'content' => array(
          'label'       => esc_html__( 'Content Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your post and page content.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-content',
        ),
        'h1' => array(
          'label'       => esc_html__( 'Title and H1 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your title and H1 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-title, body.site-body .entry-content h1, body.site-body .entry-content h1 > a',
        ),
        'h2' => array(
          'label'       => esc_html__( 'H2 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H2 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-content h2, body.site-body .entry-content h2 > a',
        ),
        'h3' => array(
          'label'       => esc_html__( 'H3 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H3 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-content h3, body.site-body .entry-content h3 > a',
        ),
        'h4' => array(
          'label'       => esc_html__( 'H4 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H4 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-content h4, body.site-body .entry-content h4 > a',
        ),
        'h5' => array(
          'label'       => esc_html__( 'H5 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H5 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-content h5, body.site-body .entry-content h5 > a',
        ),
        'h6' => array(
          'label'       => esc_html__( 'H6 Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your H6 headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .entry-content h6, body.site-body .entry-content h6 > a',
        ),
      ),
    ),
    'sidebar' => array(
      'label' => __('Sidebar typography', 'merimag-backend'),
      'elements' => array(
          'headings' => array(
            'label'       => esc_html__( 'Headings Typography', 'merimag-backend' ),
            'help' => esc_html__( 'Select and configure the font for your sidebar headings.', 'merimag-backend' ),
            'selectors'   => 'body.site-body .merimag-widget-area h1, body.site-body .merimag-widget-area h2, body.site-body .merimag-widget-area h3, body.site-body .merimag-widget-area h4, body.site-body .merimag-widget-area h5, body.site-body .merimag-widget-area h6, body.site-body .merimag-widget-area .block-title-wrapper, body.site-body .merimag-widget-area h1 > a, body.site-body .merimag-widget-area h2 > a, body.site-body .merimag-widget-area h3 > a, body.site-body .merimag-widget-area h4 > a, body.site-body .merimag-widget-area h5 > a, body.site-body .merimag-widget-area h6 > a, body.site-body .merimag-widget-area .block-title-wrapper',
          ),
          'content' => array(
            'label'       => esc_html__( 'Content Typography', 'merimag-backend' ),
            'help' => esc_html__( 'Select and configure the font for your sidebar content.', 'merimag-backend' ),
            'selectors'   => 'body.site-body .merimag-widget-area',
          ),
      ),
    ),
    'footer' => array(
      'label' => __('Footer typography', 'merimag-backend'),
      'elements' => array(
        'headings' => array(
          'label'       => esc_html__( 'Headings Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your footer headings.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .merimag-site-footer h1, body.site-body .merimag-site-footer h2, body.site-body .merimag-site-footer h3, body.site-body .merimag-site-footer h4, body.site-body .merimag-site-footer h5, body.site-body .merimag-site-footer h6, body.site-body .merimag-site-footer .block-title-wrapper, body.site-body .merimag-site-footer h1 > a, body.site-body .merimag-site-footer h2 > a, body.site-body .merimag-site-footer h3 > a, body.site-body .merimag-site-footer h4 > a, body.site-body .merimag-site-footer h5 > a, body.site-body .merimag-site-footer h6 > a, body.site-body .merimag-site-footer .block-title-wrapper',
        ),
        'content' => array(
          'label'       => esc_html__( 'Content Typography', 'merimag-backend' ),
          'help' => esc_html__( 'Select and configure the font for your footer content.', 'merimag-backend' ),
          'selectors'   => 'body.site-body .merimag-site-footer',
        ),
      ),
      
    ),
  );

  return $typography;

}

function merimag_register_customizer_partials( WP_Customize_Manager $wp_customize ) {

  if ( ! isset( $wp_customize->selective_refresh ) ) {
    return;
  }
  $general_header_options = array('search','contact_infos','cart','account', 'social','stacked_icons');
  $header_locations = array('header', 'mobile_header', 'sticky_header', 'main_menu');
  foreach( $header_locations as $header_location ) {
    foreach( $general_header_options as $general_header_option ) {
      if( $general_header_option === 'contact_infos' || $general_header_option === 'social' ) {
        if( $header_location !== 'mobile_header' ) {
          $header_options[] = sprintf('fw_options[%s_%s]', $header_location, $general_header_option );
        }
      } else {
         $header_options[] = sprintf('fw_options[%s_%s]', $header_location, $general_header_option );
      }
    }
  } 
  $header_options = array_merge( $header_options, array( 
    'fw_options[logo]',
    'fw_options[logo_height]',
    'fw_options[sticky_header_logo]',
    'fw_options[sticky_header_display_logo]',
    'fw_options[sticky_header_logo_height]',
    'fw_options[mobile_header_center_logo]',
    'fw_options[mobile_logo]',
    'fw_options[mobile_logo_height]',
    'fw_options[amp_logo]',
    'fw_options[amp_logo_height]',
    'fw_options[header_style]',
    'fw_options[header_spacing]',
    'fw_options[sticky_header_spacing]',
    'fw_options[enable_main_menu]',
    'fw_options[enable_top_menu]',
    'fw_options[show_top_menu_date]',
    'fw_options[show_top_menu_social_icons]',
    'fw_options[show_top_menu_date_mobile]',
    'fw_options[show_top_menu_social_icons_mobile]',
    'fw_options[show_top_menu_text_mobile]',
    'fw_options[show_top_menu_mobile]',
    'fw_options[top_menu_text]',
    'fw_options[mobile_menu_social]',
    'fw_options[mobile_menu_logo]', 
    'fw_options[mobile_menu_search]',
    'fw_options[ticker_position]',
    'fw_options[secondary_menu_items_hover_effect_style]',
    'fw_options[main_menu_items_hover_effect_style]',
  ) );

  $ticker_options = merimag_get_ticker_options('ticker');
    foreach($ticker_options as $o => $options ) {
      $header_options[] = 'fw_options[' . $o . ']';
    }

  $wp_customize->selective_refresh->add_partial( 'header_container', array(
        'selector' => '.merimag-header-main',
        'settings' => $header_options,
        'render_callback' => function() {
            return merimag_get_header( true );
        },
  ) );

  $footer_options = array(
      'fw_options[footer_about_layout]',
      'fw_options[footer_about_logo]',
      'fw_options[footer_about_text]',
      'fw_options[custom_footer_about_social]',
      'fw_options[footer_about_social]',
      'fw_options[footer_tags_layout]',
      'fw_options[footer_tags_title]',
      'fw_options[footer_tags_number]',
      'fw_options[footer_copyrights_layout]',
      'fw_options[footer_copyrights_text]',
      'fw_options[enable_footer_menu]',
      'fw_options[enable_footer_widgets]',
      'fw_options[enable_footer_about]',
      'fw_options[enable_footer_trending]',
  );

    
    $wp_customize->selective_refresh->add_partial( 'footer_container', array(
        'selector' => '.merimag-footer-wrapper',
        'settings' => $footer_options,
        'render_callback' => function() {
            return merimag_get_footer( true );
        },
    ) );
  $wp_customize->selective_refresh->add_partial( 'next_prev', array(
        'selector' => '.merimag-next-prev-wrapper',
        'settings' => array( 
          'fw_options[next_prev]',
    ),
        'render_callback' => function() {
            return merimag_next_prev( true );
        },
    ) );
    $wp_customize->selective_refresh->add_partial( 'author_box', array(
        'selector' => '.merimag-author-box-wrapper',
        'settings' => array( 
          'fw_options[author_box]',
    ),
        'render_callback' => function() {
          global $post;
            return merimag_author_box( $post->post_author, true );
        },
    ) );

    $wp_customize->selective_refresh->add_partial( 'related_posts', array(
        'selector' => '.merimag-related-posts-wrapper',
        'settings' => array( 
          'fw_options[related_posts_style]',
          'fw_options[related_posts]',
    ),
        'render_callback' => function() {
            return merimag_related_posts( true );
        },
    ));

    $wp_customize->selective_refresh->add_partial( 'read_also', array(
        'selector' => '.merimag-read-also-wrapper',
        'settings' => array( 
          'fw_options[read_also_style]',
          'fw_options[read_also]',
    ),
        'render_callback' => function() {
            return merimag_read_also( true );
        },
    ) );
    $wp_customize->selective_refresh->add_partial( 'subscribe_form', array(
        'selector' => '.merimag-post-subscribe-wrapper',
        'settings' => array( 
          'fw_options[subscribe_form]',
          'fw_options[subscribe_form_atts]'
    ),
        'render_callback' => function() {
            return merimag_post_subscribe();
        },
    ) );
    $wp_customize->selective_refresh->add_partial( 'share_before_content', array(
        'selector' => '.merimag-share-buttons-bc-wrapper',
        'settings' => array( 
          'fw_options[show_share_buttons_before_content]',
    ),
        'render_callback' => function() {
            return merimag_get_share_buttons_before_content();
        },
    ) );
     $wp_customize->selective_refresh->add_partial( 'share_after_content', array(
        'selector' => '.merimag-share-buttons-ac-wrapper',
        'settings' => array( 
          'fw_options[show_share_buttons_after_content]',
    ),
        'render_callback' => function() {
            return merimag_get_share_buttons_after_content();
        },
    ) );
     $wp_customize->selective_refresh->add_partial( 'share_meta', array(
        'selector' => '.merimag-share-buttons-meta',
        'settings' => array( 
          'fw_options[show_share_buttons_on_meta]',
    ),
        'render_callback' => function() {
            return merimag_get_share_buttons_meta();
        },
    ) );

    $builder_section_areas = merimag_recognized_builder_sections();
    foreach( $builder_section_areas as $area ) {
      $wp_customize->selective_refresh->add_partial( 'builder_section_' . $area, array(
              'selector' => '.merimag-builder-section-' . $area,
              'settings' => array( 
                'fw_options[builder_section_' . $area . ']',
          ),
          'render_callback' => function() use( $area ) {
              return merimag_get_builder_section( $area );
          },
      ));
    }
       

}
$all_in_live_customizer = merimag_get_db_customizer_option('all_in_live_customizer');
if( $all_in_live_customizer === 'yes' ) {
  add_action( 'customize_register', 'merimag_register_customizer_partials' );
}

function merimag_register_customizer_styling_partials( WP_Customize_Manager $wp_customize ) {

  if ( ! isset( $wp_customize->selective_refresh ) ) {
    return;
  }


  $areas         = array( 'body', 'site_container', 'content_container', 'article_content', 'header', 'header_inner', 'main_menu', 'main_menu_sub_menu', 'secondary_menu', 'secondary_menu_sub_menu', 'ticker', 'footer_tags', 'footer_about', 'footer', 'footer_infos', 'sticky_header', 'sticky_header_sub_menu', 'mobile_menu_panel', 'mobile_header');
  $settings = array();
  foreach( $areas as $area ) {
    $areas_options = merimag_get_area_options( $area );
    foreach( $areas_options as $area_option ) {
      $settings[] = 'fw_options[' . $area . '_' . $area_option . ']';
    }
  }
  $settings[] = 'fw_options[principal_color]';

  $custom_areas = array(
    'general_block',
    'general_widget',
    'general_footer_widget',
    'general_content_area',
    'general_sidebar_area',
  );
  
  foreach( $custom_areas as $custom_area ) {
      $custom_area_options = merimag_get_block_style_options( $custom_area );
      foreach( $custom_area_options as $custom_area_option => $option ) {
        $settings[] = 'fw_options[' . $custom_area_option . ']';
      }
  }
  $wp_customize->selective_refresh->add_partial( 'styling', array(
        'selector' => '#merimag-styling-wrapper',
        'settings' => $settings,
        'render_callback' => function() {
            return merimag_get_general_style( true );
        },
    ) );
       

}
add_action( 'customize_register', 'merimag_register_customizer_styling_partials' );


function merimag_customizer_load_scripts(){
  wp_enqueue_script( 'merimag-customizer-js', get_template_directory_uri() . '/assets/js/customizer.js', array('jquery', 'customize-preview'), THEME_VERSION, true );
  wp_localize_script( 'merimag-customizer-js', 'customizer_data', array('selectors' => merimag_custom_selectors(), 'typography_selectors' =>merimag_get_typography_selectors(), 'typography_components' => merimag_get_typography_components() ) );

}
add_action('customize_register', 'merimag_customizer_load_scripts');
function merimag_delete_customizer_cache() {
  global $wpdb;
  // sorry about format I hate scrollbars in answers.
    $shortcodes_cache = $wpdb->get_results(
       "SELECT option_name AS name, option_value AS value FROM $wpdb->options 
        WHERE option_name LIKE '_transient_merimag_customizer_%'"
    );
    if( is_array( $shortcodes_cache ) ) {
      foreach( $shortcodes_cache as $transient ) {
        if( isset( $transient->name ) ) {
          $name = str_replace('_transient_', '', $transient->name );
          delete_transient($name);
        }
      }
    }
}
add_action('customize_save', 'merimag_delete_customizer_cache');
function merimag_recognized_builder_sections( $keys = true ) {
  $sections = array(
    'before_header' => __('Before header', 'merimag-backend'),
    'after_header' => __('After header', 'merimag-backend'),
    'before_footer' => __('Before footer', 'merimag-backend'),
    'header_content' => __('Header content', 'merimag-backend'),
    'before_related_posts' => __('Before related posts', 'merimag-backend'),
    'after_read_also' => __('After related posts', 'merimag-backend'),
    'before_post_content' => __('Before post content', 'merimag-backend'),
    'after_post_content' => __('Before post content', 'merimag-backend'),
  );
  return $keys === true ? array_keys( $sections ) : $sections;
}
add_filter('elementor_builder_sections', 'merimag_recognized_builder_sections');

/**
 * Get recognized page layouts
 *
 * @param bool $keys
 * @return array list of recognized page layouts
 */
function merimag_get_recognized_page_layouts( $keys = false ) {
  $layouts = array(
    'default' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/default.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/default.png',
          'height' => 98
      ),
    ),
    'content-sidebar' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/content-sidebar.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/content-sidebar.png',
          'height' => 98
      ),
    ),
    'sidebar-content' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/sidebar-content.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/sidebar-content.png',
          'height' => 98
      ),
    ),
    'content' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/content.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/layouts/content.png',
          'height' => 98
      ),
    ),
  );
  return $keys === true ? array_keys( $layouts ) : $layouts;
}
/**
 * Get recognized post templates
 *
 * @param bool $keys
 * @return array list of recognized post templates
 */
function merimag_get_recognized_post_templates( $keys = false ) {
  $layouts = array(
    'default' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/default.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/default.png',
          'height' => 98
      ),
    ),
    '1' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/1.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/1.png',
          'height' => 98
      ),
    ),
    '2' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/2.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/2.png',
          'height' => 98
      ),
    ),
    '10' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/10.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/10.png',
          'height' => 98
      ),
    ),
    '11' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/11.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/11.png',
          'height' => 98
      ),
    ),
    '12' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/12.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/12.png',
          'height' => 98
      ),
    ),
    '3' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/3.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/3.png',
          'height' => 98
      ),
    ),
    '8' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/8.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/8.png',
          'height' => 98
      ),
    ),
    '4' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/4.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/4.png',
          'height' => 98
      ),
    ),
    '5' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/5.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/5.png',
          'height' => 98
      ),
    ),
    '6' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/6.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/6.png',
          'height' => 98
      ),
    ),
    '13' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/13.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/13.png',
          'height' => 98
      ),
    ),
    '7' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/7.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/7.png',
          'height' => 98
      ),
    ),
    '18' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/18.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/18.png',
          'height' => 98
      ),
    ),
    '14' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/14.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/14.png',
          'height' => 98
      ),
    ),
    '9' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/9.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/9.png',
          'height' => 98
      ),
    ),
    '15' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/15.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/15.png',
          'height' => 98
      ),
    ),
    '16' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/16.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/16.png',
          'height' => 98
      ),
    ),
    '17' => array(
      'small' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/17.png',
          'height' => 70
      ),
      'large' => array(
          'src' => get_template_directory_uri() .'/assets/images/post-layouts/17.png',
          'height' => 98
      ),
    ),
  );
  return $keys === true ? array_keys( $layouts ) : $layouts;
}
function merimag_wpmdm_menu_features( $features = array()) {
  $features = array(
    'post_templates_1' => array(
      'name' => 'Post templates',
      'type' => 'post',
      'option' => 'post_template',
      'values' => array(
        '1' => 'Post template 1',
        '2' => 'Post template 2',
        '3' => 'Post template 3',
        '4' => 'Post template 4',
        '5' => 'Post template 5',
        '6' => 'Post template 6',
        '7' => 'Post template 7',
        '8' => 'Post template 8',
        '9' => array( 'featured_media' => 'video_youtube', 'text' => 'Post template 9'),
      ),
    ),
    'post_templates_2' => array(
      'name' => 'Post templates',
      'type' => 'post',
      'option' => 'post_template',
      'values' => array(
        '10' => 'Post template 10',
        '11' => 'Post template 11',
        '12' => 'Post template 12',
        '13' => 'Post template 13',
        '14' => array( 'featured_media' => 'video_youtube', 'text' => 'Post template 14'),
        '15' => array( 'featured_media' => 'video_youtube', 'text' => 'Post template 15'),
        '16' => array( 'featured_media' => 'video_youtube', 'text' => 'Post template 16'),
        '17' => array( 'featured_media' => 'video_youtube', 'text' => 'Post template 17'),
        '18' => 'Post template 18',
      ),
    ),
    'post_formats' => array(
      'name' => 'Post formats',
      'type' => 'post',
      'option' => 'featured_media',
      'values' => array(
        'default' => 'Default',
        'video_youtube' => 'Video youtube',
        'video_vimeo' => 'Video vimeo',
        'video_self_hosted' => 'Video self hosted',
        'gallery' => 'Gallery',
        'gallery_tiles' => 'Gallery tiles',
        'audio_soundcloud' => 'Audio soundcloud',
        'audio_self_hosted' => 'Audio self hosted',
        'amp' => 'Amp example',
      ),
    ),
    'category_templates' => array(
      'name' => 'Category templates',
      'type' => 'category',
      'option' => 'category_grid_style',
      'values' => array(
        'default' => 'Default',
        'classic-1' => 'Classic 1',
        'classic-2' => 'Classic 2',
         'classic-3' => 'Classic 3',
         'classic-4' => 'Classic 4',
        'one-column-2'=> 'Large image',
        'two-column-2'=> 'Two columns',
        'three-column-2'=> 'Three columns',
        'four-column-4'=> array('text'=>'Four columns', 'layout_default' => 'content'),
        'masonry-4'=> 'Masonry',
        
      ),
    ),
  );
  return $features;
}
function merimag_wpmdm_support() {
  add_filter('wpmdm_menu_features', 'merimag_wpmdm_menu_features', 10, 1);
  if( class_exists('WPMDM') && isset( $_GET['featured_media'] ) ) {
    $featured_media = $_GET['featured_media'];
    if( strpos($featured_media, 'video') !== false ) {
      add_filter('merimag_get_post_format', function() {
        return 'video';
      });
    }
    if( strpos($featured_media, 'audio') !== false ) {
      add_filter('merimag_get_post_format', function() {
        return 'audio';
      });
    }
    if( strpos($featured_media, 'gallery') !== false ) {
      add_filter('merimag_get_post_format', function() {
        return 'gallery';
      });
    }
  }
  add_filter('wpmdm_shortcodes_list', function() {
    return merimag_get_shortcodes_list();
  });
  add_filter('wpmdm_grid_system_options', function() {
    return merimag_grid_settings('', false, 'post');
  });
  add_filter('wpmdm_grid_system_query_options', function() {
    return merimag_get_query_options('post');
  });
  add_filter('wpmdm_grid_system_product_query_options', function() {
    return merimag_get_query_options('product');
  });
  add_filter('wpmdm_grid_system_styling_options', function() {
    return merimag_get_block_style_options('product');
  });
  add_filter('wpmdm_grid_system_sliding_options', function() {
    return merimag_get_sliding_options();
  });
  add_filter('wpmdm_grid_system_carousel_sliding_options', function() {
    return merimag_get_sliding_options('', true);
  });
  add_filter('wpmdm_grid_system_animating_options', function() {
    return merimag_get_animation_options();
  });
  add_filter('wpmdm_grid_system_options_product', function() {
    return merimag_grid_settings('', false, 'product');
  });
}
add_action('after_setup_theme','merimag_wpmdm_support');

