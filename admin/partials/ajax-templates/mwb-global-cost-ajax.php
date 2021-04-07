<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set ajax handler fields for the global rules for Costs
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/ajax-templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}
$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
	die( 'Nonce value cannot be verified' );
}
$rule_count = ! empty( $_POST['rule_count'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_count'] ) ) : 0;

?>

<div id="mwb_global_cost_rule_<?php echo esc_html( $rule_count ); ?>" data-id="<?php echo esc_html( $rule_count ); ?>">
	<table class="form-table mwb_global_cost_rule_fields" >
		<tbody>
			<div class="mwb_global_cost_rule_heading">
				<h2>
				<label><?php echo esc_html( sprintf( 'Rule No- %u', $rule_count ) ); ?></label>
				<input type="hidden" name="mwb_cost_rule_count" value="<?php echo esc_html( $rule_count ); ?>" >
				<input type="checkbox" class="mwb_global_cost_rule_heading_switch" name="mwb_global_cost_rule_heading_switch[<?php echo esc_html( $rule_count - 1 ); ?>]" checked  >
				</h2>
			</div>
			<tr valign="top">
				<th scope="row" class="">
					<label><?php esc_html_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<input type="text" class="mwb_global_cost_rule_name" name="mwb_global_cost_rule_name[<?php echo esc_html( $rule_count - 1 ); ?>]" >
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="">
					<label><?php esc_html_e( 'Conditions', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<select name="mwb_global_cost_rule_condition[<?php echo esc_html( $rule_count - 1 ); ?>]" id="mwb_booking_global_cost_condition" class="mwb_booking_global_cost_condition" style="width: auto; margin-right: 7px;">
						<option value="" selected><?php esc_html_e( 'none', 'mwb-wc-bk' ); ?></option>
					<?php
					foreach ( $this->global_cost_conditions() as $k => $v ) {
						if ( ! is_array( $v ) ) {
							?>
							<option value="<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></option>
							<?php
						} else {
							foreach ( $v as $peoples => $people ) {
								if ( 'heading' === $peoples ) {
									?>
									<option value="" disabled><h5><?php echo esc_html( $people ); ?></h5></option>';
									<?php
								} else {
									?>
								<option value="<?php echo esc_html( $peoples ); ?>" ><?php echo esc_html( printf( ' - %s', $people ) ); ?></option>
									<?php
								}
							}
						}
					}
					?>
					</select>
				</td>
				<td class="forminp forminp-text date">
					<p>
						<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
						<input type="date" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]" >
						<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
						<input type="date" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]" >
					</p>
				</td>
				<td class="forminp forminp-text days">
						<p>
							<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							<select class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]">
							<?php foreach ( $this->global_func->booking_search_weekdays() as $k => $v ) { ?>
								<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
							<?php } ?>
							</select>
							<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
							<select class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]">
							<?php foreach ( $this->global_func->booking_search_weekdays() as $k => $v ) { ?>
								<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
							<?php } ?>
							</select>
						</p>
					</td>
					<td class="forminp forminp-text months">
						<p>
							<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							<select class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]">
							<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
								<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
							<?php } ?>
							</select>
							<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
							<select class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]">
							<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
								<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
							<?php } ?>
							</select>
						</p>
					</td>
					<td class="forminp forminp-text weeks">
						<p>
							<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							<select class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]">
							<?php foreach ( $this->global_func->booking_search_weeks() as $k => $v ) { ?>
								<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
							<?php } ?>
							</select>
							<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
							<select class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]">
							<?php foreach ( $this->global_func->booking_search_weeks() as $k => $v ) { ?>
								<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
							<?php } ?>
							</select>
						</p>
					</td>
					<td class="forminp forminp-text time">
						<p>
							<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							<input type="time" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]" >
							<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
							<input type="time" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]" >
						</p>
					</td>
					<td class="forminp forminp-text unit">
						<p>
							<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							<input type="number" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]" step="1" min="1">
							<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
							<input type="number" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]" step="1" min="1">
						</p>
					</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="">
					<label><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<p>
						<select name="mwb_global_cost_rule_base_cal[<?php echo esc_html( $rule_count - 1 ); ?>]" id="mwb_global_cost_rule_base_cal" class="mwb_global_cost_rule_base_cal">
							<?php
							foreach ( $this->global_func->booking_global_cost_cal() as $k => $v ) {
								?>
								<option value="<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></option>
								<?php
							}
							?>
						</select>
						<input type="number" class="mwb_global_cost_rule_base_cost" name="mwb_global_cost_rule_base_cost[<?php echo esc_html( $rule_count - 1 ); ?>]" step="1" min="1" >
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="">
					<label><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<p>
						<select name="mwb_global_cost_rule_unit_cal[<?php echo esc_html( $rule_count - 1 ); ?>]" id="mwb_global_cost_rule_unit_cal" class="mwb_global_cost_rule_unit_cal">
							<?php
							foreach ( $this->global_func->booking_global_cost_cal() as $k => $v ) {
								?>
								<option value="<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></option>
								<?php
							}
							?>
						</select>
						<input type="number" class="mwb_global_cost_rule_unit_cost" name="mwb_global_cost_rule_unit_cost[<?php echo esc_html( $rule_count - 1 ); ?>]" step="1" min="1" >
					</p>
				</td>
			</tr>
		</tbody>
	</table>
	<button type="button" id="mwb_delete_cost_rule" class="button" rule_count="<?php echo esc_html( $rule_count ); ?>" ><?php esc_html_e( 'Delete Rule', 'mwb-wc-bk' ); ?></button>
</div>
