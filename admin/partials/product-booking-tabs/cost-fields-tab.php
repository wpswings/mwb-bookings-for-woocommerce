<?php
/**
 * MWB Booking Product Cost Fields Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_cost_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_cost_heading">
		<h1><em><?php esc_html_e( 'Costs', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_booking_normal_cost" class="options_group">
		<div id="mwb_booking_normal_cost_heading">
			<h3><?php esc_html_e( 'Normal Cost', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_booking_normal_cost_fields">
			<p class="form-field">
				<label for="mwb_booking_unit_cost_input"><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
				<input type="number" name="mwb_booking_unit_cost_input" id="mwb_booking_unit_cost_input" value="<?php esc_attr_e( $this->setting_fields['mwb_booking_unit_cost_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<input type="checkbox" name="mwb_booking_unit_cost_multiply" id="mwb_booking_unit_cost_multiply" value="yes" <?php checked( 'yes', $this->setting_fields['mwb_booking_unit_cost_multiply'] ); ?>/>
				<label for="mwb_booking_unit_cost_multiply"><?php esc_html_e( 'Count per people', 'mwb-wc-bk' ); ?></label>
			</p>
			<p class="form-field">
				<label for="mwb_booking_base_cost_input"><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
				<input type="number" name="mwb_booking_base_cost_input" id="mwb_booking_base_cost_input" value="<?php esc_attr_e( $this->setting_fields['mwb_booking_base_cost_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<input type="checkbox" name="mwb_booking_base_cost_multiply" id="mwb_booking_base_cost_multiply" value="no" <?php checked( 'yes', $this->setting_fields['mwb_booking_base_cost_multiply'] ); ?>/>
				<label for="mwb_booking_base_cost_multiply"><?php esc_html_e( 'Count per people', 'mwb-wc-bk' ); ?></label>
			</p>
		</div>
	</div>
	<div id="mwb_booking_discount_cost" class="options_group">
		<div id="mwb_booking_discount_cost_heading">
			<h3><?php esc_html_e( 'Discount', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_booking_discount_cost_fields">
			<?php

				woocommerce_wp_radio(
					array(
						'label'       => __( 'Discount Type', 'mwb-wc-bk' ),
						'class'       => 'mwb_discount_type',
						'id'          => 'mwb_booking_monthly_discount_type',
						'value'       => $this->setting_fields['mwb_booking_monthly_discount_type'],
						'options'     => array(
							'weekly_discount'  => __( 'Weekly Discount', 'mwb-wc-bk' ),
							'monthly_discount' => __( 'Monthly Discount', 'mwb-wc-bk' ),
							'custom_discount'  => __( 'Custom Discount', 'mwb-wc-bk' ),
						),
						'desc_tip'    => true,
						'description' => __( 'Type of discount for the booking', 'mwb-wc-bk' ),
					)
				);
				woocommerce_wp_text_input(
					array(
						'id'                => 'mwb_booking_monthly_discount_input',
						'label'             => __( 'Monthly Discount', 'mwb-wc-bk' ),
						'description'       => __( 'Monthly discount in %', 'mwb-wc-bk' ),
						'value'             => $this->setting_fields['mwb_booking_monthly_discount_input'],
						'desc_tip'          => false,
						'type'              => 'number',
						'style'             => 'width: 30%; margin-right: 7px;',
						'custom_attributes' => array(
							'step' => '1',
							'min'  => '1',
						),
					)
				);
				woocommerce_wp_text_input(
					array(
						'id'                => 'mwb_booking_weekly_discount_input',
						'label'             => __( 'Weekly Discount', 'mwb-wc-bk' ),
						'description'       => __( 'Weekly discount in %', 'mwb-wc-bk' ),
						'value'             => $this->setting_fields['mwb_booking_weekly_discount_input'],
						'desc_tip'          => false,
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
				<label for="mwb_booking_custom_days_discount_input"><?php esc_html_e( 'Discount for custom days', 'mwb-wc-bk' ); ?></label>
				<input type="number" name="mwb_booking_custom_days_discount_input" id="mwb_booking_custom_days_discount_input" value="<?php esc_attr_e( $this->setting_fields['mwb_booking_custom_days_discount_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<?php esc_html_e( 'Discount for random days in %', 'mwb-wc-bk' ); ?>
				<input type="number" name="mwb_booking_custom_discount_days" id="mwb_booking_custom_discount_days" value="<?php esc_attr_e( $this->setting_fields['mwb_booking_custom_discount_days'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<label for="mwb_booking_custom_discount_days_input"><?php esc_html_e( 'Custom days', 'mwb-wc-bk' ); ?></label>
			</p>
		</div>
	</div>
	<div id="mwb_booking_added_costs" class="options_group">
		<div id="mwb_booking_added_costs_heading">
			<h3><?php esc_html_e( 'Added Costs', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_booking_added_costs_btn" style="margin-bottom: 10px;">
			<button class="btn btn-primary"><?php esc_html_e( 'New Added Cost', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
	<div id="mwb_booking_local_cost_rules" class="options_group">
		<div id="mwb_booking_local_cost_rules_heading">
			<h3><?php esc_html_e( 'Local Cost Rules', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_booking_local_cost_rules_btn" style="margin-bottom: 10px;">
			<button class="btn btn-primary"><?php esc_html_e( 'Add new Rule Cost', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
</div>