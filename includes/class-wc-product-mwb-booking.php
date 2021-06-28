<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to extend WC_Product class.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */

/**
 * Extending WC_Product class.
 */
class WC_Product_Mwb_Booking extends WC_Product {	
	public function __construct( $product ) {
		$this->product_type = 'mwb_booking';
		parent::__construct( $product );
	}
	/**
	 * Will be used when we use $product = wc_get_product(); $product->get_type().
	 *
	 * @return string
	 */
	public function get_type() {
		return 'mwb_booking';
	}
}
