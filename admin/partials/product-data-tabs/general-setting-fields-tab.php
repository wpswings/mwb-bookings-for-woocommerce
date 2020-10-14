<?php
/**
 * MWB Booking Product General Settings Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_product_general_data" class="panel woocommerce_options_panel show_if_mwb_booking options_group">
	<div class="mwb_booking_setting_heading" >
			<h1><?php esc_html_e( 'General Settings', 'mwb-wc-bk' ); ?></h1>
	</div>
	<div id="mwb_booking_unit">
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
	<div id="mwb_start_booking_from">
		<p class="form-field">
			<label for="start_booking_date_from"><?php esc_html_e( 'Start Booking on date', 'mwb-wc-bk' ); ?></label>
			<select name="start_booking_date_from" id="start_booking_date_from" style="width: auto; margin-right: 7px;">
				<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
				<option value="today"><?php esc_html_e( 'Today', 'mwb-wc-bk' ); ?></option>
				<option value="tomorrow"><?php esc_html_e( 'Tomorrow', 'mwb-wc-bk' ); ?></option>
				<option value="initially_available"><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
				<option value="custom_date"><?php esc_html_e( 'Custom Date', 'mwb-wc-bk' ); ?></option>
			</select>
			<br>
			<label for="start_booking_time_from"><?php esc_html_e( 'Time:', 'mwb-wc-bk' ); ?></label>
			<select name="start_booking_time_from" id="start_booking_time_from" style="width: auto; margin-right: 7px;">
				<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
				<option value="initially_available"><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
			</select>
		</p>
		<p class="form-field">
			<label for="mwb_booking_custom_date"><?php esc_html_e( 'Custom date to start booking', 'mwb-wc-bk' ); ?></label>
			<input id="mwb_booking_custom_date" type="text">
		</p>
	</div>
	<div id="mwb_calendar_range">
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
	<div id="mwb_full_day_select">
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
	<div id="mwb_admin_confirmation">
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
	<div id="mwb_booking_cancellation">
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
