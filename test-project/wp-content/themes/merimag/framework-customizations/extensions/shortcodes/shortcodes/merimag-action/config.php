<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Call to Action', 'merimag' ),
		'description' => __( 'Create awesome call to action block', 'merimag' ),
		'tab'         => __( 'Content Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }}',
	)
);