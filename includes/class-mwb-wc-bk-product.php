<?php   // @codingStandardsIgnoreLine
/**
 * The Register Booking Product of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
 */

/**
 * Booking product class
 */
class WC_Product_MWB_Booking extends WC_Product {

	/**
	 * Construct for registering booking product type
	 *
	 * @param int|WC_Product_MWB_Booking|object $product Product to init.
	 */
	public function __construct( $product ) {
		$this->product_type = 'mwb_booking';

		$this->vitual_product = 'yes';
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
