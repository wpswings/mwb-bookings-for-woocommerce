<?php

/**
 * Check duration settings for the booking
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
<div id="mwb_create_booking_duration" class="mwb-wc-bk-form-section">
	<?php
	if ( ! empty( $product_meta['mwb_booking_unit_select'][0] ) && 'fixed' === $product_meta['mwb_booking_unit_select'][0] ) {
		?>
		<div id="mwb_create_booking_date">
			<div id="mwb_create_booking_duration_field">
				<label for="mwb-wc-bk-duration-input"><?php esc_html_e( 'Duration', 'mwb-wc-bk' ); ?></label>
				<br>
				<input type="hidden" id="mwb-wc-bk-duration-input" name="duration" value="<?php echo esc_html( $product_meta['mwb_booking_unit_input'][0] ); ?>">
				<p><b><?php echo esc_html( sprintf( '%d-%s', $product_meta['mwb_booking_unit_input'][0], $product_meta['mwb_booking_unit_duration'][0] ) ); ?></b></p>
			</div>
		</div>
		<?php
	} elseif ( ! empty( $product_meta['mwb_booking_unit_select'][0] ) && 'customer' === $product_meta['mwb_booking_unit_select'][0] ) {
		if ( empty( $product_meta['mwb_enable_range_picker'][0] ) || 'no' === $product_meta['mwb_enable_range_picker'][0] ) {
			if ( ! empty( $product_meta['mwb_booking_unit_duration'][0] ) && ! empty( $product_meta['mwb_booking_unit_input'][0] ) ) {
				?>
			<div class="mwb-wc-bk-form-section" id="mwb_create_booking_duration_field" product-data = "<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>">
				<label for="mwb-wc-bk-duration-input"><?php esc_html_e( 'Duration', 'mwb-wc-bk' ); ?></label>
				<br>
				<?php if ( ! empty( $product_meta['mwb_booking_min_duration'][0] ) && ! empty( $product_meta['mwb_booking_max_duration'][0] ) ) { ?>
					<input id="mwb-wc-bk-duration-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" type="number" name="duration" value="1" step="1" min="<?php echo esc_html( $product_meta['mwb_booking_min_duration'][0] ); ?>" max="<?php echo esc_html( $product_meta['mwb_booking_max_duration'][0] ); ?>">
				<?php } else { ?>
					<input id="mwb-wc-bk-duration-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" type="number" name="duration" value="1" step="1" min="1">
				<?php } ?>
				<?php echo esc_html( sprintf( 'X %d %s', $product_meta['mwb_booking_unit_input'][0], $product_meta['mwb_booking_unit_duration'][0] ) ); ?>
			</div>
				<?php
			}
		}
	}
	?>
</div>
