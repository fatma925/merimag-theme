<?php
/**
 * Plugin Name: WPmdmImportOptions
 * Plugin URI:  https://wpmdm.net/plugins/wpmdm-import-options/
 * Description: Theme Options Framework
 * Version:     1.0.0
 * Author:      Merrasse Mouhcine
 * Author URI:  http://wpmdm.net
 * License:     GPLv3
 * Text Domain: wpmdm-import-options
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * This is the WPmdmImportOptions loader class.
 *
 * @package   WPmdmImportOptions
 * @author    Merrasse Mouhcine <merrasse@wpmdm.net>
 * @copyright Copyright (c) 2016, Merrasse Mouhcine
 */
if ( ! class_exists( 'wpmdm_import_options_Loader' ) ) {

  class wpmdm_import_options_Loader {
    
    /**
     * PHP5 constructor method.
     *
     * This method loads wpmdm_import_optionsher methods of the class.
     *
     * @return    void
     *
     * @access    public
     * @since     1.0
     */
    public function __construct() {
      
      
      /* load WPmdmImportOptions */
      add_action( 'after_setup_theme', array( $this, 'load_wpmdm_import_options' ), 1 );



    }

    
    /** 
     * Load WPmdmImportOptions on the 'after_setup_theme' action. Then filters will 
     * be availble to the theme, and not only when in Theme Mode.
     *
     * @return    void
     *
     * @access    public
     * @since     2.1.2
     */
    public function load_wpmdm_import_options() {
    
      /* setup the constants */
      $this->constants();
      
      /* include the required admin files */
      $this->admin_includes();
      
      /* include the required files */
      $this->includes();
      
      /* hook into WordPress */
      $this->hooks();


      
    }

    /**
     * Constants
     *
     * Defines the constants for use within WPmdmImportOptions. Constants 
     * are prefixed with 'wpmdm_import_options_' to avoid any naming collisions.
     *
     * @return    void
     *
     * @access    private
     * @since     1.0
     */
    private function constants() {
      
      /**
       * Current Version number.
       */
      define( 'WPMDM_IMPORT_OPTIONS_VERSION', '1.0.0' );
      
      /**
       * For developers: Theme mode.
       *
       * Run a filter and set to true to enable WPmdmImportOptions theme mode.
       * You must have this files parent directory inside of 
       * your themes rowpmdm_import_options directory. As well, you must include 
       * a reference to this file in your themes functions.php.
       *
       * @since     1.0
       */
      define( 'WPMDM_IMPORT_OPTIONS_THEME_MODE', apply_filters( 'wpmdm_import_options_theme_mode', false ) );

      /**
       * For developers: Meta Boxes.
       *
       * Run a filter and set to false to keep WPmdmImportOptions from
       * loading the meta box resources.
       *
       * @since     1.0
       */
      define( 'WPMDM_IMPORT_OPTIONS_META_BOXES', apply_filters( 'wpmdm_import_options_meta_boxes', true ) );
      
      /**
       * For developers: Allow Unfiltered HTML in all the textareas.
       *
       * Run a filter and set to true if you want all the
       * users to be able to post anything in the textareas.
       * WARNING: This opens a security hole for low level users
       * to be able to post malicious scripts, you've been warned.
       *
       * @since     1.0
       */
      define( 'WPMDM_IMPORT_OPTIONS_ALLOW_UNFILTERED_HTML', apply_filters( 'wpmdm_import_options_allow_unfiltred_html', false ) );

      /**
       * For developers: Post Formats.
       *
       * Run a filter and set to true if you want WPmdmImportOptions 
       * to load meta boxes for post formats.
       *
       * @since     2.4.0
       */
      define( 'WPMDM_IMPORT_OPTIONS_POST_FORMATS', apply_filters( 'wpmdm_import_options_post_foramts', true ) );
      
      /**
       * Check if in theme mode.
       *
       * If WPMDM_IMPORT_OPTIONS_THEME_MOD is false, set the 
       * directory path & URL like any wpmdm_import_optionsher plugin. wpmdm_import_optionsherwise, use 
       * the parent or child themes rowpmdm_import_options directory. 
       *
       * @since     1.0
       */
      if ( false == WPMDM_IMPORT_OPTIONS_THEME_MODE ) {

          define( 'WPMDM_IMPORT_OPTIONS_DIR', plugin_dir_path( __FILE__ ) );
          define( 'WPMDM_IMPORT_OPTIONS_URL', plugin_dir_url( __FILE__ ) );

      } else {
        
          define( 'WPMDM_IMPORT_OPTIONS_DIR',  get_template_directory() . WPMDM_IMPORT_OPTIONS_FOLDER  );
          define( 'WPMDM_IMPORT_OPTIONS_URL', get_template_directory_uri() . WPMDM_IMPORT_OPTIONS_FOLDER   );
      }

    }
    
    /**
     * Include admin files
     *
     * These functions are included on admin pages only.
     *
     * @return    void
     *
     * @access    private
     * @since     1.0
     */
    private function admin_includes() {
      
      /* exit early if we're not on an admin page */
      if ( ! is_admin() )
        return false;
      
      /* global include files */
      $files = array( 
        'options-functions-admin',
        'options-functions-option-types',
        'options-settings-api',
      );
      
      /* include the meta box api */
      if ( WPMDM_IMPORT_OPTIONS_META_BOXES == true ) {
        $files[] = 'options-meta-box-api';
      }


      /* include the post formats api */
      if ( WPMDM_IMPORT_OPTIONS_META_BOXES == true && WPMDM_IMPORT_OPTIONS_POST_FORMATS == true ) {
        $files[] = 'options-post-formats-api';
      }

      /* require the files */
      foreach ( $files as $file ) {
        $this->load_file( WPMDM_IMPORT_OPTIONS_DIR . "includes" . DIRECTORY_SEPARATOR . "{$file}.php" );
      }
      


      
    }
    
    /**
     * Include front-end files
     *
     * These functions are included on every page load 
     * incase wpmdm_import_optionsher plugins need to access them.
     *
     * @return    void
     *
     * @access    private
     * @since     1.0
     */
    private function includes() {
    
      $files = array( 
        'options-functions',
      );

      /* require the files */
      foreach ( $files as $file ) {
        $this->load_file( WPMDM_IMPORT_OPTIONS_DIR . "includes" . DIRECTORY_SEPARATOR . "{$file}.php" );
      }
      
    }
    
    /**
     * Execute the WordPress Hooks
     *
     * @return    void
     *
     * @access    public
     * @since     1.0
     */
    private function hooks() {

      /* load the Meta Box assets */
      if ( WPMDM_IMPORT_OPTIONS_META_BOXES == true ) {
      
        /* add scripts for metaboxes to post-new.php & post.php */
        add_action( 'admin_print_scripts-post-new.php', 'wpmdm_import_options_admin_scripts', 11 );
        add_action( 'admin_print_scripts-post.php', 'wpmdm_import_options_admin_scripts', 11 );
              
        /* add styles for metaboxes to post-new.php & post.php */
        add_action( 'admin_print_styles-post-new.php', 'wpmdm_import_options_admin_styles', 11 );
        add_action( 'admin_print_styles-post.php', 'wpmdm_import_options_admin_styles', 11 );
      
      }
       
      /* create media post */
      add_action( 'admin_init', 'wpmdm_import_options_create_media_post', 8 );
                                     
      /* AJAX call to create a new list item */
      add_action( 'wp_ajax_wpmdm_import_add_list_item', array( $this, 'wpmdm_import_add_list_item' ) );

      /* AJAX call to create a new list item */
      add_action( 'wp_ajax_wpmdm_import_add_list_item_inner', array( $this, 'wpmdm_import_add_list_item_inner' ) );

      /* AJAX call to create a clear folder */
      add_action( 'wp_ajax_wpmdm_import_options_clear_folder', array( $this, 'wpmdm_import_options_clear_folder' ) );

      /* AJAX call to create a check folder */
      add_action( 'wp_ajax_wpmdm_import_options_check_folder', array( $this, 'wpmdm_import_options_check_folder' ) );

      /* AJAX call to create a refresh folder */
      add_action( 'wp_ajax_wpmdm_import_options_refresh_folder', array( $this, 'wpmdm_import_options_refresh_folder' ) );

      /* AJAX call to update log */
      add_action( 'wp_ajax_wpmdm_import_update_log', array( $this, 'wpmdm_import_update_log' ) );
      
      // Adds the temporary hacktastic shortcode
      add_filter( 'media_view_settings', array( $this, 'shortcode' ), 10, 2 );
    
      // AJAX update
      add_action( 'wp_ajax_gallery_update', array( $this, 'ajax_gallery_update' ) );
      
      /* Modify the media uploader button */
      add_filter( 'gettext', array( $this, 'change_image_button' ), 10, 3 );
      
    }
    
    /**
     * Load a file
     *
     * @return    void
     *
     * @access    private
     * @since     1.0.15
     */
    private function load_file( $file ){
      
      include_once( $file );
      
    }
    

    /**
     * AJAX utility function for adding a new list item.
     */
    public function wpmdm_import_add_list_item() {
      check_ajax_referer( 'wpmdm_import_options', 'nonce' );
      wpmdm_import_options_list_item_view( $_REQUEST['name'], $_REQUEST['count'], array(), $_REQUEST['post_id'], $_REQUEST['get_option'], unserialize( wpmdm_import_options_decode( $_REQUEST['settings'] ) ), $_REQUEST['type'], unserialize( wpmdm_import_options_decode( $_REQUEST['value'] ) )  );
      die();
    }
    
    /**
     * AJAX utility function for adding a new list item.
     */
    public function wpmdm_import_add_list_item_inner() {
      check_ajax_referer( 'wpmdm_import_options', 'nonce' );
      wpmdm_import_options_list_item_view( $_REQUEST['name'], $_REQUEST['count'], array(), $_REQUEST['post_id'], $_REQUEST['get_option'], unserialize( wpmdm_import_options_decode( $_REQUEST['settings'] ) ), $_REQUEST['type'], unserialize( wpmdm_import_options_decode( $_REQUEST['value'] ) ), true );
      die();
    }

    /**
     * AJAX utility function for adding a new social link.
     */
    public function wpmdm_import_add_social_links() {
      check_ajax_referer( 'wpmdm_import_options', 'nonce' );
      wpmdm_import_options_social_links_view( $_REQUEST['name'], $_REQUEST['count'], array(), $_REQUEST['post_id'], $_REQUEST['get_option'], unserialize( wpmdm_import_options_decode( $_REQUEST['settings'] ) ), $_REQUEST['type'] );
      die();
    }

    public function wpmdm_import_options_clear_folder() {
      check_ajax_referer( 'wpmdm_import_options', 'nonce' );

      $dir = wpmdm_import_options_decode( $_REQUEST['folder'] );

      if( is_dir( $dir ) ) {
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ( $ri as $file ) {
            $file->isDir() ?  rmdir($file) : unlink($file);
        }
      }
      
      wp_die();
    }

    public function wpmdm_import_options_check_folder() {
      check_ajax_referer( 'wpmdm_import_options', 'nonce' );

      $dir = wpmdm_import_options_decode( $_REQUEST['folder'] );

      if( is_dir( $dir ) ) {
        echo 1;
      } else {
        echo 0;
      }
      wp_die();
    }


    public function wpmdm_import_options_refresh_folder() {
      check_ajax_referer( 'wpmdm_import_options', 'nonce' );
      $path = $_REQUEST['path'];
      $url = $_REQUEST['url'];
      $type = $_REQUEST['type'];
      if( is_dir( $path ) ) {
        wpmdm_import_options_folder_view($path, $url, $type);
      }
      wp_die();
    }
    
    /**
     * Fake the gallery shortcode
     *
     * The JS takes over and creates the actual shortcode with 
     * the real attachment IDs on the fly. Here we just need to 
     * pass in the post ID to get the ball rolling.
     *
     * @param     array     The current settings
     * @param     object    The post object
     * @return    array
     *
     * @access    public
     * @since     2.2.0
     */
    public function shortcode( $settings, $post ) {
      global $pagenow;

      if ( in_array( $pagenow, array( 'upload.php', 'customize.php' ) ) ) {
        return $settings;
      }

      // Set the WPmdmImportOptions post ID
      if ( ! is_object( $post ) ) {
        $post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_GET['post_ID'] ) ? $_GET['post_ID'] : 0 );
        if ( $post_id == 0 && function_exists( 'wpmdm_import_options_get_media_post_ID' ) ) {
          $post_id = wpmdm_import_options_get_media_post_ID();
        }
        $settings['post']['id'] = $post_id;
      }
      
      // No ID return settings
      if ( $settings['post']['id'] == 0 )
        return $settings;
  
      // Set the fake shortcode
      $settings['wpmdm_import_options_gallery'] = array( 'shortcode' => "[gallery id='{$settings['post']['id']}']" );
      
      // Return settings
      return $settings;
      
    }
    
    /**
     * Returns the AJAX images
     *
     * @return    string
     *
     * @access    public
     * @since     2.2.0
     */
    public function ajax_gallery_update() {
    
      if ( ! empty( $_POST['ids'] ) )  {
        
        $return = '';
        
        foreach( $_POST['ids'] as $id ) {
        
          $thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
          
          $return .= '<li><img  src="' . $thumbnail[0] . '" width="75" height="75" /></li>';
          
        }
        
        echo html_entity_decode(esc_html($return));
        exit();
      
      }
      
    }
    
    /**
     * Filters the media uploader button.
     *
     * @return    string
     *
     * @access    public
     * @since     2.1
     */
    public function change_image_button( $translation, $text, $domain ) {
      global $pagenow;
    
      if ( $pagenow == apply_filters( 'wpmdm_import_options_theme_options_parent_slug', 'themes.php' ) && 'default' == $domain && 'Insert into post' == $text ) {
        
        // Once is enough.
        remove_filter( 'gettext', array( $this, 'wpmdm_import_options_change_image_button' ) );
        return apply_filters( 'wpmdm_import_options_upload_text', esc_html__( 'Send', 'wpmdm-import-options' ) );
        
      }
      
      return $translation;
      
    }
    
    
  }
  
  /**
   * Instantiate the WPmdmImportOptions loader class.
   *
   * @since     1.0
   */
  $wpmdm_import_options_loader = new wpmdm_import_options_Loader();

}

/* End of file wpmdm-import-options-loader.php */
/* Location: ./wpmdm-import-options-loader.php */
