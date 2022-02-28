<?php
/**
 * Provide a public area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$product_id = get_the_id();
if ( empty( $product->get_type() ) || 'wps_booking' !== $product->get_type() ) {
	exit;
}
?>
<div class="wps-mbfw-cart-page-data">
	<?php
	wp_nonce_field( 'wps_booking_frontend', '_wps_nonce' );
	//desc - Before Booking add to cart form.
	do_action( 'wps_booking_before_add_to_cart_form', $product_id, $product );
	//desc - Add Time selector on frontend for booking.
	do_action( 'wps_bfw_add_calender_or_time_selector_for_booking', $product_id, $product );
	//desc - Show input field for number of people selection.
	do_action( 'wps_bfw_number_of_people_while_booking_on_form', $product_id, $product );
	//desc - Show Booking services details on form.
	do_action( 'wps_bfw_booking_services_details_on_form', $product_id, $product );
	//desc - After Boooking add to cart form.
	do_action( 'wps_booking_after_add_to_cart_form', $product_id, $product );
	if ( 'fixed_unit' === get_post_meta( $product_id, 'wps_bfw_booking_criteria', true ) ) {
		$booking_quantity = get_post_meta( $product_id, 'wps_bfw_booking_count', true );
		if ( ! empty( $booking_quantity ) ) {
			?>
			<div class="wps-bfwp-booking-quantity__public-show">
				<?php esc_html_e( 'Quantity : ', 'bookings-for-woocommerce' ); ?>
				<span>
					<?php echo esc_html( $booking_quantity ); ?>
				</span>
			</div>
			<?php
		}
	}
	?>
	<input type="hidden" name="wps_bfw_booking_product_id" class="wps_bfw_booking_product_id" value="<?php echo esc_html( $product_id ); ?>">
	<?php
	if ( 'yes' === get_option( 'wps_bfw_is_show_totals' ) ) {
		?>
		<div class="wps-mbfw-total-area"></div>
		<?php
	}
	?>
</div>
