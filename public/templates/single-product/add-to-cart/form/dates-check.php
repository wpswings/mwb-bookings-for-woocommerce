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

$unit_select  = ! empty( $product_meta['mwb_booking_unit_select'][0] ) ? $product_meta['mwb_booking_unit_select'][0] : '';
$range_picker = ! empty( $product_meta['mwb_enable_range_picker'][0] ) ? $product_meta['mwb_enable_range_picker'][0] : '';

?>
<div id="mwb-wc-bk-date-section" class="mwb-wc-bk-form-section">
	<?php
	if ( 'fixed' === $unit_select ) {
		?>
		<div id="mwb-wc-bk-start-date-field">
			<label for="mwb-wc-bk-start-date-input"><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label>
			<input type="text" id="mwb-wc-bk-start-date-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-date" name="start_date" placeholder="dd-mm-yyyy">
		</div>
		<?php
	} elseif ( 'customer' === $unit_select ) {
		if ( 'yes' === $range_picker ) {
			?>
		<div id="mwb-wc-bk-start-date-field">
			<label for="mwb-wc-bk-start-date-input"><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label><br>
			<input type="text" id="mwb-wc-bk-start-date-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-date" name="start_date" placeholder="dd-mm-yyyy">
		</div>
		<div id="mwb-wc-bk-end-date-field">
			<label for="mwb-wc-bk-end-date-input"><?php esc_html_e( 'End Date', 'mwb-wc-bk' ); ?></label><br>
			<input type="date" id="mwb-wc-bk-end-date-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-date" name="end_date">
		</div>
			<?php
		} else {
			?>
		<div id="mwb-wc-bk-start-date-field">
			<label for="mwb-wc-bk-start-date-input"><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label><br>
			<input type="text" id="mwb-wc-bk-start-date-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-date" name="start_date" placeholder="dd-mm-yyyy">
		</div>
			<?php
		}
	}
	?>
</div>
