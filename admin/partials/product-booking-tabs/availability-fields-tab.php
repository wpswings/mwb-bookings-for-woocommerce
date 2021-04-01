<?php
/**
 * MWB Booking Product Availability Fields Tab
 *
 * @package Mwb_Wc_Bk
 */

//  print_r( $this->global_func->booking_search_weekdays() );
// //  die;

//$global_func = Mwb_Booking_Global_Functions::get_global_instance();
?>
<div id="mwb_booking_availability_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<!-- <div id="mwb_availability_heading">
		<h1><em><?php // esc_html_e( 'Availability', 'mwb-wc-bk' ); ?></em></h1>
	</div> -->
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
					'value'             => $this->setting_fields['mwb_max_bookings_per_unit'],
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
			<label for="mwb_booking_min_duration"><?php esc_html_e( 'Minimum duaration for booking', 'mwb-wc-bk' ); ?></label>
			<input type="number" name="mwb_booking_min_duration" id="mwb_booking_min_duration" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_min_duration'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Minimum Duartion for booking when selected by the customer', 'mwb-wc-bk' ) ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_max_duration"><?php esc_html_e( 'Maximum duaration for booking', 'mwb-wc-bk' ); ?></label>
			<input type="number" name="mwb_booking_max_duration" id="mwb_booking_max_duration" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_max_duration'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Maximum Duartion for booking when selected by the customer', 'mwb-wc-bk' ) ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_start_time"><?php esc_html_e( 'Daily start time', 'mwb-wc-bk' ); ?></label>
			<input type="time" name="mwb_booking_start_time" id="mwb_booking_start_time" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_start_time'] ); ?>" />
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Time when the booking starts', 'mwb-wc-bk' ) ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_end_time"><?php esc_html_e( 'Daily end time', 'mwb-wc-bk' ); ?></label>
			<input type="time" name="mwb_booking_end_time" id="mwb_booking_end_time" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_end_time'] ); ?>" />
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Time when the booking ends', 'mwb-wc-bk' ) ); ?>
			<span id="mwb_booking_time_notice"></span>
		</p>
		<p class="form-field mwb-bookings__pro">
			<label for="mwb_booking_buffer_input"><?php esc_html_e( 'Booking Buffer', 'mwb-wc-bk' ); ?></label>
			<input type="number" name="mwb_booking_buffer_input" id="mwb_booking_buffer_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_buffer_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;" disabled>
			<select name="mwb_booking_buffer_duration" id="mwb_booking_buffer_duration" class="" style="width: auto; margin-right: 7px;" disabled>

				<?php foreach ( apply_filters( 'mwb_wc_bk_buffer_durations', $this->get_booking_duration_options() ) as $key => $value ) : ?>
					<option <?php selected( $key, $this->setting_fields['mwb_booking_buffer_duration'] ); ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>

			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Time or days between two adjacent booking', 'mwb-wc-bk' ) ); ?>
		</p>
	</div>
	<div id="mwb_advance_booking" class="options_group">
		<div id="mwb_advance_booking_heading">
			<h3><?php esc_html_e( 'Adavance Booking', 'mwb-wc-bk' ); ?></h3>
		</div>
		<p class="form-field">
			<label for="mwb_advance_booking_min_input"><?php esc_html_e( 'Minimum advance booking', 'mwb-wc-bk' ); ?></label>
			<input type="number" name="mwb_advance_booking_min_input" id="mwb_advance_booking_min_input" value="<?php echo esc_attr( $this->setting_fields['mwb_advance_booking_min_input'] ); ?>" step="1" min="0" style="margin-right: 7px; width: 4em;">
			<select name="mwb_advance_booking_min_duration" id="mwb_advance_booking_min_duration" class="" style="width: auto; margin-right: 7px;">
				<option value="month" <?php selected( $this->setting_fields['mwb_advance_booking_min_duration'], 'month' ); ?>><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
				<option value="day" <?php selected( $this->setting_fields['mwb_advance_booking_min_duration'], 'day' ); ?>><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Max days before which the product can be booked', 'mwb-wc-bk' ) ); ?>
		</p>

		<p class="form-field">
			<label for="mwb_advance_booking_max_input"><?php esc_html_e( 'Maximum advance booking', 'mwb-wc-bk' ); ?></label>
			<input type="number" name="mwb_advance_booking_max_input" id="mwb_advance_booking_max_input" value="<?php echo esc_attr( $this->setting_fields['mwb_advance_booking_max_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
			<select name="mwb_advance_booking_max_duration" id="mwb_advance_booking_max_duration" class="" style="width: auto; margin-right: 7px;">
				<option value="month" <?php selected( $this->setting_fields['mwb_advance_booking_max_duration'], 'month' ); ?>><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
				<option value="day" <?php selected( $this->setting_fields['mwb_advance_booking_max_duration'], 'day' ); ?>><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
				<option value="year" <?php selected( $this->setting_fields['mwb_advance_booking_max_duration'], 'year' ); ?>><?php esc_html_e( 'Year(s)', 'mwb-wc-bk' ); ?></option>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Min days before which the product can be booked', 'mwb-wc-bk' ) ); ?>
		</p>

		<p class="form-field">
			<label for="mwb_booking_not_allowed_days"><?php esc_html_e( 'Booking not allowed', 'mwb-wc-bk' ); ?></label>
			<select name="mwb_booking_not_allowed_days[]" id="mwb_booking_not_allowed_days" multiple ="multiple" data-placeholder="<?php esc_html_e( 'Weekday(s)', 'mwb-wc-bk' ); ?>" data-day="<?php echo esc_html( $this->setting_fields['mwb_booking_not_allowed_days'] ); ?>">
				<?php foreach ( apply_filters( 'mwb_wc_bk_not_allowed_days', $this->global_func->booking_search_weekdays() ) as $key => $value ) : ?>
					<?php //if ( ! empty( $this->setting_fields['mwb_booking_not_allowed_days'] ) ) { ?>
					<option <?php echo in_array( $key, $this->setting_fields['mwb_booking_not_allowed_days'], true ) ? 'selected' : ''; ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $value ); ?></option>
					<?php //} ?>
				<?php endforeach; ?>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Select weekday(s) on which booking is not allowed', 'mwb-wc-bk' ) ); ?>
		</p>
	</div>
	<div id="mwb_local_availability_rules" class="options_group mwb-bookings__pro">
		<div id="mwb_local_availability_rules_heading">
			<h3><?php esc_html_e( 'Local Availability Rules', 'mwb-wc-bk' ); ?></h3>
		</div>	
		<div id="mwb_local_availability_rules_add" style="margin-bottom: 7px;">
			<button class="btn"><?php esc_html_e( 'Add New Rule', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
</div>
