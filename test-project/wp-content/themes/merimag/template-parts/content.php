<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package merimag
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	

	<div class="entry-content article-content">
		<?php
			echo '<div class="merimag-share-buttons-bc-wrapper alignwide">'; merimag_get_share_buttons_before_content(); echo '</div>';

			do_action('merimag_single_before_content');
			
			echo '<div class="merimag-article-content">';
			
			the_content();

			echo '<div class="clear merimag-clear"></div>';
			echo '</div>';

			do_action('merimag_single_after_content');

			merimag_tag_source_via();

			echo '<div class="merimag-share-buttons-ac-wrapper alignwide">'; merimag_get_share_buttons_after_content(); echo '</div>';

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'merimag' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
