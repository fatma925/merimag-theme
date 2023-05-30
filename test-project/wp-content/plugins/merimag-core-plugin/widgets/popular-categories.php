<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Tranquility - the highest manifestation of power!' ); }
 
class Merimag_Popular_Categories_Widget extends WP_Widget {
 
    /**
     * Widget constructor.
     */
    private $options;
    private $prefix;
    function __construct() {
 
        $widget_ops = array( 'description' => __( 'Display popular categories', 'merimag' ) );
        parent::__construct( false, __( 'Popular Categories Widget', 'merimag' ), $widget_ops );
        
        //Create our options by using merimag option types
        $this->options = merimag_popular_categories_options();
        $this->prefix = 'merimag_popular_categories_widget';
    }
 
    function widget( $argss, $instance ) {

        echo $argss['before_widget'];

        $title = isset( $instance['general']['title'] ) ? $instance['general']['title'] : '';

        $atts = isset( $instance['general'] ) ? $instance['general'] : array();

        echo $title ? $argss['before_title'] . esc_attr( apply_filters( 'merimag_filter_block_title', $title ) ) . $argss['after_title'] : '';
        
        $this->view( $atts );

        echo $argss['after_widget'];
    }
    
    function view( $atts ) {

        $taxonomy = isset( $atts['taxonomy'] ) && taxonomy_exists( $atts['taxonomy'] ) ? $atts['taxonomy'] : 'category';

        $sub_cats = isset( $atts['sub_categories'] ) && $atts['sub_categories'] === 'yes' ? true : false;

        $terms    = get_terms( array( 'taxonomy' => $taxonomy, 'orderby' => 'count', 'order'=> 'DESC', 'parent' => $sub_cats ) );

        echo '<ul class="merimag-popular-categories-list">';

        foreach( $terms as $term ) {
            $link = get_term_link( $term->term_id );
            $icon_angle = is_rtl() ? 'left' : 'right';
            echo sprintf('<li class="general-border-color"><i class="fa fa-angle-%s"></i><a href="%s">%s</a><span class="merimag-term-count principal-color-background-color %s-%s">%s</span></li>', esc_attr( $icon_angle ), esc_url( $link ), $term->name, $taxonomy, $term->slug, $term->count  );

        }

        echo '</ul>';
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

add_action( 'widgets_init', function() { return register_widget("Merimag_Popular_Categories_Widget"); } );