<?php if ( ! defined( 'THEME_VERSION' ) ) {
  die( 'Forbidden' );
}

function merimag_get_sidebar_id_from_widget_id( $widget_id )
{
   
    $sidebars = wp_get_sidebars_widgets();
    foreach( (array) $sidebars as $sidebar_id => $sidebar )
    {
        if( in_array( $widget_id, (array) $sidebar, true ) )
            return $sidebar_id;
    }
    return null;
}
function merimag_in_widget_form($t,$return,$instance){
	if( defined('FW') ) {
		$sidebar_id = merimag_get_sidebar_id_from_widget_id( $t->id );
		$widget_settings = 	fw()->theme->get_options('widget');
	    $prefix = 'styling_options';
	    $id = 'fw-widget-options-'. merimag_uniqid( $prefix );
	    // Print our options
	    echo '<div class="fw-force-xs fw-theme-admin-widget-wrap" id="'. esc_attr($id) .'">';
	    echo fw()->backend->render_options($widget_settings, isset( $instance[$prefix] ) ? $instance[$prefix] : null, array(
	        'id_prefix' => $id .'-',
	        'name_prefix' => $t->get_field_name($prefix),
	    ));
	    echo '</div>';
    }
    $retrun = null;
    return array($t,$return,$instance);
}
function merimag_in_widget_form_update($instance, $new_instance, $old_instance, $t ){
	if( defined('FW') ) {
		$sidebar_id = merimag_get_sidebar_id_from_widget_id( $t->id );
			$widget_settings = 	fw()->theme->get_options( 'widget' );
			$prefix = 'styling_options';
			$test = fw_get_options_values_from_input(
		            $widget_settings,
		            FW_Request::POST(fw_html_attr_name_to_array_multi_key($t->get_field_name( $prefix )), $instance)
		        );
			$instance[$prefix] = $test;
	}
    return $instance;
}
function merimag_dynamic_sidebar_params($params){
    global $wp_registered_widgets;
    $sidebar_id = isset( $params[0]['id'] ) ? $params[0]['id'] : false;
    $widget_id  = $params[0]['widget_id'];
	$widget_obj = $wp_registered_widgets[$widget_id];
	$widget_opt = get_option($widget_obj['callback'][0]->option_name);
	$widget_num = $widget_obj['params'][0]['number'];
    $get_block_title_style_from = $sidebar_id === 'footer-sidebar' ? 'footer_widget' : 'widget';
    $block_title_style = isset( $widget_opt[$widget_num]['styling_options']['styling']['block_title_style'] ) ? $widget_opt[$widget_num]['styling_options']['styling']['block_title_style'] : 'default';
    $block_title_style = merimag_get_block_title_style( $block_title_style, $get_block_title_style_from );
    $params[0] = array_replace($params[0], array('before_title' => str_replace("block-title-wrapper", sprintf("block-title-wrapper %s", $block_title_style), $params[0]['before_title'])));
    $selector 	= '#' . $widget_id . '.sidebar-widget';
    if( isset( $widget_opt[$widget_num]['styling_options']['styling']['ignore_general_style'] ) && $widget_opt[$widget_num]['styling_options']['styling']['ignore_general_style'] === 'yes' ) {
        $params[0] = array_replace($params[0], array('before_widget' => str_replace("sidebar-widget", "sidebar-widget ignore-general-style", $params[0]['before_widget'])));
    }
    $block_css  = isset( $widget_opt[$widget_num]['styling_options']['styling'] ) && is_array( $widget_opt[$widget_num]['styling_options']['styling'] ) ? merimag_get_dynamic_block_style( $widget_opt[$widget_num]['styling_options']['styling'], $selector ) : merimag_get_dynamic_block_style( 'general_widget', $selector );
    merimag_render_css( $block_css );
    return $params;
}
//Add input fields(priority 5, 3 parameters)
add_action('in_widget_form', 'merimag_in_widget_form',10,3);
 //Callback function for options update (priorität 5, 3 parameters)
add_filter('widget_update_callback', 'merimag_in_widget_form_update',10,4);
//add class names (default priority, one parameter)
add_filter('dynamic_sidebar_params', 'merimag_dynamic_sidebar_params');


