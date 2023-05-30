<?php if (!defined('FW')) die('Forbidden');

class FW_Option_Type_Number extends FW_Option_Type {
    public function get_type() {
        return 'number';
    }

    /**
     * @internal
     * {@inheritdoc}
     */
    protected function _enqueue_static( $id, $option, $data ) {
    }

    protected function _get_data_for_js($id, $option, $data = array()) {
        return false;
    }

    /**
     * @param string $id
     * @param array $option
     * @param array $data
     *
     * @return string
     *
     * @internal
     */
    protected function _render( $id, $option, $data ) {
        $option['attr']['value'] = is_numeric( $data['value'] ) ? $data['value'] : false;

        return '<input ' . fw_attr_to_html( $option['attr'] ) . ' type="number" />';
    }

    /**
     * @param array $option
     * @param array|null|string $input_value
     *
     * @return string
     *
     * @internal
     */
    protected function _get_value_from_input( $option, $input_value ) {
        return !is_numeric( $input_value ) ? $option['value'] : $input_value;
    }

    /**
     * @internal
     */
    protected function _get_defaults() {
        return array(
            'value' => ''
        );
    }
}



FW_Option_Type::register('FW_Option_Type_Number');