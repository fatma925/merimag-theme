<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
    'group_ads' => array(
        'title' => __('Group ads', 'merimag'),
        'type' => 'box',
        'options' => array(
        	'merimag_group_ads' => array(
        		'type' => 'multi-select',
        		'label' => __('Select ads', 'merimag'),
        		'population' => 'posts',
     			'source' => 'adsforwp',
        	),
        	'merimag_group_ads_order' => array(
        		'type' => 'select',
        		'label' => __('Ads order', 'merimag'),
     			'choices' => array(
     				'default' => sprintf('-- %s --', __('Default', 'merimag') ),
     				'random' => __('Random', 'merimag'),
     			),
        	),
        	'merimag_group_ads_refresh' => array(
        		'type' => 'number',
     			'label' => __('Refresh every ( Milliseconds )', 'merimag'),
        	),
        ),
    ),
);