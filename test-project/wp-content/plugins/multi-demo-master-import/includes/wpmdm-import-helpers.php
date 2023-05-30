<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Helper function to return plugin settings array()
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if( ! function_exists( 'wpmdm_import_settings_array') ) {

    function wpmdm_import_settings_array()
    {

        $settings = array( 
            array(
                'id' => 'wpmdm-import',
                'pages' => array(
                    array(
                        'id' => 'wpmdm-import',
                        'big_sub_header' => true,
                        'sub_header_icon' => 'fa fa-download',
                        'parent_slug' => 'theme-page',
                        'under' => 'submenu',
                        'page_title' => esc_html__('Multi Demo Import', 'wpmdm'),
                        'menu_title' => esc_html__('Import Demos', 'wpmdm'),
                        'capability' => 'edit_theme_options',
                        'menu_slug' => 'wpmdm-import',
                        'position' => null,
                        'show_buttons' => false,
                        'screen_icon' => 'options-general',
                        'contextual_help' => null,
                        'sections' => array(
                            array(
                                'id' => 'import',
                                'title' => esc_html__('Export Demos', 'wpmdm'),
                                'icon' => 'fa-wrench'
                            ),    
                        ),
                        'settings' => array(
                            array(
                                'id' => 'wpmdm-import-demos',
                                'label' => esc_html__('Import Demos', 'wpmdm'),
                                'desc' => '',
                                'std' => '',
                                'section' => 'import',
                                'type' => 'ajax-action-import',
                                'button_title' => esc_html__('Import Demos', 'wpmdm'),
                                'class' => 'wpmdm-demos-import',
                                'function' => 'wpmdm_import_import_demos',
                                'options_type' => 'checkbox',
                                'messages' => array( 
                                    'progress' => esc_html__('Import in progress!', 'wpmdm'),
                                    'success' => esc_html__('Import Completed!', 'wpmdm'),
                                    'error' => esc_html__('Import failed!', 'wpmdm'),
                                ),
                            ),
                            array(
                                'id' => 'wpmdm-import-pages',
                                'label' => esc_html__('Import home pages', 'wpmdm'),
                                'desc' => '',
                                'std' => '',
                                'section' => 'import',
                                'type' => 'ajax-action',
                                'button_title' => esc_html__('Import home pages as draft', 'wpmdm'),
                                'class' => 'wpmdm-pages-import',
                                'function' => 'wpmdm_import_import_pages',
                                'options_type' => 'checkbox',
                                'messages' => array( 
                                    'progress' => esc_html__('Import in progress!', 'wpmdm'),
                                    'success' => esc_html__('Import Completed!', 'wpmdm'),
                                    'error' => esc_html__('Import failed!', 'wpmdm'),
                                ),
                            ),
                        )
                    )
                    
                )
            ),
        );

        return $settings;
    }

}

/**
 * Helper function to check if keys exists inside an array
 *
 * @return    bool
 *
 * @access    public
 * @since     1.0.0
 */
if( ! function_exists( 'wpmdm_import_array_keys_exists') ) {

    function wpmdm_import_array_keys_exists( $keys, $array ) {


        if( !is_array( $array ) || !is_array( $keys ) ) {
            echo 'not array';
            return;
        }

        $exists = true;

        foreach( $keys as $key ) {

            if( !array_key_exists( $key, $array ) ) {


                $exists = false;

                break;

            }
        }

        return $exists;

    }

}



/**
 * Helper function to return encoded strings
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */

if( ! function_exists( 'wpmdm_import_encode') ) {

    function wpmdm_import_encode( $value ) {

      $func = 'base64' . '_encode';
      return $func( $value );
      
    }

}


/**
 * Helper function to return decoded strings
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */

if( ! function_exists( 'wpmdm_import_decode') ) {

    function wpmdm_import_decode( $value ) {

      $func = 'base64' . '_decode';
      return $func( $value );
      
    }

}
/**
 * Helper function to add import form
 *
 * @return    void
 *
 * @access    public
 * @since     2.0.9
 */
function wpmdm_import_retreive_body( $url ) {
    $get = wp_remote_get( $url, array('timeout' => 240) );
    $body = wp_remote_retrieve_body( $get );
    return $body;
}
/**
 * Helper function to add import form
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if( ! function_exists( 'wpmdm_import_options_type_ajax_action_import' ) ) {

    function wpmdm_import_options_type_ajax_action_import($args = array()) {
        $theme       = wp_get_theme();

        $dir = get_template_directory_uri() . '/demos';
        


        $url = get_template_directory_uri() . '/demos';
        if( $dir  ) {

            echo '<h2 style="color:red; font-weight:bold; font-size: 20px;">Please note : all posts and data generated with this tool will be deleted and regenarated on every import, if you import a demo, than imported new one the old import data will be deleted and new data will be generated.</h2><br><br>';


            $demos_infos = array();

            $content_infos = array();

            $demos_infos = wpmdm_import_retreive_body( $dir . '/demos-infos.dat' );

            if( $demos_infos ) {

                $demos_infos = json_decode(  $demos_infos, true );

            }

            $content_infos = wpmdm_import_retreive_body( $dir . '/content-infos.dat' );

            if( $content_infos  ) {

                $content_infos = json_decode(  $content_infos, true );

            }

            if( is_array( $demos_infos ) && is_array( $content_infos ) && !empty( $demos_infos )  && !empty( $content_infos ) ) {


                $demos_infos_keys = array( 'title', 'path', 'settings', 'preview', 'link' );

                $content_infos_keys = array( 'type', 'name', 'path');

                echo '<div class="wpmdm-import-demos-container">';

                $count = 1;

                foreach ( $demos_infos as $key => $demo ) {

                    echo '<div class="wpmdm-import-demo-item">';

                    echo '<div class="wpmdm-import-demo-item-content">';

                    if( wpmdm_import_array_keys_exists( $demos_infos_keys, $demo ) ) {

                        echo '<h3>' . esc_attr( $demo['title'] ) .'</h3>';

                        $path = $dir . '/' . $demo['path'] . '/';

                        $preview_url  = $url . '/' . $demo['path'] . '/' . $demo['preview'];

                        $preview_path = $dir . '/' . $demo['path'] . '/' . $demo['preview'];

                        if( isset( $demo['preview'] ) && !empty( $demo['preview'] ) &&  strpos( $demo['preview'], '.jpg') !== false ) {

                            echo '<div class="wpmdm-import-preview">';

                                echo '<a target="_blank" href="' . esc_url( $demo['link'] ) . '">' . esc_html__( 'Live Preview', 'wpmdm-import' ) . '</a>';

                                echo '<img src="' . esc_url( $preview_url ) .'" />'; 

                            echo '</div>';

                        } else {

                            echo '<div class="wpmdm-import-no-preview wpmdm-import-preview">';

                                echo '<a target="_blank" href="' . esc_url( $demo['link'] ) . '">' . esc_html__( 'Live Preview', 'wpmdm-import' ) . '</a>';

                                echo '<span>' . esc_html__( 'No preview available', 'wpmdm-import' ) . '</span>';

                                echo '<img src="' . esc_url( plugins_url('assets/images/no-preview.png', dirname(__FILE__)) ) .'" />'; 

                            echo '</div>'; 

                        }

                        foreach( $content_infos as $content ) {

                            if( is_file( $dir . '/' . $content['path'] ) && post_type_exists( $content['type'] ) && $content['type'] !== 'attachment' ) {

                                $import_types[ $content['type'] ] = $content['name'];
 
                            }

                        }
                        $import_types['content' ]  = 'Content';

                        $import_types['settings']  = 'Settings and style';

                        $import_types['home_page'] = 'Home Page';

                        echo '<div class="wpmdm-import-form" id="wpmdm-import-form-' . esc_attr( $count ) . '" class=" wpmdm-import-options-import-checkboxes">';

                        echo '<div class="wpmdm-import-types-container">';

                        foreach( $import_types as $key => $type ) {

                            echo '<div class="wpmdm-import-options-format-setting  wpmdm-import-options-type-checkbox">';

                            echo '<div class="wpmdm-import-options-format-setting-inner">';

                                echo '<p>';

                                    echo '<input class="wpmdm-import-options-ui-checkbox" id="wpmdm-import-checkbox-' . esc_attr( $count ) . '-' . esc_attr( $key ) . '" name="wpmdm-import-types[]" value="' . esc_attr( $key ) . '" type="checkbox">';

                                    echo '<label for="wpmdm-import-checkbox-' . esc_attr( $count ) . '-' . esc_attr( $key ) . '">' . esc_attr( $type ) . '</label>';

                                echo '</p>';


                            echo '</div>';

                            echo '</div>';

                        }

                        echo '</div>';


                        $messages = array(
                            'progress' => esc_html__( 'Import in progress', 'wpmdm-import'),
                            'success'  => esc_html__( 'Demo successfully imported', 'wpmdm-import'),
                            'error'    => esc_html__( 'An error was occurred when trying to import demo'),
                            'empty' => esc_html__( 'You must select at least one thing to import', 'wpmdm-import'),
                        );

                        $messages = wpmdm_import_messages_to_data_string( $messages );


                        echo '<a ' . htmlspecialchars_decode ( esc_attr( $messages ) ) . ' data-id="' . esc_attr( $count ) . '" href="javascript:void(0);" class="wpmdm-import-button wpmdm-options-ui-button button-hero button button-primary" title="">' . esc_html__('Import Demo', 'wpmdm-import')  . '</a>';

                        echo '<div class="wpmdm-import-status"></div>';

                        echo '</div>'; # close import form

                    } else {

                        echo '<div class="wpmdm-import-options-type-message wpmdm-import-options-type-vertical-message ">';

                            echo '<div class="wpmdm-import-options-infos-message">';

                                echo esc_html__('Not a valid demos infos', 'wpmdm-import' );

                            echo '</div>';

                        echo '</div>';

                    }

                    echo '</div>';

                    echo '</div>';

                    $count++;

                }

                echo '</div>';

            } else {

                echo '<div class="wpmdm-import-options-type-message ">';

                    echo '<div class="wpmdm-import-options-infos-message">';

                        echo esc_html__('No valid demos found', 'wpmdm-import' );

                    echo '</div>';

                echo '</div>';
            }

            
            
        } else {

            echo '<div class="wpmdm-import-options-type-message ">';

                echo '<div class="wpmdm-import-options-infos-message">';

                    echo esc_html__('No demos found for this theme', 'wpmdm-import' );

                echo '</div>';

            echo '</div>';


        }
        
    }
}
add_action( 'wp_ajax_wpmdm_import_import_pages','wpmdm_import_import_pages' );

function wpmdm_import_import_pages() {
         $theme       = wp_get_theme();

        


        $url = get_template_directory_uri() . '/demos';

         $dir = get_template_directory_uri() . '/demos';

        if( $dir  ) {


            $demos_infos = array();

            $content_infos = array();

            $demos_infos = wpmdm_import_retreive_body( $dir . '/demos-infos.dat' );

            if( $demos_infos ) {

                $demos_infos = json_decode(  $demos_infos, true );

            }

            $content_infos = wpmdm_import_retreive_body( $dir . '/content-infos.dat' );

            if( $content_infos ) {

                $content_infos = json_decode(  $content_infos, true );

            }

            if( is_array( $demos_infos ) && is_array( $content_infos ) && !empty( $demos_infos )  && !empty( $content_infos ) ) {


                $demos_infos_keys = array( 'title', 'path', 'settings', 'preview', 'link' );

                $content_infos_keys = array( 'type', 'name', 'path');


                $count = 1;

                foreach ( $demos_infos as $key => $demo ) {
                    if( wpmdm_import_array_keys_exists( $demos_infos_keys, $demo ) ) {
                        $path = $dir . '/' . $demo['path'] . '/';

                        $home_page_file = $path . 'home.dat';

                        $home_page_file = wpmdm_import_retreive_body( $home_page_file );

                        if( $home_page_file ) {

                            $home_page_data = json_decode( $home_page_file, true );

                            $home_page_content = isset( $home_page_data['content'] ) ? $home_page_data['content'] : false;
                            $home_page_title = isset( $home_page_data['title'] ) ? $home_page_data['title'] : false;
                            $home_page_meta = isset( $home_page_data['meta'] ) ? $home_page_data['meta'] : false;

                            if( $home_page_content && $home_page_title && $home_page_meta ) {
                                if( post_exists($home_page_title) ) {
                                    echo esc_attr($home_page_title ) . __(' already exists', 'wpmdm-import') . '<br/>';
                                    continue;
                                }
                                foreach( $home_page_meta as $meta_key => $meta_value ) {
                                    if( isset( $home_page_meta[$meta_key][0] ) && count( $home_page_meta[$meta_key] ) === 1 ) {
                                        $home_page_meta[$meta_key] = $home_page_meta[$meta_key][0];
                                    }
                                }
                                $home_page_created = wp_insert_post( array('post_content' => $home_page_content, 'meta_input' => $home_page_meta, 'post_title' => $home_page_title, 'post_type' => 'page', 'post_status' => 'draft' ) );

                                update_post_meta( $home_page_created, 'wpmdm_demo_post', true );

                            }
   

                        }
                    }
                }
            }
        }
        wp_die();
}
/**
 * Process import
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if( ! function_exists( 'wpmdm_import_ajax_import' ) ) {

    function wpmdm_import_ajax_import() {

        check_ajax_referer( 'wpmdm_import', 'nonce' );

        if( !defined('FW') ) {

            $messages[] = array(
                'message' => esc_html__('Please install all required plugins before importing demos' , 'wpmdm-import'),
                'status' => false,
            );

            wpmdm_import_status( $messages );

            wp_die();
        }

        parse_str( $_REQUEST['import_types'], $import_types );

        $id = $_REQUEST['id'];

        $import_types = $import_types['wpmdm-import-types'];

        $messages = array();

        $theme       = wp_get_theme();


        $settings_file = get_template_directory_uri() . '/demos/demo' . $id . '/settings.dat';

        $settings = wpmdm_import_retreive_body( $settings_file );

        if( $settings ) {

            $settings = json_decode( $settings, true );

        }

         
        foreach( $import_types as $type ) {

            if( $type == 'content' ) {

                $îmage_packs = wpmdm_import_get_recognized_image_packs( true );

                $image_pack = isset( $settings ) && isset( $settings['image_pack'] ) && in_array( $settings['image_pack'], $îmage_packs ) ? $settings['image_pack'] : 'general';

                wpmdm_import_delete_demos_posts();

                $content_imported = wpmdm_import_generate_content( $image_pack );

                $messages[] = array(
                    'message' => esc_html__('Content imported' , 'wpmdm-import'),
                    'status' => $content_imported,
                ); 
                

                # menus

                $menus_file = get_template_directory_uri() . '/demos/menus.dat';

                $menus_file = wpmdm_import_retreive_body( $menus_file );

                $menus_data = json_decode($menus_file, true);

                $menus_pages = get_template_directory_uri() . '/demos/menus-pages.dat';

                $menus_pages = wpmdm_import_retreive_body( $menus_pages );

                $menus_pages = json_decode($menus_pages, true );

                if( is_array($menus_data) ) {

                    foreach( $menus_data as $menu_slug => $menu_items ) {
                        $menu = wp_get_nav_menu_object( $menu_slug );
                        if( !is_object($menu) ) {
                            $menu_id = wp_create_nav_menu( str_replace('-', ' ', $menu_slug) );
                            $item_ids = array();
                            foreach( $menu_items as $menu_item ) {
                                $menu_page_created = false;
                                if( $menu_item['object'] == 'page' ) {
                                    $import_page_id = $menu_item['object_id'];
                                    if( isset( $menus_pages[$import_page_id] ) ) {
                                        $menu_page_data = $menus_pages[$import_page_id];
                                        $menu_page_content = isset( $menu_page_data['content'] ) ? $menu_page_data['content'] : false;
                                        $menu_page_title = isset( $menu_page_data['title'] ) ? $menu_page_data['title'] : false;
                                        $menu_page_meta = isset( $menu_page_data['meta'] ) ? $menu_page_data['meta'] : false;

                                        
                                        if( $menu_page_title && $menu_page_meta ) {
                                            foreach( $menu_page_meta as $meta_key => $meta_value ) {
                                                if( isset( $menu_page_meta[$meta_key][0] ) && count( $menu_page_meta[$meta_key] ) === 1 ) {
                                                    $menu_page_meta[$meta_key] = $menu_page_meta[$meta_key][0];
                                                    if( $meta_key == '_elementor_data') {
                                                        $menu_page_meta[$meta_key] = wp_slash( $menu_page_meta[$meta_key] );
                                                    }
                                                    if( $meta_key == '_elementor_page_settings' ) {
                                                        $menu_page_meta[$meta_key] = unserialize( $menu_page_meta[$meta_key] );
                                                    }
                                                    if( $meta_key == '_elementor_page_assets') {
                                                        $menu_page_meta[$meta_key] = array();
                                                    }
                                                }
                                            }
                                            $page = get_page_by_title( $menu_page_title );

                                            if( !isset( $page->ID ) || is_null( get_post_status( $page->ID ) ) ) {
                                                $menu_page_created = wp_insert_post( array('post_content' => $menu_page_content, 'meta_input' => $menu_page_meta, 'post_title' => $menu_page_title, 'post_type' => 'page', 'post_status' => 'publish' ) );
                                            } else {
                                                $menu_page_created = $page->ID;
                                            }
                                            
                                            if(isset( $menu_page_meta['fw_options'] ) && function_exists('fw_set_db_post_option') ) {
                                                $fw_data = json_decode($menu_page_meta['fw_options']);
                                                if( is_array($fw_data ) ) {
                                                    foreach( $fw_data as $fw_option => $fw_value ) {
                                                        fw_set_db_post_option( $menu_page_created , $fw_option, $fw_value );
                                                    }
                                                }
                                            }

                                            update_post_meta( $menu_page_created, 'wpmdm_demo_post', true );

                                        }
                                    }
                                }

                                if( !$menu_page_created ) {
                                     $item_ids[$menu_item['ID']] = wp_update_nav_menu_item($menu_id, 0, array(
                                        'menu-item-title' =>  $menu_item['title'],
                                        'menu-item-url' => '#', 
                                        'menu-item-status' => 'publish',
                                        'menu-item-type' => 'custom',
                                        )
                                    );    
                                } else {
                                    $item_ids[$menu_item['ID']] = wp_update_nav_menu_item($menu_id, 0, array(
                                        'menu-item-title' =>  $menu_item['title'],
                                        'menu-item-status' => 'publish',
                                        'menu-item-object-id' => isset( $menu_page_created ) ? $menu_page_created : false,
                                        'menu-item-object' => isset( $menu_page_created ) ? 'page' : 'custom',
                                        'menu-item-type' => isset( $menu_page_created ) ? 'post_type' : 'custom',
                                        )
                                    );
                                }

                                
                                $meta_data = isset( $menu_item['meta_data'] ) && is_array($menu_item['meta_data'] ) ? $menu_item['meta_data'] : array();
                                $parent = $menu_item['menu_item_parent'];
                                if( isset($item_ids[$parent] ) ) {
                                    update_post_meta( $item_ids[$menu_item['ID']], '_menu_item_menu_item_parent', $item_ids[$parent] );
                                }
                                
                                foreach( $meta_data as $item_meta_key => $item_meta_val ) {
                                    if( $item_meta_key == 'mega-menu' || strpos($item_meta_key, 'fw') !== false ) {
                                        $item_meta_val = isset( $item_meta_val[0] ) ? $item_meta_val[0] : $item_meta_val;
                                        $item_meta_val = maybe_unserialize($item_meta_val);
                                        update_post_meta( $item_ids[$menu_item['ID']], $item_meta_key, $item_meta_val );
                                    }
                                    
                                }
                            }
                        }
                        
                    }
                }

            } elseif( $type == 'settings' ) {

                $settings_file = get_template_directory() . '/demos/demo' . $id . '/settings.dat';

                if( isset( $settings ) && is_array( $settings ) ) {
                    
                    $import_settings = wpmdm_import_settings( $settings );

                    $messages[] = array(
                        'message' => esc_html__('Settings imported' , 'wpmdm-import'),
                        'status' => $import_settings,
                    );

                } else {

                    $messages[] = array(
                        'message' => esc_html__('Settings file does not exist for this demo' , 'wpmdm-import'),
                        'status' => false,
                    );

                }

            } elseif( $type == 'widgets' ) {

                $settings = wpmdm_import_retreive_body(get_template_directory_uri() . '/demos/demo' . $id . '/settings.dat');

                if( $settings ) {


                    $settings = json_decode( $settings, true  );

                    $widgets  = isset( $settings['widgets'] ) && is_array( $settings['widgets'] ) ? (object) $settings['widgets'] : false;

                    if( empty( $widgets ) || !is_object( $widgets ) ) {

                        $messages[] = array(
                            'message' => esc_html__('No widgets to import for this demo' , 'wpmdm-import'),
                            'status' => false,
                        );

                    } else {

                        $import_widgets = wpmdm_import_widgets( $widgets );

                        $messages[] = array(
                            'message' => esc_html__('Widgets imported' , 'wpmdm-import'),
                            'status' => $import_widgets,
                        );

                    }


                } else {

                    $messages[] = array(
                        'message' => esc_html__('Settings file does not exist for this demo' , 'wpmdm-import'),
                        'status' => false,
                    );

                }

            } elseif( $type == 'home_page') {

                $home_page_file = wpmdm_import_retreive_body(get_template_directory_uri(). '/demos/demo' . $id . '/home.dat');

                if( $home_page_file ) {


                    $home_page_data = json_decode( $home_page_file, true );

                    $home_page_content = isset( $home_page_data['content'] ) ? $home_page_data['content'] : false;
                   
                    $home_page_title = isset( $home_page_data['title'] ) ? $home_page_data['title'] : false;
                    $home_page_meta = isset( $home_page_data['meta'] ) ? $home_page_data['meta'] : false;

                    if( $home_page_content && $home_page_title && $home_page_meta ) {
                        foreach( $home_page_meta as $meta_key => $meta_value ) {
                            if( isset( $home_page_meta[$meta_key][0] ) && count( $home_page_meta[$meta_key] ) === 1 ) {
                                $home_page_meta[$meta_key] = $home_page_meta[$meta_key][0];
                                if( $meta_key == '_elementor_data') {
                                    $home_page_meta[$meta_key] = wp_slash( $home_page_meta[$meta_key] );
                                }
                                if( $meta_key == '_elementor_page_settings' ) {
                                    $home_page_meta[$meta_key] = unserialize( $home_page_meta[$meta_key] );
                                }
                                if( $meta_key == '_elementor_page_assets') {
                                    $home_page_meta[$meta_key] = array();
                                }
                            }
                        }
                        $page = get_page_by_title( $home_page_title );

                        if( !isset( $page->ID ) || is_null( get_post_status( $page->ID ) ) ) {
                            $home_page_created = wp_insert_post( array('post_content' => $home_page_content, 'meta_input' => $home_page_meta, 'post_title' => $home_page_title, 'post_type' => 'page', 'post_status' => 'publish' ) );
                        } else {
                            $home_page_created = $page->ID;
                        }
                        

                        if(isset( $home_page_meta['fw_options'] ) && function_exists('fw_set_db_post_option') ) {
                            $fw_data = json_decode($home_page_meta['fw_options']);
                            if( is_array($fw_data ) ) {
                                foreach( $fw_data as $fw_option => $fw_value ) {
                                    fw_set_db_post_option( $home_page_created , $fw_option, $fw_value );
                                }
                            }
                        }

                        update_post_meta( $home_page_created, 'wpmdm_demo_post', true );

                    }
                    
                    $messages[] = array(
                        'message' => esc_html__('Home page content imported' , 'wpmdm-import'),
                        'status' => $home_page_created,
                    );

                } else {
                    $messages[] = array(
                        'message' => esc_html__('Home page content imported' , 'wpmdm-import'),
                        'status' => $home_page_created,
                    );
                }

                $settings = wpmdm_import_retreive_body(get_template_directory_uri() . '/demos/demo' . $id . '/settings.dat');

                if( $settings ) {


                    $settings = json_decode( $settings, true );

                    $home_page = isset( $settings['home_page'] ) && !empty( $settings['home_page'] ) ? $settings['home_page'] : false;

                    if( false != $home_page ) {

                        $page = get_page_by_title( $home_page );

                        if( !is_null( get_post_status( $page->ID ) ) ) {

                            update_option( 'show_on_front' , 'page' );
                            update_option( 'page_on_front', $page->ID );

                            $messages[] = array(
                                'message' => esc_html__('Home page imported' , 'wpmdm-import'),
                                'status' => true,
                            );

                        } else {

                            $messages[] = array(
                                'message' => esc_html__('Home page not found' , 'wpmdm-import'),
                                'status' => false,
                            );
                        }

                    } else {

                        $messages[] = array(
                            'message' => esc_html__('No custom home page for this demo' , 'wpmdm-import'),
                            'status' => false,
                        );
                        
                    }

                }


            }

        } # end foreach

        wpmdm_import_status( $messages );

        wp_die();
        
    }

}
function wpmdm_import_get_recognized_image_packs( $keys = false, $label_value = false ) {
    $themes = array(
        'adventure' => __('Adventure', 'wpmdm'),
        'business'  => __('Business', 'wpmdm'),
        'cars'      => __('Cars', 'wpmdm'),
        'celebrity' => __('celebrity', 'wpmdm'),
        'classic'   => __('Classic', 'wpmdm'),
        'crypto'    => __('Crypto', 'wpmdm'),
        'dark'      => __('Dark / Gothic', 'wpmdm'),
        'fashion'   => __('Fashion', 'wpmdm'),
        'finance'   => __('Finance', 'wpmdm'),
        'food'      => __('Food', 'wpmdm'),
        'gaming'    => __('Gaming', 'wpmdm'),
        'general'   => __('General', 'wpmdm'),
        'girly'     => __('Girly', 'wpmdm'),
        'health'    => __('Health', 'wpmdm'),
        'home'      => __('Home', 'wpmdm'),
        'life'      => __('Life', 'wpmdm'),
        'luxe'      => __('Luxe', 'wpmdm'),
        'music'     => __('Music', 'wpmdm'),
        'news'      => __('News', 'wpmdm'),
        'pets'      => __('Pets', 'wpmdm'),
        'people'    => __('People', 'wpmdm'),
        'sport'     => __('Sport', 'wpmdm'),
        'tech'      => __('Tech', 'wpmdm'),
        'travel'    => __('Travel', 'wpmdm'),
        'funny'     => __('Funny', 'wpmdm')
    );
    if( $label_value === true ) {
        foreach ($themes as $key => $value) {
            $return[] = array('label' => $value, 'value' => $key );
        }
        return $return;
    }
    return $keys === true ? array_keys( $themes ) : $themes;
}
function wpmdm_import_random_date($start_date = '01-01-2015', $end_date = '15-02-2018')
{
    // Convert to timetamps
    $min = strtotime($start_date);
    $max = strtotime($end_date);

    // Generate random number using above bounds
    $val = rand($min, $max);

    // Convert back to desired date format
    return date('Y-m-d H:i:s', $val);
}
function wpmdm_import_get_demo_post_title( $demo_key, $post_type = 'post' ) {
    $demo_key = $demo_key - 1;
    $demo = 'default';
    $direction = is_rtl() ? 'rtl' : 'ltr';
    $titles = array(
        'default' => array(
            'ltr' => array(
                'post' => array(
                    'Hope is like the sun, which, as we journey toward it, casts the shadow of our burden behind us',
                    'Inside each of us, there is the seed of both good and evil. It\'s a constant struggle',
                    'Music, at its essence, is what gives us memories. And the longer a song has existed',
                    'The moments of happiness we enjoy take us by surprise. It is not that we seize them',
                    'Each friend represents a world in us, a world not born until they arrive',
                    'Times may have changed, but there are some things that are always with us',
                    'Imagination will often carry us to worlds that never were, But without',
                    'We are what our thoughts have made us, so take care about what you think',
                    'As long as poverty, injustice and gross inequality persist in our world',
                    'What we have once enjoyed we can never lose. All that we love deeply becomes a part of us',
                    'Monsters are real, and ghosts are real too. They live inside us, and sometimes, they win',
                    'What lies behind us and what lies ahead of us are tiny matters compared to what lives within us',
                    'There are big problems that change the world. If we are working together',
                    'Living with fear stops us taking risks, and if you don\'t go out on the branch',
                    'Life is a gift, and it offers us the privilege, opportunity, and responsibility to give something back',
                    'This is the power of gathering: it inspires us, delightfully, to be more hopeful',
                    'Let us not forget that the cultivation of the earth is the most important labor of man',
                    'Future generations are not going to ask us what political party were you in',
                    'We are part of this universe; we are in this universe, but perhaps more important than both',
                    'Life is not a solo act. It\'s a huge collaboration, and we all need to assemble around',
                    'The ache for home lives in all of us, the safe place where we can go as we are and not be questioned',
                    'When we long for life without difficulties, remind us that oaks grow strong in',
                    'It is important for all of us to appreciate where we come from and how that history has',
                    'Our incomes are like our shoes; if too small, they gall and pinch us; but if too large',
                    'The highest education is that which does not merely give us information but makes our life',
                    'Every great and deep difficulty bears in itself its own solution. It forces us to change',
                    'I believe that through knowledge and discipline, financial peace is possible for all of us',
                    'Technological progress has merely provided us with more efficient means for going backwards',
                    'There are no morals in politics; there is only expedience. A scoundrel may be of use to us',
                    'The secret of success is to be in harmony with existence, to be always calm to let each wave',
                    'The new year stands before us, like a chapter in a book, waiting to be written',
                    'There\'s a little bit of pain in every transition, but we can\'t let that stop us from making it',
                    'The most authentic thing about us is our capacity to create, to overcome, to endure',
                    'Life doesn\'t make any sense without interdependence. We need each other',
                    'Great theatre is about challenging how we think and encouraging us to fantasize about a world',
                    'The industrial revolution allowed us, for the first time, to start replacing human',
                    'True wisdom comes to each of us when we realize how little we understand about life',
                    'The Untapped Gold Mine Of That Virtually No One Knows About',
                    'Why You Never See That Actually Works like a chapter in a book',
                    '7 Ways To Keep Your Growing Without Burning The Midnight Oil',
                    'How To Find The Right For Your Specific Product(Service).',
                    'You Can Thank Us Later - 3 Reasons To Stop Thinking About',
                    'The Untold Secret To Mastering In Just 3 Days they gall and pinch us; but if too large',
                    'Is Bound To Make An Impact In Your Business with more efficient means for going backwards',
                    '3 Ways Create Better With The Help Of Your Dog and we all need to assemble around',
                    'Believe In Your Skills But Never Stop Improving It forces us to change',
                    'What Alberto Savoia Can Teach You About to be always calm to let each wave',
                    'I Don\'t Want To Spend This Much Time On . How About You?',
                    'How To Win Clients And Influence Markets with but we can\'t let that stop us from making it',
                    '3 Ways You Can Reinvent Without Looking Like An Amateur is our capacity to create, to overcome',
                    'The Secrets To Finding World Class Tools For Your Quickly we realize how little we understand about life',
                    'To People That Want To Start But Are Affraid To Get Started a solo act. It\'s a huge collaboration',
                    'You Will Thank Us - 10 Tips About You Need To Know ache for home lives in all of us',
                    'What You Should Have Asked Your Teachers About this universe; we are in this universe',
                    'Want To Step Up Your ? You Need To Read This First like the sun, which, as we journey toward it',
                    '17 Tricks About You Wish You Knew Before generations are not going to ask us what political',
                    'Proof That Is Exactly What You Are Looking For progress has merely provided us with',
                    'In 10 Minutes, I\'ll Give You The Truth About success is to be in harmony with existence',
                    '5 Incredibly Useful Tips For Small Businesses We are what our thoughts have made us',
                    'Picture Your On Top. Read This And Make It So and ghosts are real too. They live inside us',
                    'Using 7 Strategies Like The Pros Life is a gift, and it offers us the privilege',
                    'Everything You Wanted to Know About and Were Afraid To Ask',
                    'These 10 Hacks Will Make You(r) (Look) Like A Pro People That Want To Start But Are Affraid',
                    'Apply Any Of These 10 Secret Techniques To Improve which does not merely give us information',
                    'Believing Any Of These 10 Myths About Keeps You From Growing',
                    '10 Reasons You Need To Stop Stressing About financial peace is possible for all of us',
                    '12 Ways You Can Without Investing Too Much Of Your Time',
                    '10 Horrible Mistakes To Avoid When You (Do) Our incomes are like our shoes; if too small',
                    '22 Very Simple Things You Can Do To Save Time With are no morals in politics; there is only',
                    'Like An Expert. Follow These 5 Steps To Get There Let us not forget that the cultivation',
                    'When Professionals Run Into Problems With , This Is What They Do  All that we love deeply becomes a part of us',
                ),
                'product' => array(
                    'The Untapped Gold Mine Of That Virtually No One Knows About',
                    'Why You Never See That Actually Works',
                    '7 Ways To Keep Your Growing Without Burning The Midnight Oil',
                    'How To Find The Right For Your Specific Product(Service).',
                    'You Can Thank Us Later - 3 Reasons To Stop Thinking About',
                    'The Untold Secret To Mastering In Just 3 Days',
                    'Is Bound To Make An Impact In Your Business',
                    '3 Ways Create Better With The Help Of Your Dog',
                    'Believe In Your Skills But Never Stop Improving',
                    'What Alberto Savoia Can Teach You About',
                    'I Don\'t Want To Spend This Much Time On . How About You?',
                    'How To Win Clients And Influence Markets with',
                    '3 Ways You Can Reinvent Without Looking Like An Amateur',
                    'The Secrets To Finding World Class Tools For Your Quickly',
                    'To People That Want To Start But Are Affraid To Get Started',
                    'You Will Thank Us - 10 Tips About You Need To Know',
                    'What You Should Have Asked Your Teachers About',
                    'Want To Step Up Your ? You Need To Read This First',
                    '17 Tricks About You Wish You Knew Before',
                    'Proof That Is Exactly What You Are Looking For',
                    'In 10 Minutes, I\'ll Give You The Truth About',
                    '5 Incredibly Useful Tips For Small Businesses',
                    'Picture Your On Top. Read This And Make It So',
                    'Using 7 Strategies Like The Pros',
                    'Everything You Wanted to Know About and Were Afraid To Ask',
                    'These 10 Hacks Will Make You(r) (Look) Like A Pro',
                    'Apply Any Of These 10 Secret Techniques To Improve',
                    'Believing Any Of These 10 Myths About Keeps You From Growing',
                    '10 Reasons You Need To Stop Stressing About',
                    '12 Ways You Can Without Investing Too Much Of Your Time',
                    '10 Horrible Mistakes To Avoid When You (Do)',
                    '22 Very Simple Things You Can Do To Save Time With',
                    'Like An Expert. Follow These 5 Steps To Get There',
                    'When Professionals Run Into Problems With , This Is What They Do',
                ),
            ),
            'rtl' => array(
                'post' => array(
                    'إن الطبيعة تلطف بنا لأنها جعلتنا نعثر على المعرفة حيثما أدرنا وجوهنا في العالم',
                    ' يستطيع عود الكبريت أن يحرق ملايين الأشجار ... بينما من الشجرة الواحدة تصنع ملايين الأعواد',
                    'إذا أنت أسديت جميلا إلى إنسان فحذار أن تذكره ... وإن أسدى إنسان إليك جميلا فحذار أن تنساه',
                    'عندما أقرأ كتابا للمرة الأولى فذلك بالنسبة لي كسب صديق جديد وعندما أقرأ مجددا كتابا سبق لى قراءته فذلك يشبه لقائي صديق قديم',
                    'تمهل عند اختيار الصديق ، وتمهل أكثر عند تغييره',
                    'عيوب الناس نحفرها على النحاس وفضائلهم نكتبها على الماء',
                    'ما أشبه التعليم بالمجداف إن لم تتقدم للأمام عاد بك للوراء',
                    'يبدو أن القمة ليست مكانا فسيحا ومريحا فان الكثيرين ممن يبلغونها يستسلمون للنوم ويهوون إلى القاع',
                    'في قلب كل شتاء ربيع نابض ... ووراء كل ليل فجر باسم ...',
                    'ليس الشجاع من يعترف بالخطأ ... الشجاع من لا يكرر الخطأ',
                    'إذا كنت قد بنيت قصورا في الهواء ... فلا تحاول هدمها بل ابدأ فى وضع الأساس تحتها',
                    'لو ظل التخلف في مجتمعاتنا فسيأتي السياح ليتفرجوا علينا بدلا من الآثار',
                    'ما وجد أحد في نفسه كبر إلا من مهانة يجدها في نفسه',
                    'الصباح البهي مثل النقود يستحسن عدم إفساده بالتفكير فيما يليه',
                    'إني مدين بكل ما وصلت اليه وما أرجو أن يصل إليه من الرفعة إلى أمي الحنون',
                    'ليس ثمة إلا الأب لا يحسد ابنه على موهبته',
                    'ويل لأمة تلبس مما لا تنسج ، وتأكل مما لا تزرع ، وتشرب مما لا تعصر',
                    'تعود الناس على أن يلعنوا ظروف حياتهم ولست أؤمن بالظروف فالناس هم الذين يصنعونها',
                    'عندما ينتزع الراعي عنزا من بين براثن ذئب ، تعتبره العنز بطلا ، أما الذئاب فتعتبره ديكتاتوريا',
                    'لكل إنسان 3 طباع ، طبعه الحقيقي والطبع الذي يظهر به أمام الناس والطبع الذي يعتقده في نفسه',
                    'أسهل على الإنسان أن يعمل من أن يفكر ، وإذا فكر فانه من أصعب الأمور أن يجعل عمله خاضعا دائما لما أوصله إليه فكره',
                    'كلما كبرت السنبلة انحنت ، وكلما تعمق العالم في علمه تواضع',
                    'بعض الناس عظماء لأن المحيطين بهم صغار',
                    'خير لي أن أموت صعلوكا لا يعرف أحد عنى شيئا من أن أموت عظيما مهلهل السيرة',
                    'لا زوال لنعمة مع الشكر ... ولا دوام لنعمة مع الكفر',
                    'ما ذل ذو حق ولو أطبق العالم عليه ، ولا عز ذو باطل ولو طلع من جبينه القمر',
                    'إن التركيز هو سر النجاح في السياسة في الحرب وفي التجارة وفى العلاقات الإنسانية كافة',
                    'إن المقياس الصحيح الذي يمكن به الحكم على الرجل هو قدرته على التركيز',
                    ' يسقط الكذب وحده مع الزمن والتريث ',
                    'لو كنت أبني حبلا ، ثم هجرت عملي قبل أن أضع الحجر الأخير في قمته ، لعددت نفسي فاشلا ',
                    'إذا لم تستطع شيئا فدعه وجاوزه إلى ما تستطيع',
                    'إن وجود الإنسان على ظهر الأرض والمظاهر الفاخرة لذكائه إنما هي جزء من برنامج ينفذه بارئ الكون',
                    'الكلمات الصادقة ليست دائما جميلة والكلمات الجميلة ليست دائما صادقة',
                    ' أحب الكتاب لا لأنني زاهد في الحياة ، ولكن لأن حياة واحدة لا تكفيني',
                    'أحسن اختيار عباراتك فانك لا تدرى متى تضطر إلى ابتلاعها',
                    'لكي تتقى حقد الناس كن قاسيا على نفسك كريما مع الناس',
                    'ليس المهم أن يكون لديك مال ... المهم أن يكون لديك أمل',
                    'من يذهب إلى وليمة الذئب يجب أن يصحب كلبه معه',
                    'كثرة السفر تزيد العاقل حكمة والسفيه غفلة',
                    ' تستطيع أن تغلق كل السجون يوم تستطيع أن تجد عملا لكل إنسان',
                    'لا تكن كالبعوضة التي تقف خلف صاحب المنزل الذي تعيش فيه',
                    'من الأفضل أن تمتلئ مكتبتك بالكتب بدلا من أن تكتنز محفظتك بالنقود',
                    'إذا أردت أن تعيش آمنا من الانتقاد ... لا تقل ، ولا تفعل شيئا ، وعندها لن ينتقدك أحد ',
                    'السعادة تعنى أن تنجح في تحويل ليمونتك لشراب لذيذ ',
                    ' لسان العاقل وراء قلبه وقلب الأحمق وراء لسانه ',
                    'ليس هناك شئ ذو قيمة يمكن أن يشترى بلا آلام',
                    'العمل هو شرط الحياة والينبوع الحقيقي للرفاهية الإنسانية ',
                    'فكر فيما سوف يكون عليه شعورك في الغد فالأمس مضى واليوم يوشك على الانتهاء',
                    'لا تخجل من السؤال عن شئ تجهله ، فخير لك أن تكون جاهلا مرة من أن تظل على جهلك طول العمر',
                    'ما يزعجني ليس أنك كذبت على ... لكن ما يزعجني أنه لا يمكن تصديقك بعد الآن ',
                    'اجتهد دائما أن تحافظ على تلك الشعلة الإلهية التي تضيء القلوب والتي يسمونها الضمير ',
                    'أحسن مقياس لعقلية الإنسان ... أهمية الموضوعات التي يتجادل فيها',
                    'لا يضيع شئ ذو قيمة إذا صرفنا الوقت الكافي في إتقانه',
                    'أعظم الثروات في الرضا بالقليل لأنه لا تكون هناك حاجة حيث تكون القناعة',
                    'من الناس من يحبون أن يقعدوا في صندوق من الجهل ويقفلوه على أنفسهم حتى لا يأتي فاتح ويفرج عنهم',
                    'لا تنم بينما غيرك يتكلم ، ولا تجلس وغيرك واقف ، ولا تتكلم في موقف يستدعى الصمت',
                    'ان سر الإحساس والشقاء هو أن يكون عندك وقت فراغ تتساءل فيه عنا إذا كنت سعيدا أم لا',
                    'أول الحكمة أن نعرف الحق ... وآخر الحكمة ألا نعرف الخوف ',
                    'المصلحة الشخصية هي دائما الصخرة التي تتحطم عليها أقوى المبادئ',
                    'العاقل من له عينان تبصران ... أما الأحمق فله في وجهه تجويفان ',
                    'إذا أردت اختبار وفاء رجل ... فانظر إلى حنينه لأوطانه وتشوقه لإخوانه',
                ),
                'product' => array(
                    'إن الطبيعة تلطف بنا لأنها جعلتنا نعثر على المعرفة حيثما أدرنا وجوهنا في العالم',
                    ' يستطيع عود الكبريت أن يحرق ملايين الأشجار ... بينما من الشجرة الواحدة تصنع ملايين الأعواد',
                    'إذا أنت أسديت جميلا إلى إنسان فحذار أن تذكره ... وإن أسدى إنسان إليك جميلا فحذار أن تنساه',
                    'عندما أقرأ كتابا للمرة الأولى فذلك بالنسبة لي كسب صديق جديد وعندما أقرأ مجددا كتابا سبق لى قراءته فذلك يشبه لقائي صديق قديم',
                    'تمهل عند اختيار الصديق ، وتمهل أكثر عند تغييره',
                    'عيوب الناس نحفرها على النحاس وفضائلهم نكتبها على الماء',
                    'ما أشبه التعليم بالمجداف إن لم تتقدم للأمام عاد بك للوراء',
                    'يبدو أن القمة ليست مكانا فسيحا ومريحا فان الكثيرين ممن يبلغونها يستسلمون للنوم ويهوون إلى القاع',
                    'في قلب كل شتاء ربيع نابض ... ووراء كل ليل فجر باسم ...',
                    'ليس الشجاع من يعترف بالخطأ ... الشجاع من لا يكرر الخطأ',
                    'إذا كنت قد بنيت قصورا في الهواء ... فلا تحاول هدمها بل ابدأ فى وضع الأساس تحتها',
                    'لو ظل التخلف في مجتمعاتنا فسيأتي السياح ليتفرجوا علينا بدلا من الآثار',
                    'ما وجد أحد في نفسه كبر إلا من مهانة يجدها في نفسه',
                    'الصباح البهي مثل النقود يستحسن عدم إفساده بالتفكير فيما يليه',
                    'إني مدين بكل ما وصلت اليه وما أرجو أن يصل إليه من الرفعة إلى أمي الحنون',
                    'ليس ثمة إلا الأب لا يحسد ابنه على موهبته',
                    'ويل لأمة تلبس مما لا تنسج ، وتأكل مما لا تزرع ، وتشرب مما لا تعصر',
                    'تعود الناس على أن يلعنوا ظروف حياتهم ولست أؤمن بالظروف فالناس هم الذين يصنعونها',
                    'عندما ينتزع الراعي عنزا من بين براثن ذئب ، تعتبره العنز بطلا ، أما الذئاب فتعتبره ديكتاتوريا',
                    'لكل إنسان 3 طباع ، طبعه الحقيقي والطبع الذي يظهر به أمام الناس والطبع الذي يعتقده في نفسه',
                    'أسهل على الإنسان أن يعمل من أن يفكر ، وإذا فكر فانه من أصعب الأمور أن يجعل عمله خاضعا دائما لما أوصله إليه فكره',
                    'كلما كبرت السنبلة انحنت ، وكلما تعمق العالم في علمه تواضع',
                    'بعض الناس عظماء لأن المحيطين بهم صغار',
                    'خير لي أن أموت صعلوكا لا يعرف أحد عنى شيئا من أن أموت عظيما مهلهل السيرة',
                    'لا زوال لنعمة مع الشكر ... ولا دوام لنعمة مع الكفر',
                    'ما ذل ذو حق ولو أطبق العالم عليه ، ولا عز ذو باطل ولو طلع من جبينه القمر',
                    'إن التركيز هو سر النجاح في السياسة في الحرب وفي التجارة وفى العلاقات الإنسانية كافة',
                    'إن المقياس الصحيح الذي يمكن به الحكم على الرجل هو قدرته على التركيز',
                    ' يسقط الكذب وحده مع الزمن والتريث ',
                    'لو كنت أبني حبلا ، ثم هجرت عملي قبل أن أضع الحجر الأخير في قمته ، لعددت نفسي فاشلا ',
                    'إذا لم تستطع شيئا فدعه وجاوزه إلى ما تستطيع',
                    'إن وجود الإنسان على ظهر الأرض والمظاهر الفاخرة لذكائه إنما هي جزء من برنامج ينفذه بارئ الكون',
                    'الكلمات الصادقة ليست دائما جميلة والكلمات الجميلة ليست دائما صادقة',
                    ' أحب الكتاب لا لأنني زاهد في الحياة ، ولكن لأن حياة واحدة لا تكفيني',
                    'أحسن اختيار عباراتك فانك لا تدرى متى تضطر إلى ابتلاعها',
                    'لكي تتقى حقد الناس كن قاسيا على نفسك كريما مع الناس',
                    'ليس المهم أن يكون لديك مال ... المهم أن يكون لديك أمل',
                    'من يذهب إلى وليمة الذئب يجب أن يصحب كلبه معه',
                    'كثرة السفر تزيد العاقل حكمة والسفيه غفلة',
                    ' تستطيع أن تغلق كل السجون يوم تستطيع أن تجد عملا لكل إنسان',
                    'لا تكن كالبعوضة التي تقف خلف صاحب المنزل الذي تعيش فيه',
                    'من الأفضل أن تمتلئ مكتبتك بالكتب بدلا من أن تكتنز محفظتك بالنقود',
                    'إذا أردت أن تعيش آمنا من الانتقاد ... لا تقل ، ولا تفعل شيئا ، وعندها لن ينتقدك أحد ',
                    'السعادة تعنى أن تنجح في تحويل ليمونتك لشراب لذيذ ',
                    ' لسان العاقل وراء قلبه وقلب الأحمق وراء لسانه ',
                    'ليس هناك شئ ذو قيمة يمكن أن يشترى بلا آلام',
                    'العمل هو شرط الحياة والينبوع الحقيقي للرفاهية الإنسانية ',
                    'فكر فيما سوف يكون عليه شعورك في الغد فالأمس مضى واليوم يوشك على الانتهاء',
                    'لا تخجل من السؤال عن شئ تجهله ، فخير لك أن تكون جاهلا مرة من أن تظل على جهلك طول العمر',
                    'ما يزعجني ليس أنك كذبت على ... لكن ما يزعجني أنه لا يمكن تصديقك بعد الآن ',
                    'اجتهد دائما أن تحافظ على تلك الشعلة الإلهية التي تضيء القلوب والتي يسمونها الضمير ',
                    'أحسن مقياس لعقلية الإنسان ... أهمية الموضوعات التي يتجادل فيها',
                    'لا يضيع شئ ذو قيمة إذا صرفنا الوقت الكافي في إتقانه',
                    'أعظم الثروات في الرضا بالقليل لأنه لا تكون هناك حاجة حيث تكون القناعة',
                    'من الناس من يحبون أن يقعدوا في صندوق من الجهل ويقفلوه على أنفسهم حتى لا يأتي فاتح ويفرج عنهم',
                    'لا تنم بينما غيرك يتكلم ، ولا تجلس وغيرك واقف ، ولا تتكلم في موقف يستدعى الصمت',
                    'ان سر الإحساس والشقاء هو أن يكون عندك وقت فراغ تتساءل فيه عنا إذا كنت سعيدا أم لا',
                    'أول الحكمة أن نعرف الحق ... وآخر الحكمة ألا نعرف الخوف ',
                    'المصلحة الشخصية هي دائما الصخرة التي تتحطم عليها أقوى المبادئ',
                    'العاقل من له عينان تبصران ... أما الأحمق فله في وجهه تجويفان ',
                    'إذا أردت اختبار وفاء رجل ... فانظر إلى حنينه لأوطانه وتشوقه لإخوانه',
                ),
            ),
        ),
    );

    return isset( $titles[$demo][$direction][$post_type][$demo_key] ) ? $titles[$demo][$direction][$post_type][$demo_key] : false;
}
function wpmdm_import_get_demo_term_title( $i, $tax = 'category', $demo = 'default' ) {

    $demo_key  = $i;
    $demo_key  = is_numeric( $demo_key ) ? $demo_key - 1 : $demo_key;

    $direction = is_rtl() ? 'rtl' : 'ltr';
    $titles = array(
        'default' => array(
            'ltr' => array(
                'category' => array(
                    'General',
                    'News',
                    'Events',
                    'Featured',
                    'Reaction',
                ),
            ),
            'rtl' => array(
                'category' => array(
                    'مستجدات',
                    'أخبار',
                    'آراء و تحليلات',
                    'برامج',
                    'المواضيع البارزة',
                ),
            ),
        ),
    );

    return isset( $titles[$demo][$direction][$tax][$demo_key] ) ? $titles[$demo][$direction][$tax][$demo_key] : false;
}
function wpmdm_import_get_demo_tag_title_rtl( $tag ) {
    $tags = array('best' => 'الأحسن', 'wordpress' => 'ووردبريس', 'theme' => 'قالب', 'tech' => 'تكنلوجيا', 'business' => 'أعمال', 'news' => 'أخبار', 'magazine' => 'مجلة', 'shop' => 'تجارة', 'blog' => 'مدونة', 'landing' => 'تميز', 'page' => 'صفحة','share' => 'مشاركة', 'social' => 'تواصل', 'luxe' => 'تصميم');
    return isset( $tags[ $tag ] ) ? $tags[ $tag ] : $tag;
}
function wpmdm_import_generate_attachment_metadata( $attachment_id, $file ) {
    $attachment = get_post( $attachment_id );

    $metadata = array();
    if ( preg_match('!^image/!', get_post_mime_type( $attachment )) && file_is_displayable_image($file) ) {
        $imagesize = getimagesize( $file );
        $metadata['width'] = $imagesize[0];
        $metadata['height'] = $imagesize[1];
        list($uwidth, $uheight) = wp_constrain_dimensions($metadata['width'], $metadata['height'], 128, 96);
        $metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";

        // Make the file path relative to the upload dir
        $metadata['file'] = _wp_relative_upload_path($file);

        // fetch additional metadata from exif/iptc
        $image_meta = wp_read_image_metadata( $file );
        if ( $image_meta )
            $metadata['image_meta'] = $image_meta;
    }

    return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
}
function wpmdm_import_insert_attachment_from_url($url, $parent_post_id = null) {
    if( !class_exists( 'WP_Http' ) )
        include_once( ABSPATH . WPINC . '/class-http.php' );
    $http = new WP_Http();
    $response = $http->request( $url );
    if( is_wp_error($response ) ) {
        return false;
    }
    if( isset( $response['response']['code'] ) && $response['response']['code'] != 200 ) {
        return false;
    }
    $upload = wp_upload_bits( basename($url), null, $response['body'] );
    if( !isset( $upload['file'] ) || ( isset( $upload['error'] ) && !empty( $upload['error'] ) ) ) {
        return false;
    }
    $file_path = $upload['file'];
    $file_name = basename( $file_path );
    $file_type = wp_check_filetype( $file_name, null );
    $attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
    $wp_upload_dir = wp_upload_dir();
    $post_info = array(
        'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
        'post_mime_type' => $file_type['type'],
        'post_title'     => $attachment_title,
        'post_content'   => '',
        'post_status'    => 'inherit',
    );
    // Create the attachment
    $attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

    // Include image.php
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    // Define attachment metadata
    $attach_data = wpmdm_import_generate_attachment_metadata( $attach_id, $file_path );
    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id,  $attach_data );
    
    return $attach_id;
}
function wpmdm_import_get_attachment_id( $key, $theme = 'general', $post_type = 'post' ) {
    $id = false;
    $args = array(
        'post_type' => array('attachment'),
        'showposts' => 1,
        'post_status' => 'any',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'       => 'wpmdm_demo_image_key',
                'value'     => $key,
                'compare' => '='
            ),
            array(
                'key'       => 'wpmdm_demo_image_theme',
                'value'     => $theme,
                'compare' => '='
            ),
            array(
                'key'       => 'wpmdm_demo_image_post_type',
                'value'     => $post_type,
                'compare' => '='
            )
        ),
    );
    $posts = get_posts( $args );
    foreach( $posts as $postss ) {

        if( isset( $postss->ID ) ) {
            $id = $postss->ID;
            break;
        }
    }
    
    return $id;
}
function wpmdm_import_rand_comment_text( $key = 'no' ) {
    $strings = array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac nulla eget augue semper dictum vitae vel ligula. Suspendisse sit amet orci elit.',
        'Maecenas tempus, lectus nec convallis bibendum, sem nibh placerat felis, in aliquam odio odio tincidunt arcu. Praesent iaculis nisl quis massa tincidunt, ac maximus libero semper.',
        'Vestibulum ac arcu quis massa scelerisque volutpat vitae ut mauris. Maecenas quis lobortis nibh. Pellentesque id euismod augue. Curabitur luctus posuere elit, ut porttitor purus aliquam ut',
        'Mauris et tempor augue, eget ornare tellus. Duis eu aliquam eros. Proin in orci mattis, convallis nulla vitae, molestie dolor. Aenean semper maximus aliquet.',
        'Morbi ac semper metus, non maximus nunc. Vestibulum urna arcu, dapibus vitae luctus a, convallis in augue. Duis faucibus ullamcorper metus et sagittis.',
        'Maecenas semper ipsum at mi luctus laoreet. Maecenas laoreet massa tincidunt aliquet ullamcorper. Nunc eu mattis risus.',
        'Sed dolor dui, pulvinar eu vulputate et, convallis sed sapien. Integer metus quam, feugiat at ultricies id, cursus ut erat. Maecenas eu felis semper, tincidunt nunc in, maximus lectus. Fusce ultricies nulla ut feugiat varius.',
        'Aliquam interdum aliquet interdum. Aliquam tempor sit amet elit eget iaculis. Nulla venenatis venenatis est id elementum. Donec vel tincidunt eros.',
        'Donec vitae quam aliquet, rhoncus ex quis, viverra ipsum. Morbi nec sodales urna, et dapibus sapien. Aliquam diam ante, consectetur nec urna id, aliquet ultricies justo.',
        'Praesent faucibus sapien eu lacus cursus, varius tincidunt sem vehicula. Morbi eu tincidunt lectus. Morbi id massa porttitor, laoreet sapien in, facilisis metus. In tincidunt mi nec consequat bibendum. Pellentesque ut dolor nec odio condimentum rhoncus.',
        'Cras eget suscipit ipsum, id facilisis eros. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed non dictum sapien.'
    );
    return isset( $strings[$key] ) ? $strings[$key] : $strings[array_rand($strings)];
}
add_action('not_now', function() {
$args = array(
        'posts_per_page'   => -1,
        'post_type'        => array('post', 'product', 'page'),
        'meta_key'         => 'wpmdm_demo_post',
        'meta_value'       => true,
        'post_status'      => 'any',
        
    );
    $query = new WP_Query( $args );

    while ($query->have_posts()) {
        $query->the_post();
        var_dump(get_the_modified_time()); echo ' | ';
        var_dump(get_the_time()); echo '<br/>';
    }
});

function wpmdm_import_delete_demos_posts() {

    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => array('post', 'product'),
        'meta_key'         => 'wpmdm_demo_post',
        'meta_value'       => true,
        'post_status'      => 'any',

    );
    $query = new WP_Query( $args );

    while ($query->have_posts()) {
        $query->the_post();
        wp_delete_post( get_the_ID(), true );
    }
    wp_reset_postdata();

    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => array('attachment'),
        'meta_query' => array(
                array(
                   'key'       => 'wpmdm_demo_post',
                   'value'     => true,
                )
            ),
        'post_status'      => 'any',
    );
    $query = new WP_Query( $args );

    while ($query->have_posts()) {
        $query->the_post();
        wp_delete_attachment( get_the_ID(), true );
    }
    wp_reset_postdata();

    $terms = get_terms( 
        array(
            'taxonomy' => array('category', 'product_cat'),
            'hide_empty' => false,
            'meta_query' => array(
                array(
                   'key'       => 'wpmdm_demo_post',
                   'value'     => true,
                )
            )
        )
     );

    foreach( $terms as $term ) {
        $demo_post = get_term_meta( $term->term_id, 'wpmdm_demo_post', true );
        if( $demo_post == true ) {
            wp_delete_term( $term->term_id, $term->taxonomy );
        }
    }

   
    delete_transient( 'webte_purge_cache');
}
function wpmdm_import_generate_content( $theme = 'general' ) {
    # insert ad

    $ad_url = plugins_url( 'assets/images/ad-728x90.png', dirname(__FILE__) );
    $ad_attach_id = wpmdm_import_insert_attachment_from_url( $ad_url );
    if( function_exists('fw_set_db_settings_option') ) {
        $ad = array('ad' => 'show', 'show' => array(
            'image' => array(
                'attachment_id' => $ad_attach_id,
                'url' => wp_get_attachment_url( $ad_attach_id ),
            ),
            'link' => '#',
        ));
        fw_set_db_settings_option('default_header_ad', $ad );
    }

    # generate featured images

    $post_types = class_exists('WooCommerce') ? array('post', 'product') : array('post');

    foreach( $post_types as $post_type ) {

        for( $i = 1; $i <= 12; $i++ ) {

            $images_url  = 'https://res.cloudinary.com/dds3nc46p/image/upload/v1677751811/wsdm-images/'; 

            // $filename should be the path to a file in the upload directory.
            $filename_jpg   = $post_type . '/' . $theme . '/' . $i . '.jpg';
            $filename_png   = $post_type . '/' . $theme . '/' . $i . '.png';

            // Insert the attachment.
            $attach_id = wpmdm_import_insert_attachment_from_url( $images_url . $filename_jpg );
            
            update_post_meta( $attach_id, 'wpmdm_demo_post', true );
            update_post_meta( $attach_id, 'wpmdm_demo_image_key', $i );
            update_post_meta( $attach_id, 'wpmdm_demo_image_theme', $theme );
            update_post_meta( $attach_id, 'wpmdm_demo_image_post_type', $post_type );

        }

        unset( $i );

    }


    // create users

    
    $tags  = is_rtl() ?  array('افضل', 'ووردبريس', 'قالب', 'تيك', 'اعمال', 'اخبار', 'مجلة', 'متجر', 'مدونى', 'مميز', 'صفحة',' مشاركة', 'اجتماعي', 'راقي') : array('best', 'wordpress', 'theme', 'tech', 'business', 'news', 'magazine', 'shop', 'blog', 'landing', 'page',' share', 'social', 'luxe');
    
   
    // create categories
    $post_categories = array();
    $product_categories = array();
    for( $i = 1; $i <= 5; $i++ ) {
        $categories_names = is_rtl() ? array(
                    'مستجدات',
                    'أخبار',
                    'آراء و تحليلات',
                    'برامج',
                    'المواضيع البارزة'
                ) : array('General',
                    'News',
                    'Events',
                    'Featured',
                    'Reaction');
        $category = wp_insert_term(
         $categories_names[$i-1], // the term 
          'category', // the taxonomy
          array(
            'description'=> '',
            'slug' => 'demo-post-category-' . $i,
          )
        );
        $category_exists = get_term_by( 'name',  $categories_names[$i-1], 'category' );
        $category_id = isset( $category_exists->term_id ) ? $category_exists->term_id : ( isset( $category['term_id'] ) ? $category['term_id'] : false );

        add_term_meta( $category_id, 'wpmdm_demo_post_key', $i );
        add_term_meta( $category_id, 'wpmdm_demo_post', true );

        $post_categories[] = $category_id;

        if( class_exists('WooCommerce')) {
            $product_category = wp_insert_term(
              $categories_names[$i-1], // the term 
              'product_cat', // the taxonomy
              array(
                'description'=> '',
                'slug' => 'demo-product-category-' . $i,
              )
            );

            $category_exists = get_term_by( 'name',  $categories_names[$i-1], 'product_cat' );
            $product_category_id = isset( $category_exists->term_id ) ? $category_exists->term_id : ( isset( $product_category['term_id'] ) ? $product_category['term_id'] : false );   
            add_term_meta( $product_category_id, 'wpmdm_demo_post_key', $i );
            add_term_meta( $product_category_id, 'wpmdm_demo_post', true );

            $product_categories[] = $product_category_id;
        }

        
    }

    unset( $i );
    // create posts
    $user_id = 0;
    $c = 1;
    $attach_ids = array();
    for( $i = 1; $i <= 12; $i++ ) {
        
        $post_title = wpmdm_import_get_demo_post_title( $i, 'post' );
        $args = array(
            'post_status' => 'publish',
            'post_date' => wpmdm_import_random_date(),
            'post_title' =>  $post_title,
            'post_content' => is_rtl() ? 'وكسبت المدن قد حيث, أملاً المتاخمة استراليا، ذلك أي, ٣٠ ومن فمرّ وشعار الشرق،. أخذ وعلى الثالث، الأمريكية هو, تونس بمحاولة كل بحث, خيار تاريخ انذار فصل عن. ضمنها الساحل الأوضاع قد وتم, قد وجهان واحدة كلّ, قد وصل أسيا والقرى استعملت. نتيجة بمحاولة تم قام, لان تم قامت الحدود بمباركة. أضف ببعض لدحر أن, أطراف وقامت قام هو, يتم هو لغزو وحرمان المنتصر. قبل ثم الأرض الأعمال. بحث أن هامش بالرّد الأمور, ان وكسبت المدن حدى.' : 'Nunc et ipsum ornare, fringilla arcu id, eleifend dolor. Pellentesque ornare at ligula non ullamcorper. Pellentesque feugiat justo sed nisl bibendum, et lacinia dolor finibus. Aliquam purus augue, vulputate eget turpis ut, finibus dictum metus. Duis in libero tempor, euismod nulla id, congue massa. Suspendisse mattis odio et tristique faucibus. Curabitur auctor suscipit nulla sit amet rutrum. Vestibulum porttitor metus nisl, ac dictum lacus iaculis vel. Phasellus vitae ante felis. Fusce lacus urna, bibendum ultrices dictum sed, iaculis in velit. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec laoreet lacinia sem, eu commodo lorem condimentum nec. Mauris feugiat sagittis eleifend. Donec ac varius massa.',
            'meta_input' => array('wpmdm_demo_post_key' => $i, 'wpmdm_demo_post' => true ),
        );
        
        $post_id = wp_insert_post( $args );
        $c_key = $c-1;

        $category = isset( $post_categories[$c_key] ) ? $post_categories[$c_key] : false;
        $categories = array();
        $categories[] = $category;
        
        wp_set_object_terms( $post_id, $categories, 'category', false );


        $post_tags = array();

        for( $t=1; $t<=4; $t++) {
            $tag_key = array_rand( $tags );
            $post_tags[] = is_rtl() ? wpmdm_import_get_demo_tag_title_rtl( $tags[$tag_key] ) : $tags[$tag_key];
        }

        wp_set_post_tags( $post_id, $post_tags, false );

        $attach_id = wpmdm_import_get_attachment_id( $i, $theme, 'post' );

        if( $i <= 12 ) {
            $attach_ids[] = array('attachment_id' => $attach_id, 'url' => wp_get_attachment_url( $attach_id ) );
        }

        if( isset( $attach_id ) && $attach_id && $post_id ) {

            set_post_thumbnail( $post_id, $attach_id );

        }

        update_post_meta( $post_id, 'wpmdm_demo_post_key', $i );
        update_post_meta( $post_id, 'wpmdm_demo_post', true );
        $c++;
        if( $i % 3 == 0 ) {
            $c = 1;

        }
        // review
        if( $i % 2 == 0 && function_exists('fw_set_db_post_option') ) {
            fw_set_db_post_option($post_id, 'single_enable_review', 'yes');
            fw_set_db_post_option( $post_id, 'single_review_position', 'after_content');
            $review_styles = array('percent', 'points', 'stars');
            $rsk = array_rand( $review_styles);
            $review_cretirias = array(
                array( 'title' => 'Design', 'note' => rand(4,10) ),
                array( 'title' => 'Durability', 'note' => rand(4,10) ),
                array( 'title' => 'Ergonomy', 'note' => rand(4,10) ),
                array( 'title' => 'User experience', 'note' => rand(4, 10)),
                array( 'title' => 'Performance', 'note' => rand(4, 10)),
            );
            $review = array(
                'yes' => array(
                    'review_title' => 'Product review',
                    'review_summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam non neque eros. Phasellus lobortis nulla nibh, eget mollis purus molestie ut. Pellentesque euismod pulvinar sem vel consequat. Donec a lacus vitae libero scelerisque mollis nec sit amet nulla. Donec dictum vulputate condimentum. ',
                    'review_style' =>  $review_styles[$rsk],
                    'review_cretirias' => $review_cretirias,
                ),
            );
            foreach( (array) $review_cretirias as $k => $review_cretiria ) {
              $note += isset( $review_cretiria['note'] ) && is_numeric( $review_cretiria['note'] ) && $review_cretiria['note'] >= 0 && $review_cretiria['note'] <= 10 ? $review_cretiria['note'] : 0;
            }
            $score = ( $note / count( $review_cretirias ) ) / 2;
            update_post_meta( $post_id, 'review_score', $score );
            fw_set_db_post_option( $post_id, 'single_review', $review );
            fw_set_db_post_option($post_id, 'single_enable_review', 'yes');
        }
        // video
        if( $i % 2 != 0 && function_exists('fw_set_db_post_option') ) {
            set_post_format( $post_id, 'video');
            $video_urls = array('https://vimeo.com/33510073', 'https://vimeo.com/69130612');
            $video_key = array_rand( $video_urls );
            fw_set_db_post_option( $post_id, 'post_video_url', $video_urls[$video_key] );
            wp_update_post( array('ID' => $post_id, 'post_title' => $post_title . ' video' ) );
        }

       
        
    }
    unset( $i );
    if( class_exists('WooCommerce')) {
        for( $i = 1; $i <= 8; $i++ ) {
            $post_title = 'Demo product ' . $i;
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_date' => wpmdm_import_random_date(),
                'post_title' =>  wpmdm_import_get_demo_post_title( $i, 'product' ),
                'post_content' => is_rtl() ? 'وكسبت المدن قد حيث, أملاً المتاخمة استراليا، ذلك أي, ٣٠ ومن فمرّ وشعار الشرق،. أخذ وعلى الثالث، الأمريكية هو, تونس بمحاولة كل بحث, خيار تاريخ انذار فصل عن. ضمنها الساحل الأوضاع قد وتم, قد وجهان واحدة كلّ, قد وصل أسيا والقرى استعملت. نتيجة بمحاولة تم قام, لان تم قامت الحدود بمباركة. أضف ببعض لدحر أن, أطراف وقامت قام هو, يتم هو لغزو وحرمان المنتصر. قبل ثم الأرض الأعمال. بحث أن هامش بالرّد الأمور, ان وكسبت المدن حدى.' : 'Nunc et ipsum ornare, fringilla arcu id, eleifend dolor. Pellentesque ornare at ligula non ullamcorper. Pellentesque feugiat justo sed nisl bibendum, et lacinia dolor finibus. Aliquam purus augue, vulputate eget turpis ut, finibus dictum metus. Duis in libero tempor, euismod nulla id, congue massa. Suspendisse mattis odio et tristique faucibus. Curabitur auctor suscipit nulla sit amet rutrum. Vestibulum porttitor metus nisl, ac dictum lacus iaculis vel. Phasellus vitae ante felis. Fusce lacus urna, bibendum ultrices dictum sed, iaculis in velit. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec laoreet lacinia sem, eu commodo lorem condimentum nec. Mauris feugiat sagittis eleifend. Donec ac varius massa.',
                'meta_input' => array('wpmdm_demo_post_key' => $i, 'wpmdm_demo_post' => true ),
            );

            $post_id = wp_insert_post( $args );

            $attach_id = wpmdm_import_get_attachment_id( $i, $theme, 'product' );

            if( isset( $attach_id ) && $attach_id && $post_id ) {

                set_post_thumbnail( $post_id, $attach_id );

            }

            // wp_set_object_terms( $post_id, $product->model, 'product_cat' );
            wp_set_object_terms( $post_id, wpmdm_import_rand_array_keys( $product_categories ), 'product_cat', true );
            $product = wc_get_product( $post_id );
            if( ($i % 3) === 0 ) {
                $price = rand(100,400);
                $sale_price = $price - rand( 20,60 );
                $product->set_regular_price($price);
                $product->set_sale_price($sale_price);
                $product->set_price($sale_price);

            } else {
                $price = rand(100,200);
                $product->set_regular_price($price);
                $product->set_sale_price($price);
                $product->set_price($price);

            }

            $product->set_catalog_visibility( 'visible' );
            $product->set_stock_status( 'instock');
            $product->set_total_sales(rand(0,56) );

            update_post_meta( $post_id, 'wpmdm_demo_post_key', $i );
            update_post_meta( $post_id, 'wpmdm_demo_post', true );

            $product->save();
        }
        unset( $i );
    }
    return true;

}
if( ! function_exists( 'wpmdm_import_rand_array_keys' ) ) {
    function wpmdm_import_rand_array_keys( $list = array(), $count = false ) {

      if (!is_array($list)) return $list; 

      $keys = array_keys($list); 
      shuffle($keys); 
      $random = array();
      $c = 1;
      foreach ($keys as $key) { 
        $random[] = $list[$key];
        if( $count !== false && is_numeric( $count ) && $c == $count ) {
            break;
        }
        $c++;
      }
      return $random; 

    }
}
if( ! function_exists( 'wpmdm_import_get_setting' ) ) {
    function wpmdm_import_get_setting( $id, $name ) {
        $setting = false;
        if( $name == 'option_tree' && function_exists( 'ot_settings_id') ) {
             $settings = get_option( ot_settings_id() );
            if( isset( $settings['settings'] ) ) {
                foreach( $settings['settings'] as $ot_setting ) {
                    if( isset( $ot_setting['id'] ) && $ot_setting['id'] === $id ) {
                        $setting = $ot_setting;
                    }
                }
            }
        }
        return $setting;
    }
}
if( !function_exists('wpmdm_import_sanitize_theme_mods') ) {
    function wpmdm_import_sanitize_theme_mods( $mods, $options_name, $update = false ) {
        $default_mods = get_theme_mods();
            switch ($options_name) {
                case 'fw_options':
                    if( defined('FW') ) {
                        $options = fw()->theme->get_options( 'customizer' );
                        $new_mods = fw_get_options_values_from_input( $options, $mods );
                        if( $update === true ) {
                            foreach( (array) $new_mods as $option_key => $option_value ) {
                                fw_set_db_customizer_option( $option_key, $option_value );
                            }
                        }

                    }
                    break;
                default:
                    # code...
                    break;
            }
        return isset( $new_mods ) ? $new_mods : $default_mods;
    }
}
if( ! function_exists( 'wpmdm_import_sanitize_options' ) ) {
    function wpmdm_import_sanitize_options( $options, $option_name, $update = false ) {
        switch ($option_name) {
            case 'option_tree':
                foreach( (array) $options as $option_id => $option_value ) {
                    $setting = wpmdm_import_get_setting( $option_id, $option_name );
                    $type    = isset( $setting['type'] ) ? $setting['type'] : false;
                    $new_options[$option_id] = $type !== false ? ot_validate_setting( $option_value, $type, '' ) : $option_value;
                }
                if( isset( $new_options ) && $update === true ) {
                    update_option( $option_name, $new_options );
                }
                break;
            case 'fw_options':
                $settings = fw()->theme->get_settings_options();
                $new_options = fw_get_options_values_from_input( $settings, $options );
                if( $update === true ) {
                    foreach( (array) $new_options as $option_key => $option_value ) {
                        fw_set_db_settings_option( $option_key, $option_value );
                    }
                }
                break;
        }
        return isset( $new_options) ? $new_options : $options;
    }
}
/**
 * Import settings
 *
 * @return    bool
 *
 * @access    public
 * @since     1.0.0
 */
if( ! function_exists( 'wpmdm_import_settings' ) ) {

    function wpmdm_import_settings( $settings = array() ) {

        if( is_array( $settings ) && !empty( $settings ) && wpmdm_import_array_keys_exists( array( 'mods', 'options', 'options_name', 'nav_menus', 'theme_skin') ,$settings ) == true ) {

            $mods            = $settings['mods'];

            $options         = $settings['options'];

            $options_name    = $settings['options_name'];

            $menus_loactions = $settings['nav_menus'];

            $theme_skin      = $settings['theme_skin'];
            
            # update theme mods


            fw_set_db_settings_option('theme_skin', $theme_skin );

            fw_set_db_customizer_option('theme_skin', $theme_skin );


            $copy_text = '© 2020, Powered By <a href="https://webte.studio/">Wordpress</a>';

            fw_set_db_settings_option( 'footer_copyrights_text', $copy_text);

            # update menus

            $locations = get_registered_nav_menus();

            foreach ( (array) $locations as $location => $desc ) {

                if( isset( $menus_loactions[ $location ] ) && isset( $menus_loactions[ $location ]['slug'] )) {

                    $new_menu_id = wp_get_nav_menu_object(  $menus_loactions[ $location ]['slug'] );

                    $new_menu_id = isset( $new_menu_id->term_id ) ? $new_menu_id->term_id : false;

                    if( $new_menu_id != false && !empty( $new_menu_id ) ) {

                        $locations[ $location ] = $new_menu_id;

                    }

                }
                
            }

            if( isset( $new_menu_id ) &&  $new_menu_id != false ) {

                set_theme_mod( 'nav_menu_locations', $locations );

            }

            return true;

        } else {

            return false;

        }

    }
    
}
/**
 * Import Menus
 *
 * @return    bool
 *
 * @access    public
 * @since     1.0.0
 */
if( ! function_exists( 'wpmdm_import_menus' ) ) {

    function wpmdm_import_menus( $menus = array(), $id ) {

        $required_keys = array( 'id', 'name', 'slug', 'items' );

        if( is_array( $menus ) && !empty( $menus ) ) {

            foreach( $menus as $menu ) {

                # check for valid menus data

                if(  wpmdm_import_array_keys_exists( $required_keys, $menus ) == true ) {

                    return false;

                }

                # begin import menus

                $menu_exists = wp_get_nav_menu_object( $menu['slug'] );

                if( false == $menu_exists ) {

                    # create menu

                    $menu_id = wp_create_nav_menu( $menu['name'] );

                    # create menu items

                    foreach( $menu['items'] as $k => $item ) {



                        $item_id = wp_update_nav_menu_item( $menu_id, 0, $item);

                        $item_title = $item['menu-item-title']; 

                        $item_old_id = $item['menu-item-id'];

                        $item_parent_id = $item['menu-item-parent-id'];

                        $new_items[] = array( 'id' => $item_id, 'title' => $item_title, 'old_id' => $item_old_id,'parent_id' => $item_parent_id );

                    }

                    # update menu items parents and locations

                    $menu_items = wp_get_nav_menu_items( $menu_id );

                    foreach( $menu_items as $menu_item ) {

                        if( !empty( $menu_item->menu_item_parent ) ) {

                            foreach( $new_items as $new_item ) {

                                if( $new_item['old_id'] == $menu_item->menu_item_parent ) {

                                    $new_item_with_parent = array( 
                                        'menu-item-parent-id' => $new_item['id'],
                                        'menu-item-title' => $menu_item->title,
                                        'menu-item-url' => $menu_item->url,
                                        'menu-item-type' => $menu_item->type,
                                        'menu-item-object' => $menu_item->object,
                                        'menu-item-status' => $menu_item->post_status,
                                    );

                                    wp_update_nav_menu_item( $menu_id, $menu_item->ID, $new_item_with_parent);

                                } 

                            }

                        } # end check menu item parent


                    } # end loop menu items
                    
                }


            } # end loop for menus

            # update locations

            $settings = wpmdm_import_retreive_body(get_template_directory_uri(). '/demos/demo' . $id . '/settings.dat');

            if( $settings ) {


                $settings = json_decode( $settings, true );

                $menus_loactions = isset( $settings['nav_menus'] ) ? $settings['nav_menus'] : array();

                $locations = get_registered_nav_menus();

                foreach ($locations as $location => $desc ) {

                    if( isset( $menus_loactions[ $location ] ) && isset( $menus_loactions[ $location ]['slug'] )) {

                        $new_menu_id = wp_get_nav_menu_object(  $menus_loactions[ $location ]['slug'] );

                        $new_menu_id = isset( $new_menu_id->term_id ) ? $new_menu_id->term_id : false;

                        if( $new_menu_id != false && !empty( $new_menu_id ) ) {

                            $locations[ $location ] = $new_menu_id;

                        }

                    }
                    
                }

                if( isset( $new_menu_id ) &&  $new_menu_id != false ) {

                    set_theme_mod( 'nav_menu_locations', $locations );

                }

            }

            # end update locations

            return true;

        } else {

            return false;

        }

    }
    
}
/**
 * Import Content ( Beta )
 *
 * @return    bool
 *
 * @access    public
 * @since     1.0.0
 */
function wpmdm_import_content( $file ) {

    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

    require_once ABSPATH . 'wp-admin/includes/import.php';

    $importer_error = false;

    if ( !class_exists( 'WP_Importer' ) ) {

        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

        if ( file_exists( $class_wp_importer ) ){

            require_once( $class_wp_importer );

        } else {

            $importer_error = true;

        }

    }

    if ( !class_exists( 'WP_Import' ) ) {

        $class_wp_import = plugin_dir_path( dirname(__FILE__ ) ).'includes/wordpress-importer.php';

        if ( file_exists( $class_wp_import ) ) {

            require_once($class_wp_import);

        } else {

            $importer_error = true;

        }

    }

    if( $importer_error ){

        return false;

    } else {

        if( !is_file( $file ) ){

            return false;

        } else {

            $wp_import = new WP_Import();

            $wp_import->fetch_attachments = false;

            ob_start();

            $wp_import->import( $file );

            $imprt_data = ob_get_contents();

            ob_end_clean();

            return true;

        }

    }

}

/**
 * Import status
 *
 * @return    bool
 *
 * @access    public
 * @since     1.0.13
 */
function wpmdm_import_status( $messages ) {


    if( is_array(  $messages ) && !empty(  $messages ) ) {

        echo '<div class="wpmdm-action-status">';

        foreach( $messages as $message ) {

            echo '<div class="wpmdm-action-status-item">';

                echo '<span>' . esc_attr( $message['message'] ) . '</span>';

                $status_class = $message['status'] == true ? 'fa fa-check-circle wpmdm-action-status-success' : 'fa fa-times wpmdm-action-status-error';

                echo '<span class="' . esc_attr( $status_class ) . '"></span>';

            echo '</div>';

        }

        echo '</div>';

    }



} # end wpmdm_import_status()
/**
 * Function to get available widget
 *
 * @return void
 *
 * @access private
 * @since 1.0.0
 */
function wpmdm_import_available_widgets() {

    global $wp_registered_widget_controls;

    $widget_controls = $wp_registered_widget_controls;

    $available_widgets = array();

    foreach ( $widget_controls as $widget ) {

        // No duplicates.
        if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
            $available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
            $available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];
        }

    }

    return $available_widgets;

}
/**
 * Import widget JSON data
 *
 * @since 0.4
 * @global array $wp_registered_sidebars
 * @param object $data JSON widget data from .wpmdm file.
 * @return array Results array
 */
function wpmdm_import_widgets( $data ) {

    global $wp_registered_sidebars;

    // Have valid data?
    // If no data or could not decode.
    if ( empty( $data ) || ! is_object( $data ) ) {

        return false;

    }

    $fail = false;

    // Hook before import.
    do_action( 'wpmdm_before_import' );
    $data = apply_filters( 'wpmdm_import_data', $data );

    // Get all available widgets site supports.
    $available_widgets = wpmdm_import_available_widgets();

    // Get all existing widget instances.
    $widget_instances = array();
    foreach ( $available_widgets as $widget_data ) {
        $widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
    }

    // Begin results.
    $results = array();

    // Loop import data's sidebars.
    foreach ( $data as $sidebar_id => $widgets ) {

        // Skip inactive widgets (should not be in export file).
        if ( 'wp_inactive_widgets' === $sidebar_id ) {
            continue;
        }

        // Check if sidebar is available on this site.
        // Otherwise add widgets to inactive, and say so.
        if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
            $sidebar_available    = true;
            $use_sidebar_id       = $sidebar_id;
            $sidebar_message_type = 'success';
            $sidebar_message      = '';
        } else {
            $sidebar_available    = false;
            $use_sidebar_id       = 'wp_inactive_widgets'; // Add to inactive if sidebar does not exist in theme.
            $sidebar_message_type = 'error';
            $sidebar_message      = esc_html__( 'Widget area does not exist in theme (using Inactive)', 'widget-importer-exporter' );
        }

        // Result for sidebar
        // Sidebar name if theme supports it; otherwise ID.
        $results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id;
        $results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
        $results[ $sidebar_id ]['message']      = $sidebar_message;
        $results[ $sidebar_id ]['widgets']      = array();

        // Loop widgets.
        foreach ( $widgets as $widget_instance_id => $widget ) {

            // Get id_base (remove -# from end) and instance ID number.
            $id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
            $instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

            // Does site support this widget?
            if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
                $fail                = true;
                $widget_message_type = 'error';
                $widget_message = esc_html__( 'Site does not support widget', 'widget-importer-exporter' ); // Explain why widget not imported.
            }

            // Filter to modify settings object before conversion to array and import
            // Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
            // Ideally the newer wpmdm_widget_settings_array below will be used instead of this.
            $widget = apply_filters( 'wpmdm_widget_settings', $widget );

            // Convert multidimensional objects to multidimensional arrays
            // Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
            // Without this, they are imported as objects and cause fatal error on Widgets page
            // If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
            // It is probably much more likely that arrays are used than objects, however.
            $widget = json_decode( wp_json_encode( $widget ), true );

            // Filter to modify settings array
            // This is preferred over the older wpmdm_widget_settings filter above
            // Do before identical check because changes may make it identical to end result (such as URL replacements).
            $widget = apply_filters( 'wpmdm_widget_settings_array', $widget );

            // Does widget with identical settings already exist in same sidebar?
            if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

                // Get existing widgets in this sidebar.
                $sidebars_widgets = get_option( 'sidebars_widgets' );
                $sidebar_widgets = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // Check Inactive if that's where will go.

                // Loop widgets with ID base.
                $single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
                foreach ( $single_widget_instances as $check_id => $check_widget ) {

                    // Is widget in same sidebar and has identical settings?
                    if ( in_array( "$id_base-$check_id", $sidebar_widgets, true ) && (array) $widget === $check_widget ) {

                        $fail = true;
                        $widget_message_type = 'warning';

                        // Explain why widget not imported.
                        $widget_message = esc_html__( 'Widget already exists', 'widget-importer-exporter' );

                        break;

                    }

                }

            }

            // No failure.
            if ( ! $fail ) {

                // Add widget instance
                $single_widget_instances = get_option( 'widget_' . $id_base ); // All instances for that widget ID base, get fresh every time.
                $single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
                    '_multiwidget' => 1, // Start fresh if have to.
                );
                $single_widget_instances[] = $widget; // Add it.

                // Get the key it was given.
                end( $single_widget_instances );
                $new_instance_id_number = key( $single_widget_instances );

                // If key is 0, make it 1
                // When 0, an issue can occur where adding a widget causes data from other widget to load,
                // and the widget doesn't stick (reload wipes it).
                if ( '0' === strval( $new_instance_id_number ) ) {
                    $new_instance_id_number = 1;
                    $single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
                    unset( $single_widget_instances[0] );
                }

                // Move _multiwidget to end of array for uniformity.
                if ( isset( $single_widget_instances['_multiwidget'] ) ) {
                    $multiwidget = $single_widget_instances['_multiwidget'];
                    unset( $single_widget_instances['_multiwidget'] );
                    $single_widget_instances['_multiwidget'] = $multiwidget;
                }

                // Update option with new widget.
                update_option( 'widget_' . $id_base, $single_widget_instances );

                // Assign widget instance to sidebar.
                // Which sidebars have which widgets, get fresh every time.
                $sidebars_widgets = get_option( 'sidebars_widgets' );

                // Avoid rarely fatal error when the option is an empty string
                // https://github.com/churchthemes/widget-importer-exporter/pull/11.
                if ( ! $sidebars_widgets ) {
                    $sidebars_widgets = array();
                }

                // Use ID number from new widget instance.
                $new_instance_id = $id_base . '-' . $new_instance_id_number;

                // Add new instance to sidebar.
                $sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id;

                // Save the amended data.
                update_option( 'sidebars_widgets', $sidebars_widgets );

                // After widget import action.
                $after_widget_import = array(
                    'sidebar'           => $use_sidebar_id,
                    'sidebar_old'       => $sidebar_id,
                    'widget'            => $widget,
                    'widget_type'       => $id_base,
                    'widget_id'         => $new_instance_id,
                    'widget_id_old'     => $widget_instance_id,
                    'widget_id_num'     => $new_instance_id_number,
                    'widget_id_num_old' => $instance_id_number,
                );
                do_action( 'wpmdm_after_widget_import', $after_widget_import );

                // Success message.
                if ( $sidebar_available ) {
                    $widget_message_type = 'success';
                    $widget_message      = esc_html__( 'Imported', 'widget-importer-exporter' );
                } else {
                    $widget_message_type = 'warning';
                    $widget_message      = esc_html__( 'Imported to Inactive', 'widget-importer-exporter' );
                }

            }

            // Result for widget instance
            $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name'] = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // Widget name or ID if name not available (not supported by site).
            $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget['title'] ) ? $widget['title'] : esc_html__( 'No Title', 'widget-importer-exporter' ); // Show "No Title" if widget instance is untitled.
            $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
            $results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;

        }

    }

    // Hook after import.
    do_action( 'wpmdm_after_import' );

    // Return results.
    return apply_filters( 'wpmdm_import_results', $fail );

}