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


			<?php
			while ( have_posts() ) :
				the_post();

				the_content();


			endwhile; // End of the loop.
			?>

<?php wp_footer(); ?>
</body>
</html>