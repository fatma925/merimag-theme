<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package merimag
 */

get_header();
$widget_title_style = merimag_get_block_title_style( 'style-1', 'widget' );
?>

	<div id="primary" class="site-content-area site-content-area-style">
		<main id="main" class="merimag-site-main">
			<section class="error-404 not-found">
				<h1 class="merimag-404 principal-color-background-color merimag-404-404"><span><?php esc_html_e('404', 'merimag') ?><br/></span></h1>
				<h2 class="merimag-404-title principal-color-border-color"><span><?php esc_html_e('Page not found', 'merimag')?></span></h2>
				<div class="merimag-404-search">
					<p class="merimag-404-search"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'merimag' );?></p>
					<?php  get_search_form();  ?>
				</div>
				<div class="page-content">
				<?php if(is_active_sidebar( '404-sidebar' )) : ?>
					<?php dynamic_sidebar( '404-sidebar' ); ?>
				<?php endif; ?>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
