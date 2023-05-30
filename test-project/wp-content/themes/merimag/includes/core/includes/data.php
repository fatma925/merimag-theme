<?php
/**
 * Get recognized block title style
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid block title styles
 */
function merimag_get_recognized_block_title_styles( $keys = false ) {
  $styles['default'] = sprintf( '-- %s --', __('Default', 'merimag') );
  for( $i= 1; $i<=17; $i++) {
    $styles[ 'style-' . $i ] = sprintf(__('Style %s', 'merimag'), $i);
  }
  $styles = apply_filters('merimag_get_recognized_block_title_styles', $styles );
  return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get image sizes
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid image sizes
 */
function merimag_get_recognized_image_sizes( $keys = false ) {
  $sizes = array(
    'default' => __('Default', 'merimag'),
    'small' => __('Small', 'merimag'),
    'medium' => __('Medium', 'merimag'),
    'big' => __('Big', 'merimag'),
    'large' => __('Large', 'merimag'),
    'full' => __('Full', 'merimag'),
  );
  return $keys === true ? array_keys( $sizes ) : $sizes;
}
/**
 * Get image size
 *
 * @param string $size merimag_get_recognized_image_sizes()
 *
 * @return array width and height
 */
function merimag_get_image_size( $size = 'medium', $only_width = false ) {
  switch ($size) {
      case 'small':
          return $only_width == false ? array(200, 150, 'bfi_thumb' => true) : array(  200, false, 'bfi_thumb' => true );
          break;
      case 'medium':
          return $only_width == false ? array(400, 300, 'bfi_thumb' => true) : array(  400, false, 'bfi_thumb' => true );
          break;
      case 'big':
          return $only_width == false ? array(800, 600, 'bfi_thumb' => true) : array(  800, false, 'bfi_thumb' => true );
          break;
      case 'large':
          return $only_width == false ? array(1200, 800, 'bfi_thumb' => true) : array(  1200, false, 'bfi_thumb' => true );
          break;
      case 'full':
          return $only_width == false ? array(1920, 1080, 'bfi_thumb' => true) : array(  1920, false, 'bfi_thumb' => true );
          break;
      default:
          return $only_width == false ? array(400, 300, 'bfi_thumb' => true) : array(  400, false, 'bfi_thumb' => true );
          break;
  }
}
/**
 * Get image size by height
 *
 * @param int $height
 *
 * @return string $size merimag_get_recognized_image_sizes()
 */
function merimag_get_image_size_by_height( $height = 200 ) {
  $size = 'medium';
  if( $height <= 150 ) {
    $size = 'small';
  }
  if( $height <= 300 && $height > 150 ) {
    $size = 'medium';
  }
  if( $height <= 600 && $height > 300  ) {
    $size = 'big';
  }
  if( $height <= 800 && $height > 600  ) {
    $size = 'full';
  }
  if( $height <= 1080 && $height > 800  ) {
    $size = 'large';
  }
  return merimag_get_image_size( $size );
}
/**
 * Get valid gallery styles
 *
 * @param bool $keys if true function will return array keys
 * @return array list of valid gallery styles
 */
function merimag_get_recognized_gallery_styles( $keys = false ) {
  $styles = array(
    'tiles' => __('Tiles', 'merimag'),
    'tilesgrid' => __('Tiles grid', 'merimag'),
    'slider' => __('Slider', 'merimag'),
    'default' => __('Slider with thumbs', 'merimag'),
    'compact' => __('Slider with thumbs 2', 'merimag'),
    'grid' => __('Slider width grid thumbs', 'merimag'),
    'carousel' => __('Carousel', 'merimag'),
  );
  return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get recognized order by options
 *
 * @param string $post_type
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid query order by options
 */
function merimag_get_recognized_order_by_options( $post_type = 'post', $keys = false ) {
    
    switch ($post_type) {
        case 'post':
        case 'post-mix':
            $options = array(
                 'none' => esc_html__( 'None', 'merimag'),
                 'ID'  => esc_html__( 'ID', 'merimag'),
                 'author' => esc_html__( 'Author', 'merimag'),
                 'title' => esc_html__( 'Title', 'merimag'),
                 'name' => esc_html__( 'Slug', 'merimag'),
                 'type' => esc_html__( 'Type', 'merimag'),
                 'date' => esc_html__( 'Date', 'merimag'),
                 'modified' => esc_html__( 'Date of modification', 'merimag'),
                 'parent' => esc_html__( 'Parent ID', 'merimag'),
                 'rand' => esc_html__( 'Random', 'merimag'),
                 'comment_count' => esc_html__( 'Comment count', 'merimag'),
                 'relevance' => esc_html__( 'Relevance', 'merimag'),
                 'menu_order' => esc_html__( 'Menu order', 'merimag'),
                 'views' => esc_html__( 'Post views', 'merimag'),
                 'review_score' => esc_html__( 'Review score', 'merimag'),
                 'post__in' => esc_html__( 'Post in', 'merimag'),
            );
            break;
        case 'product':
            $options = array(
                 'none' => esc_html__( 'None', 'merimag'),
                 'popularity' => esc_html__( 'Popularity', 'merimag'),
                 'price' => esc_html__( 'Price', 'merimag'),
                 'rating' => esc_html__( 'Average rating', 'merimag'),
                 'ID'  => esc_html__( 'ID', 'merimag'),
                 'author' => esc_html__( 'Author', 'merimag'),
                 'title' => esc_html__( 'Title', 'merimag'),
                 'name' => esc_html__( 'Slug', 'merimag'),
                 'type' => esc_html__( 'Type', 'merimag'),
                 'date' => esc_html__( 'Date', 'merimag'),
                 'modified' => esc_html__( 'Date of modification', 'merimag'),
                 'parent' => esc_html__( 'Parent ID', 'merimag'),
                 'rand' => esc_html__( 'Random', 'merimag'),
                 'comment_count' => esc_html__( 'Comment count', 'merimag'),
                 'relevance' => esc_html__( 'Relevance', 'merimag'),
                 'menu_order' => esc_html__( 'Menu order', 'merimag'),
            );
            break;
    }
    return $keys === true ? array_keys( $options ) : $options;
}
/**
 * Get recognized order options
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid query order options
 */
function merimag_get_recognized_order_options( $keys = false ) {
    $orders = array(
        'desc' => esc_html__( 'Descending', 'merimag'),
        'asc'  => esc_html__( 'Ascending', 'merimag'),
    );
    return $keys === true ? array_keys( $orders ) : $orders;
}
/**
 * Get recognized grid columns options
 *
 * @param bool $keys if true the function return only the keys
 * @param mixed $for the area that will use this columns
 *
 * @return array the list of valid columns options
 */
function merimag_get_recognized_grid_columns( $keys = false, $for = false ) {
    switch ($for) {
      case false:
        $columns = array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        );
        break;
      case 'widget':
        $columns = array(
            '2' => '2',
            '3' => '3',
        );
        break;
      case 'general':
        $columns = array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        );
        break;
    }
    
    return $keys === true ? array_keys( $columns ) : $columns;
}
/**
 * Get recognized infos position options
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid infos position options
 */
function merimag_get_recognized_infos_positions( $keys = false ) {
  $positions = array( 
    'left-bottom' => esc_html__('Left bottom', 'merimag'),
    'left-top' => esc_html__('Left top', 'merimag'), 
    'left-center' => esc_html__('Left center', 'merimag'),
    'center-center' => esc_html__('Center center', 'merimag'), 
    'center-top' => esc_html__('Center top', 'merimag'), 
    'center-bottom' => esc_html__('Center bottom', 'merimag'), 
    'right-top' => esc_html__('Right top', 'merimag'), 
    'right-center' => esc_html__('Right center', 'merimag'), 
    'right-bottom' => esc_html__('Right top', 'merimag') 
  );
  return $keys === true ? array_keys( $positions ) : $positions;
}
/**
 * Get recognized carousel columns options
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid carousel columns options
 */
function merimag_get_recognized_carousel_columns( $keys = false ) {
  $columns = array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
    );
    return $keys === true ? array_keys( $columns ) : $columns;
}
/**
 * Get recognized custom content grid styles
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid grid styles options
 */
function merimag_get_recognized_custom_grid_styles( $keys = false ) {
  $styles = array(
        'simple' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 300
            ),
        ),
        'simple-separed' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple-separed.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple-separed.png',
                'height' => 300
            ),
        ),
        'bordered' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/bordered.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/bordered.png',
                'height' => 300
            ),
        ),
        'modern' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 300
            ),
        ),
        'absolute' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 300
            ),
        ),
        'image' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 300
            ),
        ),

    );
    return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get recognized grid styles for posts or products
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid grid styles options
 */
function merimag_get_recognized_grid_styles( $keys = false ) {
  $styles = array(
        'text' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/text.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/text.png',
                'height' => 300
            ),
        ),
        'simple' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 300
            ),
        ),
        'modern' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 300
            ),
        ),
        'absolute' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 300
            ),
        ),
        'image' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 300
            ),
        ),
        'left-classic' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left-classic.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left-classic.png',
                'height' => 300
            ),
        ),
        'right-classic' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/right-classic.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/right-classic.png',
                'height' => 300
            ),
        ),
        'left-right-classic' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left-right-classic.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left-right-classic.png',
                'height' => 300
            ),
        ),
        'right' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/right.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/right.png',
                'height' => 300
            ),
        ),
        'left' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left.png',
                'height' => 300
            ),
        ),

    );
    return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get recognized grid styles for widgets
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid grid styles options
 */
function merimag_get_recognized_grid_styles_for_widget( $keys = false ) {
  $styles = array(
        'text' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/text.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/text.png',
                'height' => 300
            ),
        ),
        'simple' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 300
            ),
        ),
        'modern' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 300
            ),
        ),
        'absolute' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 300
            ),
        ),
        'image' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 300
            ),
        ),
        
    );
    return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get recognized grid styles for widgets
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid grid styles options
 */
function merimag_get_recognized_list_styles_for_widget( $keys = false ) {
  $styles = array(
        'text' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/text.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/text.png',
                'height' => 300
            ),
        ),
        'simple' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple.png',
                'height' => 300
            ),
        ),
        'simple-separed' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple-separed.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/simple-separed.png',
                'height' => 300
            ),
        ),
        'bordered' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/bordered.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/bordered.png',
                'height' => 300
            ),
        ),
        'modern' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/modern.png',
                'height' => 300
            ),
        ),
        'absolute' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/absolute.png',
                'height' => 300
            ),
        ),
        'image' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/image.png',
                'height' => 300
            ),
        ),
        'left' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/left.png',
                'height' => 300
            ),
        ),
        'right' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/right.png',
                'height' => 80
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/right.png',
                'height' => 300
            ),
        ),
        
    );
    return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get recognized title sizes
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid title sizes options
 */
function merimag_get_recognized_title_sizes( $keys = false ) {
  $title_sizes = array(
    'tiny'   => esc_html__('Tiny', 'merimag'),
    'small'  => esc_html__('Small', 'merimag'),
    'normal' => esc_html__('Normal', 'merimag'),
    'medium' => esc_html__('Medium', 'merimag'),
    'big'    => esc_html__('Big', 'merimag'),
    'huge'   => esc_html__('Huge', 'merimag'),
  );
  return $keys === true ? array_keys( $title_sizes ) : $title_sizes;
}
/**
 * Get recognized grid spacing
 *
 * @param bool $keys if true the function return only the keys
 *
 * @return array the list of valid grid spacing options
 */
function merimag_get_recognized_grid_spacing( $keys = false ) {
  $spacing = array(
        'default' => esc_html__('Default spacing', 'merimag'),
        'no'      => esc_html__('No spacing', 'merimag'),
        'tiny'   => esc_html__('Tiny spacing', 'merimag'),
        'small'   => esc_html__('Small spacing', 'merimag'),
        'medium'  => esc_html__('Medium spacing', 'merimag'),
        'extended'  => esc_html__('Extended spacing', 'merimag'),
        'wide'  => esc_html__('Wide spacing', 'merimag'),
        'big'     => esc_html__('Big spacing', 'merimag'),
    );
    return $keys === true ? array_keys( $spacing ) : $spacing;
}
/**
 * Get recognized after title
 *
 * @param bool $keys if true the function return only the keys
 * @param string $page the name of the page that will use this options
 *
 * @return array the list of valid after title options
 */
function merimag_get_recognized_after_title( $keys = false, $page = 'index' ) {
  $after_title = array(
        'author_upper|dash|date' => esc_html__('Author upper date', 'merimag'),
        'author_upper' => esc_html__('Author upper', 'merimag'),
        'author_image|dash|date|views|comments' => esc_html__('Author image date views comments', 'merimag'),
        'author_image' => esc_html__('Author image', 'merimag'),
        'author_upper|dash|date|views|comments' => esc_html__('Author date views comments', 'merimag'),
        'author_upper|dash|date|review|views|comments' => esc_html__('Author date review views comments', 'merimag'),
        'author_image|dash|date' => esc_html__('Author image date', 'merimag'),
        'date|views|comments' => esc_html__('Date views comments', 'merimag'),
        'date|views|comments|review' => esc_html__('Date comments review', 'merimag'),
        'review|date|views|comments' => esc_html__('Review date views comments', 'merimag'),
        'date' => esc_html__('Date', 'merimag'),
        'date_icon' => esc_html__('Date icon', 'merimag'),
        'review' => esc_html__('Review', 'merimag'),
        'views' => esc_html__('Views', 'merimag'),
        'comments_text' => esc_html__('Comments', 'merimag'),
        'hide' => esc_html__('Hide', 'merimag'),
    );
    if( $page === 'shop' ) {
        $after_title = array(
            'price|product_rating' => esc_html__('Price rating', 'merimag'),
        );
    }
    return $keys === true ? array_keys( $after_title ) : $after_title;
}
/**
 * Get recognized entrance animations
 *
 * @return array the list of valid entrance animations
 */
function merimag_get_recognized_in_animations( $groups = true ) {

  $animations = array(
    array( 
      'attr' => array('label' => 'Default'), 
      'choices' => array(
        'Default',

        ) 
    ),
    array( 
      'attr' => array('label' => 'Attention Seekers'), 
      'choices' => array(
        'bounce',
        'flash',
        'pulse',
        'rubberBand',
        'shake',
        'swing',
        'tada',
        'wobble',
        'jello',
        ) 
    ),
    array( 
      'attr' =>  array('label' => 'Bouncing Entrances'), 
      'choices' => array(
        'bounceIn',
        'bounceInDown',
        'bounceInLeft',
        'bounceInRight',
        'bounceInUp',
        ) 
    ),
    array( 
      'attr' =>  array('label' => 'Fading Entrances'), 
      'choices' => array(
        'fadeIn',
        'fadeInDown',
        'fadeInDownBig',
        'fadeInLeft',
        'fadeInLeftBig',
        'fadeInRight',
        'fadeInRightBig',
        'fadeInUp',
        'fadeInUpBig',
        ) 
    ),
    array( 
      'attr' =>  array('label' => 'Flippers'), 
      'choices' => array(
        'flip',
        'flipInX',
        'flipInY',

        ) 
      ),
    array( 
      'attr' =>  array('label' => 'Lightspeed'), 
      'choices' => array(
        'lightSpeedIn',
        ) 
      ),
    array( 
      'attr' =>  array('label' => 'Rotating Entrances'), 
      'choices' => array(
        'rotateIn',
        'rotateInDownLeft',
        'rotateInDownRight',
        'rotateInUpLeft',
        'rotateInUpRight',
        ) 
      ),

    array( 
      'attr' =>  array('label' => 'Sliding Entrances'), 
      'choices' => array(
        'slideInUp',
        'slideInDown',
        'slideInLeft',
        'slideInRight',

        ) 
      ),

    array( 
      'attr' =>  array('label' => 'Zoom Entrances'), 
      'choices' => array(
        'zoomIn',
        'zoomInDown',
        'zoomInLeft',
        'zoomInRight',
        'zoomInUp',
        ) 
      ),        

    array( 
      'attr' =>  array('label' => 'Specials'), 
      'choices' => array(
        'rollIn',
        ) 
      )

    );
  foreach( $animations as $key_group => $group ) {
    foreach( $group['choices'] as $key => $animation ) {
      if( $groups === true ) {
        unset( $animations[$key_group]['choices'][$key] );
        $animations[$key_group]['choices'][$animation] = $animation;
      } else {
        $return[$animation] = $animation;
      }
      
    }
  }
  return $groups === true ? $animations : $return;

}
/**
 * Get recognized out animations
 *
 * @return array the list of valid out animations
 */
function merimag_get_recognized_out_animations() {

  $animations = array(

    array( 
      'label' => 'Attention Seekers', 
      'items' => array(
        'bounce',
        'flash',
        'pulse',
        'rubberBand',
        'shake',
        'swing',
        'tada',
        'wobble',
        'jello',
        ) 
      ),

    array( 
      'label' => 'Bouncing Exits', 
      'items' => array(
        'bounceOut',
        'bounceOutDown',
        'bounceOutLeft',
        'bounceOutRight',
        'bounceOutUp',
        ) 
      ),

    array( 
      'label' => 'Fading Exits', 
      'items' => array(
        'fadeOut',
        'fadeOutDown',
        'fadeOutDownBig',
        'fadeOutLeft',
        'fadeOutLeftBig',
        'fadeOutRight',
        'fadeOutRightBig',
        'fadeOutUp',
        'fadeOutUpBig',
        ) 
      ),
    array( 
      'label' => 'Flippers', 
      'items' => array(
        'flip',
        'flipOutX',
        'flipOutY',
        ) 
      ),
    array( 
      'label' => 'Lightspeed', 
      'items' => array(
        'lightSpeedOut',
        ) 
      ),

    array( 
      'label' => 'Rotating Exits', 
      'items' => array(
        'rotateOut',
        'rotateOutDownLeft',
        'rotateOutDownRight',
        'rotateOutUpLeft',
        'rotateOutUpRight',
        ) 
      ),

    array( 
      'label' => 'Sliding Exits', 
      'items' => array(
        'slideOutUp',
        'slideOutDown',
        'slideOutLeft',
        'slideOutRight',

        ) 
      ),

    array( 
      'label' => 'Zoom Exits', 
      'items' => array(
        'zoomOut',
        'zoomOutDown',
        'zoomOutLeft',
        'zoomOutRight',
        'zoomOutUp',
        ) 
      ),
    array( 
      'label' => 'Specials', 
      'items' => array(
        'hinge',
        'rollOut',
        ) 
      )

    );

  return $animations;

}
/**
 * Get recognized font families
 *
 * @return array the list of valid font families
 */
function merimag_get_recognized_font_families( $sf = true ) {

  $families = array();

  if( $sf === true ) {

      $default_families = array(
          'arial'     => 'Arial',
          'georgia'   => 'Georgia',
          'helvetica' => 'Helvetica',
          'palatino'  => 'Palatino',
          'tahoma'    => 'Tahoma',
          'times'     => 'Times New Roman',
          'trebuchet' => 'Trebuchet',
          'verdana'   => 'Verdana'
      );
      
      foreach( (array) $default_families as $key => $font ) {
          $families[$key] = $font;
      }
  }
  $google_fonts = merimag_get_google_fonts();

  if( is_array( $google_fonts ) ) {
      foreach( $google_fonts as $k => $font ) {
        if( !is_string($font)) {
          continue;
        }
        $families[$k] = $font;
      }
  }

  return apply_filters( 'merimag_get_recognized_font_families', $families );

}
/**
 * Get recognized css background ( position, repeat, attachment, size )
 *
 * @param string $option can be background_position, background_repeat, background_size, background_attachment
 * @param bool $keys return only keys if this is true
 * @return array the list of valid background choices for the chosen option
 */
function merimag_get_recognized_background( $option, $keys = false ) {
  if( !$option ) {
     return;
  }
  $choices = array();
  switch ($option) {
    case 'background_position':
      $choices = array(
        'center center'  => esc_html__('center center', 'merimag'),
        'top center'     => esc_html__('top center', 'merimag'),
        'bottom center'  => esc_html__('bottom center', 'merimag'),
        'top left'       => esc_html__('top left', 'merimag'),
        'bottom left'    => esc_html__('bottom left', 'merimag'),
        'top right'      => esc_html__('Top right', 'merimag'),
        'bottom right'   => esc_html__('bottom right', 'merimag'),
      );
      break;
    case 'background_repeat':
      $choices = array(
        'no-repeat' => esc_html__('No repeat', 'merimag'),
        'repeat-x'  => esc_html__('Repeat horizontally', 'merimag'),
        'repeat-y'  => esc_html__('Repeat vertically', 'merimag'),
        'repeat'    => esc_html__('Repeat all', 'merimag'),
      );
      break;
    case 'background_attachment':
      $choices = array(
        'scroll' => esc_html__('Scroll', 'merimag'),
        'fixed'  => esc_html__('Fixed', 'merimag'),
        'local'  => esc_html__('Local', 'merimag'),
      );
      break;
    case 'background_size':
      $choices = array(
        'auto' => esc_html__('Auto', 'merimag'),
        'cover'    => esc_html__('Cover', 'merimag'),
        'contain'  => esc_html__('Contain', 'merimag'),
        '100% 100%'     => esc_html__('Fill', 'merimag'),
      );
      break;
  }
  return $keys === true ? array_keys( $choices ) : $choices;
}
/**
 * Get recognized css border styles
 *
 * @param bool $keys return only keys if this is true
 * @return array the list of valid border styles
 */
function merimag_get_recognized_border_styles( $keys = false ) {
    $styles = array(
      'solid'  => 'Solid',
      'hidden' => 'Hidden',
      'dashed' => 'Dashed',
      'dotted' => 'Dotted',
      'double' => 'Double',
      'groove' => 'Groove',
      'ridge'  => 'Ridge',
      'inset'  => 'Inset',
      'outset' => 'Outset',
    );
    return $keys === false ? apply_filters( 'merimag_get_recognized_border_styles', $styles ) : array_keys( $styles );
}
/**
 * Get recognized css positions
 *
 * @param bool $keys return only keys if this is true
 * @return array the list of valid css positions
 */
function merimag_get_recognized_posistions( $keys = false ) {
    $positions = array(
      'top'  => 'Top',
      'bottom' => 'Bottom',
      'left' => 'Left',
      'right' => 'Right',
    );
    return $keys === false ? apply_filters( 'merimag_get_recognized_posistions', $positions ) : array_keys( $positions );
}
/**
 * Get recognized css skin colors
 *
 * @return array the list of valid skin colors
 */
function merimag_get_recognized_skin_colors() {
    $colors = array(
        'default' => '#ed4849',
        'marron'  => '#e1743e',
        'green'   => '#93c24f',
        'blue'    => '#4f9bc2',
        'orange'  => '#f59329',
        'pistachio' => '#c2ff59',
        'purple' => '#de54f3',
        'pink' => '#fb42d2',
        'yellow' => '#f8fe5c',
        'green-2' => '#46a114',
        'red-2' => '#ca2f45',
        'blue-2' => '#4484d3',
        'blue-3' => '#3cd2cd',
        'pink-2' => '#fd9cd7',
    );
}
/**
 * Get valid html tag
 *
 * @param arrat $atts list of arguments
 * @param bool $keys if true function will return array keys
 * @return array list of valid html tags
 */
function merimag_get_recognized_element_tags( $keys = false ) {
  $tags = array(
    'div'  => 'div',
    'p'    => 'p',
    'span' => 'span',
    'h1'   => 'h1',
    'h2'   => 'h2',
    'h3'   => 'h3',
    'h4'   => 'h4',
    'h5'   => 'h5',
    'h6'   => 'h6',
  );
  return $keys === true ? array_keys( $tags ) : $tags; 
}
/**
 * Get valid button styles
 *
 * @param bool $keys if true function will return array keys
 * @return array list of valid button styles
 */
function merimag_get_recognized_button_styles( $keys = false ) {
  $styles = array(
    'flat' => __('Flat', 'merimag'),
    'bordered' => __('Bordered', 'merimag'),
  );
  return $keys === true ? array_keys( $styles ) : $styles;
}
/**
 * Get valid social icons display styles
 *
 * @param bool $keys if true function will return array keys
 * @return array list of valid icons display styles
 */
function merimag_get_recognized_social_icons_display_styles( $keys = false ) {
  for( $i = 1; $i <=5; $i++ ) {
    $styles [$i] = sprintf(__('Style %s', 'merimag'), $i);
  }
  return $keys === true ? array_keys( $styles ) : $styles;
}



/**
 * Recognized font weights
 *
 * Returns an array of all recognized font weights.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 */
function merimag_get_recognized_font_weights( $keys = false ) {
  $weights = apply_filters( 'merimag_get_recognized_font_weights', array(
    'normal'    => 'Normal',
    'bold'      => 'Bold',
    'bolder'    => 'Bolder',
    'lighter'   => 'Lighter',
    '100'       => '100',
    '200'       => '200',
    '300'       => '300',
    '400'       => '400',
    '500'       => '500',
    '600'       => '600',
    '700'       => '700',
    '800'       => '800',
    '900'       => '900',
    'inherit'   => 'Inherit'
  ) );
  return $keys === true ? array_keys( $weights ) : $weights;
}

/**
 * Recognized text transformations
 *
 * Returns an array of all recognized text transformations.
 * Keys are intended to be stored in the database
 * while values are ready for display in html.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 */
function merimag_get_recognized_text_transformations( $keys = false ) {
    $transforms = apply_filters( 'merimag_get_recognized_text_transformations', array(
      'capitalize'  => 'Capitalize',
      'inherit'     => 'Inherit',
      'lowercase'   => 'Lowercase',
      'none'        => 'None',
      'uppercase'   => 'Uppercase'
    ));
    return $keys === true ? array_keys( $transforms ) : $transforms;
}
/**
 * Recognized icons themes
 *
 * @return    array
 *
 */
function merimag_get_recognized_icons_themes( $only_icon = false ) {
  $choices = array();
  $max = $only_icon === true ? 7 : 10;
  for( $i = 1; $i <= $max; $i++) {
    $choices['theme-' . esc_attr( $i )] = array(
        'small' => array(
            'src' => MERIMAG_CORE_URL .sprintf('/assets/img/social-icons/%s.png', esc_attr($i) ),
            'height' => 50
        ),
        'large' => array(
            'src' => MERIMAG_CORE_URL .sprintf('/assets/img/social-icons/%s.png', esc_attr($i) ),
            'height' => 75
        ),
    );
  }
  return $choices;
}
function merimag_get_block_style_number( $block_style ) {
    $blocks = array(
                'mix-7' => 5,
                'mix-8' => 5,
                'mix-11' => 7,
                'mix-12' => 7,
                'mix-18' => 5,
                'mix-19' => 5,
                'tiled-2' => 5,
                'tiled-3' => 4,
                'tiled-4' => 5,
                'tiled-5' => 5,
                'tiled-6' => 5,
                'tiled-7' => 7, 
                'tiled-8' => 6,
                'tiled-9' => 5,
                'tiled-10' => 6,
                'mix-4' => 4,
                'mix-5' => 4,
                'mix-6' => 4,
                'mix-23' => 4,
                'mix-21' => 5,
                'mix-22' =>7,  
                'mix-1' => 5,
                'mix-2' => 5,
                'mix-3' => 5,
                'mix-9' => 3,
                'mix-10' => 3,
                'mix-13' => 4,
                'mix-14' => 4,
                'mix-15' => 5,
                'mix-16' => 6,
                'mix-17' => 6,
                'mix-20' => 5,
                'tiled-1' => 3,
                
            );
    return isset( $blocks[$block_style]) ? $blocks[$block_style] : get_option('posts_per_page');
}
/**
 * Recognized blocks
 *
 * @return    array
 *
 */
function merimag_get_recognized_blocks( $shortcode = 'posts' ) {
	$blocks = array();
	switch ($shortcode) {
		case 'posts':
			$blocks = array(
				'mix-7' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/7.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/7.png',
		                'height' => 200
		            ),
		        ),
		        'mix-8' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/8.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/8.png',
		                'height' => 200
		            ),
		        ),
		        'mix-11' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/11.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/11.png',
		                'height' => 200
		            ),
		        ),
		        'mix-12' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/12.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/12.png',
		                'height' => 200
		            ),
		        ),
		        'mix-18' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/18.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/18.png',
		                'height' => 200
		            ),
		        ),
		        'mix-19' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/19.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/19.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-2' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-2.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-2.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-3' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-3.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-3.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-4' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-4.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-4.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-5' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-5.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-5.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-6' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-6.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-6.png',
		                'height' => 200
		            ),
		        ),
                'tiled-7' => array(
                    'small' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-7.png',
                        'height' => 100
                    ),
                    // (optional) url for large image that will appear in tooltip
                    'large' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-7.png',
                        'height' => 200
                    ),
                ), 
                'tiled-8' => array(
                    'small' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-8.png',
                        'height' => 100
                    ),
                    // (optional) url for large image that will appear in tooltip
                    'large' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-8.png',
                        'height' => 200
                    ),
                ),
                'tiled-9' => array(
                    'small' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-9.png',
                        'height' => 100
                    ),
                    // (optional) url for large image that will appear in tooltip
                    'large' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-9.png',
                        'height' => 200
                    ),
                ),
                'tiled-10' => array(
                    'small' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-10.png',
                        'height' => 100
                    ),
                    // (optional) url for large image that will appear in tooltip
                    'large' => array(
                        'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-10.png',
                        'height' => 200
                    ),
                ),
				'mix-4' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/4.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/4.png',
		                'height' => 200
		            ),
		        ),
		        'mix-5' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/5.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/5.png',
		                'height' => 200
		            ),
		        ),
		        'mix-6' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/6.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/6.png',
		                'height' => 200
		            ),
		        ),
            'mix-23' => array(
                'small' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/23.png',
                    'height' => 100
                ),
                // (optional) url for large image that will appear in tooltip
                'large' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/23.png',
                    'height' => 200
                ),
            ),
		        'mix-21' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/21.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/21.png',
		                'height' => 200
		            ),
		        ),
		        'mix-22' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/22.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/22.png',
		                'height' => 200
		            ),
		        ),	
				'mix-1' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/1.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/1.png',
		                'height' => 200
		            ),
		        ),
		        'mix-2' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/2.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/2.png',
		                'height' => 200
		            ),
		        ),
		        'mix-3' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/3.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/3.png',
		                'height' => 200
		            ),
		        ),
		        'mix-9' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/9.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/9.png',
		                'height' => 200
		            ),
		        ),
		        'mix-10' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/10.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/10.png',
		                'height' => 200
		            ),
		        ),
		        'mix-13' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/13.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/13.png',
		                'height' => 200
		            ),
		        ),
		        'mix-14' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/14.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/14.png',
		                'height' => 200
		            ),
		        ),
		        'mix-15' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/15.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/15.png',
		                'height' => 200
		            ),
		        ),
		        'mix-16' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/16.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/16.png',
		                'height' => 200
		            ),
		        ),
		        'mix-17' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/17.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/17.png',
		                'height' => 200
		            ),
		        ),

		        'mix-20' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/20.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/20.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-1' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-1.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-1.png',
		                'height' => 200
		            ),
		        ),
		        
			);
			break;
    case 'posts_widget':
      $blocks = array(
        'mix-4' => array(
                'small' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/4.png',
                    'height' => 100
                ),
                // (optional) url for large image that will appear in tooltip
                'large' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/4.png',
                    'height' => 200
                ),
            ),
            'mix-5' => array(
                'small' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/5.png',
                    'height' => 100
                ),
                // (optional) url for large image that will appear in tooltip
                'large' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/5.png',
                    'height' => 200
                ),
            ),
            'mix-6' => array(
                'small' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/6.png',
                    'height' => 100
                ),
                // (optional) url for large image that will appear in tooltip
                'large' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/6.png',
                    'height' => 200
                ),
            ),
            'mix-23' => array(
                'small' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/23.png',
                    'height' => 100
                ),
                // (optional) url for large image that will appear in tooltip
                'large' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/23.png',
                    'height' => 200
                ),
            ),
            'mix-21' => array(
                'small' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/21.png',
                    'height' => 100
                ),
                // (optional) url for large image that will appear in tooltip
                'large' => array(
                    'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/21.png',
                    'height' => 200
                ),
            ),  
      );
      break;
		case 'custom_tiled':
			$blocks = array(
				'tiled-7' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-7.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-7.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-2' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-2.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-2.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-3' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-3.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-3.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-4' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-4.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-4.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-5' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-5.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-5.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-6' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-6.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-6.png',
		                'height' => 200
		            ),
		        ),
		        'tiled-1' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-1.png',
		                'height' => 100
		            ),
		            // (optional) url for large image that will appear in tooltip
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/tiled-1.png',
		                'height' => 200
		            ),
		        ),
		        
			);
			break;
		default:
			# code...
			break;
	}
	return $blocks;
}
/**
 * Recognized contact types
 *
 * @return    array
 *
 */
function merimag_get_recognized_contact_types( $keys = false ) {
  $types = array(
    'phone' => __('Phone number', 'merimag-backend'),
    'mobile_phone' => __('Mobile phone number', 'merimag-backend'),
    'email_address' => __('Email address', 'merimag-backend'),
    'address' => __('Address', 'merimag-backend'),
    'open_hours' => __('Open hours', 'merimag-backend'),
  );
  return $keys === true ? array_keys( $types ) : $types;
}
/**
 * Recognized title box styles
 *
 * @return    array
 *
 */
function merimag_get_recognized_title_box_styles( $keys = false ) {
	$blocks = array();
	$title_boxes = array(
		'simple' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/simple.png',
                'height' => 100
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/simple.png',
                'height' => 200
            ),
        ),
		'bordered' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/bordered.png',
                'height' => 100
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/bordered.png',
                'height' => 200
            ),
        ),
        'dark-background' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/dark-background.png',
                'height' => 100
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/dark-background.png',
                'height' => 200
            ),
        ),
        'dark-background-border-top' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/dark-background-border-top.png',
                'height' => 100
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/dark-background-border-top.png',
                'height' => 200
            ),
        ),
        'white-background' => array(
            'small' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/white-background.png',
                'height' => 100
            ),
            'large' => array(
                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/title-box/white-background.png',
                'height' => 200
            ),
        ),

	);
	return $keys === true ? array_keys( $title_boxes ) : $title_boxes;
}
/**
 * Recognized sliders
 *
 * @return    array
 *
 */
function merimag_get_recognized_sliders( $shortcode = 'posts' ) {
	$blocks = array();
	switch ($shortcode) {
		case 'posts':
		case 'custom_content':
			$blocks = array(
				'slider-1' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/1.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/1.png',
		                'height' => 200
		            ),
		        ),
		        'slider-2' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/2.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/2.png',
		                'height' => 200
		            ),
		        ),
		        'slider-3' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/3.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/3.png',
		                'height' => 200
		            ),
		        ),
		        'slider-4' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/4.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/4.png',
		                'height' => 200
		            ),
		        ),
		        'slider-10' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/10.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/10.png',
		                'height' => 200
		            ),
		        ),
			);
			break;
		case 'thumbs':
			$blocks = array(
		        'slider-5' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/5.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/5.png',
		                'height' => 200
		            ),
		        ),
		        'slider-6' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/6.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/6.png',
		                'height' => 200
		            ),
		        ),
		        'slider-7' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/7.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/7.png',
		                'height' => 200
		            ),
		        ),
		        'slider-8' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/8.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/8.png',
		                'height' => 200
		            ),
		        ),
		        'slider-9' => array(
		            'small' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/9.png',
		                'height' => 100
		            ),
		            'large' => array(
		                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/slider/9.png',
		                'height' => 200
		            ),
		        ),
			);
			break;

	}
	return $blocks;
}
/**
 * Recognized blocks grouped
 *
 * @return    array
 *
 */
function merimag_get_recognized_blocks_grouped( $shortcode = 'posts' ) {
	$blocks = array();
	switch ($shortcode) {
		case 'posts':
			$blocks = array(
				'wide' => array(
					'style' => array(
						'type'  => 'image-picker',
						'choices' => array(
							'mix-7' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/7.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/7.png',
					                'height' => 200
					            ),
					        ),
					        'mix-8' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/8.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/8.png',
					                'height' => 200
					            ),
					        ),
					        'mix-11' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/11.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/11.png',
					                'height' => 200
					            ),
					        ),
					        'mix-12' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/12.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/12.png',
					                'height' => 200
					            ),
					        ),
					        'mix-18' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/18.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/18.png',
					                'height' => 200
					            ),
					        ),
					        'mix-19' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/19.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/19.png',
					                'height' => 200
					            ),
					        ),
						),
					),
				),
				'one-column' => array(
					'style' => array(
						'type'  => 'image-picker',
						'choices' => array(
							'mix-4' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/4.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/4.png',
					                'height' => 200
					            ),
					        ),
					        'mix-5' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/5.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/5.png',
					                'height' => 200
					            ),
					        ),
					        'mix-6' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/6.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/6.png',
					                'height' => 200
					            ),
					        ),
						),
					),
				),
				'two-column' => array(
					'style' => array(
						'type' => 'image-picker',
						'choices' => array(
							'mix-1' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/1.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/1.png',
					                'height' => 200
					            ),
					        ),
					        'mix-2' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/2.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/2.png',
					                'height' => 200
					            ),
					        ),
					        'mix-3' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/3.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/3.png',
					                'height' => 200
					            ),
					        ),
					        'mix-9' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/9.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/9.png',
					                'height' => 200
					            ),
					        ),
					        'mix-10' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/10.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/10.png',
					                'height' => 200
					            ),
					        ),
					        'mix-13' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/13.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/13.png',
					                'height' => 200
					            ),
					        ),
					        'mix-14' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/14.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/14.png',
					                'height' => 200
					            ),
					        ),
					        'mix-15' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/15.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/15.png',
					                'height' => 200
					            ),
					        ),
					        'mix-16' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/16.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/16.png',
					                'height' => 200
					            ),
					        ),
					        'mix-17' => array(
					            'small' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/17.png',
					                'height' => 100
					            ),
					            // (optional) url for large image that will appear in tooltip
					            'large' => array(
					                'src' => MERIMAG_CORE_URL .'/assets/img/blocks/mix/17.png',
					                'height' => 200
					            ),
					        ),
						),
					),
				),
			);
			break;
		
		default:
			# code...
			break;
	}

	return $blocks;
}
function merimag_get_elementor_image_picker_label( $key) {
  $blocks = array(
    'mix-4' => '(1) column 1',
    'mix-5' => '(1) column 2',
    'mix-6' => '(1) column 3',
    'mix-21' => '(1) column 4',
    'mix-22' => '(1) column 5',
    'mix-23' => '(1) column 6',
    'mix-1' => '(2) columns 1',
    'mix-2' => '(2) columns 2',
    'mix-3' => '(2) columns 3',
    'mix-9' => '(2) columns 4',
    'mix-10' => '(2) columns 5',
    'mix-13' => '(2) columns 6',
    'mix-14' => '(2) columns 7',
    'mix-15' => '(2) columns 8',
    'mix-16' => '(2) columns 9',
    'mix-17' => '(2) columns 10',
    'mix-20' => '(2) columns 11',
    'tiled-1' => '(2) columns 12 ( Tiled )',
    'mix-7' => '(3) columns 1',
    'mix-8' => '(3) columns 2',
    'mix-11' => '(3) columns 3',
    'mix-12' => '(3) columns 4',
    'mix-18' => '(3) columns 5',
    'mix-19' => '(3) columns 6',
    'tiled-2' => 'Tiled 2',
    'tiled-3' => 'Tiled 3',
    'tiled-4' => 'Tiled 4',
    'tiled-5' => 'Tiled 5',
    'tiled-6' => 'Tiled 6',
    'tiled-7' => 'Tiled 7',
    'tiled-8' => 'Tiled 8',
    'tiled-9' => 'Tiled 9',
    'tiled-10' => 'Tiled 10',
  );
  return isset( $blocks[$key] ) ? $blocks[$key] : false;
}
/**
 * Recognized pagination options
 *
 * @return    array
 *
 */
function merimag_recognized_pagination_options( $listing = false, $keys = false ) {
	$pagination_options = array(
	    'default' => esc_html__('Default', 'merimag-backend'),
	    'load_more' => esc_html__('Ajax load more', 'merimag-backend'),
	    'next_prev' => esc_html__('Ajax Next / Prev', 'merimag-backend'),
      'infinite_scroll' => esc_html__('Infinite scroll', 'merimag-backend'),
	    'ajax_pagination' => esc_html__('Ajax Pagination', 'merimag-backend'),

	);
	if( $listing === true ) {
		$pagination_options['pagination'] = esc_html__('Pagination', 'merimag-backend');
		$pagination_options['newer_older'] = esc_html__('Newer / Older', 'merimag-backend');
	} else {
    $pagination_options['block_pagination'] = esc_html__('Pagination', 'merimag-backend');
  }
  if( $keys === true ) {
    $pagination_options['pagination'] = esc_html__('Pagination', 'merimag-backend');
    $pagination_options['newer_older'] = esc_html__('Newer / Older', 'merimag-backend');
    $pagination_options['block_pagination'] = esc_html__('Pagination', 'merimag-backend');
  }
	return $keys === true ? array_keys( $pagination_options ) : $pagination_options;
}
/**
 * @return string JSON encoded array with Google fonts
 */
function merimag_get_google_fonts( $items = false ) {

    $saved_data = get_transient( 'merimag_new_google_api_fonts_list_data' );

    if ( !$saved_data ) {
        $response = wp_remote_get( apply_filters( 'fw_googleapis_webfonts_url',
            'https://raw.githubusercontent.com/jonathantneal/google-fonts-complete/master/api-response.json' ) );
        $body     = wp_remote_retrieve_body( $response );
        if (
            200 === wp_remote_retrieve_response_code( $response )
            &&
            ! is_wp_error( $body ) && ! empty( $body )
        ) {
            $saved_data['items'] = json_decode(  $body, true );
          set_transient( 'merimag_new_google_api_fonts_list_data', $saved_data, MONTH_IN_SECONDS );
          
        }
    }

    // Change the object to a multidimensional array.
    $fonts_array = $saved_data;
    // Change the array key to the font's ID.
    if( !isset( $fonts_array['items'] ) || !is_array( $fonts_array['items'] ) ) {
        return;
    } 
    foreach ( $fonts_array['items'] as $key => $font ) {

        $variants_remove = array(
            'italic',
            '100italic',
            '200italic',
            '300italic',
            '400italic',
            '500italic',
            '600italic',
            '700italic',
            '800italic',
            '900italic',
        );

        $font['variants'] = array_diff( $font['variants'], $variants_remove );

        $font['variants'] = str_replace( 'regular', '400', $font['variants'] );

        $font['variants'] = array_flip( $font['variants'] );

        $weights = array(
            '100' => esc_html__( 'Thin', 'merimag' ),
            '200' => esc_html__( 'Extra Light', 'merimag' ),
            '300' => esc_html__( 'Light', 'merimag' ),
            '400' => esc_html__( 'Normal', 'merimag' ),
            '500' => esc_html__( 'Medium', 'merimag' ),
            '600' => esc_html__( 'Semi Bold', 'merimag' ),
            '700' => esc_html__( 'Bold', 'merimag' ),
            '800' => esc_html__( 'Extra Bold', 'merimag' ),
            '900' => esc_html__( 'Ultra Bold', 'merimag' ),
        );

        foreach ( $font['variants'] as $k => $v ) {
            $font['variants'][ $k ] = $weights[ $k ];
        }

        $font['variants']['0'] = esc_html__( '- Default -', 'merimag' );

        $fonts_array['items'][ $key ] = $font;

    }

    // Change the array key to the font's ID.
    foreach ( $fonts_array['items'] as $font ) {
        $id           = trim( str_replace( ' ', '-', $font['family'] ) );
        $fonts[ $id ] = $items === true ? $font : $font['family'];
    }
    return $fonts;
}

/**
 * Core shortcodes maping
 *
 * @return void
 */
function merimag_get_shortcodes_list( $keys = false ) {
	$shortcodes = apply_filters('merimag_get_shortcodes_list', array(
		'about' => array(
      'title' => __('About site', 'merimag'),
      'icon' => 'eicon-welcome',
      'desc' => __('Show your besiness or personal infos with social icons in multiple layouts', 'merimag')
    ),
		'alert' => array(
      'title' => __('Alert', 'merimag'),
      'icon' => 'eicon-alert',
      'desc' => __('Show alert messages with icons and color selection', 'merimag'),
    ),
		'action' => array(
      'title' => __('Action', 'merimag'),
      'icon' => 'eicon-call-to-action',
      'desc' => __('Show call to action with multiple layouts', 'merimag'),
    ),
		'button' => array(
      'title' => __('Button', 'merimag'),
      'icon' => 'eicon-button',
      'desc' => __('Show button with multiple styles', 'merimag'),
    ),
    'multi-buttons' => array(
      'title' => __('Multi buttons', 'merimag'),
      'icon' => 'eicon-dual-button',
      'desc' => __('Show multi buttons side by side with multiple styles', 'merimag'),
    ),
		'divider' => array(
      'title' => __('Divider', 'merimag'),
      'icon' => 'eicon-divider',
      'desc' => __('Show divider with multiple styles', 'merimag'),
    ),
		'dropcap' => array(
      'title' => __('Dropcap', 'merimag'),
      'icon' => 'eicon-typography',
      'desc' => __('Show dropcap with multiple styles', 'merimag'),
    ),
		'spacing' => array(
      'title' => __('Spacing', 'merimag'),
      'icon' => 'eicon-spacer',
      'desc' => __('Empty spacing to separe content', 'merimag'),
    ),
		'heading' => array(
      'title' => __('Heading', 'merimag'),
      'icon' => 'eicon-heading',
      'desc' => __('Add headings to your design', 'merimag'),
    ),
		'special-heading' => array(
      'title' => __('Special Heading', 'merimag'),
      'icon' => 'eicon-heading special',
      'desc' => __('Add special headings to your design over 15 styles', 'merimag'),
    ),
		'menu' => array(
      'title' => __('Menu items', 'merimag'),
      'icon' => 'eicon-menu-bar',
      'desc' => __('Add custom menu to your design', 'merimag'),
    ),
    'custom-list' => array(
      'title' => __('Custom content list', 'merimag'),
      'icon' => 'fa fa-list',
      'desc' => __('Add custom list with icons to your design', 'merimag'),
    ),
		'tabs' => array(
      'title' => __('Tabs', 'merimag'),
      'icon' => 'eicon-tabs',
      'desc' => __('Add custom tabs to your design', 'merimag'),
    ),
		'accordion' => array(
      'title' => __('Accordion', 'merimag'),
      'icon' => 'eicon-accordion',
      'desc' => __('Add custom accordion to your design', 'merimag'),
    ),
		'icon' => array(
      'title' => __('Icon', 'merimag'),
      'icon' => 'eicon-heart-o',
      'desc' => __('Add icons in multiple styles', 'merimag'),
    ),
		'icon-box' => array(
      'title' => __('Icon box', 'merimag'),
      'icon' => 'eicon-icon-box',
      'desc' => __('Custom text and icon to highlight your content', 'merimag'),
    ),
		'search' => array(
      'title' => __('Search', 'merimag'),
      'icon' => 'eicon-search-bold',
      'desc' => __('Search box can be added to your pages', 'merimag'),
    ),
    'member' => array(
      'title' => __('Team member', 'merimag'),
      'icon' => 'fa fa-user',
      'desc' => __('Add team member to your design', 'merimag'),
    ),
		'author' => array(
      'title' => __('Author', 'merimag'),
      'icon' => 'fa fa-user',
      'desc' => __('Wordpress author infos and social networks', 'merimag'),
    ),
		'authors' => array(
      'title' => __('Authors list', 'merimag'),
      'icon' => 'fa fa-users',
      'desc' => __('Wordpress authors infos and social networks', 'merimag'),
    ),
		'gallery' => array(
      'title' => __('Slider Gallery', 'merimag'),
      'icon' => 'eicon-slider-album',
      'desc' => __('Nice image gallery with multiple options', 'merimag'),
    ),
		'gallery-tiles' => array(
      'title' => __('Tiles Gallery', 'merimag'),
      'icon' => 'eicon-gallery-justified',
      'desc' => __('Nice tiles image gallery with multiple options', 'merimag'),
    ),
		'gallery-tilesgrid' => array(
      'title' => __('Grid Gallery', 'merimag'),
      'icon' => 'eicon-gallery-masonry',
      'desc' => __('Nice tiles masonry image gallery with multiple options', 'merimag'),
    ),
		'gallery-carousel' => array(
      'title' => __('Carousel Gallery', 'merimag'),
      'icon' => 'eicon-carousel',
      'desc' => __('Nice carousel image gallery with multiple options', 'merimag'),
    ),
		'mailchimp' => array(
      'title' => __('Mailchimp form', 'merimag'),
      'icon' => 'fa fa-envelope-square',
      'desc' => __('Add mailchimp form you your pages', 'merimag'),
    ),
		'embed' => array(
      'title' => __('Embed', 'merimag'),
      'icon' => 'fa fa-globe',
      'desc' => __('Embed content from multiple sources, see <a href="https://wordpress.org/support/article/embeds/">This article</a> for more infos', 'merimag'),
    ),
		'video' => array(
      'title' => __('Video', 'merimag'),
      'icon' => 'eicon-youtube',
      'desc' => __('Add responsive self hosted video or external video from youtube, vimeo and more', 'merimag'),
    ),
		'image' => array(
      'title' => __('Image', 'merimag'),
      'icon' => 'fa fa-picture-o',
      'desc' => __('Simple image', 'merimag'),
    ),
		'ticker' => array(
      'title' => __('News ticker', 'merimag'),
      'icon' => 'eicon-posts-ticker',
      'desc' => __('Show posts news ticker that scroll beautifully', 'merimag'),
    ),
	'posts-block' => array(
      'title' => __('Posts mix', 'merimag'),
      'icon' => 'eicon-posts-group',
      'desc' => __('Show your posts in more than 20 layouts mixed', 'merimag'),
    ),
		'posts-carousel' => array(
      'title' => __('Posts carousel', 'merimag'),
      'icon' => 'eicon-posts-carousel',
      'desc' => __('Show your posts in carousel with easy configuration', 'merimag'),
    ),
		'posts-grid' => array(
      'title' => __('Posts grid', 'merimag'),
      'icon' => 'eicon-posts-grid',
      'desc' => __('Show your posts in grid style with multiple ways and a lot of options', 'merimag'),
    ),
    'simple-posts-grid' => array(
      'title' => __('Simple posts grid', 'merimag'),
      'icon' => 'eicon-posts-grid',
      'desc' => __('Show your posts in grid style with multiple ways and minimum options', 'merimag'),
    ),
		'posts-list' => array(
      'title' => __('Posts list', 'merimag'),
      'icon' => 'eicon-post-list',
      'desc' => __('Show your posts in list style with multiple ways and a lot of options', 'merimag'),
    ),
    'simple-posts-list' => array(
      'title' => __('Simple posts list', 'merimag'),
      'icon' => 'eicon-post-list another-color',
      'desc' => __('Show your posts in list style with multiple ways and minimum options', 'merimag'),
    ),
		'posts-slider-thumbs' => array(
      'title' => __('Posts slider with thumbs', 'merimag'),
      'icon' => 'eicon-post-slider',
      'desc' => __('Show your posts in a slider with thumbnails, 5 styles available', 'merimag'),
    ),
		'posts-slider' => array(
      'title' => __('Posts slider', 'merimag'),
      'icon' => 'eicon-post-slider',
      'desc' => __('Show your posts in a slider with a lot of options', 'merimag'),
    ),
		'products-carousel' => array(
      'title' => __('Products carousel', 'merimag'),
      'icon' => 'eicon-posts-carousel',
      'desc' => __('Show your posts in a carousel with a lot of options', 'merimag'),
    ),

		'products-grid' => array(
      'title' => __('Products grid', 'merimag'),
      'icon' => 'eicon-posts-grid',
      'desc' => __('Show your products in a grid with a lot of options', 'merimag'),
    ),
		'products-list' => array(
      'title' => __('Products list', 'merimag'),
      'icon' => 'eicon-posts-carousel',
      'desc' => __('List your products with a lot of options', 'merimag'),
    ),
        'tabbed-widget' => array(
      'title' => __('Tabbed widget', 'merimag'),
      'icon' => 'eicon-tabs',
      'desc' => __('Show recent posts, popular posts and latest comments in tabs', 'merimag'),
    ),
       'popular-categories' => array(
      'title' => __('Popular categories', 'merimag'),
      'icon' => 'eicon-editor-list-ul',
      'desc' => __('Show popular categories', 'merimag'),
    ),
		'quotation' => array(
      'title' => __('Quotation', 'merimag'),
      'icon' => 'fa fa-quote-left',
      'desc' => __('Add beautiful quotations to your design, more than 11 styles', 'merimag'),
    ),
		'review' => array(
      'title' => __('Product eview', 'merimag'),
      'icon' => 'eicon-review',
      'desc' => __('Add product reviews to your design', 'merimag'),
    ),
		'social-icons' => array(
      'title' => __('Social icons', 'merimag'),
      'icon' => 'eicon-social-icons',
      'desc' => __('Add social icons to your design with a lot of options', 'merimag'),
    ),
		'category-carousel' => array(
      'title' => __('Categories carousel', 'merimag'),
      'icon' => 'eicon-carousel another-color',
      'desc' => __('Add your categories with image and posts count in carousel', 'merimag'),
    ),
		'category-grid' => array(
      'title' => __('Categories grid', 'merimag'),
      'icon' => 'eicon-posts-grid another-color',
      'desc' => __('Add your categories with image and posts count in grid', 'merimag'),
    ),
		'product-category-carousel' => array(
      'title' => __('Product categories carousel', 'merimag'),
      'icon' => 'eicon-carousel product-color',
      'desc' => __('Add your product categories with image and products count in carousel', 'merimag'),
    ),
		'product-category-grid' => array(
      'title' => __('Product categories grid', 'merimag'),
      'icon' => 'eicon-posts-grid product-color',
      'desc' => __('Add your product categories with image and products count in grid', 'merimag'),
    ),
		'video-playlist'  => array(
      'title' => __('Video playlist', 'merimag'),
      'icon' => 'eicon-slider-video',
      'desc' => __('Add video playlist to your design, support youtube, vimeo and custom uploads', 'merimag'),
    ),
		'contact-infos' => array(
      'title' => __('Contact infos', 'merimag'),
      'merimag', 'icon' => 'eicon-info-circle',
      'desc' => __('Add contact infos to your design', 'merimag'),
    ),
		'image-box' => array(
      'title' => __('Image box', 'merimag'),
      'merimag', 'icon' => 'eicon-image-box',
      'desc' => __('Add attractive image with text in different styles', 'merimag'),
    ),
    
    'comments' => array(
      'title' => __('Recent comments', 'merimag'),
      'merimag', 'icon' => 'eicon-comments',
      'desc' => __('Recent comments list', 'merimag'),
    ),
    'wp-menu' => array(
      'title' => __('Wordpress menu', 'merimag'),
      'icon' => 'eicon-menu-bar another-color',
      'desc' => __('Add Wordpress native menu', 'merimag'),
    ),
    'demo-ad' => array(
      'title' => __('Demo ad', 'merimag'),
      'icon' => 'fa fa-picture-o another-color'
    ),
	));
	return $keys === true ? array_keys($shortcodes) : $shortcodes;
}

/**
 * Get recognized predifined grid styles
 *
 * @param bool $keys if true the function return only the keys
 * @param string $page the name of the page that will use this options
 *
 * @return array the list of valid predifined grid styles options
 */
function merimag_get_recognized_predefined_grid_styles( $keys = false, $page = 'index' ) {
  $styles = apply_filters('merimag_predefined_grid_styles', array(
    'default' => sprintf('-- %s --',__('Default', 'merimag-backend')),
    'classic-1' => __('Classic #1', 'merimag-backend'),
    'classic-2' => __('Classic #2', 'merimag-backend'),
    'classic-3' => __('Classic #3', 'merimag-backend'),
    'classic-4' => __('Classic #4', 'merimag-backend'),
    'classic-5' => __('Classic #5', 'merimag-backend'),
    'classic-6' => __('Classic #6', 'merimag-backend'),
    'classic-7' => __('Classic #7', 'merimag-backend'),
    'classic-8' => __('Classic #8', 'merimag-backend'),
    'one-column-1' => __('One column #1', 'merimag-backend'),
    'one-column-2' => __('One column #2', 'merimag-backend'),
    'one-column-3' => __('One column #3', 'merimag-backend'),
    'one-column-4' => __('One column #4', 'merimag-backend'),
    'one-column-5' => __('One column #5', 'merimag-backend'),
    'two-column-1' => __('Two column #1', 'merimag-backend'),
    'two-column-2' => __('Two column #2', 'merimag-backend'),
    'two-column-3' => __('Two column #3', 'merimag-backend'),
    'two-column-4' => __('Two column #4', 'merimag-backend'),
    'two-column-5' => __('Two column #5', 'merimag-backend'),
    'three-column-1' => __('Three column #1', 'merimag-backend'),
    'three-column-2' => __('Three column #2', 'merimag-backend'),
    'three-column-3' => __('Three column #3', 'merimag-backend'),
    'three-column-4' => __('Three column #4', 'merimag-backend'),
    'three-column-5' => __('Three column #5', 'merimag-backend'),
    'four-column-1' => __('Four column #1', 'merimag-backend'),
    'four-column-2' => __('Four column #2', 'merimag-backend'),
    'four-column-3' => __('Four column #3', 'merimag-backend'),
    'four-column-4' => __('Four column #4', 'merimag-backend'),
    'four-column-5' => __('Four column #5', 'merimag-backend'),
    'five-column-1' => __('Five column #1', 'merimag-backend'),
    'five-column-2' => __('Five column #2', 'merimag-backend'),
    'five-column-3' => __('Five column #3', 'merimag-backend'),
    'five-column-4' => __('Five column #4', 'merimag-backend'),
    'five-column-5' => __('Five column #5', 'merimag-backend'),
    'masonry-1' => __('Masonry #1', 'merimag-backend'),
    'masonry-2' => __('Masonry #2', 'merimag-backend'),
    'masonry-3' => __('Masonry #3', 'merimag-backend'),
    'masonry-4' => __('Masonry #4', 'merimag-backend'),
    'masonry-5' => __('Masonry #4', 'merimag-backend'),
    'custom' => __('Custom', 'merimag-backend'),
  ));
  return $keys === true ? array_keys($styles) : $styles;
}