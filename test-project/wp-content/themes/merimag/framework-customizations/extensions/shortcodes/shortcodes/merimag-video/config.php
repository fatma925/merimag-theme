<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Video element', 'merimag' ),
		'description' => __( 'Add video to your design', 'merimag' ),
		'tab'         => __( 'Media Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.media_type }}',
	)
);