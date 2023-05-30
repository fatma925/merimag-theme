<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Alert notification', 'merimag' ),
		'description' => __( 'Add awesome alert boxes to your content', 'merimag' ),
		'tab'         => __( 'Content Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.alert_title }}',
	)
);