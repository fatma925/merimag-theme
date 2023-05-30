<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package merimag
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="page-builder-template-content merimag-full-section">
		<?php the_content(); ?>
		<div class="clear merimag-clear"></div>
	</div><!-- .entry-content -->
	<div class="merimag-full-section">
		<div class="merimag-full-section-content site-content-width">
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'merimag' ),
					'after'  => '</div>',
				) );
			?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
