<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set the global rules for Availability
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
$rule_arr = array();

$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
$rule_count         = ! empty( $availability_rules['rule_name'] ) ? count( $availability_rules['rule_name'] ) : 0;

echo '<pre>'; print_r( $availability_rules ); echo '</pre>';

if ( isset( $_POST['mwb_booking_global_availability_rules_save'] ) ) {


	// Nonce verification.
	check_admin_referer( 'mwb_booking_global_options_availability_nonce', 'mwb_booking_availability_nonce' );

	$rule_count = isset( $_POST['mwb_availability_rule_count'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_availability_rule_count'] ) ) : $rule_count;

	if ( $rule_count > 0 ) {
		$rule_arr['rule_switch']        = isset( $_POST['mwb_global_availability_rule_heading_switch'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_heading_switch'] ) ) : array();
		$rule_arr['rule_name']          = isset( $_POST['mwb_global_availability_rule_name'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_name'] ) ) : array();
		$rule_arr['rule_type']          = isset( $_POST['mwb_global_availability_rule_type'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_type'] ) ) : array();
		$rule_arr['rule_range_from']    = isset( $_POST['mwb_global_availability_rule_range_from'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_range_from'] ) ) : array();
		$rule_arr['rule_range_to']      = isset( $_POST['mwb_global_availability_rule_range_to'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_range_to'] ) ) : array();
		$rule_arr['rule_bookable']      = isset( $_POST['mwb_global_availability_rule_bookable'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_bookable'] ) ) : array();
		$rule_arr['rule_weekdays']      = isset( $_POST['mwb_global_availability_rule_weekdays'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_global_availability_rule_weekdays'] ) ) : array();
		$rule_arr['rule_weekdays_book'] = isset( $_POST['mwb_global_availability_rule_weekdays_book'] ) ? $_POST['mwb_global_availability_rule_weekdays_book'] : array();

		for ( $count = 0; $count < $rule_count; $count++ ) {
			$rule_arr['rule_switch'][ $count ]   = isset( $rule_arr['rule_switch'][ $count ] ) ? $rule_arr['rule_switch'][ $count ] : 'off';
			$rule_arr['rule_type'][ $count ]     = isset( $rule_arr['rule_type'][ $count ] ) ? $rule_arr['rule_type'][ $count ] : 'specific';
			$rule_arr['rule_bookable'][ $count ] = isset( $rule_arr['rule_bookable'][ $count ] ) ? $rule_arr['rule_bookable'][ $count ] : 'bookable';
			$rule_arr['rule_weekdays'][ $count ] = isset( $rule_arr['rule_weekdays'][ $count ] ) ? $rule_arr['rule_weekdays'][ $count ] : 'off';
			foreach ( $this->global_func->booking_search_weekdays() as $k => $v ) {
				$rule_arr['rule_weekdays_book'][ $count ][ $k ] = isset( $rule_arr['rule_weekdays_book'][ $count ][ $k ] ) ? $rule_arr['rule_weekdays_book'][ $count ][ $k ] : 'bookable';
			}
		}
		update_option( 'mwb_global_avialability_rules', $rule_arr );

	} else {
		echo esc_html__( 'Add a new Availability rule', 'mwb-wc-bk' );
	}
	update_option( 'mwb_global_availability_rules_count', $rule_count );
}

$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
?>

<!-- For Global options Setting -->
<form id="mwb_global_availability_form" action="" method="POST">
	<div class="mwb_booking_global_availability_rules">
		<div id="mwb_global_availability_rules">
		<?php

		wp_nonce_field( 'mwb_booking_global_options_availability_nonce', 'mwb_booking_availability_nonce' );

		if ( ! empty( $availability_rules ) ) {
			$mwb_availability_rule_switch        = ! empty( $availability_rules['rule_switch'] ) ? $availability_rules['rule_switch'] : array();
			$mwb_availability_rule_name          = ! empty( $availability_rules['rule_name'] ) ? $availability_rules['rule_name'] : array();
			$mwb_availability_rule_type          = ! empty( $availability_rules['rule_type'] ) ? $availability_rules['rule_type'] : array();
			$mwb_availability_rule_range_from    = ! empty( $availability_rules['rule_range_from'] ) ? $availability_rules['rule_range_from'] : array();
			$mwb_availability_rule_range_to      = ! empty( $availability_rules['rule_range_to'] ) ? $availability_rules['rule_range_to'] : array();
			$mwb_availability_rule_bookable      = ! empty( $availability_rules['rule_bookable'] ) ? $availability_rules['rule_bookable'] : array();
			$mwb_availability_rule_weekdays      = ! empty( $availability_rules['rule_weekdays'] ) ? $availability_rules['rule_weekdays'] : array();
			$mwb_availability_rule_weekdays_book = ! empty( $availability_rules['rule_weekdays_book'] ) ? $availability_rules['rule_weekdays_book'] : array();

			for ( $count = 0; $count < $rule_count; $count++ ) {
				?>
			<div id="mwb_global_availability_rule_<?php echo esc_html( $count + 1 ); ?>" data-id="<?php echo esc_html( $count + 1 ); ?>" class="mwb-availability-rules__table">
				<table class="form-table mwb_global_availability_rule_fields" >
					<tbody>
						<div class="mwb_global_availability_rule_heading mwb-global-availability-rule__heading">
							<h2>
							<label class="booking-availability__title" data-id="<?php echo esc_html( $count + 1 ); ?>" ><?php echo ! empty( $mwb_availability_rule_name[ $count ] ) ? esc_html( $mwb_availability_rule_name[ $count ] ) : esc_html__( 'Rule No- ', 'mwb-wc-bk' ) . esc_html( $count + 1 ); ?></label>
							<input type="hidden" name="mwb_availability_rule_count" value="<?php echo esc_html( $count + 1 ); ?>" >
							<input type="checkbox" class="mwb_global_availability_rule_heading_switch" name="mwb_global_availability_rule_heading_switch[<?php echo esc_html( $count ); ?>]" <?php checked( 'on', $mwb_availability_rule_switch[ $count ] ); ?>>
							</h2>
						</div>
						<tr valign="top" class="mwb-form-group">
							<th scope="row" class="mwb-form-group__label">
								<label><?php esc_html_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text mwb-form-group__input">
								<input type="text" class="mwb_global_availability_rule_name" name="mwb_global_availability_rule_name[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_name[ $count ] ); ?>">
							</td>
						</tr>
						<tr valign="top" class="mwb-form-group">
							<th scope="row" class="mwb-form-group__label">
								<label><?php esc_html_e( 'Rule Type', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text mwb-form-group__input">
								<input type="radio" class="mwb_global_availability_rule_type" name="mwb_global_availability_rule_type[<?php echo esc_html( $count ); ?>]" value="specific" <?php checked( 'specific', $mwb_availability_rule_type[ $count ] ); ?> >
								<label><?php esc_html_e( 'Specific Dates', 'mwb-wc-bk' ); ?></label>
								<input type="radio" class="mwb_global_availability_rule_type" name="mwb_global_availability_rule_type[<?php echo esc_html( $count ); ?>]" value="generic" <?php checked( 'generic', $mwb_availability_rule_type[ $count ] ); ?>>
								<label><?php esc_html_e( 'Generic Dates', 'mwb-wc-bk' ); ?></label>
							</td>
						</tr>
						<tr valign="top" class="range mwb-form-group">
							<th scope="row" class="mwb-form-group__label">
								<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text specific mwb-form-group__input">
								<p>
									<input type="date" class="mwb_global_availability_rule_range_from" name="mwb_global_availability_rule_range_from[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_range_from[ $count ] ); ?>" required >
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<input type="date" class="mwb_global_availability_rule_range_to" name="mwb_global_availability_rule_range_to[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_range_to[ $count ] ); ?>" required>
								</p>
							</td>
							<td class="forminp forminp-text generic mwb-form-group__input">
								<p>
									<select class="mwb_global_availability_rule_range_from" name="mwb_global_availability_rule_range_from[<?php echo esc_html( $count ); ?>]" required>
									<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_availability_rule_range_from[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<select class="mwb_global_availability_rule_range_to" name="mwb_global_availability_rule_range_to[<?php echo esc_html( $count ); ?>]" required>
									<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
										<option value="<?php echo esc_html( $k ); ?>" <?php selected( $k, $mwb_availability_rule_range_to[ $count ] ); ?>><?php echo esc_html( $v ); ?></option>
									<?php } ?>
									</select>
								</p>
							</td>
						</tr>
						<tr valign="top" class="bookable mwb-form-group">
							<th scope="row" class="mwb-form-group__label"></th>
							<td class="forminp forminp-text mwb-form-group__input">
								<p>
								<input type="radio" class="mwb_global_availability_rule_bookable" name="mwb_global_availability_rule_bookable[<?php echo esc_html( $count ); ?>]" value="bookable" <?php checked( 'bookable', $mwb_availability_rule_bookable[ $count ] ); ?> >
								<label><?php esc_html_e( 'Bookable', 'mwb-wc-bk' ); ?></label>
								<input type="radio" class="mwb_global_availability_rule_non_bookable" name="mwb_global_availability_rule_bookable[<?php echo esc_html( $count ); ?>]" value="non-bookable" <?php checked( 'non-bookable', $mwb_availability_rule_bookable[ $count ] ); ?>>
								<label><?php esc_html_e( 'Non-Bookable', 'mwb-wc-bk' ); ?></label>
								</p>
							</td>
						</tr>
						<tr valign="top" class="weekdays_rule mwb-form-group mwb-form-group__days">
							<th scope="row" class=""></th>
							<td class="forminp forminp-text mwb-form-group__input-rules" >
								<p>
									<input type="checkbox" class="mwb_global_availability_rule_weekdays" name="mwb_global_availability_rule_weekdays[<?php echo esc_html( $count ); ?>]" <?php checked( 'on', $mwb_availability_rule_weekdays[ $count ] ); ?> >
									<?php esc_html_e( 'Rules for weekdays', 'mwb-wc-bk' ); ?>
								</p>
							</td>
						<?php foreach ( $this->global_func->booking_search_weekdays() as $key => $values ) { ?>
							<td class="forminp forminp-text mwb_global_availability_rule_weekdays_book mwb-form-group__input">
								<p><?php echo esc_html( $values ); ?></p>
								<input type="hidden" name="mwb_global_availability_rule_weekdays_book[<?php echo esc_html( $count ); ?>][<?php echo esc_html( $key ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_weekdays_book[ $count ][ $key ] ); ?>">
								<input type="button" class="mwb_global_availability_rule_weekdays_book_button button" value="<?php echo esc_html( $mwb_availability_rule_weekdays_book[ $count ][ $key ] ); ?>" book="bookable">
							</td>
						<?php } ?>
						</tr>
					</tbody>
				</table>
				<button type="button" id="mwb_delete_avialability_rule" class="button mwb-delete-icon" rule_count="<?php echo esc_html( $count + 1 ); ?>" title="<?php esc_html_e( 'Delete Rule', 'mwb-wc-bk' ); ?>" >
					<svg width="16" height="16" fill="currentColor" class="mwb-trash-icon" viewBox="0 0 16 16">
						<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
						<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
					</svg>
				</button>
			</div>
				<?php
			}
		}
		?>
		</div>
		<div id="mwb_global_availability_button">
			<button type="button" id="mwb_add_avialability_rule" class="button mwb-add-rule__icon" rule_count="<?php echo esc_html( $rule_count ); ?>" title="<?php esc_html_e( 'Add New Avialability Rule', 'mwb-wc-bk' ); ?>" >
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
					<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
				</svg>
			</button>
		</div>
	</div>
	<!-- Save Settings -->
	<p class="submit mwb-booking__submit">
		<input type="submit" value="<?php esc_html_e( 'Save Changes', 'mwb-wc-bk' ); ?>" class="button-primary woocommerce-save-button mwb-btn" name="mwb_booking_global_availability_rules_save" id="mwb_booking_global_availability_rules_save" >
	</p>
</form>
