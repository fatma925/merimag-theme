<?php

/**
 * Get shortcode title
 *
 * @param string $shortcode the name of the shortcode
 * @return string shortcode title
 */
 function merimag_get_shortcode_title( $shortcode) {
 	$shortcodes = merimag_get_shortcodes_list();
 	$shortcode = str_replace('_', '-', $shortcode);
 	return isset( $shortcodes[$shortcode]['title'] ) ? $shortcodes[$shortcode]['title'] : false;
 }
 /**
 * Get shortcode title
 *
 * @param string $shortcode the name of the shortcode
 * @return string shortcode title
 */
 function merimag_get_shortcode_icon( $shortcode ) {
 	$shortcodes = merimag_get_shortcodes_list();
 	$shortcode = str_replace('_', '-', $shortcode);
 	return isset( $shortcodes[$shortcode]['icon'] ) ? $shortcodes[$shortcode]['icon'] : false;
 }
/**
 * Display shortcode
 *
 * @param string $shortcode the name of the shortcode to display
 * @param array $atts the list of arguments that will be passed to the shortcode fill
 * @return void
 */
function merimag_get_shortcode_html( $shortcode, $atts = array(), $echo_style = false ) {
	if( !is_string($shortcode) ) {
		echo sprintf( 'Invalid shortcode', $shortcode );
	}
	$shortcode = str_replace('-', '_', $shortcode );
	$shortcode_function = 'merimag_shortcode_' . $shortcode;
	$id = get_the_ID();
	if( function_exists($shortcode_function) ) {
		if( ( defined('DOING_AJAX') && DOING_AJAX ) || is_admin() || is_preview() || $echo_style === true ) {
			merimag_get_shortcode_css_parse( str_replace('_', '-', $shortcode), $atts, true );
		}
		$shortcode_function( $atts );
	} else {
		echo sprintf( 'shortcode %s does not exist', $shortcode );
	} 
}
/**
 * Do shortcodes with scripts inside
 *
 * @param string $cache html
 * @return string filtered html
 */
function merimag_filter_cache_shortcodes( $cache ) {
	$cache = str_replace('-shortcode-start-', '[', $cache);
	$cache = str_replace('-shortcode-end-', ']', $cache);
	return do_shortcode( wp_specialchars_decode(esc_attr($cache), ENT_QUOTES) );
}
/**
 * Enqueue shortcode assets
 *
 * @param string $shortcode the name of the shortcode to display
 * @param array $atts the list of arguments that will be passed to the shortcode fill
 * @return void
 */
function merimag_get_shortcode_assets( $shortcode ) {
	if( strpos($shortcode, 'slider')!== false || strpos($shortcode, 'carousel') !== false ) {
		wp_enqueue_style('merimag-core-slick-css');
		wp_enqueue_script('merimag-core-slick-js');
	}
	if( strpos($shortcode, 'gallery')!== false ) {
		wp_enqueue_style('merimag-core-unite-gallery-css');
		wp_enqueue_script('merimag-core-unite-gallery-js');
	}
	if( strpos($shortcode, 'video')!== false ) {
		wp_enqueue_style('merimag-core-plyr-css');
		wp_enqueue_script('merimag-core-plyr-js');
	}
}
/**
 * Get shortcode html
 *
 * @param string $shortcode the name of the shortcode to display
 * @param array $atts the list of arguments that will be passed to the shortcode fill
 * @return string shortcode html 
 */
function merimag_shortcode_html( $shortcode, $atts = array(), $echo_style = false ) {
    ob_start();
    merimag_get_shortcode_html( $shortcode, $atts, $echo_style );
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
/**
 * About
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_search( $atts = array() ) {
	$post_type 	  = isset( $atts['post_type'] ) && post_type_exists( $atts['post_type'] ) ? $atts['post_type'] : 'all';
	$post_type_label = post_type_exists($post_type) ? get_post_type_object($post_type) : '';
	$post_type_label = isset( $post_type_label->label ) ? $post_type_label->label : '';
	?>
	<div class="merimag-shortcode-container merimag-search-shortcode">
		<form action="<?php echo esc_url( home_url( '/' ) ) ?>" method="get" class="merimag-search-form">
	        <input type="text" name="s" id="s" class="merimag-search-field" placeholder="<?php echo esc_html__('Search for', 'merimag') . ' ' . esc_attr( $post_type_label ) . '..' ?>" value="<?php echo esc_attr( get_search_query() ) ?>" required>
	        <button type="submit" id="merimag-search-submit" class="merimag-search-submit button"></button>
	        <?php if( post_type_exists($post_type) ) : ?>
	        	<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ) ?>">
	        <?php endif; ?>
	    </form>
	</div>
	<?php
}
/**
 * Features
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_features( $atts = array() ) {
	$type = isset( $atts['type'] ) && $atts['type'] ? $atts['type'] : '';
	switch ($type) {
		case 'post_templates':
			$templates = apply_filters('recognized_post_templates');
			break;
		default:
			# code...
			break;
	}
}
/**
 * About
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_about( $atts = array() ) {
	$atts['icons_columns']   = 'flex';
	$atts['icons_layout']  	 = 'only_icon';
	$layout 	  = isset( $atts['business_layout'] ) && in_array( $atts['business_layout'], array('simple', 'flex') ) ? $atts['business_layout'] : 'simple';
	$logo_url 	  = isset( $atts['business_logo']['url'] ) && $atts['business_logo']['url'] ? $atts['business_logo']['url'] : false;
	$url 	  	  = isset( $atts['business_url']['attr'] ) && $atts['business_url']['attr'] ? $atts['business_url']['attr'] : home_url();
	$about 	  	  = isset( $atts['business_about'] ) && is_string( $atts['business_about'] ) ? $atts['business_about'] : get_bloginfo('description');
	$name 	  	  = isset( $atts['business_name'] ) && is_string( $atts['business_name'] ) ? $atts['business_name'] : get_bloginfo('name');
	$class 	  	  = isset( $atts['business_centered'] ) && $atts['business_centered'] === 'yes' ? 'merimag-about-business-centered' : '';
	$logo_height = isset( $atts['business_logo_height'] ) && is_numeric( $atts['business_logo_height'] ) && $atts['business_logo_height'] > 20 ? $atts['business_logo_height'] : false;
	$img_height   = $logo_height ? sprintf('max-height: %spx', $logo_height ) : '';
	?>
	<div class="merimag-shortcode-container merimag-about-business-shortcode merimag-about-business-shortcode-<?php echo esc_attr($layout )?> <?php echo esc_attr($class)?>">
		<?php if( $logo_url ) : ?>
		<div class="merimag-about-business-logo merimag-flex-item">
			<a href="<?php echo esc_url($url)?>"><img style="<?php echo esc_attr($img_height)?>" src="<?php echo esc_url($logo_url)?>" alt="<?php echo esc_attr($name)?>" title="<?php echo esc_attr($name)?>" /></a>
		</div>
		<?php endif; ?>
		<p class="merimag-about-business-description merimag-flex-item"><?php echo esc_attr($about)?></p>
		<div class="merimag-about-business-social merimag-flex-item">
			<?php merimag_get_shortcode_html('social-icons', $atts ); ?>
		</div>
	</div>
	<?php
}
/**
 * Contact infos
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_contact_infos( $atts = array() ) {

	$elements = isset( $atts['elements'] ) ? $atts['elements'] : merimag_get_db_customizer_option('contact_infos');
	$layout   = isset( $atts['layout'] ) && $atts['layout'] === 'inline' ? 'inline' : 'default';
	$font_size = isset( $atts['font_size'] ) && is_numeric( $atts['font_size'] ) ? $atts['font_size'] : false;
	$color = isset( $atts['color'] ) && merimag_validate_color( $atts['color'] ) ? $atts['color'] : '';
	$icons_color = isset( $atts['icons_color'] ) && merimag_validate_color( $atts['icons_color'] ) ? $atts['icons_color'] : '';
	$stacked_icons = isset( $atts['icons_style'] ) && $atts['icons_style'] === 'stacked' ? true : false;
	$icons_color = $stacked_icons === true && !$icons_color ? merimag_get_principal_color() : $icons_color;
	$icons_text_color = $icons_color ? merimag_get_text_color_from_background( $icons_color ) : '';
	$icons_style = $icons_color ? sprintf('color:%s', esc_attr($icons_color)) : '';
	$icons_style = $icons_color && $stacked_icons === true ? sprintf('background:%s; color:%s', esc_attr($icons_color), esc_attr($icons_text_color)) : '';
	$vertical_align = isset( $atts['vertical_align'] ) && $atts['vertical_align'] === 'start' ? 'vertical-align-start' : 'vertical-align-default';
	$style = $color ? sprintf('color:%s;', esc_attr($color)) : '';
	$icons_class = $stacked_icons === true ? 'merimag-stacked-icon' : '';
	$style .= $font_size ? sprintf('font-size:%spx;', esc_attr($font_size)) : '';
	if( !$elements ) {
		return;
	}
	echo sprintf( '<div class="merimag-contact-infos-shortcode merimag-contact-infos-shortcode-%s">', esc_attr( $layout ));
	foreach ((array)$elements as $element ) {
		$type  = isset( $element['type'] ) ? $element['type'] : false;
		$title = isset( $element['title'] ) && !empty( $element['title'] ) ? $element['title'] : false;
		$text  = isset( $element['text'] ) && !empty( $element['text'] ) ? $element['text'] : false;
		$link  = isset( $element['link'] ) && !empty( $element['link'] ) && is_string( $element['link'] ) ? $element['link'] : false;
		if( !in_array($type, merimag_get_recognized_contact_types(true))) {
			continue;
		}
		switch ($type) {
			case 'phone':
				$icon_class = 'icofont-ui-dial-phone';
				$link 		= $link  ? $link  : 'tel:' . esc_attr( $text );
				break;
			case 'mobile_phone':
				$icon_class = 'icofont-ui-touch-phone';
				$link 		= $link  ? $link  : 'tel:' . esc_attr( $text );
				break;
			case 'email_address':
				$icon_class = 'icofont-envelope';
				$link 		= $link  ? $link  : 'mailto:' . esc_attr( $text );
				break;
			case 'address';
				$icon_class = 'icofont-google-map';
				$link 		= $link  ? $link  : 'http://maps.google.com/?q=' . esc_attr( $text );
				break;
			case 'open_hours':
				$icon_class = 'icofont-wall-clock';
				break;
			default:
				$icon_class = 'icofont-contacts';
				break;
		}
		$target = $type === 'address' ? '_blank' : '';
		?>
		<a href="<?php echo esc_url( $link ) ?>" class="merimag-contact-item <?php echo esc_attr($vertical_align)?>" style="<?php echo esc_attr($style)?>">
			<div class="merimag-contact-item-icon <?php echo esc_attr( $icons_class) ?>" style="<?php echo esc_attr( $icons_style) ?>"><i class="<?php echo esc_attr( $icon_class ) ?>"></i></div>
			<div class="merimag-contact-item-infos">
				<?php if( $title ) : ?>
				<div class="merimag-contact-item-title"><?php echo esc_attr( $title )?></div>
				<?php endif; ?>
				<?php if( $text ) : ?>
				<div class="merimag-contact-item-text"><?php echo esc_attr( $text )?></div>
				<?php endif; ?>
			</div>
		</a>
		<?php
		
	}
	echo '</div>';
}
/**
 * Contact infos
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_wp_menu( $atts = array() ) {
	$menu = isset( $atts['menu'] ) ? $atts['menu'] : false;
	$columns = isset( $atts['columns'] ) && is_string( $atts['columns'] ) ? $atts['columns'] : '1';
	$menu_exists = wp_get_nav_menu_object( $menu );
	if( !$menu_exists ) {
		return;
	}
	$class = isset( $atts['layout'] ) && $atts['layout'] === 'vertical' ? 'vertical-menu' : '';
	$class = isset( $atts['layout'] ) && $atts['layout'] === 'horizontal' ? 'horizontal-menu' : $class;
	$class = isset( $atts['layout'] ) && $atts['layout'] === 'simple' ? 'simple-menu' : $class;
	switch ($columns) {
		case 'flex':
			$grid_class = ' merimag-simple-menu merimag-menu-grid-flex';
			break;
		case '1':
			$grid_class = ' merimag-simple-menu merimag-menu-grid-100';
			break;
		case '2': 
			$grid_class = ' merimag-simple-menu  merimag-menu-grid-50';
			break;
		case '3':
			$grid_class = ' merimag-simple-menu  merimag-menu-grid-33';
			break;
		case '4':
			$grid_class = ' merimag-simple-menu  merimag-menu-grid-25';
			break;
		case '5':
			$grid_class = ' merimag-simple-menu  merimag-menu-grid-20';
			break;
		default:
			$grid_class = '';
			break;
	}
	$class .= isset( $atts['layout'] ) && $atts['layout'] === 'simple' ? $grid_class : '';
	?>
	<div class="merimag-menu-container <?php echo esc_attr( $class ) ?>">
				<?php
				wp_nav_menu( array(
					'menu'        => $menu,
					'fallback_cb' 	 => '__return_false',
					'is_header' => false,
				) );
				?>
	</div>	
	<?php
 	
	
}
/**
 * Tabbed widget
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_tabbed_widget( $atts = array() ) {
	$id = merimag_uniqid('tabbed-widget-');
	$recent_title = isset( $atts['recent_title'] ) && !empty( $atts['recent_title'] ) && is_string($atts['recent_title'] ) ? $atts['recent_title'] : '';
	$popular_title = isset( $atts['popular_title'] ) && !empty( $atts['popular_title'] ) && is_string($atts['popular_title'] ) ? $atts['popular_title'] : '';
	$comments_title = isset( $atts['comments_title'] ) && !empty( $atts['comments_title'] ) && is_string($atts['comments_title'] ) ? $atts['comments_title'] : '';
	?>
	<div id="<?php echo esc_attr($id)?>" class="merimag-tabs-shortcode merimag-tabbed-widget">
		<ul class="merimag-tabs-shortcode-list">
			<li><a href="#recent-<?php echo esc_attr($id)?>"><i class="icofont-clock-time"></i><?php echo esc_attr($recent_title)?></a></li>
			<li><a href="#popular-<?php echo esc_attr($id)?>"><i class="icofont-fire-burn"></i><?php echo esc_attr($popular_title)?></a></li>
			<li><a href="#comments-<?php echo esc_attr($id)?>"><i class="icofont-speech-comments"></i><?php echo esc_attr($comments_title)?></a></li>
		</ul>
		<div class="merimag-tabs-shortcode-content" id="recent-<?php echo esc_attr($id)?>">
			<?php
				$recent_atts['order_by'] = 'date';
				$recent_atts['order'] = 'desc';
				$recent_atts['number'] = 5;
				$recent_atts['grid_style'] = 'left';
				$recent_atts['after_title'] = 'date';
				$recent_atts['image_height'] = 60;
				$recent_atts['separator'] = true;
				$recent_atts['title_ellipsis'] = 2;
				$recent_atts['spacing'] = 'small';
				$recent_atts['title_size'] = 'small';
				$recent_atts['ignore_general_style'] = 'yes';
				merimag_get_shortcode_html('posts-list', $recent_atts );
			?>
		</div>
		<div class="merimag-tabs-shortcode-content"  id="popular-<?php echo esc_attr($id)?>">
			<?php
				$recent_atts['order_by'] = 'comment_count';
				$recent_atts['order'] = 'desc';
				$recent_atts['number'] = 5;
				$recent_atts['grid_style'] = 'left';
				$recent_atts['after_title'] = 'comments_text';
				$recent_atts['image_height'] = 60;
				$recent_atts['separator'] = true;
				$recent_atts['title_ellipsis'] = 2;
				$recent_atts['spacing'] = 'small';
				$recent_atts['title_size'] = 'small';
				$recent_atts['ignore_general_style'] = 'yes';
				merimag_get_shortcode_html('posts-list', $recent_atts );
			?>
		</div>
		<div class="merimag-tabs-shortcode-content"  id="comments-<?php echo esc_attr($id)?>">
			<?php merimag_get_shortcode_html('comments'); ?>
		</div>
	</div>
	<?php
}
/*

*/
function merimag_shortcode_popular_categories( $atts = array() ) {

    $taxonomy = isset( $atts['taxonomy'] ) && taxonomy_exists( $atts['taxonomy'] ) ? $atts['taxonomy'] : 'category';

    $sub_cats = isset( $atts['sub_categories'] ) && $atts['sub_categories'] === 'yes' ? true : false;

    $terms    = get_terms( array( 'taxonomy' => $taxonomy, 'orderby' => 'count', 'order'=> 'DESC', 'parent' => $sub_cats ) );

    echo '<ul class="merimag-popular-categories-list">';

    foreach( $terms as $term ) {
        $link = get_term_link( $term->term_id );
        $icon_angle = is_rtl() ? 'left' : 'right';
        echo sprintf('<li class="general-border-color"><i class="fa fa-angle-%s"></i><a href="%s">%s</a><span class="merimag-term-count principal-color-background-color %s-%s">%s</span></li>', esc_attr( $icon_angle ), esc_url( $link ), $term->name, $taxonomy, $term->slug, $term->count  );

    }

    echo '</ul>';
}
/** 
 * Recent Comments
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_comments( $atts = array() ) {
	$number = isset( $atts['number'] ) && is_numeric( $atts['number'] ) ? $atts['number'] : 5;
	$comments = get_comments(
		array(
			'number'      => $number,
			'status'      => 'approve',
			'post_status' => 'publish',
			'post_type' => 'post',
		)
	);
	echo '<div class="merimag-recent-comments-list">';
	foreach( $comments as $comment ) {
		$comment_author = $comment->user_id ? get_the_author_meta('display_name',  $comment->user_id) : $comment->comment_author;
		$comment_avatar = $comment->user_id ? get_avatar_url( $comment->user_id) : get_avatar_url( $comment->comment_author_email);
		$comment_author_url = $comment->user_id ? get_the_author_meta( 'user_url', $comment->user_id ) : ( $comment->comment_author_url ? $comment->comment_author_url : '' );
		?>
		<div class="merimag-comment-item">
			<div class="merimag-comment-avatar">
				<?php if( $comment_author_url ) :?>
				<a title="<?php echo esc_attr( $comment_author )?>" href="<?php echo esc_url( $comment_author_url ) ?>">
				<?php endif; ?>
				<img title="<?php echo esc_attr( $comment_author )?>" alt="<?php echo esc_attr( $comment_author )?>" src="<?php echo esc_url( $comment_avatar )?>" />
				<?php if( $comment_author_url ) :?>
				</a>
				<?php endif; ?>
			</div>
			<div class="merimag-comment-content">
				<?php if( $comment_author_url ) :?>
				<a title="<?php echo esc_attr( $comment_author )?>" href="<?php echo esc_url( $comment_author_url ) ?>">
				<?php endif; ?>
				<b><?php echo esc_attr( $comment_author )?></b>
				<?php if( $comment_author_url ) :?>
				</a>
				<?php endif; ?>
				<a class="merimag-line-clamp merimag-line-clamp-2" href="<?php echo get_comment_link( $comment->comment_ID )?>"><?php echo esc_attr( wp_trim_words($comment->comment_content, 10, '...') ) ?></a>
			</div>
		</div>
		<?php
	}
	echo '</div>';
}
/**
 * Accordion
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_accordion( $atts = array() ) {
	$accordion 	 	= isset( $atts['tabs'] ) && is_array( $atts['tabs'] ) ? $atts['tabs'] : array();
	$accordion_data = isset( $atts['collapsible'] ) && $atts['collapsible'] === 'yes' ? 'data-collapsible="true"' : '';
	$accordion_id 	= isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-accordion-shortcode-');
	?>
	<div id="<?php echo esc_attr($accordion_id)?>" class="merimag-accordion-shortcode" <?php echo wp_specialchars_decode(esc_attr($accordion_data), ENT_QUOTES)?>>
		<?php foreach( $accordion as $key => $tab ) : ?>
			<?php $title = isset( $tab['title'] ) && is_string( $tab['title'] ) ? $tab['title'] : __('Tab ', 'merimag') . esc_attr( $key ); ?>
			<h4 class="merimag-accordion-shortcode-title general-border-color"><?php echo esc_attr($title)?></a></h4>
			<div class="merimag-accordion-shortcode-content general-border-color" id="<?php echo esc_attr($accordion_id)?>-<?php echo esc_attr($key)?>">
				<?php echo isset( $tab['content'] ) && is_string( $tab['content'] ) ? wp_specialchars_decode(esc_attr(do_shortcode($tab['content'])), ENT_QUOTES) : ''?>
				<div class="clear merimag-clear"></div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}
/**
 * Action
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_action( $atts = array() ) {
	$atts['block_id'] 	= isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-action-');
	$slabtext           = isset( $atts['slabtext'] ) && $atts['slabtext'] === 'yes' ? true : false;
	$before_title_style = isset( $atts['before_title_style'] ) ? $atts['before_title_style'] : false;
	$title_style 		= isset( $atts['title_style'] ) ? $atts['title_style'] : false;
	$sub_title_style 	= isset( $atts['sub_title_style'] ) ? $atts['sub_title_style'] : false;
	$description_style  = isset( $atts['description_style'] ) ? $atts['description_style'] : false;
	$title_color 		= isset( $atts['title_color'] ) && merimag_validate_color( $atts['title_color'] ) ? sprintf( 'color:%s;', $atts['title_color'] ) : false;
	$sub_title_color 	= isset( $atts['sub_title_color'] ) && merimag_validate_color( $atts['sub_title_color'] ) ? sprintf( 'color:%s;', $atts['sub_title_color'] ): false;
	$before_title_color = isset( $atts['before_title_color'] ) && merimag_validate_color( $atts['before_title_color'] ) ? sprintf( 'color:%s;', $atts['before_title_color'] ) : false;
	$color 				= isset( $atts['color'] ) && merimag_validate_color( $atts['color'] ) ? $atts['color'] : false;
	$css  				= isset( $atts['center_content'] ) && $atts['center_content'] === 'yes' ? 'text-align: center; margin: 0 auto;' : '';
	$max_width 			= isset( $atts['max_width'] ) && is_numeric( $atts['max_width'] ) ? $atts['max_width'] : '';
	$css 			   .= $max_width <= 100 && $max_width > 0 ? sprintf('max-width: %s%%;', $max_width) : '';
	$button_atts 		= $atts;
	$title_font = isset( $atts['action_title_typography'] ) && is_string( $atts['action_title_typography'] ) && in_array($atts['action_title_typography'], merimag_get_selected_google_fonts() ) ? sprintf('font-family:%s;', $atts['action_title_typography']) : '';
	$sub_title_font = isset( $atts['action_sub_title_typography'] ) && is_string( $atts['action_sub_title_typography'] ) && in_array($atts['action_sub_title_typography'], merimag_get_selected_google_fonts() ) ? sprintf('font-family:%s;', $atts['action_sub_title_typography']) : '';
	$before_title_font = isset( $atts['action_before_title_font'] ) && is_string( $atts['action_before_title_font'] ) && in_array($atts['action_before_title_font'], merimag_get_selected_google_fonts() ) ? sprintf('font-family:%s;', $atts['action_before_title_font']) : '';
	$title_style = $title_color . $title_font;
	$sub_title_style = $sub_title_color . $sub_title_font;
	$before_title_style = $before_title_color . $before_title_font;
	$css .= isset( $atts['size'] ) && is_numeric( $atts['size'] ) ? sprintf( 'font-size:%s', $atts['size'] . 'px') : '';
	?>
	<div id="<?php echo esc_attr($atts['block_id'])?>" class="merimag-shortcode-container merimag-call-to-action-shortcode" style="<?php if( $color ) : echo sprintf( 'color:%s; %s', esc_attr( $color ), esc_attr( $css ) ); else: esc_attr( $css ); endif; ?>">
	    <?php if( $slabtext === true ) : ?>
	        <div class="merimag-slabtext-block">
	            <?php if( isset( $atts['before_title'] ) && !empty( $atts['before_title'] ) ) : ?>
	                <span style="<?php echo esc_attr($before_title_style);?>" class="merimag-call-to-action-before-title slabtext"><?php echo wp_specialchars_decode( esc_attr( $atts['before_title'] ), ENT_QUOTES )?></span>
	            <?php endif; ?>
	            <?php if( isset( $atts['title'] ) && !empty( $atts['title'] ) ) : ?>
	                <span style="<?php echo esc_attr($title_style);?>" style="" class="merimag-call-to-action-title slabtext"><?php echo wp_specialchars_decode( esc_attr( $atts['title'] ), ENT_QUOTES )?></span>
	            <?php endif; ?>
	            <?php if( isset( $atts['sub_title'] ) && !empty( $atts['sub_title'] ) ) : ?>
	                <span style="<?php echo esc_attr($sub_title_style);?>" style="" class="merimag-call-to-action-sub-title slabtext"><?php echo wp_specialchars_decode( esc_attr( $atts['sub_title'] ), ENT_QUOTES )?></span>
	            <?php endif; ?>
	        </div>
	    <?php else : ?>
		<?php if( isset( $atts['before_title'] ) && !empty( $atts['before_title'] ) ) : ?>
			<h5  style="<?php echo esc_attr($before_title_style);?>" class="merimag-call-to-action-before-title"><?php echo wp_specialchars_decode( esc_attr( $atts['before_title'] ), ENT_QUOTES )?></h5>
	    <?php endif; ?>
	    <?php if( isset( $atts['title'] ) && !empty( $atts['title'] ) ) : ?>
			<h3 style="<?php echo esc_attr($title_style);?>" class="merimag-call-to-action-title"><?php echo wp_specialchars_decode( esc_attr( $atts['title'] ), ENT_QUOTES )?></h3>
	    <?php endif; ?>
	    <?php if( isset( $atts['sub_title'] ) && !empty( $atts['sub_title'] ) ) : ?>
			<h4 style="<?php echo esc_attr($sub_title_style);?>>" class="merimag-call-to-action-sub-title"><?php echo wp_specialchars_decode( esc_attr( $atts['sub_title'] ), ENT_QUOTES )?></h4>
	    <?php endif; ?>
	    <?php endif; ?>
	    <?php if( isset( $atts['description'] ) && !empty( $atts['description'] ) ) : ?>
			<div style="" class="merimag-call-to-action-description"><?php echo wp_specialchars_decode( esc_attr( $atts['description'] ), ENT_QUOTES )?></div>
	    <?php endif; ?>
	    <?php if( isset( $atts['button_title'] ) && !empty( $atts['button_title'] ) ) : ?>
	    <?php $button_atts['block_id'] = $atts['block_id'] . '-button'; ?>
	    <div class="merimag-call-to-action-buttons"><?php wp_specialchars_decode(esc_attr(merimag_get_shortcode_html( 'button', $button_atts )), ENT_QUOTES); ?></div>
	    <?php endif; ?>
	    
	</div>
	<?php
}
/**
 * Alert
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_alert( $atts = array() ) {
	$alert_color        = isset( $atts['alert_color'] ) && merimag_validate_color( $atts['alert_color'] ) ? $atts['alert_color'] : '#d9edf7';
	$alert_title 		= isset( $atts['alert_title'] ) && is_string( $atts['alert_title'] ) && !empty( $atts['alert_title'] ) ? $atts['alert_title'] : '';
    $alert_text_color   = isset( $atts['alert_text_color'] ) && merimag_validate_color( $atts['alert_text_color'] ) ? $atts['alert_text_color'] : merimag_get_text_color_from_background( $alert_color ); 
    $alert_message      = isset( $atts['alert_message'] ) && !empty( $atts['alert_message'] ) ? $atts['alert_message'] : false;
    $alert_css          = sprintf( 'background: %s;', $alert_color );
    $alert_css         .= sprintf( 'color: %s;', $alert_text_color );
    $alert_icon 		= isset( $atts['alert_icon']['icon-class'] ) && is_string( $atts['alert_icon']['icon-class'] ) ? $atts['alert_icon']['icon-class'] : '';
    $alert_icon 		= isset( $atts['alert_icon']['value'] ) && is_string( $atts['alert_icon']['value'] ) ? $atts['alert_icon']['value'] : $alert_icon;
    $class              = '';
	?>
	<?php if( $alert_message ) : ?>
	<div  style="<?php echo wp_specialchars_decode(esc_attr($alert_css), ENT_QUOTES);?>" class="merimag-shortcode-container merimag-alert-wrapper merimag-alert-shortcode <?php echo esc_attr($class)?>">
	    <div class="merimag-alert-content site-content-width">
	    	
	        <?php if( !empty( $alert_icon  ) ) : ?>
	            <i class="merimag-alert-icon <?php echo esc_attr($alert_icon)?>"></i>
	        <?php endif; ?>
	        <div class="merimag-alert-message">
		        <?php if( $alert_title ) : ?>
		    		<div class="merimag-alert-title h4-title"><?php echo esc_attr($alert_title)?></div>
				<?php endif; ?>
		        <?php echo wp_specialchars_decode(esc_attr($alert_message), ENT_QUOTES)?>
	        </div>
	    </div>
	</div>
	<?php endif; ?>
	<?php
}
/**
 * Author
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_author( $atts = array() ) {
	
	$author_id = isset( $atts['author_id'] ) ? $atts['author_id'] : false;
	$author    = get_userdata( $author_id );

	if( !isset( $author->data ) ) {
		 return;
	}


	// author data
	$member_atts['author_id'] 		  = $author_id;
	$member_atts['author_name'] 	  = isset( $author->data->display_name ) ? $author->data->display_name : '';
	$member_atts['author_company']	  = get_user_meta($author_id, 'company', true );
	$member_atts['author_position']	  = get_user_meta($author_id, 'position', true );
	$member_atts['author_bio']		  = get_user_meta($author_id, 'description', true );
	$member_atts['author_img']['url'] = get_avatar_url($author_id, array( 'size' => 500 ));
	$member_atts['author_link'] 	  = get_author_posts_url( $author_id );

	// networks

	$networks            	 		  = merimag_get_recognized_social_networks( true );

	foreach( (array) $networks as $network ) {
		$network_link = get_user_meta( $author_id, $network, true );
		if( $network_link ) {
			$member_atts['social_links'][] = array('network' => $network, 'link' => $network_link );
		}
	}
	$member_atts = array_merge( $member_atts, $atts );

	merimag_get_shortcode_html('member', $member_atts );

}
/**
 * Authors list
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_authors( $atts = array() ) {
	$authors = isset( $atts['authors'] ) && is_array( $atts['authors'] ) && !empty( $atts['authors'] ) ? $atts['authors'] : merimag_get_users('ID');
	$columns = isset( $atts['member_columns'] ) ? $atts['member_columns'] : '1';
	$class 	 = '';
	switch ($columns) {
		case '1':
			$class .= ' merimag-grid ';
			break;
		case '2':
			$class .= ' merimag-grid merimag-grid-50 ';
			break;
		case '3':
			$class .= ' merimag-grid merimag-grid-33 ';
			break;
		case '4':
			$class .= ' merimag-grid merimag-grid-25 ';
			break;
		case '5':
			$class .= ' merimag-grid merimag-grid-20 ';
			break;
	}
	$class 				 	.= isset( $atts['member_sliding'] ) && $atts['member_sliding'] === 'yes' ? ' merimag-block-carousel merimag-slick-block merimag-carousel merimag-grid-equal-height ' : '';
	$atts['grid_columns'] 	 = intval( $columns );
	$atts['sliding_columns'] = 1;
	$atts['show_dots'] 		 = true;
	$sliding_data 			 = merimag_get_sliding_data( $atts );
	$slick_data   			 = merimag_array_to_html_attributes( $sliding_data );
	$id 		  			 = merimag_uniqid('merimag-authors-carousel-');
	$color 				     = isset( $atts['member_color'] ) && merimag_validate_color( $atts['member_color'] ) ? $atts['member_color'] : false;
	$style 				     = $color ? sprintf('color:%s', $color) : '';
	?>
	<div style="<?php echo esc_attr($style)?>" id="<?php echo esc_attr($id)?>" <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> class="merimag-shortcode-container merimag-authors-shortcode <?php echo esc_attr($class)?>">
		<?php foreach( $authors as $author ) : ?>
		<div class="merimag-author-item merimag-carousel-item merimag-column">
			<?php
				$author_atts['author_id'] = $author;
				$author_atts = array_merge( $author_atts, $atts );
				merimag_get_shortcode_html( 'author', $author_atts );
			?>
		</div>
		<?php endforeach; ?>
	</div>
	<?php
}
/**
 * Button
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_button( $atts = array() ) {
	if( !isset( $atts['button_title'] ) || empty( $atts['button_title'] ) ) {
		return;
	}
	$button_style 									 = isset( $atts['button_style'] ) && in_array( $atts['button_style'], merimag_get_recognized_button_styles( true ) ) ? $atts['button_style'] : 'flat';
	$button_size 									 = isset( $atts['button_size'] ) && in_array( $atts['button_size'], merimag_get_recognized_title_sizes( true ) ) ?  $atts['button_size'] : 'normal';
	$button_rounded 								 = isset( $atts['button_rounded'] ) && in_array( $atts['button_rounded'], array('no', 'small', 'medium', 'big') ) ?  $atts['button_rounded'] : 'no';
	$block_id 	 									 = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-awesome-button-');
	$title 		 									 = isset( $atts['button_title'] ) && !empty( $atts['button_title'] ) && is_string( $atts['button_title'] ) ? $atts['button_title'] : __('Click me', 'merimag');
	$align 											 = isset( $atts['button_align'] ) && in_array( $atts['button_align'], array('default', 'start','center','end') ) ? $atts['button_align'] : 'start';
	$title 		 									 = !empty( $button_link ) ? sprintf('<a href="%s" target="%s">%s</a>', $button_link, $target, $title ) : $title;
	$full 										     = isset( $atts['button_full'] ) && $atts['button_full'] === 'yes' ? true : false;
	$class 											 = $full === true ? 'button-full' : '';
	$btn_class 										 = isset( $atts['block_class'] ) && !empty( $atts['block_class'] ) && is_string( $atts['block_class'] ) ? ' ' . $atts['block_class'] . ' ' : '';
	$button_color 									 = isset( $atts['button_color'] ) && merimag_validate_color( $atts['button_color'] ) ? $atts['button_color'] : '#000000';
	$button_text_color = merimag_get_text_color_from_background( $button_color );
	$style 											 = $button_style === 'bordered' ? sprintf('color:%s', $button_color ) : sprintf('background:%s; color:%s', $button_color, $button_text_color );
	$link 											 = isset( $atts['link'] ) ? $atts['link'] : false;
	?>
	<div  id="<?php echo esc_attr($block_id)?>"  class="merimag-shortcode-container merimag-button-shortcode merimag-button-shortcode-align-<?php echo esc_attr($align)?> <?php echo esc_attr($class)?>">
		<a style="<?php echo esc_attr($style)?>" <?php echo merimag_get_link_attr( $link ); ?> class="merimag-awesome-button merimag-btn-<?php echo esc_attr( $button_style ) ?> <?php echo esc_attr( $button_size ) ?> <?php echo esc_attr($button_rounded)?>-rounded <?php echo esc_attr( $btn_class ); ?>"><?php echo wp_specialchars_decode( esc_attr( $title ), ENT_QUOTES )?></a>
	</div>
	<?php
}
/**
 * Custom grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_custom_grid( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';

	$atts['grid_data'] 	  = $grid_data;

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'custom';
		$atts['action_shortcode'] = true;

		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Image box
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_image_box( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';

	$atts['grid_data'] 	  = $grid_data;

	$atts['columns']   	  = '1';
	$atts['show_title'] = false;
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'custom';
		$atts['action_shortcode'] = true;
		$atts['elements'] = array( $atts);
		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Custom list
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_custom_list( $atts = array() ) {
		$list_size      = isset( $atts['list_size'] ) ? $atts['list_size'] : 14;
	    $icon_color     = isset( $atts['icon_color'] ) && merimag_validate_color( $atts['icon_color'] ) ? $atts['icon_color'] : merimag_get_principal_color(); 
	    $list_css       = $list_size !== 'inherit' ? sprintf('font-size: %spx', $list_size ) : sprintf('font-size: %s', $list_size );
	    $list_items     = isset( $atts['list_items'] ) && is_array( $atts['list_items'] ) ? $atts['list_items'] : array();
	    $default_icon   = isset( $atts['list_icon']['icon-class'] ) && !empty( $atts['list_icon']['icon-class'] ) ? $atts['list_icon']['icon-class'] : 'fa fa-check';
	    $default_icon   = isset( $atts['list_icon']['value'] ) && !empty( $atts['list_icon']['value'] ) ? $atts['list_icon']['value'] : $default_icon;
	    $icon_css       = sprintf('color: %s;', $icon_color);
	    $layout 		= isset( $atts['list_layout'] ) && $atts['list_layout'] === 'flex' ? 'flex' : 'default';
	?>
	<div style="<?php echo wp_specialchars_decode(esc_attr($list_css), ENT_QUOTES);?>" class="merimag-shortcode-container merimag-custom-icon-list-shortcode merimag-custom-icon-list-<?php echo esc_attr($layout)?>">
	    <ul class="merimag-custom-icon-list">
	        <?php foreach(  $list_items as $list_item ) : ?>
	            <?php if( isset( $list_item['list_item_text'] ) && !empty( $list_item['list_item_text'] ) ) : ?>
	                <?php 
	                	$item_icon = isset( $list_item['list_item_icon']['icon-class'] ) && !empty( $list_item['list_item_icon']['icon-class'] ) ? $list_item['list_item_icon']['icon-class'] : $default_icon;
	                	$item_icon = isset( $list_item['list_item_icon']['value'] ) && !empty( $list_item['list_item_icon']['value'] ) ? $list_item['list_item_icon']['value'] : $item_icon;
	                	$list_item_text = $list_item['list_item_text'];
	                	$list_item_link_url = isset( $list_item['list_item_link']['url'] ) && is_string( $list_item['list_item_link']['url'] ) ? $list_item['list_item_link']['url'] : false;
	                	$list_item_link_target = isset( $list_item['list_item_link']['is_external'] ) && $list_item['list_item_link']['is_external'] === 'on' ? '_blank': '';
	                	$list_item_link_rel = isset( $list_item['list_item_link']['nofollow'] ) && $list_item['list_item_link']['nofollow'] === 'on' ? 'nofollow': '';
	                ?>
	                <li>
	                	<i style="<?php echo wp_specialchars_decode(esc_attr($icon_css), ENT_QUOTES);?>" class="<?php echo esc_attr($item_icon)?>"></i>
	                	<?php 
	                		if( $list_item_link_url ) {
	                			echo  sprintf('<a target="%s" href="%s" rel="%s">%s</a>', esc_attr( $list_item_link_target ), esc_url($list_item_link_url), esc_attr( $list_item_link_rel ), esc_attr($list_item_text));
	                		} else {
	                			echo esc_attr($list_item_text);
	                		}
	                	?>
	                </li>
	            <?php endif; ?>
	        <?php endforeach; ?>
	    </ul>
	</div>
	<?php
}
/**
 * Custom slider
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_custom_slider( $atts = array() ) {

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['width']		  = isset( $atts['custom_width']['custom_width'] ) && $atts['custom_width']['custom_width'] === 'yes' && isset( $atts['custom_width']['yes']['width'] ) && is_numeric( $atts['custom_width']['yes']['width']  ) ? $atts['custom_width']['yes']['width'] : false;
	$atts['infos_width']  = isset( $atts['infos_width'] ) && is_numeric( $atts['infos_width'] ) ? $atts['infos_width'] : 100;
	$atts['block_style']  = 'slider';

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'custom';

		$atts['action_shortcode'] = true;
		merimag_blocks_box( $atts );

	echo '</div>';
	
}
/**
 * Custom titled
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_custom_tiled( $atts = array() ) {
	
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'custom';

		$atts['action_shortcode'] = true;

		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Divider
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_divider( $atts = array() ) {
	$spacing_height = isset( $atts['divider_spacing_height'] ) && is_numeric( $atts['divider_spacing_height'] ) && $atts['divider_spacing_height'] > 0 && $atts['divider_spacing_height'] < 1080 ? intval( $atts['divider_spacing_height'] ) : 60;
	$spacing_width  = isset( $atts['divider_spacing_width'] ) && is_numeric( $atts['divider_spacing_width'] ) && $atts['divider_spacing_width'] > 0 && $atts['divider_spacing_width'] < 100 ? intval( $atts['divider_spacing_width'] ) : 100;
	$border_size    = isset( $atts['divider_border_size'] ) && is_numeric( $atts['divider_border_size'] ) && $atts['divider_border_size'] > 0 && $atts['divider_border_size'] <= 12 ? $atts['divider_border_size'] : 0;
	$border_style   = isset( $atts['divider_border_style'] ) && in_array( $atts['divider_border_style'], merimag_get_recognized_border_styles( true )) ? $atts['divider_border_style'] : 'solid';
	$border_color	= isset( $atts['divider_border_color'] ) && merimag_validate_color( $atts['divider_border_color'] ) ? $atts['divider_border_color'] : false;
	$style 			= '';
	$centered 		= isset( $atts['centered_divider']) && $atts['centered_divider'] === 'yes' ? true : false;
	if( $border_size > 0 ) {
		$style .= sprintf('border-top-width: %spx;', $border_size);
		$style .= sprintf('border-top-style: %s;', $border_style);
		$style .= $border_color ? sprintf('border-top-color: %s;', $border_color) : '';
	}
	$style .= sprintf( 'margin-top: %spx; margin-bottom: %spx;', $spacing_height / 2, $spacing_height / 2 );
	$style .= sprintf( 'width: %s%%;', $spacing_width );
	$style .= $centered === true ? 'margin-left: auto; margin-right: auto;' : '';
	?>
	<div class="merimag-shortcode-container merimag-divider-shortcode">
		<div style="<?php echo wp_specialchars_decode(esc_attr($style), ENT_QUOTES)?>" class="merimag-divider-container">
		</div>
	</div>
	<?php
}
/**
 * Dropcap
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_dropcap( $atts = array() ) {
	$dropcap_color         = isset( $atts['dropcap_color'] ) && merimag_validate_color( $atts['dropcap_color'] ) ? $atts['dropcap_color'] : merimag_get_principal_color(); 
    $dropcap_box_css       = isset( $atts['dropcap_style'] ) && strpos( $atts['dropcap_style'], 'background' ) !== false ? sprintf( 'background: %s;', $dropcap_color ) : sprintf( 'color: %s;', $dropcap_color );
    $color                 = isset( $atts['dropcap_style'] ) && in_array( $atts['dropcap_style'], array('background', 'background with-radius', 'background full-circle') ) ? merimag_get_text_color_from_background( $dropcap_color ) : false;
    $dropcap_box_css      .= $color ? sprintf('color:%s;',  $color ) : '';
    $dropcap_paragraph     = isset( $atts['dropcap_paragraph'] ) ? $atts['dropcap_paragraph'] : false;
    $dropcap_letter        = is_string( $dropcap_paragraph ) ? mb_substr( $dropcap_paragraph, 0, 1) : '';
    $dropcap_box_css  	  .= isset( $atts['dropcap_font_family'] ) && in_array( $atts['dropcap_font_family'], merimag_get_selected_google_fonts()) ? sprintf( 'font-family:%s;', $atts['dropcap_font_family'])  : '';
    $class                 = '';
    if( isset( $atts['dropcap_style'] ) && in_array( $atts['dropcap_style'], array('simple', 'bordered', 'background', 'bordered with-radius', 'bordered full-circle', 'background with-radius', 'background full-circle') ) ) {
        $class .= 'merimag-dropcap-shortcode-' . esc_attr( $atts['dropcap_style'] );
    }
	?>
	<div class="merimag-shortcode-container merimag-dropcap-shortcode <?php echo esc_attr($class)?>">
	    <p class="merimag-dropcap-shortcode-content">
	        <?php if($dropcap_letter ) : ?>
	        <span style="<?php echo wp_specialchars_decode(esc_attr($dropcap_box_css), ENT_QUOTES);?>" class="merimag-dropcap-letter"><?php echo esc_attr($dropcap_letter)?></span>
	        <?php endif; ?>
	        <?php echo esc_attr($dropcap_paragraph)?>
	    </p>
	</div>
	<?php
}
/**
 * Gallery
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_gallery( $atts = array() ) {
	$images 	 				   			 = isset( $atts['gallery_items'] ) && is_array( $atts['gallery_items'] ) ? $atts['gallery_items'] : array();
	$gallery_data 				   			 = $atts;
	$gallery_id 				   			 = merimag_uniqid('merimag-gallery-shortcode-');
	$gellery_style 				   			 = isset( $atts['gallery_style']['gallery_style'] ) && in_array( $atts['gallery_style']['gallery_style'], merimag_get_recognized_gallery_styles(true ) ) ? $atts['gallery_style']['gallery_style'] : 'default';
	$gallery_data   			   			 = isset( $atts['gallery_style'][ $gellery_style ])  ? $atts['gallery_style'][ $gellery_style ] : array();
	$gallery_data = array_merge($gallery_data, $atts);
	$gallery_data['gallery_width'] 			 = isset( $atts['gallery_width'] ) && is_numeric( $atts['gallery_width'] ) && $atts['gallery_width'] > 150 ? $atts['gallery_width'] : '100%';
	$gallery_data['gallery_height'] = isset( $atts['gallery_height'] ) && is_numeric( $atts['gallery_height'] ) && $atts['gallery_height'] > 150 ? $atts['gallery_height'] : 500;
	$gallery_data['slider_enable_textpanel'] = isset( $atts['enable_textpanel'] ) && ( $atts['enable_textpanel'] === true || $atts['enable_textpanel'] === 'yes' ) ? true : false;
	$gallery_data['tile_enable_textpanel']	 = isset( $atts['enable_textpanel'] ) && ( $atts['enable_textpanel'] === true || $atts['enable_textpanel'] === 'yes' ) ? true : false;
	$gallery_data['slider_textpanel_always_on'] = isset( $atts['textpanel_always_on'] ) && ( $atts['textpanel_always_on'] === true || $atts['textpanel_always_on'] === 'yes' ) ? true : false;
	$gallery_data['tile_textpanel_always_on'] = isset( $atts['textpanel_always_on'] ) && ( $atts['textpanel_always_on'] === true || $atts['textpanel_always_on'] === 'yes' ) ? true : false;
	$gallery_data['tile_enable_border'] = isset( $atts['tile_enable_border'] ) && ( $atts['tile_enable_border'] === true || $atts['tile_enable_border'] === 'yes' ) ? true : false;
	$gallery_data['tile_enable_outline'] = isset( $atts['tile_enable_outline'] ) && ( $atts['tile_enable_outline'] === true || $atts['tile_enable_outline'] === 'yes' ) ? true : false;
	$gallery_data['tile_enable_shadow'] = isset( $atts['tile_enable_shadow'] ) && ( $atts['tile_enable_shadow'] === true || $atts['tile_enable_shadow'] === 'yes' ) ? true : false;
	$gallery_data['gallery_theme'] = isset( $atts['gallery_theme'] ) && in_array( $atts['gallery_theme'], merimag_get_recognized_gallery_styles(true)) ? $atts['gallery_theme'] : 'default';
	$gallery_data['gallery_autoplay'] = isset( $atts['gallery_autoplay'] ) && ( $atts['gallery_autoplay'] === true || $atts['gallery_autoplay'] === 'yes' ) ? true : false;
	$gallery_data['gallery_play_interval'] = isset( $atts['gallery_play_interval'] ) && is_numeric( $atts['gallery_play_interval'] ) ? $atts['gallery_play_interval'] : 3000;
	$gallery_data['tiles_space_between_cols'] = isset( $atts['tiles_space_between_cols'] ) && is_numeric( $atts['tiles_space_between_cols'] ) ? $atts['tiles_space_between_cols'] : 3;
	$gallery_data['tiles_nested_optimal_tile_width'] = isset( $atts['tiles_nested_optimal_tile_width'] ) && is_numeric( $atts['tiles_nested_optimal_tile_width'] ) ? $atts['tiles_nested_optimal_tile_width'] : false;
	$gallery_data['tiles_justified_row_height'] = isset( $atts['tiles_justified_row_height'] ) && is_numeric( $atts['tiles_justified_row_height'] ) ? $atts['tiles_justified_row_height'] : false;
	$gallery_data['tiles_type'] = isset( $atts['tiles_type'] ) && in_array( $atts['tiles_type'], array('justified','nested','columns')) ? $atts['tiles_type'] : 'justified';
	$gallery_data['tiles_min_columns'] = isset( $atts['tiles_min_columns'] ) && is_numeric($atts['tiles_min_columns']) ? $atts['tiles_min_columns'] : 2;
	$gallery_data['tiles_max_columns'] = isset( $atts['tiles_max_columns'] ) && is_numeric($atts['tiles_max_columns']) ? $atts['tiles_max_columns'] : 7;

	?>
	<input type="hidden" id="<?php echo esc_attr($gallery_id)?>-data" value="<?php echo esc_js(str_replace('"', '-quote-', json_encode($gallery_data)))?>" />
	<div id="<?php echo esc_attr($gallery_id)?>" data-gallery-theme="<?php echo esc_attr($gellery_style)?>" class="merimag-gallery-shortcode merimag-gallery-default">
		<?php foreach( $images as $key => $image ) : ?>
			<?php 
				$attachment_id = isset( $image['attachment_id'] ) ? $image['attachment_id'] : false;
				$attachment_id = isset( $image['id'] ) ? $image['id'] : $attachment_id;
			?>
			<?php if( isset( $image['url'] ) ) : ?>
				<?php 
					$caption 	 = wp_get_attachment_caption( $attachment_id );
					$alt 		 = get_the_title( $attachment_id );
				?>
				<img alt="<?php echo esc_attr($alt)?>" 
					 src="<?php echo esc_url( $image['url'] )?>" 
					 data-image="<?php echo esc_url( $image['url'] )?>"
					 data-description="<?php echo esc_attr($caption)?>" />
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php
}
/**
 * Gallery tiles
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_gallery_tiles( $atts = array() ) {
	$atts['gallery_theme'] = 'tiles';
	merimag_get_shortcode_html('gallery', $atts );
}
/**
 * Gallery tiles grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_gallery_tilesgrid( $atts = array() ) {
	$atts['gallery_theme'] = 'tilesgrid';
	merimag_get_shortcode_html('gallery', $atts );
}
/**
 * Gallery carousel
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_gallery_carousel( $atts = array() ) {
	$atts['gallery_theme'] = 'carousel';
	merimag_get_shortcode_html('gallery', $atts );
}
/**
 * Heading
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_heading( $atts = array() ) {
	$title_style 		= isset( $atts['title_style'] ) ? $atts['title_style'] : false;
	$block_id 			= isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-heading-');
	$sub_title_style 	= isset( $atts['sub_title_style'] ) ? $atts['sub_title_style'] : false;
	$centered 			= isset( $atts['centered_heading'] ) && $atts['centered_heading'] === 'yes' ? true : false;
	$class 				= $centered === true ? 'merimag-heading-shortcode-centered' : '';
	$title_color 		= isset( $atts['title_color'] ) && merimag_validate_color( $atts['title_color'] ) ? sprintf('color:%s;', $atts['title_color'] ) : '';
	$sub_title_color 	= isset( $atts['sub_title_color'] ) && merimag_validate_color( $atts['sub_title_color'] ) ? $atts['sub_title_color'] : false;
	$tag 				= isset( $atts['title_tag'] ) && in_array( $atts['title_tag'], merimag_get_recognized_element_tags(true) ) ? $atts['title_tag'] : 'div';
	$font 				= isset( $atts['title_typography'] ) && in_array( $atts['title_typography'], merimag_get_selected_google_fonts() ) ? sprintf('font-family:%s;', $atts['title_typography'] ) : '';
	$size 				= isset( $atts['title_size'] ) && is_numeric( $atts['title_size'] ) ? sprintf('font-size:%spx', $atts['title_size'] ) : '';
	$style 				= $title_color . $font . $size;
	?>
	<div id="<?php echo esc_attr($block_id)?>" class="merimag-shortcode-container merimag-heading-shortcode <?php echo esc_attr($class)?>">
	    <?php if( isset( $atts['title'] ) && !empty( $atts['title'] ) ) : ?>
			<<?php echo esc_attr($tag)?> style="<?php echo esc_attr($style)?>" class="merimag-heading-title h1-title"><?php echo wp_specialchars_decode( esc_attr( $atts['title'] ), ENT_QUOTES )?></<?php echo esc_attr($tag)?>>
	    <?php endif; ?>
	</div>
	<?php
}
/**
 * Special heading
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_special_heading( $atts = array() ) {
	$title_style 		= isset( $atts['title_style'] ) ? $atts['title_style'] : false;
	$block_id 			= isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-heading-');
	$sub_title_style 	= isset( $atts['sub_title_style'] ) ? $atts['sub_title_style'] : false;
	$centered 			= isset( $atts['centered_heading'] ) && $atts['centered_heading'] === 'yes' ? true : false;
	$class 				= $centered === true ? 'merimag-heading-shortcode-centered' : '';
	$css 				= isset( $atts['title_color'] ) && merimag_validate_color( $atts['title_color'] ) ? sprintf('color:%s;', $atts['title_color'] ) : '';
	$tag 				= isset( $atts['title_tag'] ) && in_array( $atts['title_tag'], merimag_get_recognized_element_tags(true) ) ? $atts['title_tag'] : 'div';
	$style 				= isset( $atts['block_title_style'] ) ? $atts['block_title_style'] : 'default';
	$style 				= merimag_get_block_title_style( $style );
	$css 			   .= isset( $atts['title_size'] ) && is_numeric( $atts['title_size'] ) ? sprintf('font-size:%spx;', $atts['title_size']  ) : '';
	$css 		       .= isset( $atts['title_typography'] ) && in_array($atts['title_typography'], merimag_get_selected_google_fonts() ) ? sprintf('font-family:%s', $atts['title_typography']  ) : $css;
	?>
	<div id="<?php echo esc_attr($block_id)?>" class="merimag-shortcode-container merimag-special-heading-shortcode <?php echo esc_attr($class)?>">
		<div class="block-title-wrapper  <?php echo esc_attr($style)?>">
	    <?php if( isset( $atts['title'] ) && !empty( $atts['title'] ) ) : ?>
	    	<?php $atts['title'] = apply_filters('block_title_filter', $atts['title'] ); ?>
			<<?php echo esc_attr($tag)?> style="<?php echo esc_attr( $css ) ?>" class="block-title"><?php echo wp_specialchars_decode( esc_attr( $atts['title'] ), ENT_QUOTES )?></<?php echo esc_attr($tag)?>>
	    <?php endif; ?>
	    </div>
	</div>
	<?php
}
/**
 * Icon Box
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_icon_box( $atts = array() ) {
	$title_style 		= isset( $atts['title_style'] ) ? $atts['title_style'] : false;
	$sub_title_style 	= isset( $atts['sub_title_style'] ) ? $atts['sub_title_style'] : false;
	$description_style  = isset( $atts['description_style'] ) ? $atts['description_style'] : false;
    $icon_box_size      = isset( $atts['icon_box_size'] ) && is_numeric( $atts['icon_box_size'] ) ? $atts['icon_box_size'] : 14;
    $icon_color         = isset( $atts['icon_color'] ) && merimag_validate_color( $atts['icon_color'] ) ? $atts['icon_color'] : merimag_get_principal_color(); 
    $icon_box_css       = sprintf('font-size: %spx', $icon_box_size );
    $icon_css           = isset( $atts['icon_box_style'] ) && strpos( $atts['icon_box_style'], 'background' ) !== false ? sprintf( 'background: %s;', $icon_color ) : sprintf( 'color: %s;', $icon_color );
    $class              = '';
    $atts['icon_box_style'] = !isset( $atts['icon_box_style'] ) || !in_array( $atts['icon_box_style'], array('simple', 'bordered', 'background', 'bordered with-radius', 'bordered full-circle', 'background with-radius', 'background full-circle')) ? 'simple' : $atts['icon_box_style'];
    $atts['icon_box_layout'] = !isset( $atts['icon_box_layout'] ) || !in_array($atts['icon_box_layout'], array('above', 'beside' ) )  ? 'above' : $atts['icon_box_layout'];
    $class .= 'merimag-icon-box-shortcode-' . esc_attr( $atts['icon_box_style'] );
    $class .= ' merimag-icon-box-shortcode-' . esc_attr( $atts['icon_box_layout'] );
    $icon               = isset( $atts['icon']['value'] ) && is_string( $atts['icon']['value'] ) ? $atts['icon']['value'] : false;
    $icon               = isset( $atts['icon']['icon-class'] ) && is_string( $atts['icon']['icon-class'] ) ? $atts['icon']['icon-class'] : $icon;
    $description 		= isset( $atts['description'] ) && !empty( $atts['description'] ) ? $atts['description'] : false;
    $class 			   .= $description ? ' has-description ' : '';
    $title 				= isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : false;
    $sub_title 			= isset( $atts['sub_title'] ) && !empty( $atts['sub_title'] ) ? $atts['sub_title'] : false;
	?>
	<div style="<?php echo wp_specialchars_decode(esc_attr($icon_box_css), ENT_QUOTES);?>" class="merimag-shortcode-container merimag-icon-box-shortcode <?php echo esc_attr($class)?>">
	    <?php if( !empty( $icon ) ) : ?>
	        <div style="<?php echo wp_specialchars_decode(esc_attr($icon_css), ENT_QUOTES);?>" class="merimag-icon-box-icon"><i class="<?php echo esc_attr($icon)?>"></i></div>
	    <?php endif; ?>
	    <?php if( isset( $atts['icon']['url'] ) && !empty( $atts['icon']['url'] ) ) : ?>
	        <div style="" class="merimag-icon-box-image"><img src="<?php echo esc_url($atts['icon']['url'])?>"></div>
	    <?php endif; ?>
	    <?php if( $title || $sub_title || $description ) : ?>
	    <div class="merimag-icon-box-content">
	        <?php if( $title ) : ?>
	        	<div class="merimag-icon-box-title"><?php echo wp_specialchars_decode( esc_attr( $title ), ENT_QUOTES )?></div>
	        <?php endif; ?>
	        <?php if( $sub_title ) : ?>
	            <div class="merimag-icon-box-sub-title"><?php echo wp_specialchars_decode( esc_attr( $sub_title ), ENT_QUOTES )?></div>
	        <?php endif; ?>
	        <?php if( $description ) : ?>
	            <div class="merimag-icon-box-description"><?php echo wp_specialchars_decode( esc_attr( $description ), ENT_QUOTES )?></div>
	        <?php endif; ?>
	    </div>
	    <?php endif; ?>
	</div>
	<?php
}
/**
 * Icon
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_icon( $atts = array() ) {
	$icon_box_size      = isset( $atts['icon_box_size'] ) && is_numeric( $atts['icon_box_size'] ) ? $atts['icon_box_size'] : 14;
    $icon_color         = isset( $atts['icon_color'] ) && merimag_validate_color( $atts['icon_color'] ) ? $atts['icon_color'] : 'inherit'; 
    $icon_box_css       = !isset( $atts['flex_size'] ) || $atts['flex_size'] !== true ? sprintf('font-size: %spx', $icon_box_size ) : '';
    $icon_text_color    = merimag_get_text_color_from_background( $icon_color );
    $icon_css           = isset( $atts['icon_box_style'] ) && strpos( $atts['icon_box_style'], 'background' ) !== false && $icon_text_color ? sprintf( 'background: %s; color: %s;', $icon_color, $icon_text_color ) : sprintf( 'color: %s;', $icon_color );
    $icon_css           = isset( $atts['icon_box_style'] ) && strpos( $atts['icon_box_style'], 'background' ) !== false && !$icon_text_color ? sprintf( 'background: %s;', $icon_color ) : $icon_css;
    $class              = '';
    $tag                = isset( $atts['inline'] ) && $atts['inline'] === true ? 'span' : 'div';
    if( isset( $atts['icon_box_style'] ) && in_array( $atts['icon_box_style'], array('simple', 'bordered', 'background', 'bordered with-radius', 'bordered full-circle', 'background with-radius', 'background full-circle') ) ) {
        $class .= 'merimag-icon-box-shortcode-' . esc_attr( $atts['icon_box_style'] );
    }
    $class             .= !isset( $atts['inline'] ) || $atts['inline'] !== true ? ' merimag-shortcode-container merimag-icon-box-shortcode ' : ' merimag-item-icon ';
    $icon               = isset( $atts['icon']['value'] ) && is_string( $atts['icon']['value'] ) ? $atts['icon']['value'] : false;
    $icon               = isset( $atts['icon']['icon-class'] ) && is_string( $atts['icon']['icon-class'] ) ? $atts['icon']['icon-class'] : $icon;
	?>
	<<?php echo esc_attr($tag)?> style="<?php echo wp_specialchars_decode(esc_attr($icon_box_css), ENT_QUOTES);?>" class="<?php echo esc_attr($class)?>">
	    <?php if( !empty( $icon ) ) : ?>
	        <<?php echo esc_attr($tag)?> style="<?php echo wp_specialchars_decode(esc_attr($icon_css), ENT_QUOTES);?>" class="merimag-icon-box-icon "><i class="<?php echo esc_attr($icon)?>"></i></<?php echo esc_attr($tag)?>>
	    <?php endif; ?>
	</<?php echo esc_attr($tag)?>>
	<?php
}
/**
 * Instagram
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_instagram( $atts = array() ) {
	$usertag             = isset( $atts['instagram_usertag'] ) && is_string( $atts['instagram_usertag'] ) && !empty( $atts['instagram_usertag'] ) ? $atts['instagram_usertag'] : '@packtography';
    $number        		 = isset( $atts['instagram_number'] ) && is_numeric( $atts['instagram_number'] ) && $atts['instagram_number'] > 0 && $atts['instagram_number'] <= 30 ? $atts['instagram_number'] : 6;
    $cols 	       		 = isset( $atts['instagram_cols'] ) && is_numeric( $atts['instagram_cols'] ) && $atts['instagram_cols'] <= 10 && $atts['instagram_cols'] > 0 ? $atts['instagram_cols'] : 4;
    $list_class          = isset( $atts['list_class'] ) && is_string( $atts['list_class'] ) ? $atts['list_class'] : '';
    $padding             = isset( $atts['instagram_padding'] ) && is_numeric( $atts['instagram_padding'] ) ? $atts['instagram_padding'] : 0;

    $ignore_videos       = isset( $atts['instagram_only_images'] ) && $atts['instagram_only_images'] === 'yes' ? true : false;
    $ul_class            = sprintf('merimag-instagram-cols-%s %s', $cols, $list_class ); 
    $follow_button 		 = isset( $atts['instagram_follow_button'] ) && $atts['instagram_follow_button'] === 'yes' ? true : false; 
    $follow_button_color = isset( $atts['instagram_follow_button_color'] ) && merimag_validate_color( $atts['instagram_follow_button_color'] ) ? $atts['instagram_follow_button_color'] : false;
    $follow_button_text  = isset( $atts['instagram_follow_text'] ) && !empty( $atts['instagram_follow_text'] ) && is_string( $atts['instagram_follow_text'] ) ? $atts['instagram_follow_text'] : $usertag;
    $text_color_follow   = $follow_button_color ? merimag_get_text_color_from_background(  $follow_button_color ) : false;
    $custom_width        = isset( $atts['instagram_custom_width'] ) && is_numeric( $atts['instagram_custom_width'] ) ? $atts['instagram_custom_width'] : false;
    $style               = $custom_width ? sprintf('width:%spx;', $custom_width ) : '';
    $style              .= sprintf('padding: %spx;', $padding );
    $lazy                = merimag_get_db_customizer_option('lazy_image_loading');
    $lazy                = isset( $atts['instagram_lazy_load'] ) && $atts['instagram_lazy_load'] === true ? true : $lazy;
    if( $usertag ) {
        $images = merimag_scrape_instagram( $usertag );
    }
	?>
	<?php if( isset( $images ) && is_array( $images ) ) : ?>
	<div class="merimag-shortcode-container merimag-instagram-shortcode">
	    <div class="merimag-instagram-images-list <?php echo esc_attr($ul_class)?>" style="<?php echo esc_attr(sprintf('margin:-%spx', $padding))?>">
		<?php
	    foreach( $images as $k => $image ) :
	        $link = isset( $image['link'] ) ? $image['link'] : false;
	        $description = isset( $image['description'] ) && !is_customize_preview() ? $image['description'] : false;
	        $src = isset( $image['large'] ) ? $image['large'] : false;
	        $type = isset( $image['type'] ) && in_array( $image['type'], array('image', 'video') ) ? $image['type'] : $image;
	        if( $type == 'video' && $ignore_videos == true ) {
	            $number += 1;
	            continue;
	        }
	        $load_image = $lazy === true ? sprintf('data-src="%s"', esc_url( $src ) ) : sprintf('style="background-image: url(%s);"', esc_url( $src ) );
	    ?>
	    <div class="merimag-instagram-item" style="<?php echo esc_attr($style)?>">
	        <?php
	        	if( $type === 'video' ) {
	        		echo '<i class="fa fa-play"></i>';
	        	}
	        ?>
	        <a href="<?php echo esc_url( $link )?>" title="<?php echo esc_attr($description)?>" <?php echo wp_specialchars_decode(esc_attr($load_image), ENT_QUOTES)?> target="_blank" class="merimag-instagram-image merimag-lazy-image"></a>
	    </div>
	    <?php 
	        if( $k == ( $number - 1 ) ) {
	            break;
	        }
	    endforeach; 
	    ?>
	    <div class="merimag-clear clear"></div>
	    </div>
	    <?php
	        switch ( substr( $usertag, 0, 1 ) ) {
	            case '#':
	                $url = '//instagram.com/explore/tags/' . str_replace( '#', '', $usertag );
	                break;

	            default:
	                $url = '//instagram.com/' . str_replace( '@', '', $usertag );
	                break;
	        }

	        if ( '' !== $link && $follow_button ) { ?>
	            <a class="merimag-instagram-button" href="<?php echo trailingslashit( esc_url( $url ) ); ?>" rel="me" target="_blank">
	                <?php echo wp_kses_post( $follow_button_text ); ?>
	            </a>
	        <?php
	        }
	    ?>
	</div>
	<?php endif; ?>
	<?php
}
/**
 * Mailchimp
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_mailchimp( $atts = array() ) {
	if( !function_exists('yikes_easy_mailchimp_extender_get_form_interface')) {
		 return;
	}
	$form_id  	 = isset( $atts['mailchimp_form'] ) && is_numeric($atts['mailchimp_form']) ? $atts['mailchimp_form'] : false;
	$shortcode   = sprintf('[yikes-mailchimp form="%s"]', $form_id);
	$form_interface   = yikes_easy_mailchimp_extender_get_form_interface();
	$all_forms        = $form_interface->get_all_forms();
	$form_attr 		  = '';
	foreach($all_forms as $id=>$form) {
		if( $id == $form_id) {
			$form_name = isset( $form['form_name'] ) ? $form['form_name'] : '';
			$form_name = str_replace(' ', '-', strtolower( $form_name ) );
			$form_attr = $form_name . '-' . $form_id;
			break;
		}
	}
	$form_id_attr 	 = sprintf( 'id="%s"', esc_attr( $form_attr ) );
	$form_id_replace = sprintf('id="%s"', esc_attr( merimag_uniqid($form_attr . '-') ) );
	$inline      	 = isset( $atts['inline_form'] ) && $atts['inline_form'] == 'yes' ? true : false;
	$stacked     	 = isset( $atts['stacked_inputs'] ) && $atts['stacked_inputs'] == 'yes' ? true : false;
	$title 		 	 = isset( $atts['form_title'] ) && !empty( $atts['form_title'] ) ? $atts['form_title'] : false;
	$text 		 	 = isset( $atts['text'] ) && !empty( $atts['text'] ) ? $atts['text'] : false;
	$footer_text 	 = isset( $atts['footer_text'] ) && !empty( $atts['footer_text'] ) ? $atts['footer_text'] : false;
	$show_icon   	 = isset( $atts['show_newsletter_icon'] ) && ( $atts['show_newsletter_icon'] === true || $atts['show_newsletter_icon'] === 'yes' ) ? true : false;
	$class 		 	 = $inline === true ? ' merimag-mailchimp-form-inline ' : ' merimag-mailchimp-form-block ';
	$class 	        .= $stacked === true ? ' merimag-mailchimp-form-stacked-inputs ' : '';
	$color 	 		 = isset( $atts['color'] ) && merimag_validate_color( $atts['color'] ) ? sprintf( 'color:%s;', $atts['color'] ) : false;
	$icon_color 	 = isset( $atts['icon_color'] ) && merimag_validate_color( $atts['icon_color'] ) ? sprintf( 'color:%s;', $atts['icon_color'] ) : false;
	$title_color 	 = isset( $atts['title_color'] ) && merimag_validate_color( $atts['title_color'] ) ? sprintf( 'color:%s;', $atts['title_color'] ) : false;
	$block_id 		 = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('mailchimp-form-');

	?>
	<div id="<?php echo esc_attr($block_id )?>" class="merimag-mailchimp-shortcode merimag-shortcode-container merimag-mailchimp-form <?php echo esc_attr($class)?>">
		<div class="merimag-mailchimp-call-for-subscribe">
			<?php
				if( $show_icon ) {
					echo '<div class="merimag-mailchimp-icon"><span class="fa fa-envelope"></span></div>';
				}
			?>
			<div class="merimag-mailchimp-call-for-subscribe-text">
				<?php
					if( $title ) {
						echo sprintf('<h5 class="merimag-mailchimp-title">%s</h5>' , esc_attr($title));
					}
					if( $text ) {
						echo sprintf('<p class="merimag-mailchimp-text">%s</p>', esc_attr($text));
					}
				?>
			</div>
		</div>
		<?php echo do_shortcode($shortcode); ?>
	</div>
	<?php
}
function merimag_get_media_icon( $media_type, $url ) {
	switch ($media_type) {
		case 'video':
			$type = 'video';
			break;
		case 'audio':
			$type = 'audio';
			break;
		case 'external_image':
			$type = 'image';
			break;
		case 'external_media':
			if (strpos($url, 'youtube.com') > 0) {
		        $type = 'video';
		    } elseif (strpos($url, 'vimeo.com') > 0) {
		        $type = 'video';
		    } elseif (strpos($url, 'dailymotion.com') > 0) {
		        $type = 'video';
		    } elseif (strpos($url, 'ted.com') > 0) {
		        $type = 'video';
		    } elseif (strpos($url, 'wordpress.tv') > 0) {
		        $type = 'video';
		    } elseif (strpos($url, 'soundcloud.com') > 0) {
		        $type = 'audio';
		    } elseif (strpos($url, 'spotify.com') > 0) {
		        $type = 'audio';
		    } elseif (strpos($url, 'scribd.com') > 0) {
		        $type = 'book';
		    } elseif (strpos($url, 'hulu.com') > 0) {
		        $type = 'video';
		    } elseif (strpos($url, 'flickr.com') > 0) {
		        $type = 'image';
		    } elseif (strpos($url, 'instagram.com') > 0) {
		        $type = 'image';
		    } elseif (strpos($url, 'photobucket.com') > 0) {
		        $type = 'image';
		    } else {
		        $type = 'unknown';
		    }
		    break;
		default:
			$type = 'unknown';
			break;
	}
	switch ($type) {
		case 'video':
			return 'icofont-play-alt-1';
			break;
		case 'audio':
			return 'icofont-headphone-alt-1';
			break;
		case 'image':
			return 'icofont-image';
			break;
		case 'book':
			return 'icofont-read-book';
			break;
		default:
			return 'icofont-multimedia';
			break;
	}
}
/**
 * Media
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_media( $atts = array() ) {
	if( !isset( $atts['media_type'] ) || !isset( $atts['media_picker'] ) ) {
		 return;
	}
	$media_type   = $atts['media_type'];
	$id 		  = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-media-');
	$media_picker = $atts['media_picker'];
	$url 		  = isset( $media_picker[$media_type]['url'] ) ? $media_picker[$media_type]['url'] : false;
	$media_icon   = merimag_get_media_icon( $media_type, $url );
	$upload 	  = isset( $media_picker[$media_type]['upload']['url'] ) ? $media_picker[$media_type]['upload']['url'] : false;
	$height 	  = isset( $atts['height'] ) && is_numeric( $atts['height'] ) ? $atts['height'] : false;
	$width 	  	  = isset( $atts['width'] ) && is_numeric( $atts['width'] ) ? $atts['width'] : false;
	$style 		  = $height !== false ? sprintf('height:%spx;', $height) : '';
	$style 		 .= $width !== false ? sprintf('width:%spx;', $width) : '';
	$class 		  = $height === false && $media_type === 'external_media' ? 'merimag-fitvids' : '';
	$cover 		  = isset( $media_picker[$media_type]['cover']['url'] ) ? $media_picker[$media_type]['cover']['url'] : false;
	$alt 		  = isset( $media_picker[$media_type]['alt_text'] ) ? $media_picker[$media_type]['alt_text'] : __('Image', 'merimag');
	$style   	  .= $cover ? sprintf('background-image:url(%s);', $cover) : ( $media_type === 'audio' ? merimag_get_color_layer() : '' );
	$class 		  .= $media_type === 'audio' ? 'merimag-media-shortcode-audio' : '';
	$title 		   = isset( $atts['title'] ) && is_string( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : false;
	$author 		= isset( $atts['author'] ) && is_string( $atts['author'] ) && !empty( $atts['author'] ) ? $atts['author'] : false;
	$align 		   = isset( $atts['align'] ) && in_array($atts['align'], array('left', 'right', 'none')) ? $atts['align'] : 'none';
	$style 		  .= sprintf('float:%s;', $align);
	$container_class = 'merimag-media-shortcode-' . $align;
	$title_class = $media_type !== 'audio' ? 'principal-color-background-color' : '';
	?>
	<div id="<?php echo esc_attr($id)?>" class="merimag-shortcode-container merimag-media-shortcode <?php echo esc_attr($container_class)?>" style="<?php echo esc_attr($style)?>">
		<div class="merimag-media-shortcode-content <?php echo esc_attr($class)?>" >
		<?php if( $title ) : ?>
		<div class="merimag-media-shortcode-title <?php echo esc_attr($title_class)?>">
			<div class="merimag-media-title"><?php echo esc_attr($title)?></div>
			<?php if( $author ) : ?>
			<div class="merimag-media-shortcode-author">
				<?php echo esc_attr($author)?>
			</div>
			<?php endif; ?>
			<?php if( $media_type !== 'audio' ) : ?>
			<span class="merimag-media-shortcode-icon"><i class="<?php echo esc_attr($media_icon)?>"></i></span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if( $media_type === 'audio' ) : ?>
		<span class="merimag-media-shortcode-icon"><i class="<?php echo esc_attr($media_icon)?>"></i></span>
		<?php endif; ?>
		<?php
			switch ($media_type) {
				case 'external_image':
					echo sprintf('<img width="%spx" height="%spx" src="%s" alt="%s" />', esc_attr($width), esc_attr($height), esc_url($url), esc_attr($alt) );
					break;
				case 'external_media':
					echo wp_oembed_get( $url);
					break;
				case 'video':
					?>
					<video id="<?php echo esc_attr($id)?>-player" poster="<?php echo esc_url( $cover )?>" class="merimag-video" playsinline controls>
					    <source src="<?php echo esc_url( $upload )?>" type="video/mp4" />
					</video>
					<?php
					break;
				case 'audio':
					?>
					<audio id="<?php echo esc_attr($id)?>-player" poster="<?php echo esc_url( $cover )?>" class="merimag-video" playsinline controls>
					    <source src="<?php echo esc_url( $upload )?>" />
					</audio>
					<?php
					break;
				case 'image':
					echo sprintf('<img width="%spx" height="%spx" src="%s" alt="%s" />', esc_attr($width), esc_attr($height), esc_url($upload), esc_attr($alt) );
					break;
				default:
					# code...
					break;
			}
		?>
		</div>
	</div>
	<?php
}
/**
 * Embed
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_embed( $atts = array() ) {
	$atts['media_type'] = 'external_media';
	$atts['media_picker'][$atts['media_type']] = $atts;
	merimag_get_shortcode_html('media', $atts );
}
/**
 * Video
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_video( $atts = array() ) {
	$atts['media_type'] = isset( $atts['media_type'] ) && $atts['media_type'] === 'external_media' ? 'external_media' : 'video';
	$atts['media_picker'][$atts['media_type']] = $atts;
	merimag_get_shortcode_html('media', $atts );
}
/**
 * Video
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_audio( $atts = array() ) {
	$atts['media_type'] = isset( $atts['media_type'] ) && $atts['media_type'] === 'external_media' ? 'external_media' : 'audio';
	$atts['media_picker'][$atts['media_type']] = $atts;
	merimag_get_shortcode_html('media', $atts );
}
/**
 * Image
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_image( $atts = array() ) {
	$atts['media_type'] = isset( $atts['media_type'] ) && $atts['media_type'] === 'external_media' ? 'external_image' : 'image';
	$atts['media_picker'][$atts['media_type']] = $atts;
	merimag_get_shortcode_html('media', $atts );
}
/**
 * Member
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_member( $atts = array() ) {
	// social icons flex for author
	$atts['icons_columns']   = 'flex';
	$atts['icons_layout']  	 = 'only_icon';
	$atts['custom_networks'] = true;
	$author_name 		     = isset( $atts['author_name'] ) && $atts['author_name'] && is_string( $atts['author_name'] ) ? $atts['author_name'] : __('Author name', 'merimag');
	$author_company		     = isset( $atts['author_company'] ) && $atts['author_company'] && is_string( $atts['author_company'] ) ? $atts['author_company'] : false;
	$author_position		 = isset( $atts['author_position'] ) && $atts['author_position'] && is_string( $atts['author_position'] ) ? $atts['author_position'] : false;
	$author_company 		 = $author_position && $author_position ? sprintf('%s / %s', $author_position, $author_company ) : $author_company;
	$author_bio		   	     = isset( $atts['author_bio'] ) &&  $atts['author_bio']  && is_string( $atts['author_bio'] ) ? $atts['author_bio'] : false;
	$author_img 		     = isset( $atts['author_img']['url'] ) && $atts['author_img']['url'] ? $atts['author_img']['url'] : get_avatar_url(false, array('size' => 500 ) );
	$author_link 			 = isset( $atts['author_link'] ) && !empty( $atts['author_link'] ) ? $atts['author_link'] : false;
	$author_link 			 = is_array( $author_link ) && isset( $author_link['attr'] ) ? $author_link['attr'] : $author_link;
	$author_link 			 = is_string( $author_link ) ? $author_link : false;
	$member_layout 		     = isset( $atts['member_layout'] ) && $atts['member_layout'] && is_string( $atts['member_layout'] ) ? $atts['member_layout'] : 'image-beside-title';
	$class 				     = $member_layout;
	$rounded_image 		     = isset( $atts['rounded_image'] ) && $atts['rounded_image'] === 'yes' ? true : false;
	$class 	   			    .= $rounded_image === true ? ' rounded-image-member' : '';
	$class 				    .= isset( $atts['member_border_style'] ) && is_string( $atts['member_border_style'] ) ? sprintf(' %s ', esc_attr($atts['member_border_style'])) : '';
	$color 				     = isset( $atts['member_color'] ) && merimag_validate_color( $atts['member_color'] ) ? $atts['member_color'] : false;
	$style 				     = $color ? sprintf('color:%s', $color) : '';
	$posts_count 			 = isset( $atts['author_id'] ) && $atts['author_id'] ? count_user_posts( $atts['author_id'] ) : false;
	$atts['icon_size']  	 = isset( $atts['icon_size'] ) && is_numeric( $atts['icon_size'] ) ? $atts['icon_size'] : 4;
	?>
	<div class="merimag-shortcode-container merimag-member-shortcode <?php echo esc_attr($class)?>" style="<?php echo esc_attr($style)?>">
		<?php 
			if( $author_img && $author_link  )  {
				echo sprintf('<div style="background-image: url(%s);" class="merimag-author-img"><a class="merimag-member-link" href="%s"></a></div>', esc_url($author_img), esc_url($author_link) );
			}
			if( $author_img && !$author_link )  {
				echo sprintf('<div style="background-image: url(%s);" class="merimag-author-img"></div>', esc_url($author_img) );
			}
		?>
		<div class="merimag-member-data merimag-block-infos">
			<div class="merimag-member-infos">
				<div class="merimag-member-name-company-social">
					<h4 class="merimag-member-name">
						<?php 
							if( $author_link ) {
								echo sprintf('<a href="%s">%s</a>', esc_url( $author_link ), esc_attr( $author_name ) );
							} else {
								echo esc_attr( $author_name );
							}
							if( $author_company ) {
								echo sprintf('<span class="merimag-member-company">%s</span>', esc_attr( $author_company ) );
							}
							if( !$author_company && isset( $atts['author_id'] ) && $atts['author_id'] ) {
								sprintf('<a class="merimag-member-company" href="%s">%s %s</a>', esc_url( $author_link ), esc_html__('View posts by', 'merimag'), esc_attr( $author_name ) );
							}
						?>
						</h4>
						<?php if( $member_layout === 'image-beside-title') : ?>
						<div class="merimag-member-social-links">
							<?php merimag_get_shortcode_html('social-icons', $atts ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if( $member_layout === 'image-beside-title') : ?>
				<div class="merimag-member-bio">
					<?php
						if( $author_bio ) {
							echo sprintf('<p>%s</p>', esc_attr( $author_bio ) );
						}
					?>
				</div>
				<?php endif; ?>
			</div>
			<?php if( $member_layout !== 'image-beside-title') : ?>
			<div class="merimag-member-bio">
				<?php
					if( $author_bio ) {
						echo sprintf('<p>%s</p>', esc_attr( $author_bio ) );
					}
				?>
				<div class="merimag-member-social-links">
					<?php merimag_get_shortcode_html('social-icons', $atts ); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
/**
 * Menu
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_menu( $atts = array() ) {
	$separator = isset( $atts['items_separator'] ) && $atts['items_separator'] === 'border-spacing' ? 'border-spacing' : 'simple-spacing';
	?>
	<div class="merimag-shortcode-container merimag-menu-shortcode  merimag-menu-shortcode-<?php echo esc_attr( $separator ); ?>">
	<?php if( isset( $atts['menu_items'] ) && is_array( $atts['menu_items'] ) ) : ?>
		<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : false; ?>
		<?php if( $title ) : ?>
			<h5 class="merimag-menu-shortcode-title  general-border-color"><?php echo esc_attr( $title )?></h5>
		<?php endif; ?>
		<ul>
			<?php foreach( $atts['menu_items'] as $key => $menu_item ) : ?>
				<?php 
					$link 		= isset( $menu_item['link'] ) ? $menu_item['link'] : '#';
					$item_title = isset( $menu_item['title'] ) && !empty( $menu_item['title'] ) ? $menu_item['title'] : sprintf(__('Menu item #%s', 'merimag'), ( $key+1 ) );
				?>
				<?php echo sprintf('<li class="general-border-color"><a %s>%s</a></li>', merimag_get_link_attr( $link ), esc_attr( $item_title ) ); ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
	<?php
}
/**
 * Multi buttons
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_multi_buttons( $atts = array() ) {
	$button_size    = isset( $atts['button_size'] ) && in_array( $atts['button_size'], merimag_get_recognized_title_sizes( true ) ) ?  $atts['button_size'] : 'normal';
	$button_rounded = isset( $atts['button_rounded'] ) && in_array( $atts['button_rounded'], array('no', 'small', 'medium', 'big') ) ?  $atts['button_rounded'] : 'no';
	$buttons 		= isset( $atts['buttons'] ) && is_array( $atts['buttons'] ) ? $atts['buttons'] : array();
	$block_id 		= isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-element-');
	$full 			= isset( $atts['button_full'] ) && $atts['button_full'] === 'yes' ? $atts['button_full'] : 'no';
	$align 			= isset( $atts['button_align'] ) && in_array( $atts['button_align'], array('default', 'start','center','end') ) ? $atts['button_align'] : 'start';

	?>
	<div class="merimag-shortcode-container merimag-multi-buttons-shortcode merimag-button-shortcode-align-<?php echo esc_attr($align)?>">
		<?php foreach( $buttons as $k => $button ) :
			$button['button_size'] = $button_size;
			$button['button_rounded'] = $button_rounded;
			$button['block_id'] =  $atts['block_id'] . '-' . $k;
			$button['block_class'] = isset( $button['_id'] ) ? 'elementor-repeater-item-' . $button['_id'] : $button['block_id'];
			$button['button_full'] 	= $full;
			$button['key'] = $k;
			merimag_get_shortcode_html('button', $button );
		endforeach; ?>
	</div>
	<?php
}

/**
 * News ticker
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_ticker( $atts = array() ) {
	$ticker_title = isset( $atts['ticker_title'] ) && is_string( $atts['ticker_title'] ) ? $atts['ticker_title'] : '';
	$ticker_icon = isset( $atts['ticker_icon'] ) && is_string( $atts['ticker_icon'] ) ? $atts['ticker_icon'] : 'icofont-flash';
	?>
	<div class="merimag-ticker-shortcode">
		<div class="merimag-news-ticker-container">
			<div class="merimag-news-ticker-title principal-color-background-color">
				<span class="ticker-icon <?php echo esc_attr($ticker_icon) ?>"></span>
				<span class="merimag-news-ticker-text"><?php echo esc_attr($ticker_title)?></span>
			</div>
			<div class="merimag-news-ticker-content">
				<?php
					$post_type  = 'post';
					$query_keys = array('order_by', 'order');
					$query 		= isset( $atts ) && is_array( $atts ) && merimag_array_keys_exists( $atts, $query_keys ) ? merimag_validate_query( $post_type, $atts ) : false;
					$query     	= !$query ? merimag_get_theme_settings_query( 'post', 'ticker') : $query;
					$data		= merimag_get_block_data( $query );
					$elements 	= $data['elements'];
					merimag_blocks_ticker( $elements, $atts );
				?>
		   	</div>
	   	</div>
	</div>
	<?php
}
/**
 * Padding
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_padding( $atts = array() ) {
	$content = isset( $atts['padding_content'] ) ? $atts['padding_content'] : '';
	?>
	<div class="merimag-content-padding merimag-shortcode-container">
		<?php echo do_shortcode($content); ?>
	</div>
	<?php
}
/**
 * Posts block
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_posts_block( $atts = array() ) {

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'post';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');
		$atts['number'] = merimag_get_block_style_number( $atts['block_style'] );
		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Posts carousel
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_posts_carousel( $atts = array() ) {
	
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']		  = 'carousel';
	$atts['grid_data'] 	  = $grid_data;

	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'post';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Posts grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_posts_grid( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';
	$atts['grid_data'] 	  = $grid_data;

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'post';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Simple posts grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_simple_posts_grid( $atts = array() ) {
	$defaults = merimag_get_predefined_grid_style($atts['grid_style']);
	unset( $defaults['pagination'] );
	$atts = array_replace($atts, $defaults);
	merimag_get_shortcode_html('posts-grid', $atts );
}
/**
 * Posts list
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_posts_list( $atts = array() ) {
	
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';
	$atts['grid_data'] 	  = $grid_data;

	$atts['columns']   	  = '1';
	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'post';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Simple Posts list
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_simple_posts_list( $atts = array() ) {

	$atts['block_style']  	= 'grid';

	$atts['grid_style'] 	= is_rtl() ? 'right' : 'left';
	$atts['title_size'] 	= 'small';
	$atts['after_title'] 	= 'date-review';

	$atts['show_category'] 	= 'no';
	$atts['title_ellipsis'] = 2;
	$atts['columns'] 		= '1';
	$atts['show_review'] 	= 'no';

	echo '<div class="merimag-block-container">';

		$atts['post_type'] 	= 'post';

		$id = merimag_uniqid('simple-posts-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Row shortcode
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_row( $atts = array() ) {
	$spacing = isset( $atts['row_spacing'] ) && is_string( $atts['row_spacing'] ) ? $atts['row_spacing'] : 'medium';
	$class   = sprintf('merimag-%s-spacing', $spacing );
	$columns = isset( $atts['row_columns'] ) && is_array( $atts['row_columns'] ) ? $atts['row_columns'] : array();
	echo sprintf('<div class="merimag-grid %s">', esc_attr( $class ) );
	foreach( $columns as $column ) {
		$column_width = isset( $column['width'] ) && is_numeric( $column['width'] ) ? $column['width'] : 33;
		$content 	  = isset( $column['content'] ) && is_string( $column['content'] ) ? $column['content'] : ''; 
		echo sprintf('<div class="merimag-column merimag-column-%s">', esc_attr( $column_width) );
		echo do_shortcode( $content );
		echo '</div>';
	}
	echo '</div>';
}
/**
 * Posts slider
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_posts_slider( $atts = array() ) {
	
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['width']		  = isset( $atts['custom_width']['custom_width'] ) && $atts['custom_width']['custom_width'] === 'yes' && isset( $atts['custom_width']['yes']['width'] ) && is_numeric( $atts['custom_width']['yes']['width']  ) ? $atts['custom_width']['yes']['width'] : false;
	$atts['infos_width']  = isset( $atts['infos_width'] ) && is_numeric( $atts['infos_width'] ) ? $atts['infos_width'] : 100;
	$atts['block_style']  = 'slider';

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'post';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Posts slider with thumbs
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_posts_slider_thumbs( $atts = array() ) {
	
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['width']		  = isset( $atts['custom_width']['custom_width'] ) && $atts['custom_width']['custom_width'] === 'yes' && isset( $atts['custom_width']['yes']['width'] ) && is_numeric( $atts['custom_width']['yes']['width']  ) ? $atts['custom_width']['yes']['width'] : false;
	$atts['infos_width']  = isset( $atts['infos_width'] ) && is_numeric( $atts['infos_width'] ) ? $atts['infos_width'] : 100;
	

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'post';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Products carousel
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_products_carousel( $atts = array() ) {
	
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']		  = 'carousel';
	$atts['grid_data'] 	  = $grid_data;

	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'product';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Products grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_products_grid( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']		  = 'grid';
	$atts['grid_data'] 	  = $grid_data;

	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'product';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Products list
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_products_list( $atts = array() ) {
	
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';
	$atts['grid_data'] 	  = $grid_data;
	$atts['columns']   	  = '1';

	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'product';

		$id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-box-filter-');

		merimag_get_block_filters_head( $atts, $id );

		merimag_get_box( $atts, $id );

	echo '</div>';
}
/**
 * Quotation
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_quotation( $atts = array() ) {
	
	$quotation   = isset( $atts['quote'] ) ? $atts['quote'] : false;
	$author_name = isset( $atts['author_name'] ) ? $atts['author_name'] : false;
	$company 	 = isset( $atts['author_company'] ) ? $atts['author_company'] : false;
    $link        = isset( $atts['author_link'] ) && is_string( $atts['author_link'] ) ? $atts['author_link'] : false;
	$img         = isset( $atts['author_img']['url'] ) ? $atts['author_img']['url']  : false;
    $style       = isset( $atts['quote_style']) && in_array( $atts['quote_style'], range(1, 11)) ? $atts['quote_style'] : 5;
    $float       = isset( $atts['quote_align'] ) && in_array( $atts['quote_align'], array('quote-float-left', 'quote-float-right','quote-center')) ? $atts['quote_align'] : 'quote-center';
    $width       = isset( $atts['quote_width'] ) && is_numeric( $atts['quote_width'] ) && $atts['quote_width'] >= 20 && $atts['quote_width'] <=100 ? $atts['quote_width'] : 100;
    $color       = isset( $atts['quote_color'] ) && merimag_validate_color( $atts['quote_color'] ) ? $atts['quote_color'] : false;
    $bg_color    = isset( $atts['quote_background'] ) && merimag_validate_color( $atts['quote_background'] ) ? $atts['quote_background'] : false;
    $css         = sprintf('width:%s%%;', $width);
    $color       = $bg_color && in_array( $style, array('5','4')) && !$color ? merimag_get_text_color_from_background( $bg_color ) : $color;
    $css        .= $color ? sprintf('color:%s;', $color) : '';
    $css        .= $bg_color && in_array( $style, array('4', '5')) ? sprintf('background:%s;', $bg_color) : '';
    $open_link_tag = $link ? sprintf('<a href="%s" target="_blank"', esc_url( $link ) ) : '<span';
    $close_link_tag = $link ? '</a>' : '</span>';
    ?>
    <div style="<?php echo wp_specialchars_decode(esc_attr($css), ENT_QUOTES)?>" class="merimag-shortcode-container merimag-quote-shortcode quote-container style-<?php echo esc_attr($style)?> <?php echo esc_attr($float)?>">
	    <div class="merimag-quote-content">
	    	<div class="quote-quotation"><?php echo esc_attr($quotation)?></div>
		    <?php if( $author_name ) : ?>
		    <div class="quote-author">
		        <div class="quote-author-content">
		            <?php if( $img ) : ?>
		                <?php echo wp_specialchars_decode(esc_attr($open_link_tag), ENT_QUOTES)?> class="quote-author-img" style="background-image: url(<?php echo esc_url($img)?>);"><?php echo wp_specialchars_decode(esc_attr($close_link_tag), ENT_QUOTES)?>
		            <?php endif; ?>
		            <div class="quote-author-infos">
		                <?php echo wp_specialchars_decode(esc_attr($open_link_tag), ENT_QUOTES)?> class="quote-autor-name"><?php echo esc_attr($author_name)?><?php echo wp_specialchars_decode(esc_attr($close_link_tag), ENT_QUOTES)?>
		                <span class="quote-autor-company" href="#"><?php echo esc_attr($company)?></span>
		            </div>
		        </div>
		    </div>
		    <?php endif; ?>
	    </div>
	</div>
    <?php
}
/**
 * Review
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_review( $atts = array() ) {
	
	$review_title 	  = isset( $atts['review_title'] ) && !empty( $atts['review_title'] ) ? $atts['review_title'] : false;
	$review_summary   = isset( $atts['review_summary'] ) && !empty( $atts['review_summary'] ) ? $atts['review_summary'] : false;

	$review_cretirias = isset( $atts['review_cretirias'] ) && !empty( $atts['review_cretirias'] ) ? $atts['review_cretirias'] : array();
	$valid_styles	  = array('stars','points','percent'); 
	$review_style 	  = isset( $atts['review_style'] ) && in_array( $atts['review_style'], $valid_styles ) ? $atts['review_style'] : 'points';
	$score 			  = 0;
	$review_author    = get_bloginfo('name');
	$publisher 		  = get_bloginfo('name');
	if( $review_cretirias && count( $review_cretirias ) > 0 ) {
		$note 			  = 0;
		foreach( (array) $review_cretirias as $k => $review_cretiria ) {
			$note += isset( $review_cretiria['note'] ) && is_numeric( $review_cretiria['note'] ) && $review_cretiria['note'] >= 0 && $review_cretiria['note'] <= 10 ? $review_cretiria['note'] : 0;
		}
		$score = ( $note / count( $review_cretirias ) ) / 2;
	}
	if( $score > 0 && $score <= 1 ) {
		$score_comment = __('Very Bad', 'merimag');
	}
	if( $score > 1 && $score <= 2 ) {
		$score_comment = __('Bad', 'merimag');
	}
	if( $score > 2 && $score <= 3 ) {
		$score_comment = __('Correct', 'merimag');
	}
	if( $score > 3 && $score <= 4 ) {
		$score_comment = __('Nice', 'merimag');
	}
	if( $score > 4 && $score <= 5 ) {
		$score_comment = __('Awesome!', 'merimag');
	}
	$score_comment 	  = isset( $atts['review_score_comment'] ) && !empty( $atts['review_score_comment'] ) ? $atts['review_score_comment'] : $score_comment;
	$score_percent 	 = ( $score * 100 ) / 5;
	$score_points  	 = $score * 2;
	if( !function_exists('wp_star_rating') ) {
      require_once( ABSPATH . 'wp-admin/includes/template.php' );
    }
	$score_stars     = wp_star_rating( array('rating' => $score, 'echo' => false) );
	$score_highlight = $score_points;
	switch ($review_style) {
		case 'stars':
			$score_highlight = $score_stars;
			break;
		case 'points':
			$score_highlight = number_format( $score_points, 1);
			break;
		case 'percent':
			$score_highlight = number_format( $score_percent, 0);
			break;
	}
	$block_title_style = merimag_get_block_title_style();
	$score_content_class = $review_style === 'stars' ? 'general-border-color stars' : ' circle principal-color-background-color';
    ?>
    <div class="merimag-shortcode-container merimag-review-shortcode">
		<?php if( $review_title ) : ?>
		<div class="block-title-wrapper <?php echo esc_attr($block_title_style)?>"><span class="block-title merimag-review-title"><?php echo esc_attr($review_title)?></span></div>
		<?php endif; ?>
		<div class="merimag-review-score-summary general-border-color">
			<?php if( $review_summary ) : ?>
				
				<div class="merimag-review-summary"><?php echo esc_attr($review_summary)?></div>
			<?php endif; ?>
			<div class="merimag-review-score <?php echo esc_attr( $review_style ) ?>">
				<div class="merimag-review-score-content <?php echo esc_attr($score_content_class)?>" data-width="<?php echo esc_attr($score_percent / 100) ?>">
					<?php echo sprintf('<div class="merimag-score-highlight %s" ><span class="merimag-review-score">%s</span></div>', esc_attr( $review_style ), wp_specialchars_decode( esc_attr( $score_highlight ), ENT_QUOTES )  ); ?>
					
				</div>
				<?php echo sprintf('<div class="merimag-score-comment principal-color-color">%s</div>', esc_attr( $score_comment ) ); ?>
			</div>
		</div>
		<?php if( $review_cretirias && count( $review_cretirias ) > 0 ) : ?>
			<?php foreach( $review_cretirias as $k => $review_cretiria ) : ?>
				<?php
					$cretiria_title 	   = isset( $review_cretiria['title'] ) && !empty( $review_cretiria['title'] ) ? $review_cretiria['title'] : sprintf(__('Cretiria %s', 'merimag' ), $k );
					$cretiria_note  	   = isset( $review_cretiria['note'] ) && is_numeric( $review_cretiria['note'] ) && $review_cretiria['note'] <= 10 ? $review_cretiria['note'] / 2 : 0;
					$cretiria_note_percent = number_format( ( $cretiria_note * 100 ) / 5, 0 );
					$cretiria_note_points  = number_format( $cretiria_note * 2, 1 );
					$cretiria_note_stars   = wp_star_rating( array('rating' => $cretiria_note, 'echo' => false) );
					$cretiria_note_display = $cretiria_note;
					$bar_width 			   = sprintf('width:%s%%;', esc_attr($cretiria_note_percent) );
					switch ($review_style) {
						case 'stars':
							$cretiria_note_display = $cretiria_note_stars;
							break;
						case 'points':
							$cretiria_note_display = $cretiria_note_points;
							break;
						case 'percent':
							$cretiria_note_display = sprintf('%s%%', esc_attr($cretiria_note_percent) );
							break;
					} 		   
				?>
				<div class="merimag-review-cretiria general-border-color <?php echo esc_attr($review_style)?>">
					<div class="merimag-review-cretiria-infos  merimag-review-cretiria-<?php echo esc_attr($k)?>">
						<?php echo sprintf('<span class="merimag-review-cretiria-name">%s</span>', esc_attr( $cretiria_title ) ); ?>
						<?php echo wp_specialchars_decode(esc_attr( $cretiria_note_display ), ENT_QUOTES ); ?>
						
					</div>
					
							<div class="merimag-review-cretiria-bar">
								<?php $no_js_css = function_exists('is_amp_endpoint') && is_amp_endpoint() ? sprintf('width:%s%%', esc_attr($cretiria_note_percent) ) : ''; ?>
								<span class="merimag-review-cretiria-bar-note principal-color-background-color" style="<?php echo esc_attr($no_js_css)?>" data-width="<?php echo esc_attr($cretiria_note_percent)?>%"></span>
							</div>
					
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div style="display: none;" itemscope itemtype="https://schema.org/Product">
	  <span itemprop="name"><?php echo esc_attr($review_title)?></span>
	  <div itemprop="description"><?php echo esc_attr($review_summary)?></div>
	  <div itemprop="review" itemscope itemtype="https://schema.org/Review">

	  <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
	  <?php echo esc_html_e('Rating', 'merimag')?>
	  	<meta itemprop="worstRating" content="1"/>
	    <span itemprop="ratingValue"><?php echo esc_attr(number_format($score, 1))?></span> <?php echo esc_html_e('out of', 'merimag')?>
	    <span itemprop="bestRating">5</span> <?php echo esc_html_e('Stars', 'merimag')?>
	    <span itemprop="description"><?php echo esc_attr($score_comment)?></span>
	  </span>
	    <?php echo esc_html_e('By', 'merimag')?>
	    <span itemprop="author" itemscope itemtype="https://schema.org/Person">
	      <span itemprop="name"><?php echo esc_attr($review_author)?></span></span>, <?php echo esc_html_e('Written on', 'merimag')?>
	    <meta itemprop="datePublished" content="<?php echo get_the_date('Y-m-d')?>"><?php echo get_the_date('F j, Y')?>
	    <div itemprop="reviewBody"><?php echo esc_attr($review_summary)?></div>
	    <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
	        <meta itemprop="name" content="<?php echo esc_attr($publisher)?>">
	    </span>
	  </div>
	</div>
    <?php
}
/**
 * Social icons
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_social_icons( $atts = array() ) {
	$theme = isset( $atts['icons_theme'] ) ? $atts['icons_theme'] : '';
	switch ($theme) {
		case 'theme-1':
			$icon_atts['icon_box_style'] = 'simple';
			break;
		case 'theme-2':
			$icon_atts['icon_box_style'] = 'bordered';
			break;
		case 'theme-3':
			$icon_atts['icon_box_style'] = 'background';
			break;
		case 'theme-4':
			$icon_atts['icon_box_style'] = 'bordered with-radius';
			break;
		case 'theme-5':
			$icon_atts['icon_box_style'] = 'background with-radius';
			break;
		case 'theme-6':
			$icon_atts['icon_box_style'] = 'bordered full-circle';
			break;
		case 'theme-7':
			$icon_atts['icon_box_style'] = 'background full-circle';
			break;
		case 'theme-8':
			$icon_atts['icon_box_style'] = 'simple';
			$label_background = true;
			break;
		case 'theme-9':
			$icon_atts['icon_box_style'] = 'background';
			$label_background = true;
			$icon_background  = 'rgba(0,0,0,0.2)';
			break;
		case 'theme-10':
			$icon_atts['icon_box_style'] = 'background full-circle';
			$label_background = true;
			$icon_background  = 'rgba(0,0,0,0.2)';
			break;
	}
	
	$icon_atts['icon_box_size']  = isset( $atts['icon_size'] ) && is_numeric( $atts['icon_size'] ) ? $atts['icon_size'] : 14;

	$icon_atts['inline'] 		 = true;
	$custom_networks 			 = isset( $atts['custom_networks'] ) && $atts['custom_networks'] === true ? true : false;
	$social_networks 			 = isset( $atts['social_links'] ) && is_array( $atts['social_links'] ) && !empty( $atts['social_links'] ) ? $atts['social_links'] : ( defined('FW') && $custom_networks === false ? merimag_get_db_customizer_option('social_links') : array() );
	$class 						 = isset( $atts['centered_icons'] ) && $atts['centered_icons'] === 'yes' ? ' centered-icons ' : '';
	$columns					 = isset( $atts['icons_columns'] ) && ( is_numeric( $atts['icons_columns'] ) || $atts['icons_columns'] === 'flex' || $atts['icons_columns'] === 'one' ) ? $atts['icons_columns'] : '4';
	$icon_atts['flex_size'] 	 = $columns === 'flex' ? false : true;
	$class 						.= $columns !== 'flex' ? ' merimag-social-grid ' : ' merimag-social-icons-shortcode-flex';
	$class 						.= isset( $atts['icons_spacing'] ) && is_string( $atts['icons_spacing'] ) ? sprintf(' %s-spacing ', $atts['icons_spacing'] ) : '';
	$class 						.= isset( $atts['icons_layout'] ) && is_string( $atts['icons_layout'] ) ? sprintf(' %s ', $atts['icons_layout'] ) : ' icons_infos_bellow ';
	$show_count 				 = isset( $atts['show_count'] ) && $atts['show_count'] === 'yes' ? true : false;
	$show_action 				 = isset( $atts['show_action'] ) && $atts['show_action'] === 'yes' ? true : false;
	$show_title 				 = isset( $atts['show_title'] ) && $atts['show_title'] === 'yes' ? true : false;
	$icons_layout 				 = isset( $atts['icons_layout'] ) ? $atts['icons_layout'] : '';
	switch ($columns) {
		case 'one':
			$class .= ' merimag-social-grid-100 ';
			break;
		case '2':
			$class .= ' merimag-social-grid-50 ';
			break;
		case '3':
			$class .= ' merimag-social-grid-33 ';
			break;
		case '4':
			$class .= ' merimag-social-grid-25 ';
			break;
		case '5':
			$class .= ' merimag-social-grid-20 ';
			break;
		case '6':
			$class .= ' merimag-social-grid-16 ';
			break;
	}
	if( empty($social_networks) || !is_array( $social_networks ) ) {
		return;
	}
    ?>
    <div class="merimag-shortcode-container merimag-social-icons-shortcode <?php echo esc_attr($class)?>">
		<?php
			foreach($social_networks as $social_link ) :
				if( $columns === 'flex' ) {
					echo '<div class="merimag-social-icon-item merimag-social-icon-item-flexible">';
				} else {
					echo '<div class="merimag-social-icon-item merimag-social-column ">';
				}
				$network  = isset( $social_link['network'] ) && in_array($social_link['network'], merimag_get_recognized_social_networks( true, 'name') ) ? $social_link['network'] : 'link';
				$data     = merimag_get_recognized_social_networks( false, $network );
				if( $data === false ) {
					continue;
				}
				$link  	  = isset( $social_link['link'] ) && !empty( $social_link['link'] ) ? $social_link['link'] : '#';
				$count    = isset( $social_link['count'] ) && !empty( $social_link['count'] ) && $show_count === true ? $social_link['count'] : false;

				$title    = isset( $social_link['title'] ) && !empty( $social_link['title'] ) ? $social_link['title'] :  ( isset( $data['title'] ) ? $data['title'] : __('Follow us', 'merimag') );
				$name 	  = isset( $data['name'] ) && !empty( $data['name'] ) ? $data['name'] : '';
				$icon 	  = isset( $data['icon'] ) && !empty( $data['icon'] ) !== 'link' ? $data['icon'] : 'fa-link';
				$action   = isset( $data['action'] ) && !empty( $data['action'] ) ? $data['action'] : '';
				$icon_atts['icon']['icon-class'] =  $icon;
				if( isset( $atts['icons_color'] ) && !empty( $atts['icons_color'] ) ) {
					$data['color'] = merimag_validate_color( $atts['icons_color'] ) ? $atts['icons_color'] : $data['color'];
				}
				if( !isset( $label_background ) || $label_background !== true ) {
					$icon_atts['icon_color'] = isset( $data['color'] ) && merimag_validate_color( $data['color'] ) ? $data['color'] : false;
				} else {
					$icon_atts['icon_color'] = isset( $icon_background ) && merimag_validate_color( $icon_background ) ? $icon_background : false;

				}
				echo sprintf( '<a class="merimag-social-icon-link" href="%s" rel="nofollow"></a>', esc_url( $link ) );
				if( isset( $label_background ) && $label_background === true  ) {
					echo sprintf('<span class="merimag-social-icon-content" style="background:%s">', esc_attr($data['color']) );
				} else {
					echo '<span class="merimag-social-icon-content-simple">';
				}
				merimag_get_shortcode_html( 'icon', $icon_atts );
				if( $icons_layout !== 'only_icon' ) {
					echo '<span class="merimag-social-icon-title-count">';
					if( $count ) {
						echo sprintf('<span class="merimag-social-icon-count">%s</span>', is_numeric( $count ) ? esc_attr( number_format($count, 0, ',', ',') ) : esc_attr( $count ) );
					} else {
						$count = $name;
						echo sprintf('<span class="merimag-social-icon-count">%s</span>', esc_attr($name));
					}
					if( $count && $show_title ) {
						echo sprintf('<span class="merimag-social-icon-title">%s</span>', esc_attr($title) );
					}
					echo '</span>';
					if( $show_action && $count ) {
						echo sprintf('<span class="merimag-social-icon-action">%s</span>', esc_attr( $action) );
					}
				}
				echo '</span>';
				echo '</div>';
			endforeach;
		?>
	</div>
    <?php
}
/**
 * Spacing
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_spacing( $atts = array() ) {
	$spacing_height = isset( $atts['spacing_height'] ) && is_numeric( $atts['spacing_height'] ) && $atts['spacing_height'] > 0 && $atts['spacing_height'] < 1080 ? intval( $atts['spacing_height'] ) : 60;	
	$style  = '';
	$style .= sprintf( 'height: %spx;', $spacing_height );
    ?>
    <div class="merimag-shortcode-container merimag-spacing-shortcode">
		<div style="<?php echo wp_specialchars_decode(esc_attr($style), ENT_QUOTES)?>" class="merimag-spacing-container">
		</div>
	</div>
    <?php
}
/**
 * Tabs
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_tabs( $atts = array() ) {
	$tabs 	 	= isset( $atts['tabs'] ) && is_array( $atts['tabs'] ) ? $atts['tabs'] : array();
	$tabs_class = isset( $atts['vertical_tabs'] ) && $atts['vertical_tabs'] === 'yes' ? 'vertical-tabs' : '';
	$tabs_id 	= merimag_uniqid('merimag-tabs-shortcode-');
    ?>
    <div  data-id="<?php echo esc_attr($tabs_id)?>" class="merimag-loader-container"><?php echo merimag_get_spinkit(); ?></div>
	<div   data-id="<?php echo esc_attr($tabs_id)?>" class="merimag-tabs-container">
		<div id="<?php echo esc_attr($tabs_id)?>" class="merimag-tabs-shortcode <?php echo esc_attr($tabs_class)?>">
			<ul class="merimag-tabs-shortcode-list">
				<?php foreach( $tabs as $key => $tab ) : ?>
					<?php $title = isset( $tab['title'] ) && is_string( $tab['title'] ) ? $tab['title'] : __('Tab ', 'merimag') . esc_attr( $key ); ?>
					<li class="principal-color-border-color"><a href="#<?php echo esc_attr($tabs_id)?>-<?php echo esc_attr($key)?>"><?php echo esc_attr($title)?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php foreach( $tabs as $key => $tab ) : ?>
				<div class="merimag-tabs-shortcode-content " id="<?php echo esc_attr($tabs_id)?>-<?php echo esc_attr($key)?>">
					<?php if( isset( $tab['content'] ) && is_string( $tab['content'] ) ) {
						echo do_shortcode($tab['content'] );
					}
					?>
					<div class="clear merimag-clear"></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
    <?php
}
/**
 * Post Categories carousel
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_category_carousel( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'carousel';
	$atts['grid_data'] 	  = $grid_data;
	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'category';

		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Post Categories grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_category_grid( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';

	$atts['grid_data'] 	  = $grid_data;

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'category';
		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Product Categories carousel
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_product_category_carousel( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );

	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'carousel';
	$atts['grid_data'] 	  = $grid_data;
	
	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'product_cat';

		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Product Categories grid
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_product_category_grid( $atts = array() ) {
	$defaults 			  = merimag_get_default_grid_data();
	$grid_data 			  = merimag_get_grid_data( $defaults, $atts );
	$atts['full']  		  = !isset( $atts['section-layout'] ) || empty( $atts['section-layout'] ) || ( isset( $atts['section-layout']) && in_array( $atts['section-layout'], array('full', 'no-layout' ) ) ) ? 'yes' : 'no';
	$atts['fullwidth']    = isset( $atts['fullwidth'] ) && $atts['fullwidth'] === 'yes' ? true : false;
	$atts['block_style']  = 'grid';

	$atts['grid_data'] 	  = $grid_data;

	echo '<div class="merimag-block-container">';

		$atts['post_type'] = 'product_cat';

		merimag_blocks_box( $atts );

	echo '</div>';
}
/**
 * Demo ad
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_demo_ad( $atts = array() ) {
	$size = isset( $atts['size'] ) ? $atts['size'] : '300x250';
	echo merimag_get_demo_ad($size);
}
/**
 * Team
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_team( $atts = array() ) {
	$columns   = isset( $atts['member_columns'] ) && is_numeric( $atts['member_columns'] ) ? $atts['member_columns'] : '1';
	$slider    = isset( $atts['member_sliding'] ) && $atts['member_sliding'] === true ? true : false;
	$show_dots = isset( $atts['member_show_dots'] ) && $atts['member_show_dots'] === true ? true : false;
	$authors   = isset( $atts['authors'] ) && is_array( $atts['authors'] ) ? $atts['authors'] : array();
	$columns = isset( $atts['member_columns'] ) ? $atts['member_columns'] : '1';
	$class 	 = '';
	switch ($columns) {
		case '1':
			$class .= ' merimag-grid ';
			break;
		case '2':
			$class .= ' merimag-grid merimag-grid-50 ';
			break;
		case '3':
			$class .= ' merimag-grid merimag-grid-33 ';
			break;
		case '4':
			$class .= ' merimag-grid merimag-grid-25 ';
			break;
		case '5':
			$class .= ' merimag-grid merimag-grid-20 ';
			break;
	}
	$class 				 	.= isset( $atts['member_sliding'] ) && $atts['member_sliding'] == true ? ' merimag-block-carousel merimag-slick-block merimag-carousel merimag-grid-equal-height ' : '';
	$atts['grid_columns'] 	 = intval( $columns );
	$atts['sliding_columns'] = 1;
	$atts['show_dots'] 		 = true;
	$sliding_data 			 = merimag_get_sliding_data( $atts );
	$slick_data   			 = merimag_array_to_html_attributes( $sliding_data );
	$id 		  			 = merimag_uniqid('merimag-authors-carousel-');
	$color 				     = isset( $atts['member_color'] ) && merimag_validate_color( $atts['member_color'] ) ? $atts['member_color'] : false;
	$style 				     = $color ? sprintf('color:%s', $color) : '';
	?>
	<div style="<?php echo esc_attr($style)?>" id="<?php echo esc_attr($id)?>" <?php echo wp_specialchars_decode(esc_attr($slick_data), ENT_QUOTES)?> class="merimag-shortcode-container merimag-authors-shortcode <?php echo esc_attr($class)?>">
		<?php foreach( $authors as $author ) : ?>
		<div class="merimag-member-item merimag-carousel-item merimag-column">
			<?php
				$member_atts = array_merge( $atts, $author );
				merimag_get_shortcode_html('member', $member_atts );
			?>
		</div>
		<?php endforeach; ?>
	</div>
	<?php
}
/**
 * Video playlist
 *
 * @param array $atts list of params
 * @return void
 */
function merimag_shortcode_video_playlist( $atts = array() ) {
	$items = isset( $atts['items'] ) && is_array( $atts['items'] ) ? $atts['items'] : array();
	$playlist_title = isset( $atts['playlist_title'] ) && is_string( $atts['playlist_title'] ) && !empty( $atts['playlist_title'] ) ? $atts['playlist_title'] : __('Video playlist', 'merimag');
	if( !isset( $items[0] ) ) {
		 return;
	}
	$player_id = isset( $atts['block_id'] ) ? $atts['block_id'] : merimag_uniqid('merimag-video-player-');
	?>
	<div id="<?php echo esc_attr($player_id)?>" class="merimag-shortcode-container merimag-video-playlist">
		<?php $player_id .= '-player'; ?>
		<div class="merimag-video-playlist-player">
			<?php
				$video_data = merimag_get_video_data( $items[0] );
				$src = isset( $video_data['id'] ) ? $video_data['id'] : false;
				$provider = isset( $video_data['provider'] ) ? $video_data['provider'] : 'self';
				$type = isset( $video_data['type'] ) ? $video_data['type'] : '';
				if( $provider !== 'self' && isset( $video_data['id'] ) ) {
					echo sprintf('<div id="%s" class="merimag-video-player" data-plyr-provider="%s" data-plyr-embed-id="%s"></div>', esc_attr( $player_id ), esc_attr( $provider ), esc_attr( $src ) );
				} else {
					echo sprintf('<video id="%s" class="merimag-video-player" playsinline controls><source src="%s" type="%s" /></video>', esc_attr( $player_id ), esc_attr( $src ), esc_attr( $type ) );
				}
			?>
		</div>
		<div class="merimag-video-playlist-list">
			<div class="merimag-video-playlist-title principal-color-background-color">
				<span class="merimag-playlist-icon icofont-ui-video-play"></span>
				<span class="merimag-video-playlist-infos">
					<span class="merimag-playlist-title  h6-title"><?php echo esc_attr($playlist_title)?></span>
					<span class="merimag-playlist-count"><span data-player="<?php echo esc_attr($player_id)?>" class="merimag-playlist-current">1</span> / <?php echo esc_attr(count($items))?></span>
				</span>
			</div>
			<div class="merimag-video-playlist-items">
				<?php foreach ($items as $k => $item) : ?>
				<?php 
					$video_data = merimag_get_video_data( $item );
					$src = isset( $video_data['id'] ) ? $video_data['id'] : false;
					$provider = isset( $video_data['provider'] ) ? $video_data['provider'] : 'self';
					$video_title = isset( $video_data['title'] ) ? $video_data['title'] : sprintf('%s #%s', __('Video', 'merimag'), $k + 1 );
					$thumbnail = isset( $video_data['thumbnail'] ) && !empty( $video_data['thumbnail'] ) ? $video_data['thumbnail'] : false;
					$duration = isset( $video_data['duration'] ) && !empty( $video_data['duration'] ) ? $video_data['duration'] : false;
					$type = isset( $video_data['type'] ) ? $video_data['type'] : '';
					$class = $k === 0 ? 'selected-video' : '';
				?>
				<div data-index="<?php echo esc_attr($k + 1)?>" data-player="<?php echo esc_attr($player_id)?>" data-type="<?php echo esc_attr($type)?>" data-provider="<?php echo esc_attr($provider)?>" data-src="<?php echo esc_attr($src)?>" class="merimag-video-playlist-item <?php echo esc_attr($class)?>">
					<?php $title = isset( $item['title'] ) && is_string( $item['title'] ) && !empty( $item['title'] ) ? $item['title'] : $video_title; ?>
					<?php if( $thumbnail ) : ?>
					<div class="merimag-video-playlist-thumb" style="background-image: url(<?php echo esc_url($thumbnail)?>);"><i class="icofont-ui-play video-play"></i><i class="icofont-ui-pause video-pause"></i></div>
					<?php endif; ?>
					<?php if( $provider === 'self' ) : ?>
					<div class="merimag-video-playlist-thumb">
						<video preload="metadata" src="<?php echo esc_attr($src)?>"><i class="icofont-ui-play video-play"></i><i class="icofont-ui-pause video-pause"></i></video>
					</div>
					<?php endif; ?>
					<div class="merimag-video-playlist-item-infos">
						<span data-ellipsis="2" class="merimag-video-playlist-item-title"><?php echo esc_attr(merimag_substr($title, 0, 37))?></span>
						<?php if( $duration ) : ?>
							<span class="merimag-video-playlist-item-duration"><?php echo esc_attr($duration)?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}
/**
 * Get shortcode css args
 *
 * @param string $shortcode name
 * @return array list of arguments
 */
function merimag_get_shortcode_css_args( $shortcode ) {

	switch ($shortcode) {
		case 'mailchimp':
			$args = array(
				'color' => array(
					'color' => '.merimag-mailchimp-call-for-subscribe, .yikes-mailchimp-container, input, .merimag-mailchimp-title',
					'border-color' => 'input',
					'merimag_get_buttons_css' => false,
				),
				'icon_color' => array(
					'color' => '.merimag-mailchimp-icon',
				),
				'title_color' => array(
					'color' => '.merimag-mailchimp-title',
				),
			);
			break;
		case 'image-box':
		case 'action':
		case 'button':
			$args = array(
				'button_text_color' => array(
					'color' => '.merimag-awesome-button',
				),
				'button_text_color_hover' => array(
					'color' => '.merimag-awesome-button:hover',
				),
			);
			break;
		case 'accordion':
			$args = array(
				'color' => array(
					'color' => '.merimag-accordion-shortcode-title, .merimag-accordion-shortcode-content',
					'border-color' => '.merimag-accordion-shortcode-title, .merimag-accordion-shortcode-content',
				),
				'title_color' => array(
					'color' => '.merimag-accordion-shortcode-title',
					'border-color' => '.merimag-accordion-shortcode-title',
				),
			);
			break;
		case 'video-playlist':
		case 'special-heading':
			$args = array(
				'principal_color' => array(
					'merimag_get_principal_color_css' => false,
				)
			);
			break;
		case 'video':
			$args = array(
				'controls_color' => array(
					'background-color' => '.plyr .plyr__control.plyr__tab-focus, .plyr .plyr__control:hover, .plyr .plyr__control[aria-expanded=true], .plyr__control--overlaid',
					'color' => '.plyr--full-ui input[type=range]'
				),
			);
			break;
		case 'image-box':
		case 'multi-buttons':
		case 'action':
			$args = array(
				'shortcodes' => array(
					'button' => 'buttons',
				),
			);

			break;
		case 'custom-tiled':
		case 'custom-grid':
		case 'custom-carousel':
			$args = array(
				'shortcodes' => array(
					'button' => array( 'slug' => 'button', 'data' => 'elements' ),
				),
			);
			break;

	}
	return isset( $args ) ? $args : false;
}
/**
 * Get shortcode css args
 *
 * @param string $shortcode name
 * @return array list of arguments
 */
function merimag_get_shortcode_css_parse( $shortcode, $atts, $echo = false ) {
	$args 		 = merimag_get_shortcode_css_args( $shortcode );
	$css 		 = '';
	$block_id 	 = isset( $atts['block_id'] ) ? $atts['block_id'] : false;
	$selector 	 = '#' . $block_id;

	if( is_array( $args ) ) {
		foreach( $args as $attribute => $properties ) {
			if( $attribute === 'all' ) {
				foreach( $properties as $function => $selectors ) {

					if( function_exists($function) ) {
						try {
							$css .= $function( $atts, $selector );
						} catch (Exception $e) {
							
						}
					}
				}
			} elseif( $attribute === 'shortcodes' ) {

				foreach( $properties as $shortcode => $inner_attribute ) {
					if( is_array( $inner_attribute ) && isset( $inner_attribute['slug'] ) && isset( $inner_attribute['data'] ) ) {
						if( isset( $atts[$inner_attribute['data']] ) && is_array( $atts[$inner_attribute['data']] ) ) {
							foreach( $atts[$inner_attribute['data']] as $k => $shortcode_atts ) {
								$atts[$inner_attribute['data']][$k]['block_id'] = $block_id . '-' . $k . '-' . $inner_attribute['slug'];
								$css .= merimag_get_shortcode_css_parse( $shortcode, $atts[$inner_attribute['data']][$k] );
							}
						}
					} else {
						
						if( isset( $atts[$inner_attribute] ) && is_array( $atts[$inner_attribute] ) ) {
							foreach( $atts[$inner_attribute] as $k => $shortcode_atts ) {
								$atts[$inner_attribute][$k]['block_id'] = $block_id . '-' . $k;
								$css .= merimag_get_shortcode_css_parse( $shortcode, $atts[$inner_attribute][$k] );
							}
						}
					}
					
				}
			} else {
				if( isset( $atts[$attribute] )) {
					foreach( $properties as $property => $selectors ) {
						if( !function_exists($property) ) {
							if( strpos($property, 'color' ) > 0 && merimag_validate_color( $atts[$attribute] ) ) {
								$selectors  = $selector . ' ' . $selectors;
								$selectors  = str_replace(',', ',' . $selector, $selectors);
								$css .= sprintf('%s { %s: %s }', esc_attr( $selectors ), esc_attr( $property ), $atts[$attribute] );
							} elseif( strpos($property, 'size' ) > 0 && is_numeric( $atts[$attribute] ) ) {
								$selectors  = strpos($selectors, ',') > 0 ? $selector . ' ' . $selectors : $selector . ' ' . $selectors . ':not(.slabtext)';
								$selectors  = str_replace(',', ':not(.slabtext),' . $selector, $selectors);
								$css .= sprintf('%s { %s: %spx }', esc_attr( $selectors ), esc_attr( $property ), $atts[$attribute] );
							} else {
								$selectors  = $selector . ' ' . $selectors;
								$selectors  = str_replace(',', ',' . $selector, $selectors);
							
								$css .= sprintf('%s { %s: %s }', esc_attr( $selectors ), esc_attr( $property ), $atts[$attribute] );
							}
						} else {
							try {
								$css .= $property( $atts[$attribute], $selector );
							} catch (Exception $e) {
								
							}
						}
					}
				}
			}
		}
	}
	if( $echo === true ) {
		merimag_render_css( $css );
	} else {
		return $css;
	}

}