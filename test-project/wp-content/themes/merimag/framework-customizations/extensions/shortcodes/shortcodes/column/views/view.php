<?php if (!defined('FW')) die('Forbidden');

$class 			  = fw_ext_builder_get_item_width('page-builder', $atts['width'] . '/frontend_class');

$column_id  	  = merimag_uniqid('merimag-column-');
$selector 		  = '#' . $column_id;
$block_css  	  = isset( $atts ) && is_array( $atts ) ? merimag_get_dynamic_block_style( $atts, $selector ) : '';
$bg_video_data_attr = '';
$video_class = '';
if ( isset( $atts['video'] ) && ! empty( $atts['video'] ) ) {
	$filetype           = wp_check_filetype( $atts['video'] );
	$filetypes          = array( 'mp4' => 'mp4', 'ogv' => 'ogg', 'webm' => 'webm', 'jpg' => 'poster' );
	$filetype           = array_key_exists( (string) $filetype['ext'], $filetypes ) ? $filetypes[ $filetype['ext'] ] : 'video';
	$data_name_attr 	= version_compare( fw_ext('shortcodes')->manifest->get_version(), '1.3.9', '>=' ) ? 'data-background-options' : 'data-wallpaper-options';
	$bg_video_data_attr = $data_name_attr.'="' . fw_htmlspecialchars( json_encode( array( 'source' => array( $filetype => $atts['video'] ) ) ) ) . '"';
	$video_class 	   .= ' background-video';
}

// Dynamic bloc css
wp_register_style( 'merimag-blocks-dynamic-css', false );
wp_enqueue_style( 'merimag-blocks-dynamic-css' );
wp_add_inline_style( 'merimag-blocks-dynamic-css', $block_css );

$style = '';
if( isset( $atts['align_items'] ) && in_array( $atts['align_items'], array('inherit', 'flex-start', 'center', 'flex-end') )  ) {
	$style .= sprintf('align-items: %s;', $atts['align_items'] );
}
if( isset( $atts['justify_content'] ) && in_array( $atts['justify_content'], array('inherit', 'flex-start', 'center', 'flex-end') )  ) {
	$style .= sprintf('justify-content: %s;', $atts['justify_content'] );
}
if( isset( $atts['text_align'] ) && in_array( $atts['text_align'], array('inherit', 'initial', 'left', 'right', 'center') )  ) {
	$style .= sprintf('text-align: %s;', $atts['text_align'] );
}
?>
<div <?php echo esc_attr($bg_video_data_attr)?> class="<?php echo esc_attr($class); ?> merimag-builder-column <?php echo esc_attr($video_class)?>">
	<div style="<?php echo wp_specialchars_decode(esc_attr($style), ENT_QUOTES)?>" class="merimag-builder-column-content" id="<?php echo esc_attr($column_id)?>">
		<div class="merimag-builder-column-shortcodes">
			<?php echo do_shortcode($content); ?>
		</div>
	</div>
</div>
