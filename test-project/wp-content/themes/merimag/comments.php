<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package merimag
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
$block_title_style = merimag_get_block_title_style();
?>

<div id="comments" class="comments-area merimag-general-padding general-border-color">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title block-title-wrapper <?php echo esc_attr($block_title_style)?>">
			<span class="block-title"><?php comments_number(); ?></span>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list commentlist">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'callback' => 'merimag_html5_comment',
			) );
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'merimag' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().
	
	comment_form(array(
		'title_reply_before' => sprintf('<div class="block-title-wrapper comment-reply-title %s"><span class="block-title">', $block_title_style ),
	    'title_reply_after' => '</span></div>',
	));
	?>

</div><!-- #comments -->
