<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add fields for the custom taxonomy 'mwb_ct_people_type'
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
<div class="form-field term-people-cost-wrap">
	<label for="mwb_ct_booking_people_unit_cost"><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_ct_booking_people_unit_cost" class="postform" name="mwb_ct_booking_people_unit_cost" />
	<p class="description"><?php esc_html_e( 'Enter the unit cost for the people type.', 'mwb-wc-bk' ); ?></p>

	<label for="mwb_ct_booking_people_base_cost"><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_ct_booking_people_base_cost" class="postform" name="mwb_ct_booking_people_base_cost" />
	<p class="description"><?php esc_html_e( 'Enter the base cost for the people type.', 'mwb-wc-bk' ); ?></p>

</div>
<div class="form-field term-people-quantity-wrap">

	<!-- <label for="mwb_booking_ct_people_min_quantity"><?php // esc_html_e( 'Minimum Quantity', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_booking_ct_people_min_quantity" class="postform" name="mwb_booking_ct_people_min_quantity" />
	<p class="description"><?php // esc_html_e( 'Minimum Quantity of peoples allowed for respective people type', 'mwb-wc-bk' ); ?></p> -->

	<label for="mwb_booking_ct_people_max_quantity"><?php esc_html_e( 'Maximum Quantity', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_booking_ct_people_max_quantity" class="postform" name="mwb_booking_ct_people_max_quantity" />
	<p class="description"><?php esc_html_e( 'Maximum Quantity of peoples allowed for respective people type', 'mwb-wc-bk' ); ?></p>

</div>
