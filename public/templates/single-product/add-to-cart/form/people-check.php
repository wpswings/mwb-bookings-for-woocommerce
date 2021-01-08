<?php

/**
 * Check People type settings for the booking
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

if ( ! empty( $product_meta['mwb_booking_people_select'][0] ) ) {
	$people_ids = $product_meta['mwb_booking_people_select'][0];
	foreach ( $people_ids as $k => $v ) {
		echo '<pre>'; print_r( $k . "=>" . $v ); echo '</pre>';
		$people_term = get_term_by( 'id', $v );
		echo '<pre>'; print_r( $people_term ); echo '</pre>';
	}
}
?>
<div id="mwb-wc-bk-date-field" class="mwb-wc-bk-form-section">
	<?php
	if ( ! empty( $product_meta['mwb_people_enable_checkbox'][0] ) && 'yes' === $product_meta['mwb_people_enable_checkbox'][0] ) {
		?>
		<div id="mwb-wc-bk-people-field">
			<?php
			if ( ! empty( $product_meta['mwb_people_as_seperate_booking'][0] ) && 'no' === $product_meta['mwb_people_as_seperate_booking'][0] ) {
				if ( ! empty( $product_meta['mwb_enable_people_types'] ) && 'no' === $product_meta['mwb_enable_people_types'] ) {
					if ( ! empty( $product_meta['mwb_min_people_per_booking'] ) && ! empty( $product_meta['mwb_max_people_per_booking'] ) ) {
						?>
						<label for="mwb-wc-bk-people-input"><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></label>
						<input type="number" id="mwb-wc-bk-people-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" name="people" value="1" step="1" min="<?php $product_meta['mwb_min_people_per_booking']; ?>" max="<?php $product_meta['mwb_max_people_per_booking']; ?>">
						<?php
					} else {
						?>
						<label for="mwb-wc-bk-people-input"><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></label>
						<input type="number" id="mwb-wc-bk-people-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" name="people" value="1" step="1" min="0">
						<?php
					}
				} else {
					?>
						<label for="mwb-wc-bk-people-input"><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></label>
						<select name="people" id="mwb-wc-bk-people-input">
							<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
							<option value="people"><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></option>
						</select>
						<div>
							<form id="mwb-wc-bk-people-form" method="POST">
								<?php
								if ( ! empty( $product_meta['mwb_booking_people_select'][0] ) ) {
									$people_ids = $product_meta['mwb_booking_people_select'][0];
									foreach ( $people_ids as $k => $v ) {
										$people_term = get_term_by( 'id', $v );
										echo '<pre>'; print_r( $people_term ); echo '</pre>';

									}
								}
								?>
								<input type="number" id="mwb-wc-bk-people-form-">
							</form>
						</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
	?>
</div>
