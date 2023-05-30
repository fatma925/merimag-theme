<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package merimag
 */
$page_layout = merimag_get_page_layout();
if ( ! is_active_sidebar( 'forums-sidebar' ) || $page_layout === 'content' ) {
	return;
}
?>
<aside id="merimag-secondary" class="merimag-widget-area widget-area">
	<div class="merimag-widget-area-content">
		<?php dynamic_sidebar( 'forums-sidebar' ); ?>
	</div>
</aside><!-- #secondary -->
