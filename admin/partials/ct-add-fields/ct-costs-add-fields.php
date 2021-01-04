<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add fields for the custom taxonomy 'mwb_ct_costs'
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/ct-add-fields
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}
?>
<div class="form-field term-extra-cost-wrap">
	<p>
		<label for="mwb_booking_ct_costs_custom_price"><?php esc_html_e( 'Cost Price', 'mwb-wc-bk' ); ?></label>
		<input type="number" id="mwb_booking_ct_costs_custom_price" name="mwb_booking_ct_costs_custom_price" step="1" min="1"/>
		<p class="description"><?php esc_html_e( 'Cost price of the added cost', 'mwb-wc-bk' ); ?></p>
	</p>
	<p>
		<input type="checkbox" id="mwb_booking_ct_costs_multiply_units" name="mwb_booking_ct_costs_multiply_units" value="yes">
		<p class="description"><?php esc_html_e( 'Multiply cost by booking unit duration', 'mwb-wc-bk' ); ?></p>
	</p>
	<p>
		<input type="checkbox" id="mwb_booking_ct_costs_multiply_people" name="mwb_booking_ct_costs_multiply_people" value="yes">
		<p class="description"><?php esc_html_e( 'Multiply cost by the number of people', 'mwb-wc-bk' ); ?></p>
	</p>
</div>
