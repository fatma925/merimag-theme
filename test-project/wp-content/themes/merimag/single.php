<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package merimag
 */
get_header();
$page_layout 		  = merimag_get_page_layout();
$single_template_args = merimag_get_post_template_args();

extract( $single_template_args );
$title_after_image 	  = isset( $single_title_after_image ) && $single_title_after_image === true ? true : false;
$top_sidebar = isset( $single_top_sidebar ) && $single_top_sidebar === true ? true : false;
$full_image  = isset( $single_full_image ) && $single_full_image === true ? true : false;
$marged_infos = isset( $single_marged_infos ) && $single_marged_infos === true ? true : false;
$stretched_image = isset( $single_stretched_image) && $single_stretched_image === true ? true : false;
$absolute_infos = isset( $single_absolute_infos ) && $single_absolute_infos === true ? true : false;
$single_header_content_class = $stretched_image !== true && $top_sidebar === false ? ' merimag-full-image' : 'merimag-stretched-image';
$single_header_content_class .= $top_sidebar === false && $stretched_image === false  ? ' merimag-full-section-content site-content-width ' : '';

$single_header_container_class = isset( $single_dark_media_container ) && $single_dark_media_container === true ? 'dark-single-header white-text' : '';
$single_template_class 		   = $title_after_image === true ? ' merimag-single-title-after-image ' : '';

$single_template_class	.= $absolute_infos === true ? ' merimag-single-absolute-infos ' : ' merimag-single-normal-infos ';
$single_template_class .= $marged_infos === true ? ' merimag-single-marged-infos ' : ''; 
$single_template_class .= $full_image === false && $stretched_image === false ? ' merimag-single-content-img ' : '';
$single_template_class .= $full_image === true && $stretched_image === false  ? ' merimag-single-full-image ' : '';
$single_template_class .= $stretched_image === true && $full_image === false ? ' merimag-single-stretched-image ' : '';
$title_full = isset( $single_title_full ) && $single_title_full === true ? true : false;
$enable_breadcrumbs = merimag_get_db_customizer_option('enable_breadcrumbs');
$single_template_class  .= isset( $single_absolute_infos_center ) && $single_absolute_infos_center === true ? ' absolute-center-center' : '';
?>

<div class="merimag-single-template-container <?php echo esc_attr($single_template_class)?>">
	<?php if( isset( $single_full_image ) && $single_full_image === true ) : ?>
	<div class="merimag-single-header merimag-fitvids merimag-full-section <?php echo esc_attr($single_header_container_class)?>">
		<?php if( $absolute_infos === true && $full_image === true && $stretched_image === true && $enable_breadcrumbs !== 'no' ) : ?>
			<div class="merimag-breadcrumb">
				<div class="merimag-full-section-content site-content-width white-text">
					<?php merimag_breadcrumb( false ); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if( $title_full === true && $title_after_image === false && $top_sidebar === false ) : ?>
		<div class="merimag-single-header-title">
			<div class="merimag-full-section-content merimag-single-full-title merimag-single-header-title-content site-content-width">
				<?php merimag_entry_header(); ?>
			</div>
		</div>
		<?php endif; ?>
		<?php 
			if( $top_sidebar ) {
				echo sprintf('<div class="content-sidebar-container site-content-width merimag-full-section-content content-sidebar"><div class="site-content-area site-content-area-video">' );
			}
		?>
		<?php if( $title_full === true && $title_after_image === false && $top_sidebar === true ) : ?>
			<div class="merimag-single-header-title">
				<div class="merimag-single-full-title merimag-single-header-title-content">
					<?php merimag_entry_header(); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="merimag-single-header-content <?php echo esc_attr($single_header_content_class)?>">
			
			<?php merimag_get_post_featured_media(); ?>
			<?php if( isset( $single_absolute_infos ) && $single_absolute_infos === true ) : ?>
			<?php 
				
				$title_overlay_class = isset( $single_marged_infos ) && $single_marged_infos === true ? '  site-content-area-style ' : ' white-text';
				$title_overlay_class .= isset( $single_marged_infos ) && $single_marged_infos === true && $page_layout === 'content' ? ' full' : '';
				$overlay_container_class = isset( $single_stretched_image ) && $single_stretched_image === true ? ' merimag-full-section-content ' : '  ';
			?>
			<div class="merimag-single-header-title-overlay site-content-width <?php echo esc_attr($overlay_container_class)?>">
				<div class="merimag-single-header-title-overlay-content merimag-general-padding <?php echo esc_attr($title_overlay_class)?>">
					<?php 
						$copyrights = merimag_get_db_post_option(get_the_ID(), 'featured_image_copyrights');
						if( isset( $single_marged_infos ) && $single_marged_infos === true && $copyrights ) {
							echo sprintf('<span class="merimag-image-copyrights">&copy; %s</span>', esc_attr( $copyrights ) );
						}
					?>
					<?php merimag_entry_header(); ?>
					
				</div>
			</div>
			<?php endif; ?>
			
		</div>
		<?php if( isset( $single_show_arrow ) && $single_show_arrow === true ) : ?>
						<a href="#content" class="merimag-single-header-nav-arrow"><i class="fa fa-angle-down"></i></a>
					<?php endif; ?>
		<?php if( $title_full === true && $title_after_image === true && $top_sidebar === false  ) : ?>
		<div class="merimag-single-header-title">
			<div class="merimag-full-section-content merimag-single-full-title merimag-single-header-title-content site-content-width">
				<?php merimag_entry_header(); ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if( $top_sidebar === true ) : ?>
			</div>
			<div class="merimag-widget-area">
				<?php dynamic_sidebar( 'video-template-sidebar' ); ?>
			</div>
			<div class="clear merimag-clear"></div>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php $content_class = isset( $single_marged_infos ) && $single_marged_infos === true ? 'site-content-no-top-padding' : ''; ?>
	<div class="merimag-content-container merimag-full-section">
		<div id="content" class="merimag-site-content merimag-full-section-content content-sidebar-container site-content-width <?php echo esc_attr($page_layout)?> <?php echo esc_attr($content_class)?>">
		    <div id="primary" class="site-content-area site-content-area-style">
				<main id="main" class="merimag-site-main">

					<?php
						while ( have_posts() ) :
							the_post();

							if( $absolute_infos === false && $title_after_image === false && $title_full === false ) {
								merimag_entry_header();
							}

							if( $full_image === false && $absolute_infos === true  ) : ?>
								<div class="merimag-single-header alignfull">
									<div class="merimag-single-header-content ">
										<?php merimag_get_post_featured_media(); ?>
										<div class="merimag-single-header-title-overlay">
											<div class="merimag-single-header-title-overlay-content-normal merimag-general-padding with-background white-text">
												<?php merimag_entry_header(); ?>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<?php if( $full_image === false && $absolute_infos === false   ) : ?>
								<div class="merimag-single-header alignfull">
									<div class="merimag-single-header-content">
										<?php merimag_get_post_featured_media(); ?>
									</div>
								</div>
							<?php endif;

							if(  $absolute_infos === false && $title_after_image === true && $title_full === false ) {
								merimag_entry_header();
							}
							merimag_get_builder_section('before_post_content');
							get_template_part( 'template-parts/content', get_post_type() );
							merimag_get_builder_section('after_post_content');
							?>
							<!-- -Author box -->
							<div class="merimag-author-box-wrapper">
								<?php merimag_author_box(); ?>
							</div>
							<!-- /Author box -->
							<!-- -Next / prev -->
							<div class="merimag-next-prev-wrapper">
								<?php merimag_next_prev(); ?>
							</div>
							<!-- /Next / prev -->
							<!-- -Related posts -->
							<?php merimag_get_builder_section('before_related_posts'); ?>
							<div class="merimag-related-posts-wrapper">
								<?php merimag_related_posts(); ?>
							</div>
							<?php merimag_get_builder_section('after_related_posts'); ?>
							<!-- /Related posts -->
							<!-- -Post subscribe -->
							<div class="merimag-post-subscribe-wrapper">
								<?php merimag_post_subscribe(); ?>
							</div>
							<!-- -Post subscribe -->
							<?php

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

							?>

							<div class="merimag-read-also-wrapper">
								<?php merimag_read_also(); ?>
							</div> 
							<?php merimag_get_builder_section('after_read_also'); ?>
						<?php
						endwhile; // End of the loop.
					?>

				</main><!-- #main -->
			</div><!-- #primary -->
			<?php get_sidebar(); ?>
		    <div class="merimag-clear"></div>
	    </div><!-- #content -->
	</div><!-- #content-container -->
</div><!-- #single-template-container -->
<?php merimag_toc(); ?>
<?php get_footer(); ?>