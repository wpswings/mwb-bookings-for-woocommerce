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
$cost_rule_arr = array();

$cost_rules      = get_option( 'mwb_global_cost_rules', array() );
$cost_rule_count = ! empty( $cost_rules['rule_name'] ) ? count( $cost_rules['rule_name'] ) : 0;


if ( isset( $_POST['mwb_booking_global_cost_rules_save'] ) ) {

	check_admin_referer( 'mwb_booking_global_options_cost_nonce', 'mwb_booking_cost_nonce' );

	$cost_rule_count = isset( $_POST['mwb_cost_rule_count'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_cost_rule_count'] ) ) : $cost_rule_count;

	if ( $cost_rule_count > 0 ) {
		$cost_rule_arr['rule_switch']     = isset( $_POST['mwb_global_cost_rule_heading_switch'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_heading_switch'] ) ) : array();
		$cost_rule_arr['rule_name']       = isset( $_POST['mwb_global_cost_rule_name'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_name'] ) ) : array();
		$cost_rule_arr['rule_condition']  = isset( $_POST['mwb_global_cost_rule_condition'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_condition'] ) ) : array();
		$cost_rule_arr['rule_range_from'] = isset( $_POST['mwb_global_cost_rule_range_from'] ) ? $_POST['mwb_global_cost_rule_range_from'] : array();
		$cost_rule_arr['rule_range_to']   = isset( $_POST['mwb_global_cost_rule_range_to'] ) ? $_POST['mwb_global_cost_rule_range_to'] : array();
		$cost_rule_arr['rule_base_cal']   = isset( $_POST['mwb_global_cost_rule_base_cal'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_base_cal'] ) ) : array();
		$cost_rule_arr['rule_base_cost']  = isset( $_POST['mwb_global_cost_rule_base_cost'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_base_cost'] ) ) : array();
		$cost_rule_arr['rule_unit_cal']   = isset( $_POST['mwb_global_cost_rule_unit_cal'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_unit_cal'] ) ) : array();
		$cost_rule_arr['rule_unit_cost']  = isset( $_POST['mwb_global_cost_rule_unit_cost'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_cost_rule_unit_cost'] ) ) : array();

		for ( $count = 0; $count < $cost_rule_count; $count++ ) {
			$cost_rule_arr['rule_switch'][ $count ] = isset( $cost_rule_arr['rule_switch'][ $count ] ) ? $cost_rule_arr['rule_switch'][ $count ] : 'off';
		}

		update_option( 'mwb_global_cost_rules', $cost_rule_arr );
	} else {
		echo esc_html__( 'Add a new Cost rule', 'mwb-wc-bk' );
	}
}
$cost_rules = get_option( 'mwb_global_cost_rules', array() );

// echo '<pre>';
// print_r( $cost_rules );
// echo '</pre>';
?>

<!-- For Global options Setting -->
<form id="mwb_global_cost_form" action="" method="POST">
	<div class="mwb_booking_global_cost_rules">
		<div id="mwb_global_cost_rules">
		<?php

		wp_nonce_field( 'mwb_booking_global_options_cost_nonce', 'mwb_booking_cost_nonce' );

		if ( ! empty( $cost_rules ) && $cost_rule_count > 0 ) {
			$mwb_cost_rule_switch     = ! empty( $cost_rules['rule_switch'] ) ? $cost_rules['rule_switch'] : '';
			$mwb_cost_rule_name       = ! empty( $cost_rules['rule_name'] ) ? $cost_rules['rule_name'] : '';
			$mwb_cost_rule_condition  = ! empty( $cost_rules['rule_condition'] ) ? $cost_rules['rule_condition'] : '';
			$mwb_cost_rule_range_from = ! empty( $cost_rules['rule_range_from'] ) ? $cost_rules['rule_range_from'] : '';
			$mwb_cost_rule_range_to   = ! empty( $cost_rules['rule_range_to'] ) ? $cost_rules['rule_range_to'] : '';
			$mwb_cost_rule_base_cal   = ! empty( $cost_rules['rule_base_cal'] ) ? $cost_rules['rule_base_cal'] : '';
			$mwb_cost_rule_base_cost  = ! empty( $cost_rules['rule_base_cost'] ) ? $cost_rules['rule_base_cost'] : '';
			$mwb_cost_rule_unit_cal   = ! empty( $cost_rules['rule_unit_cal'] ) ? $cost_rules['rule_unit_cal'] : '';
			$mwb_cost_rule_unit_cost  = ! empty( $cost_rules['rule_unit_cost'] ) ? $cost_rules['rule_unit_cost'] : '';

			for ( $count = 0; $count < $cost_rule_count; $count++ ) {
				?>
			<div id="mwb_global_cost_rule_<?php echo esc_html( $count + 1 ); ?>" data-id="<?php echo esc_html( $count + 1 ); ?>">
				<table class="form-table mwb_global_cost_rule_fields" >
					<tbody>
						<div class="mwb_global_cost_rule_heading">
							<h2>
							<label data-id="<?php echo esc_html( $count + 1 ); ?>"><?php echo ! empty( $mwb_cost_rule_name[ $count ] ) ? esc_html( $mwb_cost_rule_name[ $count ] ) : esc_html__( 'Rule No- ', 'mwb-wc-bk' ) . esc_html( $count + 1 ); ?></label>
							<input type="hidden" name="mwb_cost_rule_count" value="<?php echo esc_html( $count + 1 ); ?>" >
							<input type="checkbox" class="mwb_global_cost_rule_heading_switch" name="mwb_global_cost_rule_heading_switch[<?php echo esc_html( $count ); ?>]" <?php checked( 'on', $mwb_cost_rule_switch[ $count ] ); ?>>
							</h2>
						</div>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<input type="text" class="mwb_global_cost_rule_name" name="mwb_global_cost_rule_name[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_name[ $count ] ); ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Conditions', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<select name="mwb_global_cost_rule_condition[<?php echo esc_html( $count ); ?>]" class="mwb_booking_global_cost_condition" style="width: auto; margin-right: 7px;">
								<?php
								foreach ( $this->global_cost_conditions() as $k => $v ) {
									if ( ! is_array( $v ) ) {
										?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_condition[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
										<?php
									} else {
										foreach ( $v as $peoples => $people ) {
											if ( 'heading' === $peoples ) {
												?>
												<option value="" disabled><h5><?php echo esc_html( $people ); ?></h5></option>';
												<?php
											} else {
												?>
											<option value="<?php echo esc_html( $peoples ); ?>" <?php selected( $peoples, $mwb_cost_rule_condition[ $count ] ); ?>><?php echo esc_html( printf( ' - %s', $people ) ); ?></option>
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
									<input type="date" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_from[ $count ] ); ?>">
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<input type="date" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_to[ $count ] ); ?>" >
								</p>
							</td>
							<td class="forminp forminp-text days">
								<p>
									<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $count ); ?>]">
										<!-- <option value="" selected ><?php //esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
									<?php foreach ( $this->global_func->booking_search_weekdays() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_range_from[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $count ); ?>]">
										<!-- <option value="" selected ><?php //esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
									<?php foreach ( $this->global_func->booking_search_weekdays() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_range_to[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
								</p>
							</td>
							<td class="forminp forminp-text months">
								<p>
									<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $count ); ?>]">
										<!-- <option value="" selected ><?php //esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
									<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_range_from[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $count ); ?>]">
										<!-- <option value="" selected ><?php //esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
									<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_range_to[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
								</p>
							</td>
							<td class="forminp forminp-text weeks">
								<p>
									<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $count ); ?>]">
										<!-- <option value="" selected ><?php //esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
									<?php foreach ( $this->global_func->booking_search_weeks() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_range_from[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $count ); ?>]">
										<!-- <option value="" selected ><?php //esc_html_e( 'None', 'mwb-wc-bk' ); ?></option> -->
									<?php foreach ( $this->global_func->booking_search_weeks() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_range_to[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
								</p>
							</td>
							<td class="forminp forminp-text time">
								<p>
									<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
									<input type="time" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_from[ $count ] ); ?>">
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<input type="time" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_to[ $count ] ); ?>" >
								</p>
							</td>
							<td class="forminp forminp-text unit">
								<p>
									<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
									<input type="number" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_from[ $count ] ); ?>" step="1" min="1">
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<input type="number" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_cost_rule_range_to[ $count ] ); ?>" step="1" min="1">
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<p>
									<select name="mwb_global_cost_rule_base_cal[<?php echo esc_html( $count ); ?>]" id="mwb_global_cost_rule_base_cal" class="mwb_global_cost_rule_base_cal">
										<?php
										foreach ( $this->global_func->booking_global_cost_cal() as $k => $v ) {
											?>
											<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_base_cal[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
											<?php
										}
										?>
									</select>
									<input type="number" class="mwb_global_cost_rule_base_cost" name="mwb_global_cost_rule_base_cost[<?php echo esc_html( $count ); ?>]" step="1" min="1" value="<?php echo esc_html( $mwb_cost_rule_base_cost[ $count ] ); ?>" >
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<p>
									<select name="mwb_global_cost_rule_unit_cal[<?php echo esc_html( $count ); ?>]" id="mwb_global_cost_rule_unit_cal" class="mwb_global_cost_rule_unit_cal">
										<?php
										foreach ( $this->global_func->booking_global_cost_cal() as $k => $v ) {
											?>
											<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_cost_rule_unit_cal[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
											<?php
										}
										?>
									</select>
									<input type="number" class="mwb_global_cost_rule_unit_cost" name="mwb_global_cost_rule_unit_cost[<?php echo esc_html( $count ); ?>]" step="1" min="1" value="<?php echo esc_html( $mwb_cost_rule_unit_cost[ $count ] ); ?>" >
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- <div id="mwb_delete_cost_rule_button"> -->
					<button type="button" id="mwb_delete_cost_rule" class="button" rule_count="<?php echo esc_html( $count + 1 ); ?>" ><?php esc_html_e( 'Delete Rule', 'mwb-wc-bk' ); ?></button>
				<!-- </div> -->
			</div>
				<?php
			}
		}
		?>
		</div>
		<div id="mwb_global_cost_button">
			<button type="button" id="mwb_add_cost_rule" class="button" rule_count="<?php echo esc_html( $cost_rule_count ); ?>"><?php esc_html_e( 'Add New Cost Rule', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
	<!-- Save Settings -->
	<p class="submit">
		<input type="submit" value="<?php esc_html_e( 'Save Changes', 'mwb-wc-bk' ); ?>" class="button-primary woocommerce-save-button" name="mwb_booking_global_cost_rules_save" id="mwb_booking_global_cost_rules_save" >
	</p>
</form>

