<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Posts Slider', 'merimag' ),
		'description' => __( 'Create awesome posts sliders', 'merimag' ),
		'tab'         => __( 'Posts blocks', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }} {{- o.style }}',
	)
);