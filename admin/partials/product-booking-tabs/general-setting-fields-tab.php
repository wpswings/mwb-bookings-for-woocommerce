<?php
/**
 * MWB Booking Product General Settings Tab
 *
 * @package Mwb_Wc_Bk
 */


$this->mwb_booking_setting_fields['booking_unit_select']       = ! empty( $_POST['mwb_booking_unit_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_unit_select'] ) ) : 'customer';
$this->mwb_booking_setting_fields['booking_unit_input']        = isset( $_POST['mwb_booking_unit_input'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_unit_input'] ) ) : '';
$this->mwb_booking_setting_fields['booking_unit_duration']     = isset( $_POST['mwb_booking_unit_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_unit_duration'] ) ) : 'day';
$this->mwb_booking_setting_fields['start_booking_date']        = isset( $_POST['start_booking_date_from'] ) ? sanitize_text_field( wp_unslash( $_POST['start_booking_date_from'] ) ) : '';
$this->mwb_booking_setting_fields['start_booking_time']        = isset( $_POST['start_booking_time_from'] ) ? sanitize_text_field( wp_unslash( $_POST['start_booking_time_from'] ) ) : '';
$this->mwb_booking_setting_fields['start_booking_custom_date'] = isset( $_POST['mwb_booking_custom_date'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_custom_date'] ) ) : '';
$this->mwb_booking_setting_fields['enable_range_picker']       = isset( $_POST['mwb_enable_range_picker'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_enable_range_picker'] ) ) : 'no';
$this->mwb_booking_setting_fields['full_day_booking']          = isset( $_POST['mwb_full_day_booking'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_full_day_booking'] ) ) : 'no';
$this->mwb_booking_setting_fields['admin_confirmation']        = isset( $_POST['mwb_admin_confirmation_required'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_admin_confirmation_required'] ) ) : 'no';
$this->mwb_booking_setting_fields['allow_cancellation']        = isset( $_POST['mwb_allow_booking_cancellation'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_allow_booking_cancellation'] ) ) : 'no';
$this->mwb_booking_setting_fields['max_days_for_cancellation'] = isset( $_POST['mwb_max_day_for_cancellation'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_max_day_for_cancellation'] ) ) : '';

?>
<div id="mwb_booking_general_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_general_settings_heading">
		<h1><em><?php esc_html_e( 'General Settings', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_booking_unit" class="options_group">
		<p class="form-field">
			<label for="mwb_booking_unit_select"><?php esc_html_e( 'Booking Unit', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_booking_unit_select" id="mwb_booking_unit_select" class="" style="width: auto; margin-right: 7px;">
				<option value="fixed"><?php esc_html_e( 'Fixed unit', 'mwb-wc-bk' ); ?></option>
				<option value="customer"><?php esc_html_e( 'Customer selected unit', 'mwb-wc-bk' ); ?></option>
			</select>
			<input type="number" name="mwb_booking_unit_input" id="mwb_booking_unit_input" value="1" step="1" min="1" style="margin-right: 7px; width: 4em;">
			<select name="mwb_booking_unit_duration" id="mwb_booking_unit_duration" class="" style="width: auto; margin-right: 7px;">
				<option value="month" ><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
				<option value="day" ><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
				<option value="hour" ><?php esc_html_e( 'Hour(s)', 'mwb-wc-bk' ); ?></option>
				<option value="minute"><?php esc_html_e( 'Minute(s)', 'mwb-wc-bk' ); ?></option>
			</select>
		</p>
	</div>
	<div id="mwb_start_booking_from" class="options_group">
		<p class="form-field">
			<label for="start_booking_date_from"><?php esc_html_e( 'Start Booking on date', 'mwb-wc-bk' ); ?></label>
			<select name="start_booking_date_from" id="start_booking_date_from">
				<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
				<option value="today"><?php esc_html_e( 'Today', 'mwb-wc-bk' ); ?></option>
				<option value="tomorrow"><?php esc_html_e( 'Tomorrow', 'mwb-wc-bk' ); ?></option>
				<option value="initially_available"><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
				<option value="custom_date"><?php esc_html_e( 'Custom Date', 'mwb-wc-bk' ); ?></option>
			</select><br>
			<label for="start_booking_time_from"><?php esc_html_e( 'Time:', 'mwb-wc-bk' ); ?></label>
			<select name="start_booking_time_from" id="start_booking_time_from">
				<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
				<option value="initially_available"><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
			</select>
		</p>	
		<p class="form-field">	
			<label for="mwb_booking_custom_date"><?php esc_html_e( 'Custom date to start booking', 'mwb-wc-bk' ); ?></label>
			<input id="mwb_booking_custom_date" name="mwb_booking_custom_date" type="time">
		</p>
	</div>
	<div id="mwb_calendar_range" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_enable_range_picker',
					'label'       => __( 'Enable Calendar Range Picker', 'mwb-wc-bk' ),
					'value'       => 'yes',
					'description' => __( 'To select the start and end date on the calendar.', 'mwb-wc-bk' ),
				)
			);
			?>
	</div>
	<div id="mwb_full_day_select" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_full_day_booking',
					'label'       => __( 'Full Day Booking', 'mwb-wc-bk' ),
					'value'       => 'no',
					'description' => __( 'Booking for full day.', 'mwb-wc-bk' ),
				)
			);
			?>
	</div>
	<div id="mwb_admin_confirmation" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_admin_confirmation_required',
					'label'       => __( 'Confirmation Required', 'mwb-wc-bk' ),
					'value'       => 'no',
					'description' => __( 'Enable booking confirmation by the admin.', 'mwb-wc-bk' ),
				)
			);
			?>
	</div>
	<div id="mwb_booking_cancellation" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_allow_booking_cancellation',
					'label'       => __( 'Cancellation Allowed', 'mwb-wc-bk' ),
					'value'       => 'no',
					'description' => __( 'Allows user to cancel their booking.', 'mwb-wc-bk' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_day_for_cancellation',
					'label'             => __( 'Max days to allow cancellation', 'mwb-wc-bk' ),
					'description'       => __( 'Maximum Day after which booking cancellation cannot be allowed.', 'mwb-wc-bk' ),
					'value'             => '',
					'desc_tip'          => true,
					'type'              => 'number',
					'style'             => 'width: auto; margin-right: 7px;',
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '1',
					),
				)
			);
			?>
	</div>
</div>
