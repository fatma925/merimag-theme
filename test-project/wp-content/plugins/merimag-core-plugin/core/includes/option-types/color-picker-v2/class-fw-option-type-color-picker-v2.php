<?php if (!defined('FW')) die('Forbidden');

/**
 * Color Picker
 */
class FW_Option_Type_Color_Picker_V2 extends FW_Option_Type
{
	public function get_type()
	{
		return 'color-picker-v2';
	}

	/**
	 * @internal
	 * {@inheritdoc}
	 */
	protected function _enqueue_static($id, $option, $data)
	{
		

		wp_enqueue_script(
			'fw-option-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/js/scripts.js',
			array('jquery'),
			fw()->manifest->get_version(),
			true
		);
		wp_enqueue_script(
			'fw-option-init-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/js/init.js',
			array('jquery'),
			fw()->manifest->get_version(),
			true
		);

		wp_enqueue_script(
			'fw-option-spectrum-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/js/spectrum/spectrum.js',
			array('jquery'),
			fw()->manifest->get_version(),
			true
		);
		wp_enqueue_style(
			'fw-option-spectrum-css-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/js/spectrum/spectrum.css',
			array(),
			fw()->manifest->get_version()
		);

	}

	/**
	 * @internal
	 */
	protected function _render($id, $option, $data)
	{
		$option['attr']['value']  		= strtolower($data['value']);
		$option['attr']['class'] 	   .= ' code';
		if( $option['rgba'] === true ) {
			$option['attr']['data-show-alpha'] = true;
		}
		$option['attr']['data-default'] = $option['value'];


		return '<input class="merimag-options-color-picker" type="text" '. fw_attr_to_html($option['attr']) .'>';
	}

	/**
	 * @internal
	 */
	protected function _get_value_from_input($option, $input_value)
	{
		if (
			is_null($input_value)
			||
			(
				// do not use `!is_null()` allow empty values https://github.com/ThemeFuse/Unyson/issues/2025
				!empty($input_value)
				&&
				!(
					preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $input_value )
					||
					preg_match( '/^rgba\( *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *(1|0|0?.\d+) *\)$/', $input_value )
				)
			)
		) {
			return (string)$option['value'];
		} else {
			return (string)$input_value;
		}
	}

	/**
	 * @internal
	 */
	public function _get_backend_width_type()
	{
		return 'auto';
	}

	/**
	 * @internal
	 */
	protected function _get_defaults()
	{
		return array(
			'value' => '',
			'rgba'=> false,
		);
	}
}
FW_Option_Type::register('FW_Option_Type_Color_Picker_V2');
