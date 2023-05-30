<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Tranquility - the highest manifestation of power!' ); }
 
class Merimag_Posts_Mix_Widget extends WP_Widget {
 
    /**
     * Widget constructor.
     */
    private $options;
    private $prefix;
    function __construct() {
 
        $widget_ops = array( 'description' => __( 'Display posts with multiple layouts', 'merimag' ) );
        parent::__construct( false, __( 'Posts Mix Widget', 'merimag' ), $widget_ops );
        
        //Create our options by using merimag option types
        $this->options = merimag_get_posts_block_options_for_widget();
        $this->prefix = 'merimag_posts_mix_widget';
    }
 
    function widget( $argss, $instance ) {

        echo $argss['before_widget'];

        $title = isset( $instance['general']['title'] ) ? $instance['general']['title'] : '';

        $atts = array_merge( $instance['general'],  $instance['query']);
        $atts['is_footer'] = isset( $argss['id'] ) && $argss['id'] === 'footer-sidebar' ? true : false;

        $atts['ignore_general_style'] = 'yes';

        $atts['block_title_style'] = isset( $instance['styling_options']['styling']['block_title_style'] ) ? $instance['styling_options']['styling']['block_title_style'] : 'default';

        $atts['post_type'] = 'post';

        $atts['is_widget'] = true;

        merimag_get_block_filters_head( $atts, $this->id );

        merimag_get_box( $atts, $this->id );

        echo $argss['after_widget'];
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

add_action( 'widgets_init', function() { return register_widget("Merimag_Posts_Mix_Widget"); } );