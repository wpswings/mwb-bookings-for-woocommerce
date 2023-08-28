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
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 2.0.0
	 */
	public function mbfw_public_enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name . 'public', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-public.js', array( 'jquery' ), time(), true );
		$daily_start_time = '';
		$daily_end_time = '';
		$upcoming_holiday = '';
		$booking_product = '';
		$wps_cal_type = '';
		$wps_available_slots = '';
		$booking_unit='';
		$is_pro_active = '';
		$booking_slot_array = array();
		$booking_unavailable = array();
		$single_available_dates = array();
		$single_unavailable_dates = array();
		if ( is_single() ) {
			global $post;
			$product_id = $post->ID;
			$temp_product = wc_get_product( $product_id );
			if( ! empty( $temp_product ) ) {

				if ( 'mwb_booking' == $temp_product->get_type() ) {
					$daily_start_time = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_daily_calendar_start_time', true );
					$daily_end_time = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_daily_calendar_end_time', true );
					$upcoming_holiday = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_choose_holiday', true );
					$upcoming_holiday = gmdate( 'Y-m-d', strtotime( $upcoming_holiday ) );
					if( 'yes' == get_option( 'mwb_mbfw_disable_book_now' ) ) {

						$booking_product = 'yes';
					}
					$active_plugins = get_option( 'active_plugins' );
					

					if( in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) { 
						$is_pro_active = 'yes';
					}
					$wps_cal_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
					$wps_available_slots = wps_booking_get_meta_data($product_id, 'wps_mbfw_time_slots', true );
					$booking_unit = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true );
					$active_plugins = get_option( 'active_plugins' );
					
					

						$booking_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
						$single_availables = wps_booking_get_meta_data( $product_id, 'wps_mbfw_set_availability', true  );
						$single_available_date_array = explode( ' ', $single_availables );
						if( ! empty( $single_available_date_array ) && is_array( $single_available_date_array ) ) {
							foreach( $single_available_date_array as $key => $values ) {
								$single_available_dates[] = gmdate( 'Y-m-d', strtotime( $values ) );
							}
						}
						
						if( 'single_cal' === $booking_type ) { 
							

							if( in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) { 
								
								$_orders     = wc_get_orders(
									array(
										'status'   => array( 'wc-processing', 'wc-on-hold', 'wc-pending' ),
										'limit'    => -1,
										'meta_key' => 'mwb_order_type', // phpcs:ignore WordPress
										'meta_val' => 'booking',
									)
								);
								$wps_single_dates_temp = array();
								if( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) { 
									foreach ( $_orders as $order ) {
										$items = $order->get_items();
										foreach ( $items as $item ) {
											if ( $product_id == $item['product_id'] ) {
												$wps_booking_slot = $item->get_meta( '_wps_booking_slot', true );
												if( ! empty( $wps_booking_slot ) ) {
	
													if( key_exists( $wps_booking_slot, $booking_slot_array ) ) {
														$booking_slot_array[$wps_booking_slot] += 1;
													} else {
														$booking_slot_array[$wps_booking_slot] = 1;
													}
												}
											}
										}
									}
								} else {
									
									foreach ( $_orders as $order ) {
										$items = $order->get_items();
										foreach ( $items as $item ) {
											if ( $product_id == $item['product_id'] ) {
												$wps_single_bookings_dates = explode(',', $item->get_meta( '_wps_single_cal_booking_dates', true ) );
												
												if( ! empty( $wps_single_bookings_dates ) && is_array( $wps_single_bookings_dates ) ) {
													foreach($wps_single_bookings_dates as $key => $values) {

														if( key_exists( $values, $wps_single_dates_temp ) ) {
															$wps_single_dates_temp[trim( $values)] += 1;
														} else {
															$wps_single_dates_temp[trim( $values )] = 1;
														}
													}
												}
											}
										}
									}
								}
								$max_limit = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_max_limit_for_hour', true );
								
								if( ! empty( $max_limit ) && ! empty( $booking_slot_array ) ) {
									foreach( $booking_slot_array as $key => $values ) {
										if( $values >= $max_limit ) {
											$booking_unavailable[] = $key;
										}
									}
								}
								$max_limit_days = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_max_limit', true );
								
								if( ! empty( $max_limit_days ) && ! empty( $wps_single_dates_temp ) ) {
									foreach( $wps_single_dates_temp as $k => $v ) {
										if( $v >= $max_limit_days ) {
											$k = gmdate('Y-m-d', strtotime($k));
											 

											$single_unavailable_dates[] = $k;
											
											
										}
									}
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
				'today_date'       => current_time( 'd-m-Y' ),
				'wrong_order_date_1' => __( 'To date can not be less than from date.', 'mwb-bookings-for-woocommerce' ),
				'wrong_order_date_2' => __( 'From date can not be greater than To date.', 'mwb-bookings-for-woocommerce' ),
				'daily_start_time'   => $daily_start_time,
				'daily_end_time'   => $daily_end_time,
				'upcoming_holiday' => array( $upcoming_holiday ),
				'is_pro_active' => $is_pro_active,
				'booking_product' => $booking_product,
				'wps_cal_type'   => $wps_cal_type,
				'wps_available_slots' => $wps_available_slots,
				'booking_unit'  => $booking_unit,
				'booking_unavailable' => $booking_unavailable,
				'single_available_dates' => $single_available_dates,
				'single_unavailable_dates' => $single_unavailable_dates,
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
		if( 'yes' == $check ){

			$start_time = get_option( 'mwb_mbfw_daily_start_time' );
			$end_time   = get_option( 'mwb_mbfw_daily_end_time' );
			if ( strtotime( $start_time ) <= strtotime( current_time( 'H:i' ) ) &&  strtotime( current_time( 'H:i' ) ) <= strtotime( $end_time ) && 'yes' === get_option( 'mwb_mbfw_is_booking_enable' ) ) {
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
										<input type="number" value="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ); ?>" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_quantity[<?php echo esc_attr( $custom_term->term_id ); ?>]" min="<?php echo ! empty( esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ) ) ? esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ) : 0 ; ?>" max="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_maximum_quantity', true ) ); ?>" class="mwb-mbfw-additional-service-quantity" />
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
			require_once
			/**
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
		$class = false;
		$class2 = '';
		if( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
			$label1 = __( 'From', 'mwb-bookings-for-woocommerce' );
			$label2 = __( 'To', 'mwb-bookings-for-woocommerce' );
			$class            = 'mwb_mbfw_time_date_picker_frontend';
			$class2 = 'wps_single_cal_hourly';
			
			$accepted_pattern = '(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$';
		} else if( 'day' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) {
			$label1 = __( 'Check in', 'mwb-bookings-for-woocommerce' );
			$label2 = __( 'Check out', 'mwb-bookings-for-woocommerce' );
			$class            = 'mwb_mbfw_date_picker_frontend';
			$accepted_pattern = '(\d{2})-(\d{2})-(\d{4})$';
			if( 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_show_date_with_time', true ) ) {
				$label1 = __( 'From', 'mwb-bookings-for-woocommerce' );
				$label2 = __( 'To', 'mwb-bookings-for-woocommerce' );
				$class            = 'mwb_mbfw_time_date_picker_frontend';
				$accepted_pattern = '(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$';
			}
		} 
		
		if ( $class ) {
			do_action('wps_mbfw_add_html_before_calender');
			$wps_cal_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
			
			?>
			
			<div class="mbfw-date-picker-section__wrapper">
				<?php 
				if( 'single_cal' === $wps_cal_type ) { ?>
					<div class="mbfw-date-picker-section">
						<label for="wps_booking_single_calendar_form"><?php esc_html_e( 'Choose Booking date', 'mwb-bookings-for-woocommerce' ); ?></label>
						<input type="text" name="wps_booking_single_calendar_form" id="wps_booking_single_calendar_form" class="<?php echo esc_attr( $class2 ); ?>" autocomplete="off" placeholder="<?php echo esc_attr('Choose date/time', 'mwb-bookings-for-woocommerce'); ?>"  required />
					</div>

				<?php } else { ?>
					<div class="mbfw-date-picker-section">
					<label for="mwb-mbfw-booking-from-time"><?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?></label>
					<input type="text" name="mwb_mbfw_booking_from_time" id="mwb-mbfw-booking-from-time" class="<?php echo esc_attr( $class ); ?>" autocomplete="off" placeholder="<?php echo esc_attr( $label1 ); ?>" pattern="<?php echo esc_attr( $accepted_pattern ); ?>" required />
					</div>
					<div class="mbfw-date-picker-section">
						<label for="mwb-mbfw-booking-to-time"><?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?></label>
						<input type="text" name="mwb_mbfw_booking_to_time" id="mwb-mbfw-booking-to-time" class="<?php echo esc_attr( $class ); ?>" autocomplete="off" placeholder="<?php echo esc_attr( $label2 ); ?>" pattern="<?php echo esc_attr( $accepted_pattern ); ?>" required />
					</div>
				<?php }
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
			$product_id = array_key_exists( 'mwb_mbfw_booking_product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_product_id'] ) ) : '';
			$booking_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
			$single_cal_booking_dates = '';
			$date_time_from = '';
			$date_time_to = '';
			$booking_slot = '';
			if( 'single_cal' === $booking_type ) { 
				$single_cal_booking_dates = array_key_exists( 'wps_booking_single_calendar_form', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_booking_single_calendar_form'] ) ) : '';
				if( 'hour' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true ) ) { 

					$booking_dates = explode(' ',$single_cal_booking_dates);
					$date_time_from = $booking_dates[0] . ' ' . $booking_dates[1];
					$date_time_to   = $booking_dates[0] . ' ' . $booking_dates[3];
					$booking_slot = $single_cal_booking_dates;
					$single_cal_booking_dates = '';
					
				} 
			}
			$custom_data = array(
				'people_number'    => array_key_exists( 'mwb_mbfw_people_number', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_people_number'] ) ) : '',
				'service_option'   => array_key_exists( 'mwb_mbfw_service_option_checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_option_checkbox'] ), 'sanitize_text_field' ) : array(),
				'service_quantity' => array_key_exists( 'mwb_mbfw_service_quantity', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_quantity'] ), 'sanitize_text_field' ) : array(),
				'date_time_from'   => array_key_exists( 'mwb_mbfw_booking_from_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_from_time'] ) ) : $date_time_from,
				'date_time_to'     => array_key_exists( 'mwb_mbfw_booking_to_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_to_time'] ) ) : $date_time_to,
				'single_cal_booking_dates' => $single_cal_booking_dates,
				'single_cal_date_time_from' => $date_time_from ,
				'single_cal_date_time_to'   => $date_time_to,
				'wps_booking_slot' => $booking_slot,
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
				$other_data[] =  array(
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
				$other_data[] =  array(
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
			if( ! empty( $custom_cart_data['single_cal_booking_dates'] ) ) {
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
		if ( 'mwb_booking' === $custom_values['data']->product_type && isset( $custom_values['mwb_mbfw_booking_values'] ) ) {
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
						$term                             = get_term( $term_id );
						$service_count                    = array_key_exists( $term_id, $service_quantity ) ? $service_quantity[ $term_id ] : 1;
						$service_id_and_quant[ $term_id ] = $service_count;
					}
				}
			}
			$line_item_meta['_mwb_mbfw_service_and_count'] = $service_id_and_quant;
			$line_item_meta['_mwb_bfwp_date_time_from']    = isset( $custom_booking_values['date_time_from'] ) ? $custom_booking_values['date_time_from'] : '';
			$line_item_meta['_mwb_bfwp_date_time_to']      = isset( $custom_booking_values['date_time_to'] ) ? $custom_booking_values['date_time_to'] : '';
			$line_item_meta['_wps_single_cal_date_time_from']    = isset( $custom_booking_values['single_cal_date_time_from'] ) ? $custom_booking_values['single_cal_date_time_from'] : '';
			$line_item_meta['_wps_single_cal_date_time_to']      = isset( $custom_booking_values['single_cal_date_time_to'] ) ? $custom_booking_values['single_cal_date_time_to'] : '';
			$line_item_meta['_wps_single_cal_booking_dates']      = isset( $custom_booking_values['single_cal_booking_dates'] ) ? $custom_booking_values['single_cal_booking_dates'] : '';
			$line_item_meta['_wps_booking_slot']      = isset( $custom_booking_values['wps_booking_slot'] ) ? $custom_booking_values['wps_booking_slot'] : '';
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
		$location = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_location', true );
		if( 'yes' === $enable_location && ! empty( $location ) ) { ?>

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
		$items['wps-mybookings-tab'] = esc_html__( 'my bookings', 'membership-for-woocommerce' );

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
	
}
