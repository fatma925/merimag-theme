<?php 
	$enable_main_menu = merimag_get_db_customizer_option('enable_main_menu', 'yes'); 
	$header_spacing = merimag_get_db_customizer_option('header_spacing', 'medium' );

			$ticker_position = merimag_get_db_customizer_option('ticker_position');

?>
<div class="merimag-full-section ">
	
	<header class="merimag-site-header-container merimag-full-section principal-color-border-top-color">
		<div class="merimag-block-logo-header merimag-menu-header"">
			
			
			<div class="merimag-header-content merimag-custom-header-content merimag-site-header-content merimag-main-navigation-typgraphy general-border-color header-spacing-<?php echo esc_attr( $header_spacing )?>">
				<div class="site-content-width merimag-full-section-content merimag-block-logo-top-nav">
					<?php include_once( locate_template( 'includes/headers/components/secondary-menu.php') ); ?>
				</div>
				<div class="site-content-width merimag-block-logo-container merimag-header-content-part merimag-main-navigation-wrapper merimag-full-section-content">
						<div class="merimag-block-logo merimag-site-header" >
						   <?php merimag_get_logo(); ?>
						</div>
						<div class="merimag-block-logo-menu">
							<?php if( $ticker_position !== 'hide' ) : ?>
						    <?php merimag_news_ticker(); ?>
							<?php endif; ?>
						    <?php $only_content = true ?>
							<?php include(locate_template( 'includes/headers/components/main-menu.php') ); ?>
						</div>
				</div>
			</div>
			
		</div>
	</header>
</div>