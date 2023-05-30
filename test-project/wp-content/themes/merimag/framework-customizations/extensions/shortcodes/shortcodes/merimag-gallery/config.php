<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Gallery', 'merimag' ),
		'description' => __( 'Create awesome galleries', 'merimag' ),
		'tab'         => __( 'Content Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.gallery_style.gallery_style }}',
	)
);