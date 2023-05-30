<?php 
	$enable_main_menu = merimag_get_db_customizer_option('enable_main_menu', 'yes'); 
	$header_spacing = merimag_get_db_customizer_option('header_spacing', 'medium' );


?>
<div class="merimag-full-section ">
	
	<header class="merimag-site-header-container no-shadow merimag-full-section principal-color-border-top-color">
		<div class=" merimag-site-header merimag-all-menus-header"">
			
			<?php merimag_news_ticker('before_header'); ?>
			<div class="merimag-header-content merimag-custom-header-content merimag-site-header-content general-border-color header-spacing-<?php echo esc_attr( $header_spacing )?>">
				<div class="site-content-width merimag-block-logo-container merimag-header-content-part merimag-full-section-content">
						   <?php merimag_get_logo(); ?>
						<div class="merimag-all-menus merimag-menu-header">
						   
						    <?php include_once( locate_template( 'includes/headers/components/secondary-menu.php') ); ?>
						    <?php $only_content = true ?>
							<?php include(locate_template( 'includes/headers/components/main-menu.php') ); ?>
						</div>
				</div>
			</div>
			<?php merimag_news_ticker('after_header'); ?>
		</div>
	</header>
</div>