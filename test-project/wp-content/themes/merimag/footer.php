<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package merimag
 */
$post_type = get_post_type();
?>	
	<?php if(!(is_single() && $post_type === 'post') && !is_page_template('page-builder.php')) : ?>
			<div class="merimag-clear"></div>
		</div><!-- #content-container -->
	</div><!-- #content -->
	<?php endif; ?>
	<?php merimag_get_builder_section('before_footer'); ?>
	<?php do_action('merimag_before_footer');  ?>
	<?php merimag_get_ad('before_footer'); ?>
	<div class="merimag-footer-wrapper">
		<?php merimag_get_footer(); ?>
	</div>
	
	<a href="#" class="merimag-back-to-top"></a>

	<?php do_action('merimag_after_footer');  ?>	
</div><!-- #page -->
<div class="merimag-sidebar-overlay"></div>
<?php
wp_footer(); ?>
</body>
</html>
