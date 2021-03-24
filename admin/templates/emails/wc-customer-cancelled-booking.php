<?php
/**
 * Cancelled Booking email
 *
 * @author  makewebbetter
 * @package mwb-woocommerce-booking/admin/templates/emails
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: $1: customer's billing first name and last name */ ?>
<p><?php printf( esc_html__( 'Booking belonging to %1$s has been cancelled. The booking\'s details are as follows:', 'mwb-wc-bk' ), esc_html( $order->get_formatted_billing_full_name() ) ); ?></p>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Booking', 'mwb-wc-bk' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Price', 'table headings in notification email', 'mwb-wc-bk' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Start Date', 'table heading', 'mwb-wc-bk' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'End Date', 'table heading', 'mwb-wc-bk' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td" width="1%" style="text-align:left; vertical-align:middle;">
				<a href="#">#<?php echo esc_html( $order->get_order_number() ); ?></a>
			</td>
			<td class="td" style="text-align:left; vertical-align:middle;">
				<?php $meta = $order->get_meta( 'mwb_meta_data' ); ?>
				<?php echo wp_kses_post( get_woocommerce_currency_symbol() . ' ' . $booking_meta['total_cost'] ); ?>
			</td>
			<td class="td" style="text-align:left; vertical-align:middle;">
				<?php
				// $meta = $order->get_meta( 'mwb_meta_data' );
				echo esc_html( gmdate( 'Y-m-d h:i:s a', $booking_meta['start_timestamp'] ) );
				?>
			</td>
			<td class="td" style="text-align:left; vertical-align:middle;">
				<?php
				echo esc_html( gmdate( 'Y-m-d h:i:s a', $booking_meta['end_timestamp'] ) );
				?>
			</td>
		</tr>
	</tbody>
</table>
<br/>
<?php

// do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

//do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );



/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
