<?php if (!defined('FW')) die('Forbidden');
// MegaMenu row options
$options = array(
	'general' => array(
		'type' => 'tab',
		'title' => __('General', 'merimag'),
		'options' => array(
			'menu_content' => array(
				'type' => 'select',
				'title' => __('Show on mega menu', 'merimag'),
				'choices' => !class_exists('WPMDM') ? array(
					'default' => __('Children elements', 'merimag'),
					'category_grid' => __('Category grid ( For category links )', 'merimag'),
					'mega_menu' => __('Elementor custom content', 'merimag'),
				) : array(
					'default' => __('Children elements', 'merimag'),
					'category_grid' => __('Category grid ( For category links )', 'merimag'),
					'mega_menu' => __('Elementor custom content', 'merimag'),
					'demo_features' => __('Demo features', 'merimag'),
				),
			),
			'menu_grid_data' => array(
				'type' => 'multi-picker',
				'picker' => 'menu_content',
				'choices' => array(
					'default' => array(
						'column_width' => array(
		            		'type' => 'select',
		            		'label' => __('Columns width', 'merimag'),
		            		'value' => '4',
		            		'choices' => array(
		            			'3' => '3',
		            			'4' => '4',
		            			'5' => '5',
		            			'6' => '6',
		            		),
		            	),
					),
		            'category_grid' => array(
		            	'grid_columns' => array(
		            		'type' => 'select',
		            		'label' => __('Number of columns', 'merimag'),
		            		'value' => '4',
		            		'choices' => array(
		            			'3' => '3',
		            			'4' => '4',
		            			'5' => '5',
		            		),
		            	),
		            	'filters' => array(
		            		'type' => 'select',
		            		'label' => __('Sub categories filters', 'merimag'),
		            		'choices' => array(
		            			'default' => __('Default', 'merimag'),
		            			'no_filters' => __('No filters', 'merimag'),
		            			'side_filters' => __('Side filters', 'merimag'),
		            			'top_filters' => __('Top filters', 'merimag'),
		            			'title_filters' => __('Beside title filters', 'merimag'),
		            		),
		            	),
		            	'pagination' => array(
					      'label'        => __( 'Enable pagination', 'merimag-backend' ),
					      'type'         => 'switch',
					      'right-choice' => array(
					        'value' => 'yes',
					        'label' => __( 'Yes', 'merimag-backend' )
					      ),
					      'left-choice'  => array(
					        'value' => 'no',
					        'label' => __( 'No', 'merimag-backend' )
					      ),
					      'value'        => 'yes',
					    ),
					    'random' => array(
					      'label'        => __( 'Random order', 'merimag-backend' ),
					      'type'         => 'switch',
					      'right-choice' => array(
					        'value' => 'yes',
					        'label' => __( 'Yes', 'merimag-backend' )
					      ),
					      'left-choice'  => array(
					        'value' => 'no',
					        'label' => __( 'No', 'merimag-backend' )
					      ),
					      'value'        => 'yes',
					    ),
		            ),
		            'mega_menu' => array(
		            	'mega_menu' => array(
							'label'   => __('Select mega menu', 'merimag'),
							'type'    => 'multi-select',
							'population' => 'posts',
					     	'source' => 'mega_menu',
							'prepopulate' => 10,
							'limit' => 1,
						),
		            ),
		        )
			),
		),
	),
	'styling' => array(
		'type' => 'tab',
		'title' => __('Styling', 'merimag'),
		'options' => merimag_get_block_style_options(),
	),
);