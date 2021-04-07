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

$booking_people_price = '';

$people_enable_check = ! empty( $product_meta['mwb_people_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_people_enable_checkbox'][0] ) ) : 'no';
$people_type_check   = ! empty( $product_meta['mwb_enable_people_types'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_people_types'][0] ) ) : 'no';
$min_people          = ! empty( $product_meta['mwb_min_people_per_booking'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_min_people_per_booking'][0] ) ) : 1;
$max_people          = ! empty( $product_meta['mwb_max_people_per_booking'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_max_people_per_booking'][0] ) ) : '';
$people_select       = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_people_select'][0] ) : array();
$unit_price          = ! empty( $product_meta['mwb_booking_unit_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_input'] ) ) : sanitize_text_field( wp_unslash( $product_meta['_price'] ) );
$base_price          = ! empty( $product_meta['mwb_booking_base_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_input'] ) ) : 0;

$product_data = array(
	'product_id' => $product->get_id(),
);

if ( empty( $people_select ) ) {
	$people_type_check = 'no';
}

?>
<div id="mwb-wc-bk-people-section" class="mwb-wc-bk-form-section" product-data="<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>">
	<?php
	if ( 'yes' === $people_enable_check ) {
		?>
		<div id="mwb-wc-bk-people-field">
			<?php

			if ( 'no' === $people_type_check ) {
				?>
				<div id="mwb-wc-bk-people-input-div">
					<label for="mwb-wc-bk-people-input"><b><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></b></label>
					<input type="hidden" id="mwb-wc-bk-people-input-hidden" class="people-input-hidden" name="people_total" data-min="<?php echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" data-max="<?php echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>" >
					<input type="number" id="mwb-wc-bk-people-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number people-input" name="people_total" value="1" step="1" min="<?php echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" max="<?php echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>" required>
					<span class="mwb-wc-bk-form-error people-error" style="display:none; color:red;"></span>
				</div>
				<?php

			} elseif ( 'yes' === $people_type_check ) {

				?>
				<label for="mwb-wc-bk-people-input-div"><b><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></b></label>
				<div id="mwb-wc-bk-people-input-div">
					<span id="mwb-wc-bk-people-input-span" ><?php esc_html_e( 'Select Peoples', 'mwb-wc-bk' ); ?></span>	
					<input type="hidden" id="mwb-wc-bk-people-input-hidden" class="mwb-wc-bk-form-input-hidden" name="people_total" data-min="<?php echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" data-max="<?php echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>" required >
					<div class="mwb-wc-bk-people-type-popup">
						<ul class="mwb-wc-bk-people-type-list">
						<?php
						if ( is_array( $people_select ) && ! empty( $people_select ) ) {
							foreach ( $people_select as $k => $v ) {
								$people_term      = get_term( $v );
								$people_name      = $people_term->name;
								$people_term_meta = get_term_meta( $v );
								$max_quant        = ! empty( $people_term_meta['mwb_booking_ct_people_max_quantity'][0] ) ? $people_term_meta['mwb_booking_ct_people_max_quantity'][0] : '';
								$min_quant        = ! empty( $people_term_meta['mwb_booking_ct_people_min_quantity'][0] ) ? $people_term_meta['mwb_booking_ct_people_min_quantity'][0] : '';
								?>
								<li>
									<label for="mwb-wc-bk-people-input-<?php echo esc_html( $v ); ?>"><?php echo esc_html( $people_name ); ?></label>
									<input type="hidden" class="people-input-hidden" data-max="<?php echo esc_html( $max_quant ); ?>" data-min="<?php echo esc_html( $min_quant ); ?>">
									<input type="number" id="mwb-wc-bk-people-input-<?php echo esc_html( $v ); ?>" name="people-<?php echo esc_html( $v ); ?>" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number people-input" data-id="<?php echo esc_html( $v ); ?>" value="0" step="1" min="0" max="" >
								</li>
								<?php
							}
						}
						?>
						</ul>
						<span class="mwb-wc-bk-form-error people-error" style="display:none; color:red;"></span>
					</div>
				</div>
					<?php
			}
	} else {
		?>
		<div id="mwb-wc-bk-people-field">
			<label for="mwb-wc-bk-people-input"><b><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></b></label>
			<input type="hidden" id="mwb-wc-bk-people-input-hidden" class="people-input-hidden" name="people_total" value="1" >
			<span><?php esc_html_e( '1 people', 'mwb-wc-bk' ); ?></span>
		</div>
		<?php
	}
	?>
		</div>
	</div>
