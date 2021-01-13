<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add fields for the custom taxonomy 'mwb_ct_services'
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
<div class="form-field term-cost-wrap">
	<label for="mwb_ct_booking_service_cost"><?php esc_html_e( 'Service Cost', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_ct_booking_service_cost" class="postform" name="mwb_ct_booking_service_cost" />
	<p class="description"><?php esc_html_e( 'Enter the cost for the services to be included', 'mwb-wc-bk' ); ?></p>
</div>
<div class="form-field" id="mwb_booking_ct_services_custom_fields">
	<p class="form-field">
		<label for=""></label>
		<input type="checkbox" id="mwb_booking_ct_services_multiply_units" class="checkbox" name="mwb_booking_ct_services_multiply_units" value="yes">
		<p class="description"><?php esc_html_e( 'Multiply cost by booking units', 'mwb-wc-bk' ); ?></p>
	</p>
	<p class="form-field">
		<label for=""></label>
		<input type="checkbox" id="mwb_booking_ct_services_multiply_people" class="checkbox" name="mwb_booking_ct_services_multiply_people" value="yes">
		<p class="description"><?php esc_html_e( 'Multiply cost by number of peoples per booking', 'mwb-wc-bk' ); ?></p>
	</p>
	<p class="form-field">
		<label for=""></label>
		<input type="checkbox" id="mwb_booking_ct_services_has_quantity" class="checkbox" name="mwb_booking_ct_services_has_quantity" value="yes">
		<p class="description"><?php esc_html_e( 'If has quantity', 'mwb-wc-bk' ); ?></p>
	</p>
	<p class="form-field">
		<label for=""></label>
		<input type="checkbox" id="mwb_booking_ct_services_hidden" class="checkbox" name="mwb_booking_ct_services_hidden" value="yes">
		<p class="description"><?php esc_html_e( 'If Hidden', 'mwb-wc-bk' ); ?></p>
	</p>
	<p class="form-field">
		<label for=""></label>
		<input type="checkbox" id="mwb_booking_ct_services_optional" class="checkbox" name="mwb_booking_ct_services_optional" value="yes" >
		<p class="description"><?php esc_html_e( 'If Optional', 'mwb-wc-bk' ); ?></p>
	</p>
</div>
<div class="form-field term-has-quantity-checked-wrap">

	<label for="mwb_booking_ct_services_min_quantity"><?php esc_html_e( 'Minimum Quantity', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_booking_ct_services_min_quantity" class="postform" name="mwb_booking_ct_services_min_quantity" />
	<p class="description"><?php esc_html_e( 'Minimum Quantity if the service has quantity', 'mwb-wc-bk' ); ?></p>

	<label for="mwb_booking_ct_services_max_quantity"><?php esc_html_e( 'Maximum Quantity', 'mwb-wc-bk' ); ?></label>
	<input type="number" id="mwb_booking_ct_services_max_quantity" class="postform" name="mwb_booking_ct_services_max_quantity" />
	<p class="description"><?php esc_html_e( 'Maximum Quantity if the service has quantity', 'mwb-wc-bk' ); ?></p>

</div>
<div class="form-field term-has-people-checked-wrap">

	<?php
		$booking_people_taxonomy_terms = get_terms(
			array(
				'taxonomy'   => 'mwb_ct_people_type',
				'hide_empty' => false,
			)
		);
		foreach ( $booking_people_taxonomy_terms as $term ) {
			$term_name = $term->slug;
			?>
		<label for="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>"><?php echo esc_html( 'Service Cost for ' . $term->name ); ?></label>
		<input type="number" id="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>"  class="postform mwb_ct_service_multiply_people" name="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>" />
		<p class="description"><?php esc_html_e( 'Enter the service cost for respective people type.', 'mwb-wc-bk' ); ?></p>
	<?php } ?>

</div>
