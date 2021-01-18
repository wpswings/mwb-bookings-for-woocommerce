<?php
/**
 * Booking Class.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global Booking Class For MWB Booking Plugin.
 *
 * This class defines the global functions to be used anywhere in the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Woocommerce_Booking {

	/**
	 * Instance of the Booking class.
	 *
	 * @var [type]
	 */
	public static $booking_instance;

	/**
	 * Defining a Booking product.
	 *
	 * @var [type]
	 */
	public $booking_product;

	/**
	 * ID of the Booking Product.
	 *
	 * @var [type]
	 */
	public $booking_product_id;

	/**
	 * Final Price for the booking
	 *
	 * @var [type]
	 */
	public $booking_price;

	/**
	 * Custructor Function.
	 */
	private function __construct() {

		global $product;

		// echo '<pre>'; print_r( $product ); echo '</pre>';

		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			$this->booking_product    = $product;
			$this->booking_product_id = $product->get_id();
		}

		self::$booking_instance = $this;
	}

	/**
	 * Get the ID for the current booking product.
	 *
	 * @return number
	 */
	public function get_booking_product_id() {

		return $this->booking_product_id;
	}

	/**
	 * Get the booking Product Instance
	 *
	 * @return obj
	 */
	public static function get_booking_instance() {

		if ( null === self::$booking_instance ) {
			self::$booking_instance = new self();
		}

		return self::$booking_instance;
	}

	/**
	 * Retrieving the booking product meta.
	 *
	 * @return array
	 */
	public function get_booking_product_meta() {

		$booking_product_meta = get_post_meta( $this->booking_product_id );

		return $booking_product_meta;
	}

	/**
	 * Get Booking Global settings.
	 *
	 * @return array
	 */
	public function get_global_settings() {

		$global_settings = array();
		if ( $this->booking_product && $this->booking_product->is_type( 'mwb_booking' ) ) {
			$global_settings = get_option( 'mwb_booking_settings_options' );
		}

		return $global_settings;
	}


}

