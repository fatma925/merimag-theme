<?php if (!defined('FW')) die('Forbidden');

$cfg = array(
	'page_builder' => array(
		'title'       => __( 'Mailchimp form', 'merimag' ),
		'description' => __( 'Add mailchimp subscribe forms to your design', 'merimag' ),
		'tab'         => __( 'Content Elements', 'merimag' ),
		'title_template' => '{{- title }} : {{- o.mailchimp_form }}',
	)
);