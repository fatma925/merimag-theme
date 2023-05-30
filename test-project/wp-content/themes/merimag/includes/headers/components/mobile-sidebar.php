<?php 
	$show_social_icons = merimag_get_db_customizer_option('mobile_menu_social', 'yes');
	$show_logo  = merimag_get_db_customizer_option('mobile_menu_logo', 'yes');
	$mobile_menu_search = merimag_get_db_customizer_option('mobile_menu_search', 'yes');
?>
<div id="merimag-mobile-menu-sidebar" class="merimag-mobile-menu-sidebar merimag-scroll-bar merimag-sidebar" data-side="left">
	<div class="merimag-mobile-menu-sidebar-header general-border-color">
		
			
		<a data-id="merimag-mobile-menu-sidebar" href="#" class="merimag-sidebar-close"><i class="icofont-close"></i></a>
	</div>
	<div class="merimag-mobile-sidebar-content">
	<?php if( $show_logo === 'yes' ) : ?>
		<div class="merimag-mobile-menu-logo general-border-color">
		<?php merimag_get_logo(); ?>
		</div>
	<?php endif; ?>
	
	<div class="merimag-mobile-sidebar-menu horizontal-menu">
		<?php
		 merimag_wp_nav_menu( array(
			'theme_location' => 'mobile-menu',
			'fallback_cb' 	 => '__return_false',
			'is_header' => false,
			'mobile_sidebar' => true,
		) );
		?>
	</div>
	<?php if( $mobile_menu_search === 'yes' ) : ?>
		<div class="merimag-mobile-menu-search general-border-color">
		    <?php echo get_search_form(); ?>
		</div>
	<?php endif; ?> 
	<div class="merimag-mobile-sidebar-menu flex-menu">
		<?php
		 merimag_wp_nav_menu( array(
			'theme_location' => 'secondary-menu',
			'fallback_cb' 	 => '__return_false',
			'is_header' => false,
			'mobile_sidebar' => true,
		) );
		?>
	</div>
	<?php if( $show_social_icons === 'yes' ) : ?>
		<div class="merimag-mobile-menu-social">
		    <?php merimag_get_shortcode_html('social-icons', apply_filters('mobile_menu_social_icons_args', array('icons_theme' => 'theme-7', 'icon_size' => 4, 'centered_icons' => 'yes', 'icons_columns' => 'flex', 'icons_layout' => 'only_icon', 'icons_color' => merimag_get_principal_color() ) ) ); ?>
		</div>
	<?php endif; ?>
	</div>
</div>
