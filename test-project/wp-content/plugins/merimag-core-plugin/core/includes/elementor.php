<?php
function merimag_unyson_settings_to_elementor( $widget, $settings, $post_type = 'post') {

	foreach( (array) $settings as $settings_id => $setting ) {
		$control_settings = false;
		if( !isset( $setting['type'] ) ) {
			continue;
		}
		$exclude = array(
			'background_gradient',
			'background_image',
			'background_position',
			'background_repeat',
			'background_attachment',
			'background_size',
			'border_color',
			'border_style',
		);
		if( in_array($settings_id, $exclude ) ) {
			continue;
		}
		if( isset($setting['elementor_type'] ) ) {
			$setting['type'] = $setting['elementor_type'];
		}
		
		switch ( $setting['type'] ) {
			case 'tab':
				$widget->start_controls_section(
					$settings_id,
					[
						'label' => $setting['title'],
						'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
						'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					]
				);

				merimag_unyson_settings_to_elementor( $widget, $setting['options'] );

				$widget->end_controls_section();
				break;
			
			case 'heading':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::HEADING,
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'typography-v3':
				$selector = isset( $setting['selector'] ) && is_string( $setting['selector'] ) ? $setting['selector'] : '';
				$widget->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => $settings_id,
						'label' => isset( $setting['label'] ) ? $setting['label'] : '',
						'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
						'selector' => '{{WRAPPER}} ' . $selector,
					]
				);
				break;
			case 'upload':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::MEDIA,
					'media_type' => isset( $setting['media_type'] ) && $setting['media_type'] === 'video' ? 'video' : 'image',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'divider':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::DIVIDER,
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'margin':
				$selector = isset( $setting['selector'] ) ? $setting['selector'] : '';
				$widget->add_control(
					$settings_id,
					[
						'label' => isset( $setting['label'] ) ? $setting['label'] : '',
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} ' . esc_attr( $selector ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				break;
			case 'padding':
				$selector = isset( $setting['selector'] ) ? $setting['selector'] : '';
				$widget->add_control(
					$settings_id,
					[
						'label' => isset( $setting['label'] ) ? $setting['label'] : '',
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} ' . esc_attr( $selector ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				break;
			case 'gradient-v2':
			case 'background':
				$selector = isset( $setting['selector'] ) ? $setting['selector'] : '';
				$selectors = explode(',', $selector);
				$multi_selector = '';
				$multi_selector_hover = '';
				foreach ((array)$selectors as $k => $select ) {
					$multi_selector .= $k === 0 ? '{{WRAPPER}} ' . $select : ', {{WRAPPER}} ' . $select;
					$multi_selector_hover .= $k === 0 ? '{{WRAPPER}} ' . $select . ':hover' : ', {{WRAPPER}} ' . $select . ':hover';
				}
				if( !$multi_selector ) {
					$multi_selector = $selector;
					$multi_selector_hover = $selector . ':hover';
				}
				if( isset( $setting['hover'] ) && $setting['hover'] === true ) {
					$widget->start_controls_tabs(
						$settings_id. '_states_tab'
					);
					$widget->start_controls_tab(
						$settings_id . '_normal_state',
						[
							'label' => __( 'Normal', 'merimag' ),
						]
					);
					$widget->add_group_control(
						\Elementor\Group_Control_Background::get_type(),
						[
							'name' => $settings_id,
							'label' => isset( $setting['label'] ) ? $setting['label'] : '',
							'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
							'types' => isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : [ 'classic', 'gradient' ],
							'selector' => $multi_selector ? $multi_selector : '{{WRAPPER}}',
						]
					);
					$widget->end_controls_tab();
					$widget->start_controls_tab(
						$settings_id . '_hover_state',
						[
							'label' => __( 'Hover', 'merimag' ),
						]
					);
					$widget->add_group_control(
						\Elementor\Group_Control_Background::get_type(),
						[
							'name' => $settings_id . '_hover',
							'label' => isset( $setting['label'] ) ? $setting['label'] : '',
							'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
							'types' => isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : [ 'classic', 'gradient' ],
							'selector' => $multi_selector_hover ? $multi_selector_hover  : '{{WRAPPER}}:hover',
						]
					);
					$widget->end_controls_tab();
					$widget->end_controls_tabs();
				} else {
					$widget->add_group_control(
						\Elementor\Group_Control_Background::get_type(),
						[
							'name' => $settings_id,
							'label' => isset( $setting['label'] ) ? $setting['label'] : '',
							'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
							'types' => isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : [ 'classic', 'gradient' ],
							'selector' => $multi_selector ? $multi_selector : '{{WRAPPER}}',
						]
					);
					
				}
				break;
			case 'border':
				if( isset( $setting['hover'] ) && $setting['hover'] === true ) {
					$widget->start_controls_tabs(
						$settings_id. '_states_tab'
					);
					$widget->start_controls_tab(
						$settings_id . '_normal_state',
						[
							'label' => __( 'Normal', 'merimag' ),
						]
					);
					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => $settings_id,
							'label' => isset( $setting['label'] ) ? $setting['label'] : '',
							'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
							'types' => isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : [ 'classic', 'gradient' ],
							'selector' => isset( $setting['selector'] ) ? '{{WRAPPER}} ' . $setting['selector'] : '{{WRAPPER}}',
						]
					);
					$widget->end_controls_tab();
					$widget->start_controls_tab(
						$settings_id . '_hover_state',
						[
							'label' => __( 'Hover', 'merimag' ),
						]
					);
					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => $settings_id . '_hover',
							'label' => isset( $setting['label'] ) ? $setting['label'] : '',
							'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
							'types' => isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : [ 'classic', 'gradient' ],
							'selector' => isset( $setting['selector'] ) ? '{{WRAPPER}} ' . $setting['selector'] . ':hover' : '{{WRAPPER}}',
						]
					);
					$widget->end_controls_tab();
					$widget->end_controls_tabs();
				} else {

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => $settings_id,
							'label' => isset( $setting['label'] ) ? $setting['label'] : '',
							'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
							'types' => isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : ( isset( $setting['bg_types'] ) && is_array( ['bg_types'] ) ? $setting['bg_types'] : [ 'classic', 'gradient' ] ),
							'selector' => isset( $setting['selector'] ) ? '{{WRAPPER}} ' . $setting['selector'] : '{{WRAPPER}}',
						]
					);
				}
				break;
			case 'addable-popup':
			case 'addable-box':
				$repeater = new \Elementor\Repeater();
				$repeater_options = $setting['type'] === 'addable-box' ? $setting['box-options'] : $setting['popup-options'];
				foreach( $repeater_options as $repearer_oid => $repeater_option ) {
					if( isset( $repeater_option['selector'] ) ) {
						$repeater_options[$repearer_oid]['selector'] = '{{CURRENT_ITEM}}' . $repeater_option['selector'];
					}
				}
				merimag_unyson_settings_to_elementor( $repeater, $repeater_options );
					$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'prevent_empty' => false,
					'default' => isset( $setting['value'] ) && is_array( $setting['value'] ) ? $setting['value'] : array(),
					'title_field' => isset( $setting['title_field'] ) ? $setting['title_field'] : '',
				];
				
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'text':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::TEXT,
					'input_type' => 'text',
					'default' => isset( $setting['value'] ) ? $setting['value'] : '',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'hidden':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::HIDDEN,
					'default' => isset( $setting['value'] ) ? $setting['value'] : '',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'icon-v2':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => isset( $setting['value'] ) ? $setting['value'] : array(),
				];

				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'wp-editor':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::WYSIWYG,
					'default' => isset( $setting['value'] ) ? $setting['value'] : '',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'wplink':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::URL,
					'default' => isset( $setting['value'] ) ? $setting['value'] : array(),
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'color-picker-v2':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'alpha' => isset( $setting['rgba'] ) && is_bool( $setting['rgba'] ) ? $setting['rgba'] : false,
					'default' => isset( $setting['value'] ) ? $setting['value'] : '',
					'selectors' => isset( $setting['selector'] ) ? array('{{WRAPPER}} ' . $setting['selector'] => 'color: {{VALUE}}') : array(),
				];	
				
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'textarea':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'default' => isset( $setting['value'] ) ? $setting['value'] : '',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'number':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::NUMBER,
					'input_type' => 'number',
					'min' => isset( $setting['min'] ) ? $setting['min'] : false,
					'mix' => isset( $setting['max'] ) ? $setting['max'] : false,
					'step' => isset( $setting['step'] ) ? $setting['step'] : false,
					'default' => isset( $setting['value'] ) ? $setting['value'] : false,
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'radio':
				
		    	$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => isset( $setting['choices'] ) ? $setting['choices'] : array(),
					'default' => isset( $setting['value'] ) ? $setting['value'] : key($setting['choices']),
				];
			    
			    $widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'multi-upload':
		    	$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::GALLERY,
				];
			    
			    $widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'slider':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::NUMBER,
					'input_type' => 'number',
					'min' => isset( $setting['min'] ) ? $setting['min'] : false,
					'mix' => isset( $setting['max'] ) ? $setting['max'] : false,
					'step' => isset( $setting['step'] ) ? $setting['step'] : false,
					'default' => isset( $setting['value'] ) ? $setting['value'] : '',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'select':
			    
		    	$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => isset( $setting['choices'] ) ? $setting['choices'] : array(),
					'default' => isset( $setting['value'] ) ? $setting['value'] : key($setting['choices']),
				];
			    
			    $widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'image-picker': 
				$choices = array();
				if( isset( $setting['choices'] ) ) {
					foreach ( $setting['choices'] as $key => $value) {
						$label_key = merimag_get_elementor_image_picker_label( $key );
						$choices[ $key ] = $label_key ? $label_key : ucfirst( str_replace('-', ' ', $key ) );
					}
				}
				asort($choices);
				$control_settings = [
					'label' =>  isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' =>  \Elementor\Controls_Manager::SELECT,
					'options' => $choices,
					'default' => isset( $setting['value'] ) ? $setting['value'] : key($setting['choices']),
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			
			case 'switch':
				$control_settings = [
					'label' => isset( $setting['label'] ) ? $setting['label'] : '',
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'merimag' ),
					'label_off' => __( 'No', 'merimag' ),
					'return_value' => 'yes',
					'default' => isset( $setting['value'] ) ? $setting['value'] : 'no',
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			case 'multi-select':
				$choices = array();
				if( isset( $setting['source'] ) && isset( $setting['population'] ) ) {
					switch ( $setting['population'] ) {
						case 'taxonomy':
							if( taxonomy_exists( $setting['source'] ) ) {
								$choices = merimag_get_terms($setting['source'] );
								
							}
							break;
						case 'posts':
							if( post_type_exists( $setting['source'] ) ) {
								$choices = merimag_get_posts($setting['source']);
							}
							break;
					}
				} else {
					$choices = isset( $setting['choices'] ) ? $setting['choices'] : null;
				}
				$control_settings = [
					'label' =>  isset( $setting['label'] ) ? $setting['label'] : '',
					'type' => \Elementor\Controls_Manager::SELECT2,
					'condition' => isset( $setting['condition'] ) && !empty( $setting['condition'] ) ? $setting['condition'] : false,
					'options' => isset( $choices ) ? $choices : array(),
					'default' => isset( $setting['value'] ) ? $setting['value'] : null,
					'multiple' => isset( $setting['limit'] ) && $setting['limit'] == 1 ? false : true,
					'label_block' => true,
				];
				
				$widget->add_control(
					$settings_id,
					$control_settings
				);
				break;
			default:
				# code...
				break;
		}
	} 
}


# ELEMENTOR WIDGETS

function merimag_elementor_register_wigdets() {
	require_once( MERIMAG_CORE_DIR . '/includes/elementor-widgets.php');
	$shortcodes = array_unique( merimag_get_shortcodes_list( true ) );
		
	foreach( $shortcodes as $shortcode ) {
		$shortcode = str_replace('-', '_', $shortcode);
		$shortcode_widget = 'Merimag_Elementor_' . $shortcode;
		if( class_exists($shortcode_widget) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $shortcode_widget() );
		}
	}
}
add_action( 'elementor/widgets/widgets_registered', 'merimag_elementor_register_wigdets' );

add_action(	'elementor/element/parse_css', function( $post_css, $element ) {
	$id = $post_css->get_post_id();
	$post_id = get_the_ID();
	if( $id != $post_id ) {
		return;
	}
 	$shortcode = str_replace('merimag-', '', $element->get_name());
	$shortcode = str_replace('_', '-', $shortcode);
	$atts 	   = $element->get_settings();
	$element_id = $element->get_id();
	$atts['block_id'] = 'merimag-element-' .$element_id;
	$selector   = '#' . $atts['block_id'];
	$block_css  = isset( $atts ) && is_array( $atts ) ? merimag_get_dynamic_block_style( $atts, $selector ) : merimag_get_dynamic_block_style( 'general_block', $selector );
	$block_css .= merimag_get_shortcode_css_parse( $shortcode, $atts );

	add_action('wp_enqueue_scripts', function() use( $block_css ) {
		wp_add_inline_style( 'merimag-dynamic-css', $block_css );
	}, 99);

}, 10, 2 );
/**
 * Add custom theme builder sections to list used blocks
 *
 * @return void
 */
function merimag_builder_sections_css() {
	if (!class_exists("\\Elementor\\Plugin")) {
		return;
	}
    $sections = apply_filters('elementor_builder_sections', array());
    foreach( $sections as $section => $label ) {
        $builder_page = merimag_get_db_customizer_option('builder_section_' . $section );
        if( isset( $builder_page[0] ) && get_post_type( $builder_page[0] ) === 'builder_section' ) {
            if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                $has_elementor_in_page = true;
                $post_id = $builder_page[0];
                // Check $post_id for virtual pages. check is singular because the $post_id is set to the first post on archive pages.
                if ( $post_id ) {
                    $css_file = \Elementor\Core\Files\CSS\Post::create( $post_id );
                    $css_file->enqueue();
                }
            }
        }
    }
    $mega_menus = merimag_get_posts('mega_menu');
    if( is_array($mega_menus) ) {
    	foreach( $mega_menus as $post_id => $mega_menu ) {
    		if ( ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
	    		if ( $post_id ) {
	                $css_file = \Elementor\Core\Files\CSS\Post::create( $post_id );
	                $css_file->enqueue();
	            }
            }
    	}
    }
    if( isset($has_elementor_in_page) ) {
    	$scheme_css_file = \Elementor\Core\Files\CSS\Global_CSS::create( 'global.css' );
        $scheme_css_file->enqueue();
    }
}
/**
 * Add custom post type elementor support
 *
 * @return void
 */
function merimag_add_cpt_support() {
    
    //if exists, assign to $cpt_support var
	$cpt_support = get_option( 'elementor_cpt_support' );
	//check if option DOESN'T exist in db
	if( ! $cpt_support ) {
	    $cpt_support = [ 'page', 'post', 'builder_section', 'mega_menu' ]; //create array of our default supported post types
	    update_option( 'elementor_cpt_support', $cpt_support ); //write it to the database
	}
	
	//if it DOES exist, but portfolio is NOT defined
	else if( ! in_array( 'builder_section', $cpt_support ) || ! in_array( 'mega_menu', $cpt_support ) ) {
	    $cpt_support[] = 'builder_section'; //append to array
	    $cpt_support[] = 'mega_menu'; //append to array
	    update_option( 'elementor_cpt_support', $cpt_support ); //update database
	}
	
	//otherwise do nothing, portfolio already exists in elementor_cpt_support option
}
add_action( 'after_setup_theme', 'merimag_add_cpt_support' );
function merimag_add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'merimag',
		[
			'title' => __( 'Merimag', 'merimag-backend' ),
			'icon' => 'fa fa-plug',
		]
	);


}
add_action( 'elementor/elements/categories_registered', 'merimag_add_elementor_widget_categories' );

function merimag_add_elementor_editor_css() {
	wp_enqueue_style( 'merimag-elementor-editor-css', MERIMAG_CORE_URL . '/assets/css/elementor.css', array(), MERIMAG_CORE_VERSION );

}
add_action( 'elementor/editor/before_enqueue_scripts', 'merimag_add_elementor_editor_css' );