<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Products List', 'merimag' ),
		'description' => __( 'Create awesome posts grid layouts', 'merimag' ),
		'tab'         => __( 'Products blocks', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }} {{- o.style }}',
	)
);