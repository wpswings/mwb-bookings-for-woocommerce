<?php

/**
 * Check Calendar Range Picker and date settings for the booking
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/templates/single-product/add-to-cart/form
 */

defined( 'ABSPATH' ) || exit;

global $product;
$product_meta = get_post_meta( $product->get_id() );
$product_data = array(
	'product_id' => $product->get_id(),
);
?>
<div id="mwb_create_booking_date">
	<?php
	if ( ! empty( $product_meta['mwb_booking_unit_duration'][0] ) && 'day' === $product_meta['mwb_booking_unit_duration'][0] && ! empty( $product_meta['mwb_enable_range_picker'] ) && 'yes' === $product_meta['mwb_enable_range_picker'][0] ) {
		?>
		<div id="mwb_create_booking_start_date_field">
			<label for=""><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label>
			<input type="date" id="mwb_create_booking_start_date">
			<label for=""><?php esc_html_e( 'End Date', 'mwb-wc-bk' ); ?></label>
			<input type="date" id="mwb_create_booking_end_date">
		</div>
		<div class="mwb-wc-bk-form-section" product-data = "<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>">
			<label for="duration"><?php esc_html_e( 'Duration', 'mwb-wc-bk' ); ?></label>
			<input id="mwb-wc-bk-duration-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" type="number" name="duration" value="1" step="1" min="1">
		</div>
		<?php
	}
	?>
</div>
