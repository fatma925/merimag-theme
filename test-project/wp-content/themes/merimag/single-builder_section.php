<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<?php $post_type = get_post_type(); ?>
<body id="site-body" <?php body_class(); ?>>
<div class="merimag-content-container">
	<div id="content" class="merimag-site-content content-sidebar-container site-content-width  page-builder-template">
		<div id="primary" class="merimag-builder-container">
			<main id="main" class="merimag-site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				the_content();


			endwhile; // End of the loop.
			?>

			</main><!-- #main -->
		</div><!-- #primary -->
		<div class="merimag-clear"></div>
	</div><!-- #content-container -->
</div><!-- #content -->
<?php wp_footer(); ?>
</body>
</html>