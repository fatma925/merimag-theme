<?php 
	$enable_main_menu = merimag_get_db_customizer_option('enable_main_menu', 'yes'); 
	$header_spacing = merimag_get_db_customizer_option('header_spacing', 'small' );
	$sticky_header_logo = merimag_get_db_customizer_option('sticky_header_display_logo', 'yes');
	$class = merimag_get_menu_effect_class();
?>
<header class="merimag-sticky-header-container merimag-full-section">
	<div class="merimag-sticky-header  merimag-menu-header merimag-site-header">
		<div class="merimag-header-content  merimag-main-navigation-typgraphy general-border-color header-spacing-<?php echo esc_attr( $header_spacing )?>">
			<div class="site-content-width merimag-header-content-part  merimag-spaced-flex merimag-full-section-content">
					<?php if( $sticky_header_logo === 'yes' ) : ?>
					<?php merimag_get_logo('sticky_header'); ?>
					<?php endif; ?>
					<div class="sticky-header-menu merimag-menu-container vertical-menu <?php echo esc_attr($class)?>"></div>
					<?php merimag_header_content('sticky_header'); ?>
			</div>
		</div>
	</div>
</header>