<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public/partials
 */

use Automattic\WooCommerce\Utilities\OrderUtil;
$table_headers = array(
	'order-id'        => esc_html__( 'Order ID', 'mwb-bookings-for-woocommerce' ),
	'booking-date'    => esc_html__( 'Booking Date', 'mwb-bookings-for-woocommerce' ),
	'booking-status'  => esc_html__( 'Booking Status', 'mwb-bookings-for-woocommerce' ),
	'booking-total'   => esc_html__( 'Total', 'mwb-bookings-for-woocommerce' ),
	'booking-actions' => esc_html__( 'Actions', 'mwb-bookings-for-woocommerce' ),
);


$event_attendees_details = array();
$customer               = wp_get_current_user(); // do this when user is logged in.
if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
	$customer_orders = wc_get_orders(
		array(
			'limit'       => -1,
			'customer_id' => get_current_user_id(),
			'status'      => array_keys( wc_get_order_statuses() ),
			'return'      => 'ids',
		)
	);
} else {

	$customer_orders = get_posts(
		array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'orderby'     => 'date',
			'order'       => 'DESC',
			'meta_value'  => get_current_user_id(),
			'post_status' => array_keys( wc_get_order_statuses() ),
			'post_type'   => 'shop_order',
			'fields'      => 'ids',
		)
	);
}
?>

<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
	<thead>
		<tr>
			<?php foreach ( $table_headers as $column_id => $column_name ) : ?>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<?php

	if ( ! empty( $customer_orders ) ) {
		foreach ( $customer_orders as $key => $value ) {

			$_order = wc_get_order( $value );
			foreach ( $_order->get_items() as $item_id => $item ) {

				$product        = $item->get_product();

				if ( empty( $product ) ) {
					continue;
				}
				$pro_short_desc = get_post_meta( $product->get_id(), '_short_description', true );

				$data = array();
				$status_booking = '';
				$data = get_post_meta( $item->get_product_id(), 'mwb_bfwp_order_statuses_to_cancel', true );

				if ( 'processing' == $_order->get_status() ) {
					$status_booking = 'wc-processing';
				} elseif ( 'pending' == $_order->get_status() ) {
					$status_booking = 'wc-processing';
				} elseif ( 'on-hold' == $_order->get_status() ) {
					$status_booking = 'wc-on-hold';
				} elseif ( 'checkout-draft' == $_order->get_status() ) {
					$status_booking = 'wc-checkout-draft';
				} else {
					$status_booking = $_order->get_status();
				}


				if ( $product instanceof WC_Product && $product->is_type( 'mwb_booking' ) ) {

					$booking_name     = $product->get_name();
					$event_venue     = wps_booking_get_meta_data( $product->get_id(), 'mwb_mbfw_booking_location', true );
					$date_time_from   = $item->get_meta( '_wps_single_cal_date_time_from', true );
					$date_time_to     = $item->get_meta( '_wps_single_cal_date_time_to', true );
					$single_cal_dates = $item->get_meta( '_wps_single_cal_booking_dates', true );
					if ( ! empty( $single_cal_dates ) ) {

						$start_timestamp    = strtotime( gmdate( 'Y-m-d 00:00', strtotime( $single_cal_dates ) ) );
						$end_timestamp      = strtotime( gmdate( 'Y-m-d 23:59', strtotime( $single_cal_dates ) ) );
						$gmt_offset_seconds = wps_mbfw_get_gmt_offset_seconds( $start_timestamp );
						$calendar_url       = 'https://calendar.google.com/calendar/r/eventedit?text=' . $booking_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;

						?>
						<tr>
							<td><?php echo esc_html( $value ); ?></td>
							<td><?php echo esc_html( $single_cal_dates ); ?></td>
							<td><?php echo esc_html( $_order->get_status() ); ?></td>
							<td><?php echo wp_kses_post( wc_price( $_order->get_total() ) ); ?></td>
							<td>
								<a href="<?php echo esc_attr( $calendar_url ); ?>" class="button" style="margin-bottom:5px;" target="_blank"><?php esc_html_e( '+ Add to Google Calendar', 'mwb-bookings-for-woocommerce' ); ?></a>
								<a class="button" href="
									<?php
									echo esc_attr( get_site_url() );
									echo esc_html( '/my-account/view-order/' );
									echo esc_attr( $value );
									?>
								"><?php esc_html_e( 'View', 'mwb-bookings-for-woocommerce' ); ?>
							</a>
							<?php
							if ( 'yes' === wps_booking_get_meta_data( $item->get_product_id(), 'mwb_mbfw_cancellation_allowed', true ) ) {
								if ( 'cancelled' !== $_order->get_status() ) {
									if ( ! empty( $data ) ) {
										if ( in_array( $status_booking, $data ) ) {
											?>
											<button class="button" id="wps_bfw_cancel_order" data-product="<?php echo esc_html( $item->get_product_id() ); ?>" data-order="<?php echo esc_html( $_order->get_id() ); ?>">Cancel</button>
											<?php
										}
									} else {
										?>
										<button class="button" id="wps_bfw_cancel_order" data-product="<?php echo esc_html( $item->get_product_id() ); ?>" data-order="<?php echo esc_html( $_order->get_id() ); ?>">Cancel</button>
										<?php

									}
								}
							}
							?>
							</td>
						</tr>
						<?php
					} else if ( ! empty( $date_time_from ) && ! empty( $date_time_from ) ) {

						$start_timestamp    = strtotime( $date_time_from );
						$end_timestamp      = strtotime( $date_time_to );
						$gmt_offset_seconds = wps_mbfw_get_gmt_offset_seconds( $start_timestamp );
						$calendar_url       = 'https://calendar.google.com/calendar/r/eventedit?text=' . $booking_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;
						?>
						<tr>
							<td><?php echo esc_html( $value ); ?></td>
							<td>
							<?php
							echo esc_html( $date_time_from );
							esc_html_e( ' To ', 'mwb-bookings-for-woocommerce' );
							echo esc_html( $date_time_to );
							?>
							</td>
							<td><?php echo esc_html( $_order->get_status() ); ?></td>
							<td><?php echo wp_kses_post( wc_price( $_order->get_total() ) ); ?></td>
							<td>
								<a href="<?php echo esc_attr( $calendar_url ); ?>" class="button" style="margin-bottom:5px;" target="_blank"><?php esc_html_e( '+ Add to Google Calendar', 'mwb-bookings-for-woocommerce' ); ?></a>
								<a class="button" href="
								<?php
								echo esc_attr( get_site_url() );
								echo esc_html( '/my-account/view-order/' );
								echo esc_attr( $value );
								?>
								"><?php esc_html_e( 'View', 'mwb-bookings-for-woocommerce' ); ?>
								</a>
								<?php
								if ( 'yes' === wps_booking_get_meta_data( $item->get_product_id(), 'mwb_mbfw_cancellation_allowed', true ) ) {

									if ( 'cancelled' !== $_order->get_status() ) {

										if ( ! empty( $data ) ) {
											if ( in_array( $status_booking, $data ) ) {
												?>
												<button class="button" id="wps_bfw_cancel_order" data-product="<?php echo esc_html( $item->get_product_id() ); ?>" data-order="<?php echo esc_html( $_order->get_id() ); ?>">Cancel</button>
												<?php
											}
										} else {
											?>
											<button class="button" id="wps_bfw_cancel_order" data-product="<?php echo esc_html( $item->get_product_id() ); ?>" data-order="<?php echo esc_html( $_order->get_id() ); ?>">Cancel</button>
											<?php

										}
									}
								}
								?>
							</td>
						</tr>
						<?php
					} else {

						$date_time_from     = $item->get_meta( '_mwb_bfwp_date_time_from', true );
						$date_time_to       = $item->get_meta( '_mwb_bfwp_date_time_to', true );
						$start_timestamp    = strtotime( $date_time_from );
						$end_timestamp      = strtotime( $date_time_to );
						$gmt_offset_seconds = wps_mbfw_get_gmt_offset_seconds( $start_timestamp );
						$calendar_url       = 'https://calendar.google.com/calendar/r/eventedit?text=' . $booking_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;
						?>
						<tr>
							<td><?php echo esc_html( $value ); ?></td>
							<td>
								<?php
								echo esc_html( $date_time_from );
								esc_html_e( ' To ', 'mwb-bookings-for-woocommerce' );
								echo esc_html( $date_time_to );
								?>
								</td>
							<td><?php echo esc_html( $_order->get_status() ); ?></td>
							<td><?php echo wp_kses_post( wc_price( $_order->get_total() ) ); ?></td>
							<td>
								<a href="<?php echo esc_attr( $calendar_url ); ?>" class="button" style="margin-bottom:5px;" target="_blank"><?php esc_html_e( '+ Add to Google Calendar', 'mwb-bookings-for-woocommerce' ); ?></a>
								<a class="button" href="
									<?php
									echo esc_attr( get_site_url() );
									echo esc_html( '/my-account/view-order/' );
									echo esc_attr( $value );
									?>
									"><?php esc_html_e( 'View', 'mwb-bookings-for-woocommerce' ); ?>
								</a>
								<?php
								if ( 'yes' === wps_booking_get_meta_data( $item->get_product_id(), 'mwb_mbfw_cancellation_allowed', true ) ) {

									if ( 'cancelled' !== $_order->get_status() ) {
										if ( ! empty( $data ) ) {
											if ( in_array( $status_booking, $data ) ) {
												?>
												<button class="button" id="wps_bfw_cancel_order" data-product="<?php echo esc_html( $item->get_product_id() ); ?>" data-order="<?php echo esc_html( $_order->get_id() ); ?>">Cancel</button>
												<?php
											}
										} else {
											?>
											<button class="button" id="wps_bfw_cancel_order" data-product="<?php echo esc_html( $item->get_product_id() ); ?>" data-order="<?php echo esc_html( $_order->get_id() ); ?>">Cancel</button>
											<?php

										}
									}
								}
								?>
							</td>
						</tr>
						<?php
					}
				}
			}
		}
	} else {
		?>
		<tr>
		<td colspan="5"><?php esc_html_e( 'No bookings have been purchased yet.', 'mwb-bookings-for-woocommerce' ); ?></td></tr>
		<?php
	}
	?>
</table>


<?php
/**
 * Get timezone by offset.
 *
 * @param mixed $offset Time offset.
 * @return string.
 */
function wps_mbfw_get_timezone_by_offset( $offset ) {
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
	 * @return string.
	 */
function wps_mbfw_get_timezone( $event = null ) {
	$timezone_string = get_option( 'timezone_string' );
	$gmt_offset = get_option( 'gmt_offset' );

	if ( trim( $timezone_string ) == '' && trim( $gmt_offset ) ) {
		$timezone_string = wps_mbfw_get_timezone_by_offset( $gmt_offset );
	} elseif ( trim( $timezone_string ) == '' && trim( $gmt_offset ) == '0' ) {
		$timezone_string = 'UTC';
	}

	return $timezone_string;
}


	/**
	 * Get GMT offset based on seconds.
	 *
	 * @param string $date Event Start Date.
	 * @return string.
	 */
function wps_mbfw_get_gmt_offset_seconds( $date = null ) {
	if ( $date ) {
		$timezone = new DateTimeZone( wps_mbfw_get_timezone() );

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
