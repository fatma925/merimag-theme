<?php if (!defined('FW')) die('Forbidden');
/**
 * @var WP_Post $item
 * @var string $title
 * @var array $attributes
 * @var object $args
 * @var int $depth
 */
$is_header = isset( $args->is_header ) && $args->is_header === true ? true : false;
	if (
		( fw()->extensions->get('megamenu')->show_icon()
		&&
		($icon = fw_ext_mega_menu_get_meta($item, 'icon')) ) 

	) {
		
		if (empty($title )) {
			$title = '<span class="menu-item-content"><span class="menu-item-icon ' . esc_attr( $icon ) . '"></span></span>';
		} else {
			$title = '<span class="menu-item-content"><span class="menu-item-icon ' . esc_attr( $icon ) . '"></span><span class="menu-item-title">' . esc_attr( $title ) . '</span></span>';
		}
		
	} else {
		$title = '<span class="menu-item-content"><span class="menu-item-title">' . esc_attr( $title ) . '</span></span>';
	}


echo esc_attr( $args->before );
echo wp_specialchars_decode( esc_attr( fw_html_tag('a', $attributes, $args->link_before . $title . $args->link_after) ), ENT_QUOTES );
echo esc_attr( $args->after );