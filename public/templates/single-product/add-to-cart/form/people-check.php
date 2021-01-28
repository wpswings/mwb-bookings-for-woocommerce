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

$people_enable_check = ! empty( $product_meta['mwb_people_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_people_enable_checkbox'][0] ) ) : '';
$seperate_booking    = ! empty( $product_meta['mwb_people_as_seperate_booking'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_people_as_seperate_booking'][0] ) ) : '';
$people_type_check   = ! empty( $product_meta['mwb_enable_people_types'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_people_types'][0] ) ) : '';
$min_people          = ! empty( $product_meta['mwb_min_people_per_booking'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_min_people_per_booking'][0] ) ) : '';
$max_people          = ! empty( $product_meta['mwb_max_people_per_booking'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_max_people_per_booking'][0] ) ) : '';
$people_select       = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_people_select'][0] ) : '';
$unit_price          = ! empty( $product_meta['mwb_booking_unit_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_input'] ) ) : sanitize_text_field( wp_unslash( $product_meta['_price'] ) );
$base_price          = ! empty( $product_meta['mwb_booking_base_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_input'] ) ) : '';

$product_data = array(
	'product_id'                 => $product->get_id(),
	'people-as-seperate-booking' => $seperate_booking,
);
?>
<div id="mwb-wc-bk-people-section" class="mwb-wc-bk-form-section" product-data="<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>">
	<?php
	if ( 'yes' === $people_enable_check ) {
		?>
		<div id="mwb-wc-bk-people-field">
			<?php
			if ( 'no' === $people_type_check ) {
				?>
				<label for="mwb-wc-bk-people-input"><b><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></b></label>
				<input type="hidden" id="mwb-wc-bk-people-input-hidden" name="people" data-min="<?php echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" data-max="<?php echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>" >
				<input type="number" id="mwb-wc-bk-people-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" name="people" value="1" step="1" min="<?php echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" max="<?php echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>">
				<?php
				// $booking_people_price = $unit_price + ( ! empty( $base_price ) ? $base_price : 0 );
			} elseif ( 'yes' === $people_type_check ) {
				// if ( 'no' === $seperate_booking ) {
				?>
				<label for="mwb-wc-bk-people-input-div"><b><?php esc_html_e( 'People', 'mwb-wc-bk' ); ?></b></label>
				<div id="mwb-wc-bk-people-input-div">
					<!-- <option value="none"><?php // esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
					<span id="mwb-wc-bk-people-input-span" ><?php esc_html_e( 'Select Peoples', 'mwb-wc-bk' ); ?></span>
					<span class="mwb-wc-bk-form-dropdown"></span>
					<input type="hidden" id="mwb-wc-bk-people-input-hidden" class="mwb-wc-bk-form-input-hidden" name="people" data-min="<?php echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" data-max="<?php echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>" >
					<div class="mwb-wc-bk-people-type-popup">
						<ul class="mwb-wc-bk-people-type-list">
						<?php
						if ( is_array( $people_select ) ) {
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
									<input type="number" id="mwb-wc-bk-people-input-<?php echo esc_html( $v ); ?>" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number people-input" data-id="<?php echo esc_html( $v ); ?>" value="0" step="1" min="0" max="" >
								</li>
								<?php
							}
						}
						?>
						</ul>
					</div>
				</div>
					<?php
				// }
				// elseif ( 'yes' === $seperate_booking ) {
					?>
				<!-- <label for="mwb-wc-bk-people-input-hidden"><?php // esc_html_e( 'People', 'mwb-wc-bk' ); ?></label>
				<input type="hidden" id="mwb-wc-bk-people-input-hidden" data-min="<?php // echo ! empty( $min_people ) ? esc_html( $min_people ) : 1; ?>" data-max="<?php //echo ! empty( $max_people ) ? esc_html( $max_people ) : ''; ?>">
				<ul class="mwb-wc-bk-people-type-list"> -->
					<?php
					// if ( is_array( $people_select ) ) {
					// 	foreach ( $people_select as $k => $v ) {
					// 		$people_term = get_term( $v );
					// 		$people_name = $people_term->name;
							?>
							<!-- <li>
								<label for="mwb-wc-bk-people-input-<?php //echo esc_html( $people_name ); ?>"><?php //echo esc_html( $people_name ); ?></label>
								<input type="number" id="mwb-wc-bk-people-input-<?php //echo esc_html( $people_name ); ?>" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" value="1" step="1" min="" max="" >
							</li> -->
							<?php
					// 	}
					// }
					?>
				<!-- </ul> -->
					<?php
				// }
			}
	}
	?>
		</div>
	</div>
