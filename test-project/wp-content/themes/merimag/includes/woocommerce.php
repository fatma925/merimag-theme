<?php if ( ! defined( 'THEME_VERSION' ) ) {
  die( 'Forbidden' );
}
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package merimag
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function merimag_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'merimag_woocommerce_setup' );
function merimag_wc_get_rating_html( $rating_html, $rating ) { 
    if ( $rating > 0 ) { 
        $rating_html = '<div class="star-rating woocommerce-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'merimag' ), $rating ) . '">'; 
        $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'merimag' ) . '</span>'; 
        $rating_html .= '</div>'; 
    } else { 
        $rating_html = ''; 
    } 
    return $rating_html; 
}
add_filter('woocommerce_product_get_rating_html', 'merimag_wc_get_rating_html', 10, 2 );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function merimag_woocommerce_scripts() {
	wp_enqueue_style( 'merimag-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.min.css' );
	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'merimag-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'merimag_woocommerce_scripts' , 98 );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function merimag_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'merimag_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function merimag_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'merimag_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function merimag_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'merimag_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function merimag_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'merimag_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function merimag_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'merimag_woocommerce_related_products_args' );

if ( ! function_exists( 'merimag_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function merimag_woocommerce_product_columns_wrapper() {
		$columns = merimag_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'merimag_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'merimag_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function merimag_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'merimag_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'merimag_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function merimag_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="site-content-area site-content-area-style">
			<main id="main" class="merimag-site-main" role="main">
			<?php
			if( is_single() )  {
				merimag_breadcrumb();
			}
			
	}
}
add_action( 'woocommerce_before_main_content', 'merimag_woocommerce_wrapper_before' );


/**
 * Add to cart link filter 
 */
function merimag_woocommerce_loop_add_to_cart_link( $link ) {
	return str_replace( '</a>', '<i class="animate-spin icon-spinner1"></i></a>', $link);
}
add_filter('woocommerce_loop_add_to_cart_link', 'merimag_woocommerce_loop_add_to_cart_link' );
if ( ! function_exists( 'merimag_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function merimag_woocommerce_wrapper_after() {
			?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'merimag_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'merimag_woocommerce_header_cart' ) ) {
			merimag_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'merimag_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function merimag_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		merimag_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'merimag_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'merimag_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function merimag_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'merimag' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'merimag' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'merimag_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function merimag_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="merimag-site-header-cart" class="merimag-site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php merimag_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

function merimag_woocommerce_rating_filter_count( $string, $count ) {
	return "<span>({$count})</span>";
}
add_filter('woocommerce_rating_filter_count', 'merimag_woocommerce_rating_filter_count', 10, 2);



function merimag_woocommerce_widget_shopping_cart_button_view_cart() {
    echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button">' . esc_html__( 'View cart', 'merimag' ) . '</a>';
}
function merimag_woocommerce_widget_shopping_cart_proceed_to_checkout() {
    echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button bordered-button">' . esc_html__( 'Checkout', 'merimag' ) . '</a>';
}


add_action( 'woocommerce_widget_shopping_cart_buttons', function(){
    // Removing Buttons
    remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
    remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

    // Adding customized Buttons
    add_action( 'woocommerce_widget_shopping_cart_buttons', 'merimag_woocommerce_widget_shopping_cart_button_view_cart', 10 );
    add_action( 'woocommerce_widget_shopping_cart_buttons', 'merimag_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
}, 1 );

function merimag_get_cart_count() {
	merimag_check_ajax_referer( 'merimag_options', 'nonce' );
	if( class_exists('WooCommerce') ) {
		echo sprintf ( '%d', WC()->cart->get_cart_contents_count() );
	} else {
		echo '0';
	}
	wp_die();
}
add_action( 'wp_ajax_merimag_get_cart_count', 'merimag_get_cart_count' );
add_action( 'wp_ajax_nopriv_merimag_get_cart_count', 'merimag_get_cart_count' );
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
/**
 * Woocommerce mini cart html
 * @param array $args list of arguments
 * @return void
 */
function merimag_woocommerce_mini_cart_html( $args = array() ) {
	if( function_exists('wc_get_template_html') ) {
	    $defaults = array(
	        'list_class' => '',
	    );

	    $args = wp_parse_args( $args, $defaults );

	    wc_get_template_html( 'cart/mini-cart.php', $args );
    }
}


/* This snippet removes the action that inserts thumbnails to products in teh loop
 * and re-adds the function customized with our wrapper in it.
 * It applies to all archives with products.
 *
 * @original plugin: WooCommerce
 * @author of snippet: Brian Krogsard
 */

/**
 * WooCommerce Loop Product Thumbs
 **/
 if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
	function woocommerce_template_loop_product_thumbnail() {
		echo woocommerce_get_product_thumbnail();
	} 
 }
/**
 * WooCommerce Product Thumbnail
 **/
 if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $product;

        $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

        return $product ? '<div class="woocommerce-thumbnail-container">' . $product->get_image( $image_size ) . '</div>' : '';
	}
 }


function merimag_woocommerce_checkout_before_customer_details() {
	echo '<div class="checkout-details">';
}
add_action('woocommerce_checkout_before_customer_details', 'merimag_woocommerce_checkout_before_customer_details');
function merimag_woocommerce_checkout_after_customer_details() {
	echo '</div>';
}
add_action('woocommerce_checkout_after_customer_details', 'merimag_woocommerce_checkout_after_customer_details');
function merimag_woocommerce_checkout_before_order_review() {
	echo '<div class="order-details">';
}
add_action('woocommerce_checkout_before_order_review', 'merimag_woocommerce_checkout_before_order_review');
function merimag_woocommerce_checkout_after_order_review() {
	echo '</div><div class="clear merimag-clear"></div>';
}
add_action('woocommerce_checkout_after_order_review', 'merimag_woocommerce_checkout_after_order_review');

function merimag_woocommerce_after_cart() {
	echo '<div class="clear merimag-clear"></div>';
}
add_action('woocommerce_after_cart', 'merimag_woocommerce_after_cart');
function merimag_woocommerce_before_cart_table() {
	echo '<h2>' . esc_html__('Cart details', 'merimag') . '</h2>';
}
add_action('woocommerce_before_cart_table', 'merimag_woocommerce_before_cart_table');

function merimag_woocommerce_before_shop_loop() {
	echo '<div class="merimag-before-shop-loop general-border-color">';
}
add_action('woocommerce_before_shop_loop', 'merimag_woocommerce_before_shop_loop', 19);

function merimag_woocommerce_filter_sidebar() {
	?>
	<a href="#" data-id="merimag-shop-sidebar" class="merimag-sidebar-opener general-border-color merimag-shop-filter bordered-button" title="<?php esc_html_e('Filter', 'merimag')?>"><i class="icon-sliders"></i></a>
	<div id="merimag-shop-sidebar" class="merimag-shop-sidebar merimag-sidebar" data-position="right">
		<?php dynamic_sidebar( 'shop-filter-sidebar' ); ?>
	</div>
	<?php
}
add_action('woocommerce_before_shop_loop', 'merimag_woocommerce_filter_sidebar', 21);
function merimag_woocommerce_before_shop_loop_end() {
	echo '</div>';
}
add_action('woocommerce_before_shop_loop', 'merimag_woocommerce_before_shop_loop_end', 31);
add_filter('woocommerce_show_page_title', function() {
	return false;
});
function merimag_woocommerce_page_title() {
	merimag_page_title();
}
add_action('woocommerce_archive_description', 'merimag_woocommerce_page_title', 9);

function merimag_responsive_cart_item( $cart_item, $cart_item_key ) {
	$quantity = isset( $cart_item['quantity'] ) && is_numeric( $cart_item['quantity']  ) ? $cart_item['quantity']  : 1;
	$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	echo sprintf( '<div class="merimag-mobile-cart-price">%s x %s</div>', esc_attr( $quantity ), apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) ); // PHPCS: XSS ok.
}
add_action('woocommerce_after_cart_item_name', 'merimag_responsive_cart_item', 10, 2);

function merimag_single_share() {
	merimag_inline_share(array('size' =>'small', 'rounded' => true, 'layout' => 'inline'));
}
add_action('woocommerce_share', 'merimag_single_share');