<?php if (!defined('FW')) die('Forbidden');

class FW_Ext_Mega_Menu_Custom_Walker extends FW_Ext_Mega_Menu_Walker
 {
     function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		if ( !$element )
			return;

		$id_field 	= $this->db_fields['id'];

		$id        	= $element->$id_field;
		$is_header  = isset( $args[0]->is_header ) && $args[0]->is_header === true ? true : false;
		$item_type 	= fw_ext_mega_menu_get_db_item_option($element->ID,'type');
		$menu_content = false;
		if($item_type == 'row'){
			$menu_content = fw_ext_mega_menu_get_db_item_option($element->ID,'row/menu_content');
			$menu_data 	  = fw_ext_mega_menu_get_db_item_option($element->ID,'row/menu_grid_data');
			if( $menu_content === 'mega_menu' && isset( $menu_data['mega_menu']['mega_menu'] ) ) {
				$mega_menu_custom_content = $menu_data['mega_menu']['mega_menu'];
			}
		}

		//display this element
		$this->has_children = ! empty( $children_elements[ $id ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
		   $args[0]['has_children'] = $this->has_children; // Backwards compatibility.
		}
		
		if( $menu_content !== 'default' && $is_header === true && $item_type == 'row' ) {
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array($this, 'start_el'), $cb_args);
			$this->unset_children( $element, $children_elements );
			$css_id = uniqid('merimag-menu-item-');
			$style_atts = merimag_get_menu_block_atts($element->ID);
			$menu_css = merimag_get_dynamic_block_style( $style_atts, '#' . $css_id );
			wp_register_style( 'merimag-blocks-dynamic-css-' . $css_id, false );
			wp_enqueue_style( 'merimag-blocks-dynamic-css-' . $css_id );
			wp_add_inline_style( 'merimag-blocks-dynamic-css-' . $css_id, $menu_css );
			
			if( isset( $mega_menu_custom_content[0]  ) &&  get_post_type( $mega_menu_custom_content[0] ) === 'mega_menu' ) {
				$output .= '<div id="' . esc_attr( $css_id ) . '" class="custom-content-mega-menu mega-menu-full mega-menu site-content-width sub-menu">';
				if (class_exists("\\Elementor\\Plugin")) {
		            $output .= \Elementor\Plugin::$instance->frontend->get_builder_content( $mega_menu_custom_content[0], true );
		            
		        }
			} else {
				if( $menu_content === 'category_grid' && isset( $menu_data['category_grid'] )) {
					$columns = isset( $menu_data['category_grid']['grid_columns']) ? $menu_data['category_grid']['grid_columns'] : '4';
					$filters = isset( $menu_data['category_grid']['filters']) ? $menu_data['category_grid']['filters'] : 'default';
					$pagination = isset( $menu_data['category_grid']['pagination']) ? $menu_data['category_grid']['pagination'] : 'yes';
					$random = isset( $menu_data['category_grid']['random']) ? $menu_data['category_grid']['random'] : 'yes';
					if(  in_array($element->object, array('category', 'product_cat')) ) {
						$children_categories = get_terms($element->object, 
						  array(
						    'parent' => $element->object_id
						  )
						);
					} else {
						$children_categories = get_terms('category');
					}
					$filters_class = $filters === 'default' || $filters === 'side_filters' ? 'mega-menu-no-padding' : 'mega-menu-padding';
					$output .= '<div id="' . esc_attr( $css_id ) . '" class="custom-content-mega-menu mega-menu-full mega-menu site-content-width sub-menu ' . esc_attr( $filters_class ) . '">';
					$cat_title = get_term($element->object_id);
					$cat_title = isset( $cat_title->name ) ? $cat_title->name : __('All', 'merimag');
					$sub_categories = array();
					if( $children_categories && is_array( $children_categories ) ) {
						foreach( $children_categories as $sub_cat ) {
							$sub_categories[] = $sub_cat->term_id;
						}
					}
					$atts['columns'] = $columns;
					$atts['pagination'] = $pagination === 'yes' ? 'next_prev' : 'default';
					$atts['order_by'] = $element->object === 'product_cat' ? 'popularity' : 'date';
					$atts['order_by'] = $random === 'yes' ? 'rand' : $atts['order_by'];
					$atts['order'] = 'desc';
					$atts['number'] = intval($columns);
					$atts['title_ellipsis'] = $element->object === 'product_cat' ? 3  : 2;
					$atts['after_title'] = $element->object === 'product_cat' ? 'price|product_rating' : 'date|comments';
					$atts['ignore_general_style'] = 'yes';
					$atts['after_title'] = $element->object === 'category' && $columns === '5' ? 'date' : $atts['after_title'];
					$atts['title_size'] = $filters === 'default' || $filters === 'side_filters' ? ($columns === '5' ? 'tiny' : 'small' ) : ($columns === '5' ? 'small' : 'normal' );
					$atts['mouseover_load'] = 'yes';
					if( $filters === 'title_filters') {
						$atts['title'] = $cat_title;
					} 
					if( $filters === 'title_filters' ) {
						$atts['filters_style'] = 'beside_title';
					}
					if( $filters === 'side_filters' ) {
						$atts['filters_style'] = 'vertical_tabs';
					}
					if( $filters === 'top_filters' ) {
						$atts['filters_style'] = 'buttons';
					}
					if( $filters === 'default' ) {
						$atts['filters_style'] = 'vertical_tabs';
					}
					if(  in_array($element->object, array('category', 'product_cat')) ) {
						if( $element->object === 'product_cat' ) {
							$atts['product_cat'][] = $element->object_id;
						} else {
							$atts['category'][] = $element->object_id;
						}
					}
					if( is_array( $sub_categories ) && count( $sub_categories ) > 0 && $filters !== 'no_filters' ) {
						$atts['filter_title'] = $filters !== 'title_filters' ? $cat_title : __('All', 'merimag');
						foreach( $sub_categories as $sub_cat ) {
							$sub_cat_title = get_term($sub_cat);
							$sub_cat_title = isset( $sub_cat_title->name ) ? $sub_cat_title->name : __('Filter', 'merimag');
							$atts['filters'][] = array(
								$element->object => array( $sub_cat ),
								'order_by' => $atts['order_by'],
								'filter_title' => $sub_cat_title,
								'order' => 'desc',
							);
						}
					}
					
					$shortcode = $element->object === 'product_cat' ? 'products-grid' : 'posts-grid';
					$output .= merimag_shortcode_html( $shortcode, $atts );
				} elseif( $menu_content === 'demo_features' ) {
					$output .= '<div id="' . esc_attr( $css_id ) . '" class="custom-content-mega-menu mega-menu-full mega-menu site-content-width sub-menu">';
					$features_content = '';
					$output .= apply_filters('wpmdm_demo_features', $features_content);
				} else {
					$output .= '<div id="' . esc_attr( $css_id ) . '" class="custom-content-mega-menu mega-menu-full mega-menu site-content-width sub-menu">';
				}
			}
			$output .= '</div>';
		} else {
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array($this, 'start_el'), $cb_args);
		}
		


		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){
# BEGIN - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				if ($depth == 0 && fw_ext_mega_menu_get_meta($id, 'enabled') && fw_ext_mega_menu_get_meta($child, 'new-row')) {
					if (isset($newlevel) && $newlevel) {
						$cb_args = array_merge( array(&$output, $depth), $args);
						call_user_func_array(array($this, 'end_lvl'), $cb_args);
						unset($newlevel);
					}
				}
# END - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				if ( !isset($newlevel) ) {
					$newlevel = true;
# BEGIN - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					if (!isset($mega_menu_container) && $depth == 0 && fw_ext_mega_menu_get_meta($id, 'enabled') ) {
						$css_id = uniqid('merimag-menu-item-');
						$menu_content = fw_ext_mega_menu_get_db_item_option($element->ID,'row/menu_content');
						
						$mega_menu_container = apply_filters('fw_ext_mega_menu_container', array(
							'tag'  => 'div',
							'attr' => array( 'class' => 'mega-menu site-content-width sub-menu', 'id' => $css_id )
						), array(
							'element' => $element,
							'children_elements' => $children_elements,
							'max_depth' => $max_depth,
							'depth' => $depth,
							'args' => $args,
						));
						$style_atts = merimag_get_menu_block_atts($element->ID);
						$menu_css = merimag_get_dynamic_block_style( $style_atts, '#' . $css_id );
						wp_register_style( 'merimag-blocks-dynamic-css-' . $css_id, false );
						wp_enqueue_style( 'merimag-blocks-dynamic-css-' . $css_id );
						wp_add_inline_style( 'merimag-blocks-dynamic-css-' . $css_id, $menu_css );
						$output .= '<'. $mega_menu_container['tag'] . ' ' . fw_attr_to_html($mega_menu_container['attr']) .'>';
					}

					$classes = array('sub-menu' => true);
					if (isset($mega_menu_container)) {
						if ($this->row_contains_icons($element, $child, $children_elements)) {
							$classes['sub-menu-has-icons'] = true;
						}
						$menu_data 	  = fw_ext_mega_menu_get_db_item_option($element->ID,'row/menu_grid_data');
						$column_width = isset( $menu_data['default']['column_width'] ) && is_string( $menu_data['default']['column_width'] ) ? $menu_data['default']['column_width'] : '4';
						$classes['mega-menu-row'] = true;
						$classes['mega-menu-row-' . esc_attr( $column_width ) ] = true;
						unset($classes['sub-menu']);
					}
					else {
						if ($this->sub_menu_contains_icons($element, $children_elements)) {
							$classes['sub-menu-has-icons'] = true;
						}
					}
					$classes = apply_filters('fw_ext_mega_menu_start_lvl_classes', $classes, array(
						'element' => $element,
						'children_elements' => $children_elements,
						'max_depth' => $max_depth,
						'depth' => $depth,
						'args' => $args,
						'mega_menu_container' => isset($mega_menu_container) ? $mega_menu_container : false
					));
					$classes = array_filter($classes);
# END - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					//start the child delimiter
# BEGIN - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					//$cb_args = array_merge( array(&$output, $depth), $args);
					$cb_args = array_merge( array(&$output, $depth), $args, array(
						implode(' ', array_keys($classes))
					));
# END - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					call_user_func_array(array($this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array($this, 'end_lvl'), $cb_args);
		}

# BEGIN - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		if (isset($mega_menu_container)) {
			$output .= '</'. $mega_menu_container['tag'] .'>';
		}
# END - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array($this, 'end_el'), $cb_args);
	}

     // other customizations ...
 }
 function merimag_get_menu_background_css($id) {
 	$backround['background-color'] = fw_ext_mega_menu_get_db_item_option($id,'row/background_color');
 	$backround['background-image'] = fw_ext_mega_menu_get_db_item_option($id,'row/background_image');
 	$backround['background-image'] = isset( $backround['background-image']['url'] ) && $backround['background-image']['url'] ? $backround['background-image']['url'] : false;
 	$backround['background-size'] = fw_ext_mega_menu_get_db_item_option($id,'row/background_size');
 	$backround['background-repeat'] = fw_ext_mega_menu_get_db_item_option($id,'row/background_repeat');
 	$backround['background-attachment'] = fw_ext_mega_menu_get_db_item_option($id,'row/background_attachment');
 	$backround['background-position'] = fw_ext_mega_menu_get_db_item_option($id,'row/background_position');
 	$css = '';
 	foreach( $backround as $key => $value ) {
 		if( $value ) {
 			$css .= $key !== 'background-image' ? $key . ':' . $value . ';' : $key . ':url(' . $value . ');';
 		}
 	}
 	return 'style="' . $css . '"';
 }