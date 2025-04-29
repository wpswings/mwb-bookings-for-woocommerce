<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://wpswings.com/
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
	 * @var   string    $plugin_name    The ID of this plugin.
	 * @since 2.0.0
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
		wp_enqueue_style( 'datetime-picker-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datetimepicker-master/build/jquery.datetimepicker.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'mwb-bfwp-multi-date-picker-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/multiple-datepicker/jquery-ui.multidatespicker.css', array(), $this->version, 'all' );
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
		wp_enqueue_script( 'mwb-mbfw-common-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-common.js', array(), $this->version, true );
		// database error connection issue fixed.
		$is_single_cal = '';
		if ( is_single() ) {
			global $post;
			$booking_type = wps_booking_get_meta_data( $post->ID, 'wps_mbfw_booking_type', true );
			if ( 'single_cal' == $booking_type ) {

				$is_single_cal = 'yes';
			}
		}
		wp_localize_script(
			'mwb-mbfw-common-js',
			'mwb_mbfw_common_obj',
			array(
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'nonce'                => wp_create_nonce( 'mbfw_common_nonce' ),
				'minDate'              => current_time( 'd-m-Y H:m' ),
				'minTime'              => current_time( 'H:m' ),
				'maxTime'              => gmdate( 'd/m/Y', strtotime( current_time( 'mysql' ) . '+1 days' ) ) . '00:00',
				'date_time_format'     => __( 'Please choose the dates from calendar with correct format, wrong format can not be entered', 'mwb-bookings-for-woocommerce' ),
				'date_format'          => get_option( 'date_format' ),
				'is_single_cal'        => $is_single_cal,
				'cancel_booking_order' => __( 'Are you sure to cancel Booking order?', 'mwb-bookings-for-woocommerce' ),
				'holiday_alert'        => __( 'It looks like some dates are not available in between the dates choosen by you! , please select available dates!', 'mwb-bookings-for-woocommerce' ),
			)
		);

		if ( is_admin() ) {

			$screen = get_current_screen();

			if ( isset( $screen->id ) && 'toplevel_page_theme-general-settings' !== $screen->id && 'page' !== $screen->id ) {

				wp_enqueue_script( 'jquery-ui-datepicker' );
				if ( 'wp-swings_page_mwb_bookings_for_woocommerce_menu' == $screen->id ) {
					wp_enqueue_script( 'mwb-mbfw-time-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/user-friendly-time-picker/dist/js/timepicker.min.js', array( 'jquery' ), $this->version, true );
				}
				wp_enqueue_script( 'moment-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/moment-js/moment.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( 'moment-locale-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/moment-js/moment-locale-js.js', array( 'jquery', 'moment-js' ), $this->version, true );
				wp_enqueue_script( 'datetime-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datetimepicker-master/build/jquery.datetimepicker.full.js', array( 'jquery', 'moment-js' ), $this->version, true );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'mwb-bfwp-multi-date-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/multiple-datepicker/jquery-ui.multidatespicker.js', array( 'jquery-ui-core', 'jquery', 'jquery-ui-datepicker' ), time(), true );
			}
		} else {
			wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'mwb-mbfw-time-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/user-friendly-time-picker/dist/js/timepicker.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( 'moment-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/moment-js/moment.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( 'moment-locale-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/moment-js/moment-locale-js.js', array( 'jquery', 'moment-js' ), $this->version, true );
				wp_enqueue_script( 'datetime-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datetimepicker-master/build/jquery.datetimepicker.full.js', array( 'jquery', 'moment-js' ), $this->version, true );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'mwb-bfwp-multi-date-picker-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/multiple-datepicker/jquery-ui.multidatespicker.js', array( 'jquery-ui-core', 'jquery', 'jquery-ui-datepicker' ), time(), true );
		}
	}

	/**
	 * Registering custom taxonomy for mwb_booking product type.
	 *
	 * @return void
	 */
	public function mbfw_custom_taxonomy_for_products() {
		$labels = array(
			'name'          => __( 'Additional Costs', 'mwb-bookings-for-woocommerce' ),
			'singular_name' => __( 'Booking Costs', 'mwb-bookings-for-woocommerce' ),
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
			'description'       => __( 'This setting tab allows you to create different types of additional booking costs for your booking products i.e. the part of your booking product’s additional resources or addons.', 'mwb-bookings-for-woocommerce' ),
			'public'            => true,
			'show_ui'           => true,
			'query_var'         => true,
			'show_in_menu'      => false,
			'show_in_nav_menus' => false,
			'show_admin_column' => false,
			'menu_position'     => 10,
			'rewrite'           => array( 'slug' => 'mwb_booking_cost' ),
			'show_in_rest'      => true,
		);
		register_taxonomy( 'mwb_booking_cost', 'product', $args );
		$labels = array(
			'name'          => __( 'Additional Services', 'mwb-bookings-for-woocommerce' ),
			'singular_name' => __( 'Booking Services', 'mwb-bookings-for-woocommerce' ),
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
			'show_in_menu'      => false,
			'show_in_nav_menus' => false,
			'show_admin_column' => false,
			'menu_position'     => 10,
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
	 * Cost taxonomy description.
	 *
	 * @return void
	 */
	public function mbfw_booking_cost_add_description() {
		echo wp_kses(
			wpautop( __( 'This setting tab allows you to create different types of additional booking costs for your booking products i.e. the part of your booking product’s additional resources or addons.', 'mwb-bookings-for-woocommerce' ) ),
			array( 'span' => array() )
		);
	}

	/**
	 * Service taxonomy description.
	 *
	 * @return void
	 */
	public function mbfw_booking_services_add_description() {
		echo wp_kses(
			wpautop( __( 'This setting tab allows you to create different types of additional booking services for your booking products i.e. the part of your booking product’s additional resources or addons.', 'mwb-bookings-for-woocommerce' ) ),
			array( 'span' => array() )
		);
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
				),
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
		$unit      = 0;
		$cart_data = $cart_object->get_cart();
		foreach ( $cart_data as $cart ) {
			if ( 'mwb_booking' === $cart['data']->get_type() && isset( $cart['mwb_mbfw_booking_values'] ) ) {
				$new_price        = (float) $cart['data']->get_price();
				$base_price       = 0;
				$product_id       = $cart['data']->get_id();
				$custom_cart_data = $cart['mwb_mbfw_booking_values'];
				$people_number    = isset( $custom_cart_data['people_number'] ) && ( $custom_cart_data['people_number'] > 0 ) ? (int) $custom_cart_data['people_number'] : 1;
				$hide_base_cost   = wps_booking_get_meta_data( $cart['product_id'], 'mwb_mbfw_booking_base_cost_hide', true );
				$hide_general_cost = wps_booking_get_meta_data( $cart['product_id'], 'mwb_mbfw_booking_general_cost_hide', true );
				if ( 'yes' != $hide_base_cost ) {

					$base_price = wps_booking_get_meta_data( $cart['product_id'], 'mwb_mbfw_booking_base_cost', true );
										/**
										 * Filter is for returning something.
										 *
										 * @since 1.0.0
										 */
					$base_price = apply_filters( 'mwb_mbfw_vary_product_base_price', ( ! empty( $base_price ) ? (float) $base_price : 0 ), $custom_cart_data, $cart_object, $cart );
				}
				$booking_type      = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
				$booking_dates     = array_key_exists( 'single_cal_booking_dates', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['single_cal_booking_dates'] ) ) : '';
				$wps_general_price = '';

				if ( 'single_cal' === $booking_type ) {

					if ( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
						$booking_dates = explode( ' | ', $booking_dates );

						$unit = count( $booking_dates );

						$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_day_single', $new_price, $cart['product_id'], $booking_dates, $unit );
					} else {

						$date_time_from = array_key_exists( 'single_cal_date_time_from', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['single_cal_date_time_from'] ) ) : '';
						$date_time_to   = array_key_exists( 'single_cal_date_time_to', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['single_cal_date_time_to'] ) ) : '';
						$from_timestamp = strtotime( $date_time_from );
						$to_timestamp   = strtotime( $date_time_to );
						if ( $to_timestamp < $from_timestamp ) {
							$to_time = $to_timestamp + 86400; // Add 24 hours (86400 seconds).
						} else {
									$to_time = $to_timestamp;
						}
						$unit_timestamp    = $to_time - $from_timestamp;
						$unit              = $unit_timestamp / 3600;
						$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_hour', $new_price, $cart['product_id'], $date_time_from, $date_time_to, $unit );
					}
				} else {

					if ( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
						$date_from         = array_key_exists( 'date_time_from', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['date_time_from'] ) ) : '';
						$date_to           = array_key_exists( 'date_time_to', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['date_time_to'] ) ) : '';
						$date_from         = gmdate( 'd-m-Y', strtotime( $date_from ) );
						$date_to           = gmdate( 'd-m-Y', strtotime( $date_to ) );
						$from_timestamp    = strtotime( $date_from );
						$to_timestamp      = strtotime( $date_to );
						$unit_timestamp    = $to_timestamp - $from_timestamp;
						$unit              = $unit_timestamp / 86400;
						$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_day', $new_price, $cart['product_id'], $date_from, $date_to, $unit );
					} elseif ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
						$date_time_from = array_key_exists( 'date_time_from', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['date_time_from'] ) ) : '';
						$date_time_to   = array_key_exists( 'date_time_to', $custom_cart_data ) ? sanitize_text_field( wp_unslash( $custom_cart_data['date_time_to'] ) ) : '';
						$from_timestamp = strtotime( $date_time_from );
						$to_timestamp   = strtotime( $date_time_to );
						if ( $to_timestamp < $from_timestamp ) {
							$to_time = $to_timestamp + 86400; // Add 24 hours (86400 seconds).
						} else {
									$to_time = $to_timestamp;
						}
						$unit_timestamp    = $to_time - $from_timestamp;
						$unit              = abs( $unit_timestamp ) / 3600;
						$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_hour', $new_price, $cart['product_id'], $date_time_from, $date_time_to, $unit );
					}
				}

				if ( 'yes' == $hide_general_cost ) {
					$wps_general_price = '';
				}

				$unit_price = 0;
				if ( $wps_general_price ) {

					if ( $wps_general_price === $new_price ) {

						if ( $unit ) {
							$unit_price = (float) $new_price * $unit;
						}
					} else {
						$unit_price = (float) $wps_general_price;
					}
				} else {
					if ( $unit ) {
						$unit_price = (float) $new_price * $unit;
					}
				}

				$regular_price__ = $unit_price;
				/**
				 * Filter is for returning something.
				 *
				 * @since 1.0.0
				 */

				$unit_price = apply_filters( 'mwb_mbfw_vary_product_unit_price', ( ! empty( $unit_price ) ? (float) $unit_price : 0 ), $custom_cart_data, $cart_object, $cart );

				// adding unit cost.
				if ( 'yes' === wps_booking_get_meta_data( $cart['product_id'], 'mwb_mbfw_is_booking_unit_cost_per_people', true ) ) {
					$new_price = (float) $unit_price * (int) $people_number;
				} else {
					$new_price = (float) $unit_price;
				}

				// adding base cost.
				if ( 'yes' === wps_booking_get_meta_data( $cart['product_id'], 'mwb_mbfw_is_booking_base_cost_per_people', true ) ) {
					$new_price = $new_price + (float) $base_price * (int) $people_number;
				} else {

					$new_price = $new_price + (float) $base_price;
				}

				$service_option_checked = isset( $custom_cart_data['service_option'] ) ? $custom_cart_data['service_option'] : array();
				$service_option_count   = isset( $custom_cart_data['service_quantity'] ) ? $custom_cart_data['service_quantity'] : array();
				$new_price             += $this->mbfw_extra_service_charge( $cart['product_id'], $service_option_checked, $service_option_count, $people_number, $unit );
				$new_price             += $this->mbfw_extra_charges_calculation( $cart['product_id'], $people_number, $unit );

				$new_price =
				/**
				 * Filter is for returning something.
				 *
				 * @since 1.0.0
				 */
				apply_filters( 'mbfw_set_price_individually_during_adding_in_cart', $new_price, $custom_cart_data, $cart_object );
				// setting the new price.
				$additionalu_price = intval( $new_price ) - intval( $regular_price__ );
				$cart['data']->set_regular_price( $additionalu_price );
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
		$date_time_from   = array_key_exists( 'mwb_mbfw_booking_from_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_from_time'] ) ) : '';
		$date_time_to     = array_key_exists( 'mwb_mbfw_booking_to_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_to_time'] ) ) : '';
		if('Invalid date' == $date_time_from ){
			$date_time_from = '';
		}
		if('Invalid date' == $date_time_to ){
			$date_time_to = '';
		}

		$date_from = gmdate( 'd-m-Y', strtotime( ! empty( $date_time_from ) ? $date_time_from : current_time( 'd-m-Y H:i' ) ) );

		$date_to           = gmdate( 'd-m-Y', strtotime( ! empty( $date_time_to ) ? $date_time_to : current_time( 'd-m-Y H:i' ) ) );
		$time_from         = gmdate( 'H:i', strtotime( ! empty( $date_time_from ) ? $date_time_from : current_time( 'H:i' ) ) );
		$time_to           = gmdate( 'H:i', strtotime( ! empty( $date_time_to ) ? $date_time_to : current_time( 'H:i' ) ) );
		$booking_type      = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
		$booking_dates     = array_key_exists( 'wps_booking_single_calendar_form', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_booking_single_calendar_form'] ) ) : '';
		$unit              = 1;
		$product_price     = wps_booking_get_meta_data( $product_id, '_price', true );
		$wps_general_price = '';

		if ( 'single_cal' === $booking_type ) {

			if ( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
				$booking_dates     = explode( ',', $booking_dates );
				$unit              = count( $booking_dates );
				$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_day_single', $product_price, $product_id, $booking_dates, $unit );
			} else {
				if ( ! empty( $booking_dates ) ) {
					$booking_dates = explode( ' ', $booking_dates );
					if ( isset( $booking_dates[1] ) ) {
						$date_time_from = $booking_dates[0] . ' ' . $booking_dates[1];
						$date_time_to   = $booking_dates[0] . ' ' . $booking_dates[3];
					}
				}
				$from_timestamp = strtotime( $date_time_from );
				$to_timestamp   = strtotime( $date_time_to );
				if ( $to_timestamp < $from_timestamp ) {
					$to_time = $to_timestamp + 86400; // Add 24 hours (86400 seconds).
				} else {
					$to_time = $to_timestamp;
				}
				$unit_timestamp    = $to_time - $from_timestamp;
				$unit              = $unit_timestamp / 3600;
				$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_hour', $product_price, $product_id, $date_time_from, $date_time_to, $unit );
			}
		} else {
			$wps_unv_day = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_choose_holiday', true );
			if ( strtotime( $date_time_from ) < strtotime( $wps_unv_day ) && strtotime( $date_time_to ) > strtotime( $wps_unv_day ) ) {
				echo 'fail';
				wp_die();
			}

			if ( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) && ! empty( $date_time_to ) && ! empty( $date_time_from ) ) {
				$from_timestamp    = strtotime( $date_from );
				$to_timestamp      = strtotime( $date_to );
				$unit_timestamp    = $to_timestamp - $from_timestamp;
				$unit              = $unit_timestamp / 86400;
				$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_day', $product_price, $product_id, $date_time_from, $date_time_to, $unit );
			} elseif ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) && ! empty( $date_time_to ) && ! empty( $date_time_from ) ) {

				$from_timestamp    = strtotime( $date_time_from );
				$to_timestamp      = strtotime( $date_time_to );
				$unit_timestamp    = $to_timestamp - $from_timestamp;
				$unit              = $unit_timestamp / 3600;
				$wps_general_price = apply_filters( 'wps_mbfw_set_unit_cost_price_hour', $product_price, $product_id, $date_time_from, $date_time_to, $unit );
			}
		}
		$wps_bfwp_msg = apply_filters( 'wps_mbfw_check_availablity', $product_id, $date_time_from, $date_time_to );

		if ( 'fail' === $wps_bfwp_msg ) {
			echo 'fail';
			wp_die();
		}
		$services_cost = $this->mbfw_extra_service_charge( $product_id, $services_checked, $service_quantity, $people_number, $unit );
		$extra_charges = $this->mbfw_extra_charges_calculation( $product_id, $people_number, $unit );
							/**
							 * Filter is for returning something.
							 *
							 * @since 1.0.0
							 */
		$product_price = apply_filters(
			'mwb_mbfw_change_price_ajax_global_rule',
			( ! empty( $product_price ) ? $product_price : 0 ),
			array(
				'date_from'     => $date_from,
				'date_to'       => $date_to,
				'time_from'     => $time_from,
				'time_to'       => $time_to,
				'quantity'      => $quantity,
				'people_number' => $people_number,
				'cost_type'     => 'unit_cost',
			)
		);
		$base_cost     = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_base_cost', true );
							/**
							 * Filter is for returning something.
							 *
							 * @since 1.0.0
							 */
		$base_cost = apply_filters(
			'mwb_mbfw_change_price_ajax_global_rule',
			( ! empty( $base_cost ) ? (float) $base_cost : 0 ),
			array(
				'date_from'     => $date_from,
				'date_to'       => $date_to,
				'time_from'     => $time_from,
				'time_to'       => $time_to,
				'quantity'      => $quantity,
				'people_number' => $people_number,
				'cost_type'     => 'base_cost',
			)
		);
		
		if ( $product_price === $wps_general_price ) {

			$product_price = (float) $product_price * (float) $unit;
		} else {
			$product_price = (float) $wps_general_price;
		}


			$global_product_price = apply_filters(
				'mwb_mbfw_change_price_ajax_global_rule',
				( ! empty( $product_price ) ? (float) $product_price : 0 ),
				array(
					'date_from'     => $date_from,
					'date_to'       => $date_to,
					'time_from'     => $time_from,
					'time_to'       => $time_to,
					'quantity'      => $quantity,
					'people_number' => $people_number,
					'cost_type'     => 'unit_cost',
				)
			);

			if ( ! empty($date_time_to) && ! empty($date_time_from) ){

			if ($global_product_price !== $product_price) {
				$product_price = (float) $global_product_price * (float) $unit;

			}
		}
		

		if ( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_booking_unit_cost_per_people', true ) ) {
			$product_price = (float) $product_price * (int) $people_number;
		}
		if ( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_booking_base_cost_per_people', true ) ) {
			$base_cost = (float) $base_cost * (int) $people_number;
		}

		$charges__ = array();
		if ( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_add_extra_services', true ) ) {
			$charges__ = array(
				'service_cost' => array(
					'title' => __( 'Service Cost', 'mwb-bookings-for-woocommerce' ),
					'value' => $services_cost,
				),

			);

		}

		$charges_other = array(

			'base_cost'    => array(
				'title' => __( 'Base Cost', 'mwb-bookings-for-woocommerce' ),
				'value' => $base_cost,
			),
			'general_cost' => array(
				'title' => __( 'General Cost', 'mwb-bookings-for-woocommerce' ),
				'value' => $product_price,
			),

		);
		$charges = array_merge( $charges__, $charges_other );

		// check additional cost.
		$mfw_additional_cost_check = array(
			'additional_charge' => array(
				'title' => __( 'Additional Costs', 'mwb-bookings-for-woocommerce' ),
				'value' => $extra_charges,
			),
		);
		if ( $extra_charges > 0 ) {
			$charges = array_merge( $charges, $mfw_additional_cost_check );
		}

		$charges =
		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'mbfw_ajax_load_total_booking_charge_individually', $charges, $product_id );
		$this->mbfw_booking_total_listing_single_page( $charges, $quantity, $product_id );
		wp_die();

	}

	/**
	 * Booking total listing on single product page.
	 *
	 * @param array $charges array containing all the charges.
	 * @param int   $quantity quantity of booked product.
	 * @param int   $product_id is the id of product.
	 * @return void
	 */
	public function mbfw_booking_total_listing_single_page( $charges, $quantity, $product_id ) {
		$general_price                   = wps_booking_get_meta_data( $product_id, '_price', true );
		$mwb_mbfw_booking_base_cost_hide = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_base_cost_hide', true );
		$mwb_mbfw_booking_genral_cost_hide = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_general_cost_hide', true );

		?>
		<div class="mbfw-total-listing-single-page__wrapper-parent">
			<?php
			$total = 0;
			foreach ( $charges as $types ) {

				$price = $types['value'];
				$title = $types['title'];
				if ( 'Base Cost' == $title ) {
					if ( 'yes' == $mwb_mbfw_booking_base_cost_hide ) {
						continue;
					}
				}
				if ( 'General Cost' == $title ) {
					if ( 'yes' == $mwb_mbfw_booking_genral_cost_hide ) {
						continue;
					}
				}
				$total += (float) $price * (int) $quantity;
				?>
				<div class="mbfw-total-listing-single-page__wrapper">
					<div class="mbfw-total-listing-single-page">
						<?php
						echo wp_kses_post( $title );
						if ( 'General Cost' == $title ) {
							?>
							<strong>( 
							<?php
							if ( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {

								// for general unit cost.
								echo wp_kses_post( wc_price( $general_price ) );
								esc_html_e( '/day', 'mwb-bookings-for-woocommerce' );

							} elseif ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
								echo wp_kses_post( wc_price( $general_price ) );
								esc_html_e( '/hour', 'mwb-bookings-for-woocommerce' );
							}
							?>
							)</strong>
							<?php
						}
						if ( 'Base Cost' == $title ) {
							?>
							<strong>( 
								<?php
								$base_cost = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_base_cost', true );
								echo wp_kses_post( wc_price( $base_cost ) );
								?>
							)</strong>
							<?php
						}
						?>
					</div>
					<div class="mbfw-total-listing-single-page">

						<?php
						if ( 'General Cost' == $title ) {
							echo wp_kses_post( wc_price( $price ) ) . ' x ' . wp_kses_post( $quantity ); // phpcs:ignore WordPress

						} else {
							echo wp_kses_post( wc_price( $price ) );
						}

						?>
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
			<?php
			/**
			 * Filter is for returning something.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbfw_show_booking_policy' );
			?>
		</div>
		<?php
	}

	/**
	 * Extra charges calculation.
	 *
	 * @param int $product_id current product id.
	 * @param int $people_number number of people in the booking.
	 * @param int $unit is used for pricing.
	 * @return float
	 */
	public function mbfw_extra_charges_calculation( $product_id, $people_number, $unit ) {
		$extra_charges = 0;
		$terms         = get_the_terms( $product_id, 'mwb_booking_cost' );

		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				$cost = get_term_meta( $term->term_id, 'mwb_mbfw_booking_cost', true );
				$cost = ! empty( $cost ) ? (float) $cost : 0;
				if ( 'yes' == get_term_meta( $term->term_id, 'mwb_mbfw_is_booking_cost_multiply_duration', true ) ) {
					$cost = $cost * $unit;
				}
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
	 * @param int   $unit is for pricing.
	 * @return float
	 */
	public function mbfw_extra_service_charge( $product_id, $services_checked, $service_quantity, $people_number, $unit ) {
		$services_cost = 0;

		if ( is_array( $services_checked ) ) {
			foreach ( $services_checked as $term_id ) {
				$service_count = array_key_exists( $term_id, $service_quantity ) ? $service_quantity[ $term_id ] : 1;
				$service_price = get_term_meta( $term_id, 'mwb_mbfw_service_cost', true );
				$service_price = ( ! empty( $service_price ) && $service_price > 0 ) ? (float) $service_price : 0;
				if ( 'yes' == get_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_duration', true ) ) {
					$service_price = $service_price * $unit;
				}
				if ( ! empty( $service_count ) ) {

					if ( 'yes' === get_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_people', true ) ) {
						$services_cost += $service_count * $service_price * $people_number;
					} else {
						$services_cost += $service_count * $service_price;
					}
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
					if ( 'yes' == get_term_meta( $term->term_id, 'mwb_mbfw_is_service_cost_multiply_duration', true ) ) {

							$service_price = $service_price * $unit;

					}
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

		$midnight_next_day = strtotime( 'tomorrow' );
		$current_time      = gmdate( 'H:i:s' );
		$midnight_n_day    = strtotime( $current_time );
		$data              = $midnight_next_day - $midnight_n_day;
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
			if ( 'yes' === wps_booking_get_meta_data( $item->get_product_id(), 'mwb_mbfw_admin_confirmation', true ) ) {
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
			if ( 'yes' === wps_booking_get_meta_data( $item->get_product_id(), 'mwb_mbfw_cancellation_allowed', true ) ) {

				$order_statuses = wps_booking_get_meta_data( $item->get_product_id(), 'mwb_bfwp_order_statuses_to_cancel', true );
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
	 * Undocumented function.
	 *
	 * @return void
	 */
	public function wps_bfw_cancelled_booked_order() {
		check_ajax_referer( 'mbfw_common_nonce', 'nonce' );

		$product_id = array_key_exists( 'product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$order_id   = array_key_exists( 'order_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : '';
		if ( ! empty( $product_id ) && ! empty( $order_id ) ) {

			$order          = wc_get_order( $order_id );
			$order_statuses = wps_booking_get_meta_data( $product_id, 'mwb_bfwp_order_statuses_to_cancel', true );
			$order_statuses = preg_replace( '/wc-/', '', $order_statuses );
			if ( in_array( $order->get_status(), $order_statuses, true ) ) {

				$order->update_status( 'wc-cancelled' );
				$order->save();
			}
		}
		wp_die();
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
			
			<span class="mwb-mbfw-ser-booking-toggler"></span>
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
					/**
					 * Filter is for returning something.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mwb_mbfw_people_user_booking_my_account', $item_id, $item, $order );
				}

				$services_and_count = $item->get_meta( '_mwb_mbfw_service_and_count', true );
				$product_id         = $item->get_product_id();

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
							   <?php echo esc_html( isset( $term->name ) ? $term->name : '' ); ?>
							</td>
						</tr>
						<?php
					}
				}
				$date_time_from     = $item->get_meta( '_mwb_bfwp_date_time_from', true );
				$date_time_to       = $item->get_meta( '_mwb_bfwp_date_time_to', true );
				$wps_date_time_from = $item->get_meta( '_wps_single_cal_date_time_from', true );
				$wps_date_time_to   = $item->get_meta( '_wps_single_cal_date_time_to', true );
				$wps_booking_dates  = $item->get_meta( '_wps_single_cal_booking_dates', true );

				if ( ! empty( $date_time_from ) && ! empty( $date_time_to ) ) {
					?>
					<tr>
						<th>
							<?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo esc_html( $date_time_from ); ?>
						</td>
						<td>
							<?php echo esc_html( $date_time_to ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							/**
							 * Filter is for returning something.
							 *
							 * @since 1.0.0
							 */
							do_action( 'mwb_mbfw_reschedule_user_booking_my_account', $item_id, $item, $order );
							?>
						</td>
						<td></td>
					</tr>
					<?php
				} elseif ( ! empty( $wps_date_time_from ) && ! empty( $wps_date_time_to ) ) {
					?>
					<tr>
						<th>
							<?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo esc_html( $wps_date_time_from ); ?>
						</td>
						<td>
							<?php echo esc_html( $wps_date_time_to ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							/**
							 * Filter is for returning something.
							 *
							 * @since 1.0.0
							 */
							do_action( 'mwb_mbfw_reschedule_user_booking_my_account', $item_id, $item, $order );
							?>
						</td>
						<td></td>
					</tr>
					<?php
				} else {
					?>
					<tr>
						<th>
							<?php esc_html_e( 'Booking Dates', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo esc_html( $wps_booking_dates ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
							/**
							 * Filter is for returning something.
							 *
							 * @since 1.0.0
							 */
							do_action( 'mwb_mbfw_reschedule_user_booking_my_account', $item_id, $item, $order );
							?>
						</td>
						<td></td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
	}

	/**
	 * Hide reorder button on the my account page.
	 *
	 * @param array $order_statuses array containing the order statuses.
	 * @since 2.0.2
	 * @return array
	 */
	public function mwb_mbfw_hide_reorder_button_my_account_orders( $order_statuses ) {
		return array();
	}

	/**
	 * Get cart items for max booking slot.
	 *
	 * @return void
	 */
	public function mwb_mbfw_get_cart_items() {
		check_ajax_referer( 'mbfw_common_nonce', 'nonce' );
		$product_id    = array_key_exists( 'product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$slot_selected = array_key_exists( 'slot_selected', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['slot_selected'] ) ) : '';
		$slot_left = array_key_exists( 'slot_left', $_POST ) ? $this->sanitize_text_associative_array( wp_unslash( $_POST['slot_left'] ) ) : array();// phpcs:ignore
		$cart          = WC()->cart->get_cart();
		$max           = '';
		$max           = ! empty( get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_for_hour', true ) ) ? get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_for_hour', true ) : '';

		if ( ! empty( $max ) ) {
			if ( ! empty( $cart ) ) {
				foreach ( $cart as $cart_item ) {
					if ( ( $cart_item['product_id'] == $product_id ) && ( $cart_item['mwb_mbfw_booking_values']['wps_booking_slot'] == $slot_selected ) ) {
						if ( ! empty( $slot_left ) && array_key_exists( $slot_selected, $slot_left ) ) {
							$max_limit = $slot_left[ $slot_selected ] - $cart_item['quantity'];
							break;
						} elseif ( $cart_item['mwb_mbfw_booking_values']['wps_booking_slot'] == $slot_selected ) {

							$max_limit = $max - $cart_item['quantity'];
							break;
						} else {
							$max_limit = $max - $cart_item['quantity'];
							break;
						}
					} else {
						if ( ! empty( $slot_left ) && array_key_exists( $slot_selected, $slot_left ) ) {

							$max_limit = $slot_left[ $slot_selected ];

						} else {
							$max_limit = $max;
						}
					}
				}
			} else {
				if ( ! empty( $slot_left ) && array_key_exists( $slot_selected, $slot_left ) ) {

					$max_limit = $slot_left[ $slot_selected ];
				} else {
					$max_limit = $max;

				}
			}
		} else {
			$max_limit = 'no';
		}

		echo esc_html( $max_limit );

		wp_die();

	}

	/**
	 * Function to sanitize associative array.
	 *
	 * @param array $array Array to sort the data.
	 * @return array
	 */
	public function sanitize_text_associative_array( $array ) {
		$sanitized_array = array();
		if ( empty( $array ) ) {
			return $sanitized_array;
		}

		foreach ( $array as $key => $value ) {

			// Sanitize text fields.
			$sanitized_array[ $key ] = sanitize_text_field( $value );
		}

		return $sanitized_array;
	}


	/**
	 * Rewnel order for subscription.
	 *
	 * @param [type] $target_order_id is the order type.
	 * @return bool
	 */
	public function wps_sfw_compatible_with_subscription( $target_order_id ) {

		$source_order_id = wps_booking_get_meta_data( $target_order_id, 'wps_sfw_parent_order_id', true );
		$source_order = wc_get_order( $source_order_id );
		if ( ! $source_order ) {
			return false;
		}

		// Get the target order.
		$target_order = wc_get_order( $target_order_id );
		if ( ! $target_order ) {
			return false;
		}

		// Loop through source order items.
		foreach ( $source_order->get_items() as $item_id => $item ) {
			// Get item meta.
			$line_item_meta = array(
				'_mwb_mbfw_service_and_count'    => wc_get_order_item_meta( $item_id, '_mwb_mbfw_service_and_count', true ),
				'_mwb_bfwp_date_time_from'       => wc_get_order_item_meta( $item_id, '_mwb_bfwp_date_time_from', true ),
				'_mwb_bfwp_date_time_to'         => wc_get_order_item_meta( $item_id, '_mwb_bfwp_date_time_to', true ),
				'_wps_single_cal_date_time_from' => wc_get_order_item_meta( $item_id, '_wps_single_cal_date_time_from', true ),
				'_wps_single_cal_date_time_to'   => wc_get_order_item_meta( $item_id, '_wps_single_cal_date_time_to', true ),
				'_wps_single_cal_booking_dates'  => wc_get_order_item_meta( $item_id, '_wps_single_cal_booking_dates', true ),
				'_wps_booking_slot'              => wc_get_order_item_meta( $item_id, '_wps_booking_slot', true ),
			);

			// Find matching product in the target order.
			foreach ( $target_order->get_items() as $target_item_id => $target_item ) {
				if ( $target_item->get_product_id() === $item->get_product_id() ) {
					// Copy meta to the existing target item.
					foreach ( $line_item_meta as $meta_key => $meta_value ) {
						if ( ! empty( $meta_value ) ) {
							wc_update_order_item_meta( $target_item_id, $meta_key, $meta_value );
						}
					}
				}
			}
		}

		$order_type = wps_booking_get_meta_data( $source_order_id, 'mwb_order_type', true );
		if ( ! empty( $order_type ) ) {
			wps_booking_update_meta_data( $target_order_id, 'mwb_order_type', 'booking' );
		}

	}

	/**
	 * Booking confirmation in case of subscription.
	 *
	 * @param [type] $wps_new_order new recurring order.
	 * @param [type] $subscription_id subscription id.
	 * @param [type] $payment_method payment method used.
	 * @return void
	 */
	public function wps_bfw_after_renewal_payment( $wps_new_order, $subscription_id, $payment_method ) {

		if ( ! $wps_new_order ) {
			return;
		}

		if ( 'on-hold' === $wps_new_order->get_status() ) {
			return;
		}
		$is_confirmation_allowed = 'false';
		$items = $wps_new_order->get_items();
		foreach ( $items as $item ) {
			if ( 'yes' === wps_booking_get_meta_data( $item->get_product_id(), 'mwb_mbfw_admin_confirmation', true ) ) {
				$wps_new_order->update_status( 'on-hold', __( 'confirmation required from admin.', 'mwb-bookings-for-woocommerce' ) );
				$is_confirmation_allowed = 'true';
				break;
			}
		}
	}

}
