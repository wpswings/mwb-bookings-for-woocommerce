<?php
/**
 * The file that defines the core plugin api class
 *
 * A class definition that includes api's endpoints and functions used across the plugin
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/package/rest-api/version1
 */

/**
 * The core plugin  api class.
 *
 * This is used to define internationalization, api-specific hooks, and
 * endpoints for plugin.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/package/rest-api/version1
 */
class Mwb_Bookings_For_Woocommerce_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin api.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the merthods, and set the hooks for the api and
	 *
	 * @since    2.0.0
	 * @param   string $plugin_name    Name of the plugin.
	 * @param   string $version        Version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	/**
	 * Define endpoints for the plugin.
	 *
	 * Uses the Mwb_Bookings_For_Woocommerce_Rest_Api class in order to create the endpoint
	 * with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function mwb_mbfw_add_endpoint() {
		register_rest_route(
			'wps-bfw/v1',
			'/bookings',
			array(
				'methods'  => 'GET',
				'permission_callback' => function () {
					return is_user_logged_in();
				},
				'callback' => array( $this, 'wps_bfw_get_user_bookings' ),
			)
		);
	}


	/**
	 * Begins validation process of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $result   return rest response to server from where the endpoint hits.
	 * @since    2.0.0
	 */
	public function mwb_mbfw_default_permission_check( $request ) {

		// Add rest api validation for each request.
		$result = true;
		return $result;
	}


	/**
	 * Begins execution of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $mwb_mbfw_response   return rest response to server from where the endpoint hits.
	 * @since    2.0.0
	 */
	public function mwb_mbfw_default_callback( $request ) {

		require_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'package/rest-api/version1/class-mwb-bookings-for-woocommerce-api-process.php';
		$mwb_mbfw_api_obj     = new Mwb_Bookings_For_Woocommerce_Api_Process();
		$mwb_mbfw_resultsdata = $mwb_mbfw_api_obj->mwb_mbfw_default_process( $request );
		if ( is_array( $mwb_mbfw_resultsdata ) && isset( $mwb_mbfw_resultsdata['status'] ) && 200 == $mwb_mbfw_resultsdata['status'] ) {
			unset( $mwb_mbfw_resultsdata['status'] );
			$mwb_mbfw_response = new WP_REST_Response( $mwb_mbfw_resultsdata, 200 );
		} else {
			$mwb_mbfw_response = new WP_Error( $mwb_mbfw_resultsdata );
		}
		return $mwb_mbfw_response;
	}

	/**
	 * Get User Bookings.
	 *
	 * @return WP_REST_Response
	 */
	public function wps_bfw_get_user_bookings() {

		$user_id = get_current_user_id();
		$orders  = wc_get_orders([
			'customer' => $user_id,
			'limit'    => -1,
		]);

		$results = [];

		foreach ( $orders as $order ) {
			if ( 'checkout-draft' == $order->get_status() ) {
				continue;
			}

			foreach ( $order->get_items() as $item ) {

				$product = $item->get_product();
				
				if ( ! $product ) continue;

				$wps_booking_details_ = "";
				$calendar_url       = '';
				if ( $product instanceof WC_Product && $product->is_type( 'mwb_booking' ) ) {
						$booking_name     = $product->get_name();
						$event_venue     = wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_booking_location', true );
						$date_time_from   = $item->get_meta( '_wps_single_cal_date_time_from', true );
						$date_time_to     = $item->get_meta( '_wps_single_cal_date_time_to', true );
						$single_cal_dates = $item->get_meta( '_wps_single_cal_booking_dates', true );
						
						if ( ! empty( $single_cal_dates ) ) {

							$start_timestamp    = strtotime( gmdate( 'Y-m-d 00:00', strtotime( $single_cal_dates ) ) );
							$end_timestamp      = strtotime( gmdate( 'Y-m-d 23:59', strtotime( $single_cal_dates ) ) );
							$gmt_offset_seconds = $this->wps_mbfw_get_gmt_offset_seconds( $start_timestamp );
							$calendar_url       = 'https://calendar.google.com/calendar/r/eventedit?text=' . $booking_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;
							$wps_booking_details_ = $single_cal_dates;
							
						} else if ( ! empty( $date_time_from ) && ! empty( $date_time_from ) ) {

							$start_timestamp    = strtotime( $date_time_from );
							$end_timestamp      = strtotime( $date_time_to );
							$gmt_offset_seconds = $this->wps_mbfw_get_gmt_offset_seconds( $start_timestamp );
							$calendar_url       = 'https://calendar.google.com/calendar/r/eventedit?text=' . $booking_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;
							$wps_booking_details_ = ( $date_time_from ).' '.esc_html( ' To ', 'mwb-bookings-for-woocommerce' ).' '.( $date_time_to );
							
						} else {

							$date_time_from     = $item->get_meta( '_mwb_bfwp_date_time_from', true );
							$date_time_to       = $item->get_meta( '_mwb_bfwp_date_time_to', true );
							$start_timestamp    = strtotime( $date_time_from );
							$end_timestamp      = strtotime( $date_time_to );
							$gmt_offset_seconds = $this->wps_mbfw_get_gmt_offset_seconds( $start_timestamp );
							$calendar_url       = 'https://calendar.google.com/calendar/r/eventedit?text=' . $booking_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;
							$wps_booking_details_ = ( $date_time_from ).' '.esc_html( ' To ', 'mwb-bookings-for-woocommerce' ).' '.( $date_time_to );

						}
					} else if ( 'yes' === get_post_meta($product->get_id(), '_is_calendar_booking_product', 'no')) {
					
						$wps_booking_details_ = wc_get_order_item_meta($item->get_id(), 'Booking Date', true);

					}

				$pro_short_desc = get_post_meta( $product->get_id(), '_short_description', true );

				if ( empty( trim( wp_strip_all_tags( $pro_short_desc ) ) ) ) {
						$pro_short_desc = get_the_excerpt( $product->get_id() );
				}
				
					$image_id = $product->get_image_id();
					if ( ! $image_id ) {
						$gallery_ids = $product->get_gallery_image_ids();

						if ( ! empty( $gallery_ids ) ) {
							$image_id = $gallery_ids[0]; // First gallery image.
						}
					}

					if ( $image_id ) {
						$image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
					} else {
						// WooCommerce default placeholder.
						$image_url = wc_placeholder_img_src( 'woocommerce_thumbnail' );
					}
					if ( 'cancelled' !== $order->get_status() ) {
						$can_cancel = true;
					} else {
						$can_cancel = false;
					}
					
				$results[] = [
					'order_id'   => $order->get_id(),
					'product'    => $product->get_name(),
					'product_id' => $product->get_id(),
					'product_url' => get_permalink( $product->get_id() ),
					'product_rating' => $product->get_average_rating(),
					'status'     => 'wc-' . $order->get_status(),
					'total'      =>  html_entity_decode(wp_strip_all_tags( wc_price( $order->get_total() ) )),
					'image'      => $image_url,
					'booking' => $wps_booking_details_,
					'can_cancel' => $can_cancel,
					'cancel_allowed' => get_post_meta( $product->get_id(), 'mwb_mbfw_cancellation_allowed', true ),
					'view_order_url' => esc_url(
									wc_get_endpoint_url(
										'view-order',
										$order->get_id(),
										wc_get_page_permalink( 'myaccount' )
									)
									),
					'calendar_url' => $calendar_url,
					'short_desc' => $pro_short_desc,
					'payment_method' => $order->get_payment_method_title(),

				];
			}
		}

		return rest_ensure_response( $results );
	}


	/**
	 * Get timezone by offset.
	 *
	 * @param mixed $offset Offset.
	 * @return string
	 */
	public function wps_mbfw_get_timezone_by_offset( $offset ) {
		$seconds = $offset * 3600;

		$timezone = timezone_name_from_abbr( '', $seconds, 0 );
		if ( false === $timezone ) {
			$timezones = array(
				'-12' => 'Pacific/Auckland',
				'-11.5' => 'Pacific/Auckland', // Approx.
				'-11' => 'Pacific/Apia',
				'-10.5' => 'Pacific/Apia', // Approx.
				'-10' => 'Pacific/Honolulu',
				'-9.5' => 'Pacific/Honolulu', // Approx.
				'-9' => 'America/Anchorage',
				'-8.5' => 'America/Anchorage', // Approx.
				'-8' => 'America/Los_Angeles',
				'-7.5' => 'America/Los_Angeles', // Approx.
				'-7' => 'America/Denver',
				'-6.5' => 'America/Denver', // Approx.
				'-6' => 'America/Chicago',
				'-5.5' => 'America/Chicago', // Approx.
				'-5' => 'America/New_York',
				'-4.5' => 'America/New_York', // Approx.
				'-4' => 'America/Halifax',
				'-3.5' => 'America/Halifax', // Approx.
				'-3' => 'America/Sao_Paulo',
				'-2.5' => 'America/Sao_Paulo', // Approx.
				'-2' => 'America/Sao_Paulo',
				'-1.5' => 'Atlantic/Azores', // Approx.
				'-1' => 'Atlantic/Azores',
				'-0.5' => 'UTC', // Approx.
				'0' => 'UTC',
				'0.5' => 'UTC', // Approx.
				'1' => 'Europe/Paris',
				'1.5' => 'Europe/Paris', // Approx.
				'2' => 'Europe/Helsinki',
				'2.5' => 'Europe/Helsinki', // Approx.
				'3' => 'Europe/Moscow',
				'3.5' => 'Europe/Moscow', // Approx.
				'4' => 'Asia/Dubai',
				'4.5' => 'Asia/Tehran',
				'5' => 'Asia/Karachi',
				'5.5' => 'Asia/Kolkata',
				'5.75' => 'Asia/Katmandu',
				'6' => 'Asia/Yekaterinburg',
				'6.5' => 'Asia/Yekaterinburg', // Approx.
				'7' => 'Asia/Krasnoyarsk',
				'7.5' => 'Asia/Krasnoyarsk', // Approx.
				'8' => 'Asia/Shanghai',
				'8.5' => 'Asia/Shanghai', // Approx.
				'8.75' => 'Asia/Tokyo', // Approx.
				'9' => 'Asia/Tokyo',
				'9.5' => 'Asia/Tokyo', // Approx.
				'10' => 'Australia/Melbourne',
				'10.5' => 'Australia/Adelaide',
				'11' => 'Australia/Melbourne', // Approx.
				'11.5' => 'Pacific/Auckland', // Approx.
				'12' => 'Pacific/Auckland',
				'12.75' => 'Pacific/Apia', // Approx.
				'13' => 'Pacific/Apia',
				'13.75' => 'Pacific/Honolulu', // Approx.
				'14' => 'Pacific/Honolulu',
			);

			$timezone = isset( $timezones[ $offset ] ) ? $timezones[ $offset ] : null;
		}

		return $timezone;
	}

	/**
	 * Get default timezone of WordPress.
	 *
	 * @param mixed $event Event Date.
	 * @return string
	 */
	public function wps_mbfw_get_timezone( $event = null ) {
		$timezone_string = get_option( 'timezone_string' );
		$gmt_offset = get_option( 'gmt_offset' );

		if ( trim( $timezone_string ) == '' && trim( $gmt_offset ) ) {
			$timezone_string = $this->wps_mbfw_get_timezone_by_offset( $gmt_offset );
		} elseif ( trim( $timezone_string ) == '' && trim( $gmt_offset ) == '0' ) {
			$timezone_string = 'UTC';
		}

		return $timezone_string;
	}


	/**
	 * Get GMT offset based on seconds.
	 *
	 * @param string $date Event Start Date.
	 * @return string
	 */
	public function wps_mbfw_get_gmt_offset_seconds( $date = null ) {
		if ( $date ) {
			$timezone = new DateTimeZone( $this->wps_mbfw_get_timezone() );

			// Convert to Date.
			if ( is_numeric( $date ) ) {
				$date = gmdate( 'Y-m-d', $date );
			}

			$target = new DateTime( $date, $timezone );
			return $timezone->getOffset( $target );
		} else {
			$gmt_offset = get_option( 'gmt_offset' );
			$seconds = $gmt_offset * HOUR_IN_SECONDS;

			return ( substr( $gmt_offset, 0, 1 ) == '-' ? '' : '+' ) . $seconds;
		}
	}

}
