<?php if (!defined('FW')) die('Forbidden');

$options = array(
	'general' => array(
		'type' => 'tab',
		'title' => __('General', 'merimag'),
		'options' => array(
			'align_items' => array(
				'type' => 'select',
				'label' => __('Vertical align', 'merimag'),
				'choices' => array( 'inherit' => __('Default', 'merimag'), 'flex-start' => __('Start', 'merimag'), 'center' => __('Center', 'merimag'),'flex-end' => __('End', 'merimag')),
			),
			'justify_content' => array(
				'type' => 'select',
				'label' => __('Horizontal align', 'merimag'),
				'choices' => array( 'inherit' => __('Default', 'merimag'), 'flex-start' => __('Start', 'merimag'), 'center' => __('Center', 'merimag'),'flex-end' => __('End', 'merimag')),
			),
			'text_align' => array(
				'type' => 'select',
				'label' => __('Text align', 'merimag'),
				'choices' => array( 'inherit' => __('Default', 'merimag'), 'initial' => __('Initial', 'merimag'), 'left' => __('Left', 'merimag'), 'center' => __('Center', 'merimag'),'right' => __('Right', 'merimag')),
			),
			'video' => array(
				'type' => 'text',
				'label' => __('Video background', 'merimag'),
			),
		)
	),
	'styling' => array(
		'type' => 'tab',
		'title' => __('styling', 'merimag'),
		'options' => merimag_get_block_style_options(),
	),
);

