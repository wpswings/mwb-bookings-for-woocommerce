<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public
 */

use Automattic\WooCommerce\StoreApi\Utilities\QuantityLimits;
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
	 * @since    2.0.0
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
	 * @since    2.0.0
	 */
	public function mbfw_public_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/css/mwb-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/css/datepicker.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'flatpickercss', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/flatpickr/dist/flatpickr.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Function to check that the current device is a mobile device or not.
	 *
	 * @return mixed
	 */
	public function mwb_is_mobile_device() {
		$user_ag = '';
		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_ag = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
			if ( preg_match( '/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis', $user_ag ) ) {
				return true;
			};
		};
		return false;
	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 2.0.0
	 */
	public function mbfw_public_enqueue_scripts() {

		// Check if the device is mobile.
		if ( $this->mwb_is_mobile_device() ) {
			$is_mobile_site = 'mobile';
		} else {
			$is_mobile_site = 'desktop';
		}
		$wps_lang = get_option( 'mwb_mbfw_select_language_for_calendar', 'default' );

		wp_enqueue_script( 'flatpicker_js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/flatpickr/dist/flatpickr.min.js', array( 'jquery' ), time(), true );

		wp_enqueue_script( 'wps-flatpickr-locale', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/flatpickr/dist/l10n/' . $wps_lang . '.js', array( 'flatpicker_js' ), time(), true );

		wp_enqueue_script( $this->plugin_name . 'public', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-public.js', array( 'jquery', 'flatpicker_js', 'wps-flatpickr-locale' ), time(), true );
		$daily_start_time                            = '';
		$daily_end_time                              = '';
		$upcoming_holiday                            = '';
		$booking_product                             = '';
		$wps_cal_type                                = '';
		$wps_available_slots                         = '';
		$booking_unit                                = '';
		$is_pro_active                               = '';
		$booking_slot_array                          = array();
		$booking_unavailable                         = array();
		$single_available_dates                      = array();
		$single_availables_till                      = array();
		$single_unavailable_dates                    = array();
		$single_unavailable_prices                   = array();
		$wps_single_dates_temp                       = array();
		$wps_single_dates_temp_dual                  = array();
		$current_year                                = gmdate( 'Y' );
		$currentday                                  = gmdate( 'd' );
		$current_month                               = gmdate( 'm' );
		$date_array                                  = array();
		$mwb_mbfw_show_date_with_time                = '';
		$today_date_check                            = '';
		$wps_mbfw_day_and_days_upto_togather_enabled = '';
		$booking_slot_array_max_limit                = array();
		// Get the number of days in the current month.
		$num_days         = cal_days_in_month( CAL_GREGORIAN, $current_month, $current_year );
		$today_date_check = sprintf( '%04d-%02d-%02d', $current_year, $current_month, $currentday );
		// Loop through the days of the current month and add them to the array.
		for ( $day = $currentday; $day <= $num_days; $day++ ) {
			// Construct the date in 'Y-m-d' format.
			$date = sprintf( '%04d-%02d-%02d', $current_year, $current_month, $day );
			// Add the date to the array.
			$date_array[] = $date;
		}
		if ( 12 == $current_month ) {
			$current_month = 01;
			++$current_year;
		} else {
			++$current_month;

		}
		$num_days_next_month = cal_days_in_month( CAL_GREGORIAN, $current_month, $current_year );

		// Initialize an empty array to store the dates.

		for ( $day = 1; $day <= $num_days_next_month; $day++ ) {
			// Construct the date in 'Y-m-d' format.
			$date = sprintf( '%04d-%02d-%02d', $current_year, $current_month, $day );
			// Add the date to the array.
			$date_array[] = $date;
		}
		if ( is_single() || is_page() ) {
			global $post;
			$product_id = '';
			if ( is_page() && has_shortcode( $post->post_content, 'product_page' ) ) {
				// Scan the content for product shortcodes to extract the product ID.
				$pattern = get_shortcode_regex();
				if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER ) ) {
					foreach ( $matches as $shortcode ) {
						// Look for [product] or [product_page] shortcodes.
						if ( in_array( $shortcode[2], array( 'product_page' ) ) ) {
							$attrs = shortcode_parse_atts( $shortcode[3] );

							if ( isset( $attrs['id'] ) ) {
								$product = wc_get_product( $attrs['id'] );

								// Check if the product type matches.
								if ( $product && $product->get_type() === 'mwb_booking' ) { // Replace 'specific_type' with the desired type.
									// Enqueue the script if the product type matches.
									$product_id = $product->get_id();
									break;
									// Stop further processing once the script is enqueued.
								}
							}
						}
					}
				}
			} else {
				$product_id = $post->ID;
			}
			$temp_product                 = wc_get_product( $product_id );
			$mwb_mbfw_show_date_with_time = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_show_date_with_time', true );
			if ( ! empty( $temp_product ) ) {

				if ( 'mwb_booking' == $temp_product->get_type() ) {
					$daily_start_time = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_daily_calendar_start_time', true );
					$daily_end_time   = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_daily_calendar_end_time', true );
					$upcoming_holiday = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_choose_holiday', true );
					$upcoming_holiday = gmdate( 'Y-m-d', strtotime( $upcoming_holiday ) );
					if ( 'yes' == get_option( 'mwb_mbfw_disable_book_now' ) ) {

						$booking_product = 'yes';
					}
					$active_plugins = get_option( 'active_plugins' );

					if ( in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) {
						$is_pro_active = 'yes';
					}
					$wps_cal_type        = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
					$wps_available_slots = wps_booking_get_meta_data( $product_id, 'wps_mbfw_time_slots', true );
					$booking_unit        = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true );
					$active_plugins      = get_option( 'active_plugins' );

						$booking_type                = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
						$single_availables           = wps_booking_get_meta_data( $product_id, 'wps_mbfw_set_availability', true );
						$single_available_date_array = explode( ' ', $single_availables );
					if ( ! empty( $single_available_date_array ) && is_array( $single_available_date_array ) ) {
						foreach ( $single_available_date_array as $key => $values ) {

							if ( ! empty( $values ) && ( strtotime( $values ) < strtotime( current_time( 'Y-m-d' ) ) ) ) {
								continue;
							}
							$single_available_dates[] = gmdate( 'Y-m-d', strtotime( $values ) );
							$key                      = 'wps_mbfw_unit_' . gmdate( 'd-M-Y', strtotime( $values ) );

							if ( $is_pro_active ) {

								$price = get_post_meta( $product_id, $key, true );
								if ( ! empty( $price ) ) {
									$date_price                               = gmdate( 'Y-m-d', strtotime( $values ) );
									$date_price                               = str_replace( ',', '', $date_price );
									$currency_symbol                          = get_woocommerce_currency();
									$single_unavailable_prices[ $date_price ] = $currency_symbol . ' ' . $price;

								}
							}
						}
					}

					if ( 'single_cal' === $booking_type ) {

						if ( in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) {
							$_orders = '';

							$is_hour_disabled = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_max_limit_for_hour_check_box', true );

							if ( 'yes' == $is_hour_disabled ) {

								$_orders = wc_get_orders(
									array(
										'status'     => array( 'wc-processing', 'wc-on-hold', 'wc-pending' ),
										'limit'      => -1,
										'meta_key'   => 'mwb_order_type',
										'meta_value' => 'booking',
									)
								);
							} else {

								$one_hour_ago = gmdate( 'Y-m-d H:i:s', strtotime( '-1 hour' ) );

								$_orders = wc_get_orders(
									array(
										'status'     => array( 'wc-processing', 'wc-on-hold', 'wc-pending' ),
										'limit'      => -1,
										'meta_key'   => 'mwb_order_type',
										'meta_value' => 'booking',
										'date_query' => array(
											array(
												'column' => 'date_created_gmt',
												'after'  => $one_hour_ago,
												'inclusive' => true,
											),
										),
									)
								);

							}

							if ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {

								foreach ( $_orders as $order ) {

									$items = $order->get_items();

									foreach ( $items as $item ) {
										if ( $product_id == $item['product_id'] ) {
											$quantity         = $item['quantity'];
											$wps_booking_slot = $item->get_meta( '_wps_booking_slot', true );

											if ( ! empty( $wps_booking_slot ) ) {

												if ( key_exists( $wps_booking_slot, $booking_slot_array ) ) {
													$booking_slot_array[ $wps_booking_slot ] += $quantity;
												} else {
													$booking_slot_array[ $wps_booking_slot ] = $quantity;
												}
											}
										}
									}
								}
							} else {
								if ( $is_pro_active ) {
									$bfwp_plugin_public    = new Bookings_For_Woocommerce_Pro_Public( '', '' );
									$wps_single_dates_temp = $bfwp_plugin_public->mbfw_get_all_dtes_booking_occurence__( $product_id );
								}
							}

							$max_limit = '';

							if ( ! empty( get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true ) ) ) {

								$max_limit = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true );
							} elseif ( ! empty( get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true ) ) ) {
								$max_limit = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit', true );

							} else {
								$max_limit = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_for_hour', true );
							}

							if ( ! empty( $max_limit ) && ! empty( $booking_slot_array ) ) {
								foreach ( $booking_slot_array as $key => $values ) {
									if ( $values >= $max_limit ) {
										$booking_unavailable[] = $key;

									} else {
										$limit                                = $max_limit - $values;
										$booking_slot_array_max_limit[ $key ] = $limit;
									}
								}
							}

							$max_limit_days = '';
							if ( ! empty( get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true ) ) ) {
								$max_limit_days = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true );
							} else {
								$max_limit_days = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit', true );
							}

							if ( ! empty( $max_limit_days ) && ! empty( $wps_single_dates_temp ) ) {
								foreach ( $wps_single_dates_temp as $k => $v ) {

									if ( $v >= $max_limit_days ) {

										$k = gmdate( 'Y-m-d', strtotime( $k ) );

										$key = 'wps_mbfw_unit_' . gmdate( 'd-M-Y', strtotime( $k ) );

										$single_unavailable_dates[] = $k;
										if ( $is_pro_active ) {

											$price = get_post_meta( $product_id, $key, true );

											$single_unavailable_prices[ $k ] = $price;
										}
									}
								}
							}
						}
					} elseif ( 'dual_cal' === $booking_type ) {
						if ( $is_pro_active ) {
							$bfwp_plugin_public         = new Bookings_For_Woocommerce_Pro_Public( '', '' );
							$wps_single_dates_temp_dual = $bfwp_plugin_public->mbfw_get_all_dtes_booking_occurence_dual( $product_id );
						}
						$max_limit_days = '';
						if ( ! empty( get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true ) ) ) {
							$max_limit_days = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit_person', true );
						} else {
							$max_limit_days = get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit', true );
						}

						if ( ! empty( $max_limit_days ) && ! empty( $wps_single_dates_temp_dual ) ) {
							foreach ( $wps_single_dates_temp_dual as $k => $v ) {

								if ( $v >= $max_limit_days ) {

									$k = gmdate( 'Y-m-d', strtotime( $k ) );

									$key = 'wps_mbfw_unit_' . gmdate( 'd-M-Y', strtotime( $k ) );

									$single_unavailable_dates[] = $k;
									if ( $is_pro_active ) {

										$price = get_post_meta( $product_id, $key, true );

										$single_unavailable_prices[ $k ] = $price;
									}
								}
							}
						}
					}
				}
			}
			$single_availables_till = wps_booking_get_meta_data( $product_id, 'wps_mbfw_set_availability_upto', true );
		}
		$wps_mbfw_day_and_days_upto_togather_enabled = wps_booking_get_meta_data( get_the_ID(), 'wps_mbfw_day_and_days_upto_togather_enabled', true );

		if ( empty( $single_availables_till ) ) {

			if ( ! empty( $single_available_dates ) ) {

				if ( '1970-01-01' == $single_available_dates[0] ) {

					$single_available_dates = $date_array;
					foreach ( $single_available_dates as $key => $values ) {
						$single_available_dates[] = gmdate( 'Y-m-d', strtotime( $values ) );
						$key                      = 'wps_mbfw_unit_' . gmdate( 'd-M-Y', strtotime( $values ) );

						if ( $is_pro_active ) {

							$price = get_post_meta( $product_id, $key, true );
							if ( ! empty( $price ) ) {
								$date_price                               = gmdate( 'n/d/Y', strtotime( $values ) );
								$date_price                               = str_replace( ',', '', $date_price );
								$currency_symbol                          = get_woocommerce_currency();
								$single_unavailable_prices[ $date_price ] = $currency_symbol . ' ' . $price;

							}
						}
					}
				}
			}
		}
		wp_localize_script(
			$this->plugin_name . 'public',
			'mwb_mbfw_public_obj',
			array(
				'today_date'                   => current_time( 'd-m-Y' ),
				'wrong_order_date_1'           => __( 'To date can not be less than from date.', 'mwb-bookings-for-woocommerce' ),
				'wrong_order_date_2'           => __( 'From date can not be greater than To date.', 'mwb-bookings-for-woocommerce' ),
				'daily_start_time'             => $daily_start_time,
				'daily_end_time'               => $daily_end_time,
				'upcoming_holiday'             => array( $upcoming_holiday ),
				'is_pro_active'                => $is_pro_active,
				'booking_product'              => $booking_product,
				'wps_cal_type'                 => $wps_cal_type,
				'wps_available_slots'          => $wps_available_slots,
				'booking_unit'                 => $booking_unit,
				'booking_unavailable'          => $booking_unavailable,
				'single_available_dates'       => $single_available_dates,
				'single_available_dates_till'  => $single_availables_till,
				'today_date_check'             => $today_date_check,
				'single_unavailable_dates'     => $single_unavailable_dates,
				'date_format'                  => get_option( 'date_format' ),
				'single_unavailable_prices'    => $single_unavailable_prices,
				'wps_single_dates_temp'        => $wps_single_dates_temp,
				'wps_single_dates_temp_dual'   => $wps_single_dates_temp_dual,
				'mwb_mbfw_show_date_with_time' => $mwb_mbfw_show_date_with_time,
				'booking_slot_array_max_limit' => $booking_slot_array_max_limit,
				'validation_message'           => __( 'Please select valid date!', 'mwb-bookings-for-woocommerce' ),
				'is_mobile_device'             => $is_mobile_site,
				'wps_mbfw_day_and_days_upto_togather_enabled' => $wps_mbfw_day_and_days_upto_togather_enabled,
				'wps_diaplay_time_format' => wps_booking_get_meta_data( get_the_ID(), 'mwb_mbfw_booking_time_fromat', true ),
				'firstDayOf_Week' => get_option( 'mwb_mbfw_select_first_day_of_week' ),
				'lang' => $wps_lang,
			)
		);

	}


	/**
	 * Adding custom fields before add to cart button.
	 *
	 * @return void
	 */
	public function mbfw_add_custom_fields_before_add_to_cart_button() {
		global $product;

		if ( is_object( $product ) && 'mwb_booking' === $product->get_type() ) {
			require_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/templates/mwb-bookings-for-woocommerce-public-add-to-cart-form.php';
		}
	}

	/**
	 * Check if we are in the booking hours.
	 *
	 * @return bool
	 */
	public function mwb_mbfw_is_enable_booking() {
		$check = get_option( 'mwb_mbfw_enable_availibility_setting' );
		if ( 'yes' == $check ) {

			$start_time = get_option( 'mwb_mbfw_daily_start_time' );
			$end_time   = get_option( 'mwb_mbfw_daily_end_time' );
			if ( strtotime( $start_time ) <= strtotime( current_time( 'H:i' ) ) && strtotime( current_time( 'H:i' ) ) <= strtotime( $end_time ) && 'yes' === get_option( 'mwb_mbfw_is_booking_enable' ) ) {
				return true;
			}
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Loading single product template for our custom product type.
	 *
	 * @return void
	 */
	public function mwb_mbfw_load_single_page_template() {

		$is_booking_available =
		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'mwb_mbfw_is_booking_available_filter', $this->mwb_mbfw_is_enable_booking() );
		if ( $is_booking_available ) {
			/**
			 * Template for Booking Product Type.
			 *
			 * @since 1.0.0
			 */
			do_action( 'woocommerce_simple_add_to_cart' );
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

	/**
	 * Show additional booking services on form.
	 *
	 * @param int    $product_id current product id.
	 * @param object $product current product object from the loop.
	 * @return void
	 */
	public function mwb_mbfw_show_additional_booking_services_details_on_form( $product_id, $product ) {
		if ( 'yes' === get_option( 'mwb_mbfw_is_show_included_service' ) && 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_add_extra_services', true ) ) {
			$mbfw_booking_service = get_the_terms( $product_id, 'mwb_booking_service' );
			if ( $mbfw_booking_service && is_array( $mbfw_booking_service ) ) {
				?>
				<div class="mwb_mbfw_included_service_title"><?php esc_html_e( 'Additional services', 'mwb-bookings-for-woocommerce' ); ?></div>
				<div class="mbfw-additionl-detail-listing-section__wrapper">
					<?php
					foreach ( $mbfw_booking_service as $custom_term ) {
						if ( 'yes' !== get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_hidden', true ) ) {
							?>
							<div class="mwb_mbfw_detail-listing-wrap">
								<div class="mbfw-additionl-detail-listing-section">
									<?php if ( 'yes' === get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_optional', true ) ) { ?>
										<input type="checkbox" value="<?Php echo esc_attr( $custom_term->term_id ); ?>" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_option_checkbox[]" id="mwb-mbfw-service-option-checkbox-<?php echo esc_attr( $custom_term->term_id ); ?>" class="mwb-mbfw-additional-service-option" />
										<?php
									}
									?>
									<span title="
									<?php
									echo esc_html(
										/**
										 * Filter is for returning something.
										 *
										 * @since 1.0.0
										 */
										do_action( 'mbfw_add_tooltip_show_additional_details', $custom_term->term_id, 'mwb_booking_service' )
									);
									?>
									" >
										<?php echo esc_html( $custom_term->name ); ?>
									</span>
								</div>
								<div class="mbfw-additionl-detail-listing-section">
									<?php echo wp_kses_post( wc_price( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_cost', true ) ) ); ?>
								</div>
								<div class="mbfw-additionl-detail-listing-section">
									<?php if ( get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_has_quantity', true ) ) { ?>
										<input type="number" value="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ); ?>" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_quantity[<?php echo esc_attr( $custom_term->term_id ); ?>]" min="<?php echo ! empty( esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ) ) ? esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ) : 0; ?>" max="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_maximum_quantity', true ) ); ?>" class="mwb-mbfw-additional-service-quantity" />
									<?php } ?>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Show People while booking.
	 *
	 * @param int    $product_id current product id.
	 * @param object $product current product object in the loop.
	 * @return void
	 */
	public function mwb_mbfw_show_people_while_booking( $product_id, $product ) {
		if ( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_people_option', true ) ) {
			$file = MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/templates/mwb-bookings-for-woocommerce-public-show-people-option.php';
			$path = 'public/templates/mwb-bookings-for-woocommerce-public-show-people-option.php';
			require_once /**
			 * Filter is for returning something.
			 *
			 * @since 1.0.0
			 */
			apply_filters( 'mbfw_load_people_option_template', $file, $path );
		}
	}

	/**
	 * Add date selector on single product listing page.
	 *
	 * @param int    $product_id current product id.
	 * @param object $product current product object.
	 * @return void
	 */
	public function mwb_mbfw_show_date_time_selector_on_single_product_page( $product_id, $product ) {
		$class            = false;
		$class2           = '';
		$accepted_pattern = '';
		$attr             = '';
		wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true );
		if ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {

			$label1 = __( 'From', 'mwb-bookings-for-woocommerce' );
			$label2 = __( 'To', 'mwb-bookings-for-woocommerce' );
			$class  = 'mwb_mbfw_time_date_picker_frontend';
			$class2 = 'wps_single_cal_hourly';

			$accepted_pattern = '(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$';
		} elseif ( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
			$label1           = __( 'Check in', 'mwb-bookings-for-woocommerce' );
			$label2           = __( 'Check out', 'mwb-bookings-for-woocommerce' );
			$class            = 'mwb_mbfw_date_picker_frontend';
			$attr             = 'data-id="multiple"';
			$accepted_pattern = '(\d{2})-(\d{2})-(\d{4})$';

			if ( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_show_date_with_time', true ) ) {

				$label1           = __( 'From', 'mwb-bookings-for-woocommerce' );
				$label2           = __( 'To', 'mwb-bookings-for-woocommerce' );
				$class            = 'mwb_mbfw_time_date_picker_frontend';
				$accepted_pattern = '(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$';
			}
		}

		if ( $class ) {
			do_action( 'wps_mbfw_add_html_before_calender' );
			$wps_cal_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );

			?>
			
			<div class="mbfw-date-picker-section__wrapper">
				<?php
				if ( 'single_cal' === $wps_cal_type ) {
					?>
					<div class="mbfw-date-picker-section">
						<label for="wps_booking_single_calendar_form"><?php esc_html_e( 'Choose Booking date', 'mwb-bookings-for-woocommerce' ); ?></label>
						<!-- -->
						<?php
						if ( ! empty( $attr ) ) {
							?>
							<input id="wps_booking_single_calendar_form_" name="wps_booking_single_calendar_form" <?php echo esc_attr( $attr ); ?>  class="flatpickr flatpickr-input active"  type="text" placeholder="<?php echo esc_attr__( 'Choose date', 'mwb-bookings-for-woocommerce' ); ?>" readonly="readonly" >
							<?php
						} else {
							?>
							 <input type="text" name="wps_booking_single_calendar_form" id="wps_booking_single_calendar_form" class="<?php echo esc_attr( $class2 ); ?>" autocomplete="off" placeholder="<?php echo esc_attr__( 'Choose date', 'mwb-bookings-for-woocommerce' ); ?>"  required  readonly="readonly" />
							<?php
						}

						?>
						
						
					
					</div>

				<?php } else { ?>
					
						<div class="mbfw-date-picker-section">
							<label for="mwb-mbfw-booking-from-time"><?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?></label>
							<input id="mwb-mbfw-booking-from-time" name="mwb_mbfw_booking_from_time"   class="flatpickr flatpickr-input active <?php echo esc_attr( $class2 ); ?>"  type="text" placeholder="<?php echo esc_attr__( 'Choose date', 'mwb-bookings-for-woocommerce' ); ?>" readonly="readonly">			
						</div>
						<div class="mbfw-date-picker-section">
							<label for="mwb-mbfw-booking-to-time"><?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?></label>
							<input id="mwb-mbfw-booking-to-time" name="mwb_mbfw_booking_to_time"   class="flatpickr flatpickr-input active  <?php echo esc_attr( $class2 ); ?>"  type="text" placeholder="<?php echo esc_attr__( 'Choose date', 'mwb-bookings-for-woocommerce' ); ?>" readonly="readonly">
						</div>
					
					<?php
				}
				?>
				
			</div>
			<?php
		}

	}

	/**
	 * Add additional data in the cart.
	 *
	 * @param array $cart_item_data array containing cart items.
	 * @param int   $product_id product id of the added prouct.
	 * @param int   $variation_id variation product id.
	 * @param int   $quantity quantity of the product.
	 * @return array
	 */
	public function mwb_mbfw_add_additional_data_in_cart( $cart_item_data, $product_id, $variation_id, $quantity ) {
		$product = wc_get_product( $product_id );
		if ( is_object( $product ) && 'mwb_booking' === $product->get_type() ) {
			if ( ! isset( $_POST['_mwb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_mwb_nonce'] ) ), 'mwb_booking_frontend' ) ) {
				return;
			}

			$product_id               = array_key_exists( 'mwb_mbfw_booking_product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_product_id'] ) ) : '';
			$booking_type             = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
			$single_cal_booking_dates = '';
			$date_time_from           = '';
			$date_time_to             = '';
			$booking_slot             = '';
			if ( 'single_cal' === $booking_type ) {
				$date_format              = get_option( 'date_format' );
				$single_cal_booking_dates = array_key_exists( 'wps_booking_single_calendar_form', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_booking_single_calendar_form'] ) ) : '';
				if ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
					$booking_dates = explode( ' ', $single_cal_booking_dates );
					if ( ! empty( $booking_dates[0] ) ) {

						if ( isset( $booking_dates[1] ) ) {
							if ( 'twelve_hour' == wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_time_fromat', true ) ) {
								$date = $booking_dates[0];
								$start_time = $booking_dates[1] . $booking_dates[2]; // 11:30 PM.
								$end_time = $booking_dates[4] . $booking_dates[5];   // 12:30 AM.

								// Convert start and end times to 24-hour format for comparison.
								$start_24 = gmdate( 'H:i', strtotime( $start_time ) );
								$end_24 = gmdate( 'H:i', strtotime( $end_time ) );

								// If end time is smaller, it means it's past midnight, so move to the next day.
								$end_date = ( $end_24 < $start_24 ) ? gmdate( 'Y-m-d', strtotime( $date . ' +1 day' ) ) : $date;

								// Format final date-time values.
								$date_time_from = gmdate( $date_format, strtotime( $date ) ) . ' ' . $start_time;
								$date_time_to = gmdate( $date_format, strtotime( $end_date ) ) . ' ' . $end_time;
							} else {
								$date = $booking_dates[0];
								$start_time = $booking_dates[1]; // 11:30
								$end_time = $booking_dates[3];   // 12:30

								// Convert start and end times to 24-hour format for comparison.
								$start_24 = gmdate( 'H:i', strtotime( $start_time ) );
								$end_24 = gmdate( 'H:i', strtotime( $end_time ) );

								// If end time is smaller, it means it's past midnight, so move to the next day.
								$end_date = ( $end_24 < $start_24 ) ? gmdate( 'Y-m-d', strtotime( $date . ' +1 day' ) ) : $date;

								$date_time_from = gmdate( $date_format, strtotime( $date ) ) . ' ' . $start_time;
								$date_time_to   = gmdate( $date_format, strtotime( $end_date ) ) . ' ' . $end_time;
							}
						}
					}
					$booking_slot             = $single_cal_booking_dates;
					$single_cal_booking_dates = '';

				} else {
					$booking_dates = explode( ',', $single_cal_booking_dates );

					foreach ( $booking_dates as $key => $value ) {
						$booking_dates[ $key ] = gmdate( $date_format, strtotime( $value ) );
					}
					$single_cal_booking_dates = implode( ' | ', $booking_dates );

				}
			} else {
				if ( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
					$date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
				} else {
					if ( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_show_date_with_time', true ) ) {
						$date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
					} else {
						$date_format = get_option( 'date_format' );
					}
				}
			}

			$custom_data = array(
				'people_number'             => array_key_exists( 'mwb_mbfw_people_number', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_people_number'] ) ) : '',
				'service_option'            => array_key_exists( 'mwb_mbfw_service_option_checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_option_checkbox'] ), 'sanitize_text_field' ) : array(),
				'service_quantity'          => array_key_exists( 'mwb_mbfw_service_quantity', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_quantity'] ), 'sanitize_text_field' ) : array(),
				'date_time_from'            => array_key_exists( 'mwb_mbfw_booking_from_time', $_POST ) ? gmdate( $date_format, strtotime( sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_from_time'] ) ) ) ) : $date_time_from,
				'date_time_to'              => array_key_exists( 'mwb_mbfw_booking_to_time', $_POST ) ? gmdate( $date_format, strtotime( sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_to_time'] ) ) ) ) : $date_time_to,
				'single_cal_booking_dates'  => $single_cal_booking_dates,
				'single_cal_date_time_from' => $date_time_from,
				'single_cal_date_time_to'   => $date_time_to,
				'wps_booking_slot'          => $booking_slot,
			);

			$custom_data =
			/**
			 * Filter is for returning something.
			 *
			 * @since 1.0.0
			 */
			apply_filters( 'mbfw_add_extra_custom_details_in_cart', $custom_data );
			$cart_item_data['mwb_mbfw_booking_values'] = $custom_data;

		}
		return $cart_item_data;
	}

	/**
	 * Show addiditional data on cart and checkout page.
	 *
	 * @param array $other_data array containing other data.
	 * @param array $cart_item array containing cart items.
	 * @return array
	 */
	public function mwb_mbfw_show_additional_data_on_cart_and_checkout_page( $other_data, $cart_item ) {
		if ( isset( $cart_item['mwb_mbfw_booking_values'] ) ) {
				$custom_cart_data = $cart_item['mwb_mbfw_booking_values'];
			if ( ! empty( $custom_cart_data['people_number'] ) ) {
				$other_data[] = array(
					'name'    => _n( 'People', 'Peoples', $custom_cart_data['people_number'], 'mwb-bookings-for-woocommerce' ),
					'display' => wp_kses_post( $custom_cart_data['people_number'] ),
				);
			}
			$terms            = get_the_terms( $cart_item['product_id'], 'mwb_booking_service' );
			$service_name     = array();
			$service_quantity = isset( $custom_cart_data['service_quantity'] ) ? $custom_cart_data['service_quantity'] : array();
			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( 'yes' !== get_term_meta( $term->term_id, 'mwb_mbfw_is_service_optional', true ) ) {
						$service_count  = array_key_exists( $term->term_id, $service_quantity ) ? $service_quantity[ $term->term_id ] : 1;
						$service_name[] = isset( $term->name ) ? $term->name . '( ' . $service_count . ' )' : __( 'not found', 'mwb-bookings-for-woocommerce' );
					}
				}
			}
			if ( ! empty( $custom_cart_data['service_option'] ) ) {
				$selected_services = $custom_cart_data['service_option'];
				if ( is_array( $selected_services ) ) {
					foreach ( $selected_services as $term_id ) {
						$term           = get_term( $term_id );
						$service_count  = array_key_exists( $term_id, $service_quantity ) ? $service_quantity[ $term_id ] : 1;
						$service_name[] = isset( $term->name ) ? $term->name . '( ' . $service_count . ' )' : __( 'not found', 'mwb-bookings-for-woocommerce' );
					}
				}
			}
			if ( $service_name ) {
				$other_data[] = array(
					'name'    => _n( 'Service', 'Services', count( $service_name ), 'mwb-bookings-for-woocommerce' ),
					'display' => join( ', ', $service_name ),
				);
			}
			if ( ! empty( $custom_cart_data['date_time_from'] ) && ! empty( $custom_cart_data['date_time_to'] ) ) {
				$other_data[] = array(
					'name'    => __( 'From', 'mwb-bookings-for-woocommerce' ),
					'display' => wp_kses_post( $custom_cart_data['date_time_from'] ),
				);
				$other_data[] = array(
					'name'    => __( 'To', 'mwb-bookings-for-woocommerce' ),
					'display' => wp_kses_post( $custom_cart_data['date_time_to'] ),
				);
			}
			if ( ! empty( $custom_cart_data['single_cal_booking_dates'] ) ) {
				$other_data[] = array(
					'name'    => __( 'Booking Dates', 'mwb-bookings-for-woocommerce' ),
					'display' => wp_kses_post( $custom_cart_data['single_cal_booking_dates'] ),
				);
			}
			$other_data =
			/**
			 * Filter is for returning something.
			 *
			 * @since 1.0.0
			 */
			apply_filters( 'mbfw_show_additional_details_on_cart_and_checkout_pro', $other_data, $custom_cart_data, $cart_item );
		}
		return $other_data;
	}

	/**
	 * Show read more button on archieve page.
	 *
	 * @param string $button string containing html of the add to cart button.
	 * @param object $product product object of the current looping item.
	 * @return string
	 */
	public function mwb_mbfw_show_readmore_button_on_archieve_page( $button, $product ) {
		if ( 'mwb_booking' === $product->get_type() ) {
			$button_text = __( 'View Details', 'mwb-bookings-for-woocommerce' );
			$button      = '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
		}
		return $button;
	}

	/**
	 * Set maximum and minimum booking quantity per product.
	 *
	 * @param array  $args array containing attributes of the html filed for count.
	 * @param object $product current product object.
	 * @return array
	 */
	public function mwb_mbfw_set_max_quantity_to_be_booked_by_individual( $args, $product ) {
		if ( 'mwb_booking' === $product->get_type() ) {
			if ( 'fixed_unit' === wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_booking_criteria', true ) ) {
				$booking_count     = wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_booking_count', true );
				$args['min_value'] = $booking_count;
				$args['max_value'] = $booking_count;
			} else {
				$args['max_value'] = wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_maximum_booking_per_unit', true );
			}
		}

		return $args;
	}

	/**
	 * Updating additional meta data with products in line items.
	 *
	 * @param object $item object containing the item details.
	 * @param string $cart_item_key string containing arbitrary key of cart items.
	 * @param array  $values array containing the values for the cart item key.
	 * @param object $order current order object.
	 * @return void
	 */
	public function mwb_mbfw_add_custom_order_item_meta_data( $item, $cart_item_key, $values, $order ) {
		$custom_values = $item->legacy_values;
		$product       = $item->get_product();
		$product_type  = $product->get_type();
		if ( 'mwb_booking' === $product_type && isset( $custom_values['mwb_mbfw_booking_values'] ) ) {
			$custom_booking_values                     = $custom_values['mwb_mbfw_booking_values'];
			$line_item_meta                            = array();
			$line_item_meta['_mwb_mbfw_people_number'] = isset( $custom_booking_values['people_number'] ) ? $custom_booking_values['people_number'] : 1;
			$terms                                     = get_the_terms( $custom_values['product_id'], 'mwb_booking_service' );
			$service_quantity                          = isset( $custom_booking_values['service_quantity'] ) ? $custom_booking_values['service_quantity'] : array();
			$service_id_and_quant                      = array();

			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( 'yes' !== get_term_meta( $term->term_id, 'mwb_mbfw_is_service_optional', true ) ) {
						$service_count                          = array_key_exists( $term->term_id, $service_quantity ) ? $service_quantity[ $term->term_id ] : 1;
						$service_id_and_quant[ $term->term_id ] = $service_count;
					}
				}
			}

			if ( isset( $custom_booking_values['service_option'] ) ) {
				$selected_services = $custom_booking_values['service_option'];
				if ( is_array( $selected_services ) ) {
					foreach ( $selected_services as $term_id => $is_selected ) {
						$term                                 = get_term( $is_selected );
						$service_count                        = array_key_exists( $is_selected, $service_quantity ) ? $service_quantity[ $is_selected ] : 1;
						$service_id_and_quant[ $is_selected ] = $service_count;

					}
				}
			}

			$date_format                                      = get_option( 'date_format' );
			$line_item_meta['_mwb_mbfw_service_and_count']    = $service_id_and_quant;
			$line_item_meta['_mwb_bfwp_date_time_from']       = isset( $custom_booking_values['date_time_from'] ) ? $custom_booking_values['date_time_from'] : '';
			$line_item_meta['_mwb_bfwp_date_time_to']         = isset( $custom_booking_values['date_time_to'] ) ? $custom_booking_values['date_time_to'] : '';
			$line_item_meta['_wps_single_cal_date_time_from'] = isset( $custom_booking_values['single_cal_date_time_from'] ) ? $custom_booking_values['single_cal_date_time_from'] : '';
			$line_item_meta['_wps_single_cal_date_time_to']   = isset( $custom_booking_values['single_cal_date_time_to'] ) ? $custom_booking_values['single_cal_date_time_to'] : '';
			$line_item_meta['_wps_single_cal_booking_dates']  = isset( $custom_booking_values['single_cal_booking_dates'] ) ? $custom_booking_values['single_cal_booking_dates'] : '';
			$line_item_meta['_wps_booking_slot']              = isset( $custom_booking_values['wps_booking_slot'] ) ? $custom_booking_values['wps_booking_slot'] : '';
			$terms = get_the_terms( $custom_values['product_id'], 'mwb_booking_cost' );
			if ( $terms && is_array( $terms ) ) {
				$term_ids = array();
				foreach ( $terms as $term ) {
					$term_ids[] = $term->term_id;
				}
				$line_item_meta['_mwb_mbfw_booking_extra_costs'] = $term_ids;
			}
			$line_item_meta =
			/**
			 * Filter is for returning something.
			 *
			 * @since 1.0.0
			 */
			apply_filters( 'mbfw_add_meta_data_in_the_db_for_line_item', $line_item_meta, $custom_booking_values, $item );
			foreach ( $line_item_meta as $meta_key => $meta_val ) {
				$item->update_meta_data( $meta_key, $meta_val );
			}
			if ( 'yes' === wps_booking_get_meta_data( $custom_values['product_id'], 'mwb_mbfw_admin_confirmation', true ) ) {
				update_option( 'check_order_status_mwb', $order->get_status() );
			}
		}
	}

	/**
	 * Function for location.
	 *
	 * @param int $product_id is the id product.
	 * @return void
	 */
	public function mwb_mbfw_show_location_on_map( $product_id ) {
		$enable_location = get_option( 'mwb_mbfw_enable_location_site' );
		$active_plugins  = get_option( 'active_plugins' );
		$is_pro_active   = '';
		if ( in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) {
			$is_pro_active = 'yes';
		}
		$location = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_location', true );
		if ( 'yes' === $enable_location && ! empty( $location ) && 'yes' == $is_pro_active ) {
			?>

			<div class="mwb_mbfw_location_map_wrapper">
			
				<iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q=<?php echo esc_html( $location ); ?>&output=embed"></iframe>
			</div>

			<?php
		}
	}

	/**
	 * Register Endpoint for My Event Tab.
	 */
	public function wps_my_bookings_register_endpoint() {

		add_rewrite_endpoint( 'wps-mybookings-tab', EP_PERMALINK | EP_PAGES );
		flush_rewrite_rules();

	}

	/**
	 * Adding a query variable for the Endpoint.
	 *
	 * @param array $vars An array of query variables.
	 */
	public function wps_mybookings_endpoint_query_var( $vars ) {

		$vars[] = 'wps-mybookings-tab';

		/**
		 * Filter for endpoints.
		 *
		 * @since 1.0.0
		 */
		$vars = apply_filters( 'wps_mybookings_endpoint_query_var', $vars );

		return $vars;
	}



	/**
	 * Inserting custom membership endpoint.
	 *
	 * @param array $items An array of all menu items on My Account page.
	 */
	public function wps_bookings_add_mybookings_tab( $items ) {
		// Placing the custom tab just above logout tab.
		$items['wps-mybookings-tab'] = esc_html__( 'Bookings', 'mwb-bookings-for-woocommerce' );

		/**
		 * Filter for my event tab.
		 *
		 * @since 1.0.0
		 */
		$items = apply_filters( 'wps_bookings_add_mybookings_tab_filter', $items );

		return $items;
	}

	/**
	 * Add content to My Event details tab.
	 *
	 * @return void
	 */
	public function wps_mybookings_populate_tab() {
		require plugin_dir_path( __FILE__ ) . 'partials/wps-mybookings-details-tab.php';
	}

	/**
	 * Share cart button for block cart.
	 *
	 * @return void
	 */
	public function wps_mybookings_block_cart_page() {

		wp_enqueue_script( 'mwb-booking-block-cart', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-booking-block-cart.js', array( 'jquery' ), $this->version, false );

		$cart = WC()->cart;

		// Get cart items.
		$cart_items = $cart->get_cart();

		// Loop through each cart item.
		$data         = array();
		$user_id      = get_current_user_id();
		$max_quantity = '';
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $cart_item_key => $cart_item ) {

				if ( ! empty( $cart_item ) ) {
					$product = wc_get_product( $cart_item['product_id'] );

					if ( 'mwb_booking' === $product->get_type() ) {
						$max_quantity = get_post_meta( $product->get_id(), 'mwb_mbfw_booking_max_limit', true );
						if ( ! empty( $max_quantity ) ) {

							$get_mwb_mbfw_booking_max_limit = get_option( '_transient_mwb_mbfw_booking_max_limit_' . $product->get_id() );
							if ( $get_mwb_mbfw_booking_max_limit >= $max_quantity ) {
								$max_quantity = 0;
							} else {
								if ( ! empty( get_post_meta( $product->get_id(), 'mwb_mbfw_booking_max_limit_person', true ) ) ) {
									$max_quantity                          = get_post_meta( $product->get_id(), 'mwb_mbfw_booking_max_limit_person', true );
									$get_mwb_mbfw_booking_max_limit_person = get_option( '_transient_mwb_mbfw_booking_max_limit_person_' . $product->get_id() . '_' . $user_id );

									if ( $get_mwb_mbfw_booking_max_limit_person >= $max_quantity ) {
										$max_quantity = 0;
									} else {
										$max_quantity = $max_quantity - $get_mwb_mbfw_booking_max_limit_person;
										array_push( $data, $product->get_title() );
									}
								}
							}
						}
					}
				}
			}
		}
		$not_fixed_value = '';

		if ( empty( $max_quantity ) ) {

			$not_fixed_value = 'not';

		}

		wp_register_script( 'mwb-booking-block-cart', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-booking-block-cart.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			'mwb-booking-block-cart',
			'booking_block_public_param',
			array(
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
				'nonce'           => wp_create_nonce( 'ajax-nonce' ),
				'quantity__check' => $data,
				'not_fixed_value' => $not_fixed_value,

			)
		);
		wp_enqueue_script( 'mwb-booking-block-cart' );

	}

	/**
	 * Add limit to booking product.
	 *
	 * @param [type] $limit current limit.
	 * @param [type] $product current product details.
	 * @return mixed
	 */
	public function mwb_mbfw_add_limit_to_cart_page_for_booking_product( $limit, $product ) {

		if ( 'mwb_booking' === $product->get_type() ) {
			$user_id      = get_current_user_id();
			$max_quantity = get_post_meta( $product->get_id(), 'mwb_mbfw_booking_max_limit', true );
			if ( ! empty( $max_quantity ) ) {

				$get_mwb_mbfw_booking_max_limit = get_option( '_transient_mwb_mbfw_booking_max_limit_' . $product->get_id() );
				if ( $get_mwb_mbfw_booking_max_limit >= $max_quantity ) {
					$max_quantity = 0;
				} else {
					if ( ! empty( get_post_meta( $product->get_id(), 'mwb_mbfw_booking_max_limit_person', true ) ) ) {
						$max_quantity                          = get_post_meta( $product->get_id(), 'mwb_mbfw_booking_max_limit_person', true );
						$get_mwb_mbfw_booking_max_limit_person = get_option( '_transient_mwb_mbfw_booking_max_limit_person_' . $product->get_id() . '_' . $user_id );

						if ( $get_mwb_mbfw_booking_max_limit_person >= $max_quantity ) {
							$max_quantity = 0;
						} else {
							$max_quantity = $max_quantity - $get_mwb_mbfw_booking_max_limit_person;
						}
					}
				}
			}
			if ( 'fixed_unit' === wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_booking_criteria', true ) ) {
				$booking_count = wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_booking_count', true );

				$limit = $booking_count;

			}
			if ( empty( $booking_count ) ) {
				if ( ! empty( $max_limit_days ) ) {
					$limit = $max_limit_days;
				}
			}
		}

		return $limit;
	}



	/**
	 * Function to set maximum value.
	 *
	 * @param [type] $value is the current value.
	 * @param [type] $product is the current product.
	 * @param [type] $cart_item cart items.
	 * @return mixed
	 */
	public function mwb_mbfw_woocommerce_store_api_product_quantity_maximum( $value, $product, $cart_item ) {
		$active_plugins = get_option( 'active_plugins' );
		$max_quantity   = 0;
		if ( ! in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) {

			if ( 'fixed_unit' === get_post_meta( $product->get_id(), 'mwb_mbfw_booking_criteria', true ) ) {
				$booking_count_fixed_quantity = get_post_meta( $product->get_id(), 'mwb_mbfw_booking_count', true );
			}
			if ( ! empty( $booking_count_fixed_quantity ) ) {
				$max_quantity = $booking_count_fixed_quantity;
				return $max_quantity;
			}
		}
		return $value;
	}

	/**
	 * Function to reduce booking quantity.
	 *
	 * @param [type] $order is the order placed.
	 * @param [type] $data is the order data.
	 * @return void
	 */
	public function mwb_mbfw_custom_reduce_stock_of_booking( $order, $data ) {
		$order_id = $order->get_id();
		foreach ( $order->get_items() as $item_id => $item ) {
			$product      = $item->get_product();
			$product_type = $product->get_type();
			if ( 'mwb_booking' === $product_type ) {
				if ( $product->managing_stock() ) {
					$quantity = $item->get_quantity();
					$product->set_stock_quantity( $product->get_stock_quantity() - $quantity );
					$product->save();
				}
			}
		}
	}
}
