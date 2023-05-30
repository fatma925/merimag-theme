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
	<?php merimag_page_title(); ?>

	<?php merimag_post_thumbnail(); ?>

	<div class="entry-content merimag-article-content">
		<?php
		the_content();
		echo '<div class="clear merimag-clear"></div>';
		wp_link_pages( array(
			'before' => '<div class="page-links">',
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
