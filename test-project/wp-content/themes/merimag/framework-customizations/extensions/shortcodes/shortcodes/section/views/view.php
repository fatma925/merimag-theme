<?php 
	if ( ! defined( 'FW' ) ) {
		die( 'Forbidden' );
	}


	$section_id 			  		 = merimag_uniqid('merimag-section-');
	$selector 						 = '#' . $section_id;
	$block_css 		  	  			 = isset( $atts ) && is_array( $atts ) ? merimag_get_dynamic_block_style( $atts, $selector ) : merimag_get_dynamic_block_style( 'general_section', $selector );
	// Dynamic bloc css
	wp_register_style( 'merimag-blocks-dynamic-css', false );
	wp_enqueue_style( 'merimag-blocks-dynamic-css' );
	wp_add_inline_style( 'merimag-blocks-dynamic-css', $block_css );



	$bg_video_data_attr = '';
	$video_class = '';
	if ( isset( $atts['video'] ) && ! empty( $atts['video'] ) ) {
		$filetype           = wp_check_filetype( $atts['video'] );
		$filetypes          = array( 'mp4' => 'mp4', 'ogv' => 'ogg', 'webm' => 'webm', 'jpg' => 'poster' );
		$filetype           = array_key_exists( (string) $filetype['ext'], $filetypes ) ? $filetypes[ $filetype['ext'] ] : 'video';
		$data_name_attr = version_compare( fw_ext('shortcodes')->manifest->get_version(), '1.3.9', '>=' ) ? 'data-background-options' : 'data-wallpaper-options';
		$bg_video_data_attr = $data_name_attr.'="' . fw_htmlspecialchars( json_encode( array( 'source' => array( $filetype => $atts['video'] ) ) ) ) . '"';
		$video_class .= ' background-video';
	}


	$is_fullwidth 	 				= ( isset( $atts['is_fullwidth'] ) && $atts['is_fullwidth'] ) ? 'yes' : 'no';
	$stretch_content 	 			= ( isset( $atts['stretch_content'] ) && $atts['stretch_content'] ) ? 'yes' : 'no';
	// $row_class 		   		   	    = ( isset( $atts['is_fullwidth'] ) && $atts['is_fullwidth'] ) ? ' merimag-full-section' : '';
	$row_class 						= '';
	$no_layout 		 				= isset( $atts['auto_generated'] ) && boolval( $atts['auto_generated'] ) === true ? true : false;
	$layout  		 				= isset( $atts['layout_picker']['layout'] ) ? $atts['layout_picker']['layout'] : 'content';
	$container_class 			    = $stretch_content === 'yes' ? '' : ' merimag-full-section-content';
	$container_class 			   .= $is_fullwidth === 'yes' && $stretch_content === 'yes' ? '' : ' site-content-width';
	$section_class 		   		    = $is_fullwidth === 'no' ? '  site-content-width' : '';

	$sidebar 		 				= $layout !== 'content' && isset( $atts['layout_picker'][ $layout ]['sidebar'] ) ? $atts['layout_picker'][ $layout ]['sidebar'] : false;
	$container_class 			   .= $layout !== 'content' && $sidebar ? ' content-sidebar-container ' : '';
	$section_layout  				= $layout === 'content' ? 'full' : 'with-sidebar';
	$section_layout  				= $no_layout === true ? 'full' : $section_layout;
	$content 		 				= merimag_filter_blocks_shortcodes( $content, 'section-layout', $section_layout );
	$content 		 				= merimag_filter_blocks_shortcodes( $content, 'fullwidth', $is_fullwidth );
	$spacing_class 					= isset( $atts['spacing'] ) && in_array( $atts['spacing'], merimag_get_recognized_grid_spacing( true ) ) ? sprintf( ' merimag-%s-spacing', $atts['spacing'] ) : '';
	$equal_height_class				= isset( $atts['equal_height'] ) && $atts['equal_height'] ? ' merimag-grid-equal-height ' : '';
	$inner_row_class 				= sprintf( '%s %s', $spacing_class, $equal_height_class );
	$content 						= isset( $atts['spacing'] ) && in_array( $atts['spacing'], merimag_get_recognized_grid_spacing( true ) ) ? merimag_filter_shortcode( $content, 'row', 'class', $inner_row_class ) : $content;
	$ignore_style		 		    = isset( $atts['ignore_general_style'] ) && $atts['ignore_general_style'] === 'yes' ? true : false;
	$section_class		 		   .= $ignore_style === true ? ' ignore-general-style' : '';
	$has_sidebar_layout 			= in_array($layout, array('content-sidebar', 'sidebar-content') ) && $sidebar ? true : false;
?>

<div  id="<?php echo esc_attr( $section_id )?>" class="merimag-section-container <?php echo esc_attr($section_class)?> <?php echo esc_attr($row_class)?> <?php echo esc_attr($video_class)?>"  <?php echo esc_attr($bg_video_data_attr)?>>
	<div class="fw-main-row">
		<div class="<?php echo esc_attr($container_class)?> <?php echo esc_attr($layout)?>">
			<?php 
				if( $has_sidebar_layout ) :
					echo '<div class="site-content-area ">';
				endif;
				echo do_shortcode( $content );
				if( $has_sidebar_layout ) :
					echo '</div>';
				endif;
			?>
			<?php if( $has_sidebar_layout ) : ?>
				<aside id="merimag-secondary" class="merimag-widget-area widget-area">
					<div class="merimag-widget-area-content">
						<?php dynamic_sidebar( $sidebar ); ?>
					</div>
				</aside><!-- #secondary -->
			<?php endif; ?>
			<div class="clear merimag-clear"></div>
		</div>

	</div>
</div>
