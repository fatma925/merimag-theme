<?php if (!defined('FW')) die('Forbidden');
{
    /** @internal */
    function _filter_theme_ext_mega_menu_wp_nav_menu_args($args) {
    	
        $args['walker'] = new FW_Ext_Mega_Menu_Custom_Walker();
  
        return $args;
    }
    add_filter('wp_nav_menu_args', '_filter_theme_ext_mega_menu_wp_nav_menu_args');
}