<?php
/**
 * MWB Booking Product People Fields Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_people_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_people_heading">
		<h1><em><?php esc_html_e( 'Peoples', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_people_enable" class="options_group">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_people_enable_checkbox',
					'label'       => __( 'Enable/Disable People option', 'mwb-wc-bk' ),
					'value'       => $this->setting_fields['mwb_people_enable_checkbox'],
					'description' => __( 'Enable if extra peoples are to be included per booking.', 'mwb-wc-bk' ),
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_min_people_per_booking',
					'label'             => __( 'Min People per booking', 'mwb-wc-bk' ),
					'description'       => __( 'Minimum number of peoples allowed per bookings.', 'mwb-wc-bk' ),
					'value'             => $this->setting_fields['mwb_min_people_per_booking'],
					'desc_tip'          => true,
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
					'id'                => 'mwb_max_people_per_booking',
					'label'             => __( 'Max People per booking', 'mwb-wc-bk' ),
					'description'       => __( 'Maximum number of peoples allowed per bookings.', 'mwb-wc-bk' ),
					'value'             => $this->setting_fields['mwb_max_people_per_booking'],
					'desc_tip'          => true,
					'type'              => 'number',
					'style'             => 'width: 30%; margin-right: 7px;',
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '1',
					),
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_people_as_seperate_booking',
					'label'       => __( 'Allow Peoples as seperate booking', 'mwb-wc-bk' ),
					'value'       => $this->setting_fields['mwb_people_as_seperate_booking'],
					'description' => __( 'Check if peoples are to be counted as seperate bookings.', 'mwb-wc-bk' ),
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_enable_people_types',
					'label'       => __( 'Enable people types', 'mwb-wc-bk' ),
					'value'       => $this->setting_fields['mwb_enable_people_types'],
					'description' => __( 'If people types are to be created.', 'mwb-wc-bk' ),
				)
			);
			?>
	</div>
	<div id="mwb_add_people_type">
		<p class="form-field">
			<label for="mwb_booking_people_select_search"><?php esc_html_e( 'Add People Type', 'mwb-wc-bk' ); ?></label>
			<select id="mwb_booking_people_select_search" multiple ='multiple' name="mwb_booking_people_select[]" data-placeholder="<?php esc_html_e( 'Add the people type you want to include in booking', 'mwb-wc-bk' ); ?>">
				<?php
				if ( ! empty( $this->setting_fields['mwb_booking_people_select'] ) ) {
					$selected_people_type = is_array( $this->setting_fields['mwb_booking_people_select'] ) ? array_map( 'absint', $this->setting_fields['mwb_booking_people_select'] ) : null;
					// echo "<pre>";
					// print_r( $selected_services );
					// echo "</pre>";
					foreach ( $selected_people_type as $people_type_id ) {
						$people_type = get_term( $people_type_id )->name;
						?>
						<option value="<?php echo esc_html( $people_type_id ); ?>" selected="selected"><?php echo( esc_html( $people_type ) . '(#' . esc_html( $people_type_id ) . ')' ); ?></option>
						<?php
					}
				}
				?>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Add people type you want to include in booking', 'mwb-wc-bk' ) ); ?>
		</p>
	</div>

	<div id="mwb_enabled_people_type" class="options_group">
		<div id="mwb_people_type_heading">
			<h3><?php esc_html_e( 'People Types', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_people_type_add" style="margin-bottom: 7px;">
			<button class="btn"><a href="edit-tags.php?taxonomy=mwb_ct_people_type&post_type=mwb_cpt_booking" target="blank"><?php esc_html_e( 'Add New Type', 'mwb-wc-bk' ); ?></a></button>
		</div>
	</div>
</div>
