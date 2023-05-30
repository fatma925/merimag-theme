<?php
/**
 * merimag functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package merimag
 */
$theme   		 = wp_get_theme();
$theme_version 	 = $theme->get('Version');

define('THEME_VERSION', $theme_version );

if(is_admin()) {
	require get_template_directory() . '/includes/theme-update-checker.php';
	$GLOBALS['update_checker'] = new ThemeUpdateChecker(
		'merimag', //Theme slug. Usually the same as the name of its directory.
		'https://webte.studio/wp-update-server/?action=get_metadata&slug=merimag' //Metadata URL.
	);
}

require_once( get_template_directory() . '/includes/core/core.php');


/**
 * Options helper
 * 
 */

require_once( get_template_directory() . '/includes/customizer-helper.php');

/**
 * Google Fonts helper
 * 
 */

require_once( get_template_directory() . '/includes/fonts.php');

/**
 * Widgets Helper
 * 
 */

require_once( get_template_directory() . '/includes/widgets.php');


/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/includes/woocommerce.php';
}


require_once( get_template_directory() . '/includes/share.php');

$cached_version  = get_transient('merimag_theme_version');
if( $cached_version && version_compare( $theme_version, $cached_version) !== 0 ) {
	merimag_delete_customizer_cache();
	merimag_delete_cache();
	delete_transient('merimag_options');
	set_transient('merimag_theme_version', $theme_version );
} else {
	if( !$cached_version ) {
		set_transient('merimag_theme_version', $theme_version );
	}
}
/**
 * Theme setup actions
 * 
 * @return void
 */
if ( ! function_exists( 'merimag_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function merimag_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on merimag, use a find and replace
		 * to change 'merimag' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'merimag', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-formats', array( 'video', 'image', 'gallery', 'audio' ) );


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary menu', 'merimag' ),
			'mobile-menu' => esc_html__( 'Mobile menu', 'merimag' ),
			'secondary-menu' => esc_html__( 'Secondry menu', 'merimag' ),
			'secondary-menu-mobile' => esc_html__( 'Secondry menu mobile', 'merimag' ),
			'footer-menu' => esc_html__( 'Footer menu', 'merimag' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		/*
		 * Align wide
		 */
		add_theme_support( 'align-wide' );
		/*
		 * Custom logo
		 */
		remove_theme_support( 'custom-logo' );
		
		/*
		 * Custom background
		 */
		remove_theme_support( 'custom-background' );

		remove_theme_support( 'widgets-block-editor' );
		

	}
endif;
add_action( 'after_setup_theme', 'merimag_setup' );
function merimag_toc() {
	$post_type = get_post_type();
	if( $post_type !== 'post' ) {
		return;
	}
	$content_index = merimag_get_db_customizer_option('content_index');
	if( is_single() && $content_index === 'yes' ) {
		echo '<div class="merimag-toc-container principal-color-background-color">';
		echo '<span class="merimag-toc-toggle" title="' . esc_html__('Table of contents', 'merimag') .'"><i class="icofont-listing-number"></i></span>';
		echo '<ul id="merimag-toc" class="content-background"></ul>';
		echo '</div>';
	}
	return;
}


/**
 * Load theme admin assets
 * 
 * @return void
 */
function merimag_load_admin_assets() {

	wp_enqueue_script( 'merimag-admin-js', get_template_directory_uri() . '/assets/js/admin.js', array(  'jquery' ), THEME_VERSION , true );
	wp_enqueue_script( 'merimag-init-js', get_template_directory_uri() . '/assets/js/init.js', array( 'jquery' ), THEME_VERSION , false );
	wp_enqueue_style( 'merimag-admin-animate.css', get_template_directory_uri() . '/assets/css/animate.css', array(), THEME_VERSION );
	wp_enqueue_style( 'merimag-admin-css', get_template_directory_uri() . '/assets/css/admin.css', array() );
	$localized_array['ajax'] 	   = admin_url( 'admin-ajax.php' );
	$localized_array['nonce'] 	   = wp_create_nonce( 'merimag_options' );
	wp_localize_script( 'merimag-admin-js', 'merimag_theme', $localized_array );
	if( merimag_is_gutenberg_active() && defined('FW') && fw_ext('shortcodes') ) {
		wp_enqueue_style( 'merimag-gurenberg-css', get_template_directory_uri() . '/assets/css/gutenberg.css' , array() );
		wp_enqueue_script(
			'fw-ext-shortcodes-editor-integration',
			fw_ext('shortcodes')->get_uri('/static/js/aggressive-coder.js'),
			array('fw'),
			fw_ext('shortcodes')->manifest->get('version'),
			true
		);

		wp_enqueue_script(
			'fw-ext-shortcodes-load-shortcodes-data',
			fw_ext('shortcodes')->get_uri('/static/js/load-shortcodes-data.js'),
			array('fw'),
			fw_ext('shortcodes')->manifest->get('version'),
			true
		);
	}
	if( defined('FW') ) {
		wp_enqueue_script( 'merimag-widgets-admin-js', get_template_directory_uri() . '/assets/js/widgets-admin.js', array( 'jquery' ), THEME_VERSION , true );
	    wp_enqueue_style( 'merimag-widgets-admin-css', get_template_directory_uri() . '/assets/css/widgets-admin.css', array(), THEME_VERSION );
	}
	wp_enqueue_style( 'merimag-font-awesome', get_template_directory_uri() . '/assets/css/fa/css/font-awesome.min.css', array(), THEME_VERSION );
}
add_action('admin_enqueue_scripts', 'merimag_load_admin_assets', 99 );

/**
 * Check for gutenberg
 * 
 * @return bool
 */
function merimag_is_gutenberg_active() {
    if ( function_exists( 'is_gutenberg_page' ) &&
            is_gutenberg_page()
    ) {
        // The Gutenberg plugin is on.
        return true;
    }
    $current_screen = get_current_screen();
    if ( method_exists( $current_screen, 'is_block_editor' ) &&
            $current_screen->is_block_editor()
    ) {
        // Gutenberg page on 5+.
        return true;
    }
    return false;
}


/**
 * Menu cart button
 * 
 * @return void
 */
function merimag_get_menu_cart_link( $stacked = false, $sidebar_id = '' ) {
	if( function_exists('wc_get_cart_url')) {
		$count = WC()->cart->get_cart_contents_count();
		$class = $stacked === true ? 'merimag-stacked-icon' : 'merimag-header-icon';
		$color = merimag_get_db_customizer_option('header_cart_icon_color', merimag_get_principal_color());
		$text_color = merimag_get_text_color_from_background( $color );
		$style = $stacked === true ? sprintf('background:%s; color:%s;', esc_attr($color), esc_attr($text_color)) : '';
		if( $sidebar_id ) {
			return sprintf('<a href="%s"  data-id="%s" style="%s" class="merimag-cart-icon merimag-sidebar-opener %s"><i class="icon-bag"><span class="merimag-cart-count popular-color-background-color ">%s</span></i></a>', esc_url( wc_get_cart_url() ), esc_attr( $sidebar_id ),  esc_attr($style), esc_attr( $class ), esc_attr( $count) );
		} else {
			return sprintf('<a href="%s" style="%s" class="merimag-cart-icon %s"><i class="icon-bag"><span class="merimag-cart-count popular-color-background-color">%s</span></i></a>', esc_url( wc_get_cart_url() ),  esc_attr($style), esc_attr( $class ), esc_attr( $count) );
		}
		
	}
}
/**
 * Menu search button
 * 
 * @return void
 */
function merimag_get_menu_social( $header_icon = false ) {
	$social_links = merimag_get_db_customizer_option('social_links');
	$html  = '';
	if( is_array( $social_links ) && !empty( $social_links ) ) {
		$html .= '<div class="merimag-menu-social ">';
		foreach( $social_links as $social_link ) {
			$network  = isset( $social_link['network'] ) && in_array($social_link['network'], merimag_get_recognized_social_networks( true, 'name') ) ? $social_link['network'] : 'link';
			$data     = merimag_get_recognized_social_networks( false, $network );
			$link  	  = isset( $social_link['link'] ) && !empty( $social_link['link'] ) ? $social_link['link'] : '#';
			$title    = isset( $social_link['title'] ) && !empty( $social_link['title'] ) ? $social_link['title'] : ( isset( $data['title'] ) ? $data['title'] : __('Follow us', 'merimag') );
			$icon 	  = isset( $data['icon'] ) && !empty( $data['icon'] ) !== 'link' ? $data['icon'] : 'fa-link';
			$class    = $header_icon === true ? ' merimag-header-icon ' : '';
			$html 	 .= sprintf('<a class="merimag-menu-social-button %s" href="%s" rel="nofollow" title="%s">', esc_attr( $class ), esc_url( $link ), esc_attr( $title ) );
			$html 	 .= sprintf('<span class="merimag-menu-line-height  custom-menu-item-content "><i class="%s"></i></span>', esc_attr( $icon ) );
			$html 	 .= '</a>';
		}
		$html .= '</div>';
	}

	return $html;
}
/**
 * TGM Plugin Activation
 */
{	
	if( is_admin() ) {

		require_once get_template_directory() . '/tgm/class-tgm-plugin-activation.php';

		/** @internal */
		function _action_theme_register_required_plugins() {
			tgmpa( array(
				array(
					'name'      => 'Unyson',
					'slug'	    => 'unyson',
					'required'  => true,
				),
				array(
					'name'      => 'Core Plugin',
					'slug' 		=> 'merimag-core-plugin',
					'source'    => 'https://webte.studio/wp-update-server/?action=download&slug=merimag-core-plugin', // The plugin source.
					'required'  => true,
				),
				array(
					'name'      => 'Import demos',
					'slug' 		=> 'multi-demo-master-import',
					'source'    => 'https://webte.studio/wp-update-server/?action=download&slug=multi-demo-master-import',
					'required'  => true,
				),
				array(
					'name' 		=> 'Elementor Page Builder',
					'slug' 		=> 'elementor',
					'required' 	=> false,
				),
			) );

		}
		add_action( 'tgmpa_register', '_action_theme_register_required_plugins' );
	}

}


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function merimag_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'merimag_content_width', 1200 );
}
add_action( 'after_setup_theme', 'merimag_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function merimag_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main sidebar', 'merimag' ),
		'id'            => 'main-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'merimag' ),
		'before_widget' => '<div class="merimag-widget-container "><div id="%1$s" class="merimag-widget sidebar-widget %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
		'after_title'   => '</span></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Page sidebar', 'merimag' ),
		'id'            => 'page-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'merimag' ),
		'before_widget' => '<div class="merimag-widget-container"><div id="%1$s" class="merimag-widget sidebar-widget  %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
		'after_title'   => '</span></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer widgets', 'merimag' ),
		'id'            => 'footer-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'merimag' ),
		'before_widget' => '<div class="merimag-widget-container"><div id="%1$s" class="merimag-widget footer-widget merimag-footer-widget  %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
		'after_title'   => '</span></div>',
	) );
	if( function_exists('is_bbpress') ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Forums sidebar', 'merimag' ),
			'id'            => 'forums-sidebar',
			'description'   => esc_html__( 'BBPress forums sidebar.', 'merimag' ),
			'before_widget' => '<div class="merimag-widget-container"><div id="%1$s" class="merimag-widget sidebar-widget  %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
			'after_title'   => '</span></div>',
		) );
	} 
	if( class_exists('WooCommerce')) {
		register_sidebar( array(
			'name'          => esc_html__( 'Shop sidebar', 'merimag' ),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__( 'Sidebar for woocommerce archive template.', 'merimag' ),
			'before_widget' => '<div class="merimag-widget-container"><div id="%1$s" class="merimag-widget sidebar-widget  %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
			'after_title'   => '</span></div>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Shop Off-canvas filter', 'merimag' ),
			'id'            => 'shop-filter-sidebar',
			'description'   => esc_html__( 'Sidebar for woocommerce filter off-canvas sidebar.', 'merimag' ),
			'before_widget' => '<div class="merimag-widget-container"><div id="%1$s" class="merimag-widget sidebar-widget %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
			'after_title'   => '</span></div>',
		) );
	}
	
	register_sidebar( array(
		'name'          => esc_html__( 'Video template sidebar', 'merimag' ),
		'id'            => 'video-template-sidebar',
		'description'   => esc_html__( 'Sidebar for video templates on article pages.', 'merimag' ),
		'before_widget' => '<div class="merimag-widget-container"><div id="%1$s" class="merimag-widget  sidebar-widget  %2$s">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
		'after_title'   => '</span></div>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( '404 widgets', 'merimag' ),
		'id'            => '404-sidebar',
		'description'   => esc_html__( 'Widgets for 404 page.', 'merimag' ),
		'before_widget' => '<div id="%1$s" class="merimag-widget merimag-404-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="block-title-wrapper merimag-widget-title"><span class="block-title">',
		'after_title'   => '</span></div>',
	) );
}
add_action( 'widgets_init', 'merimag_widgets_init' );
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function merimag_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	$container_layout = merimag_get_db_customizer_option( 'container_layout', 'wide' );
	

	if( is_user_logged_in() ) {
		$classes[] = 'logged-in';
	} else {
		$classes[] = 'logged-out';
	}

	if( is_customize_preview() ) {
		$classes[] = 'wp-customizer';
	}

	if( !is_rtl() ) {
		$classes[] = 'ltr';
	}

	$sticky_menu = merimag_get_db_customizer_option('sticky_header', 'yes');

	$classes[] = $sticky_menu === 'yes' ? 'merimag-sticky-header-desktop' : '';

	$sticky_mobile_header = merimag_get_db_customizer_option('sticky_mobile_header', 'yes');

	$classes[] = $sticky_mobile_header === 'yes' ? 'merimag-sticky-mobile-header' : '';

	$show_sticky_menu_logo = merimag_get_db_customizer_option('show_sticky_menu_logo', 'yes');

	$classes[] = $show_sticky_menu_logo === 'yes' ? 'merimag-sticky-menu-logo' : '';

	$sticky_sidebar = merimag_get_db_customizer_option('sticky_sidebar', 'yes');

	$classes[] = $sticky_sidebar === 'yes' ? 'merimag-sticky-sidebar' : '';

	$media_type = merimag_get_post_featured_media_type();

	$classes[] = 'media-type-' . esc_attr( $media_type );

	$classes[] = $container_layout;

	$classes[] = 'site-body';

	return $classes;
}
add_filter( 'body_class', 'merimag_body_classes' );


/**
 * Add nav menu items class.
 */
function merimag_menu_classes($classes, $item) {
  $classes[] = 'merimag-nav-menu-item';
  return $classes;
}
add_filter('nav_menu_css_class', 'merimag_menu_classes', 10, 3);
/**
 * The post thumbnail.
 *
 * @return void
 */
function merimag_post_thumbnail() {
	if( merimag_get_title_cover_style() ) {
		return;
	}
	$attachment_id = get_post_thumbnail_id();
	if( merimag_rwd_attachment_image( $attachment_id ) ) {
		echo merimag_rwd_attachment_image( $attachment_id );
	} else {
		echo '<div class="site-post-thumbnail">';
		the_post_thumbnail();
		echo '</div>';
	}
}
/**
 * The post thumbnail html.
 *
 * @return void
 */
function merimag_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	$is_amp_endpoint = function_exists('is_amp_endpoint') ? is_amp_endpoint() : false;
	$lazy_loading    = !$is_amp_endpoint ? merimag_get_db_customizer_option('lazy_image_loading', 'yes') : 'no';
	if( $lazy_loading === 'yes' ) {
		$html = str_replace('src', 'data-src', $html );
	}
	return $html;
}
add_filter('post_thumbnail_html', 'merimag_post_thumbnail_html', 10, 5 );

/**
 * Filter function to alter navigation menu classes.
 *
 * @return array list of classes
 */
function merimag_category_nav_class( $classes, $item, $args ){
    if( 'category' == $item->object ){
        $category = get_category( $item->object_id );
        $classes[] = 'category-' . $category->slug;
    }
    $classes[] = 'principal-color-sub-menu-border-top-color ';

    $current_classes = array('current-menu-ancestor', 'current-menu-parent', 'current_page_parent', 'current_page_ancestor','current-menu-item' );
    foreach( $current_classes as $current_class ) {
    	 if (in_array($current_class, $classes) ) {
	        $classes[] = 'active-menu-item ';
	    }
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'merimag_category_nav_class', 10, 3 );
/**
 * Get the correct classes for a menu item location based on user selection 
 * in the wordpress customizer
 *
 * @param string location a valid menu location
 * @param integer parent if the menu item has parent
 * @return array list of classes
 */
function merimag_get_menu_links_classes( $location, $args ) {
	$class = '';
	switch ($location) {
		case 'menu-1':
			$hover_effect_style = merimag_get_db_customizer_option('main_menu_items_hover_effect_style');
			break;
		case 'header-menu':
			$hover_effect_style = merimag_get_db_customizer_option('header_menu_items_hover_effect_style');
			break;
		case 'secondary-menu':
			$hover_effect_style = merimag_get_db_customizer_option('secondary_menu_items_hover_effect_style');
			break;
		case 'footer-menu':
			$hover_effect_style = merimag_get_db_customizer_option('footer_menu_items_hover_effect_style');
			break;
	}
	if( isset( $hover_effect_style ) ) {
	 	switch ($hover_effect_style) {
	    	case 'border-top':
	    	case 'border-bottom':
	    		$class .= ' principal-color-span-hover-before-background';
	    		break;
	    	case 'background':
	    	case 'background-close-border-top':
	    	case 'background-radius':
	    		$class .= ' principal-color-background-color-span-hover';
	    		break;
	    }
    }
    return $class;
}
/**
 * Filter function to alter navigation menu link classes
 *
 * @param array atts
 * @param object item
 * @param array args
 * @return array list of arguments with modified classes
 */
function merimag_add_menu_links_classes( $atts, $item, $args ) {
    $atts['class'] = merimag_get_menu_links_classes( $args->theme_location, $item );
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'merimag_add_menu_links_classes', 10, 3 );
/**
 * Get menu hover effect class 
 *
 * @param array classes default classes
 * @param object item arguments
 * @return object menu arguments
 */
function merimag_get_menu_effect_class( $menu = 'main_menu' ) {
	$recognized_styles = merimag_get_recognized_menu_items_styles( true );
	$items_effect 	   = merimag_get_db_customizer_option($menu . '_items_hover_effect_style');
	$main_menu_class   = !empty( $items_effect ) && in_array( $items_effect, $recognized_styles ) ? ' effect-' . esc_attr( $items_effect ) : '';
	return $main_menu_class;
}

/**
 * Filter function to alter navigation menu link output
 *
 * @param array list of menu items sorted
 * @param array args
 * @return array list of menu items objects
 */
function merimag_wp_nav_menu_objects( $sorted_menu_items, $args  ) {
	if( defined('FW') ) {
		return $sorted_menu_items;
	}
    // Loop over the menu items wrapping only top level items in span tags.
    foreach ( $sorted_menu_items as $item ) {
        $item->title = '<span class="menu-item-content"><span class="menu-item-title">' . $item->title . '</span></span>';
    }
    return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'merimag_wp_nav_menu_objects', 10, 2 );

/**
 * Filter to add support for unyson page builder for mega menus post type
 *
 * @return void
 */
function merimag_filter_auto_activate_builder() {

    $auto = array(
        'page' => true,
    );

    return  $auto;
}
add_filter( 'fw_ext_page_builder_settings_options_post_types_default_value', 'merimag_filter_auto_activate_builder' );

/**
 * Filter to add support for unyson page builder for mega menus post type
 *
 * @return void
 */
function merimag_filter_page_builder_support($result){

    $result['merimag_mega_menus'] = esc_html__('Mega menus','merimag');
    return $result;

}
add_filter( 'fw_ext_page_builder_supported_post_types', 'merimag_filter_page_builder_support' );
/**
 * Filter function to alter navigation menu link classes
 *
 * @param array list of menu items sorted
 * @param array args
 * @return array list of menu items objects
 */
if( defined('FW') && function_exists('fw_ext_mega_menu_get_meta') ) {
	
	function merimag_filter_fw_ext_mega_menu_wp_nav_menu_objects($sorted_menu_items, $args) {
		$mega_menu = array();
		foreach ($sorted_menu_items as $item) {
			if ($item->menu_item_parent == 0 && fw_ext_mega_menu_get_meta($item, 'enabled')) {
				$mega_menu[$item->ID] = true;
			}
		}
		foreach ($sorted_menu_items as $item) {

			if (isset($mega_menu[$item->menu_item_parent])) {
				$item->classes[] = 'mega-menu-col';
				$item->classes[] = 'general-border-color';
			}
			
		}
		return $sorted_menu_items;
	}
	add_filter('wp_nav_menu_objects', 'merimag_filter_fw_ext_mega_menu_wp_nav_menu_objects', 10, 2);
}
/**
 * Filter function to alter navigation menu link output
 *
 * @param string the type of the option
 * @return array list of menu items objects
 */
function merimag_select_menu_item_icon( $option_type ) {
	return array(
		'type' => 'icon-v2',
		'label' => __('Select icon', 'merimag'),
	);
}
add_filter('fw:ext:megamenu:icon-option', 'merimag_select_menu_item_icon');

/**
 * deregister the styles included with YIKES_MAILCHIMP
 */
if( ! defined( 'YIKES_MAILCHIMP_EXCLUDE_STYLES' ) ) {
   define( 'YIKES_MAILCHIMP_EXCLUDE_STYLES', true );
}
/**
 * Callback function for wp_list_comments()
 *
 * @param object comment
 * @param array args list of arguments
 * @param integer depth
 * @return void
 */
function merimag_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	$args = is_array( $args ) ? $args : array();
	$avatar_size =  75;
	$author_email = isset( $comment->comment_author_email ) ? $comment->comment_author_email : '';
	?>
    <<?php echo esc_attr($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body general-border-color">
        	<div class="comment-avatar">
            	<?php echo get_avatar( $author_email, $avatar_size ); ?>
            </div>
            <div class="comment-body-text">
	            <footer class="comment-meta">
	                <div class="comment-author vcard">
	                	<div class="post-author-label"><?php echo esc_html__('Author', 'merimag')?></div>
	                    <?php printf('%s', sprintf( '<b class="fn">%s</b>', get_comment_author_link() ) ); ?>
	                </div><!-- .comment-author -->
	                <div class="comment-metadata">
	                    <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
	                        <time datetime="<?php comment_time( 'c' ); ?>">
	                            <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'merimag' ), get_comment_date(), get_comment_time() ); ?>
	                        </time>
	                    </a>
	                    <?php edit_comment_link( __( 'Edit', 'merimag' ), '<span class="edit-link">', '</span>' ); ?>
	                </div><!-- .comment-metadata -->
	                
	            </footer><!-- .comment-meta -->
	            <?php if ( '0' == $comment->comment_approved ) : ?>
	                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'merimag' ); ?></p>
	            <?php endif; ?>
	            <div class="comment-content">
	                <?php comment_text(); ?>
	            </div><!-- .comment-content -->
	            <?php
	            comment_reply_link( array_merge( $args, array(
	                'add_below' => 'div-comment',
	                'depth'     => $depth,
	                'max_depth' => isset( $args['max_depth'] ) ? $args['max_depth'] : 4,
	                'before'    => '<div class="reply">',
	                'after'     => '</div>',
	                
	            ) ) );
	            ?>
            </div>
        </article><!-- .comment-body -->
	<?php 
}
/**
 * Next prev buttons in single post template
 *
 * @return void
 */
function merimag_next_prev( $sv = false ) {
	$next_prev = merimag_get_db_customizer_option('next_prev', 'yes');
	if( $next_prev !== 'yes' ) return;
	$next_post 	   = get_next_post();
	$previous_post = get_previous_post();
	$next_id 	   = isset( $next_post->ID ) ? $next_post->ID : false;
	$prev_id 	   = isset( $previous_post->ID ) ? $previous_post->ID : false;
	$next_tag 	   = merimag_get_thumbnail_background_tag(array('tag' => 'span', 'class' => 'merimag-next-prev-img', 'post_id' => $next_id ));
	$prev_tag 	   = merimag_get_thumbnail_background_tag(array('tag' => 'span', 'class' => 'merimag-next-prev-img', 'post_id' => $prev_id ));
	$next_ic 	   = is_rtl() ? 'left' : 'right';
	$prev_ic 	   = is_rtl() ? 'right' : 'left';
	?>
	<div class="merimag-next-prev">
		<div class="merimag-next-prev-element">
			<?php if( $prev_id ) : ?>
			<a class="merimag-next-prev-content  general-border-color merimag-next-content" href="<?php echo esc_url(get_the_permalink($prev_id))?>" title="<?php echo esc_attr(get_the_title($prev_id))?>">	
				<?php echo wp_specialchars_decode( esc_attr($prev_tag), ENT_QUOTES)?><i class="merimag-next-prev-icon fa fa-chevron-circle-<?php echo esc_attr($prev_ic)?>"></i></span>
				<span class="merimag-next-prev-text-container"><span class="merimag-next-prev-text"><?php echo get_the_title($prev_id); ?></span></span>
			</a>
			<?php endif; ?>
		</div>
		<div class="merimag-next-prev-element">
			<?php if( $next_id ) : ?>
			<a class="merimag-next-prev-content   general-border-color merimag-next-content" href="<?php echo esc_url(get_the_permalink($next_id))?>" title="<?php echo esc_attr(get_the_title($next_id))?>">	
				<?php echo wp_specialchars_decode( esc_attr($next_tag), ENT_QUOTES)?><i class="merimag-next-prev-icon fa fa-chevron-circle-<?php echo esc_attr($next_ic)?>"></i></span>
				<span class="merimag-next-prev-text-container"><span class="merimag-next-prev-text"><?php echo get_the_title($next_id); ?></span></span>
			</a>
			<?php endif; ?>
		</div>
	</div>
	<?php
	if( function_exists('rwd_print_styles') && $sv === true  ) {
    	rwd_print_styles();
  	}
}
/**
 * Related posts in single post template
 *
 * @return void
 */
function merimag_related_posts( $sv = false ) {
	$related_posts_style = merimag_get_db_customizer_option('related_posts_style','grid');
	$related_posts 		 = merimag_get_db_customizer_option('related_posts');
	$atts 				 = array();
	$get_by 	 		 = 'category';
	
	switch ($related_posts_style) {
		case 'grid':
			$atts 	   	 = isset( $related_posts['grid'] ) && !empty( $related_posts['grid']) ? $related_posts['grid'] : merimag_get_default_grid_data(false, 'related_posts');
			$atts['order_by'] 	 = 'date';
			$atts['order'] 	 	 = 'desc';
			$atts['title_ellipsis'] = 2;
			$atts['filter_title'] = __('Related posts', 'merimag');
			$get_by		 = isset( $related_posts['grid']['get_by'] ) && $related_posts['grid']['get_by'] === 'category' ? 'category' : 'tag';
			$shortcode 	 = 'posts-grid';
			break;
		case 'default_grid':
			$atts 	   	 = merimag_get_default_grid_data(false, 'related_posts');
			$atts['number'] = isset( $atts['columns'] ) && is_numeric( $atts['columns'] ) ? $atts['columns'] : 3;
			$atts['order_by'] 	 = 'date';
			$atts['order'] 	 	 = 'desc';
			$atts['title_ellipsis'] = 2;
			$atts['filter_title'] = __('Related posts', 'merimag');
			$atts['title'] = __('Related posts', 'merimag');
			$shortcode 	 = 'posts-grid';
			break;
		case 'slider':
			$atts 	   	 = isset( $related_posts['slider'] ) && !empty($related_posts['slider'] ) ? $related_posts['slider'] : merimag_get_default_grid_data(false, 'related_posts');
			$shortcode 	 = 'posts-slider';
			break;
	}

	$post_id 		 	 = get_the_ID();
	
	
	$categories 		 = wp_get_object_terms( $post_id, 'category', array('fields' => 'ids') );
	$tags 		 		 = wp_get_object_terms( $post_id, 'post_tag', array('fields' => 'ids') );

	if( $get_by === 'category' ) {
		$atts['category'] = $categories;
	} else {
		$atts['post_tag'] =	$tags;
	}
	$atts['exclude'] 	 = array(get_the_ID());
	$atts['ignore_general_style'] = 'yes';
	$atts['filters'][]   = array(
		'filter_title' => __('More from author', 'merimag'),
		'order_by' 	   => 'date',
		'order' 	   => 'desc',
		'number' 	   => isset( $atts['columns'] ) && is_numeric( $atts['columns'] ) ? $atts['columns'] : 3,
		'author' 	   => get_the_author_meta( 'ID'),
		'category' 	   => array(),
		'post_tag' 	   => array(),
		'exclude' 	   => array(),
	);
	
	if( isset( $shortcode ) ) {
		echo '<div class="merimag-related-posts general-border-color">';
		merimag_get_shortcode_html( $shortcode, $atts );
		echo '</div>';
	}
	if( function_exists('rwd_print_styles') && $sv === true  ) {
    	rwd_print_styles();
  	}
}
/**
 * Related posts in single post template
 *
 * @return void
 */
function merimag_read_also( $sv = false ) {
	$related_posts_style = merimag_get_db_customizer_option('read_also_style','grid');
	$related_posts 		 = merimag_get_db_customizer_option('read_also');
	$atts 				 = array();
	$get_by 	 		 = 'category';
	switch ($related_posts_style) {
		case 'grid':
			$atts 	   	 = isset( $related_posts['grid'] ) && !empty( $related_posts['grid'] ) ? $related_posts['grid'] : merimag_get_default_grid_data(false, 'read_also');
			$atts['order_by'] = 'date';
			$atts['order'] = 'desc';
			$atts['title_ellipsis'] = 2;
			$shortcode 	 = 'posts-grid';
			break;
		case 'default_grid':
			$atts 	   	 = merimag_get_default_grid_data(false, 'read_also');
			$atts['order_by'] = 'date';
			$atts['order'] = 'desc';
			$atts['title_ellipsis'] = 2;
			$shortcode 	 = 'posts-grid';
			break;
		case 'slider':
			$atts 	   	 = isset( $related_posts['slider'] ) && !empty( $related_posts['grid'] ) ? $related_posts['slider'] :  merimag_get_default_grid_data(false, 'read_also');
			$shortcode 	 = 'posts-slider';
			break;
	}

	$atts['ignore_general_style'] = 'yes';
	$atts['exclude'] 	 = array(get_the_ID());
	if( isset( $shortcode ) ) {
		echo '<div class="merimag-related-posts general-border-color">';
		merimag_get_shortcode_html( $shortcode, $atts );
		echo '</div>';
	}
	if( function_exists('rwd_print_styles') && $sv === true  ) {
    	rwd_print_styles();
  	}
}
/**
 * Tag lists and other informations in single post template
 *
 * @return void
 */
function merimag_tag_source_via() {

	echo '<div class="merimag-single-bottom-meta alignwide">';

	$tags = get_the_tags( get_the_ID() );

	if( is_array( $tags ) && !empty( $tags ) ) {

		echo '<div class="merimag-tags">';

		echo '<div class="merimag-tags-item-container">';

		echo '<div class="merimag-tags-item principal-color-color"><i class="fa fa-tags"></i></div>';
		foreach( (array) $tags as $tag ) {

			if( isset($tag->name) ) {
				echo sprintf('<a class="merimag-tags-item" href="%s">%s</a>', esc_url( get_tag_link( $tag->term_id ) ), esc_attr( $tag->name ) );
			}
		}

		echo '</div></div>';

	}
	echo merimag_meta_info('views_ajax');
	
	echo '</div>';
}


/**
 * Author infos in single post template
 *
 * @return void
 */
function merimag_author_box( $author_id = false, $sv = false) {
	$author_box = merimag_get_db_customizer_option('author_box');
	if( $author_box != 'yes' ) return;
	echo '<div class="merimag-author-box merimag-general-padding general-border-color">';
	$author_id = $author_id === false ? get_the_author_meta('ID') : $author_id;

	$atts['author_id'] = $author_id;
	$atts['icons_theme'] = 'theme-2';
	$atts['icons_color'] = merimag_get_principal_color();
	$atts['rounded_image'] = 'yes';
	merimag_get_shortcode_html('author', $atts );
	echo '</div>';

}
/**
 * Subscribe form in single post template
 *
 * @return void
 */
function merimag_post_subscribe() {

	if( !function_exists('yikes_easy_mailchimp_extender_get_form_interface') ) {
		return;
	}
    $form_interface   = yikes_easy_mailchimp_extender_get_form_interface();
    $all_forms        = $form_interface->get_all_forms();
    $form_id = false;
    foreach( (array) $all_forms as $id => $form ) {
      $form_id = $id;
      break;
    }
    $subscribe = merimag_get_db_customizer_option('subscribe_form');
    if( $subscribe === 'yes' ) {
    	$get_form = merimag_get_db_customizer_option('subscribe_form_atts');
    	$atts = $get_form['yes'] && is_array( $get_form['yes'] ) ? $get_form['yes'] : array();
    	$atts['show_newsletter_icon'] = isset( $atts['show_newsletter_icon'] ) ? $atts['show_newsletter_icon'] : true;
    	$atts['mailchimp_form']  = isset( $atts['mailchimp_form'] ) && !empty( $atts['mailchimp_form'] ) ? $atts['mailchimp_form'] : $form_id;
    }
    if( !isset( $atts ) ) {
    	return;
    }
	echo '<div class="merimag-single-subscribe merimag-general-padding general-border-color">';
	merimag_get_shortcode_html('mailchimp', $atts );
	echo '</div>';

}

/**
 * Share buttons in single post template
 *
 * @return void
 */
function merimag_get_share_buttons( $location ) {
	if( !$location ) {
		 return;
	}

	$show_share_buttons_before_content = merimag_get_db_customizer_option('show_share_buttons_before_content', 'yes');
	$show_share_buttons_after_content = merimag_get_db_customizer_option('show_share_buttons_after_content', 'yes');
	$show_share_buttons_on_meta = merimag_get_db_customizer_option('show_share_buttons_on_meta', 'no');
	switch ($location) {
		case 'before_content':
			if( $show_share_buttons_before_content == 'yes' ) {
				echo '<div class="merimag-share-buttons merimag-share-buttons-before-content">';
				$atts = function_exists('is_amp_endpoint') && is_amp_endpoint() ? array('layout' => 'justified') : array();
				merimag_inline_share( $atts );
				echo '</div>';
			}
			break;
		case 'after_content':
			if( $show_share_buttons_after_content == 'yes' ) {
				echo '<div class="merimag-share-buttons merimag-share-buttons-after-content">';
				$atts = function_exists('is_amp_endpoint') && is_amp_endpoint() ? array('layout' => 'justified') : array();
				merimag_inline_share( $atts );
				echo '</div>';
			}
			break;
		case 'meta':
			if( $show_share_buttons_on_meta == 'yes' ) {
				$args = array('layout' => 'inline', 'size' => 'tiny', 'rounded' => true);
				echo '<div class="merimag-share-buttons merimag-share-buttons-meta">';
				merimag_inline_share($args);
				echo '</div>';
			}
			break;
	}
}
/**
 * Share buttons in single post template - before content
 *
 * @return void
 */
function merimag_get_share_buttons_before_content() {
	merimag_get_share_buttons('before_content');
}
/**
 * Share buttons in single post template - after content
 *
 * @return void
 */
function merimag_get_share_buttons_after_content() {
	merimag_get_share_buttons('after_content');
}
/**
 * Share buttons in single post template - on meta infos line
 *
 * @return void
 */
function merimag_get_share_buttons_meta() {
	merimag_get_share_buttons('meta');
}
/**
 * Filter search form output
 *
 * @param string form the html of the search form
 * @return string the filtered html of the search form
 */
function merimag_search_form($form, $post_type = 'all' ) {
	$post_type_label = post_type_exists($post_type) ? get_post_type_object($post_type) : '';
	$post_type_label = isset( $post_type_label->label ) ? $post_type_label->label : '';
	$id = merimag_uniqid();
    $form = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" class="search-form">
        <input type="text" name="s" id="search-form-' . esc_attr( $id) . '" class="search-field" placeholder="' . esc_html__('Search for', 'merimag') . ' ' . esc_attr( $post_type_label ) . '.." value="' . esc_attr( get_search_query() ) . '" required>
        <button type="submit" id="search-submit-' . esc_attr( $id) . '" class="search-submit button"></button>';
        if( post_type_exists($post_type) ) {
        	$form .= '<input type="hidden" name="post_type" value="' . esc_attr( $post_type ) . '">';
        }

    $form .= '</form>';
	return $form;
}

add_filter('get_search_form', 'merimag_search_form');

/**
 * Output child terms of the current term
 *
 * @return void
 */
function merimag_get_term_childs() {
	$queried_object = get_queried_object();
	if( !isset( $queried_object->term_id) ) {
		return;
	}
	$child_items   = get_terms(
		array(
			'taxonomy' => $queried_object->taxonomy,
	    	'parent' => $queried_object->term_id,
	    	'hide_empty' => false 
	    )
	);
	if( $queried_object->parent != false ) {
		$sibling_items = get_terms(
			array(
				'taxonomy' => $queried_object->taxonomy,
		    	'parent' => $queried_object->parent,
		    	'hide_empty' => false 
		    )
		);
	}
	if( $child_items && !empty( $child_items ) ) {
		echo '<div class="merimag-terms-list">';
		foreach( (array) $child_items as $child_item ) {
			$name = $child_item->name;
			$link = get_term_link( $child_item->term_id );
			$class = $queried_object->taxonomy . '-' . $child_item->slug;
			echo sprintf('<a class="merimag-term-item principal-color-background-color %s" href="%s" class="">%s</a>', esc_attr( $class ), esc_attr( $link ), esc_attr( $name ) );
		}
		echo '</div>';
	} else {
		return;
		if( isset( $sibling_items ) && !empty( $sibling_items ) ) {
			echo '<div class="merimag-terms-list">';
			foreach( (array) $sibling_items as $child_item ) {
				$name = $child_item->name;
				$link = get_term_link( $child_item->term_id );
				$class = $queried_object->taxonomy . '-' . $child_item->slug;
				echo sprintf('<a class="merimag-term-item principal-color-background-color %s" href="%s" class="">%s</a>', esc_attr( $class ), esc_attr( $link ), esc_attr( $name ) );
			}
			echo '</div>';
		}
	}
}
/**
 * Display breadcrumbs
 *
 * @return void
 */
function merimag_breadcrumb( $container = true ) {
	$single_template_args = merimag_get_post_template_args();
	$entry_header_class = isset( $single_centered_infos ) && $single_centered_infos === true ? 'centered-breadcrumb' : '';

	if( $container === true && !is_home() ) {
		echo sprintf('<div class="merimag-breadcrumb general-border-color %s">', esc_attr( $entry_header_class ) );
	}
	if( function_exists('is_bbpress') && is_bbpress() ) {
		bbp_breadcrumb();
		if( $container === true ) {
			echo '</div>';
		}
		return;
	}
	if( !is_home() ) {
    	echo sprintf('<a href="%s"><i class="fa fa-home"></i></a>', esc_url( merimag_home_url() ) );
    }
    $queried_object = get_queried_object();
    $post_type = get_post_type();
    global $wp_query;
    $query_vars = $wp_query->query_vars;
    if( class_exists('WooCommerce') && is_woocommerce() ) {
    	if( !is_shop() ) {
	    	echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
	    	echo sprintf('<a href="%s">%s</a>', get_permalink( wc_get_page_id( 'shop' ) ), esc_html__('Shop', 'merimag') );
    	} else {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Shop', 'merimag');
    	}
    }
    if (is_archive() && $queried_object instanceof WP_Term ) {
    	if( isset( $queried_object->parent ) && $queried_object->parent != false ) {
    		$parent_item = get_term( $queried_object->parent );
    		$name = $parent_item->name;
			$link = get_term_link( $parent_item->term_id );
			echo ' &nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
			echo sprintf('<a class="merimag-term-item" href="%s">%s</a>', esc_attr( $link ), esc_attr( $name ) );
    	}
    	if( isset( $queried_object->name ) ) {
	    	$term_name = $queried_object->name;
			echo ' &nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
			echo the_archive_title();
		}
    }
    if( is_archive() && isset( $query_vars['year'] ) && $query_vars['year'] ) {
    	echo ' &nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    	echo the_archive_title();
    }
    if( is_author() ) {
    	echo ' &nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    	echo the_archive_title();
    }
    if (is_search()) {
        echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;' . __('Search Results for...', 'merimag');
        echo '"<b>';
        echo the_search_query();
        echo '</b>"';
    }
    if (is_404() ) {
    	echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
        echo esc_html__('Page not found', 'merimag');
    }
    if( is_singular() ) {
		if( class_exists('WooCommerce') && is_product() && $post_type === 'product' ) {
			$terms = get_the_terms( get_the_ID(), 'product_cat' );
			foreach ((array) $terms as $term) {
			    $product_cat_id = $term->term_id;
			    $name = $term->name;
			    $link = get_term_link( $term->term_id );
			    break;
			}
			if( isset( $product_cat_id ) ) {
				echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
				echo sprintf('<a href="%s">%s</a>', esc_attr( $link ), esc_attr( $name ) );
			}
		}
		if( is_single() && $post_type === 'post' ) {
			$terms = get_the_terms( get_the_ID(), 'category' );
			foreach ((array) $terms as $term) {
			    $cat_id = $term->term_id;
			    $name = $term->name;
			    $link = get_term_link( $term->term_id );
			    break;
			}
			if( isset( $cat_id ) ) {
				echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
				echo sprintf('<a href="%s">%s</a>', esc_attr( $link ), esc_attr( $name ) );
			}
		}
		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    	if( class_exists('WooCommerce') && is_account_page() && !is_wc_endpoint_url('dashboard')  ) {
    		echo sprintf('<a href="%s">%s</a>', get_the_permalink(), get_the_title() );
    	} else {
    		echo the_title();
    	}
    }
    if( class_exists('WooCommerce') ) {
    	if( is_wc_endpoint_url('orders') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Orders', 'merimag');
    	}
    	if( is_wc_endpoint_url('downloads') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Downloads', 'merimag');
    	}
    	if( is_wc_endpoint_url('edit-address') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Addresses', 'merimag');
    	}
    	if( is_wc_endpoint_url('edit-account') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Account details', 'merimag');
    	}
    	if( is_wc_endpoint_url('customer-logout') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Logout', 'merimag');
    	}
    	if( is_wc_endpoint_url('customer-login') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Login', 'merimag');
    	}
    	if( is_wc_endpoint_url('lost-password') ) {
    		echo '&nbsp;<i class="icofont-rounded-right"></i>&nbsp;';
    		echo esc_html__('Lost password', 'merimag');
    	}
    }
    if( $container === true && !is_home()  ) {
    	echo '</div>';
    }
}
/**
 * Generate title cover background css
 *
 * @return string css
 */
function merimag_get_title_cover_style() {
	$title_cover_color	   = merimag_get_db_customizer_option('title_cover_color');
	$title_cover_image	   = merimag_get_db_customizer_option('title_cover_image');
	if( is_tax() || is_category() || is_tag() ) {
		$queried_object = get_queried_object();
		$term_id = $queried_object->term_id;
		$tax = $queried_object->taxonomy;
		$title_cover_image = merimags_get_the_category_thumbnail_url( $term_id, $tax );
	}
	$thumbnail_title_cover = merimag_get_db_customizer_option('thumbnail_title_cover');
	$has_cover 			   = false;
	$primary_color 		   = false;
	$secondary_color 	   = false;
	$css 				   = '';
	$degree 			   = isset( $title_cover_color['degree'] ) && in_array( $title_cover_color['degree'], array('to bottom', 'to right')) ? $title_cover_color['degree'] : 'to bottom';
	if( isset( $title_cover_color['primary'] ) && merimag_validate_color( $title_cover_color['primary'] ) ) {
		$primary_color = $title_cover_color['primary'];
		$has_cover = true;
	}
	if( isset( $title_cover_color['secondary'] ) && merimag_validate_color( $title_cover_color['secondary'] ) ) {
		$secondary_color = $title_cover_color['secondary'];
		$has_cover = true;
	}
	if( $thumbnail_title_cover === 'yes' && !$title_cover_image ) {
		$title_cover_image = merimag_get_the_post_thumbnail_url( get_the_ID() );

	}
	if( $title_cover_image && !empty( $title_cover_image ) ) {
		$has_cover = true;
	}
	$title_cover_image = isset( $title_cover_image['url'] ) && !empty( $title_cover_image['url'] ) ? $title_cover_image['url'] : $title_cover_image;
	if( $has_cover === true ) {
		if( $primary_color && $secondary_color && $title_cover_image ) {
			$css = sprintf( 'background-image:linear-gradient( %s, %s, %s ), url( %s ) ;', $degree, $primary_color, $secondary_color, $title_cover_image );
		}
		if( $primary_color && $secondary_color && !$title_cover_image ) {
			$css = sprintf( 'background-image: linear-gradient( %s, %s, %s );', $degree, $primary_color, $secondary_color );
		}
		if( $primary_color && !$secondary_color && $title_cover_image ) {
			$css = sprintf( 'background-image: linear-gradient( %s, %s, %s ), url( %s );', $degree, $primary_color, $primary_color, $title_cover_image );
		}
		if( !$primary_color && $secondary_color && $title_cover_image ) {
			$css = sprintf( 'background-image:linear-gradient( %s, %s, %s ), url( %s );', $degree, $secondary_color, $secondary_color, $title_cover_image );
		}
		if( $primary_color && !$secondary_color && !$title_cover_image ) {
			$css = sprintf( 'background-color: %s;', $primary_color );
		}
		if( !$primary_color && $secondary_color && !$title_cover_image ) {
			$css = sprintf( 'background-color: %s;', $secondary_color );
		}
		if( !$primary_color && !$secondary_color && $title_cover_image ) {
			$css = sprintf( 'background-image: url(%s);', $title_cover_image );
		}
	}

	return $css;
}
/**
 * Display page head that contains only breadcrumbs at the moment
 *
 * @return void
 */
function merimag_get_page_header( $section = true ) {
	
	$cover_css = merimag_get_title_cover_style();
	$cover_text_color = merimag_get_db_customizer_option('title_cover_text_color', 'white');
	$cover_text_color = in_array($cover_text_color, array('white', 'dark') ) ? $cover_text_color : 'white';
	$has_cover = false;
	if( $cover_css ) {
		$has_cover = true;
	}
	if( $has_cover === true ) {
		$enable = merimag_get_db_customizer_option('enable_breadcrumbs');
		if( $enable === 'no' ) {
			return;
		}
		$post_type = get_post_type();
		if( $post_type === 'post') {
			if( is_single()) {
				$single_template_args = merimag_get_post_template_args();
				extract( $single_template_args );
			}
			if( isset( $single_stretched_image) && $single_stretched_image === true ) {
				return;
			}
		}
		$header_class = $section === true ? 'merimag-full-section' : '';
		$header_class = $has_cover === true ? ' page-header-has-title ' : '';
		$header_content_class = $section === true ? 'merimag-full-section-content site-content-width' : '';
		$header_content_class .= $has_cover === true ? ' ' . $cover_text_color . '-text ' : '';
		if(!is_home()) {
			echo sprintf( '<div style="%s" class="merimag-page-header merimag-full-section general-border-color %s">', esc_attr( $cover_css ), esc_attr( $header_class ) );
			echo sprintf('<div class="merimag-page-header-content %s">', esc_attr( $header_content_class ) );
			
			
				merimag_breadcrumb();
				$title = merimag_get_page_title();
				echo '<h1>' . esc_attr( $title ) . '</h1>';
				merimag_get_term_childs();
			
			echo '</div>';
			echo '</div>';
		}

	}
}
/**
 * Display page title
 *
 * @return void
 */
function merimag_page_title() {
	$has_cover = merimag_get_title_cover_style();
	if( !$has_cover ) {
		merimag_breadcrumb();
		$title = merimag_get_page_title();
		echo sprintf('<h3 class="merimag-page-title general-border-top-color principal-color-border-bottom-color entry-title">%s</h3>', $title );

		merimag_get_term_childs();
		if( is_category() && category_description() ) {
			echo '<div class="merimag-category-description general-border-color">';
			echo category_description();
			echo '</div>';
		}
	}
}
/**
 * Get the current page title
 *
 * @return string
 */
function merimag_get_page_title() {
	$title = '';
	if( is_singular() ) {
		$title = get_the_title();
	}
	if( is_archive() ) {
		$title = get_the_archive_title();
	}
	if( is_home() ) {
		$title = esc_html__('Latest posts', 'merimag');
	}
	if( is_search() ) {
		$title = sprintf(__( 'Search Results for &#8220;%s&#8221;', 'merimag' ), get_search_query() );
	}
	if( is_404() ) {
		$title = esc_html__( 'Oops! That page can&rsquo;t be found.', 'merimag' );
	}
	if( class_exists('WooCommerce') ) {
		if( is_woocommerce() ) {
			$title = woocommerce_page_title(false);
		}
	}
	return $title;
}


/**
 * Dispaly entry header in single post template
 *
 * @return void
 */
function merimag_entry_header() {
	$single_template_args = merimag_get_post_template_args();
	extract( $single_template_args );
	if( !isset( $single_absolute_infos ) || $single_absolute_infos === false ) {
		merimag_breadcrumb();
	}
	$entry_header_class = isset( $single_centered_infos ) && $single_centered_infos === true ? 'centered-header' : '';
	echo sprintf('<header class="entry-header %s">', esc_attr( $entry_header_class ) );
	$post_type = get_post_type();
	if ( 'post' === $post_type ) :
		echo '<div class="entry-meta">';
		echo merimag_meta_info('categories');
		echo '</div>';
	endif;
	if ( is_singular() ) :
		the_title( '<h1 class="entry-title">', '</h1>' );
	else :
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	endif;
	if ( 'post' === $post_type ) :
		$subtitle = merimag_get_db_post_option(get_the_ID(), 'post_subtitle');
		if( $subtitle ) {
			if ( is_singular() ) :
				echo sprintf('<h2 class="entry-subtitle">%s</h2>', esc_attr($subtitle));
			else :
				echo sprintf('<h3 class="entry-subtitle">%s</h3>', esc_attr($subtitle));
			endif;
		}
		echo '<div class="entry-meta">';
			echo merimag_meta_info('author_image|dash|date|comments');
			merimag_get_share_buttons_meta();
		echo '</div>';
	endif;
	
	echo '</header>';
}
function merimag_get_post_format() {
	$format = get_post_format() ? : 'standard';
	return apply_filters('merimag_get_post_format', $format );
}
/**
 * Dispaly featured media in single post template
 *
 * @return void
 */
function merimag_get_post_featured_media() {
	$format 	= merimag_get_post_format() ? : 'standard';
	$code 		= '';
	$type 		= merimag_get_post_featured_media_type();
	$single_template_args = merimag_get_post_template_args();
	extract( $single_template_args );
	$top_sidebar = isset( $single_top_sidebar ) && $single_top_sidebar === true ? true : false;
	$full_image  = isset( $single_full_image ) && $single_full_image === true ? true : false;
	$marged_infos = isset( $single_marged_infos ) && $single_marged_infos === true ? true : false;
	$stretched_image = isset( $single_stretched_image) && $single_stretched_image === true ? true : false;
	$absolute_infos = isset( $single_absolute_infos ) && $single_absolute_infos === true ? true : false;
	$img_url	= merimag_get_the_post_thumbnail_url( get_the_ID() );
	$height = $stretched_image === true ? 1080 : 800;
	$parallax = isset( $single_stretched_image ) && $single_stretched_image === true ? 'parallax' : '';
	$copyrights = merimag_get_db_post_option(get_the_ID(), 'featured_image_copyrights');
	$copyrights = $marged_infos == false ? $copyrights : false;
	$img_code 	= merimag_get_thumbnail_background_tag(array('class' => 'merimag-single-header-img ' . $parallax, 'height' => $height, 'copyrights' => $copyrights ));
	if( $stretched_image === false && $absolute_infos === true ) {
		?>
		<div class="merimag-breadcrumb merimag-breadcrumb-absolute">
			<div class="merimag-full-section-content site-content-width white-text merimag-breadcrumb-content">
				<?php merimag_breadcrumb( false ); ?>
			</div>
		</div>
		<?php
	}
	switch ($format) {
		case 'video':
			$video_url = merimag_get_db_post_option( get_the_ID(), 'post_video_url');
			$video_self = merimag_get_db_post_option(get_the_ID(), 'post_video_upload');
			if( $video_url && !$video_self ) {
				$atts['media_type'] = 'external_media';
				$atts['url'] = $video_url;
			}
			if( $video_self ) {
				$atts['media_type'] = 'video';
				$atts['upload'] = $video_self;
			}
			if( $video_url || $video_self ) {
				$atts['cover']['url'] = $img_url;
				$code = merimag_shortcode_html('video', $atts );
			}
			break;
		case 'audio':
			$audio_url = merimag_get_db_post_option( get_the_ID(), 'post_audio_url');
			$audio_self = merimag_get_db_post_option(get_the_ID(), 'post_audio_upload');

			if( $audio_url && !$audio_self ) {
				$atts['media_type'] = 'external_media';
				$atts['url'] = $audio_url;
			}
			if( $audio_self ) {
				$atts['media_type'] = 'video';
				$atts['upload'] = $audio_self;
			}
			if( $audio_url || $audio_self ) {
				$atts['cover']['url'] = $img_url;
				$code = merimag_shortcode_html('audio', $atts );
			}
			break;
		case 'gallery':
			$gallery_images = merimag_get_db_post_option( get_the_ID(), 'gallery_items');
			$gallery_style  = merimag_get_db_post_option( get_the_ID(), 'gallery_theme');
			if( $gallery_images && is_array( $gallery_images ) ) {
				$atts['gallery_items'] = $gallery_images;
				if( $gallery_style === 'tiles' ) {
					$atts['tiles_type'] = 'justified';
					$atts['enable_textpanel'] = 'yes';
					$code = merimag_shortcode_html( 'gallery-tiles', $atts );
				} else {
					$gallery_style = $gallery_style == 'default' ? 'compact' : $gallery_style;
					$atts['gallery_theme'] = $gallery_style;
					$code = merimag_shortcode_html( 'gallery', $atts );
				}
			}
			break;
	
	}
	if( $code ) {
		$code_escaped = $code;
		echo $code_escaped;  // PHPCS: XSS ok
	} else {
		if( $type !== 'none' ) {
			echo wp_specialchars_decode( esc_attr( $img_code ), ENT_QUOTES ) . '</div><!--featured-media-background-tag-->';
		}
	}
}

/**
 * Get the media type selected by the user for the current post
 *
 * @return string media type
 */
function merimag_get_post_featured_media_type() {
	if( !is_single() ) {
		return 'none';
	}
	$format  = merimag_get_post_format() ? : 'standard';
	$img_url = merimag_get_the_post_thumbnail_url( get_the_ID() );
	$media_type = $img_url && !empty( $img_url ) ? 'image' : 'none';
	switch ($format) {
		case 'video':
			$video_url  = merimag_get_db_post_option( get_the_ID(), 'post_video_url');
			$video_self = merimag_get_db_post_option(get_the_ID(), 'post_video_upload');
			if( $video_url || ( isset( $video_self['url'] ) && !empty( $video_self['url'] ) ) ) {
				$media_type = 'video';
			}
			break;
		case 'audio':
			$audio_url = merimag_get_db_post_option( get_the_ID(), 'post_audio_url');
			$audio_self = merimag_get_db_post_option(get_the_ID(), 'post_audio_upload');
			if( $audio_url || ( isset( $audio_self['url'] ) && !empty( $audio_self['url'] ) ) ) {
				$media_type = 'audio';
			}
			break;
		case 'gallery':
			$gallery_images = merimag_get_db_post_option( get_the_ID(), 'gallery_items');
			if( $gallery_images && is_array( $gallery_images ) ) {
				$media_type = 'gallery';
			}
			break;
	}
	return $media_type;	
}
/**
 * Display news ticker
 *
 * @return void
 */
function merimag_news_ticker( $location = 'show', $boxed = false ) {
	$ticker_position = merimag_get_db_customizer_option('ticker_position');
	$custom_ticker = merimag_get_db_customizer_option('custom_ticker');
	$custom_ticker_settings = merimag_get_db_customizer_option('custom_ticker_settings');
	if( $custom_ticker === 'yes' && isset( $custom_ticker_settings['yes'] ) && is_array( $custom_ticker_settings['yes']) ) {
		$atts = $custom_ticker_settings['yes'];
	}
	$atts['ticker_title'] = isset( $custom_ticker_settings['yes']['ticker_title'] ) && !empty($custom_ticker_settings['yes']['ticker_title'] ) ? $custom_ticker_settings['yes']['ticker_title'] : merimag_get_db_customizer_option('ticker_title', __('Trending', 'merimag'));
	$atts['ticker_icon'] = isset( $custom_ticker_settings['yes']['filter_icon']['icon-class']) && is_string( $custom_ticker_settings['yes']['filter_icon']['icon-class'] ) ? $custom_ticker_settings['yes']['filter_icon']['icon-class'] : false;
	if( $ticker_position === $location || $location === 'show' ) {
		$cls = $boxed === true ? ' site-content-width merimag-full-section-content ' : '';
		if( $boxed === true ) {
			echo '<div class="site-content-width merimag-full-section-content boxed-main-ticker">';
		}
		echo '<div class="merimag-main-ticker general-border-color principal-color-gradient-right ticker_' . esc_attr( $location ) . '">';
		$class = $location !== 'show' && $boxed !== true ? 'site-content-width merimag-full-section-content' : '';
		echo sprintf('<div class="merimag-main-ticker-content %s">', $class );
		$atts['block_id'] = 'site-main-ticker';
		merimag_get_shortcode_html('ticker', $atts );
		echo '</div></div>';
		if( $boxed === true ) {
			echo '</div>';
		}
	}
}

/**
 * Display footer about infos
 *
 * @return void
 */
function merimag_get_footer_about() {
	$enable 	  = merimag_get_db_customizer_option('enable_footer_about', 'yes');
	if( $enable === 'no' ) return;
	$default_logo = merimag_get_db_customizer_option('logo');
	$about_logo   = merimag_get_db_customizer_option( 'footer_about_logo', $default_logo );
	$about_text   = merimag_get_db_customizer_option('footer_about_text');
	$about_layout = merimag_get_db_customizer_option('footer_about_layout', 'inline' );
	$class 		  = $about_layout === 'inline' ? 'merimag-footer-about-inline' : 'merimag-footer-about-centered';
	if( empty($about_text) ) {
		 return;
	}
	echo sprintf('<div class="merimag-footer-about %s general-border-color">', esc_attr( $class ) );
	echo '<div class="merimag-footer-about-container merimag-full-section-content site-content-width">';
	echo '<div class="merimag-footer-about-content">';
	if( isset( $about_logo['url'] ) && !empty( $about_logo['url'] ) ) {
		echo '<div class="merimag-footer-about-logo">';
		echo sprintf('<a href="%s"><img alt="%s" src="%s"></a>', esc_url( merimag_home_url() ) , esc_attr( get_bloginfo('name')), esc_url( $about_logo['url'] ) );
		echo '</div>';
	}
	if( !empty( $about_text ) ) {
		echo '<div class="merimag-footer-about-text">';
		echo wp_specialchars_decode( esc_attr( $about_text ), ENT_QUOTES );
		echo '</div>';
	}
	echo '<div class="merimag-footer-about-social">';
	$about_social = merimag_get_db_customizer_option('footer_about_social');
	$custom_about_social = merimag_get_db_customizer_option('custom_footer_about_social');
	if( $custom_about_social === 'yes' && isset( $about_social['yes'] ) && is_array( $about_social['yes'] ) ) {
		$atts = $about_social['yes'];
	} else {
		$atts = apply_filters('footer_about_social', array(
			'icons_theme' => 'theme-5',
			'icon_size' => 5,
			'icons_color' => merimag_get_principal_color(),
		));
	}
	$atts['icons_layout'] = 'only_icon';
	$atts['icons_columns'] = 'flex';
	$atts['block_id'] = 'footer-about-social-icons';
	merimag_get_shortcode_html('social-icons', $atts);
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
add_action('merimag_after_footer_inner', 'merimag_get_footer_about');
/**
 * Display footer tags
 *
 * @return void
 */
function merimag_get_footer_tags() {
	$enable 	  = merimag_get_db_customizer_option('enable_footer_trending', 'yes');
	if( $enable === 'no' ) return;
	$tags_layout  = merimag_get_db_customizer_option('footer_tags_layout', 'inline' );
	$tags_title   = merimag_get_db_customizer_option('footer_tags_title' );
	$number 	  = merimag_get_db_customizer_option('footer_tags_number', 20 );
	$class 		  = $tags_layout === 'inline' ? 'merimag-footer-tags-inline' : 'merimag-footer-tags-centered';
	$enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
	echo sprintf('<div class="merimag-footer-tags %s general-border-color">', esc_attr( $class ) );
	echo '<div class="merimag-footer-tags-container merimag-full-section-content site-content-width">';
	echo '<div class="merimag-footer-tags-content">';
	if( $tags_title ) {
		echo sprintf('<h4 class="merimag-footer-tags-title">%s</h4>', esc_attr( $tags_title ) );
	}
	$cached_tags = get_transient('merimag_cache_footer_trending_tags' . merimag_get_demo_slug());
	if( $cached_tags && $enable_cache === 'yes' ) {
		$tags = $cached_tags;
	} else {
		$tags = get_tags( array('orderby' => 'count', 'order' => 'desc', 'number' => $number ));
		foreach( $tags as $tag ) {
			$tag->link = get_term_link( $tag->term_id );
		}
		if( $enable_cache === 'yes' ) {
			set_transient('merimag_cache_footer_trending_tags' . merimag_get_demo_slug(), $tags );
		}
		
	}
	
	echo '<div class="merimag-footer-tags-list" dir="ltr">';
	foreach((array) $tags as $tag ) {
		if( !isset( $tag->term_id) ) {
			continue;
		}
		echo sprintf('<a class="merimag-footer-tags-item" href="%s" title="%s %s">%s</a>', esc_url( $tag->link ), esc_attr( $tag->count ), esc_html__('Posts', 'merimag'), esc_attr( $tag->name ) );
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
add_action('merimag_after_footer_inner', 'merimag_get_footer_tags');

/**
 * Display review
 *
 * @return void
 */
function merimag_review() {
	$enable = merimag_get_db_post_option( get_the_ID(), 'single_enable_review');
	if( $enable === 'yes' ) {
		$review = merimag_get_db_post_option( get_the_ID(), 'single_review');
		if( isset( $review['yes'] ) && !empty( $review['yes'] ) && isset( $review['yes']['review_cretirias'] ) && !empty( $review['yes']['review_cretirias'] ) ) {
			echo '<div class="merimag-single-review-container general-border-color">';
			merimag_get_shortcode_html( 'review', $review['yes'] );
			echo '</div>';
		} 
	}
}

/**
 * Display review in position selected by the user
 *
 * @return void
 */
function merimag_review_position() {
	$review_position = merimag_get_db_post_option( get_the_ID(), 'single_review_position', 'after_content');
	if( $review_position === 'after_content' ) {
		add_action('merimag_single_after_content', 'merimag_review');
	} 
	if( $review_position === 'before_content' ) {
		add_action('merimag_single_before_content', 'merimag_review');
	}
}
add_action('wp_head', 'merimag_review_position');
function merimag_update_review_score( $post_id ) {
	$score = merimag_get_review_score_point( $post_id, true );
	if( $score ) {
		update_post_meta( $post_id, 'review_score', $score );
	}
	
}
add_action( 'save_post', 'merimag_update_review_score' );
function merimag_delete_cache() {
	global $wpdb;
	// sorry about format I hate scrollbars in answers.
	$shortcodes_cache = $wpdb->get_results(
	 "SELECT option_name AS name, option_value AS value FROM $wpdb->options 
	  WHERE option_name LIKE '_transient_merimag_cache_%'"
	);
	if( is_array( $shortcodes_cache ) ) {
	foreach( $shortcodes_cache as $transient ) {
	  if( isset( $transient->name ) ) {
	    $name = str_replace('_transient_', '', $transient->name );
	    delete_transient($name);
	  }
	}
	}

	$wpdb->query(
	 "DELETE FROM `wp_postmeta` WHERE `meta_key` LIKE 'merimag_meta_shortcodes_cache_%'"
	);
	$wpdb->query(
	 "DELETE FROM `wp_postmeta` WHERE `meta_key` LIKE 'merimag_builder_cache_%'"
	);

}
add_action('save_post', 'merimag_delete_cache');
add_action('edit_term', 'merimag_delete_cache');
add_action('webte_purge_cache', 'merimag_delete_cache');
add_action('customize_save', 'merimag_delete_cache');
/**
 * Get user contact methods
 *
 * @param array $methods list of contact methods
 * @return array list of contact methods
 */
function merimag_add_user_contact_methods( $methods ) {
    $networks            = merimag_get_recognized_social_networks( false, 'name');
    $methods['company']  = __('Company', 'merimag');
    $methods['position'] = __('Position', 'merimag');
    foreach( (array) $networks as $network_id => $network ) {
      if( !in_array($network_id, array('rss','website') ) ) {
        $methods[$network_id] = $network;
      }
    }
    return $methods;
}
add_filter('user_contactmethods','merimag_add_user_contact_methods',10,1);
/**
 * Get selected page layout
 *
 * @return string the page layout content / content-sidebar / sidebar-content
 */
function merimag_get_page_layout() {
  $current_page = 'default';
  $default = is_rtl() ? 'sidebar-content' : 'content-sidebar';
  $default = merimag_get_db_customizer_option( 'layout_' . $current_page, $default );
  if( is_archive() ) {
    $current_page = 'archive';
  }
  if( is_home() ) {
    $current_page = 'index';
  }
  if( is_page() ) {
    $current_page = 'page';
    $default      = 'content';
  }
  if( is_single() ) {
    $current_page = 'single';
  }
  if( is_tax() ) {
    $current_page = 'tax';
  }
  if( is_category() ) {
    $current_page = 'category';
  }
  if( is_tag() ) {
    $current_page = 'tag';
  }
  if( is_search() ) {
    $current_page = 'search';
  }
  if( class_exists('WooCommerce') ) {
	  if( is_account_page() || is_cart() || is_checkout() ) {
	    $current_page = 'woocommerce';
	    $default      = 'content';
	  }
	  if( is_shop() || is_product_category() ) {
	    $current_page = 'shop';
	  }
	  if( is_product() ) {
	    $current_page = 'product';
	  }
  }
  if( is_404() ) {
    $current_page = '404';
    $default = 'content';
  }

  $layout = merimag_get_db_customizer_option( 'layout_' . $current_page );
  $layout = !in_array( $layout, array('content-sidebar', 'sidebar-content', 'content') ) ? $default : $layout;
  
  return $layout;
}
function merimag_widget_nav_menu_args( $nav_menu_args, $nav_menu, $args, $instance ) {
	$nav_menu_args['fallback_cb'] = '__return_false';
	$nav_menu_args['is_header']   = false;
	return $nav_menu_args;
}
add_filter('widget_nav_menu_args', 'merimag_widget_nav_menu_args', 10, 4 );

function merimag_mobile_search() {
	$class = is_search() ? 'is-search' : '';
	echo '<div class="merimag-mobile-search-form general-border-color %s">';
	echo '<div class="merimag-full-section-content site-content-width">';
		echo get_search_form();
	echo '</div>';
	echo '</div>';
}
add_action('merimag_after_header', 'merimag_mobile_search');

/**
 * Get container layout
 *
 * @return string the selected site layout
 */
function merimag_get_container_layout( $content_width = true ) {
  
  $container_layout = merimag_get_db_customizer_option( 'container_layout', 'wide' );
  
  $container_layout     = in_array( $container_layout, array('boxed', 'wide') ) ? $container_layout : 'wide';
  if( $content_width === false ) {
    return $container_layout;
  }
  return $container_layout === 'boxed' ? $container_layout . ' site-content-width'  : $container_layout;
}
/**
 * Get cached skin css
 *
 * @return string cached css code
 */
function merimag_get_cached_skin_css() {
	$enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
	if( is_category() || is_single() || $enable_cache !== 'yes' ) {
		return false;
	}
	$skin = merimag_get_db_live_customizer_option('theme_skin', 'default');
	$cache_id = 'merimag_customizer_skin_cache_' . $skin;
	$css = get_transient($cache_id);
	return $css;
}
/**
 * Set cached skin css
 *
 * @return string cached css code
 */
function merimag_set_cached_skin_css( $css ) {
	$enable_cache = merimag_get_db_customizer_option('enable_cache', 'no');
	if( $enable_cache !== 'yes' ) {
		return;
	}
	$skin = merimag_get_db_live_customizer_option('theme_skin', 'default');
	$cache_id = 'merimag_customizer_skin_cache_' . $skin;
	set_transient($cache_id, $css );
}
/**
 * Get skin css
 *
 * @param string $dark_light can be dark or light
 * @param array $args list of arguments
 * @param bool $location a valid css selector
 * @return string css code
 */
function merimag_get_skin_css( $dark_light = 'light', $args = false, $location = 'body', $boxed = false ) {
  $areas                   = merimag_get_location_css_selector( $location, true, $boxed );
  $style                   = '';
  foreach((array) $areas as $area ) {

    $container_layout       = merimag_get_container_layout( false );

    $principal_color        = isset( $args['principal_color'] ) ? $args['principal_color'] : merimag_get_principal_color();
    $dark_light = isset( $args['background'] ) && merimag_validate_color( $args['background'], true ) ? merimag_get_text_color_from_background( $args['background'], true ) : $dark_light;
    if( isset( $args['background'] ) && $args['background'] === $principal_color ) {
    	$args['principal_color'] = $dark_light === 'light' ? merimag_adjustBrightness( $principal_color, -100 ) : merimag_adjustBrightness( $principal_color, 100 );
    }
    $principal_color = isset( $args['principal_color'] ) ? $args['principal_color'] : $principal_color;
    if( isset( $args['generate_colors'] ) && $args['generate_colors'] === true ) {
    	$generate_bg_color      = true;

	    $generate_text_color    = true;

	    $generate_borders_color = true;
    } else {
    	$generate_bg_color      = isset( $args['generate_bg_color'] ) && $args['generate_bg_color'] === true ? true : false;

	    $generate_text_color    = isset( $args['generate_text_color'] ) && $args['generate_text_color'] === true ? $principal_color : false;

	    $generate_borders_color = isset( $args['generate_borders_color'] ) && $args['generate_borders_color'] === true ? $principal_color : false;
    }

    $defaults               = merimag_get_default_colors( $principal_color, $dark_light, $generate_bg_color, $generate_text_color, $generate_borders_color );

    $text_color             = isset( $args['text_color'] ) && merimag_validate_color( $args['text_color'] ) ? $args['text_color'] : $defaults['text_color'];

    $links_color            = isset( $args['links_color'] ) && merimag_validate_color( $args['links_color'] ) ? $args['links_color'] : $defaults['links_color'];

    $links_hover_color      = isset( $args['links_hover_color'] ) && merimag_validate_color( $args['links_hover_color'] ) ? $args['links_hover_color'] : false;

    if( $location === 'body' && !$links_hover_color ) {
    	$links_hover_color = $defaults['links_hover_color'];
    }

    $borders_color          = isset( $args['borders_color'] ) && merimag_validate_color( $args['borders_color'] ) ? $args['borders_color'] : $defaults['borders_color'];

    $background             = isset( $args['background'] ) && merimag_validate_color( $args['background'] ) ? $args['background'] : $defaults['background'];
    

    $boxed_background       = isset( $args['boxed_background'] ) && merimag_validate_color( $args['boxed_background'] ) ? $args['boxed_background'] : $defaults['boxed_background'];

    $background_header      = isset( $args['background_header'] ) && merimag_validate_color( $args['background_header'] ) ? $args['background_header'] : $defaults['background_header_footer'];

    $background_footer      = isset( $args['background_footer'] ) && merimag_validate_color( $args['background_footer'] ) ? $args['background_footer'] : $defaults['background_header_footer'];

    $background_gradient    = isset( $args['background_gradient'] ) && is_array( $args['background_gradient'] ) ? $args['background_gradient'] : false;

    $style                 .= esc_attr( $area ) . ' {
        background-color: ' . esc_attr( $background )  . ';
    }';
    if( $container_layout === 'boxed' ) {
   		$style                 .= esc_attr( $area ) . ' .merimag-site-container { background-color:' . esc_attr( $boxed_background ) . ';}';
    }

    if( isset( $background_gradient[0] ) && merimag_validate_color( $background_gradient[0] ) && isset( $background_gradient[1] ) && merimag_validate_color( $background_gradient[1] ) ) {
      $background_degree = isset( $background_gradient[2] ) ? $background_gradient[2] : 'to bottom';
      $style .= esc_attr( $area ) . ' {
          background-image: ' . sprintf('linear-gradient( %s, %s, %s )', $background_degree , $background_gradient[0], $background_gradient[1] ) . ';
      }';
    }
    if( $location === 'body' ) {

    	if( $container_layout === 'boxed' ) {
    		$style   .= merimag_get_custom_selector_css('from_background_color', $boxed_background, 'body.site-body .merimag-site-container' );
    	}

        $style .= 'body.site-body .merimag-site-header, body.site-body .merimag-site-footer {
            background: ' . esc_attr( $background_header )  . ';
        }';
        $style .= merimag_get_custom_selector_css('from_background_color', $background_header, 'body.site-body .merimag-site-header, body.site-body .merimag-site-footer');

        $style .= 'body.site-body .merimag-site-footer {
            background: ' . esc_attr( $background_footer )  . ';
        }';
        $style .= merimag_get_custom_selector_css('from_background_color', $background_footer, 'body.site-body .merimag-site-footer');

        $skin = merimag_get_db_live_customizer_option('theme_skin');


        if( file_exists( get_template_directory() . '/assets/skins/' . $skin . '/bg.jpg') ) {
        	$style .= 'body.site-body { background-image: url(' . esc_attr( get_template_directory_uri() . '/assets/skins/' . $skin . '/bg.jpg') . '); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; background-position: center center; }';
        }

    }

    $style    .= isset( $args['principal_color'] ) && merimag_validate_color( $args['principal_color'] ) ? merimag_get_principal_color_css( $args['principal_color'], $area, false ) : '';

    $style    .= merimag_get_borders_color_css( $borders_color, $area );

    $style    .= merimag_get_text_color_css( $text_color, $links_color, $links_hover_color, $area );

    $style   .= merimag_get_custom_selector_css('from_background_color', $background, $area );

  }

  $areas_normal = merimag_get_location_css_selector( $location, false );

  return $style;
}
/**
 * Return true if the current device is a mobile device
 * 
 * @return bool
 */
function merimag_is_mobile() {
    global $mobile_detect;
    $is_mobile = false;
    if( class_exists( 'Mobile_Detect') && $mobile_detect instanceof Mobile_Detect) {
        if( $mobile_detect->isMobile() ) {
            $is_mobile = true;
        }
    }
    return $is_mobile;
}
/**
 * Hook to load skins
 * 
 * @return void
 */
function merimag_skin_init(){

    $skin = merimag_get_db_live_customizer_option('theme_skin', 'default');
    
    $skin_css_file = get_template_directory() . '/assets/skins/' . esc_attr( $skin ) . '/style.css';

    if( file_exists( $skin_css_file ) ) {

        wp_enqueue_style( 'merimag-skin-dynamic-css', get_template_directory_uri() . '/assets/skins/' . esc_attr( $skin ) . '/style.css', array(), THEME_VERSION);
    } else {
    	wp_enqueue_style( 'merimag-skin-dynamic-css', get_template_directory_uri() . '/assets/css/dynamic.css', array(), THEME_VERSION);
    }
    
    $skin_file = get_template_directory() . '/assets/skins/' . esc_attr( $skin ) . '/get.php';

    if( file_exists( $skin_file ) ) {

        require_once( $skin_file );
    }
    
}
add_action('wp_enqueue_scripts', 'merimag_skin_init', 9 );
add_action('amp_post_template_css', 'merimag_skin_init', 99 );
/**
 * Hook to load skins filters
 * 
 * @return void
 */
function merimag_skin_filters(){

    $skin = merimag_get_db_live_customizer_option('theme_skin', 'default');

    $skin_file = get_template_directory() . '/assets/skins/' . esc_attr( $skin ) . '/filters.php';

    if( file_exists( $skin_file ) ) {

        require_once( $skin_file );
    }

    $logo_file =  '/assets/skins/' . esc_attr( $skin ) . '/logo.png';

    $logo_rtl = '/assets/skins/' . esc_attr( $skin ) . '/logo-rtl.png';
    
    if( ( class_exists('WPMDM') )  && file_exists(get_template_directory() . $logo_file) ) {
		add_filter('logo', function( $logo ) use( $logo_file ) {
			
			return array('url' => get_template_directory_uri() . $logo_file );
		});
	}

	if( ( class_exists('WPMDM') )  && file_exists(get_template_directory() . $logo_rtl) && is_rtl() ) {
		add_filter('logo', function( $logo ) use( $logo_rtl ) {
			
			return array('url' => get_template_directory_uri() . $logo_rtl );
		});


	}

	$logo_mobile =  '/assets/skins/' . esc_attr( $skin ) . '/logo-mobile.png';
    
    if( class_exists('WPMDM') && file_exists(get_template_directory() . $logo_mobile) ) {
		add_filter('mobile_logo', function( $logo ) use( $logo_mobile ) {
			
			return array('url' => get_template_directory_uri() . $logo_mobile );
		});
		
	}
	if( class_exists('WPMDM') ) {
		add_filter('footer_about_text', function() {
			if( is_rtl() ) {
				return 'موقع عالمي اخباري مهتم بالشؤون السياسية و الرياضية و الفنية و الثقافية ينقل لكم كل جديد في ضرف وجيز تابعونا';
			} else {
				return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor odio sed justo porttitor ullamcorper. Duis eu ultricies massa.';
			}
		});
		if( is_rtl() ) {
			add_filter('logo_height', function() {
				return 65;
			});
		}
	}
	add_filter('footer_tags_title', function($title) {
		return esc_html__('# TRENDING', 'merimag');
	});
}
if( is_customize_preview() ) {
	add_action('customize_preview_init', 'merimag_skin_filters', 98);
}
add_action('after_setup_theme', 'merimag_skin_filters', 98);

/**
 * Get default colors
 *
 * @param string $principal_color valid css hex color
 * @param string $dark_light can be dark or light
 * @param bool $from_principal_color_background generate background from principal color if true
 * @param bool $from_principal_color_text generate text color from principal color if true
 * @param bool $from_principal_color_borders generate border color from principal color if true
 * @return string adjusted hexh color
 */
function merimag_get_default_colors( $principal_color, $dark_light, $from_principal_color_background = false, $from_principal_color_text = false, $from_principal_color_borders = false) {

    $defaults = array(
        'text_color' => '#6d6d6d',
        'links_color' => '#2d2d2d',
        'links_hover_color' => $principal_color,
    );

    $defaults_borders = array(
        'borders_color' => 'rgba(0,0,0,0.1)',
    );

    $defaults_background = array(
        'background' => '#fff',
        'boxed_background' => '#eee',
        'background_header_footer' => '#fff',
    );

    $defaults_dark = array(
        'text_color' => '#ccc',
        'links_color' => '#fff',
        'links_hover_color' => $principal_color,
    );

    $defaults_dark_borders = array(
        'borders_color' => 'rgba(255,255,255,0.1)',
    );

    $defaults_dark_background = array(
        'background' => '#333333',
        'boxed_background' => '#3e3e3e',
        'background_header_footer' => '#242424',
    );

    $from_color_args = array(
        'text_color' => merimag_adjustBrightness( $principal_color, -80 ),
        'links_color' => merimag_adjustBrightness( $principal_color, -120 ),
        'links_hover_color' => merimag_adjustBrightness( $principal_color, -160 ),
    );

    $from_color_args_borders = array(
        'borders_color' => merimag_adjustBrightness( $principal_color, -30 ),
    );

    $from_color_args_background = array(
        'background' => merimag_adjustBrightness( $principal_color, 50 ),
        'boxed_background' => merimag_adjustBrightness( $principal_color, 30 ),
        'background_header_footer' => merimag_adjustBrightness( $principal_color, 90 ),
    );

    $from_color_args_dark = array(
        'text_color' => merimag_adjustBrightness( $principal_color, 140 ),
        'links_color' => merimag_adjustBrightness( $principal_color, 180 ),
        'links_hover_color' => $principal_color,
    );

    $from_color_args_dark_borders = array(
        'borders_color' => merimag_adjustBrightness( $principal_color, -50 ),
    );

    $from_color_args_dark_background = array(
        'background' => merimag_adjustBrightness( $principal_color, -50 ),
        'boxed_background' => merimag_adjustBrightness( $principal_color, -30 ),
        'background_header_footer' => merimag_adjustBrightness( $principal_color, -90 ),
    );

    switch ($dark_light) {
        case 'light':
            switch ($from_principal_color_background) {
                case true:
                    $args['defaults_background'] = $from_color_args_background;
                    break;
                default:
                    $args['defaults_background'] = $defaults_background;
                    break;
            }
            switch ($from_principal_color_borders) {
                case true:
                     $args['defaults_borders'] = $from_color_args_borders;
                    break;
                default:
                    $args['defaults_borders'] = $defaults_borders;
                    break;
            }
            switch ($from_principal_color_text) {
                case true:
                    $args['defaults'] = $from_color_args;
                    break;
                default:
                    $args['defaults'] = $defaults;
                    break;
            }
            break;
        case 'dark':
            switch ($from_principal_color_background) {
                case true:
                    $args['defaults_background'] = $from_color_args_dark_background;
                    break;
                default:
                    $args['defaults_background'] = $defaults_dark_background;
                    break;
            }
            switch ($from_principal_color_borders) {
                case true:
                    $args['defaults_borders'] = $from_color_args_dark_borders;
                    break;
                default:
                    $args['defaults_borders'] = $defaults_dark_borders;
                    break;
            }
            switch ($from_principal_color_text) {
                case true:
                    $args['defaults'] = $from_color_args_dark;
                    break;
                default:
                    $args['defaults'] = $defaults_dark;
                    break;
            }
            break;
    }
    $args = array_merge( $args['defaults'], $args['defaults_background'], $args['defaults_borders'] );
    return $args;
}

/**
 * Get custom css selectors to be used to generate css
*/
function merimag_get_theme_areas_selectors() {
    $area_settings = array(
        'background-color',
        'content-background-color',
        'background-gradient',
        'background-gradient[primary]',
        'background-image',
        'background-position',
        'background-repeat',
        'background-attachment',
        'background-size',
        'border-width',
        'border-color',
        'border-style',
        'text-color',
        'links-color',
        'links-hover-color',
        'principal-color',
        'buttons-color',
        'borders-color',
        'padding',
        'padding[top]',
        'margin',
        'margin[top]',
    );
   
    $araes_selectors = merimag_get_locations_selectors();

    $theme_selectors = array();

    foreach( $araes_selectors as $area_selector => $selector ) {
        foreach( $area_settings as $setting ) {
            $selector_id = $area_selector . '_' . str_replace('-', '_', $setting );
            $theme_selectors[$selector_id][$setting] = $selector;   
        }
    }

    return $theme_selectors;
}
/**
 * Get custom css selectors to be used to generate typography css
*/
function merimag_get_theme_typography_selectors() {
    $area_settings = array(
        'font-family',
        'font-style',
        'font-weight',
        'text-transform',
        'font-size',
        'line-height',
        'letter-spacing'
    );

    $araes_selectors = merimag_get_locations_selectors();

    $theme_selectors = array();

    foreach( $araes_selectors as $area_selector => $selector ) {
        foreach( $area_settings as $setting ) {
            $selector_id = $area_selector . '_' . str_replace('-', '_', $setting );
            $theme_selectors[$selector_id][$setting] = $selector;   
        }
    }

    return $theme_selectors;
}
/**
 * Get above the fold css selectors
 */
function merimag_get_above_the_fold_selectors() {
	return array('body', 'site_container', 'content_container', 'header', 'header_inner', 'main_menu', 'breadcrumbs');
}
/**
 * Get location css selectors
 */
function merimag_get_locations_selectors() {
    return array(
        'body'           => 'body',
        'site_container' => '.merimag-site-container',
        'block_logo_header' => '.merimag-block-logo',
        'content_container' => '.merimag-content-container',
        'header'         => '.merimag-site-header', 
        'header_inner'   => '.merimag-site-header-content',
        'main_menu'      => '.merimag-main-navigation-background',
        'main_menu_sub_menu' => '.merimag-main-navigation-wrapper .sub-menu',
        'secondary_menu' => '.merimag-top-navigation',
        'secondary_menu_sub_menu' => '.merimag-top-navigation .sub-menu',
        'breadcrumbs'    => '.merimag-page-header',
        'article_content' => '.merimag-article-content > *:not(.merimag-inline-related-posts)',
        'ticker'         => '.merimag-main-ticker',
        'footer_about'   => '.merimag-footer-about',
        'footer_tags'    => '.merimag-footer-tags',
        'footer'         => '.merimag-site-footer',
        'footer_infos'   => '.merimag-footer-infos',
        'sticky_header'    => '.merimag-sticky-header',
        'sticky_header_sub_menu' => '.merimag-sticky-header .sub-menu',
        'mobile_header'    => '.merimag-mobile-header',
        'mobile_menu_panel' => '.merimag-sidebar',
        
        'general_block' => '.general-box-container:not(.ignore-general-style)',
        'general_widget' => '.sidebar-widget:not(.ignore-general-style)',
        'general_content_area' => '.site-content-area-style',
        'general_sidebar_area' => '.merimag-widget-area',
        'general_section' => '.section-container',
    );
}
/**
 * Get location css selector
 * Normalize the theme location css selectors
 *
 * @param string $location a valid theme location
 * @param bool $important add a parent selector to increase the priority  
 * @return string valid css selector
 */
function merimag_get_location_css_selector( $location, $important = true, $boxed = false ) {
    $locations = merimag_get_locations_selectors();
    $areas  = isset( $locations[ $location ] ) ? $locations[ $location ] : array();
    $areas  = explode(',', $areas );
    $return = array();
    $body_class = $boxed === true ? 'body.boxed' : 'body.site-body';
    foreach( $areas as $area ) {
      if( $important === true ) {
        $return[] = $location === 'body' ? $body_class : $body_class . ' ' . esc_attr( $area );
      } else {
        $return[] = $area;
      }
    }
    return $return;
    
}
/**
 * Get header style
 * 
 * @return string the id of the site header style filtered
 */ 
function merimag_get_header_style() {

  $header_style = merimag_get_db_customizer_option('header_style', 'default' );

  $header_style = empty( $header_style ) || $header_style === 'default' ? 1 : $header_style;

  return $header_style;
}
/**
 * Display site header
 * 
 * @return void
 */ 
function merimag_get_header( $sv = false ) {

  $header_style          = merimag_get_header_style(); 

  $secondary_menu_enable = merimag_get_db_customizer_option('enable_top_menu' );

  $main_menu_enable      = merimag_get_db_customizer_option('enable_main_menu' );


  $header_spacing        = merimag_get_db_customizer_option('header_spacing', 'medium' );

  $secondary_menu_spacing = merimag_get_db_customizer_option('secondary_menu_spacing', 'small' );

  $header_spacing        = !empty( $header_spacing ) && is_string( $header_spacing ) && in_array( $header_spacing, array('big', 'medium', 'small' ) ) ? 'merimag-header-spacing-' . esc_attr( $header_spacing ) : '';

  $header_style          = $header_style ? $header_style : 2;

  $main_side_borders     = merimag_get_db_customizer_option('main_menu_items_borders_color', '' );

  $main_side_borders     = !empty( $main_side_borders ) ? 'merimag-site-navigation-side-borders' : '';

  $second_side_borders   = merimag_get_db_customizer_option('secondary_menu_items_borders_color', '' );

  $second_side_borders   = !empty( $second_side_borders ) ? 'merimag-site-navigation-side-borders' : '';

  $mobile_search_icon    = merimag_get_db_customizer_option('mobile_menu_search', 'yes');

  $mobile_cart_icon      = class_exists('WooCommerce') ? merimag_get_db_customizer_option('mobile_menu_cart', 'no') : 'no';

  $mobile_full_icons     = $mobile_search_icon === 'yes' && $mobile_cart_icon === 'yes' ? true : false;

  $mobile_no_icons       = $mobile_search_icon === 'no' && $mobile_cart_icon === 'no' ? true : false;

  $mobile_has_icons      = $mobile_search_icon === 'yes' || $mobile_cart_icon === 'yes' ? true : false;

  $content_part_class    = $mobile_full_icons === true ? ' mobile-full-icons ' : '';
  $content_part_class   .= $mobile_no_icons === true ? ' mobile-no-icons ' : '';
  $content_part_class   .= $mobile_has_icons === true ? ' mobile-has-icons ' : '';
  $content_part_class   .= $mobile_has_icons === true && $mobile_full_icons === false ? ' mobile-one-icon ' : '';

  merimag_get_builder_section('before_header');

  if( !locate_template('includes/headers/header-' . esc_attr( $header_style ) . '.php') ) {
  	$header_style = 1;
  }
  $overlay_header = merimag_get_db_customizer_option('overlay_header');
  $transparent_header = merimag_get_db_customizer_option('transparent_header', 'no');

  $class = $overlay_header === 'yes' ? 'merimag-absolute-header' : '';
  $class .= $transparent_header === 'yes' ? ' merimag-transparent-header' : '';
  $header_file           = 'includes/headers/header-' . esc_attr( $header_style ) . '.php';
  if( locate_template( $header_file ) ) {
  	echo sprintf('<div class="merimag-header-file-container %s">', esc_attr($class) );
    require_once( locate_template( $header_file ) );
    echo '</div>';
  }
  $sticky_header_file           = 'includes/headers/components/sticky-header.php';
  if( locate_template( $sticky_header_file ) ) {
    require_once( locate_template( $sticky_header_file ) );
  }

  $mobile_header_file           = 'includes/headers/components/mobile-header.php';
  if( locate_template( $mobile_header_file ) ) {
    require_once( locate_template( $mobile_header_file ) );
  }

  if( function_exists('rwd_print_styles') && $sv === true  ) {
    rwd_print_styles();
  }

  merimag_get_builder_section('after_header');
  
}
add_action( 'wp_ajax_merimag_get_header', 'merimag_get_header' );

add_action('wp_footer', function() {
	
});
/**
 * Check if sidebar has content
 * 
 * @return bool
 */ 
if( !function_exists('sidebar_has_content') ) {
    function sidebar_has_content( $sidebar ) {
        ob_start();
        dynamic_sidebar( $sidebar );
        $content = ob_get_contents();
        ob_end_clean();
        return $content ? true : false;
    } 
}

/**
 * Display footer
 * 
 * @return void
 */ 
function merimag_get_footer( $sv = false ) {
    $instagram          = 'no'; // we try to find a fix for the instagram feed
    $usertag            = merimag_get_db_customizer_option('footer_instagram_usertag');
    $scroll_instagram   = merimag_get_db_customizer_option('footer_instagram_scroll');
    $list_class         = $scroll_instagram == 'yes' ? 'merimag-marquee' : '';
    $instagram_number   = merimag_get_db_customizer_option('footer_instagram_number');
    $custom_width       = $scroll_instagram == 'yes' ? 200 : false;
    $instagram_lazy     = $scroll_instagram == 'yes' ? false : true;
    $feed_class         = $scroll_instagram == 'yes' ? 'scrolling' : '';
    $only_images        = merimag_get_db_customizer_option('footer_instagram_only_images');
    $post_type          = get_post_type();
    $footer_menu        = merimag_get_db_customizer_option('enable_footer_menu');
    $footer_widgets     = merimag_get_db_customizer_option('enable_footer_widgets', 'yes');
    $footer_social 		= merimag_get_db_customizer_option('enable_footer_social', 'yes');;
    $copyrights_text    = merimag_get_db_customizer_option('footer_copyrights_text', sprintf( esc_html__('%s Copyright %s, All Rights Reserved', 'merimag'), '&copy;', date('Y') ) );
    $copyrights_layout  = merimag_get_db_customizer_option('footer_copyrights_layout', 'inline');
    $footer_infos_class = $copyrights_layout === 'inline' ? 'merimag-site-info-inline' : 'merimag-site-info-centered';
    ?>
    <?php if( $instagram !== 'no' ) : ?>
    <div class="merimag-footer-instagram-feed <?php echo esc_attr($feed_class)?>">
        <?php merimag_get_shortcode_html('instagram', array('block_id' => 'footer_instagram', 'instagram_usertag' => $usertag, 'instagram_follow_button' => 'yes','instagram_custom_width' => $custom_width, 'instagram_lazy_load' => $instagram_lazy, 'instagram_only_images' => $only_images, 'instagram_cols' => '6', 'instagram_number' => $instagram_number, 'list_class' => $list_class)); ?>
    </div>
    <?php endif; ?>
    <footer id="colophon" class="merimag-site-footer principal-color-border-top-color merimag-full-section">
        <?php do_action('merimag_before_footer_inner');  ?>
        <?php if (  sidebar_has_content( 'footer-sidebar' ) && $footer_widgets === 'yes' ) : ?>
        <div class="merimag-footer-widgets merimag-footer-section ">
            <aside id="merimag-footer-widget-area" class="merimag-full-section-content site-content-width">
                <div class="merimag-footer-widget-area">
                    <?php dynamic_sidebar( 'footer-sidebar' ); ?>
                </div>
            </aside><!-- .site-info -->
        </div>
        <?php endif; ?>
        <?php do_action('merimag_after_footer_inner');  ?>
        <div class="merimag-footer-infos <?php echo esc_attr($footer_infos_class)?>">
            <div class="merimag-site-info site-content-width  merimag-full-section-content">
                <div class="merimag-site-copyrights">
                    <?php echo wp_specialchars_decode(esc_attr($copyrights_text), ENT_QUOTES); ?>
                </div>

                <?php if( $footer_menu !== 'no' ) : ?>
                <div class="merimag-footer-menu">
                    <?php
                        merimag_wp_nav_menu( array(
                            'theme_location' => 'footer-menu',
                            'menu_id'        => 'footer-menu',
                            'fallback_cb'    => '__return_false'
                        ) );
                    ?>
                     <?php if( $footer_social === 'yes' ) : ?>
	                <div class="merimag-footer-social">
	                	<?php echo merimag_get_menu_social()?>
	                </div>
	                <?php endif; ?>
                </div>
                <?php endif; ?>

            </div><!-- .site-info -->
        </div>
    </footer><!-- #colophon -->
    <?php

    if( function_exists('rwd_print_styles') && $sv === true  ) {
        rwd_print_styles();
    }
}

/**
 * Get logo height
 * Calculate the height of the seleted site logo
 *
 * @return integer height of the selected site logo
 */
function merimag_get_logo_height( $logo_from = '') {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if( $custom_logo_id ) {
		$data        = wp_get_attachment_metadata( $custom_logo_id );
		$height      = isset( $data['height'] ) ? $data['height'] : false;
		return $height % 2 === 0 ? $height : $height - 1;
	}
	$logo_from = $logo_from ? $logo_from . '_' : '';
	$logo_height = merimag_get_db_customizer_option($logo_from.'logo_height');
	if( isset( $logo_height ) && is_numeric( $logo_height ) ) {
		$height      =  $logo_height;
	} else {
		$logo        = merimag_get_db_customizer_option( $logo_from . 'logo');
		$logo_id     = isset( $logo['attachment_id'] ) ? $logo['attachment_id'] : false;
		$data        = wp_get_attachment_metadata( $logo_id );
		$height      = isset( $data['height'] ) ? false : false;
	}
	return $height % 2 === 0 ? $height : $height - 1;
}
/**
 * Display site logo
 *
 * @return void
 */
function merimag_get_logo( $logo_from = '' ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id && function_exists( 'the_custom_logo' ) ) {
	 the_custom_logo();
	 return;
	}
	$from  		 = $logo_from ? $logo_from . '_' : '';
	$logo        = merimag_get_db_customizer_option( $from . 'logo' );
	$logo        = !$logo && $logo_from !== 'logo' ? merimag_get_db_customizer_option('logo') : $logo;
	$logo_url    = isset( $logo['url'] ) ? $logo['url'] : false;
	$tag         = is_front_page() && is_home() ? 'h1' : 'div';
	$logo_height = merimag_get_logo_height($logo_from);
	$logo_height = $logo_height ? sprintf('max-height:%spx', $logo_height ) : '';
	$class 		 = $logo ? ' logo-branding ' : ' text-branding ';
	echo sprintf( '<div class="merimag-site-branding %s">', esc_attr( $class ) );
	if( $logo ) {
		echo '<'. esc_attr($tag) .'  class="site-logo"><a title="' . esc_attr( get_bloginfo('name')) . '" href="' . esc_url( merimag_home_url() ) . '"><img style="' . esc_attr($logo_height) . '"  alt="' . esc_attr( get_bloginfo('name')) . '" src="' . esc_url( $logo_url ) . '"></a></'. esc_attr($tag) .'>';
	} else {
		echo sprintf( '<%1$s class="merimag-site-title site-title site-logo h1-title"><a href="%3$s">%2$s</a></%1$s><div class="site-description">%4$s</div>', esc_attr( $tag ), get_bloginfo('title'), esc_url( merimag_home_url() ), get_bloginfo('description') );
	}
	echo '</div>';
}
/**
 * Get recognized theme skins
 *
 * @param bool $keys return only keys if this is true
 * @return array the list of valid skin colors
 */
function merimag_get_recognized_skins( $keys = false ) {
    $skins = array(
        'default'      => 'Default',
        'default-dark' => 'Default dark',
        'default-2'    => 'Default 2',
        'news'    		=> 'News',
        'tech'    		=> 'Tech',
        'games'    		=> 'Games',
        'video'    		=> 'Video',
        'travel'    	=> 'Travel',
        'adventure'    	=> 'Adventure',
        'food' 		=> 'Food',
        'photography' => 'Photography',
        'crypto' => 'Crypto',
        'sports' => 'Sports',
        'cars' => 'Cars',
        'times' => 'Times',
        'life' => 'Lifestyle',
        'shop' => 'Shop',
    );
    return $keys === false ? apply_filters( 'merimag_get_recognized_skins', $skins ) : array_keys( $skins );
}


/**
 * Get post to arguments to be used in single template based on chosen template
 *
 * @param string $template the template id
 * @return array list of arguments
 */
function merimag_get_post_template_args( $template = false ) {
  $template = !$template ? merimag_get_db_customizer_option('post_template') : $template;

  $template = !$template || $template === 'default' ? apply_filters('merimag_post_template', $template ) : $template;
  $media_type = merimag_get_post_featured_media_type();
  $args     = array();
  switch ($template) {
    case '2':
      $args['single_centered_infos'] = true;
      break;
    case '3':
      $args['single_absolute_infos'] = true;
      break;
    case '4':
      $args['single_full_image'] = true;
      $args['single_absolute_infos'] = true;
      break;
    case '5':
      $args['single_full_image'] = true;
      $args['single_absolute_infos'] = true;
      $args['single_absolute_infos_center'] = true;
      $args['single_centered_infos'] = true;
      break;
    case '6':
      $args['single_stretched_image'] = true;
      $args['single_full_image'] = true;
      $args['single_absolute_infos'] = true;
      $args['single_absolute_infos_center'] = true;
      $args['single_centered_infos'] = true;
      break;
    case '13':
      $args['single_stretched_image'] = true;
      $args['single_full_image'] = true;
      $args['single_absolute_infos'] = true;
      $args['single_absolute_infos_center'] = true;
      $args['single_centered_infos'] = true;
      $args['single_show_arrow'] = true;
      break;
    case '7':
      $args['single_full_image'] = true;
      $args['single_stretched_image'] = true;
      $args['single_absolute_infos'] = true;
      $args['single_marged_infos'] = true;
      break;
    case '18':
      $args['single_full_image'] = true;
      $args['single_absolute_infos'] = true;
      $args['single_marged_infos'] = true;
      break;
    case '8':
      $args['single_full_image'] = true;
      break;
    case '10':
      $args['single_title_after_image'] = true;
      break;
    case '11':
      $args['single_full_image'] = true;
      $args['single_title_full'] = true;
      break;
    case '12':
      $args['single_full_image'] = true;
      $args['single_title_full'] = true;
      $args['single_centered_infos'] = true;
      break;
    case '14':
      $args['single_dark_media_container'] = true;
      $args['single_full_image'] = true;
      $args['single_top_sidebar'] = true;
      break;
    case '9':
      $args['single_dark_media_container'] = true;
      $args['single_full_image'] = true;
      $args['single_title_full'] = true;
      $args['single_top_sidebar'] = true;
      break;
    case '15':
      $args['single_dark_media_container'] = true;
      $args['single_full_image'] = true;
      break;
    case '16':
      $args['single_dark_media_container'] = true;
      $args['single_full_image'] = true;
      $args['single_title_full'] = true;
      $args['single_title_after_image'] = true;
      break;
    case '17':
      $args['single_dark_media_container'] = true;
      $args['single_full_image'] = true;
      $args['single_title_full'] = true;
      break;
  }
  if( $media_type !== 'image' ) {
    $args['single_absolute_infos'] = false;
    $args['single_stretched_image'] = false;
  }
  return $args;
}


/**
 * Get recognized menu items styles
 *
 * @param string $location a valid theme location
 * @param bool $keys if true function will return array keys  
 * @return array valid menu items styles
 */
function merimag_get_recognized_menu_items_styles( $keys = false ) {
    $styles = array(
        'default' => array(
          'small' => array(
              'src' => get_template_directory_uri() . '/assets/images/menu/items/default.png',
           )
        ),
        'border-top' => array(
          'small' => array(
              'src' => get_template_directory_uri() . '/assets/images/menu/items/1.png',
           )
        ),
        'border-bottom' => array(
          'small' => array(
              'src' => get_template_directory_uri() . '/assets/images/menu/items/2.png',
           )
        ),
        'background' => array(
          'small' => array(
              'src' => get_template_directory_uri() . '/assets/images/menu/items/6.png',
           )
        ),
        'background-radius' => array(
          'small' => array(
              'src' => get_template_directory_uri() . '/assets/images/menu/items/8.png',
           )
        ),
        'background-close-border-top' => array(
          'small' => array(
              'src' => get_template_directory_uri() . '/assets/images/menu/items/7.png',
           )
        ),
    );
    return $keys === true ? array_keys( $styles ) : $styles;
}

/**
 * Display ads
 *
 * @return array list of post objects
 */
function merimag_get_ad( $ad, $section = true ) {
    if(!$ad ) {
         return;
    }

    $ad_group_id = merimag_get_db_customizer_option( $ad . '_ad' );
    if( !$ad_group_id ) {
        return;
    }

    $background = merimag_get_db_customizer_option( $ad . '_ad_background' );
    $padding = merimag_get_db_customizer_option( $ad . '_ad_padding' );
    $section_style = merimag_validate_color( $background ) ? sprintf('background-color: %s;', $background ) : '';
    $section_style .= merimag_generate_spacing_css( 'padding', $padding );
    if( do_shortcode('[adsforwp-group id="' . esc_attr( $ad_group_id ) . '"]') ) {
        if( $section === true ) {
            echo sprintf( '<div style="%s" class="merimag-full-section merimag-ad-section">', esc_attr($section_style));
            echo '<div class="merimag-full-section-content site-content-width">';
        }
        echo do_shortcode('[adsforwp-group id="' . esc_attr( $ad_group_id ) . '"]');
        if( $section === true ) {
            echo '</div></div>';
        }
    }
}
/**
 * Header content ad
 *
 * @return void
 */
function merimag_get_header_content_ad() {
	ob_start();
	merimag_get_core_ad('header');
	$header_core_content = ob_get_contents();
	ob_end_clean();
	if( !$header_core_content ) {
		ob_start();
	    merimag_header_content();
	    $header_content = ob_get_contents();
	    ob_end_clean();
	    if( $header_content ) {
	    	return;
	    }
	    $ad_id = merimag_get_db_customizer_option( 'header_content_ad' );
	    if( $ad_id ) {
	        echo '<div class="merimag-header-content-ad">';
	        echo do_shortcode('[adsforwp-group id="' . esc_attr( $ad_id ) . '"]');
	        echo '</div>';
	    }
	} else {
		merimag_get_core_ad('header');
	}
    if( class_exists('WPMDM') ) {
    	echo merimag_get_demo_ad('728x90');
    }
}
/**
 * Display ads
 *
 * @return array list of post objects
 */
function merimag_get_core_ad( $ad ) {
    $default_header_ad = merimag_get_db_customizer_option( 'default_' . $ad . '_ad');
	$default_show_ad = isset( $default_header_ad['ad'] ) ? $default_header_ad['ad'] : false;
	$header_ad = merimag_get_db_customizer_option(  $ad . '_ad');
	$show_ad = isset( $header_ad['ad'] ) ? $header_ad['ad'] : false;
	$header_ad = $show_ad == 'default' || !$show_ad ? $default_header_ad : $header_ad;
	$show_ad = $show_ad == 'default' || !$show_ad  ? $default_show_ad : $show_ad;
	if( $show_ad == 'show' && isset( $header_ad['show'] ) ) {
		$header_ad = $header_ad['show'];
		$header_ad_title = isset( $header_ad['title'] ) && $header_ad['title'] ? $header_ad['title'] : '';
		$header_ad_code = isset( $header_ad['code'] ) && $header_ad['code'] ? $header_ad['code'] : '';
		$header_ad_image = isset( $header_ad['image'] ) && $header_ad['image'] ? $header_ad['image'] : '';
		$header_ad_link = isset( $header_ad['link'] ) && $header_ad['link'] ? $header_ad['link'] : '';
		$class = $ad !== 'header' ? 'merimag-full-section' : '';
		$content_class = $ad !== 'header' ? 'merimag-full-section-content site-content-width' : '';
		if( $header_ad_code || (isset( $header_ad_image['url'] ) &&  $header_ad_image['url']) ) {
			echo sprintf('<div class="merimag-ad-section %s">', esc_attr( $class) );
			echo sprintf('<div class="merimag-ad-content %s">', esc_attr( $content_class ) );
			if( $header_ad_code ) {
				echo wp_specialchars_decode( esc_attr( $header_ad_code ), ENT_QUOTES );
			} else {
				if( isset( $header_ad_image['url'] ) &&  $header_ad_image['url'] ) {
					echo sprintf('<a href="%s" target="_blank"><img src="%s" alt="%s" /></a>', esc_url( $header_ad_link ), esc_attr( $header_ad_image['url'] ), esc_attr( $header_ad_title ) );
				}
			}
			echo '</div></div>';
		}
	}
}
/**
 * Display ads
 *
 * @return array list of post objects
 */
function merimag_get_ad_old( $ad, $section = true ) {
    if(!$ad || !class_exists('adsforwp_view_placement')) {
         return;
    }

    $ad_group_id = merimag_get_db_customizer_option( $ad . '_ad' );

    $background = merimag_get_db_customizer_option( $ad . '_ad_background' );
    $padding = merimag_get_db_customizer_option( $ad . '_ad_padding' );
    $section_style = merimag_validate_color( $background ) ? sprintf('background-color: %s;', $background ) : '';
    $section_style .= merimag_generate_spacing_css( 'padding', $padding );
    $placement_obj = new adsforwp_view_placement();
    $ad_ids = merimag_get_db_post_option( $ad_group_id, 'merimag_group_ads');
    foreach((array) $ad_ids as $ad_id ) {
        $ad_status = $placement_obj->adsforwp_get_post_conditions_status($ad_id);
        if( $ad_status ) break;
    }
    if( isset( $ad_status ) && $ad_status ) {
        if( $section === true ) {
            echo sprintf( '<div style="%s" class="merimag-full-section merimag-ad-section">', esc_attr($section_style));
            echo '<div class="merimag-full-section-content site-content-width">';
        }
        echo '<div class="merimag-ad-slider">';
        foreach( $ad_ids as $ad_id ) {
            $ad_status = $placement_obj->adsforwp_get_post_conditions_status($ad_id);
            if( $ad_status ) {
                echo '<div class="merimag-ad-slide">';
                echo do_shortcode(sprintf('[adsforwp id="%s"]', $ad_id ));
                echo '</div>';
            }
        }
        echo '</div>';
        if( $section === true ) {
            echo '</div></div>';
        }
    }
}
/**
 * Amp ads
 *
 * @return void
 */
function merimag_get_amp_ad( $ad ) {
    if(!$ad || !is_string( $ad ) ) {
        return;
    } 
    ob_start();
    do_action($ad);

    $content = ob_get_contents();
    ob_end_clean();

    if (!empty($content)) {
        ?>
        <div class="amp-ad">
            <div class="amp-site-content">
                <?php do_action($ad); ?>
            </div>
        </div>
        <?php
    }
}
/**
 * Promo bar html
 *
 * @return void
 */
function merimag_get_promo_bar() {
    $text = merimag_get_db_customizer_option('promo_bar_text');
    $link = merimag_get_db_customizer_option('promo_bar_link');
    $home = merimag_get_db_customizer_option('promo_bar_home');
    if( $home === 'yes' && !is_home() ) {
        return;
    }
    if( $text ) {
        ?>
        <div class="merimag-full-section merimag-promo-bar principal-color-background-color">
            <div class="merimag-full-section-content site-content-width">
                <?php echo wp_specialchars_decode( esc_attr( $text ), ENT_QUOTES ); ?>
                <a href="#" class="merimag-promo-bar-close"><i class="icofont-close-circled"></i></a>
            </div>
        </div>
        <?php
    }
}

/**
 * Get all selectors
 *
 * @return array list of all selectors
 */
function merimag_custom_selectors() {
   return array_merge( merimag_custom_slectors(), merimag_get_theme_areas_selectors() );
}

/**
 * Display builder section content
 *
 * @return void
 */
function merimag_get_builder_section_content( $position ) {
    $builder_page = merimag_get_db_customizer_option('builder_section_' . $position );

    if( isset( $builder_page ) && get_post_type( $builder_page ) === 'builder_section' ) {
    	
        echo '<div class="page-builder-template">';
        echo '<div class="merimag-full-section merimag-builder-section">';
        if( current_user_can('edit_post', $builder_page) ) {
            $edit_link = get_edit_post_link( $builder_page );
            echo sprintf('<a class="merimag-edit-section-link" target="_blank" href="%s">%s</a>', esc_url( $edit_link ), esc_html__('Edit section', 'merimag' ) ); 
        }
        if (class_exists("\\Elementor\\Plugin") && isset( $builder_page ) ) {
            $css_file = \Elementor\Core\Files\CSS\Post::create( $builder_page );
	        $css_file->enqueue();
            echo \Elementor\Plugin::$instance->frontend->get_builder_content( $builder_page );
        }
        echo '</div>';
        echo '</div>';
    }
}
/**
 * Display builder section content and it's wrapper
 *
 * @return void
 */
function merimag_get_builder_section( $position ) {
    $builder_page = merimag_get_db_customizer_option('builder_section_' . $position );
    echo sprintf('<div class="merimag-builder-section-%s">', esc_attr( $position ) );
    if( isset( $builder_page ) && get_post_type( $builder_page ) === 'builder_section' ) {
        merimag_get_builder_section_content($position );
    }
    echo '</div>';
}
/**
 * Filter function to add unique id to blocks
 *
 * @param array $options list of options
 * @return array list of all selectors
 */
function merimag_filter_theme_fw_shortcode_get_options( $options ) {
    $options['block_id'] = array(
        'type' => 'hidden',
        'value' =>'element-' . uniqid(),
    );

    return $options;
}
add_action( 'fw_shortcode_get_options', 'merimag_filter_theme_fw_shortcode_get_options', 10, 1 );
/**
 * Generate blocks css used with unyson page builder or wp-editor
 *
 * @return void
 */
add_action('fw_extensions_init', function() {
    $disabled_shortcodes = apply_filters('fw_ext_shortcodes_disable_shortcodes', array());
    if( !class_exists('_FW_Shortcodes_Loader') ) {
    	return;
    }
    $shortcodes    = _FW_Shortcodes_Loader::load(array(
        'disabled_shortcodes' => $disabled_shortcodes
    ));
    foreach( $shortcodes as $shortcode => $shortcode_data ) {
        $shortcode_parse_atts = function($data) use($shortcode) {
            $atts = isset( $data['atts_string'] ) ? shortcode_parse_atts($data['atts_string']) : array();
            if( !is_array($atts) ) {
                return;
            }
            $atts = fw_ext('shortcodes')->get_attr_coder('json')->decode(
                $atts, '', null
            );
            if( !isset(  $atts['block_id'] ) ) {
                return;
            }
            $selector   = '#' . $atts['block_id'];
            $block_css  = isset( $atts ) && is_array( $atts ) ? merimag_get_dynamic_block_style( $atts, $selector ) : merimag_get_dynamic_block_style( 'general_block', $selector );
            $block_css .= merimag_get_shortcode_css_parse( $shortcode, $atts );
            // Dynamic bloc css
            wp_register_style( 'merimag-' . $shortcode . '-css', false );
            wp_enqueue_style( 'merimag-' . $shortcode . '-css' );
                
            wp_add_inline_style( 'merimag-' . $shortcode . '-css', $block_css );
        };
        add_action('fw_ext_shortcodes_enqueue_static:' . $shortcode, $shortcode_parse_atts);
    }
});

/**
 * Display Header content
 *
 * @return void
 */
function merimag_header_content( $header_location = 'header' ) {
	$stacked_icons = merimag_get_db_customizer_option( $header_location . '_stacked_icons');
	$stacked_icons  = $stacked_icons === 'yes' ? true : false;
	$header_search = merimag_get_db_customizer_option( $header_location . '_search', $header_location !== 'header' ? 'icon' : 'hide');
	$stacked_class = $stacked_icons === true ? 'merimag-stacked-icon' : 'merimag-header-icon';
	$class 		   = $stacked_icons === true ? 'merimag-spaced-flex-small merimag-header-tools-stacked-icons' : '';
	if( $header_search === 'form' ) {
		echo '<div class="merimag-header-search ">';
		$atts = array();
		$atts['post_type'] = merimag_get_db_customizer_option($header_location . '_search_post_type');
		$atts['block_id'] = 'header-search-form';
		merimag_get_shortcode_html('search', $atts);
		echo '</div>';
	}
	$header_contact_infos = merimag_get_db_customizer_option($header_location . '_contact_infos', 'no');
	$header_contact_infos_items = merimag_get_db_customizer_option('contact_infos');
	// contact infos
	$atts['icons_style'] = $stacked_icons === true ? 'stacked' : 'stacked';
	$atts['layout'] = 'inline';
	$atts['elements'] = is_array( $header_contact_infos_items ) ? $header_contact_infos_items : array();
	$atts['block_id'] = 'header-contact-infos';
	if( $header_contact_infos === 'yes' && merimag_shortcode_html('contact-infos', $atts) ) {
		echo '<div class="merimag-header-contact-infos">';
		merimag_get_shortcode_html('contact-infos', $atts);
		echo '</div>';
	}
	$header_cart = merimag_get_db_customizer_option($header_location . '_cart', $header_location !== 'header' ? 'yes' : 'no');
	$header_account = merimag_get_db_customizer_option($header_location . '_account');
	$header_social = merimag_get_db_customizer_option( $header_location . '_social');
	if( $header_account === 'yes' || $header_cart === 'yes' || $header_search === 'icon' || $header_social === 'yes' ) {
		echo '<div class="merimag-header-tools vertical-menu ' . esc_attr( $class ) . '">';
		if( $header_social === 'yes' && $header_location !== 'mobile_header' ) {
			echo merimag_get_menu_social( true);
		}
		if( $header_account === 'yes' ) {
			echo '<div class="merimag-header-account menu-item">';
			$color = merimag_get_db_customizer_option('header_account_icon_color', merimag_get_principal_color());
			$text_color = merimag_get_text_color_from_background( $color );
			$style = $stacked_icons === true ? sprintf('background:%s; color:%s;', esc_attr($color), esc_attr($text_color)) : '';
			echo sprintf('<a class="%s merimag-account-link" style="%s" href="%s" title="%s"><i class="icon-user-1"></i></a>', esc_attr($stacked_class), esc_attr($style),  esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ), esc_html__('My account', 'merimag'));
			echo '</div>';
		}
		$sub_menu_side = is_rtl() ? 'left-side-sub-menu' : 'right-side-sub-menu';
		if( $header_cart === 'yes' && class_exists('WooCommerce') ) {
			$cart_id = merimag_uniqid('merimag-sidebar-');
			echo '<div class="merimag-header-cart menu-item menu-item-has-children ' . esc_attr( $sub_menu_side ) . '">';
			if( $header_location !== 'mobile_header' ) {
				echo merimag_get_menu_cart_link( $stacked_icons );
			} else {
				echo merimag_get_menu_cart_link( $stacked_icons, $cart_id );
			}
			$color = merimag_get_db_customizer_option('header_cart_icon_color', merimag_get_principal_color());
			$widget_title_style = merimag_get_block_title_style( 'style-1', 'widget' );
			$cart_class = $header_location !== 'mobile_header' ? 'sub-menu' : 'merimag-sidebar';
			echo '<div  id="' . esc_attr( $cart_id  ) .'" class="merimag-header-cart-content ' . esc_attr( $cart_class ) . '" data-position="right" style="border-top-color:' . esc_attr( $color ) . '" >';
			if( $header_location === 'mobile_header' ) {
				echo sprintf('<div class="block-title-wrapper merimag-widget-title %s"><span class="block-title">%s</span></div>', esc_attr($widget_title_style), esc_html__('Cart', 'merimag') );
			}
			echo '<div class="widget_shopping_cart_content"></div></div>';
			echo '</div>';
		}
		if( $header_search === 'icon' ) {
			
			echo '<div class="merimag-header-search-icon click-event menu-item menu-item-has-children ' . esc_attr( $sub_menu_side ) . '">';
			$color = merimag_get_db_customizer_option('header_search_icon_color', merimag_get_principal_color());
			$text_color = merimag_get_text_color_from_background( $color );
			$style = $stacked_icons === true ? sprintf('background:%s; color:%s;', esc_attr($color), esc_attr($text_color)) : '';
			echo sprintf('<a class="%s merimag-search-link " style="%s"  title="%s"><i class="icon-search-1"></i></a>', esc_attr( $stacked_class), esc_attr($style), esc_html__('Search', 'merimag'));
			echo '<div class="sub-menu merimag-header-search-content"  style="border-top-color:' . esc_attr( $color ) . '">';
			echo get_search_form();
			echo '</div>';
			echo '</div>';
			
		}
		
		echo '</div>';
	}

}
/**
 * Register a custom menu page.
 */
function merimag_add_theme_page() {

	$theme   	 = wp_get_theme();
	$version 	 = $theme->get('Version');
	$update_data = get_option('external_theme_updates-merimag');
	$new_version = isset( $update_data->update->version ) && version_compare( $update_data->update->version, $version ) === 1 ? $update_data->update->version : false;
	$count 		 = $new_version ? 1 : 0;
	add_menu_page(
        __( 'MERIMAG Theme', 'merimag' ),
        $new_version ? __( 'MERIMAG', 'merimag' ) . sprintf('<span class="merimag-theme-update-notification">%s</span>', $count ) : __( 'MERIMAG Theme', 'merimag' ),
        'manage_options',
        'theme-page',
        '',
        get_template_directory_uri() . '/assets/images/menu-icon.png',
        2
    );
    add_submenu_page( 'theme-page' , __('MERIMAG Theme', 'merimag'), __('Welcome', 'merimag'), 'edit_theme_options', 'theme-page', 'merimag_theme_page' );
}
add_action( 'admin_menu', 'merimag_add_theme_page', 1 );
/**
 * Admin Custom menu page.
 */
function merimag_theme_page() {
    include_once(get_template_directory() . '/includes/theme-admin.php');
}
/**
 * Update check.
 */
function merimag_update_check() {

	merimag_check_ajax_referer( 'merimag_options', 'nonce' );

	global $update_checker;

	$update_checker->checkForUpdates();

	$theme   	 = wp_get_theme();
	$version 	 = $theme->get('Version');
	$update_data = get_option('external_theme_updates-merimag');
	$last_check  = isset( $update_data->lastCheck ) ? $update_data->lastCheck : false;
	$new_version = isset( $update_data->update->version ) && version_compare( $update_data->update->version, $version ) === 1 ? $update_data->update->version : false;
	if( !$new_version ) {
		?>
		<div class="notice  notice-success">
			<p><?php echo esc_html__('Latest version installed last check on : ', 'merimag'); ?> <?php echo date('M d Y H:i:s', $last_check); ?> <a class="merimag-check-update" href="#"><?php echo esc_html__('Check for updates', 'merimag'); ?></a></p>
		</div>
		<?php
	} else {
		?>
		<div class="notice  notice-warning">
			<p><?php echo esc_html__('New version available', 'merimag'); ?> <a class="merimag-check-update" href="<?php echo esc_url( admin_url( 'themes.php')) ?>"><?php echo esc_html__('Go to themes page', 'merimag'); ?></a></p>
		</div>
		<?php
	}

	wp_die();
}
add_action( 'wp_ajax_merimag_update_check', 'merimag_update_check' );
/**
 * Theme ad.
 */
function merimag_get_theme_ad() {
	$transient = get_transient('merimag_admin_theme_ad');
	if( isset( $transient['image'] ) ) {
		$data = $transient;
	} else {
		$theme = wp_get_theme();
		$name = $theme->get('Name');
		$url = $theme->get('AuthorURI');
		$response = wp_remote_get( $url . '/ads/'. $name . '.json' );
		$data = wp_remote_retrieve_body( $response );
		$data = json_decode( $data, true );
		set_transient('merimag_admin_theme_ad', $data, 60 * 60 * 24 );
	}
	if( isset( $data['image'] ) ) {
		$url = isset( $data['link'] ) ?  $data['link'] : '';
		$alt = isset( $data['alt'] ) ? $data['alt'] : '';
		echo sprintf('<a href="%s"><img src="%s" alt="%s" /></a>', esc_url( $url ), esc_url( $data['image'] ), esc_attr( $alt ) );
	}
}

/**
 * Comments number.
 */
function merimag_comments_number($output, $number) {
	if ($number == 0) { 
		$output = esc_html__('0 Comment', 'merimag');
	} elseif ($number == 1) {
		 $output = esc_html__('1 Comment', 'merimag');
	} else {
		$output = $number . esc_html__(' Comments', 'merimag');
	}
	return $output;
}
add_filter ('comments_number', 'merimag_comments_number', 10, 2);
/**
 * Nav menu cache.
 */
function merimag_wp_nav_menu( $args = array() ) {
	
	wp_nav_menu($args);
		
}
/**
 * Inline related posts.
 */
function merimag_inline_related_posts($content) {
	 if( function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
	 	return $content;
	 }
	 $post_id 		 	  = get_the_ID();
	 $elementor_page = get_post_meta( $post_id, '_elementor_edit_mode', true );
	if ( $elementor_page ) {
	    return $content;
	}
	 if ( is_singular( 'post' ) ) {

	 	
	 	$categories 		  = wp_get_object_terms( $post_id, 'category', array('fields' => 'ids') );
		$tags 		 		  = wp_get_object_terms( $post_id, 'post_tag', array('fields' => 'ids') );
		$style = merimag_get_db_customizer_option('inline_related_posts_style');
		$settings = merimag_get_db_customizer_option('inline_related_posts_settings');
		$include = merimag_get_db_customizer_option('related_posts_include');
		$atts['title'] 		  = __('More stories', 'merimag');
		if( is_array($include) && !empty($include) ) {
			$atts['include'] = $include;
			$atts['order_by'] = 'post__in';
		} else {
			$atts['category'] 	  = $categories;
			$atts['post_tag'] 	  =	$tags;
			$atts['order_by'] 	  = 'rand';
			$atts['number'] 	  = 4;
			$atts['order'] 		  = 'desc';
			$atts['ignore_general_style'] = 'yes';
			if( $style === 'grid' && isset($settings['grid']) ) {
				$atts['order_by'] 	  = isset( $settings['grid']['order_by']) ? $settings['grid']['order_by'] : $atts['order_by'];
				$atts['order'] 	  = isset( $settings['grid']['order']) ? $settings['grid']['order'] : $atts['order'];
				$atts['number'] 	  = isset( $settings['grid']['number']) ? $settings['grid']['number'] : $atts['number'];
			}
		}
		
		
	    $shortcode_html 	  = '<div class="merimag-inline-related-posts">' . merimag_shortcode_html('simple-posts-list', $atts ) . '</div>';

	    $output = ''; // define variable to avoid PHP warnings

	    $parts = explode("<p", $content);
	    if( isset( $parts[0] ) ) {
	    	unset( $parts[0] );
	    }
	    
	    $count = count($parts); // call count() only once, it's faster
	    $after = merimag_get_db_customizer_option('inline_related_posts', 'automatic');

	    if( $after === 'disable' ) {
	    	return $content;
	    } elseif( $after === 'automatic' && $count > 1 ) {
	    	$after = intval( $count / 2 ) - 1;
	    } elseif( $after === 'automatic' && $count === 1 ) {
	    	$after = 1;
	    }
	    if( is_numeric( $after ) && $after < 100 ) {
	    	for($i=1; $i<=$count; $i++) {
	    		
		    	if( $i === intval( $after ) ) {
		    		$output .= $shortcode_html . '<p'  . $parts[$i];
		    	} else {
		    		$output .= '<p' . $parts[$i];
		    	}
		    }
		    return $output;
	    } else {
	    	 return $content;
	    }
	    
    }
    return $content;
}
add_filter('the_content','merimag_inline_related_posts');

add_filter('recognized_post_templates', function() {
	for( $i = 1; $i <= 18; $i++ ) {
		$templates[] = $i;
	}
	return $templates;
});

/**
 * Home url filter.
 */
function merimag_home_url() {
	$home_url = home_url();
	$home_url = apply_filters('webte_home_url', $home_url);
	return $home_url;
}
/**
 * Hook to load theme CSS files
 * 
 * @return void
 */
function merimag_load_styles(){
	
	wp_enqueue_style( 'merimag-theme-css', get_template_directory_uri() . '/style.css', array(), THEME_VERSION);

	wp_enqueue_style('merimag-dynamic-css', get_template_directory_uri() . '/assets/css/dynamic.css', array(), THEME_VERSION);
	if( is_singular() ) {
		wp_enqueue_style('merimag-share-css', get_template_directory_uri() . '/assets/css/share.css', array(), THEME_VERSION);
	}

	wp_enqueue_style('merimag-header-css', get_template_directory_uri() . '/assets/css/header.css', array(), THEME_VERSION);

	wp_enqueue_style('merimag-icofont', get_template_directory_uri() . '/assets/css/iconfont/icofont.min.css', array(), THEME_VERSION);

	wp_enqueue_style( 'merimag-animate-css', get_template_directory_uri() . '/assets/css/animate.css', array(), THEME_VERSION );

	wp_enqueue_style('merimag-fontello', get_template_directory_uri() . '/assets/css/fontello/css/fontello.css', array(), THEME_VERSION);
	if( class_exists('WooCommerce') && is_product() ) {
		wp_enqueue_style('merimag-slick-css', get_template_directory_uri() . '/assets/js/slick/slick.min.css', array(), THEME_VERSION);
	}
	wp_enqueue_style( 'merimag-font-awesome', get_template_directory_uri() . '/assets/css/fa/css/font-awesome.min.css', array(), THEME_VERSION );


}
add_action('wp_enqueue_scripts', 'merimag_load_styles', 97 );

function _custom_packs_list($current_packs) {
    /**
     * $current_packs is an array of pack names.
     * You should return which one you would like to show in the picker.
     */
    return array('font-awesome');
}

add_filter('fw:option_type:icon-v2:filter_packs', '_custom_packs_list');
/**
 * Load theme scripts
 * 
 * @return void
 */
function merimag_load_scripts() {
	if( class_exists('WooCommerce') && is_product() ) {
		
		wp_enqueue_script('merimag-slick', get_template_directory_uri() . '/assets/js/slick/slick.min.js', array(), THEME_VERSION, true);

	}

    wp_enqueue_script( 'merimag-plugins-js', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), THEME_VERSION, false);

    wp_enqueue_script( 'merimag-toc', get_template_directory_uri() . '/assets/js/jquery.toc.min.js', array('jquery'), true );

	wp_enqueue_script( 'merimag-js', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), THEME_VERSION, true );
	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	global $wp_query;

	$localized_array['ajax'] 	   = admin_url( 'admin-ajax.php' );
	$localized_array['nonce'] 	   = wp_create_nonce( 'merimag_options' );
	$localized_array['query_vars'] = json_encode( $wp_query->query_vars );
	$localized_array['principal_color'] = merimag_get_principal_color();

	wp_localize_script( 'merimag-js', 'merimag_theme', $localized_array );

}
add_action('wp_enqueue_scripts', 'merimag_load_scripts' );
/**
 * Triggered after the opening body tag.
 *
 * @since 5.2.0
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}

add_filter( 'big_image_size_threshold', '__return_false' );

add_action('init', function() {
	set_transient('fw_brz_admin_notice', true);
});
