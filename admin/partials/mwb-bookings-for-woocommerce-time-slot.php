<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used from markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}


$wps_mbfw_field_data = wps_booking_get_meta_data( get_the_ID(), 'wps_mbfw_time_slots', true );
if ( empty( $wps_mbfw_field_data ) ) {
	$wps_mbfw_field_data = array();
}
?>
<div id="wps_mbfw_add_fields_wrapper">
	<div class="wps_mbfw_add_fields_title">
		<h2>
			<strong class="attribute_name"><?php esc_html_e( 'Set Daily time slots ( Cost will be calculated according to per hour. )', 'mwb-bookings-for-woocommerce' ); ?></strong></h2>
		</div>
		<div class="wps_mbfw_add_fields_data">
			<div class="wps_mbfw_fields_panel">
				<table class="field-options wp-list-table widefat wps_mbfw_field_table">
					<thead>
						<tr>
							<th></th>
							<th class="mbfw_field_from"><?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?></th>
							<th class="mbfw_field_to"><?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?></th>
							<th class="etmfw_field_actions"><?php esc_html_e( 'Actions', 'mwb-bookings-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="wps_mbfw_field_body">
						<?php if ( empty( $wps_mbfw_field_data ) ) : ?>
							<tr class="wps_mbfw_field_wrap" data-id="0">
								<td class="drag-icon">
									<i class="dashicons dashicons-move"></i>
								</td>
								<td class="form-field wps_mbfw_from_fields">
									<input type="text" class="wps_mbfw_field_from" style="" name="mbfw_fields[0][_from]" id="from_fields_0" value="9:00" placeholder="">
								</td>
								
								<td class="form-field wps_mbfw_to_fields">
									<input type="text" class="wps_mbfw_field_to" style="" name="mbfw_fields[0][_to]" id="to_fields_0" value="10:00">
								</td>
								<td class="wps_mbfw_remove_row">
								</td>
							</tr>
							<?php
						else :
							foreach ( $wps_mbfw_field_data as $row_id => $row_value ) :

								?>
								<tr class="wps_mbfw_field_wrap" data-id="<?php echo esc_attr( $row_id ); ?>">
									<td class="drag-icon">
										<i class="dashicons dashicons-move"></i>
									</td>
									<td class="form-field wps_mbfw_from_fields">
										<input type="text" class="wps_mbfw_field_from" style="" name="mbfw_fields[<?php echo esc_attr( $row_id ); ?>][_from]"  id="from_fields_<?php echo esc_attr( $row_id ); ?>" value="<?php echo esc_attr( $row_value['_from'] ); ?>" placeholder="">
									</td>
									<td class="form-field wps_mbfw_to_fields">
										<input type="text" class="wps_mbfw_field_to" style="" min="<?php echo esc_attr( $row_value['_from'] ); ?>" name="mbfw_fields[<?php echo esc_attr( $row_id ); ?>][_to]" id="to_fields_<?php echo esc_attr( $row_id ); ?>" value="<?php echo esc_attr( $row_value['_to'] ); ?>">
									</td>
									<td class="wps_mbfw_remove_row">
										<?php
										if ( 0 != $row_id ) {

											?>
													<input type="button" name="wps_mbfw_remove_fields_button" class="wps_mbfw_remove_row_btn" value="Remove">
												<?php

										}
										?>
									</td>
								</tr>
								<?php
							endforeach;
							?>
						<?php endif; ?>				
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<input type="button" name="wps_mbfw_add_fields_button" class="button wps_mbfw_add_fields_button" value="<?php esc_attr_e( 'Add More', 'mwb-bookings-for-woocommerce' ); ?>">
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
<?php
