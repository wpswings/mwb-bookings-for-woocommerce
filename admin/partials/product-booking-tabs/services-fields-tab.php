<?php
/**
 * MWB Booking Product Service Fields Tab
 *
 * @package Mwb_Wc_Bk
 */

?>
<div id="mwb_booking_services_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<!-- <div id="mwb_servives_heading">
		<h1><em><?php // esc_html_e( 'Services', 'mwb-wc-bk' ); ?></em></h1>
	</div> -->
	<div id="mwb_booking_servives_fields" class="options_group">
	<?php
		woocommerce_wp_checkbox(
			array(
				'id'          => 'mwb_services_enable_checkbox',
				'label'       => __( 'Enable/Disable Services', 'mwb-wc-bk' ),
				'value'       => $this->setting_fields['mwb_services_enable_checkbox'],
				'description' => __( 'Enable if services are to be included in booking.', 'mwb-wc-bk' ),
			)
		);
		?>
		<p class="form-field">
			<label for="mwb_booking_services_select_search"><?php esc_html_e( 'Add Services', 'mwb-wc-bk' ); ?></label>
			<select id="mwb_booking_services_select_search" multiple ='multiple' name="mwb_booking_services_select[]" data-placeholder="<?php esc_html_e( 'Add the services you want to include in booking', 'mwb-wc-bk' ); ?>">
					<?php
					if ( ! empty( $this->setting_fields['mwb_booking_services_select'] ) ) {
						$selected_services = is_array( $this->setting_fields['mwb_booking_services_select'] ) ? array_map( 'absint', $this->setting_fields['mwb_booking_services_select'] ) : null;
						foreach ( apply_filters( 'mwb_wc_bk_services_select', $selected_services ) as $service_id ) {
							$service_name = get_term( $service_id )->name;
							?>
							<option value="<?php echo esc_html( $service_id ); ?>" selected="selected"><?php echo( esc_html( $service_name ) . '(#' . esc_html( $service_id ) . ')' ); ?></option>
							<?php
						}
					}
					?>
			</select>
			<?php $this->global_func->mwb_booking_help_tip( esc_html__( 'Add services you want to include in booking', 'mwb-wc-bk' ) ); ?>
		</p>
		<?php
		// woocommerce_wp_select(
		// 	array(
		// 		'id'          => 'mwb_services_mandatory_check',
		// 		'label'       => __( 'Services are', 'mwb-wc-bk' ),
		// 		'value'       => $this->setting_fields['mwb_services_mandatory_check'],
		// 		'options'     => array(
		// 			'mandatory'         => __( 'Mandatory', 'mwb-wc-bk' ),
		// 			'customer_selected' => __( 'Selected by Customer', 'mwb-wc-bk' ),
		// 		),
		// 		'description' => __( 'If services are mandatory in booking, enable this option.', 'mwb-wc-bk' ),
		// 		'desc_tip'    => true,
		// 	)
		// );
		?>
		<div id="mwb_booking_service_add" style="margin-bottom: 7px;">
			<button class=""><a href="edit-tags.php?taxonomy=mwb_ct_services&post_type=mwb_cpt_booking" target="blank"><?php esc_html_e( 'Add New Service', 'mwb-wc-bk' ); ?></a></button>
		</div>
	</div>
</div>
