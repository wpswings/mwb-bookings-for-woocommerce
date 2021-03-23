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
class WC_Booking_Confirmation_Required extends WC_Email {

	/**
	 * Create an instance of the class.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id             = 'customer_confirmation_booking';
		$this->customer_email = true;
		$this->title          = __( 'Confirmed Required for Booking', 'woocommerce' );
		$this->description    = __( 'Booking confirmation required emails are sent to customers when their booking is under confirmation.', 'woocommerce' );
		$this->heading        = __( 'Booking Confirmation Required', 'custom-wc-email' );

		// translators: placeholder is {blogname}, a variable that will be substituted when email is sent out.
		$this->subject = sprintf( _x( '[%s] Booking is completed', 'Congratulations your booking is under confirmation', 'mwb-wc-bk' ), '{blogname}' );

		$this->template_html  = 'emails/wc-customer-confirmation-booking.php';
		$this->template_plain = 'emails/plain/wc-customer-confirmation-booking.php';
		$this->template_base  = MWB_WC_BK_BASEPATH . 'admin/templates/';

		$this->placeholders = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);
		// Action to which we hook onto to send the email.

		add_action( 'mwb_booking_status_pending_to_confirmation', array( $this, 'trigger', 10, 2 ) );
		add_action( 'woocommerce_order_status_confirmation', array( $this, 'trigger' ), 10, 2 );

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

			$this->subject = __( 'Booking Confirmation required', 'wooocommerce' );

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
