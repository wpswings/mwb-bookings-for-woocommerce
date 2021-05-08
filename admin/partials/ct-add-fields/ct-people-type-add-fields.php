<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add fields for the custom taxonomy 'mwb_ct_people_type'
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
<div class="form-field term-people-cost-wrap">
	<label for="mwb_ct_booking_people_unit_cost"><?php esc_html_e( 'Unit Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
	<input type="number" id="mwb_ct_booking_people_unit_cost" class="postform" name="mwb_ct_booking_people_unit_cost" min="0"/>
	<p class="description"><?php esc_html_e( 'Enter the unit cost for the people type.', 'mwb-bookings-for-woocommerce' ); ?></p>

	<label for="mwb_ct_booking_people_base_cost"><?php esc_html_e( 'Base Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
	<input type="number" id="mwb_ct_booking_people_base_cost" class="postform" name="mwb_ct_booking_people_base_cost" min="0"/>
	<p class="description"><?php esc_html_e( 'Enter the base cost for the people type.', 'mwb-bookings-for-woocommerce' ); ?></p>

</div>
<div class="form-field term-people-quantity-wrap">

	<label for="mwb_booking_ct_people_max_quantity"><?php esc_html_e( 'Maximum Quantity', 'mwb-bookings-for-woocommerce' ); ?></label>
	<input type="number" id="mwb_booking_ct_people_max_quantity" class="postform" name="mwb_booking_ct_people_max_quantity" min="1"/>
	<p class="description"><?php esc_html_e( 'Maximum Quantity of peoples allowed for respective people type', 'mwb-bookings-for-woocommerce' ); ?></p>

</div>
