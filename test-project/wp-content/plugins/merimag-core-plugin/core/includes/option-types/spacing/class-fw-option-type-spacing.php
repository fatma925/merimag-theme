<?php if (!defined('FW')) {
	die('Forbidden');
}

/**
 * Background Color
 */
class FW_Option_Type_Spacing extends FW_Option_Type
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

		wp_enqueue_style(
			'fw-option-css-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/css/styles.css',
			array(),
			fw()->manifest->get_version()
		);
		wp_enqueue_style(
			'fw-option-arrows-css-'. $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/'. $this->get_type() .'/static/css/arrows/style.css',
			array(),
			fw()->manifest->get_version()
		);
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
	}

	/**
	 * @internal
	 */
	public function get_positions()
	{

		return array( 'top', 'right', 'bottom', 'left');
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
				'data' => $data,
				'default' => $this->get_defaults(),
				'positions' => $this->get_positions(),
			)
		);
	}

	public function get_type()
	{
		return 'spacing';
	}

	/**
	 * @internal
	 */
	protected function _get_value_from_input($option, $input_value)
	{
		if (!is_array($input_value)) {
			return $option['value'];
		}

		$value 	   = array();
		$positions = $this->get_positions();
		$min 	   = isset( $option['properties']['min'] ) && is_numeric( $option['properties']['min'] ) ? $option['properties']['min'] : false;
		$max 	   = isset( $option['properties']['max'] ) && is_numeric( $option['properties']['max'] ) ? $option['properties']['max'] : false;
		foreach( $positions as $position ) {
			if( isset( $input_value[ $position ] ) && is_numeric( $input_value[ $position ] ) ) {
				$value[ $position ] = $input_value[ $position ];
				$value[ $position ] = $value[ $position ] > $max && $max !== false ? $max : $value[ $position ];
				$value[ $position ] = $value[ $position ] < $min && $min !== false ? $min : $value[ $position ];
 			} else {
				$value[ $position ] = '';
			}
		}
		return $value;
	}

	/**
	 * @internal
	 */
	protected function _get_defaults()
	{
		$defaults  = array();
		$positions = $this->get_positions();
		foreach( $positions as $position ) {
			$defaults['value'][ $position ] = '';
		}
		return $defaults;
	}

}
FW_Option_Type::register('FW_Option_Type_Spacing');
