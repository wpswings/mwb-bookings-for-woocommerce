<?php

/**
 * Booking product class
 */
class WC_Product_MWB_Booking extends WC_Product {

	public function __construct( $product ) {
		$this->product_type = 'mwb_booking';
		parent::__construct( $product );
	}

	/**
	 * Return the product type
	 *
	 * @return string
	 */
	public function get_type() {
		return 'mwb_booking';
	}
}
