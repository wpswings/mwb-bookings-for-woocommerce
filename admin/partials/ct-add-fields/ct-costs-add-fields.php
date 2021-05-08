<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add fields for the custom taxonomy 'mwb_ct_costs'
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/admin/partials/ct-add-fields
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}
?>
<div class="form-field term-extra-cost-wrap">
	<p class="form-field">
		<label for="mwb_booking_ct_costs_custom_price"><?php esc_html_e( 'Cost Price', 'mwb-bookings-for-woocommerce' ); ?></label>
		<input type="number" id="mwb_booking_ct_costs_custom_price" name="mwb_booking_ct_costs_custom_price" step="1" min="1" required/>
		<span class="description"><?php esc_html_e( 'Cost price of the added cost', 'mwb-bookings-for-woocommerce' ); ?></span>
	</p>
	<p class="form-field">
		<input type="checkbox" id="mwb_booking_ct_costs_multiply_units" name="mwb_booking_ct_costs_multiply_units" value="yes">
		<span class="description"><?php esc_html_e( 'Multiply cost by duration', 'mwb-bookings-for-woocommerce' ); ?></span>
	</p>
	<p class="form-field">
		<input type="checkbox" id="mwb_booking_ct_costs_multiply_people" name="mwb_booking_ct_costs_multiply_people" value="yes">
		<span class="description"><?php esc_html_e( 'Multiply cost by the number of people', 'mwb-bookings-for-woocommerce' ); ?></span>
	</p>
</div>
