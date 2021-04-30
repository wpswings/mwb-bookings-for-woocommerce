<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mwb_Wc_Bk_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance for the MWB_Woocommerce_Booking Public class
	 *
	 * @var obj
	 */
	public $mwb_booking;

	/**
	 * Slot for a particular booking product
	 *
	 * @var array
	 */
	public $mwb_product_slots = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
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
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Wc_Bk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Wc_Bk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb-wc-bk-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), '1.12.0' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Wc_Bk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Wc_Bk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-wc-bk-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'jquery-ui-datepicker' );

		$args = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'mwb_wc_bk_public' ),
		);

		$global_func = Mwb_Booking_Global_Functions::get_global_instance();

		$global_settings = get_option( 'mwb_booking_settings_options', $global_func->booking_settings_tab_default_global_options() );

		if ( is_product() ) {
			$p_id    = get_the_id();
			$product = wc_get_product( $p_id );
			if ( $product && $product->is_type( 'mwb_booking' ) ) {
				$args['mwb_booking_product_page'] = 'true';

				$args['product_settings'] = get_post_meta( get_the_id() );
				$args['global_settings']  = $global_settings;
				$args['current_date']     = gmdate( 'Y-m-d' );
				$args['not_allowed_days'] = maybe_unserialize( ! empty( $args['product_settings']['mwb_booking_not_allowed_days'][0] ) ? $args['product_settings']['mwb_booking_not_allowed_days'][0] : array() );
			}
		}

		wp_localize_script(
			$this->plugin_name,
			'mwb_wc_bk_public',
			$args
		);

	}

	/**
	 * Add the form to the product type 'mwb_booking'.
	 *
	 * @return void
	 */
	public function mwb_include_booking_add_to_cart() {
		global $product;

		if ( $product && $product->is_type( 'mwb_booking' ) ) {

			wc_get_template( 'single-product/add-to-cart/mwb-booking.php', array( 'slots' => $this->mwb_product_slots ), '', MWB_WC_BK_TEMPLATE_PATH );

		}
	}

	/**
	 * Slot management for the booking product
	 *
	 * @return void
	 */
	public function mwb_booking_slot_management() {

		global $product;
		$product_id   = $product->get_id();
		$product_meta = get_post_meta( $product_id );

		$slots = array();

		$current_date = gmdate( 'Y-m-d', time() );
		$current_ts   = strtotime( $current_date );

		$start_date = gmdate( 'Y-m-d', $current_ts );

		$start_booking     = isset( $product_meta['mwb_start_booking_from'][0] ) ? $product_meta['mwb_start_booking_from'][0] : '';
		$daily_start_time  = isset( $product_meta['mwb_booking_start_time'][0] ) ? $product_meta['mwb_booking_start_time'][0] : '00:01';
		$daily_end_time    = isset( $product_meta['mwb_booking_end_time'][0] ) ? $product_meta['mwb_booking_end_time'][0] : '23:59';
		$min_advance_input = isset( $product_meta['mwb_advance_booking_min_input'][0] ) ? $product_meta['mwb_advance_booking_min_input'][0] : 0;
		$max_advance_input = isset( $product_meta['mwb_advance_booking_max_input'][0] ) ? $product_meta['mwb_advance_booking_max_input'][0] : 1;
		$min_advance_dura  = isset( $product_meta['mwb_advance_booking_min_duration'][0] ) ? $product_meta['mwb_advance_booking_min_duration'][0] : 'day';
		$max_advance_dura  = isset( $product_meta['mwb_advance_booking_max_duration'][0] ) ? $product_meta['mwb_advance_booking_max_duration'][0] : 'day';
		$unit_input        = ! empty( $product_meta['mwb_booking_unit_input'][0] ) ? $product_meta['mwb_booking_unit_input'][0] : '';
		$unit_duration     = ! empty( $product_meta['mwb_booking_unit_duration'][0] ) ? $product_meta['mwb_booking_unit_duration'][0] : '';

		if ( 'today' === $start_booking ) {
			$start_date = gmdate( 'Y-m-d', $current_ts );

		} elseif ( 'tomorrow' === $start_booking ) {
			$start_date = gmdate( 'Y-m-d', strtotime( '+1 day', $current_ts ) );

		} elseif ( 'custom_date' === $start_booking ) {
			$custom_date = isset( $product_meta['mwb_start_booking_custom_date'][0] ) ? $product_meta['mwb_start_booking_custom_date'][0] : '';
			if ( strtotime( $custom_date ) < strtotime( gmdate( 'Y-m-d', time() ) ) ) {
				$custom_date = gmdate( 'Y-m-d', time() );
			}
			$start_date = gmdate( 'Y-m-d', strtotime( $custom_date ) );
			if ( strtotime( $custom_date ) < $current_ts ) {
				$start_date = gmdate( 'Y-m-d', strtotime( $current_ts ) );
			}
		} elseif ( 'initially_available' === $start_booking ) {
			$start_date = gmdate( 'Y-m-d', strtotime( '+' . $min_advance_input . ' ' . $min_advance_dura . '', $current_ts ) );

		}

		$end_date = gmdate( 'Y-m-d', strtotime( '+' . $max_advance_input . ' ' . $max_advance_dura . '', strtotime( $start_date ) ) );

		$slots = $this->date_range( $start_date, $end_date, '+1 day', 'Y-m-d' );

		if ( ! empty( $unit_duration ) && ! empty( $unit_input ) ) {
			if ( ! empty( $slots ) && is_array( $slots ) ) {
				foreach ( $slots as $date => $slot ) {
					$start_time = strtotime( $daily_start_time, strtotime( $date ) );
					$end_time   = strtotime( '+' . $unit_input . ' ' . $unit_duration, $start_time );
					$s          = array();

					if ( 'hour' === $unit_duration || 'minute' === $unit_duration ) {
						while ( $end_time <= strtotime( $daily_end_time, strtotime( $date ) ) ) {

							$s[ gmdate( 'H:i:s', $start_time ) . '-' . gmdate( 'H:i:s', $end_time ) ] = array(
								'book'          => 'bookable',
								'booking_count' => 0,
							);

							$start_time = $end_time;
							$end_time   = strtotime( '+' . $unit_input . ' ' . $unit_duration, $start_time );

						}
					} elseif ( 'day' === $unit_duration ) {

						$start_time = gmdate( 'H:i:s', strtotime( $daily_start_time, strtotime( $date ) ) );
						$end_time   = gmdate( 'H:i:s', strtotime( $daily_end_time, strtotime( $date ) ) );

						$s[ $start_time . '-' . $end_time ] = array(
							'book'          => 'bookable',
							'booking_count' => 0,
						);
					}
					$slots[ $date ] = $s;
				}
			}
		}

		$availability_instance = MWB_Woocommerce_Booking_Availability::get_availability_instance();

		$slot_arr = $availability_instance->check_product_global_availability( $product_id, $slots );
		$slot_arr = $availability_instance->check_product_setting_availability( $product_id, $slot_arr );
		$slot_arr = $availability_instance->manage_avaialability_acc_to_created_bookings( $product_id, $slot_arr );

		$unavail_dates = $availability_instance->fetch_unavailable_dates( $slot_arr );

		$slot_arr = $availability_instance->make_unavailable_todays_passed_slots( $product_id, $slot_arr );

		echo '<div id="booking-slots-data" slots="' . esc_html( htmlspecialchars( wp_json_encode( $slot_arr ) ) ) . '" unavail_dates="' . esc_html( htmlspecialchars( wp_json_encode( $unavail_dates ) ) ) . '" ></div>';

		update_post_meta( $product_id, 'mwb_booking_product_slots', $slot_arr );

	}

	/**
	 * Calculate dates between start and end date.
	 *
	 * @param string $first Start Date.
	 * @param string $last Last Date.
	 * @param string $step Step to increment the date.
	 * @param string $output_format DAte format.
	 * @return array
	 */
	public function date_range( $first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

		$dates   = array();
		$current = strtotime( $first );
		$last    = strtotime( $last );

		while ( $current <= $last ) {
			$dates[ gmdate( $output_format, $current ) ] = array();

			$current = strtotime( $step, $current );
		}

		return $dates;
	}

	/**
	 * Show time field on the booking form if the duration is in hours or minutes.
	 * Ajax hander
	 *
	 * @return void
	 */
	public function mwb_time_slots_in_booking_form() {

		check_ajax_referer( 'mwb_wc_bk_public', 'nonce' );

		$product_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
		$start_date = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';

		$start_date = gmdate( 'Y-m-d', strtotime( $start_date ) );

		$unit_duration = get_post_meta( $product_id, 'mwb_booking_unit_duration', true );

		$slots = isset( $_POST['slots'] ) ? $_POST['slots'] : array();    // @codingStandardsIgnoreLine

		if ( ! empty( $product_id ) && ! empty( $start_date ) ) {
			if ( 'hour' === $unit_duration || 'minute' === $unit_duration ) {
				if ( array_key_exists( $start_date, $slots ) ) {
					?>
						<div id="mwb-wc-bk-time-slot-field">
							<label for="mwb-wc-bk-time-slot-input"><?php esc_html_e( 'Time', 'mwb-wc-bk' ); ?></label>
							<select type="text" id="mwb-wc-bk-time-slot-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-time" name="time_slot" required>
								<?php
								if ( ! empty( $slots ) ) {
									foreach ( $slots[ $start_date ] as $k => $v ) {
										if ( 'bookable' === $v['book'] ) {
											$s = explode( '-', $k );
											?>
											<option value="<?php echo esc_html( strtotime( $s[0], strtotime( $start_date ) ) ); ?>">
											<?php
											echo esc_html( gmdate( 'h:i:s a', strtotime( $s[0], strtotime( $start_date ) ) ) );
											?>
											</option>
											<?php
										} else {
											$s = explode( '-', $k );
											?>
											<option value="<?php strtotime( $s[0], strtotime( $start_date ) ); ?>" disabled ><?php echo esc_html( gmdate( 'h:i:s a', strtotime( $s[0], strtotime( $start_date ) ) ) ); ?></option>
											<?php
										}
									}
								}
								?>
							</select>
						</div>
					<?php
				}
			}
		}
		wp_die();
	}

	/**
	 * Check time slot availability on basis of duration and start date.
	 * Ajax Handler
	 *
	 * @return void
	 */
	public function mwb_check_time_slot_availability() {

		check_ajax_referer( 'mwb_wc_bk_public', 'nonce' );

		$slots      = isset( $_POST['slots'] ) ? $_POST['slots'] : array();     // @codingStandardsIgnoreLine
		$time_slot  = isset( $_POST['time_slot'] ) ? sanitize_text_field( wp_unslash( $_POST['time_slot'] ) ) : '';
		$start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( wp_unslash( $_POST['start_date'] ) ) : array();
		$duration   = isset( $_POST['duration'] ) ? sanitize_text_field( wp_unslash( $_POST['duration'] ) ) : '';

		$time = gmdate( 'H:i:s', $time_slot );

		$arr     = $slots[ $start_date ];
		$result  = array( 'status' => true );
		$count   = 0;
		$arr_len = count( $arr );
		if ( $duration > $arr_len ) {
			$result['status'] = false;
		}
		foreach ( $arr as $k => $v ) {

			$time2 = explode( '-', $k );
			$time3 = $time2[0];
			if ( strtotime( $time, strtotime( $start_date ) ) <= strtotime( $time3, strtotime( $start_date ) ) ) {
				$count++;
				if ( $count <= $duration ) {
					if ( 'non-bookable' === $v['book'] ) {
						$result['status'] = false;
					}
				} else {
					break;
				}
			}
		}

		echo wp_json_encode( $result );
		wp_die();
	}

	/**
	 * Add the form fields to the Product of type 'mwb_booking'
	 *
	 * @return void
	 */
	public function mwb_booking_add_to_cart_form_fields() {
		global $product;

		$product_meta = get_post_meta( $product->get_id() );

		$unit_cost = ! empty( $product_meta['mwb_booking_unit_cost_input'][0] ) ? $product_meta['mwb_booking_unit_cost_input'][0] : 0;
		$base_cost = ! empty( $product_meta['mwb_booking_base_cost_input'][0] ) ? $product_meta['mwb_booking_base_cost_input'][0] : 0;

		$product_price = $unit_cost + $base_cost;
		$product->set_price( $product_price );

		$product_data = array(
			'product_id' => $product->get_id(),
		);

		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			?>
			<div id="mwb-wc-bk-create-booking-form" product-data = "<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>" >
				<?php
					wc_get_template( 'single-product/add-to-cart/form/duration-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/dates-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/people-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/service-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/show-total.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
				?>
			</div>
			<?php
		}
	}


	/**
	 * Add the additional product data to the cart items
	 *
	 * @param array  $cart_item_data Array of any other cart item data for the product.
	 * @param number $product_id     ID of the product adding to the cart.
	 * @return array
	 */
	public function mwb_wc_bk_add_cart_item_data( $cart_item_data, $product_id ) {

		$product = wc_get_product( $product_id );
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			if ( ! isset( $cart_item_data['mwb_wc_bk_cart_data'] ) ) {
				$posted_data                      = $_REQUEST;    // @codingStandardsIgnoreLine
				$booking_data                     = $this->mwb_wc_bk_get_product_data( $posted_data, $product_id );
				$cart_item_data['mwb_wc_bk_data'] = $booking_data;
			}
		}

		return $cart_item_data;
	}

	/**
	 * Function to get the Product's added fields Data.
	 *
	 * @param array $posted_data parameter.
	 * @return array
	 */
	public function mwb_wc_bk_get_product_data( $posted_data, $product_id ) {

		$booking_data = array();

		$product_meta = get_post_meta( $product_id );

		$booking_unit_dur   = ! empty( $product_meta['mwb_booking_unit_duration'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_duration'][0] ) ) : '';
		$booking_unit_input = ! empty( $product_meta['mwb_booking_unit_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_input'][0] ) ) : 0;
		$booking_people     = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_people_select'][0] ) : array();
		$booking_service    = ! empty( $product_meta['mwb_booking_services_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_services_select'][0] ) : array();
		$booking_start_time = ! empty( $product_meta['mwb_booking_start_time'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_start_time'][0] ) ) : '00:01';
		$booking_end_time   = ! empty( $product_meta['mwb_booking_end_time'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_end_time'][0] ) ) : '23:59';

		if ( ! isset( $posted_data['end_date'] ) ) {
			if ( isset( $posted_data['duration'] ) && ! empty( $booking_unit_dur ) && ! empty( $booking_unit_input ) && isset( $posted_data['start_date'] ) ) {

				$time_slot        = isset( $posted_data['time_slot'] ) ? $posted_data['time_slot'] : strtotime( $booking_start_time, strtotime( $posted_data['start_date'] ) );
				$booking_start_ts = $time_slot;

				$booking_data['duration']        = $posted_data['duration'];
				$start_timestamp                 = $booking_start_ts;
				$booking_data['start_date']      = gmdate( 'd-m-Y', $booking_start_ts );
				$booking_data['start_timestamp'] = $booking_start_ts;
				$total_dur                       = $posted_data['duration'] * $booking_unit_input;
				$booking_data['dura_param']      = $booking_unit_dur;

				if ( 'day' === $booking_unit_dur || 'month' === $booking_unit_dur ) {
					$end_date                      = gmdate( 'd-m-Y', strtotime( gmdate( 'd-m-Y', strtotime( $booking_data['start_date'] ) ) . ' +' . ( $total_dur - 1 ) . ' ' . $booking_unit_dur ) );
					$end_timestamp                 = strtotime( $booking_end_time, strtotime( $end_date ) );
					$booking_data['end_date']      = gmdate( 'd-m-Y', $end_timestamp );
					$booking_data['end_timestamp'] = $end_timestamp;
				} elseif ( 'hour' === $booking_unit_dur ) {
					$end_timestamp                 = $start_timestamp + ( $total_dur * 60 * 60 );
					$booking_data['end_timestamp'] = $end_timestamp;
					$booking_data['time_slot']     = $time_slot;
				} elseif ( 'minute' === $booking_unit_dur ) {
					$end_timestamp                 = $start_timestamp + ( $total_dur * 60 );
					$booking_data['end_timestamp'] = $end_timestamp;
					$booking_data['time_slot']     = $time_slot;
				}
			}
		} else {
			if ( ! empty( $booking_unit_dur ) && ! empty( $booking_unit_input ) && isset( $posted_data['start_date'] ) ) {

				$booking_start_ts = strtotime( $booking_start_time, strtotime( $posted_data['start_date'] ) );
				$booking_end_ts   = strtotime( $booking_end_time, strtotime( $posted_data['end_date'] ) );

				$booking_data['start_date']      = gmdate( 'd-m-Y', $booking_start_ts );
				$start_timestamp                 = $booking_start_ts;
				$booking_data['start_timestamp'] = $start_timestamp;
				$booking_data['end_date']        = gmdate( 'd-m-Y', $booking_end_ts );
				$booking_data['end_timestamp']   = $booking_end_ts;
				$booking_data['dura_param']      = $booking_unit_dur;

				$duration_timestamp_diff = strtotime( $booking_data['end_date'] ) - strtotime( $booking_data['start_date'] );

				if ( 'day' === $booking_unit_dur ) {
					$booking_data['duration'] = $duration_timestamp_diff / ( 24 * 60 * 60 * $booking_unit_input );
				}
			}
		}
		if ( isset( $posted_data['people_total'] ) ) {
			$booking_data['people_total'] = ! empty( $posted_data['people_total'] ) ? $posted_data['people_total'] : 0;
			if ( ! empty( $booking_people ) && is_array( $booking_people ) ) {
				foreach ( $booking_people as $id ) {
					$people      = get_term( $id );
					$people_name = $people->name;

					$booking_data['people_count'][ $people_name ] = ! empty( $posted_data[ 'people-' . $id ] ) ? $posted_data[ 'people-' . $id ] : 0;
				}
			}
		}

		if ( isset( $posted_data['service_cost'] ) ) {
			if ( ! empty( $booking_service ) && is_array( $booking_service ) ) {
				foreach ( $booking_service as $id ) {
					$service_meta = get_term_meta( $id );
					$service      = get_term( $id );
					$service_name = $service->name;
					if ( 'no' === $service_meta['mwb_booking_ct_services_optional'][0] ) {
						$booking_data['inc_service'][ $service_name ] = ! empty( $posted_data[ 'inc-service-' . $id ] ) ? $posted_data[ 'inc-service-' . $id ] : 0;
					} else {
						if ( ! empty( $posted_data[ 'add-service-check-' . $id ] ) && 'on' === $posted_data[ 'add-service-check-' . $id ] ) {
							$booking_data['add_service'][ $service_name ] = ! empty( $posted_data[ 'add-service-' . $id ] ) ? $posted_data[ 'add-service-' . $id ] : 0;
						}
					}
				}
			}
		}

		if ( ! empty( $posted_data['total_cost'] ) ) {

			$booking_data['total_cost'] = $posted_data['total_cost'];
		}

		$booking_data['unit_dur']       = $booking_unit_dur;
		$booking_data['unit_dur_input'] = $booking_unit_input;

		return $booking_data;
	}

	/**
	 * Change Price for Product (while add to cart) according to the form fields.
	 *
	 * @param array  $cart_item_data Data for the cart item's products.
	 * @param string $cart_item_key  Key for the cart items array.
	 * @return array
	 */
	public function mwb_wc_bk_add_cart_item( $cart_item_data, $cart_item_key ) {

		$product_id = isset( $cart_item_data['product_id'] ) ? $cart_item_data['product_id'] : 0;
		$_product   = wc_get_product( $product_id );
		if ( $_product && $_product->is_type( 'mwb_booking' ) ) {
			$product      = $cart_item_data['data'];
			$booking_data = $cart_item_data['mwb_wc_bk_data'];
			$price        = (int) $cart_item_data['mwb_wc_bk_data']['total_cost'];

			$product->set_price( $price );
			$cart_item_data['data'] = $product;
		}
		return $cart_item_data;
	}

	/**
	 * Change Price for Product (while add to cart) according to the form fields using session data.
	 *
	 * @param array  $session_data  Cart Item Product Data stored in the session.
	 * @param array  $cart_item     Items in the cart.
	 * @param string $cart_item_key Key for the cart items array.
	 * @return array
	 */
	public function mwb_wc_bk_get_cart_item_from_session( $session_data, $cart_item, $cart_item_key ) {
		$product_id = isset( $cart_item['product_id'] ) ? $cart_item['product_id'] : 0;
		$_product   = wc_get_product( $product_id );
		if ( $_product && $_product->is_type( 'mwb_booking' ) ) {
			$product      = $session_data['data'];
			$booking_data = $session_data['mwb_wc_bk_data'];

			if ( array_key_exists( 'total_cost', $cart_item['mwb_wc_bk_data'] ) ) {
				$price = (int) $cart_item['mwb_wc_bk_data']['total_cost'];
			} else {
				$price = 0;
			}

			$product->set_price( $price );
			$session_data['data'] = $product;
		}
		return $session_data;
	}

	/**
	 * Change button text on product pages
	 *
	 * @param string $text    Text of the add to cart button.
	 * @param object $product Product Object.
	 *
	 * @return string
	 */
	public function mwb_wc_bk_add_to_cart_text( $text, $product ) {

		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			return __( 'Book now', 'mwb-wc-bk' );
		}
		return __( 'Add To Cart', 'mwb-wc-bk' );

	}

	/**
	 * Redirect add to cart to Checkout page.
	 *
	 * @param [string] $url for Cart page.
	 * @return string
	 */
	public function mwb_wc_bk_skip_cart_redirect_checkout( $url ) {

		if ( is_user_logged_in() ) {
			return wc_get_checkout_url();
		} else {
			$url = wc_get_page_permalink( 'myaccount' );
			return $url;
		}

	}

	/**
	 * Display the added product data in the cart.
	 *
	 * @param array $item_data      Array containing additional data for our cart item.
	 * @param array $cart_item_data Array of our cart item and its associated data.
	 * @return array
	 */
	public function mwb_wc_bk_get_item_data( $item_data, $cart_item_data ) {

		if ( empty( $cart_item_data['mwb_wc_bk_data'] ) ) {
			return $item_data;
		}

		$product_id = isset( $cart_item_data['product_id'] ) ? $cart_item_data['product_id'] : 0;
		$product    = wc_get_product( $product_id );
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			$booking_data = $cart_item_data['mwb_wc_bk_data'];

			$total_duration = $booking_data['duration'] * $booking_data['unit_dur_input'];
			$duration_str   = $total_duration . ' ' . $booking_data['unit_dur'];

			$booking_item_data = array(
				'mwb_wc_bk_duration' => array(
					'key'     => 'Duration',
					'value'   => $total_duration > 1 ? $duration_str . 's' : $duration_str,
					'display' => '',
				),
				'mwb_wc_bk_from'     => array(
					'key'     => 'From',
					'value'   => $booking_data['start_date'],
					'display' => '',
				),
			);

			if ( 'hour' !== $booking_data['unit_dur'] && 'minute' !== $booking_data['unit_dur'] ) {
				$booking_item_data['mwb_wc_bk_to'] = array(
					'key'     => 'To',
					'value'   => $booking_data['end_date'],
					'display' => '',
				);
			} else {
				$booking_item_data['mwb_wc_bk_from']      = array(
					'key'     => 'On',
					'value'   => $booking_data['start_date'],
					'display' => '',
				);
				$booking_item_data['mwb_wc_bk_time_slot'] = array(
					'key'     => 'Start Time',
					'value'   => $booking_data['time_slot'],
					'display' => gmdate( 'h:i a', $booking_data['time_slot'] ),
				);
			}
			if ( ! empty( $booking_data['people_count'] ) && is_array( $booking_data['people_count'] ) ) {

				foreach ( $booking_data['people_count'] as $name => $count ) {
					$booking_item_data[ 'mwb_wc_bk_' . $name ] = array(
						'key'     => $name,
						'value'   => $count,
						'display' => '',
					);
				}
			}
			if ( ! empty( $booking_data['inc_service'] ) && is_array( $booking_data['inc_service'] ) ) {

				foreach ( $booking_data['inc_service'] as $name => $count ) {
					$str = $name . '-' . $count . ',';
				}
				$booking_item_data[ 'mwb_wc_bk_' . $name ] = array(
					'key'     => 'Included Booking services',
					'value'   => $str,
					'display' => '',
				);
			}
			if ( ! empty( $booking_data['add_service'] ) && is_array( $booking_data['add_service'] ) ) {
				$str = '';
				foreach ( $booking_data['add_service'] as $name => $count ) {
					$str .= $name . '-' . $count . ', ';
				}
				$booking_item_data[ 'mwb_wc_bk_' . $name ] = array(
					'key'     => 'Additional Booking services',
					'value'   => $str,
					'display' => '',
				);
			}

			$item_data = array_merge( $item_data, $booking_item_data );
		}

		return $item_data;
	}

	/**
	 * Empty the cart before Add Booking.
	 *
	 * @param boolean $passed True or False.
	 * @param int     $product_id ID of the product.
	 * @param int     $quantity Quantity.
	 *
	 * @return boolean
	 */
	public function remove_cart_item_before_add_to_cart( $passed, $product_id, $quantity ) {

		if ( ! WC()->cart->is_empty() ) {
			WC()->cart->empty_cart();
		}
		return $passed;
	}

	/**
	 * Fix the quatity of the product in the cart.
	 *
	 * @param object $cart Cart Object.
	 *
	 * @return void
	 */
	public function mwb_change_booking_product_quantity( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$new_qty = 1;

		// Checking cart items.
		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

			$product_id = $cart_item['data']->get_id();
			$product    = wc_get_product( $product_id );

			if ( $product && $product->is_type( 'mwb_booking' ) && $cart_item['quantity'] != $new_qty ) {  // @codingStandardsIgnoreLine
				$cart->set_quantity( $cart_item_key, $new_qty ); // Change quantity.
			}
		}
	}

	/**
	 * Add custom added product data to the order.
	 *
	 * @param object $item          order line item object.
	 * @param string $cart_item_key the string containing our cart item key.
	 * @param array  $values        array containing all the data for our cart item, including our custom data.
	 * @param object $order         WC_Order instance containing the new order object.
	 * @return void
	 */
	public function mwb_wc_bk_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		if ( empty( $values['mwb_wc_bk_data'] ) ) {
			return;
		}

		$booking_data = array();
		if ( isset( $values['mwb_wc_bk_data'] ) && is_array( $values['mwb_wc_bk_data'] ) ) {
			$booking_data = $values['mwb_wc_bk_data'];
		}

		if ( ! empty( $booking_data ) ) {
			$item->add_meta_data( 'mwb_wc_bk_data', $booking_data, true );
			if ( isset( $booking_data['mwb_wc_bk_id'] ) ) {
				$item->add_meta_data( 'mwb_wc_bk_id', $booking_data['mwb_wc_bk_id'], true );
			}
		}
	}

	/**
	 * Avoid guest user to Book a mwb_booking product.
	 *
	 * @param [string] $value 'Yes' or 'No' for sign-up.
	 * @return string
	 */
	public function conditional_guest_checkout_based_on_product( $value ) {

		if ( WC()->cart ) {
			$cart = WC()->cart->get_cart();
			foreach ( $cart as $item ) {
				$prod_id  = $item['product_id'];
				$_product = wc_get_product( $prod_id );
				if ( $_product && $_product->is_type( 'mwb_booking' ) ) {
					$value = 'no';
					break;
				}
			}
		}
		return $value;
	}

	/**
	 * Preparing the Booking Order
	 *
	 * @param number $order_id   ID of the order created.
	 * @param array  $posted_data array for the posted data.
	 *
	 * @return void
	 */
	public function mwb_wc_bk_check_order_booking( $order_id, $posted_data = array() ) {

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		$order_items = $order->get_items();
		if ( ! $order_items ) {
			return;
		}

		foreach ( $order_items as $order_item_id => $order_item ) {
			if ( $order_item->is_type( 'line_item' ) ) {
				$product = $order_item->get_product();
				if ( ! $product || ! ( $product->is_type( 'mwb_booking' ) ) ) {
					continue;
				}

				$order_meta               = $order_item->get_meta( 'mwb_wc_bk_data' );
				$order_meta['product_id'] = $product->get_id();

				$args = array(
					'order_id'   => $order_id,
					'product_id' => $product->get_id(),
					'status'     => $order->get_status(),
					'user_id'    => $order->get_user_id(),
					'order_meta' => $order_meta,
				);

				$booking_data = $order_item->get_meta( 'mwb_wc_bk_data' );

				if ( ! empty( $booking_data ) ) {
					$booking_id = $this->mwb_wc_bk_create_booking( $args );
					if ( $booking_id ) {
						update_post_meta( $order_id, 'mwb_booking_id', $booking_id );
						$this->mwb_wc_bk_booking_details_order( $order_item, $booking_data );
						$order_item->save_meta_data();
						$order->add_order_note( sprintf( __( 'A new booking <a href="%1$1s">#%2$2s</a> has been created from this order', 'mwb-wc-bk' ), admin_url( 'post.php?post=' . $booking_id . '&action=edit' ), $booking_id ) );   // @codingStandardsIgnoreLine

						$this->save_booking_order_data( $order_id, $booking_id );
						$this->booking_status_acc_order_status( $order_id, $booking_id );
					}
				}
			}
		}

	}

	/**
	 * Display details on Thanq Page.
	 *
	 * @param object $order_item Order item Object.
	 * @param array  $booking_data Booking Details.
	 * @return void
	 */
	public function mwb_wc_bk_booking_details_order( $order_item, $booking_data ) {

		$order_item->add_meta_data( 'From', gmdate( 'Y-m-d h:i:s a', $booking_data['start_timestamp'] ) );
		$order_item->add_meta_data( 'To', gmdate( 'Y-m-d h:i:s a', $booking_data['end_timestamp'] ) );

	}

	/**
	 * Changing Booking Status according to order status, when a order is created
	 *
	 * @param int $order_id  ID of the order created.
	 * @param int $booking_id ID of the booking created.
	 * @return void
	 */
	public function booking_status_acc_order_status( $order_id, $booking_id ) {

		$order        = wc_get_order( $order_id );
		$order_status = $order->get_status();

		switch ( $order_status ) {
			case 'pending':
				$new_status = 'pending';
				break;
			case 'processing':
				$new_status = 'pending';
				break;
			case 'on-hold':
				$new_status = 'pending';
				break;
			case 'completed':
				$new_status = 'completed';
				break;
			case 'cancelled':
				$new_status = 'cancelled';
				break;
			case 'failed':
				$new_status = 'cancelled';
				break;
			case 'refunded':
				$new_status = 'refunded';
				break;
			default:
				$new_status = 'pending';
				break;
		}
		update_post_meta( $booking_id, 'mwb_booking_status', $new_status );
		update_post_meta( $booking_id, 'trigger_admin_email', 'yes' );

	}

	/**
	 * Update Order meta to the booking post meta.
	 *
	 * @param [int]  $order_id Order ID.
	 * @param [type] $booking_id Booking ID after inserting post.
	 * @return void
	 */
	public function save_booking_order_data( $order_id, $booking_id ) {

		$meta_data = get_post_meta( $order_id );
		foreach ( $meta_data as $key => $value ) {
			update_post_meta( $booking_id, $key, $value[0] );
		}
	}

	/**
	 * Fetching the Order(Booking Order) ID after inserting the post.
	 *
	 * @param array $args Arguments.
	 *
	 * @return number $booking_id
	 */
	public function mwb_wc_bk_create_booking( $args ) {

		$product      = get_post( $args['product_id'] );
		$order_id     = $args['order_id'];
		$product_name = $product->post_name;
		$title        = 'Booking for ' . $product_name;
		$booking_id   = wp_insert_post(
			array(
				'post_type'   => 'mwb_cpt_booking',
				'post_title'  => $title,
				'post_status' => 'publish',
			)
		);

		$args['order_meta']['order_id'] = $args['order_id'];
		update_post_meta( $booking_id, '_customer_user', $args['user_id'] );
		update_post_meta( $booking_id, 'mwb_meta_data', $args['order_meta'] );

		return $booking_id;
	}

	/**
	 * Calculation for the booking price
	 *
	 * @return void
	 */
	public function booking_price_cal() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'mwb_wc_bk_public' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$people_total = ! empty( $_POST['people_total'] ) ? sanitize_text_field( wp_unslash( $_POST['people_total'] ) ) : 1;
		$duration     = isset( $_POST['duration'] ) ? sanitize_text_field( wp_unslash( $_POST['duration'] ) ) : 1;

		$product_meta = get_post_meta( $product_id );

		$people_select       = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_people_select'][0] ) : '';
		$people_enable_check = ! empty( $product_meta['mwb_people_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_people_enable_checkbox'][0] ) ) : 'no';
		$enable_people_type  = ! empty( $product_meta['mwb_enable_people_types'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_people_types'][0] ) ) : 'no';
		$added_costs         = ! empty( $product_meta['mwb_booking_added_cost_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_added_cost_select'][0] ) : '';

		if ( empty( $people_select ) ) {
			$enable_people_type = 'no';
		}

		$people          = array();
		$people_data     = array();
		$added_cost_data = array();
		$added_cost_arr  = array();

		$booking_people_cost  = 0;
		$booking_added_cost   = 0;
		$booking_cost         = 0;
		$booking_service_cost = 0;
		$total_added_cost     = 0;
		$total_service_cost   = 0;

		if ( 'yes' === $people_enable_check && 'yes' === $enable_people_type ) {
			if ( is_array( $people_select ) && ! empty( $people_select ) ) {
				foreach ( $people_select as $k => $v ) {
					$people_term      = get_term( $v );
					$people_term_meta = get_term_meta( $v );
					$people[ $v ]     = ! empty( $_POST['people_count'][ $v ] ) ? sanitize_text_field( wp_unslash( $_POST['people_count'][ $v ] ) ) : '';

					$people_data[ $v ] = array(
						'name'         => $people_term->name,
						'term_id'      => $v,
						'people_count' => ! empty( $people[ $v ] ) ? $people[ $v ] : 0,
					);
					foreach ( $people_term_meta as $key => $value ) {
						$people_data[ $v ]['people_meta'][ $key ] = ! empty( $value[0] ) ? $value[0] : '';
					}
				}
			}
		}

		$unit_cost            = ! empty( $product_meta['mwb_booking_unit_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_input'][0] ) ) : '';
		$unit_cost_multiply   = ! empty( $product_meta['mwb_booking_unit_cost_multiply'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_multiply'][0] ) ) : 'no';
		$base_cost            = ! empty( $product_meta['mwb_booking_base_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_input'][0] ) ) : 0;
		$base_cost_multiply   = ! empty( $product_meta['mwb_booking_base_cost_multiply'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_multiply'][0] ) ) : 'no';
		$extra_cost           = ! empty( $product_meta['mwb_booking_extra_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_extra_cost_input'][0] ) ) : 0;
		$extra_cost_people    = ! empty( $product_meta['mwb_booking_extra_cost_people_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_extra_cost_people_input'][0] ) ) : 1;
		$service_enable_check = ! empty( $product_meta['mwb_services_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_services_enable_checkbox'][0] ) ) : 'no';

		$enabled_services = ! empty( $product_meta['mwb_booking_services_select'][0] ) ? maybe_unserialize( sanitize_text_field( wp_unslash( $product_meta['mwb_booking_services_select'][0] ) ) ) : '';

		if ( 'yes' === $people_enable_check ) {
			if ( 'yes' === $enable_people_type ) {
				if ( is_array( $people_select ) && ! empty( $people_select ) ) {
					foreach ( $people_select as $id ) {
						if ( ! empty( $unit_cost_multiply ) && 'yes' === $unit_cost_multiply ) {
							if ( ! empty( $people_data[ $id ]['people_count'] ) ) {
								if ( ! empty( $people_data[ $id ]['people_meta']['mwb_ct_booking_people_unit_cost'] ) ) {
									$booking_people_cost += $people_data[ $id ]['people_meta']['mwb_ct_booking_people_unit_cost'] * $people_data[ $id ]['people_count'];
								} else {
									$booking_people_cost += $unit_cost * $people_data[ $id ]['people_count'];
								}
							}
						} else {
							if ( ! empty( $people_data[ $id ]['people_count'] ) ) {
								if ( ! empty( $people_data[ $id ]['people_meta']['mwb_ct_booking_people_unit_cost'] ) ) {
									$booking_people_cost += $people_data[ $id ]['people_meta']['mwb_ct_booking_people_unit_cost'];
								} else {
									$booking_people_cost += $unit_cost;
								}
							}
						}
						if ( ! empty( $base_cost_multiply ) && 'yes' === $base_cost_multiply ) {
							if ( ! empty( $people_data[ $id ]['people_count'] ) ) {
								if ( ! empty( $people_data[ $id ]['people_meta']['mwb_ct_booking_people_base_cost'] ) ) {
									$booking_people_cost += $people_data[ $id ]['people_meta']['mwb_ct_booking_people_base_cost'] * $people_data[ $id ]['people_count'];
								} else {
									$booking_people_cost += $base_cost * $people_data[ $id ]['people_count'];
								}
							}
						} else {
							if ( ! empty( $people_data[ $id ]['people_count'] ) ) {
								if ( ! empty( $people_data[ $id ]['people_meta']['mwb_ct_booking_people_base_cost'] ) ) {
									$booking_people_cost += $people_data[ $id ]['people_meta']['mwb_ct_booking_people_base_cost'];
								} else {
									$booking_people_cost += $base_cost;
								}
							}
						}
					}
				}
			} else {
				if ( ! empty( $unit_cost_multiply ) && 'yes' === $unit_cost_multiply ) {
					$booking_people_cost = $people_total * $unit_cost;
				} else {
					$booking_people_cost = $unit_cost;
				}
				if ( ! empty( $base_cost_multiply ) && 'yes' === $base_cost_multiply ) {
					$booking_people_cost += $people_total * $base_cost;
				} else {
					$booking_people_cost += $base_cost;
				}
				$booking_people_cost = $booking_people_cost * $duration;
			}
		} else {

			$booking_people_cost = ( $unit_cost + $base_cost ) * $duration;
		}

		if ( empty( $unit_cost_multiply ) || 'no' === $unit_cost_multiply ) {

			if ( ! empty( $extra_cost ) ) {
				if ( ! empty( $people_total ) ) {
					if ( ! empty( $extra_cost_people ) ) {
						$booking_people_cost += $extra_cost * floor( $people_total / $extra_cost_people );
					} else {
						$booking_people_cost += $extra_cost;
					}
				}
			}
		}

		if ( is_array( $added_costs ) && ! empty( $added_costs ) ) {
			foreach ( $added_costs as $cost_id ) {
				$cost_term      = get_term( $cost_id );
				$cost_term_meta = get_term_meta( $cost_id );

				$added_cost_data[ $cost_id ] = array(
					'name'    => $cost_term->name,
					'term_id' => $cost_id,
				);
				foreach ( $cost_term_meta as $k => $v ) {

					$added_cost_data[ $cost_id ]['cost_meta'][ $k ] = ! empty( $v[0] ) ? $v[0] : '';
				}
			}
		}

		if ( is_array( $added_costs ) && ! empty( $added_costs ) ) {
			foreach ( $added_costs as $cost_id ) {
				if ( ! empty( $people_total ) ) {
					if ( ! empty( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) && 'yes' === $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) {
						$booking_added_cost = ( ( isset( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] ) ? $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] : 0 ) * $people_total );
					} elseif ( empty( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) || 'no' === $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) {
						$booking_added_cost = ( isset( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] ) ? $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] : 0 );
					}
					if ( ! empty( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_units'] ) && 'yes' === $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_units'] ) {
						$booking_added_cost = $booking_added_cost * $duration;
					}
					$added_cost_arr[ $added_cost_data[ $cost_id ]['name'] ] = $booking_added_cost;
					$total_added_cost                                      += $booking_added_cost;
				}
			}
		}

		if ( ! empty( $enabled_services ) && is_array( $enabled_services ) ) {
			foreach ( $enabled_services as $service_id ) {

				$service_term_meta = get_term_meta( $service_id );
				$service_cost      = ! empty( $service_term_meta['mwb_ct_booking_service_cost'][0] ) ? $service_term_meta['mwb_ct_booking_service_cost'][0] : 0;
				$if_optional       = ! empty( $service_term_meta['mwb_booking_ct_services_optional'][0] ) ? $service_term_meta['mwb_booking_ct_services_optional'][0] : 'no';
				$has_quantity      = ! empty( $service_term_meta['mwb_booking_ct_services_has_quantity'][0] ) ? $service_term_meta['mwb_booking_ct_services_has_quantity'][0] : 'no';
				if ( 'yes' === $has_quantity ) {
					if ( 'yes' === $if_optional ) {
						$service_count = ! empty( $_POST['add_service_count'][ $service_id ] ) ? sanitize_text_field( wp_unslash( $_POST['add_service_count'][ $service_id ] ) ) : 0;
					} else {
						$service_count = ! empty( $_POST['inc_service_count'][ $service_id ] ) ? sanitize_text_field( wp_unslash( $_POST['inc_service_count'][ $service_id ] ) ) : 1;
					}
				} else {
					if ( 'yes' === $if_optional ) {
						if ( ! empty( $_POST['add_service_count'] ) && is_array( $_POST['add_service_count'] ) ) {
							$service_count = array_key_exists( $service_id, $_POST['add_service_count'] ) ? 1 : 0;
						} else {
							$service_count = 0;
						}
					} else {
						if ( ! empty( $_POST['inc_service_count'] ) && is_array( $_POST['inc_service_count'] ) ) {
							$service_count = array_key_exists( $service_id, $_POST['inc_service_count'] ) ? 1 : 0;
						} else {
							$service_count = 0;
						}
					}
				}

				if ( 'yes' === $service_enable_check ) {
					if ( 'yes' === $enable_people_type ) {
						if ( isset( $service_term_meta['mwb_booking_ct_services_multiply_people'][0] ) && ( 'yes' === $service_term_meta['mwb_booking_ct_services_multiply_people'][0] ) ) {
							if ( ! empty( $people_select ) && is_array( $people_select ) ) {
								foreach ( $people_select as $people_id ) {
									$people_term           = get_term( $people_id );
									$service_people_cost   = ! empty( $service_term_meta[ 'mwb_ct_booking_service_cost_' . $people_term->slug ][0] ) ? $service_term_meta[ 'mwb_ct_booking_service_cost_' . $people_term->slug ][0] : $service_cost;
									$booking_service_cost += ( $service_count * $service_people_cost * $people_data[ $people_id ]['people_count'] );
								}
							}
						} else {
							$booking_service_cost = $service_count * $service_cost;
						}
					} else {
						$booking_service_cost = $service_count * $service_cost;
					}

					if ( ! empty( $service_term_meta['mwb_booking_ct_services_multiply_units'][0] ) && 'yes' === $service_term_meta['mwb_booking_ct_services_multiply_units'][0] ) {
						$booking_service_cost = $booking_service_cost * $duration;
					}
				}
				$total_service_cost  += $booking_service_cost;
				$booking_service_cost = 0;
			}
		}

		$booking_cost = $booking_people_cost + $total_added_cost + $total_service_cost;

		$price_html = wc_price( $booking_cost );
		echo wp_json_encode(
			array(
				'price_html'           => $price_html,
				'success'              => true,
				'booking_total_cost'   => $booking_cost,
				'booking_people_cost'  => $booking_people_cost,
				'booking_service_cost' => $total_service_cost,
				'booking_added_cost'   => $total_added_cost,
				'indiv_added_cost_arr' => $added_cost_arr,
				'posted_data'          => $_POST,
			)
		);
		wp_die();
	}

	/**
	 * Calculation of Booking Service Cost.
	 *
	 * @return void
	 */
	public function show_booking_total() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'mwb_wc_bk_public' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$total_cost     = isset( $_POST['total_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['total_cost'] ) ) : 0;
		$base_cost      = isset( $_POST['base_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['base_cost'] ) ) : 0;
		$service_cost   = isset( $_POST['service_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['service_cost'] ) ) : 0;
		$added_cost_arr = isset( $_POST['added_cost_arr'] ) ? array_map( 'sanitize_text_field', $_POST['added_cost_arr'] ) : array();
		?>

		<label for="mwb-wc-bk-total-fields"><b><?php esc_html_e( 'Totals', 'mwb-wc-bk' ); ?></b></label>
		<ul style="list-style-type:none;">
			<?php
			if ( ! empty( $base_cost ) ) {
				?>
				<li>
					<label for=""><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
					<span>&emsp;&#8377;<?php echo esc_html( $base_cost ); ?></span>
					<input type="hidden" name="base_cost" value="<?php echo esc_html( $base_cost ); ?>" >
				</li>
				<?php
			}
			if ( ! empty( $service_cost ) ) {
				?>
				<li>
					<label for=""><?php esc_html_e( 'Service Cost', 'mwb-wc-bk' ); ?></label>
					<span>&emsp;&#8377;<?php echo esc_html( $service_cost ); ?></span>
					<input type="hidden" name="service_cost" value="<?php echo esc_html( $service_cost ); ?>" >
				</li>
				<?php
			}
			if ( ! empty( $added_cost_arr ) && is_array( $added_cost_arr ) ) {
				foreach ( $added_cost_arr as $name => $cost ) {
					if ( ! empty( $cost ) ) {
						?>
				<li>
					<label for=""><?php echo esc_html( $name ); ?><?php esc_html_e( '-cost', 'mwb-wc-bk' ); ?></label>
					<span>&emsp;&#8377;<?php echo esc_html( $cost ); ?></span>
					<input type="hidden" name="added_cost-<?php echo esc_html( strtolower( $name ) ); ?>" value="<?php echo esc_html( $cost ); ?>" >
				</li>
						<?php
					}
				}
			}
			if ( ! empty( $total_cost ) ) {
				?>
				<li>
					<label for=""><b><?php esc_html_e( 'Total Cost', 'mwb-wc-bk' ); ?></b></label>
					<span><b>&emsp;&#8377;<?php echo esc_html( $total_cost ); ?></b></span>
					<input type="hidden" name="total_cost" value="<?php echo esc_html( $total_cost ); ?>">
				</li>
		<?php } ?>
		</ul>
		<?php
		wp_die();
	}

	/**
	 * Add a tab to the menu linkjs array to show All Booking on my account page.
	 *
	 * @param [array] $menu_links Array of the tabs.
	 * @return array
	 */
	public function mwb_booking_list_user_bookings( $menu_links ) {

		$menu_links['all_bookings'] = __( 'All Bookings', 'mwb-wc-bk' );

		return $menu_links;
	}

	/**
	 * Register the endpoint for the new tab.
	 *
	 * @return void
	 */
	public function mwb_booking_add_endpoint() {

		add_rewrite_endpoint( 'all_bookings', EP_PAGES );
	}

	/**
	 * Content for the abaove end point created.
	 *
	 * @return void
	 */
	public function mwb_booking_endpoint_content() {

		require_once MWB_WC_BK_BASEPATH . 'public/partials/mwb-booking-list-user-bookings.php';
	}

}
