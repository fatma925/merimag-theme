<?php
	$secondary_menu_enable = merimag_get_db_customizer_option('enable_top_menu' );
	if( isset( $secondary_menu_enable ) && $secondary_menu_enable === 'no' ) {
		return;
	}	
	$menu = isset( $mobile_menu ) && $mobile_menu === true ? 'secondary-menu-mobile' : 'secondary-menu';
	$show_date 		  = merimag_get_db_customizer_option('show_top_menu_date', 'yes');
	$show_social_icons = merimag_get_db_customizer_option('show_top_menu_social_icons', 'yes');
	$show_ticker = merimag_get_db_customizer_option('ticker_position');
	$top_menu_text = merimag_get_db_customizer_option('top_menu_text');
	if( isset( $mobile_menu ) && $mobile_menu === true ) {
		$show_top_menu_mobile = merimag_get_db_customizer_option('show_top_menu_mobile', 'no');
		$show_date = merimag_get_db_customizer_option('show_top_menu_date_mobile', 'no');
		$show_social_icons = merimag_get_db_customizer_option('show_top_menu_social_icons_mobile', 'yes');
		$show_top_menu_text_mobile = merimag_get_db_customizer_option('show_top_menu_text_mobile', 'yes');
	}
	if( isset( $show_top_menu_mobile ) && $show_top_menu_mobile === 'no' ) {
		return;
	}
?>
<nav id="merimag-secondary-navigation" class="merimag-site-navigation  general-border-color merimag-top-navigation merimag-menu <?php echo esc_attr(merimag_get_menu_effect_class('secondary_menu'))?>">
	<div class="merimag-navigation-content merimag-full-section-content site-content-width">
			<?php if( $show_date === 'yes' ) : ?>
			<div class="merimag-menu-line-height  padded">
				<span class="merimag-date-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo esc_attr(date_i18n('l j F Y')); ?></span>
			</div>
			<?php endif; ?>
			<?php if( $top_menu_text && ( !isset( $show_top_menu_text_mobile ) || $show_top_menu_text_mobile === 'yes' ) ) : ?>
			<div class="merimag-menu-line-height merimag-top-menu-text">
				<?php echo wp_specialchars_decode(esc_attr(do_shortcode($top_menu_text))); ?>
			</div>
			<?php endif; ?>
			<?php if( !isset($show_ticker) || $show_ticker === 'in_menu' ) :?>
			<?php merimag_news_ticker(); ?>
			<?php endif; ?>
			<?php if( ( !isset( $show_top_menu_mobile ) || $show_top_menu_mobile === 'yes' ) && ( $show_social_icons === 'yes' && $show_ticker !== 'in_menu' ) ) : ?>
			<div class="merimag-menu-container vertical-menu">
			<?php
				merimag_wp_nav_menu( array(
					'theme_location' => $menu,
					'menu_id'        => $menu,
					'fallback_cb' 	 => '__return_false',
					'is_header' => true,
				) );
			?>
			</div>
			<?php endif; ?>
			
			<?php if( ( !isset( $show_top_menu_mobile ) || $show_top_menu_mobile === 'yes' ) && ( $show_social_icons === 'no' || $show_ticker === 'in_menu' ) ) : ?>
			<div class="merimag-menu-container vertical-menu">
			<?php
				merimag_wp_nav_menu( array(
					'theme_location' => $menu,
					'menu_id'        => $menu,
					'fallback_cb' 	 => '__return_false',
					'is_header' => true,
				) );
			?>
			</div>
			<?php endif; ?>
			<?php if( $show_social_icons === 'yes' ) : ?>
		    <?php echo merimag_get_menu_social()?>
		    <?php endif; ?>
	</div>
</nav><!-- #merimag-site-navigation -->