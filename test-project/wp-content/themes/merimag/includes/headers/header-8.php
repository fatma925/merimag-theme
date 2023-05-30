<?php 
	$enable_main_menu = merimag_get_db_customizer_option('enable_main_menu', 'yes'); 
	$header_spacing = merimag_get_db_customizer_option('header_spacing', 'medium' );


?>
<header class="merimag-site-header-container no-shadow merimag-full-section principal-color-border-top-color">
	<div class="merimag-site-header">
		<?php merimag_news_ticker('before_header'); ?>
		<?php include_once( locate_template( 'includes/headers/components/secondary-menu.php') ); ?>
		<div class="merimag-header-content merimag-custom-header-content merimag-site-header-content general-border-color header-spacing-<?php echo esc_attr( $header_spacing )?>">
			<div class="site-content-width merimag-header-content-part  merimag-spaced-flex merimag-full-section-content">
				<?php merimag_get_logo(); ?>
				<?php merimag_header_content(); ?>
				<?php merimag_get_header_content_ad(); ?>
			</div>
		</div>		
		<?php $bordered_menu = true; ?>
		<?php include(locate_template( 'includes/headers/components/main-menu.php') ); ?>
		<?php merimag_news_ticker('after_header'); ?>
	</div>
</header>