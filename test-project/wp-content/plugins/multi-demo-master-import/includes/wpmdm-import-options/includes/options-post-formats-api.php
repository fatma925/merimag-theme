<?php if ( ! defined( 'WPMDM_IMPORT_OPTIONS_VERSION') ) exit( 'No direct script access allowed' );
/**
 * OptionTree Post Formats API
 *
 * This class loads all the methods and helpers specific to build a the post format metaboxes.
 *
 * @package   OptionTree
 * @author    Derek Herman <derek@valendesigns.com>
 * @copyright Copyright (c) 2014, Derek Herman
 */
if ( ! class_exists( 'wpmdm_import_options_post_formats' ) ) {

  class wpmdm_import_options_post_formats {
    
    /**
     * Class Constructor
     *
     * @return    void
     *
     * @access    public
     * @since     2.3.0
     */
    public function __construct() {
    
      $this->setup_actions();
      
    }
    
    /**
     * Setup the default filters and actions
     *
     * @uses add_action() To add various actions
     * @uses add_filter() To add various filters
     *
     * @return  void
     *
     * @access  private
     * @since   2.3.0
     */
    private function setup_actions() {

      // Initialize the meta boxes
      add_action( 'admin_init', array( $this, 'meta_boxes'          ), 2 );

      // Setup pings for the link & quwpmdm_import_optionse URLs
      add_filter( 'pre_ping',   array( $this, 'pre_ping_post_links' ), 10, 3 );

    }
  
    /**
     * Builds the default Meta Boxes.
     *
     * @return    void
     *
     * @access    private
     * @since     2.3.0
     */
    public function meta_boxes() {

      // Exit if called outside of WP admin
      if ( ! is_admin() )
        return false;

      /**
       * Filter the post formats meta boxes.
       *
       * @since 2.6.0
       *
       * @param array $meta_boxes The meta boxes being registered.
       * @return array
       */
      $meta_boxes = apply_filters( 'wpmdm_import_options_recognized_post_format_meta_boxes', array(
        wpmdm_import_options_meta_box_post_format_gallery(),
        wpmdm_import_options_meta_box_post_format_link(),
        wpmdm_import_options_meta_box_post_format_quote(),
        wpmdm_import_options_meta_box_post_format_video(),
        wpmdm_import_options_meta_box_post_format_audio(),
      ) );

      /**
       * Register our meta boxes using the 
       * wpmdm_import_options_register_meta_box() function.
       */
      foreach( $meta_boxes as $meta_box ) {

        wpmdm_import_options_register_meta_box( $meta_box );

      }

    }
    
    /**
     * Setup pings for the link & quwpmdm_import_optionse URLs
     * 
     * @param     array     $post_links The URLs to ping
     * @param     array     $pung Pinged URLs
     * @param     int       $post_id Post ID
     * @return    array
     *
     * @access    public
     * @since     2.3.0
     */
    public function pre_ping_post_links( $post_links, $pung, $post_id = null ) {
      
      $_link = get_post_meta( $post_id, '_format_link_url', true );
      if ( ! empty( $_link ) && ! in_array( $_link, $pung ) && ! in_array( $_link, $post_links ) )
        $post_links[] = $_link;
      
      $_quwpmdm_import_optionse = get_post_meta( $post_id, '_format_quwpmdm_import_optionse_source_url', true );
      if ( ! empty( $_quwpmdm_import_optionse ) && ! in_array( $_quwpmdm_import_optionse, $pung ) && ! in_array( $_quwpmdm_import_optionse, $post_links ) )
        $post_links[] = $_quwpmdm_import_optionse;
      
    }

  }

}

/**
 * Instantiate The Class
 *
 * @since 1.0
 */
if ( function_exists( 'wpmdm_import_options_register_meta_box' ) ) {

  new wpmdm_import_options_post_formats();

}

/* End of file wpmdm_import_options-post-formats-api.php */
/* Location: ./includes/wpmdm_import_options-post-formats-api.php */