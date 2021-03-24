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
class WC_Booking_New extends WC_Email {

	/**
	 * Create an instance of the class.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id             = 'customer_new_booking';
		$this->customer_email = true;
		$this->title          = __( 'New Booking Created', 'mwb-wc-bk' );
		$this->description    = __( 'Booking created emails are sent to customers when their bookings are created.', 'mwb-wc-bk' );
		$this->heading        = __( 'New Booking Created', 'custom-wc-email' );

		// translators: placeholder is {blogname}, a variable that will be substituted when email is sent out.
		$this->subject = sprintf( _x( '[%s] Booking Created', 'A new booking has been created', 'mwb-wc-bk' ), '{blogname}' );

		$this->template_html  = 'emails/wc-customer-new-booking.php';
		$this->template_plain = 'emails/plain/wc-customer-new-booking.php';
		$this->template_base  = MWB_WC_BK_BASEPATH . 'admin/templates/';

		$this->placeholders = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);
		// Action to which we hook onto to send the email.

		// add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ), 10, 2 );	
		add_action( 'mwb_booking_created' , array( $this, 'trigger' ), 10, 2 );

		//add_action( 'mwb_booking_status_pending', array( $this, 'trigger' ), 10, 2 );
		// add_action( 'mwb_booking_status_pending_to_cancelled', array( $this, 'trigger', 10, 2 ) );
		// add_action( 'mwb_booking_status_confirmation_to_cancelled', array( $this, 'trigger', 10, 2 ) );

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

		$order        = wc_get_order( $order_id );
		$booking_meta = get_post_meta( $booking_id, 'mwb_meta_data', true );
		$product_id   = $booking_meta['product_id'];
		$author       = get_post( $product_id )->post_author;

			// die('working');
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

			$this->subject = __( 'Cancelled booking', 'mwb-wc-bk' );

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
