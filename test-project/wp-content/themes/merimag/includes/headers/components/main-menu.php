<?php

	if( isset( $main_menu_enable ) && $main_menu_enable === 'no' ) {
		return;
	}
	$class = merimag_get_menu_effect_class();
	$content_class = '';
	if( isset( $boxed_main_menu ) && $boxed_main_menu === true ) {
		$class .= ' site-content-width merimag-full-section-content boxed-menu ';
		$content_class .= ' merimag-main-navigation-background ';
	} else {
		$content_class .= isset( $only_content ) && $only_content === true  ? ' boxed-menu ' : ' site-content-width merimag-full-section-content ';
		$class .= ' merimag-main-navigation-background ';
	}
	$inner_class = isset( $bordered_menu ) && $bordered_menu === true ? ' bordered-menu principal-color-border-bottom-color general-border-top-color ' : '';
?>
<nav id="merimag-main-navigation" class="merimag-site-navigation merimag-main-navigation-typgraphy merimag-main-navigation-wrapper general-border-color merimag-main-navigation merimag-menu <?php echo esc_attr($class)?>">
	<div class="merimag-navigation-content <?php echo esc_attr($content_class) ?>">
			<div class="merimag-navigation-inner  merimag-spaced-flex <?php echo esc_attr($inner_class)?>">
				<?php if( isset( $inline_logo) && $inline_logo === true && ( !isset( $sticky_header ) || $sticky_header === false ) ) : ?>
					<div class="merimag-site-branding">
						<?php merimag_get_logo(); ?>
					</div>
				<?php endif; ?>	
				<div class="merimag-menu-container main-menu-dynamic vertical-menu">
				<?php
				merimag_wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
					'fallback_cb' 	 => '__return_false',
					'is_header' => true,
				) );
				?>
				</div>
				<?php 
					if( !isset( $menu_in_header ) || $menu_in_header === false ) {
						merimag_header_content('main_menu');
					}
				?>
			</div>
	</div>
</nav><!-- #merimag-site-navigation -->

