<?php
/**
 * MWB Booking Product Availability Fields Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_availability_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_availability_heading">
		<h1><em><?php esc_html_e( 'Availability', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_availability_preferences" class="options_group">
		<div id="mwb_preferences_heading">
			<h3><?php esc_html_e( 'Availability Preferences', '' ); ?></h3>
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
			<label for=""><?php esc_html_e( 'Booking starts', '' ); ?></label>
			<select name="" id="">
				<option value="00" default selected>hrs</option>
				<?php
				for ( $i = 1; $i <= 12; $i++ ) {
					?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php
				}
				?>
			</select>
			<select name="" id="">
				<option value="" default selected>mins</option>
				<option value="00"><?php esc_html_e( '00' ); ?></option>
				<option value="15"><?php esc_html_e( '15' ); ?></option>
				<option value="30"><?php esc_html_e( '30' ); ?></option>
				<option value="45"><?php esc_html_e( '45' ); ?></option>
			</select>
			<select name="" id="">
				<option value="am"><?php esc_html_e( 'AM' ); ?></option>
				<option value="pm"><?php esc_html_e( 'PM' ); ?></option>
			</select>
			<?php esc_html_e( 'Time when the booking starts', 'mwb-wc-bk' ); ?>
		</p>
		<p class="form-field">
			<label for=""><?php esc_html_e( 'Booking ends', 'mwb-wc-bk' ); ?></label>
			<select name="" id="">
				<option value="00" default selected>hrs</option>
				<?php
				for ( $i = 1; $i <= 12; $i++ ) {
					?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php
				}
				?>
			</select>
			<select name="" id="">
				<option value="" default selected>mins</option>
				<option value="00"><?php esc_html_e( '00' ); ?></option>
				<option value="15"><?php esc_html_e( '15' ); ?></option>
				<option value="30"><?php esc_html_e( '30' ); ?></option>
				<option value="45"><?php esc_html_e( '45' ); ?></option>
			</select>
			<select name="" id="">
				<option value="am"><?php esc_html_e( 'AM' ); ?></option>
				<option value="pm"><?php esc_html_e( 'PM' ); ?></option>
			</select>
			<?php esc_html_e( 'Time when the booking ends', '' ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_buffer_input"><?php esc_html_e( 'Booking Buffer', '' ); ?></label>
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
					<label for="mwb_advance_booking_max_input"><?php esc_html_e( 'Maximum advance booking', '' ); ?></label>
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
					<label for="mwb_advance_booking_min_input"><?php esc_html_e( 'Maximum advance booking', 'mwb-wc-bk' ); ?></label>
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
		<?php
			woocommerce_wp_radio(
				array(
					'id'          => 'mwb_booking_not_allowed_days',
					'label'       => __( 'Booking not allowed', 'mwb-wc-bk' ),
					'class'       => 'mwb_booking_restricted_days',
					'value'       => '',
					'description' => __( 'Weekdays on which booking is not allowed.', 'mwb-wc-bk' ),
					'name'        => '',
					'options'     => array(
						'sunday'    => __( 'Sunday', 'mwb-wc-bk' ),
						'monday'    => __( 'Monday', 'mwb-wc-bk' ),
						'tuesday'   => __( 'Tuesday', 'mwb-wc-bk' ),
						'wednesday' => __( 'Wednesday', 'mwb-wc-bk' ),
						'thursday'  => __( 'Thursday', 'mwb-wc-bk' ),
						'friday'    => __( 'Friday', 'mwb-wc-bk' ),
						'sataurday' => __( 'Saturday', 'mwb-wc-bk' ),
					),
					'desc_tip'    => 'true',
				)
			);
			?>
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
