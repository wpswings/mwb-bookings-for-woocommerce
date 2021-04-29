<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to edit fields for the custom taxonomy 'mwb_ct_services'
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
$service_cost          = get_term_meta( $term->term_id, 'mwb_ct_booking_service_cost', true );
$multiply_unit_check   = get_term_meta( $term->term_id, 'mwb_booking_ct_services_multiply_units', true );
$multiply_people_check = get_term_meta( $term->term_id, 'mwb_booking_ct_services_multiply_people', true );
$has_quantity          = get_term_meta( $term->term_id, 'mwb_booking_ct_services_has_quantity', true );
$if_hidden             = get_term_meta( $term->term_id, 'mwb_booking_ct_services_hidden', true );
$if_optional           = get_term_meta( $term->term_id, 'mwb_booking_ct_services_optional', true );
$min_quantity          = get_term_meta( $term->term_id, 'mwb_booking_ct_services_min_quantity', true );
$max_quantity          = get_term_meta( $term->term_id, 'mwb_booking_ct_services_max_quantity', true );

?>
<tr class="form-field term-service-cost-wrap">
	<th><label for="mwb_ct_booking_service_cost"><?php esc_html_e( 'Service Cost', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="number" id="mwb_ct_booking_service_cost" name="mwb_ct_booking_service_cost" value="<?php echo esc_html( ! empty( $service_cost ) ? $service_cost : '' ); ?>" min="0"/>
		<p class="description"><?php esc_html_e( 'Enter the cost for the services to be included', 'mwb-wc-bk' ); ?></p>
	</td>	
</tr>
<tr class="form-field term-custom-checks-wrap">
	<th><label for="mwb_booking_ct_services_multiply_units"><?php esc_html_e( 'Multiply cost by duration', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_services_multiply_units" name="mwb_booking_ct_services_multiply_units" value="yes" <?php checked( 'yes', ! empty( $multiply_unit_check ) ? $multiply_unit_check : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Select to multiply the service cost by the number of booking units.', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-custom-checks-wrap">
	<th><label for="mwb_booking_ct_services_multiply_people"><?php esc_html_e( 'Multiply cost by number of peoples per booking', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_services_multiply_people" name="mwb_booking_ct_services_multiply_people" value="yes" <?php checked( 'yes', ! empty( $multiply_people_check ) ? $multiply_people_check : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Select to multiply the service cost by the number of people selected.', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-custom-checks-wrap">
	<th><label for="mwb_booking_ct_services_has_quantity"><?php esc_html_e( 'If has quantity', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_services_has_quantity" name="mwb_booking_ct_services_has_quantity" value="yes" <?php checked( 'yes', ! empty( $has_quantity ) ? $has_quantity : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Check if Quantity has to be included in the services', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-custom-checks-wrap">
	<th><label for="mwb_booking_ct_services_hidden"><?php esc_html_e( 'If Hidden', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_services_hidden" name="mwb_booking_ct_services_hidden" value="yes" <?php checked( 'yes', ! empty( $if_hidden ) ? $if_hidden : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Check if the service is hidden in booking to the user', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-custom-checks-wrap">
	<th><label for="mwb_booking_ct_services_optional"><?php esc_html_e( 'If Optional', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="checkbox" id="mwb_booking_ct_services_optional" name="mwb_booking_ct_services_optional" value="yes" <?php checked( 'yes', ! empty( $if_optional ) ? $if_optional : 'no' ); ?>>
		<p class="description"><?php esc_html_e( 'Check if the service is optional for the user', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-has-quantity-check-wrap">
	<th><label for="mwb_booking_ct_services_min_quantity"><?php esc_html_e( 'Min Quantity', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="number" id="mwb_booking_ct_services_min_quantity" name="mwb_booking_ct_services_min_quantity" value="<?php echo esc_html( ! empty( $min_quantity ) ? $min_quantity : '' ); ?>" min="0">
		<p class="description"><?php esc_html_e( 'Enter the minimum quantity of the service to be included', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<tr class="form-field term-has-quantity-check-wrap">
	<th><label for="mwb_booking_ct_services_max_quantity"><?php esc_html_e( 'Max Quantity', 'mwb-wc-bk' ); ?></label></th>
	<td>
		<input type="number" id="mwb_booking_ct_services_max_quantity" name="mwb_booking_ct_services_max_quantity" value="<?php echo esc_html( ! empty( $max_quantity ) ? $max_quantity : '' ); ?>" min="1">
		<p class="description"><?php esc_html_e( 'Enter the maximum quantity of the service to be included', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
<?php
$booking_people_taxonomy_terms = get_terms(
	array(
		'taxonomy'   => 'mwb_ct_people_type',
		'hide_empty' => false,
	)
);
foreach ( $booking_people_taxonomy_terms as $t ) {
		$term_name = $t->slug;
	?>
<tr class="form-field term-has-people-check-wrap">
	<th><label for="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>"><?php echo esc_html( 'Service Cost for ' . $t->name ); ?></label></th>
	<td>
		<input type="number" class="mwb_ct_service_multiply_people" id="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>" name="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>" value="<?php echo esc_html( get_term_meta( $term->term_id, 'mwb_ct_booking_service_cost_' . $term_name, true ) ); ?>" min="0">
		<p class="description"><?php esc_html_e( 'Enter the service cost for respective people type.', 'mwb-wc-bk' ); ?></p>
	</td>
</tr>
	<?php
}
