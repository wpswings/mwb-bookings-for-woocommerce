<?php
/**
 * MWB Booking Product Availability Tab
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
					'id'                => 'mwb_max_bookings_per unit',
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
			<?php esc_html_e( 'Time when the booking starts', '' ); ?>
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
	<div>
		<div id="mwb_preferences_heading">
			<h3><?php esc_html_e( 'Adavance Booking', 'mwb-wc-bk' ); ?></h3>
		</div>
		
	</div>
</div>
