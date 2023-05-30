<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Custom content grid', 'merimag' ),
		'description' => __( 'Create awesome custom content grid layouts', 'merimag' ),
		'tab'         => __( 'Content Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }} {{- o.style }}',
	)
);