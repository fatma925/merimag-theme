<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Tranquility - the highest manifestation of power!' ); }
 
class Merimag_Recent_Comments extends WP_Widget {
 
    /**
     * Widget constructor.
     */
    private $options;
    private $prefix;
    function __construct() {
 
        $widget_ops = array( 'description' => __( 'Dispaly recent comment with author avatar display ', 'merimag' ) );
        parent::__construct( false, __( 'Recent comments', 'merimag' ), $widget_ops );
        
        //Create our options by using merimag option types
        $this->options = merimag_get_comments_options();
        $this->prefix = 'merimag_recent_comments';
    }
 
    function widget( $argss, $instance ) {

        echo $argss['before_widget'];
        $title = isset( $instance['title'] ) ? $instance['title'] : '';

        $atts  = isset( $instance ) ? $instance : array();
        echo $title ? $argss['before_title'] . esc_attr( apply_filters( 'merimag_filter_block_title', $title ) ) . $argss['after_title'] : '';
        $this->view( $atts );

        echo $argss['after_widget'];
    }
    
    function view( $atts ) {

        merimag_get_shortcode_html('comments', $atts );
    }

    function update( $new_instance, $old_instance ) {
        return fw_get_options_values_from_input(
            $this->options,
            FW_Request::POST(fw_html_attr_name_to_array_multi_key($this->get_field_name($this->prefix)), array())
        );
    }
 
    function form( $values ) {
 
        $prefix = $this->get_field_id($this->prefix); // Get unique prefix, preventing dupplicated key
        $id = 'fw-widget-options-'. $prefix;
 
        // Print our options
        echo '<div class="fw-force-xs fw-theme-admin-widget-wrap" id="'. esc_attr($id) .'">';
        
        echo fw()->backend->render_options($this->options, $values, array(
            'id_prefix' => $prefix .'-',
            'name_prefix' => $this->get_field_name($this->prefix),
        ));
        echo '</div>';
        return $values;
    }
  
}

add_action( 'widgets_init', function() { return register_widget("Merimag_Recent_Comments"); } );