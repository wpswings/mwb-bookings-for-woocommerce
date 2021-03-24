<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

/**
 * Class WC_Customer_Cancel_Order
 */
class WC_Booking_Refunded extends WC_Email {

	/**
	 * Create an instance of the class.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->customer_email = true;
		$this->id             = 'customer_refunded_booking';
		$this->title          = __( 'Refunded Booking', 'woocommerce' );
		$this->description    = __( 'Booking refunded emails are sent to customers when their booking is refunded.', 'woocommerce' );

		$this->template_html  = 'emails/wc-customer-refunded-booking.php';
		$this->template_plain = 'emails/plain/wc-customer-refunded-booking.php';
		$this->placeholders   = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);

		// Triggers for this email.
		add_action( 'woocommerce_order_fully_refunded_notification', array( $this, 'trigger_full' ), 10, 2 );
		add_action( 'woocommerce_order_partially_refunded_notification', array( $this, 'trigger_partial' ), 10, 2 );

		// Call parent constructor.
		parent::__construct();
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param int            $order_id The order ID.
	 * @param WC_Order|false $order Order object.
	 */
	public function trigger( $booking_id, $order_id ) {
		$this->setup_locale();

		$order = wc_get_order( $order_id );
		// if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
		// 	$order = wc_get_order( $order_id );
		// }mwb_cpt_booking
		// die('kljsdvn');

		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}
		$pos = get_post( ! empty( $booking_id ) ? $booking_id : 0 );
		if ( 'mwb_cpt_booking' !== $pos->post_type ) {
			return;
		}
		// echo '<pre>'; print_r( $pos ); echo '</pre>';

		// echo '<pre>'; var_dump( $order ); echo '</pre>';die("ok");

		if ( is_a( $order, 'WC_Order' ) ) {
			$this->object    = $order;
			$this->recipient = $this->object->get_billing_email();

			$this->subject = __( 'Booking Confirmed', 'wooocommerce' );

			$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
			$this->placeholders['{order_number}'] = $this->object->get_order_number();
		}

		if ( $this->is_enabled() && $this->get_recipient() ) {
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		$this->restore_locale();
	}

	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => false,
				'plain_text'         => false,
				'email'              => $this,
			),
			'',
			$this->template_base
		);
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html(
			$this->template_plain,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => false,
				'plain_text'         => true,
				'email'              => $this,
			),
			'',
			$this->template_base
		);
	}
}
