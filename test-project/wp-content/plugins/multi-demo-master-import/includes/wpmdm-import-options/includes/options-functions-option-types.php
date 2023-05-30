<?php if ( ! defined( 'WPMDM_IMPORT_OPTIONS_VERSION' ) ) exit( 'No direct script access allowed' );
/**
 * Functions used to build each option type.
 *
 * @package   WPmdpmOptions
 * @author    Merrasse Mouhcine <merrasse@wpmdm.net>
 * @copyright Copyright (c) 2016, Merrasse Mouhcine
 * @since     1.0
 */

/**
 * Builds the HTML for each of the available option types by calling those
 * function with call_user_func and passing the arguments to the second param.
 *
 * All fields are required!
 *
 * @param     array       $args The array of arguments are as follows:
 * @param     string      $type Type of option.
 * @param     string      $field_id The field ID.
 * @param     string      $field_name The field Name.
 * @param     mixed       $field_value The field value is a string or an array of values.
 * @param     string      $field_desc The field description.
 * @param     string      $field_std The standard value.
 * @param     string      $field_class Extra CSS classes.
 * @param     array       $field_choices The array of option choices.
 * @param     array       $field_settings The array of settings for a list item.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_display_by_type' ) ) {

  function wpmdm_import_options_display_by_type( $args = array() ) {
    /* allow filters to be executed on the array */
    $args = apply_filters( 'wpmdm_import_options_display_by_type', $args );
    /* build the function name */
    $function_name_by_type = str_replace( '-', '_', 'wpmdm_import_options_type_' . $args['type'] );
    
    /* call the function & pass in arguments array */
    if ( function_exists( $function_name_by_type ) ) {
      call_user_func( $function_name_by_type, $args );
    } else {
      echo '<p>' . esc_html__( 'Sorry, this function does not exist', 'wpmdm-import-options' ) . '</p>';
    }
    
  }
  
}
/**
 * Ajax Action.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
/**
 * Ajax Action.
 *
 * See @wpmdm_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_ajax_action' ) ) {
  
  function wpmdm_import_options_type_ajax_action( $args = array() ) {

    $options_args = $args;
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-category-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '" data-id="' .esc_attr( $field_id ) . '">';

        if( isset( $field_choices ) && !empty( $field_choices ) && is_array( $field_choices ) && isset( $options_type ) ) {
          $options_args['type'] = isset( $options_type ) ? $options_type : '';
          $options_args['field_choices'] = $field_choices;
            $options_args['field_name'] = $field_name;
          $options_args['multiple'] = isset( $multiple ) && $multiple === true ? true : false;
          wpmdm_options_display_by_type( $options_args );

          echo '<div class="wpmdm-import-options-spacer"></div>';

          $no_choices = false;

        }


        if( isset( $input ) && $input == 'text' ) {
          /* input */
          echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-input" value="'.esc_attr( $field_value ).'" class="' . esc_attr( $field_id ) . '-input widefat wpmdm-import-options-ui-input" />';

          echo '<div class="wpmdm-import-options-spacer"></div>';

        }
        if( isset( $post_type_select ) && $post_type_select == true ) {
          /* input */
          $field_name_pt = str_replace(']', '-post_type]', $field_name);
          echo '<select type="text" name="' . esc_attr( $field_name_pt ) . '" id="' . esc_attr( $field_id ) . '-post-type" value="'.esc_attr( $field_value ).'" class="' . esc_attr( $field_id ) . '-post-type widefat wpmdm-import-options-ui-input">';
            echo '<option selected value="post">Post</option>';
            echo '<option value="product">Product</option>';
          echo '</select>';

          echo '<div class="wpmdm-import-options-spacer"></div>';

        }
        if( isset( $input ) && $input == 'textarea' ) {
          /* input */
          echo '<textarea rows="10" type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-input" value="'.esc_attr( $field_value ).'" class="' . esc_attr( $field_id ) . '-input widefat wpmdm-import-options-ui-input"></textarea>';

          echo '<div class="wpmdm-import-options-spacer"></div>';

        }
        if( isset( $input ) && $input == 'hidden' ) {
          /* input */
          echo '<input type="hidden" name="wpmdm_current_action" id="' . esc_attr( $field_id ) . '-input" value="'.esc_attr( $field_value ).'" class="' . esc_attr( $field_id ) . '-input widefat wpmdm-import-options-ui-input" />';


        }

        $messages = isset( $messages ) ? $messages : '';



          $get_data_from = isset( $get_data_from ) ? $get_data_from : '';
        
          echo '<a ' . htmlspecialchars_decode( esc_attr( $messages ) ) . ' data-get-from="' . esc_attr( $get_data_from ) . '" data-update="'.esc_attr( $update ).'" data-id="'.esc_attr($field_id).'" data-func="'.esc_attr($function).'" href="javascript:void(0);" class="'.$field_class.' wpmdm-import-options-ui-button button-hero button button-primary wpmdm-import-options-ajax-action-button" title="' . esc_attr( $button_title ) . '">' . esc_attr( $button_title )  . '</a>';

          echo '<div class="wpmdm-import-options-spacer"></div>';

          echo '<div class="'.esc_attr($field_id).'-result wpmdm-import-options-result-container"></div>';

      
      echo '</div>';
    
    echo '</div>';
    
  }
  

}
/**
 * Ajax Action.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_directory_files' ) ) {
  
  function wpmdm_import_options_type_directory_files( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;

    $clear_folder_messages = isset( $clear_folder_messages) ? $clear_folder_messages : '';

    $refresh_folder_messages = isset( $refresh_folder_messages) ? $refresh_folder_messages : '';

    $check_folder_messages = isset( $check_folder_messages) ? $check_folder_messages : '';

    echo '<div class="wpmdm-import-options-directory-files-tools">';

      echo '<a ' .htmlspecialchars_decode( esc_attr( $clear_folder_messages ) . esc_attr( $check_folder_messages ) ) . ' href="javascript:void(0);" data-folder="' . wpmdm_import_options_encode( esc_attr( $directory_path ) ) . '" class="wpmdm-import-options-clear-folder">' . esc_html__('Clear Folder', 'wpmdm-import-options') .'</a>';


      echo '<a ' . htmlspecialchars_decode( esc_attr( $refresh_folder_messages ) ) . ' href="javascript:void(0);" class="wpmdm-import-options-refresh-folder">' . esc_html__('Refresh Folder', 'wpmdm-import-options') .'</a>';

    echo '</div>';
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-directory-files ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
       /* format setting inner wrapper */

       echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '" data-id="' .esc_attr( $field_id ) . '">';

        echo '<div class="wpmdm-import-options-directory-files">';

          echo '<div class="wpmdm-import-options-directory-files-view" data-path="' . esc_attr( $directory_path ) . '" data-url="' . esc_attr( $directory_url ) . '"  data-files-type="' . esc_attr( $files_type ) . '">';

              wpmdm_import_options_folder_view( $directory_path,  $directory_url, $files_type, $messages );

          echo '</div>';

        echo '</div>';

      echo '</div>';
    
    echo '</div>';
    
  }
  

}
/**
 * Background option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_background' ) ) {
  
  function wpmdm_import_options_type_background( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* If an attachment ID is stored here fetch its URL and replace the value */
    if ( isset( $field_value['background-image'] ) && wp_attachment_is_image( $field_value['background-image'] ) ) {
    
      $attachment_data = wp_get_attachment_image_src( $field_value['background-image'], 'original' );
      
      /* check for attachment data */
      if ( $attachment_data ) {
      
        $field_src = $attachment_data[0];
        
      }
      
    }
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-background ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">'; 
        
        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_background_fields = apply_filters( 'wpmdm_import_options_recognized_background_fields', array( 
          'background-color',
          'background-repeat', 
          'background-attachment', 
          'background-position',
          'background-size',
          'background-image'
        ), $field_id );
        
        echo '<div class="wpmdm-import-options-background-group wpmdm-import-options-group">';
        
          /* build background color */
          if ( in_array( 'background-color', $wpmdm_import_options_recognized_background_fields ) ) {

            echo '<div class="wpmdm-import-options-background-group-item wpmdm-import-options-group-item">';
          
            echo '<div class="wpmdm-import-options-ui-colorpicker-input-wrap">';

              /* colorpicker JS */
              echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';
              
              /* set background color */
              $background_color = isset( $field_value['color'] ) ? esc_attr( $field_value['background-color'] ) : '';
              
              /* input */
              echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-color]" id="' . $field_id . '-picker-' . esc_attr(  $type ) . '" value="' . $background_color . '" class="wpmdm-import-options-hide-color-picker ' . esc_attr( $field_class ) . '" />';
            
            echo '</div>';

            echo '</div>';
          
          }
      
          /* build background repeat */
          if ( in_array( 'background-repeat', $wpmdm_import_options_recognized_background_fields ) ) {
            echo '<div class="wpmdm-import-options-background-group-item wpmdm-import-options-group-item">';
          
            $background_repeat = isset( $field_value['background-repeat'] ) ? esc_attr( $field_value['background-repeat'] ) : '';
            
            echo '<select name="' . esc_attr( $field_name ) . '[background-repeat]" id="' . esc_attr( $field_id ) . '-repeat" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
              
              echo '<option value="">' . esc_html__( 'repeat', 'wpmdm-import-options' ) . '</option>';
              foreach ( wpmdm_import_options_recognized_background_repeat( $field_id ) as $key => $value ) {
              
                echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_repeat, $key, false ) . '>' . esc_attr( $value ) . '</option>';
                
              }
              
            echo '</select>';
            echo '</div>';
          
          }
          
          /* build background attachment */
          if ( in_array( 'background-attachment', $wpmdm_import_options_recognized_background_fields ) ) {

            echo '<div class="wpmdm-import-options-background-group-item wpmdm-import-options-group-item">';
          
            $background_attachment = isset( $field_value['background-attachment'] ) ? esc_attr( $field_value['background-attachment'] ) : '';
            
            echo '<select name="' . esc_attr( $field_name ) . '[background-attachment]" id="' . esc_attr( $field_id ) . '-attachment" class="wpmdm-import-options-ui-select ' . $field_class . '">';
              
              echo '<option value="">' . esc_html__( 'attachment', 'wpmdm-import-options' ) . '</option>';
              
              foreach ( wpmdm_import_options_recognized_background_attachment( $field_id ) as $key => $value ) {
              
                echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_attachment, $key, false ) . '>' . esc_attr( $value ) . '</option>';
              
              }
              
            echo '</select>';

            echo '</div>';
          
          }
          /* build background position */
          if ( in_array( 'background-position', $wpmdm_import_options_recognized_background_fields ) ) {
            echo '<div class="wpmdm-import-options-background-group-item wpmdm-import-options-group-item">';
          
            $background_position = isset( $field_value['background-position'] ) ? esc_attr( $field_value['background-position'] ) : '';
            
            echo '<select name="' . esc_attr( $field_name ) . '[background-position]" id="' . esc_attr( $field_id ) . '-position" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
              
              echo '<option value="">' . esc_html__( 'position', 'wpmdm-import-options' ) . '</option>';
              
              foreach ( wpmdm_import_options_recognized_background_position( $field_id ) as $key => $value ) {
                
                echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_position, $key, false ) . '>' . esc_attr( $value ) . '</option>';
              
              }
            
            echo '</select>';
            echo '</div>';
          
          }
  
          /* Build background size  */
          if ( in_array( 'background-size', $wpmdm_import_options_recognized_background_fields ) ) {
            echo '<div class="wpmdm-import-options-background-group-item wpmdm-import-options-group-item">';
            

            $choices = array( 
                  array(
                    'label' => 'size',
                    'value' => ''
                  ),
                  array(
                    'label' => 'cover',
                    'value' => 'cover'
                  ),
                  array(
                    'label' => 'contain',
                    'value' => 'contain'
                  )
                );
            
            if ( is_array( $choices ) && ! empty( $choices ) ) {
            
              /* build select */
              echo '<select name="' . esc_attr( $field_name ) . '[background-size]" id="' . esc_attr( $field_id ) . '-size" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
              
                foreach ( (array) $choices as $choice ) {
                  if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
                    echo '<option value="' . esc_attr( $choice['value'] ) . '"' . selected( ( isset( $field_value['background-size'] ) ? $field_value['background-size'] : '' ), $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
                  }
                }
        
              echo '</select>';
            
            } else {
            
              echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-size]" id="' . esc_attr( $field_id ) . '-size" value="' . ( isset( $field_value['background-size'] ) ? esc_attr( $field_value['background-size'] ) : '' ) . '" class="widefat wpmdm-import-options-background-size-input wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'size', 'wpmdm-import-options' ) . '" />';
              
            }
            echo '</div>';
          
          }
        
        echo '</div>';

        /* build background image */
        if ( in_array( 'background-image', $wpmdm_import_options_recognized_background_fields ) ) {
        
          echo '<div class="wpmdm-import-options-ui-upload-parent">';
            
            /* input */
            echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-image]" id="' . esc_attr( $field_id ) . '" value="' . ( isset( $field_value['background-image'] ) ? esc_attr( $field_value['background-image'] ) : '' ) . '" class="widefat wpmdm-import-options-ui-upload-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'image', 'wpmdm-import-options' ) . '" />';
            
            /* add media button */
            echo '<a href="javascript:void(0);" class="wpmdm_import_options_upload_media wpmdm-import-options-ui-button button button-primary light" rel="' . $post_id . '" title="' . esc_html__( 'Add Media', 'wpmdm-import-options' ) . '"><span class="fa fa-plus-circle"></span>' . esc_html__( 'Add Media', 'wpmdm-import-options' ) . '</a>';
          
          echo '</div>';
          
          /* media */
          if ( isset( $field_value['background-image'] ) && $field_value['background-image'] !== '' ) {
            
            /* replace image src */
            if ( isset( $field_src ) )
              $field_value['background-image'] = $field_src;
          
            echo '<div class="wpmdm-import-options-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';
            
              if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value['background-image'] ) )
                echo '<div class="wpmdm-import-options-ui-image-wrap"><img src="' . esc_url( $field_value['background-image'] ) . '" alt="" /></div>';
              
              echo '<a href="javascript:(void);" class="wpmdm-import-options-ui-remove-media wpmdm-import-options-ui-button button button-secondary light" title="' . esc_html__( 'Remove Media', 'wpmdm-import-options' ) . '"><span class="fa fa-trash"></span>' . esc_html__( 'Remove Media', 'wpmdm-import-options' ) . '</a>';
              
            echo '</div>';
            
          }
        }

      echo '</div>';

    echo '</div>';
    
  }
  
}



/**
 * Border Option Type
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The markup.
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_border' ) ) {

  function wpmdm_import_options_type_border( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-border ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_border_fields = apply_filters( 'wpmdm_import_options_recognized_border_fields', array(
          'width',
          'unit',
          'style',
          'color'
        ), $field_id );

        echo '<div class="wpmdm-import-options-border-group wpmdm-import-options-group">';

        /* build border width */
        if ( in_array( 'width', $wpmdm_import_options_recognized_border_fields ) ) {

          $width = isset( $field_value['width'] ) ? esc_attr( $field_value['width'] ) : '';

          echo '<div class="wpmdm-import-options-group-item "><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'width', 'wpmdm-import-options' ) . '" /></div>';

        }

        /* build unit dropdown */
        if ( in_array( 'unit', $wpmdm_import_options_recognized_border_fields ) ) {
          
          echo '<div class="wpmdm-import-options-group-item">';
          
            echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
    
              echo '<option value="">' . esc_html__( 'unit', 'wpmdm-import-options' ) . '</option>';
    
              foreach ( wpmdm_import_options_recognized_border_unit_types( $field_id ) as $unit ) {
                echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
              }
    
            echo '</select>';
          
          echo '</div>';
  
        }
        
        /* build style dropdown */
        if ( in_array( 'style', $wpmdm_import_options_recognized_border_fields ) ) {
          
          echo '<div class="wpmdm-import-options-group-item">';
          
            echo '<select name="' . esc_attr( $field_name ) . '[style]" id="' . esc_attr( $field_id ) . '-style" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
    
              echo '<option value="">' . esc_html__( 'style', 'wpmdm-import-options' ) . '</option>';
    
              foreach ( wpmdm_import_options_recognized_border_style_types( $field_id ) as $key => $style ) {
                echo '<option value="' . esc_attr( $key ) . '"' . ( isset( $field_value['style'] ) ? selected( $field_value['style'], $key, false ) : '' ) . '>' . esc_attr( $style ) . '</option>';
              }
    
            echo '</select>';
          
          echo '</div>';
  
        }
        
        /* build color */
        if ( in_array( 'color', $wpmdm_import_options_recognized_border_fields ) ) {
          
          echo '<div class="wpmdm-import-options-group-item">';

            /* colorpicker JS */
              echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

            /* set color */
            $color = isset( $field_value['color'] ) ? esc_attr( $field_value['color'] ) : '';
            
            /* input */
            echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . $field_id . '-picker-' . esc_attr(  $type ) . '" value="' . $color . '" class="wpmdm-import-options-hide-color-picker ' . esc_attr( $field_class ) . '" />';
          
          echo '</div>';
        
        }

        echo '</div>';
      
      echo '</div>';

    echo '</div>';

  }

}

/**
 * Box Shadow Option Type
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The markup.
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_box_shadow' ) ) {

  function wpmdm_import_options_type_box_shadow( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-box-shadow ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

      echo '<div class="wpmdm-import-options-group">';

        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_box_shadow_fields = apply_filters( 'wpmdm_import_options_recognized_box_shadow_fields', array(
          'inset',
          'offset-x',
          'offset-y',
          'blur-radius',
          'spread-radius',
          'color'
        ), $field_id );
        
        /* build inset */
        if ( in_array( 'inset', $wpmdm_import_options_recognized_box_shadow_fields ) ) {
        
          echo '<div class="wpmdm-import-options-group-item"><p>';
            echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[inset]" id="' . esc_attr( $field_id ) . '-inset" value="inset" ' . ( isset( $field_value['inset'] ) ? checked( $field_value['inset'], 'inset', false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
            echo '<label for="' . esc_attr( $field_id ) . '-inset">inset</label>';
          echo '</p></div>';
          
        }
          
        /* build horizontal offset */
        if ( in_array( 'offset-x', $wpmdm_import_options_recognized_box_shadow_fields ) ) {

          $offset_x = isset( $field_value['offset-x'] ) ? esc_attr( $field_value['offset-x'] ) : '';

          echo '<div class="wpmdm-import-options-group-item"><span class="wpmdm-import-options-icon-arrows-h wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[offset-x]" id="' . esc_attr( $field_id ) . '-offset-x" value="' . $offset_x . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'offset-x', 'wpmdm-import-options' ) . '" /></div>';

        }
        
        /* build vertical offset */
        if ( in_array( 'offset-y', $wpmdm_import_options_recognized_box_shadow_fields ) ) {

          $offset_y = isset( $field_value['offset-y'] ) ? esc_attr( $field_value['offset-y'] ) : '';

          echo '<div class="wpmdm-import-options-group-item"><span class="wpmdm-import-options-icon-arrows-v wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[offset-y]" id="' . esc_attr( $field_id ) . '-offset-y" value="' . $offset_y . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'offset-y', 'wpmdm-import-options' ) . '" /></div>';

        }
        
        /* build blur-radius radius */
        if ( in_array( 'blur-radius', $wpmdm_import_options_recognized_box_shadow_fields ) ) {

          $blur_radius = isset( $field_value['blur-radius'] ) ? esc_attr( $field_value['blur-radius'] ) : '';

          echo '<div class="wpmdm-import-options-group-item"><span class="wpmdm-import-options-icon-circle wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[blur-radius]" id="' . esc_attr( $field_id ) . '-blur-radius" value="' . $blur_radius . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'blur-radius', 'wpmdm-import-options' ) . '" /></div>';

        }
        
        /* build spread-radius radius */
        if ( in_array( 'spread-radius', $wpmdm_import_options_recognized_box_shadow_fields ) ) {

          $spread_radius = isset( $field_value['spread-radius'] ) ? esc_attr( $field_value['spread-radius'] ) : '';

          echo '<div class="wpmdm-import-options-group-item"><span class="wpmdm-import-options-icon-arrows-alt wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[spread-radius]" id="' . esc_attr( $field_id ) . '-spread-radius" value="' . $spread_radius . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'spread-radius', 'wpmdm-import-options' ) . '" /></div>';

        }
        
        /* build color */
        if ( in_array( 'color', $wpmdm_import_options_recognized_box_shadow_fields ) ) {
          
          echo '<div class="wpmdm-import-options-group-item">';

            
            /* set color */
            $color = isset( $field_value['color'] ) ? esc_attr( $field_value['color'] ) : '';
            /* colorpicker JS */
              echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

            
            /* input */
            echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . $field_id . '-picker-' . esc_attr(  $type ) . '" value="' . $color . '" class="wpmdm-import-options-hide-color-picker ' . esc_attr( $field_class ) . '" />';
          
          echo '</div>';
        
        }

         echo '</div>';
        
      echo '</div>';

    echo '</div>';

  }

}

/**
 * Category Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_category_checkbox' ) ) {
  
  function wpmdm_import_options_type_category_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-category-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        /* get category array */
        $categories = get_categories( apply_filters( 'wpmdm_import_options_type_category_checkbox_query', array( 'hide_empty' => false ), $field_id ) );
        
        /* build categories */
        if ( ! empty( $categories ) ) {
          foreach ( $categories as $category ) {
            echo '<p>';
              echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $category->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '" value="' . esc_attr( $category->term_id ) . '" ' . ( isset( $field_value[$category->term_id] ) ? checked( $field_value[$category->term_id], $category->term_id, false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
              echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '">' . esc_attr( $category->name ) . '</label>';
            echo '</p>';
          } 
        } else {
          echo '<p>' . __( 'No Categories Found', 'wpmdm-import-options' ) . '</p>';
        }
      
      echo '</div>';
    
    echo '</div>';
    
  }
  
}


/**
 * Category Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_category_select' ) ) {
  
  function wpmdm_import_options_type_category_select( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-category-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build category */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';
        
        /* get category array */
        $categories = get_categories( apply_filters( 'wpmdm_import_options_type_category_select_query', array( 'hide_empty' => false ), $field_id ) );
        
        /* has cats */
        if ( ! empty( $categories ) ) {
          echo '<option value="">-- ' . esc_html__( 'Choose One', 'wpmdm-import-options' ) . ' --</option>';
          foreach ( $categories as $category ) {
            echo '<option value="' . esc_attr( $category->term_id ) . '"' . selected( $field_value, $category->term_id, false ) . '>' . esc_attr( $category->name ) . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Categories Found', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
      
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_checkbox' ) ) {
  
  function wpmdm_import_options_type_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      echo '<input type="hidden" id="'.esc_attr( $field_id ).'-hidden-args" class="wpmdm-import-options-hidden-args" value="'.wpmdm_import_options_encode( serialize( $args ) ).'" />';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '"  data-id="'.esc_attr( $field_id ).'">';    
      

        /* build checkbox */
        foreach ( (array) $field_choices as $key => $choice ) {
          if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
            echo '<p>';
              echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '" ' . ( isset( $field_value[$key] ) ? checked( $field_value[$key], $choice['value'], false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
              echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label>';
            echo '</p>';
          }
        }
      
      echo '</div>';

    echo '</div>';
    
  }
  
}


/**
 * Table Search option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_table_source' ) ) {
  
  function wpmdm_import_options_type_table_source( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
      
      if( isset( $field_choices )  && !empty( $field_choices ) ) {


          echo '<div class="wpmdm-import-options-table-source-container">';

          echo '<div class="wpmdm-import-options-table-source-head">';

            echo '<h4 class="wpmdm-import-options-type-table-source-head-item wpmdm-import-options-type-table-source-title">' . esc_attr( $title ) . '</h4>';

            echo '<div class="wpmdm-import-options-type-table-source-head-item">';

              echo '<label>' . esc_html__('Filter', 'wpmdm-import-options') .'</label>';

              echo '<input type="text" class="wpmdm-import-options-table-source-filter" name="wpmdm-import-options-table-source-filter-' . esc_attr( $field_id ) . '">';

            echo '</div>';

          echo '</div>';

          /* format setting outer wrapper */
          echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-table-source ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
            
              /* description */
              echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

              /* format setting inner wrapper */
              echo '<div class="wpmdm-import-options-format-setting-inner wpmdm-import-options-type-table-source"  data-id="'.esc_attr( $field_id ).'">';



              echo '<table>';

              echo '<thead>';

              if( isset( $heads ) && is_array( $heads ) ) {

                foreach ( $heads as $key => $head ) {

                  $width = isset( $widths, $widths[ $key ] ) ?  $widths[ $key ] : '';

                  echo '<th style="width : '. esc_attr( $width ) .'%">' . esc_attr( $head ) . '</th>';
                  
                }

              }

              echo '</thead>';

              echo '<tbody>';

              foreach( (array) $field_choices as $key => $choice ) {

                echo '<tr>';

                foreach( $choice as $k => $val ) {

                    $val = is_array( $val ) ? esc_html__('Array' , 'wpmdm-import-options') : $val;

                    if( $k != 'value' ) {

                      echo '<td class="wpmdm-import-options-table-source-val">' . esc_attr( $val ) . '</td>';

                    } else {

                      if( isset( $action ) && is_array( $action ) && isset( $action['id'] ) && isset( $action['title'] ) ) {

                         $data = 'data-add-to="' . esc_attr( $action['id'] ) . '" data-value="' . esc_attr( $val ) . '"';

                         echo '<td class="wpmdm-import-options-table-source-action"><a class="wpmdm-import-options-list-item-add-to" ' . htmlspecialchars_decode ( esc_attr( $data ) ) . ' href="javascript:void(0);">' . esc_attr( $action['title'] ) . '</a></td>';

                      }

                    }
                  
                }

                echo '</tr>';

              }

              echo '</tbody>';

              echo '</table>';

              echo '</div>';

              echo '</div>';

              echo '</div>';


      } else {

        echo isset( $empty_message ) ? htmlspecialchars_decode( esc_html( $empty_message ) ) : '';
      }
      
      

    if(isset($save_action) && isset($save_action['id']) && isset($save_action['title']) ) {

      echo '<a data-id="'.esc_attr( $field_id ).'" data-save-to="'. esc_attr( $save_action['id'] ).'" href="javascript:void(0);" class="wpmdm-import-options-list-item-save wpmdm-import-options-ui-button button button-primary right hug-right">'. esc_attr( $save_action['title'] ).'</a>';

    }

  }
  
}



/**
 * Colorpicker option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 * @updated   2.2.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_colorpicker' ) ) {
  
  function wpmdm_import_options_type_colorpicker( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-colorpicker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">'; 
        
        /* build colorpicker */  
        echo '<div class="wpmdm-import-options-ui-colorpicker-input-wrap">';
          
          /* colorpicker JS */
          echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

          /* set the default color */
          $std = $field_std ? 'data-default-color="' . $field_std . '"' : '';
          
          /* input */
          echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-picker-' . esc_attr( $type )  . '" value="' . esc_attr( $field_value ) . '" class="wpmdm-import-options-hide-color-picker ' . esc_attr( $field_class ) . '" ' . $std . ' />';
        
        echo '</div>';
      
      echo '</div>';

    echo '</div>';

    
  }
  
}

/**
 * Colorpicker Opacity option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_colorpicker_opacity' ) ) {

  function wpmdm_import_options_type_colorpicker_opacity( $args = array() ) {

    $args['field_class'] = isset( $args['field_class'] ) ? $args['field_class'] . ' wpmdm-import-options-colorpicker-opacity' : 'wpmdm-import-options-colorpicker-opacity';
    wpmdm_import_options_type_colorpicker( $args );

  }

}


/**
 * Custom Post Type Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_custom_post_type_checkbox' ) ) {
  
  function wpmdm_import_options_type_custom_post_type_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-custom-post-type-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        /* setup the post types */
        $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

        /* query posts array */
        $my_posts = get_posts( apply_filters( 'wpmdm_import_options_type_custom_post_type_checkbox_query', array( 'post_type' => $post_type, 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $field_id ) );

        /* has posts */
        if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
          foreach( $my_posts as $my_post ) {
            $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
            echo '<p>';
            echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[$my_post->ID] ) ? checked( $field_value[$my_post->ID], $my_post->ID, false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
            echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . $post_title . '</label>';
            echo '</p>';
          }
        } else {
          echo '<p>' . esc_html__( 'No Posts Found', 'wpmdm-import-options' ) . '</p>';
        }
        
      echo '</div>';

    echo '</div>';
    
  }
  
}

/**
 * Custom Post Type Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_custom_post_type_select' ) ) {
  
  function wpmdm_import_options_type_custom_post_type_select( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-custom-post-type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        /* build category */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';
        
        /* setup the post types */
        $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
        
        /* query posts array */
        $my_posts = get_posts( apply_filters( 'wpmdm_import_options_type_custom_post_type_select_query', array( 'post_type' => $post_type, 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $field_id ) );
        
        /* has posts */
        if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
          echo '<option value="">-- ' . esc_html__( 'Choose One', 'wpmdm-import-options' ) . ' --</option>';
          foreach( $my_posts as $my_post ) {
            $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
            echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $field_value, $my_post->ID, false ) . '>' . $post_title . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Posts Found', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
        
      echo '</div>';

    echo '</div>';
    
  }
  
}

/**
 * Date Picker option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.3
 */
if ( ! function_exists( 'wpmdm_import_options_type_date_picker' ) ) {
  
  function wpmdm_import_options_type_date_picker( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* filter date format */
    $date_format = apply_filters( 'wpmdm_import_options_type_date_picker_date_format', 'yy-mm-dd', $field_id );

    /**
     * Filter the addition of the readonly attribute.
     *
     * @since 2.5.0
     *
     * @param bool $is_readonly Whether to add the 'readonly' attribute. Default 'false'.
     * @param string $field_id The field ID.
     */
    $is_readonly = apply_filters( 'wpmdm_import_options_type_date_picker_readonly', false, $field_id );

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-date-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
    
    /* date picker JS */      
    echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_date_picker("' . esc_attr( $field_id ) . '", "' . esc_attr( $date_format ) . '"); });</script>';      
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build date picker */
        echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '"' . ( $is_readonly == true ? ' readonly' : '' ) . ' />';
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Date Time Picker option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.3
 */
if ( ! function_exists( 'wpmdm_import_options_type_date_time_picker' ) ) {
  
  function wpmdm_import_options_type_date_time_picker( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* filter date format */
    $date_format = apply_filters( 'wpmdm_import_options_type_date_time_picker_date_format', 'yy-mm-dd', $field_id );

    /**
     * Filter the addition of the readonly attribute.
     *
     * @since 2.5.0
     *
     * @param bool $is_readonly Whether to add the 'readonly' attribute. Default 'false'.
     * @param string $field_id The field ID.
     */
    $is_readonly = apply_filters( 'wpmdm_import_options_type_date_time_picker_readonly', false, $field_id );

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-date-time-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
    
    /* date time picker JS */      
    echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_date_time_picker("' . esc_attr( $field_id ) . '", "' . esc_attr( $date_format ) . '"); });</script>';      
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build date time picker */
        echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '"' . ( $is_readonly == true ? ' readonly' : '' ) . ' />';
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Dimension Option Type
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The markup.
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_dimension' ) ) {

  function wpmdm_import_options_type_dimension( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-dimension ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_dimension_fields = apply_filters( 'wpmdm_import_options_recognized_dimension_fields', array(
          'width',
          'height',
          'unit'
        ), $field_id );

        echo '<div class="wpmdm-import-options-group wpmdm-import-options-dimension-group">';

        /* build width dimension */
        if ( in_array( 'width', $wpmdm_import_options_recognized_dimension_fields ) ) {

          $width = isset( $field_value['width'] ) ? esc_attr( $field_value['width'] ) : '';

          echo '<div class="wpmdm-import-options-group-item"><span class="wpmdm-import-options-icon-arrows-h wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'width', 'wpmdm-import-options' ) . '" /></div>';

        }

        /* build height dimension */
        if ( in_array( 'height', $wpmdm_import_options_recognized_dimension_fields ) ) {

          $height = isset( $field_value['height'] ) ? esc_attr( $field_value['height'] ) : '';

          echo '<div class="wpmdm-import-options-group-item"><span class="wpmdm-import-options-icon-arrows-v wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[height]" id="' . esc_attr( $field_id ) . '-height" value="' . esc_attr( $height ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'height', 'wpmdm-import-options' ) . '" /></div>';

        }
        
        /* build unit dropdown */
        if ( in_array( 'unit', $wpmdm_import_options_recognized_dimension_fields ) ) {
          
          echo '<div class="wpmdm-import-options-group-item">';
          
            echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
    
              echo '<option value="">' . esc_html__( 'unit', 'wpmdm-import-options' ) . '</option>';
    
              foreach ( wpmdm_import_options_recognized_dimension_unit_types( $field_id ) as $unit ) {
                echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
              }
    
            echo '</select>';
          
          echo '</div>';
  
        }

        echo '</div>';
      
      echo '</div>';

    echo '</div>';

  }

}

/**
 * Gallery option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The gallery metabox markup.
 *
 * @access    public
 * @since     2.2.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_gallery' ) ) {

  function wpmdm_import_options_type_gallery( $args = array() ) {
  
    // Turns arguments array into variables
    extract( $args );
  
    // Verify a description
    $has_desc = $field_desc ? true : false;
  
    // Format setting outer wrapper
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-gallery ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
  
      // Description
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
  
      // Format setting inner wrapper
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
  
        // Setup the post type
        $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
        
        $field_value = trim( $field_value );
        
        // Saved values
        echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="wpmdm-import-options-gallery-value ' . esc_attr( $field_class ) . '" />';
        
        // Search the string for the IDs
        preg_match( '/ids=\'(.*?)\'/', $field_value, $matches );
        
        // Turn the field value into an array of IDs
        if ( isset( $matches[1] ) ) {
          
          // Found the IDs in the shortcode
          $ids = explode( ',', $matches[1] );
          
        } else {
          
          // The string is only IDs
          $ids = ! empty( $field_value ) && $field_value != '' ? explode( ',', $field_value ) : array();
          
        }

        // Has attachment IDs
        if ( ! empty( $ids ) ) {
          
          echo '<ul class="wpmdm-import-options-gallery-list">';
          
          foreach( $ids as $id ) {
            
            if ( $id == '' )
              continue;
              
            $thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
        
            echo '<li><img  src="' . $thumbnail[0] . '" width="75" height="75" /></li>';
        
          }
        
          echo '</ul>';
          
          echo '
          <div class="wpmdm-import-options-gallery-buttons">
            <a href="#" class="wpmdm-import-options-ui-button button button-secondary hug-left wpmdm-import-options-gallery-delete">' . esc_html__( 'Delete Gallery', 'wpmdm-import-options' ) . '</a>
            <a href="#" class="wpmdm-import-options-ui-button button button-primary right hug-right wpmdm-import-options-gallery-edit">' . esc_html__( 'Edit Gallery', 'wpmdm-import-options' ) . '</a>
          </div>';
        
        } else {
        
          echo '
          <div class="wpmdm-import-options-gallery-buttons">
            <a href="#" class="wpmdm-import-options-ui-button button button-primary right hug-right wpmdm-import-options-gallery-add">' . esc_html__( 'Create Gallery', 'wpmdm-import-options' ) . '</a>
          </div>';
        
        }
      
      echo '</div>';
      
    echo '</div>';
    
  }

}


/**
 * JavaScript option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_javascript' ) ) {
  
  function wpmdm_import_options_type_javascript( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-javascript simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        /* build textarea for CSS */
        echo '<textarea class="hidden" id="textarea_' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_name ) .'">' . esc_attr( $field_value ) . '</textarea>';
    
        /* build pre to convert it into ace editor later */
        echo '<pre class="wpmdm-import-options-javascript-editor ' . esc_attr( $field_class ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</pre>';
        
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Link Color option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The markup.
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_link_color' ) ) {

  function wpmdm_import_options_type_link_color( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-link-color ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_link_color_fields = apply_filters( 'wpmdm_import_options_recognized_link_color_fields', array(
          'link'    => _x( 'Standard', 'color picker', 'wpmdm-import-options' ),
          'hover'   => _x( 'Hover', 'color picker', 'wpmdm-import-options' ),
          'active'  => _x( 'Active', 'color picker', 'wpmdm-import-options' ),
          'visited' => _x( 'Visited', 'color picker', 'wpmdm-import-options' ),
          'focus'   => _x( 'Focus', 'color picker', 'wpmdm-import-options' )
        ), $field_id );

        /* build link color fields */
        foreach( $wpmdm_import_options_recognized_link_color_fields as $type => $label ) {

          if ( array_key_exists( $type, $wpmdm_import_options_recognized_link_color_fields ) ) {
            
            echo '<div class="wpmdm-import-options-ui-colorpicker-input-wrap">';

              echo '<label for="' . esc_attr( $field_id ) . '-picker-' . $type . '" class="wpmdm-import-options-ui-colorpicker-label">' . esc_attr( $label ) . '</label>';

              /* colorpicker JS */
              echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

              /* set color */
              $color = isset( $field_value[ $type ] ) ? esc_attr( $field_value[ $type ] ) : '';
              
              /* set default color */
              $std = isset( $field_std[ $type ] ) ? 'data-default-color="' . $field_std[ $type ] . '"' : '';

              /* input */
              echo '<input type="text" name="' . esc_attr( $field_name ) . '[' . $type . ']" id="' . esc_attr( $field_id ) . '-picker-' . esc_attr( $type ) . '" value="' . $color . '" class="wpmdm-import-options-hide-color-picker ' . esc_attr( $field_class ) . '" ' . $std . ' />';

            echo '</div>';

          }

        }

      echo '</div>';

    echo '</div>';

  }

}

/**
 * List Item option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_list_item' ) ) {
  
  function wpmdm_import_options_type_list_item( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* required title setting */
    $required_setting = array(
        'id'        => 'title',
        'label'     => esc_html__( 'Title', 'wpmdm-import-options' ),
        'desc'      => '',
        'std'       => isset( $value['title'] ) ? $value['title'] : '',
        'type'      => 'text',
        'rows'      => '',
        'class'     => 'wpmdm-import-options-setting-title',
        'post_type' => '',
        'choices'   => array()
    );

    array_unshift( $field_settings, $required_setting );

    if( isset( $disabled ) && is_array( $disabled ) ) {

      foreach( $field_settings as $key => $setting ) {

        if( isset( $disabled[ $setting[ 'id' ] ] ) ) {

          $field_settings[ $key ][ 'disabled' ] = $disabled[ $setting[ 'id' ] ];

        }

      }

    }


    /* verify a description */
    $has_desc = $field_desc ? true : false;

    # Default
    $sortable = true;

    # Check if the list can be sorted
    if ( ! empty( $field_class ) ) {
      $classes = explode( ' ', $field_class );
      if ( in_array( 'not-sortable', $classes ) ) {
        $sortable = false;
        str_replace( 'not-sortable', '', $field_class );
      }
    }

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-list-item ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        /* pass the settings array arround */
        echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . wpmdm_import_options_encode( serialize( $field_settings ) ) . '" />';
        
       
        /** 
         * settings pages have array wrappers like 'wpmdm_import_options'.
         * So we need that value to create a proper array to save to.
         * This is only for NON metabox settings.
         */
        if ( ! isset( $get_option ) )
          $get_option = '';
          
        /* build list items */
        echo '<ul class="wpmdm-import-options-setting-wrap' . ( $sortable ? ' wpmdm-import-options-sortable' : '' ) .'" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

        $inner = isset( $field_inner ) ? $field_inner : false;

        if ( is_array( $field_value ) && ! empty( $field_value ) ) {

          foreach( $field_value as $key => $list_item ) {

              $data_id =  $field_id . '-' . $key;

              echo '<li class="ui-state-default wpmdm-import-options-list-item" data-key="' . esc_attr( $data_id ) . '">';

                wpmdm_import_options_list_item_view(  $field_id, $key, $list_item, $post_id, $get_option, $field_settings, $type, $inner );

              echo '</li>';

          }
          
        } else {

           if( isset( $empty_message )  &&  !empty( $empty_message ) ) {

            $list_desc = $sortable ? esc_html__( 'You can re-order with drag & drop, the order will update after saving.', 'wpmdm-import-options' ) : '';
            echo '<div data-id="' . esc_attr( $field_id ) . '" class="list-item-empty wpmdm-import-options-infos-message">' . esc_attr ( $empty_message ) . '</div>';

          }

        }
        
        echo '</ul>';

        $field_add_class= isset( $field_inner ) && $field_inner == true  ? esc_attr('wpmdm-import-options-list-item-inner-add') : esc_attr('wpmdm-import-options-list-item-add');

        if( isset( $disabled ) && isset( $disabled['add'] )  &&  $disabled['add'] == true ) {

          $field_add_class .= ' wpmdm-import-options-list-item-add-disabled';

        }

        echo '<a data-id="'.esc_attr( $field_id ).'" href="javascript:void(0);" class="'.esc_attr( $field_add_class ).' wpmdm-import-options-ui-button button button-primary right hug-right" title="' . esc_attr( $add_title ) . '">' . esc_attr( $add_title )  . '</a>';

        

        
        /* description */
        if( !isset( $disabled ) || !isset( $disabled['add'] )  ||  $disabled['add'] != true ) {

          $list_desc = $sortable ? esc_html__( 'You can re-order with drag & drop, the order will update after saving.', 'wpmdm-import-options' ) : '';
          echo '<div class="list-item-description">' . apply_filters( 'wpmdm_import_options_list_item_description', $list_desc, $field_id ) . '</div>';

        }
        echo '<div class="wpmdm-import-options-clear clear"></div>';
      
      echo '</div>';

    echo '</div>';
    
  }
  
}

/**
 * Measurement option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_measurement' ) ) {
  
  function wpmdm_import_options_type_measurement( $args = array() ) {

    
    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-measurement ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

      echo '<div class="wpmdm-import-options-group">';
      
        echo '<div class="wpmdm-import-options-group-item">';
        
          echo '<input type="text" name="' . esc_attr( $field_name ) . '[0]" id="' . esc_attr( $field_id ) . '-0" value="' . ( isset( $field_value[0] ) ? esc_attr( $field_value[0] ) : '' ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" />';
        
        echo '</div>';

        echo '<div class="wpmdm-import-options-group-item">';
        
          /* build measurement */
          echo '<select name="' . esc_attr( $field_name ) . '[1]" id="' . esc_attr( $field_id ) . '-1" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
            
            echo '<option value="">' . esc_html__( 'unit', 'wpmdm-import-options' ) . '</option>';
            
            foreach ( wpmdm_import_options_measurement_unit_types( $field_id ) as $unit ) {
              echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value[1] ) ? selected( $field_value[1], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
            }
            
          echo '</select>';

        echo '</div>';

      echo '</div>';
      
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Numeric Slider option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.1
 */
if ( ! function_exists( 'wpmdm_import_options_type_numeric_slider' ) ) {

  function wpmdm_import_options_type_numeric_slider( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    $_options = explode( ',', $field_min_max_step );
    $min = isset( $_options[0] ) ? $_options[0] : 0;
    $max = isset( $_options[1] ) ? $_options[1] : 100;
    $step = isset( $_options[2] ) ? $_options[2] : 1;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-numeric-slider ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        echo '<div class="wpmdm-import-options-numeric-slider-wrap">';

          echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-numeric-slider-hidden-input" value="' . esc_attr( $field_value ) . '" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '">';

          echo '<input type="text" class="wpmdm-import-options-numeric-slider-helper-input widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" value="' . esc_attr( $field_value ) . '" readonly>';

          echo '<div id="wpmdm_import_options_numeric_slider_' . esc_attr( $field_id ) . '" class="wpmdm-import-options-numeric-slider"></div>';

        echo '</div>';
      
      echo '</div>';
      
    echo '</div>';
  }

}

/**
 * On/Off option type
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The gallery metabox markup.
 *
 * @access    public
 * @since     2.2.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_on_off' ) ) {

  function wpmdm_import_options_type_on_off( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        /* Force only two choices, and allowing filtering on the choices value & label */
        $field_choices = array(
          array(
            /**
             * Filter the value of the On button.
             *
             * @since 2.5.0
             *
             * @param string The On button value. Default 'on'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off value with one function.
             */
            'value'   => apply_filters( 'wpmdm_import_options_on_off_switch_on_value', 'on', $field_id, 'on' ),
            /**
             * Filter the label of the On button.
             *
             * @since 2.5.0
             *
             * @param string The On button label. Default 'On'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off label with one function.
             */
            'label'   => apply_filters( 'wpmdm_import_options_on_off_switch_on_label', esc_html__( 'On', 'wpmdm-import-options' ), $field_id, 'on' )
          ),
          array(
            /**
             * Filter the value of the Off button.
             *
             * @since 2.5.0
             *
             * @param string The Off button value. Default 'off'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off value with one function.
             */
            'value'   => apply_filters( 'wpmdm_import_options_on_off_switch_off_value', 'off', $field_id, 'off' ),
            /**
             * Filter the label of the Off button.
             *
             * @since 2.5.0
             *
             * @param string The Off button label. Default 'Off'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off label with one function.
             */
            'label'   => apply_filters( 'wpmdm_import_options_on_off_switch_off_label', esc_html__( 'Off', 'wpmdm-import-options' ), $field_id, 'off' )
          )
        );

        /**
         * Filter the width of the On/Off switch.
         *
         * @since 2.5.0
         *
         * @param string The switch width. Default '100px'.
         * @param string $field_id The field ID.
         */
        $switch_width = apply_filters( 'wpmdm_import_options_on_off_switch_width', '100px', $field_id );

        echo '<div class="on-off-switch"' . ( $switch_width != '100px' ? sprintf( ' style="width:%s"', $switch_width ) : '' ) . '>';
        /* build radio */
        foreach ( (array) $field_choices as $key => $choice ) {
          echo '
            <input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="radio wpmdm-import-options-ui-radio ' . esc_attr( $field_class ) . '" />
            <label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" onclick="">' . esc_attr( $choice['label'] ) . '</label>';
        }

          echo '<span class="slide-button"></span>';

        echo '</div>';

      echo '</div>';

    echo '</div>';

  }

}

/**
 * Page Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_page_checkbox' ) ) {
  
  function wpmdm_import_options_type_page_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-page-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

      /* query pages array */
      $my_posts = get_posts( apply_filters( 'wpmdm_import_options_type_page_checkbox_query', array( 'post_type' => array( 'page' ), 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $field_id ) );

      /* has pages */
      if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
        foreach( $my_posts as $my_post ) {
          $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
          echo '<p>';
            echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[$my_post->ID] ) ? checked( $field_value[$my_post->ID], $my_post->ID, false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
            echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . $post_title . '</label>';
          echo '</p>';
        }
      } else {
        echo '<p>' . esc_html__( 'No Pages Found', 'wpmdm-import-options' ) . '</p>';
      }
      
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Page Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_page_select' ) ) {
  
  function wpmdm_import_options_type_page_select( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-page-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build page select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';
        
        /* query pages array */
        $my_posts = get_posts( apply_filters( 'wpmdm_import_options_type_page_select_query', array( 'post_type' => array( 'page' ), 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $field_id ) );
        
        /* has pages */
        if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
          echo '<option value="">-- ' . esc_html__( 'Choose One', 'wpmdm-import-options' ) . ' --</option>';
          foreach( $my_posts as $my_post ) {
            $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
            echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $field_value, $my_post->ID, false ) . '>' . $post_title . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Pages Found', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
        
      echo '</div>';

    echo '</div>';
    
  }
  
}

/**
 * Post Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_post_checkbox' ) ) {
  
  function wpmdm_import_options_type_post_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-post-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* query posts array */
        $my_posts = get_posts( apply_filters( 'wpmdm_import_options_type_post_checkbox_query', array( 'post_type' => array( 'post' ), 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $field_id ) );
        
        /* has posts */
        if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
          foreach( $my_posts as $my_post ) {
            $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
            echo '<p>';
            echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[$my_post->ID] ) ? checked( $field_value[$my_post->ID], $my_post->ID, false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
            echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . $post_title . '</label>';
            echo '</p>';
          } 
        } else {
          echo '<p>' . esc_html__( 'No Posts Found', 'wpmdm-import-options' ) . '</p>';
        }
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Post Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_post_select' ) ) {
  
  function wpmdm_import_options_type_post_select( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-post-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build page select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';
        
        /* query posts array */
        $my_posts = get_posts( apply_filters( 'wpmdm_import_options_type_post_select_query', array( 'post_type' => array( 'post' ), 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'post_status' => 'any' ), $field_id ) );
        
        /* has posts */
        if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
          echo '<option value="">-- ' . esc_html__( 'Choose One', 'wpmdm-import-options' ) . ' --</option>';
          foreach( $my_posts as $my_post ) {
            $post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
            echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $field_value, $my_post->ID, false ) . '>' . $post_title . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Posts Found', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
        
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Radio option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_radio' ) ) {
  
  function wpmdm_import_options_type_radio( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build radio */
        foreach ( (array) $field_choices as $key => $choice ) {
          echo '<p><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="radio wpmdm-import-options-ui-radio ' . esc_attr( $field_class ) . '" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
        }
      
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Radio Images option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_radio_image' ) ) {
  
  function wpmdm_import_options_type_radio_image( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-radio-image ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        
          
        /* build radio image */
        foreach ( (array) $field_choices as $key => $choice ) {
          
          $src = str_replace( 'WPMDM_IMPORT_OPTIONS_URL', WPMDM_IMPORT_OPTIONS_URL, $choice['src'] );
          
          /* make radio image source filterable */
          $src = apply_filters( 'wpmdm_import_options_type_radio_image_src', $src, $field_id );
          
          /**
           * Filter the image attributes.
           *
           * @since 2.5.3
           *
           * @param string $attributes The image attributes.
           * @param string $field_id The field ID.
           * @param array $choice The choice.
           */
          $attributes = apply_filters( 'wpmdm_import_options_type_radio_image_attributes', '', $field_id, $choice );
          
          echo '<div class="wpmdm-import-options-ui-radio-images">';
            echo '<p style="display:none"><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="wpmdm-import-options-ui-radio wpmdm-import-options-ui-images" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
            echo '<img ' . $attributes . ' src="' . esc_url( $src ) . '" alt="' . esc_attr( $choice['label'] ) .'" title="' . esc_attr( $choice['label'] ) .'" class="wpmdm-import-options-ui-radio-image ' . esc_attr( $field_class ) . ( $field_value == $choice['value'] ? ' wpmdm-import-options-ui-radio-image-selected' : '' ) . '" />';
          echo '</div>';
        }
        echo '<div class="wpmdm-import-options-clear"></div>';
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_select' ) ) {
  
  function wpmdm_import_options_type_select( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      

      /* filter choices array */
      $field_choices = apply_filters( 'wpmdm_import_options_type_select_choices', $field_choices, $field_id );
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
        foreach ( (array) $field_choices as $choice ) {
          if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
            echo '<option value="' . esc_attr( $choice['value'] ) . '"' . selected( $field_value, $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
          }
        }
        
        echo '</select>';
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}
/**
 * Select icon type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_select_icon' ) ) {
  
  function wpmdm_import_options_type_select_icon( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;

    $disabled = isset( $disabled ) && $disabled == true ? 'readonly' : '';
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

      echo '<div class="wpmdm-import-options-select-icon-setting">';
      
        /* build text input */
        echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat wpmdm-import-options-select-icon-value wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" ' . esc_attr( $disabled ) . ' />';

        echo '<div class="wpmdm-import-options-select-icon-holder">';

        echo '<span class="wpmdm-import-options-select-icon-result ' . esc_attr( $field_value ) . '"></span>';

        $label = isset( $field_value ) && !empty( $field_value ) ? $field_value : esc_html__('Select icon', 'wpmdm-import-options');

        echo '<a href="javascript:void(0);" class="wpmdm-import-options-select-icon-button">' . esc_attr( $label ) . '</a>';

        echo '</div>';

        $icons_list = wpmdm_import_options_font_awesome_list();

        echo '<div class="wpmdm-import-options-select-icon-container">';


        echo '<div class="wpmdm-import-options-select-icon-content">';

       echo '<a href="javascript:void(0);" class="fa fa-times wpmdm-import-options-select-icon-close"></a>';


        echo '<div class="wpmdm-import-options-select-icon-list">';

        foreach ( (array) $icons_list as $key => $icon ) {

          if ( !isset( $icon, $icon['label'], $icon['value'] ) ) break;

          $active = strpos( $field_value, $icon['value'] ) !== false ? 'active' : '';

          echo '<div class="wpmdm-import-options-select-icon-item">';

              echo '<span class="fa ' . esc_attr( $icon['value'] ) . ' ' . esc_attr( $active ) . '"></span>';

          echo '</div>';
          
        }

         echo '</div>';

        echo '</div>';

        echo '</div>';

        echo '</div>';
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}
/**
 * Select google font option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_google_fonts_select' ) ) {
  
  function wpmdm_import_options_type_google_fonts_select( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* filter choices array */
      $field_choices = wpmdm_import_options_recognized_google_font_families($field_id);
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
        foreach ( (array) $field_choices as $key => $value ) {
          if ( isset( $key ) && isset( $value ) ) {
            echo '<option value="' . esc_attr( $key ) . '"' . selected( $field_value, $key, false ) . '>' . esc_attr( $value  ) . '</option>';
          }
        }
        
        echo '</select>';
        
      echo '</div>';


    echo '</div>';
    
  }
  
}

/**
 * Sidebar Select option type.
 *
 * This option type makes it possible for users to select a WordPress registered sidebar 
 * to use on a specific area. By using the two provided filters, 'wpmdm_import_options_recognized_sidebars', 
 * and 'wpmdm_import_options_recognized_sidebars_{$field_id}' we can be selective about which sidebars are 
 * available on a specific content area.
 *
 * For example, if we create a WordPress theme that provides the ability to change the 
 * Blog Sidebar and we don't want to have the fowpmdm_import_optionser sidebars available on this area, 
 * we can unset those sidebars either manually or by using a regular expression if we 
 * have a common name like naima-fowpmdm_import_optionser-sidebar-$i.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.1
 */
if ( ! function_exists( 'wpmdm_import_options_type_sidebar_select' ) ) {
  
  function wpmdm_import_options_type_sidebar_select( $args = array() ) {
  
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-sidebar-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build page select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';

        /* get the registered sidebars */
        global $wp_registered_sidebars;

        $sidebars = array();
        foreach( $wp_registered_sidebars as $id=>$sidebar ) {
          $sidebars[ $id ] = $sidebar[ 'name' ];
        }

        /* filters to restrict which sidebars are allowed to be selected, for example we can restrict fowpmdm_import_optionser sidebars to be selectable on a blog page */
        $sidebars = apply_filters( 'wpmdm_import_options_recognized_sidebars', $sidebars );
        $sidebars = apply_filters( 'wpmdm_import_options_recognized_sidebars_' . $field_id, $sidebars );

        /* has sidebars */
        if ( count( $sidebars ) ) {
          echo '<option value="">-- ' . esc_html__( 'Choose Sidebar', 'wpmdm-import-options' ) . ' --</option>';
          foreach ( $sidebars as $id => $sidebar ) {
            echo '<option value="' . esc_attr( $id ) . '"' . selected( $field_value, $id, false ) . '>' . esc_attr( $sidebar ) . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Sidebars', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
        
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Spacing Option Type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.5.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_spacing' ) ) {

  function wpmdm_import_options_type_spacing( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-spacing ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';

        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_spacing_fields = apply_filters( 'wpmdm_import_options_recognized_spacing_fields', array(
          'top',
          'right',
          'bottom',
          'left',
          'unit'
        ), $field_id );

        /* build top spacing */
        if ( in_array( 'top', $wpmdm_import_options_recognized_spacing_fields ) ) {

          $top = isset( $field_value['top'] ) ? esc_attr( $field_value['top'] ) : '';

          echo '<div class="wpmdm-import-options-option-group"><span class="wpmdm-import-options-icon-arrow-up wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[top]" id="' . esc_attr( $field_id ) . '-top" value="' . $top . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'top', 'wpmdm-import-options' ) . '" /></div>';

        }

        /* build right spacing */
        if ( in_array( 'right', $wpmdm_import_options_recognized_spacing_fields ) ) {

          $right = isset( $field_value['right'] ) ? esc_attr( $field_value['right'] ) : '';

          echo '<div class="wpmdm-import-options-option-group"><span class="wpmdm-import-options-icon-arrow-right wpmdm-import-options-option-group-icon"></span></span><input type="text" name="' . esc_attr( $field_name ) . '[right]" id="' . esc_attr( $field_id ) . '-right" value="' . $right . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'right', 'wpmdm-import-options' ) . '" /></div>';

        }

        /* build bottom spacing */
        if ( in_array( 'bottom', $wpmdm_import_options_recognized_spacing_fields ) ) {

          $bottom = isset( $field_value['bottom'] ) ? esc_attr( $field_value['bottom'] ) : '';

          echo '<div class="wpmdm-import-options-option-group"><span class="wpmdm-import-options-icon-arrow-down wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[bottom]" id="' . esc_attr( $field_id ) . '-bottom" value="' . $bottom . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'bottom', 'wpmdm-import-options' ) . '" /></div>';

        }

        /* build left spacing */
        if ( in_array( 'left', $wpmdm_import_options_recognized_spacing_fields ) ) {

          $left = isset( $field_value['left'] ) ? esc_attr( $field_value['left'] ) : '';

          echo '<div class="wpmdm-import-options-option-group"><span class="wpmdm-import-options-icon-arrow-left wpmdm-import-options-option-group-icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[left]" id="' . esc_attr( $field_id ) . '-left" value="' . $left . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'left', 'wpmdm-import-options' ) . '" /></div>';

        }

      /* build unit dropdown */
      if ( in_array( 'unit', $wpmdm_import_options_recognized_spacing_fields ) ) {
        
        echo '<div class="wpmdm-import-options-option-group wpmdm-import-options-option-group--is-last">';
        
          echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
  
            echo '<option value="">' . esc_html__( 'unit', 'wpmdm-import-options' ) . '</option>';
  
            foreach ( wpmdm_import_options_recognized_spacing_unit_types( $field_id ) as $unit ) {
              echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
            }
  
          echo '</select>';
        
        echo '</div>';

      }
      
      echo '</div>';

    echo '</div>';

  }

}

/**
 * Tab option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.3.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_tab' ) ) {
  
  function wpmdm_import_options_type_tab( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-tab">';

      echo '<br />';
    
    echo '</div>';
    
  }
  
}

/**
 * Tag Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_tag_checkbox' ) ) {
  
  function wpmdm_import_options_type_tag_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-tag-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* get tags */
        $tags = get_tags( array( 'hide_empty' => false ) );
        
        /* has tags */
        if ( $tags ) {
          foreach( $tags as $tag ) {
            echo '<p>';
              echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $tag->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $tag->term_id ) . '" value="' . esc_attr( $tag->term_id ) . '" ' . ( isset( $field_value[$tag->term_id] ) ? checked( $field_value[$tag->term_id], $tag->term_id, false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
              echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $tag->term_id ) . '">' . esc_attr( $tag->name ) . '</label>';
            echo '</p>';
          } 
        } else {
          echo '<p>' . esc_html__( 'No Tags Found', 'wpmdm-import-options' ) . '</p>';
        }
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Tag Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_tag_select' ) ) {
  
  function wpmdm_import_options_type_tag_select( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-tag-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build tag select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';
        
        /* get tags */
        $tags = get_tags( array( 'hide_empty' => false ) );
        
        /* has tags */
        if ( $tags ) {
          echo '<option value="">-- ' . esc_html__( 'Choose One', 'wpmdm-import-options' ) . ' --</option>';
          foreach( $tags as $tag ) {
            echo '<option value="' . esc_attr( $tag->term_id ) . '"' . selected( $field_value, $tag->term_id, false ) . '>' . esc_attr( $tag->name ) . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Tags Found', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
      
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Taxonomy Checkbox option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_taxonomy_checkbox' ) ) {
  
  function wpmdm_import_options_type_taxonomy_checkbox( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-taxonomy-checkbox wpmdm-import-options-type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        /* setup the taxonomy */
        $taxonomy = isset( $field_taxonomy ) ? explode( ',', $field_taxonomy ) : array( 'category' );
        
        /* get taxonomies */
        $taxonomies = get_categories( apply_filters( 'wpmdm_import_options_type_taxonomy_checkbox_query', array( 'hide_empty' => false, 'taxonomy' => $taxonomy ), $field_id ) );
        
        /* has tags */
        if ( $taxonomies ) {
          foreach( $taxonomies as $taxonomy ) {
            echo '<p>';
              echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $taxonomy->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $taxonomy->term_id ) . '" value="' . esc_attr( $taxonomy->term_id ) . '" ' . ( isset( $field_value[$taxonomy->term_id] ) ? checked( $field_value[$taxonomy->term_id], $taxonomy->term_id, false ) : '' ) . ' class="wpmdm-import-options-ui-checkbox ' . esc_attr( $field_class ) . '" />';
              echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $taxonomy->term_id ) . '">' . esc_attr( $taxonomy->name ) . '</label>';
            echo '</p>';
          } 
        } else {
          echo '<p>' . esc_html__( 'No Taxonomies Found', 'wpmdm-import-options' ) . '</p>';
        }
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Taxonomy Select option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_taxonomy_select' ) ) {
  
  function wpmdm_import_options_type_taxonomy_select( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-tag-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build tag select */
        echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="wpmdm-import-options-ui-select ' . $field_class . '">';
        
        /* setup the taxonomy */
        $taxonomy = isset( $field_taxonomy ) ? explode( ',', $field_taxonomy ) : array( 'category' );
        
        /* get taxonomies */
        $taxonomies = get_categories( apply_filters( 'wpmdm_import_options_type_taxonomy_select_query', array( 'hide_empty' => false, 'taxonomy' => $taxonomy ), $field_id ) );
        
        /* has tags */
        if ( $taxonomies ) {
          echo '<option value="">-- ' . esc_html__( 'Choose One', 'wpmdm-import-options' ) . ' --</option>';
          foreach( $taxonomies as $taxonomy ) {
            echo '<option value="' . esc_attr( $taxonomy->term_id ) . '"' . selected( $field_value, $taxonomy->term_id, false ) . '>' . esc_attr( $taxonomy->name ) . '</option>';
          }
        } else {
          echo '<option value="">' . esc_html__( 'No Taxonomies Found', 'wpmdm-import-options' ) . '</option>';
        }
        
        echo '</select>';
      
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Text option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_text' ) ) {
  
  function wpmdm_import_options_type_text( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;

    $disabled = isset( $disabled ) && $disabled == true ? 'readonly' : '';
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build text input */
        echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat wpmdm-import-options-ui-input ' . esc_attr( $field_class ) . '" ' . esc_attr( $disabled ) . ' />';
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/**
 * Textarea option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_textarea' ) ) {
  
  function wpmdm_import_options_type_textarea( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-textarea ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' fill-area">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build textarea */
        wp_editor( 
          $field_value, 
          esc_attr( $field_id ), 
          array(
            'editor_class'  => esc_attr( $field_class ),
            'wpautop'       => apply_filters( 'wpmdm_import_options_wpautop', false, $field_id ),
            'media_buttons' => apply_filters( 'wpmdm_import_options_media_buttons', true, $field_id ),
            'textarea_name' => esc_attr( $field_name ),
            'textarea_rows' => esc_attr( $field_rows ),
            'tinymce'       => apply_filters( 'wpmdm_import_options_tinymce', true, $field_id ),              
            'quicktags'     => apply_filters( 'wpmdm_import_options_quicktags', array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close' ), $field_id )
          ) 
        );
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}
/**
 * Textarea Simple option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_textarea_simple' ) ) {
  
  function wpmdm_import_options_type_textarea_simple( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-textarea simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
        
        /* filter to allow wpautop */
        $wpautop = apply_filters( 'wpmdm_import_options_wpautop', false, $field_id );
        
        /* wpautop $field_value */
        if ( $wpautop == true ) 
          $field_value = wpautop( $field_value );

        

        /* build textarea simple */
        echo '<textarea class="textarea ' . esc_attr( $field_class ) . '" rows="' . esc_attr( $field_rows )  . '" cols="40" name="' . esc_attr( $field_name ) .'" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</textarea>';


        
      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Textblock option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_textblock' ) ) {
  
  function wpmdm_import_options_type_textblock( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-textblock wide-desc">';
      
      /* description */
      echo '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Textblock Titled option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_textblock_titled' ) ) {
  
  function wpmdm_import_options_type_textblock_titled( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-textblock titled wide-desc">';
      
      /* description */
      echo '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Typography option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_typography' ) ) {
  
  function wpmdm_import_options_type_typography( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-typography ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">'; 

      echo '<div class="wpmdm-import-options-group wpmdm-import-options-typography-group">'; 
        
        /* allow fields to be filtered */
        $wpmdm_import_options_recognized_typography_fields = apply_filters( 'wpmdm_import_options_recognized_typography_fields', array( 
          'font-color',
          'font-family', 
          'font-size', 
          'font-style', 
          'font-variant', 
          'font-weight', 
          'letter-spacing', 
          'line-height', 
          'text-decoration', 
          'text-transform' 
        ), $field_id );

        /* build font color */
        if ( isset( $show_color ) && $show_color != false && in_array( 'font-color', $wpmdm_import_options_recognized_typography_fields ) ) {

          echo '<div class="wpmdm-import-options-group-item">'; 

          /* build colorpicker */  
          echo '<div class="wpmdm-import-options-ui-colorpicker-input-wrap">';

            
            /* set background color */
            $background_color = isset( $field_value['font-color'] ) ? esc_attr( $field_value['font-color'] ) : '';

            /* colorpicker JS */
              echo '<script>jQuery(document).ready(function($) { wpmdm_import_options_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

            
            /* input */
            echo '<input type="text" name="' . esc_attr( $field_name ) . '[font-color]" id="' . esc_attr( $field_id ) . '-picker-' . esc_attr( $type )  . '" value="' . esc_attr( $background_color ) . '" class="wpmdm-import-options-hide-color-picker ' . esc_attr( $field_class ) . '" />';
          
          echo '</div>';

          echo '</div>';
        
        }
        
        /* build font family */
        if ( in_array( 'font-family', $wpmdm_import_options_recognized_typography_fields ) ) {

          echo '<div class="wpmdm-import-options-group-item">'; 

          $font_family = isset( $field_value['font-family'] ) ? $field_value['font-family'] : '';

          echo '<select name="' . esc_attr( $field_name ) . '[font-family]" id="' . esc_attr( $field_id ) . '-font-size" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
            echo '<option value="">font-family</option>';
            foreach( wpmdm_import_options_recognized_font_families( $field_id ) as $option ) { 
              echo '<option value="' . esc_attr( $option ) . '" ' . selected( $font_family, $option, false ) . '>' . esc_attr( $option ) . '</option>';
            }
          echo '</select>';
            
          echo '</select>';

          echo '</div>';
        }
        
        /* build font size */
        if ( in_array( 'font-size', $wpmdm_import_options_recognized_typography_fields ) ) {

          echo '<div class="wpmdm-import-options-group-item">'; 

          $font_size = isset( $field_value['font-size'] ) ? esc_attr( $field_value['font-size'] ) : '';
          echo '<select name="' . esc_attr( $field_name ) . '[font-size]" id="' . esc_attr( $field_id ) . '-font-size" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
            echo '<option value="">font-size</option>';
            foreach( wpmdm_import_options_recognized_font_sizes( $field_id ) as $option ) { 
              echo '<option value="' . esc_attr( $option ) . '" ' . selected( $font_size, $option, false ) . '>' . esc_attr( $option ) . '</option>';
            }
          echo '</select>';

          echo '</div>';
        }
        
        /* build font style */
        if ( in_array( 'font-style', $wpmdm_import_options_recognized_typography_fields ) ) {

          echo '<div class="wpmdm-import-options-group-item">';

          $font_style = isset( $field_value['font-style'] ) ? esc_attr( $field_value['font-style'] ) : '';
          echo '<select name="' . esc_attr( $field_name ) . '[font-style]" id="' . esc_attr( $field_id ) . '-font-style" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
            echo '<option value="">font-style</option>';
            foreach ( wpmdm_import_options_recognized_font_styles( $field_id ) as $key => $value ) {
              echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_style, $key, false ) . '>' . esc_attr( $value ) . '</option>';
            }
          echo '</select>';

          echo '</div>';
        }
        
        
        
        
        /* build font weight */
        if ( in_array( 'font-weight', $wpmdm_import_options_recognized_typography_fields ) ) {

          echo '<div class="wpmdm-import-options-group-item">';

          $font_weight = isset( $field_value['font-weight'] ) ? esc_attr( $field_value['font-weight'] ) : '';
          echo '<select name="' . esc_attr( $field_name ) . '[font-weight]" id="' . esc_attr( $field_id ) . '-font-weight" class="wpmdm-import-options-ui-select ' . esc_attr( $field_class ) . '">';
            echo '<option value="">font-weight</option>';
            foreach ( wpmdm_import_options_recognized_font_weights( $field_id ) as $key => $value ) {
              echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_weight, $key, false ) . '>' . esc_attr( $value ) . '</option>';
            }
          echo '</select>';

          echo '</div>';
        }

        echo '</div>';

        echo '</div>';

        echo '</div>';

      echo '</div>';
      
    echo '</div>';
    
  }
  
}

/**
 * Upload option type.
 *
 * See @wpmdm_import_options_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_type_upload' ) ) {
  
  function wpmdm_import_options_type_upload( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* If an attachment ID is stored here fetch its URL and replace the value */
    if ( $field_value && wp_attachment_is_image( $field_value ) ) {
    
      $attachment_data = wp_get_attachment_image_src( $field_value, 'original' );
      
      /* check for attachment data */
      if ( $attachment_data ) {
      
        $field_src = $attachment_data[0];
        
      }
      
    }
    
    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-upload ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="wpmdm-import-options-format-setting-inner" id="wpmdm-import-options-format-setting-' . esc_attr( $field_id ) . '">';
      
        /* build upload */
        echo '<div class="wpmdm-import-options-ui-upload-parent">';
          
          /* input */
          echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat wpmdm-import-options-ui-upload-input ' . esc_attr( $field_class ) . '" />';
          
          /* add media button */
          echo '<a href="javascript:void(0);" class="wpmdm_import_options_upload_media wpmdm-import-options-ui-button button button-primary light" rel="' . $post_id . '" title="' . esc_html__( 'Add Media', 'wpmdm-import-options' ) . '"><span class="fa fa-plus-circle"></span>' . esc_html__( 'Add Media', 'wpmdm-import-options' ) . '</a>';
        
        echo '</div>';
        
        /* media */
        if ( $field_value ) {
            
          echo '<div class="wpmdm-import-options-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';
            
            /* replace image src */
            if ( isset( $field_src ) )
              $field_value = $field_src;
              
            if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value ) )
              echo '<div class="wpmdm-import-options-ui-image-wrap"><img src="' . esc_url( $field_value ) . '" alt="" /></div>';
            
            echo '<a href="javascript:(void);" class="wpmdm-import-options-ui-remove-media wpmdm-import-options-ui-button button button-secondary light" title="' . esc_html__( 'Remove Media', 'wpmdm-import-options' ) . '"><span class="fa fa-trash"></span>' . esc_html__( 'Remove Media', 'wpmdm-import-options' ) . '</a>';
            
          echo '</div>';
          
        }
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

if( !function_exists('wpmdm_import_options_type_message')) {
  
  function wpmdm_import_options_type_message( $args = array() ) {

    extract($args);

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="wpmdm-import-options-format-setting wpmdm-import-options-type-message ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
    /* description */
      echo  isset( $has_desc ) && !empty( $has_desc ) ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      if( isset( $message ) ) {

        echo '<div class="wpmdm-import-options-infos-message">';

        echo htmlspecialchars_decode( esc_html( $message ) ); 

        echo '</div>';

      }

    echo '</div>';

  }

}


/* End of file wpmdm-import-options-functions-option-types.php */
/* Location: ./includes/wpmdm-import-options-functions-option-types.php */
