<?php
/**
 * Customer Booking confirmation email.
 *
 * @author  MakeWebBetter
 * @package MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/admin/templates/emails/plain
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: %s: Customer first name */
echo sprintf( esc_html__( 'Hi %s,', 'mwb-bookings-for-woocommerce' ), esc_html( $order->get_billing_first_name() ) ) . "\n\n";
echo esc_html__( 'Thanks for your booking. It’s under confirmation process.', 'mwb-bookings-for-woocommerce' ) . "\n\n";

echo "\n----------------------------------------\n\n";

if ( ! empty( $booking_meta['start_timestamp'] ) ) {
	// translators: placeholder is last time subscription was paid.
	echo sprintf( __( 'Satrt Booking : %s', 'mwb-bookings-for-woocommerce' ), esc_html( gmdate( 'Y-m-d h:i:s a', $booking_meta['start_timestamp'] ) ) ) . "\n";  // @codingStandardsIgnoreLine
}

if ( ! empty( $booking_meta['end_timestamp'] ) ) {
	// translators: placeholder is last time subscription was paid.
	echo sprintf( __( 'End Booking : %s', 'mwb-bookings-for-woocommerce' ), esc_html( gmdate( 'Y-m-d h:i:s a', $booking_meta['end_timestamp'] ) ) ) . "\n";  // @codingStandardsIgnoreLine
}

if ( ! empty( $booking_meta['total_cost'] ) ) {
	// translators: placeholder is last time subscription was paid.
	echo sprintf( __( 'Total : %s', 'mwb-bookings-for-woocommerce' ), wp_kses_post( get_woocommerce_currency_symbol() . ' ' . $booking_meta['total_cost'] ) ) . "\n";  // @codingStandardsIgnoreLine
}

do_action( 'mwb_booking_add_other_plain_details', $order, $sent_to_admin, $plain_text, $email, $booking_meta );

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
