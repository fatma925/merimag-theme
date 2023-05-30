<?php
function merimag_get_query_valid_params() {
  return array('order_by', 'order', 'include', 'exclude', 'offset', 'period', 'only_reviews', 'on_sale', 'showposts', 'number', 'author', 'post_format');
}
/**
 * Validate query arguments
 *
 * @param string $post_type 
 * @param array $atts list of arguments
 * 
 * @return array args ready to use in WP_Query class
 */ 
function merimag_validate_query( $post_type = 'post', $atts = array() ) {
  $query                 = array();
  $query['post_type']    = post_type_exists( $post_type ) ? $post_type : 'post';
  $query['orderby']      = isset( $atts['order_by'] ) && in_array( $atts['order_by'], merimag_get_recognized_order_by_options( $post_type , true ) ) ? $atts['order_by'] : 'rand';
  $query['order']        = isset( $atts['order'] ) && in_array( $atts['order'], merimag_get_recognized_order_options( true ) ) ? $atts['order'] : 'desc';
  $query['post__in']     = isset( $atts['include'] ) && is_array( $atts['include'] ) ? $atts['include'] : array();
  $query['post__not_in'] = isset( $atts['exclude'] ) && is_array( $atts['exclude'] ) ? $atts['exclude'] : array();
  $query['author']       = isset( $atts['author'] ) && ( is_array( $atts['author'] ) || is_numeric( $atts['author'] ) ) ? $atts['author'] : false;
  $query['offset']       = isset( $atts['offset'] ) && is_numeric( $atts['offset'] ) ? $atts['offset'] : 0;
  $query['period']       = isset( $atts['period'] ) && in_array( $atts['period'], array('all', 'year', 'month', 'week', 'today'))? $atts['period'] : 'all';
  $taxonomies            = get_object_taxonomies( $post_type, 'objects' );

  if( $taxonomies && count( $taxonomies ) >= 1 ) {
    foreach( $taxonomies as $tax => $tax_object ) {
      if( $tax == 'post_format') {
        continue;
      }
      if( isset( $atts[$tax] ) && is_array( $atts[$tax] ) && count( $atts[$tax] ) >= 1 ) {
        $query[ 'tax_query' ][] = array(
          'taxonomy' => $tax,
          'field'    => 'term_id',
          'terms'    => $atts[$tax],
        );
      }
    }
    if( isset( $query['tax_query'] ) ) {
        $query[ 'tax_query' ]['relation'] = 'AND';
    }
  }
  if( isset( $atts['only_reviews'] ) && $atts['only_reviews'] === 'yes' ) {
    $query['meta_query'][] = array(
        'key'     => 'fw_option:single_enable_review',
        'value'   => 'yes',
    );
  }
  if( isset( $atts['post_format'] ) && in_array($atts['post_format'], array('audio', 'video', 'gallery'))) {
     $query[ 'tax_query' ][] = array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => array( 'post-format-' . $atts['post_format'] ),
    );
  }
  if( isset( $atts['on_sale'] ) && $atts['on_sale'] === 'yes' && $post_type === 'product' ) {
    $query['meta_query']['relation'] = 'OR';
    $query['meta_query'][] = array(
        'key'     => '_sale_price',
        'value'   => 0,
        'compare' => '>',
        'type'    => 'numeric'
    );
    $query['meta_query'][] = array(
        'key'     => '_min_variation_sale_price',
        'value'   => 0,
        'compare' => '>',
        'type'    => 'numeric'
    );
  }
  
  $query['showposts']         = isset( $atts['number'] ) && is_numeric( $atts['number'] ) && $atts['number'] > 0 && $atts['number'] <= 30 ? $atts['number'] : get_option('posts_per_page');
  $query['showposts']         = isset( $atts['number']['size'] ) && is_numeric( $atts['number']['size']  ) && $atts['number']['size']  > 0 && $atts['number']['size']  <= 30 ? $atts['number']['size']  : $query['showposts'];

  return $query;
}
/**
 * Query settings
 *
 * @param string $post_type 
 * @param string $slug to add before params name
 * 
 * @return array Query args ready to use in WP_Query class
 */ 
function merimag_get_theme_settings_query( $post_type, $slug ) {

    if( defined('FW') ) {

        $query_params      = merimag_get_query_valid_params();

        foreach ($query_params as $param) {
          $atts[$param] = fw_get_db_customizer_option( $slug . '_' . $param );
        }

        $taxonomies        = get_object_taxonomies( 'post', 'objects' );

        if( $taxonomies && count( $taxonomies ) >= 1 ) {
            foreach( $taxonomies as $tax => $tax_object ) {
                $atts[ $tax ] = fw_get_db_customizer_option( $slug . '_' . $tax, 'rand' );
                if( !is_array( $atts[$tax] ) || count( $atts[$tax] ) < 1 ) {
                    unset( $atts[$tax] );
                }
            }
        }

    } else {

        $atts = array( 'order_by' => 'date', 'order' => 'desc', 'number' => get_option('posts_per_page') );

    }

    $query = merimag_validate_query( $post_type, $atts );

    return $query;

}
/**
 * Block title style
 *
 * @param string $block_title_style selected block title style 
 * @param string $for where this style will be applied
 * 
 * @return string filtered block title style
 */ 
function merimag_get_block_title_style( $block_title_style = 'default', $for = 'general' ) {
  if( $block_title_style !== 'default' && in_array($block_title_style, merimag_get_recognized_block_title_styles(true) ) ) {
    return $block_title_style;
  } else {
    $footer_option          = merimag_get_db_live_customizer_option('general_footer_block_title_style');
    $for                    = $for === 'footer' && ( !$footer_option || $footer_option === 'default' ) ? 'widget' : $for;
    $for                    = in_array($for, array('general', 'footer_widget', 'widget', 'sidebar_area')) ? $for : 'general';
    $curstomizer_option     = $for !== 'general' ? sprintf('general_%s_block_title_style', $for ) : 'block_title_style';

    $default                = merimag_get_db_live_customizer_option( 'block_title_style'); 

    $block_title_style      = merimag_get_db_live_customizer_option( $curstomizer_option, $default );


    $block_title_style      = $for === 'widget' && $block_title_style === 'default' ? merimag_get_db_customizer_option( 'general_sidebar_area_block_title_style', $default )  : $block_title_style;

      
    $block_title_style      = $block_title_style === 'default' ? $default : $block_title_style;

    $block_title_style      = $block_title_style === 'default' ? 'style-1' : $block_title_style;

    return $block_title_style;
  }
}
/**
 * Display the header of a box that contains a list of posts
 *
 * @param array $atts list of arguments
 * @param string $id html identifier of the block
 * 
 * @return void
 */ 
function merimag_get_block_filters_head( $atts, $id ) {

    $filters_style             = isset( $atts['filters_style'] ) ? $atts['filters_style'] : 'beside_title';
    $filters                   = isset( $atts['filters'] ) && is_array( $atts['filters'] ) && !empty( $atts['filters'] ) ? true : false;
    $title                     = isset( $atts['title'] ) && !empty( $atts['title'] ) && is_string( $atts['title'] ) ? true : false;
    if( $title ) {
      $atts['title'] = apply_filters('block_title_filter', $atts['title'] );
    }
    $filter_class              = $filters_style === 'tabs' ? 'merimag-box-filter-tabs general-border-color' : '';
    $filter_class              = $filters_style === 'vertical_tabs' ? 'merimag-box-filter-tabs general-border-color vertical-tabs' : $filter_class;
    $block_title_style         = isset( $atts['block_title_style'] ) ? $atts['block_title_style'] : 'default';
    $get_from                  = isset( $atts['is_widget'] ) && $atts['is_widget'] === true ? 'widget' : 'general';
    $get_from                  = isset( $atts['is_footer'] ) && $atts['is_footer'] === true ? 'footer' : $get_from;
    $block_title_style         = merimag_get_block_title_style( $block_title_style, $get_from);
    if( $filters ) {
      $selector                = '#' . $id;
      $block_css               = isset( $atts ) && is_array( $atts ) ? merimag_get_dynamic_block_style( $atts, $selector ) : merimag_get_dynamic_block_style( 'general_block', $selector );
      // Dynamic bloc css
     
      $ignore_style            = isset( $atts['ignore_general_style'] ) && $atts['ignore_general_style'] === 'yes' ? true : false;
      $filter_class           .= $ignore_style === true ? ' ignore-general-style' : '';
      $event                   = isset( $atts['mouseover_load'] ) && $atts['mouseover_load'] === 'yes' ? 'mouseover' : 'click';

      echo '<div data-event="' . esc_attr( $event ) . '" class="merimag-box-filter general-box-container ' . esc_attr( $filter_class ) . '" id="' . esc_attr( $id ) . '">'; // begin box filter
      if(  $filters_style === 'beside_title' ) {
        echo sprintf('<div class="block-title-wrapper %s">', $block_title_style );
        if( $title ) {
           echo '<span class="block-title">' . esc_attr( esc_attr( $atts['title'] ) ) . '</span>';
        }
      }
      $li_class = $filters_style !== 'beside_title' ? 'principal-color-border-color' : '';
      $ul_class = is_string($filters_style) ? $filters_style : '';
      echo sprintf('<ul class="merimag-block-filters merimag-prio %s">', esc_attr($ul_class));
      echo  '<li class="tab-loaded ' . esc_attr($li_class) . '"><a href="#' . esc_attr( $id . '-1' ) . '">';
      $filter_icon     = isset( $atts['filter_icon']['icon-class'] ) && is_string( $atts['filter_icon']['icon-class'] ) ? $atts['filter_icon']['icon-class'] : '';
      $filter_icon     = isset( $atts['filter_icon']['value'] ) && is_string( $atts['filter_icon']['value'] ) ? $atts['filter_icon']['value'] : $filter_icon;
      if( $filter_icon ) {
        echo sprintf('<i class="merimag-filter-icon %s"></i>', esc_attr( $filter_icon ) );
      }
      echo isset( $atts['filter_title'] ) && !empty( $atts['filter_title'] ) ? esc_attr(  $atts['filter_title'] ) : esc_html__('Filter #1', 'merimag');
      echo '</a></li>';
      foreach( $atts['filters'] as $filter_index => $filter ) {
        $filters_input = 'filter-input-' . esc_attr( $id . '-' . ( $filter_index + 2 ) );
        echo  '<li class="' . esc_attr( $li_class ) . '" data-filters-input="' . esc_attr( $filters_input ) . '"><a href="#' . esc_attr( $id . '-' . ( $filter_index + 2) ) . '">';
        $filters_atts = is_array( $filter ) ? array_replace($atts, $filter ) : $atts;
        $filters_atts['block_id'] = merimag_uniqid('merimag-filter-');
        echo '<input type="hidden" id="' . esc_attr( $filters_input ) . '" value="' . esc_attr(str_replace('"', '-quote-', json_encode( $filters_atts )) ) . '">';
        $filter_icon     = isset( $filter['filter_icon']['icon-class'] ) && is_string( $filter['filter_icon']['icon-class'] ) ? $filter['filter_icon']['icon-class'] : '';
        $filter_icon     = isset( $filter['filter_icon']['value'] ) && is_string( $filter['filter_icon']['value'] ) ? $filter['filter_icon']['value'] : $filter_icon;
        if( $filter_icon ) {
          echo sprintf('<i class="merimag-filter-icon %s"></i>', esc_attr( $filter_icon ) );
        }
        echo isset( $filter['filter_title'] ) && !empty( $filter['filter_title'] ) ? esc_attr(  $filter['filter_title'] ) : esc_html__('Filter #', 'merimag') . ( $filter_index + 2);
        echo '</a></li>';
      }
      echo '</ul>';
      if(  $filters_style === 'beside_title' ) {
        echo '</div>';
      }
    }
}
/**
 * Display list of posts in different ways depends on selected settings $atts
 *
 * @param array $atts list of arguments
 * @param string $id html identifier of the block
 * 
 * @return void
 */ 
function merimag_get_box( $atts = false, $id = false ) {
  if( !is_array( $atts )  && defined('DOING_AJAX') && DOING_AJAX && isset( $_POST['atts'] ) ) {
    merimag_check_ajax_referer( 'merimag_options', 'nonce' );
    $atts = json_decode( str_replace('-quote-', '"', stripcslashes($_POST['atts'])), true );
    unset( $atts['filters'] );
    $atts['use_box_container'] = false;
    $atts['is_tab'] = true;
  }
  if( isset( $atts['filters'] ) && is_array( $atts['filters'] ) && !empty( $atts['filters'] ) ) {
    $use_box_container = false;
    $filters_style  = isset( $atts['filters_style'] ) ? $atts['filters_style'] : '';
    $panel_class    = in_array( $filters_style, array('tabs', 'vertical_tabs' ) )  ? 'merimag-tabs-panel' : '';
    $atts['is_tab'] = true;
    echo '<div class="merimag-box-filter-panel ' . esc_attr( $panel_class ) . '" id="' . esc_attr( $id . '-1' )  . '">';
      merimag_blocks_box( $atts );
    echo '</div>';
    foreach( $atts['filters'] as $filter_index => $filter ) {
      echo '<div class="merimag-box-filter-panel merimag-block-data-container ' . esc_attr( $panel_class ) . '" id="' . esc_attr( $id . '-' . ( $filter_index + 2 ) ) . '">';
      echo sprintf('<div class="merimag-block-data-loader">%s</div>', merimag_get_spinkit() );
      echo '</div>';
    }
    $must_end_box = true;
  } else {
    merimag_blocks_box( $atts );
  }
  if( isset( $must_end_box ) && $must_end_box === true ) {
    echo '</div>';
  }
  if( defined('DOING_AJAX') && DOING_AJAX ) {
    if( function_exists('rwd_print_styles') ) {
      rwd_print_styles();
    }
    if(  isset( $_POST['atts'] ) ) {
      wp_die();
    }
    
  }
}
add_action( 'wp_ajax_merimag_get_box', 'merimag_get_box' );
add_action( 'wp_ajax_nopriv_merimag_get_box', 'merimag_get_box' );
/**
 * Get page grid data
 * 
 * @param array $settings the list of grid settings
 * @param string $page name of the wordpress page to retreive settings from
 *
 * @return array grid data
 */
function merimag_get_page_grid_data( $settings, $page = 'index' ) {
  $grid_data       = array();
  $page_listing    = merimag_get_db_customizer_option('listing_' . $page );
  $default_listing = merimag_get_db_customizer_option('listing_default' );
  $page_listing    = isset( $page_listing['custom_listing'] ) && $page_listing['custom_listing'] === 'yes' ? $page_listing : $default_listing;
  foreach( $settings as $key => $default ) {
    $grid_data[$key] = isset( $page_listing['custom_listing'] ) && $page_listing['custom_listing'] === 'yes' && isset( $page_listing['yes'][$key] ) && $page_listing['yes'][$key] ? $page_listing['yes'][$key] : $default;
    $grid_data[$key] = $grid_data[$key] === 'yes' ? true : $grid_data[$key];
    $grid_data[$key] = $grid_data[$key] === 'no' && $key !== 'spacing'  ? false : $grid_data[$key];
  }
  return $grid_data;
}
/**
 * Get grid data
 * 
 * @param array $defaults the defaults settings
 * @param array $atts the selected settings
 *
 * @return array grid data
 */
function merimag_get_grid_data( $defaults, $atts ) {
  $grid_data       = array();
  foreach( $defaults as $key => $default ) {
    $grid_data[$key] = isset( $atts[ $key ] ) && !empty( $atts[ $key ]  ) ? $atts[ $key ] : $defaults[ $key ];
    $grid_data[$key] = $grid_data[$key] === 'yes' ? true : $grid_data[$key];
    $grid_data[$key] = $grid_data[$key] === 'no' && $key !== 'spacing'  ? false : $grid_data[$key];
  }
  return $grid_data;
}
/**
 * Validate grid data
 * 
 * @param string $key the key of option
 * @param mixed $value the value option
 * @param string $value the value option
 *
 * @return mixed the validated value
 */
function merimag_validate_grid_data( $key, $value, $page = 'index' ) {
  switch ($key) {
    case 'columns':
      $return = in_array( $value, merimag_get_recognized_grid_columns( true, 'general' ) ) ? $value : 2;
      break;
    case 'grid_style':
      $return = is_string( $value ) && in_array( $value, merimag_get_recognized_grid_styles( true ) ) ? $value : 'simple';
      break;
    case 'image_height':
      $return = is_numeric( $value ) && $value <= 1440 ? $value : 'auto';
      break;
    case 'title_size':
      $return = is_string( $value ) && in_array( $value, merimag_get_recognized_title_sizes( true ) ) ? $value : 'normal';
      break;
    case 'show_number':
      $return = is_bool( $value ) ? $value : false;
      break;
    case 'show_description':
      $return = is_bool( $value ) ? $value : false;
      break;
    case 'after_title':
      $return = is_string( $value ) && in_array($value, merimag_get_recognized_after_title( true, $page ) )  ? $value : 'date|views|comments';
      break;
    case 'spacing':
      $return = is_string( $value ) && in_array($value, merimag_get_recognized_grid_spacing( true ) )  ? $value : true;
      break;
    case 'masonry':
      $return = is_bool( $value ) ? $value : false;
            break;
        case 'title_length':
        case 'description_length':
            $return = is_array( $value ) ? $value : false;
            break;
        case 'pagination':
            $return = is_string( $value ) && in_array($value, merimag_recognized_pagination_options( true, true ) )  ? $value : 'default';
      break;
  }
  return !isset( $return ) || empty( $return ) ? $value : $return;
}
/**
 * Display page grid
 * 
 * @param string $page the name of wordpress page that call
 *
 * @return void
 */
function merimag_get_page_grid( $page = 'index', $force_index = false ) {

    $grid_style           = merimag_get_db_customizer_option($page . '_grid_style' );
    $default_grid_style   = merimag_get_db_customizer_option('default_grid_style' );
    $grid_style           = !$grid_style || $grid_style === 'default' ? $default_grid_style : $grid_style;
    
    $atts                 = merimag_get_predefined_grid_style( $grid_style );
    $atts['block_style']  = 'grid';
    $atts['page_query']   = true;
    $atts['post_type']    = 'post';
    $atts['use_box_container']    = false;

    $atts['force_index'] = $force_index === true ? true : false;


    merimag_blocks_box( $atts );

}
/**
 * Get default grid data
 * 
 * @param string $get_key specific key from the defaults
 *
 * @return mixed array list of defaults grid data if $get_key is false and the specific key value if a valid * key is set
 */
function merimag_get_default_grid_data( $get_key = false, $get_for = false ) {

    switch ($get_for) {
      case 'related_posts':
          $defaults = array( 
            'columns' => 3, 
            'grid_style' => 'simple', 
            'title_size' => 'normal',
            'title' => __('Related posts', 'merimag'),
            'sub_title_size' => 'small',
            'show_number' => false,
            'show_description' => false,
            'show_read_more' => false,
            'show_category' => true,
            'show_format_icon' => true,
            'show_review' => true,
            'review_display_style' => 'default',
            'centered_infos' => false,
            'after_title' => 'date|views|comments',
            'spacing' => 'normal',
            'masonry' => false,
            'title_length' => false,
            'title_ellipsis' => false,
            'description_length' => false,
            'description_ellipsis' => false,
            'number' => 3,
            'pagination' => function_exists('is_amp_endpoint') && is_amp_endpoint() ? 'default' : 'load_more',
            'infos_position' => 'left-bottom',
        );
        break;
      case 'read_also':
        $defaults = array( 
            'columns' => 3, 
            'grid_style' => 'simple', 
            'title_size' => 'normal',
            'sub_title_size' => 'small',
            'title' => __('Read also', 'merimag'),
            'show_number' => false,
            'show_description' => false,
            'show_read_more' => false,
            'show_category' => true,
            'show_format_icon' => true,
            'show_review' => true,
            'review_display_style' => 'default',
            'centered_infos' => false,
            'after_title' => 'date|views|comments',
            'spacing' => 'normal',
            'masonry' => false,
            'number' => 6,
            'title_length' => false,
            'title_ellipsis' => false,
            'description_length' => false,
            'description_ellipsis' => false,
            'pagination' => function_exists('is_amp_endpoint') && is_amp_endpoint() ? 'default' : 'load_more',
            'infos_position' => 'left-bottom',
        );
        break;
    }
    
    $defaults = isset( $defaults ) ? $defaults : array( 
        'columns' => '1', 
        'grid_style' => 'left', 
        'title_size' => 'medium',
        'sub_title_size' => 'small',
        'show_number' => false,
        'show_description' => true,
        'show_read_more' => true,
        'show_category' => true,
        'show_format_icon' => true,
        'separator' => true,
        'show_review' => true,
        'review_display_style' => 'default',
        'centered_infos' => false,
        'after_title' => 'date|views|comments',
        'spacing' => 'medium',
        'masonry' => false,
        'title_length' => false,
        'title_ellipsis' => 2,
        'description_length' => false,
        'description_ellipsis' => 3,
        'pagination' => 'pagination',
        'infos_position' => 'left-bottom',
    );
    $defaults        =  $get_for ? apply_filters( 'merimag_get_default_grid_data_' . $get_for, $defaults ) : apply_filters( 'merimag_get_default_grid_data', $defaults );

    if( defined('FW') && !is_admin() ) {
      $default_listing = merimag_get_db_customizer_option( 'listing_default' );
      foreach( $defaults as $key => $default ) {
          $defaults[$key] = isset( $default_listing['custom_listing'] ) && $default_listing['custom_listing'] === 'yes' && isset( $default_listing['yes'][$key] ) ? $default_listing['yes'][$key] : $default;
      }
    }

  return $get_key !== false && isset( $defaults[$get_key] ) ? $defaults[$get_key] : $defaults;
}
/**
 * Get default grid data
 * 
 * @param string $get_key specific key from the defaults
 *
 * @return mixed array list of defaults grid data if $get_key is false and the specific key value if a valid * key is set
 */
function merimag_get_default_grid_data_related_posts( $get_key = false ) {
    
    $defaults = array( 
        'columns' => 3, 
        'grid_style' => 'simple', 
        'title_size' => 'normal',
        'sub_title_size' => 'small',
        'show_number' => false,
        'show_description' => false,
        'show_read_more' => false,
        'show_category' => true,
        'show_format_icon' => true,
        'show_review' => true,
        'review_display_style' => 'default',
        'centered_infos' => false,
        'after_title' => 'date|views|comments',
        'spacing' => 'normal',
        'masonry' => false,
        'title_length' => false,
        'title_ellipsis' => false,
        'description_length' => false,
        'description_ellipsis' => false,
        'pagination' => 'default',
        'infos_position' => 'left-bottom',
    );

    $defaults        =  apply_filters( 'merimag_get_default_grid_data_related_posts', $defaults );


    return $get_key !== false && isset( $defaults[$get_key] ) ? $defaults[$get_key] : $defaults;
}
/**
 * Get default grid data shop
 * 
 * @param string $get_key specific key from the defaults
 *
 * @return mixed array list of defaults grid data if $get_key is false and the specific key value if a valid * key is set
 */
function merimag_get_default_grid_data_shop( $get_key = false ) {
    
    $defaults = array( 
        'columns' => 3, 
        'grid_style' => 'simple', 
        'title_size' => 'normal',
        'centered_infos' => false,
        'show_description' => false,
        'show_number' => false,
        'show_add_to_cart' => true,
        'show_category' => true,
        'show_sale' => false,
        'after_title' => 'price|product_rating',
        'spacing' => 'default',
        'masonry' => false,
        'title_length' => false,
        'title_ellipsis' => false,
        'description_length' => false,
        'description_ellipsis' => false,
        'pagination' => 'pagination',
        'infos_position' => 'left-bottom',
    );

    $defaults =  apply_filters( 'merimag_get_default_grid_data_shop', $defaults );

    return $get_key !== false && isset( $defaults[$get_key] ) ? $defaults[$get_key] : $defaults;
}

/**
 * Check if multiple keys exists in an array
 *
 * @param array the array to check for keys
 * @param array the list of keys to check
 *
 * @return bool
 */
function merimag_array_keys_exists( $array, $keys ) {
  if( !is_array( $array ) ) {
    return false;
  }
  foreach( $keys as $key ) {
    if( !array_key_exists( $key, $array ) ) {
      return false;
    }
  }
  return true;
}


/**
 * Get borders css
 *
 * @param string $borders_color valid css color
 * @param string $location valid css selector
 * @return string css code
 */
function merimag_get_borders_color_css( $borders_color, $location = 'body' ) {
    $style = '';

    if( isset( $borders_color ) && !empty( $borders_color ) && merimag_validate_color( $borders_color ) ) {

      $style .= merimag_get_custom_selector_css( 'borders_color', $borders_color, $location );
   
    }
    return $style;
}
/**
 * Get borders css
 *
 * @param string $buttons_color valid css color
 * @param string $location valid css selector
 * @return string css code
 */
function merimag_get_buttons_css( $buttons_color, $location = 'body' ) {
  $style = '';
  $principal_color = merimag_get_principal_color();
  if(  $principal_color == $buttons_color && $location != 'body' ) {
    return;
  }
  if( !empty( $buttons_color ) ) {
    $location = $location !== 'body' ? 'body.site-body ' . $location : 'body.site-body';
    $buttons_color_text_color = merimag_get_text_color_from_background( $buttons_color );
    $style .= merimag_get_custom_selector_css( 'buttons_color', $buttons_color, $location );
    $style .= merimag_get_custom_selector_css( 'buttons_color_text_color', $buttons_color_text_color, $location, true );
  }
  return $style;
}
/**
 * Get default text color based on the dark light option
 *
 * @param string $dark_light can be light or dark
 * @param string $location valid css selector
 * @return array list of colors
 */
function merimag_get_default_text_color_css( $dark_light = 'light', $location = 'body' ) {
    $defaults = array(
        'text_color' => '#505050',
        'links_color' => '#2d2d2d',
        'links_hover_color' => '#000',
    );
    $defaults_dark = array(
        'text_color' => '#ccc',
        'links_color' => '#eee',
        'links_hover_color' => '#fff',
    );
    if( $dark_light === 'dark' ) {
      extract( $defaults_dark );
    } else {
      extract( $defaults );  
    }
    $css = sprintf( '%1$s { color: %2$s; } %1$s a, %1$s a:active, %1$s a:focus { color: %3$s; } %1$s a:hover { color: %4$s; }', $location, $text_color, $links_color, $links_hover_color );
    return $css;
}
/**
 * Normalize custom css selectors
 *
 * @param string $for selector key
 * @return array list of selectors
 */
function merimag_custom_slectors( $for = false ) {
  $selectors = array(
      'text_color' => array(
          'color' => ' ',
          'background-color' => '.text-color-background',
          'fill' => '.wp-subscribe-loader path'
      ),
      'links_color' => array(
          'color' => 'a, a:active, h1, h2, h3, h4, h5, h6, label, .links-color, .block-title-wrapper',
          'border-color' => 'label, .links-border-color',
          'background-color' => '.links-color-background'
      ),
      'links_hover_color' => array(
          'color' => 'a:focus, a:hover, .merimag-menu-social a:hover span, .links-color:hover, li.active-menu-item > a, .merimag-block:hover a.title-display',
          'border-color' => 'li.active-menu-item > a, .links-border-color:hover',
          'background-color' => '.links-hover-color-background',
      ),
      'borders_color' => array(
          'border-color' => '.merimag-block-separator, .merimag-divider-container, .woocommerce form fieldset, .coupon, .comment-list ol, .merimag-odd-column .merimag-block-infos-left-right-classic, .merimag-even-column .merimag-block-infos-left-right-classic, .merimag-block.border-block, .merimag-block.border-block, .merimag-header-content, li.mega-menu-col > a, .general-border-color,.general-border-color,.woocommerce-account .woocommerce, .wp-subscribe-wrap input.email-field, .wp-subscribe-wrap input.name-field, .single-product .cart, .page-numbers, .gallery-item figcaption, .merimag-page-link, ul.page-numbers.page-numbers li>a, , ul.page-numbers.page-numbers li>span, .post-page-numbers, .entry-summary .cart, div.product .woocommerce-product-gallery, .products .woocommerce-LoopProduct-link, .products .product-category > a, .site-content-area-style, .merimag-read-more, .merimag-pagination-button, .woocommerce-pagination, .woocommerce-columns--addresses .woocommerce-column--1, .woocommerce-columns--addresses .woocommerce-column--2, .merimag-sidebar-content > .merimag-mobile-menu-social, .merimag-mobile-header-content:not(.stacked-icons) .merimag-mobile-menu-opener, .wc-tabs, .merimag-pagination-buttons.numeric-pagination, .comment_container, .horizontal-menu .menu-item-content, .merimag-contact-infos-shortcode-default .merimag-contact-item, #bbpress-forums fieldset.bbp-form, #bbpress-forums ul.bbp-forums, #bbpress-forums ul.bbp-lead-topic, #bbpress-forums ul.bbp-replies, #bbpress-forums ul.bbp-search-results, #bbpress-forums ul.bbp-topics, #bbpress-forums li.bbp-footer, #bbpress-forums li.bbp-header, .bbp-replies .bbp-reply-author, #bbpress-forums li.bbp-body ul.forum, #bbpress-forums li.bbp-body ul.topic, .site-content-area-style, .merimag-site-content.content .cart-collaterals .cart_totals, .merimag-site-content.content .cart-collaterals .coupon, .merimag-box-filter.vertical-tabs .merimag-box-filter-panel, .vertical-menu .menu .mega-menu-row > .menu-item > a, .horizontal-menu .menu > li > .sub-menu, .merimag-mobile-menu-sidebar .flex-menu .menu, .sidebar-widget:not(.ignore-general-style),.rtl .merimag-box-filter.vertical-tabs .merimag-box-filter-panel',
          'border-top-color' => '.general-border-top-color, tfoot td, tbody th, tfoot th, .merimag-box-filter.vertical-tabs ul.merimag-block-filters li.ui-state-active',
          'border-bottom-color' => '.general-border-bottom-color, table td, table th, .merimag-box-filter.vertical-tabs ul.merimag-block-filters li.ui-state-active',
          'border-left-color' => '.general-border-left-color',
          'border-right-color' => '.general-border-right-color',
      ),
      'buttons_color' => array(
        'background' => '.merimag-styled-button:not(.bordered-button), .button:not(.bordered-button), input[type=submit]:not(.bordered-button), input[type=button]:not(.bordered-button), input[type=reset]:not(.bordered-button), button[type=submit]:not(.bordered-button)',
        'color' => '.bordered-button, a.bordered-button',
      ),
      'buttons_color_text_color' => array(
            'color' => '.merimag-styled-button:not(.bordered-button), .button:not(.bordered-button), input[type=submit]:not(.bordered-button), input[type=button]:not(.bordered-button), input[type=reset]:not(.bordered-button), button[type=submit]:not(.bordered-button)',
          ),
      'from_background_color' => array(
        'background-color' => '.marged-infos, .content-background, .sub-menu, .mega-menu, .flexMenu-popup',
        'border-right-color' => '.merimag-box-filter.vertical-tabs ul.merimag-block-filters li.ui-state-active',
      ),
      'from_content_area_background_color' => array(
          'background' => '.content-area-background',
      ),

  );
  $selectors = array_merge($selectors, merimag_get_principal_color_selectors(), merimag_get_principal_color_text_color_selectors() );
  return $for ? ( isset( $selectors[$for] ) ? $selectors[$for] : false ) : $selectors;
}
/**
 * Get custom selector css
 *
 * @param string $select_this the name of the selector to be seached in custom selectors list
 * @param string $value the value to be applied
 * @return string css code
 */
function merimag_get_custom_selector_css( $select_this, $value, $location, $important = false ) {
  $style = '';
  $selectors = merimag_custom_slectors( $select_this );
  foreach( (array) $selectors as $property => $selector ) {
      $selector = $location . ' ' . $selector;
      $selector = str_replace(',', ', ' . $location, $selector );
      $important = $important === true ? '!important' : '';
      $style .= sprintf('%s { %s: %s%s } ', $selector, $property, $value, $important );
  }
 
  return $style;
}
/**
 * Get text color css of a specified area
 *
 * @param string $text_color valid css color
 * @param string $links_color valid css color
 * @param string $links_hover_color valid css color
 * @param string $location valid css selector
 * @return string css code
 */
function merimag_get_text_color_css( $text_color, $links_color, $links_hover_color, $location = 'body' ) {
    $style = '';
    if( isset( $text_color ) && !empty( $text_color ) ) {
      $style .= merimag_get_custom_selector_css('text_color', $text_color, $location );
    }
    if( isset( $links_color ) && !empty( $links_color ) ) {
      $style .= merimag_get_custom_selector_css('links_color', $links_color, $location );
    }
    if( isset( $links_hover_color ) && !empty( $links_hover_color ) ) {
      $style .= merimag_get_custom_selector_css('links_hover_color', $links_hover_color, $location );
    }

    return $style;
}
/**
 * Get block title style css
 *
 * @param string $area valid css selector
 * @param string $style valid block title style
 * @param string $color valid css color
 * @return string css code
 */
function merimag_get_block_title_css( $area = 'body', $style = false, $color = false ) {
  return;
    $color = !$color || !merimag_validate_color( $color ) ? merimag_get_principal_color() : $color;
    $text_color = merimag_get_text_color_from_background( $color );
    $block_title_styles = merimag_get_recognized_block_title_styles( true );
    if( $style !== false && !in_array( $style, $block_title_styles ) ) {
      return;
    }
    $css   = sprintf('%1$s .block-title-wrapper nav li a:hover, %1$s .block-title-wrapper nav li a:active, %1$s .block-title-wrapper nav li a:focus {color: %2$s; }%1$s .block-title-wrapper nav li.ui-state-active, %1$s .block-title-wrapper nav li a:hover, %1$s .block-title-wrapper nav li a:active, %1$s .block-title-wrapper nav li a:focus { color: %2$s; }', esc_attr( $area ), esc_attr( $color ));
    $selectors['principal_color']['background-color'] .= ', .block-title-wrapper.style-1 .block-title:before, .block-title-wrapper.style-2 .block-title:before, .block-title-wrapper.style-7, .block-title-wrapper.style-9 .block-title:before, .block-title-wrapper.style-5 .block-title, .block-title-wrapper.style-16';

    
    if( $style === false ) {
      foreach( $block_title_styles as $block_title_style ) {
        $css .= merimag_get_block_title_css( $area, $block_title_style, $color );
      }
    } else {
      switch ($style) {
        case 'style-1':
          $css .= sprintf('%1$s  {background: %2$s; color: %3$s }', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;
        case 'style-2':
          $css .= sprintf('%1$s  {background: %2$s; color: %3$s }', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;
        case 'style-3':
          $css .= sprintf('%1$s {border-color: %2$s; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-4':
          $css .= sprintf('%1$s  {border-bottom: 4px %2$s solid; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-5':
          $css .= sprintf('%1$s  {border-bottom: 4px %2$s solid; } %1$s  {background: %2$s; color: %3$s }', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;
        case 'style-6':
          $css .= sprintf('%1$s  {border-left: 7px %2$s solid; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-7':
          $css .= sprintf('%1$s  {background: %2$s; color: %3$s } %1$s  {border-top: 10px solid %2$s; }', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;
        case 'style-8':
           $css .= sprintf('%1$s  {color:%2$s } ', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;
        case 'style-9':
          $css .= sprintf('%1$s  {background:%2$s } %1$s  { border-bottom-color:%2$s } ', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;
        case 'style-10':
          $css .= sprintf('%1$s  {border-bottom: 3px %2$s dashed; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-11':
          $css .= sprintf('%1$s  {border:2px solid %2$s; } %1$s  {border-top: 2px solid %2$s; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-12':
          $css .= '';
          break;
        case 'style-13':
          $css .= sprintf('%1$s  {border: 1px solid %2$s; color: %2$s; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-14':
          $css .= sprintf('%1$s  {border-color: %2$s; }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-15':
          $css .= sprintf('%1$s  {border-top: 4px %2$s solid; } %1$s  { border-bottom-color:%2$s }', esc_attr( $area ), esc_attr( $color ));
          break;
        case 'style-16':
          $css .= sprintf('%1$s  {background: %2$s; color:%3$s } %1$s  {border-left-color: %2$s; border-right-color: %2$s; }', esc_attr( $area ), esc_attr( $color ), esc_attr( $text_color ));
          break;

      }
    }
    return $css;
}
/**
 * Get custom css selectors to be used to generate css
*/
function merimag_get_principal_color_selectors() {

  $css_propeties = array('background-color', 'color', 'border-right-color', 'border-left-color', 'border-top-color', 'border-bottom-color', 'border-color' );

  $selectors     = array();

  # principal

  foreach( $css_propeties as $property ) {
    $selectors['principal_color'][$property]  = sprintf('.principal-color-%s', $property );
    $selectors['principal_color'][$property] .= sprintf(', a.principal-color-%s', $property );
    $selectors['principal_color'][$property] .= sprintf(', a.principal-color-%s:visited', $property );
    $selectors['principal_color'][$property] .= sprintf(', a.principal-color-%s:active', $property );
    $selectors['principal_color'][$property] .= sprintf(', a.principal-color-%s:focus', $property );
    $selectors['principal_color'][$property] .= sprintf(', .block-infos-category.principal-color-%s', $property );
  }

  # general


  $selectors['principal_color']['background-color'] .= ', .merimag-media-shortcode .mejs-controls, .principal-color-background-color-span-hover:hover, .principal-color-background-color, .principal-color-background-color-hover:hover, .principal-color-background-color-span-hover:hover, .principal-color-background-color-a-hover > a:hover, li.active-menu-item > .principal-color-background-color-hover, li.active-menu-item > .principal-color-background-color-span-hover, li.active-menu-item > .principal-color-background-color-a-hover > a, .plyr .plyr__control.plyr__tab-focus, .plyr .plyr__control:hover, .plyr .plyr__control[aria-expanded=true], .plyr__control--overlaid, .merimag-styled-button:not(.bordered-button), .button:not(.bordered-button), input[type=submit]:not(.bordered-button), input[type=button]:not(.bordered-button), input[type=reset]:not(.bordered-button), button[type=submit]:not(.bordered-button), .page-numbers.current, .widget_price_filter .ui-slider .ui-slider-range, .widget_price_filter .ui-slider .ui-slider-handle, .comment-reply-link, #cancel-comment-reply-link, .merimag-button, .nav-previous a, .nav-next a, .onsale, .post-page-numbers.current, #wp-calendar caption, .mCS-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar, .merimag-review-score-display.circle, .quicktags-toolbar, .widget_display_stats dd, .merimag-box-filter.vertical-tabs ul.merimag-block-filters li.ui-state-active';
  $selectors['principal_color']['border-top-color'] .= '.principal-color-sub-menu-border-top-color .sub-menu, .principal-color-sub-menu-border-top-color .sub-menu, .wc-tabs li.ui-state-active, thead th, thead td';
  $selectors['principal_color']['border-color']     .= ', .merimag-page-link.active, .merimag-white-text-container, .post-author-label, .checkout_coupon.woocommerce-form-coupon, .woocommerce-checkout .order-details, .merimag-tabs-shortcode ul.merimag-tabs-shortcode-list li.ui-tabs-active, .vertical-menu .menu .mega-menu-row > .menu-item > a:after';
  $selectors['principal_color']['color']            .= ', .merimag-page-link.active, ul.page-numbers .current, .star-rating span:before, p.stars:hover a:before, p.stars.selected a.active:before, p.stars.selected a:not(.active):before, .plyr--full-ui input[type=range], .star-rating .star, .post-author-label, .merimag-block-order-number:before, .merimag-ticker-item:after';

  # block title

  $selectors['principal_color']['background-color'] .= ', .merimag-white-text-container, .sk-rotating-plane, .sk-double-bounce > .sk-child, .sk-wave > .sk-rect, .sk-wandering-cubes > .sk-cube, .sk-spinner, .sk-chasing-dots > .sk-child, .sk-three-bounce > .sk-child, .sk-circle .sk-child:before, sk-cube-grid > .sk-cube, .sk-fading-circle > .sk-circle:before, .sk-folding-cube > .sk-cube:before, .sk-circle-bounce .sk-child:before, .sk-cube-grid .sk-cube';

  # block title

  $selectors['principal_color']['color']                 .= ', .block-title-wrapper nav li a:hover, .block-title-wrapper nav li a:active, .block-title-wrapper nav li a:focus, .block-title-wrapper nav li.ui-state-active, .block-title-wrapper nav li a:hover, .block-title-wrapper nav li a:active, .block-title-wrapper nav li a:hover, .block-title-wrapper.style-8 .block-title, .block-title-wrapper.style-12';

  $selectors['principal_color']['background-color']      .= ', .block-title-wrapper.style-1 .block-title:before, .block-title-wrapper.style-2 .block-title:before, .block-title-wrapper.style-7, .block-title-wrapper.style-9 .block-title:before, .block-title-wrapper.style-5 .block-title, .block-title-wrapper.style-16, .block-title-wrapper.style-17 .block-title, .block-title-wrapper.style-17 .block-title:after, .block-title-wrapper.style-10 .block-title:before';

  $selectors['principal_color']['border-color']          .= ', .block-title-wrapper.style-3 .block-title, .block-title-wrapper.style-14, .block-title-wrapper.style-13, .block-title-wrapper.style-11 .block-title';
  $selectors['principal_color']['color']                 .= ', .block-title-wrapper.style-3 .block-title, .block-title-wrapper.style-14, .block-title-wrapper.style-13';
  $selectors['principal_color']['border-top-color']      .= ', .block-title-wrapper.style-4, .block-title-wrapper.style-15 .block-title:before, .block-title-wrapper.style-7 .block-title:before, .block-title-wrapper.style-11:after';
  $selectors['principal_color']['border-bottom-color']   .= ', .block-title-wrapper.style-5, .block-title-wrapper.style-9:after, .block-title-wrapper.style-10:after, .block-title-wrapper.style-15:before, .block-title-wrapper.style-10:before';  
  $selectors['principal_color']['border-left-color']     .= ', .block-title-wrapper.style-6';
  
  return $selectors;

}
/**
 * Get custom css selectors to be used to generate css
*/
function merimag_get_principal_color_text_color_selectors() {
  $selectors_text_color = array();

  $selectors_text_color['principal_color_text_color']['color'] = '.principal-color-background-color, .principal-color-background-color-hover:hover, .principal-color-background-color-span-hover:hover, .principal-color-background-color-a-hover > a:hover, li.active-menu-item > .principal-color-background-color-hover, li.active-menu-item > .principal-color-background-color-span-hover, li.active-menu-item > .principal-color-background-color-a-hover > a, .merimag-styled-button:not(.bordered-button), .button:not(.bordered-button), input[type=submit]:not(.bordered-button), input[type=button]:not(.bordered-button), input[type=reset]:not(.bordered-button), button[type=submit]:not(.bordered-button), .page-numbers.current, .comment-reply-link, #cancel-comment-reply-link, .merimag-button, .nav-previous a, .nav-next a, .onsale, .post-page-numbers.current, #wp-calendar caption, .merimag-review-score-display.circle, .quicktags-toolbar, .block-title-wrapper.style-16, .block-title-wrapper.style-16 *, .block-title-wrapper.style-5 .block-title, .merimag-box-filter.vertical-tabs ul.merimag-block-filters li.ui-state-active';


  return $selectors_text_color;
}
/**
 * Get principal color css
 *
 * @param string $principal_color valid css color
 * @param string $area valid css selector
 * @param bool $not_principal will not return all the css code if true to prevent duplicated code
 * @param string $block_title_style valid block title style
 * @return string css code
 */
function merimag_get_principal_color_css( $principal_color, $area = 'body', $force_body = true ) {

  $area = $force_body === true ? ( $area !== 'body' ? 'body.site-body ' . esc_attr( $area ) : 'body.site-body' ) : $area;

  if( !$principal_color || empty($principal_color)) {
     return;
  }
  $css = '';

  if( merimag_validate_color( $principal_color ) ) {
    $principal_color_text_color = merimag_get_text_color_from_background( $principal_color );
    $css .= merimag_get_custom_selector_css('principal_color', $principal_color, $area);
    $css .= merimag_get_custom_selector_css('principal_color_text_color', $principal_color_text_color, $area, true);
    $css .= merimag_get_custom_selector_css('links_hover_color', $principal_color,$area );
  }

  return $css;

}
/**
 * Get border style css
 *
 * @param string $location valid css selector
 * @param string $position valid css position
 * @param string $color valid css color
 * @param string $width valid css width
 * @param string $style valid css border style
 * @return string css code
 */
function merimag_get_border_style( $location, $position, $color, $width, $style ) {
  $css = '';
  $color = merimag_get_validated_color( $color );
  $width = is_numeric( $width ) && $width > 0 ? $width : 0;
  $style = in_array( $style, merimag_get_recognized_border_styles( true ) ) ? $style : false;
  $position = in_array( $position, merimag_get_recognized_posistions( true ) ) ? $position : false;
  if( !empty( $color ) && !empty( $width ) && !empty( $style ) ) {

    $css = sprintf( '%s { border-%s : %s %spx %s; }', $location, $position, $color, $width, $style );
  }
  return $css; 
}
/**
 * Get an suitable color for a specified background css color
 *
 * @param string $color valid css color
 * @param bool $dark_light
 * @return string dark or light or valid css color
 */
function merimag_get_text_color_from_background($color, $dark_light = false ) {
  if( !is_string($color) || !merimag_validate_color( $color, true ) ) {
     return;
  }
  $hex = ltrim($color, '#');
  if( hexdec(substr($hex,0,2))+hexdec(substr($hex,2,2))+hexdec(substr($hex,4,2))> 522){
      return $dark_light === false ? merimag_adjustBrightness( $color, -160 ) : 'light';
  } else{
      return $dark_light === false ? merimag_adjustBrightness( $color, 240 ) : 'dark';
  }
}
/**
 * Validate a css hex or rgba color
 *
 * @param string $input_value valid css color
 * @return bool true if color is valid
 */
function merimag_validate_color( $input_value, $hex = false ) {
  if( !is_string( $input_value ) ) {
     return false;
  }
  if( $hex === true && !preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $input_value ) ) {
    return false;
  }
  if( preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $input_value ) || preg_match( '/^rgba\( *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *(1|0|0?.\d+) *\)$/', $input_value )) {
    return true;
  } else {
    return false;
  }
}
/**
 * Return a validated color
 *
 * @param string $input_value valid css color
 * @return mixed the color if is valid or false if not
 */
function merimag_get_validated_color( $input_value ) {
  if( merimag_validate_color( $input_value ) === true ) {
    return $input_value;
  }
  return;
}
/**
 * Transform a Hex to rgba with opacity adjustment
 *
 * @param string $color valid css color
 * @param float $opacity opacity of rgba color
 * @return string the rgba color with opacity
 */
function merimag_hex2rgba($color, $opacity = false) {
 
  $default = 'rgb(0,0,0)';
 
  if(empty($color))
          return $default; 
 
  if ($color[0] == '#' ) {
    $color = substr( $color, 1 );
  }

  if (strlen($color) == 6) {
          $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
  } elseif ( strlen( $color ) == 3 ) {
          $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
  } else {
          return $default;
  }

  $rgb =  array_map('hexdec', $hex);

  if($opacity){
    $output  = 'rgba('.implode(",",$rgb).','.$opacity.')';
  } else {
    $output = 'rgb('.implode(",",$rgb).')';
  }

  //Return rgb(a) color string
  return $output;
}
/**
 * Color brichness adjustment
 *
 * @param string $hex valid css hex color
 * @param integer $steps should be between -255 and 255. Negative = darker, positive = lighter
 * @return string adjusted hexh color
 */
function merimag_adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}
/**
 * Average color between two colors
 *
 * @param string $color1 valid css hex color
 * @param string $color2 valid css hex color
 * @return valid css hex color
 */
function merimag_colorDiff($color1,$color2) {
    $color1=    ltrim($color1,'#');
    $color2=    ltrim($color2,'#'); 
    $red1 =     hexdec(substr($color1,0,2));
    $green1 =  hexdec(substr($color1,2,2));
    $blue1 =    hexdec(substr($color1,4,2));
    $red2 =     hexdec(substr($color2,0,2));
    $green2 =  hexdec(substr($color2,2,2));
    $blue2 =   hexdec(substr($color2,4,2));
    $red =  dechex(round(($red1+$red2)/2));
    $green =    dechex(round(($green1+$green2)/2));
    $blue =     dechex(round(($blue1+$blue2)/2));
    if (strlen($red) == 1) { $red = '0'.$red; }
    if (strlen($green) == 1) { $green = '0'.$green; }
    if (strlen($blue) == 1) { $blue = '0'.$blue; }
    $newcolor = '#'.$red.''.$green.''.$blue;
    return $newcolor;
}
/**
 * Get typography css
 *
 * @param arrat $args list of arguments
 * @param string $for a valid css selector 
 * @param string $size_from get text size from another option 
 * @return string css code
 */
function merimag_get_typgraphy_css( $args = array(), $for = 'body', $size_from = false ) {

  $css = '';

  $font_styles  = array( 'normal', 'italic', 'oblique');
  $font_weights = array( 'normal', 'bold', 'bolder', 'lighter');
  $transforms   = array( 'none', 'capitalize', 'lowercase', 'uppercase');
  $decorations  = array( 'underline', 'overline', 'line-through', 'underline overline');

  $defaults     = merimag_get_typography_defaults( $args, $for, $size_from );


  foreach( (array) $args as $key => $value ) {
    switch ($key) {
      case 'family':
        $css .= in_array( $value, merimag_get_recognized_font_families() ) ? 'font-family : ' . esc_attr( $value ) . '; ' : '';
        break;
      case 'style':
        $css .= in_array( $value, $font_styles ) ? 'font-style : ' . esc_attr( $value ) . '; ' : '';
        break;
      case 'weight':
        $css .= in_array( $value, $font_weights ) || ( is_numeric( $font_weights ) && $font_weights <= 900 ) ? 'font-weight : ' . esc_attr( $value ) . '; ' : '';
        break;
      case 'size':
        $css .= !empty( $value ) && !is_array( $value ) ? 'font-size : ' . esc_attr( $value ) . 'px; ' : 'font-size: ' . $defaults['size'] . 'px;';
        break;
      case 'line-height':
        $css .= !empty( $value ) && !is_array( $value ) ? 'line-height : ' . esc_attr( $value ) . '; ' : '';
        break;
      case 'letter-spacing':
        $css .= !empty( $value ) && !is_numeric( $value ) && $value > 0 ? 'letter-spacing : ' . esc_attr( $value ) . 'px; ' : '';
        break;
      case 'color':
        $css .= merimag_validate_color( $value ) ? 'color: ' . esc_attr( merimag_get_validated_color( $value ) ) . '; ' : '';
        break;
      case 'transform':
        $css .= in_array( $value, $transforms ) ? 'text-transform : ' . esc_attr( $value ) . '; ' : '';
        break;
      case 'decoration':
        $css .= in_array( $value, $decorations ) ? 'text-decoration : ' . esc_attr( $value ) . '; ' : '';
        break;
    }
  }

  return $css;
}
/**
 * Get typography defaults
 *
 * @param arrat $args list of arguments
 * @param string $for a valid theme location 
 * @param string $size_from get text size from another option 
 * @return array list of typography settings values
 */
function merimag_get_typography_defaults( $args = array(), $for = 'body', $size_from = ''  ) {

  $defaults = apply_filters( $for . '_typography', array(
    'family'         => 'Arial',
    'style'          => 'normal',
    'weight'         => $for === 'headings' ? '600' : '400',
    'size'           => $size_from ? merimag_get_text_size_defaults( false, $size_from ) : false,
    'line-height'    => false,
    'letter-spacing' => -1,
    'color'          => false,
    'transform'      => 'none',
    'decoration'     => false,
  ) );

  $ogf_load_fonts = get_theme_mod('ogf_load_fonts');

  $family = str_replace(' ', '-', $defaults['family']);
  if( !is_array($ogf_load_fonts) || !in_array( $family, $ogf_load_fonts ) ) {
      $ogf_load_fonts[] = $family;
      add_filter('theme_mod_ogf_load_fonts', function($fonts) use( $ogf_load_fonts ) {
        return $ogf_load_fonts;
      });
  }
  foreach( $defaults as $key => $value ) {
    $args[ $key ] = isset( $args[ $key ] ) && !empty( $args[ $key ] ) ? $args[ $key ] : $value;
  }
  return $args;
}
/**
 * Get text size defaults
 *
 * @param arrat $args list of arguments
 * @param string $for a valid theme location
 * @return array list of typography settings values
 */
function merimag_get_text_size_defaults( $args = array(), $for = 'body' ) {
  switch ( $for ) {
    case 'body':
    case 'paragraphs_listing':
      $size = 15;
      break;
    case 'paragraphs_single':
      $size = 18;
      break;
    case 'h1':
      $size = 40;
      break;
    case 'h2':
      $size = 30;
      break;
    case 'h3':
      $size = 22;
      break;
    case 'h4':
      $size = 18;
      break;
    case 'h5':
      $size = 15;
      break;
    case 'secondary_menu':
    case 'h6':
      $size = 12;
      break;
    case 'logo':
      $size = 40;
      break;
    default:
      $size = 15;
      break;
  }
  $defaults = apply_filters( $for . '_text_size', array(
    'size'           => $size,
  ) );
  if( empty( $args ) || $args === false ) {
    return $size;
  }
  foreach( $defaults as $key => $value ) {
    $args[ $key ] = isset( $args[ $key ] ) && !empty( $args[ $key ] ) ? $args[ $key ] : $value;
  }
  return $args;
}
/**
 * Get valid button css
 *
 * @param arrat $atts list of arguments
 * @param string $selector a valid css selector
 * @return string css code of a button
 */
function merimag_get_button_style( $atts = array(), $selector = '' ) {
  $css = ' body.site-body ' .  esc_attr( $selector ) . ' {';
  $primary_background         = isset( $atts['button_background_primary'] ) ? $atts['button_background_primary'] : false;
  $secondary_background       = isset( $atts['button_background_secondary'] ) ? $atts['button_background_secondary'] : false;
  $primary_hover_background   = isset( $atts['button_hover_background_primary'] ) ? $atts['button_hover_background_primary'] : false;
  $secondary_hover_background = isset( $atts['button_hover_background_secondary'] ) ? $atts['button_hover_background_secondary'] : false;
  $color                      = isset( $atts['button_color'] ) ? $atts['button_color'] : false;
  $hover_color                = isset( $atts['button_hover_color'] ) ? $atts['button_hover_color'] : false;
  $gradient_degree            = isset( $atts['button_background_degree'] ) && in_array( $atts['button_background_degree'], array('to bottom', 'to right') ) ? $atts['button_background']['degree'] : 'to bottom';
  $gradient_degree_hover      = isset( $atts['button_hover_background_degree'] ) && in_array( $atts['button_hover_background_degree'], array('to bottom', 'to right') ) ? $atts['button_hover_background_degree'] : $gradient_degree;
  $text_color                 = '#fff';
  $text_hover_color           = '#eee';

  if( merimag_validate_color( $primary_background )  && !in_array( $atts['button_style'], array( 'bordered', 'link') ) ) {
    $css .= sprintf( 'background-color: %s!important;', $primary_background );
  }
  if( merimag_validate_color( $primary_background )  && in_array( $atts['button_style'], array( 'bordered', 'link') ) ) {
    $css .= sprintf( 'border-color: %s!important;', $primary_background );
  }
  if( merimag_validate_color( $primary_background ) ) {
    $text_color = merimag_get_text_color_from_background( $primary_background );
  }
  if( merimag_validate_color( $primary_background ) && merimag_validate_color( $secondary_background ) ) {
    $text_color = merimag_get_text_color_from_background( merimag_colorDiff( $primary_background, $secondary_background ) );
  }
  if( merimag_validate_color( $primary_hover_background ) ) {
    $text_hover_color = merimag_get_text_color_from_background( $primary_hover_background );
  }
  if( merimag_validate_color( $primary_hover_background ) && merimag_validate_color( $secondary_hover_background ) ) {
    $text_hover_color = merimag_get_text_color_from_background( merimag_colorDiff( $primary_hover_background, $secondary_hover_background ) );
  }
  if( merimag_validate_color( $primary_background ) ) {
    $hover_color_generated =  merimag_adjustBrightness( $primary_background, -40);
    $hover_color_generated = $hover_color_generated === '#000000' ? merimag_adjustBrightness( $primary_background, 40) : $hover_color_generated;
  }
  if( !merimag_validate_color($primary_hover_background) && isset( $hover_color_generated ) ) {
    $primary_hover_background = $hover_color_generated;
  }
  if( merimag_validate_color( $color ) ) {
    $css .= sprintf( 'color: %s!important;', $color );
  } else {
    $css .= isset( $atts['button_style'] ) && in_array( $atts['button_style'], array( 'bordered', 'link') ) && merimag_validate_color( $primary_background ) ? sprintf( 'color: %s;', $primary_background ) : '';
    $css .= !isset( $atts['button_style'] ) || !in_array( $atts['button_style'], array('bordered', 'link') ) ? sprintf( 'color: %s;', $text_color ) : '';
  }

  if( merimag_validate_color( $primary_background ) && in_array( $atts['button_style'], array('three-d') )  ) {
    $css .= sprintf( 'background-image: linear-gradient( %s, %s, %s);', $gradient_degree, $primary_background, $primary_hover_background ); 
  }
  if( merimag_validate_color( $primary_background ) && merimag_validate_color( $secondary_background ) && $primary_background !== $secondary_background && !in_array( $atts['button_style'], array( 'bordered', 'link') ) ) {
    $css .= sprintf( 'background-image: linear-gradient( %s, %s, %s);', $gradient_degree, $primary_background, $secondary_background ); 
  }

  $css .= '}';

  
  $css .=  ' body.site-body ' .  esc_attr( $selector ) . ':hover {';
  if( merimag_validate_color( $primary_hover_background ) ) {
    if(  !in_array( $atts['button_style'], array( 'bordered', 'link') )) {
      $css .= sprintf( 'background-color: %s!important;', $primary_hover_background );
    } else {
      $css .= sprintf( 'border-color: %s!important;', $primary_hover_background );
      $css .= sprintf( 'color: %s!important;', $primary_hover_background );
    }
  } else {
    if( isset(  $hover_color_generated ) ) {
      if(  !in_array( $atts['button_style'], array( 'bordered', 'link') )) {
        $css .= sprintf( 'background-color: %s!important;', $hover_color_generated );
      } else {
        $css .= sprintf( 'border-color: %s!important;', $hover_color_generated );
        $css .= sprintf( 'color: %s!important;', $hover_color_generated );
      }
    }
  }
  if( merimag_validate_color( $hover_color ) ) {
    $css .= sprintf( 'color: %s!important;', $hover_color );
  } else {
    $css .= isset( $atts['button_style'] ) && !in_array( $atts['button_style'], array( 'bordered', 'link') ) ? sprintf( 'color: %s!important;', $text_hover_color ) : '';
  }
  if( merimag_validate_color( $primary_hover_background ) && merimag_validate_color( $secondary_hover_background) && $primary_hover_background !== $secondary_hover_background && !in_array( $atts['button_style'], array( 'bordered', 'link') ) ) {
    $css .= sprintf( 'background-image: linear-gradient( %s, %s, %s);', $gradient_degree_hover, $primary_hover_background, $secondary_hover_background );
  } else {
    if( merimag_validate_color( $primary_background ) && merimag_validate_color( $secondary_background) && $primary_background !== $secondary_background && !in_array( $atts['button_style'], array( 'bordered', 'link') ) ) {
      $css .= sprintf( 'background-image: linear-gradient( %s, %s, %s);', $gradient_degree_hover, $hover_color_generated, $secondary_background );
    }
  }
  $css .= '}';
  return $css;
}


/**
 * Get users data
 *
 * @param array $get the field to get
 * @return array list of users
 */
function merimag_get_users( $get = 'display_name' ) {
  $args = array(
    'who' => 'authors',
    'orderby' => 'post_count',
    'has_published_posts' => array( 'post' ),
    'order' => 'DESC'
  );
  $users_data = get_users( $args );
  $users = array();
  foreach( $users_data as $user ) {
    if( isset( $user->ID ) ) {
      $users[$user->ID] = isset( $user->data->{$get} ) ? $user->data->{$get} : $user->data;
    }
  }
  return $users;
}

/**
 * Extract link from a list of arguments
 *
 * @param array $args the list of arguments
 * @return string the link url 
 */
function merimag_extract_link_from_args( $args ) {
  $link = isset( $args['attr'] ) && is_string( $args['attr'] ) ? $args['attr'] : '';
  $link = isset( $args['url'] ) && is_string( $args['url'] ) ? $args['url'] : $link;
  return $link;
}
/**
 * Generate css spacing
 *
 * @param string $attr can be margin or padding
 * @return string valid css spacing 
 */
function merimag_generate_spacing_css( $attr, $data ) {
  if( !in_array( $attr, array('margin', 'padding') ) ) {
     return;
  }
  $style      = '';
  $positions    = array('top', 'right', 'bottom', 'left' );
  foreach ((array) $data as $position => $value ) {
    if( in_array( $position, $positions ) && is_numeric( $value ) ) {
      $style .= esc_attr( $attr) . '-' . esc_attr( $position ) . ' : ' . esc_attr( $value ) . 'px;';
    }
  }
  return $style;
}
function merimag_render_css( $css ) {
  if( !is_string($css) ) {
    return;
  }
  $css_tag  = 'sty' . 'le';
  echo '<' . esc_attr( $css_tag ) . '>';
  echo wp_specialchars_decode( wp_kses_post($css), ENT_QUOTES);
  echo '</' . esc_attr( $css_tag ) . '>';
}
/**
 * Get box css for elementor page preview
 *
 * @param array $atts list of arguments
 * @return void
 */
function merimag_get_box_css( $atts ) {
  $block_id = isset( $atts['block_id']) && is_string( $atts['block_id'] ) && !empty( $atts['block_id'] ) ? $atts['block_id'] : false;
  if( !$block_id  ) {
    return;
  }
  if( ( defined('DOING_AJAX') && DOING_AJAX ) || is_preview() || is_admin()  || isset( $atts['__fw_editor_shortcodes_id'] ) ) {
    $selector   = '#' . $block_id;
    $block_css  = isset( $atts ) && is_array( $atts ) ? merimag_get_dynamic_block_style( $atts, $selector ) : merimag_get_dynamic_block_style( 'general_block', $selector );
    merimag_render_css( $block_css );
  }
  return;
}
/**
 * Dynamic block css
 *
 * @param array $atts list of arguments
 * @param array $selector a valid css selector
 * @return string valid css for styling a block 
 */
function merimag_get_dynamic_block_style( $atts = false, $selector = '' ) {
  $atts = is_string( $atts ) ? merimag_get_general_block_atts( $atts ) : $atts;
  $ignore_style = isset( $atts['ignore_general_style'] ) && $atts['ignore_general_style'] === 'yes' ? true : false;
  if( isset( $atts['category'] ) && is_array( $atts['category'] ) && count( $atts['category'] ) === 1  ) {
    $category_principal_color = merimag_get_db_term_option( $atts['category'][0], 'category', 'category_principal_color');
    if( merimag_validate_color($category_principal_color) && ( !isset( $atts['principal_color'] ) || !merimag_validate_color( $atts['principal_color'] ) ) && $ignore_style === false ) {
      $atts['principal_color'] = $category_principal_color;
    }
  } 
  $css               = '';
  if( $selector ) {
      $open_tag        = esc_attr( $selector ) . ' { ';
  }
  $text_color        = isset( $atts['text_color'] ) && !empty( $atts['text_color'] ) ? $atts['text_color'] : false;
  $principal_color   = isset( $atts['principal_color'] ) ? $atts['principal_color'] : false;
  $links_color       = isset( $atts['links_color'] ) && !empty( $atts['links_color'] ) ? $atts['links_color'] : false;
  $links_hover_color = isset( $atts['links_hover_color'] ) && !empty( $atts['links_hover_color'] ) ? $atts['links_hover_color'] : ( $principal_color ? $principal_color : false );
  $borders_color     = isset( $atts['borders_color'] ) && !empty( $atts['borders_color'] ) ? $atts['borders_color'] : false;
  $buttons_color     = isset( $atts['buttons_color'] ) && !empty( $atts['buttons_color'] ) ? $atts['buttons_color'] : false;
  $background        = isset( $atts['background'] ) ? $atts['background']   : false;
  $border_width      = isset( $atts['border_width'] ) && is_numeric( $atts['border_width'] ) ? $atts['border_width'] : 0;
  $border_width      = isset( $atts['border_width'] ) && isset( $atts['border_width']['size'] ) ? $atts['border_width']['size'] : $border_width;
  $border_color      = isset( $atts['border_color'] ) ? $atts['border_color'] : false;
  $border_style      = isset( $atts['border_style'] ) ? $atts['border_style'] : 'solid';
  $padding           = isset( $atts['padding'] ) ? $atts['padding'] : array();
  $margin            = isset( $atts['margin'] ) ? $atts['margin']   : array();
  $css              .= merimag_generate_spacing_css( 'padding', $padding );
  $css              .= merimag_generate_spacing_css( 'margin', $margin );
  $css              .= merimag_get_background_css( $atts );
  
  if( $border_style === 'none' ) {
      $css              .= 'border:none;';
  } else {
    if( $border_width > 0 && $border_style !== 'default' && !empty( $border_style ) ) {
        if( $border_color ) {
            $css            .= 'border-color: ' . esc_attr( $border_color ) . ';';
        }
        $css            .= 'border-width: ' . esc_attr( $border_width ) . 'px;';
        $css            .= 'border-style: ' . esc_attr( $border_style ) . ';';
    }
  }
  

  if( $selector ) {
      $close_tag      = '}';
  }

  $css               = $css ? $open_tag . $css . $close_tag : '';

  $css              .= merimag_get_text_color_css( $text_color, $links_color, $links_hover_color, $selector );

  $css              .= $borders_color ? merimag_get_borders_color_css( $borders_color, $selector ) : '';

  $css              .= $buttons_color ? merimag_get_buttons_css( $buttons_color, $selector ) : '';

 

  if( isset( $atts['principal_color'] )  && merimag_validate_color( $atts['principal_color'] ) ) {
      $css .= merimag_get_principal_color_css( $atts['principal_color'], $selector, false );
  }
  if( $background ) {
      $css .= merimag_get_custom_selector_css('from_background_color', $background, $selector );
  }

  return $css;
}
/**
 * Dynamic block css
 *
 * @param array $atts list of arguments
 * @param array $selector a valid css selector
 * @return string valid css for styling a block 
 */
function merimag_get_dynamic_block_style_deprecated( $atts = false, $selector = '' ) {
  $atts = is_string( $atts ) ? merimag_get_general_block_atts( $atts ) : $atts;

  $css               = '';
  if( $selector ) {
      $open_tag        = esc_attr( $selector ) . ' { ';
  }
  $text_color        = isset( $atts['text_color'] ) && !empty( $atts['text_color'] ) ? $atts['text_color'] : false;
  $principal_color   = isset( $atts['principal_color'] ) ? $atts['principal_color'] : false;
  $links_color       = isset( $atts['links_color'] ) && !empty( $atts['links_color'] ) ? $atts['links_color'] : false;
  $links_hover_color = isset( $atts['links_hover_color'] ) && !empty( $atts['links_hover_color'] ) ? $atts['links_hover_color'] : ( $principal_color ? $principal_color : false );
  $borders_color     = isset( $atts['borders_color'] ) && !empty( $atts['borders_color'] ) ? $atts['borders_color'] : false;
  $buttons_color     = isset( $atts['buttons_color'] ) && !empty( $atts['buttons_color'] ) ? $atts['buttons_color'] : false;
  $background        = isset( $atts['background'] )   ? $atts['background']   : false;
  $border_width      = isset( $atts['border_width'] ) ? $atts['border_width'] : 0;
  $border_color      = isset( $atts['border_color'] ) ? $atts['border_color'] : false;
  $border_style      = isset( $atts['border_style'] ) ? $atts['border_style'] : 'solid';
  $padding           = isset( $atts['padding'] ) ? $atts['padding'] : array();
  $margin            = isset( $atts['margin'] ) ? $atts['margin']   : array();

  $css              .= merimag_generate_spacing_css( 'padding', $padding );
  $css              .= merimag_generate_spacing_css( 'margin', $margin );
  $css              .= merimag_get_background_css( $atts );
  

  if( $border_color ) {
      $css            .= 'border-color: ' . esc_attr( $border_color ) . ';';
  }

  if( $border_width > 0 && $border_style !== 'none' && $border_style !== 'default' ) {
      $css            .= 'border-width: ' . esc_attr( $border_width ) . 'px;';
      $css            .= 'border-style: ' . esc_attr( $border_style ) . ';';
  }
  if( $border_style === 'none' ) {
      $css            .= 'border: none;';
  }
  if( $selector ) {
      $close_tag      = '}';
  }

  $css               = $css ? $open_tag . $css . $close_tag : '';

  $css              .= merimag_get_text_color_css( $text_color, $links_color, $links_hover_color, $selector );

  $css              .= $borders_color ? merimag_get_borders_color_css( $borders_color, $selector ) : '';

  $css              .= $buttons_color ? merimag_get_buttons_css( $buttons_color, $selector ) : '';

  $block_title_style = isset( $atts['block_title_style'] ) && in_array($atts['block_title_style'], merimag_get_recognized_block_title_styles( true ) ) ? $atts['block_title_style'] : false;

  $title_filter      = isset( $atts['is_widget'] ) && $atts['is_widget'] === true ? 'merimag_block_title_style_widget' : 'merimag_block_title_style';

  $block_title_style = $block_title_style === 'default' ? apply_filters( $title_filter, $block_title_style ) : $block_title_style;

  if( isset( $atts['principal_color'] )  && merimag_validate_color( $atts['principal_color'] ) ) {
      $css .= merimag_get_principal_color_css( $atts['principal_color'], $selector, false );
  }
  if( $background ) {
      $css .= merimag_get_custom_selector_css('from_background_color', $background, $selector );
  }

  return $css;
}
/**
 * Get general block attributes from the customizer options be used with merimag_get_dynamic_block_style()
 *
 * @param array $general_block the area
 * @return array list of style attributes
 */
function merimag_get_general_block_atts( $general_block = 'general_block' ) {
  
  $general_style_settings  = array('general_block', 'general_widget', 'general_section', 'general_content_area', 'general_sidebar_area');

  $atts = array();

  $atts['text_color']            = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_text_color');
  $atts['principal_color']       = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_principal_color');
  $atts['links_color']           = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_links_color');
  $atts['links_hover_color']     = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_links_hover_color');
  $atts['borders_color']         = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_borders_color');
  $atts['buttons_color']         = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_buttons_color');
  $atts['background']            = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_color');
  $atts['background_gradient']   = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_gradient');
  $atts['background_image']      = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_image');
  $atts['background_repeat']     = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_repeat');
  $atts['background_attachment'] = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_attachment');
  $atts['background_position']   = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_position');
  $atts['background_size']       = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_background_size');
  $atts['border_width']          = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_border_width');
  $atts['border_color']          = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_border_color');
  $atts['border_style']          = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_border_style');
  $atts['padding']               = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_padding');
  $atts['margin']                = merimag_get_db_live_customizer_option(esc_attr( $general_block) . '_margin');

  return $atts;
}
/**
 * Get menu block attributes from the mega menu options be used with merimag_get_dynamic_block_style()
 *
 * @param array $general_block the area
 * @return array list of style attributes
 */
function merimag_get_menu_block_atts( $id ) {
  if( !defined( 'FW') ) {
     return;
  }
  $atts = array();

  $atts['text_color']            = fw_ext_mega_menu_get_db_item_option($id, 'row/text_color');
  $atts['principal_color']       = fw_ext_mega_menu_get_db_item_option($id, 'row/principal_color');
  $atts['links_color']           = fw_ext_mega_menu_get_db_item_option($id, 'row/links_color');
  $atts['links_hover_color']     = fw_ext_mega_menu_get_db_item_option($id, 'row/links_hover_color');
  $atts['borders_color']         = fw_ext_mega_menu_get_db_item_option($id, 'row/borders_color');
  $atts['buttons_color']         = fw_ext_mega_menu_get_db_item_option($id, 'row/buttons_color');
  $atts['background']            = fw_ext_mega_menu_get_db_item_option($id, 'row/background_color');
  $atts['background_gradient']   = fw_ext_mega_menu_get_db_item_option($id, 'row/background_gradient');
  $atts['background_image']      = fw_ext_mega_menu_get_db_item_option($id, 'row/background_image');
  $atts['background_repeat']     = fw_ext_mega_menu_get_db_item_option($id, 'row/background_repeat');
  $atts['background_attachment'] = fw_ext_mega_menu_get_db_item_option($id, 'row/background_attachment');
  $atts['background_position']   = fw_ext_mega_menu_get_db_item_option($id, 'row/background_position');
  $atts['background_size']       = fw_ext_mega_menu_get_db_item_option($id, 'row/background_size');
  $atts['border_width']          = fw_ext_mega_menu_get_db_item_option($id, 'row/border_width');
  $atts['border_color']          = fw_ext_mega_menu_get_db_item_option($id, 'row/border_color');
  $atts['border_style']          = fw_ext_mega_menu_get_db_item_option($id, 'row/border_style');
  $atts['padding']               = fw_ext_mega_menu_get_db_item_option($id, 'row/padding');
  $atts['margin']                = fw_ext_mega_menu_get_db_item_option($id, 'row/margin');

  return $atts;
}
/**
 * Normalize background css
 *
 * @param array $atts list of background attributes
 * @param string $selector a valid css selector
 * @return array list of style attributes
 */
function merimag_get_background_css( $atts, $selector = false ) {
  $style = '';

  if( $selector ) {
    $style = $selector . ' {';
  }

  if ( isset( $atts['background_gradient']['primary'] ) && ! empty( $atts['background_gradient']['primary'] ) && isset( $atts['background_gradient']['secondary'] ) && ! empty( $atts['background_gradient']['secondary'] ) ) {
    $gradient_degree  = isset( $atts['background_gradient']['degree'] ) && !empty( $atts['background_gradient']['degree'] ) && in_array( $atts['background_gradient']['degree'], array('to bottom', 'to right') ) ? $atts['background_gradient']['degree'] : 'to bottom';
    $bg_gradient      = 'linear-gradient(' . esc_attr( $gradient_degree  ) . ', ' . esc_attr( $atts['background_gradient']['primary'] ) . ', ' . esc_attr( $atts['background_gradient']['secondary'] ) . ')';
  }
  if( isset( $atts['background'] ) && !empty( $atts['background'] ) ) {
      $style         .= isset( $atts['background'] ) && !empty( $atts['background'] ) ? 'background-color: ' . esc_attr( $atts['background'] ) . ';' : '';
  }
  
  if ( isset( $atts['background_image'] ) &&  ! empty( $atts['background_image'] ) && isset( $atts['background_image']['url'] ) && ! empty( $atts['background_image']['url'] ) ) {
    $bg_image         = 'url(' . $atts['background_image']['url'] . ')';
  }
  if( isset( $bg_gradient) && isset( $bg_image ) ) {
    $bg_img_gradient  = $bg_gradient. ',' . $bg_image;
  }
  if( !isset( $bg_image ) && isset( $bg_gradient ) ) {
    $bg_image = $bg_gradient;
  }
  if( isset( $bg_image ) ) {
    $style           .= 'background-image: ' . esc_attr( $bg_image ) . ';';
    $style           .= isset( $bg_img_gradient ) ? 'background-image: ' . esc_attr( $bg_img_gradient ) . ';' : '';

    if ( isset( $atts['background_repeat'] ) &&  !empty( $atts['background_repeat'] ) && in_array( $atts['background_repeat'], merimag_get_recognized_background('background_repeat', true) ) ) {
      $style         .= sprintf( 'background-repeat: %s;', $atts['background_repeat'] );
    } else {
      $style         .= sprintf( 'background-repeat: %s;', 'no-repeat' );
    }
    if ( isset( $atts['background_position'] ) &&  !empty( $atts['background_position'] ) && in_array( $atts['background_position'], merimag_get_recognized_background('background_position', true) ) ) {
      $style         .= sprintf( 'background-position: %s;', $atts['background_position'] );
    } else {
      $style         .= sprintf( 'background-position: %s;', 'center center' );
    }
    if ( isset( $atts['background_size'] ) &&  !empty( $atts['background_size'] ) && in_array( $atts['background_size'], merimag_get_recognized_background('background_size', true) ) ) {
      $style         .= sprintf( 'background-size: %s;', $atts['background_size'] );
    } else {
      $style         .= sprintf( 'background-size: %s;', 'cover' );
    }
    if ( isset( $atts['background_attachment'] ) &&  !empty( $atts['background_attachment'] ) && in_array( $atts['background_attachment'], merimag_get_recognized_background( 'background_attachment', true) ) ) {
      $style         .= sprintf( 'background-attachment: %s;', $atts['background_attachment'] );
    } else {
      $style         .= sprintf( 'background-attachment: %s;', 'scroll' );
    }

  }

  if( $selector ) {
    $style .= $selector . ' }';
  }
  return $style;
}
/**
 * Paginate links
 *
 * @param array $args list pagination arguments
 * @return string pagination html
 */
function merimag_paginate_links( $args = array(), $atts = array() ) {
  global $wp_rewrite;
  if( is_array( $atts ) && !empty( $atts ) ) {
    $wp_query = new stdClass();
    $wp_query->max_num_pages = $atts['pages'];
  } else {
    global $wp_query;
  }
  

  // Setting up default values based on the current URL.
  $pagenum_link = html_entity_decode( get_pagenum_link() );
  $url_parts    = explode( '?', $pagenum_link );
  // Get max pages and current page out of the current query, if available.
  $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
  $current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
  // Append the format placeholder to the base URL.
  $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

  // URL base depends on permalink settings.
  $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
  $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

  $defaults = array(
    'base'               => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
    'format'             => $format, // ?page=%#% : %#% is replaced by the page number
    'total'              => $total,
    'current'            => $current,
    'aria_current'       => 'page',
    'show_all'           => false,
    'prev_next'          => true,
    'prev_text'          => is_rtl() ? '<i class="icofont-arrow-right"></i>' : '<i class="icofont-arrow-left"></i>',
    'next_text'          => is_rtl() ? '<i class="icofont-arrow-left"></i>' : '<i class="icofont-arrow-right"></i>',
    'end_size'           => 1,
    'mid_size'           => 2,
    'type'               => 'plain',
    'add_args'           => array(), // array of query args to add
    'add_fragment'       => '',
    'before_page_number' => '',
    'after_page_number'  => '',
  );

  $args = wp_parse_args( $args, $defaults );

  if ( ! is_array( $args['add_args'] ) ) {
    $args['add_args'] = array();
  }

  // Merge additional query vars found in the original URL into 'add_args' array.
  if ( isset( $url_parts[1] ) ) {
    // Find the format argument.
    $format = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
    $format_query = isset( $format[1] ) ? $format[1] : '';
    wp_parse_str( $format_query, $format_args );

    // Find the query args of the requested URL.
    wp_parse_str( $url_parts[1], $url_query_args );

    // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
    foreach ( $format_args as $format_arg => $format_arg_value ) {
      unset( $url_query_args[ $format_arg ] );
    }

    $args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
  }

  // Who knows what else people pass in $args
  $total = (int) $args['total'];
  if ( $total < 2 ) {
    return;
  }
  $current  = (int) $args['current'];
  $end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
  if ( $end_size < 1 ) {
    $end_size = 1;
  }
  $mid_size = (int) $args['mid_size'];
  if ( $mid_size < 0 ) {
    $mid_size = 2;
  }
  $add_args = $args['add_args'];
  $r = '';
  $page_links = array();
  $dots = false;

  if ( $args['prev_next'] && $current && 1 < $current ) :
    $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
    $link = str_replace( '%#%', $current - 1, $link );
    if ( $add_args )
      $link = add_query_arg( $add_args, $link );
    $link .= $args['add_fragment'];

    /**
     * Filters the paginated links for the given archive pages.
     *
     * @since 3.0.0
     *
     * @param string $link The paginated link URL.
     */
    $page_links[] = '<li class="prev-page-link"><a class="prev merimag-page-link page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a></li>';
  endif;
  for ( $n = 1; $n <= $total; $n++ ) :
    if ( $n == $current ) :
      $page_links[] = "<li><span aria-current='" . esc_attr( $args['aria_current'] ) . "' class='page-numbers merimag-page-link active current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</span></li>";
      $dots = true;
    else :
      if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
        $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
        $link = str_replace( '%#%', $n, $link );
        if ( $add_args )
          $link = add_query_arg( $add_args, $link );
        $link .= $args['add_fragment'];

        /** This filter is documented in wp-includes/general-template.php */
        $page_links[] = "<li><a class='page-numbers  merimag-page-link ' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a></li>";
        $dots = true;
      elseif ( $dots && ! $args['show_all'] ) :
        $page_links[] = '<li><span class="page-numbers merimag-page-link  dots">&hellip;</span></li>';
        $dots = false;
      endif;
    endif;
  endfor;
  if ( $args['prev_next'] && $current && $current < $total ) :
    $link = str_replace( '%_%', $args['format'], $args['base'] );
    $link = str_replace( '%#%', $current + 1, $link );
    if ( $add_args )
      $link = add_query_arg( $add_args, $link );
    $link .= $args['add_fragment'];

    /** This filter is documented in wp-includes/general-template.php */
    $page_links[] = '<li class="next-page-link"><a class="next page-numbers  merimag-page-link " href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a></li>';
  endif;
  switch ( $args['type'] ) {
    case 'array' :
      return $page_links;

    case 'list' :
      $r .= "<ul class='page-numbers'>\n\t<li>";
      $r .= join("</li>\n\t<li>", $page_links);
      $r .= "</li>\n</ul>\n";
      break;

    default :
      $r = join("\n", $page_links);
      break;
  }
  return '<ul class="page-numbers">' . $r . '</ul>';
}
/**
 * Posts navigation
 *
 * @param array $args list pagination arguments
 * @return string navigation html
 */
function merimag_posts_navigation( $args = array() ) {
  $navigation = '';

  // Don't print empty markup if there's only one page.
  if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
    $args = wp_parse_args( $args, array(
      'prev_text'          => '<i class="fa fa-angle-double-left"></i>&nbsp;' . __( 'Older posts', 'merimag' ),
      'next_text'          =>  __( 'Newer posts', 'merimag' ) . '&nbsp;<i class="fa fa-angle-double-right"></i>',
      'screen_reader_text' => __( 'Posts navigation', 'merimag' ),
    ) );

    $next_link = get_previous_posts_link( $args['next_text'] );
    $prev_link = get_next_posts_link( $args['prev_text'] );

    if ( $prev_link ) {
      $navigation .= '<div class="nav-previous">' . $prev_link . '</div>';
    }

    if ( $next_link ) {
      $navigation .= '<div class="nav-next">' . $next_link . '</div>';
    }
    $navigation .= '<div class="merimag-clear clear"></div>';

    $navigation = _navigation_markup( $navigation, 'posts-navigation', $args['screen_reader_text'] );
  }

  return $navigation;
}
/**
 * Normalize sliding data to use in slider shortcodes
 *
 * @param array $block_data list of selected options
 * @return array list of valid sliding data
 */
function merimag_get_sliding_data( $block_data ) {

  $slick_data['visible_slides']    = isset( $block_data['grid_columns'] ) ? $block_data['grid_columns'] : 2;
  $slick_data['sliding_slides']    = isset( $block_data['sliding_columns'] ) ? $block_data['sliding_columns'] : 1;
  $slick_data['sliding_slides']    = $slick_data['sliding_slides'] > $slick_data['visible_slides'] ? $slick_data['visible_slides'] : $slick_data['sliding_slides'];
  $slick_data['show_dots']         = isset( $block_data['show_dots'] ) ? $block_data['show_dots'] : false;
  $slick_data['show_arrows']       = isset( $block_data['show_arrows'] ) ? $block_data['show_arrows'] : true;
  $slick_data['auto_play']         = isset( $block_data['auto_play'] ) ? $block_data['auto_play'] : false;
  $slick_data['center_mode']       = isset( $block_data['center_mode'] ) ? $block_data['center_mode'] : false;
  $slick_data['center_padding']    = isset( $block_data['center_padding'] ) ? $block_data['center_padding'] : 0;
  $slick_data['auto_play_speed']   = isset( $block_data['auto_play_speed'] ) ? $block_data['auto_play_speed'] : 3;
  $slick_data['fade']              = isset( $block_data['fade'] ) ? $block_data['fade'] : false;
  $slick_data['speed']             = isset( $block_data['speed'] ) ? $block_data['speed'] : 300;
  $slick_data['infinite']          = isset( $block_data['infinite'] ) ? $block_data['infinite'] : true;
  $grid_spacing                    = isset( $block_data['grid_spacing'] ) && in_array($block_data['grid_spacing'], merimag_get_recognized_grid_spacing(true) ) ? $block_data['grid_spacing'] : 'default';
  switch ($grid_spacing) {
    case 'default':
      $slick_data['spacing'] = 10;
      break;
    case 'tiny':
      $slick_data['spacing'] = 2;
      break;
    case 'small':
      $slick_data['spacing'] = 5;
      break;
    case 'medium':
      $slick_data['spacing'] = 8;
      break;
    case 'extended':
      $slick_data['spacing'] = 15;
      break;
    case 'wide':
      $slick_data['spacing'] = 20;
      break;
    case 'big':
      $slick_data['spacing'] = 24;
      break;
    default:
      $slick_data['spacing'] = 10;
      break;
  }
  return $slick_data;
}
/**
 * Transform array with key value to html attributes
 *
 * @param array $data of data
 * @param bool $data_slug if true "data-" will be added before attributes 
 * @return string valid html attributes with values
 */
function merimag_array_to_html_attributes( $data, $data_slug = true ) {
  $html = '';
  $slug = $data_slug === true ? 'data-' : '';
  foreach( (array) $data as $key => $value ) {
    $html .= esc_attr( $slug ) . esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
  }
  return $html;
}
/**
 * Get block data
 *
 * @param array $args list of arguments
 * @param string $page if valid wordpress page specified arguments will be extracted from customizer
 * @return array valid data to be used in post and product blocks shortcodes
 */
function merimag_get_block_data( $args, $page = false ) {
  $elements = array();
  if( isset( $args['orderby'] ) && $args['orderby'] == 'price' ) {
    $args['orderby']  = 'meta_value_num';
    $args['meta_key'] = '_price';
  }
  if( isset( $args['orderby'] ) && $args['orderby'] == 'popularity' ) {
    $args['orderby']  = 'meta_value_num';
    $args['meta_key'] = 'total_sales';
    $args['order'] = 'DESC'; 
  }
  if( isset( $args['orderby'] ) && $args['orderby'] == 'review_score' ) {
    $args['orderby']  = 'meta_value_num';
    $args['meta_key'] = 'review_score';
  }
  if( isset( $args['orderby'] ) && $args['orderby'] == 'rating' ) {
    $args['orderby']  = 'meta_value_num';
    $args['meta_key'] = $args['post_type'] === 'product' ? '_wc_average_rating' : 'rating';
  }
  if( isset($args['orderby'] ) && $args['orderby'] == 'views' ) {
    $df     = class_exists('WPMDM') ? 'yes' : 'no';
    $fake_post_views = merimag_get_db_customizer_option('fake_post_views', $df );
    if( $fake_post_views === 'yes' ) {
      $args['orderby']  = 'meta_value_num';
      $args['meta_key'] = 'fake_views';
    } else {
      $args['orderby']  = 'meta_value_num';
      $args['meta_key'] = 'views';
    }
  }

  if( isset( $args['period'] ) ) {
    switch ( $args['period'] ) {
      case 'year':
        $args['year'] = date('Y');
        break;
      case 'month':
        $args['year']  = date('Y');
        $args['month'] = date('m');
        break;
      case 'week':
        $args['year']  = date('Y');
        $args['month'] = date('m');
        $args['w'] = date('W');
        break;
      case 'today':
        $args['year']  = date('Y');
        $args['month'] = date('m');
        $args['w'] = date('W');
        $args['day'] = date('d');
        break;
    }
  }

  global $wpmdm;

  $all_data = get_transient( 'merimag_cache_query_data' . merimag_get_demo_slug() );
  $key_args = $args;
  if( !is_array($args) ) {
    global $wp_query;
    if( isset( $wp_query->query_vars ) ) {
      $key_args = $wp_query->query_vars;
    } else {
      $key_args['paged'] = get_query_var('paged');
      $key_args['orderby'] = 'date';
      $key_args['order'] = 'desc';
    }
  }
  $enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
  $data_key = json_encode($key_args);

  $data = isset( $all_data[$data_key] ) ? $all_data[$data_key] : false;
  if( isset( $key_args['orderby'] ) && $key_args['orderby'] === 'rand' && is_array( $data ) ) {
    shuffle( $data['elements'] );
  }
  
  if( !is_array( $data ) || $enable_cache !== 'yes' ) {
    $data = array();
    if( is_array( $args ) ) {
      $query = new WP_Query( $args );
      $posts = $query->posts;
    }
    if( is_array( $args ) && $posts  ) {
      foreach( $posts as $post ) {
        $post = get_post($post->ID);
        $content = apply_filters('webte_content_filter', $post->post_content, $post->ID );
        $elements[] = (object) array(
          'type' =>  $args['post_type'],
          'ID' => $post->ID,
          'link' => function_exists('is_amp_endpoint') && is_amp_endpoint() && function_exists( 'amp_get_permalink' ) ? amp_get_permalink($post->ID) : get_the_permalink($post->ID),
          'thumbnail_id' => get_post_thumbnail_id($post->ID),
          'title' => get_the_title($post->ID) ? get_the_title($post->ID) : sprintf(__('Post #%s', 'merimag'), get_the_ID($post->ID) ), 
          'img' => merimag_get_the_post_thumbnail_url($post->ID), 
          'description' => wp_trim_words($content, 120, '...'),
          'class' => get_post_class('', $post->ID),
        );
      }
    } 
    if( is_home() || is_archive() || is_search() ) {
      if( !is_array( $args ) && have_posts() ) {
        while ( have_posts() ) : the_post();
          $elements[] = (object) array(
            'type' =>  $args['post_type'],
            'ID' => get_the_ID(),
            'link' => get_the_permalink(), 
            'title' => get_the_title() ? get_the_title() : sprintf(__('Post #%s', 'merimag'), get_the_ID() ), 
            'img' => merimag_get_the_post_thumbnail_url(),
            'thumbnail_id' => get_post_thumbnail_id(),
            'description' => get_the_excerpt(),
            'class' => get_post_class(),
          );
        endwhile;
      }
    }
    wp_reset_postdata();
    global $wp_query;

    $query                  = isset( $query ) ? $query : $wp_query;
    $data['count']          = $query->post_count;
    $data['posts_per_page'] = $query->query_vars['posts_per_page'] ;
    $data['total']          = $query->found_posts;
    $data['pages']          = ceil( $query->found_posts / $query->query_vars['posts_per_page'] );
    $data['offset']         = $query->offset;
    $data['elements']       = $elements;
    $all_data[$data_key]    = $data;
    if( $enable_cache === 'yes' ) {
      set_transient( 'merimag_cache_query_data' . merimag_get_demo_slug(), $all_data );
    }
  }
  return $data;
}
/**
 * Reset cache data on post save
 *
 * @param integer $post_id
 * @return void
 */
function merimag_reset_cached_data( $post_id ) {
  do_action('webte_purge_cache');
}
add_action( 'save_post', 'merimag_reset_cached_data' );
/**
 * Get block data for taxonomy
 *
 * @param array $tax_query taxonomy arguments
 * @return array valid data to be used in taxonomy blocks shortcodes
 */
function merimag_get_block_data_for_taxonomy( $atts = array() ) {
  $taxonomy = isset( $atts['post_type'] ) && taxonomy_exists( $atts['post_type'] ) ? $atts['post_type'] : 'category';
  $parent = is_array( $atts['parent'] ) && isset( $atts['parent'][0] ) ? $atts['parent'][0] : $atts['parent'];
  $terms = get_terms( array(
    'taxonomy' => $taxonomy,
    'parent' => is_numeric( $parent ) ? $parent : false,
    'include' => isset( $atts['include'] ) && is_array( $atts['include'] ) ? $atts['include'] : array(),
    'exclude' => isset( $atts['exclude'] ) && is_array( $atts['exclude'] ) ? $atts['exclude'] : array(),
    'hide_empty' => false,
  ) );
  foreach( $terms as $term ) {  
    $elements[] = (object) array(
      'type' =>  'taxonomy',
      'ID' => $term->term_id,
      'link' => get_term_link( $term->term_id ), 
      'title' => $term->name,
      'before_title' => $term->count, 
      'img' => merimags_get_the_category_thumbnail_url( $term->term_id, $taxonomy ),
      'thumbnail_id' => merimag_themes_get_the_category_thumbnail_id( $term->term_id, $taxonomy ),
      'description' => term_description( $term->term_id, $taxonomy ),
      'class' => sprintf('%s-%s', $term->taxonomy, $term->slug),
    );
  }
  $data['elements'] = isset( $elements ) ? $elements : array();
  return $data;
}
/**
 * Get block data for custom content
 *
 * @param array $data custom content arguments
 * @param array $grid custom content arguments
 * @return array valid data to be used in taxonomy blocks shortcodes
 */
function merimag_get_custom_block_data( $data, $grid = false ) {
  $elements = array();
  $block_id      = isset( $data['block_id'] ) ? $data['block_id'] : merimag_uniqid('merimag-element-');
  $data_elements = isset( $data['elements'] ) && is_array( $data['elements']) ? $data['elements'] : array();
  foreach( (array) $data_elements as $k => $element ) {
    $add                 = array();
    $add['ID']           = count( $data_elements ) > 1 ? $block_id . '-' . $k : $block_id;

    $add['title']        = isset( $element['title'] ) ? $element['title'] : '';
    $add['content']      = isset( $element['content'] ) ? $element['content'] : '';
    $add['sub_title']    = isset( $element['sub_title'] ) ? $element['sub_title'] : '';
    $add['before_title'] = isset( $element['before_title'] ) ? $element['before_title'] : '';
    $add['description']  = isset( $element['description'] ) ? $element['description'] : '';
    $add['link']         = isset( $element['link'] ) ? merimag_extract_link_from_args( $element['link'] ) : false;
    $add['img']          = isset( $element['image']['url'] ) ? $element['image']['url'] : false;
    $add['button_text']  = isset( $element['button_text'] ) ? $element['button_text'] : '';
    $add['thumbnail_id'] = isset( $element['image']['attachment_id'] ) ? $element['image']['attachment_id'] : false;
    $add['thumbnail_id'] = isset( $element['image']['id'] ) ? $element['image']['id'] : $add['thumbnail_id'];
    if( isset( $element['custom_title_box'] ) && $element['custom_title_box'] === 'yes' ) {
      $add['infos_position']    = isset( $element['infos_position'] ) && in_array( $element['infos_position'],  merimag_get_recognized_infos_positions( true )  ) ? $element['infos_position'] : 'left-bottom';

      $add['centered_infos']    = isset( $element['centered_infos'] ) && ( $element['centered_infos'] === true ||  $element['centered_infos'] === 'yes' ) ? 'yes' : 'no';

      $add['size']              = isset( $element['title_size'] ) && is_string( $element['title_size']  ) ? $element['title_size'] : 'big';


      $add['sub_title_size']    = isset( $element['sub_title_size'] ) && is_string( $element['sub_title_size'] ) ? $element['sub_title_size'] : 'medium';

      $add['infos_width']       = isset( $element['infos_width'] ) && is_numeric( $element['infos_width'] ) ? $element['infos_width'] : 'auto';

      $add['full_height_infos'] = isset( $element['full_height_infos'] ) && ( $element['full_height_infos'] === true ||  $element['full_height_infos'] === 'yes' ) ? $element['full_height_infos'] : false;

      $add['title_box_style']   = isset( $element['title_box_style'] ) && in_array( $element['title_box_style'], merimag_get_recognized_title_box_styles( true )  ) ? $element['title_box_style'] : 'simple';

      $add['slabtext']          = isset( $element['slabtext'] ) && ( $element['slabtext'] === true ||  $element['slabtext'] === 'yes' ) ? true : false;
    }

    $add['animation']         = isset( $element['custom_entrance_animation'] ) && is_string( $element['custom_entrance_animation'] ) && $element['custom_entrance_animation'] !== 'default' ? $element['custom_entrance_animation'] : 'default';
    $add['animation_speed']         = isset( $element['custom_animation_speed'] ) && is_string( $element['custom_animation_speed'] ) ? $element['custom_animation_speed'] : 'default';
    $add['atts'] = $element;
    $add['atts']['block_id'] = count( $data_elements ) > 1 ? $block_id . '-' . $k : $block_id;
    $elements[]  = (object) $add;
  }
  return $elements;
}
/**
 * Load next box data only ajax calls
 *
 * @return void
 */
function merimag_block_load_next() {

  merimag_check_ajax_referer( 'merimag_options', 'nonce' );
  $atts              = json_decode( str_replace('-quote-', '"', stripcslashes( $_POST['atts']) ), true );
  $block_style       = isset( $atts['block_style'] ) && is_string( $atts['block_style']  ) ? str_replace('-', '_', $atts['block_style'] ): false;
  $post_type         = isset( $atts['post_type'] ) && post_type_exists( $atts['post_type'] ) ? $atts['post_type'] : 'post';
  
  $page              = intval( $_POST['page'] );
  $block             = intval( $_POST['block'] );
  $show_posts        = isset( $atts['number'] ) && is_numeric( $atts['number'] ) ? $atts['number'] : get_option('posts_per_page');
  $offset            = ( $page - 1 ) * $show_posts;
  $atts['offset']    = $offset;
  $atts['block_data'] = merimag_get_box_args( $atts );

  $query_keys        = array('order_by', 'order');
  $query             = isset( $atts ) && is_array( $atts ) && merimag_array_keys_exists( $atts, $query_keys ) ? merimag_validate_query( $post_type, $atts ) : false;

  $atts['sliding_data']      = merimag_get_sliding_data( $atts['block_data']  );
  $atts['slick_data']        = merimag_array_to_html_attributes( $atts['sliding_data'] );

  if( is_array( $query ) ) {

    $query['offset'] = $offset;
    $data            = merimag_get_block_data( $query, false, true );
    $elements        = $data['elements'];

  } else {

    $query           = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $query['paged']  = $page;
    $data            = merimag_get_block_data( $query, false, true );
    $elements        = $data['elements'];

  }

  $atts['load_next']     = true;

  if( function_exists('merimag_blocks_' . $block_style) ) {

      $block_function = 'merimag_blocks_' . $block_style;

      $block_function( $elements, $atts );
  }


  if( function_exists('rwd_print_styles') ) {
    rwd_print_styles();
  }
  if( isset( $_POST['atts'] ) ) {
      wp_die();
  }
}
add_action( 'wp_ajax_merimag_block_load_next', 'merimag_block_load_next' );
add_action( 'wp_ajax_nopriv_merimag_block_load_next', 'merimag_block_load_next' );
/**
 * Get box pagination
 *
 * @param array $data list of arguments
 * @param string $pagination pagination style load_more, next_prev, newer_older, pagination or ajax_pagination
 * @return void
 */
function merimag_get_block_pagination( $data = array(), $pagination = 'default' ) {
  if( $pagination === 'default' ) {
    return;
  }
  $block_id   = $data['id'];
  $page       = $data['page'];
  $next_class = intval( $page ) === intval( $data['pages'] ) ? ' merimag-button-disabled' : '';
  $prev_class = intval( $page ) === 1 ? ' merimag-button-disabled' : '';
  $class = $pagination === 'next_prev' ? 'next-prev-pagination' : '';
  $class = $pagination === 'pagination' ? 'numeric-pagination' : '';
  echo sprintf('<div class="merimag-block-buttons merimag-pagination-buttons general-border-color %s">', esc_attr( $class ) );
  switch ($pagination) {
    case 'load_more':
    case 'infinite_scroll':
      echo '<div class="merimag-load-more-container"><a href="#" data-id="' . esc_attr( $block_id ) . '" class="merimag-load-next principal-color-background-color merimag-load-more merimag-full-button merimag-button ' . esc_attr( $next_class ) . '">' . esc_html__('Load more', 'merimag') .'<span class="merimag-loading-icon"><i class="animate-spin icon-spinner1"></i></span></a></div>';
      break;
    case 'next_prev':
      echo '';
      ?>
      <a href="#" data-id="<?php echo esc_attr( $block_id )?>" class="merimag-load-prev  merimag-pagination-button <?php echo esc_attr( $prev_class )?>">
        <?php echo esc_html__('Prev', 'merimag')?>
        <span class="merimag-loading-icon"><i class="animate-spin icon-spinner1"></i></span>
      </a>
      <a href="#" data-id="<?php echo esc_attr( $block_id )?>" class="merimag-load-next  merimag-pagination-button <?php echo esc_attr( $next_class )?>">
        <?php echo esc_html__('Next', 'merimag')?>
        <span class="merimag-loading-icon"><i class="animate-spin icon-spinner1"></i></span>
      </a>
      <span  data-id="<?php echo esc_attr( $block_id )?>" class="merimag-button-next-to-button">
        <?php echo esc_html__('Showing ', 'merimag')?>
        <span data-id="<?php echo esc_attr( $block_id )?>" class="merimag-filter-page-current">1</span>
        <?php echo esc_html__('Of ', 'merimag')?>
        <span class="merimag-filter-page-total"><?php echo esc_attr( $data['pages'])?></span>
      </span>
      <?php
      break;
    case 'ajax_pagination':
      echo '<div data-page="' . esc_attr( $page ) . '" data-per-page="' . esc_attr( $data['posts_per_page'] ) . '" data-total="' . esc_attr( $data['pages'] ) . '" data-id="' . esc_attr( $block_id ) . '" class="merimag-block-pagination"></div>';
      break;
    case 'pagination':
      echo merimag_paginate_links();
      break;
    case 'block_pagination':
      echo merimag_paginate_links( false, $data );
      break;
    case 'newer_older':
      echo merimag_posts_navigation();
      break;
    default:
      # code...
      break;
  }
  echo '</div>';

}
/**
 * Get block title
 *
 * @param string $title
 * @param string $link the url
 * @param string $before_title html before title
 * @param string $after_title html after title
 * @param integer $title_length maximum number of characters
 * @param integer $ellipsis maximum number of lines
 * @return void
 */
function merimag_get_title( $title, $link, $before_title = '', $after_title = '', $title_length = false, $ellipsis = false ) {
  echo '<div class="block-infos-title-content">';

  $title_full   = $title;
  $before_title = $before_title ? sprintf('<div class="merimag-before-title">%s</div>', $before_title ) : '';
  $after_title  = $after_title ? sprintf('<div class="merimag-after-title">%s</div>', $after_title ) : '';
  $title        = $title_length === false ? $title : merimag_substr( strip_tags( $title ), 0, $title_length );
  $class        = is_numeric( $ellipsis ) && $ellipsis > 0 && $ellipsis < 6 ? ' merimag-line-clamp merimag-line-clamp-' . $ellipsis : '';
  $title        = sprintf( '%5$s<h4 class="block-infos-title"><a class="%6$s  title-display" title="%3$s" href="%1$s">%2$s</a></h4>%4$s', $link, $title, strip_tags( $title_full ), $after_title, $before_title, $class );
  echo wp_specialchars_decode( esc_html( $title ), ENT_QUOTES );
  echo '</div>';
}
/**
 * Return a defined lenght of characters of a given string
 *
 * @param string $text
 * @param string $start character from where start couping 
 * @param string $lenght maximum lenght of characters
 * @return string 
 */
function merimag_substr( $text = '', $start = 0, $lenght = 80 ) {
  if( strlen( $text ) <= $lenght ) {
    return $text;
  } else {
    return mb_substr( $text, $start, $lenght ) . '...';
  }
}
/**
 * Validate block arguments
 *
 * @param object $element
 * @param array $block_data
 * @return array validated arguments 
 */
function merimag_get_block_args( $element, $block_data ) {
  $id                            = isset( $element->ID ) ? $element->ID : false;
  $args                          = array();
  $args['height']                = isset( $block_data['height'] ) && is_numeric( $block_data['height'] ) ? $block_data['height'] : 'auto';
  $args['image_size']                = isset( $block_data['image_size'] ) ? $block_data['image_size'] : 'default';
  $args['width']                 = isset( $block_data['width'] ) && is_numeric( $block_data['width'] ) ? $block_data['width'] : false;
  $args['size']                  = isset( $block_data['size'] ) && is_string( $block_data['size'] ) ? $block_data['size'] : 'medium';
  $args['force_size']            = isset( $block_data['force_size'] ) && $block_data['force_size'] === true ? true : false;
  $args['sub_title_size']        = isset( $block_data['sub_title_size'] ) && is_string( $block_data['sub_title_size'] ) ? $block_data['sub_title_size'] : 'small';

  $args['before_title']          = isset( $block_data['before_title'] ) && is_string( $block_data['before_title'] ) ? $block_data['before_title'] : '';
  $args['before_title']          = isset( $element->before_title ) && is_string( $element->before_title) ? $element->before_title : $args['before_title'] ;
  $args['after_title']           = isset( $block_data['after_title'] ) && $id !== false && is_string( $block_data['after_title'] ) ? merimag_meta_info( $block_data['after_title'], $id ) : '';
  $args['top_left']              = isset( $block_data['top_left'] ) && !is_array( $block_data['top_left'] ) ? $block_data['top_left'] : '';
  $args['top_right']             = isset( $block_data['top_right'] ) && is_string( $block_data['top_right'] ) ? $block_data['top_right'] : '';
  $args['bottom_left']           = isset( $block_data['bottom_left'] ) && is_string( $block_data['bottom_left'] ) ? $block_data['bottom_left'] : '';
  $args['bottom_right']          = isset( $block_data['bottom_right'] ) && is_string( $block_data['bottom_right'] ) ? $block_data['bottom_right'] : '';
  $args['image_ratio']           = isset( $block_data['image_ratio'] ) ? $block_data['image_ratio'] : '9-16';
  $args['center_center']         = isset( $block_data['center_center'] ) && is_string( $block_data['center_center'] ) ? $block_data['center_center'] : '';
  $args['title_length']          = isset( $block_data['title_length'] ) && is_numeric( $block_data['title_length'] ) ? $block_data['title_length'] : false;
  $args['title_ellipsis']        = isset( $block_data['title_ellipsis'] ) && is_numeric( $block_data['title_ellipsis'] ) ? $block_data['title_ellipsis'] : false;
  $args['description_length']    = isset( $block_data['description_length'] ) && is_numeric( $block_data['description_length'] ) ? $block_data['description_length'] : 145;
  $args['description_ellipsis']  = isset( $block_data['description_ellipsis'] ) && is_numeric( $block_data['description_ellipsis'] ) ? $block_data['description_ellipsis'] : false;
  $args['hover_description']     = isset( $block_data['hover_description'] ) && is_bool( $block_data['hover_description'] ) ? $block_data['hover_description'] : false;
  $args['show_description']      = isset( $block_data['show_description'] ) && is_bool( $block_data['show_description'] ) ? $block_data['show_description'] : false;
  $args['show_number']           = isset( $block_data['show_number'] ) && is_bool( $block_data['show_number'] ) ? $block_data['show_number'] : false;

  $args['show_read_more']        = isset( $block_data['show_read_more'] ) && is_bool( $block_data['show_read_more'] ) ? $block_data['show_read_more'] : false;
  $args['show_count']             = isset( $block_data['show_count'] ) && is_bool( $block_data['show_count'] ) ? $block_data['show_count'] : false;
  $args['show_category']         = isset( $block_data['show_category'] ) && is_bool( $block_data['show_category'] ) ? $block_data['show_category'] : true;
  $args['full_height_infos']     = isset( $block_data['full_height_infos'] ) && is_bool( $block_data['full_height_infos'] ) ? $block_data['full_height_infos'] : false;

  $args['show_sale']             = isset( $block_data['show_sale'] ) && is_bool( $block_data['show_sale'] ) ? $block_data['show_sale'] : false;
  $args['show_review']           = isset( $block_data['show_review'] ) && is_bool( $block_data['show_review'] ) ? $block_data['show_review'] : false;
  $args['review_display_style']  = isset( $block_data['review_display_style'] ) && in_array( $block_data['review_display_style'], array('default', 'circle', 'stars', 'bar', 'simple')) ? $block_data['review_display_style'] : 'default';
  $args['show_add_to_cart']      = isset( $block_data['show_add_to_cart'] ) && is_bool( $block_data['show_add_to_cart'] ) ? $block_data['show_add_to_cart'] : false;

  $args['show_format_icon']      = isset( $block_data['show_format_icon'] ) && is_bool( $block_data['show_format_icon'] ) ? $block_data['show_format_icon'] : false;
  $args['slabtext']              = isset( $block_data['slabtext'] ) && ( $block_data['slabtext'] === true ||  $block_data['slabtext'] === 'yes' ) ? true : false;

  $args['infos_style']           = 'bellow';
  $args['class']                 = isset( $element->class ) ? merimag_get_block_class( $element->class ) : '';
  $args['centered_infos']        = isset( $block_data['centered_infos'] ) && ( $block_data['centered_infos'] === true ||  $block_data['centered_infos'] === 'yes' ) ? true : false;
  $args['infos_position']        = isset( $block_data['infos_position'] ) && in_array( $block_data['infos_position'],  merimag_get_recognized_infos_positions( true )  ) ? $block_data['infos_position'] : 'left-bottom';
  $args['infos_content_width']   = false;

  $args['beside_title']          = '';
  $args['fullwidth']             = isset( $block_data['fullwidth'] ) && $block_data['fullwidth'] === true ? true : false;
  $args['infos_width']           = isset( $block_data['infos_width'] ) && is_numeric( $block_data['infos_width'] ) ? $block_data['infos_width'] : 'auto';
  $args['absolute_image']        = isset( $block_data['absolute_image'] ) && ( $block_data['absolute_image'] === 'yes' || $block_data['absolute_image'] === true ) ? true : false;
  $args['animation']             = isset( $block_data['animation'] ) && is_string( $block_data['animation'] ) ? $block_data['animation'] : '';
  $args['animation_speed']             = isset( $block_data['animation_speed'] ) && is_string( $block_data['animation_speed'] ) ? $block_data['animation_speed'] : '';

  $args['title_box_style']       = isset( $block_data['title_box_style'] ) && in_array( $block_data['title_box_style'], merimag_get_recognized_title_box_styles( true ) ) ? $block_data['title_box_style'] : 'simple';
  $args['title_box_background']  = isset( $block_data['title_box_background'] ) && !empty( $block_data['title_box_background'] ) ? $block_data['title_box_background'] : false;
  $args['offset']                = isset( $block_data['offset'] ) && is_numeric( $block_data['offset'] ) ? $block_data['offset'] : 0;
  $args['separator']                = isset( $block_data['separator'] ) && $block_data['separator'] === true ? true : false;
  $args['border_block']                = isset( $block_data['border_block'] ) && $block_data['border_block'] === true ? true : false;
  $args['color_layer']           = isset( $block_data['color_layer'] ) && $block_data['color_layer'] === true ? true : false;

  # element related data

  $args['button_text']           = isset( $element->button_text ) && !empty( $element->button_text ) && is_string( $element->button_text ) ? $element->button_text : false;

  $args['centered_infos']        = isset( $element->centered_infos ) && ( $element->centered_infos === false ||  $element->centered_infos === 'yes' ) ? true : $args['centered_infos'];
  $args['centered_infos']        = isset( $element->centered_infos ) && ( $element->centered_infos === 'no' ) ? false : $args['centered_infos'];
  $args['infos_position']        = isset( $element->infos_position ) && in_array( $element->infos_position,  merimag_get_recognized_infos_positions( true )  ) ? $element->infos_position : $args['infos_position'];
  $args['size']                  = isset( $element->size ) && is_string( $element->size ) ? $element->size : $args['size'];
  $args['force_size']            = isset( $element->force_size ) && is_string( $element->force_size ) ? $element->force_size : $args['force_size'];

  $args['sub_title_size']        = isset( $element->sub_title_size ) && is_string( $element->sub_title_size ) ? $element->sub_title_size : $args['sub_title_size'];
  $args['infos_width']           = isset( $element->infos_width ) && is_numeric( $element->infos_width ) ? $element->infos_width : $args['infos_width'];
  $args['full_height_infos']     = isset( $element->full_height_infos ) && is_bool( $element->full_height_infos ) ? $element->full_height_infos : $args['full_height_infos'];
  $args['full_height_infos']     = isset( $element->full_height_infos ) && ( $element->full_height_infos === true ||  $element->full_height_infos === 'yes' ) ? true : $args['full_height_infos'];
  $args['full_height_infos']     = isset( $element->full_height_infos ) && ( $element->full_height_infos === 'no' ) ? false : $args['full_height_infos'];
  $args['title_box_style']       = isset( $element->title_box_style ) && in_array( $element->title_box_style, merimag_get_recognized_title_box_styles( true ) ) ? $element->title_box_style : $args['title_box_style'];
  $args['slabtext']              = isset( $element->slabtext ) && ( $element->slabtext === true ||  $element->slabtext === 'yes' ) ? true : $args['slabtext'];
  $args['slabtext']              = isset( $element->slabtext ) && ( $element->slabtext === 'no' ) ? false : $args['slabtext'];
  $args['title_box_background']  = isset( $element->title_box_background ) && !empty( $element->title_box_background ) ? $element->title_box_background : $args['title_box_background'];
  $args['marged_infos']          = false;

  $args['animation']             = isset( $element->animation ) && is_string( $element->animation ) && $element->animation !== 'Default' ? $element->animation : $args['animation'];
  $args['animation_speed']             = isset( $element->animation_speed ) && is_string( $element->animation_speed ) && $element->animation_speed !== 'default' ? $element->animation_speed : $args['animation_speed']; 
  $args                          = merimag_get_block_args_with_title_box_args( $args['title_box_style'], $args );

  return $args;
}
/**
 * Helper function for merimag_get_block_args() 
 *
 * @param string $title_box_style the style of the block title box
 * @param array $args list of arguments
 * @return array validated arguments 
 */
function merimag_get_block_args_with_title_box_args( $title_box_style = 'simple', $args = array() ) {

  switch ($title_box_style) {
    case 'dark-background':
      $args['dark_infos_back']  = true;
      break;
    case 'dark-background-border-top':
      $args['dark_infos_back']  = true;
      $args['big_border_top_infos']  = true;
      break;
    case 'white-background':
      $args['white_infos_back'] = true;
      $args['block_white_text'] = false;
      break;
    case 'bordered':
      $args['borderd_infos']    = true;
      break;
    case 'simple':
      $args['dark_infos_back']  = false;
      $args['white_infos_back'] = false;
      $args['borderd_infos']    = false;
      break;
  }
  return $args;
}
/**
 * Validated box arguments to use in blocks inside a box
 *
 * @param array $atts list of attributes to validate
 * @return array validated arguments 
 */
function merimag_get_box_args( $atts ) {
  $block_data['predefined_grid_style'] = isset( $atts['predefined_grid_style'] ) ? $atts['predefined_grid_style'] : 'default';

  $block_data['grid_style']           = isset( $atts['grid_style'] ) ? $atts['grid_style'] : 'simple';
  $block_data['grid_columns']         = isset( $atts['columns'] ) ? $atts['columns'] : 2;
  $block_data['sliding_columns']      = isset( $atts['sliding_columns'] ) ? $atts['sliding_columns'] : 1;
  $block_data['grid_spacing']         = isset( $atts['spacing'] ) ? $atts['spacing'] : 'default';
  $block_data['grid_masonry']         = isset( $atts['masonry'] ) && ( $atts['masonry'] === 'yes' || $atts['masonry'] === true ) ? true : false;
  $block_data['button_text']          = isset( $atts['button_text'] ) && !empty( $atts['button_text'] ) && is_string( $atts['button_text'] ) ? $atts['button_text'] : false;
  $block_data['before_title']          = isset( $atts['before_title'] ) && !empty( $atts['before_title'] ) && is_string( $atts['before_title'] ) ? $atts['before_title'] : false;
  
  $block_data['top_left']             = isset( $atts['top_left'] ) && !is_array( $block_data['top_left'] ) ? $block_data['top_left'] : '';
  $block_data['top_right']            = isset( $block_data['top_right'] ) && is_string( $block_data['top_right'] ) ? $block_data['top_right'] : '';
  $block_data['bottom_left']          = isset( $block_data['bottom_left'] ) && is_string( $block_data['bottom_left'] ) ? $block_data['bottom_left'] : '';
  $block_data['bottom_right']         = isset( $block_data['bottom_right'] ) && is_string( $block_data['bottom_right'] ) ? $block_data['bottom_right'] : '';
  $block_data['center_center']        = isset( $block_data['center_center'] ) && is_string( $block_data['center_center'] ) ? $block_data['center_center'] : '';
  $block_data['height']         = isset( $atts['image_height'] ) && is_numeric( $atts['image_height'] ) ? $atts['image_height'] : 'auto';
  $block_data['image_size']         = isset( $atts['image_size'] ) ? $atts['image_size'] : 'default';
  $block_data['height']         = isset( $atts['image_height']['size'] ) && is_numeric( $atts['image_height']['size']  ) ? $atts['image_height']['size'] : $block_data['height'];
  $block_data['image_ratio']        = isset( $atts['image_ratio'] ) ? $atts['image_ratio'] : '9-16';
  $block_data['infos_width']        = isset( $atts['infos_width'] ) && is_numeric( $atts['infos_width'] ) && $atts['infos_width'] > 0 && $atts['infos_width'] <= 100 ? $atts['infos_width'] : 'auto';
  $block_data['infos_width']      = isset( $atts['infos_width']['size'] ) && is_numeric( $atts['infos_width']['size'] ) ? $atts['infos_width']['size'] : $block_data['infos_width'];

  $block_data['after_title']          = isset( $atts['after_title'] ) ? $atts['after_title'] : '';
  $block_data['show_description']     = isset( $atts['show_description'] ) && ( $atts['show_description'] === true ||  $atts['show_description'] === 'yes' ) ? true : false;
  $block_data['hover_description']     = isset( $atts['hover_description'] ) && ( $atts['hover_description'] === true ||  $atts['hover_description'] === 'yes' ) ? true : false;
  $block_data['show_number']     = isset( $atts['show_number'] ) && ( $atts['show_number'] === true ||  $atts['show_number'] === 'yes' ) ? true : false;

  $block_data['show_read_more']       = isset( $atts['show_read_more'] ) && ( $atts['show_read_more'] === true ||  $atts['show_read_more'] === 'yes' ) ? true : false;
   $block_data['show_count']       = isset( $atts['show_count'] ) && ( $atts['show_count'] === true ||  $atts['show_count'] === 'yes' ) ? true : false;
  $block_data['show_category']      = isset( $atts['show_category'] ) && ( $atts['show_category'] === true ||  $atts['show_category'] === 'yes' ) ? true : false;
  $block_data['show_sale']        = isset( $atts['show_sale'] ) && ( $atts['show_sale'] === true ||  $atts['show_sale'] === 'yes' ) ? true : false;
  $block_data['show_review']        = isset( $atts['show_review'] ) && ( $atts['show_review'] === true ||  $atts['show_review'] === 'yes' ) ? true : false;
  $block_data['review_display_style']        = isset( $atts['review_display_style'] ) && in_array( $atts['review_display_style'], array('default', 'circle', 'stars', 'bar', 'simple')) ? $atts['review_display_style'] : 'default';
  $block_data['show_add_to_cart']     = isset( $atts['show_add_to_cart'] ) && ( $atts['show_add_to_cart'] === true ||  $atts['show_add_to_cart'] === 'yes' ) ? true : false;
  $block_data['show_format_icon']     = isset( $atts['show_format_icon'] ) && ( $atts['show_format_icon'] === true ||  $atts['show_format_icon'] === 'yes' ) ? true : false;
  $block_data['slabtext']         = isset( $atts['slabtext'] ) && ( $atts['slabtext'] === true ||  $atts['slabtext'] === 'yes' ) ? true : false;

  $block_data['centered_infos']       = isset( $atts['centered_infos'] ) && ( $atts['centered_infos'] === true ||  $atts['centered_infos'] === 'yes' ) ? true : false;
  $block_data['size']             = isset( $atts['title_size'] ) && in_array( $atts['title_size'],  merimag_get_recognized_title_sizes( true )  ) ? $atts['title_size'] : 'medium';
  $block_data['force_size']             = isset( $atts['force_size'] ) && $atts['force_size'] === true ? true : false;
  $block_data['sub_title_size']     = isset( $atts['sub_title_size'] ) && in_array( $atts['sub_title_size'],  merimag_get_recognized_title_sizes( true )  ) ? $atts['sub_title_size'] : 'small';
  $block_data['infos_position']     = isset( $atts['infos_position'] ) && in_array( $atts['infos_position'],  merimag_get_recognized_infos_positions( true )  ) ? $atts['infos_position'] : 'left-bottom';
  $block_data['full_height_infos']    = isset( $atts['full_height_infos'] ) && ( $atts['full_height_infos'] === true ||  $atts['full_height_infos'] === 'yes' ) ? true : false;

  $block_data['title_box_style']    = isset( $atts['title_box_style'] ) && in_array( $atts['title_box_style'], merimag_get_recognized_title_box_styles( true ) ) ? $atts['title_box_style'] : 'simple';

  $block_data['title_box_background'] = isset( $atts['title_box_background'] ) && !empty( $atts['title_box_background'] ) ? $atts['title_box_background'] : false;

  $block_data['color_layer'] = isset( $atts['color_layer'] ) && $atts['color_layer'] === 'yes' ? true : false;


  $block_data['fullwidth']          = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === true ? $atts['fullwidth'] : false;


  $block_data['title_length']         = isset( $atts['title_length'] ) && is_numeric( $atts['title_length'] ) ? $atts['title_length'] : false;
  $block_data['title_ellipsis']     = isset( $atts['title_ellipsis'] )  && is_numeric( $atts['title_ellipsis'] ) ? $atts['title_ellipsis'] : false;
   $block_data['description_length']         = isset( $atts['description_length'] ) && is_numeric( $atts['description_length'] ) ? $atts['description_length'] : false;
  $block_data['description_ellipsis']     = isset( $atts['description_ellipsis'] )  && is_numeric( $atts['description_ellipsis'] ) ? $atts['description_ellipsis'] : false;


  $block_data['auto_play']          = isset( $atts['auto_play'] ) && ( $atts['auto_play'] === 'yes' || $atts['auto_play'] === true ) ? true : false;
  $block_data['center_mode']          = isset( $atts['center_mode'] ) && ( $atts['center_mode'] === 'yes' || $atts['auto_play'] === true ) ? true : false;
  $block_data['center_padding']          = isset( $atts['center_padding'] ) && is_numeric($atts['center_padding'] ) && $atts['center_padding'] > 0 && $atts['center_padding'] < 50 ? $atts['center_padding'] : false;
  $block_data['auto_play_speed']    = isset( $atts['auto_play_speed'] ) && is_numeric( $atts['auto_play_speed'] ) ? $atts['auto_play_speed'] : 3;
  $block_data['auto_play_speed']    = isset( $atts['auto_play_speed']['size'] ) && is_numeric( $atts['auto_play_speed']['size'] ) ? $atts['auto_play_speed']['size'] : $block_data['auto_play_speed'];
  $block_data['show_dots']          = isset( $atts['show_dots'] ) && ( $atts['show_dots'] === 'yes' || $atts['show_dots'] === true ) ? true : false;
  $block_data['show_arrows']          = isset( $atts['show_arrows'] ) && ( $atts['show_arrows'] === 'yes' || $atts['show_arrows'] === true ) ? true : false;
  $block_data['fade']             = isset( $atts['fade'] ) && $atts['fade'] === 'yes' ? true : false;
  $block_data['infinite']           = isset( $atts['infinite'] ) && ( $atts['infinite'] === 'yes' || $atts['infinite'] === true ) ? true : false;
  
  $block_data['speed']        = isset( $atts['speed'] ) && is_numeric( $atts['speed'] ) ? $atts['speed'] : 300;
  $block_data['animation']      = isset( $atts['animation'] ) && is_string( $atts['animation'] ) ? $atts['animation'] : '';
  $block_data['animation']      = isset( $atts['entrance_animation'] ) && is_string( $atts['entrance_animation'] ) ? $atts['entrance_animation'] : '';
  $block_data['animation_speed']      = isset( $atts['animation_speed'] ) && is_string( $atts['animation_speed'] ) ? $atts['animation_speed'] : '';
  $block_data['duration']       = isset( $atts['duration'] ) && is_numeric( $atts['duration'] ) ? $atts['duration'] : 300;
  $block_data['duration']       = isset( $atts['duration']['size'] ) && is_numeric( $atts['duration']['size'] ) ? $atts['duration']['size'] : $block_data['duration'];
  $block_data['offset']       = isset( $atts['offset'] ) && is_numeric( $atts['offset'] ) ? $atts['offset'] : 0;
  $block_data['separator'] = isset( $atts['separator'] ) && ( $atts['separator'] === 'yes' || $atts['separator'] === true ) ? true : false;
  $block_data['border_block'] = isset( $atts['border_block'] ) && ( $atts['border_block'] === 'yes' || $atts['border_block'] === true ) ? true : false;
  return $block_data;
}
/**
 * Transform array of classes to string separed with spacing
 *
 * @param array $class list of classes
 * @return string list of classes
 */
function merimag_get_block_class( $class ) {
  $classes = '';
  if( is_array( $class ) ) {
    foreach( $class as $c ) {
      $classes .= is_string( $c ) ? $c : '';
      $classes .= ' ';
    }
  }
  return $classes;
}
/**
 * Validate block style class
 *
 * @param string $block name of block style
 * @return string validated block style class
 */
function merimag_validate_block_class( $block = '' ) {
  $blocks = array('right', 'right-classic', 'left', 'left-classic', 'right-flex', 'left-flex', 'left-right-classic', 'bellow', 'above', 'absolute', 'text' );
  if( in_array( $block, $blocks ) ) {
    return $block;
  }
  return 'bellow';
}
/**
 * Display multiple meta infos parsed from a string separed with |
 *
 * @param string $parse string that contains list of meta infos separed with |
 * @param integer $id the post ID
 * @param bool $multi_line if true the meta infos will be displayed one per line
 * @return void
 */
function merimag_meta_info( $parse = '', $id = false, $multi_line = false ) {
  $id = $id === false ? get_the_ID() : $id;
  $meta_infos = get_transient('merimag_cache_meta_infos_' . $id  . merimag_get_demo_slug());
  $enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
  $check_meta = isset( $meta_infos[$parse] ) ? $meta_infos[$parse] : false;
  if( ( $check_meta || $check_meta === 'false' ) && $enable_cache === 'yes' )  {
    $html = $check_meta == 'false' ? '' : $check_meta;
    return $html;
  } else {
    $elements = explode('|', $parse );
    if( $parse === 'price|product_rating' ) {
      $multi_line = true;
    }
    $meta_class = $multi_line === true ? 'merimag-block-infos-meta-multiline' : 'merimag-block-infos-meta-inline';
    $begin      = strpos($parse, 'format_icon') === false && strpos($parse, 'add_to_cart') === false ? '<span class="merimag-block-infos-meta ' . esc_attr( $meta_class ) . '">' : '';
    $html       = '';
    $date = '<a href="' . esc_url( get_the_permalink( $id ) ) . '"><span>' . get_the_date( '', $id ) . '</span></a>';
    $post_review  = merimag_get_review_score( $id, 'stars', true);
    foreach ( $elements as $element ) {
      switch ($element) {
        case 'category_count':
          $term  = get_term($id, 'category');
          $html .= sprintf(__('%s posts', 'merimag'), $term->count );
          break;
        case 'product_cat_count':
          $term  = get_term($id, 'product_cat');
          $html .= sprintf(__('%s products', 'merimag'), $term->count );
          break;
        case 'format_icon':
          $format = get_post_format( $id );
          $format_img = MERIMAG_CORE_URL . '/assets/img/' . $format . '.png';
          switch ($format) {
            case 'video':
              $html .= '<a class="merimag-format-icon format-video" href="' . esc_url( get_the_permalink( $id ) ) . '"><img alt="' . esc_attr( $format ) . '" src="' . esc_url( $format_img ) .'"></a>';
              break;
          }
          break;
        case 'date':
          $html .= $date;
          break;
        case 'date_icon':
          $html .= '<a href="' . esc_url( get_the_permalink( $id ) ) . '"><i class="fa fa-clock-o"></i><span>' . get_the_date( '', $id ) . '</span></a>';
          break;
        case 'date_icon_no_link':
          $html .= '<span href="' . esc_url( get_the_permalink( $id ) ) . '"><i class="fa fa-clock-o"></i><span>' . get_the_date( '', $id ) . '</span></span>';
          break;
        case 'date_on':
          $html .= '<span>' . esc_html__('Posted on', 'merimag') . '</span><a href="' . esc_url( get_the_permalink( $id ) ) . '"><span>' . get_the_date( '', $id ) . '</span></a>';
          break;
        case 'author':
          $author_id = get_post_field('post_author', $id);
          $html .= '<a href="' .  get_author_posts_url( $author_id ) . '"><span>' . get_the_author_meta('display_name', $author_id) . '</span></a>';
          break;
        case 'author_icon':
          $author_id = get_post_field('post_author', $id);
          $html .= '<i class="fa fa-user"></i><a href="' .  get_author_posts_url( $author_id ) . '"><span>' . get_the_author_meta('display_name', $author_id) . '</span></a>';
          break;
        case 'author_upper':
          $author_id = get_post_field('post_author', $id);
          $html .= '<a class="author-upper" href="' .  get_author_posts_url( $author_id ) . '"><span>' . get_the_author_meta('display_name', $author_id) . '</span></a>';
          break;
        case 'author_image':
          $author_id = get_post_field('post_author', $id);
          $html     .=  '<img alt="' . esc_attr( get_the_author_meta('display_name', $author_id) ) . '" src="' . esc_url( get_avatar_url( $author_id, 32, get_the_author_meta('display_name') ) ) . '" class="merimag-meta-avatar"><a href="' .  get_author_posts_url( $author_id ) . '"><span>' . get_the_author_meta('display_name', $author_id) . '</span></a>';
          break;
        case 'author_by':
          $author_id = get_post_field('post_author', $id);
          $html .=  '<span>' . esc_html__('By', 'merimag') . '</span><a href="' .  get_author_posts_url( $author_id ) . '"><span>' . get_the_author_meta('display_name', $author_id ) . '</span></a>';
          break;
        case 'views':
          $views = merimag_get_post_views( $id );
          $badge = merimag_get_post_views_badge_color( $views );
          $icon  = 'icofont-eye-alt';
          $class = sprintf('%s-color-color', $badge);
          if( $views && is_numeric( $views ) ) {
            $html .=  sprintf('<span class="merimag-block-infos-views %s"><i class="%s"></i> %s</span>', esc_attr( $class ), esc_attr($icon), number_format_i18n( esc_attr( $views ) ) );
          }
          break;
        case 'categories':
          $categories_object = get_the_category( $id );
          $i = 0;
          $html .= '<div class="block-infos-multi-categories">';
          foreach( $categories_object as $category ) {
            if( $i >= 3 ) {
              break;
            }
            $html .= '<a class="block-infos-category ignore-general-principal-color principal-color-background-color category-' . esc_attr($category->slug) . ' principal-color-background" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_attr( $category->name ) . '</a>';
            
            $i++;
          }
          $html .= '</div>';
          break;
        case 'product_categories':
          $categories = wp_get_post_terms( $id, 'product_cat',array('fields'=>'ids') );
          $i = 0;
          foreach( $categories as $category ) {
            $category = get_term( $category );
            if( $i > 3 ) {
              break;
            }
            $html .= '<a class="block-infos-category ignore-general-principal-color category-' . esc_attr($category->slug) . '" href="' . esc_url( get_term_link( $category->term_id ) ) . '">' . esc_attr( $category->name ) . '</a>';
            $i++;
          }
          break;
        case 'category':
          $categories_object = get_the_category( $id );
          $i = 0;
          foreach( $categories_object as $category ) {
            if( $i > 0 ) {
              break;
            }
            $html .= '<a class="block-infos-category ignore-general-principal-color principal-color-background-color category-' . esc_attr($category->slug) . '" href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_attr( $category->name ) . '</a>';
            $i++;
          }
          break;
        case 'comments':
          $html   .= '<a href="' . get_comments_link( $id ) . '" class="merimag-block-infos-comments"><i class="icofont-comment"></i>' . get_comments_number( $id ) . '</a>';
          break;
        case 'comments_text':
          $html   .= '<a href="' . get_comments_link( $id ) . '" class="merimag-block-infos-comments-text">' . get_comments_number( $id ) . ' ' . esc_html__('Comments', 'merimag') . '</a>';
          break;
        case 'review':
          $html   .= $post_review;
          break;
        case 'date-review':
          $html .= $post_review ? $post_review : $date;
          break;
        case 'product_category':
          $categories = get_the_terms( $id, 'product_cat' );
          $i = 0;
          foreach( $categories as $category ) {
            $category = get_term( $category );
            if( $i > 0 ) {
              break;
            }
            $html .= '<a class="block-infos-category-text" href="' . esc_url( get_term_link( $category->term_id ) ) . '">' . esc_attr( $category->name ) . '</a>';
            $i++;
          }
          break;
        case 'price':
          $product = wc_get_product( $id );
          $html   .= '<div class="product-price">';
          if( $product->is_on_sale() ) {
            $html   .= wc_format_sale_price( $product->get_regular_price(), $product->get_price() );
          } else {
            $html   .= $product->get_price_html();
          }
          $html   .= '</div>';
          break;

        case 'product_rating':
          $product = wc_get_product( $id );
          $rating  = $product->get_average_rating( $id );
          if( !function_exists('wp_star_rating') ) {
            require_once( ABSPATH . 'wp-admin/includes/template.php' );
          }
          $html   .= wp_star_rating( array('rating' => $rating, 'echo' => false) );

          break;
        case 'add_to_cart':
          $product = wc_get_product( $id );
          $html   .= apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s %s product_type_%s">%s</a>',
                  esc_url( $product->add_to_cart_url() ),
                  esc_attr( $product->get_id() ),
                  esc_attr( $product->get_sku() ),
                  $product->is_purchasable() ? 'add_to_cart_button' : '',
                  !$product->is_type( 'variable' ) ? 'ajax_add_to_cart' : '',
                  esc_attr( $product->get_type() ),
                  esc_html( $product->add_to_cart_text() )
              ),
          $product );
          break;
        case 'sale':
          $product = wc_get_product( $id );
          if( $product->is_on_sale() ) {
            $html   .= sprintf('<div class="woocommerce-onsale onsale">%1$s</div>', esc_html__('Sale!', 'merimag') );
          }
          break;
        case 'bull':
          $html   .= '<span class="meta-separator">&bull;</span>';
          break;
        case 'dash':
          $html   .= '<span class="meta-separator" style="font-size: 8px;">&mdash;</span>';
          break;
        case 'slash':
          $html   .= '<span class="meta-separator">/</span>';
          break;
      }
    }
    $end      = strpos($parse, 'format_icon') === false && strpos($parse, 'add_to_cart') === false ? '</span>' : '';
    $html     = !empty( $html ) ? $begin . $html . $end : '';
    if( empty($html ) ) {
      $transient_html = 'false';
    } else {
      $transient_html = $html;
    }
    $meta_infos[$parse] = $transient_html;
    if( $enable_cache === 'yes' ) {
      set_transient('merimag_cache_meta_infos_' . $id  . merimag_get_demo_slug(), $meta_infos);
    }
    return $html;
  }
  
  
  
}

/**
 * Display multiple meta infos parsed from a string separed with |
 *
 * @param string $parse string that contains list of meta infos separed with |
 * @param integer $id_content the post ID
 * @return void
 */
function merimag_html_helper( $parse = '', $id_content = false ) {
  $id_content = $id_content === false ? get_the_ID() : $id_content;
  $elements = explode('|', $parse );
  $html       = '';
  foreach ( $elements as $element ) {
    switch ($element) {
      case 'date':
        $html .= '<a href="' . esc_url( get_the_permalink( $id ) ) . '"><span>' . get_the_date( '', $id_content ) . '</span></a>';
        break;
      case 'thumb':
        $post_thumbnail_id = get_post_thumbnail_id( $id_content );
        $size = merimag_get_image_size_by_height( 100 );
        $url   = wp_get_attachment_image_src( $post_thumbnail_id, $size );
        $url = isset( $url[0] ) ? $url[0] : false;
        $html .= sprintf('<span style="background-image: url(%1$s)" class="merimag-title-thumbnail-container"></span></span>', esc_url( $url ) );
        break;
      case 'colorful_square':
        $html .= sprintf('<div class="merimag-block-order-number principal-color-color"><span>%1$s</span></div>', esc_attr( $id_content + 1 ) );
        break;
      case 'rounded_number_big':
      case 'rounded_number_big_white':
          $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-rounded-number principal-color-background-color white big">%1$s</span></span>', $id_content );
          break;
      case 'rounded_number':
      case 'rounded_number_white':
        $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-rounded-number principal-color-background-color white">%1$s</span></span>', $id_content );
          break;
      case 'rounded_number_big_dark':
          $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-rounded-number principal-color-background-color dark big">%1$s</span></span>', $id_content );
          break;
      case 'rounded_number_dark':
        $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-rounded-number principal-color-background-color dark">%1$s</span></span>', $id_content );
          break;
      case 'square_number_big':
      case 'square_number_big_white':
          $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-square-number white big">%1$s</span></span>', $id_content );
          break;
      case 'square_number':
      case 'square_number_white':
        $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-square-number white">%1$s</span></span>', $id_content );
          break;
      case 'square_number_big_dark':
          $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-square-number dark big">%1$s</span></span>', $id_content );
          break;
      case 'square_number_dark':
        $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-square-number dark">%1$s</span></span>', $id_content );
          break;
      case 'number_big':
      case 'number_big_white':
          $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-number white big">%1$s</span></span>', $id_content );
          break;
      case 'number':
      case 'number_white':
        $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-number white">%1$s</span></span>', $id_content );
          break;
      case 'number_big_dark':
          $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-number dark big">%1$s</span></span>', $id_content );
          break;
      case 'number_dark':
        $html   .= sprintf('<span class="merimag-number-container"><span class="merimag-number dark">%1$s</span></span>', $id_content );
          break;
      default:
        # code...
        break;
    }
  }
  return $html;
}

/**
 * Get principal color of the theme filtered
 *
 * @return string hex color
 */
function merimag_get_principal_color() {
  $principal_color = merimag_get_db_live_customizer_option( 'principal_color');
  $principal_color = empty( $principal_color ) ? '#e32121' : $principal_color;
  $category        = is_category() ? get_category( get_query_var( 'cat' ) ) : false;
  $cat_id          = $category ?  $category->cat_ID : false;
  $principal_color = defined('FW') && is_category() && merimag_get_db_term_option( $cat_id, 'category', 'principal_color' ) ?  merimag_get_db_term_option( $cat_id, 'category', 'principal_color' ) : $principal_color;
  return $principal_color;
}
/**
 * Display a loading animation
 *
 * @param string $style style of the animation
 * @return void
 */
function merimag_get_spinkit( $style = false ) {
  if( !$style ) {
    $style = merimag_get_db_customizer_option( 'loading_animation');
  }
  $style = !$style || $style === 'default' ? '2' : $style;
  $html  = '<div class="principal-color-color">';
  switch( $style ) {
    case '1':
      $html .= "<div class='sk-rotating-plane'></div>";
      break;
    case '2':
      $html .= "<div class='sk-double-bounce'><div class='sk-child sk-double-bounce-1'></div><div class='sk-child sk-double-bounce-2'></div></div>";
      break;
    case '3':
      $html .= "<div class='sk-wave'> <div class='sk-rect sk-rect-1'></div><div class='sk-rect sk-rect-2'></div><div class='sk-rect sk-rect-3'></div><div class='sk-rect sk-rect-4'></div><div class='sk-rect sk-rect-5'></div></div>";
      break;
    case '4':
      $html .= "<div class='sk-wandering-cubes'> <div class='sk-cube sk-cube-1'></div><div class='sk-cube sk-cube-2'></div></div>";
      break;
    case '5':
      $html .= "<div class='sk-spinner sk-spinner-pulse'></div>";
      break;
    case '6':
      $html .= "<div class='sk-chasing-dots'> <div class='sk-child sk-dot-1'></div><div class='sk-child sk-dot-2'></div></div>";
      break;
    case '7':
      $html .= "<div class='sk-three-bounce'> <div class='sk-bounce-1 sk-child'></div><div class='sk-bounce-2 sk-child'></div><div class='sk-bounce-3 sk-child'></div></div>";
      break;
    case '8':
      $html .= "<div class='sk-circle-bounce'> <div class='sk-child sk-circle-1'></div><div class='sk-child sk-circle-2'></div><div class='sk-child sk-circle-3'></div><div class='sk-child sk-circle-4'></div><div class='sk-child sk-circle-5'></div><div class='sk-child sk-circle-6'></div><div class='sk-child sk-circle-7'></div><div class='sk-child sk-circle-8'></div><div class='sk-child sk-circle-9'></div><div class='sk-child sk-circle-10'></div><div class='sk-child sk-circle-11'></div><div class='sk-child sk-circle-12'></div></div>";
      break;
    case '9':
      $html .= "<div class='sk-cube-grid'><div class='sk-cube sk-cube-1'></div><div class='sk-cube sk-cube-2'></div><div class='sk-cube sk-cube-3'></div><div class='sk-cube sk-cube-4'></div><div class='sk-cube sk-cube-5'></div><div class='sk-cube sk-cube-6'></div><div class='sk-cube sk-cube-7'></div><div class='sk-cube sk-cube-8'></div><div class='sk-cube sk-cube-9'></div></div>";
      break;
    case '10':
      $html .= "<div class='sk-fading-circle'> <div class='sk-circle sk-circle-1'></div><div class='sk-circle sk-circle-2'></div><div class='sk-circle sk-circle-3'></div><div class='sk-circle sk-circle-4'></div><div class='sk-circle sk-circle-5'></div><div class='sk-circle sk-circle-6'></div><div class='sk-circle sk-circle-7'></div><div class='sk-circle sk-circle-8'></div><div class='sk-circle sk-circle-9'></div><div class='sk-circle sk-circle-10'></div><div class='sk-circle sk-circle-11'></div><div class='sk-circle sk-circle-12'></div></div>";
    case '11':
      $html .= "<div class='sk-folding-cube'> <div class='sk-cube sk-cube-1'></div><div class='sk-cube sk-cube-2'></div><div class='sk-cube sk-cube-4'></div><div class='sk-cube sk-cube-3'></div></div>";
      break;
    case '12':
      $html .= '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
  }
  $html .='</div>';
  return $html;
}
/**
 * Get cached customizer option value
 *
 * @param string $option name of option
 * @return mixed cached option value
 */
function merimag_get_db_customizer_cached_option( $option ) {
  $options = get_transient('merimag_customizer_cached_options');
  if( isset($options[$option]) ) {
    return $options[$option];
  } else {
    $value = fw_get_db_customizer_option($option);
    $options[$option] = $value;
    set_transient('merimag_customizer_cached_options', $options, 60 * 60 * 60 );
    return $value;
  }
}
/**
 * Get cached post option value
 *
 * @param string $option name of option
 * @return mixed cached option value
 */
function merimag_get_db_post_cached_option( $post_id, $option, $default = false ) {
  $options = get_transient('merimag_post_cached_options');
  if( isset($options[$post_id][$option]) ) {
    return $options[$post_id][$option];
  } else {
    $default = $default !== false ? fw_get_db_post_option($post_id, $option, $default) : fw_get_db_post_option($post_id, $option);
    $options[$post_id][$option] = $default;
    set_transient('merimag_post_cached_options', $options, 60 * 60 * 60 );
    return $default;
  }
}
/**
 * Get filtered customizer option value
 *
 * @param string $option name of option
 * @param mixed $default default value to set if empty
 * @return mixed filtered option value
 */
function merimag_get_db_customizer_option( $option, $default = false ) {
    if( !is_string( $option ) ) {
       return;
    }
    $default_val  = $default;
    $filtered_val = apply_filters($option, $default );
    $value        = defined('FW') && function_exists('fw_get_db_settings_option') ? fw_get_db_settings_option( $option ) : $filtered_val;
    if( class_exists('WPMDM') && isset( $_GET[$option]) ) {
        $value = $_GET[$option];
    }
    if( is_array( $value ) ) {
      foreach( $value as $key => $val ) {
        $default[$key] = empty( $value[$key]) || $value[$key] === 'default' ? ( isset( $filtered_val[$key] ) ? $filtered_val[$key] : $val ) : $val;
      }
    } else {
      $default      = empty( $value ) && empty( $filtered_val ) ? $default : $value;
      $default      = empty( $default ) || $default === 'default' || $default === true  ? $filtered_val : $default;
      $default      = empty( $default ) || $default === 'default' ? $default_val : $default;

    }

    if( is_singular() ) {
      
      $single_default = merimag_get_db_post_option( get_the_ID(), 'single_' . $option );
      
      $default        = !is_null( $single_default ) && !empty( $single_default ) && $single_default !== 'default' ? $single_default : $default;
    }
    if( is_category() ) {
      $cat_default = merimag_get_db_term_option( get_query_var( 'cat' ), 'category', 'category_' . $option );
    
      $default        = !is_null( $cat_default ) && !empty( $cat_default ) && $cat_default !== 'default' ? $cat_default : $default;
    }
    return $default;
}
/**
 * Get filtered live customizer option value
 *
 * @param string $option name of option
 * @param mixed $default default value to set if empty
 * @return mixed filtered option value
 */
function merimag_get_db_live_customizer_option( $option, $default = false ) {
    if( !is_string( $option ) ) {
       return;
    }
    $default_val  = $default;
    $filtered_val = apply_filters($option, $default );
    $value        = defined('FW') && function_exists('fw_get_db_customizer_option') ? fw_get_db_customizer_option( $option ) : $filtered_val;
    if( class_exists('WPMDM') && isset( $_GET[$option]) ) {
        $value = $_GET[$option];
    }

    if( is_array( $value ) ) {
      foreach( $value as $key => $val ) {
        $default[$key] = empty( $value[$key]) || $value[$key] === 'default' ? ( isset( $filtered_val[$key] ) ? $filtered_val[$key] : $val ) : $val;
      }
    } else {
      $default      = empty( $value ) && empty( $filtered_val ) ? $default : $value;
      $default      = empty( $default ) || $default === 'default' || $default === true  ? $filtered_val : $default;
      $default      = empty( $default ) || $default === 'default' ? $default_val : $default;

    }
    if( is_singular() ) {
      $single_default = merimag_get_db_post_option( get_the_ID(), 'single_' . $option );
      
      $default        = !is_null( $single_default ) && !empty( $single_default ) && $single_default !== 'default' ? $single_default : $default;
    }

    if( is_category() ) {
      $cat_default = merimag_get_db_term_option( get_query_var( 'cat' ), 'category', 'category_' . $option );
    
      $default        = !is_null( $cat_default ) && !empty( $cat_default ) && $cat_default !== 'default' ? $cat_default : $default;

    }

    return $default;
}
/**
 * Get filtered post meta value
 *
 * @param integer $post_id ID of the post
 * @param string $option name of option
 * @param mixed $default default value to set if empty
 * @return mixed filtered post meta value
 */
function merimag_get_db_post_option( $post_id = false, $option, $default = false ) {
  if( !is_string( $option ) ) {
     return;
  }

  if( defined('FW') ) {
    $default = $default !== false ? fw_get_db_post_option($post_id, $option, $default) : fw_get_db_post_option($post_id, $option);
    $default = apply_filters('single_option_' . $option, $default );
  }
  return $default;
}
/**
 * Get filtered term meta value
 *
 * @param integer $term_id ID of the term
 * @param string $taxonomy name of taxonomy
 * @param string $meta_name name of the option to retreive
 * @return mixed filtered post meta value
 */
function merimag_get_db_term_option( $term_id, $taxonomy, $meta_name ) {
  if( defined('FW') ) {
    return fw_get_db_term_option( $term_id , $taxonomy, $meta_name );
  }
}

/**
 * Check if a post has review enabled
 *
 * @param integer $id ID of the post
 * @return bool if has review true
 */
function merimag_has_review( $id = false ) {
  if(!$id) {
    $id = get_the_ID();
  }
  $enable = merimag_get_db_post_option( $id, 'single_enable_review');
  $has_review = false;
  if( $enable === 'yes' ) {
    $review = merimag_get_db_post_option(  $id, 'single_review');
    if( isset( $review['yes'] ) && !empty( $review['yes'] ) && isset( $review['yes']['review_cretirias'] ) && !empty( $review['yes']['review_cretirias'] ) ) {
      $has_review = true;
    } 
  }
  return $has_review;
}
/**
 * Get review score point
 *
 * @param integer $id ID of the post
 * @return float the review score point
 */
function merimag_get_review_score_point( $id = false, $percent = false ) {
  if(!$id) {
    $id = get_the_ID();
  }
  $enable = merimag_get_db_post_option( $id, 'single_enable_review');
  $score  = false;
  if( $enable === 'yes' ) {
    $review = merimag_get_db_post_option( $id, 'single_review');
    if( isset( $review['yes'] ) && !empty( $review['yes'] ) && isset( $review['yes']['review_cretirias'] ) && !empty( $review['yes']['review_cretirias'] ) ) {
      $atts = $review['yes'];
      $review_cretirias = isset( $atts['review_cretirias'] ) && !empty( $atts['review_cretirias'] ) ? $atts['review_cretirias'] : array();
      if( $review_cretirias && count( $review_cretirias ) > 0 ) {
        $note         = 0;
        foreach( (array) $review_cretirias as $k => $review_cretiria ) {
          $note += isset( $review_cretiria['note'] ) && is_numeric( $review_cretiria['note'] ) && $review_cretiria['note'] >= 0 && $review_cretiria['note'] <= 10 ? $review_cretiria['note'] : 0;
        }
        $score = ( $note / count( $review_cretirias ) ) / 2;
      }
    }
  }
  return $percent === false ? $score : ( $score * 100 ) / 5;
}
/**
 * Display review score in different ways
 *
 * @param integer $id ID of the post
 * @param string $review_display_style style of the display
 * @param string $only_stars return stars
 * @return void
 */
function merimag_get_review_score( $id = false, $review_display_style = 'simple', $only_stars = false ) {
  if(!$id) {
    $id = get_the_ID();
  }
  $html = '';
  if( !in_array( $review_display_style, array('simple', 'circle', 'bar', 'stars'))) {
    $review_display_style = apply_filters('review_display_style', 'simple');
  }
  $enable = merimag_get_db_post_option( $id, 'single_enable_review');
  if( $enable === 'yes' ) {
    $review = merimag_get_db_post_option( $id, 'single_review');
    if( isset( $review['yes'] ) && !empty( $review['yes'] ) && isset( $review['yes']['review_cretirias'] ) && !empty( $review['yes']['review_cretirias'] ) ) {
      $atts = $review['yes'];
      $review_cretirias = isset( $atts['review_cretirias'] ) && !empty( $atts['review_cretirias'] ) ? $atts['review_cretirias'] : array();
      $valid_styles    = array('stars','points','percent'); 
      $review_style    = isset( $atts['review_style'] ) && in_array( $atts['review_style'], $valid_styles ) ? $atts['review_style'] : 'points';
      $score           = merimag_get_review_score_point( $id );
      $score_comment = '';
      if( $score > 0 && $score <= 1 ) {
        $score_comment = __('Very Bad', 'merimag');
      }
      if( $score > 1 && $score <= 2 ) {
        $score_comment = __('Bad', 'merimag');
      }
      if( $score > 2 && $score <= 3 ) {
        $score_comment = __('Correct', 'merimag');
      }
      if( $score > 3 && $score <= 4 ) {
        $score_comment = __('Nice', 'merimag');
      }
      if( $score > 4 && $score <= 5 ) {
        $score_comment = __('Awesome!', 'merimag');
      }
      $score_comment   = isset( $atts['review_score_comment'] ) && !empty( $atts['review_score_comment'] ) ? $atts['review_score_comment'] : $score_comment;
      $score_percent   = ( $score * 100 ) / 5;
      $score_points    = $score * 2;
      if( !function_exists('wp_star_rating') ) {
          require_once( ABSPATH . 'wp-admin/includes/template.php' );
      }
      $score_stars   = wp_star_rating( array('rating' => $score, 'echo' => false) );
      if( $only_stars === true ) {
        return $score_stars;
      }
      $display_score   = $score_points;
      switch ($review_display_style) {
        case 'circle':
        case 'simple':
          $display_score = number_format( $score_points, 1);
          break;
        case 'bar':
          $display_score = number_format( $score_percent, 0);
          break;
        case 'stars':
          $display_score = $score_stars;
          break;
      }
      $principal_color = merimag_get_principal_color();
      $review_display_style_class =  in_array( $review_display_style, array('simple', 'bar')) ? $review_display_style . ' principal-color-background-color' : $review_display_style;
      $width = $review_display_style === 'bar' ? $score_percent . '%' : $score_percent / 100;
      $flex_ratio = $review_display_style === 'simple' ? 0.3 : 0.2;
      $html = sprintf('<div class="merimag-review-score-display flexFont %s" data-flex-font="%s" data-width="%s"><span class="merimag-review-score">%s</span></div>', $review_display_style_class, $flex_ratio, $width, $display_score );
      
    } 
  }
  return $html;
}
/**
 * Get post views
 *
 * @return integer number of post views
 */
function merimag_get_post_views( $id = false ) {
  if( !$id ) {
    $id = get_the_ID();
  }
  $default     = class_exists('WPMDM') ? 'yes' : 'no';
  $fake_post_views = merimag_get_db_customizer_option('fake_post_views', $default );
  if( $fake_post_views === 'yes' ) {
    $views = get_post_meta( $id, 'fake_views', true );
    if( $views && is_numeric($views ) && $views > 235) {
      return $views;
    } else {
      $views = mt_rand(235, 14580);
      update_post_meta( $id, 'fake_views', $views );
      return $views;
    }
  } else {
    $views = get_post_meta( $id, 'views', true );
    return $views;
  }
}
/**
 * Return a css class based on views count
 *
 * @param integer $views number of views
 * @return strring the class name can be hot trending or popular
 */
function merimag_get_post_views_badge_color( $views ) {
  $hot_number      = merimag_get_db_customizer_option('post_views_hot_badge', 10000 );
  $popular_number  = merimag_get_db_customizer_option('post_views_popular_badge', 5000 );
  $trending_number = merimag_get_db_customizer_option('post_views_trending_badge', 500 );
  $class = '';
  if( $views >= intval( $trending_number ) ) {
    $class = 'trending';
  }
  if( $views >= intval( $popular_number ) ) {
    $class = 'popular';
  }
  if( $views >= intval( $hot_number ) ) {
    $class = 'hot';
  }
  return $class;
}
/**
 * Return a filtered post thumbnail url based on post id
 *
 * @param integer $post_id the ID of the post
 * @param string $post_type the type of the post
 * @return string the post thumbnail url filtered
 */
function merimag_get_the_post_thumbnail_url( $id = false, $post_type = 'post' ) {
  $id = $id === false ? get_the_ID() : $id;
  $thumbnail_url = get_the_post_thumbnail_url( $id, merimag_get_image_size( 'full') );
  return apply_filters('merimag_get_the_post_thumbnail_url', $thumbnail_url, $id );
}
/**
 * Return a filtered term thumbnail url based on term id
 *
 * @param integer $term_id the ID of the post
 * @param string $taxonomy the term taxonomy
 * @return string the term thumbnail url filtered
 */
function merimags_get_the_category_thumbnail_url( $term_id = false, $taxonomy = 'category' ) {
  if( !$term_id) {
     return;
  }
  switch ($taxonomy) {
    case 'category':
      $thumbnail_url = defined('FW') ? merimag_get_db_term_option($term_id, $taxonomy, 'featured_image') : '';
      $thumbnail_url = isset( $thumbnail_url['url'] ) ? $thumbnail_url['url'] : '';
      break;
    case 'product_cat':
      $thumbnail_id  = get_term_meta( $term_id, 'thumbnail_id', true );
      $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
      break;
    default:
      $thumbnail_url = '';
      break;
  }
  return apply_filters('merimags_get_the_category_thumbnail_url', $thumbnail_url, $term_id );
}
/**
 * Return a filtered term thumbnail url based on term id
 *
 * @param integer $term_id the ID of the post
 * @param string $taxonomy the term taxonomy
 * @return string the term thumbnail url filtered
 */
function merimag_themes_get_the_category_thumbnail_id( $term_id = false, $taxonomy = 'category' ) {
  if( !$term_id) {
    return; 
  }
  switch ($taxonomy) {
    case 'category':
      $thumbnail_id  = defined('FW') ? merimag_get_db_term_option($term_id, $taxonomy, 'featured_image') : '';
      $thumbnail_id  = isset( $thumbnail_id['attachment_id'] ) ? $thumbnail_id['attachment_id'] : false;
      break;
    case 'product_cat':
      $thumbnail_id  = class_exists( 'WooCommerce' ) ? get_term_meta( $term_id, 'thumbnail_id', true ) : false;
      break;
    default:
      $thumbnail_id = false;
      break;
  }
  return apply_filters('merimag_themes_get_the_category_thumbnail_id', $thumbnail_id, $term_id );
}

/**
 * Get theme colors
 *
 * @param bool $keys if set to true the function will return only keys
 * @return array list of valid theme colors
 */
function merimag_get_theme_colors( $keys = false ) {
  
    $theme_colors = array( 
      'hot' => merimag_get_db_customizer_option('theme_color_hot', '#ef3c3c'),
      'popular' => merimag_get_db_customizer_option('theme_color_popular', '#f1550a'),
      'trending' => merimag_get_db_customizer_option('theme_color_trending', '#68a9fb'),
      'new' => merimag_get_db_customizer_option('theme_color_new', '#5aade7'),
      'warning' => merimag_get_db_customizer_option('theme_color_warning', '#d9b31d' ),
      'error' => merimag_get_db_customizer_option('theme_color_error', '#ff0000'), 
      'info' => merimag_get_db_customizer_option('theme_color_info', '#c9edf7') 
    );
    return $keys === true ? array_keys( $theme_colors ) : $theme_colors;
}
  
/**
 * Get theme colors css
 *
 * @param string $area valid css selector
 * @return string css code
 */
function merimag_get_theme_colors_css( $area = 'body' ) {
  
  $theme_colors = merimag_get_theme_colors();

  $css          = '';

  foreach( $theme_colors as $name => $color ) {
      
      $css_propeties = array('background-color', 'color', 'border-right-color', 'border-left-color', 'border-top-color', 'border-bottom-color', 'border-color' );

      foreach( $css_propeties as $property ) {
        $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-' . esc_attr( $property ) . ' { ' . esc_attr( $property ) . ' : ' . esc_attr( $color ) . '; }';
      }

      $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-background-color-hover:hover { background: ' . esc_attr( $color ) . ' }';
      $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-background-color-span-hover:hover { background: ' . esc_attr( $color ) . ' }';
      $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-background-color-a-hover a:hover { background: ' . esc_attr( $color ) . ' }';
      $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-span-hover-before-background span:hover:before { background: ' . esc_attr( $color ) . ' }';
      $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-sub-menu-border-top-color .sub-menu, ' . esc_attr( $area ) . '.' . esc_attr( $name ) . '-color-sub-menu-border-top-color .sub-menu { border-top-color: ' . esc_attr( $color ) . ' }';

      $color_text_color = merimag_get_text_color_from_background( $color );

      $css .= esc_attr( $area ) . ' .' . esc_attr( $name ) . '-color-background-color { color: ' . esc_attr( $color_text_color ) . '; }';
  }
  return $css;
}

/**
 * Generate <picture> or <img> html for post attachment image specifying correct responsive rules,
 * which should be set through 'rwd_image_sizes' filter hook.
 *
 * @param WP_Post|int|null $attachment WordPress attachment object, ID or null. If null passed will take featured image of current post.
 * @param string|array     $size {
 *              Single image size name OR array
 *
 *              @type int 0 =>  $size (first element should be the name of image size),
 *              @type string            $subsize => $attachment_id  ($attachment to be used to rewrite image in another resolution)
 *        }
 * @param string           $tag Specify which tag should be used: picture|img.
 * @param array            $attr  Additional html attributes to be used for main tag.
 *
 * @return string Generated html.
 */
function merimag_rwd_attachment_image( $attachment = null, $size = 'thumbnail', $tag = 'picture', $attr = array() ) {
  if( !function_exists('rwd_attachment_image') ) {
    return false;
  }
  $attachment = apply_filters('webte_attachment_id', $attachment );
  return function_exists('rwd_attachment_image') ? rwd_attachment_image( $attachment, $size, $tag, $attr ) : false;
}

/**
 * Generate css media styles for background image for specific css selector and specific rwd sizes,
 * which should be set through 'rwd_image_sizes' filter hook.
 *
 * Generated styles are add to cache and then print them in wp_foot or by calling rwd_print_css();
 *
 * @param string           $selector Dynamic css selector
 * @param WP_Post|int|null $attachment WordPress attachment object, ID or null. If null passed will take featured image of current post.
 * @param string|array     $size {
 *              Single image size name OR array
 *
 *              @type int 0 =>  $size (first element should be the name of image size),
 *              @type string            $subsize => $attachment_id  ($attachment to be used to rewrite image in another resolution)
 *        }
 */
function merimag_rwd_attachment_background( $selector, $attachment = null, $size = 'thumbnail' ) {
  if( !function_exists('rwd_attachment_background') ) {
    return false;
  }
  $attachment = apply_filters('webte_attachment_id', $attachment );
  return rwd_attachment_background( $selector, $attachment, $size );
}

/**
* Generate opening tag with background style with classes for responsive images
*
* @param array $atts list of attributes
* @return string html
*/
function merimag_get_thumbnail_background_tag( $atts = array() ) {
  $is_amp_endpoint      = function_exists('is_amp_endpoint') ? is_amp_endpoint() : false;
  $merimag_uniqid       = merimag_uniqid('merimag-block-image-');
  $class                = isset( $atts['class'] ) && is_string( $atts['class'] ) ? $atts['class'] : '';
  $style                = isset( $atts['style'] ) && is_string( $atts['style'] ) ? $atts['style'] : '';
  $post_thumbnail_id    = isset( $atts['thumbnail_id'] ) ? $atts['thumbnail_id'] : get_post_thumbnail_id();
  $post_thumbnail_id    = isset( $atts['post_id'] ) ? get_post_thumbnail_id( $atts['post_id'] ) : $post_thumbnail_id;
  $tag                  = isset( $atts['tag'] ) && is_string( $atts['tag'] ) ? $atts['tag'] : 'div';
  if( !$post_thumbnail_id ) {
     return sprintf('<%s class="%s" style="%s">', $tag, $class, $style );
  }
  $size = isset( $atts['args']['image_size'] ) && in_array( $atts['args']['image_size'], merimag_get_recognized_image_sizes(true) ) ? $atts['args']['image_size'] : 'default';
  if( $size !== 'default' ) {
    $size = merimag_get_image_size( $size );
  }
  if( $size === 'default' ) {
    $height               = isset( $atts['args']['height'] ) && is_numeric( $atts['args']['height'] ) ? $atts['args']['height'] : 300;

    $height               = isset( $atts['height'] ) && is_numeric( $atts['height'] ) ? $atts['height'] : $height;
    $size                 = merimag_get_image_size_by_height( $height );
  }
  $thumbnail_url        = $post_thumbnail_id ? wp_get_attachment_image_src( $post_thumbnail_id, $size ) : '';
  $thumbnail_url        = isset( $thumbnail_url[0] ) ? $thumbnail_url[0] : '';
  $rwd_background       = merimag_rwd_attachment_background(sprintf('.%s', $merimag_uniqid ), $post_thumbnail_id, 'visual' );
  $lazy_loading         = !$is_amp_endpoint ? merimag_get_db_customizer_option('lazy_image_loading', 'yes') : 'no';
  if( $rwd_background !== false  && !$is_amp_endpoint ) {
    $data_rwd_lazy      = $lazy_loading === 'yes' ? sprintf('data-lazy-class="%s rwd-lazy-background-loaded"', $merimag_uniqid ) : '';
    $class             .= $lazy_loading === 'no' ? sprintf(' %s' ,$merimag_uniqid) : ' rwd-lazy-background';
    $html               = sprintf('<%s class="%s" %s style="%s">', $tag, $class, $data_rwd_lazy, $style );
  } else {
    $class             .= $lazy_loading === 'yes' ? ' merimag-lazy-image' : '';
    $data_src_lazy      = $lazy_loading === 'yes' ? sprintf('data-src="%s"', $thumbnail_url ) : '';
    $img_background     = !$post_thumbnail_id ? $element->img : '';
    $style             .= $lazy_loading === 'no' ? sprintf( 'background-image: url(%s);', $thumbnail_url ) : '';
    $html               = sprintf('<%s class="%s" %s  style="%s">', $tag, $class, $data_src_lazy, $style  );
  }

  return $html;
}

/**
* Get cached image src
*
* @param string $post_thumbnail_id
* @return string $size valid image size
*/
function merimag_wp_get_attachment_image_src( $post_thumbnail_id, $size ) {
  $images_srcs = get_transient('merimag_cache_image_srcs');
  $encoded_size = json_encode($size);
  if( isset( $images_srcs[$post_thumbnail_id][$encoded_size] ) ) {
    return $images_srcs[$post_thumbnail_id][$encoded_size];
  } else {
    $images_srcs[$post_thumbnail_id][$encoded_size] = wp_get_attachment_image_src( $post_thumbnail_id, $size );
    set_transient( 'merimag_cache_image_srcs', $images_srcs );
    return $images_srcs[$post_thumbnail_id][$encoded_size];
  }

}
/**
* Get instagram media
* Based on https://gist.github.com/cosmocatalano/4544576.
*
* @param string $username @username or #tag
* @return array list of images
*/
function merimag_scrape_instagram( $username ) {

  $username = trim( strtolower( $username ) );

  switch ( substr( $username, 0, 1 ) ) {
    case '#':
      $url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
      $transient_prefix = 'h';
      break;

    default:
      $url              = 'https://instagram.com/' . str_replace( '@', '', $username );
      $transient_prefix = 'u';
      break;
  }


  if ( false === ( $instagram = get_transient( 'merimag-insta-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

    $remote = wp_remote_get( $url );

    if ( is_wp_error( $remote ) ) {
      return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'merimag' ) );
    }

    if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
      return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'merimag' ) );
    }

    $shards      = explode( 'window._sharedData = ', $remote['body'] );
    $insta_json  = explode( ';</script>', $shards[1] );
    $insta_array = json_decode( $insta_json[0], true );

    if ( ! $insta_array ) {
      return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'merimag' ) );
    }

    if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
      $images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
    } elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
      $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
    } else {
      return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'merimag' ) );
    }

    if ( ! is_array( $images ) ) {
      return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'merimag' ) );
    }

    $instagram = array();

    foreach ( $images as $image ) {
      if ( true === $image['node']['is_video'] ) {
        $type = 'video';
      } else {
        $type = 'image';
      }

      $caption = __( 'Instagram Image', 'merimag' );
      if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
        $caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
      }

      $instagram[] = array(
        'description' => $caption,
        'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
        'time'        => $image['node']['taken_at_timestamp'],
        'comments'    => $image['node']['edge_media_to_comment']['count'],
        'likes'       => $image['node']['edge_liked_by']['count'],
        'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
        'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
        'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
        'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
        'type'        => $type,
      );
    } // End foreach().

    // do not set an empty transient - should help catch private or empty accounts.
    if ( ! empty( $instagram ) ) {
      $instagram = serialize( $instagram );
      set_transient( 'merimag-insta-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
    }
  }

  if ( ! empty( $instagram ) ) {

    return unserialize( $instagram );

  } else {

    return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'merimag' ) );

  }
}
/**
* Get color layer background
*
* @return string background css
*/
function merimag_get_color_layer( $i = 'no' ) {
  $colors = array('red', 'blue', 'green', 'orange', 'purple', 'yellow','#7fe5f0', '#e763ba', '#e7ff20', '#5f54df', '#34d40f', '#fff3d9', '#ffcf17', '#ff5315', '#d63d87', '#2b670d' );
  $i = $i !== 'no' && is_numeric( $i ) && $i > 10 ? ( $i - ( $i % 10 ) ) / 10 : $i; 
  $ks = isset( $colors[$i] ) && $i !== 'no' ? $i : array_rand( $colors, 1 );

  $css = sprintf('background-image: linear-gradient(127deg, %s, %s);', 'transparent', $colors[$ks] );

  return $css; 
}
/**
 * Return selected google fonts from customizer
 *
 * @param bool keys 
 * @return array selected google fonts
 */
function merimag_get_selected_google_fonts( $keys = false ) {
  $fonts = array();
  $fonts = apply_filters('merimag_load_fonts', $fonts );
  $default_families = array(
      'default'   => 'Default',
      'arial'     => 'Arial',
      'georgia'   => 'Georgia',
      'helvetica' => 'Helvetica',
      'palatino'  => 'Palatino',
      'tahoma'    => 'Tahoma',
      'times'     => 'Times New Roman',
      'trebuchet' => 'Trebuchet',
      'verdana'   => 'Verdana'
  );
  $choices = array();
  foreach( $fonts as $font ) {
    $font = str_replace('-', ' ', $font);
    $choices[$font] = $font;
  }
  $fonts = array_merge($default_families, $choices);
  return $keys === true ? array_keys( $fonts ) : $fonts;
}
/**
 * Return valid html link attributes
 *
 * @param array $atts link params 
 * @return html link attributes
 */
function merimag_get_link_attr( $atts ) {
  $link = '';
  if( isset( $atts['url'] ) && isset( $atts['is_external'] ) && isset( $atts['nofollow'] ) ) {
    $link  = is_string( $atts['url'] ) ? sprintf('href="%s"', esc_url( $atts['url'] ) ) : '';
    $link .= $atts['is_external'] === 'on' ? 'target="_blank"' : '';
    $link .= $atts['nofollow'] === 'on' ? 'rel="nofollow"' : '';
  }
  if( isset( $atts['attr'] ) && isset( $atts['target'] ) ) {
    $link  = is_string( $atts['attr'] ) ? sprintf('href="%s"', esc_url( $atts['attr'] ) ) : '';
    $link .= $atts['target'] === '_blank' ? 'target="_blank"' : '';
  }
  return $link;
}

/**
 * @return font object data
 */
function merimag_get_google_font( $font_id ) {
  $fonts = merimag_get_google_fonts( true );
  foreach( $fonts as $font ) {
    if( isset( $font_object->family ) && $font_object->family === $font_id ) {
      $font =  $font_object;
      break;
    }
  }
  return isset( $font ) ? $font : false;
}

/**
 * Search for some shortcodes in a string and add a given paramters
 *
 * @param string $content string that contains the shortcodes
 * @param string $attr_name the parameter name to add
 * @param string $attr_val the parameter value to add
 * @return string filtred content
 */
function merimag_filter_blocks_shortcodes( $content, $attr_name, $attr_val ) {
  $blocks  = array( 'posts_block', 'products_block', 'posts_grid', 'posts_slider', 'products_grid', 'column' );
  if( !is_string( $attr_name ) || !is_string( $attr_val ) || !is_string( $content ) ) {
    return $content;
  }
  foreach( $blocks as $block ) {
    $content = str_replace('[' . esc_attr( $block ) . ' ', '[' . esc_attr( $block ) . ' ' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_val ) . '" ', $content);
  }
  return $content;
}
/**
 * Search for a shortcode in a string and add a given paramters
 *
 * @param string $content string that contains the shortcodes
 * @param string $shortcode name of shortcode
 * @param string $attr_name the parameter name to add
 * @param string $attr_val the parameter value to add
 * @return string filtred content
 */
function merimag_filter_shortcode( $content, $shortcode, $attr_name, $attr_val ) {
  $content = str_replace('[row', '[row ' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_val ) . '"', $content);
  return $content;
}

/**
 * Search video duration and thumbnail from youtube or vimeo
 *
 * @param string $url valid youtube or vimeo link
 * @return string url of thumbnail
 */
function merimag_get_youtube_vimeo_data( $url, $hight_quality = false ) {
  if( !is_string($url ) ) {
    return;
  }
  $parts = parse_url($url);
  $return = array();
  if( isset( $parts['host'] ) && strpos( 'youtu', $parts['host'] ) !== -1 && isset( $parts['query'] ) ) {
    parse_str($parts['query'], $vars);
    $video_id = isset( $vars['v'] ) ? $vars['v'] : false;
    if( $video_id ) {
      $key = '=AIzaSyCbUlSsZmbNcBEBenT54n6nZMHYyNI0qb4';
      $get = wp_remote_get('https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=' . esc_attr( $video_id ) . '&key' . esc_attr( $key ));
      $body     = wp_remote_retrieve_body( $get );
      if (200 === wp_remote_retrieve_response_code( $get ) && ! is_wp_error( $body ) && ! empty( $body ) ) {
        $data = json_decode($body, true );
        $return['id']       = $video_id;
        $return['type']     = 'youtube';
        $return['provider'] = 'youtube';
        $return['title']    = isset( $data['items'][0]['snippet']['title'] ) && is_string( $data['items'][0]['snippet']['title'] ) ? $data['items'][0]['snippet']['title'] : false;
        $return['duration'] = isset( $data['items'][0]['contentDetails']['duration'] ) ? $data['items'][0]['contentDetails']['duration'] : false;
        if( $return['duration'] ) {
          $interval = new DateInterval($return['duration']);
          $return['duration'] = $interval->h * 3600 + $interval->i * 60 + $interval->s;
          $return['duration'] = gmdate( "H:i:s", $return['duration'] );
        }
        $quality = $hight_quality === true ? 'maxresdefault' : 'mqdefault';
        $return['thumbnail'] = sprintf('https://img.youtube.com/vi/%s/%s.jpg', $video_id, $quality );
      }
    }
  }
  if( isset( $parts['host'] ) && strpos( 'vimeo', $parts['host'] ) !== -1 && isset( $parts['path'] ) ) {
    $video_id = substr($parts['path'], 1);
    $get = wp_remote_get("http://vimeo.com/api/v2/video/".substr($parts['path'], 1).".php");
    $body     = wp_remote_retrieve_body( $get );
    if (200 === wp_remote_retrieve_response_code( $get ) && ! is_wp_error( $body ) && ! empty( $body ) ) {
      $hash = unserialize($body);
      $return['id']       = $video_id;
      $return['provider'] = 'vimeo';
      $return['type']     = 'vimeo';
      $return['title']    = isset( $hash[0]['title'] ) && is_string( $hash[0]['title'] ) ? $hash[0]['title'] : false;
      $return['duration'] = isset( $hash[0]['duration'] ) ? $hash[0]['duration'] : false;
      $return['duration'] = gmdate( "H:i:s", $return['duration'] );
      $quality = $hight_quality === true ? 'thumbnail_large' : 'thumbnail_medium';
      $return['thumbnail'] = isset( $hash[0][$quality] ) ? $hash[0][$quality] : false;
    }
  }
  merimag_update_videos_data( $url, $return );
  return $return;
}
/**
 * Stock videos data
 *
 * @param string $url video url
 * @return array $data list of data
 */
function merimag_get_self_video_data( $item ) {
  if( isset( $item['upload']['url'] ) && !empty( $item['upload']['url'] ) ) {
    $video_url = $item['upload']['url'];
    $vide_id = isset( $item['upload']['id'] ) ? $item['upload']['id'] : false;
    $vide_id = isset( $item['upload']['attachment_id'] ) ? $item['upload']['attachment_id'] : false;
    $meta_data = wp_get_attachment_metadata( $vide_id );
    $data['duration'] = isset( $meta_data['length'] ) ? gmdate('H:i:s', $meta_data['length'] ) : '00:00';
    $data['provider'] = 'self';
    $data['type'] = isset( $meta_data['mime_type'] ) ? $meta_data['mime_type'] : 'video/mp4';
    $data['id']  = $item['upload']['url'];
    merimag_update_videos_data( $video_url, $data );
  }
  return isset( $data ) ? $data : false;
}
/**
 * Stock videos data
 *
 * @param string $url video url
 * @return array $data list of data
 */
function merimag_update_videos_data( $url, $data ) {
  $db_data = get_option('merimag_videos_data');
  if( !isset( $db_data[$url] ) ) {
    $db_data[$url] = $data;
    update_option( 'merimag_videos_data', $db_data );
  }
  set_transient('merimag_videos_data', $db_data, 12 * HOUR_IN_SECONDS );
}
/**
 * Get stocked video
 *
 * @param string $url video url
 * @return array $data list of data
 */
function merimag_get_db_video_data( $url ) {
  $db_data = get_transient('merimag_videos_data');
  return isset( $db_data[$url] ) && is_array( $db_data[$url] ) ? $db_data[$url] : false;
}

/**
 * Search for playlist item video thumbnail
 *
 * @param string $item item object contains video params
 * @return string url of thumbnail
 */
function merimag_get_video_thumbnail_url( $item ) {
  $media_type = isset( $item['media_type'] ) && in_array( $item['media_type'], array('external_video', 'video') ) ? $item['media_type'] : false;
  if( !$media_type ) {
     return;
  }
  switch ($media_type) {
    case 'external_video':
      if( isset( $item['media_picker']['external_video']['url'] ) && !empty( $item['media_picker']['external_video']['url'] ) ) {
        $video_url = $item['media_picker']['external_video']['url'];
        $thumbnail_url = merimag_get_youtube_vimeo_data( $video_url );
        $thumbnail_url = isset( $thumbnail_url['thumbnail'] ) && $thumbnail_url['thumbnail'] ? $thumbnail_url['thumbnail'] : false;
        return $thumbnail_url ? $thumbnail_url : false;
      }
      break;
    
    default:
      # code...
      break;
  }
}
/**
 * Search for playlist item video data
 *
 * @param string $item item object contains video params
 * @return array list of data
 */
function merimag_get_video_data( $item ) {
    $media_type = isset( $item['media_type'] ) && in_array( $item['media_type'], array('external_video', 'video') ) ? $item['media_type'] : false;
    $data = false;
    if( !$media_type ) {
       return;
    }
    $data['title'] = '';
    switch ($media_type) {
      case 'external_video':
        if( isset( $item['url'] ) && !empty( $item['url'] ) ) {
          $video_url = $item['url'];
          $data = merimag_get_db_video_data( $video_url );
          if( !is_array($data) ) {
            $data = merimag_get_youtube_vimeo_data( $video_url );
          }
        }
        break;
      case 'video':
        $video_url = isset( $item['upload']['url'] ) ? $item['upload']['url'] : false;
        $data = merimag_get_db_video_data( $video_url );

        if( !is_array($data) ) {
          $data = merimag_get_self_video_data( $item );
        }
        break;
    }
    $data['title'] = isset( $item['title'] ) && !empty( $item['title'] ) ? $item['title'] : $data['title'];
    return $data;
}
/**
 * Generate Unique ID
 *
 * @param integer $length number of chars
 * @return string random characters 
 */
function merimag_uniqid( $slug = '', $length = 5 ) {
    $random = '';
    $length = is_numeric($length) ? $length : 5;
    for ($i = 0; $i < $length; $i++) {
        $random .= chr(rand(ord('a'), ord('z')));
    }
    return $slug && is_string( $slug ) ? $slug . $random : $random;
}
/**
 * Test variable
 *
 * @param mixed $var
 * @return var details  
 */
function merimag_print_r( $var, $var_dump = false ) {
  echo '<pre><code>';
  $var_dump === true ? var_dump( $var ) : print_r($var);
  echo '</code></pre>';
}
/**
 * Get valid social networks
 *
 * @param bool $keys if true function will return array keys
 * @param string $get a specific network data
 * @return array list of valid social networks data
 */
function merimag_get_recognized_social_networks( $keys = false, $get = 'name' ) {

  $social_networks = array(
      'facebook' => array( 'color' => '#3B5998', 'name' => __('Facebook', 'merimag') , 'icon' => 'icofont-facebook', 'title' => __('Likes', 'merimag'), 'action' => __('Like our page', 'merimag') ),
      'twitter' => array( 'color' => '#55ACEE', 'name' => __('Twitter', 'merimag') , 'icon' => 'icofont-twitter', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'google_plus' => array( 'color' => '#dd4b39', 'name' => __('Google plus', 'merimag') , 'icon' => 'icofont-google-plus', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'linkedin' => array( 'color' => '#0976B4', 'name' => __('LinkedIn', 'merimag') , 'icon' => 'icofont-linkedin', 'title' => __('Subscribers', 'merimag'), 'action' => __('Let\'s connect', 'merimag') ),
      'pinterest' => array( 'color' => '#cd1d1f', 'name' => __('Pinterest', 'merimag') , 'icon' => 'icofont-pinterest', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'foursquare' => array( 'color' => '#8fd400', 'name' => __('Foursquare', 'merimag') , 'icon' => 'icofont-foursquare', 'title' => __('Friends', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'youtube' => array( 'color' => '#CC181E', 'name' => __('Youtube', 'merimag') , 'icon' => 'icofont-youtube-play', 'title' => __('Subscribers', 'merimag'), 'action' => __('Subscribe to our channel', 'merimag') ),
      'instagram' => array( 'color' => '#9c7c6e', 'name' => __('Instagram', 'merimag') , 'icon' => 'icofont-instagram', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'flickr' => array( 'color' => '#ff0085', 'name' => __('Flickr', 'merimag') , 'icon' => 'icofont-flikr', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'vimeo' => array( 'color' => '#1ab7ea', 'name' => __('Vimeo', 'merimag') , 'icon' => 'icofont-vimeo', 'title' => __('Subscribers', 'merimag'), 'action' => __('Subscribe to our channel', 'merimag') ),
      'tumblr' => array( 'color' => '#35465d', 'name' => __('Tumblr', 'merimag') , 'icon' => 'icofont-tumblr', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'stumbleupon' => array( 'color' => '#EB4924', 'name' => __('Stumbleupon', 'merimag') , 'icon' => 'icofont-stumbleupon', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'vk' => array( 'color' => '#4e7db2', 'name' => __('VK', 'merimag') , 'icon' => 'icofont-vk', 'title' => __('Members', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      '500px' => array( 'color' => '#276cc0', 'name' => __('500px', 'merimag') , 'icon' => 'icofont-500px', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'steam' => array( 'color' => '#326489', 'name' => __('Steam', 'merimag') , 'icon' => 'icofont-steam', 'title' => __('Members', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'goodreads' => array( 'color' => '#835a46', 'name' => __('Goodreads', 'merimag') , 'icon' => 'icofont-read-book', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'mixcloud' => array( 'color' => '#4eb0b3', 'name' => __('Mixcloud', 'merimag') , 'icon' => 'fa fa-mixcloud', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'twitch' => array( 'color' => '#6441a4', 'name' => __('Twitch', 'merimag') , 'icon' => 'icofont-twitch', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'dribbble' => array( 'color' => '#ea4c89', 'name' => __('Dribbble', 'merimag') , 'icon' => 'icofont-dribbble', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'delicious' => array( 'color' => '#0B79E5', 'name' => __('Delicious', 'merimag') , 'icon' => 'icofont-delicious', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'soundcloud' => array( 'color' => '#FF3D00', 'name' => __('Soundcloud', 'merimag') , 'icon' => 'icofont-soundcloud', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'github' => array( 'color' => '#171515', 'name' => __('Github', 'merimag') , 'icon' => 'icofont-github', 'title' => __('Github', 'merimag'), 'action' => __('View our code', 'merimag') ),  
      'quora' => array( 'color' => '#BC2016', 'name' => __('Quora', 'merimag') , 'icon' => 'fa fa-quora', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'behance'=> array( 'color' => '#1769FF', 'name' => __('Behance', 'merimag') , 'icon' => 'icofont-behance', 'title' => __('Followers', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'vine'=> array( 'color' => '#00B489', 'name' => __('Vine', 'merimag') , 'icon' => 'icofont-vine', 'title' => __('Subscribers', 'merimag'), 'action' => __('Subscribe to our channel', 'merimag') ),
      'reddit'=> array( 'color' => '#F64720', 'name' => __('Reddit', 'merimag') , 'icon' => 'icofont-reddit', 'title' => __('Reddit', 'merimag'), 'action' => __('Follow us', 'merimag') ),
      'rss'=> array( 'color' => '#f97410', 'name' => __('RSS', 'merimag') , 'icon' => 'icofont-rss', 'title' => __('Subscribe', 'merimag'), 'action' => __('Subscribe now', 'merimag') ),
      'website'=> array( 'color' => '#3b5b7b', 'name' => __('Website', 'merimag') , 'icon' => 'icofont-link', 'title' => __('Website', 'merimag'), 'action' => __('Visit our website ', 'merimag') ),
  );
  $valid_keys = array( 'color', 'name', 'icon', 'title', 'action');
  if( !in_array( $get, $valid_keys) && !isset( $social_networks[$get] ) ) {
      return false;
  }
  if( isset( $social_networks[$get] ) ) {
    return $social_networks[$get];
  }
  foreach( $social_networks as $id => $social_network ) {
    $return[$id] = $get !== 'all' && isset( $social_network[$get] ) ? $social_network[$get] : $social_network;
  }
  return $keys === false ? $return : array_keys($return);
}

// Cache get posts
function merimag_get_posts( $post_type = 'post', $default_choice = false ) {
  $posts = get_posts( array(
      'post_type' => $post_type,
      'numberposts' => -1,
  ) );
  $choices = array();
  if( $default_choice === true ) {
    $choices['default'] = sprintf('-- %s --', esc_html__('Default', 'merimag-backend'));
  }
  foreach( $posts as $post ) {
    $choices[$post->ID] = $post->post_title;
  }
  return $choices;
}
// Cache get terms
function merimag_get_terms( $taxonomy = 'category', $objects = false ) {
  $cached_posts = get_transient('merimag_cache_get_cached_terms');
  $cached_terms_objects = get_transient('merimag_cache_get_cached_terms_objects');
  $enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
  if( $objects === false ) {
    $posts = isset( $cached_posts[$taxonomy] ) && is_array( $cached_posts[$taxonomy] ) && $enable_cache === 'yes' ? $cached_posts[$taxonomy] : get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ) );
  } else {
    $terms = isset( $cached_terms_objects[$taxonomy] ) && $enable_cache === 'yes' ? $cached_terms_objects[$taxonomy] : get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ) );
  }
  if( $objects === true ) {
    if( !isset($cached_terms_objects[$taxonomy]) || $enable_cache !== 'yes' ) {
      $cached_terms_objects[$taxonomy] = $terms;
      set_transient('merimag_cache_get_cached_terms_objects', $cached_terms_objects );
    }
    return $terms;
  } else {
    if( !isset( $cached_posts[$taxonomy] ) || $enable_cache !== 'yes'  ) {
      $choices = array();
      foreach( $posts as $term ) {
        $choices[ $term->term_id] = $term->name;
      }
      $cached_posts[$taxonomy] = $choices;
      set_transient('merimag_cache_get_cached_terms', $cached_posts );
    } else {
      $choices = $posts;
    }
    return $choices;
  }
  
}
// demo slug
function merimag_get_demo_slug() {
  global $wpmdm;
  $slug = '';
  if( class_exists('WPMDM') && isset( $wpmdm->selected_demo_slug )  ) {
     $slug = '_' . $wpmdm->selected_demo_slug;
  }
  return $slug;
}
// demo ad
function merimag_get_demo_ad( $size = '300x250' ) {
  $parse_width = explode('x', $size);
  $width = isset( $parse_width[0] ) ? $parse_width[0] : false;
  if( !$width ) {
    return;
  }
  $url = get_template_directory_uri() . '/assets/images/ad-' . $size . '.png';
  $theme = wp_get_theme();
  $buy_url = $theme->get('ThemeURI');
  $class = $size === '728x90' ? 'vertical-ad' : 'horizontal-ad';
  return '<div style="width: '. esc_attr($width) . 'px" class="merimag-demo-ad ' . esc_attr( $class ) . '"><a target="_blank" href="' . esc_url( $buy_url ) . '"><img src="' .esc_url( $url ) . '"></a><a  target="_blank" href="' . esc_url( $buy_url ) . '" class="merimag-demo-ad-button principal-color-background-color">' . esc_html__('Buy Now', 'merimag') .'</a></div>';
}
// Check ajax referer
function merimag_check_ajax_referer( $key, $nonce ) {
  // ajax not working on cache
  return true;
}
// predefined grid styles
function merimag_get_predefined_grid_style( $style = 'default' ) {
  if( !in_array($style, merimag_get_recognized_predefined_grid_styles(true))) {
    $grid_style = 'default';
  }
  $defaults = merimag_get_default_grid_data();
  if( $style && $style !== 'default' ) {
    $defaults['separator'] = false;
    $defaults['show_read_more'] = false;
    $defaults['spacing'] = 'medium';
  }
  // Defaults reference
  // -----------------------------------------
  //   'columns' => 2, 
  //   'grid_style' => 'simple', 
  //   'title_size' => 'medium',
  //   'sub_title_size' => 'small',
  //   'show_number' => false,
  //   'show_description' => false,
  //   'show_read_more' => false,
  //   'show_category' => true,
  //   'show_format_icon' => true,
  //   'show_review' => true,
  //   'review_display_style' => 'default',
  //   'centered_infos' => false,
  //   'after_title' => 'date|views|comments',
  //   'spacing' => 'default',
  //   'masonry' => false,
  //   'title_length' => false,
  //   'title_ellipsis' => false,
  //   'description_length' => false,
  //   'description_ellipsis' => false,
  //   'pagination' => 'pagination',
  //   'infos_position' => 'left-bottom',
  // -----------------------------------------
  switch ( $style ) {
    case 'classic-1':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'left-classic',
          'show_description' => true,
          'show_read_more' => true,
          'separator' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-2':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'right-classic',
          'title_size' => 'big',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-3':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'left-right-classic',
          'title_size' => 'big',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-4':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'left',
          'separator' => true,
          'title_size' => 'big',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-5':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'right',
          'separator' => true,
          'title_size' => 'big',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-6':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'left',
          'title_size' => 'small',
          'after_title' => 'date',
          'show_review' => false,
          'show_category' => false,
          'show_description' => false,
          'show_read_more' => false,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-7':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'right',
          'title_size' => 'small',
          'after_title' => 'date',
          'show_review' => false,
          'show_category' => false,
          'show_description' => false,
          'show_read_more' => false,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'classic-8':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'left',
          'title_size' => 'small',
          'show_category' => false,
          'after_title' => 'date',
          'show_review' => false,
          'show_category' => false,
          'show_description' => false,
          'show_read_more' => false,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
      );
      break;
    case 'one-column-1':
      $defaults_style = array( 
          'columns' => '1', 
          'grid_style' => 'simple',
          'after_title' => 'author_upper|dash|date|views|comments',
          'title_size' => 'huge',
          'spacing' => 'big',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 3,
          'description_ellipsis' => 5,
          'description_length' => 500,
          'image_size' => 'large',
      );
      break;
    case 'one-column-2':
      $defaults_style = array(
          'columns' => '1', 
          'grid_style' => 'simple',
          'title_size' => 'huge',
          'after_title' => 'author_upper|dash|date|views|comments',
          'separator' => true,
          'spacing' => 'big',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 3,
          'description_ellipsis' => 5,
          'description_length' => 500,
          'image_size' => 'large',
      );
      break;
    case 'one-column-3':
      $defaults_style = array(
          'columns' => '1', 
          'grid_style' => 'bordered',
          'title_ellipsis' => 2,
          'title_size' => 'huge',
          'spacing' => 'big',
          'after_title' => 'author_upper|dash|date|views|comments',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 3,
          'description_ellipsis' => 5,
          'description_length' => 500,
          'image_size' => 'large',
      );
      break;
    case 'one-column-4':
      $defaults_style = array(
          'columns' => '1', 
          'grid_style' => 'absolute',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'title_size' => 'huge',
          'image_height' => 470,
          'spacing' => 'big',
          'after_title' => 'author_upper|dash|date|views|comments',
          'show_description' => true,
          'hover_description' => true,
          'show_read_more' => false,
          'title_ellipsis' => 3,
          'description_ellipsis' => 3,
          'description_length' => 500,
          'image_size' => 'large',
      );
      break;
    case 'one-column-5':
      $defaults_style = array(
          'columns' => '1', 
          'grid_style' => 'modern',
          'title_ellipsis' => 2,
          'title_size' => 'huge',
          'spacing' => 'big',
          'after_title' => 'author_upper|dash|date|views|comments',
          'show_description' => true,
          'show_read_more' => true,
          'title_ellipsis' => 3,
          'description_ellipsis' => 5,
          'description_length' => 500,
         'image_size' => 'large',
       );
      break;
    case 'two-column-1':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'simple',
          'after_title' => 'author_upper|dash|date|views|comments',
          'title_size' => 'big',
          'spacing' => 'extended',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'two-column-2':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'simple',
          'separator' => true,
          'after_title' => 'author_upper|dash|date|views|comments',
          'title_size' => 'big',
          'spacing' => 'extended',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'two-column-3':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'bordered',
          'after_title' => 'author_upper|dash|date|views|comments',
          'title_size' => 'big',
          'spacing' => 'extended',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'two-column-4':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'absolute',
          'after_title' => 'author_upper|dash|date|views|comments',
          'title_size' => 'big',
          'spacing' => 'extended',
          'image_height' => 380,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'hover_description' => true,
      );
      break;
    case 'two-column-5':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'modern',
          'after_title' => 'date|views|comments',
          'title_size' => 'big',
          'spacing' => 'extended',
          'image_ratio' => '4-3',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'three-column-1':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'simple',
          'after_title' => 'date|views|comments',
          'title_size' => 'medium',
          'spacing' => 'extended',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'three-column-2':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'simple',
          'separator' => true,
          'after_title' => 'date|views|comments',
          'title_size' => 'medium',
          'spacing' => 'extended',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'three-column-3':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'bordered',
          'after_title' => 'date|views|comments',
          'title_size' => 'small',
          'spacing' => 'extended',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'three-column-4':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'absolute',
          'after_title' => 'date|views|comments',
          'title_size' => 'medium',
          'spacing' => 'extended',
          'image_height' => 380,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'hover_description' => true,
      );
      break;
    case 'three-column-5':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'modern',
          'after_title' => 'date|comments',
          'title_size' => 'small',
          'spacing' => 'extended',
          'image_ratio' => '4-3',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'four-column-1':
      $defaults_style = array( 
          'columns' => '4',
          'image_ratio' => '4-3',
          'grid_style' => 'simple',
          'review_display_style' => 'simple',
          'after_title' => 'date|comments',
          'title_size' => 'small',
          'spacing' => 'medium',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'four-column-2':
      $defaults_style = array( 
          'columns' => '4', 
          'grid_style' => 'simple',
          'image_ratio' => '4-3',
          'separator' => true,
          'review_display_style' => 'simple',
          'after_title' => 'date|comments',
          'title_size' => 'small',
          'spacing' => 'medium',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'four-column-3':
      $defaults_style = array( 
          'columns' => '4', 
          'grid_style' => 'bordered',
          'image_ratio' => '4-3',
          'after_title' => 'date|comments',
          'review_display_style' => 'simple',
          'title_size' => 'small',
          'spacing' => 'medium',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'four-column-4':
      $defaults_style = array( 
          'columns' => '4',
          'image_ratio' => '4-3',
          'grid_style' => 'absolute',
          'after_title' => 'date|comments',
          'review_display_style' => 'simple',
          'title_size' => 'small',
          'spacing' => 'medium',
          'image_height' => 380,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'hover_description' => true,
      );
      break;
    case 'four-column-5':
      $defaults_style = array( 
          'columns' => '4', 
          'grid_style' => 'modern',
          'image_ratio' => '4-3',
          'after_title' => 'date|comments',
          'review_display_style' => 'simple',
          'title_size' => 'small',
          'spacing' => 'medium',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'five-column-1':
      $defaults_style = array( 
          'columns' => '5', 
          'grid_style' => 'simple',
          'image_ratio' => '1-1',
          'after_title' => 'date',
          'title_size' => 'small',
          'review_display_style' => 'simple',
          'spacing' => 'small',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'five-column-2':
      $defaults_style = array( 
          'columns' => '5', 
          'grid_style' => 'simple',
          'review_display_style' => 'simple',
          'image_ratio' => '1-1',
          'separator' => true,
          'after_title' => 'date',
          'title_size' => 'small',
          'spacing' => 'small',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'five-column-3':
      $defaults_style = array( 
          'columns' => '5', 
          'grid_style' => 'bordered',
          'image_ratio' => '1-1',
          'after_title' => 'date',
          'review_display_style' => 'simple',
          'title_size' => 'small',
          'spacing' => 'small',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'five-column-4':
      $defaults_style = array( 
          'columns' => '5', 
          'grid_style' => 'absolute',
          'review_display_style' => 'simple',
          'after_title' => 'date',
          'title_size' => 'tiny',
          'spacing' => 'small',
          'image_height' => 320,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'hover_description' => true,
      );
      break;
    case 'five-column-5':
      $defaults_style = array( 
          'columns' => '5', 
          'grid_style' => 'modern',
          'review_display_style' => 'simple',
          'image_ratio' => '1-1',
          'after_title' => 'date',
          'title_size' => 'tiny',
          'spacing' => 'small',
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'masonry-1':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'simple',
          'image_height' => '200',
          'separator' => false,
          'review_display_style' => 'simple',
          'after_title' => 'date|comments',
          'title_size' => 'big',
          'spacing' => 'extended',
          'masonry' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'masonry-2':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'bordered',
          'image_height' => '200',
          'separator' => false,
          'review_display_style' => 'simple',
          'after_title' => 'date|comments',
          'title_size' => 'small',
          'spacing' => 'extended',
          'masonry' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'masonry-3':
      $defaults_style = array( 
          'columns' => '4', 
          'grid_style' => 'simple',
          'image_height' => '200',
          'separator' => true,
          'review_display_style' => 'simple',
          'after_title' => 'date|comments',
          'title_size' => 'small',
          'spacing' => 'extended',
          'masonry' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'show_read_more' => true,
      );
      break;
    case 'masonry-4':
      $defaults_style = array( 
          'columns' => '3', 
          'grid_style' => 'absolute',
          'image_height' => '200',
          'separator' => false,
          'review_display_style' => 'simple',
          'after_title' => 'date|comments',
          'title_size' => 'small',
          'spacing' => 'extended',
          'masonry' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => true,
          'hover_description' => true,
      );
      break;
    case 'masonry-5':
      $defaults_style = array( 
          'columns' => '2', 
          'grid_style' => 'modern',
          'image_height' => '200',
          'separator' => false,
          'review_display_style' => 'modern',
          'after_title' => 'author_upper|dash|date|comments',
          'title_size' => 'medium',
          'spacing' => 'extended',
          'masonry' => true,
          'title_ellipsis' => 2,
          'description_ellipsis' => 3,
          'show_description' => false,
          'show_read_more' => false,
      );
      break;
    default:
      $defaults_style = array();
      break;
  }
  return array_merge( $defaults, $defaults_style );
}