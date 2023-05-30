<?php if ( ! defined( 'THEME_VERSION' ) ) {
  die( 'Forbidden' );
}
	$icon_url 	 = get_template_directory_uri() . '/assets/images/menu-icon-big.png';
	$theme   	 = wp_get_theme();
	$version 	 = $theme->get('Version');
	$update_data = get_option('external_theme_updates-merimag');
	$new_version = isset( $update_data->update->version ) && version_compare( $update_data->update->version, $version ) === 1 ? $update_data->update->version : false;
?>
<div class="merimag-admin-container">
	<div class="merimag-admin-header">
		<img src="<?php echo esc_url( $icon_url ); ?>">
		<div class="merimag-admin-header-content">
			<h1><?php echo esc_html__('MERIMAG THEME', 'merimag'); ?></h1>
			<p><?php echo esc_html__('VERSION', 'merimag') . ' : ' . esc_attr( $version ); ?></p>

		</div>
		<?php merimag_get_theme_ad(); ?>
	</div>
	<div class="merimag-admin-content">
		<h2><?php echo esc_html__('Welcome to MERIMAG THEME', 'merimag'); ?></h2>
		<p>
			<?php echo esc_html__('Thank your for choosing merimag, Merimag is Multipurpose Magazine, Blog and Shop Wordpress theme, a responsive theme for publishers and website creators, it has all the top features that a wordpress simple or advanced user need, the theme make the customization experience very easy by using the native wordpress live customizer, it have a full support for elementor with a large choice of amazing blocks, let you also use merimag to create amazing stores with full support of woocommerce and a really great extra features and exclusive elementor widgets for that, with regular automatic updates we add new features to let this theme the only theme that you need.', 'merimag'); ?>
				
			</p>
		<?php
			if( !$new_version ) {
				?>
				<div class="notice  notice-success">
					<p><?php echo esc_html__('Latest version installed', 'merimag'); ?> <a class="merimag-check-update" href="#"><?php echo esc_html__('Check for updates', 'merimag'); ?></a></p>
				</div>
				<?php
			} else {
				?>
				<div class="notice  notice-warning">
					<p><?php echo esc_html__('New version available', 'merimag'); ?> <a href="<?php echo esc_url( admin_url( 'themes.php')) ?>"><?php echo esc_html__('Go to themes page', 'merimag'); ?></a></p>
				</div>
				<?php
			}
		?>	
		<hr/>
		<h2><?php echo esc_html__('Get started', 'merimag'); ?></h2>
		<div class="merimag-admin-columns">
			
			<div class="merimag-admin-column">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=tgmpa-install-plugins')) ?>"><i class="fa fa-plug"></i><span><?php echo esc_html__('Install plugins', 'merimag'); ?></span></a>
			</div>
			<div class="merimag-admin-column">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpmdm-import')) ?>"><i class="fa fa-clone"></i><?php echo esc_html__('Install demos', 'merimag'); ?></a>
			</div>
			<div class="merimag-admin-column">
				<a target="_blank" href="<?php echo esc_url( admin_url( 'customize.php')) ?>"><i class="fa fa-magic"></i><?php echo esc_html__('Customize theme', 'merimag'); ?></a>
			</div>
			<div class="merimag-admin-column">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page')) ?>"><i class="fa fa-home"></i><?php echo esc_html__('Create home page', 'merimag'); ?></a>
			</div>
		</div>
		<hr/>
		<h2><?php echo esc_html__('Advanced', 'merimag'); ?></h2>
		<div class="merimag-admin-columns">
			
			<div class="merimag-admin-column">
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=mega_menu')) ?>"><i class="fa fa-bars"></i><span><?php echo esc_html__('Mega menus', 'merimag'); ?></span></a>
			</div>
			<div class="merimag-admin-column">
				<a href="<?php echo esc_url( admin_url( 'post_type=builder_section')) ?>"><i class="fa fa-pencil-square-o"></i><?php echo esc_html__('Builder sections', 'merimag'); ?></a>
			</div>

		</div>
		<hr/>
		<h2><?php echo esc_html__('Need help?', 'merimag'); ?></h2>
		<div class="merimag-admin-columns">
			<div class="merimag-admin-column">
				<a><i class="fa fa-file-text"></i><span><?php echo esc_html__('View documentation', 'merimag'); ?></span></a>
			</div>
			<div class="merimag-admin-column">
				<a><i class="fa fa-users"></i><?php echo esc_html__('Support forum', 'merimag'); ?></a>
			</div>
		</div>
	</div>
</div>