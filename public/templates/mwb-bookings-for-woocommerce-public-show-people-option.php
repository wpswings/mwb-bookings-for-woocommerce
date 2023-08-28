<?php
/**
 * Provide a public area view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
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
$min_people = get_post_meta( $product_id, 'mwb_mbfw_minimum_people_per_booking', true );
?>
<div class="mbfw-additionl-detail-listing-section__wrapper">
	<div class="mbfw-additionl-detail-listing-section">
		<?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?>
	</div>
	<div class="mbfw-additionl-detail-listing-section">
		<input type="number" name="mwb_mbfw_people_number" class="mwb_mbfw_people_number" id="mwb_mbfw_people_number" value="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?>" min="<?php echo esc_attr( ! empty( $min_people ) ? $min_people : 0 ); ?>" max="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_maximum_people_per_booking', true ) ); ?>" required>
	</div>
</div>
