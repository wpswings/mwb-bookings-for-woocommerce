<?php
/**
 * Customer confirmed Booking email
 *
 * @author  MWB
 * @package mwb-woocommerce-booking/admin/templates/emails
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p><?php esc_html_e( 'We have confirmed your booking.', 'woocommerce' ); ?></p>

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
				<a href="<?php echo esc_url( get_option( 'siteurl' ) . '/my-account/all_bookings/?booking_id=' . $booking_id ); ?>">#<?php echo esc_html( $booking_id ); ?></a>
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
<?php $order_data = $order->get_data(); ?>
<section class="woocommerce-customer-details">
	<h2 class="woocommerce-column__title"><?php esc_html_e( 'Billing address', 'mwb-wc-bk' ); ?></h2>
	<address>
		<?php echo esc_html( $order_data['billing']['first_name'] ); ?><br><?php echo esc_html( $order_data['billing']['last_name'] ); ?><br><?php echo esc_html( $order_data['billing']['address_1'] ); ?><br><?php echo esc_html( $order_data['billing']['address_2'] ); ?><br><?php echo esc_html( $order_data['billing']['city'] ); ?><?php echo esc_html( $order_data['billing']['state'] ); ?><br><?php echo esc_html( $order_data['billing']['postcode'] ); ?><br><?php echo esc_html( $order_data['billing']['country'] ); ?>
			<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order_data['billing']['phone'] ); ?></p>	
			<p class="woocommerce-customer-details--email"><?php echo esc_html( $order_data['billing']['email'] ); ?></p>
	</address>
</section>
<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
// do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
// do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
