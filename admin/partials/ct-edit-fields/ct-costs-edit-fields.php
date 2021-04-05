<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to edit fields for the custom taxonomy 'mwb_ct_costs'
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/ct-edit-fields
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}
$multiply_unit   = get_term_meta( $term->term_id, 'mwb_booking_ct_costs_multiply_units', true );
$multiply_people = get_term_meta( $term->term_id, 'mwb_booking_ct_costs_multiply_people', true );
$cost_price      = get_term_meta( $term->term_id, 'mwb_booking_ct_costs_custom_price', true );

?>
<tr class="form-field term-extra-cost-wrap">
	<th><label for="mwb_booking_ct_costs_custom_price"><?php esc_html_e( 'Cost price', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="number" id="mwb_booking_ct_costs_custom_price" name="mwb_booking_ct_costs_custom_price" value="<?php echo esc_html( ! empty( $cost_price ) ? $cost_price : 0 ); ?>" step="1" min="1">
		<p class="description"><?php esc_html_e( 'Cost price of the added cost', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-extra-cost-wrap">
	<th><label for="mwb_booking_ct_costs_multiply_units"><?php esc_html_e( 'Multiply cost by duration', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_costs_multiply_units" name="mwb_booking_ct_costs_multiply_units" value="yes" <?php checked( 'yes', ! empty( $multiply_unit ) ? $multiply_unit : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Select to multiply the extra added cost by the number of booking units.', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-extra-cost-wrap">
	<th><label for="mwb_booking_ct_costs_multiply_people"><?php esc_html_e( 'Multiply cost by the number of people', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_costs_multiply_people" name="mwb_booking_ct_costs_multiply_people" value="yes" <?php checked( 'yes', ! empty( $multiply_people ) ? $multiply_people : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Select to multiply the extra added cost by the number of people.', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
