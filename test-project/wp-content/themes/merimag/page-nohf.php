<?php
/**
 * Template Name: No header no footer
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>
<body id="site-body" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="content" class="merimag-full merimag-nohf page-builder-template">
		<?php
			if( have_posts() ) {
				while( have_posts()) {
					the_post();
					the_content();
				}
			}
		?>
</div>
<?php
wp_footer(); ?>
</body>
</html>