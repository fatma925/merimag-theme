<?php if (!defined('FW')) die('Forbidden');

$options = array(
	'general' => array(
		'type' => 'tab',
		'title' => __('General', 'merimag'),
		'options' => array(
			'is_fullwidth' => array(
				'label'        => __('Full Width', 'merimag'),
				'type'         => 'switch',
			),
			'equal_height' => array(
				'label'        => __('Equal height columns', 'merimag'),
				'type'         => 'switch',
			),
			'stretch_content' => array(
				'label'        => __('Stretch content', 'merimag'),
				'type'         => 'switch',
			),
			'layout_picker' => array(
			    'type'  => 'multi-picker',
			    'picker' => array(
			        'layout' => array(
			            'label'   => __('Select layout', 'merimag'),
			            'type'    => 'image-picker', // or 'short-select'
			            'value'   => 'content',
			            'choices' => array(
			                 'content-sidebar' => array(
					            'small' => array(
					                'src' => get_template_directory_uri() .'/assets/images/layouts/content-sidebar.png',
					                'height' => 70
					            ),
					            'large' => array(
					                'src' => get_template_directory_uri() .'/assets/images/layouts/content-sidebar.png',
					                'height' => 98
					            ),
					        ),
					        'sidebar-content' => array(
					            'small' => array(
					                'src' => get_template_directory_uri() .'/assets/images/layouts/sidebar-content.png',
					                'height' => 70
					            ),
					            'large' => array(
					                'src' => get_template_directory_uri() .'/assets/images/layouts/sidebar-content.png',
					                'height' => 98
					            ),
					        ),
					        'content' => array(
					            'small' => array(
					                'src' => get_template_directory_uri() .'/assets/images/layouts/content.png',
					                'height' => 70
					            ),
					            'large' => array(
					                'src' => get_template_directory_uri() .'/assets/images/layouts/content.png',
					                'height' => 98
					            ),
					        ),
			            ),
			        )
			       
			    ),
			    'choices' => array(
			        'content-sidebar' => array(
			            'sidebar' => array(
			                'type'  => 'select',
			                'label' => __('Sidebar', 'merimag'),
			                'choices' => FW_Shortcode_Widget_Area::get_sidebars()
			            )
			        ),
			        'sidebar-content' => array(
			            'sidebar' => array(
			                'type'  => 'select',
			                'label' => __('Sidebar', 'merimag'),
			                'choices' => FW_Shortcode_Widget_Area::get_sidebars()
			            )
			        ),
			    ),
			),
			'spacing' => array(
		        'type'   => 'select',
		        'value' => 'small',
		        'label'  => esc_html__('Spacing', 'merimag'),
		        'choices' => merimag_get_recognized_grid_spacing(),
		    ),
			'video' => array(
				'type' => 'text',
				'label' => __('Video background', 'merimag'),
			),
		)
	),
	'styling' => array(
		'type' => 'tab',
		'title' => __('styling', 'merimag'),
		'options' => merimag_get_block_style_options(),
	),
);

