<?php if (!defined('FW')) {
	die('Forbidden');
}

/**
 * Background Color
 */
class FW_Option_Type_Gradient_V2 extends FW_Option_Type
{
	/**
	 * @internal
	 */
	public function _get_backend_width_type()
	{
		return 'auto';
	}

	/**
	 * @internal
	 * {@inheritdoc}
	 */
	protected function _enqueue_static($id, $option, $data)
	{

		fw()->backend->option_type('color-picker-v2')->enqueue_static();
		wp_enqueue_style(
			'fw-option-css-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/css/styles.css',
			array(),
			fw()->manifest->get_version()
		);
	}

	/**
	 * @internal
	 */
	protected function _render($id, $option, $data)
	{
		return fw_render_view(
			MERIMAG_CORE_DIR . '/includes/option-types/' . $this->get_type() . '/view.php',
			array(
				'id' => $id,
				'option' => $option,
				'data' => $data
			)
		);
	}

	public function get_type()
	{
		return 'gradient-v2';
	}

	/**
	 * @internal
	 */
	protected function _get_value_from_input($option, $input_value)
	{
		if (!is_array($input_value)) {
			return $option['value'];
		}

		$input_value['primary']   = isset( $input_value['primary'] ) && $this->validate_color( $input_value['primary'] ) ? $input_value['primary'] : '';

		$input_value['secondary'] = isset( $input_value['secondary'] ) && $this->validate_color( $input_value['secondary'] ) ? $input_value['secondary'] : '';

		$input_value['degree'] 	  = isset( $input_value['degree'] ) && in_array($input_value['degree'], array('to bottom', 'to right') ) ? $input_value['degree'] : 'to bottom';

		return $input_value;
	}

	public function validate_color( $input_value ) {
	  if( preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $input_value ) || preg_match( '/^rgba\( *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *(1|0|0?.\d+) *\)$/', $input_value )) {
	    return true;
	  } else {
	    return false;
	  }
	}

	/**
	 * @internal
	 */
	protected function _get_defaults()
	{
		return array(
			'value' => array(
				'primary'   => '',
				'secondary' => '',
			)
		);
	}
}
FW_Option_Type::register('FW_Option_Type_Gradient_V2');
