<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * @var  FW_Option_Type_Typography_v3 $typography_v3
 * @var  string $id
 * @var  array $option
 * @var  array $data
 * @var array $defaults
 */

{
	$wrapper_attr = $option['attr'];

	unset(
		$wrapper_attr['value'],
		$wrapper_attr['name']
	);
}

{
	$option['value'] = array_merge( $defaults['value'], (array) $option['value'] );
	$data['value']   = array_merge( $option['value'], is_array($data['value']) ? $data['value'] : array() );

}

$components = (isset($option['components']) && is_array($option['components']))
	? array_merge($defaults['components'], $option['components'])
	: $defaults['components'];
?>
<div <?php echo fw_attr_to_html( $wrapper_attr ) ?>>
	<?php if ( $components['family'] ) : ?>
	<div class="fw-option-typography-v3-option fw-option-typography-v3-option-family fw-border-box-sizing fw-col-sm-5">
		<div class="fw-inner"><?php _e('Font face', 'merimag'); ?></div>
	
	            <?php
				echo fw()->backend->option_type( 'multi-select' )->render(
					'family',
					array(
						'label' => false,
						'population' => 'array',
						'choices' => merimag_get_recognized_font_families(),
						'desc'  => false,
						'type'  => 'color-picker',
						'value' => $option['value']['family'],
						'limit' => 1,
					),
					array(
						'value'       => array( $data['value']['family'] ),
						'id_prefix'   => 'fw-option-' . $id . '-typography-v3-option-',
						'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
					)
				)
				?>

	</div>
	<?php endif; ?>
	<?php if ( $components['style'] ) : ?>
	<div class="fw-option-typography-v3-option fw-option-typography-v3-option-style fw-border-box-sizing fw-col-sm-3">
		<div class="fw-inner"><?php _e( 'Font style', 'merimag' ); ?></div>
		<select data-type="style" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[style]"
		        class="fw-option-typography-v3-option-style-input">
			<?php foreach (
				array(
					''  	  => __('Default', 'merimag'),
					'normal'  => __('Normal', 'merimag'),
					'italic'  => __('Italic', 'merimag'),
					'oblique' => __('Oblique', 'merimag')
				)
				as $key => $style
			): ?>
				<option value="<?php echo esc_attr( $key ) ?>"
				        <?php if ($data['value']['style'] === $key): ?>selected="selected"<?php endif; ?>><?php echo fw_htmlspecialchars( $style ) ?></option>
			<?php endforeach; ?>
		</select>

		
	</div>
	<?php endif; ?>

	<?php if ( $components['weight'] ) : ?>
	<div class="fw-option-typography-v3-option fw-option-typography-v3-option-weight fw-border-box-sizing fw-col-sm-3">
		<div class="fw-inner"><?php _e( 'Font weight', 'merimag' ); ?></div>
		<select data-type="weight" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[weight]"
		        class="fw-option-typography-v3-option-weight-input">
			<?php foreach (
				array(
					'' => __('Default', 'merimag'),
					100 => 100,
					200 => 200,
					300 => 300,
					400 => 400,
					500 => 500,
					600 => 600,
					700 => 700,
					800 => 800,
					900 => 900
				)
				as $key => $style
			): ?>
				<option value="<?php echo esc_attr( $key ) ?>"
				        <?php if ($data['value']['weight'] == $key): ?>selected="selected"<?php endif; ?>><?php echo fw_htmlspecialchars( $style ) ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php endif; ?>
	<?php if ( $components['size'] ) : ?>
		<div class="fw-option-typography-v3-option fw-option-typography-v3-option-size fw-border-box-sizing fw-col-sm-2">
			<div class="fw-inner"><?php esc_html_e( 'Font size', 'merimag' ); ?></div>
			<input data-type="size" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[size]"
			       value="<?php echo esc_attr($data['value']['size']); ?>"
			       class="fw-option-typography-v3-option-line-height-input" type="number">
			
		</div>
	<?php endif; ?>

	<?php if ( $components['line-height'] ) : ?>
		<div class="fw-option-typography-v3-option fw-option-typography-v3-option-line-height fw-border-box-sizing fw-col-sm-2">
			<div class="fw-inner"><?php esc_html_e( 'Line height', 'merimag' ); ?></div>
			<input data-type="line-height" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[line-height]"
			       value="<?php echo esc_attr($data['value']['line-height']); ?>"
			       class="fw-option-typography-v3-option-line-height-input" type="text">

			
		</div>
	<?php endif; ?>

	<?php if ( $components['letter-spacing'] ) : ?>
		<div class="fw-option-typography-v3-option fw-option-typography-v3-option-letter-spacing fw-border-box-sizing fw-col-sm-2">
			<div class="fw-inner"><?php esc_html_e( 'Letter spacing', 'merimag' ); ?></div>
			<input data-type="letter-spacing" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[letter-spacing]"
			       value="<?php echo esc_attr($data['value']['letter-spacing']); ?>"
			       class="fw-option-typography-v3-option-letter-spacing-input" type="text">

			
		</div>
	<?php endif; ?>

	<?php if ( $components['color'] ) : ?>
		<div class="fw-option-typography-v3-option fw-option-typography-v3-option-color fw-border-box-sizing fw-col-sm-2" data-type="color">
			<div class="fw-inner"><?php esc_html_e( 'Color', 'merimag' ); ?></div>
			<?php
			echo fw()->backend->option_type( 'color-picker-v2' )->render(
				'color',
				array(
					'label' => false,
					'desc'  => false,
					'type'  => 'color-picker',
					'value' => $option['value']['color']
				),
				array(
					'value'       => $data['value']['color'],
					'id_prefix'   => 'fw-option-' . $id . '-typography-v3-option-',
					'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
				)
			)
			?>
			
		</div>
	<?php endif; ?>
	<?php if ( $components['transform'] ) : ?>
		<div class="fw-option-typography-v3-option fw-option-typography-v3-option-transform fw-border-box-sizing fw-col-sm-3">
			<div class="fw-inner"><?php _e( 'Text transform', 'merimag' ); ?></div>
			<select data-type="transform" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[transform]"
			        class="fw-option-typography-v3-option-transform-input">
					<?php foreach (
						array(
							''    		 => esc_html__('Default', 'merimag'),
							'none' 		 => esc_html__('None', 'merimag'),
							'capitalize' => esc_html__('Capitalize', 'merimag'),
							'lowercase'  => esc_html__('Lowercase', 'merimag'),
							'uppercase'  => esc_html__('Uppercase', 'merimag'),
						)
						as $key => $style
					): ?>
					<option value="<?php echo esc_attr( $key ) ?>" <?php if ($data['value']['transform'] == $key): ?>selected="selected"<?php endif; ?>><?php echo fw_htmlspecialchars( $style ) ?></option>
				<?php endforeach; ?>
			</select>
			
		</div>
	<?php endif; ?>
	<?php if ( $components['decoration'] ) : ?>
		<div class="fw-option-typography-v3-option fw-option-typography-v3-option-decoration fw-border-box-sizing fw-col-sm-3">
		<div class="fw-inner"><?php _e( 'Text decoration', 'merimag' ); ?></div>
			<select data-type="decoration" name="<?php echo esc_attr( $option['attr']['name'] ) ?>[decoration]" class="fw-option-typography-v3-option-decoration-input">
					<?php foreach (
						array(
							''    		 		 => esc_html__('Default', 'merimag'),
							'none' 		 		 => esc_html__('None', 'merimag'),
							'underline' 		 => esc_html__('Underline', 'merimag'),
							'overline'  		 => esc_html__('Overline', 'merimag'),
							'line-through'  	 => esc_html__('Line through', 'merimag'),
							'underline overline' => esc_html__('Underline & Overeline', 'merimag'),
						)
						as $key => $style
					): ?>
					<option value="<?php echo esc_attr( $key ) ?>" <?php if ($data['value']['decoration'] == $key): ?>selected="selected"<?php endif; ?>><?php echo fw_htmlspecialchars( $style ) ?></option>
				<?php endforeach; ?>
			</select>
			
		</div>
	<?php endif; ?>
</div>
