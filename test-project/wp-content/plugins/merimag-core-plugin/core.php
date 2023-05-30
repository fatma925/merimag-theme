<?php
/*
Plugin Name: Merimag Core Plugin
Plugin URI: https://webte.studio
Description: Extend theme functionalities with amazing features.
Version: 1.0.7
Author: Webte Studio
Author URI: https://webte.studio
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: merimag
*/
class MerimagCorePlugin
{
	
	function __construct()
	{
		$this->init();
	}
	function init() {

		$this->constants();

		$this->hooks();

		if( is_admin() ) {

			require MERIMAG_CORE_PLUGIN_DIR . '/update/plugin-update-checker.php';
			$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
				'https://webte.studio/wp-update-server/?action=get_metadata&slug=merimag-core-plugin',
				__FILE__,
				'merimag-core-plugin'
			);

		}

	}
	function hooks() {
		add_action('admin_enqueue_scripts', array( $this, 'load_admin_assets') );
		add_action('elementor/editor/before_enqueue_scripts', array( $this, 'load_admin_assets'));
		add_action('elementor/editor/before_enqueue_scripts', array( $this, 'elementor_enqueue_scripts'));
		
		add_action('after_setup_theme', array($this, 'include_core') );
		add_action('wp_enqueue_scripts', array( $this, 'load_styles'), 97 );
		add_action('fw_init', array( $this, 'load_widgets'));
		add_action('init', array( $this, 'merimag_mega_menus_post_type'));
		add_action('init', array( $this, 'merimag_builder_sections_post_type'));
		add_action('admin_menu', array( $this, 'merimag_submenu_pages') );
		/**
		 * deregister the styles included with YIKES_MAILCHIMP
		 */
		if( ! defined( 'YIKES_MAILCHIMP_EXCLUDE_STYLES' ) ) {
		   define( 'YIKES_MAILCHIMP_EXCLUDE_STYLES', true );
		}
		
	}
	function elementor_enqueue_scripts() {
		do_action('fw_admin_enqueue_scripts:customizer');

	}
	function constants() {

	    define( 'MERIMAG_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	    define( 'MERIMAG_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

	}
	function include_core() {

		if( !defined('MERIMAG_CORE_VERSION') ) {
			add_filter('MERIMAG_CORE_THEME_MODE', '__return_false');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/core/core.php');
		}

	}

	function load_file( $file ) {

		if( file_exists( $file ) ) {
			require_once( $file );
		}

	}

	function load_styles() {

		wp_enqueue_style( 'merimag-widgets.css', MERIMAG_CORE_PLUGIN_URL . '/assets/css/widgets.css', array() );

	}


	function load_admin_assets() {

		wp_enqueue_script( 'merimag-widgets-init-js', MERIMAG_CORE_PLUGIN_URL . '/assets/js/init.js', array( 'jquery' ), '1.0' , true );

		wp_enqueue_script( 'merimag-widgets-admin-js', MERIMAG_CORE_PLUGIN_URL . '/assets/js/widgets-admin.js', array( 'jquery' ), '1.0' , true );

		wp_enqueue_style( 'merimag-widgets-admin-css', MERIMAG_CORE_PLUGIN_URL . '/assets/css/widgets-admin.css', array() );

	}



	function load_widgets() {

		if( defined('FW') ) {
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/simple-posts-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/posts-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/image-posts-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/posts-mix-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/posts-grid-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/products-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/simple-products-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/popular-categories.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/social-icons.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/mailchimp-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/about-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/contact-widget.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/tabbed.php');
			require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/recent-comments.php');
			if( class_exists('WPMDM') ) {
				require_once( MERIMAG_CORE_PLUGIN_DIR . '/widgets/demo-ad.php');
			}
			

		}
	}
	/**
	 * Filter function to add custom post type for mega menus
	 *
	 * @return void
	 */
	function merimag_mega_menus_post_type() {
	    register_post_type( 'mega_menu',
	    	// CPT Options
	        array(
	            'labels' => array(
	                'name' => __( 'Mega menus', 'merimag' ),
	                'singular_name' => __( 'Mega menu', 'merimag' )
	            ),
	            'public' => true,
	            'has_archive' => false,
	            'rewrite' => array('slug' => 'mega_menu'),
	            'show_in_menu' => false,
	        )
	    );
	}
	
	/**
	 * Filter function to add custom builder sections post type
	 *
	 * @return void
	 */
	function merimag_builder_sections_post_type() {
	    register_post_type( 'builder_section',
	    	// CPT Options
	        array(
	            'labels' => array(
	                'name' => __( 'Builder sections', 'merimag' ),
	                'singular_name' => __( 'Builder section', 'merimag' )
	            ),
	            'public' => true,
	            'has_archive' => false,
	            'rewrite' => array('slug' => 'builder_section'),
	            'show_in_menu' => false,
	        )
	    );
	    
	}

	function merimag_submenu_pages() {
		add_submenu_page( 'theme-page', __( 'Mega menus', 'merimag' ), __( 'Mega menus', 'merimag' ),
    		'manage_options', 'edit.php?post_type=mega_menu', NULL );
		add_submenu_page( 'theme-page', __( 'Builder sections', 'merimag' ), __( 'Builder sections', 'merimag' ),
    		'manage_options', 'edit.php?post_type=builder_section', NULL );
	}
	
}

$merimag_core_plugin = new MerimagCorePlugin();