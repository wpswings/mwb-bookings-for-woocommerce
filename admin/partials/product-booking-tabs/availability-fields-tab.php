<?php
/**
 * MWB Booking Product Availability Fields Tab
 *
 * @package Mwb_Wc_Bk
 */



if ( isset( $_POST['mwb_booking_start_hrs'], $_POST['mwb_booking_start_mins'], $_POST['mwb_booking_start_interval'] ) ) {
	$booking_start_time = sanitize_text_field( wp_unslash( $_POST['mwb_booking_start_hrs'] ) ) . ':' . sanitize_text_field( wp_unslash( $_POST['mwb_booking_start_mins'] ) ) . ' ' . sanitize_text_field( wp_unslash( $_POST['mwb_booking_start_interval'] ) );
} else {
	$booking_start_time = '09:00 AM';
}
if ( isset( $_POST['mwb_booking_end_hrs'], $_POST['mwb_booking_end_mins'], $_POST['mwb_booking_end_interval'] ) ) {
	$booking_end_time = sanitize_text_field( wp_unslash( $_POST['mwb_booking_end_hrs'] ) ) . ':' . sanitize_text_field( wp_unslash( $_POST['mwb_booking_end_mins'] ) ) . ' ' . sanitize_text_field( wp_unslash( $_POST['mwb_booking_end_interval'] ) );
} else {
	$booking_end_time = '09:00 PM';
}

	$this->mwb_booking_setting_fields['max_booking_per_unit']         = isset( $_POST['mwb_max_bookings_per_unit'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_max_bookings_per_unit'] ) ) : '';
	$this->mwb_booking_setting_fields['booking_start_time']           = $booking_start_time;
	$this->mwb_booking_setting_fields['booking_start_time']           = $booking_end_time;
	$this->mwb_booking_setting_fields['booking_buffer_days']          = isset( $_POST['mwb_booking_buffer_input'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_buffer_input'] ) ) : '';
	$this->mwb_booking_setting_fields['booking_buffer_interval']      = isset( $_POST['mwb_booking_buffer_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_buffer_duration'] ) ) : '';
	$this->mwb_booking_setting_fields['max_advance_booking_days']     = isset( $_POST['mwb_advance_booking_max_input'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_advance_booking_max_input'] ) ) : '';
	$this->mwb_booking_setting_fields['max_advance_booking_interval'] = isset( $_POST['mwb_advance_booking_max_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_advance_booking_max_duration'] ) ) : '';
	$this->mwb_booking_setting_fields['min_advance_booking_days']     = isset( $_POST['mwb_advance_booking_min_input'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_advance_booking_min_input'] ) ) : '';
	$this->mwb_booking_setting_fields['min_advance_booking_interval'] = isset( $_POST['mwb_advance_booking_min_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_advance_booking_min_duration'] ) ) : '';
	$this->mwb_booking_setting_fields['booking_not_allowed_on']       = isset( $_POST['mwb_booking_not_allowed_days'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_not_allowed_days'] ) ) : '';

?>
<div id="mwb_booking_availability_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_availability_heading">
		<h1><em><?php esc_html_e( 'Availability', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_availability_preferences" class="options_group">
		<div id="mwb_preferences_heading">
			<h3><?php esc_html_e( 'Availability Preferences', 'mwb-wc-bk' ); ?></h3>
		</div>
		<?php
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_bookings_per_unit',
					'label'             => __( 'Max Booking per unit', 'mwb-wc-bk' ),
					'description'       => __( 'Maximum number of bookings allowed per unit.', 'mwb-wc-bk' ),
					'value'             => '',
					'desc_tip'          => true,
					'type'              => 'number',
					'style'             => 'width: 30%; margin-right: 7px;',
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '1',
					),
				)
			);
			?>
		<p class="form-field">
			<label for="mwb_booking_start_hrs"><?php esc_html_e( 'Booking starts', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_booking_start_hrs" id="mwb_booking_start_hrs">
				<option value="00" default selected>hrs</option>
				<?php
				for ( $i = 1; $i <= 12; $i++ ) {
					?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php
				}
				?>
			</select>
			<select name="mwb_booking_start_mins" id="mwb_booking_start_mins">
				<option value="" default selected>mins</option>
				<option value="00"><?php esc_html_e( '00' ); ?></option>
				<option value="15"><?php esc_html_e( '15' ); ?></option>
				<option value="30"><?php esc_html_e( '30' ); ?></option>
				<option value="45"><?php esc_html_e( '45' ); ?></option>
			</select>
			<select name="mwb_booking_start_interval" id="mwb_booking_start_interval">
				<option value="am"><?php esc_html_e( 'AM' ); ?></option>
				<option value="pm"><?php esc_html_e( 'PM' ); ?></option>
			</select>
			<?php esc_html_e( 'Time when the booking starts', 'mwb-wc-bk' ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_end_hrs"><?php esc_html_e( 'Booking ends', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_booking_end_hrs" id="mwb_booking_end_hrs">
				<option value="00" default selected>hrs</option>
				<?php
				for ( $i = 1; $i <= 12; $i++ ) {
					?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php
				}
				?>
			</select>
			<select name="mwb_booking_end_mins" id="mwb_booking_end_mins">
				<option value="" default selected>mins</option>
				<option value="00"><?php esc_html_e( '00' ); ?></option>
				<option value="15"><?php esc_html_e( '15' ); ?></option>
				<option value="30"><?php esc_html_e( '30' ); ?></option>
				<option value="45"><?php esc_html_e( '45' ); ?></option>
			</select>
			<select name="mwb_booking_end_interval" id="mwb_booking_end_interval">
				<option value="am"><?php esc_html_e( 'AM' ); ?></option>
				<option value="pm"><?php esc_html_e( 'PM' ); ?></option>
			</select>
			<?php esc_html_e( 'Time when the booking ends', '' ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_buffer_input"><?php esc_html_e( 'Booking Buffer', 'mwb-wc-bk' ); ?></label>
			<input type="number" name="mwb_booking_buffer_input" id="mwb_booking_buffer_input" value="1" step="1" min="1" style="margin-right: 7px; width: 4em;">
			<select name="mwb_booking_buffer_duration" id="mwb_booking_buffer_duration" class="" style="width: auto; margin-right: 7px;">
				<option value="month" ><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
				<option value="day" ><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
				<option value="hour" ><?php esc_html_e( 'Hour(s)', 'mwb-wc-bk' ); ?></option>
				<option value="minute"><?php esc_html_e( 'Minute(s)', 'mwb-wc-bk' ); ?></option>
			</select>
		</p>
	</div>
	<div id="mwb_advance_booking" class="options_group">
		<div id="mwb_advance_booking_heading">
			<h3><?php esc_html_e( 'Adavance Booking', 'mwb-wc-bk' ); ?></h3>
		</div>
		<dl>
			<dt>
				<p class="form-field">
					<label for="mwb_advance_booking_max_input"><?php esc_html_e( 'Maximum advance booking', 'mwb-wc-bk' ); ?></label>
					<input type="number" name="mwb_advance_booking_max_input" id="mwb_advance_booking_max_input" value="1" step="1" min="1" style="margin-right: 7px; width: 4em;">
					<select name="mwb_advance_booking_max_duration" id="mwb_advance_booking_max_duration" class="" style="width: auto; margin-right: 7px;">
						<option value="month" ><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
						<option value="day" ><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
					</select>
				</p>
			</dt>
			<dd><?php esc_html_e( 'Max days before which the product can be booked', 'mwb-wc-bk' ); ?></dd>
			<dt>
				<p class="form-field">
					<label for="mwb_advance_booking_min_input"><?php esc_html_e( 'Minimum advance booking', 'mwb-wc-bk' ); ?></label>
					<input type="number" name="mwb_advance_booking_min_input" id="mwb_advance_booking_min_input" value="1" step="1" min="1" style="margin-right: 7px; width: 4em;">
					<select name="mwb_advance_booking_min_duration" id="mwb_advance_booking_min_duration" class="" style="width: auto; margin-right: 7px;">
						<option value="month" ><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
						<option value="day" ><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
						<option value="year" ><?php esc_html_e( 'Year(s)', 'mwb-wc-bk' ); ?></option>
					</select>
				</p>
			</dt>
			<dd><?php esc_html_e( 'Min days before which the product can be booked', 'mwb-wc-bk' ); ?></dd>
		</dl>			
			<p class="form-field">
				<label for="mwb_booking_not_allowed_days"><?php esc_html_e( 'Booking not allowed', '' ); ?></label>
				<select name="mwb_booking_not_allowed_days" id="mwb_booking_not_allowed_days" multiple ="multiple" data-placeholder="<?php esc_html_e( 'Weekdays on which booking is not allowed', 'mwb-wc-bk' ); ?>">
					<option value="sunday"><?php esc_html_e( 'Sunday', 'mwb-wc-bk' ); ?></option>
					<option value="monday"><?php esc_html_e( 'Monday', 'mwb-wc-bk' ); ?></option>
					<option value="tuesday"><?php esc_html_e( 'Tuesday', 'mwb-wc-bk' ); ?></option>
					<option value="wednesday"><?php esc_html_e( 'Wednesday', 'mwb-wc-bk' ); ?></option>
					<option value="thursday"><?php esc_html_e( 'Thursday', 'mwb-wc-bk' ); ?></option>
					<option value="friday"><?php esc_html_e( 'Friday', 'mwb-wc-bk' ); ?></option>
					<option value="saturday"><?php esc_html_e( 'Saturday', 'mwb-wc-bk' ); ?></option>
				</select>
			</p>
	</div>
	<div id="mwb_local_availability_rules" class="options_group">
		<div id="mwb_local_availability_rules_heading">
			<h3><?php esc_html_e( 'Local Availability Rules', 'mwb-wc-bk' ); ?></h3>
		</div>	
		<div id="mwb_local_availability_rules_add" style="margin-bottom: 7px;">
			<button class="btn"><?php esc_html_e( 'Add New Rule', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
</div>
