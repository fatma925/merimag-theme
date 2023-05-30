<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Audio player', 'merimag' ),
		'description' => __( 'Add audio player to your design', 'merimag' ),
		'tab'         => __( 'Media Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.media_type }}',
	)
);