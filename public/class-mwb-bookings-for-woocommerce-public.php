<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace mwb_bookings_for_woocommerce_public.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public
 */
class Mwb_Bookings_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mbfw_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/css/mwb-bookings-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mbfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-bookings-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'mbfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}
	public function mbfw_add_custom_add_to_cart_button() {
		global $product;
		if ( $product && 'mwb_booking' === $product->get_type() ) {
			require_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/templates/mwb-bookings-for-woocommerce-public-add-to-cart-form.php';
		}
	}
	/**
	 * Return class name for custom product type.
	 *
	 * @param string $classname extended class name to return.
	 * @param string $product_type custom product name. 
	 * @return string
	 */
	public function mbfw_return_custom_product_class( $classname, $product_type ) {
		if ( 'mwb_booking' === $product_type ) {
			$classname = 'WC_Product_Mwb_Booking';
		}
		return $classname;
	}

}
