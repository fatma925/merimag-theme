<?php 
	$enable_main_menu = merimag_get_db_customizer_option('enable_main_menu', 'yes'); 
	$header_spacing = merimag_get_db_customizer_option('header_spacing', 'medium' );

?>
<header class="merimag-site-header-container merimag-full-section principal-color-border-top-color">
	<div class="merimag-site-header  merimag-menu-header">
		<?php merimag_news_ticker('before_header'); ?>
		<?php include_once( locate_template( 'includes/headers/components/secondary-menu.php') ); ?>
		<div class="merimag-header-content merimag-custom-header-content merimag-site-header-content merimag-main-navigation-typgraphy general-border-color header-spacing-<?php echo esc_attr( $header_spacing )?>">
			<div class="site-content-width merimag-header-content-part  merimag-main-navigation-wrapper merimag-spaced-flex merimag-full-section-content">
					<?php merimag_get_logo(); ?>
					<div class="merimag-menu-container main-menu-dynamic merimag-menu-header vertical-menu <?php echo esc_attr(merimag_get_menu_effect_class())?>">
					<?php
						if( $enable_main_menu === 'yes' ) {
							 	merimag_wp_nav_menu( array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'menu-1',
								'fallback_cb' 	 => '__return_false',
								'is_header' => true,
							) );
						}
					?>
					</div>
					<?php merimag_header_content('main_menu'); ?>
			</div>
		</div>
		<?php merimag_news_ticker('after_header'); ?>
	</div>
</header>