<?php
/*
Plugin Name: Multi Demo Import
Plugin URI: https://webte.studio
Description: Help you import demos in one click.
Version: 3.0.4
Author: Webte Studio
Author URI: https://webte.studio
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: wpmdm-import
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'WPMDM_Import' ) ) {

	class WPMDM_Import
	{


		function __construct()
		{

			if( is_admin() ) {
				require plugin_dir_path( __FILE__ ) . 'update/plugin-update-checker.php';
				$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
					'https://webte.studio/wp-update-server/?action=get_metadata&slug=multi-demo-master-import',
					__FILE__,
					'multi-demo-master-import'
				);
			}
			# INIT SETTINGS

			if ( ! class_exists( 'wpmdm_import_options_Loader' ) ) {

				add_filter('wpmdm_import_options_theme_mode', '__return_false');
				
				add_filter( 'wpmdm_import_options_meta_boxes', '__return_true' );

				define ( 'WPMDM_IMPORT_OPTIONS_FOLDER' , esc_attr( '/includes/wpmdm-import-options/' ) );

				$this->wpmdm_import_load_file( plugin_dir_path( __FILE__ ).'includes/wpmdm-import-options/options-loader.php' );
			}

			# INIT HOOKS

			$this->wpmdm_import_hooks();

			# LOAD HELPERS

			$this->wpmdm_import_load_file( plugin_dir_path( __FILE__ ).'includes/wpmdm-import-helpers.php');

			$this->wpmdm_import_load_textdomain();
		    			
		}

		 /** 
		 * Load plugin text domain.
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		function wpmdm_import_load_textdomain() {

		  # text domain

		  load_plugin_textdomain( 'wpmdm-import', false, plugin_dir_path( __FILE__ ) . '/languages' );
		

		}

		/** 
		 * Load file.
		 *
		 * @param 	  file   file path to load
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		private function wpmdm_import_load_file( $file ){
	      
	      include_once( $file );
	      
	    }

		/** 
		 * Plugin Hooks.
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		private function wpmdm_import_hooks() {

			add_action(	'admin_enqueue_scripts', array($this,'wpmdm_import_admin_assets') );
			add_action( 'init', array($this,'wpmdm_import_register_options_pages') );
			add_action( 'wp_ajax_wpmdm_import_ajax_import', 'wpmdm_import_ajax_import' );

		}

		/** 
		 * Plugin assests.
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		public function wpmdm_import_admin_assets() {

		    wp_register_style('wpmdm-import-admin', plugins_url( 'assets/css/admin.css', __FILE__ ) , array() , '1.1', 'all');
		    wp_enqueue_style('wpmdm-import-admin');
		    wp_register_script( 'wpmdm-import-scripts', plugins_url( 'assets/js/scripts.js', __FILE__ ));
		    wp_enqueue_script( 'wpmdm-import-scripts', plugins_url( 'assets/js/scripts.js', __FILE__ )  , array('jquery', 'wpmdm-import-scripts'), '1.0', true );
		    /* create localized JS array */
		    $localized_array = array( 
		      'ajax'                  => admin_url( 'admin-ajax.php' ),
		      'nonce'                 => wp_create_nonce( 'wpmdm_import' ),
		    );
		    
		    /* localized script attached to 'wpmdm_import_options' */
		    wp_localize_script( 'wpmdm-import-scripts', 'wpmdm_import', $localized_array );
		}

		/** 
		 * Register Options pages.
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0
		 */
		public function wpmdm_import_register_options_pages() {

			  // Only execute in admin & if wpmdm Options is included

			  if ( is_admin() && function_exists( 'wpmdm_import_options_register_settings' ) ) {

			  	if( function_exists( 'wpmdm_import_settings_array' ) ) {
			  		$wpmdm_import_settings = wpmdm_import_settings_array();
			  	}
	
			  	$wpmdm_import_infos = array();

			    // Register the pages
			    wpmdm_import_options_register_settings( $wpmdm_import_settings, $wpmdm_import_infos );
			  }

		}

	}

	$GLOBALS['wpmdm_import'] = new WPMDM_Import();

}