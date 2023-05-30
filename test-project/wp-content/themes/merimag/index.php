<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package merimag
 */
get_header();
?>
	<div id="primary" class="site-content-area site-content-area-style">
		<main id="main" class="merimag-site-main">
			<div class="merimag-box-container">
				<?php merimag_page_title(); ?>
				<div class="merimag-box-content">
					<?php
					if ( have_posts() ) :

						echo '<div class="merimag-main-grid">';

						merimag_get_page_grid();

						echo '</div>';

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
