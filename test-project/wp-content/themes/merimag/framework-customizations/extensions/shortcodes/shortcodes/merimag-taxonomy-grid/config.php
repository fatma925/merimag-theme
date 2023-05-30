<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Taxonomy Grid', 'merimag' ),
		'description' => __( 'Create awesome post / product category grid layouts', 'merimag' ),
		'tab'         => __( 'Posts blocks', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }} {{- o.style }}',
	)
);