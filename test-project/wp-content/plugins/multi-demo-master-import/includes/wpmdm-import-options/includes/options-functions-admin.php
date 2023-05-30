<?php
/**
 * Functions used only while viewing the admin UI.
 *
 * Limit loading these function only when needed 
 * and not in the front end.
 *
 * @package   WPmdpmOptions
 * @author    Merrasse Mouhcine <merrasse@wpmdm.net>
 * @copyright Copyright (c) 2016, Merrasse Mouhcine
 * @since     1.0
 */


/**
 * Validate the options by type before saving.
 *
 * This function will run on only some of the option types
 * as all of them don't need to be validated, just the
 * ones users are going to input data into; because they
 * can't be trusted.
 *
 * @param     mixed     Setting value
 * @param     string    Setting type
 * @param     string    Setting field ID
 * @param     string    WPML field ID
 * @return    mixed
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_validate_setting' ) ) {

  function wpmdm_import_options_validate_setting( $input, $type, $field_id, $wmpl_id = '' ) {
    
    /* exit early if missing data */
    if ( ! $input || ! $type || ! $field_id )
      return $input;
    
    $input = apply_filters( 'wpmdm_import_options_validate_setting', $input, $type, $field_id );
    
    /* WPML Register and Unregister strings */
    if ( ! empty( $wmpl_id ) ) {
    
      /* Allow filtering on the WPML option types */
      $single_string_types = apply_filters( 'wpmdm_import_options_wpml_option_types', array( 'text', 'textarea', 'textarea-simple' ) );
              
      if ( in_array( $type, $single_string_types ) ) {
      
        if ( ! empty( $input ) ) {
        
          wpmdm_import_options_wpml_register_string( $wmpl_id, $input );
          
        } else {
        
          wpmdm_import_options_wpml_unregister_string( $wmpl_id );
          
        }
      
      }
    
    }
            
    if ( 'background' == $type ) {

      $input['background-color'] = wpmdm_import_options_validate_setting( $input['background-color'], 'colorpicker', $field_id );
      
      $input['background-image'] = wpmdm_import_options_validate_setting( $input['background-image'], 'upload', $field_id );
      
      // Loop over array and check for values
      foreach( (array) $input as $key => $value ) {
        if ( ! empty( $value ) ) {
          $has_value = true;
        }
      }
      
      // No value; set to empty
      if ( ! isset( $has_value ) ) {
        $input = '';
      }
    
    } else if ( 'border' == $type ) {
      
      // Loop over array and set errors or unset key from array.
      foreach( $input as $key => $value ) {
        
        // Validate width
        if ( $key == 'width' && ! empty( $value ) && ! is_numeric( $value ) ) {
          
          $input[$key] = '0';
          
          add_settings_error( 'wpmdm-import-options', 'invalid_border_width', sprintf( esc_html__( 'The %s input field for %s only allows numeric values.', 'wpmdm-import-options' ), '<code>width</code>', '<code>' . $field_id . '</code>' ), 'error' );
          
        }
        
        // Validate color
        if ( $key == 'color' && ! empty( $value ) ) {

          $input[$key] = wpmdm_import_options_validate_setting( $value, 'colorpicker', $field_id );
          
        }
        
        // Unset keys with empty values.
        if ( empty( $value ) && strlen( $value ) == 0 ) {
          unset( $input[$key] );
        }
        
      }
      
      if ( empty( $input ) ) {
        $input = '';
      }
      
    } else if ( 'box-shadow' == $type ) {
      
      // Validate inset
      $input['inset'] = isset( $input['inset'] ) ? 'inset' : '';
      
      // Validate offset-x
      $input['offset-x'] = wpmdm_import_options_validate_setting( $input['offset-x'], 'text', $field_id );
      
      // Validate offset-y
      $input['offset-y'] = wpmdm_import_options_validate_setting( $input['offset-y'], 'text', $field_id );
      
      // Validate blur-radius
      $input['blur-radius'] = wpmdm_import_options_validate_setting( $input['blur-radius'], 'text', $field_id );
      
      // Validate spread-radius
      $input['spread-radius'] = wpmdm_import_options_validate_setting( $input['spread-radius'], 'text', $field_id );
      
      // Validate color
      $input['color'] = wpmdm_import_options_validate_setting( $input['color'], 'colorpicker', $field_id );
      
      // Unset keys with empty values.
      foreach( $input as $key => $value ) {
        if ( empty( $value ) && strlen( $value ) == 0 ) {
          unset( $input[$key] );
        }
      }
      
      // Set empty array to empty string.
      if ( empty( $input ) ) {
        $input = '';
      }
      
    } else if ( 'colorpicker' == $type ) {

      /* return empty & set error */
      if ( 0 === preg_match( '/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $input ) && 0 === preg_match( '/^rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]{1,4})\s*\)/i', $input ) ) {
        
        $input = '';
        
        add_settings_error( 'wpmdm-import-options', 'invalid_hex', sprintf( esc_html__( 'The %s Colorpicker only allows valid hexadecimal or rgba values.', 'wpmdm-import-options' ), '<code>' . $field_id . '</code>' ), 'error' );
      
      }
      
    } else if ( 'colorpicker-opacity' == $type ) {

      // Not allowed
      if ( is_array( $input ) ) {
        $input = '';
      }

      // Validate color
      $input = wpmdm_import_options_validate_setting( $input, 'colorpicker', $field_id );

    } else if ( in_array( $type, array( 'css', 'javascript', 'text', 'textarea', 'textarea-simple' ) ) ) {
      
      if ( ! current_user_can( 'unfiltered_html' ) && wpmdm_import_options_ALLOW_UNFILTERED_HTML == false ) {
      
        $input = wp_kses_post( $input );
        
      }
    
    } else if ( 'dimension' == $type ) {
      
      // Loop over array and set error keys or unset key from array.
      foreach( $input as $key => $value ) {
        if ( ! empty( $value ) && ! is_numeric( $value ) && $key !== 'unit' ) {
          $errors[] = $key;
        }
        if ( empty( $value ) && strlen( $value ) == 0 ) {
          unset( $input[$key] );
        }
      }

      /* return 0 & set error */
      if ( isset( $errors ) ) {
        
        foreach( $errors as $error ) {
          
          $input[$error] = '0';
          
          add_settings_error( 'wpmdm-import-options', 'invalid_dimension_' . $error, sprintf( esc_html__( 'The %s input field for %s only allows numeric values.', 'wpmdm-import-options' ), '<code>' . $error . '</code>', '<code>' . $field_id . '</code>' ), 'error' );
          
        }
        
      }
      
      if ( empty( $input ) ) {
        $input = '';
      }
      
    } else if ( 'google-fonts' == $type ) {
      
      unset($input['%key%']);
      
      // Loop over array and check for values
      if ( is_array( $input ) && ! empty( $input ) ) {
        $input = array_values( $input );
      }

      // No value; set to empty
      if ( empty( $input ) ) {
        $input = '';
      }
    
    } else if ( 'link-color' == $type ) {
      
      // Loop over array and check for values
      if ( is_array( $input ) && ! empty( $input ) ) {
        foreach( $input as $key => $value ) {
          if ( ! empty( $value ) ) {
            $input[$key] = wpmdm_import_options_validate_setting( $input[$key], 'colorpicker', $field_id . '-' . $key );
            $has_value = true;
          }
        }
      }
      
      // No value; set to empty
      if ( ! isset( $has_value ) ) {
        $input = '';
      }
               
    } else if ( 'measurement' == $type ) {
    
      $input[0] = sanitize_text_field( $input[0] );
      
      // No value; set to empty
      if ( empty( $input[0] ) && strlen( $input[0] ) == 0 && empty( $input[1] ) ) {
        $input = '';
      }
      
    } else if ( 'spacing' == $type ) {
      
      // Loop over array and set error keys or unset key from array.
      foreach( $input as $key => $value ) {
        if ( ! empty( $value ) && ! is_numeric( $value ) && $key !== 'unit' ) {
          $errors[] = $key;
        }
        if ( empty( $value ) && strlen( $value ) == 0 ) {
          unset( $input[$key] );
        }
      }

      /* return 0 & set error */
      if ( isset( $errors ) ) {
        
        foreach( $errors as $error ) {
          
          $input[$error] = '0';
          
          add_settings_error( 'wpmdm-import-options', 'invalid_spacing_' . $error, sprintf( esc_html__( 'The %s input field for %s only allows numeric values.', 'wpmdm-import-options' ), '<code>' . $error . '</code>', '<code>' . $field_id . '</code>' ), 'error' );
          
        }
        
      }
      
      if ( empty( $input ) ) {
        $input = '';
      }
      
    } else if ( 'typography' == $type && isset( $input['font-color'] ) ) {
      
      $input['font-color'] = wpmdm_import_options_validate_setting( $input['font-color'], 'colorpicker', $field_id );
      
      // Loop over array and check for values
      foreach( $input as $key => $value ) {
        if ( ! empty( $value ) ) {
          $has_value = true;
        }
      }
      
      // No value; set to empty
      if ( ! isset( $has_value ) ) {
        $input = '';
      }
      
    } else if ( 'upload' == $type ) {

      if( filter_var( $input, FILTER_VALIDATE_INT ) === FALSE ) {
        $input = esc_url_raw( $input );
      }
    
    } else if ( 'gallery' == $type ) {

      $input = trim( $input );
           
    } else if ( 'social-links' == $type ) {
      
      // Loop over array and check for values, plus sanitize the text field
      foreach( (array) $input as $key => $value ) {
        if ( ! empty( $value ) && is_array( $value ) ) {
          foreach( (array) $value as $item_key => $item_value ) {
            if ( ! empty( $item_value ) ) {
              $has_value = true;
              $input[$key][$item_key] = sanitize_text_field( $item_value );
            }
          }
        }
      }
      
      // No value; set to empty
      if ( ! isset( $has_value ) ) {
        $input = '';
      }
    
    }
    
    $input = apply_filters( 'wpmdm_import_options_after_validate_setting', $input, $type, $field_id );
 
    return $input;
    
  }

}

/**
 * Setup the default admin styles
 *
 * @return    void
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_admin_styles' ) ) {

  function wpmdm_import_options_admin_styles() {

    global $wp_styles, $post;
    
    
    /* load WP colorpicker */
    wp_enqueue_style( 'wp-color-picker' );
    
    /* load admin styles */
   
    wp_enqueue_style( 'wpmdm-import-options-admin-css', WPMDM_IMPORT_OPTIONS_URL . 'assets/css/options-admin.css', false, WPMDM_IMPORT_OPTIONS_VERSION );

    wp_register_style('wpmdm-import-options-font-awesome', WPMDM_IMPORT_OPTIONS_URL .'assets/css/fa/css/font-awesome.min.css', false, WPMDM_IMPORT_OPTIONS_VERSION);

     wp_enqueue_style( 'wpmdm-import-options-magnific-popup-css', WPMDM_IMPORT_OPTIONS_URL . 'assets/js/magnific-popup/magnific-popup.css', false, WPMDM_IMPORT_OPTIONS_VERSION );

    wp_enqueue_style('wpmdm-import-options-font-awesome');

    /* load the RTL stylesheet */
    $wp_styles->add_data( 'options-admin-css','rtl', true );
    
    /* Remove styles added by the Easy Digital Downloads plugin */
    if ( isset( $post->post_type ) && $post->post_type == 'post' )
      wp_dequeue_style( 'jquery-ui-css' );
    
  }
  
}

/**
 * Setup the default admin scripts
 *
 * @uses      add_thickbox()          Include Thickbox for file uploads
 * @uses      wp_enqueue_script()     Add WPmdpmOptions scripts
 * @uses      wp_localize_script()    Used to include arbitrary Javascript data
 *
 * @return    void
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_admin_scripts' ) ) {

  function wpmdm_import_options_admin_scripts() {
    
    /* execute scripts before actions */
    do_action( 'wpmdm_import_options_admin_scripts_before' );
    
    if ( function_exists( 'wp_enqueue_media' ) ) {
      /* WP 3.5 Media Uploader */
      wp_enqueue_media();
    } else {
      /* Legacy Thickbox */
      add_thickbox();
    }

    /* load jQuery-ui slider */
    wp_enqueue_script( 'jquery-ui-slider' );

    /* load jQuery-ui datepicker */
    wp_enqueue_script( 'jquery-ui-datepicker' );

    /* load WP colorpicker */
    wp_enqueue_script( 'wp-color-picker' );

    /* magnific popup */
    wp_enqueue_script( 'wpmdm-import-options-magnific-popup-js', WPMDM_IMPORT_OPTIONS_URL.'assets/js/magnific-popup/magnific-popup.js', null, '1.1.3' );

    /* sticky message */
    wp_enqueue_script( 'wpmdm-import-options-sticky', WPMDM_IMPORT_OPTIONS_URL.'assets/js/sticky.js', null, '1.1.3' );
     

    /* load jQuery UI timepicker addon */
    wp_enqueue_script( 'jquery-ui-timepicker', WPMDM_IMPORT_OPTIONS_URL . 'assets/js/vendor/jquery/jquery-ui-timepicker.js', array( 'jquery', 'jquery-ui-slider', 'jquery-ui-datepicker' ), '1.4.3' );

    /* load the post formats */
    if ( WPMDM_IMPORT_OPTIONS_META_BOXES == true && WPMDM_IMPORT_OPTIONS_POST_FORMATS == true ) {
      wp_enqueue_script( 'wpmdm-import-options-postformats', WPMDM_IMPORT_OPTIONS_URL . 'assets/js/options-postformats.js', array( 'jquery' ), '1.0.1' );
    }

    /* load all the required scripts */
    wp_enqueue_script( 'wpmdm-import-options-admin-js', WPMDM_IMPORT_OPTIONS_URL . 'assets/js/options-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-datepicker', 'jquery-ui-timepicker' ), WPMDM_IMPORT_OPTIONS_VERSION );

    /* create localized JS array */
    $localized_array = array( 
      'ajax'                  => admin_url( 'admin-ajax.php' ),
      'nonce'                 => wp_create_nonce( 'wpmdm_import_options' ),
      'upload_text'           => apply_filters( 'wpmdm_import_options_upload_text', esc_html__( 'Send', 'wpmdm-import-options' ) ),
      'remove_media_text'     => esc_html__( 'Remove Media', 'wpmdm-import-options' ),
      'reset_agree'           => esc_html__( 'Are you sure you want to reset back to the defaults?', 'wpmdm-import-options' ),
      'remove_no'             => esc_html__( 'You can\'t remove this! But you can edit the values.', 'wpmdm-import-options' ),
      'remove_agree'          => esc_html__( 'Are you sure you want to remove this?', 'wpmdm-import-options' ),
      'setting_limit'         => esc_html__( 'Sorry, you can\'t have settings three levels deep.', 'wpmdm-import-options' ),
      'delete'                => esc_html__( 'Delete Gallery', 'wpmdm-import-options' ), 
      'edit'                  => esc_html__( 'Edit Gallery', 'wpmdm-import-options' ), 
      'create'                => esc_html__( 'Create Gallery', 'wpmdm-import-options' ), 
      'confirm'               => esc_html__( 'Are you sure you want to delete this Gallery?', 'wpmdm-import-options' ),
      'date_current'          => esc_html__( 'Today', 'wpmdm-import-options' ),
      'date_time_current'     => esc_html__( 'Now', 'wpmdm-import-options' ),
      'date_close'            => esc_html__( 'Close', 'wpmdm-import-options' ),
      'replace'               => esc_html__( 'Featured Image', 'wpmdm-import-options' ),
      'with'                  => esc_html__( 'Image', 'wpmdm-import-options' )
    );
    
    /* localized script attached to 'wpmdm_import_options' */
    wp_localize_script( 'wpmdm-import-options-admin-js', 'wpmdm_import_options', $localized_array );
    
    /* execute scripts after actions */
    do_action( 'wpmdm_import_options_admin_scripts_after' );

  }
  
}

/**
 * Returns the ID of a custom post type by post_title.
 *
 * @uses        get_results()
 *
 * @return      int
 *
 * @access      public
 * @since       2.0
 */
if ( ! function_exists( 'wpmdm_import_options_get_media_post_ID' ) ) {

  function wpmdm_import_options_get_media_post_ID() {
    
    // Option ID
    $option_id = 'wpmdm_import_options_media_post_ID';
    
    // Get the media post ID
    $post_ID = get_option( $option_id, false );
    
    // Add $post_ID to the DB
    if ( $post_ID === false || empty( $post_ID ) ) {
      global $wpdb;
      
      // Get the media post ID
      $post_ID = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE `post_title` = %s AND `post_type` = %s AND `post_status` = %s", 'Media', 'wpmdm-import-options', 'private' ) );
      
      // Add to the DB
      if ( $post_ID !== null )
        update_option( $option_id, $post_ID );

    }
    
    return $post_ID;
    
  }

}

/**
 * Register custom post type & create the media post used to attach images.
 *
 * @uses        get_results()
 *
 * @return      void
 *
 * @access      public
 * @since       2.0
 */
if ( ! function_exists( 'wpmdm_import_options_create_media_post' ) ) {
  
  function wpmdm_import_options_create_media_post() {
    
    $regsiter_post_type = 'register_' . 'post_type';
    $regsiter_post_type( 'wpmdm-import-options', array(
      'labels'              => array( 'name' => esc_html__( 'WPmdpm Options', 'wpmdm-import-options' ) ),
      'public'              => false,
      'show_ui'             => false,
      'capability_type'     => 'post',
      'exclude_from_search' => true,
      'hierarchical'        => false,
      'rewrite'             => false,
      'supports'            => array( 'title', 'editor' ),
      'can_export'          => false,
      'show_in_nav_menus'   => false
    ) );
  
    /* look for custom page */
    $post_id = wpmdm_import_options_get_media_post_ID();
      
    /* no post exists */
    if ( $post_id == 0 ) {
      
      /* create post object */
      $_p = array();
      $_p['post_title']     = 'Media';
      $_p['post_name']      = 'media';
      $_p['post_status']    = 'private';
      $_p['post_type']      = 'wpmdm-import-options';
      $_p['comment_status'] = 'closed';
      $_p['ping_status']    = 'closed';
      
      /* insert the post into the database */
      wp_insert_post( $_p );
      
    }
  
  }

}




 
/**
 * Helper function to load filters for XML mime type.
 *
 * @return    void
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_add_xml_to_upload_filetypes' ) ) {

  function wpmdm_import_options_add_xml_to_upload_filetypes() {
    
    add_filter( 'upload_mimes', 'wpmdm_import_options_upload_mimes' );
    add_filter( 'wp_mime_type_icon', 'wpmdm_import_options_xml_mime_type_icon', 10, 2 );
  
  }

}

/**
 * Filter 'upload_mimes' and add xml. 
 *
 * @param     array     $mimes An array of valid upload mime types
 * @return    array
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_upload_mimes' ) ) {

  function wpmdm_import_options_upload_mimes( $mimes ) {
  
    $mimes['xml'] = 'application/xml';
    
    return $mimes;
    
  }

}

/**
 * Filters 'wp_mime_type_icon' and have xml display as a document.
 *
 * @param     string    $icon The mime icon
 * @param     string    $mime The mime type
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_xml_mime_type_icon' ) ) {

  function wpmdm_import_options_xml_mime_type_icon( $icon, $mime ) {
  
    if ( $mime == 'application/xml' || $mime == 'text/xml' )
      return wp_mime_type_icon( 'document' );
      
    return $icon;
    
  }

}




/**
 * Helper function to display alert messages.
 *
 * @param     array     Page array
 * @return    mixed
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_alert_message' ) ) {

  function wpmdm_import_options_alert_message( $page = array() ) {
    
    if ( empty( $page ) )
      return false;
    
    $before = apply_filters( 'wpmdm_import_options_before_page_messages', '', $page );
    
    if ( $before ) {
      return $before;
    }
    
    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
    $message = isset( $_REQUEST['message'] ) ? $_REQUEST['message'] : '';
    $updated = isset( $_REQUEST['settings-updated'] ) ? $_REQUEST['settings-updated'] : '';
    

    if ( $action == 'reset' ) {
      
      return '<div id="message" class="updated fade below-h2"><p>' . $page['reset_message'] . '</p></div>';
        
    }

    do_action( 'wpmdm_import_options_custom_page_messages', $page );
    
    if ( $updated == 'true' ) {  
       
      return '<div id="message" class="updated fade below-h2"><p>' . $page['updated_message'] . '</p></div>';
        
    } 
    
    return false;
    
  }
  
}

/**
 * Setup the default option types.
 *
 * The returned option types are filterable so you can add your own.
 * This is not a task for a beginner as you'll need to add the function
 * that displays the option to the user and validate the saved data.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_option_types_array' ) ) {

  function wpmdm_import_options_option_types_array() {
  
    return apply_filters( 'wpmdm_import_options_option_types_array', array( 
      'background'                => esc_html__('Background', 'wpmdm-import-options'),
      'border'                    => esc_html__('Border', 'wpmdm-import-options'),
      'box-shadow'                => esc_html__('Box Shadow', 'wpmdm-import-options'),
      'category-checkbox'         => esc_html__('Category Checkbox', 'wpmdm-import-options'),
      'category-select'           => esc_html__('Category Select', 'wpmdm-import-options'),
      'checkbox'                  => esc_html__('Checkbox', 'wpmdm-import-options'),
      'colorpicker'               => esc_html__('Colorpicker', 'wpmdm-import-options'),
      'colorpicker-opacity'       => esc_html__('Colorpicker Opacity', 'wpmdm-import-options'),
      'custom-post-type-checkbox' => esc_html__('Custom Post Type Checkbox', 'wpmdm-import-options'),
      'custom-post-type-select'   => esc_html__('Custom Post Type Select', 'wpmdm-import-options'),
      'date-picker'               => esc_html__('Date Picker', 'wpmdm-import-options'),
      'date-time-picker'          => esc_html__('Date Time Picker', 'wpmdm-import-options'),
      'dimension'                 => esc_html__('Dimension', 'wpmdm-import-options'),
      'gallery'                   => esc_html__('Gallery', 'wpmdm-import-options'),
      'link-color'                => esc_html__('Link Color', 'wpmdm-import-options'),
      'list-item'                 => esc_html__('List Item', 'wpmdm-import-options'),
      'measurement'               => esc_html__('Measurement', 'wpmdm-import-options'),
      'numeric-slider'            => esc_html__('Numeric Slider', 'wpmdm-import-options'),
      'on-off'                    => esc_html__('On/Off', 'wpmdm-import-options'),
      'page-checkbox'             => esc_html__('Page Checkbox', 'wpmdm-import-options'),
      'page-select'               => esc_html__('Page Select', 'wpmdm-import-options'),
      'post-checkbox'             => esc_html__('Post Checkbox', 'wpmdm-import-options'),
      'post-select'               => esc_html__('Post Select', 'wpmdm-import-options'),
      'radio'                     => esc_html__('Radio', 'wpmdm-import-options'),
      'radio-image'               => esc_html__('Radio Image', 'wpmdm-import-options'),
      'select'                    => esc_html__('Select', 'wpmdm-import-options'),
      'sidebar-select'            => esc_html__('Sidebar Select',  'wpmdm-import-options'),
      'social-links'              => esc_html__('Social Links', 'wpmdm-import-options'),
      'spacing'                   => esc_html__('Spacing', 'wpmdm-import-options'),
      'tab'                       => esc_html__('Tab', 'wpmdm-import-options'),
      'tag-checkbox'              => esc_html__('Tag Checkbox', 'wpmdm-import-options'),
      'tag-select'                => esc_html__('Tag Select', 'wpmdm-import-options'),
      'taxonomy-checkbox'         => esc_html__('Taxonomy Checkbox', 'wpmdm-import-options'),
      'taxonomy-select'           => esc_html__('Taxonomy Select', 'wpmdm-import-options'),
      'text'                      => esc_html__('Text', 'wpmdm-import-options'),
      'textarea'                  => esc_html__('Textarea', 'wpmdm-import-options'),
      'textarea-simple'           => esc_html__('Textarea Simple', 'wpmdm-import-options'),
      'textblock'                 => esc_html__('Textblock', 'wpmdm-import-options'),
      'textblock-titled'          => esc_html__('Textblock Titled', 'wpmdm-import-options'),
      'typography'                => esc_html__('Typography', 'wpmdm-import-options'),
      'upload'                    => esc_html__('Upload', 'wpmdm-import-options')
    ) );
    
  }
}

/**
 * Map old option types for rebuilding XML and Table data.
 *
 * @param     string      $type The old option type
 * @return    string      The new option type
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_map_old_option_types' ) ) {

  function wpmdm_import_options_map_old_option_types( $type = '' ) {
    
    if ( ! $type ) 
      return 'text';
      
    $types = array(
      'background'        => 'background',
      'category'          => 'category-select',
      'categories'        => 'category-checkbox',
      'checkbox'          => 'checkbox',
      'colorpicker'       => 'colorpicker',
      'css'               => 'css',
      'custom_post'       => 'custom-post-type-select',
      'custom_posts'      => 'custom-post-type-checkbox',                     
      'input'             => 'text',
      'image'             => 'upload',
      'measurement'       => 'measurement',
      'page'              => 'page-select',
      'pages'             => 'page-checkbox',
      'post'              => 'post-select',
      'posts'             => 'post-checkbox',
      'radio'             => 'radio',
      'select'            => 'select',
      'slider'            => 'slider',
      'tag'               => 'tag-select',
      'tags'              => 'tag-checkbox',
      'textarea'          => 'textarea',
      'textblock'         => 'textblock',
      'typography'        => 'typography',
      'upload'            => 'upload'
    );
    
    if ( isset( $types[$type] ) )
      return $types[$type];
    
    return false;
    
  }
}


/**
 * Recognized font families
 *
 * Returns an array of all recognized font families.
 * Keys are intended to be stored in the database
 * while values are ready for display in html.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
function wpmdm_import_options_recognized_font_families( ) {

    $field_id=$_POST['field_id'];

    $font_family=$_POST['font_family'];

    $families = array(
      'arial'     => 'Arial',
      'georgia'   => 'Georgia',
      'helvetica' => 'Helvetica',
      'palatino'  => 'Palatino',
      'tahoma'    => 'Tahoma',
      'times'     => '"Times New Roman", sans-serif',
      'trebuchet' => 'Trebuchet',
      'verdana'   => 'Verdana'
    );

    foreach ( $families as $key => $value ) {
        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_family, $key, false ) . '>' . esc_attr( $value ) . '</option>';
    }
    
}


/**
 * Recognized font sizes
 *
 * Returns an array of all recognized font sizes.
 *
 * @uses      apply_filters()
 *
 * @param     string  $field_id ID that's passed to the filters.
 * @return    array
 *
 * @access    public
 * @since     1.0.12
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_font_sizes' ) ) {

  function wpmdm_import_options_recognized_font_sizes( $field_id ) {
  
    $range = wpmdm_import_options_range( 
      apply_filters( 'wpmdm_import_options_font_size_low_range', 0, $field_id ), 
      apply_filters( 'wpmdm_import_options_font_size_high_range', 150, $field_id ), 
      apply_filters( 'wpmdm_import_options_font_size_range_interval', 1, $field_id )
    );
    
    $unit = apply_filters( 'wpmdm_import_options_font_size_unit_type', 'px', $field_id );
    
    foreach( $range as $k => $v ) {
      $range[$k] = $v . $unit;
    }
    
    return apply_filters( 'wpmdm_import_options_recognized_font_sizes', $range, $field_id );
  }

}

/**
 * Recognized font styles
 *
 * Returns an array of all recognized font styles.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_font_styles' ) ) {

  function wpmdm_import_options_recognized_font_styles( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_font_styles', array(
      'normal'  => 'Normal',
      'italic'  => 'Italic',
      'oblique' => 'Oblique',
      'inherit' => 'Inherit'
    ), $field_id );
    
  }

}

/**
 * Recognized font variants
 *
 * Returns an array of all recognized font variants.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_font_variants' ) ) {

  function wpmdm_import_options_recognized_font_variants( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_font_variants', array(
      'normal'      => 'Normal',
      'small-caps'  => 'Small Caps',
      'inherit'     => 'Inherit'
    ), $field_id );
  
  }
  
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
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_font_weights' ) ) {

  function wpmdm_import_options_recognized_font_weights( $field_id = '' ) {
    
    return apply_filters( 'wpmdm_import_options_recognized_font_weights', array(
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
    ), $field_id );
  
  }
  
}

/**
 * Recognized letter spacing
 *
 * Returns an array of all recognized line heights.
 *
 * @uses      apply_filters()
 *
 * @param     string  $field_id ID that's passed to the filters.
 * @return    array
 *
 * @access    public
 * @since     1.0.12
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_letter_spacing' ) ) {

  function wpmdm_import_options_recognized_letter_spacing( $field_id ) {
  
    $range = wpmdm_import_options_range( 
      apply_filters( 'wpmdm_import_options_letter_spacing_low_range', -0.1, $field_id ), 
      apply_filters( 'wpmdm_import_options_letter_spacing_high_range', 0.1, $field_id ), 
      apply_filters( 'wpmdm_import_options_letter_spacing_range_interval', 0.01, $field_id )
    );
    
    $unit = apply_filters( 'wpmdm_import_options_letter_spacing_unit_type', 'em', $field_id );
    
    foreach( $range as $k => $v ) {
      $range[$k] = $v . $unit;
    }
    
    return apply_filters( 'wpmdm_import_options_recognized_letter_spacing', $range, $field_id );
  }

}

/**
 * Recognized line heights
 *
 * Returns an array of all recognized line heights.
 *
 * @uses      apply_filters()
 *
 * @param     string  $field_id ID that's passed to the filters.
 * @return    array
 *
 * @access    public
 * @since     1.0.12
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_line_heights' ) ) {

  function wpmdm_import_options_recognized_line_heights( $field_id ) {
  
    $range = wpmdm_import_options_range( 
      apply_filters( 'wpmdm_import_options_line_height_low_range', 0, $field_id ), 
      apply_filters( 'wpmdm_import_options_line_height_high_range', 150, $field_id ), 
      apply_filters( 'wpmdm_import_options_line_height_range_interval', 1, $field_id )
    );
    
    $unit = apply_filters( 'wpmdm_import_options_line_height_unit_type', 'px', $field_id );
    
    foreach( $range as $k => $v ) {
      $range[$k] = $v . $unit;
    }
    
    return apply_filters( 'wpmdm_import_options_recognized_line_heights', $range, $field_id );
  }

}

/**
 * Recognized text decorations
 *
 * Returns an array of all recognized text decorations.
 * Keys are intended to be stored in the database
 * while values are ready for display in html.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.10
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_text_decorations' ) ) {
  
  function wpmdm_import_options_recognized_text_decorations( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_text_decorations', array(
      'blink'         => 'Blink',
      'inherit'       => 'Inherit',
      'line-through'  => 'Line Through',
      'none'          => 'None',
      'overline'      => 'Overline',
      'underline'     => 'Underline'
    ), $field_id );
    
  }

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
 * @access    public
 * @since     1.0.10
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_text_transformations' ) ) {
  
  function wpmdm_import_options_recognized_text_transformations( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_text_transformations', array(
      'capitalize'  => 'Capitalize',
      'inherit'     => 'Inherit',
      'lowercase'   => 'Lowercase',
      'none'        => 'None',
      'uppercase'   => 'Uppercase'
    ), $field_id );
    
  }

}

/**
 * Recognized background repeat
 *
 * Returns an array of all recognized background repeat values.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_background_repeat' ) ) {
  
  function wpmdm_import_options_recognized_background_repeat( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_background_repeat', array(
      'no-repeat' => 'No Repeat',
      'repeat'    => 'Repeat All',
      'repeat-x'  => 'Repeat Horizontally',
      'repeat-y'  => 'Repeat Vertically',
      'inherit'   => 'Inherit'
    ), $field_id );
    
  }
  
}

/**
 * Recognized background attachment
 *
 * Returns an array of all recognized background attachment values.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_background_attachment' ) ) {

  function wpmdm_import_options_recognized_background_attachment( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_background_attachment', array(
      "fixed"   => "Fixed",
      "scroll"  => "Scroll",
      "inherit" => "Inherit"
    ), $field_id );
    
  }

}

/**
 * Recognized background position
 *
 * Returns an array of all recognized background position values.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_background_position' ) ) {

  function wpmdm_import_options_recognized_background_position( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_recognized_background_position', array(
      "left top"      => "Left Top",
      "left center"   => "Left Center",
      "left bottom"   => "Left Bottom",
      "center top"    => "Center Top",
      "center center" => "Center Center",
      "center bottom" => "Center Bottom",
      "right top"     => "Right Top",
      "right center"  => "Right Center",
      "right bottom"  => "Right Bottom"
    ), $field_id );
    
  }

}

/**
 * Border Styles
 *
 * Returns an array of all available style types.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_border_style_types' ) ) {

  function wpmdm_import_options_recognized_border_style_types( $field_id = '' ) {

    return apply_filters( 'wpmdm_import_options_recognized_border_style_types', array(
      'hidden' => 'Hidden',
      'dashed' => 'Dashed',
      'solid'  => 'Solid',
      'double' => 'Double',
      'groove' => 'Groove',
      'ridge'  => 'Ridge',
      'inset'  => 'Inset',
      'outset' => 'Outset',
    ), $field_id );

  }

}

/**
 * Border Units
 *
 * Returns an array of all available unit types.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_border_unit_types' ) ) {

  function wpmdm_import_options_recognized_border_unit_types( $field_id = '' ) {

    return apply_filters( 'wpmdm_import_options_recognized_border_unit_types', array(
      'px' => 'px',
      '%'  => '%',
      'em' => 'em',
      'pt' => 'pt'
    ), $field_id );

  }

}

/**
 * Dimension Units
 *
 * Returns an array of all available unit types.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_dimension_unit_types' ) ) {

  function wpmdm_import_options_recognized_dimension_unit_types( $field_id = '' ) {

    return apply_filters( 'wpmdm_import_options_recognized_dimension_unit_types', array(
      'px' => 'px',
      '%'  => '%',
      'em' => 'em',
      'pt' => 'pt'
    ), $field_id );

  }

}

/**
 * Spacing Units
 *
 * Returns an array of all available unit types.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_recognized_spacing_unit_types' ) ) {

  function wpmdm_import_options_recognized_spacing_unit_types( $field_id = '' ) {

    return apply_filters( 'wpmdm_import_options_recognized_spacing_unit_types', array(
      'px' => 'px',
      '%'  => '%',
      'em' => 'em',
      'pt' => 'pt'
    ), $field_id );

  }

}



/**
 * Measurement Units
 *
 * Returns an array of all available unit types.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'wpmdm_import_options_measurement_unit_types' ) ) {
  
  function wpmdm_import_options_measurement_unit_types( $field_id = '' ) {
  
    return apply_filters( 'wpmdm_import_options_measurement_unit_types', array(
      'px' => 'px',
      '%'  => '%',
      'em' => 'em',
      'pt' => 'pt'
    ), $field_id );
    
  }

}


/**
 * Default List Item Settings array.
 *
 * Returns an array of the default list item settings.
 * You can filter this function to change the settings
 * on a per option basis.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_list_item_settings' ) ) {

  function wpmdm_import_options_list_item_settings( $id ) {
    
    $settings = apply_filters( 'wpmdm_import_options_list_item_settings', array(
      array(
        'id'        => 'image',
        'label'     => esc_html__( 'Image', 'wpmdm-import-options' ),
        'desc'      => '',
        'std'       => '',
        'type'      => 'upload',
        'rows'      => '',
        'class'     => '',
        'post_type' => '',
        'choices'   => array()
      ),
      array(
        'id'        => 'link',
        'label'     => esc_html__( 'Link', 'wpmdm-import-options' ),
        'desc'      => '',
        'std'       => '',
        'type'      => 'text',
        'rows'      => '',
        'class'     => '',
        'post_type' => '',
        'choices'   => array()
      ),
      array(
        'id'        => 'description',
        'label'     => esc_html__( 'Description', 'wpmdm-import-options' ),
        'desc'      => '',
        'std'       => '',
        'type'      => 'textarea-simple',
        'rows'      => 10,
        'class'     => '',
        'post_type' => '',
        'choices'   => array()
      )
    ), $id );
    
    return $settings;
  
  }

}

/**
 * Default Social Links Settings array.
 *
 * Returns an array of the default social links settings.
 * You can filter this function to change the settings
 * on a per option basis.
 *
 * @uses      apply_filters()
 *
 * @return    array
 *
 * @access    public
 * @since     2.4.0
 */
if ( ! function_exists( 'wpmdm_import_options_social_links_settings' ) ) {

  function wpmdm_import_options_social_links_settings( $id ) {
    
    $settings = apply_filters( 'wpmdm_import_options_social_links_settings', array(
      array(
        'id'        => 'name',
        'label'     => esc_html__( 'Name', 'wpmdm-import-options' ),
        'desc'      => esc_html__( 'Enter the name of the social website.', 'wpmdm-import-options' ),
        'std'       => '',
        'type'      => 'text',
        'class'     => 'wpmdm-import-options-setting-title'
      ),
      array(
        'id'        => 'title',
        'label'     => 'Title',
        'desc'      => esc_html__( 'Enter the text shown in the title attribute of the link.', 'wpmdm-import-options' ),
        'type'      => 'text'
      ),
      array(
        'id'        => 'href',
        'label'     => 'Link',
        'desc'      => sprintf( esc_html__( 'Enter a link to the profile or page on the social website. Remember to add the %s part to the front of the link.', 'wpmdm-import-options' ), '<code>http://</code>' ),
        'type'      => 'text',
      )
    ), $id );
    
    return $settings;
  
  }

}
/**
 * Helper function to display list items.
 *
 * This function is used in AJAX to add a new list items
 * and when they have already been added and saved.
 *
 * @param     string    $name The form field name.
 * @param     int       $key The array key for the current element.
 * @param     array     An array of values for the current list item.
 *
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'wpmdm_import_options_list_item_view' ) ) {

  function wpmdm_import_options_list_item_view( $name, $key, $list_item = array(), $post_id = 0, $get_option = '', $settings = array(), $type = '',  $value = null, $inner = false ) {

    foreach( (array) $settings as $k => $setting ) {

      if( isset( $value[ $setting[ 'id' ] ] ) ) {

        $settings[ $k ][ 'std' ] = $value[ $setting[ 'id' ] ];
      }

    }


    $top_level = isset( $inner ) && $inner == false ? 'wpmdm-import-options-setting-edit-top-level' : '';


    /* if no settings array load the filterable list item settings */
    if ( empty( $settings ) ) {
      
      $settings = wpmdm_import_options_list_item_settings( $name );
      
    }

    /* reference for current list item */

    $data_id =  $name . '-' . $key;

    echo '<div class="wpmdm-import-options-setting">';

     echo  '<div class="open"><span data-id="' . esc_attr( $name . '_title_' . $key ) . '">' . ( isset( $list_item['title'] ) ? esc_attr( $list_item['title'] ) : '' ) . '</span>
      <div class="button-section">
        <a href="javascript:void(0);" data-list-id="' . esc_attr( $name ) .'" data-key="' . esc_attr( $data_id ) . '" class="wpmdm-import-options-setting-edit ' . esc_attr( $top_level ) . ' wpmdm-import-options-ui-button button left-item" title="' . esc_html__( 'Edit', 'wpmdm-import-options' ) . '">
          <span class="fa fa-pencil"></span></a>
        <a href="javascript:void(0);" data-list-id="' . esc_attr( $name ) .'" data-key="' . esc_attr( $data_id ) . '" class="wpmdm-import-options-setting-remove wpmdm-import-options-ui-button button button-secondary light right-item" title="' . esc_html__( 'Delete', 'wpmdm-import-options' ) . '">
          <span class="fa fa-trash-o"></span></a>
      </div></div>
      <div class="wpmdm-import-options-setting-body"  data-list-id="' . esc_attr( $name ) .'" data-key="' . esc_attr( $data_id ) . '">';


      wpmdm_import_options_list_item_view_loop($name, $key, $list_item, $post_id , $get_option, $settings, $type );
      
    echo '</div>';
    /* option body, list-item is not allowed inside another list-item */
          
    
  }
  
}

function wpmdm_import_options_list_item_view_loop($name, $key, $list_item = array(), $post_id = 0, $get_option = '', $settings = array(), $type = '', $inner = false ) {

  foreach( $settings as $field ) {
        
        // Set field value
        $field_value = isset( $list_item[$field['id']] ) ? $list_item[$field['id']] : '';
        
        /* set default to standard value */
        if ( isset( $field['std'] ) ) {  
          $field_value = wpmdm_import_options_filter_std_value( $field_value, $field['std'] );
        }
        
        // filter the title label and description
        if ( $field['id'] == 'title' ) {
          
          // filter the label
          $field['label'] = apply_filters( 'wpmdm_import_options_list_item_title_label', $field['label'], $name );
          
          // filter the description
          $field['desc'] = apply_filters( 'wpmdm_import_options_list_item_title_desc', $field['desc'], $name );
        
        }
        /* make life easier */
        $_field_name = $get_option ? $get_option . '[' . $name . ']' : $name;

        if( $field['type']=='list-item' ) {

          $field_inner = true;

        } else {

          $field_inner = false;

        }
        /* build the arguments array */
        $_args = array(
          'type'              => $field['type'],
          'field_id'          => $name . '_' . $field['id'] . '_' . $key,
          'field_name'        => $_field_name . '[' . $key . '][' . $field['id'] . ']',
          'field_value'       => $field_value,
          'field_inner'       => $field_inner,
          'add_title'         => isset( $field['add_title'] ) ? $field['add_title'] : 'Add New',
          'multiple'          => isset( $field['multiple'] ) ? $field['multiple'] : false,
          'field_desc'        => isset( $field['desc'] ) ? $field['desc'] : '',
          'field_std'         => isset( $field['std'] ) ? $field['std'] : '',
          'field_rows'        => isset( $field['rows'] ) ? $field['rows'] : 10,
          'field_post_type'   => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
          'field_taxonomy'    => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
          'field_min_max_step'=> isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
          'field_class'       => isset( $field['class'] ) ? $field['class'] : '',
          'field_condition'   => isset( $field['condition'] ) ? $field['condition'] : '',
          'field_operator'    => isset( $field['operator'] ) ? $field['operator'] : 'and',
          'field_choices'     => isset( $field['choices'] ) && ! empty( $field['choices'] ) ? $field['choices'] : array(),
          'field_settings'    => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
          'post_id'           => $post_id,
          'get_option'        => $get_option,
          'button_title'      => isset( $field['button_title'] ) && ! empty( $field['button_title']  ) ? $field['button_title']  : '',
          'function'          => isset( $field['function']  ) && ! empty( $field['function']  ) ? $field['function']  : '',
          'update'          => isset( $field['update']  ) && ! empty( $field['update'] ) ? $name . '_' . $field['update'] . '_' . $key  : '',
          'add_action'    =>isset( $field['add_action']  ) && ! empty( $field['add_action']  ) ? $field['add_action']  : '',
          'save_action'    =>isset( $field['save_action'] ) && ! empty( $field['save_action'] ) ? $field['save_action']  : '',
          'disabled'=>isset( $field['disabled'] ) && ! empty( $field['disabled']  ) ? $field['disabled']  : '',
          'empty_message'=>isset( $field['empty_message'] ) && ! empty( $field['empty_message']  ) ? $field['empty_message']  : '',
          'fields' => isset($field['fields']) && is_array($field['fields']) ? $field['fields'] : array(),
        );

        
        $conditions = '';
        
        /* setup the conditions */
        if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {
          
          /* doing magic on the conditions so they work in a list item */
          $conditionals = explode( ',', $field['condition'] );
          foreach( $conditionals as $condition ) {
            $parts = explode( ':', $condition );
            if ( isset( $parts[0] ) ) {
              $field['condition'] = str_replace( $condition, $name . '_' . $parts[0] . '_' . $key . ':' . $parts[1], $field['condition'] );
            }
          }

          $conditions = ' data-condition="' . $field['condition'] . '"';
          $conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';

        }

        // Build the setting CSS class
        if ( ! empty( $_args['field_class'] ) ) {

          $classes = explode( ' ', $_args['field_class'] );

          foreach( $classes as $_key => $value ) {

            $classes[$_key] = $value . '-wrap';

          }

          $class = 'wpmdm-import-options-format-settings ' . implode( ' ', $classes );

        } else {

          $class = 'wpmdm-import-options-format-settings';

        }
          
        /* option label */
        echo '<div id="setting_' . $_args['field_id'] . '" class="' . $class . '"' . $conditions . '>';
          
          /* don't show title with textblocks */
          if (  $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
            echo '<div class="wpmdm-import-options-format-setting-label">';
              echo '<h3 class="label">' . esc_attr( $field['label'] ) . '</h3>';
            echo '</div>';
          }
          
          /* only allow simple textarea inside a list-item due to known DOM issues with wp_editor() */
          if ( apply_filters( 'wpmdm_import_options_override_forced_textarea_simple', false, $field['id'] ) == false && $_args['type'] == 'textarea' ) {

            $_args['type'] = 'textarea-simple';
            $_args['field_class'] = 'wpmdm-import-options-wp-editor';

          }


            
          /* option body, list-item is not allowed inside another list-item */
            echo wpmdm_import_options_display_by_type( $_args );
        
        echo '</div>';
      
      }
}


/**
 * Helper function to display social links.
 *
 * This function is used in AJAX to add a new list items
 * and when they have already been added and saved.
 *
 * @param     string    $name The form field name.
 * @param     int       $key The array key for the current element.
 * @param     array     An array of values for the current list item.
 *
 * @return    void
 *
 * @access    public
 * @since     2.4.0
 */
if ( ! function_exists( 'wpmdm_import_options_social_links_view' ) ) {

  function wpmdm_import_options_social_links_view( $name, $key, $list_item = array(), $post_id = 0, $get_option = '', $settings = array(), $type = '' ) {
    
    /* if no settings array load the filterable social links settings */
    if ( empty( $settings ) ) {
      
      $settings = wpmdm_import_options_social_links_settings( $name );
      
    }
    
    echo '
    <div class="wpmdm-import-options-setting">
      <div class="open">' . ( isset( $list_item['name'] ) ? esc_attr( $list_item['name'] ) : '' ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="wpmdm-import-options-setting-edit wpmdm-import-options-ui-button button left-item" title="' . esc_html__( 'Edit', 'wpmdm-import-options' ) . '">
          <span class="icon wpmdm-import-options-icon-pencil"></span>' . esc_html__( 'Edit', 'wpmdm-import-options' ) . '
        </a>
        <a href="javascript:void(0);" class="wpmdm-import-options-setting-remove wpmdm-import-options-ui-button button button-secondary light right-item" title="' . esc_html__( 'Delete', 'wpmdm-import-options' ) . '">
          <span class="icon wpmdm-import-options-icon-trash-o"></span>' . esc_html__( 'Delete', 'wpmdm-import-options' ) . '
        </a>
      </div>
      <div class="wpmdm-import-options-setting-body">';
        
      foreach( $settings as $field ) {
        
        // Set field value
        $field_value = isset( $list_item[$field['id']] ) ? $list_item[$field['id']] : '';
        
        /* set default to standard value */
        if ( isset( $field['std'] ) ) {  
          $field_value = wpmdm_import_options_filter_std_value( $field_value, $field['std'] );
        }
          
        /* make life easier */
        $_field_name = $get_option ? $get_option . '[' . $name . ']' : $name;
             
        /* build the arguments array */
        $_args = array(
          'type'              => $field['type'],
          'field_id'          => $name . '_' . $field['id'] . '_' . $key,
          'field_name'        => $_field_name . '[' . $key . '][' . $field['id'] . ']',
          'field_value'       => $field_value,
          'field_desc'        => isset( $field['desc'] ) ? $field['desc'] : '',
          'field_std'         => isset( $field['std'] ) ? $field['std'] : '',
          'field_rows'        => isset( $field['rows'] ) ? $field['rows'] : 10,
          'field_post_type'   => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
          'field_taxonomy'    => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
          'field_min_max_step'=> isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
          'field_class'       => isset( $field['class'] ) ? $field['class'] : '',
          'field_condition'   => isset( $field['condition'] ) ? $field['condition'] : '',
          'field_operator'    => isset( $field['operator'] ) ? $field['operator'] : 'and',
          'field_choices'     => isset( $field['choices'] ) && ! empty( $field['choices'] ) ? $field['choices'] : array(),
          'field_settings'    => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
          'post_id'           => $post_id,
          'get_option'        => $get_option
        );
        
        $conditions = '';
        
        /* setup the conditions */
        if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {
          
          /* doing magic on the conditions so they work in a list item */
          $conditionals = explode( ',', $field['condition'] );
          foreach( $conditionals as $condition ) {
            $parts = explode( ':', $condition );
            if ( isset( $parts[0] ) ) {
              $field['condition'] = str_replace( $condition, $name . '_' . $parts[0] . '_' . $key . ':' . $parts[1], $field['condition'] );
            }
          }

          $conditions = ' data-condition="' . $field['condition'] . '"';
          $conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';

        }
          
        /* option label */
        echo '<div id="setting_' . $_args['field_id'] . '" class="wpmdm-import-options-format-settings"' . $conditions . '>';
          
          /* don't show title with textblocks */
          if ( $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
            echo '<div class="wpmdm-import-options-format-setting-label">';
              echo '<h3 class="label">' . esc_attr( $field['label'] ) . '</h3>';
            echo '</div>';
          }
          
          /* only allow simple textarea inside a list-item due to known DOM issues with wp_editor() */
          if ( $_args['type'] == 'textarea' )
            $_args['type'] = 'textarea-simple';
          
          /* option body, list-item is not allowed inside another list-item */
          if ( $_args['type'] !== 'list-item' && $_args['type'] !== 'slider' && $_args['type'] !== 'social-links' ) {
            echo wpmdm_import_options_display_by_type( $_args );
          }
        
        echo '</div>';
      
      }
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Helper function to validate option ID's
 *
 * @param     string      $input The string to sanitize.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_sanitize_option_id' ) ) {

  function wpmdm_import_options_sanitize_option_id( $input ) {
  
    return preg_replace( '/[^a-z0-9]/', '_', trim( strtolower( $input ) ) );
      
  }

}


/**
 * Convert choices array to string
 *
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_convert_array_to_string' ) ) {

  function wpmdm_import_options_convert_array_to_string( $input ) {

    if ( is_array( $input ) ) {

      foreach( $input as $k => $choice ) {
        $choices[$k] = $choice['value'] . '|' . $choice['label'];
        
        if ( isset( $choice['src'] ) )
          $choices[$k].= '|' . $choice['src'];
          
      }
      
      return implode( ',', $choices );
    }
    
    return false;
  }
}

/**
 * Convert choices string to array
 *
 * @return    array
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_convert_string_to_array' ) ) {

  function wpmdm_import_options_convert_string_to_array( $input ) {
    
    if ( '' !== $input ) {
    
      /* empty choices array */
      $choices = array();
      
      /* exlode the string into an array */
      foreach( explode( ',', $input ) as $k => $choice ) {
        
        /* if ":" is splitting the string go deeper */
        if ( preg_match( '/\|/', $choice ) ) {
          $split = explode( '|', $choice );
          $choices[$k]['value'] = trim( $split[0] );
          $choices[$k]['label'] = trim( $split[1] );
          
          /* if radio image there are three values */
          if ( isset( $split[2] ) )
            $choices[$k]['src'] = trim( $split[2] );
            
        } else {
          $choices[$k]['value'] = trim( $choice );
          $choices[$k]['label'] = trim( $choice );
        }
        
      }
      
      /* return a formated choices array */
      return $choices;
    
    }
    
    return false;
    
  }
}

/**
 * Helper function - strpos() with arrays.
 *
 * @param     string    $haystack
 * @param     array     $needles
 * @return    bool
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_strpos_array' ) ) {

  function wpmdm_import_options_strpos_array( $haystack, $needles = array() ) {
  
    foreach( $needles as $needle ) {
      $pos = strpos( $haystack, $needle );
      if ( $pos !== false ) {
        return true;
      }
    }
    
    return false;
  }

}

/**
 * Helper function - strpos() with arrays.
 *
 * @param     string    $haystack
 * @param     array     $needles
 * @return    bool
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_array_keys_exists' ) ) {
  
  function wpmdm_import_options_array_keys_exists( $array, $keys ) {
    
    foreach($keys as $k) {
      if ( isset($array[$k]) ) {
        return true;
      }
    }
    
    return false;
  }
  
}

/**
 * Custom stripslashes from single value or array.
 *
 * @param       mixed   $input
 * @return      mixed
 *
 * @access      public
 * @since       2.0
 */
if ( ! function_exists( 'wpmdm_import_options_stripslashes' ) ) {

  function wpmdm_import_options_stripslashes( $input ) {
  
    if ( is_array( $input ) ) {
    
      foreach( $input as &$val ) {
      
        if ( is_array( $val ) ) {
        
          $val = wpmdm_import_options_stripslashes( $val );
          
        } else {
        
          $val = stripslashes( trim( $val ) );
          
        }
        
      }
      
    } else {
    
      $input = stripslashes( trim( $input ) );
      
    }
    
    return $input;
    
  }

}

/**
 * Reverse wpautop.
 *
 * @param     string    $string The string to be filtered
 * @return    string
 *
 * @access    public
 * @since     1.0.9
 */
if ( ! function_exists( 'wpmdm_import_options_reverse_wpautop' ) ) {

  function wpmdm_import_options_reverse_wpautop( $string = '' ) {
    
    /* return if string is empty */
    if ( trim( $string ) === '' )
      return '';
      
    /* remove all new lines & <p> tags */
    $string = str_replace( array( "\n", "<p>" ), "", $string );
  
    /* replace <br /> with \r */
    $string = str_replace( array( "<br />", "<br>", "<br/>" ), "\r", $string );
  
    /* replace </p> with \r\n */
    $string = str_replace( "</p>", "\r\n", $string );
    
    /* return clean string */
    return trim( $string );
                
  }

}

/**
 * Returns an array of elements from start to limit, inclusive.
 *
 * Occasionally zero will be some impossibly large number to 
 * the "E" power when creating a range from negative to positive.
 * This function attempts to fix that by setting that number back to "0".
 *
 * @param     string    $start First value of the sequence.
 * @param     string    $limit The sequence is ended upon reaching the limit value.
 * @param     string    $step If a step value is given, it will be used as the increment 
 *                      between elements in the sequence. step should be given as a 
 *                      positive number. If not specified, step will default to 1.
 * @return    array
 *
 * @access    public
 * @since     1.0.12
 */
function wpmdm_import_options_range( $start, $limit, $step = 1 ) {
  
  if ( $step < 0 )
    $step = 1;
    
  $range = range( $start, $limit, $step );
  
  foreach( $range as $k => $v ) {
    if ( strpos( $v, 'E' ) ) {
      $range[$k] = 0;
    }
  }
  
  return $range;
}

/**
 * Helper function to return encoded strings
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.13
 */
function wpmdm_import_options_encode( $value ) {

  $func = 'base64' . '_encode';
  return $func( $value );
  
}

/**
 * Helper function to return decoded strings
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.13
 */
function wpmdm_import_options_decode( $value ) {

  $func = 'base64' . '_decode';
  return $func( $value );
  
}

/**
 * Helper function to open a file
 *
 * @access    public
 * @since     1.0.13
 */
function wpmdm_import_options_file_open( $handle, $mode ) {

  $func = 'f' . 'open';
  return @$func( $handle, $mode );
  
}

/**
 * Helper function to close a file
 *
 * @access    public
 * @since     1.0.13
 */
function wpmdm_import_options_file_close( $handle ) {

  $func = 'f' . 'close';
  return $func( $handle );
  
}

/**
 * Helper function to write to an open file
 *
 * @access    public
 * @since     1.0.13
 */
function wpmdm_import_options_file_write( $handle, $string ) {

  $func = 'f' . 'write';
  return $func( $handle, $string );
  
}

/**
 * Helper function to filter standard option values.
 *
 * @param     mixed     $value Saved string or array value
 * @param     mixed     $std Standard string or array value
 * @return    mixed     String or array
 *
 * @access    public
 * @since     1.0.15
 */
function wpmdm_import_options_filter_std_value( $value = '', $std = '' ) {
  
  $std = maybe_unserialize( $std );
  
  if ( is_array( $value ) && is_array( $std ) ) {
  
    foreach( $value as $k => $v ) {
      
      if ( '' == $value[$k] && isset( $std[$k] ) ) {
      
        $value[$k] = $std[$k];
        
      }
      
    }
  
  } else if ( '' == $value && ! empty( $std ) ) {
  
    $value = $std;
    
  }

  return $value;
  
}




/**
 * Helper function to register a WPML string
 *
 * @access    public
 * @since     2.1
 */
function wpmdm_import_options_wpml_register_string( $id, $value ) {

  if ( function_exists( 'icl_register_string' ) ) {
      
    icl_register_string( 'Theme Options', $id, $value );
      
  }
  
}

/**
 * Helper function to unregister a WPML string
 *
 * @access    public
 * @since     2.1
 */
function wpmdm_import_options_wpml_unregister_string( $id ) {

  if ( function_exists( 'icl_unregister_string' ) ) {
      
    icl_unregister_string( 'Theme Options', $id );
      
  }
  
}


/**
 * Returns an array with the post format gallery meta box.
 *
 * @param     mixed     $pages Excepts a comma separated string or array of 
 *                      post_types and is what tells the metabox where to 
 *                      display. Default 'post'.
 * @return    array
 *
 * @access    public
 * @since     2.4.0
 */
function wpmdm_import_options_meta_box_post_format_gallery( $pages = 'post' ) {

  if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'gallery', current( get_theme_support( 'post-formats' ) ) ) )
    return false;
    
  if ( is_string( $pages ) )
    $pages = explode( ',', $pages );
  
  return apply_filters( 'wpmdm_import_options_meta_box_post_format_gallery', array(
    'id'        => 'wpmdm-import-options-post-format-gallery',
    'title'     => esc_html__( 'Gallery', 'wpmdm-import-options' ),
    'desc'      => '',
    'pages'     => $pages,
    'context'   => 'side',
    'priority'  => 'low',
    'fields'    => array(
      array(
        'id'          => '_format_gallery',
        'label'       => '',
        'desc'        => '',
        'std'         => '',
        'type'        => 'gallery',
        'class'       => 'wpmdm-import-options-gallery-shortcode'
      )
  	)
  ), $pages );

}

/**
 * Returns an array with the post format link metabox.
 *
 * @param     mixed     $pages Excepts a comma separated string or array of 
 *                      post_types and is what tells the metabox where to 
 *                      display. Default 'post'.
 * @return    array
 *
 * @access    public
 * @since     2.4.0
 */
function wpmdm_import_options_meta_box_post_format_link( $pages = 'post' ) {
  
  if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'link', current( get_theme_support( 'post-formats' ) ) ) )
    return false;
    
  if ( is_string( $pages ) )
    $pages = explode( ',', $pages );
  
  return apply_filters( 'wpmdm_import_options_meta_box_post_format_link', array(
    'id'        => 'wpmdm-import-options-post-format-link',
    'title'     => esc_html__( 'Link', 'wpmdm-import-options' ),
    'desc'      => '',
    'pages'     => $pages,
    'context'   => 'side',
    'priority'  => 'low',
    'fields'    => array(
      array(
        'id'      => '_format_link_url',
        'label'   => '',
        'desc'    => esc_html__( 'Link URL', 'wpmdm-import-options' ),
        'std'     => '',
        'type'    => 'text'
      ),
      array(
        'id'      => '_format_link_title',
        'label'   => '',
        'desc'    => esc_html__( 'Link Title', 'wpmdm-import-options' ),
        'std'     => '',
        'type'    => 'text'
      )
  	)
  ), $pages );

}

/**
 * Returns an array with the post format quote metabox.
 *
 * @param     mixed     $pages Excepts a comma separated string or array of 
 *                      post_types and is what tells the metabox where to 
 *                      display. Default 'post'.
 * @return    array
 *
 * @access    public
 * @since     2.4.0
 */
function wpmdm_import_options_meta_box_post_format_quote( $pages = 'post' ) {
  
  if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'quote', current( get_theme_support( 'post-formats' ) ) ) )
    return false;
    
  if ( is_string( $pages ) )
    $pages = explode( ',', $pages );

  return apply_filters( 'wpmdm_import_options_meta_box_post_format_quote', array(
    'id'        => 'wpmdm-import-options-post-format-quote',
    'title'     => esc_html__( 'Quote', 'wpmdm-import-options' ),
    'desc'      => '',
    'pages'     => $pages,
    'context'   => 'side',
    'priority'  => 'low',
    'fields'    => array(
      array(
        'id'      => '_format_quote_source_name',
        'label'   => '',
        'desc'    => esc_html__( 'Source Name (ex. author, singer, actor)', 'wpmdm-import-options' ),
        'std'     => '',
        'type'    => 'text'
      ),
      array(
        'id'      => '_format_quote_source_url',
        'label'   => '',
        'desc'    => esc_html__( 'Source URL', 'wpmdm-import-options' ),
        'std'     => '',
        'type'    => 'text'
      ),
      array(
        'id'      => '_format_quote_source_title',
        'label'   => '',
        'desc'    => esc_html__( 'Source Title (ex. book, song, movie)', 'wpmdm-import-options' ),
        'std'     => '',
        'type'    => 'text'
      ),
      array(
        'id'      => '_format_quote_source_date',
        'label'   => '',
        'desc'    => esc_html__( 'Source Date', 'wpmdm-import-options' ),
        'std'     => '',
        'type'    => 'text'
      )
  	)
  ), $pages );

}

/**
 * Returns an array with the post format video metabox.
 *
 * @param     mixed     $pages Excepts a comma separated string or array of 
 *                      post_types and is what tells the metabox where to 
 *                      display. Default 'post'.
 * @return    array
 *
 * @access    public
 * @since     2.4.0
 */
function wpmdm_import_options_meta_box_post_format_video( $pages = 'post' ) {
  
  if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'video', current( get_theme_support( 'post-formats' ) ) ) )
    return false;
    
  if ( is_string( $pages ) )
    $pages = explode( ',', $pages );
  
  return apply_filters( 'wpmdm_import_options_meta_box_post_format_video', array(
    'id'        => 'wpmdm-import-options-post-format-video',
    'title'     => esc_html__( 'Video', 'wpmdm-import-options' ),
    'desc'      => '',
    'pages'     => $pages,
    'context'   => 'side',
    'priority'  => 'low',
    'fields'    => array(
      array(
        'id'      => '_format_video_embed',
        'label'   => '',
        'desc'    => sprintf( esc_html__( 'Embed video from services like Youtube, Vimeo, or Hulu. You can find a list of supported oEmbed sites in the %1$s. Alternatively, you could use the built-in %2$s shortcode.', 'wpmdm-import-options' ), '<a href="http://codex.wordpress.org/Embeds" target="_blank">' . esc_html__( 'Wordpress Codex', 'wpmdm-import-options' ) .'</a>', '<code>[video]</code>' ),
        'std'     => '',
        'type'    => 'textarea'
      )
  	)
  ), $pages );

}

/**
 * Returns an array with the post format audio metabox.
 *
 * @param     mixed     $pages Excepts a comma separated string or array of 
 *                      post_types and is what tells the metabox where to 
 *                      display. Default 'post'.
 * @return    array
 *
 * @access    public
 * @since     2.4.0
 */
function wpmdm_import_options_meta_box_post_format_audio( $pages = 'post' ) {
  
  if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'audio', current( get_theme_support( 'post-formats' ) ) ) )
    return false;
    
  if ( is_string( $pages ) )
    $pages = explode( ',', $pages );
  
  return apply_filters( 'wpmdm_import_options_meta_box_post_format_audio', array(
    'id'        => 'wpmdm-import-options-post-format-audio',
    'title'     => esc_html__( 'Audio', 'wpmdm-import-options' ),
    'desc'      => '',
    'pages'     => $pages,
    'context'   => 'side',
    'priority'  => 'low',
    'fields'    => array(
      array(
        'id'      => '_format_audio_embed',
        'label'   => '',
        'desc'    => sprintf( esc_html__( 'Embed audio from services like SoundCloud and Rdio. You can find a list of supported oEmbed sites in the %1$s. Alternatively, you could use the built-in %2$s shortcode.', 'wpmdm-import-options' ), '<a href="http://codex.wordpress.org/Embeds" target="_blank">' . esc_html__( 'Wordpress Codex', 'wpmdm-import-options' ) .'</a>', '<code>[audio]</code>' ),
        'std'     => '',
        'type'    => 'textarea'
      )
  	)
  ), $pages );

}


/**
 * Build an array of potential Meta Box options that could share terms
 *
 * @return    array
 *
 * @access    private
 * @since     2.5.4
 */
function _wpmdm_import_options_meta_box_potential_shared_terms() {

  global $wpmdm_import_options_meta_boxes;

  $options      = array();
  $settings     = $wpmdm_import_options_meta_boxes;
  $option_types = array( 
    'category-checkbox',
    'category-select',
    'tag-checkbox',
    'tag-select',
    'taxonomy-checkbox',
    'taxonomy-select'
  );

  foreach( $settings as $setting ) {

    if ( isset( $setting['fields'] ) ) {

      foreach( $setting['fields'] as $value ) {

        if ( isset( $value['type'] ) ) {

          if ( $value['type'] == 'list-item' && isset( $value['settings'] ) ) {

            $children = array();

            foreach( $value['settings'] as $item ) {

              if ( isset( $value['id'] ) && isset( $item['type'] ) && in_array( $item['type'], $option_types ) ) {

                $children[$value['id']][] = $item['id'];

              }

            }
            
            if ( ! empty( $children[$value['id']] ) ) {
              $options[] = array( 
                'id'       => $value['id'],
                'children' => $children[$value['id']],
                'taxonomy' => $value['taxonomy'],
              );
            }

          }

          if ( in_array( $value['type'], $option_types ) ) {

            $options[] = array( 
              'id'       => $value['id'],
              'taxonomy' => $value['taxonomy'],
            );

          }

        }

      }

    }

  }

  return $options;

}
/**
 * Get List of available font awesome icons.
 *
 * @param     int     $encoded bool.
 * @return    void
 *
 * @access    public
 * @since     2.5.4
 */
function wpmdm_import_options_font_awesome_list($encoded=false) {

  $pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"\\\\(.+)";\s+}/';
  $subject = wp_remote_retrieve_body(wp_remote_get(WPMDM_IMPORT_OPTIONS_URL.'assets/css/fa/css/font-awesome.css'));

  preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

  $icons = array();

  foreach($matches as $match){
      $icons[] = array('label'=>$match[1], 'value'=>$match[1]);
      //$icons[$match[1]] = $match[2];
  }

  if($encoded==true) {
    $icons=serialize($icons); 
    $icons=htmlentities($icons);
  }

  return $icons;
}
/**
 * Messages To data string.
 *
 * @param     array() messages.
 * @return    void
 *
 * @access    public
 * @since     2.5.4
 */
function wpmdm_import_messages_to_data_string( $messages, $add_slug = '' ) {

  $add_slug = isset( $add_slug ) && !empty( $add_slug ) ? $add_slug . '-' : '';

  $messages_string = '';

  foreach( (array) $messages as $key => $value ) {


    $messages[ 'data-message-' . $add_slug . $key ] = $value;

    unset( $messages[ $key ] );

  }

  foreach ((array) $messages as $key => $value) {

    $messages_string .= $key . ' ="' . $value . '" ';

  }

  return $messages_string;
}
/**
 * Directory Folder View.
 *
 * @param     int     $directory_path.
 * @param     int     $directory_url.
 * @param     int     $files_type.
 * @return    void
 *
 * @access    public
 * @since     2.5.4
 */
function wpmdm_import_options_folder_view( $directory_path, $directory_url, $files_type ) {

      if( !isset( $directory_path, $directory_url, $files_type ) ) {

          return;
      }

      $dir = isset( $directory_path ) ? $directory_path : '';

      $url = isset( $directory_url ) ? $directory_url : '';

      $files_type = isset( $files_type ) ? '.' . $files_type : '';

      $files = array();

      if ( $handle = opendir( $dir ) ) {

          while ( false !== ($file = readdir( $handle ) ) ) {

              if ( $file != "." && $file != ".." && strpos( $file, $files_type) !== false ) {

                  $files[] = array( 'name' => $file, 'date' => date( 'F d Y, H:i:s', filemtime( $directory_path.'/'.$file ) ), 'url' => $directory_url.'/'.$file );
              }

          }

          closedir( $handle );
      }

      if( empty( $files ) ) {

        echo '<div class="list-item-empty wpmdm-import-options-infos-message">';

        echo esc_html__('The folder does not contain any files!', 'wpmdm-import-options');

        echo '</div>';
      }

      echo '<table>';


      foreach ( (array) $files as $file ) {


        echo '<tr class="wpmdm-import-options-directory-files-item">';

        if( isset( $file['name'] ) ) {

          echo '<td class="wpmdm-import-options-directory-files-item-name">';

           echo esc_attr( $file['name'] );

          echo '</td>';

        }

        if( isset( $file['date'] ) ) {

          echo '<td class="wpmdm-import-options-directory-files-item-date">';

           echo esc_attr( $file['date'] );

          echo '</td>';

        }

        if( isset( $file['url'] ) ) {

          echo '<td class="wpmdm-import-options-directory-files-item-download">';

           echo '<a href="' . esc_attr( $file['url'] ) . '"><i class="fa fa-download" title="' . esc_html__('Download', 'wpmdm-import-options') . '"></i></a>';

          echo '</td>';

        }

          echo '<td class="wpmdm-import-options-directory-files-item-delete">';

             echo '<a href="' . esc_attr( $file['url'] ) . '"><i class="fa fa-times" title="' . esc_html__('delete', 'wpmdm-import-options') . '"></i></a>';

          echo '</td>';

        echo '</tr>';
        

      }

      echo '</table>';

}

/**
 * Helper function for calling wp_editor via js
 *
 * @return    array
 *
 * @access    private
 * @since     2.5.4
 */

function wpmdm_import_options_js_wp_editor( $settings = array() ) {
  if ( ! class_exists( '_WP_Editors' ) )
    require( ABSPATH . WPINC . '/class-wp-editor.php' );
  $set = _WP_Editors::parse_settings( 'apid', $settings );

  if ( !current_user_can( 'upload_files' ) )
    $set['media_buttons'] = false;

  if ( $set['media_buttons'] ) {
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script('media-upload');

    $post = get_post();
    if ( ! $post && ! empty( $GLOBALS['post_ID'] ) )
      $post = $GLOBALS['post_ID'];

    wp_enqueue_media( array(
      'post' => $post
    ) );
  }

  _WP_Editors::editor_settings( 'apid', $set );

  $ap_vars = array(
    'url' => get_home_url(),
    'includes_url' => includes_url()
  );

  wp_register_script( 'ap_wpeditor_init', WPMDM_IMPORT_OPTIONS_URL . 'assets/js/js-wp-editor.min.js', array( 'jquery' ), '1.1', true );
  wp_localize_script( 'ap_wpeditor_init', 'ap_vars', $ap_vars );
  wp_enqueue_script( 'ap_wpeditor_init' );
}

/* End of file wpmdm-import-options-functions-admin.php */
/* Location: ./includes/wpmdm-import-options-functions-admin.php */