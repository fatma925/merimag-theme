<?php
/**
 * Template Name: Builder Template
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();
$page_layout = merimag_get_page_layout();
$id = get_the_ID();
?>
<div class="merimag-content-container">
	<div id="content" class="merimag-site-content content-sidebar-container site-content-width <?php echo esc_attr($page_layout)?> page-builder-template">
		<div id="primary" class="merimag-builder-container">
			<main id="main" class="merimag-site-main">

			<?php
				if( have_posts() ) {
					while( have_posts()) {
						the_post();
						the_content();
					}
				}
			?>

			</main><!-- #main -->
		</div><!-- #primary -->
		<div class="merimag-clear"></div>
	</div><!-- #content-container -->
</div><!-- #content -->
<?php
get_footer();