<?php
/**
 * Expired Booking email ( Plain Text )
 *
 * @author  MWB
 * @package mwb-woocommerce-booking/admin/templates/emails/plain
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


echo $email_heading . "\n\n";   // @codingStandardsIgnoreLine

// translators: $1: customer's billing first name and last name.
printf( __( 'Booking belonging to %1$s has been expired. Their booking\'s details are as follows:', 'mwb-wc-bk' ), $order->get_formatted_billing_full_name() );  // @codingStandardsIgnoreLine

echo "\n\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

echo "\n----------\n\n";

if ( ! empty( $booking_meta['start_timestamp'] ) ) {
	// translators: placeholder is last time subscription was paid.
	echo sprintf( __( 'Satrt Booking : %s', 'mwb-wc-bk' ), esc_html( gmdate( 'Y-m-d h:i:s a', $booking_meta['start_timestamp'] ) ) ) . "\n";  // @codingStandardsIgnoreLine
}

if ( ! empty( $booking_meta['end_timestamp'] ) ) {
	// translators: placeholder is last time subscription was paid.
	echo sprintf( __( 'End Booking : %s', 'mwb-wc-bk' ), esc_html( gmdate( 'Y-m-d h:i:s a', $booking_meta['end_timestamp'] ) ) ) . "\n";  // @codingStandardsIgnoreLine
}
if ( ! empty( $booking_meta['total_cost'] ) ) {
	// translators: placeholder is last time subscription was paid.
	echo sprintf( __( 'Total : %s', 'mwb-wc-bk' ), wp_kses_post( get_woocommerce_currency_symbol() . ' ' . $booking_meta['total_cost'] ) ) . "\n";  // @codingStandardsIgnoreLine
}

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'mwb_booking_add_other_plain_details', $order, $sent_to_admin, $plain_text, $email, $booking_meta );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
}

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );  // @codingStandardsIgnoreLine


