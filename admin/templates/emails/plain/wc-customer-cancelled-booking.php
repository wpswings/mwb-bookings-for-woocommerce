<?php
/**
 * Cancelled Booking email ( Plain Text )
 *
 * @author  MWB
 * @package mwb-woocommerce-booking/admin/templates/emails
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


echo $email_heading . "\n\n";

// translators: $1: customer's billing first name and last name
printf( __( 'Booking belonging to %1$s has been cancelled. Their booking\'s details are as follows:', 'mwb-wc-bk' ), $order->get_formatted_billing_full_name() );

echo "\n\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";

/**
 * @hooked WC_Subscriptions_Email::order_details() Shows the order details table.
 * @since 2.1.0
 */
do_action( 'woocommerce_booking_email_order_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n----------\n\n";

$meta = $order->get_meta( 'mwb_meta_data' );

if ( ! empty( $meta['start_date'] ) ) {
	// translators: placeholder is last time subscription was paid
	echo sprintf( __( 'Satrt Booking Date: %s', 'mwb-wc-bk' ), esc_html( $meta['start_date'] ) ) . "\n";
}

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

echo "\n" . sprintf( _x( 'View Booking: %s', 'in plain emails for subscription information', 'mwb-wc-bk' ), get_edit_post_link( $order->get_id() ) ) . "\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
}

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );


