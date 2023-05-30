<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Posts Grid', 'merimag' ),
		'description' => __( 'Create awesome posts grid layouts', 'merimag' ),
		'tab'         => __( 'Posts blocks', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }} {{- o.style }}',
	)
);