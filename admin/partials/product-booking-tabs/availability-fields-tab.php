<?php
/**
 * MWB Booking Product Availability Fields Tab
 *
 * @package    MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/admin/partials
 */

?>
<div id="mwb_booking_availability_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_availability_preferences" class="options_group">
		<div id="mwb_preferences_heading">
			<h3><?php esc_html_e( 'Availability Preferences', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>
		<?php
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_bookings_per_unit',
					'label'             => __( 'Max Booking per unit', 'mwb-bookings-for-woocommerce' ),
					'description'       => __( 'Maximum number of bookings allowed per unit.', 'mwb-bookings-for-woocommerce' ),
					'value'             => $this->setting_fields['mwb_max_bookings_per_unit'],
					'desc_tip'          => true,
					'type'              => 'number',
					'style'             => 'width: 30%; margin-right: 7px;',             // Style parameter for the "woocommerce_wp_text_input" function.
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '1',
					),
				)
			);
			?>
		<p class="form-field">
			<label for="mwb_booking_min_duration"><?php esc_html_e( 'Minimum duration for booking', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="number" name="mwb_booking_min_duration" id="mwb_booking_min_duration" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_min_duration'] ); ?>" step="1" min="1" >
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Minimum Duration for booking when selected by the customer', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_max_duration"><?php esc_html_e( 'Maximum duration for booking', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="number" name="mwb_booking_max_duration" id="mwb_booking_max_duration" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_max_duration'] ); ?>" step="1" min="1" >
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Maximum Duration for booking when selected by the customer', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>
		<p class="form-field">
			<label for="mwb_booking_end_time"><?php esc_html_e( 'Daily end time', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="time" name="mwb_booking_end_time" id="mwb_booking_end_time" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_end_time'] ); ?>" />
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Time when the booking ends', 'mwb-bookings-for-woocommerce' ) ); ?>
			<span id="mwb_booking_time_notice"></span>
		</p>
		<p class="form-field">
			<label for="mwb_booking_start_time"><?php esc_html_e( 'Daily start time', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="time" name="mwb_booking_start_time" id="mwb_booking_start_time" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_start_time'] ); ?>" />
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Time when the booking starts', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>
		<p class="form-field mwb-bookings__pro">
			<label for="mwb_booking_buffer_input"><?php esc_html_e( 'Booking Buffer', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="number" name="mwb_booking_buffer_input" id="mwb_booking_buffer_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_buffer_input'] ); ?>" step="1" min="1"  disabled>
			<select name="mwb_booking_buffer_duration" id="mwb_booking_buffer_duration" class="" disabled>                                                                                                                      <!-- Mandatory Inline CSS. -->

				<?php foreach ( apply_filters( 'mwb_wc_bk_buffer_durations', $this->get_booking_duration_options() ) as $key => $value ) : ?>
					<option <?php selected( $key, $this->setting_fields['mwb_booking_buffer_duration'] ); ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>

			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Time or days between two adjacent bookings', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>
	</div>
	<div id="mwb_advance_booking" class="options_group">
		<div id="mwb_advance_booking_heading">
			<h3><?php esc_html_e( 'Adavance Booking', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>
		<p class="form-field">
			<label for="mwb_advance_booking_min_input"><?php esc_html_e( 'Minimum advance booking', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="number" name="mwb_advance_booking_min_input" id="mwb_advance_booking_min_input" value="<?php echo esc_attr( $this->setting_fields['mwb_advance_booking_min_input'] ); ?>" step="1" min="0">                <!-- Mandatory Inline CSS. -->
			<select name="mwb_advance_booking_min_duration" id="mwb_advance_booking_min_duration" class="" >                                                                                                                        <!-- Mandatory Inline CSS. -->
				<option value="month" <?php selected( $this->setting_fields['mwb_advance_booking_min_duration'], 'month' ); ?>><?php esc_html_e( 'Month(s)', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="day" <?php selected( $this->setting_fields['mwb_advance_booking_min_duration'], 'day' ); ?>><?php esc_html_e( 'Day(s)', 'mwb-bookings-for-woocommerce' ); ?></option>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Max days before which the product can be booked', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>

		<p class="form-field">
			<label for="mwb_advance_booking_max_input"><?php esc_html_e( 'Maximum advance booking', 'mwb-bookings-for-woocommerce' ); ?></label>
			<input type="number" name="mwb_advance_booking_max_input" id="mwb_advance_booking_max_input" value="<?php echo esc_attr( $this->setting_fields['mwb_advance_booking_max_input'] ); ?>" step="1" min="1" >                <!-- Mandatory Inline CSS. -->
			<select name="mwb_advance_booking_max_duration" id="mwb_advance_booking_max_duration" class="" >                                                                                                                        <!-- Mandatory Inline CSS. -->
				<option value="month" <?php selected( $this->setting_fields['mwb_advance_booking_max_duration'], 'month' ); ?>><?php esc_html_e( 'Month(s)', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="day" <?php selected( $this->setting_fields['mwb_advance_booking_max_duration'], 'day' ); ?>><?php esc_html_e( 'Day(s)', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="year" <?php selected( $this->setting_fields['mwb_advance_booking_max_duration'], 'year' ); ?>><?php esc_html_e( 'Year(s)', 'mwb-bookings-for-woocommerce' ); ?></option>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Min days before which the product can be booked', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>

		<p class="form-field">
			<label for="mwb_booking_not_allowed_days"><?php esc_html_e( 'Booking not allowed', 'mwb-bookings-for-woocommerce' ); ?></label>
			<select name="mwb_booking_not_allowed_days[]" id="mwb_booking_not_allowed_days" multiple ="multiple" data-placeholder="<?php esc_html_e( 'Select Weekday(s)', 'mwb-bookings-for-woocommerce' ); ?>" data-day="<?php echo esc_html( wp_json_encode( $this->setting_fields['mwb_booking_not_allowed_days'] ) ); ?>" style="width:40%;">              <!-- Mandatory Inline CSS. -->
				<?php foreach ( apply_filters( 'mwb_wc_bk_not_allowed_days', $this->global_func->booking_search_weekdays() ) as $key => $value ) : ?>
					<option <?php echo in_array( $key, $this->setting_fields['mwb_booking_not_allowed_days'], true ) ? 'selected' : ''; ?> value="<?php echo esc_html( $key ); ?>"><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Select weekday(s) on which booking is not allowed', 'mwb-bookings-for-woocommerce' ) ); ?>
		</p>
	</div>
	<div id="mwb_local_availability_rules" class="options_group mwb-bookings__pro">
		<div id="mwb_local_availability_rules_heading">
			<h3><?php esc_html_e( 'Local Availability Rules', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>	
		<div id="mwb_local_availability_rules_add" >
			<button class="btn"><?php esc_html_e( 'Add New Rule', 'mwb-bookings-for-woocommerce' ); ?></button>
		</div>
	</div>
</div>
