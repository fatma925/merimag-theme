<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package merimag
 */

get_header();
?>

	<div id="primary" class="site-content-area merimag-simple-page site-content-area-style">
		<main id="main" class="merimag-site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
$sidebar_for = class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_woocommerce()  ) ? 'shop' : '';
$sidebar_for = function_exists('is_bbpress') && is_bbpress() ? 'forums' : $sidebar_for;

get_sidebar($sidebar_for);
get_footer();
