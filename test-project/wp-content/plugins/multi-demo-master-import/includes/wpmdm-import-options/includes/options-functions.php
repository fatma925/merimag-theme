<?php if ( ! defined( 'WPMDM_IMPORT_OPTIONS_VERSION' ) ) exit( 'No direct script access allowed' );
/**
 * WPmdpmOptions functions
 *
 * @package   WPmdpmOptions
 * @author    Merrasse Mouhcine <merrasse@wpmdm.net>
 * @copyright Copyright (c) 2016, Merrasse Mouhcine
 * @since     1.0
 */




/**
 * Get Option.
 *
 * Helper function to return the option value.
 * If no value has been saved, it returns $default.
 *
 * @param     string    The option ID.
 * @param     string    The default option value.
 * @return    mixed
 *
 * @access    public
 * @since     1.0
 */
if ( ! function_exists( 'wpmdm_import_options_get_option' ) ) {

  function wpmdm_import_options_get_option( $settings_id, $option_id, $default = '' ) {
    
    /* get the saved options */ 
    $options = get_option( $settings_id );

    /* look for the saved value */
    if ( isset( $options[$option_id] ) && '' != $options[$option_id] ) {
        
      return $options[$option_id];
      
    }
    
    return $default;
    
  }
  
}

/**
 * Echo Option.
 *
 * Helper function to echo the option value.
 * If no value has been saved, it echos $default.
 *
 * @param     string    The option ID.
 * @param     string    The default option value.
 * @return    mixed
 *
 * @access    public
 * @since     2.2.0
 */
if ( ! function_exists( 'wpmdm_import_options_echo_option' ) ) {
  
  function wpmdm_import_options_echo_option( $settings_id, $option_id, $default = '' ) {
    
    echo wpmdm_import_options_get_option( $settings_id, $option_id, $default );
  
  }
  
}




/* End of file wpmdm-import-options-functions.php */
/* Location: ./includes/wpmdm-import-options-functions.php */