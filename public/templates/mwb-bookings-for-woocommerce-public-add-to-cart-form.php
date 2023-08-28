<?php
/**
 * Provide a public area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$product_id = get_the_id();
if ( empty( $product->get_type() ) || 'mwb_booking' !== $product->get_type() ) {
	exit;
}
?>
<div class="mwb-mbfw-cart-page-data">
	<?php
	wp_nonce_field( 'mwb_booking_frontend', '_mwb_nonce' );

	/**
	 * Before Booking add to cart form..
	 *
	 * @since 1.0.0
	 */
	do_action( 'mwb_booking_before_add_to_cart_form', $product_id, $product );
 
	/**
	 * Add Time selector on frontend for booking.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mwb_mbfw_add_calender_or_time_selector_for_booking', $product_id, $product );

	/**
	 * Show input field for number of people selection.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mwb_mbfw_number_of_people_while_booking_on_form', $product_id, $product );
 
	/**
	 * Show Booking services details on form.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mwb_mbfw_booking_services_details_on_form', $product_id, $product );

	/**
	 * After Boooking add to cart form.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mwb_booking_after_add_to_cart_form', $product_id, $product );
	if ( 'fixed_unit' === get_post_meta( $product_id, 'mwb_mbfw_booking_criteria', true ) ) {
		$booking_quantity = get_post_meta( $product_id, 'mwb_mbfw_booking_count', true );
		if ( ! empty( $booking_quantity ) ) {
			?>
			<div class="mwb-bfwp-booking-quantity__public-show">
				<?php esc_html_e( 'Quantity : ', 'mwb-bookings-for-woocommerce' ); ?>
				<span>
					<?php echo esc_html( $booking_quantity ); ?>
				</span>
			</div>
			<?php
		}
	}
	?>
	<input type="hidden" name="mwb_mbfw_booking_product_id" class="mwb_mbfw_booking_product_id" value="<?php echo esc_html( $product_id ); ?>">
	<?php
	if ( 'yes' === get_option( 'mwb_mbfw_is_show_totals' ) ) {
		?>
		<div class="mwb-mbfw-total-area"></div>
		<?php
	}
	do_action('mwb_booking_before_add_to_cart_button', $product_id );
	?>
</div>
