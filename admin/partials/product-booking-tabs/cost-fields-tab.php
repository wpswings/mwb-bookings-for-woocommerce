<?php
/**
 * MWB Booking Product Cost Fields Tab
 *
 * @package MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/admin/partials
 */

?>
<div id="mwb_booking_cost_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_booking_normal_cost" class="options_group">
		<div id="mwb_booking_normal_cost_heading">
			<h3><?php esc_html_e( 'Normal Cost', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>
		<div id="mwb_booking_normal_cost_fields">
			<p class="form-field unit-cost">
				<label for="mwb_booking_unit_cost_input"><?php esc_html_e( 'Unit Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
				<input type="number" name="mwb_booking_unit_cost_input" id="mwb_booking_unit_cost_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_unit_cost_input'] ); ?>" step="1" >
				<input type="checkbox" name="mwb_booking_unit_cost_multiply" id="mwb_booking_unit_cost_multiply" value="yes" <?php checked( 'yes', $this->setting_fields['mwb_booking_unit_cost_multiply'] ); ?>/>
				<label for="mwb_booking_unit_cost_multiply"><?php esc_html_e( 'Count per people', 'mwb-bookings-for-woocommerce' ); ?></label>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Unit cost is the main cost for your booking', 'mwb-bookings-for-woocommerce' ) ); ?>
			</p>
			<p class="form-field base-cost">
				<label for="mwb_booking_base_cost_input"><?php esc_html_e( 'Base Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
				<input type="number" name="mwb_booking_base_cost_input" id="mwb_booking_base_cost_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_base_cost_input'] ); ?>" step="1" min="1" >
				<input type="checkbox" name="mwb_booking_base_cost_multiply" id="mwb_booking_base_cost_multiply" value="yes" <?php checked( 'yes', $this->setting_fields['mwb_booking_base_cost_multiply'] ); ?>/>
				<label for="mwb_booking_base_cost_multiply"><?php esc_html_e( 'Count per people', 'mwb-bookings-for-woocommerce' ); ?></label>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Base cost is the base rental for the booking', 'mwb-bookings-for-woocommerce' ) ); ?>
			</p>
			<p class="form-field extra-cost">
				<label for="mwb_booking_extra_cost_input"><?php esc_html_e( 'Extra Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
				<input type="number" id="mwb_booking_extra_cost_input" name="mwb_booking_extra_cost_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_extra_cost_input'] ); ?>" step="1" min="1">
				<label for="mwb_booking_extra_cost_people_input"><?php esc_html_e( 'for every', 'mwb-bookings-for-woocommerce' ); ?></label>
				<input type="number" id="mwb_booking_extra_cost_people_input" name="mwb_booking_extra_cost_people_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_extra_cost_people_input'] ); ?>" step="1" min="1" >
				<label for="mwb_booking_extra_cost_people_input"><?php esc_html_e( 'peoples', 'mwb-bookings-for-woocommerce' ); ?></label>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Extra cost is the cost for x extra peoples added to booking', 'mwb-bookings-for-woocommerce' ) ); ?>
			</p>
		</div>
	</div>
	<div id="mwb_booking_discount_cost" class="options_group mwb-bookings__pro">
		<div id="mwb_booking_discount_cost_heading">
			<h3><?php esc_html_e( 'Discount', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>
		<div id="mwb_booking_discount_cost_fields">
			<div id="mwb_booking_discount_type_field" >
				<?php
				woocommerce_wp_radio(
					array(
						'label'       => __( 'Discount Type', 'mwb-bookings-for-woocommerce' ),
						'class'       => 'mwb_discount_type',
						'id'          => 'mwb_booking_cost_discount_type',
						'value'       => $this->setting_fields['mwb_booking_cost_discount_type'],
						'options'     => array(
							'none'             => __( 'No Discount', 'mwb-bookings-for-woocommerce' ),
							'weekly_discount'  => __( 'Weekly Discount', 'mwb-bookings-for-woocommerce' ),
							'monthly_discount' => __( 'Monthly Discount', 'mwb-bookings-for-woocommerce' ),
							'custom_discount'  => __( 'Custom Discount', 'mwb-bookings-for-woocommerce' ),
						),
						'desc_tip'    => true,
						'description' => __( 'Type of discount for the booking', 'mwb-bookings-for-woocommerce' ),
					)
				);
				?>
			</div>
			<div id="mwb_booking_monthly_discount_field" style="display: none;">          <!-- Mandatory Inline CSS. -->
				<?php
				woocommerce_wp_text_input(
					array(
						'id'                => 'mwb_booking_monthly_discount_input',
						'label'             => __( 'Monthly Discount', 'mwb-bookings-for-woocommerce' ),
						'description'       => __( 'Monthly discount in %', 'mwb-bookings-for-woocommerce' ),
						'value'             => $this->setting_fields['mwb_booking_monthly_discount_input'],
						'desc_tip'          => false,
						'type'              => 'number',
						'style'             => 'width: 30%; margin-right: 7px;',           // Style parameter of the function.
						'custom_attributes' => array(
							'step' => '1',
							'min'  => '1',
						),
					)
				);
				?>
			</div>
			<div id="mwb_booking_weekly_discount_field" style="display: none;">           <!-- Mandatory Inline CSS. -->                     
				<?php
				woocommerce_wp_text_input(
					array(
						'id'                => 'mwb_booking_weekly_discount_input',
						'label'             => __( 'Weekly Discount', 'mwb-bookings-for-woocommerce' ),
						'description'       => __( 'Weekly discount in %', 'mwb-bookings-for-woocommerce' ),
						'value'             => $this->setting_fields['mwb_booking_weekly_discount_input'],
						'desc_tip'          => false,
						'type'              => 'number',
						'style'             => 'width: 30%; margin-right: 7px;',          // Style parameter of the function.
						'custom_attributes' => array(
							'step' => '1',
							'min'  => '1',
						),
					)
				);
				?>
			</div>
			<div id="mwb_booking_custom_discount_field" style="display: none;">           <!-- Mandatory Inline CSS. -->                     
				<p class="form-field">
					<label for="mwb_booking_custom_days_discount_input"><?php esc_html_e( 'Discount for custom days', 'mwb-bookings-for-woocommerce' ); ?></label>
					<input type="number" name="mwb_booking_custom_days_discount_input" id="mwb_booking_custom_days_discount_input" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_custom_days_discount_input'] ); ?>" step="1" min="1" >
					<?php esc_html_e( 'Discount for random days in %', 'mwb-bookings-for-woocommerce' ); ?>
					<input type="number" name="mwb_booking_custom_discount_days" id="mwb_booking_custom_discount_days" value="<?php echo esc_attr( $this->setting_fields['mwb_booking_custom_discount_days'] ); ?>" step="1" min="1" >
					<label for="mwb_booking_custom_discount_days_input"><?php esc_html_e( 'Custom days', 'mwb-bookings-for-woocommerce' ); ?></label>
				</p>
			</div>
		</div>
	</div>
	<div id="mwb_booking_added_costs" class="options_group">
		<div id="mwb_booking_added_costs_heading">
			<h3><?php esc_html_e( 'Added Costs', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>
		<div id="mwb_booking_added_cost_select_field">
			<p class="form-field">
				<label for="mwb_booking_added_cost_select_search"><?php esc_html_e( 'Add Extra Costs', 'mwb-bookings-for-woocommerce' ); ?></label>
				<select id="mwb_booking_added_cost_select_search" multiple ='multiple' name="mwb_booking_added_cost_select[]" data-placeholder="<?php esc_html_e( 'Select Extra Costs', 'mwb-bookings-for-woocommerce' ); ?>" style="width: 40%;">                                     <!-- Mandatory Inline CSS. --> 
					<?php
					if ( ! empty( $this->setting_fields['mwb_booking_added_cost_select'] ) ) {
						$selected_costs = is_array( $this->setting_fields['mwb_booking_added_cost_select'] ) ? array_map( 'absint', $this->setting_fields['mwb_booking_added_cost_select'] ) : null;
						foreach ( apply_filters( 'mwb_wc_bk_added_costs_select', $selected_costs ) as $cost_id ) {
							$cost_name = get_term( $cost_id )->name;
							?>
							<option value="<?php echo esc_html( $cost_id ); ?>" selected="selected"><?php echo( esc_html( $cost_name ) . '(#' . esc_html( $cost_id ) . ')' ); ?></option>
							<?php
						}
					}
					?>
				</select>
				<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Add Extra Costs you want to include in booking', 'mwb-bookings-for-woocommerce' ) ); ?>
			</p>
		</div>
		<div id="mwb_booking_added_costs_btn" >
			<button class="btn btn-primary"><a href="edit-tags.php?taxonomy=mwb_ct_costs&post_type=mwb_cpt_booking" target="blank"><?php esc_html_e( 'New Added Cost', 'mwb-bookings-for-woocommerce' ); ?></a></button>
		</div>
	</div>
	<div id="mwb_booking_local_cost_rules" class="options_group mwb-bookings__pro">
		<div id="mwb_booking_local_cost_rules_heading">
			<h3><?php esc_html_e( 'Local Cost Rules', 'mwb-bookings-for-woocommerce' ); ?></h3>
		</div>
		<div id="mwb_booking_local_cost_rules_btn" >                                                                                                                                                                                                           <!-- Mandatory Inline CSS. -->
			<button class="btn btn-primary"><?php esc_html_e( 'Add new Rule Cost', 'mwb-bookings-for-woocommerce' ); ?></button>
		</div>
	</div>
</div>

