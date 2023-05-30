<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Quotation', 'merimag' ),
		'description' => __( 'Add awesome quote boxes to your design', 'merimag' ),
		'tab'         => __( 'Content Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.author_name }}',
	)
);