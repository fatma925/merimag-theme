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
}

$color_regex  = '/^rgba\( *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *([01]?\d\d?|2[0-4]\d|25[0-5]) *\, *(1|0|0?.\d+) *\)$/';
$color_regex_2 = '/^#([a-f0-9]{3}){1,2}$/i';
?>
<div <?php echo fw_attr_to_html($div_attr) ?> >
	<div class="primary-color">
		<?php
		echo fw()->backend->option_type( 'color-picker-v2' )->render(
			'primary',
			array(
				'type'  => 'color-picker-v2',
				'value' => ( isset( $option['value']['primary'] ) && ( preg_match( $color_regex, $option['value']['primary'] ) || preg_match( $color_regex_2, $option['value']['primary'] ) ) )
						? $option['value']['primary']
						: '#ffffff',
				'rgba' => true,
				'attr'  => array(
					'class' => 'primary'
				)
			),
			array(
				'value'       => ( isset( $data['value']['primary'] ) && ( preg_match( $color_regex, $data['value']['primary'] ) || preg_match( $color_regex_2, $data['value']['primary'] ) ) )
						? $data['value']['primary']
						: $option['value']['primary'],
				'id_prefix'   => $data['id_prefix'] . $id . '-',
				'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
			)
		);
		?>
	</div>
	<?php if ( isset( $option['value']['secondary'] ) ): ?>
		<div class="secondary-color">
			<?php
			echo fw()->backend->option_type( 'color-picker-v2' )->render(
				'secondary',
				array(
					'type'  => 'color-picker-v2',
					'value' => ( isset( $option['value']['secondary'] ) && ( preg_match( $color_regex, $option['value']['secondary'] ) || preg_match( $color_regex_2, $option['value']['secondary'] ) ) )
							? $option['value']['secondary']
							: '#ffffff',
					'rgba' => true,
					'attr'  => array(
						'class' => 'secondary'
					)
				),
				array(
					'value' => ( isset( $data['value']['secondary'] ) && ( preg_match( $color_regex, $data['value']['secondary'] ) || preg_match( $color_regex_2, $data['value']['secondary'] ) ) )
							? $data['value']['secondary']
							: $option['value']['secondary'],
					'id_prefix'   => $data['id_prefix'] . $id . '-',
					'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
				)
			);
			?>
		</div>
	<?php endif; ?>
	<div class="gradient-dgree">
		<?php echo fw()->backend->option_type( 'select' )->render(
			'degree',
			array(
				'type'  => 'select',
				'choices' => array('to bottom' => esc_html__('To bottom', 'merimag' ), 'to right' => esc_html__('To right', 'merimag' ) ),
				'value' => ( isset( $option['value']['degree'] ) && in_array($option['value']['degree'], array('to bottom', 'to right') ) )
						? $option['value']['degree']
						: 'to bottom',
				'attr'  => array(
					'class' => 'degree'
				)
			),
			array(
				'value'       => ( isset( $data['value']['degree'] ) && in_array($data['value']['degree'], array('to bottom', 'to right') ) )
						? $data['value']['degree']
						: 'to bottom',
				'id_prefix'   => $data['id_prefix'] . $id . '-',
				'name_prefix' => $data['name_prefix'] . '[' . $id . ']',
			)
		);
		 ?>
	</div>
	<div class="clear"></div>
</div>
