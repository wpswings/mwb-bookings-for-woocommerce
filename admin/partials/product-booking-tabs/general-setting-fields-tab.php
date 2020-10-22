<?php
/**
 * MWB Booking Product General Settings Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_general_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_general_settings_heading">
		<h1><em><?php esc_html_e( 'General Settings', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_booking_unit" class="options_group">
		<p class="form-field">
			<label for="mwb_booking_unit_select"><?php esc_html_e( 'Booking Unit', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_booking_unit_select" id="mwb_booking_unit_select" class="" style="width: auto; margin-right: 7px;">
				<option value="fixed" <?php selected( $this->setting_fields['mwb_booking_unit_select'], 'fixed' ); ?>><?php esc_html_e( 'Fixed unit', 'mwb-wc-bk' ); ?></option>
				<option value="customer" <?php selected( $this->setting_fields['mwb_booking_unit_select'], 'customer' ); ?>><?php esc_html_e( 'Customer selected unit', 'mwb-wc-bk' ); ?></option>
			</select>
			<input type="number" name="mwb_booking_unit_input" id="mwb_booking_unit_input" value="<?php echo esc_attr__( $this->setting_fields['mwb_booking_unit_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
			<select name="mwb_booking_unit_duration" id="mwb_booking_unit_duration" class="booking_unit_duration" style="width: auto; margin-right: 7px;">

				<?php foreach ( $this->get_booking_duration_options() as $key => $value ) : ?>
					<option <?php selected( $key, $this->setting_fields['mwb_booking_unit_duration'] ); ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html__( $value ); ?></option>
				<?php endforeach; ?>

			</select>
		</p>
	</div>
	<div id="mwb_start_booking_from" class="options_group">
		<p class="form-field">
			<label for="mwb_start_booking_date"><?php esc_html_e( 'Start Booking on date', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_start_booking_date" id="mwb_start_booking_date">
				<option value="none" <?php selected( $this->setting_fields['mwb_start_booking_date'], 'none' ); ?>><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
				<option value="today" <?php selected( $this->setting_fields['mwb_start_booking_date'], 'today' ); ?>><?php esc_html_e( 'Today', 'mwb-wc-bk' ); ?></option>
				<option value="tomorrow" <?php selected( $this->setting_fields['mwb_start_booking_date'], 'tomorrow' ); ?>><?php esc_html_e( 'Tomorrow', 'mwb-wc-bk' ); ?></option>
				<option value="initially_available" <?php selected( $this->setting_fields['mwb_start_booking_date'], 'initially_available' ); ?>><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
				<option value="custom_date" <?php selected( $this->setting_fields['mwb_start_booking_date'], 'custom_date' ); ?>><?php esc_html_e( 'Custom Date', 'mwb-wc-bk' ); ?></option>
			</select><br>
			<label for="mwb_start_booking_time"><?php esc_html_e( 'Time:', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_start_booking_time" id="mwb_start_booking_time">
				<option value="none" <?php selected( $this->setting_fields['mwb_start_booking_time'], 'none' ); ?> ><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
				<option value="initially_available" <?php selected( $this->setting_fields['mwb_start_booking_time'], 'initially_available' ); ?>><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
			</select>
		</p>	
		<p class="form-field">	
			<label for="mwb_start_booking_custom_date"><?php esc_html_e( 'Custom date to start booking', 'mwb-wc-bk' ); ?></label>
			<input id="mwb_start_booking_custom_date" name="mwb_start_booking_custom_date" type="date" value="<?php esc_attr_e( $this->setting_fields['mwb_start_booking_custom_date'] ); ?>">
		</p>
	</div>
	<div id="mwb_calendar_range" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_enable_range_picker',
					'label'       => __( 'Enable Calendar Range Picker', 'mwb-wc-bk' ),
					'value'       => $this->setting_fields['mwb_enable_range_picker'],
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
					'value'       => $this->setting_fields['mwb_full_day_booking'],
					'description' => __( 'Booking for full day.', 'mwb-wc-bk' ),
				)
			);
			?>
	</div>
	<div id="mwb_admin_confirmation" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_admin_confirmation',
					'label'       => __( 'Confirmation Required', 'mwb-wc-bk' ),
					'value'       => $this->setting_fields['mwb_admin_confirmation'],
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
					'value'       => $this->setting_fields['mwb_allow_booking_cancellation'],
					'description' => __( 'Allows user to cancel their booking.', 'mwb-wc-bk' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_days_for_cancellation',
					'label'             => __( 'Max days to allow cancellation', 'mwb-wc-bk' ),
					'description'       => __( 'Maximum Day after which booking cancellation cannot be allowed.', 'mwb-wc-bk' ),
					'value'             => $this->setting_fields['mwb_max_day_for_cancellation'],
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
