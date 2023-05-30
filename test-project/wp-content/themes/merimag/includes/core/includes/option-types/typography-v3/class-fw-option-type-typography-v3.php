<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Typography
 */
class FW_Option_Type_Typography_v3 extends FW_Option_Type {
	public function _get_backend_width_type() {
		return 'full';
	}

	/**
	 * @internal
	 * {@inheritdoc}
	 */
	protected function _enqueue_static( $id, $option, $data ) {
		wp_enqueue_style(
			'fw-option-' . $this->get_type(),
			MERIMAG_CORE_URL . '/includes/option-types/' . $this->get_type() . '/static/css/styles.css',
			array( 'fw-selectize' ),
			fw()->manifest->get_version()
		);

		fw()->backend->option_type( 'color-picker-v2' )->enqueue_static();
        fw()->backend->option_type( 'slider' )->enqueue_static();
		

		wp_localize_script(
			'fw-option-' . $this->get_type(),
			'fw_typography_v3_fonts',
			$this->get_fonts()
		);
	}

	public function get_type() {
		return 'typography-v3';
	}

	/**
	 * Returns fonts
	 * @return array
	 */
	public function get_fonts() {
		$cache_key = 'fw_option_type/'. $this->get_type();

		try {
			return FW_Cache::get($cache_key);
		} catch (FW_Cache_Not_Found_Exception $e) {
			$fonts = array(
				'standard' => apply_filters( 'fw_option_type_typography_v3_standard_fonts', array(
					"Arial",
					"Verdana",
					"Trebuchet",
					"Georgia",
					"Times New Roman",
					"Tahoma",
					"Palatino",
					"Helvetica",
					"Calibri",
					"Myriad Pro",
					"Lucida",
					"Arial Black",
					"Gill Sans",
					"Geneva",
					"Impact",
					"Serif"
				) ),
				'google' => apply_filters(
					'fw_option_type_typography_v3_google_fonts',
					merimag_get_recognized_font_families()
				)
			);

			FW_Cache::set($cache_key, $fonts);

			return $fonts;
		}
	}

	/**
	 * @internal
	 */
	protected function _render( $id, $option, $data ) {
		return fw_render_view( dirname(__FILE__) . '/view.php', array(
			'typography_v3' => $this,
			'id'            => $id,
			'option'        => $option,
			'data'          => $data,
			'defaults'      => $this->get_defaults()
		) );
	}

	/**
	 * @internal
	 */
	protected function _get_value_from_input( $option, $input_value ) {

		$default = $this->get_defaults();
		$values  = array_merge( $default['value'], $option['value'], is_array($input_value) ? $input_value : array());

		if ( ! empty($values['color']) && ! preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $values['color'] ) ) {
			$values['color'] = isset( $option['value']['color'] ) ? $option['value']['color'] : $default['value']['color'];
		}

		$components = array_merge( $default['components'], $option['components'] );
		foreach ( $components as $component => $enabled ) {
			if ( ! $enabled ) {
				$values[ $component ] = false;
			}
		}


		return $values;

	}

	public function get_google_font( $font ) {
		$fonts = $this->get_fonts();

		foreach ( $fonts as $g_font ) {
			if ( $font === $g_font ) {
				return $g_font;
			}
		}

		return false;
	}

	/**
	 * @internal
	 */
	protected function _get_defaults() {
		return array(
			'value' => array(
				'family'         => '',
				'style'          => '',
				'weight'         => '',
				'size'           => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '',
                'transform'      => '',
                'decoration'     => '',
			),
			'components' => array(
				'family'         => true,
				'size'           => true,
				'line-height'    => true,
				'letter-spacing' => true,
				'color'          => true,
				'weight'         => true,
				'style'          => true,
                'transform'      => true,
                'decoration'     => true,
			)
		);
	}

}

FW_Option_Type::register('FW_Option_Type_Typography_v3');
