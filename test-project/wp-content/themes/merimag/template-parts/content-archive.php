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

	<?php merimag_post_thumbnail(); ?>
	<h3><?php the_title(); ?></h3>
	<div class="entry-content">
		<?php
		the_excerpt();

		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
