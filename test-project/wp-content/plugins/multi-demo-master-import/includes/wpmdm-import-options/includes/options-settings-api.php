<?php
if (!defined('WPMDM_IMPORT_OPTIONS_VERSION'))

    exit('No direct script access allowed');

/**
* WPmdpmOptions Settings API
*
* This class loads all the methods and helpers specific to a Settings page.
*
* @package   WPmdpmOptions
* @author    Merrasse Mouhcine <merrasse@wpmdm.net>
* @copyright Copyright (c) 2016, Merrasse Mouhcine
 */
if (!class_exists('wpmdm_import_options_Settings')) {
    
    class wpmdm_import_options_Settings
    {
        
        /* the options array */
        private $options;
        
        /* infos array */
        private $infos;
        
        /* hooks for targeting admin pages */
        private $page_hook;
        
        /**
         * Constructor
         *
         * @param     array     An array of options
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function __construct($args, $infos)
        {
            
            $this->options = $args;
            $this->infos   = $infos;
            
            /* return early if not viewing an admin page or no options */
            if (!is_admin() || !is_array($this->options))
                return false;
            
            /* load everything */
            $this->hooks();

            add_action( 'wp_ajax_wpmdm_import_options_reset_options', array( $this, 'wpmdm_import_options_reset_options' ) );
            add_action( 'wp_ajax_nopriv_wpmdm_import_options_reset_options', array( $this, 'wpmdm_import_options_reset_options' ) );
        }
        
        /**
         * Execute the WordPress Hooks
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function hooks()
        {
            
            /**
             * Filter the `admin_menu` action hook priority.
             *
             * @since 2.5.0
             *
             * @param int $priority The priority. Default '10'.
             */
            $priority = apply_filters('wpmdm_import_options_admin_menu_priority', 3);
            
            /* add pages & menu items */
            add_action('admin_menu', array(
                $this,
                'add_page'
            ), $priority);
            
            /* register sections */
            add_action('admin_init', array(
                $this,
                'add_sections'
            ));
            
            /* register settings */
            add_action('admin_init', array(
                $this,
                'add_settings'
            ));
            
            
            
            /* initialize settings */
            add_action('admin_init', array(
                $this,
                'initialize_settings'
            ), 11);
            
        }
        
        /**
         * Loads each admin page
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function add_page()
        {
            
            /* loop through options */
            foreach ((array) $this->options as $option) {
                
                /* loop through pages */
                foreach ((array) $this->get_pages($option) as $page) {
                    
                   


                        $page_hook = add_submenu_page(  $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], array(
                                $this,
                                'display_page'
                            ));
                    
                    
                    
                    /* only load if not a hidden page */
                    if (!isset($page['hidden_page'])) {
                        
                        /* associate $page_hook with page id */
                        $this->page_hook[$page['id']] = $page_hook;
                        
                        /* add scripts */
                        add_action('admin_print_scripts-' . $page_hook, array(
                            $this,
                            'scripts'
                        ));
                        
                        /* add styles */
                        add_action('admin_print_styles-' . $page_hook, array(
                            $this,
                            'styles'
                        ));
                        
                        /* add contextual help */
                        add_action('load-' . $page_hook, array(
                            $this,
                            'help'
                        ));
                        
                    }
                    
                }
                
            }
            
            return false;
        }
        
        /**
         * Loads the scripts
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function scripts()
        {
            wpmdm_import_options_admin_scripts();
        }
        
        /**
         * Loads the styles
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function styles()
        {
            wpmdm_import_options_admin_styles();
        }
        
        /**
         * Loads the contextual help for each page
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function help()
        {
            $screen = get_current_screen();
            
            /* loop through options */
            foreach ((array) $this->options as $option) {
                
                /* loop through pages */
                foreach ((array) $this->get_pages($option) as $page) {
                    
                    /* verify page */
                    if (!isset($page['hidden_page']) && $screen->id == $this->page_hook[$page['id']]) {
                        
                        /* set up the help tabs */
                        if (!empty($page['contextual_help']['content'])) {
                            foreach ($page['contextual_help']['content'] as $contextual_help) {
                                $screen->add_help_tab(array(
                                    'id' => esc_attr($contextual_help['id']),
                                    'title' => esc_attr($contextual_help['title']),
                                    'content' => htmlspecialchars_decode($contextual_help['content'])
                                ));
                            }
                        }
                        
                        /* set up the help sidebar */
                        if (!empty($page['contextual_help']['sidebar'])) {
                            $screen->set_help_sidebar(htmlspecialchars_decode($page['contextual_help']['sidebar']));
                        }
                        
                    }
                    
                }
                
            }
            
            return false;
        }
        
        /**
         * Loads the content for each page
         *
         * @return    string
         *
         * @access    public
         * @since     1.0
         */
        public function display_page()
        {
            $screen = get_current_screen();

            /* loop through settings */
            foreach ((array) $this->options as $option) {
                
                foreach ((array) $this->get_pages($option) as $page) {
                    
                    echo '<div id ="page-' . $page['id'] . '">';
                    
                    if (isset($_GET['select']) && !empty($_GET['select'])) {
                        
                        echo '<span id="wpmdm-import-options-select-field" data-select="' . esc_attr($_GET['select']) . '"></span>';
                        
                    }
                    
                    /* verify page */
                    
                    if (!isset($page['hidden_page']) && $screen->id == $this->page_hook[$page['id']]) {
                        
                        echo '<div class="wrap settings-wrap wpmdm-import-options-container">';
                        
                        echo '<h2 class="wpmdm-import-options-fake-title"></h2>';
                        
                        echo wpmdm_import_options_alert_message( $page );
                        
                        settings_errors('wpmdm-import-options');
                        
                        $this->display_header();
                        
                        /* verify page */
                        if (!isset($page['hidden_page']) && $screen->id == $this->page_hook[$page['id']]) {
                            
                            $show_buttons = isset($page['show_buttons']) && $page['show_buttons'] == false ? false : true;
                            
                           
                            
                            /* remove forms on the custom settings pages */
                            if ($show_buttons) {
                                
                                echo '<form action="options.php" method="post" id="wpmdm-import-options-settings-api">';
                                
                                settings_fields($option['id']);
                                
                            } else {
                                
                                echo '<div id="wpmdm-import-options-settings-api">';
                                
                            }
                            $sub_header_class = '';
                            if( isset( $page['big_sub_header'] )  && $page['big_sub_header'] == true ) {

                                $sub_header_class = 'wpmdm-import-options-big-sub-header';
                            }
                             
                            /* Sub Header */
                            echo '<div id="wpmdm-import-options-sub-header" class="' . esc_attr( $sub_header_class ) . '">';

                            if( isset( $page['sub_header_icon'] ) ) {

                                echo '<div class="wpmdm-import-options-sub-header-icon">';

                                    echo '<i class="' . esc_attr( $page['sub_header_icon'] ) . '"></i>';

                                echo '</div>';

                            }
                            
                            echo '<h2>' . $page['page_title'] . '</h2>';
                            
                            if ($show_buttons)

                                echo '<div class="wpmdm-import-options-sub-header-button"><button class="wpmdm-import-options-ui-button button button-primary right">' . $page['button_text'] . '</button></div>';
                            
                            echo '<div class="wpmdm-import-options-clear"></div>';
                            
                            
                            echo '</div>';

                            echo '<div class="wpmdm-import-options-header-message">
                            <span class="wpmdm-import-options-header-messag-text-message"></span>
                            <a href="javascript:void(0);" class="wpmdm-import-options-header-messag-close-message">' . esc_html__('Close', 'wpmdm-import-options') . '</a>
                            </div>';
                            
                            /* Navigation */
                            echo '<div class="ui-tabs wpmdm-import-options-content-container ">';
                            
                            /* check for sections */
                            
                            if (isset($page['sections']) && count($page['sections']) > 0) {

                                $section_class = count($page['sections']) == 1 ? 'wpmdm-import-options-sidebar-one-section' : 'wpmdm-import-options-sidebar';
                                
                                echo '<ul class="ui-tabs-nav normal-sections '. esc_attr( $section_class ) . '">';
                                
                                /* loop through page sections */
                                
                                foreach ((array) $page['sections'] as $section) {
                                    if (isset($section['icon'])) {
                                        $icon_class = $section['icon'];
                                    } else {
                                        $icon_class = 'fa-caret-right';
                                    }
                                    echo '<li id="tab_' . $section['id'] . '"><a href="#section_' . $section['id'] . '"><i class="fa ' . $icon_class . '"></i>  ' . $section['title'] . '</a></li>';
                                }
                                
                                echo '</ul>';
                                
                            }
                            
                            /* sections */
                            echo '<div id="poststuff" class="metabox-holder">';
                            
                            echo '<div id="post-body">';
                            
                            echo '<div id="post-body-content">';
                            if (!isset($page['sections']) || count($page['sections']) == 0) {
                                
                                echo '<div class="wpmdm-import-options-message">';
                                
                                echo esc_attr($page['empty_message']);
                                
                                echo '</div>';
                            }
                            
                            $this->do_settings_sections($_GET['page']);
                            
                            echo '</div>';
                            
                            echo '</div>';
                            
                            echo '</div>';
                            
                            echo '</div>';
                            
                            
                            
                            /* buttons */
                            
                            if ($show_buttons) {
                                
                                echo '<div class="wpmdm-import-options-ui-buttons">';
                                
                                echo '<button class="wpmdm-import-options-ui-button button button-primary right">' . $page['button_text'] . '</button>';
                                echo '<button data-page="' . esc_attr($_GET['page']) . '" data-nonce="' . wp_create_nonce('wpmdm_import_options_reset_form') . '" data-ajax-url="' . esc_url( admin_url('admin-ajax.php') ) . '" class="wpmdm-import-options-ui-button button button-secondary left reset-settings" title="' . esc_html__('Reset Options', 'wpmdm-import-options') . '">' . $page['reset_button_text'] . '</button>';
                                
                                echo '</div>';
                                
                            }
                            
                            echo $show_buttons ? '</form>' : '</div>';
                            
                        }
                        
                    }
                    
                    
                }
                
                
                echo '</div>';
                
            }
            return false;
        }
        public function display_menu()
        {
            if (count($this->options) > 1) {
                echo '<div class="wpmdm-import-options-header-menu">';
                echo '<ul>';
                foreach ($this->options as $option) {
                    
                    foreach ($this->get_pages($option) as $page) {
                        $current_style = '';
                        if ($page['menu_slug'] == esc_attr($_GET['page'])) {
                            $class = "current";
                            
                        } else {
                            $class = "not-current";
                        }
                        echo '<li class="' . $class . '" ><a style="' . $current_style . '" href="' . menu_page_url($page['menu_slug'], false) . '">' . $page['menu_title'] . '</a></li>';
                        
                        
                    }
                }
                echo '</ul>';
                echo '</div>';
            }
        }
        public function display_header()
        {
            $infos = $this->infos;
            /* HEADER */
            if (isset($infos['cover_url'])) {
                $style = "background: #333 url('" . $infos['cover_url'] . "') bottom center no-repeat ; background-size: cover; background-scroll: fixed;";
            } else {
                $backgournd = "background: #333";
            }
            if (isset($infos['cover_title'])) {
                
                
                
                echo '<div id="wpmdm-import-options-header" style="' . esc_attr($style) . '">';
                
                if ($infos['cover_background']) {

                    echo '<div class="wpmdm-import-options-header-mask" style="background:' . esc_attr($infos['cover_background']) . '"></div>';
                }

                $this->display_menu();

                echo '<div class="wpmdm-import-options-header-top">';
                
                echo '<div class="wpmdm-import-options-header-info">';
                
                
                echo '<div class="wpmdm-import-options-header-info-title">' . $infos['cover_title'] . '</div>';
                
                if (isset($infos['data']) && is_array($infos['data'])) {
                    
                    echo '<div class="wpmdm-import-options-header-info-data">';
                    
                    foreach ($infos['data'] as $key => $value) {
                        
                        echo '<span class="wpmdm-import-options-header-info-data-item">' . esc_attr($key) . ' : ' . htmlspecialchars_decode(esc_html($value)) . '</span>';
                        
                    }
                    echo '</div>';
                    
                }
                
                
                echo '</div>';
                
                if (isset($infos['links']) && count($infos['links'] > 0)) {
                    echo '<div class="wpmdm-import-options-header-links">';
                    $links = $infos['links'];
                    foreach ($links as $link) {
                        echo '<a target="_blank" href="' . esc_url($link['link']) . '">' . esc_attr($link['title']) . '</a>';
                    }
                    echo '</div>';
                }
                
                if (isset($infos['buttons']) && count($infos['buttons'] > 0)) {
                    $buttons = $infos['buttons'];
                    echo '<div class="wpmdm-import-options-header-buttons">';
                    foreach ($buttons as $button) {
                        $button_icon = '';
                        if (isset($button['icon'])) {
                            $button_icon = '<i class="fa ' . $button['icon'] . '"></i>  ';
                        }
                        $button_style = '';
                        if (isset($button['color'])) {
                            $button_style = 'style="background:' . esc_attr($button['color']) . '"';
                        }
                        echo '<a ' . $button_style . ' target="_blank" href="' . esc_url($button['link']) . '">' . html_entity_decode(esc_html($button_icon . $button['title'])) . '</a>';
                    }
                    echo '</div>';
                }
                
                
                echo '<div class="clear"></div>';
                
                echo '</div>';
                
                
                
                echo '</div>';
                
            }
            /* loop through pages */
        }
        /**
         * Adds sections to the page
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function add_sections()
        {
            
            /* loop through options */
            foreach ((array) $this->options as $option) {
                
                /* loop through pages */
                foreach ((array) $this->get_pages($option) as $page) {
                    
                    /* loop through page sections */
                    foreach ((array) $this->get_sections($page) as $section) {
                        
                        /* add each section */
                        add_settings_section($section['id'], $section['title'], array(
                            $this,
                            'display_section'
                        ), $page['menu_slug']);
                        
                    }
                    
                }
                
            }
            
            return false;
        }
        
        /**
         * Callback for add_settings_section()
         *
         * @return    string
         *
         * @access    public
         * @since     1.0
         */
        public function display_section()
        {
            /* currently pointless */
        }
        
        /**
         * Add settings the the page
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function add_settings()
        {
            
            /* loop through options */
            foreach ((array) $this->options as $option) {
                
                register_setting($option['id'], $option['id'], array(
                    $this,
                    'sanitize_callback'
                ));
                
                /* loop through pages */
                foreach ((array) $this->get_pages($option) as $page) {
                    
                    /* loop through page settings */
                    foreach ((array) $this->get_the_settings($page) as $setting) {
                        
                        /* skip if no setting ID */
                        if (!isset($setting['id']))
                            continue;
                        if (!isset($setting['section'])) {
                            $setting['section'] = null;
                        }
                        /* add get_option param to the array */
                        $setting['get_option'] = $option['id'];
                        
                        /* add each setting */
                        add_settings_field($setting['id'], $setting['label'], array(
                            $this,
                            'display_setting'
                        ), $page['menu_slug'], $setting['section'], $setting);
                        
                    }
                    
                }
                
            }
            
            return false;
        }
        
        /**
         * Callback for add_settings_field() to build each setting by type
         *
         * @param     array     Setting object array
         * @return    string
         *
         * @access    public
         * @since     1.0
         */
        public function display_setting($args = array())
        {
            
            extract($args);
            
            /* get current saved data */
            $options = get_option($get_option, false);
            
            // Set field value
            $field_value = isset($options[$id]) ? $options[$id] : '';
            
            /* set standard value */
            if (isset($std)) {
                $field_value = wpmdm_import_options_filter_std_value($field_value, $std);
            }
            
            // Allow the descriptions to be filtered before being displayed
            $desc = apply_filters('wpmdm_import_options_filter_description', (isset($desc) ? $desc : ''), $id);
            
            /* build the arguments array */
            $_args = array(
                'type' => $type,
                'field_id' => $id,
                'field_name' => $get_option . '[' . $id . ']',
                'field_value' => $field_value,
                'field_desc' => $desc,
                'field_std' => isset($std) ? $std : '',
                'show_color' => isset($show_color) ? $show_color : true,
                'add_title' => isset($add_title) ? $add_title : 'Add New',
                'multiple' => isset($multiple) ? $multiple : false,
                'field_rows' => isset($rows) && !empty($rows) ? $rows : 15,
                'field_post_type' => isset($post_type) && !empty($post_type) ? $post_type : 'post',
                'field_taxonomy' => isset($taxonomy) && !empty($taxonomy) ? $taxonomy : 'category',
                'field_min_max_step' => isset($min_max_step) && !empty($min_max_step) ? $min_max_step : '0,100,1',
                'field_condition' => isset($condition) && !empty($condition) ? $condition : '',
                'field_operator' => isset($operator) && !empty($operator) ? $operator : 'and',
                'field_class' => isset($class) ? $class : '',
                'field_choices' => isset($choices) && !empty($choices) ? $choices : array(),
                'field_settings' => isset($settings) && !empty($settings) ? $settings : array(),
                'post_id' => wpmdm_import_options_get_media_post_ID(),
                'get_option' => $get_option,
                'button_title' => isset($button_title) && !empty($button_title) ? $button_title : '',
                'function' => isset($function) && !empty($function) ? $function : '',
                'update' => isset($update) && !empty($update) ? $update : '',
                'action' => isset($action) && !empty($action) ? $action : '',
                'save_action' => isset($save_action) && !empty($save_action) ? $save_action : '',
                'disabled' => isset( $disabled) && !empty( $disabled ) ? $disabled : '',
                'empty_message' => isset($empty_message) && !empty($empty_message) ? $empty_message : '',
                'input' => isset($input) ? $input : array(),
                'heads'=> isset($heads) && is_array($heads) ? $heads : array(),
                'widths'=> isset($widths) && is_array($widths) ? $widths : array(),
                'title'=> isset($title) ? $title : array(),
                'session'=> isset($session) ? $session : '',
                'directory_path' => isset($directory_path) ? $directory_path : '',
                'directory_url' => isset($directory_url) ? $directory_url : '',
                'files_type' => isset($files_type) ? $files_type : '',
                'options_type' => isset($options_type) ? $options_type : '',
                'messages' => isset($messages) && is_array($messages) ? wpmdm_import_messages_to_data_string( $messages ) : array(),
                'clear_folder_messages' => isset($clear_folder_messages) && is_array($clear_folder_messages) ? wpmdm_import_messages_to_data_string( $clear_folder_messages ) : array(),
                'refresh_folder_messages' => isset($refresh_folder_messages) && is_array($refresh_folder_messages) ? wpmdm_import_messages_to_data_string( $refresh_folder_messages ) : array(),
                'check_folder_messages' => isset($check_folder_messages) && is_array($check_folder_messages) ? wpmdm_import_messages_to_data_string( $check_folder_messages, 'check' ) : array(),
            );
            
            /* get the option HTML */
            echo wpmdm_import_options_display_by_type( $_args );
            
            
        }
        
        /**
         * Sets the option standards if nothing yet exists.
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function initialize_settings()
        {
            
            /* loop through options */
            foreach ((array) $this->options as $option) {
                
                /* skip if option is already set */
                if (isset($option['id']) && get_option($option['id'], false)) {
                    return false;
                }
                
                $defaults = array();
                
                /* loop through pages */
                foreach ((array) $this->get_pages($option) as $page) {
                    
                    /* loop through page settings */
                    foreach ((array) $this->get_the_settings($page) as $setting) {
                        
                        if (isset($setting['std'])) {
                            
                            $defaults[$setting['id']] = wpmdm_import_options_validate_setting($setting['std'], $setting['type'], $setting['id']);
                            
                        }
                        
                    }
                    
                }
                
                update_option($option['id'], $defaults);
                
            }
            
            return false;
        }
        
        /**
         * Sanitize callback for register_setting()
         *
         * @return    string
         *
         * @access    public
         * @since     1.0
         */
        public function sanitize_callback($input)
        {
            
            /* loop through options */
            foreach ((array) $this->options as $option) {
                
                /* loop through pages */
                foreach ((array) $this->get_pages($option) as $page) {
                    
                    /* loop through page settings */
                    foreach ((array) $this->get_the_settings($page) as $setting) {
                        
                        /* verify setting has a type & value */
                        if (isset($setting['type']) && isset($input[$setting['id']])) {
                            

                            $current_options  = get_option( $option['id'] );
                            
                            /* validate setting */
                            if (is_array($input[$setting['id']]) && in_array($setting['type'], array(
                                'list-item',
                                'slider'
                            ))) {
                                
                                /* required title setting */
                                $required_setting = array(
                                    array(
                                        'id' => 'title',
                                        'label' => esc_html__('Title', 'wpmdm-import-options'),
                                        'desc' => '',
                                        'std' => '',
                                        'type' => 'text',
                                        'rows' => '',
                                        'class' => 'wpmdm-import-options-setting-title',
                                        'post_type' => '',
                                        'choices' => array()
                                    )
                                );
                                
                                /* get the settings array */
                                $settings = isset($_POST[$setting['id'] . '_settings_array']) ? unserialize(wpmdm_import_options_decode($_POST[$setting['id'] . '_settings_array'])) : array();
                                
                                /* settings are empty for some odd ass reason get the defaults */
                                if (empty($settings)) {
                                    $settings = wpmdm_import_options_list_item_settings( $setting['id'] );
                                }
                                
                                /* merge the two settings array */
                                $settings = array_merge($required_setting, $settings);
                                
                                /* create an empty WPML id array */
                                $wpml_ids = array();
                                
                                foreach ($input[$setting['id']] as $k => $setting_array) {
                                    
                                    foreach ($settings as $sub_setting) {
                                        /* setup the WPML ID */
                                        $wpml_id = $setting['id'] . '_' . $sub_setting['id'] . '_' . $k;
                                        
                                        /* add id to array */
                                        $wpml_ids[] = $wpml_id;
                                        
                                        /* verify sub setting has a type & value */
                                        if (isset($sub_setting['type']) && isset($input[$setting['id']][$k][$sub_setting['id']])) {
                                            
                                            /* validate setting */
                                            $input[$setting['id']][$k][$sub_setting['id']] = wpmdm_import_options_validate_setting($input[$setting['id']][$k][$sub_setting['id']], $sub_setting['type'], $sub_setting['id'], $wpml_id);
                                            
                                        }
                                        
                                        if ($sub_setting['type'] == 'list-item' && isset($input[$setting['id'] . '_' . $sub_setting['id'] . '_' . $k])) {
                                            $input[$setting['id']][$k][$sub_setting['id']] = $input[$setting['id'] . '_' . $sub_setting['id'] . '_' . $k];
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                            } else if (is_array($input[$setting['id']]) && $setting['type'] == 'social-links') {
                                
                                /* get the settings array */
                                $settings = isset($_POST[$setting['id'] . '_settings_array']) ? unserialize(wpmdm_import_options_decode($_POST[$setting['id'] . '_settings_array'])) : array();
                                
                                /* settings are empty get the defaults */
                                if (empty($settings)) {
                                    $settings = wpmdm_import_options_social_links_settings($setting['id']);
                                }
                                
                                /* create an empty WPML id array */
                                $wpml_ids = array();
                                
                                foreach ($input[$setting['id']] as $k => $setting_array) {
                                    
                                    foreach ($settings as $sub_setting) {
                                        
                                        /* setup the WPML ID */
                                        $wpml_id = $setting['id'] . '_' . $sub_setting['id'] . '_' . $k;
                                        
                                        /* add id to array */
                                        $wpml_ids[] = $wpml_id;
                                        
                                        /* verify sub setting has a type & value */
                                        if (isset($sub_setting['type']) && isset($input[$setting['id']][$k][$sub_setting['id']])) {
                                            
                                            /* validate setting */
                                            $input[$setting['id']][$k][$sub_setting['id']] = wpmdm_import_options_validate_setting($input[$setting['id']][$k][$sub_setting['id']], $sub_setting['type'], $sub_setting['id'], $wpml_id);
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                            } else {
                                
                                $input[$setting['id']] = wpmdm_import_options_validate_setting($input[$setting['id']], $setting['type'], $setting['id'], $setting['id']);
                                
                            }
                            
                        }
                        
                        /* unregister WPML strings that were deleted from lists and sliders */
                        if (isset($current_settings['settings']) && isset($setting['type']) && in_array($setting['type'], array(
                            'list-item',
                            'slider'
                        ))) {
                            
                            if (!isset($wpml_ids))
                                $wpml_ids = array();
                            
                            foreach ($current_settings['settings'] as $check_setting) {
                                
                                if ($setting['id'] == $check_setting['id'] && !empty($current_options[$setting['id']])) {
                                    
                                    foreach ($current_options[$setting['id']] as $key => $value) {
                                        
                                        foreach ($value as $ckey => $cvalue) {
                                            
                                            $id = $setting['id'] . '_' . $ckey . '_' . $key;
                                            
                                            if (!in_array($id, $wpml_ids)) {
                                                
                                                wpmdm_import_options_wpml_unregister_string($id);
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                            }
                            
                        }
                        
                        /* unregister WPML strings that were deleted from social links */
                        if (isset($current_settings['settings']) && isset($setting['type']) && $setting['type'] == 'social-links') {
                            
                            if (!isset($wpml_ids))
                                $wpml_ids = array();
                            
                            foreach ($current_settings['settings'] as $check_setting) {
                                
                                if ($setting['id'] == $check_setting['id'] && !empty($current_options[$setting['id']])) {
                                    
                                    foreach ($current_options[$setting['id']] as $key => $value) {
                                        
                                        foreach ($value as $ckey => $cvalue) {
                                            
                                            $id = $setting['id'] . '_' . $ckey . '_' . $key;
                                            
                                            if (!in_array($id, $wpml_ids)) {
                                                
                                                wpmdm_import_options_wpml_unregister_string($id);
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
            }
            
            return $input;
            
        }
        
        /**
         * Helper function to get the pages array for an option
         *
         * @param     array     Option array
         * @return    mixed
         *
         * @access    public
         * @since     1.0
         */
        public function get_pages($option = array())
        {
            
            if (empty($option))
                return false;
            
            /* check for pages */
            if (isset($option['pages']) && !empty($option['pages'])) {
                
                /* return pages array */
                return $option['pages'];
                
            }
            
            return false;
        }
        
        /**
         * Helper function to get the sections array for a page
         *
         * @param     array     Page array
         * @return    mixed
         *
         * @access    public
         * @since     1.0
         */
        public function get_sections($page = array())
        {
            
            if (empty($page))
                return false;
            
            /* check for sections */
            if (isset($page['sections']) && !empty($page['sections'])) {
                
                /* return sections array */
                return $page['sections'];
                
            }
            
            return false;
        }
        
        /**
         * Helper function to get the settings array for a page
         *
         * @param     array     Page array
         * @return    mixed
         *
         * @access    public
         * @since     1.0
         */
        public function get_the_settings($page = array())
        {
            
            if (empty($page))
                return false;
            
            /* check for settings */
            if (isset($page['settings']) && !empty($page['settings'])) {
                
                /* return settings array */
                return $page['settings'];
                
            }
            
            return false;
        }
        /**
         * Helper function to get the settings array for a page
         *
         * @param     array     Page array
         * @return    mixed
         *
         * @access    public
         * @since     1.0
         */
        public function get_the_tabs($section_id)
        {
            
            $tabs = array();
            foreach ((array) $this->options as $option) {
                foreach ((array) $this->get_pages($option) as $page) {
                    $sections = $page['sections'];
                    foreach ($sections as $section) {
                        if ($section['id'] == $section_id && isset($section['tabs'])) {
                            $tabs = $section['tabs'];
                        }
                    }
                }
            }
            return $tabs;
        }
        /**
         * Prints out all settings sections added to a particular settings page
         *
         * @global    $wp_settings_sections   Storage array of all settings sections added to admin pages
         * @global    $wp_settings_fields     Storage array of settings fields and info about their pages/sections
         *
         * @param     string    The slug name of the page whos settings sections you want to output
         * @return    string
         *
         * @access    public
         * @since     1.0
         */
        public function do_settings_sections($page)
        {
            global $wp_settings_sections, $wp_settings_fields, $wp_settings_tabs;
            
            if (!isset($wp_settings_sections) || !isset($wp_settings_sections[$page])) {
                return false;
            }
            
            foreach ( (array) $wp_settings_sections[$page] as $section ) {
                
                
                if (!isset($section['id']))
                    continue;
                $section_id = $section['id'];
                $tabs       = $this->get_the_tabs( $section_id );
                
                
                if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section_id]))
                    continue;
                
                echo '<div id="section_' . $section_id . '" class="postbox ui-tabs-panel wpmdm-import-options-section-container">';
                
                
                call_user_func($section['callback'], $section);
                
                
                
                echo '<div class="inside">';
                
                /**
                 * Hook to insert arbitrary markup before the `do_settings_fields` method.
                 *
                 * @since 2.6.0
                 *
                 * @param string $page       The page slug.
                 * @param string $section_id The section ID.
                 */
                do_action('wpmdm_import_options_do_settings_fields_before', $page, $section_id);

                if (count($tabs) > 0) {
                    
                    echo '<div class="wpmdm-import-options-inside-section-tabs-container">';
                    
                    echo '<ul class="wpmdm-import-options-inside-section-tabs-nav">';
                    
                    /* loop through page sections */
                    foreach ((array) $tabs as $tab) {
                        
                        echo '<li><a href="#section_tab_' . $tab['id'] . '">' . $tab['title'] . '</a></li>';
                    }
                    
                    echo '</ul>';
                    
                    
                }
                if (count($tabs) > 0) {
                    
                    echo '<div id="setion-tabs-container">';
                    
                    /* loop through page sections */
                    foreach ((array) $tabs as $tab) {
                        $tab_id = $tab['id'];
                        echo '<div id="section_tab_' . $tab['id'] . '" class="wpmdm-import-options-inside-section-tab-content">';
                        $this->do_settings_fields_tabbed($page, $section_id, $tab_id);
                        echo '</div>';
                    }

                    echo '</div>';

                    echo '</div>'; # close tabs container
                    
                } else {

                    $this->do_settings_fields($page, $section_id);
                    
                }
                
                
                
                /**
                 * Hook to insert arbitrary markup after the `do_settings_fields` method.
                 *
                 * @since 2.6.0
                 *
                 * @param string $page       The page slug.
                 * @param string $section_id The section ID.
                 */
                do_action('wpmdm_import_options_do_settings_fields_after', $page, $section_id);
                
                echo '</div>';
                
                echo '</div>';
                
            }
            
        }
        
        /**
         * Print out the settings fields for a particular settings section
         *
         * @global    $wp_settings_fields Storage array of settings fields and their pages/sections
         *
         * @param     string    $page Slug title of the admin page who's settings fields you want to show.
         * @param     string    $section Slug title of the settings section who's fields you want to show.
         * @return    string
         *
         * @access    public
         * @since     1.0
         */
        public function do_settings_fields($page, $section, $tab_id = null)
        {
            global $wp_settings_fields;
            
            if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]))
                return;
            
            
            foreach ((array) $wp_settings_fields[$page][$section] as $field) {
                $conditions = '';
                
                if (isset($field['args']['condition']) && !empty($field['args']['condition'])) {
                    
                    $conditions = ' data-condition="' . $field['args']['condition'] . '"';
                    $conditions .= isset($field['args']['operator']) && in_array($field['args']['operator'], array(
                        'and',
                        'AND',
                        'or',
                        'OR'
                    )) ? ' data-operator="' . $field['args']['operator'] . '"' : '';
                    
                }
                
                // Build the setting CSS class
                if (isset($field['args']['class']) && !empty($field['args']['class'])) {
                    
                    $classes = explode(' ', $field['args']['class']);
                    
                    
                    
                    $class = 'wpmdm-import-options-format-settings ' . implode(' ', $classes);
                    
                } else {
                    
                    $class = 'wpmdm-import-options-format-settings';
                    
                }
                
                echo '<div id="setting_' . $field['id'] . '" class="' . $class . '"' . $conditions . '>';
                
                echo '<div class="wpmdm-import-options-format-setting-wrap">';

                if ($field['args']['type'] != 'textblock' && !empty($field['title'])) {
                    
                    echo '<div class="wpmdm-import-options-format-setting-label">';
                    
                    echo '<h3 class="label">' . $field['title'] . '</h3>';
                    
                    echo '</div>';
                    
                }
                
                call_user_func($field['callback'], $field['args']);
                
                echo '</div>';
                
                echo '</div>';
                
            }
            
        }
        public function do_settings_fields_tabbed($page, $section, $tab_id = null)
        {
            global $wp_settings_fields;
            
            if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]))
                return;
            
            
            foreach ((array) $wp_settings_fields[$page][$section] as $field) {
                if (isset($field['args']['tab']) && isset($tab_id) && $field['args']['tab'] == $tab_id) {
                    $conditions = '';
                    
                    if (isset($field['args']['condition']) && !empty($field['args']['condition'])) {
                        
                        $conditions = ' data-condition="' . $field['args']['condition'] . '"';
                        $conditions .= isset($field['args']['operator']) && in_array($field['args']['operator'], array(
                            'and',
                            'AND',
                            'or',
                            'OR'
                        )) ? ' data-operator="' . $field['args']['operator'] . '"' : '';
                        
                    }
                    
                    // Build the setting CSS class
                    if (isset($field['args']['class']) && !empty($field['args']['class'])) {
                        
                        $classes = explode(' ', $field['args']['class']);
                        
                        foreach ($classes as $key => $value) {
                            
                            $classes[$key] = $value . '-wrap';
                            
                        }
                        
                        $class = 'wpmdm-import-options-format-settings ' . implode(' ', $classes);
                        
                    } else {
                        
                        $class = 'wpmdm-import-options-format-settings';
                        
                    }
                    
                    echo '<div id="setting_' . $field['id'] . '" class="' . $class . '"' . $conditions . '>';
                    
                    echo '<div class="wpmdm-import-options-format-setting-wrap">';

                    if ($field['args']['type'] != 'textblock' && !empty($field['title'])) {
                        
                        echo '<div class="wpmdm-import-options-format-setting-label">';
                        
                        echo '<h3 class="label">' . $field['title'] . '</h3>';
                        
                        echo '</div>';
                        
                    }
                    
                    call_user_func($field['callback'], $field['args']);
                    
                    echo '</div>';
                    
                    echo '</div>';
                }
                
                
            }
            
        }
        
        /**
         * Resets page options before the screen is displayed
         *
         * @return    void
         *
         * @access    public
         * @since     1.0
         */
        public function wpmdm_import_options_reset_options()
        {
            
            /* check for reset action */
            if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wpmdm_import_options_reset_form')) {
                /* loop through options */
                foreach ((array) $this->options as $option) {
                    
                    /* loop through pages */
                    foreach ((array) $this->get_pages($option) as $page) {
                        
                        /* verify page */
                        if (isset($_POST['page']) && $_POST['page'] == $page['menu_slug']) {
                            
                            /* reset options */
                            delete_option($option['id']);
                            
                        }
                        
                    }
                    
                }
                
            }
            
            return false;
            
        }
        
    }
    
}

/**
 * This method instantiates the settings class & builds the UI.
 *
 * @uses     wpmdm_import_options_Settings()
 *
 * @param    array    Array of arguments to create settings
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if (!function_exists('wpmdm_import_options_register_settings')) {
    
    function wpmdm_import_options_register_settings($args, $framework = null)
    {
        if (!$args)
            return;
        
        $wpmdm_import_options_settings = new wpmdm_import_options_Settings($args, $framework);
    }
    
}

/* End of file wpmdm-import-options-settings-api.php */
/* Location: ./includes/wpmdm-import-options-settings-api.php */
