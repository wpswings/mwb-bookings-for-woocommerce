<?php
/**
 * MWB Booking Product General Settings Tab
 *
 * @package MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/admin/partials
 */

?>
<div id="mwb_booking_general_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_booking_unit" class="options_group">
		<p class="form-field">
			<label for="mwb_booking_unit_select"><?php esc_html_e( 'Booking Unit', 'mwb-bookings-for-woocommerce' ); ?></label>
			<select name="mwb_booking_unit_select" id="mwb_booking_unit_select" class="" >
				<option value="fixed" <?php selected( $this->setting_fields['mwb_booking_unit_select'], 'fixed' ); ?>><?php esc_html_e( 'Fixed unit', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="customer" <?php selected( $this->setting_fields['mwb_booking_unit_select'], 'customer' ); ?>><?php esc_html_e( 'Customer selected unit', 'mwb-bookings-for-woocommerce' ); ?></option>
			</select>
			<input type="number" name="mwb_booking_unit_input" id="mwb_booking_unit_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_unit_input'] ); ?>" step="1" min="1" >
			<select name="mwb_booking_unit_duration" id="mwb_booking_unit_duration" class="booking_unit_duration" >

				<?php foreach ( apply_filters( 'mwb_wc_bk_unit_durations', $this->get_booking_duration_options() ) as $key => $value ) : ?>
					<option <?php selected( $key, $this->setting_fields['mwb_booking_unit_duration'] ); ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>

			</select>
		</p>
	</div>
	<div id="mwb_start_booking_from" class="options_group">
		<p class="form-field">
			<label for="mwb_start_booking_date"><?php esc_html_e( 'Start date in Booking Form', 'mwb-bookings-for-woocommerce' ); ?></label>
			<select name="mwb_start_booking_from" id="mwb_start_booking_date">
				<option value="today" <?php selected( $this->setting_fields['mwb_start_booking_from'], 'today' ); ?>><?php esc_html_e( 'Today', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="tomorrow" <?php selected( $this->setting_fields['mwb_start_booking_from'], 'tomorrow' ); ?>><?php esc_html_e( 'Tomorrow', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="initially_available" <?php selected( $this->setting_fields['mwb_start_booking_from'], 'initially_available' ); ?>><?php esc_html_e( 'Initially Available', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="custom_date" <?php selected( $this->setting_fields['mwb_start_booking_from'], 'custom_date' ); ?>><?php esc_html_e( 'Custom Date', 'mwb-bookings-for-woocommerce' ); ?></option>
			</select>
		</p>	
		<p class="form-field" id="mwb_start_booking_custom_date_field">	
			<label for="mwb_start_booking_custom_date"><?php esc_html_e( 'Custom date to start booking', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input id="mwb_start_booking_custom_date" name="mwb_start_booking_custom_date" type="date" value="<?php echo esc_attr( $this->setting_fields['mwb_start_booking_custom_date'] ); ?>" >
		</p>
	</div>
	<div id="mwb_calendar_range" class="options_group" style="display:none">                   <!-- Mandatory Inline CSS. -->
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_enable_range_picker',
					'label'       => __( 'Enable Calendar Range Picker', 'mwb-bookings-for-woocommerce' ),
					'value'       => $this->setting_fields['mwb_enable_range_picker'],
					'description' => __( 'To select the start and end date on the calendar.', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
	</div>
	<div id="mwb_full_day_select" class="options_group mwb-bookings__pro" style="display:none">    <!-- Mandatory Inline CSS. -->
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_full_day_booking',
					'label'       => __( 'Full Day Booking', 'mwb-bookings-for-woocommerce' ),
					'value'       => $this->setting_fields['mwb_full_day_booking'],
					'description' => __( 'Booking for the full day.', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
	</div>
	<div id="mwb_admin_confirmation" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_admin_confirmation',
					'label'       => __( 'Confirmation Required', 'mwb-bookings-for-woocommerce' ),
					'value'       => $this->setting_fields['mwb_admin_confirmation'],
					'description' => __( 'Enable booking confirmation by the admin.', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
	</div>
	<div id="mwb_booking_cancellation" class="options_group mwb-bookings__pro">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_allow_booking_cancellation',
					'label'       => __( 'Cancellation Allowed', 'mwb-bookings-for-woocommerce' ),
					'value'       => $this->setting_fields['mwb_allow_booking_cancellation'],
					'description' => __( 'Allows a user to cancel their booking.', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
	</div>
	<div id="mwb_booking_cancellation_days" class="options_group mwb-bookings__pro" style="display:none">     <!-- Mandatory Inline CSS. -->
		<?php
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_days_for_cancellation',
					'label'             => __( 'Max days to allow cancellation', 'mwb-bookings-for-woocommerce' ),
					'description'       => __( 'Maximum Day after which booking cancellation cannot be allowed.', 'mwb-bookings-for-woocommerce' ),
					'value'             => $this->setting_fields['mwb_max_days_for_cancellation'],
					'desc_tip'          => true,
					'type'              => 'number',
					'style'             => 'width: 4em; margin-right: 7px;',                                 // Style parameter of the function.
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '1',
					),
				)
			);
			?>
	</div>
</div>
