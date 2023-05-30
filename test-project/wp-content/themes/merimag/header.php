<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package merimag
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<link rel="profile" href="//gmpg.org/xfn/11" />
	<link rel='dns-prefetch' href='//fonts.googleapis.com' />
	<link rel="preconnect" href="https://fonts.gstatic.com/" />
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
	<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if( is_singular() ) : ?>
	<meta property="og:title" content="<?php echo get_the_title(); ?>">
	<meta property="og:description" content="<?php echo get_the_title(); ?>">
	<meta property="og:type" content="article">
	<meta property="og:url" content="<?php echo esc_url(get_the_permalink()); ?>">
	<meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo get_the_title(); ?>">
	<meta name="twitter:description" content="<?php echo get_the_title(); ?>">
	<meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
	<?php else: ?>
	<?php
		$logo  = merimag_get_db_customizer_option('logo');
		$logo  = isset( $logo['url'] ) ? $logo['url'] : '';
	?>
	<meta property="og:title" content="<?php echo is_front_page() ? bloginfo('name') : trim(wp_title(false, false)); ?>">
	<meta property="og:description" content="<?php echo is_front_page() ? bloginfo('description') : trim(wp_title(false, false)); ?>">
	<meta property="og:type" content="article">
	<meta property="og:url" content="<?php echo esc_url(home_url( $wp->request )); ?>">
	<meta property="og:image" content="<?php echo esc_url($logo); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo is_front_page() ? bloginfo('name') : trim(wp_title(false, false)); ?>">
	<meta name="twitter:description" content="<?php echo is_front_page() ? bloginfo('description') : trim(wp_title(false, false)); ?>">
	<meta name="twitter:image" content="<?php echo esc_url($logo); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<?php $post_type = get_post_type(); ?>
<body id="site-body" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php $container_layout = merimag_get_container_layout(); $page_layout = merimag_get_page_layout();
$sticky_mobile_header = merimag_get_db_customizer_option('sticky_mobile_header');
$header_class = $sticky_mobile_header !== 'no' ? 'merimag-header-main-sticky' : '';
 ?>
<div id="page" class="merimag-site-container <?php echo esc_attr($container_layout)?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'merimag' ); ?></a>
	<?php merimag_get_ad('before_header'); ?>
	<div class="merimag-header-main principal-color-border-top-color <?php echo esc_attr( $header_class); ?>">
		<?php merimag_get_header();  ?>
	</div>
	<?php merimag_get_ad('after_header'); ?>
	<?php merimag_get_page_header(); ?>
	<?php if(!(is_single() && $post_type === 'post' ) && !is_page_template('page-builder.php')) : ?>
		<div class="merimag-content-container merimag-full-section">
		<div id="content" class="merimag-site-content merimag-full-section-content content-sidebar-container site-content-width <?php echo esc_attr($page_layout)?>">
	<?php endif; ?>

	