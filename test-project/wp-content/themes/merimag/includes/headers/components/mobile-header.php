<?php
	$stacked_icons 	= merimag_get_db_customizer_option( 'mobile_header_stacked_icons');
	$stacked_icons  = $stacked_icons === 'yes' ? true : false;
	$header_search  = merimag_get_db_customizer_option( 'mobile_header_search', 'icon');
	$stacked_class  = $stacked_icons === true ? 'merimag-stacked-icon  principal-color-background-color' : 'merimag-header-icon';
	$class 		    = $stacked_icons === true ? 'stacked-icons' : '';
	$center_logo 	= merimag_get_db_customizer_option('mobile_header_center_logo');
	$mobile_menu  	= true;
	
	
?>
<header class="merimag-mobile-header-container principal-color-border-top-color merimag-full-section">
	<div class="merimag-site-header merimag-mobile-header">

		
		<?php include( locate_template( 'includes/headers/components/secondary-menu.php') ); ?>
		<?php include_once( locate_template( 'includes/headers/components/mobile-sidebar.php') ); ?>
		<div class="merimag-header-content merimag-site-header-content merimag-main-navigation-typgraphy general-border-color">

			<div class="site-content-width merimag-header-content-part merimag-mobile-header-content <?php echo esc_attr( $class ) ?> merimag-spaced-flex merimag-full-section-content">
				<a href="#" data-id="merimag-mobile-menu-sidebar" class="merimag-mobile-menu-opener merimag-sidebar-opener menu-item">
					<span class="<?php echo esc_attr( $stacked_class ) ?>"><i class="icon-menu icon-navigation-menu"></i></span>
				</a>
				<?php merimag_get_logo('mobile'); ?>
				<?php merimag_header_content('mobile_header'); ?>
			</div>
		</div>
		
	</div>
</header>
