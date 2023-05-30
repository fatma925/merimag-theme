<?php if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

class FW_Option_Type_WPLink extends FW_Option_Type
{
    public function get_type()
    {
        return 'wplink';
    }

    /**
     * @internal
     */
    protected function _enqueue_static($id, $option, $data)
    {
        wp_enqueue_script('wplink');
        wp_enqueue_style('editor-buttons');
    }

    /**
     * @internal
     */
    protected function _render($id, $option, $data)
    {
        /**
         * $data['value'] contains correct value returned by the _get_value_from_input()
         * You decide how to use it in html
         */
        $option['attr']['value'] = isset( $data['value'] ) ? $data['value'] : array('text' => '', 'target' => '_blank');

        /**
         * $option['attr'] contains all attributes.
         *
         * Main (wrapper) option html element should have "id" and "class" attribute.
         *
         * All option types should have in main element the class "fw-option-type-{$type}".
         * Every javascript and css in that option should use that class.
         *
         * Remaining attributes you can:
         *  1. use them all in main element (if option itself has no input elements)
         *  2. use them in input element (if option has input element that contains option value)
         *
         * In this case you will use second option.
         */

        $wrapper_attr = array(
            'id'    => $option['attr']['id'],
            'class' => $option['attr']['class'],
        );

        $html = fw()->backend->option_type('text')->render(
            'attr',
            array(
                'type'  => 'text',
                'value' => (isset($option['value']['attr'])) ? $option['value']['attr'] : '',
                'attr'  => array(
                    'class' => 'attr',
                    
                )
            ),

            array(
                'value' => (isset($data['value']['attr'])) ? $data['value']['attr'] : $option['value']['attr'],
                'id_prefix'   => $data['id_prefix'] . $id . '-',
                'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
            )
        );

        $name = $data['id_prefix'] . $id . '-text';

        $html .= fw()->backend->option_type('hidden')->render(
            'text',
            array(
                'type'  => 'hidden',
                'value' => (isset($option['value']['text'])) ? $option['value']['text'] : '',
                'attr'  => array(
                    'class' => 'wplink-text'
                )
            ),

            array(
                'value' => (isset($data['value']['text'])) ? $data['value']['text'] : $option['value']['text'],
                'id_prefix'   => $data['id_prefix'] . $id . '-',
                'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
            )
        );
        $html .= fw()->backend->option_type('hidden')->render(
            'target',
            array(
                'type'  => 'hidden',
                'value' => isset($option['value']['target']) ? $option['value']['target'] : '',
                'attr'  => array(
                    'class' => 'wplink-target'
                )
            ),

            array(
                'value' => isset($data['value']['target']) ? $data['value']['target'] : $option['value']['target'],
                'id_prefix'   => $data['id_prefix'] . $id . '-',
                'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
            )
        );

        require_once ABSPATH . "wp-includes/class-wp-editor.php";
        _WP_Editors::wp_link_dialog();

         $html .= '
         <script type="text/javascript">
            var ajaxurl = "' . admin_url( 'admin-ajax.php') . '";
            jQuery(document).ready(function() {
                jQuery("#'.$data['id_prefix'] . $id . '-attr'.'").click(function() {
                    
                    wpLink.open("'.$data['id_prefix'] . $id .'-attr");
                });

                jQuery("#wp-link-submit").click(function() {
                    var val = wpLink.textarea.value;
                    var href = jQuery(val).attr("href");
                    var url = "<a href=\"" + jQuery(val).attr("href") + "\"";

                    if (typeof jQuery(val).attr("target") != "undefined") {
                        url = url + " target=\"" + jQuery(val).attr("target") + "\"";
                    }

                    var text = jQuery(val).text();
                    url = url + ">" + text + "</a>";
                    var target = jQuery(val).attr("target") && jQuery(val).attr("target") === "_blank" ? "_blank" : "";
                    
                    jQuery("#'.$data['id_prefix'] . $id .'-text").val(text);
                    jQuery("#'.$data['id_prefix'] . $id .'-attr").val(href);
                    jQuery("#'.$data['id_prefix'] . $id .'-target").val(target);
                });
            });
        </script>';


        return $html;
    }

    /**
     * @internal
     */
    protected function _get_value_from_input($option, $input_value)
    {
        /**
         * In this method you receive $input_value (from form submit or whatever)
         * and must return correct and safe value that will be stored in database.
         *
         * $input_value can be null.
         * In this case you should return default value from $option['value']
         */

         if (is_array($input_value)) {
         $value = array();
         $value['attr'] = '';
         $value['text'] = '';

         if (isset($input_value['attr'])) {
             $value['attr'] = $input_value['attr'];
         }

         if (isset($input_value['text'])) {
             $value['text'] = $input_value['text'];
         }

         if (isset($input_value['target'])) {
             $value['target'] =$input_value['target'] === '_blank' ? $input_value['target'] : '';
         }

         return $value;
     }

     return $option['value'];
    }

    /**
     * @internal
     */
    protected function _get_defaults()
    {
        /**
         * These are default parameters that will be merged with option array.
         * They makes possible that any option has
         * only one required parameter array('type' => 'new').
         */

        return array(
            'value' => array(
                'text' => '',
                'attr' => ''
            )
        );
    }
}

FW_Option_Type::register('FW_Option_Type_WPLink');