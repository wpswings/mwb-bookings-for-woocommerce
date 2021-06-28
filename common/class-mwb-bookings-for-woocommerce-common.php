<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/common
 */

/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace mwb_bookings_for_woocommerce_common.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/common
 */
class Mwb_Bookings_For_Woocommerce_Common {
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
	 * @since 1.0.0
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the common side of the site.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_common_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . 'common', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/css/mwb-bookings-for-woocommerce-common.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-bookings-for-woocommerce-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'common', 'mbfw_common_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name . 'common' );
	}

	/**
	 * Registering custom taxonomy for mwb_booking product type.
	 *
	 * @return void
	 */
	public function mbfw_custom_taxonomy_for_products() {
		$labels = array(
			'name'          => _x( 'Booking Costs', 'taxonomy general name', 'mwb-bookings-for-woocommerce' ),
			'singular_name' => _x( 'Booking Costs', 'taxonomy singular name', 'mwb-bookings-for-woocommerce' ),
			'search_items'  => __( 'Search Booking Cost', 'mwb-bookings-for-woocommerce' ),
			'all_items'     => __( 'All Booking Costs', 'mwb-bookings-for-woocommerce' ),
			'view_item'     => __( 'View Booking Cost', 'mwb-bookings-for-woocommerce' ),
			'edit_item'     => __( 'Edit Booking Cost', 'mwb-bookings-for-woocommerce' ),
			'update_item'   => __( 'Update Booking Cost', 'mwb-bookings-for-woocommerce' ),
			'add_new_item'  => __( 'Add New Booking Cost', 'mwb-bookings-for-woocommerce' ),
			'new_item_name' => __( 'New Booking Cost Name', 'mwb-bookings-for-woocommerce' ),
			'not_found'     => __( 'No Booking Cost Found', 'mwb-bookings-for-woocommerce' ),
			'back_to_items' => __( 'Back to Booking Costs', 'mwb-bookings-for-woocommerce' ),
			'menu_name'     => __( 'Booking Costs', 'mwb-bookings-for-woocommerce' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'mwb_booking_cost' ),
			'show_in_rest'      => true,
		);
		register_taxonomy( 'mwb_booking_cost', 'product', $args );
		$labels = array(
			'name'          => _x( 'Booking Services', 'taxonomy general name', 'mwb-bookings-for-woocommerce' ),
			'singular_name' => _x( 'Booking Services', 'taxonomy singular name', 'mwb-bookings-for-woocommerce' ),
			'search_items'  => __( 'Search Booking Service', 'mwb-bookings-for-woocommerce' ),
			'all_items'     => __( 'All Booking Services', 'mwb-bookings-for-woocommerce' ),
			'view_item'     => __( 'View Booking Service', 'mwb-bookings-for-woocommerce' ),
			'edit_item'     => __( 'Edit Booking Service', 'mwb-bookings-for-woocommerce' ),
			'update_item'   => __( 'Update Booking Service', 'mwb-bookings-for-woocommerce' ),
			'add_new_item'  => __( 'Add New Booking Service', 'mwb-bookings-for-woocommerce' ),
			'new_item_name' => __( 'New Booking Service Name', 'mwb-bookings-for-woocommerce' ),
			'not_found'     => __( 'No Booking Service Found', 'mwb-bookings-for-woocommerce' ),
			'back_to_items' => __( 'Back to Booking Services', 'mwb-bookings-for-woocommerce' ),
			'menu_name'     => __( 'Booking Services', 'mwb-bookings-for-woocommerce' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'mwb_booking_service' ),
			'show_in_rest'      => true,
		);
		register_taxonomy( 'mwb_booking_service', 'product', $args );
	}
	/**
	 * Registering custom product type.
	 *
	 * @return void
	 */
	public function mbfw_registering_custom_product_type() {
		require_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-wc-product-mwb-booking.php';
	}
}
