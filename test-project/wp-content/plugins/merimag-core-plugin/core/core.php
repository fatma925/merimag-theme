<?php
/*
Merimag Core
*/
class MerimagCore
{
	
	function __construct()
	{
		$this->init();
	}
	function init() {

		$this->constants();

		$this->includes();

		$this->hooks();

	}
	function hooks() {
		add_action('wp_enqueue_scripts', array( $this, 'load_scripts'), 99 );
		add_action('wp_enqueue_scripts', array( $this, 'load_styles'), 97 );

		add_action('fw_init', array( $this, 'merimag_fw_custom_option_types'), 9);
		/**
		 * deregister the styles included with YIKES_MAILCHIMP
		 */
		if( ! defined( 'YIKES_MAILCHIMP_EXCLUDE_STYLES' ) ) {
		   define( 'YIKES_MAILCHIMP_EXCLUDE_STYLES', true );
		}
		
	}
	function constants() {

		define( 'MERIMAG_CORE_THEME_MODE', apply_filters('MERIMAG_CORE_THEME_MODE', true ) );
		if( defined('THEME_VERSION') ) {
			define( 'MERIMAG_CORE_VERSION', THEME_VERSION );
		} else {
			define( 'MERIMAG_CORE_VERSION', "1.0.0" );
		}
		

		if ( false == MERIMAG_CORE_THEME_MODE ) {

		    define( 'MERIMAG_CORE_DIR', plugin_dir_path( __FILE__ ) );
		    define( 'MERIMAG_CORE_URL', plugin_dir_url( __FILE__ ) );

		} else {

			define( 'MERIMAG_CORE_FOLDER', '/includes/core');
		    
		    define( 'MERIMAG_CORE_DIR',  get_template_directory() . MERIMAG_CORE_FOLDER  );
		    define( 'MERIMAG_CORE_URL', get_template_directory_uri() . MERIMAG_CORE_FOLDER   );
		}
	}
	function includes() {

		$files = array(
			'thumb',
			'data',
        	'helpers',
        	'options',
        	'pagination',
        	'blocks',
        	'shortcodes',
        	'elementor',
    	);

      	/* require the files */
      	foreach ( $files as $file ) {
        	$this->load_file( MERIMAG_CORE_DIR . "/includes/{$file}.php" );
      	}

	}

	function load_file( $file ) {

		if( file_exists( $file ) ) {
			require_once( $file );
		}

	}

	function load_styles() {
		wp_enqueue_style( 'merimag-core-slick-css', MERIMAG_CORE_URL . '/assets/js/slick/slick.min.css', array(), MERIMAG_CORE_VERSION );
		wp_enqueue_style( 'merimag-core-unite-gallery-css', MERIMAG_CORE_URL . '/assets/js/unitegallery/css/unite-gallery.css', array(), MERIMAG_CORE_VERSION );
		wp_enqueue_style( 'merimag-core-plyr-css', MERIMAG_CORE_URL . '/assets/js/plyr/plyr.css', array(), MERIMAG_CORE_VERSION );
		wp_enqueue_style( 'merimag-core-css', MERIMAG_CORE_URL . '/assets/css/styles.min.css', array(), MERIMAG_CORE_VERSION );
		wp_enqueue_style( 'merimag-core-plugins-css', MERIMAG_CORE_URL . '/assets/css/plugins.css', array(), MERIMAG_CORE_VERSION );
		wp_enqueue_style( 'merimag-font-awesome', MERIMAG_CORE_URL . '/assets/css/fa/css/font-awesome.min.css', array(), MERIMAG_CORE_VERSION );
		wp_enqueue_style('merimag-icofont', MERIMAG_CORE_URL . '/assets/css/iconfont/icofont.min.css', MERIMAG_CORE_VERSION);
	}

	function load_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('merimag-core-unite-gallery-js', MERIMAG_CORE_URL . '/assets/js/unitegallery/unite-gallery.min.js', array(), MERIMAG_CORE_VERSION, false);
		wp_enqueue_script('merimag-core-plyr-js', MERIMAG_CORE_URL . '/assets/js/plyr/plyr.min.js', array(), MERIMAG_CORE_VERSION, true);
		wp_enqueue_script( 'merimag-toc', MERIMAG_CORE_URL . '/assets/js/jquery.toc.min.js', array('jquery'), true );
		
		wp_enqueue_script( 'merimag-core-init-js', MERIMAG_CORE_URL . '/assets/js/init.js', array(), MERIMAG_CORE_VERSION, false);
		wp_enqueue_script('merimag-core-slick-js', MERIMAG_CORE_URL . '/assets/js/slick/slick.min.js', array(), MERIMAG_CORE_VERSION, true);
		wp_enqueue_script( 'merimag-core-plugins-js', MERIMAG_CORE_URL . '/assets/js/plugins.js', array(), MERIMAG_CORE_VERSION, true);
		wp_enqueue_script( 'merimag-core-js', MERIMAG_CORE_URL . '/assets/js/scripts.js', array(), MERIMAG_CORE_VERSION, true);

		global $wp_query;

		$localized_array['ajax'] 	   		= admin_url( 'admin-ajax.php' );
		$localized_array['nonce'] 	   		= wp_create_nonce( 'merimag_options' );
		$localized_array['query_vars'] 		= isset( $wp_query ) ? json_encode( $wp_query->query_vars ) : '';
		$localized_array['plyr_icon_url']  	= MERIMAG_CORE_URL . '/assets/js/plyr/plyr.svg';
	    $localized_array['principal_color'] = function_exists('merimag_get_principal_color') ? merimag_get_principal_color() : '';
	    $localized_array['strings'] 		= array(
	    	'flex_menu_more' => __('More', 'merimag'),
			'flex_menu_title' => __('View more', 'merimag'),
			'flex_menu_menu' => __('Menu', 'merimag'),
			'flex_menu_menu_all' => __('Open / Close menu', 'merimag'),
	    );
	    $plyr['plyr_icon_url'] = MERIMAG_CORE_URL . '/assets/js/plyr/plyr.svg';
	    wp_localize_script( 'merimag-core-js', 'merimag_options', $localized_array );
	    wp_localize_script( 'merimag-core-plyr-js', 'merimag_options', $plyr );
	}

	function merimag_fw_custom_option_types() {

		if( defined('FW') ) {

		    require_once MERIMAG_CORE_DIR . '/includes/option-types/typography-v3/class-fw-option-type-typography-v3.php';
		    require_once MERIMAG_CORE_DIR . '/includes/option-types/color-picker-v2/class-fw-option-type-color-picker-v2.php';
		    require_once MERIMAG_CORE_DIR . '/includes/option-types/gradient-v2/class-fw-option-type-gradient-v2.php';
		    require_once MERIMAG_CORE_DIR . '/includes/option-types/spacing/class-fw-option-type-spacing.php';
		    //require_once MERIMAG_CORE_DIR . '/includes/option-types/number/class-fw-option-type-number.php';
		    require_once MERIMAG_CORE_DIR . '/includes/option-types/wp-link/wp-link.php';

	    }
	}
	
}

$merimag_core = new MerimagCore();