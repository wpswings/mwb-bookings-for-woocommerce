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
			<p class="form-field unit-cost">
				<label for="mwb_booking_unit_cost_input"><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
				<input type="number" name="mwb_booking_unit_cost_input" id="mwb_booking_unit_cost_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_unit_cost_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<input type="checkbox" name="mwb_booking_unit_cost_multiply" id="mwb_booking_unit_cost_multiply" value="yes" <?php checked( 'yes', $this->setting_fields['mwb_booking_unit_cost_multiply'] ); ?>/>
				<label for="mwb_booking_unit_cost_multiply"><?php esc_html_e( 'Count per people', 'mwb-wc-bk' ); ?></label>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Unit cost is main cost for your booking', 'mwb-wc-bk' ) ); ?>
			</p>
			<p class="form-field base-cost">
				<label for="mwb_booking_base_cost_input"><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
				<input type="number" name="mwb_booking_base_cost_input" id="mwb_booking_base_cost_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_base_cost_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<input type="checkbox" name="mwb_booking_base_cost_multiply" id="mwb_booking_base_cost_multiply" value="no" <?php checked( 'yes', $this->setting_fields['mwb_booking_base_cost_multiply'] ); ?>/>
				<label for="mwb_booking_base_cost_multiply"><?php esc_html_e( 'Count per people', 'mwb-wc-bk' ); ?></label>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Base cost is the base rental for the booking', 'mwb-wc-bk' ) ); ?>
			</p>
			<p class="form-field extra-cost">
				<label for="mwb_booking_extra_cost_input"><?php esc_html_e( 'Extra Cost', 'mwb-wc-bk' ); ?></label>
				<input type="number" id="mwb_booking_extra_cost_input" name="mwb_booking_extra_cost_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_extra_cost_input'] ); ?>" step="1" min="1">
				<label for="mwb_booking_extra_cost_people_input"><?php esc_html_e( 'for every', 'mwb-wc-bk' ); ?></label>
				<input type="number" id="mwb_booking_extra_cost_people_input" name="mwb_booking_extra_cost_people_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_extra_cost_people_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<label for="mwb_booking_extra_cost_people_input"><?php esc_html_e( 'peoples', 'mwb-wc-bk' ); ?></label>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Extra cost is the cost for x extra peoples added to booking', 'mwb-wc-bk' ) ); ?>
			</p>
		</div>
	</div>
	<div id="mwb_booking_discount_cost" class="options_group">
		<div id="mwb_booking_discount_cost_heading">
			<h3><?php esc_html_e( 'Discount', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_booking_discount_cost_fields">
			<div id="mwb_booking_discount_type_field" >
				<?php
				woocommerce_wp_radio(
					array(
						'label'       => __( 'Discount Type', 'mwb-wc-bk' ),
						'class'       => 'mwb_discount_type',
						'id'          => 'mwb_booking_cost_discount_type',
						'value'       => $this->setting_fields['mwb_booking_monthly_discount_type'],
						'options'     => array(
							'none'             => __( 'No Discount', 'mwb-wc-bk' ),
							'weekly_discount'  => __( 'Weekly Discount', 'mwb-wc-bk' ),
							'monthly_discount' => __( 'Monthly Discount', 'mwb-wc-bk' ),
							'custom_discount'  => __( 'Custom Discount', 'mwb-wc-bk' ),
						),
						'desc_tip'    => true,
						'description' => __( 'Type of discount for the booking', 'mwb-wc-bk' ),
					)
				);
				?>
			</div>
			<div id="mwb_booking_monthly_discount_field" style="display: none;">
				<?php
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
				?>
			</div>
			<div id="mwb_booking_weekly_discount_field" style="display: none;">
				<?php
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
			</div>
			<div id="mwb_booking_custom_discount_field" style="display: none;">
				<p class="form-field">
					<label for="mwb_booking_custom_days_discount_input"><?php esc_html_e( 'Discount for custom days', 'mwb-wc-bk' ); ?></label>
					<input type="number" name="mwb_booking_custom_days_discount_input" id="mwb_booking_custom_days_discount_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_custom_days_discount_input'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
					<?php esc_html_e( 'Discount for random days in %', 'mwb-wc-bk' ); ?>
					<input type="number" name="mwb_booking_custom_discount_days" id="mwb_booking_custom_discount_days" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_custom_discount_days'] ); ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
					<label for="mwb_booking_custom_discount_days_input"><?php esc_html_e( 'Custom days', 'mwb-wc-bk' ); ?></label>
				</p>
			</div>
		</div>
	</div>
	<div id="mwb_booking_added_costs" class="options_group">
		<div id="mwb_booking_added_costs_heading">
			<h3><?php esc_html_e( 'Added Costs', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_booking_added_cost_select_field">
			<p class="form-field">
				<label for="mwb_booking_added_cost_select_search"><?php esc_html_e( 'Add Extra Costs', 'mwb-wc-bk' ); ?></label>
				<select id="mwb_booking_added_cost_select_search" multiple ='multiple' name="mwb_booking_added_cost_select[]" data-placeholder="<?php esc_html_e( 'Add the Extra Costs you want to include in booking', 'mwb-wc-bk' ); ?>">
					<?php
					if ( ! empty( $this->setting_fields['mwb_booking_added_cost_select'] ) ) {
						$selected_costs = is_array( $this->setting_fields['mwb_booking_added_cost_select'] ) ? array_map( 'absint', $this->setting_fields['mwb_booking_added_cost_select'] ) : null;
						foreach ( $selected_costs as $cost_id ) {
							$cost_name = get_term( $cost_id )->name;
							?>
							<option value="<?php echo esc_html( $cost_id ); ?>" selected="selected"><?php echo( esc_html( $cost_name ) . '(#' . esc_html( $cost_id ) . ')' ); ?></option>
							<?php
						}
					}
					?>
				</select>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Add Extra Costs you want to include in booking', 'mwb-wc-bk' ) ); ?>
			</p>
		</div>
		<div id="mwb_booking_added_costs_btn" style="margin-bottom: 10px;">
			<button class="btn btn-primary"><a href="edit-tags.php?taxonomy=mwb_ct_costs&post_type=mwb_cpt_booking" target="blank"><?php esc_html_e( 'New Added Cost', 'mwb-wc-bk' ); ?></a></button>
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
