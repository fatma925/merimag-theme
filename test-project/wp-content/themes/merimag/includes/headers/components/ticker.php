<?php $ticker_title = merimag_get_db_customizer_option('ticker_title', __('Trending news', 'merimag')); $ticker_title = empty( $ticker_title ) ? __('Trending news', 'merimag') : $ticker_title ?>
<div class="merimag-news-ticker-menu">
	<div class="merimag-news-ticker-container custom-menu-item-content">
		<div class="merimag-news-ticker-title  merimag-menu-line-height marged-padded principal-color-background-color ">
			<span class=""><?php echo esc_attr($ticker_title)?><i class="fa fa-caret-right"></i></span>
		</div>
		<div class="merimag-news-ticker-content merimag-menu-line-height">
			<?php
				$atts     = merimag_get_theme_settings_query( 'post', 'ticker');
				$elements = $data['elements'];
				include( locate_template( 'includes/blocks/post/ticker.php' ) );
			?>
	   	</div>
   	</div>
</div>