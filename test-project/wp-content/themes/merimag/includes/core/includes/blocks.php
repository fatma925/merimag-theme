<?php
/**
 * Box
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_box( $atts = array() ) {
	if( !isset( $atts['block_style'] ) ) {
		return;
	}
	$block_style 					 = str_replace('-', '_', $atts['block_style']);
	$block_id 			  			 = isset( $atts['block_id'] ) && is_string( $atts['block_id'] ) && !empty( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-block-');
	$atts['block_id'] 				 = $block_id;
	merimag_get_box_css( $atts );
	$ignore_style		  			 = isset( $atts['ignore_general_style'] ) && $atts['ignore_general_style'] === 'yes' ? true : false;
	$box_class			  			 = $ignore_style === true ? ' ignore-general-style' : '';
	$post_type			  			 = $atts['post_type'];
	$filters_style     				 = isset( $atts['filters_style'] ) ? $atts['filters_style'] : '';
	$filters           				 = isset( $atts['filters'] ) && is_array( $atts['filters'] ) && !empty( $atts['filters'] ) ? true : false;
	$title             				 = isset( $atts['title'] ) && !empty( $atts['title'] ) && is_string( $atts['title'] ) ? true : false;
	$block_title_style         		 = isset( $atts['block_title_style'] ) ? $atts['block_title_style'] : 'default';
	$get_from                  		 = isset( $atts['is_widget'] ) && $atts['is_widget'] === true ? 'widget' : 'general';
    $get_from                  		 = isset( $atts['is_footer'] ) && $atts['is_footer'] === true ? 'footer' : $get_from;
	$block_title_style       	     = merimag_get_block_title_style( $block_title_style, $get_from);

	$paged      = get_query_var('paged');
	$pagination = isset( $atts['pagination'] ) && in_array( $atts['pagination'], merimag_recognized_pagination_options( true, true ) ) ? $atts['pagination'] : 'default';

	$pagination = $pagination === 'default' && isset( $atts['page_query'] ) && $atts['page_query'] === true ? 'newer_older' : $pagination;

	$page      						 = ( isset( $query) && !is_array( $query )  && $paged > 0 ) || ( $pagination === 'block_pagination' && $paged > 0 ) ? $paged : 1;
	$show_posts       				 = isset( $atts['number'] ) && is_numeric( $atts['number'] ) ? $atts['number'] : get_option('posts_per_page');
	$atts['offset'] 				 = $pagination === 'block_pagination' && $page > 1 ? ( $page - 1 ) * $show_posts : ( isset( $atts['offset'] ) ? $atts['offset'] : 0 );
	if( isset( $atts['post_type'] ) && post_type_exists( $atts['post_type'] ) ) {
		$query_keys 		  		 = array('order_by', 'order');
		$query 				  		 = !isset( $atts['page_query'] ) || $atts['page_query'] === false ?merimag_validate_query( $post_type, $atts ) : false;
		$data			  			 = merimag_get_block_data( $query );
		$elements 					 = $data['elements'];
	}

	if( isset( $atts['post_type'] ) && taxonomy_exists( $atts['post_type'] )  ) {
		$data			  			 = merimag_get_block_data_for_taxonomy( $atts );
		$elements 					 = $data['elements'];
	}
	if( isset( $elements ) && count( $elements ) === 0 && !current_user_can( 'edit_posts' ) ) {
		return;
	}

	if( isset($atts['elements'] ) && $atts['post_type'] === 'custom' ) {
		$elements = merimag_get_custom_block_data( $atts, true );
	}

	if( ( !isset( $atts['use_box_container'] ) || $atts['use_box_container'] === true ) && !$filters  ) {

		echo '<div id="' . esc_attr( $block_id ) . '" class="general-box-container ' . esc_attr( $box_class ) . '">';

	}
	if( $title && ( !isset( $atts['is_tab'] ) || $atts['is_tab'] === false ) && ( !isset( $atts['show_title'] ) || $atts['show_title'] === true ) && ( !$filters || $filters_style !== 'beside_title' ) ) {
		$atts['title'] = apply_filters('block_title_filter', $atts['title'] );

	    echo sprintf( '<div class="block-title-wrapper %s"><span class="block-title">%s</span></div>', esc_attr($block_title_style), esc_attr( $atts['title'] ) );

	}
	$atts['block_data']   = merimag_get_box_args( $atts );
	$atts['sliding_data'] = merimag_get_sliding_data( $atts['block_data'] );
	$atts['slick_data']   = merimag_array_to_html_attributes( $atts['sliding_data'] );

	if( isset( $elements ) && count( $elements ) > 0 && post_type_exists( $atts['post_type'] )  ) {

		$container_class = isset( $atts['pagination'] ) && $atts['pagination'] === 'infinite_scroll' ? 'infinite-scroll' : '';

		if( function_exists('merimag_blocks_' . $block_style) )  {

			echo '<div data-id="' . esc_attr( $block_id ) . '" class="merimag-block-data-container ' . esc_attr($container_class) . '">';
				echo sprintf('<div class="merimag-block-data-loader">%s</div>', merimag_get_spinkit() );

				echo '<div class="merimag-block-data-content animatedParent">';
				$block_function = 'merimag_blocks_' . $block_style;
				$block_function( $elements, $atts );
				echo '</div>';

			echo '</div>';

		} else {
			echo sprintf('The block style %s does not exist', esc_attr( $block_style ) );
		}

		$input_atts = $atts;
		unset( $input_atts['slick_data'] );
		echo '<input type="hidden" id="atts-input-' . esc_attr( $block_id ) . '" value="' . esc_attr(str_replace('"', '-quote-', json_encode( $input_atts )) ) . '">';

		
		
		echo '<input data-id="' . esc_attr( $block_id ) . '" data-min="1" data-max="' . esc_attr( $data['pages'] ) . '" type="hidden" class="merimag-page-input" id="page-input-' . esc_attr( $block_id ) . '" value="' . esc_attr( $page ) . '">';

		echo '<input type="hidden" id="block-input-' . esc_attr( $block_id ) . '" value="' . esc_attr( $atts['block_style'] ) . '">';
		if( isset( $data['pages'] ) && $data['pages'] > 1 ) {

			$pagination_data['pages'] 		   = $data['pages'];
			$pagination_data['id']	  		   = $block_id;
			$pagination_data['page']  		   = $page;
			$pagination_data['posts_per_page'] = $data['posts_per_page'];

			merimag_get_block_pagination( $pagination_data, $pagination );

		}


	} elseif( isset( $elements ) && count( $elements ) === 0 && post_type_exists( $atts['post_type'] ) && current_user_can( 'edit_posts' ) ) {

		echo sprintf('<div class="merimag-no-content merimag-full-section-content"><span>%s</span></div>', esc_html__('Nothing to show at the moment !', 'merimag'));

	}
	if( $atts['post_type'] === 'custom' || taxonomy_exists( $atts['post_type']) )  {

		if( function_exists('merimag_blocks_' . $block_style) )  {

			echo '<div data-id="' . esc_attr( $block_id ) . '" class="merimag-block-data-container">';

				echo '<div class="merimag-block-data-loader"><i class="animate-spin icon-spinner1"></i></div>';

				echo '<div class="merimag-block-data-content animatedParent">';

				$block_function = 'merimag_blocks_' . $block_style;

				$block_function( $elements, $atts );

				echo '</div>';

			echo '</div>';

		} else {
			echo sprintf('The block style %s does not exist', esc_attr( $block_style ) );
		}

	}

	if( ( !isset( $atts['use_box_container'] ) || $atts['use_box_container'] === true ) && !$filters ) {

		echo '</div>';

	}
	if( is_admin() && function_exists('rwd_print_styles') ) {
    	rwd_print_styles();
    }
}
/**
 * Box
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_block( $args = array() ) {
		extract($args);
		$merimag_uniqid 		   = merimag_uniqid();
		$action_shortcode  	   = $post_type === 'custom' ? true : false;
		$infos_class 	       = isset( $infos_style ) ? merimag_validate_block_class( $infos_style ) : merimag_validate_block_class();
		$side_infos 		   = in_array( $infos_style, array( 'right', 'left', 'right-classic', 'left-classic', 'left-right-classic', 'left-flex', 'right-flex') ) ? true : false;
		$floated_infos 		   = in_array( $infos_style, array( 'right', 'left', 'right-classic', 'left-classic', 'left-right-classic') ) ? true : false;
		$infos_class 		  .= $floated_infos === true ? ' merimag-side-infos ' : '';
		$block_infos_class 	   = isset( $infos_content_width ) && $infos_content_width === true ? ' site-content-width ' : '';
		$infos_class 	      .= isset( $border_block ) && $border_block === true ?  ' border-block ' : '';
		$infos_class      	  .= isset( $infos_background ) && $infos_background === true ? ' merimag-block-infos-background ' : '';
		$height 			   = isset( $height ) && ( ( is_numeric( $height ) && $height <= 1440 ) || $height === 'auto' ) ? $height : false;
		$font_size 			   = is_numeric($height ) ? $height / 7 : '';
		$height       	       = isset( $marged_image ) && $marged_image === true ? intval( $height  + 60 ) : $height;
		$height 			   = is_numeric( $height ) ? $height . 'px' : $height;
		$img_class 	       	   = isset( $marged_image ) && $marged_image === true ? ' marged-image-in-border-block ' : ''; 
		$block_infos_class    .= isset( $marged_infos ) && $marged_infos === true ? ' marged-infos ' : '';
		$infos_class  	      .= isset( $border_infos ) && $border_infos === true ? ' border-infos ' : '';
		$infos_class 	      .= isset( $small_padding ) && $small_padding === true ? ' small-padding ' : '';
		$positions		       = merimag_get_recognized_infos_positions( true );
		$infos_class     	  .= isset( $infos_position ) && $infos_style === 'absolute' && in_array( $infos_position, $positions ) ? ' ' . $infos_position . ' ' : '';
		$infos_css             = in_array( $infos_style, array( 'left-right-classic', 'left-classic', 'right-classic', 'left', 'right') ) ? 'style="min-height:' . esc_attr( $height ) . '"' : '';
		$infos_class	  	  .= ( isset( $block_white_text ) && $block_white_text === true ) || ( !isset( $block_white_text ) && $infos_style === 'absolute' ) ? ' white-text ' : '';
		$infos_content_class   = '';
		$infos_content_class  .= isset( $dark_infos_back ) && $dark_infos_back === true ? ' dark-background ' : '';
		$infos_content_class  .= isset( $centered_infos ) && $centered_infos === true ? ' centered-infos ' : '';
		$infos_content_class  .= isset( $borderd_infos ) && $borderd_infos === true ? ' bordered ' : '';
		$infos_content_class  .= isset( $white_infos_back ) && $white_infos_back === true ? ' white-background ' : '';
		$infos_content_class  .= isset( $full_height_infos ) && $full_height_infos === true ? ' full-height ' : '';
		$infos_content_class  .= isset( $big_border_top_infos ) && $big_border_top_infos === true ? ' principal-color-border-top-color big-border-top-infos ' : '';
		$block_infos_class 	  .= isset( $vcentered_infos ) && $vcentered_infos === true ? ' vertical-centered-infos' : '';
		$beside_title		   = isset( $beside_title ) && is_string( $beside_title ) ? $beside_title : '';
		$beside_title 		   = !$beside_title && isset( $show_number ) && $infos_style === 'text' && $show_number === true && isset( $i ) && is_numeric( $i ) ? merimag_html_helper('colorful_square', $i ) : $beside_title;
		$show_title	 		   = isset( $show_title ) && $show_title	=== false ? false : true;
		$infos_width           = isset( $infos_width ) && is_numeric( $infos_width ) && $infos_width > 0 && $infos_width <= 100 ? $infos_width : 'auto';
		$before_title 		   = isset( $before_title ) && is_string( $before_title ) ? $before_title : '';
		$before_title 		   = $post_type === 'product' && $show_category === true ? merimag_meta_info('product_category', $element->ID) : $before_title;
		$before_title 		   = $post_type === 'post' && $show_category === true ? merimag_meta_info('category', $element->ID) : $before_title;

		$after_title 		   = isset( $after_title ) && is_string( $after_title ) ? $after_title : '';
		$hover_description     = isset( $hover_description ) && is_bool( $hover_description ) ? $hover_description : false;
		$top_left 		  	   = isset( $top_left ) && is_string( $top_left ) ? $top_left : '';
		$top_left 			   = isset( $show_sale ) && $show_sale === true ? merimag_meta_info('sale', $element->ID) : $top_left;
		$top_right 		  	   = isset( $top_right ) && is_string( $top_right ) ? $top_right : '';
		$bottom_left 		   = isset( $bottom_left ) && is_string( $bottom_left ) ? $bottom_left : '';
		$bottom_right 		   = isset( $bottom_right ) && is_string( $bottom_right ) ? $bottom_right : '';
		$center_center 		   = isset( $center_center ) && is_string( $center_center ) ? $center_center : '';
		$center_center 		   = $post_type === 'post' && $show_format_icon === true && $infos_style !== 'absolute' ? merimag_meta_info('format_icon', $element->ID) : $center_center;
		$top_right 		   	   = $post_type === 'post' && $show_format_icon === true && $infos_style === 'absolute' ? merimag_meta_info('format_icon', $element->ID) : $top_right;


		$top_right 		   	   = $post_type === 'post' && $show_review === true && $review_display_style !== 'bar' && merimag_get_review_score( $element->ID, $review_display_style ) ? merimag_get_review_score( $element->ID, $review_display_style ) : $top_right;
		$review_bar 		   = $post_type === 'post' && $show_review === true  && $review_display_style === 'bar' ? merimag_get_review_score( $element->ID, $review_display_style ) : '';
		$img_class 			  .= in_array($infos_style, array('bellow') ) ? 'bottom-spaced' : '';
		$img_class			  .= isset( $absolute_image ) && $absolute_image === true ? ' merimag-block-absolute-image' : '';
		$image_ratio 		   = isset( $image_ratio ) && is_string( $image_ratio ) ? $image_ratio : '9-16';
		$img_class 		  	   .= $height === 'auto' && is_string($image_ratio) ? ' merimag-' . $image_ratio : '';
		$title_ellipsis		   = isset( $title_ellipsis ) && is_numeric( $title_ellipsis ) ? $title_ellipsis : false;
		$description_ellipsis  = isset( $description_ellipsis ) && is_numeric( $description_ellipsis ) ? $description_ellipsis : 140;
		$style 				   = $height !== 'auto' ? ' height: ' . esc_attr($height) .';' : '';
		$style 				  .= $font_size ? sprintf('font-size: %spx', esc_attr($font_size) ) : '';
		$animation 	  		   = isset( $animation ) && !empty( $animation ) && is_string( $animation ) ? $animation : '';
		$animation 			   = $animation === 'Default' && apply_filters( 'merimag_default_block_animation', $animation ) !== 'Default' ? apply_filters( 'merimag_default_block_animation', $animation ) : $animation;
		$animation 			   = $animation === 'Default' ? '' : $animation;
		$animation_speed 	   = $animation_speed === 'default' || empty( $animation_speed ) ? 'faster' : $animation_speed;

		$animation 			   = !empty( $animation ) && $animation !== 'default' ? $animation . ' ' . $animation_speed : '';
		$container_animation   = $infos_style === 'absolute' || empty( $animation ) ? '' : ' id= "container-' . $merimag_uniqid . '" data-animation-classes="animated  ' . $animation . '"';
		$infos_animation 	   = $infos_style === 'absolute' && !empty( $animation ) && $animation !== 'default' ? ' id= "infos-' . $merimag_uniqid . '" data-animation-classes="animated  ' . $animation . '"' : '';
		$infos_animation 	  .=  isset( $i ) && is_numeric( $i ) ? ' data-animation-delay="' . intval($i + 1) * 50 . '"' : '';
		$container_animation  .=  isset( $i ) && is_numeric( $i ) ? ' data-animation-delay="' . intval($i + 1) * 50 . '"' : '';
		$infos_background      = '';
		$offset 			   = isset( $offset ) && is_numeric( $offset ) ? $offset : 0;
		$i 					   = isset( $i ) && is_numeric( $i ) ? $i  : 0;
		$number 			   =  $i + $offset;
		
		if( isset( $title_box_background ) ) {
			if( isset( $title_box_background['primary'] ) && isset( $title_box_background['secondary'] ) && isset( $title_box_background['degree'] ) ) {
				if( !empty( $title_box_background['primary'] ) && !empty( $title_box_background['secondary'] ) ) {
					$infos_background = 'background: linear-gradient( ' . esc_attr(  $title_box_background['degree'] ) . ', ' . esc_attr(  $title_box_background['primary'] ) . ', ' . esc_attr(  $title_box_background['secondary'] ) . ' );';
				}
				if( !empty( $title_box_background['primary'] ) && empty( $title_box_background['secondary'] ) ) {
					$infos_background = 'background: ' . esc_attr(  $title_box_background['primary'] ) . ';';
				}
			}
		}

		
		$post_thumbnail_id 	  = isset( $element->thumbnail_id ) ? $element->thumbnail_id : false;
		$img_class .= !$post_thumbnail_id ? ' merimag-no-image ' : '';

		$block_image_args 	  = array('args' => $args, 'class' => sprintf('merimag-block-image %s', $img_class ), 'style' => $style, 'thumbnail_id' => $post_thumbnail_id );

		$color_layer_style 	  = isset( $color_layer ) && $color_layer === true ? merimag_get_color_layer( $number ) : '';
		$img_dim = merimag_get_image_size($image_size, true );
		$img_full = wp_get_attachment_image_src($post_thumbnail_id ,$img_dim);
		
		$img_dim['quality'] = 100;

		$thumb_src = bfi_thumb( $img_full[0], array('quality' => 100) );
	?>
	<article <?php echo wp_specialchars_decode(esc_attr($container_animation), ENT_QUOTES)?> class="merimag-block merimag-block-infos merimag-block-infos-<?php echo esc_attr($infos_class)?> <?php echo esc_attr($class)?> general-border-color " <?php echo wp_specialchars_decode(esc_attr($infos_css), ENT_QUOTES)?>>
		<?php  if( !in_array($infos_style, array('text') ) ) : ?>
			<?php if( $image_ratio !== 'original_ratio') : ?>
			<?php echo wp_specialchars_decode(esc_attr(merimag_get_thumbnail_background_tag( $block_image_args )), ENT_QUOTES)?>
			<?php else : ?>
			<div class="merimag-block-image original-ratio">
				<?php if( $thumb_src ) : ?>
					<img decoding="async" src="<?php echo esc_url($thumb_src); ?>" class="webtecore-block-img" alt="<?php echo get_the_title($element->ID); ?>" />
				<?php endif; ?>
			<?php endif; ?>
				<span  style="<?php echo esc_attr($color_layer_style)?>"  class="merimag-block-color-layer"></span>
				<a class="merimag-block-link" href="<?php echo esc_url($element->link)?>" title="<?php echo esc_attr($element->title)?>"></a>
				
				<?php if( isset( $show_number ) && $infos_style !== 'text' && $show_number === true && isset( $number ) && is_numeric( $number ) ) : ?>
					<div class="merimag-block-order-number principal-color-background-color"><span><?php echo esc_attr($number + 1)?></span></div>
				<?php endif; ?>
				<?php if( $review_bar ) : ?>
				<div class="merimag-review-bar"><?php echo wp_specialchars_decode(esc_attr($review_bar), ENT_QUOTES)?></div>
				<?php endif; ?>
				<?php if( $top_left ) : ?>
				<div class="merimag-block-top-left"><?php echo wp_specialchars_decode(esc_attr($top_left), ENT_QUOTES)?></div>
				<?php endif; ?>
				<?php if( $top_right ) : ?>
				<div class="merimag-block-top-right"><?php echo wp_specialchars_decode(esc_attr($top_right), ENT_QUOTES)?></div>
				<?php endif; ?>
				<?php if( $bottom_left ) : ?>
				<div class="merimag-block-bottom-left"><?php echo wp_specialchars_decode(esc_attr($bottom_left), ENT_QUOTES)?></div>
				<?php endif; ?>
				<?php if( $bottom_right ) : ?>
				<div class="merimag-block-bottom-right"><?php echo wp_specialchars_decode(esc_attr($bottom_right), ENT_QUOTES)?></div>
				<?php endif; ?>
				<?php if( $center_center ) : ?>
				<div class="merimag-block-center-center"><?php echo wp_specialchars_decode(esc_attr($center_center), ENT_QUOTES)?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if( $infos_style !== 'image' ) : ?>
		<div <?php echo isset($grid_id) ? 'data-mh="' . esc_attr( $grid_id) . '"' : ''?> class="merimag-block-infos <?php echo esc_attr($block_infos_class)?>" >
			<div <?php echo wp_specialchars_decode(esc_attr($infos_animation))?> style="width: <?php echo esc_attr($infos_width)?>%; <?php echo esc_attr($infos_background)?>" class="merimag-block-infos-content <?php echo esc_attr($infos_content_class)?>">
				<div class="merimag-block-infos-content-inner">
					<?php if( ( $show_title === true || $beside_title ) && !empty( $element->title ) && ( !isset( $action_shortcode )  || $action_shortcode === false )  ) : ?>
					<?php $size .= isset( $force_size ) && $force_size === true ? ' force-size ' : ''; ?>
					<div class="block-infos-title-wrapper <?php echo esc_attr($size)?>">
						<?php if( $beside_title ) : ?>
						<?php echo wp_specialchars_decode( esc_attr($beside_title), ENT_QUOTES ); ?>
						<?php endif; ?>
						<?php 
							$title = $show_title === true ? $element->title : '';
							merimag_get_title( $title, $element->link, $before_title, $after_title, $title_length, $title_ellipsis );
						?>
					</div>
					<?php endif; ?>
					<?php if( isset( $element->sub_title ) && !empty( $element->sub_title ) && ( !isset( $action_shortcode ) || $action_shortcode === false )  ) : ?>
					<div class="block-infos-title merimag-block-infos-subtitle  <?php echo esc_attr($sub_title_size)?>">
						<span class="title-display"><?php echo wp_specialchars_decode(esc_attr( $element->sub_title), ENT_QUOTES) ?></span>
					</div>
					<?php endif; ?>
					<?php  if( $show_description === true && isset( $element->description ) && !empty( $element->description ) ) : ?>
						<?php $desc_class = $hover_description === true ? 'merimag-block-description-hover' : ''; ?>
						<div class="merimag-block-description <?php echo esc_attr($desc_class)?>">
							<div class="merimag-block-vertical-spacer"></div>
							<?php $description_length = isset( $description_length ) && is_numeric( $description_length ) ? $description_length : false; ?>
							<?php $description_class = $description_ellipsis > 0 && $description_ellipsis < 12 ? ' merimag-line-clamp merimag-line-clamp-' . $description_ellipsis : ''; ?>
							<p class="<?php echo esc_attr($description_class)?>">
							<?php 
								if( $description_length ) {
									echo esc_attr( merimag_substr( $element->description, 0, $description_length ) );
								} else {
									esc_attr( $element->description );
								}
							?>
							</p>
						</div>
					<?php endif; ?>
					<?php if( isset( $action_shortcode ) && $action_shortcode === true && isset( $element->atts ) ) : ?>
						<?php 
							$action_atts = $element->atts;
							$action_atts['block_id'] = $action_atts['block_id'] . '-action';
						?>
						<?php merimag_get_shortcode_html( 'action', $action_atts ); ?>
					<?php endif; ?>
					<?php if( isset( $element->content ) && !empty( $element->content ) ) : ?>
						<div class="merimag-block-infos-custom-content"><?php echo wp_specialchars_decode(esc_attr(do_shortcode($element->content)), ENT_QUOTES)?></div>
					<?php endif; ?>
					<?php if( isset( $show_read_more ) && $show_read_more === true ) : ?>
						<a class="merimag-read-more" href="<?php echo esc_url($element->link)?>"><?php echo esc_html__('Read more', 'merimag')?></a>
					<?php endif; ?>
					<?php if( isset( $show_add_to_cart ) && $show_add_to_cart === true ) : ?>
						<?php echo wp_specialchars_decode(esc_attr(merimag_meta_info('add_to_cart', $element->ID)), ENT_QUOTES ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="merimag-clear"></div>
	</article>
	<?php if( isset( $separator ) && $separator === true ) : ?>
		<div class="merimag-block-separator"></div>
	<?php endif; ?>
<?php
}
/**
 * Grid style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_grid( $elements, $atts ) {
	$load_next    = isset( $atts['load_next'] ) && $atts['load_next'] === true ? true : false;
	$block_data   = isset( $atts['block_data'] ) ? $atts['block_data'] : array();

	$grid_style   = isset( $block_data['grid_style'] ) ? $block_data['grid_style'] : 'simple';
	$grid_columns = isset( $block_data['grid_columns'] ) ? $block_data['grid_columns'] : 2;
	$grid_spacing = isset( $block_data['grid_spacing'] ) ? $block_data['grid_spacing'] : 'default';
	$grid_masonry = isset( $block_data['grid_masonry'] )  && $block_data['grid_masonry'] === true ? true : false;
	$separator 		= isset( $atts['separator'] ) && $atts['separator'] === true ? true : false;
	$responsive = $grid_style === 'image' ? 'merimag-not-responsive-grid' : 'merimag-responsive-grid';
	switch ($grid_columns) {
		case '1-2':
			$grid_class = 'merimag-grid-50 merimag-grid-columns';
			break;
		case '2': 
			$grid_class = 'merimag-grid-50 merimag-grid-columns';
			break;
		case '1-3':
			$grid_class = 'merimag-grid-33 merimag-grid-columns';
			break;
		case '3':
			$grid_class = 'merimag-grid-33 merimag-grid-columns';
			break;
		case '4':
			$grid_class = 'merimag-grid-25 merimag-grid-columns';
			break;
		case '5':
			$grid_class = 'merimag-grid-20 merimag-grid-columns';
			break;
		default:
			$grid_class = '';
			break;
	}
	$grid_class .= ' ' . $responsive;
	$grid_class .= isset( $grid_spacing ) && !empty( $grid_spacing ) && $grid_spacing !== 'default' ? ' merimag-' . esc_attr( $grid_spacing ) . '-spacing' : ' merimag-medium-spacing';
	$grid_class .= isset( $grid_masonry ) && $grid_masonry === true ? ' merimag-grid-masonry' : '';
	$grid_class .= isset( $grid_style ) && in_array( $grid_style, array( 'right', 'left', 'left-classic', 'right-classic', 'left-right-classic') ) ? ' merimag-grid-equal-height ' : '';
	$grid_class .= isset( $grid_style ) && in_array( $grid_style, array('right', 'left', 'left-classic', 'right-classic', 'left-right-classic') ) ? ' merimag-grid-side-infos ' : '';

	$grid_class .= $grid_style === 'image' ? '' : ' merimag-responsive-grid ';

	$grid_id     = merimag_uniqid('merimag-grid-');
	?>
	<?php if( !isset( $load_next ) || $load_next === false ) : ?>
	<div data-id="<?php echo esc_attr($grid_id)?>" class="merimag-block-content merimag-block-grid merimag-grid <?php echo esc_attr($grid_class)?>">
	<?php endif; ?>
	<?php 
		$count   = 0;

		for( $i = 0; $i <= count($elements) - 1; $i++ ) :

			if( isset( $elements[$i]->ID ) ) {

				$column_class  	  				 = $grid_columns === '1-2' && $count == 0 ? 'merimag-full-column merimag-column' : 'merimag-column';
				$column_class      				 = $grid_columns === '1-3' && $count == 0 ? 'merimag-full-column merimag-column' : $column_class;
				$column_class     				.= $i % 2 ? ' merimag-even-column' : ' merimag-odd-column';

				echo '<div class="' . esc_attr( $column_class ) . '">';

				$args 			  				 = merimag_get_block_args( $elements[$i], $block_data );
				extract( $args );
				$block_args 					 = $args;
				$block_args['post_type'] 		= isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
				$block_args['i'] 				 = $i; 
				$block_args['element']			 = $elements[$i];
				$block_args['height']            = isset( $grid_masonry ) && $grid_masonry === true && !in_array( $grid_style, array( 'right', 'left', 'right-classic', 'left-classic', 'left-right-classic') ) ? random_int( intval($height), intval($height) * 2 ) : $height;
				$args['border_block'] 			 = $grid_style === 'left-right-classic' ? true : $args['border_block'];
				$block_args['infos_background']  = isset( $args['border_block'] ) && $args['border_block'] === true && $grid_style === 'simple' ? true : false;
				$block_args['marged_infos']      = isset( $grid_style ) && $grid_style === 'modern' ? true : false;
				$block_args['border_block']      = isset( $args['border_block'] ) && $args['border_block'] === true ? true : false;
				if( isset( $atts['post_type'] ) && in_array( $atts['post_type'], array('product_cat', 'category') ) && isset( $show_count ) && $show_count === true ) {
					$tax_count_meta 				 = $atts['post_type'] === 'product_cat' ? 'product_cat_count' : 'category_count';
					$block_args['after_title']		 = merimag_meta_info( $tax_count_meta, $elements[$i]->ID);
				}
				$block_args['absolute_image']	 = in_array( $grid_style, array('right-classic', 'left-classic', 'left-right-classic', 'right', 'left') ) ? true : false;

				$grid_styles = array('absolute', 'right-classic', 'left-classic', 'left-right-classic', 'left', 'right', 'text', 'image');
				if( isset( $grid_style ) && in_array( $grid_style, $grid_styles ) ) {

					$block_args['infos_style'] = $grid_style;
				}

				merimag_blocks_block( $block_args );

				echo '</div>';
			}

			$count++;	
			$count = $count === 5 && $grid_columns === '1-2' ? 0 : $count;
			$count = $count === 7 && $grid_columns === '1-3' ? 0 : $count;
		endfor;
	?>
	<?php if( !isset( $load_next ) || $load_next === false ) : ?>
	</div>
	<?php endif; ?>
<?php
}
/**
 * Carousel style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_carousel( $elements, $atts = array() ) {
	$load_next    	 = isset( $atts['load_next'] ) && $atts['load_next'] === true ? true : false;
	$slick_data 	 = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data      = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$grid_style   	 = isset( $block_data['grid_style'] ) ? $block_data['grid_style'] : 'simple';

	$grid_spacing 	 = isset( $block_data['grid_spacing'] ) && in_array($block_data['grid_spacing'], merimag_get_recognized_grid_spacing(true)) ? $block_data['grid_spacing'] : 'default';
	$class 			 = sprintf('merimag-%s-spacing', $grid_spacing );
	

	$grid_id     	 = merimag_uniqid('merimag-carousel-');

	$grid_columns 	 = isset( $block_data['grid_columns'] ) ? $block_data['grid_columns'] : 3;
	
	switch ($grid_columns) {
		case '2':
			$class .= ' merimag-grid-50';
			break;
		case '3':
			$class .= ' merimag-grid-33';
			break;
		case '4':
			$class .= ' merimag-grid-25';
			break;
		case '5':
			$class .= ' merimag-grid-20';
			break;
		case '6':
			$class .= ' merimag-grid-16';
			break;
	}

	?>

	<div class="merimag-block-content">
		<div  class="merimag-carousel-container merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($grid_id)?>" class=" merimag-block-carousel merimag-grid <?php echo esc_attr($class)?> merimag-slick-block merimag-carousel">
				<?php 
					$count   = 0;

					for( $i = 0; $i <= count($elements) - 1; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-carousel-item merimag-column">';


							$args 			   				 = merimag_get_block_args( $elements[$i], $block_data );
							
							extract( $args );

							$block_args 					 = $args;
							$block_args['post_type'] 		 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 				 = $i; 
							$block_args['element']			 = $elements[$i];


							$block_args['height']            = isset( $grid_masonry ) && $grid_masonry === true && !in_array( $grid_style, array( 'right', 'left', 'right-classic', 'left-classic', 'left-right-classic') ) ? random_int( $height, $height * 2 ) : $height;


							$block_args['infos_background']  = isset( $args['border_block'] ) && $args['border_block'] === true && $grid_style === 'simple' ? true : false;
							$block_args['marged_infos']      = isset( $grid_style ) && $grid_style === 'modern' ? true : false;
							$block_args['border_block']      = isset( $args['border_block'] ) && $args['border_block'] === true ? true : false;
							$block_args['center_center']	 = $grid_style === 'absolute' || $marged_infos === true ? '' : $center_center;
							$block_args['absolute_image']	 = in_array( $grid_style, array('right-classic', 'left-classic', 'left-right-classic', 'left', 'right') ) ? true : false;
							if( isset( $atts['post_type'] ) && in_array( $atts['post_type'], array('product_cat', 'category') ) && isset( $show_count ) && $show_count === true ) {
								$tax_count_meta 				 = $atts['post_type'] === 'product_cat' ? 'product_cat_count' : 'category_count';
								$block_args['after_title']		 = merimag_meta_info( $tax_count_meta, $elements[$i]->ID);
							}
							$grid_styles = array('absolute', 'right-classic', 'left-classic', 'image', 'left-right-classic', 'left', 'left-flex', 'right-flex', 'right', 'text');

							if( isset( $grid_style ) && in_array( $grid_style, $grid_styles ) ) {

								$block_args['infos_style'] = $grid_style;
							}

							merimag_blocks_block( $block_args );

							echo '</div>';
						}

						$count++;	
					endfor;
				?>
			</div>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($grid_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($grid_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
		</div>
	</div>
	<?php
}
/**
 * slider style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_slider( $elements, $atts = array() ) {
	$slider_id    = merimag_uniqid('merimag-slider-'); 
	$slick_data   = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data   = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$center_mode  = isset( $block_data['center_mode'] ) && $block_data['center_mode'] === true ? true : false;
	$slider_class = $center_mode ? 'merimag-slider-block-centered' : '';
	?>
	<div class="merimag-block-content">
	 	<div class="merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($slider_id)?>" class="merimag-slider-block merimag-slick-block merimag-slick-block <?php echo esc_attr($slider_class)?>">
			<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

					if( isset( $elements[$i]->ID ) ) {

						$args = merimag_get_block_args( $elements[$i], $block_data );

						extract( $args );
						echo '<div class="merimag-slider-item">';
						
						$block_args 			  	 = $args;
						$block_args['post_type']  	 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 = $i; 
						$block_args['element']	  	 = $elements[$i];

						$block_args['top_left']      = '';
						$block_args['infos_style']   = 'absolute';
						$block_args['center_center'] = '';

						merimag_blocks_block( $block_args );

						

						echo '</div>';
					}

			endfor ;?>
			</div>
			<?php $show_arrows  = isset( $atts['show_arrows'] ) && $atts['show_arrows'] === 'yes' ? true : false; ?>
			<?php if( $show_arrows === true ) : ?>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
/**
 * slider 5 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_slider_5( $elements, $atts = array() ) {
	$slider_id  = merimag_uniqid('merimag-slider-');
	$slick_data = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($slider_id)?>" class="merimag-slider-block merimag-slick-block merimag-slider-with-bottom-thumbs merimag-dots-top-center merimag-dots-absolute">
			<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

					if( isset( $elements[$i]->ID ) ) {

						echo '<div class="merimag-slider-item">';

						$args 				= merimag_get_block_args( $elements[$i], $block_data );

						extract( $args );

						$block_args 			  		   = $args;
						$block_args['post_type']  	 	   = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 	   = $i; 
						$block_args['element']	  	 	   = $elements[$i];
						$block_args['top_left']     	   = '';
						$block_args['infos_style']         = 'absolute';
						$block_args['infos_position']      = 'center-center';
						$block_args['centered_infos']      = false;
						$block_args['block_white_text']    = true;
						$block_args['center_center']	   = '';
						$block_args['centered_infos'] 	   = true;

						merimag_blocks_block( $block_args );

						echo '</div>';
					}

			endfor ;?>
			</div>
			<?php $show_arrows  = isset( $atts['show_arrows'] ) && $atts['show_arrows'] === 'yes' ? true : false; ?>
			<?php if( $show_arrows === true ) : ?>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
			<?php endif; ?>
			<div class="merimag-slider-thumbs merimag-slider-thumbs-text merimag-slider-thumbs-bottom">
				<div data-id="<?php echo esc_attr($slider_id)?>" id="<?php echo esc_attr($slider_id)?>-thumbs" class="merimag-slider-thumbs-content merimag-slick-block merimag-equal-height site-content-width">
					<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-slider-thumb-item">';

							$args 				= merimag_get_block_args( $elements[$i], $block_data );

							extract( $args );

							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']                	 = 'small';
							$block_args['force_size']			 = true;
							$block_args['height']		  	     = 50;
							$block_args['show_description']    	 = false;
							$block_args['show_read_more']    	 = false;
							$block_args['show_review']    	 	 = false;
							$block_args['show_category'] 		 = false;
							$block_args['before_title']        	 = '';	
							$block_args['after_title']         	 = '';
							$block_args['infos_width'] 		 	 = 100;
							$block_args['top_left']     	     = '';
							$block_args['infos_style']           = 'text';
							$block_args['block_white_text']	 	 = true;
							$block_args['center_center']		 = '';
							$block_args['infos_content_width']   = false;
							$block_args['title_length']		 	 = 38;
							$block_args['title_ellipsis']		 = 2;
							$block_args['beside_title']		 	 = merimag_html_helper('rounded_number_big_dark', $i + 1 );	
							$block_args['centered_infos']		 = false;

							merimag_blocks_block( $block_args );

							echo '</div>';
						}

					endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * slider 6 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_slider_6( $elements, $atts = array() ) {
	$slider_id  = merimag_uniqid('merimag-slider-');
	$slick_data = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($slider_id)?>" class="merimag-slider-block merimag-slick-block merimag-dots-absolute merimag-slider-with-bottom-thumbs merimag-dots-top-center">
			<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

					if( isset( $elements[$i]->ID ) ) {

						echo '<div class="merimag-slider-item">';

						$element 		   = $elements[$i];	
						$args = merimag_get_block_args( $elements[$i], $block_data );
						extract( $args );

						$block_args 			  			 = $args;
						$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 		 = $i; 
						$block_args['element']	  	 		 = $elements[$i];
						$block_args['top_left']     	     = '';
						$block_args['infos_style']           = 'absolute';
						$block_args['infos_position']        = 'center-center';
						$block_args['centered_infos']		 = true;
						$block_args['block_white_text']    	 = true;
						$block_args['center_center']	     = '';
						$block_args['infos_content_width'] 	 = true;

						merimag_blocks_block( $block_args );

						echo '</div>';
					}

			endfor ;?>
			</div>
			<?php $show_arrows  = isset( $atts['show_arrows'] ) && $atts['show_arrows'] === 'yes' ? true : false; ?>
			<?php if( $show_arrows === true ) : ?>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
			<?php endif; ?>
			<div class="merimag-slider-thumbs merimag-slider-thumbs-text merimag-slider-thumbs-bottom">
				<div data-id="<?php echo esc_attr($slider_id)?>"  id="<?php echo esc_attr($slider_id)?>-thumbs" class="merimag-slider-thumbs-content site-content-width merimag-slick-block">
					<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-slider-thumb-item">';

							$element 		   = $elements[$i];	
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']                	 = 'small';
							$block_args['force_size']			 = true;
							$block_args['height']		  	     = 50;
							$block_args['show_description']    	 = false;
							$block_args['show_read_more']    	 = false;
							$block_args['show_review']    	 	 = false;
							$block_args['show_category'] 		 = false;
							$block_args['before_title']        	 = '';	
							$block_args['after_title']         	 = '';	
							$block_args['top_left']     	     = '';
							$block_args['infos_width'] 		 	 = 100;
							$block_args['infos_style']         	 = 'left';
							$block_args['block_white_text']	 	 = true;
							$block_args['center_center']		 = '';
							$block_args['infos_content_width'] 	 = false;
							$block_args['title_length']		 	 = 38;
							$block_args['title_ellipsis']		 = 2;
							$block_args['centered_infos']		 = false;

							merimag_blocks_block( $block_args );

							echo '</div>';
						}

					endfor ;?>
				</div>
			</div>
		</div>
	</div>

  	<?php
}
/**
 * slider 7 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_slider_7( $elements, $atts = array() ) {
	$slider_id  = merimag_uniqid('merimag-slider-');
	$slick_data = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$dots_class = !is_rtl() ? 'merimag-dots-top-left' : 'merimag-dots-top-right';
	?>
	<div class="merimag-block-content">
		<div class="merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($slider_id)?>" class="merimag-slider-block merimag-slick-block <?php echo esc_attr( $dots_class ); ?> merimag-dots-absolute">
			<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

					if( isset( $elements[$i]->ID ) ) {

						echo '<div class="merimag-slider-item">';

						$element 		   = $elements[$i];	
						$args = merimag_get_block_args( $elements[$i], $block_data );
						extract( $args );

						$block_args 			  			 = $args;
						$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 		 = $i; 
						$block_args['element']	  	 		 = $elements[$i];
						$block_args['top_left']     	     = '';
						$block_args['infos_style']         	 = 'absolute';
						$block_args['infos_position']      	 = 'left-bottom';
						$block_args['dark_infos_back']     	 = true;
						$block_args['big_border_top_infos']  = true;
						$block_args['block_white_text']    	 = true;
						$block_args['center_center']	     = '';
						$block_args['infos_content_width'] 	 = true;

						merimag_blocks_block( $block_args );

						echo '</div>';
					}

			endfor ;?>
			</div>
			<?php $show_arrows  = isset( $atts['show_arrows'] ) && $atts['show_arrows'] === 'yes' ? true : false; ?>
			<?php if( $show_arrows === true ) : ?>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
			<?php endif; ?>
			<div class="merimag-slider-thumbs-container site-content-width">
				<div class="merimag-slider-thumbs merimag-slider-thumbs-text merimag-slider-thumbs-right">
					<div data-id="<?php echo esc_attr($slider_id)?>"  id="<?php echo esc_attr($slider_id)?>-thumbs" data-number="<?php echo esc_attr(count($elements))?>" class="merimag-slider-thumbs-content merimag-slick-block">
						<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div data-id="' . esc_attr($slider_id) . '" data-index="' . esc_attr( $i ) . '" class="merimag-slider-thumb-item">';

								$element 		   = $elements[$i];	
								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );

								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']                	 = 'tiny';
								$block_args['height']		  	     = 57;
								$block_args['show_description']    	 = false;
								$block_args['show_read_more']    	 = false;
								$block_args['show_review']    	 	 = false;
								$block_args['show_category'] 		 = false;
								$block_args['force_size']			 = true;
								$block_args['before_title']        	 = '';	
								$block_args['after_title']         	 = merimag_meta_info('date', $element->ID );	
								$block_args['top_left']     	     = '';
								$block_args['infos_width'] 		 	 = 100;
								$block_args['infos_style']         	 = 'left';
								$block_args['block_white_text']	 	 = true;
								$block_args['center_center']		 = '';
								$block_args['infos_content_width'] 	 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['title_length']		 	 = 38;
								$block_args['centered_infos']		 = false;

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

						endfor ;?>
					</div>
				</div>
			</div>
		</div>
	</div>

  	<?php
}
/**
 * slider 8 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_slider_8( $elements, $atts = array() ) {
	$slider_id  = merimag_uniqid('merimag-slider-');
	$slick_data = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$dots_class = !is_rtl() ? 'merimag-dots-top-left' : 'merimag-dots-top-right';
	?>
	<div class="merimag-block-content">
		<div class="merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($slider_id)?>" class="merimag-slider-block merimag-slick-block merimag-dots-absolute <?php echo esc_attr($dots_class)?>">
			<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

					if( isset( $elements[$i]->ID ) ) {

						echo '<div class="merimag-slider-item">';

						$element 		   = $elements[$i];	
						$args = merimag_get_block_args( $elements[$i], $block_data );
						extract( $args );
						$block_args 			  			 = $args;
						$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 		 = $i; 
						$block_args['element']	  	 		 = $elements[$i];
						
						$block_args['top_left']     	     = '';
						$block_args['infos_style']         = 'absolute';
						$block_args['infos_position']      = 'left-bottom';
						$block_args['dark_infos_back']     = true;
						
						$block_args['block_white_text']    = true;
						$block_args['center_center']	     = '';
						$block_args['infos_content_width'] = true;

						merimag_blocks_block( $block_args );

						echo '</div>';
					}

			endfor ;?>
			</div>
			<?php $show_arrows  = isset( $atts['show_arrows'] ) && $atts['show_arrows'] === 'yes' ? true : false; ?>
			<?php if( $show_arrows === true ) : ?>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
			<?php endif; ?>
			<div class="merimag-slider-thumbs-container  site-content-width">
				<div class="merimag-slider-thumbs merimag-slider-thumbs-text merimag-slider-thumbs-right merimag-slider-thumbs-number">
					<div data-id="<?php echo esc_attr($slider_id)?>"  id="<?php echo esc_attr($slider_id)?>-thumbs" data-number="<?php echo esc_attr(count($elements))?>" class="merimag-slider-thumbs-content merimag-slick-block merimag-content-width">
						<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div data-id="' . esc_attr($slider_id) . '" data-index="' . esc_attr( $i ) . '" class="merimag-slider-thumb-item">';

								$element 		   = $elements[$i];	
								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['force_size']			 = true;	
								$block_args['size']                	 = 'small';
								$block_args['height']		  	     = 50;
								$block_args['show_description']    	 = false;
								$block_args['show_read_more']    	 = false;
								$block_args['show_review']    	 	 = false;
								$block_args['show_category'] 		 = false;
								$block_args['before_title']        	 = '';	
								$block_args['after_title']         	 = '';	
								$block_args['top_left']     	     = '';
								$block_args['infos_style']         	 = 'text';
								$block_args['infos_width'] 		 	 = 100;
								$block_args['block_white_text']	 	 = true;
								$block_args['center_center']		 = '';
								$block_args['infos_content_width'] 	 = false;
								$block_args['centered_infos']		 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['title_length']		 	 = 38;
								$block_args['beside_title']		 	 = merimag_html_helper('rounded_number_big_white', $i + 1 );	

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

						endfor ;?>
					</div>
				</div>
			</div>
		</div>
	</div>

  	<?php
}
/**
 * slider 9 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_slider_9( $elements, $atts = array() ) {
	$slider_id  = merimag_uniqid('merimag-slider-');
	$slick_data = isset( $atts['slick_data'] ) ? $atts['slick_data'] : array();
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$dots_class = !is_rtl() ? 'merimag-dots-top-left' : 'merimag-dots-top-right';
	?>
	<div class="merimag-block-content">
		<div class="merimag-slider-block-container">
			<div <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> id="<?php echo esc_attr($slider_id)?>" class="merimag-slider-block merimag-slick-block <?php echo esc_attr($dots_class); ?> merimag-dots-absolute">
			<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

					if( isset( $elements[$i]->ID ) ) {

						echo '<div class="merimag-slider-item">';

						$element 		   = $elements[$i];	
						$args = merimag_get_block_args( $elements[$i], $block_data );
						extract( $args );
						$block_args 			  			 = $args;
						$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 		 = $i; 
						$block_args['element']	  	 		 = $elements[$i];
						
						$block_args['top_left']     	     = '';
						$block_args['infos_style']         	 = 'absolute';
						$block_args['infos_position']      	 = 'left-bottom';
						$block_args['dark_infos_back'] 	 	 = true;
						
						$block_args['lock_white_text']    	 = true;
						$block_args['center_center']	     = '';
						$block_args['infos_content_width'] 	 = true;

						merimag_blocks_block( $block_args );

						echo '</div>';
					}

			endfor ;?>
			</div>
			<?php $show_arrows  = isset( $atts['show_arrows'] ) && $atts['show_arrows'] === 'yes' ? true : false; ?>
			<?php if( $show_arrows === true ) : ?>
			<div class="merimag-slider-arrows merimag-slider-arrows-free">
				<span class="merimag-slider-prev" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-left"></i></span>
				<span class="merimag-slider-next" data-id="<?php echo esc_attr($slider_id)?>"><i class="fa fa-caret-right"></i></span>
			</div>
			<?php endif; ?>
			<div class="merimag-slider-thumbs-container  site-content-width">
				<div class="merimag-slider-thumbs merimag-slider-thumbs-text merimag-slider-thumbs-right only-thumb">
					<div data-id="<?php echo esc_attr($slider_id)?>"  id="<?php echo esc_attr($slider_id)?>-thumbs" data-number="<?php echo esc_attr(count($elements))?>" class="merimag-slider-thumbs-content merimag-slick-block merimag-content-width">
						<?php for( $i = 0; $i <= ( count($elements) - 1 ); $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div data-id="' . esc_attr($slider_id) . '" data-index="' . esc_attr( $i ) . '" class="merimag-slider-thumb-item">';

								$element 		   = $elements[$i];	
								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['force_size']			 = true;
								$block_args['size']                	 = 'small';
								$block_args['height']		  	     = 50;
								$block_args['show_description']    	 = false;
								$block_args['show_read_more']    	 = false;
								$block_args['show_review']    	 	 = false;
								$block_args['show_category'] 		 = false;
								$block_args['before_title']       	 = '';	
								$block_args['after_title']        	 = '';	
								$block_args['top_left']     	     = '';
								$block_args['infos_style']        	 = 'text';
								$block_args['infos_width'] 			 = 100;
								$block_args['block_white_text']		 = true;
								$block_args['title_ellipsis']		 = 2;
								$block_args['center_center']		 = '';
								$block_args['infos_content_width']	 = false;
								$block_args['title_length']		 	 = 38;
								$block_args['beside_title']			 = merimag_html_helper('thumb', $element->ID );
								$block_args['show_title']			 = false;	
								$block_args['centered_infos']		 = false;

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

						endfor ;?>
					</div>
				</div>
			</div>
		</div>
	</div>

  	<?php
}
/**
 * Mix 1 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_1( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class=" merimag-grid merimag-grid-50">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args 			   = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']              	 = 'normal';
					$block_args['height']		  	   	 = 200;
					$block_args['show_description']  	 = true;
					$block_args['hover_description']	 = false;
					$block_args['title_length']     	 = 65;
					$block_args['marged_infos']     	 = false;
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['after_title']      	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_read_more']     	 = true;
					$block_args['title_ellipsis']	  	 = 2;
					$block_args['description_ellipsis']	  	 = 3;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-side-infos merimag-normal-spacing">
					<?php for( $i = 1; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';
								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['separator']     	  	 = true;
								
								$block_args['size']            	 	 = 'small';
								$block_args['height']		  	 	 = 75;
								$block_args['show_description'] 	 = false;
								$block_args['after_title']  	  	 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = false;
								$block_args['show_format_icon']      = true;
								$block_args['infos_style']      	 = 'left';
								$block_args['title_ellipsis']	  	 = 2;
					$block_args['description_ellipsis']	  	 = 2;

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 2 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_2( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class=" merimag-grid merimag-grid-50">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'normal';
					$block_args['height']		  	  	 = 240;
					$block_args['show_description'] 	 = true;
					$block_args['hover_description']	 = false;
					$block_args['show_read_more']	  	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['marged_infos']     	 = true;
					$block_args['after_title']  		 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['show_read_more']     	 = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-side-infos merimag-normal-spacing">
					<?php for( $i = 1; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']             	 = 'small';
								$block_args['height']		  	  	 = 80;
								$block_args['show_description'] 	 = false;
								$block_args['after_title']  	  	 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = false;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;	
								$block_args['infos_style'] 	  		 = 'left';
								$block_args['separator']     	  	 = true;

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}

/**
 * Mix 3 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_3( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class=" merimag-grid merimag-grid-50">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'big';
					$block_args['height']		  	  	 = 480;
					$block_args['show_description'] 	 = false;
					$block_args['show_category'] 		 = true;
					$block_args['hover_description']	 = false;
					$block_args['title_length']     	 = 65;
					$block_args['after_title']  		 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['infos_style'] 			 = 'absolute';
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid">
					<?php for( $i = 1; $i <= 5; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']             	 = 'small';
								$block_args['height']		  	  	 = 80;
								$block_args['show_description'] 	 = false;
								$block_args['after_title'] 			 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
								$block_args['top_left']    			 = '';
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
								$block_args['infos_style']			 = 'left';
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = false;
								$block_args['show_format_icon']      = true;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}

/**
 * Mix 4 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_4( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-normal-spacing">
			<div class="merimag-column">
			<?php if( $first_element !== false ) : ?>
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'normal';
					$block_args['height']		  	  	 = 200;
					$block_args['show_description'] 	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['marged_infos']	  		 = false;
					$block_args['show_category']	  	 = true;
					$block_args['show_format_icon'] 	 = true;
					$block_args['show_read_more']     	 = true;
					$block_args['show_review'] 			 = true;
					$block_args['after_title']  	  	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['separator']		  	 = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-side-infos merimag-small-spacing">
				<?php for( $i = 1; $i <= 3; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 75;
							$block_args['show_description'] 	 = false;
							$block_args['top_left']  		  	 = '';
							$block_args['after_title']      	 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
							$block_args['show_category']	  	 = false;
							$block_args['show_format_icon'] 	 = true;
							$block_args['show_review'] 			 = false;
							$block_args['infos_style']      	 = 'left';
							$block_args['separator']		  	 = true;
							$block_args['title_ellipsis']	  	 = 2;
							

							merimag_blocks_block( $block_args );

							echo '</div>';
						}

				endfor ;?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 5 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_5( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-normal-spacing">
			<div class="merimag-column">
			<?php if( $first_element !== false ) : ?>
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'normal';
					$block_args['height']		  	  	 = 200;
					$block_args['show_description'] 	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['marged_infos']	  		 = true;
					$block_args['show_categroy']	  	 = true;
					$block_args['after_title']  	  	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['center_center'] 	  	 = '';
					$block_args['show_category']	  	 = true;
					$block_args['show_read_more']     	 = true;
					$block_args['show_format_icon'] 	 = true;
					$block_args['show_review'] 			 = true;
					$block_args['separator']		  	 = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-side-infos merimag-small-spacing">
				<?php for( $i = 1; $i <= 4; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 75;
							$block_args['show_description'] 	 = false;
							$block_args['after_title']      	 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'left';
							$block_args['separator']		  	 = true;
							$block_args['title_ellipsis']	  	 = 2;
							$block_args['show_category']	  	 = false;
							$block_args['show_format_icon'] 	 = true;
							$block_args['show_review'] 			 = false;
							
							merimag_blocks_block( $block_args );

							echo '</div>';
						}

				endfor ;?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
  	<?php
}

/**
 * Mix 5 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_6( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-medium-spacing">
			<div class="merimag-column">
			<?php if( $first_element !== false ) : ?>
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'medium';
					$block_args['height']		  	  	 = 340;
					$block_args['show_description'] 	 = false;
					$block_args['title_length']     	 = 65;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					$block_args['show_category']	  	 = true;
					$block_args['show_format_icon'] 	 = true;
					$block_args['show_review'] 			 = true;
					$block_args['after_title']  	  	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['infos_style']      	 = 'absolute';

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-side-infos merimag-small-spacing">
				<?php for( $i = 1; $i <= 4; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']	  	  		 = 75;
							$block_args['show_description'] 	 = false;
							$block_args['after_title']  	  	 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
							$block_args['top_left']     	  	 = '';
							$block_args['show_format_icon'] 	 = true;
							$block_args['separator']		  	 = true;
							$block_args['title_ellipsis']	  	 = 2;
							$block_args['show_category']	  	 = false;
							$block_args['show_format_icon'] 	 = true;
							$block_args['show_review'] 			 = false;
							$block_args['infos_style'] 	  		 = 'left';

							merimag_blocks_block( $block_args );

							echo '</div>';
						}

				endfor ;?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 7 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_7( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-50">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']            		 = 'medium';
					$block_args['height']		  	 	 = 280;
					$block_args['show_description']		 = true;
					$block_args['marged_infos']    		 = false;
					$block_args['show_read_more']  		 = true;
					$block_args['show_format_icon']  	 = true;
					$block_args['show_review']  		 = true;
					$block_args['title_ellipsis'] 		 = 2;
					$block_args['description_ellipsis']  = 3;

					$block_args['before_title']	 		 = merimag_meta_info('category', $first_element->ID );
					$block_args['after_title'] 			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50">
					<?php for( $i = 1; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column ">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 140;
								$block_args['show_description']		 = false;
								$block_args['marged_infos']    		 = false;
								$block_args['show_format_icon']  	 = true;
								$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 3;
								$block_args['show_review']  		 = true;
								$block_args['before_title']	 		 = merimag_meta_info('category', $first_element->ID );
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['top_left']    			 = '';

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 8 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_8( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-50">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']            		 = 'medium';
					$block_args['height']		  	 	 = 220;
					$block_args['show_description']		 = true;
					$block_args['marged_infos']    		 = true;
					$block_args['show_read_more']  		 = true;
					$block_args['show_category']  		 = true;
					$block_args['show_format_icon']  	 = true;
					$block_args['show_review']  		 = true;
					$block_args['title_ellipsis'] 		 = 2;
					$block_args['description_ellipsis']  = 3;
					$block_args['before_title']	 		 = merimag_meta_info('category', $first_element->ID );
					$block_args['after_title']     		 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50">
					<?php for( $i = 1; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column small-marged-infos">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 140;
								$block_args['show_description']		 = false;
								$block_args['marged_infos']    		 = true;
								$block_args['show_category']  		 = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
								$block_args['after_title']     		 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_format_icon']  	 = true;
								$block_args['show_review']  		 = true;

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 9 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_9( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-66-33">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'medium';
					$block_args['height']		  	  	 = 300;
					$block_args['show_description'] 	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['show_read_more']   	 = true;
					$block_args['after_title'] 			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid">
				<?php for( $i = 1; $i <= 2; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 160;
							$block_args['show_description'] 	 = false;
							$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['show_category']     	 = false;
							$block_args['show_review']     		 = true;
							$block_args['show_format_icon']      = true;
							$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}

				endfor ;?>
			</div>
		</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 10 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_10( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-66-33">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'medium';
					$block_args['height']		  	  	 = 300;
					$block_args['show_description'] 	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['marged_infos']	  		 = true;
					$block_args['title_ellipsis']      = 2;
					$block_args['description_ellipsis'] = 2;
					$block_args['center_center'] 	  	 = '';
					$block_args['show_read_more']   	 = true;
					$block_args['after_title'] 			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid">
				<?php for( $i = 1; $i <= 2; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-column small-marged-infos">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i; 
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 160;
							$block_args['show_description'] 	 = false;
							$block_args['marged_infos']	  		 = true;
							$block_args['after_title']      	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
							$block_args['show_category']     	 = false;
							$block_args['show_review']     		 = true;
							$block_args['show_format_icon']      = true;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}

				endfor ;?>
			</div>
		</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 11 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_11( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$grid_layout = isset( $full ) && $full === 'yes' ? '50' : '33-66';
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-<?php echo esc_attr($grid_layout)?>">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column merimag-column-100-tablet">
				<?php
					$args = merimag_get_block_args( $first_element->ID, $block_data  );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']              	 = 'medium';
					$block_args['height']		  	   	 = 240;
					$block_args['show_description']  	 = true;
					$block_args['marged_infos']      	 = false;
					$block_args['description_lenght']	 = 220;
					$block_args['show_format_icon']  	 = true;
					$block_args['show_review']  		 = true;
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 2;
					$block_args['show_category']  		 = true;
					$block_args['after_title']       	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_read_more']    	 = true;

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-100-tablet">
				<div class="merimag-grid merimag-grid-33">
					<?php for( $i = 1; $i <= 6; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {


								echo '<div class="merimag-column">';
								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 160;
								$block_args['show_description']		 = false;
								$block_args['show_format_icon']  	 = true;
								$block_args['title_ellipsis']		 = 2;
								$block_args['description_ellipsis']	 = 2;
								$block_args['show_review']  		 = true;
								$block_args['show_category']  		 = false;
								$block_args['after_title']  	 	 = merimag_meta_info('date', $elements[$i]->ID );
							
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}

/**
 * Mix 12 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_12( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	$grid_layout = isset( $full ) && $full === 'yes' ? '50' : '33-66';
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-<?php echo esc_attr($grid_layout)?>">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column merimag-column-100-tablet">
				<?php
					$element            = $first_element;
					$args = merimag_get_block_args( $element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']              	 = 'medium';
					$block_args['height']		  	   	 = 240;
					$block_args['show_description']  	 = true;
					$block_args['marged_infos']			 = true;
					$block_args['description_lenght']	 = 260;
					$block_args['show_read_more']		 = true;
					$block_args['after_title']       	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_format_icon']  	 = true;
					$block_args['show_review']  		 = true;
					$block_args['show_category']  		 = true;
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-100-tablet">
				<div class="merimag-grid merimag-grid-33">
					<?php for( $i = 1; $i <= 6; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {


								echo '<div class="merimag-column">';
								$element 		  = $elements[$i];
								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 160;
								$block_args['show_description']		 = false;
								$block_args['after_title']  	 	 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_format_icon']  	 = true;
								$block_args['show_review']  		 = true;
								$block_args['show_category']  		 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['description_ellipsis']	 = 2;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 13 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_13( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']            		 = 'big';
					$block_args['height']		  	 	 = 220;
					$block_args['show_description']		 = true;
					$block_args['description_lenght']	 = 300;
					$block_args['marged_infos'] 	 	 = true;
					$block_args['title_length']			 = 44;
					$block_args['after_title']  	 	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['separator']     	 	 = true;
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-33">
					<?php for( $i = 1; $i <= 3; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 120;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = true;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;	
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 14 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_14( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']					 = 'big';
					$block_args['height']				 = 220;
					$block_args['show_description']		 = true;
					$block_args['description_lenght']	 = 300;
					$block_args['marged_infos']			 = false;
					$block_args['title_length']			 = 44;
					$block_args['after_title']			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['separator']			 = true;
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-33">
					<?php for( $i = 1; $i <= 3; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 120;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = true;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 15 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_15( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-small-spacing merimag-grid">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']              	 = 'medium';
					$block_args['height']		  	   	 = 220;
					$block_args['show_description']  	 = true;
					$block_args['show_read_more']		 = true;
					$block_args['description_lenght']	 = 160;
					$block_args['after_title']			 = merimag_meta_info('date|views|comments', $first_element->ID );
					$block_args['absolute_image']		 = true;
					$block_args['infos_style']			 = 'left-classic';
					$block_args['border_block'] 		 = true;
					$block_args['separator']			 = false;
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis']      = 2;
					$block_args['description_ellipsis'] = 2;

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-small-spacing merimag-grid-50">
					<?php for( $i = 1; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']             	 = 'small';
								$block_args['height']		  	  	 = 75;
								$block_args['show_description'] 	 = false;
								$block_args['after_title']  	  	 = merimag_has_review( $elements[$i]->ID ) ? merimag_meta_info('review', $elements[$i]->ID ) : merimag_meta_info('date', $elements[$i]->ID );
								$block_args['infos_style'] 	  		 = 'left';
								$block_args['border_block'] 		 = true;
								$block_args['title_ellipsis']	  	 = 2;
								$block_args['separator']			 = false;
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = false;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['show_description']  	 = false;
								$block_args['description_ellipsis'] = 2;
								merimag_blocks_block( $block_args );

								echo '</div>';

							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 16 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_16( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50">
				<?php
					for( $i = 0; $i <= 1; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'medium';
								$block_args['height']		  	 	 = 220;
								$block_args['show_description']		 = false;
								$block_args['marged_infos'] 	 	 = false;
								$block_args['after_title'] 			 = merimag_meta_info('author_upper|dash|date|comments', $elements[$i]->ID );
								$block_args['show_category']     	 = true;
								$block_args['show_review']     		 = true;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;
				?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-25">
					<?php for( $i = 2; $i <= 5; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 120;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = true;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis']      = 2;
								$block_args['description_ellipsis'] = 2;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * Mix 17 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_17( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-small-spacing">
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50">
				<?php
					for( $i = 0; $i <= 1; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']              	 = 'normal';
								$block_args['height']		  	   	 = 220;
								$block_args['show_description']  	 = false;
								$block_args['description_lenght']	 = 220;
								$block_args['marged_infos']      	 = true;
								$block_args['show_read_more']    	 = false;
								$block_args['after_title']       	 = merimag_meta_info('date|comments', $elements[$i]->ID );
								$block_args['show_category']     	 = true;
								$block_args['show_review']     		 = true;
								$block_args['show_format_icon']      = true;
									$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;
				?>
				</div>
			</div>

			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-grid-25">
					<?php for( $i = 2; $i <= 5; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 120;
								$block_args['show_description']		 = false;
								$block_args['after_title']     		 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_category']     	 = false;
								$block_args['show_review']     		 = true;
								$block_args['show_format_icon']      = true;
								$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * Mix 18 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_18( $elements, $atts = array() ) {
	$first_element = isset( $elements[2] ) ? $elements[2] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column merimag-column-25 merimag-column-100-mobile">
				<div class="merimag-grid merimag-small-spacing">
					<?php for( $i = 0; $i <= 1; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column merimag-column-100-mobile">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 150;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_format_icon']  	 = true;
								$block_args['show_review']  		 = true;
								$block_args['show_category']  		 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['description_ellipsis']	 = 3;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
			<div class="merimag-column merimag-column-50 merimag-column-100-mobile">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 2;
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']            		 = 'normal';
					$block_args['height']		  	 	 = 260;
					$block_args['show_description']		 = true;
					$block_args['marged_infos']    		 = false;
					$block_args['show_read_more']  		 = true;
					$block_args['show_format_icon']  	 = true;
					$block_args['show_review']  		 = true;
					$block_args['show_category']  		 = true;
					$block_args['after_title'] 			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-25 merimag-column-100-mobile">
				<div class="merimag-grid merimag-small-spacing">
					<?php for( $i = 3; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 150;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_format_icon']  	 = true;
								$block_args['show_review']  		 = true;
								$block_args['show_category']  		 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['description_ellipsis']	 = 3;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 19 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_19( $elements, $atts = array() ) {
	$first_element = isset( $elements[2] ) ? $elements[2] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column merimag-column-25 merimag-column-100-mobile">
				<div class="merimag-grid merimag-small-spacing">
					<?php for( $i = 0; $i <= 1; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column merimag-column-100-mobile">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 176;
								$block_args['marged_infos']     	 = true;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_format_icon']  	 = true;
								$block_args['show_review']  		 = true;
								$block_args['show_category']  		 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['description_ellipsis']	 = 3;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
			<div class="merimag-column merimag-column-50 merimag-column-100-mobile">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 2;
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']            		 = 'big';
					$block_args['height']		  	 	 = 240;
					$block_args['show_description']		 = true;
					$block_args['marged_infos']     	 = true;
					$block_args['show_read_more']  		 = true;
					$block_args['show_format_icon']  	 = true;
					$block_args['show_review']  		 = true;
					$block_args['show_category']  		 = true;
					$block_args['after_title'] 			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-25 merimag-column-100-mobile">
				<div class="merimag-grid merimag-small-spacing">
					<?php for( $i = 3; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['marged_infos']     	 = true;
								$block_args['height']		  	 	 = 176;
								$block_args['show_description']		 = false;
								$block_args['after_title'] 			 = merimag_meta_info('date', $elements[$i]->ID );
								$block_args['show_format_icon']  	 = true;
								$block_args['show_review']  		 = true;
								$block_args['show_category']  		 = false;
								$block_args['title_ellipsis']		 = 2;
								$block_args['description_ellipsis']	 = 3;
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 20 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_20( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content ">
		<div class="merimag-grid merimag-medium-spacing merimag-grid-66-33">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$element          = $first_element;
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
				
					$block_args['size']              	 = 'big';
					$block_args['height']		  	   	 = 265;
					$block_args['show_description']  	 = true;
					$block_args['hover_description']	 = false;
					$block_args['show_read_more']		 = true;
					$block_args['title_length']      	 = 65;
					$block_args['marged_infos']      	 = true;
					$block_args['after_title']			 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-small-spacing">
					<?php for( $i = 1; $i <= 5; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']             	 = 'small';
								$block_args['height']		  	  	 = 50;
								$block_args['show_description'] 	 = false;
								$block_args['border_infos']	  		 = true;
								$block_args['small_padding']    	 = true;
								$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
								$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );	
								$block_args['infos_style'] 	  		 = 'text';
								
								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Mix 21 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_21( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
			<div class="merimag-column">
				<div class="merimag-grid">
				<?php
					if( $first_element ) {

						echo '<div class="merimag-column">';
						$args = merimag_get_block_args( $first_element, $block_data );
						extract( $args );
						$block_args 			  			 = $args;
						$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 		 = 0; 
						$block_args['element']	  	 		 = $first_element;
						$block_args['size']              	 = 'normal';
						$block_args['height']		  	   	 = 220;
						$block_args['show_description']  	 = true;
						$block_args['description_lenght']	 = 220;
						$block_args['marged_infos']      	 = false;
						$block_args['show_read_more']    	 = false;
						$block_args['show_category']	  	 = true;
						$block_args['show_format_icon'] 	 = true;
						$block_args['title_ellipsis'] 		 = 2;
						$block_args['description_ellipsis']  = 2;
						$block_args['show_review'] 			 = true;
						$block_args['after_title']       	 = merimag_meta_info('date|comments', $first_element->ID );
						$block_args['separator']			 = true;


						merimag_blocks_block( $block_args );

						echo '</div>';
					}

				?>
				</div>
			</div>

			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height by-row merimag-normal-spacing merimag-grid-50">
					<?php for( $i = 1; $i <= 4; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'small';
								$block_args['height']		  	 	 = 120;
								$block_args['show_description']		 = false;
								$block_args['show_category']	  	 = false;
								$block_args['show_format_icon'] 	 = true;
								$block_args['show_review'] 			 = true;
								$block_args['separator'] 		 	 = true;
								$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
								$block_args['after_title']     		 = merimag_meta_info('date', $elements[$i]->ID );

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * Mix 22 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_22( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid">
			<div class="merimag-column">
				<div class="merimag-grid">
				<?php
					if( $first_element ) {

						echo '<div class="merimag-column">';
						$args = merimag_get_block_args( $first_element, $block_data );
						extract( $args );
						$block_args 			  			 = $args;
						$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
						$block_args['i'] 		  	 		 = 0; 
						$block_args['element']	  	 		 = $first_element;
						$block_args['size']              	 = 'normal';
						$block_args['height']		  	   	 = 220;
						$block_args['show_description']  	 = true;
						$block_args['description_lenght']	 = 220;
						$block_args['marged_infos']      	 = false;
						$block_args['show_read_more']    	 = false;
						$block_args['show_category']	  	 = true;
						$block_args['show_format_icon'] 	 = true;
						$block_args['show_review'] 			 = true;
						$block_args['title_ellipsis'] 		 = 2;
						$block_args['description_ellipsis']  = 2;
						$block_args['after_title']       	 = merimag_meta_info('date|comments', $first_element->ID );
						$block_args['separator']			 = true;


						merimag_blocks_block( $block_args );

						echo '</div>';
					}

				?>
				</div>
			</div>

			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height by-row merimag-normal-spacing merimag-grid-33">
					<?php for( $i = 1; $i <= 6; $i++ ) :

							if( isset( $elements[$i]->ID ) ) {

								echo '<div class="merimag-column">';

								$args = merimag_get_block_args( $elements[$i], $block_data );
								extract( $args );
								$block_args 			  			 = $args;
								$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
								$block_args['i'] 		  	 		 = $i; 
								$block_args['element']	  	 		 = $elements[$i];
								$block_args['size']            		 = 'tiny';
								$block_args['height']		  	 	 = 80;
								$block_args['show_description']		 = false;
								$block_args['show_category']	  	 = false;
								$block_args['show_format_icon'] 	 = true;
								$block_args['show_review'] 			 = true;
								$block_args['separator'] 		 	 = true;
								$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
								$block_args['after_title']     		 = merimag_meta_info('date', $elements[$i]->ID );

								merimag_blocks_block( $block_args );

								echo '</div>';
							}

					endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * Mix 23 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_mix_23( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-normal-spacing">
			<div class="merimag-column">
			<?php if( $first_element !== false ) : ?>
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'normal';
					$block_args['height']		  	  	 = 200;
					$block_args['show_description'] 	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['marged_infos']	  		 = false;
					$block_args['show_category']	  	 = true;
					$block_args['show_format_icon'] 	 = true;
					$block_args['show_review'] 			 = true;
					$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
					$block_args['after_title']  	  	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['separator']		  	 = true;

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-equal-height merimag-small-spacing">
				<?php for( $i = 1; $i <= 4; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {

							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']            		 = 'normal';
							$block_args['height']		  	  	 = 75;
							$block_args['show_description'] 	 = false;
							$block_args['top_left']  		  	 = '';
							$block_args['after_title']			 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'text';
							$block_args['separator']		  	 = true;
							$block_args['show_category']	  	 = false;
							$block_args['show_format_icon'] 	 = true;
							$block_args['title_ellipsis'] 		 = 2;
								$block_args['description_ellipsis']  = 2;
							$block_args['show_review'] 			 = true;	

							merimag_blocks_block( $block_args );

							echo '</div>';
						}

				endfor ;?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Tiled 1 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_1( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content ">
		<div class="merimag-grid merimag-grid-66-33 merimag-small-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column merimag-column-100-tablet">
				<?php
					$args 			  					 = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']              	 = 'big';
					$block_args['height'] 		  		 = 450;
					$block_args['show_description']		 = true;
					$block_args['hover_description']	 = true;
					$block_args['title_length']     	 = 165;
					$block_args['hover_description']	 = true;
					$block_args['show_description'] 	 = true;
					$block_args['after_title']      	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );	
					$block_args['infos_style']			 = 'absolute';
					$block_args['show_category']     	 = true;
					$block_args['show_review']     		 = true;
					$block_args['show_format_icon']      = true;
					$block_args['title_ellipsis']		 = 3;
					$block_args['description_ellipsis']	 = 3;

					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-100-tablet">
				<div class="merimag-grid merimag-grid-50-tablet merimag-small-spacing">
				<?php for( $i = 1; $i <= 2; $i++ ) :

						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height'] 		  		 = 220;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['infos_style']			 = 'absolute';
							$block_args['show_category']     	 = true;
							$block_args['show_review']     		 = true;
							$block_args['show_format_icon']      = true;
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}

				endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>

  	<?php
}

/**
 * Tiled 2 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_2( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-50 merimag-grid-100-tablet  merimag-tiny-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );

					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'big';
					$block_args['height'] 		  		 = 460;
					$block_args['show_description'] 	 = true;
					$block_args['hover_description']	 = true;
					$block_args['title_length']     	 = 165;
					$block_args['hover_description']	 = true;
					$block_args['show_description'] 	 = true;
					$block_args['before_title']	  		 = merimag_meta_info('category', $first_element->ID );
					$block_args['after_title']      	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );
					$block_args['center_center'] 	  	 = '';
					$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $first_element->ID );
					$block_args['infos_style']      	 = 'absolute';
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50 merimag-grid-50-tablet merimag-grid-50-tablet  merimag-tiny-spacing">
				<?php for( $i = 1; $i <= 4; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 228;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']	  		 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']      	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}

/**
 * Tiled 3 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_3( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content ">
		<div class="merimag-grid merimag-grid-50 merimag-tiny-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0; 
					$block_args['element']	  	 		 = $first_element;
					
					$block_args['size']            		 = 'big';
					$block_args['height'] 		  		 = 460;
					$block_args['show_description'] 	 = true;
					$block_args['hover_description']	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['before_title']	  		 = merimag_meta_info('category', $first_element->ID );
					$block_args['after_title']      	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );	
					$block_args['center_center']	  	 = '';
					$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $first_element->ID );
					$block_args['infos_style']			 = 'absolute';
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50 merimag-grid-50-tablet merimag-grid-50-tablet merimag-tiny-spacing">
				<?php for( $i = 1; $i <= 3; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							$column_width = $i === 1 ? 'merimag-full-column' : '';
							echo '<div class="merimag-column ' . esc_attr( $column_width ) . '">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height'] 		  		 = 228;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']     	 	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']			 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>

  	<?php
}

/**
 * Tiled 4 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_4( $elements, $atts = array() ) {
	$first_element = isset( $elements[2] ) ? $elements[2] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content ">
		<div class="merimag-grid merimag-tiny-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column  merimag-column-30 merimag-column-100-tablet">
				<div class="merimag-grid merimag-grid-50-tablet merimag-tiny-spacing ">
				<?php for( $i = 0; $i <= 1; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 228;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']     		 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']			 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						
				endfor ;?>
				</div>
			</div>
			<div class="merimag-column merimag-column-40 merimag-column-100-tablet">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 2; 
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'big';
					$block_args['height'] 		  		 = 460;
					$block_args['show_description'] 	 = true;
					$block_args['hover_description']	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['before_title']     	 = merimag_meta_info('category', $first_element->ID );
					$block_args['after_title']      	 = merimag_meta_info('author_upper|dash|date|comments', $first_element->ID );	
					$block_args['center_center'] 	  	 = '';
					$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $first_element->ID );
					$block_args['infos_style']			 = 'absolute';
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-30 merimag-column-100-tablet">
				<div class="merimag-grid merimag-grid-50-tablet merimag-tiny-spacing">
				<?php for( $i = 3; $i <= 4; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 228;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']     		 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							$block_args['infos_style']			 = 'absolute';

							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>

  	<?php
}
/**
 * Tiled 5 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_5( $elements, $atts = array() ) {
	$first_element = isset( $elements[0] ) ? $elements[0] : false;
	$second_element = isset( $elements[1] ) ? $elements[1] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content ">
		<div class="merimag-grid merimag-tiny-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column merimag-column-30 merimag-column-100-tablet">
				<?php
					$args = merimag_get_block_args( $first_element, $block_data );
					extract( $args );

					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 0;
					$block_args['element']	  	 		 = $first_element;
					$block_args['size']             	 = 'medium';
					$block_args['height'] 		  		 = 460;
					$block_args['show_description'] 	 = true;
					$block_args['hover_description']	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['before_title']     	 = merimag_meta_info('category', $first_element->ID );
					$block_args['after_title']      	 = merimag_meta_info('date|comments', $first_element->ID );
					$block_args['center_center'] 	  	 = '';
					$block_args['top_right']		  	 = merimag_meta_info('format_icon', $first_element->ID );
					$block_args['infos_style']			 = 'absolute';
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
			<div class="merimag-column merimag-column-40 merimag-column-100-tablet">
				<div class="merimag-grid merimag-grid-50 merimag-grid-50-tablet merimag-tiny-spacing">
				<?php for( $i = 2; $i <= 4; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							$column_width = $i === 2 ? 'merimag-full-column' : '';
							echo '<div class="merimag-column ' . esc_attr( $column_width ) . '">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 228;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']	  		 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']      	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']			 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column merimag-column-30 merimag-column-100-tablet">
				<?php
					$args = merimag_get_block_args( $second_element, $block_data );
					extract( $args );
					$block_args 			  			 = $args;
					$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
					$block_args['i'] 		  	 		 = 1;
					$block_args['element']	  	 		 = $second_element;
					$block_args['size']             	 = 'medium';
					$block_args['height'] 		  		 = 460;
					$block_args['show_description'] 	 = true;
					$block_args['hover_description']	 = true;
					$block_args['title_length']     	 = 65;
					$block_args['before_title']	  		 = merimag_meta_info('category', $second_element->ID );
					$block_args['after_title']      	 = merimag_meta_info('date|comments', $second_element->ID );
					$block_args['center_center'] 	  	 = '';
					$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $second_element->ID );
					$block_args['infos_style']			 = 'absolute';
					$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 2;
					merimag_blocks_block( $block_args );
				?>
			</div>
		<?php endif; ?>
		</div>
	</div>

  	<?php
}
/**
 * Tiled 5 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_6( $elements, $atts = array() ) {
	$first_element = isset( $elements[2] ) ? $elements[2] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid  merimag-small-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-50 merimag-small-spacing">
				<?php for( $i = 0; $i <= 1; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'big';
							$block_args['height']				 = 320;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-grid-33 merimag-small-spacing">
				<?php for( $i = 2; $i <= 4; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 260;
							$block_args['show_description'] 	 = true;
							$block_args['hover_description']	 = true;
							$block_args['before_title']  	  	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 2;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Tiled 7 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_7( $elements, $atts = array() ) {
	$first_element = isset( $elements[2] ) ? $elements[2] : false;
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-33  merimag-tiny-spacing">
		<?php if( $first_element !== false ) : ?>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 0; $i <= 1; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';

							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i !== 1 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i !== 1 ? 364 : 180;
							
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 2; $i <= 3; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i !== 2 ? 'medium' : 'small';
							$block_args['height']				 = $i !== 2 ? 364 : 180;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 4; $i <= 6; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = 180;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
  	<?php
}
/**
 * Tiled 8 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_8( $elements, $atts = array() ) {
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-33  merimag-tiny-spacing">
			
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 0; $i <= 1; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i === 0 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i === 1 ? 180 : 296;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 2; $i <= 3; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i === 3 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i === 2 ? 180 : 296;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 4; $i <= 5; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i === 4 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i === 5 ? 180 : 296;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * Tiled 9 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_9( $elements, $atts = array() ) {
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-33  merimag-tiny-spacing">
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php
						
						if( isset( $elements[0]->ID ) ) {
							echo '<div class="merimag-column">';
							$i = 0;
							$args = merimag_get_block_args( $elements[0], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[0];
							$block_args['size']             	 = 'medium';
							$block_args['height']				 = 480;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[0]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[0]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
							$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );

							echo '</div>';
						}
						

				?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 1; $i <= 2; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i === 1 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i === 1 ? 296 : 180;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 3; $i <= 4; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i === 4 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i === 4 ? 296 : 180;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
/**
 * Tiled 10 style
 *
 * @param array $atts list of elements
 * @param array $atts list of params
 * @return void
 */
function merimag_blocks_tiled_10( $elements, $atts = array() ) {
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div class="merimag-block-content">
		<div class="merimag-grid merimag-grid-33  merimag-tiny-spacing">
			
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 2; $i <= 3; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = $i === 2 ? 'medium' : 'small';
							$block_args['height']		  	  	 = $i === 2 ? 364 : 180;
							$block_args['show_description'] 	 = false;
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 0; $i <= 1; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'medium';
							$block_args['height']		  	  	 = 272;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
			<div class="merimag-column">
				<div class="merimag-grid merimag-tiny-spacing">
				<?php for( $i = 4; $i <= 6; $i++ ) :
						
						if( isset( $elements[$i]->ID ) ) {
							echo '<div class="merimag-column">';
							$args = merimag_get_block_args( $elements[$i], $block_data );
							extract( $args );
							$block_args 			  			 = $args;
							$block_args['post_type']  			 = isset( $atts['post_type'] ) ? $atts['post_type'] : 'post';
							$block_args['i'] 		  	 		 = $i;
							$block_args['element']	  	 		 = $elements[$i];
							$block_args['size']             	 = 'small';
							$block_args['height']		  	  	 = $i === 3 ? 296 : 180;
							$block_args['show_description'] 	 = false;
							$block_args['before_title']     	 = merimag_meta_info('category', $elements[$i]->ID );
							$block_args['after_title']  	  	 = merimag_meta_info('date', $elements[$i]->ID );
							$block_args['center_center'] 	  	 = '';
							$block_args['top_right']	   	  	 = merimag_meta_info('format_icon', $elements[$i]->ID );
							$block_args['infos_style']      	 = 'absolute';
							$block_args['title_ellipsis']		 = 2;
					$block_args['description_ellipsis']	 = 3;
							merimag_blocks_block( $block_args );
							echo '</div>';
						}
						

				endfor ;?>
				</div>
			</div>
		</div>
	</div>
  	<?php
}
function merimag_blocks_ticker( $elements, $atts = array() ) {
	$block_data = isset( $atts['block_data'] ) ? $atts['block_data'] : array();
	?>
	<div id="<?php echo esc_attr(merimag_uniqid('ticker-'))?>" class="merimag-news-ticker">
		<?php
		foreach( (array) $elements as $element ) {
		?>
		<a class="merimag-ticker-item" href="<?php echo esc_url( $element->link )?>"><span class="merimag-ticker-date"><?php echo merimag_meta_info('date_icon_no_link', $element->ID ) ?></span><?php echo esc_attr( $element->title ); ?></a>

		<?php
		}
		?>
	</div>
	<?php
}