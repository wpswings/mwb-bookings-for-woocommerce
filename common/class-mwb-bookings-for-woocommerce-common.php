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
	 * @since    2.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
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
	 * @since 2.0.0
	 */
	public function mbfw_common_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . 'common', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/css/mwb-bookings-for-woocommerce-common.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'mwb-mbfw-common-custom-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/css/mwb-common.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'mwb-mbfw-time-picker-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/user-friendly-time-picker/dist/css/timepicker.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/jquery-ui-css/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'date-range-picker-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/daterangepicker-master/daterangepicker.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since 2.0.0
	 */
	public function mbfw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-bookings-for-woocommerce-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'common', 'mbfw_common_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name . 'common' );
		wp_enqueue_script( 'mwb-mbfw-common-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-common.min.js', array(), $this->version, true );
		wp_localize_script(
			'mwb-mbfw-common-js',
			'mwb_mbfw_common_obj',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'mbfw_common_nonce' ),
				'minDate'    => current_time( 'd/m/Y H:m' ),
				'maxTime'    => gmdate( 'd/m/Y', strtotime( current_time( 'mysql' ) . '+1 days' ) ) . '00:00',
			)
		);
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'mwb-mbfw-time-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/user-friendly-time-picker/dist/js/timepicker.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'moment-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/moment-js/moment.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'date-range-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/daterangepicker-master/daterangepicker.js', array( 'moment-js', 'jquery' ), $this->version, true );
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
		$order_count   = count(
			wc_get_orders(
				array(
					'status'   => array( 'wc-processing', 'wc-on-hold', 'wc-pending' ),
					'return'   => 'ids',
					'limit'    => -1,
					'meta_key' => 'mwb_order_type', // phpcs:ignore WordPress
					'meta_val' => 'booking',
				)
			)
		);
		$booking_title = _n( 'Booking', 'Bookings', $order_count, 'mwb-bookings-for-woocommerce' );
		$admin_bar->add_menu(
			array(
				'id'     => 'mwb-mbfw-custom-admin-menu-bookings',
				'parent' => null,
				'group'  => null,
				'title'  => sprintf(
					/* translators: 1- Booking text, 2- Booking count, 3- Booking count, 4- Booking text for screeen readers. */
					'<span class="mwb-admin-bar-booking-icon">%1$s</span>
					<span class="mwb-mbfw-booking-count">%2$s</span>
					<span class="screen-reader-text">%3$s %4$s</span>',
					$booking_title,
					$order_count,
					$order_count,
					$booking_title
				),
				'href'   => admin_url( 'edit.php?post_status=all&post_type=shop_order&filter_booking=booking&filter_action=Filter' ),
				'meta'   => array(
					'title' => __( 'List All Bookings', 'mwb-bookings-for-woocommerce' ),
				)
			)
		);
	}

	/**
	 * Showing extra charges on cart listing total.
	 *
	 * @param object $cart_object cart object.
	 * @return void
	 */
	public function mwb_mbfw_show_extra_charges_in_total( $cart_object ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}
		$cart_data = $cart_object->get_cart();
		foreach ( $cart_data as $cart ) {
			if ( 'mwb_booking' === $cart['data']->get_type() && isset( $cart['mwb_mbfw_booking_values'] ) ) {
				$new_price        = $cart['data']->get_price();
				$custom_cart_data = $cart['mwb_mbfw_booking_values'];
				$people_number    = isset( $custom_cart_data['people_number'] ) && ( $custom_cart_data['people_number'] > 0 ) ? (int) $custom_cart_data['people_number'] : 1;
				$base_price       = get_post_meta( $cart['product_id'], 'mwb_mbfw_booking_base_cost', true );
				$base_price       = apply_filters( 'mwb_mbfw_vary_product_base_price', ( ! empty( $base_price ) ? (float) $base_price : 0 ), $custom_cart_data, $cart_object, $cart );
				$unit_price       = get_post_meta( $cart['product_id'], '_price', true );
				$unit_price       = apply_filters( 'mwb_mbfw_vary_product_unit_price', ( ! empty( $unit_price ) ? (float) $unit_price : 0 ), $custom_cart_data, $cart_object, $cart );
				
				// adding unit cost.
				if ( 'yes' === get_post_meta( $cart['product_id'], 'mwb_mbfw_is_booking_unit_cost_per_people', true ) ) {
					$new_price = (float) $unit_price * (int) $people_number;
				} else {
					$new_price = (float) $unit_price;
				}
				
				// adding base cost.
				if ( 'yes' === get_post_meta( $cart['product_id'], 'mwb_mbfw_is_booking_base_cost_per_people', true ) ) {
					$new_price = $new_price + (float) $base_price * (int) $people_number;
				} else {
					$new_price = $new_price + (float) $base_price;
				}

				$service_option_checked = isset( $custom_cart_data['service_option'] ) ? $custom_cart_data['service_option'] : array();
				$service_option_count   = isset( $custom_cart_data['service_quantity'] ) ? $custom_cart_data['service_quantity'] : array();
				$new_price             += $this->mbfw_extra_service_charge( $cart['product_id'], $service_option_checked, $service_option_count, $people_number );
				$new_price             += $this->mbfw_extra_charges_calculation( $cart['product_id'], $people_number );
				
				$new_price =
				apply_filters( 'mbfw_set_price_individually_during_adding_in_cart', $new_price, $custom_cart_data, $cart_object );
				// setting the new price.
				$cart['data']->set_price( $new_price );
			}
		}
	}

	/**
	 * Retrieve total cost at single booking.
	 *
	 * @return void
	 */
	public function mbfw_retrieve_booking_total_single_page() {
		check_ajax_referer( 'mbfw_common_nonce', 'nonce' );
		$product_id = array_key_exists( 'mwb_mbfw_booking_product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_product_id'] ) ) : '';
		if ( ! $product_id ) {
			wp_die();
		}
		$services_checked = array_key_exists( 'mwb_mbfw_service_option_checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_option_checkbox'] ), 'sanitize_text_field' ) : array();
		$service_quantity = array_key_exists( 'mwb_mbfw_service_quantity', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_quantity'] ), 'sanitize_text_field' ) : array();
		$people_number    = array_key_exists( 'mwb_mbfw_people_number', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_people_number'] ) ) : 1;
		$people_number    = $people_number > 0 ? $people_number : 1;
		$quantity         = array_key_exists( 'quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) : 1;
		$quantity         = $quantity > 0 ? $quantity : 1;
		$date_time        = array_key_exists( 'mwb_mbfw_booking_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_time'] ) ) : '';
		$date_time        = explode( ',', $date_time );
		$date_from        = gmdate( 'd-m-Y', strtotime( ! empty( $date_time[0] ) ? $date_time[0] : current_time( 'd-m-Y' ) ) );
		$date_to          = gmdate( 'd-m-Y', strtotime( ! empty( $date_time[1] ) ? $date_time[1] : current_time( 'd-m-Y' ) ) );
		$time_from        = gmdate( 'H:i', strtotime( ! empty( $date_time[0] ) ? $date_time[0] : current_time( 'H:i' ) ) );
		$time_to          = gmdate( 'H:i', strtotime( ! empty( $date_time[1] ) ? $date_time[1] : current_time( 'H:i' ) ) );
		$services_cost    = $this->mbfw_extra_service_charge( $product_id, $services_checked, $service_quantity, $people_number );
		$extra_charges    = $this->mbfw_extra_charges_calculation( $product_id , $people_number );
		$product_price    = get_post_meta( $product_id, '_price', true );
		$product_price    = apply_filters( 'mwb_mbfw_change_price_ajax_global_rule', ( ! empty( $product_price ) ? $product_price : 0 ), $date_from, $date_to, $time_from, $time_to, $quantity, $people_number, 'unit_cost' );
		$base_cost        = get_post_meta( $product_id, 'mwb_mbfw_booking_base_cost', true );
		$base_cost        = apply_filters( 'mwb_mbfw_change_price_ajax_global_rule', ( ! empty( $base_cost ) ? (float) $base_cost : 0 ), $date_from, $date_to, $time_from, $time_to, $quantity, $people_number, 'base_cost' );
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_is_booking_unit_cost_per_people', true ) ) {
			$product_price = (float) $product_price * (int) $people_number;
		}
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_is_booking_base_cost_per_people', true ) ) {
			$base_cost = (float) $base_cost * (int) $people_number;
		}
		$charges = array(
			'service_cost' => array(
				'title' => __( 'Service Cost', 'mwb-bookings-for-woocommerce' ),
				'value' => $services_cost,
			),
			'base_cost' => array(
				'title' => __( 'Base Cost', 'mwb-bookings-for-woocommerce' ),
				'value' => $base_cost,
			),
			'general_cost' => array(
				'title' => __( 'General Cost', 'mwb-bookings-for-woocommerce' ),
				'value' => $product_price,
			),
			'additional_charge' => array(
				'title' => __( 'Additional Charges', 'mwb-bookings-for-woocommerce' ),
				'value' => $extra_charges,
			),
		);
		$charges =
		//desc - ajax loading total listings.
		apply_filters( 'mbfw_ajax_load_total_booking_charge_individually', $charges, $product_id );
		$this->mbfw_booking_total_listing_single_page( $charges, $quantity );
		wp_die();
	}

	/**
	 * Booking total listing on single product page.
	 *
	 * @param array $charges array containing all the charges.
	 * @param int   $quantity quantity of booked product.
	 * @return void
	 */
	public function mbfw_booking_total_listing_single_page( $charges, $quantity ) {
		?>
		<div class="mbfw-total-listing-single-page__wrapper-parent">
			<?php
			$total = 0;
			foreach ( $charges as $types ) {
				$price  = $types['value'];
				$title  = $types['title'];
				$total += (float) $price * (int) $quantity;
				?>
				<div class="mbfw-total-listing-single-page__wrapper">
					<div class="mbfw-total-listing-single-page">
						<?php echo wp_kses_post( $title ); ?>
					</div>
					<div class="mbfw-total-listing-single-page">
						<?php echo wp_kses_post( wc_price( $price ) ); ?>
					</div>
				</div>
				<?php
			}
			?>
			<div class="mbfw-total-listing-single-page__wrapper mbfw-total-cost__wrapper">
				<div class="mbfw-total-listing-single-page">
					<?php esc_html_e( 'Total', 'mwb-bookings-for-woocommerce' ); ?>
				</div>
				<div class="mbfw-total-listing-single-page">
					<?php echo wp_kses_post( wc_price( $total ) ); ?>
				</div>
			</div>
			<?php do_action( 'mbfw_show_booking_policy' ); ?>
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
				$cost = ! empty( $cost ) ? (float) $cost : 0;
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
	 * @param int   $people_number number of people.
	 * @return float
	 */
	public function mbfw_extra_service_charge( $product_id, $services_checked, $service_quantity, $people_number ) {
		$services_cost = 0;
		if ( is_array( $services_checked ) ) {
			foreach ( $services_checked as $term_id ) {
				$service_count = array_key_exists( $term_id, $service_quantity ) ? $service_quantity[ $term_id ] : 1;
				$service_price = get_term_meta( $term_id, 'mwb_mbfw_service_cost', true );
				$service_price = ! empty( $service_price ) ? (float) $service_price : 0;
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
					$service_price = ! empty( $service_price ) ? (float) $service_price : 0;
					if ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_service_cost_multiply_people', true ) ) {
						$services_cost += $service_count * $service_price * $people_number;
					} else {
						$services_cost += $service_count * $service_price;
					}
				}
			}
		}
		return $services_cost;
	}

	/**
	 * Set order as booking type.
	 *
	 * @param int    $order_id current order id.
	 * @param object $order current order object.
	 * @return void
	 */
	public function mwb_bfwp_set_order_as_mwb_booking( $order_id, $order ) {
		$order_items = $order->get_items();
		foreach ( $order_items as $item ) {
			$product = $item->get_product();
			if ( 'mwb_booking' === $product->get_type() ) {
				$order->update_meta_data( 'mwb_order_type', 'booking' );
				$order->save();
				break;
			}
		}
	}

	/**
	 * Change order status.
	 *
	 * @param integer $order_id current order id.
	 * @return void
	 */
	public function mwb_bfwp_change_order_status( $order_id ) {
		if ( ! $order_id ) {
			return;
		}
		$order = wc_get_order( $order_id );
		if ( 'on-hold' === $order->get_status() ) {
			return;
		}
		$items = $order->get_items();
		foreach ( $items as $item ) {
			if ( 'yes' === get_post_meta( $item->get_product_id(), 'mwb_mbfw_admin_confirmation', true ) ) {
				$order->update_status( 'on-hold', __( 'confirmation required from admin.', 'mwb-bookings-for-woocommerce' ) );
				break;
			}
		}
	}

	/**
	 * Create cancel order link.
	 *
	 * @param array  $statuses array containing order statuses on which to triger cancel button.
	 * @param object $order current order object.
	 * @return array
	 */
	public function mwb_mbfw_set_cancel_order_link_order_statuses( $statuses, $order ) {
		$items = $order->get_items();
		foreach ( $items as $item ) {
			if ( 'yes' === get_post_meta( $item->get_product_id(), 'mwb_mbfw_cancellation_allowed', true ) ) {
				$order_statuses = get_post_meta( $item->get_product_id(), 'mwb_bfwp_order_statuses_to_cancel', true );
				$order_statuses = is_array( $order_statuses ) ? map_deep(
						$order_statuses,
						function ( $status ) {
							return preg_replace( '/wc-/', '', $status );
						}
					) : array();
				if ( in_array( $order->get_status(), $order_statuses, true ) ) {
					return array( $order->get_status() );
				}
			}
		}
		return $statuses;
	}

	/**
	 * Show additional order details to user account page.
	 *
	 * @param int    $item_id item id.
	 * @param object $item current item object.
	 * @param object $order current order object.
	 * @return void
	 */
	public function mbfw_show_booking_details_on_my_account_page_user( $item_id, $item, $order ) {
		if ( 'mwb_booking' === $item->get_product()->get_type() ) {
			?>
			<table class="mwb-mbfw-user-booking-meta-data-listing">
				<?php
				$people_number = $item->get_meta( '_mwb_mbfw_people_number', true );
				if ( ! empty( $people_number ) ) {
					?>
					<tr>
						<th><?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?></th>
					</tr>
					<tr>
						<td><?php echo esc_attr( $people_number ); ?></td>
					</tr>
					<?php
				} else {
					// People type show in user booking.
					do_action( 'mwb_mbfw_people_user_booking_my_account', $item_id, $item, $order );
				}
				$services_and_count = $item->get_meta( '_mwb_mbfw_service_and_count', true );
				if ( ! empty( $services_and_count ) && is_array( $services_and_count ) ) {
					?>
					<tr>
						<th>
							<?php esc_html_e( 'Service(s)', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
					</tr>
					<?php
					foreach ( $services_and_count as $term_id => $count ) {
						$term = get_term( $term_id, 'mwb_booking_service' );
						?>
						<tr>
							<td>
								<?php echo esc_html( isset( $term->name ) ? $term->name . '( ' . $count . ' )' : '' ); ?>
							</td>
						</tr>
						<?php
					}
				}
				$date_time = $item->get_meta( '_mwb_bfwp_date_time', true );
				if ( ! empty( $date_time ) ) {
					?>
					<tr>
						<th>
							<?php esc_html_e( 'Duration', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo esc_html( $date_time ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							// Reschedule user booking link.
							do_action( 'mwb_mbfw_reschedule_user_booking_my_account', $item_id, $item, $order );
							?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
	}
}
