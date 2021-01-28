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

// echo '<pre>'; print_r( $kl ); echo '</pre>';die;
global $product;
$product_meta = get_post_meta( $product->get_id() );
$product_data = array(
	'product_id' => $product->get_id(),
);

$unit_select   = ! empty( $product_meta['mwb_booking_unit_select'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_select'][0] ) ) : '';
$range_picker  = ! empty( $product_meta['mwb_enable_range_picker'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_range_picker'][0] ) ) : '';
$unit_duration = ! empty( $product_meta['mwb_booking_unit_duration'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_duration'][0] ) ) : '';
$unit_input    = ! empty( $product_meta['mwb_booking_unit_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_input'][0] ) ) : '';
$min_duration  = ! empty( $product_meta['mwb_booking_min_duration'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_min_duration'][0] ) ) : '';
$max_duration  = ! empty( $product_meta['mwb_booking_max_duration'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_max_duration'][0] ) ) : '';

?>
<div id="mwb-wc-bk-duration-section" class="mwb-wc-bk-form-section" product-data = "<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>">
	<?php
	if ( 'fixed' === $unit_select ) {
		?>
		<div id="mwb_create_booking_date">
			<div id="mwb_create_booking_duration_field">
				<label for="mwb-wc-bk-duration-input"><b><?php esc_html_e( 'Duration', 'mwb-wc-bk' ); ?></b></label>
				<br>
				<input type="hidden" id="mwb-wc-bk-duration-input" name="duration" value="<?php echo esc_html( $product_meta['mwb_booking_unit_input'][0] ); ?>">
				<p><b><?php echo esc_html( sprintf( '%d-%s', $unit_input, $unit_duration . ( ( $unit_input > 1 ) ? 's' : '' ) ) ); ?></b></p>
			</div>
		</div>
		<?php
	} elseif ( 'customer' === $unit_select ) {
		if ( empty( $range_picker ) || 'no' === $range_picker ) {
			if ( ! empty( $unit_duration ) && ! empty( $unit_input ) ) {
				?>
			<div class="mwb-wc-bk-form-field" id="mwb-wc-bk-duration-div" >
				<label for="mwb-wc-bk-duration-input"><b><?php esc_html_e( 'Duration', 'mwb-wc-bk' ); ?></b></label>
				<br>
				<?php if ( ! empty( $min_duration ) && ( $min_duration !== $max_duration ) ) { ?>
					<input id="mwb-wc-bk-duration-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" type="number" name="duration" value="1" step="1" min="<?php echo esc_html( $min_duration ); ?>" max="<?php echo esc_html( $max_duration ); ?>">
					<?php echo esc_html( sprintf( 'X %d %s', $unit_input, $unit_duration . ( ( $unit_input > 1 ) ? 's' : '' ) ) ); ?>
						<?php
				} elseif ( ( $min_duration === $max_duration ) && (int) $min_duration > 1 ) {
					?>
					<input type="hidden" id="mwb-wc-bk-duration-input" name="duration" value="<?php echo esc_html( $product_meta['mwb_booking_unit_input'][0] ); ?>">
					<p><b><?php echo esc_html( sprintf( '%d-%s', ( $unit_input * $min_duration ), $unit_duration . ( ( $unit_input * $min_duration > 1 ) ? 's' : '' ) ) ); ?></b></p>
						<?php
				} else {
					?>
					<input id="mwb-wc-bk-duration-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" type="number" name="duration" value="1" step="1" min="1" max="<?php echo esc_html( $max_duration ); ?>">
					<?php echo esc_html( sprintf( 'X %d %s', $unit_input, $unit_duration . ( ( $unit_input > 1 ) ? 's' : '' ) ) ); ?>
					<?php } ?>
			</div>
				<?php
			}
		}
	}
	?>
</div>
