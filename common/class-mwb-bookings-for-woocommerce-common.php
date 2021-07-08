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
		wp_enqueue_style( 'mwb-mbfw-common-custom-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/css/mwb-common.min.css', array(), $this->version, 'all' );
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
		wp_enqueue_script( 'mwb-mbfw-common-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-common.min.js', array( 'jquery' ), $this->version, true );
		wp_localize_script(
			'mwb-mbfw-common-js',
			'mwb_mbfw_common_obj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'mbfw_common_nonce' ),
			)
		);
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

	/**
	 * Adding Bookings menu on admin bar.
	 *
	 * @param object $admin_bar object to add custom menu items.
	 * @return void
	 */
	public function mbfw_add_admin_menu_custom_tab( $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$admin_bar->add_menu(
			array(
				'id'     => 'mwb-mbfw-custom-admin-menu-bookings',
				'parent' => null,
				'group'  => null,
				'title'  => 'Bookings',
				'href'   => admin_url( 'admin.php?page=custom-page' ),
				'meta'   => array(
					'title' => __( 'List All Bookings', 'mwb-bookings-for-woocommerce' ),
				)
			)
		);
	}

	/**
	 * Showing extra charges on cart listing total.
	 *
	 * @return void
	 */
	public function mwb_mbfw_show_extra_charges_in_total( $cart_object ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$cart_data = $cart_object->get_cart();
		foreach ( $cart_data as $cart ) {
			if ( 'mwb_booking' === $cart['data']->get_type() ) {
				$new_price     = $cart['data']->get_price();
				$people_number = isset( $cart['mwb_mbfw_people_number'] ) && ( $cart['mwb_mbfw_people_number'] > 0 ) ? (int) $cart['mwb_mbfw_people_number'] : 1;
				$base_price    = get_post_meta( $cart['product_id'], 'mwb_mbfw_booking_base_cost', true );
				$unit_price    = get_post_meta( $cart['product_id'], '_price', true );
				
				// adding unit cost.
				if ( 'yes' === get_post_meta( $cart['product_id'], 'mwb_mbfw_is_booking_unit_cost_per_people', true ) ) {
					$new_price = (float) $unit_price * $people_number;
				} else {
					$new_price = (float) $unit_price;
				}
				
				// adding base cost.
				if ( 'yes' === get_post_meta( $cart['product_id'], 'mwb_mbfw_is_booking_base_cost_per_people', true ) ) {
					$new_price = $new_price + (float) $base_price * $people_number;
				} else {
					$new_price = $new_price + (float) $base_price;
				}

				$service_option_checked = isset( $cart['mwb_mbfw_service_option_checkbox'] ) ? $cart['mwb_mbfw_service_option_checkbox'] : array();
				$service_option_count   = isset( $cart['mwb_mbfw_service_quantity'] ) ? $cart['mwb_mbfw_service_quantity'] : array();
				$new_price             += $this->mbfw_extra_service_charge( $cart['product_id'], $service_option_checked, $service_option_count, $people_number );
				$new_price             += $this->mbfw_extra_charges_calculation( $cart['product_id'], $people_number );
				
				// setting the new price.
				$cart['data']->set_price( $new_price );
			}
		}
	}

	/**
	 * Retrieve total cost at single booking.
	 *
	 * @return string
	 */
	public function mbfw_retrieve_booking_total_single_page() {
		check_ajax_referer( 'mbfw_common_nonce', 'nonce' );
		$services_checked = array_key_exists( 'mwb_mbfw_service_option_checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_option_checkbox'] ), 'sanitize_text_field' ) : '';
		$service_quantity = array_key_exists( 'mwb_mbfw_service_quantity', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_quantity'] ), 'sanitize_text_field' ) : '';
		$product_id       = array_key_exists( 'mwb_mbfw_booking_product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_product_id'] ) ) : '';
		$people_number    = array_key_exists( 'mwb_mbfw_people_number', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_people_number'] ) ) : 1;
		$quantity         = array_key_exists( 'quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : 1;
		$services_cost    = $this->mbfw_extra_service_charge( $product_id, $services_checked, $service_quantity, $people_number );
		$extra_charges    = $this->mbfw_extra_charges_calculation( $product_id , $people_number);
		$product_price    = get_post_meta( $product_id, '_price', true );
		$base_cost        = get_post_meta( $product_id, 'mwb_mbfw_booking_base_cost', true );
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_is_booking_unit_cost_per_people', true ) ) {
			$product_price = (float) $product_price * (int) $people_number;
		}
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_is_booking_base_cost_per_people', true ) ) {
			$base_cost = (float) $base_cost * (int) $people_number;
		}
		$charges = array(
			__( 'Service Cost', 'mwb-bookings-for-woocommerce' )       => $services_cost * $quantity,
			__( 'Base Cost', 'mwb-bookings-for-woocommerce' )          => $base_cost * $quantity,
			__( 'General Cost', 'mwb-bookings-for-woocommerce' )       => $product_price * $quantity,
			__( 'Additional Charges', 'mwb-bookings-for-woocommerce' ) => $extra_charges * $quantity,
		);
		$this->mbfw_booking_total_listing_single_page( $charges );
		wp_die();
	}

	/**
	 * Booking total listing on single product page.
	 *
	 * @param array $charges array containing all the charges.
	 * @return void
	 */
	public function mbfw_booking_total_listing_single_page( $charges ) {
		?>
		<div class="mbfw-total-listing-single-page__wrapper-parent">
			<?php
			$total = 0;
			foreach ( $charges as $title => $price ) {
				$total += $price;
				?>
				<div class="mbfw-total-listing-single-page__wrapper">
					<div class="mbfw-total-listing-single-page">
						<?php echo wp_kses_post( $title ); ?>
					</div>
					<div class="mbfw-total-listing-single-page">
						<?php echo wp_kses_post( $price ); ?>
					</div>
				</div>
				<?php
			}
			?>
			<hr/>
			<div class="mbfw-total-listing-single-page__wrapper">
				<div class="mbfw-total-listing-single-page">
					<?php esc_html_e( 'Total', 'mwb-bookings-for-woocommerce' ); ?>
				</div>
				<div class="mbfw-total-listing-single-page">
					<?php echo wp_kses_post( $total ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Extra charges calculation.
	 *
	 * @param int $product_id current product id.
	 * @param int $people_number number of people in the booking.
	 * @return float
	 */
	public function mbfw_extra_charges_calculation( $product_id, $people_number ) {
		$extra_charges = 0;
		$terms         = get_the_terms( $product_id, 'mwb_booking_cost' );
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$cost = get_term_meta( $term->term_id, 'mwb_mbfw_booking_cost', true );
				if ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_booking_cost_multiply_people', true ) ) {
					$extra_charges += $cost * $people_number;
				} else {
					$extra_charges += $cost;
				}
			}
		}
		return $extra_charges;
	}

	/**
	 * Extra service charges calculation.
	 *
	 * @param int   $product_id current product id.
	 * @param array $services_checked array containing optional services checked by user.
	 * @param array $service_quantity quantity array containing services and there count.
	 * @param int   $people_number
	 * @return float
	 */
	public function mbfw_extra_service_charge( $product_id, $services_checked, $service_quantity, $people_number ) {
		$services_cost = 0;
		if ( is_array( $services_checked ) ) {
			foreach ( $services_checked as $term_id ) {
				$service_count = array_key_exists( $term_id, $service_quantity ) ? $service_quantity[ $term_id ] : 1;
				$service_price = (float) get_term_meta( $term_id, 'mwb_mbfw_service_cost', true );
				if ( 'yes' === get_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_people', true ) ) {
					$services_cost += $service_count * $service_price * $people_number;
				} else {
					$services_cost += $service_count * $service_price;
				}
			}
		}
		$terms = get_the_terms( $product_id, 'mwb_booking_service' );
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( 'yes' !== get_term_meta( $term->term_id, 'mwb_mbfw_is_service_optional', true ) ) {
					$service_count = array_key_exists( $term->term_id, $service_quantity ) ? $service_quantity[ $term->term_id ] : 1;
					$service_price = (float) get_term_meta( $term->term_id, 'mwb_mbfw_service_cost', true );
					if ( 'yes' === get_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_people', true ) ) {
						$services_cost += $service_count * $service_price * $people_number;
					} else {
						$services_cost += $service_count * $service_price;
					}
				}
			}
		}
		return $services_cost;
	}
}