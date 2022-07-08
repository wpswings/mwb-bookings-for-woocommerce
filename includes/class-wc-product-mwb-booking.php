<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to extend WC_Product class.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */

/**
 * Extending WC_Product class.
 */
class WC_Product_Mwb_Booking extends WC_Product {
	/**
	 * Constructor for extended class WC_Product.
	 *
	 * @param object $product product object.
	 */	
	public function __construct( $product ) {
		$this->product_type = 'mwb_booking';
		$this->virtual      = 'yes';
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

	/**
	 * Set product as virtual.
	 *
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return bool
	 */
	public function get_virtual( $context = 'view' ) {
		return true;
	}

	/**
	 * Set product as purchaseable.
	 *
	 * @return boolean
	 */
	public function is_purchasable() {

		/**
		 * Filter to make product purchasable.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'woocommerce_is_purchasable', true, $this );
	}

	/**
	 * Get the add to cart button text for the single page.
	 *
	 * @return string
	 */
	public function single_add_to_cart_text() {

		/**
		 * Filter to make product purchasable.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'woocommerce_product_single_add_to_cart_text', $this->get_button_text() ? $this->get_button_text() : _x( 'Book Now', 'placeholder', 'mwb-bookings-for-woocommerce' ), $this );
	}
	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text() {

		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'woocommerce_product_add_to_cart_text', __( 'View Details', 'mwb-bookings-for-woocommerce' ), $this );
	}

	/**
	 * Get the add to url used mainly in loops.
	 *
	 * @return string
	 */
	public function add_to_cart_url() {
		$url = $this->get_permalink();


		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
	}
	/**
	 * Set product URL.
	 *
	 * @since 1.0.2
	 * @param string $product_url Product URL.
	 */
	public function set_product_url( $product_url ) {
		$this->set_prop( 'product_url', htmlspecialchars_decode( $product_url ) );
	}

	/**
	 * Get product url.
	 *
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return string
	 */
	public function get_product_url( $context = 'view' ) {
		return esc_url_raw( $this->get_prop( 'product_url', $context ) );
	}

	/**
	 * Set button text.
	 *
	 * @since 1.0.2
	 * @param string $button_text Button text.
	 */
	public function set_button_text( $button_text ) {
		$this->set_prop( 'button_text', $button_text );
	}

	/**
	 * Get button text.
	 *
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return string
	 */
	public function get_button_text( $context = 'view' ) {
		return $this->get_prop( 'button_text', $context );
	}

	/**
	 * Get the add to cart button text description - used in aria tags.
	 *
	 * @since 1.0.2
	 * @return string
	 */
	public function add_to_cart_description() {

		/* translators: %s: Product title */
		$temp_var = $this->get_button_text() ? $this->get_button_text() : sprintf( __( 'Book &ldquo;%s&rdquo;', 'mwb-bookings-for-woocommerce' ), $this->get_name() );

		/**
		 * Filter for description.
		 * @since 1.0.0
		 */																														
		return apply_filters( 'woocommerce_product_add_to_cart_description', $temp_var, $this );
	}
}
