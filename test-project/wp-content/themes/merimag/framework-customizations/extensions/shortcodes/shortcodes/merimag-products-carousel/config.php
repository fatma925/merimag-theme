<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Products Carousel', 'merimag' ),
		'description' => __( 'Create awesome products carousel', 'merimag' ),
		'tab'         => __( 'Products blocks', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.title }} {{- o.style }}',
	)
);