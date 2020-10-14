<?php
/**
 * MWB Booking Product Availability Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_product_availability_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_booking_availability" class="options_group">
		<div class="mwb_booking_setting_heading" >
			<h1><em><?php esc_html_e( 'Availability', 'mwb-wc-bk' ); ?></em></h1>
		</div>
		<div id="mwb_availability_max_booking">
			<div id="mwb_advance_booking_heading">
				<h3><?php esc_html_e( 'Availability Preferences', 'mwb-wc-bk' ); ?></h3>
			</div>
			<?php
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_booking_per-unit',
					'label'             => __( 'Max Bookings per unit', 'mwb-wc-bk' ),
					'description'       => __( 'Maximum Number of bookings allowed for each unit', 'mwb-wc-bk' ),
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
		</div>
		<div id="mwb_availability_booking time">
			<?php
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_booking_starts',
					'label'       => __( 'Booking starts', 'mwb-wc-bk' ),
					'description' => __( 'Time when the booking starts', 'mwb-wc-bk' ),
					'value'       => '',
					'desc_tip'    => false,
					'type'        => 'text',
					'style'       => 'width: auto; margin-right: 7px;',
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_booking_stops',
					'label'       => __( 'Booking ends', 'mwb-wc-bk' ),
					'description' => __( 'Time when the booking stops', 'mwb-wc-bk' ),
					'value'       => '',
					'desc_tip'    => false,
					'type'        => 'text',
					'style'       => 'width: auto; margin-right: 7px;',
				)
			);
			?>
			<div id="mwb_booking_buffer">
				<p class="form-field">
					<label for="mwb_booking_buffer_input"><?php esc_html_e( 'Booking Buffer' ); ?></label>
					<input type="number" name="mwb_booking_buffer_input" id="mwb_booking_buffer_input" value="1" step="1" min="1" style="margin-right: 7px; width: 4em;">
					<select name="mwb_booking_buffer_duration" id="mwb_booking_buffer_duration" class="" style="width: auto; margin-right: 7px;">
						<option value="month"><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
						<option value="day"><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
						<option value="hour" ><?php esc_html_e( 'Hour(s)', 'mwb-wc-bk' ); ?></option>
						<option value="minute"><?php esc_html_e( 'Minute(s)', 'mwb-wc-bk' ); ?></option>
					</select>
					<!-- <label for="">Time between two adjacent bookings</label> -->
					Time between two adjacent bookings
				</p>				
			</div>
		</div>
	</div>
	<div id="mwb_product_advance_booking" class="option_group">
		<div id="mwb_advance_booking_heading">
			<h3><?php esc_html_e( 'Advance Booking' ); ?></h3>
		</div>
		<?php
		woocommerce_wp_text_input(
			array(
				'id'                => 'mwb_min_advance_booking',
				'label'             => __( 'Min days', 'mwb-wc-bk' ),
				'description'       => __( 'Maximum Number of bookings allowed for each unit', 'mwb-wc-bk' ),
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
		<?php
		woocommerce_wp_text_input(
			array(
				'id'                => 'mwb_max_advance_booking',
				'label'             => __( 'Max Bookings per unit', 'mwb-wc-bk' ),
				'description'       => __( 'Maximum Number of bookings allowed for each unit', 'mwb-wc-bk' ),
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
	</div>
</div>
