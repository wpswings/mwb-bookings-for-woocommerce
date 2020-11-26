<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set the global rules for Costs
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}
$cost_rule_arr   = array();
$cost_rule_count = 0;

if ( isset( $_POST['mwb_booking_global_cost_rules_save'] ) ) {

	$cost_rule_count = isset( $_POST['mwb_cost_rule_count'] ) ? $_POST['mwb_cost_rule_count'] : $cost_rule_count;

	if ( $cost_rule_count > 0 ) {
		$cost_rule_arr['rule_switch']     = isset( $_POST['mwb_global_cost_rule_heading_switch'] ) ? $_POST['mwb_global_cost_rule_heading_switch'] : array();
		$cost_rule_arr['rule_name']       = isset( $_POST['mwb_global_cost_rule_name'] ) ? $_POST['mwb_global_cost_rule_name'] : array();
		$cost_rule_arr['rule_type']       = isset( $_POST['mwb_global_cost_rule_type'] ) ? $_POST['mwb_global_cost_rule_type'] : array();
		$cost_rule_arr['rule_range_from'] = isset( $_POST['mwb_global_cost_rule_range_from'] ) ? $_POST['mwb_global_cost_rule_range_from'] : array();
		$cost_rule_arr['rule_range_to']   = isset( $_POST['mwb_global_cost_rule_range_to'] ) ? $_POST['mwb_global_cost_rule_range_to'] : array();

		update_option( 'mwb_global_cost_rules', $cost_rule_arr );
	} else {
		echo esc_html__( 'Add a new Cost rule', 'mwb-wc-bk' );
	}
}
$cost_rules = get_option( 'mwb_global_cost_rules', array() );

echo '<pre>';
print_r( $cost_rules );
echo '</pre>';
?>

<!-- For Global options Setting -->
<form id="mwb_global_cost_form" action="" method="POST">
	<div class="mwb_booking_global_cost_rules">
		<div id="mwb_global_cost_rules">
		<?php
		if ( ! empty( $cost_rules ) && $cost_rule_count > 0 ) {
			$mwb_cost_rule_switch     = ! empty( $cost_rules['rule_switch'] ) ? $cost_rules['rule_switch'] : '';
			$mwb_cost_rule_name       = ! empty( $cost_rules['rule_name'] ) ? $cost_rules['rule_name'] : '';
			$mwb_cost_rule_type       = ! empty( $cost_rules['rule_type'] ) ? $cost_rules['rule_type'] : '';
			$mwb_cost_rule_range_from = ! empty( $cost_rules['rule_range_from'] ) ? $cost_rules['rule_range_from'] : '';
			$mwb_cost_rule_range_to   = ! empty( $cost_rules['rule_range_to'] ) ? $cost_rules['rule_range_to'] : '';

			for ( $count = 1; $count <= $cost_rule_count; $count++ ) {
				?>
			<div id="mwb_global_cost_rule_$rule_count">
				<table class="form-table mwb_global_cost_rule_fields" >
					<tbody>
						<div class="mwb_global_cost_rule_heading">
							<h2>
							<label><?php echo ! empty( $mwb_cost_rule_name[ $count ] ) ? esc_html( $mwb_cost_rule_name[ $count ] ) : esc_html__( 'Rule No- ', 'mwb-wc-bk' ) . esc_html( $count ); ?></label>
							<input type="hidden" name="mwb_cost_rule_count" value="<?php echo esc_html( $count ); ?>" >
							<input type="checkbox" class="mwb_global_cost_rule_heading_switch" name="mwb_global_cost_rule_heading_switch[<?php echo esc_html( $cost_rule_count ); ?>]" <?php checked( 'on', $mwb_cost_rule_switch[ $count ] ); ?>>
							</h2>
						</div>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<input type="text" class="mwb_global_cost_rule_name" name="mwb_global_cost_rule_name[<?php echo esc_html( $cost_rule_count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_name[ $count ] ); ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Rule Type', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<select name="mwb_global_cost_rule_conditions" id="mwb_booking_global_cost_conditions" class="" style="width: auto; margin-right: 7px;">
									<option value="month" <?php selected( '', 'day_range' ); ?>><?php esc_html_e( 'Day Range', 'mwb-wc-bk' ); ?></option>
									<option value="day" <?php selected( '', 'week_range' ); ?>><?php esc_html_e( 'Week Range', 'mwb-wc-bk' ); ?></option>
									<option value="year" <?php selected( '', 'month_range' ); ?>><?php esc_html_e( 'Month Range', 'mwb-wc-bk' ); ?></option>
									<option value="year" <?php selected( '', 'date_range' ); ?>><?php esc_html_e( 'Custom Date Range', 'mwb-wc-bk' ); ?></option>
									<option value="year" <?php selected( '', 'time_range' ); ?>><?php esc_html_e( 'Time Range', 'mwb-wc-bk' ); ?></option>
									<option value="year" <?php selected( '', 'unit_range' ); ?>><?php esc_html_e( 'Booking Unit Range', 'mwb-wc-bk' ); ?></option>
									<option value="year" <?php selected( '', 'people_range' ); ?>><?php esc_html_e( 'People Range', 'mwb-wc-bk' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<p>
									<input type="date" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $cost_rule_count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_from[ $count ] ); ?>" >
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<input type="date" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $cost_rule_count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_to[ $count ] ); ?>" >
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
				<?php
			}
		}
		?>
		</div>
		<div id="mwb_global_cost_button">
			<button type="button" id="mwb_add_cost_rule" class="button"><?php esc_html_e( 'Add New Cost Rule', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
	<!-- Save Settings -->
	<p class="submit">
		<input type="submit" value="<?php esc_html_e( 'Save Changes', 'mwb-wc-bk' ); ?>" class="button-primary woocommerce-save-button" name="mwb_booking_global_cost_rules_save" id="mwb_booking_global_cost_rules_save" >
	</p>
</form>

