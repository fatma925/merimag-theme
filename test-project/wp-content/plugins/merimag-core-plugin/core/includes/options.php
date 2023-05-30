<?php
function merimag_get_query_options( $post_type = 'post', $slug = '', $filters = true ) {
  
  $slug    = !empty($slug) ? $slug . '_' : '';
  $wp_post_type = in_array($post_type, array('post', 'post-mix')) ? 'post' : $post_type;
  $options = array(
    $slug . 'filter_title' => array(
        'type'  => 'text',
        'label' => __('Filter Title', 'merimag-backend'),
        'desc' => __('Main filter title recomanded if you have added filters', 'merimag-backend'),
    ),
    $slug . 'filter_icon' => array(
        'type'  => 'icon-v2',
        'label' => __('Filter icon', 'merimag-backend'),
        'desc' => __('Main filter icon recomanded if you have added filters with icons', 'merimag-backend'),
    ),
    $slug . 'order_by' => array(
        'type'  => 'select',
        'value' => 'rand',
        'label' => __('Order by', 'merimag-backend'),
        'choices' => merimag_get_recognized_order_by_options($wp_post_type),
        'desc' => __('Select how you want to order items', 'merimag-backend'),
    ),
    $slug . 'order' => array(
        'type'  => 'select',
        'value' => 'desc',
        'label' => __('Order', 'merimag-backend'),
        'choices' => merimag_get_recognized_order_options($wp_post_type),
        'desc' => __('Ascending or descending order can be selected from here', 'merimag-backend'),
    ),
    $slug . 'include' => array(
        'type'  => 'multi-select',
        'population' => 'posts',
        'label' => __('Include', 'merimag-backend'),
        'desc' => __('Select specific posts or products', 'merimag-backend'),
        'source' => $wp_post_type,
    ),
    $slug . 'exclude' => array(
        'type'  => 'multi-select',
        'population' => 'posts',
        'desc' => __('Exlude specific posts or products', 'merimag-backend'),
        'label' => __('Exclude', 'merimag-backend'),
        'source' => $wp_post_type,
    ),
    
    $slug . 'offset' => array(
        'type'  => 'number',
        'desc' => __('Number of items to load', 'merimag-backend'),
        'label' => __('Offset', 'merimag-backend'),
    ),
    $slug . 'period' => array(
        'type'  => 'select',
        'label' => __('Period', 'merimag-backend'),
        'desc' => __('Select only items from a defined period', 'merimag-backend'),
        'choices' => array(
          'all' => __('All time', 'merimag-backend'),
          'year' => __('This year', 'merimag-backend'),
          'month' => __('This month', 'merimag-backend'),
          'week' => __('This week', 'merimag-backend'),
          'today' => __('Today', 'merimag-backend'),
        ),
    ),

  );
  if( $filters !== true ) {
    unset( $options[$slug . 'filter_title']);
  }
  if( $wp_post_type === 'post' ) {
    $options[ $slug . 'only_reviews'] = array(
      'label'        => __( 'Only review posts', 'merimag-backend' ),
      'desc' => __('Select only posts that have review', 'merimag-backend'),
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
    );
    $options[ $slug . 'post_format'] = array(
      'label'        => __( 'Post format', 'merimag-backend' ),
      'desc' => __('Select only posts with a specific post format', 'merimag-backend'),
      'type'         => 'select',
      'choices'      => array(
        'all' => __('All', 'merimag-backend'),
        'video' => __('Video', 'merimag-backend'),
        'audio' => __('Audio', 'merimag-backend'),
        'gallery' => __('Gallery', 'merimag-backend'),
      ),
      'value' => 'all',
    );
  }
  if( $wp_post_type  === 'product' ) {
    $options[ $slug . 'on_sale'] = array(
      'label'        => __( 'On sale products', 'merimag-backend' ),
      'desc' => __('Select only on sale products', 'merimag-backend'),
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
    );
  }
  $cached_taxs = get_transient('merimag_cache_taxonomies'. $wp_post_type);
  $enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
  if( isset( $cached_taxs[$wp_post_type] ) && is_array( $cached_taxs[$wp_post_type] ) && $enable_cache === 'yes' ) {
    $taxonomies = $cached_taxs[$wp_post_type];
  } else {
    $taxonomies = get_object_taxonomies( $wp_post_type, 'objects' );
    $cached_taxs[$wp_post_type] = $taxonomies;
    set_transient('merimag_cache_taxonomies'. $wp_post_type, $cached_taxs );
  }
  foreach( (array) $taxonomies as $tax => $tax_object ) :
    if( $tax == 'post_format' ) {
      continue;
    }
    $terms = get_terms( $tax, array(
        'hide_empty' => false,
    ) );
    if( is_countable( $terms ) && count( $terms ) > 0 ) {
      $options[ $slug . $tax ] = array(
        'type'  => 'multi-select',
        'desc' => __('Select posts from a specific taxonomy', 'merimag-backend'),
        'label' => $tax_object->labels->singular_name,
        'population' => 'taxonomy',
        'source' => $tax,
      );
    }
  endforeach;
  if( $post_type !== 'post-mix' ) {
    $options[ $slug . 'number'] = array(
        'type'  => 'slider',
        'value' => get_option('posts_per_page'),
        'desc' => __('Select number of posts', 'merimag-backend'),
        'size_unit' => 'px',
        'properties' => array(
            'min' => 0,
            'max' => 30,
            'step' => 1,
        ),
        'label' => __('Number of posts', 'merimag-backend'),
    );
  }
  $sub_options = $options;
  unset( $sub_options[$slug . 'number']);

  if( $filters === true ) {
    $options[$slug . 'mouseover_load'] = array(
      'label'        => __( 'Load on mouse over', 'merimag-backend' ),
      'type'         => 'switch',
      'desc' => __('If you have filters select yes if you want to load items on mouse over or no for click', 'merimag-backend'),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'        => 'no',
    );
    $options[$slug . 'filters_style'] = array(
      'type'  => 'select',
      'value' => 'beside_title',
      'desc' => __('Select how you want to show filters', 'merimag-backend'),
      'label' => __('Filters style', 'merimag-backend'),
      'choices' => array(
        'beside_title' => __('Beside title', 'merimag-backend'),
        'tabs' => __('Tabs', 'merimag-backend'),
        'vertical_tabs' => __('Verical tabs', 'merimag-backend'),
        'buttons' => __('Buttons', 'merimag-backend'),
      ),
    );
    $options[$slug . 'filters'] = array(
      'type' => 'addable-popup',
      'desc' => __('Add ajax filters here and select different queries for each filter', 'merimag-backend'),
      'label' => __('Add AJAX filter', 'merimag-backend'),
      'popup-options' => $sub_options,
      'template' => '{{- filter_title }}',
      'title_field' => '{{{ filter_title }}}',
    );
  }

  return $options;
}
function merimag_get_instagram_options( $widget = false ) {
  $options = array(
    'instagram_usertag' => array(
      'type' => 'text',
      'label' => __('@username or #tag', 'merimag-backend'),
    ),
    'instagram_number' => array(
      'type' => 'number',
      'label' => __('Number of photos ( max 33 )', 'merimag-backend'),
    ),
    'instagram_only_images' => array(
      'type' => 'switch',
      'label' => __('Ignore videos', 'merimag-backend'),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'        => 'no',
    ),
    'instagram_cols' => array(
      'type' => 'select',
      'label' => __('Number of columns', 'merimag-backend'),
      'choices' => $widget === false ? array(
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
      ) : array(
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
      ),
    ),
    'instagram_padding' => array(
      'type' => 'number',
      'label' => __('Spacing between images', 'merimag-backend'),
    ),
    'instagram_follow_button' => array(
      'type' => 'switch',
      'label' => __('Show button', 'merimag-backend'),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'        => 'yes',
    ),

    'instagram_follow_button_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Follow button color', 'merimag-backend'),
    ),
    'instagram_follow_text' => array(
      'type' => 'text',
      'label' => __('Follow button text', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_search_options( $widget = false ) {
  $options = array(
    'post_type' => array(
      'type' => 'select',
      'label' => __('Post type', 'merimag-backend'),
      'choices' => merimag_get_public_post_types(),
    ),
  );
  return $options;
}
function merimag_get_comments_options( $widget = false ) {
  $options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Block title', 'merimag-backend'),
    ),
    'number' => array(
      'type' => 'number',
      'label' => __('Number of comments', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_demo_ad_options( $widget = false ) {
  $options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Block title', 'merimag-backend'),
    ),
    'size' => array(
      'type' => 'text',
      'label' => __('Size', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_public_post_types() {
  $post_types = get_post_types( array('public' => true, 'publicly_queryable' => true), 'objects');
  $pts['all'] = __('All', 'merimag-backend');
  foreach( $post_types as $post_type ) {
    if( !isset( $post_type->name ) ) {
      continue;
    }
    $label = isset( $post_type->label ) ? $post_type->label : $post_type->name;
    $pts[$post_type->name] = $label;
  }
  return $pts;
}
function merimag_get_query_options_for_category( $slug = '' ) {
  $slug    = !empty($slug) ? $slug . '_' : '';
  $options = array(
    'parent' => array(
      'type'  => 'multi-select',
      'population' => 'taxonomy',
      'source' => 'category',
      'label' => __('Parent', 'merimag-backend'),
      'limit' => 1,
    ),
    'include' => array(
      'type'  => 'multi-select',
      'label' => __('Include', 'merimag-backend'),
      'population' => 'taxonomy',
      'source' => 'category',
    ),
    'exclude' => array(
      'type'  => 'multi-select',
      'population' => 'taxonomy',
      'label' => __('Exclude', 'merimag-backend'),
      'source' => 'category',
    ),
  );
  return $options;
}
function merimag_get_query_options_for_product_cat( $slug = '' ) {
  $slug    = !empty($slug) ? $slug . '_' : '';
  

  $options = array(
    'parent' => array(
      'type'  => 'multi-select',
      'population' => 'taxonomy',
      'source' => 'product_cat',
      'label' => __('Parent', 'merimag-backend'),
      'limit' => 1,
    ),
    'include' => array(
      'type'  => 'multi-select',
      'population' => 'taxonomy',
      'source' => 'product_cat',
    ),
    'exclude' => array(
      'type'  => 'multi-select',
      'population' => 'taxonomy',
      'source' => 'product_cat',
    ),
  );

  return $options;
}
function merimag_get_accordion_options() {
  $options = array(
    'collapsible' => array(
      'label'        => __( 'Collapsible', 'merimag-backend' ),
      'type'         => 'switch',
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value' => 'no',
    ),
    'color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Color', 'merimag-backend'),
    ),
    'title_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Title color', 'merimag-backend'),
    ),
    'tabs' => array(
      'type' => 'addable-popup',
      'label' => __('Toggles', 'merimag-backend'),
      'popup-options' => array(
        'title' => array(
          'type' => 'text',
          'label' => __('Toggle title' , 'merimag-backend'),
        ),
        'content' => array(
          'type' => 'wp-editor',
          'label' => __('Toggle content' , 'merimag-backend'),
          'shortcodes' => true,
        ),

      ),
      'value' => merimag_tabs_default_content(),
      'template' => '{{- title }}',
      'title_field' => '{{{ title }}}',
    ),
  );
  return $options;
}

function merimag_get_social_icons_options( $only_icon = false, $add_icons = true ) {
  $general_options = array(
    'icons_columns' => array(
      'type' => 'select',
      'value' => '4',
      'label' => __('Columns', 'merimag-backend'),
      'choices' => array( 'one' => __('One column', 'merimag-backend'), 'flex' => __('Flexible', 'merimag-backend'), '2' => __('2 columns', 'merimag-backend'), '3' => __('3 columns', 'merimag-backend'), '4' => __('4 columns', 'merimag-backend'), '5' => __('5 columns', 'merimag-backend'), '6' => __('6 columns', 'merimag-backend'))
    ),
    'icons_theme' => array(
      'label' => __('Icon Theme', 'merimag-backend'),
      'type' => 'image-picker',
      'choices'=> merimag_get_recognized_icons_themes( $only_icon ),
    ),
    'icons_spacing' => array(
      'type' => 'select',
      'label' => __('Spacing between icons', 'merimag-backend'),
      'value' => 'default',
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'small' => __('Small', 'merimag-backend'),
        'big' => __('Big', 'merimag-backend'),
        'no' => __('No spacing', 'merimag-backend'),
      ),
    ),
    'icons_layout' => array(
      'label'        => __( 'Layout', 'merimag-backend' ),
      'type'         => 'select',
      'value' => $only_icon === true ? 'icons_infos_bellow' : 'only_icon',
      'choices'=> array(
        'icons_infos_bellow' => __('Infos bellow', 'merimag-backend'),
        'icons_infos_beside_1 icons_infos_beside' => __('Infos beside 1', 'merimag-backend'),
        'icons_infos_beside_2 icons_infos_beside' => __('Infos beside 2', 'merimag-backend'),
        'only_icon' => __('Only icons', 'merimag-backend'),
      )
    ),
    'icons_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Icons color', 'merimag-backend'),
    ),
    'icon_size' => array(
      'type' => 'number',
      'value' => $only_icon === true ? '5' : '12',
      'label' => __('Size', 'merimag-backend'),
    ),
    'centered_icons' => array(
      'type' => 'switch',
      'label' => __('Center icons', 'merimag-backend'),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'        => 'no',
    ),
    'show_count' => array(
      'label'        => __( 'Show count', 'merimag-backend' ),
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
    ),
    'show_title' => array(
      'label'        => __( 'Show title', 'merimag-backend' ),
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
    ),
    'show_action' => array(
      'label'        => __( 'Show action', 'merimag-backend' ),
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
    ),
  );
  if( $only_icon === true ) {
    unset( $general_options['icons_layout'], $general_options['icons_columns'], $general_options['show_count'], $general_options['show_title'], $general_options['show_action'] );
  }
  $options = merimag_social_options( $only_icon );
  return $add_icons === true ? array_merge($general_options, $options) : $general_options;
}
function merimag_social_options( $only_icon = false ) {
  $options = array(
    'social_links' => array(
        'type' => 'addable-box',
        'title' => __('Add networks', 'merimag-backend'),
        'box-options' => array(
            'network' => array(
              'type' => 'select',
              'label' => __('Add network', 'merimag-backend'),
              'choices' => merimag_get_recognized_social_networks( false, 'name'),
            ),
            'count' => array(
              'type' => 'text',
              'label' => __('Count', 'merimag-backend'),
            ),
            'title' => array(
              'type' => 'text',
              'label' => __('Title', 'merimag-backend'),
            ),
            'action' => array(
              'type' => 'text',
              'label' => __('Action ( ex : Follow us )', 'merimag-backend'),
            ),
            'link' => array(
              'type' => 'text',
              'label' => __('Link', 'merimag-backend'),
            ),
        ),
        'value' => array(
          array(
            'network' => 'facebook',
          ),
          array(
            'network' => 'twitter',
          ),
          array(
            'network' => 'pinterest',
          ),
          array(
            'network' => 'instagram',
          ),
          array(
            'network' => 'youtube',
          ),
          array(
            'network' => 'vimeo',
          ),
        ),
        'template' => '{{- network }}',
        'title_field' => '{{{ network }}}',
    ),
  );
   if( $only_icon === true ) {
    unset( $options['social_links']['box-options']['count'], $options['social_links']['box-options']['action'] );
  }
  return $options;
}
function merimag_get_ticker_options( $slug = '') {
  $query_options   = merimag_get_query_options( 'post', $slug, false );
  $general_options = array(
    'ticker_title' => array(
      'title'   => __( 'Title', 'merimag-backend' ),
      'type'    => 'text',
    ),
  );
  $breaking_options = array_merge( $general_options, $query_options );
  return $breaking_options;
}
function merimag_get_tabs_options() {
  $options = array(
    'vertical_tabs' => array(
      'label'        => __( 'Vertical tabs', 'merimag-backend' ),
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
    ),
    'tabs' => array(
      'type' => 'addable-popup',
      'label' => __('Tabs', 'merimag-backend'),
      'popup-options' => array(
        'title' => array(
          'type' => 'text',
          'label' => __('Tab title' , 'merimag-backend'),
        ),
        'content' => array(
          'type' => 'wp-editor',
          'label' => __('Tab content' , 'merimag-backend'),
          'shortcodes' => true,
        ),
        
      ),
      'value' => merimag_tabs_default_content(),
      'template' => '{{- title }}',
      'title_field' => '{{{ title }}}',
    ),
  );
  return $options;
}
function merimag_tabs_default_content() {
  return array(
    array(
      'title' => __('Quisque lacinia', 'merimag-backend'),
      'content' => __('<h3>Quisque lacinia blandit dui</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et neque erat. Quisque lacinia blandit dui, et pharetra ligula. Cras eu magna velit. Nullam at sapien augue. Donec ac feugiat lacus. Integer cursus urna eu feugiat maximus. Etiam ut orci eros. Praesent ut dapibus orci. Donec consequat vel leo vel feugiat.</p>', 'merimag-backend') 
    ),
    array(
      'title' => __('Nullam at sapien', 'merimag-backend'),
      'content' => __('<h3>Quisque lacinia blandit dui</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et neque erat. Quisque lacinia blandit dui, et pharetra ligula. Cras eu magna velit. Nullam at sapien augue. Donec ac feugiat lacus. Integer cursus urna eu feugiat maximus. Etiam ut orci eros. Praesent ut dapibus orci. Donec consequat vel leo vel feugiat.</p>', 'merimag-backend') 
    ),
    array(
      'title' => __('Cras et neque', 'merimag-backend'),
      'content' => __('<h3>Quisque lacinia blandit dui</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et neque erat. Quisque lacinia blandit dui, et pharetra ligula. Cras eu magna velit. Nullam at sapien augue. Donec ac feugiat lacus. Integer cursus urna eu feugiat maximus. Etiam ut orci eros. Praesent ut dapibus orci. Donec consequat vel leo vel feugiat.</p>', 'merimag-backend') 
    ),
  );
}
function merimag_get_icon_box_options() {
  $options['icon_options'] = array(
    'type' => 'tab',
    'title' => __('Icon', 'merimag-backend'),
    'options' => merimag_get_icon_options(),
  );
  $options['content_options'] = array(
    'type' => 'tab',
    'title' => __('Content', 'merimag-backend'),
    'options' => array(
      'title' => array(
        'type' => 'text',
        'label' => __('Title', 'merimag-backend'),
        'value' => __('Quisque lacinia', 'merimag-backend'),
      ),
      'description' => array(
        'type' => 'textarea',
        'label' => __('Description', 'merimag-backend'),
        'value' => __('Lorem ipsum dolor sit amet, consectetur', 'merimag-backend'),
      ),
    ),
  );
  return $options;
}
function merimag_get_quotation_options() {
  $options = array(
    'quote_style' => array(
        'type' => 'select',
        'choices' => array_combine(range(1,11), range(1,11)),
    ),
    'quote_width' => array(
        'type' => 'slider',
        'label' => __('Max width', 'merimag-backend'),
        'value' => 100,
        'properties' => array(
            'min' => 20,
            'max' => 100,
            'step' => 1,
        ),
    ),
    'quote_align' => array(
        'type' => 'select',
        'label' => __('Quote float', 'merimag-backend'),
        'value' => 'quote-center',
        'choices' => array('quote-float-left' => __('Float left','merimag-backend'), 'quote-float-right' => __('Float right', 'merimag-backend'),'quote-center' => __('Center', 'merimag-backend') ),
    ),
    'quote_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Color', 'merimag-backend'),
    ),
    'quote_background' => array(
      'type' => 'color-picker-v2',
      'label' => __('Quotation backgorund', 'merimag-backend'),
      'desc'=> __(' applied only for styles with background', 'merimag-backend'),
    ),
    'quote' => array(
        'type' => 'textarea',
        'label' => __('Quotation', 'merimag-backend'),
        'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras et neque erat. Quisque lacinia blandit dui, et pharetra ligula. Cras eu magna velit. Nullam at sapien augue. Donec ac feugiat lacus. Integer cursus urna eu feugiat maximus. Etiam ut orci eros. Praesent ut dapibus orci. Donec consequat vel leo vel feugiat.',
    ),
    'author_name' => array(
        'type' => 'text',
        'label' => __('Author full name', 'merimag-backend'),
        'value' => 'INTEGER Diam',
    ),
    'author_company' => array(
        'type' => 'text',
        'label' => __('Author company name', 'merimag-backend'),
        'value' => 'Ullamcorper Sed'
    ),
    'author_img' => array(
        'type' => 'upload',
        'label' => __('Author photo', 'merimag-backend'),
        'images_only' => true,
    ),
    'author_link' => array(
        'type' => 'text',
        'label' => __('Author website', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_author_options() {
  $general_options = merimag_get_general_member_options( true, false );
  $social_options  = merimag_get_social_icons_options( true, false );
  $options['general'] = array(
    'type' => 'tab',
    'title' => __('General', 'merimag-backend'),
    'options' => $general_options,
  );
  $options['social'] = array(
    'type' => 'tab',
    'title' => __('Social icons styling', 'merimag-backend'),
    'options' => $social_options,
  );
  return $options;
}
function merimag_get_authors_options() {
  $general_options = merimag_get_general_member_options( true, true );
  $social_options  = merimag_get_social_icons_options( true, false );
  $options['general'] = array(
    'type' => 'tab',
    'title' => __('General', 'merimag-backend'),
    'options' => $general_options,
  );
  $options['social'] = array(
    'type' => 'tab',
    'title' => __('Social icons styling', 'merimag-backend'),
    'options' => $social_options,
  );
  return $options;
}
function merimag_get_member_options() {
  $general_options = merimag_get_general_member_options( false, false );
  $social_options  = merimag_get_social_icons_options( true, false );
  $options['general'] = array(
    'type' => 'tab',
    'title' => __('General', 'merimag-backend'),
    'options' => $general_options,
  );
  $options['social'] = array(
    'type' => 'tab',
    'title' => __('Social icons styling', 'merimag-backend'),
    'options' => $social_options,
  );
  return $options;
}
function merimag_get_team_options() {
  return merimag_get_general_member_options( false, true );
}
function merimag_get_about_options( $author = false ) {
  $general_options = merimag_get_general_about_options();
  $social_options  = merimag_get_social_icons_options( true );
  $options['general'] = array(
    'type' => 'tab',
    'title' => __('General', 'merimag-backend'),
    'options' => $general_options,
  );
  $options['social'] = array(
    'type' => 'tab',
    'title' => __('Social icons styling', 'merimag-backend'),
    'options' => $social_options,
  );
  return $options;
}
function merimag_get_video_playlist_options() {
  $options = array(
    'playlist_title' => array(
        'type' => 'text',
        'label' => 'title',
      ),
    'items' => array(
      'type' => 'addable-popup',
      'label' => __('Add items', 'merimag-backend'),
      'popup-options' => array(
        'media_type' => array(
          'type' => 'select',
          'choices' => array(
            'video' => __('Self hosted video', 'merimag-backend'),
            'external_video' => __('Externa video ( Youtube, vimeo, ... )', 'merimag-backend'),
          ),
        ),
        'title' => array(
          'type' => 'text',
          'label' => 'title',
        ),
        'url' => array(
          'type' => 'text',
          'label' => __('Url', 'merimag-backend'),
          'condition' => array('media_type' => 'external_video'),
        ),
        'upload' => array(
          'type' => 'upload',
          'label' => __('Upload video', 'merimag-backend'),
          'images_only' => false,
          'media_type' => 'video',
          'condition' => array('media_type' => 'video'),
        ),
        'cover' => array(
          'type' => 'upload',
          'label' => __('Upload audio cover', 'merimag-backend'),
          'condition' => array('media_type' => 'video'),
        ),
      ),
      'template' => '{{- title }}',
      'title_field' => '{{{ title }}}',
    ),
    'principal_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Controls color', 'merimag-backend'),
    ),

  );
  return $options;
}
function merimag_get_embed_options() {
  $options = array(
    'url' => array(
      'type' => 'text',
      'label' => __('Url', 'merimag-backend'),
    ),
    'width' => array(
      'type' => 'number',
      'label' => __('Width', 'merimag-backend'),
    ),
    'height' => array(
      'type' => 'number',
      'label' => __('Height', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_video_options() {
  $options = array(
    'media_type' => array(
      'type' => 'select',
      'choices' => array(
        'external_media' => __('External link', 'merimag-backend'),
        'video' => __('Self Hosted', 'merimag-backend'),
      ),
    ),
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'author' => array(
      'type' => 'text',
      'label' => __('Author', 'merimag-backend'),
    ),
    'url' => array(
      'type' => 'text',
      'label' => __('Url', 'merimag-backend'),
      'condition' => array('media_type' => 'external_media')
    ),
    'upload' => array(
      'type' => 'upload',
      'label' => __('Upload video', 'merimag-backend'),
      'images_only' => false,
      'media_type' => 'video',
      'condition' => array('media_type' => 'video')
    ),
    'cover' => array(
      'type' => 'upload',
      'label' => __('Upload cover', 'merimag-backend'),
      'condition' => array('media_type' => 'video')
    ),
    'controls_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Controls color', 'merimag-backend'),
    ),
    'width' => array(
      'type' => 'number',
      'label' => __('Width', 'merimag-backend'),
    ),
    'height' => array(
      'type' => 'number',
      'label' => __('Height', 'merimag-backend'),
    ),
    'align' => array(
      'type' => 'select',
      'label' => __('Align', 'merimag-backend'),
      'choices' => array(
        'none' => __('None', 'merimag-backend'),
        'left' => __('Left', 'merimag-backend'),
        'right' => __('Right', 'merimag-backend'),
        'center' => __('Center', 'merimag-backend'),
      ),
    ),
  );
  return $options;
}
function merimag_get_audio_options() {
  $options = array(
    'media_type' => array(
      'type' => 'select',
      'choices' => array(
        'external_media' => __('External link', 'merimag-backend'),
        'audio' => __('Self Hosted', 'merimag-backend'),
      ),
    ),
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'author' => array(
      'type' => 'text',
      'label' => __('Author', 'merimag-backend'),
    ),
    'url' => array(
      'type' => 'text',
      'label' => __('Url', 'merimag-backend'),
      'condition' => array('media_type' => 'external_media')
    ),
    'upload' => array(
      'type' => 'upload',
      'label' => __('Upload audio', 'merimag-backend'),
      'images_only' => false,
      'media_type' => 'video',
      'condition' => array('media_type' => 'audio')
    ),
    'cover' => array(
      'type' => 'upload',
      'label' => __('Upload cover', 'merimag-backend'),
      'condition' => array('media_type' => 'audio')
    ),
    'controls_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Controls color', 'merimag-backend'),
    ),
    'width' => array(
      'type' => 'number',
      'label' => __('Width', 'merimag-backend'),
    ),
    'height' => array(
      'type' => 'number',
      'label' => __('Height', 'merimag-backend'),
    ),
    'align' => array(
      'type' => 'select',
      'label' => __('Align', 'merimag-backend'),
      'choices' => array(
        'none' => __('None', 'merimag-backend'),
        'left' => __('Left', 'merimag-backend'),
        'right' => __('Right', 'merimag-backend'),
        'center' => __('Center', 'merimag-backend'),
      ),
    ),
  );
  return $options;
}
function merimag_get_image_options() {
  $options = array(
    'media_type' => array(
      'type' => 'select',
      'choices' => array(
        'external_media' => __('External link', 'merimag-backend'),
        'image' => __('Self Hosted', 'merimag-backend'),
      ),
    ),
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'author' => array(
      'type' => 'text',
      'label' => __('Author', 'merimag-backend'),
    ),
    'url' => array(
      'type' => 'text',
      'label' => __('Url', 'merimag-backend'),
      'condition' => array('media_type' => 'external_media')
    ),
    'upload' => array(
      'type' => 'upload',
      'label' => __('Upload image', 'merimag-backend'),
      'images_only' => true,
      'condition' => array('media_type' => 'image')
    ),
    'alt_text' => array(
      'type' => 'text',
      'label' => __('Alternative text', 'merimag-backend'),
    ),
    'width' => array(
      'type' => 'number',
      'label' => __('Width', 'merimag-backend'),
    ),
    'height' => array(
      'type' => 'number',
      'label' => __('Height', 'merimag-backend'),
    ),
    'align' => array(
      'type' => 'select',
      'label' => __('Align', 'merimag-backend'),
      'choices' => array(
        'none' => __('None', 'merimag-backend'),
        'left' => __('Left', 'merimag-backend'),
        'right' => __('Right', 'merimag-backend'),
        'center' => __('Center', 'merimag-backend'),
      ),
    ),
  );
  return $options;
}
function merimag_get_general_about_options_for_widget() {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $about_options      = merimag_get_general_about_options();
  $social_options     = merimag_get_social_icons_options( true );
  $about_options      = array_merge( $general_options, $about_options );
  $general_options    = array_merge( $about_options, $social_options );
  $options['general'] = array(
    'type' => 'popup',
    'title' => __('General', 'merimag-backend'),
    'popup-options' => $general_options,
    'popup-title' => __('General options','merimag-backend'),
    'label' => __('General', 'merimag-backend'),
  );
  return $options;
}
function merimag_get_instagram_options_for_widget()  {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $instagram_options  = merimag_get_instagram_options( true );
  $general_options    = array_merge( $general_options, $instagram_options );
  $options['general'] = array(
    'type' => 'popup',
    'title' => __('General', 'merimag-backend'),
    'popup-options' => $general_options,
    'popup-title' => __('General options','merimag-backend'),
    'label' => __('General', 'merimag-backend'),
  );
  return $options;
}
function merimag_get_tabbed_widget_options( $widget = false ) {
  $options = array();
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $general_options['recent_title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Recent tab title', 'merimag-backend'),
  );
  $general_options['popular_title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Popular tab title', 'merimag-backend'),
  );
  $general_options['comments_title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Comments tab title', 'merimag-backend'),
  );
  if( $widget === false ) {
    return $general_options;
  }
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_general_about_options() {
  $options = array(
    'business_layout' => array(
      'type' => 'select',
      'label' => __('Layout', 'merimag-backend'),
      'choices' => array(
        'simple' => __('Simple', 'merimag-backend'),
        'flex' => __('Flex', 'merimag-backend'),
      ),
    ),
    'business_name' => array(
      'type' => 'text',
      'label' => __('Business name', 'merimag-backend'),
      'value' => get_bloginfo('name'),
    ),
    'business_logo' => array(
      'type' => 'upload',
      'label' => __('Business logo', 'merimag-backend'),
      'only_images' => true,
    ),
    'business_logo_height' => array(
      'type' => 'number',
      'label' => __('Logo height', 'merimag-backend'),
    ),
    'business_about' => array(
      'type' => 'textarea',
      'label' => __('About business', 'merimag-backend'),
      'value' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam accumsan nunc mi. Etiam id ultricies dui. Nullam eget nisl in nisi elementum pellentesque.', 'merimag-backend' ),
    ),
    'business_url' => array(
      'type' => 'wplink',
      'label' => __('Business website', 'merimag-backend'),
    ),
    'business_centered' => array(
      'type' => 'switch',
      'label' => __('Centered', 'merimag-backend'),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'        => 'no',
    ),
  );
  return $options;
}
function merimag_get_general_member_options( $author = false, $multi = false ) {
  $multi_options = array(
    'member_columns' => array(
        'type' => 'select',
        'value' => '1',
        'label' => __('Columns', 'merimag-backend'),
        'choices' => array(
          '1' => __('1 column', 'merimag-backend'),
          '2' => __('2 columns', 'merimag-backend'),
          '3' => __('3 columns', 'merimag-backend'),
          '4' => __('4 columns', 'merimag-backend'),
          '5' => __('5 columns', 'merimag-backend'),
        ),
    ),
    'member_sliding' => array(
        'type' => 'switch',
        'label' => __('Display as slider', 'merimag-backend'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),
    'member_show_dots' => array(
        'type' => 'switch',
        'label' => __('Show dots ( for slider )', 'merimag-backend'),
        'value' => 'yes',
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
    'member_layout' => array(
        'type' => 'select',
        'value' => 'image-above',
        'label' => __('Layout', 'merimag-backend'),
        'choices' => array(
          'image-above' => __('Image above', 'merimag-backend'),
          'image-beside' => __('Image beside', 'merimag-backend'),
          'image-beside-title' => __('Image beside title', 'merimag-backend'),
        ),
    ),
    'member_border_style' => array(
        'type' => 'select',
        'value' => 'none',
        'label' => __('Layout', 'merimag-backend'),
        'choices' => array(
          'none' => __('No borders', 'merimag-backend'),
          'top-bottom' => __('Top and bottom simple', 'merimag-backend'),
          'solid' => __('Simple', 'merimag-backend'),
          'double' => __('Double', 'merimag-backend'),
          'dotted' => __('Dotted', 'merimag-backend'),
          'dashed' => __('Dashed', 'merimag-backend'),
        ),
    ),
    'member_color' => array(
        'type' => 'color-picker-v2',
        'label' => __('Color', 'merimag-backend'),
    ),
    'rounded_image' => array(
        'type' => 'switch',
        'label' => __('Rounded image', 'merimag-backend'),
        'value' => 'no',
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
  if( $author === true ) {
    if( $multi === false ) {
      $author_options = array(
        'author_id' => array(
            'type' => 'select',
            'label' => __('Select author', 'merimag-backend'),
            'choices' => merimag_get_users(),
        )
      );
    } else {
      $author_options = array(
        'authors' => array(
            'type' => 'multi-select',
            'label' => __('Select author', 'merimag-backend'),
            'choices' => merimag_get_users(),
        )
      );
    }
  } else {
    if( $multi === true ) {
      $author_options = array( 
        'authors' => array(
          'type' => 'addable-popup',
          'popup-options' => merimag_get_author_infos_options(),
          'label' => __('Members', 'merimag-backend'),
          'title' => __('Members', 'merimag-backend'),
        )
      );
    } else {
      $author_options = merimag_get_author_infos_options();
    }
  }
  return $multi === true ? array_merge( $multi_options, $options, $author_options ) : array_merge( $options, $author_options );
}
function merimag_get_author_infos_options() {
  $options = array(
    'author_name' => array(
        'type' => 'text',
        'label' => __('Author full name', 'merimag-backend'),
        'value' => 'INTEGER Diam',
    ),
    'author_company' => array(
        'type' => 'text',
        'label' => __('Author company name', 'merimag-backend'),
        'value' => 'Ullamcorper Sed'
    ),
    'author_position' => array(
        'type' => 'text',
        'label' => __('Author position', 'merimag-backend'),
        'value' => 'General manager'
    ),
    'author_bio' => array(
        'type' => 'textarea',
        'label' => __('Author biography', 'merimag-backend'),
        'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ac nisl vehicula, finibus dui at, convallis sapien. Quisque a augue velit. Sed commodo, justo non feugiat ornare, sem purus consectetur mi, blandit semper velit erat non tellus.',
    ),
    'author_img' => array(
        'type' => 'upload',
        'label' => __('Author photo', 'merimag-backend'),
        'only_images' => true,
    ),
    'author_link' => array(
        'type' => 'wplink',
        'label' => __('Author website', 'merimag-backend'),
    ),
  );
  $social_options = merimag_social_options();
  return array_merge( $options, $social_options );
}
function merimag_get_dropcap_options() {
  $options = array(
    'dropcap_paragraph' => array(
      'type' => 'textarea',
      'label' => __('Paragraph', 'merimag-backend'),
      'value' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a ex dictum, dapibus ligula maximus, bibendum arcu. Suspendisse id turpis vel dui vehicula mattis quis quis dolor. Mauris eleifend magna leo, eget viverra nisl laoreet at. Curabitur vestibulum bibendum vestibulum. Donec nec condimentum urna. Nunc dapibus odio vel laoreet condimentum. Pellentesque eu auctor diam. Duis lobortis dignissim arcu quis posuere. Suspendisse imperdiet ullamcorper justo eu tristique.', 'merimag-backend'),
    ),
    'dropcap_font_family' => array(
      'type'  => 'select',
      'elementor_type' => 'typography-v3',
      'choices' => merimag_get_selected_google_fonts(),
      'label' => __('Typography', 'merimag-backend'),
    ),
    'dropcap_style' => array(
      'type' => 'select',
      'label' => __('Dropcap Style', 'merimag-backend'),
      'choices' => array(
        'simple' => __('Simple', 'merimag-backend'),
        'bordered' => __('With border', 'merimag-backend'),
        'bordered with-radius' => __('With border and rounded corners', 'merimag-backend'),
        'bordered full-circle' => __('With border and circular', 'merimag-backend'),
        'background' => __('With background', 'merimag-backend'),
        'background with-radius' => __('With background and rounded corners', 'merimag-backend'),
        'background full-circle' => __('With background and circular', 'merimag-backend'),
      ),
    ),
    'dropcap_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Dropcap color', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_alert_options() {
  $options = array(
    'alert_icon' => array(
      'type' => 'icon-v2',
      'label' => __('Alert icon', 'merimag-backend'),
      'value' => array('value' => 'fa fa-bell')
    ),
    'alert_title' => array(
      'type' => 'text',
      'value' => __('Alert title', 'merimag-backend'),
      'label' => __('Alert title', 'merimag-backend'),
    ),
    'alert_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Alert color', 'merimag-backend'),
    ),
    'alert_text_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Alert text color', 'merimag-backend'),
    ),
    'alert_message' => array(
      'type' => 'textarea',
      'label' => __('Alert message', 'merimag-backend'),
      'value' => __('This an alert message with icon and title', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_row_options() {
  $options = array(
    'row_spacing' => array(
        'type'   => 'select',
        'value' => 'medium',
        'label'  => esc_html__('Spacing between columns', 'merimag-backend'),
        'choices' => merimag_get_recognized_grid_spacing(),
    ),
    'row_columns' => array(
      'type' => 'addable-popup',
      'label' => __('Columns', 'merimag-backend'),
      'popup-options' => array(
        'width' => array(
          'type' => 'select',
          'choices' => array(
            '100' => '1/1',
            '50'  => '1/2',
            '33'  => '1/3',
            '66'  => '2/3',
            '75'  => '3/4',
            '25'  => '1/4',
            '80'  => '4/5',
            '20'  => '1/5',
            '83'  => '5/6',
            '16'  => '1/6',
          ),
        ),
        'content' => array(
          'type' => 'wp-editor',
          'label' => __('Content', 'merimag-backend'),
          'shortcodes' => true,
        ),
      ),
      'template' => '{{- width }}',
    ),
  );
  return $options;
}
function merimag_get_custom_list_options() {
  $options = array(
    'list_icon' => array(
      'type' => 'icon-v2',
      'label' => __('Icon', 'merimag-backend'),
    ),
    'list_layout' => array(
      'type' => 'select',
      'label' => __('Layout', 'merimag-backend'),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'flex' => __('Flex', 'merimag-backend'),
      ),
    ),
    'list_size' => array(
      'type' => 'select',
      'value' => 'inherit',
      'label' => __('Size', 'merimag-backend'),
      'choices' => array(
        'inherit' => __('Default', 'merimag-backend'),
        '14' => __('1', 'merimag-backend'),
        '18' => __('2', 'merimag-backend'),
        '24' => __('3', 'merimag-backend'),
        '34' => __('4', 'merimag-backend'),
        '44' => __('5', 'merimag-backend'),
        '54' => __('6', 'merimag-backend'),
        '64' => __('7', 'merimag-backend'),
      ),
    ),
    'icon_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Icon color', 'merimag-backend'),
    ),
    'list_items' => array(
      'type' => 'addable-box',
      'label' => __('Items', 'merimag-backend'),
      'box-options' => array(
        'list_item_icon' => array(
          'type' => 'icon-v2',
          'label' => __('Custom item icon', 'merimag-backend'),
        ),
        'list_item_text' => array(
          'type' => 'textarea',
          'label' => __('Text','merimag-backend'),
        ),
        'list_item_link' => array(
          'type' => 'wplink',
          'label' => __('Link','merimag-backend'),
        ),
        
      ),
      'template' => '{{- list_item_text }}',
        'title_field' => '{{{ list_item_text }}}',
    ),
  );
  return $options;
}
function merimag_get_icon_options() {
  $options = array(
    'icon' => array(
      'type' => 'icon-v2',
      'label' => __('Icon', 'merimag-backend'),
      'value' => array('value' => 'fa fa-plug')
    ),
    'icon_box_size' => array(
      'type' => 'select',
      'value' => '20',
      'label' => __('Icon box size', 'merimag-backend'),
      'choices' => array(
        '10' => __('1', 'merimag-backend'),
        '14' => __('2', 'merimag-backend'),
        '20' => __('3', 'merimag-backend'),
        '28' => __('4', 'merimag-backend'),
        '34' => __('5', 'merimag-backend'),
        '40' => __('6', 'merimag-backend'),
        '50' => __('7', 'merimag-backend'),
      ),
    ),
    'icon_box_layout' => array(
      'type' => 'select',
      'label' => __('Icon box layout', 'merimag-backend'),
      'choices' => array(
        'above' => __('Icon above content', 'merimag-backend'),
        'beside' => __('Icon beside content', 'merimag-backend'),
      ),
    ),
    'icon_box_style' => array(
      'type' => 'select',
      'label' => __('Icon Style', 'merimag-backend'),
      'choices' => array(
        'simple' => __('Simple', 'merimag-backend'),
        'bordered' => __('With border', 'merimag-backend'),
        'bordered with-radius' => __('With border and rounded corners', 'merimag-backend'),
        'bordered full-circle' => __('With border and circular', 'merimag-backend'),
        'background' => __('With background', 'merimag-backend'),
        'background with-radius' => __('With background and rounded corners', 'merimag-backend'),
        'background full-circle' => __('With background and circular', 'merimag-backend'),
      ),
    ),
    'icon_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Icon color', 'merimag-backend'),
    ),
    'icon_text_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Icon color ( for background style )', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_button_options( $multi_buttons = false ) {
  $options = array(
    'button_title' => array(
      'type'  => 'text',
      'value' => __('Click me', 'merimag-backend'),
      'label' => __('Button Title', 'merimag-backend'),
    ),
    'button_style' => array(
      'type'  => 'select',
      'value' => 'simple',
      'label' => __('Button Style', 'merimag-backend'),
      'choices' => merimag_get_recognized_button_styles(),
      'blank' => true, // (optional) if true, images can be deselected
    ),
    'button_size' => array(
      'type'   => 'select',
      'value' => 'normal',
      'label'  => esc_html__('Button size', 'merimag-backend'),
       'choices' => merimag_get_recognized_title_sizes(),
    ),
    'button_full' => array(
      'type' => 'switch',
      'label' => __('Full width', 'merimag-backend'),
      'value' => 'no',
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
    ),
    'button_rounded' => array(
      'type'   => 'select',
      'value' => 'no',
      'label'  => esc_html__('Rounded button', 'merimag-backend'),
      'choices' => array(
        'no' => __('No', 'merimag-backend'),
        'small' => __('Small', 'merimag-backend'),
        'medium' => __('Medium', 'merimag-backend'),
        'big' => __('Big', 'merimag-backend'),
      ),
    ),
    'button_color' => array(
      'type'   => 'color-picker-v2',
      'label'  => esc_html__('Button color', 'merimag-backend'),
      'value' => '#000000',
    ),
    'button_hover_color' => array(
      'type'   => 'color-picker-v2',
      'label'  => esc_html__('Button text color', 'merimag-backend'),
    ),
    'button_link' => array(
      'type' => 'wplink',
      'label' => __( 'Link', 'merimag-backend' ),
    ),
    'button_align' => array(
      'type' => 'select',
      'label' => __( 'Align', 'merimag-backend' ),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'start' => __('Start', 'merimag-backend'),
        'center' => __('Center', 'merimag-backend'),
        'end' => __('End', 'merimag-backend'),
      ),
    ),

  );
  if( $multi_buttons === true ) {
    unset( $options['button_size']);
    unset( $options['button_full']);
    unset( $options['button_rounded']);
    unset( $options['button_align'] );
  }
  return $options;
}
function merimag_seperator_option( $label = false ) {
  $options = array(
    merimag_uniqid('seperator') => array(
      'type' => 'html',
      'label' => $label,
      'elementor_type' => !empty( $label ) ? 'heading' : 'divider',
      'html' => !empty( $label ) ? sprintf('<h3>%s</h3>', $label ) : '<hr/>',
    ),
  );
  return $options;
}
function merimag_get_multi_buttons_options() {
  $options = array(

    'button_size' => array(
      'type'   => 'select',
      'value' => 'normal',
      'label'  => esc_html__('Buttons size', 'merimag-backend'),
       'choices' => merimag_get_recognized_title_sizes(),
    ),
    'button_rounded' => array(
      'type'   => 'select',
      'value' => 'no',
      'label'  => esc_html__('Rounded buttons', 'merimag-backend'),
      'choices' => array(
        'no' => __('No', 'merimag-backend'),
        'small' => __('Small', 'merimag-backend'),
        'medium' => __('Medium', 'merimag-backend'),
        'big' => __('Big', 'merimag-backend'),
      ),
    ),
    'button_full' => array(
      'type' => 'switch',
      'label' => __('Full width', 'merimag-backend'),
      'value' => 'no',
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
    ),
    'button_align' => array(
      'type' => 'select',
      'label' => __( 'Align', 'merimag-backend' ),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'start' => __('Start', 'merimag-backend'),
        'center' => __('Center', 'merimag-backend'),
        'end' => __('End', 'merimag-backend'),
      ),
    ),
    'buttons' => array(
      'type' => 'addable-popup',
      'label' => __('Elements', 'merimag-backend'),
      'popup-options' => merimag_get_button_options( true ),
      'template' => '{{- button_title }}',
      'title_field' => '{{{ button_title }}}',
    ),

  );
  return $options;
}
function merimag_get_review_options() {
  $options = array(
    'review_title' => array(
      'type'   => 'text',
      'label'  => __('Review title', 'merimag-backend'),
    ),
    'review_summary' => array(
      'type'   => 'textarea',
      'label'  => __('Review summary', 'merimag-backend'),
    ),
    'review_score_comment' => array(
      'type'   => 'textarea',
      'label'  => __('Review score comment', 'merimag-backend'),
    ),
    'review_style' => array(
      'type'   => 'select',
      'label'  => __('Review style', 'merimag-backend'),
      'value' => 'percent',
      'choices' => array(
        'points' => __('Points', 'merimag-backend'),
        'percent' => __('Percent', 'merimag-backend'),
        'stars' => __('Stars', 'merimag-backend'),
      ),
    ),
    'review_cretirias' => array(
      'type' => 'addable-box',
      'label' => __('Cretirias', 'merimag-backend'),
      'box-options' => array(
        'title' => array(
          'type' => 'text',
          'label' => __('Title', 'merimag-backend'),
        ),
        'note' => array(
          'type'  => 'slider',
          'label' => __('Note', 'merimag-backend'),
          'value' => 0,
          'properties' => array(
              'min' => 0,
              'max' => 10,
              'step' => 0.5,
          ),
        ),
      ),
      'template' => '{{- title }}',
    ),
  );
  return $options;
}
function merimag_get_mailchimp_options( $widget = false ) {
  if( function_exists('yikes_easy_mailchimp_extender_get_form_interface') ) {
    $form_interface = yikes_easy_mailchimp_extender_get_form_interface();
    $all_forms = $form_interface->get_all_forms();
    foreach( (array) $all_forms as $id => $form ) {
      $choices[ $id ] = isset( $form['form_name'] ) ? $form['form_name'] : sprintf(__('Form %s', 'merimag-backend'), $id );
    }
    if( isset( $choices ) && is_array( $choices ) ) {
      $options = array(
        'mailchimp_form' => array(
          'type' => 'select',
          'label' => __('Select form', 'merimag-backend'),
          'choices' => $choices,
        ),
        'title' => array(
          'type' => 'text',
          'label' => __('Block Title', 'merimag-backend'),
        ),
        'form_title' => array(
          'type' => 'text',
          'label' => __('Title', 'merimag-backend'),
          'value' => __('Get more stuff', 'merimag-backend'),
        ),
        'text' => array(
          'type' => 'textarea',
          'label' => __('Text', 'merimag-backend'),
          'value' => __('Subscribe to our mailing list and get interesting stuff and updates to your email inbox.', 'merimag-backend'),
        ),
        'show_newsletter_icon' => array(
          'type' => 'switch',
          'label' => __('Show newsletter icon', 'merimag-backend'),
          'value' => 'yes',
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
        ),
        'color' => array(
          'type' => 'color-picker-v2',
          'label' => __('Color', 'merimag-backend'),
        ),
        'icon_color' => array(
          'type' => 'color-picker-v2',
          'label' => __('Icon color', 'merimag-backend'),
          'condition' => array('show_newsletter_icon' => 'yes'),
        ),
        'title_color' => array(
          'type' => 'color-picker-v2',
          'label' => __('Title color', 'merimag-backend'),
        ),
      );
      if( $widget === false ) {
        $builder_options = array(
          'inline_form' => array(
            'type' => 'switch',
            'label' => __('Inline', 'merimag-backend'),
            'value' => 'no',
            'right-choice' => array(
              'value' => 'yes',
              'label' => __( 'Yes', 'merimag-backend' )
            ),
            'left-choice'  => array(
              'value' => 'no',
              'label' => __( 'No', 'merimag-backend' )
            ),
          ),
          'stacked_inputs' => array(
            'type' => 'switch',
            'label' => __('Stacked inputs', 'merimag-backend'),
            'value' => 'no',
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
      }
      $options = isset( $builder_options ) ? array_merge( $options, $builder_options ) : $options;
    } else {
      $add_form_link = admin_url('admin.php?page=yikes-inc-easy-mailchimp');
      $options = array(
        'mailchimp_form' => array(
          'type'  => 'html',
          'value' => false,
          'label' => __('Form', 'merimag-backend'),
          'html'  => sprintf('<p>Please make sure you have added signup forms <a target="_blank" href="%s">here</a>.<p/>', esc_url( $add_form_link ) ),
        ),
      );
    }
    
  } else {
    $install_plugins_page = admin_url('themes.php?page=tgmpa-install-plugins');
    $form_plugin_link = admin_url('plugin-install.php?tab=plugin-information&plugin=yikes-inc-easy-mailchimp-extender');
    $options = array(
      'mailchimp_form' => array(
        'type'  => 'html',
        'value' => false,
        'label' => __('Form', 'merimag-backend'),
        'html'  => sprintf('<p>Please visit <a href="%s" target="_balnk">install plugins page</a> to make sure you have installed and activated <a target="_blank" href="%s">Easy Forms for MailChimp</a>!</p>', esc_url( $install_plugins_page ), esc_url( $form_plugin_link ) ),
      ),
    );
  }
  return $options;
}
function merimag_get_heading_options() {
  $options = array(
      'title' => array(
        'type'   => 'text',
        'value'  => esc_html__('Element Title', 'merimag-backend'),
        'label'  => esc_html__('Title', 'merimag-backend'),
      ),
      'title_tag' => array(
        'type'    => 'select',
        'value'   => 'h2',
        'label'   => esc_html__('Title tag', 'merimag-backend'),
        'choices' => merimag_get_recognized_element_tags(),
      ),
      'centered_heading' => array(
        'label'        => __( 'Centered', 'merimag-backend' ),
        'type'         => 'switch',
        'value' => 'no',
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
      ),
      'title_color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Title color', 'merimag-backend'),
      ),
      'title_typography' => array(
        'type' => 'select',
        'choices' => merimag_get_selected_google_fonts(),
        'elementor_type' => 'typography-v3',
        'label' => __('Title typography', 'merimag-backend'),
        'selector' => '.merimag-heading-title'
      ),
      'title_size' => array(
        'type' => 'number',
        'label' => __('Size ( px )', 'merimag-backend'),
      ),
  );
  return $options;
}
function merimag_get_special_heading_options() {
  $options = array(
      'title' => array(
        'type'   => 'text',
        'value'  => esc_html__('Element Title', 'merimag-backend'),
        'label'  => esc_html__('Title', 'merimag-backend'),
      ),
      'block_title_style' => array(
        'type' => 'select',
        'choices' => merimag_get_recognized_block_title_styles(),
        'label' => __('Select style', 'merimag-backend'),
      ),
      'title_tag' => array(
        'type'    => 'select',
        'value'   => 'h2',
        'label'   => esc_html__('Title tag', 'merimag-backend'),
        'choices' => merimag_get_recognized_element_tags(),
      ),
      'title_color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Title color', 'merimag-backend'),
      ),
      'principal_color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Styling color', 'merimag-backend'),
      ),
      'title_typography' => array(
        'elementor_type' => 'typography-v3',
        'type' => 'select',
        'choices' => merimag_get_selected_google_fonts(),
        'label' => __('Title typography', 'merimag-backend'),
        'selector' => '.block-title'
      ),
      'title_size' => array(
        'type' => 'number',
        'label' => __('Title size', 'merimag-backend'),
      ),
  );
  return $options;
}
function merimag_get_divider_options() {
  $options = array(
    'divider_spacing_height' => array(
      'type' => 'number',
      'label' => __('Height', 'merimag-backend'),
      'value' => 30
    ),
    'divider_spacing_width' => array(
      'type'  => 'slider',
      'label' => __('Max width', 'merimag-backend'),
      'value' => 100,
      'properties' => array(
          'min' => 0,
          'max' => 100,
          'step' => 1,
      ),
    ),
    'centered_divider' => array(
      'label'        => __( 'Centered', 'merimag-backend' ),
      'type'         => 'switch',
      'value' => 'no',
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),
    'divider_border_size' => array(
      'type' => 'select',
      'value' => 1,
      'label' => __('Border size', 'merimag-backend'),
      'choices' => array_combine( range(1, 12),range(1, 12) ),
    ),
    'divider_border_style' => array(
      'type' => 'select',
      'label' => __('Style', 'merimag-backend'),
      'choices' => merimag_get_recognized_border_styles(),
    ),
    'divider_border_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Color', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_gallery_options() {
  $options = array(
    'gallery_items' => array(
      'type' => 'multi-upload',
      'label' => __('Gallery Images', 'merimag-backend'),
      'images_only' => true,
    ),
    'gallery_theme' => array(
      'type' => 'select',
      'label' => __('Gallery Theme', 'merimag-backend'),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'compact' => __('Compact', 'merimag-backend'),
        'slider' => __('Slider', 'merimag-backend'),
      ),
    ),
    'theme_panel_position' => array(
      'type' => 'select',
      'value' => 'bottom',
      'label' => __('Thumbs panel position', 'merimag-backend'),
      'choices' => array(
        'right' => __('Right', 'merimag-backend'),
        'bottom' => __('Bottom', 'merimag-backend'),
        'top' => __('Top', 'merimag-backend'),
        'left' => __('Left', 'merimag-backend'),
      ),
      'condition' => array('gallery_theme' => 'compact'),
    ),
    'gallery_width' => array(
      'type' => 'number',
      'label' => __('Gallery width', 'merimag-backend'),
    ),
    'gallery_height' => array(
      'type' => 'number',
      'label' => __('Gallery height', 'merimag-backend'),
    ),
    'gallery_autoplay' => array(
      'type' => 'switch',
      'label' => __('Auto play', 'merimag-backend'),
      'value' => 'no',
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),
    'gallery_play_interval' => array(
      'type' => 'number',
      'label' => __('Auto play interval ( ms )', 'merimag-backend'),
    ),
    'enable_textpanel' => array(
      'type' => 'switch',
      'label' => __('Show title and description', 'merimag-backend'),
      'value' => 'no',
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
    ),
    'textpanel_always_on' => array(
      'type' => 'switch',
      'label' => __('Always show title and description', 'merimag-backend'),
      'value' => 'no',
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
  return $options;
}
function merimag_get_gallery_tilesgrid_options() {
  $options = array(
    'gallery_items' => array(
      'type' => 'multi-upload',
      'label' => __('Images', 'merimag-backend'),
      'images_only' => true,
    ),
    'gallery_width' => array(
      'type' => 'number',
      'label' => __('Gallery width', 'merimag-backend'),
    ),
    'gallery_height' => array(
      'type' => 'number',
      'label' => __('Gallery height', 'merimag-backend'),
    ),
    'grid_num_rows' => array(
      'type' => 'select',
      'label' => __('Number of rows', 'merimag-backend'),
      'value' => 3,
      'choices' => array_combine( range(2, 10),range(2, 10) ),
    ),
    'tile_width' => array(
      'type' => 'number',
      'label' => __('Items width', 'merimag-backend'),
      'value' => 160,
    ),
    'tile_height' => array(
      'type' => 'number',
      'label' => __('Items height', 'merimag-backend'),
      'value' => 160,
    ),
    'grid_space_between_cols' => array(
      'type' => 'number',
      'label' => __('Space between cols', 'merimag-backend'),
      'value' => 20,
    ),
    'grid_space_between_rows' => array(
      'type' => 'number',
      'label' => __('Space between rows', 'merimag-backend'),
      'value' => 20,
    ),
    'enable_textpanel' => array(
      'type' => 'switch',
      'label' => __('Show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'textpanel_always_on' => array(
      'type' => 'switch',
      'label' => __('Always show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_border' => array(
      'type' => 'switch',
      'label' => __('Enable border', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_outline' => array(
      'type' => 'switch',
      'label' => __('Enable outline', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_shadow' => array(
      'type' => 'switch',
      'label' => __('Enable shadow', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
  );
  return $options;
}
function merimag_get_gallery_tiles_options() {
  $options = array(
    'gallery_items' => array(
      'type' => 'multi-upload',
      'label' => __('Images', 'merimag-backend'),
      'images_only' => true,
    ),
    'gallery_width' => array(
      'type' => 'number',
      'label' => __('Gallery width', 'merimag-backend'),
    ),
    'tiles_type' => array(
      'type' => 'select',
      'value' => 'justified',
      'label' => __('Tiles type', 'merimag-backend'),
      'choices' => array( 
        'justified' => __('Justified', 'merimag-backend'),
        'nested' => __('Nested', 'merimag-backend'),
        'columns' => __('Columns', 'merimag-backend')
      ),
    ),
    'tiles_space_between_cols' => array(
      'type' => 'number',
      'label' => __('Space between cols', 'merimag-backend'),
      'value' => 3,
    ),
    'tiles_min_columns' => array(
      'type' => 'number',
      'label' => __('Minimum columns', 'merimag-backend'),
      'condition' => array('tiles_type' => 'columns'),
    ),
    'tiles_max_columns' => array(
      'type' => 'number',
      'label' => __('Maximum columns', 'merimag-backend'),
      'condition' => array('tiles_type' => 'columns'),
    ),
    'tiles_justified_row_height' => array(
      'type' => 'number',
      'label' => __('Optimale row height ( for justified )', 'merimag-backend'),
      'condition' => array('tiles_type' => 'justified'),
    ),
    'tiles_nested_optimal_tile_width' => array(
      'type' => 'number',
      'label' => __('Optimale tile width ( for nested )', 'merimag-backend'),
      'condition' => array('tiles_type' => 'nested'),
    ),
    'enable_textpanel' => array(
      'type' => 'switch',
      'label' => __('Show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'textpanel_always_on' => array(
      'type' => 'switch',
      'label' => __('Always show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_border' => array(
      'type' => 'switch',
      'label' => __('Enable border', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_outline' => array(
      'type' => 'switch',
      'label' => __('Enable outline', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_shadow' => array(
      'type' => 'switch',
      'label' => __('Enable shadow', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
  );
  return $options;
}
function merimag_get_gallery_grid_options() {
  $options = array(
    'gallery_items' => array(
      'type' => 'multi-upload',
      'label' => __('Images', 'merimag-backend'),
      'images_only' => true,
    ),
    'gallery_width' => array(
      'type' => 'number',
      'label' => __('Gallery width', 'merimag-backend'),
    ),
    'gallery_height' => array(
      'type' => 'number',
      'label' => __('Gallery height', 'merimag-backend'),
    ),
    'grid_num_cols' => array(
      'type' => 'select',
      'label' => __('Number of columns', 'merimag-backend'),
      'value' => 2,
      'choices' => array_combine( range(2, 5),range(2, 5) ) ,
    ),
    'theme_panel_position' => array(
      'type' => 'select',
      'value' => 'right',
      'label' => __('Thumbs panel position', 'merimag-backend'),
      'choices' => array(
        'right' => __('Right', 'merimag-backend'),
        'bottom' => __('Bottom', 'merimag-backend'),
        'top' => __('Top', 'merimag-backend'),
        'left' => __('Left', 'merimag-backend'),
      ),
    ),
    'thumb_width' => array(
      'type' => 'number',
      'label' => __('Items width', 'merimag-backend'),
      'value' => 100,
    ),
    'thumb_height' => array(
      'type' => 'number',
      'label' => __('Items height', 'merimag-backend'),
      'value' => 75,
    ),
    'enable_textpanel' => array(
      'type' => 'switch',
      'label' => __('Show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'textpanel_always_on' => array(
      'type' => 'switch',
      'label' => __('Always show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_border' => array(
      'type' => 'switch',
      'label' => __('Enable border', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_outline' => array(
      'type' => 'switch',
      'label' => __('Enable outline', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_shadow' => array(
      'type' => 'switch',
      'label' => __('Enable shadow', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
  );
  return $options;
}
function merimag_get_gallery_carousel_options() {
  $options = array(
    'gallery_items' => array(
      'type' => 'multi-upload',
      'label' => __('Images', 'merimag-backend'),
      'images_only' => true,
    ),
    'gallery_width' => array(
      'type' => 'number',
      'label' => __('Gallery width', 'merimag-backend'),
    ),
    'gallery_height' => array(
      'type' => 'number',
      'label' => __('Gallery height', 'merimag-backend'),
    ),
    'tile_width' => array(
      'type' => 'number',
      'label' => __('Items width', 'merimag-backend'),
      'value' => 160,
    ),
    'tile_height' => array(
      'type' => 'number',
      'label' => __('Items height', 'merimag-backend'),
      'value' => 160,
    ),
    'carousel_space_between_tiles' => array(
      'type' => 'number',
      'label' => __('Space between items', 'merimag-backend'),
      'value' => 20,
    ),
    'enable_textpanel' => array(
      'type' => 'switch',
      'label' => __('Show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'textpanel_always_on' => array(
      'type' => 'switch',
      'label' => __('Always show title and description', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_border' => array(
      'type' => 'switch',
      'label' => __('Enable border', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_outline' => array(
      'type' => 'switch',
      'label' => __('Enable outline', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
    'tile_enable_shadow' => array(
      'type' => 'switch',
      'label' => __('Enable shadow', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
  );
  return $options;
}
function merimag_get_spacing_options() {
  $options = array(
    'spacing_height' => array(
      'type' => 'number',
      'label' => __('Height', 'merimag-backend'),
      'value' => 40
    ),
  );
  return $options;
}
function merimag_get_paragraph_padding_options() {
  $options = array(
    'padding_content' => array(
      'type' => 'wp-editor',
      'editor_type' => 'tinymce',
      'wpautop' => false,
      'label' => false,
      'shortcodes' => true,
    ),
  );
  return $options;
}
function merimag_get_action_options( $title_box = false, $slug = false ) {
    if( $title_box === 'image_box' ) {
        $general_title_box_options = array(
          'image' => array(
              'type'  => 'upload',
              'label' => __('Image', 'merimag-backend'),
              'only_images' => true,
          ),
          'link' => array(
            'type'         => 'wplink',
            'label' => __( 'Link', 'merimag-backend' ),
          ),
          'image_height' => array(
            'type'         => 'number',
            'label' => __( 'Image height', 'merimag-backend' ),
          ),
          'image_size' => array(
              'type'   => 'select',
              'label'  => esc_html__('Image dimensions', 'merimag-backend'),
              'value' => 'default',
              'choices' => merimag_get_recognized_image_sizes(),
          ),
          'image_ratio' => array(
            'type'   => 'select',
            'label'  => esc_html__('Image aspect ratio', 'merimag-backend'),
            'value' => '16-9',
            'choices' => array(
              '1-2' => '1:2',
              '16-9' => '16:9',
              '2-3' => '2:3',
              '4-3' => '4:3',
              '1-1' => '1:1',
              '2-1' => '2:1',
            ),
          ),
          'grid_style' => array(
              'type'  => 'image-picker',
              'label' => esc_html__('Grid Style', 'merimag-backend'),
              'value' => merimag_get_default_grid_data( 'grid_style'),
              'choices' => merimag_get_recognized_grid_styles(),
              'blank' => true,
          ),
          
      );
        $options['general'] = array(
          'type' => 'tab',
          'title' => __('General', 'merimag-backend'),
          'options' => array_merge( $general_title_box_options, merimag_get_title_box_options( ) ),
        );
    }
   if( $title_box === true ) {
    $general_title_box_options = array(
        'image' => array(
            'type'  => 'upload',
            'label' => __('Image', 'merimag-backend'),
            'only_images' => true,
        ),
        'link' => array(
          'type'         => 'wplink',
          'label' => __( 'Link', 'merimag-backend' ),
        ),

        'slabtext' => array(
          'type'  => 'switch',
          'label' => __( 'Justify content', 'merimag-backend' ),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
        ),
        'custom_title_box' => array(
          'type'  => 'switch',
          'label' => __( 'Custom title box', 'merimag-backend' ),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
        )
    );
    $options['general'] = array(
      'type' => 'tab',
      'title' => __('General', 'merimag-backend'),
      'options' => array_merge( $general_title_box_options, merimag_get_title_box_options( 'custom_grid' ) ),
    );
  }
  $options['content'] = array(
    'type' => 'tab',
    'title' => __('Content', 'merimag-backend'),
    'options' => array(
      
      'max_width' => array(
          'type' => 'number',
          'label' => __('Max width ( % )', 'merimag-backend'),
        ),
      'title' => array(
        'type'   => 'text',
        'value'  => $title_box === false ? esc_html__('Element Title', 'merimag-backend') : '',
        'label'  => esc_html__('Title', 'merimag-backend'),
      ),
      'sub_title' => array(
        'type'   => 'text',
        'value'  => $title_box === false ? esc_html__('This is a nice sub title here', 'merimag-backend') : '',
        'label'  => esc_html__('Sub Title', 'merimag-backend'),
      ),
      'before_title' => array(
        'type'   => 'text',
        'value'  => $title_box === false ? esc_html__('Small text', 'merimag-backend') : '',
        'label'  => esc_html__('Before Title', 'merimag-backend'),
      ),
      'description' => array(
        'type'   => 'textarea',
        'label'  => esc_html__('Description', 'merimag-backend'),
      ),
      'center_content' => array(
        'type'  => 'switch',
         'label' => __( 'Center content', 'merimag-backend' ),
         'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
         ),
      ),

      'title_tag' => array(
        'type'    => 'select',
        'value'   => 'h2',
        'label'   => esc_html__('Title tag', 'merimag-backend'),
        'choices' => merimag_get_recognized_element_tags(),
      ),
      'sub_title_tag' => array(
        'type'    => 'select',
        'value'   => 'h3',
        'label'   => esc_html__('Sub Title tag', 'merimag-backend'),
        'choices' => merimag_get_recognized_element_tags(),
      ),
      'before_title_tag' => array(
        'type'    => 'select',
        'value'   => 'h4',
        'label'   => esc_html__('Before Title tag', 'merimag-backend'),
        'choices' => merimag_get_recognized_element_tags(),
      ),
      'description_tag' => array(
        'type'    => 'select',
        'value'   => 'p',
        'label'   => esc_html__('Description tag', 'merimag-backend'),
        'choices' => merimag_get_recognized_element_tags(),
      ),
    )
  );
  if( $title_box === false || $title_box === 'image_box' ) {
    $options['content']['options']['slabtext'] = array(
      'type'  => 'switch',
      'label' => __( 'Justify content', 'merimag-backend' ),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'   => 'no',
    );
    $options['buttons_tab'] = array(
      'type' => 'tab',
      'title' => __('Buttons', 'merimag-backend'),
      'options' => merimag_get_button_options(),
    );
  }
  $options['styling_tab'] = array(
    'type' => 'tab',
    'title' => __('Styling', 'merimag-backend'),
    'options' => array(
      'ignore_general_style' => array(
        'label'        => __( 'Ignore general style', 'merimag-backend' ),
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
        'desc'         => __( 'Select yes you want to not use the general style applied from customizer', 'merimag-backend' ),
      ),
      'size' => array(
        'type'   => 'number',
        'label'  => esc_html__('Size ( px )', 'merimag-backend'),
      ),
      'color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Color', 'merimag-backend'),
      ),
      'title_color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Title color', 'merimag-backend'),
      ),
      'sub_title_color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Sub Title color', 'merimag-backend'),
      ),
      'before_title_color' => array(
        'type'   => 'color-picker-v2',
        'label'  => esc_html__('Before Title color', 'merimag-backend'),
      ),
      
    )
  );
  $typography_options = array(
    'action_title_typography' => array(
      'type'  => 'number',
      'label' => __( 'Title typography', 'merimag-backend' ),
      'type' => 'select',
      'choices' => merimag_get_selected_google_fonts(),
      'elementor_type' => 'typography-v3',
      'selector' => ' .merimag-call-to-action-title'
    ),
    'action_sub_title_typography' => array(
      'type'  => 'number',
      'label' => __( 'Sub Title typography', 'merimag-backend' ),
      'type' => 'select',
      'choices' => merimag_get_selected_google_fonts(),
      'elementor_type' => 'typography-v3',
      'selector' => ' .merimag-call-to-action-sub-title'
    ),
    'action_before_title_typography' => array(
      'type'  => 'number',
      'label' => __( 'Sub Title typography', 'merimag-backend' ),
      'type' => 'select',
      'choices' => merimag_get_selected_google_fonts(),
      'type' => 'typography-v3',
      'selector' => ' .merimag-call-to-action-before-title'
    ),
  );
  $options['styling_tab']['options'] = array_merge( $typography_options, $options['styling_tab']['options'] );
  if( $title_box === true ) {
    $options['button_tab'] = array(
      'type' => 'tab',
      'title' => __('Button', 'merimag-backend'),
      'options' => merimag_get_button_options(),
    );
    $options['custom_animation_tab'] = array(
      'type' => 'tab',
      'title' => __('Animating', 'merimag-backend'),
      'options' => merimag_get_animation_options('custom'),
    );
  }
  return $options;
}
function merimag_get_custom_content_options( $content_type = false ) {

  $options = array(
    'elements' => array(
      'type' => 'addable-popup',
      'label' => __('Elements', 'merimag-backend'),
      'popup-options' => merimag_get_action_options( true ),
      'template' => '{{- title }}',
      'title_field' => '{{{ title }}}',
    ),
  );

  return $options;
}
function merimag_get_image_box_options() {

  $options = array(

    'animation' => array(
        'type' => 'tab',
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  return array_merge( merimag_get_action_options('image_box'), $options );
}
function merimag_get_contact_infos_options() {
  $options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'layout' => array(
      'type' => 'select',
      'label' => __('Layout', 'merimag-backend'),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'inline' => __('Inline', 'merimag-backend'),
      ),
    ),
    'vertical_align' => array(
      'type' => 'select',
      'label' => __('Vertical align', 'merimag-backend'),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'start' => __('Start', 'merimag-backend'),
      ),
    ),
    'icons_style' => array(
      'type' => 'select',
      'label' => __('Icons style', 'merimag-backend'),
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'stacked' => __('Stacked', 'merimag-backend'),
      ),
    ),
    'elements' => array(
      'type' => 'addable-box',
      'label' => __('Elements', 'merimag-backend'),
      'value' => merimag_get_db_customizer_option('contact_infos'),
      'box-options' => merimag_contact_item_options(),
      'template' => '{{- title }}',
      'title_field' => '{{{ text }}}',
    ),
    'font_size' => array(
      'type' => 'number',
      'label' => __('Font size', 'merimag-backend'),
    ),
    'color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Color', 'merimag-backend'),
    ),
    'icons_color' => array(
      'type' => 'color-picker-v2',
      'label' => __('Icons color', 'merimag-backend'),
    ),
  );

  return $options;
}
function merimag_get_wp_menu_options() {
  $menus = get_terms('nav_menu');
  foreach($menus as $menu) {
    $menus[$menu->slug] = $menu->name;
  } 
  $options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'layout' => array(
      'type' => 'select',
      'label' => __('Layout', 'merimag-backend'),
      'choices' => array(
        'simple' => __('Simple', 'merimag-backend'),
        'horizontal' => __('Collapsed horizontal menu', 'merimag-backend'),
        'vertical' => __('Collapsed vertical menu', 'merimag-backend'),
      ),
    ),
    'columns' => array(
      'type' => 'select',
      'label' => __('Columns ( for simple menu )', 'merimag-backend'),
      'choices' => array(
        'flex' => 'flex',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
      ),
    ),
    'menu' => array(
      'type' => 'select',
      'label' => __('Select menu', 'merimag-backend'),
      'choices' => $menus,
    ),
  );

  return $options;
}
function merimag_contact_item_options() {
  return array(
    'type' => array(
      'type' => 'select',
      'label' => __('Type', 'merimag-backend'),
      'choices' =>  merimag_get_recognized_contact_types(),
    ),
    'text' => array(
      'type' => 'text',
      'label' => __('Content', 'merimag-backend'),
    ),
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'link' => array(
      'type' => 'text',
      'label' => __('Link', 'merimag-backend'),
    ),
  );
}
function merimag_get_title_box_options( $content_type = false, $condition = false ) {
  $slug = '';
  $options = array(
       $slug . 'title_box_style' => array(
         'type'  => 'image-picker',
          'value' => 'simple',
          'label' => __('Title box Style', 'merimag-backend'),
          'choices' => merimag_get_recognized_title_box_styles(),
          'blank' => true, // (optional) if true, images can be deselected
          'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array('grid_style' => 'absolute'),
      ),
      $slug . 'title_box_background' => array(
          'type'  => 'gradient-v2',
          'label' => __('Title box background', 'merimag-backend'),
          'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array(),
          'selector' => '.merimag-block-infos-content, .merimag-block-infos.marged-infos',
          'bg_types' => array('gradient'),
      ),
      $slug . 'infos_width' => array(
        'type'  => 'slider',
        'label'  => esc_html__('Title box width', 'merimag-backend'),
        'value' => 100,
        'properties' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ),
        'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array(),
      ),
      $slug . 'full_height_infos' => array(
          'type'  => 'switch',
          'label' => __( 'Full height title box', 'merimag-backend' ),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
          'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array('grid_style' => 'absolute'),
      ),
      $slug . 'centered_infos' => array(
          'type'  => 'switch',
          'label' => __( 'Center title box content', 'merimag-backend' ),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
          'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array(),
      ),
      $slug . 'infos_position' => array(
          'type'   => 'select',
          'value' => 'left-bottom',
          'label'  => esc_html__('Title box position', 'merimag-backend'),
          'choices' => merimag_get_recognized_infos_positions(),
          'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array('grid_style' => 'absolute'),
      ),
  );
  if( $content_type === 'posts_slider' ) {
    $options[$slug . 'title_size'] = array(
        'type'   => 'select',
        'value' => merimag_get_default_grid_data( 'title_size'),
        'label'  => esc_html__('Title size', 'merimag-backend'),
        'choices' => merimag_get_recognized_title_sizes(),
        'condition' => isset( $condition ) && !empty( $condition ) ? array($condition => 'yes') : array(),
    );
  }
  
  return $options;
}
function merimag_carousel_settings( $slug = '', $custom = false ) {
    $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options_slug = $custom === true ? '' : $slug;
    $options = merimag_grid_settings( $slug, $custom, 'post', true );
    return $custom === false ? $options : $options_custom;
}
function merimag_taxonomy_carousel_settings( $slug = '', $custom = false ) {
    $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options_slug = $custom === true ? '' : $slug;
    $options = merimag_grid_settings( $slug, $custom, 'taxonomy', true );
    return $custom === false ? $options : $options_custom;
}
function merimag_product_carousel_settings( $slug = '', $custom = false ) {
    $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options_slug = $custom === true ? '' : $slug;
    $options = merimag_grid_settings( $slug, $custom, 'product', true );
    return $custom === false ? $options : $options_custom;
}
function merimag_product_grid_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'product' );
  return $options;
}
function merimag_custom_grid_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'custom' );
  return $options;
}
function merimag_custom_list_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'custom', true );
  return $options;
}
function merimag_products_list_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'product', true );
  return $options;
}
function merimag_posts_grid_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'post' );
  return $options;
}
function merimag_taxonomy_grid_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'taxonomy', 'general' );
  return $options;
}
function merimag_posts_list_settings( $slug = '', $custom = false ) {
  $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
  $options_slug = $custom === true ? '' : $slug;
  $options = merimag_grid_settings( $slug, $custom, 'post', true );
  return $options;
}
function merimag_grid_settings( $slug = '', $custom = false, $post_type = 'post', $list = false ) {
    $slug         = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options      = array();
    $options_slug = $custom === true ? '' : $slug;
    if( $list === false || $list === 'general' || $list === 'widget' ) {
      $options['columns' . $options_slug ] = array(
        'type'  => 'radio',
        'label' => esc_html__('Number of columns', 'merimag-backend'),
        'choices' => merimag_get_recognized_grid_columns( false, $list ),
        'desc' => esc_html__('Number of columns can be 2, 3, 4 or 5 columns', 'merimag-backend'),
        'inline' => true,
      );
    }
    $options['grid_style' . $options_slug ] = array(
        'type'  => 'image-picker',
        'label' => esc_html__('Block Style', 'merimag-backend'),
        'value' => 'simple',
        'choices' => $post_type === 'custom' || $post_type === 'taxonomy'  ? merimag_get_recognized_custom_grid_styles() : merimag_get_recognized_grid_styles(),
        'blank' => true,
        'desc' => esc_html__('Change the look of items, there is more than 5 styles', 'merimag-backend'),
    );
    $options['image_ratio' . $options_slug ] = array(
        'type'   => 'select',
        'label'  => esc_html__('Image aspect ratio', 'merimag-backend'),
        'value' => '16-9',
        'desc' => esc_html__('For a responsive images height use this option to keep images ratio in all screens', 'merimag-backend'),
        'choices' => array(
          '1-2' => '1:2',
          '16-9' => '16:9',
          '2-3' => '2:3',
          '4-3' => '4:3',
          '1-1' => '1:1',
          '2-1' => '2:1',
        ),
    );
    $options['image_height' . $options_slug ] = array(
        'type'   => 'number',
        'label'  => esc_html__('Image height', 'merimag-backend'),
        'desc' => esc_html__('For fixed height image add a desired height of image to this box', 'merimag-backend'),
    );
    $options['image_size' . $options_slug ] = array(
        'type'   => 'select',
        'label'  => esc_html__('Image dimensions', 'merimag-backend'),
        'value' => 'default',
        'desc' => esc_html__('For best appearance select the desired image size to keep the images appear in the best resolution', 'merimag-backend'),
        'choices' => merimag_get_recognized_image_sizes(),
    );
    $options['title_size' . $options_slug ] = array(
        'type'   => 'select',
        'value' => merimag_get_default_grid_data( 'title_size'),
        'label'  => esc_html__('Title size', 'merimag-backend'),
        'desc' => esc_html__('Control the post/product title size', 'merimag-backend'),
        'choices' => merimag_get_recognized_title_sizes(),
    );
    $options['title_size_elementor' . $options_slug ] = array(
        'type'   => 'hidden',
        'elementor_type'   => 'typography-v3',
        'value' => '',
        'label'  => esc_html__('Title typography', 'merimag-backend'),
        'desc' => esc_html__('Control the post/product title size and appearance', 'merimag-backend'),
        'selector' => ' .block-infos-title-wrapper:not(.force-size) .title-display'
    );
    $options['show_description' . $options_slug ] = array(
        'type'  => 'switch',
        'label' => __( 'Show description', 'merimag-backend' ),
         'desc' => esc_html__('Show the post/product description', 'merimag-backend'),
        'value' => merimag_get_default_grid_data( 'show_description'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['separator' . $options_slug ] = array(
        'type'  => 'switch',
        'label' => __( 'Border separator', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'separator'),
        'desc' => esc_html__('Show separator between elements', 'merimag-backend'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['border_block' . $options_slug ] = array(
        'type'  => 'switch',
        'label' => __( 'Bordered block', 'merimag-backend' ),
        'desc' => esc_html__('Make elements with a border for a different looking', 'merimag-backend'),
        'value' => merimag_get_default_grid_data( 'separator'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    if( $post_type === 'post' ) {
      $options['show_number' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show order number', 'merimag-backend' ),
          'desc' => esc_html__('Show order number nicely', 'merimag-backend'),
          'value' => merimag_get_default_grid_data( 'show_number'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
      );
      $options['show_read_more' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show read more', 'merimag-backend' ),
          'desc' => esc_html__('Show read more button bellow the elements', 'merimag-backend'),
          'value' => merimag_get_default_grid_data( 'show_read_more'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
      );
      $options['show_category' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show category', 'merimag-backend' ),
          'value' => merimag_get_default_grid_data( 'show_category'),
          'desc' => esc_html__('Show post category', 'merimag-backend'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'yes',
      );
      $options['show_format_icon' . $options_slug ] = array(
          'type'  => 'switch',
          'desc' => esc_html__('Show format icon for video posts', 'merimag-backend'),
          'label' => __( 'Show post format icon', 'merimag-backend' ),
          'value' => merimag_get_default_grid_data( 'show_format_icon'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
      );
      $options['show_review' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show review score', 'merimag-backend' ),
          'desc' => esc_html__('Show review score nicely for reviews posts', 'merimag-backend'),
          'value' => merimag_get_default_grid_data( 'show_review'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'yes',
      );
      $options['review_display_style' . $options_slug ] = array(
          'type'  => 'select',
          'label' => __( 'Review display style', 'merimag-backend' ),
          'desc' => esc_html__('Select the display style for review scores', 'merimag-backend'),
          'value' => 'default',
          'choices' => array(
            'default' => sprintf('-- %s --', __('Default', 'merimag-backend')),
            'stars' => __('Stars', 'merimag-backend'),
            'circle' => __('Circle', 'merimag-backend'),
            'simple' => __('Simple', 'merimag-backend'),
            'bar'   => __('Bar', 'merimag-backend'),
          ),
      );
    }
    if( $post_type === 'product' ) {
      $options['show_sale' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show on sale indicator', 'merimag-backend' ),
          'desc' => esc_html__('Show on sale indicator above product image', 'merimag-backend'),
          'value' => merimag_get_default_grid_data_shop( 'show_sale'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
      );
      $options['show_add_to_cart' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show add to cart', 'merimag-backend' ),
          'desc' => esc_html__('Show add to cart button bellow products items', 'merimag-backend'),
          'value' => merimag_get_default_grid_data_shop( 'show_add_to_cart'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
      );
    }
    if( in_array($post_type, array('post', 'product') ) ) {
      $options['after_title' . $options_slug ] = array(
          'type'   => 'select',
          'value' => $post_type === 'product' ? merimag_get_default_grid_data_shop('after_title') : merimag_get_default_grid_data( 'after_title'),
          'label'  => esc_html__('Meta infos', 'merimag-backend'),
          'desc' => esc_html__('Control meta infos to display like date - author - comments ...', 'merimag-backend'),
          'choices' => $post_type === 'product' ? merimag_get_recognized_after_title( false, 'shop') :merimag_get_recognized_after_title(),
      );
    }
    if( $post_type === 'taxonomy' ) {
      $options['show_count' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Show count', 'merimag-backend' ),
          'desc' => esc_html__('Show taxonomy total number of posts', 'merimag-backend'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'yes',
      );
    }
    $options['spacing' . $options_slug ] = array(
        'type'   => 'select',
        'desc' => esc_html__('Control the spacing between the elements', 'merimag-backend'),
        'value' => merimag_get_default_grid_data( 'spacing'),
        'label'  => esc_html__('Spacing', 'merimag-backend'),
        'choices' => merimag_get_recognized_grid_spacing(),
    );
    
    if( $post_type === 'post' && $list !== true ) {
      $options['masonry' . $options_slug ] = array(
          'type'  => 'switch',
          'label' => __( 'Masonry layout?', 'merimag-backend' ),
          'desc' => esc_html__('Display elements in masonry layout easily just by checking this box, and also you have to set a fixed image height to be the minimum image heights for masonry layout', 'merimag-backend'),
          'value' => merimag_get_default_grid_data( 'masonry'),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'   => 'no',
      );
    }
    $options['title_length' . $options_slug] = array(
        'type'   => 'number',
        'label'  => esc_html__('Title length', 'merimag-backend'),
        'desc' => esc_html__('Title number of characters', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 500,
            'step' => 1,
        ),
    );
    $options['title_ellipsis' . $options_slug] = array(
        'type'   => 'number',
        'label'  => esc_html__('Title lines', 'merimag-backend'),
        'desc' => esc_html__('Title maximum number of lines', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 20,
            'step' => 1,
        ),
    );
    $options['description_length' . $options_slug] = array(
        'type'   => 'number',
        'label'  => esc_html__('Desciption length', 'merimag-backend'),
        'desc' => esc_html__('Desciption number of characters', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 500,
            'step' => 1,
        ),
    );
    $options['description_ellipsis' . $options_slug] = array(
        'type'   => 'number',
        'label'  => esc_html__('Desciption lines', 'merimag-backend'),
        'desc' => esc_html__('Desciption maximum number of lines', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 20,
            'step' => 1,
        ),
    );
    if( in_array($post_type, array('post', 'product') ) ) {
      $options['pagination' . $options_slug ] = array(
          'type'         => 'select',
          'label'        => esc_html__('Pagination', 'merimag-backend'),
          'value'        => merimag_get_default_grid_data( 'pagination'),
          'desc' => esc_html__('Select Pagination type', 'merimag-backend'),
          'choices' => merimag_recognized_pagination_options( true ),
          'show_borders' => false,
      );
    }
    $options['color_layer'] = array(
      'type'  => 'switch',
      'label' => __('Colorful image layer', 'merimag-backend'),
      'desc' => esc_html__('Add colorful layour above elements images to add a nice looking elements', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    );
    $options_custom[ 'listing' . $slug ] = array(
      'type'         => 'multi-picker',
      'label'        => false,
      'desc'         => false,
      'value'        => merimag_get_default_grid_data( 'listing'),
      'picker'       => array(
        'custom_listing' => array(
          'type'  => 'switch',
          'label' => __( 'Use custom layout', 'merimag-backend' ),
          'right-choice' => array(
            'value' => 'yes',
            'label' => __( 'Yes', 'merimag-backend' )
          ),
          'left-choice'  => array(
            'value' => 'no',
            'label' => __( 'No', 'merimag-backend' )
          ),
          'value'        => 'no',
        )
      ),
      'choices' => array(
        'yes'  => $options,
      ),
      'show_borders' => false,
    );
    return $custom === false ? $options : $options_custom;
}
function merimag_list_settings_for_widget( $slug = '', $custom = false ) {
    $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options = array();
    $options_slug = $custom === true ? '' : $slug;
    $options = merimag_grid_settings( $slug, $custom, 'post', true );
    $options['grid_style' . $options_slug ] = array(
        'type'  => 'image-picker',
        'label' => esc_html__('Block Style', 'merimag-backend'),
        'value' => merimag_get_default_grid_data( 'grid_style'),
        'choices' => merimag_get_recognized_list_styles_for_widget(),
        'blank' => true,
    );
    return $options;
}
function merimag_grid_settings_for_widget( $slug = '', $custom = false ) {
    $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options = array();
    $options_slug = $custom === true ? '' : $slug;
    $options = merimag_grid_settings( $slug, $custom, 'post', 'widget' );
    $options['grid_style' . $options_slug ] = array(
        'type'  => 'image-picker',
        'label' => esc_html__('Block Style', 'merimag-backend'),
        'value' => merimag_get_default_grid_data( 'grid_style'),
        'choices' => merimag_get_recognized_grid_styles_for_widget(),
        'blank' => true,
    );
    return $options;
}
function merimag_product_grid_settings_for_widget( $slug = '', $custom = false ) {
    $slug = !empty( $slug ) && is_string( $slug ) ? '_' . $slug : '';
    $options = array();
    $options_slug = $custom === true ? '' : $slug;
    $options = merimag_grid_settings( $slug, $custom, 'product', true );
    $options['grid_style' . $options_slug ] = array(
        'type'  => 'image-picker',
        'label' => esc_html__('Block Style', 'merimag-backend'),
        'value' => merimag_get_default_grid_data( 'grid_style'),
        'choices' => merimag_get_recognized_grid_styles_for_widget(),
        'blank' => true,
    );
    return $options;
}



function merimag_get_posts_block_options() {
	$options = array();
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);

	$general_options['block_style'] = array(
	    'type'  => 'image-picker',
	    'label' => __('Block Style', 'merimag-backend'),
	    'choices' => merimag_get_recognized_blocks('posts'),
	    'blank' => true, // (optional) if true, images can be deselected
	);
  $general_options['color_layer'] = array(
      'type'  => 'switch',
      'label' => __('Colorful image layer', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
  );
  $general_options['title_box_background'] = array(
      'type'  => 'gradient-v2',
      'label' => __('Title box background', 'merimag-backend'),
      'selector' => '.merimag-block-infos-content, .merimag-block-infos.marged-infos',
      'bg_types' => array('gradient'),
  );
	$pagination_options = merimag_get_pagination_options();
	$general_options = array_merge( $general_options, $pagination_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
		'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options('post-mix'),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	
	return $options;
}
function merimag_get_social_networks_options_for_widget() {
  $options = array();
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  
  $social_options = merimag_get_social_icons_options();
  $general_options = array_merge( $general_options, $social_options );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
  );
  
  return $options;
}
function merimag_get_contact_infos_options_for_widget() {
  $options = array();
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  
  $social_options = merimag_get_contact_infos_options();
  $general_options = array_merge( $general_options, $social_options );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
  );
  
  return $options;
}
function merimag_get_mailchimp_options_for_widget() {
  $options = array();
  $mailchimp_options = merimag_get_mailchimp_options( true );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $mailchimp_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General settings', 'merimag-backend'),
        'label' => __('General', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_posts_block_options_for_widget() {
  $options = array();
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $general_options['block_style'] = array(
      'type'  => 'image-picker',
      'label' => __('Block Style', 'merimag-backend'),
      'choices' => merimag_get_recognized_blocks('posts_widget'),
      'blank' => true, // (optional) if true, images can be deselected
  );
  $general_options['show_number'] = array(
      'type'  => 'switch',
      'label' => __( 'Show order number', 'merimag-backend' ),
      'value' => merimag_get_default_grid_data( 'show_number'),
      'right-choice' => array(
        'value' => 'yes',
        'label' => __( 'Yes', 'merimag-backend' )
      ),
      'left-choice'  => array(
        'value' => 'no',
        'label' => __( 'No', 'merimag-backend' )
      ),
      'value'   => 'no',
  );
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'popup',
        'popup-options' => merimag_get_query_options('post-mix'),
        'popup-title' => __('Posts configuration', 'merimag-backend'),
        'button' => __('Posts configuration', 'merimag-backend'),
        'label' => __('Posts configuration settings', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_popular_categories_options( $widget = false ) {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $general_options['taxonomy'] = array(
    'type'  => 'select',
    'value' => '',
    'label' => __('Taxonomy', 'merimag-backend'),
    'choices' => array(
      'category' => __('Category', 'merimag-backend'),
      'product_cat' => __('Product category', 'merimag-backend'),
    ),
  );
  $general_options['sub_categories'] = array(
    'type'  => 'select',
    'value' => '',
    'label' => __('Show sub categories', 'merimag-backend'),
    'choices' => array(
      'default' => sprintf('-- %s --', __('Default', 'merimag-backend')),
      'yes' => __('Yes', 'merimag-backend'),
      'no' => __('No', 'merimag-backend'),
    ),
  );
  if( $widget === false ) {
    return $general_options;
  }
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_popular_categories_options() {
  return merimag_get_popular_categories_options( true );
}
function merimag_get_custom_tiled_options() {
	$options = array();
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$general_options['block_style'] = array(
	    'type'  => 'image-picker',
	    'label' => __('Block Style', 'merimag-backend'),
	    'choices' => merimag_get_recognized_blocks('custom_tiled'),
	    'blank' => true, // (optional) if true, images can be deselected
	);
  $general_options['grid_style'] = array(
     'type'  => 'hidden',
     'value' => 'absolute',
  );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
		'title_box' => array(
        	'type' => 'tab',
        	'options' => merimag_get_title_box_options( 'custom_tiled' ),
        	'title' => __('Title box', 'merimag-backend'),
        ),
		'content' => array(
		    'type' => 'tab',
		    'options' => merimag_get_custom_content_options( 'custom_tiled' ),
		    'title' => __('Content', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_pagination_options( $slug = '' ) {
	$slug = $slug ? 'pagination_' . $slug : 'pagination';
	$pagination_options[$slug ] = array(
      'type'         => 'select',
      'label'        => esc_html__('Pagination', 'merimag-backend'),
      'desc'         => false,
      'choices'       => merimag_recognized_pagination_options( false ),
    );
    return $pagination_options;
}


function merimag_get_posts_grid_options( $post_type = 'post', $box_type = 'tab', $excludes = array()) {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $box_type = !in_array($box_type, array('tab', 'box', 'popup')) ? 'tab' : $box_type;
  $grid_options = $post_type === 'post' ? merimag_posts_grid_settings() : merimag_product_grid_settings();
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $grid_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => $box_type,
        'popup-title' => __('General', 'merimag-backend'),
        'options' => $general_options,
        'title' => __('General', 'merimag-backend'),
    ),
    'title_box' => array(
        'type' => $box_type,
        'popup-title' => __('Title box', 'merimag-backend'),
        'options' => merimag_get_title_box_options(),
        'title' => __('Title box', 'merimag-backend'),
    ),
    'query' => array(
        'type' => $box_type,
        'options' => merimag_get_query_options( $post_type ),
        'popup-title' => __('Posts configuration', 'merimag-backend'),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
    'style' => array(
        'type' => $box_type,
        'popup-title' => __('Styling', 'merimag-backend'),
        'options' => merimag_get_block_style_options(),
        'title' => __('Styling', 'merimag-backend'),
    ),
    'animation' => array(
        'type' => $box_type,
        'popup-title' => __('Animating', 'merimag-backend'),
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  foreach( $excludes as $exclude ) {
    if( isset( $options[$exclude] ) ) {
      unset( $options[$exclude]);
    }
  }
  return $options;
}
function merimag_get_category_grid_options() {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = merimag_taxonomy_grid_settings();
  $general_options = array_merge( $general_options, $grid_options );
  $options = array(
    'general' => array(
        'type' => 'tab',
        'options' => $general_options,
        'title' => __('General', 'merimag-backend'),
    ),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title box', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'tab',
        'options' => merimag_get_query_options_for_category(),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
    'style' => array(
        'type' => 'tab',
        'options' => merimag_get_block_style_options(),
        'title' => __('Styling', 'merimag-backend'),
    ),
    'animation' => array(
        'type' => 'tab',
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_category_carousel_options() {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = merimag_taxonomy_carousel_settings();
  $general_options = array_merge( $general_options, $grid_options );
  $options = array(
    'general' => array(
        'type' => 'tab',
        'options' => $general_options,
        'title' => __('General', 'merimag-backend'),
    ),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title box', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'tab',
        'options' => merimag_get_query_options_for_category(),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
    'style' => array(
        'type' => 'tab',
        'options' => merimag_get_block_style_options(),
        'title' => __('Styling', 'merimag-backend'),
    ),
    'sliding' => array(
        'type' => 'tab',
        'options' => merimag_get_sliding_options('', true),
        'title' => __('Sliding', 'merimag-backend'),
    ),
    'animation' => array(
        'type' => 'tab',
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_product_category_grid_options() {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = merimag_taxonomy_grid_settings();
  $general_options = array_merge( $general_options, $grid_options );
  $options = array(
    'general' => array(
        'type' => 'tab',
        'options' => $general_options,
        'title' => __('General', 'merimag-backend'),
    ),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title box', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'tab',
        'options' => merimag_get_query_options_for_product_cat(),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
    'style' => array(
        'type' => 'tab',
        'options' => merimag_get_block_style_options(),
        'title' => __('Styling', 'merimag-backend'),
    ),
    'animation' => array(
        'type' => 'tab',
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_product_category_carousel_options() {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = merimag_taxonomy_carousel_settings();
  $general_options = array_merge( $general_options, $grid_options );
  $options = array(
    'general' => array(
        'type' => 'tab',
        'options' => $general_options,
        'title' => __('General', 'merimag-backend'),
    ),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title box', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'tab',
        'options' => merimag_get_query_options_for_product_cat(),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
    'style' => array(
        'type' => 'tab',
        'options' => merimag_get_block_style_options(),
        'title' => __('Styling', 'merimag-backend'),
    ),
    'sliding' => array(
        'type' => 'tab',
        'options' => merimag_get_sliding_options('', true),
        'title' => __('Sliding', 'merimag-backend'),
    ),
    'animation' => array(
        'type' => 'tab',
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_posts_list_options( $post_type = 'post' ) {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_posts_list_settings();
	$pagination_options = merimag_get_pagination_options();
	$general_options = array_merge( $general_options, $grid_options, $pagination_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title', 'merimag-backend'),
    ),
		'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options( $post_type ),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_simple_posts_list_options( $post_type = 'post' ) {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),

  );
  
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $grid_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => 'tab',
        'options' => $general_options,
        'title' => __('General settings', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'tab',
        'options' => merimag_get_query_options( $post_type ),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_products_list_options( $post_type = 'post' ) {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_products_list_settings();
	$pagination_options = merimag_get_pagination_options();
	$general_options = array_merge( $general_options, $grid_options, $pagination_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
		'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options( $post_type ),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_category_titled_grid_options() {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_posts_grid_options_for_widget( $post_type = 'post') {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_grid_settings_for_widget();
	$grid_options = $post_type === 'product' ? merimag_product_grid_settings_for_widget() : $grid_options;
	$pagination_options = merimag_get_pagination_options();
	$general_options = array_merge( $general_options, $grid_options, $pagination_options );
	$options = array(
		'general' => array(
		    'type' => 'popup',
		    'popup-options' => $general_options,
		    'popup-title' => __('General', 'merimag-backend'),
		    'button' => __('General', 'merimag-backend'),
		    'label' => __('General settings', 'merimag-backend'),
		),
		'query' => array(
		    'type' => 'popup',
		    'popup-options' => merimag_get_query_options( $post_type ),
		    'popup-title' => __('Configuration', 'merimag-backend'),
		    'button' => __('Configuration', 'merimag-backend'),
		    'label' => __('Posts configuration', 'merimag-backend'),
		),
	);
	return $options;
}

function merimag_get_posts_list_options_for_widget( $post_type = 'post') {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = merimag_list_settings_for_widget();
  $grid_options = $post_type === 'product' ? merimag_product_grid_settings_for_widget() : $grid_options;
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $grid_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'popup',
        'popup-options' => merimag_get_query_options( $post_type ),
        'popup-title' => __('Configuration', 'merimag-backend'),
        'button' => __('Configuration', 'merimag-backend'),
        'label' => __('Posts configuration', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_image_posts_options_for_widget( $post_type = 'post') {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),
    'image_ratio' => array(
      'type'   => 'select',
      'label'  => esc_html__('Image aspect ratio', 'merimag-backend'),
      'value' => '16-9',
      'choices' => array(
        '1-2' => '1:2',
          '16-9' => '16:9',
          '2-3' => '2:3',
          '4-3' => '4:3',
          '1-1' => '1:1',
          '2-1' => '2:1',
      ),
    ),
    'columns' => array(
      'type' => 'select',
      'label' => __('colum', 'merimag-backend'),
      'choices' => merimag_get_recognized_grid_columns(false, 'general'),
    ),

  );
  
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $grid_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'popup',
        'popup-options' => merimag_get_query_options( $post_type ),
        'popup-title' => __('Configuration', 'merimag-backend'),
        'button' => __('Configuration', 'merimag-backend'),
        'label' => __('Posts configuration', 'merimag-backend'),
    ),
  );
  return $options;
}

function merimag_get_simple_posts_options_for_widget( $post_type = 'post' ) {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = array(
    'title' => array(
      'type' => 'text',
      'label' => __('Title', 'merimag-backend'),
    ),

  );
  
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $grid_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => 'popup',
        'popup-options' => $general_options,
        'popup-title' => __('General', 'merimag-backend'),
        'button' => __('General', 'merimag-backend'),
        'label' => __('General settings', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'popup',
        'popup-options' => merimag_get_query_options( $post_type ),
        'popup-title' => __('Configuration', 'merimag-backend'),
        'button' => __('Configuration', 'merimag-backend'),
        'label' => __('Posts configuration', 'merimag-backend'),
    ),
  );
  return $options;
}

function merimag_get_posts_carousel_options() {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_carousel_settings();
	if( isset( $grid_options['pagination'] ) ) {
    unset( $grid_options['pagination'] );
  }
	$general_options = array_merge( $general_options, $grid_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title', 'merimag-backend'),
    ),
		'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options(),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'sliding' => array(
		    'type' => 'tab',
		    'options' => merimag_get_sliding_options('', true),
		    'title' => __('Sliding', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_products_grid_options() {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_product_grid_settings();
	$pagination_options = merimag_get_pagination_options();
	$general_options = array_merge( $general_options, $grid_options, $pagination_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
    'title_box' => array(
        'type' => 'tab',
        'options' => merimag_get_title_box_options(),
        'title' => __('Title', 'merimag-backend'),
    ),
		'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options( 'product' ),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_menu_options() {
	$options = array(
		'title' => array(
			'type'  => 'text',
			'value' => '',
			'label' => __('Title', 'merimag-backend'),
		),
		'menu_items' => array(
			'type' => 'addable-box',
			'box-options' => array(
				'title' => array(
					'type'  => 'text',
					'value' => '',
					'label' => __('Title', 'merimag-backend'),
				),
				'link' => array(
					'type'  => 'wplink',
					'label' => __('Link', 'merimag-backend'),
				),

			),
			'template' => '{{- title }}',
      'title_field' => '{{{ title }}}',
		),
		'items_separator' => array(
		    'type' => 'select',
		    'label' => __('Items separator', 'merimag-backend'),
		    'value' => 'simple-spacing',
		    'choices' => array(
		    	'simple-spacing' => __('Simple spacing', 'merimag-backend'),
		    	'border-spacing' => __('Border spacing', 'merimag-backend'),
		    ),
		),
	);
	return $options;
}
function merimag_get_features_options() {
  $options = array(
    'title' => array(
      'type'  => 'text',
      'value' => '',
      'label' => __('Title', 'merimag-backend'),
    ),
    'type' => array(
      'type'  => 'select',
      'value' => '',
      'label' => __('Features type', 'merimag-backend'),
      'choices' => array(
        'post_templates' => __('Post templates', 'merimag-backend'),
        'post_formats' => __('Post formats', 'merimag-backend'),
        'post_blocks' => __('Post blocks', 'merimag-backend'),
      ),
    ),
    
  );
  return $options;
}
function merimag_get_custom_grid_options() {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_custom_grid_settings();
	$general_options = array_merge( $general_options, $grid_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
		'title_box' => array(
        	'type' => 'tab',
        	'options' => merimag_get_title_box_options( 'custom_grid' ),
        	'title' => __('Title box', 'merimag-backend'),
        ),
		'content' => array(
		    'type' => 'tab',
		    'options' => merimag_get_custom_content_options( 'custom_grid' ),
		    'title' => __('Content', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_products_carousel_options() {
	$general_options['title'] = array(
		'type'  => 'text',
		'value' => '',
		'label' => __('Block title', 'merimag-backend'),
	);
	$grid_options = merimag_product_carousel_settings();
  if( isset( $grid_options['pagination'] ) ) {
    unset( $grid_options['pagination'] );
  }
	$general_options = array_merge( $general_options, $grid_options );
	$options = array(
		'general' => array(
		    'type' => 'tab',
		    'options' => $general_options,
		    'title' => __('General', 'merimag-backend'),
		),
		'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options('product'),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'sliding' => array(
		    'type' => 'tab',
		    'options' => merimag_get_sliding_options('', true),
		    'title' => __('Sliding', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_general_slider_options( $post_type = 'post', $thumbs = false ) {
  if( $thumbs === true ) {
    $thumbs_options['block_style'] = array(
      'type'  => 'image-picker',
      'label' => __('Block Style', 'merimag-backend'),
      'choices' => merimag_get_recognized_sliders('thumbs'),
      'blank' => true,
    );

    $thumbs_options['infos_width'] = array(
        'type'  => 'slider',
        'label'  => esc_html__('Title box width', 'merimag-backend'),
        'value' => 60,
        'properties' => array(
            'min' => 0,
            'max' => 100,
            'step' => 1,
        ),
      );
  }
  $options = array(
    'title' => array(
      'type'  => 'text',
      'value' => '',
      'label' => __('Block title', 'merimag-backend'),
    ),
    'grid_style' => array(
      'type'  => 'hidden',
      'value' => 'absolute',
    ),
    'image_ratio' => array(
      'type'   => 'select',
        'label'  => esc_html__('Image aspect ratio', 'merimag-backend'),
        'value' => '16-9',
        'choices' => array(
          '1-2' => '1:2',
          '16-9' => '16:9',
          '2-3' => '2:3',
          '4-3' => '4:3',
          '1-1' => '1:1',
          '2-1' => '2:1',
        ),
    ),
    'title_size_elementor' => array(
      'type'   => 'hidden',
        'elementor_type'   => 'typography-v3',
        'value' => '',
        'label'  => esc_html__('Title typography', 'merimag-backend'),
        'selector' => ' .block-infos-title-wrapper:not(.force-size) .block-infos-title > a'
    ),
    'image_height' => array(
      'type'  => 'number',
      'label'  => esc_html__('Image height', 'merimag-backend'),
    ),
    'image_size' => array(
        'type'   => 'select',
        'label'  => esc_html__('Image dimensions', 'merimag-backend'),
        'value' => 'default',
        'choices' => merimag_get_recognized_image_sizes(),
    ),
    'fade' => array(
      'label'        => __( 'Fade animation', 'merimag-backend' ),
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
    ),
   'title_length'=> array(
        'type'   => 'number',
        'label'  => esc_html__('Title length', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 500,
            'step' => 1,
        ),
    ),
   'title_ellipsis' => array(
        'type'   => 'number',
        'label'  => esc_html__('Title lines', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 20,
            'step' => 1,
        ),
    ),
    'description_length' => array(
        'type'   => 'number',
        'label'  => esc_html__('Desciption length', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 500,
            'step' => 1,
        ),
    ),
    'description_ellipsis' => array(
        'type'   => 'number',
        'label'  => esc_html__('Desciption lines', 'merimag-backend'),
        'properties' => array(
            'min' => 0,
            'max' => 20,
            'step' => 1,
        ),
    ),
    'color_layer' => array(
      'type'  => 'switch',
      'label' => __('Colorful image layer', 'merimag-backend'),
      'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value' => 'no',
    ),
  );
  if( $post_type === 'post' ) {
    $options['show_description'] = array(
        'type'  => 'switch',
        'label' => __( 'Show description', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'show_description'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['show_number'] = array(
        'type'  => 'switch',
        'label' => __( 'Show order number', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'show_number'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['show_read_more'] = array(
        'type'  => 'switch',
        'label' => __( 'Show read more', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'show_read_more'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['show_category'] = array(
        'type'  => 'switch',
        'label' => __( 'Show category', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'show_category'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'yes',
    );
    $options['show_format_icon'] = array(
        'type'  => 'switch',
        'label' => __( 'Show post format icon', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'show_format_icon'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['show_review'] = array(
        'type'  => 'switch',
        'label' => __( 'Show review score', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data( 'show_review'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['review_display_style'] = array(
        'type'  => 'select',
        'label' => __( 'Review display style', 'merimag-backend' ),
        'value' => 'default',
        'choices' => array(
          'default' => sprintf('-- %s --', __('Default', 'merimag-backend')),
          'stars' => __('Stars', 'merimag-backend'),
          'circle' => __('Circle', 'merimag-backend'),
          'simple' => __('Simple', 'merimag-backend'),
          'bar'   => __('Bar', 'merimag-backend'),
        ),
    );
  }
  if( $post_type === 'product' ) {
    $options['show_sale'] = array(
        'type'  => 'switch',
        'label' => __( 'Show on sale indicator', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data_shop( 'show_sale'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
    $options['show_add_to_cart'] = array(
        'type'  => 'switch',
        'label' => __( 'Show add to cart', 'merimag-backend' ),
        'value' => merimag_get_default_grid_data_shop( 'show_add_to_cart'),
        'right-choice' => array(
          'value' => 'yes',
          'label' => __( 'Yes', 'merimag-backend' )
        ),
        'left-choice'  => array(
          'value' => 'no',
          'label' => __( 'No', 'merimag-backend' )
        ),
        'value'   => 'no',
    );
  }
  if( in_array($post_type, array('post', 'product') ) ) {
    $options['after_title'] = array(
        'type'   => 'select',
        'value' => $post_type === 'product' ? merimag_get_default_grid_data_shop('after_title') : merimag_get_default_grid_data( 'after_title'),
        'label'  => esc_html__('Meta infos', 'merimag-backend'),
        'choices' => $post_type === 'product' ? merimag_get_recognized_after_title( false, 'shop') :merimag_get_recognized_after_title(),
    );
  }
  return isset( $thumbs_options ) ? array_merge( $thumbs_options, $options ) : $options;
}
function merimag_get_custom_slider_options() {
	$options = array(
		'general' => array(
			'type' => 'tab',
			'title' => __('General', 'merimag-backend'),
			'options' => merimag_get_general_slider_options('custom'),
    ),
		'title_box' => array(
        	'type' => 'tab',
        	'options' => merimag_get_title_box_options( 'custom_slider' ),
        	'title' => __('Title box', 'merimag-backend'),
   ),
   'content' => array(
		    'type' => 'tab',
		    'options' => merimag_get_custom_content_options( 'custom_slider' ),
		    'title' => __('Content', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'sliding' => array(
		    'type' => 'tab',
		    'options' => merimag_get_sliding_options(),
		    'title' => __('Sliding', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_posts_slider_options() {
	$options = array(
		'general' => array(
			'type' => 'tab',
			'title' => __('General', 'merimag-backend'),
			'options' => merimag_get_general_slider_options(),
    ),
    'title_box' => array(
       'type' => 'tab',
       'options' => merimag_get_title_box_options('posts_slider'),
       'title' => __('Title box', 'merimag-backend'),
    ),
    'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options(),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'sliding' => array(
		    'type' => 'tab',
		    'options' => merimag_get_sliding_options( '', 'slider'),
		    'title' => __('Sliding', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_posts_slider_thumbs_options() {

	$options = array(
		'general' => array(
			'type' => 'tab',
			'title' => __('General', 'merimag-backend'),
			'options' => merimag_get_general_slider_options('post', true ),
		),		
    'query' => array(
		    'type' => 'tab',
		    'options' => merimag_get_query_options(),
		    'title' => __('Posts configuration', 'merimag-backend'),
		),
		'style' => array(
		    'type' => 'tab',
		    'options' => merimag_get_block_style_options(),
		    'title' => __('Styling', 'merimag-backend'),
		),
		'sliding' => array(
		    'type' => 'tab',
		    'options' => merimag_get_sliding_options(),
		    'title' => __('Sliding', 'merimag-backend'),
		),
		'animation' => array(
		    'type' => 'tab',
		    'options' => merimag_get_animation_options(),
		    'title' => __('Animating', 'merimag-backend'),
		),
	);
	return $options;
}
function merimag_get_block_style_options( $slug = '') {
	$slug	 = is_string( $slug ) && !empty( $slug ) ? $slug . '_' : '';
	$options = array(
      esc_attr( $slug ) . 'ignore_general_style' => array(
        'label'        => __( 'Ignore general style', 'merimag-backend' ),
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
        'desc'         => __( 'Select yes you want to not use the general style applied from customizer', 'merimag-backend' ),
      ),
        esc_attr( $slug ) . 'block_title_style' => array(
          'type'    => 'select',
          'value'   => 'default',
          'label'   => esc_html__('Block heading style', 'merimag-backend'),
          'choices' => merimag_get_recognized_block_title_styles(),
          'desc'         => __( 'Choose custom block title style', 'merimag-backend' ),
        ),
        esc_attr( $slug ) . 'principal_color' => array(
          'type'    => 'color-picker-v2',
          'value'   => '',
          'label'   => esc_html__('Block principal color', 'merimag-backend'),
          'desc'         => __( 'Primary color that will be used in styling elements', 'merimag-backend' ),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
        ),
      esc_attr( $slug ) .'background_color' => array(
          'type'  => 'color-picker-v2',
          'label' => __('Background', 'merimag-backend'),
          'desc'         => __( 'Background color', 'merimag-backend' ),
          'rgba' => true,
          'selector' => '.general-box-container',
          'elementor_type' => 'background',
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) .'background_gradient' => array(
          'type'  => 'gradient-v2',
          'desc'         => __( 'Background gradient', 'merimag-backend' ),
          'label' => __('Gradient background', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) .'background_image' => array(
          'type'  => 'upload',
          'desc'         => __( 'Background image', 'merimag-backend' ),
          'label' => __('Image background', 'merimag-backend'),
          'images_only' => true,
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
        esc_attr( $slug ). 'background_position' => array(
          'type'    => 'select',
          'desc'         => __( 'Background position', 'merimag-backend' ),
          'value'   => 'center center',
          'label'   => esc_html__('Background position', 'merimag-backend'),
          'choices' => merimag_get_recognized_background('background_position'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
        ),
        esc_attr( $slug ) . 'background_repeat' => array(
          'type'    => 'select',
          'desc'         => __( 'Background repeat', 'merimag-backend' ),
          'label'   => esc_html__('Background repeat', 'merimag-backend'),
          'choices' => merimag_get_recognized_background('background_repeat'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
        ),
        esc_attr( $slug ) . 'background_attachment' => array(
          'type'    => 'select',
          'desc'         => __( 'Background attachment', 'merimag-backend' ),
          'label'   => esc_html__('Background attachment', 'merimag-backend'),
          'choices' => merimag_get_recognized_background('background_attachment'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
        ),
        esc_attr( $slug ) . 'background_size' => array(
          'type'    => 'select',
          'desc'         => __( 'Background size', 'merimag-backend' ),
          'value'   => 'cover',
          'label'   => esc_html__('Background size', 'merimag-backend'),
          'choices' => merimag_get_recognized_background('background_size'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
        ),
      esc_attr( $slug ) . 'padding' => array(
          'type'  => 'spacing',
          'desc'         => __( 'Block padding', 'merimag-backend' ),
          'label' => __('Padding', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'margin' => array(
          'type'  => 'spacing',
          'desc'         => __( 'Block margin', 'merimag-backend' ),
          'label' => __('Margin', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'border_width' => array(
          'type'  => 'slider',
          'desc'         => __( 'Border width', 'merimag-backend' ),
          'label' => __('Border width', 'merimag-backend'),
          'value' => 0,
          'elementor_type' => 'border',
          'selector' => '.general-box-container',
          'properties' => array(
              'min' => 0,
              'max' => 10,
              'step' => 1,
          ),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'border_color' => array(
          'type'  => 'color-picker-v2',
          'desc'         => __( 'Border color', 'merimag-backend' ),
          'label' => __('Border color', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      
      esc_attr( $slug ) . 'border_style' => array(
          'type'  => 'select',
          'desc'         => __( 'Border style', 'merimag-backend' ),
          'label' => __('Border style', 'merimag-backend'),
          'choices' => array(
            'default'   => esc_html__('Default', 'merimag-backend'),
            'none'   => esc_html__('None', 'merimag-backend'),
            'solid'   => esc_html__('Solid', 'merimag-backend'),
            'dotted'  => esc_html__('Dotted', 'merimag-backend'),
            'dashed'  => esc_html__('Dashed', 'merimag-backend'),
            'double'  => esc_html__('Double', 'merimag-backend'),
          ),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'text_color' => array(
          'type'  => 'color-picker-v2',
          'desc'         => __( 'Text color', 'merimag-backend' ),
          'label' => __('Text color', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'links_color' => array(
          'type'  => 'color-picker-v2',
          'desc'         => __( 'Links color', 'merimag-backend' ),
          'label' => __('Links color', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'links_hover_color' => array(
          'type'  => 'color-picker-v2',
          'desc'         => __( 'Links hover color', 'merimag-backend' ),
          'label' => __('Links hover color', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'borders_color' => array(
          'type'  => 'color-picker-v2',
          'desc'         => __( 'Borderds color', 'merimag-backend' ),
          'label' => __('Borders color', 'merimag-backend'),
          'rgba' => true,
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
      esc_attr( $slug ) . 'buttons_color' => array(
          'type'  => 'color-picker-v2',
          'desc'         => __( 'Buttons color', 'merimag-backend' ),
          'label' => __('Buttons color', 'merimag-backend'),
          'wp-customizer-setting-args' => array(
                  'transport' => 'postMessage'
                ),
      ),
    );
  if( strrpos($slug, 'general') !== false ) {
    unset( $options[$slug . 'ignore_general_style'] );
  }
  if( $slug === 'general_content_area_' ) {
    unset( $options[$slug . 'padding'], $options[$slug . 'margin'] );
  }
  return $options;
}
function merimag_get_sliding_options( $slug = '', $carousel = false ) {
	$slug	 = is_string( $slug ) && !empty( $slug ) ? $slug . '_' : '';
	$options = array(
		esc_attr( $slug ) . 'auto_play' => array(
			'label'        => __( 'Auto play', 'merimag-backend' ),
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
		),
		esc_attr( $slug ) . 'auto_play_speed' => array(
		    'type'  => 'slider',
		    'label' => __('Auto play speed ( ms )', 'merimag-backend'),
		    'value' => 3,
		    'properties' => array(
		        'min' => 0,
		        'max' => 30000,
		        'step' => 1,
		    ),
		),
		esc_attr( $slug ) . 'show_dots' => array(
			'label'        => __( 'Show dots', 'merimag-backend' ),
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
		),
		esc_attr( $slug ) . 'show_arrows' => array(
			'label'        => __( 'Show arrows ( on hover )', 'merimag-backend' ),
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
		),
		esc_attr( $slug ) . 'infinite' => array(
			'label'        => __( 'Infinite loop', 'merimag-backend' ),
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
		),
		esc_attr( $slug ) . 'speed' => array(
		    'type'  => 'slider',
		    'label' => __('Animation speed ( ms )', 'merimag-backend'),
		    'value' => 300,
		    'properties' => array(
		        'min' => 0,
		        'max' => 10000,
		        'step' => 100,
		    ),
		),
	);
	if( $carousel === true ) {
		$carousel_options[esc_attr( $slug ) . 'columns' ] = array(
	        'type'  => 'radio',
	        'value' => merimag_get_default_grid_data( 'columns'),
	        'label' => esc_html__('Visible slides', 'merimag-backend'),
	        'choices' => merimag_get_recognized_carousel_columns(),
	        'inline' => true,
	    );
		$carousel_options[esc_attr( $slug ) . 'sliding_columns' ] = array(
	        'type'  => 'radio',
	        'value' => merimag_get_default_grid_data( 'columns'),
	        'label' => esc_html__('Sliding slides', 'merimag-backend'),
	        'choices' => merimag_get_recognized_carousel_columns(),
	        'inline' => true,
	    );
	    $options = $carousel_options + $options;
	}
  if( $carousel === 'slider' ) {
    $options[$slug . 'center_mode'] = array(
      'label'        => __( 'Center mode', 'merimag-backend' ),
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
    );
    $options[$slug . 'center_padding'] = array(
      'label'        => __( 'Center padding ( % )', 'merimag-backend' ),
      'type'         => 'number',

    );
  }
	return $options;
}
function merimag_get_animation_options( $slug = '' ) {
	$slug	 = is_string( $slug ) && !empty( $slug ) ? $slug . '_' : '';
	$options = array(
		esc_attr( $slug ) . 'entrance_animation' => array(
			'label'   => __( 'Animation', 'merimag-backend' ),
			'type'    => 'select',
			'choices' => merimag_get_recognized_in_animations( false ),
			'attr'    => array( 'class' => 'fw-animation-select' ),
		),
    esc_attr( $slug ) . 'animation_speed' => array(
      'label'   => __( 'Animation speed', 'merimag-backend' ),
      'type'    => 'select',
      'choices' => array(
        'default' => __('Default', 'merimag-backend'),
        'slow' => __('Slow', 'merimag-backend'),
        'slower' => __('Slower', 'merimag-backend'),
        'fast' => __('Fast', 'merimag-backend'),
        'faster' => __('Faster', 'merimag-backend'),
      ),
    ),
	);
	return $options;
}
function merimag_get_main_query_options( $slug = '' ) {
  $slug  = is_string( $slug ) && !empty( $slug ) ? $slug . '_' : '';
  $options = array(
    'block_title' => array(
      'type' => 'text',
      'label' => __('Block title', 'merimag-backend'),
    ),
  );
  return $options;
}
function merimag_get_simple_grid_options( $slug = '' ) {
  $slug  = is_string( $slug ) && !empty( $slug ) ? $slug . '_' : '';
  $options = array(
    esc_attr( $slug ) . 'grid_style' => array(
      'type' => 'select',
      'label' => __('Grid style', 'merimag-backend'),
      'value' => 'default',
      'choices' => merimag_get_recognized_predefined_grid_styles(),
    ),
  );
  return $options;
}
function merimag_get_simple_posts_grid_options( $post_type = 'post') {
  $general_options['title'] = array(
    'type'  => 'text',
    'value' => '',
    'label' => __('Block title', 'merimag-backend'),
  );
  $grid_options = merimag_get_simple_grid_options();
  $pagination_options = merimag_get_pagination_options();
  $general_options = array_merge( $general_options, $grid_options, $pagination_options );
  $options = array(
    'general' => array(
        'type' => 'tab',
        'options' => $general_options,
        'title' => __('General', 'merimag-backend'),
    ),
    'query' => array(
        'type' => 'tab',
        'options' => merimag_get_query_options( $post_type ),
        'title' => __('Posts configuration', 'merimag-backend'),
    ),
    'style' => array(
        'type' => 'tab',
        'options' => merimag_get_block_style_options(),
        'title' => __('Styling', 'merimag-backend'),
    ),
    'animation' => array(
        'type' => 'tab',
        'options' => merimag_get_animation_options(),
        'title' => __('Animating', 'merimag-backend'),
    ),
  );
  return $options;
}
