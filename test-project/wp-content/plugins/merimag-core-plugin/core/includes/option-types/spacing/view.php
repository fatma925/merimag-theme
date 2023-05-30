<?php if ( ! defined( 'FW' ) ) { die( 'Forbidden' ); }

/**
 * @var string $id
 * @var  array $option
 * @var  array $data
 */

{
	$div_attr = $option['attr'];

	unset(
		$div_attr['value'],
		$div_attr['name']
	);
	$field_id = merimag_uniqid('fw-option-type-spacing-');
}
?>
<div <?php echo fw_attr_to_html($div_attr) ?> >
	<?php foreach( $positions as $position ) : ?>
		<div class="fw-field-container">
			<div class="fw-icon-input">
				<span><i class="icon-caret-down"></i></span>
				<?php $data['value'][ $position ] = isset( $data['value'][ $position ] ) && is_numeric( $data['value'][ $position ] ) ? $data['value'][ $position ] : $option['value'][ $position ]; ?>
				<input data-id="<?php echo esc_attr($field_id)?>" value="<?php echo esc_attr( $data['value'][ $position ] )?>" type="number" id="<?php echo esc_attr( $option['attr']['id'] . '-' . $position ) ?>" name="<?php echo esc_attr( $option['attr']['name'] . '[' . $position . ']' ) ?>">
			</div>
		</div>
	<?php endforeach; ?>
	<div class="fw-field-container">
		<div class="fw-icon-input only-icon">
			<span data-id="<?php echo esc_attr($field_id)?>" class="fw-link-spacing-fields"><i class="icon-chain"></i></span>
		</div>
	</div>
</div>
