<?php
/**
 * Host Google Fonts Locally
 *
 * Plugin Name: Host Google Fonts Locally
 * Plugin URI:  https://wordpress.org/plugins/host-google-fonts-locally
 * Description: Load fonts from your own local server instead of Google's. GDPR-friendly.
 * Version:     1.0.3
 * Author:      Fonts Plugin
 * Author URI:  https://fontsplugin.com
 * Text Domain: host-google-fonts-locally
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * @package   host-google-fonts-locally
 * @copyright Copyright (c) 2019, Fonts Plugin
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! class_exists( 'Merimag_Host_Google_Fonts_Locally' ) ) {

	/**
	 * Main Host_Google_Fonts_Locally Class
	 */
	class Merimag_Host_Google_Fonts_Locally {

		/**
		 * The local CSS.
		 *
		 * @var string
		 */
		public $local_css;

		/**
		 * All Google Fonts.
		 *
		 * @var array
		 */
		public $google_fonts = array();

		/**
		 * The users font choices.
		 *
		 * @var array
		 */
		public $choices = array();

		/**
		 * Initialize plugin.
		 */
		public function __construct() {
			

			add_action( 'init', array($this, 'hooks'), 97 );
			
		}
		public function hooks() {

			$this->get_choices();

			$this->google_fonts();
		
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_stylesheet' ), 99 );
			
		}
		public function enqueue_stylesheet() {
			$url = $this->build_url();

			wp_enqueue_style( 'merimag-fonts-css', $url, array(), THEME_VERSION);
		}
		/**
		 * Build the local CSS.
		 */
		public function build_css() {
			

			if ( ! $this->has_custom_fonts() ) {
				return;
			}

			$url 		  = $this->build_url();

			$transient_id = 'merimag_local_google_fonts_' . md5( $url );
			$contents     = get_site_transient( $transient_id );

			if ( ! $contents ) {
				// Get the contents of the remote URL.
				$contents = $this->get_remote_url_contents(
					$url,
					array(
						'headers' => array(
							'user-agent' => 'Mozilla/5.0 (X11; Linux i686; rv:21.0) Gecko/20100101 Firefox/21.0',
						),
					)
				);

				if ( $contents ) {
					// Remove blank lines and extra spaces.
					$contents = str_replace(
						array( ': ', ';  ', '; ', '  ' ),
						array( ':', ';', ';', ' ' ),
						preg_replace( "/\r|\n/", '', $contents )
					);

					$contents = $this->use_local_files( $contents );

					// Set the transient for a week.
					set_site_transient( $transient_id, $contents, WEEK_IN_SECONDS );
				}
			}
			if ( $contents ) {
				$this->local_css = wp_strip_all_tags( $contents ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}
		/**
		 * Load fonts list
		 *
		 * @return void
		 */
		public function google_fonts() {
			$this->google_fonts = merimag_get_google_fonts( true );

		}
		/**
		 * Add inline style
		 *
		 * @return void
		 */
		public function enqueue_style() {
			$host_fonts_locally = merimag_get_db_customizer_option('local_host_fonts');
			if( $host_fonts_locally !== 'yes' ) {
				$this->enqueue_stylesheet();
				return;
			}
			echo '<style>';
        	$this->output_css();
        	echo '</style>';
		}
		/**
		 * Gets the remote URL contents.
		 *
		 * @param string $url The URL to retrieve.
		 * @param array  $args An array of arguments for the wp_remote_retrieve_body() function.
		 */
		public function get_remote_url_contents( $url, $args = array() ) {
			$response = wp_remote_get( $url, $args );
			if ( is_wp_error( $response ) ) {
				return array();
			}
			$html = wp_remote_retrieve_body( $response );
			if ( is_wp_error( $html ) ) {
				return;
			}
			return $html;
		}

		/**
		 * Downloads font-files locally and uses the local files instead of the ones from Google's servers.
		 *
		 * @param string $css The CSS with original URLs.
		 * @return string     The CSS with local URLs.
		 */
		public function use_local_files( $css ) {
			preg_match_all( '/https\:.*?\.woff/', $css, $matches );
			$matches = array_shift( $matches );
			foreach ( $matches as $match ) {
				if ( 0 === strpos( $match, 'https://fonts.gstatic.com' ) ) {
					$new_url = $this->download_font_file( $match );
					if ( $new_url ) {
						$css = str_replace( $match, $new_url, $css );
					}
				}
			}
			return $css;
		}

		/**
		 * Download the font file and move it to wp-content/uploads.
		 *
		 * @param string $url The font URL to download.
		 * @return string     The new URL.
		 */
		public function download_font_file( $url ) {
			// Gives us access to the download_url() and wp_handle_sideload() functions.
			require_once ABSPATH . 'wp-admin/includes/file.php';

			$timeout_seconds = 5;

			// Download file to temp dir.
			$temp_file = download_url( $url, $timeout_seconds );

			if ( is_wp_error( $temp_file ) ) {
				return false;
			}

			// Array based on $_FILE as seen in PHP file uploads.
			$file = array(
				'name'     => basename( $url ),
				'type'     => 'font/woff',
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			);

			$overrides = array(
				'test_type' => false,
				'test_form' => false,
				'test_size' => true,
			);

			// Move the temporary file into the uploads directory.
			$results = wp_handle_sideload( $file, $overrides );

			if ( empty( $results['error'] ) ) {
				return $results['url'];
			}

			return false;
		}

		/**
		 * Output the local CSS to load the font(s).
		 */
		public function output_css() {
			echo wp_kses_post( $this->local_css );
		}

		/**
		 * Remove the Google URL.
		 */
		public function dequeue_style() {
			wp_dequeue_style( 'olympus-google-fonts' );
		}

		/**
		 * Get the users font choices.
		 */
		public function get_choices() {
			$fonts = array();
			$fonts = apply_filters('merimag_load_fonts', $fonts );
			$this->choices = $fonts;
		}

		/**
		 * Make the font name safe for use in URLs
		 *
		 * @param string $font The font we are getting the id of.
		 */
		public function get_font_id( $font ) {

			return str_replace( ' ', '+', $font );

		}

		/**
		 * Get the font weights from ID.
		 *
		 * @param string $font_id The font ID.
		 */
		public function get_font_weights( $font_id ) {

			$weights = $this->google_fonts[ $font_id ]['variants'];

			unset( $weights['0'] );

			foreach ( $weights as $key => $value ) {
				$weights[ $key . 'i' ] = $value . ' Italic';
			}

			return $weights;

		}

		/**
		 * Get the font name from ID.
		 *
		 * @param string $font_id The font ID.
		 */
		public function get_font_name( $font_id ) {

			return $this->google_fonts[ $font_id ]['family'];

		}

		/**
		 * Helper to check if the user is using any Google fonts.
		 */
		public function has_custom_fonts() {
			if ( empty( $this->choices ) ) {
				return false;
			}

			return true;

		}

		/**
		 * Remove the fonts the user has chosen not to load.
		 *
		 * @param string $font_id The font ID.
		 * @param string $weights The font weights.
		 */
		public function filter_selected_weights( $font_id, $weights ) {

			unset( $weights['0'] );

			foreach ( $weights as $key => $value ) {
				$weights[ $key . 'i' ] = $value . ' Italic';
			}

			$selected_weights = get_theme_mod( $font_id . '_weights', false );

			if ( ! $selected_weights ) {
				return $weights;
			}
			return array_intersect_key( $weights, array_flip( $selected_weights ) );

		}

		/**
		 * Return the Google Fonts url.
		 */
		public function build_url() {

			$families = array();
			$subsets  = array();

			if ( empty( $this->choices ) ) {
				return;
			}
			$fonts = array_unique( $this->choices );

			foreach ( $fonts as $font_id ) {

				// Check the users choice is a real font.
				if ( is_array( $this->google_fonts ) && array_key_exists( $font_id, $this->google_fonts ) ) {
					$font_id_for_url = $this->get_font_id( $this->google_fonts[ $font_id ]['family'] );

					$weights = $this->filter_selected_weights( $font_id, $this->google_fonts[ $font_id ]['variants'] );

					$families[] = $font_id_for_url . ':' . implode( ',', array_keys( $weights ) );

					$subsets_array = $this->google_fonts[ $font_id ]['subsets'];

					// Build an array of the subsets that need to be loaded.
					foreach ( $subsets_array as $subset ) {

						if ( ! in_array( $subset, $subsets, true ) ) {
							$subsets[] = $subset;
						}
					}
				}
			}

			$query_args = array(
				'family'  => implode( '|', $families ),
				'subset'  => implode( ',', $subsets ),
				'display' => 'swap',
			);
			return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		}

	}

	/**
	 * Initialize Google Fonts Pro.
	 */
	function merimag_host_google_fonts_locally_init() {
		$hgfl = new Merimag_Host_Google_Fonts_Locally();
	}

	add_action( 'after_setup_theme', 'merimag_host_google_fonts_locally_init', 99 );


}
