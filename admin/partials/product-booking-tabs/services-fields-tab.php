<?php
/**
 * MWB Booking Product Service Fields Tab
 *
 * @package Mwb_Wc_Bk
 */

// if( isset( get_post_meta( 'mwb_booking' ) ) )
	$this->mwb_booking_setting_fields['enable_services']         = isset( $_POST['mwb_services_enable_checkbox'] ) ? sanitize_text_field( $_POST['mwb_services_enable_checkbox'] ) : 'no';
	$this->mwb_booking_setting_fields['booking_services']        = isset( $_POST['mwb_booking_services_select'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['mwb_booking_services_select'] ) ) : '';
	$this->mwb_booking_setting_fields['mandatory_service_check'] = isset( $_POST['mwb_services_mandatory_check'] ) ? sanitize_text_field( $_POST['mwb_services_mandatory_check'] ) : 'no';

?>
<div id="mwb_booking_services_data" class="panel woocommerce_options_panel show_if_mwb_booking">
	<div id="mwb_servives_heading">
		<h1><em><?php esc_html_e( 'Services', 'mwb-wc-bk' ); ?></em></h1>
	</div>
	<div id="mwb_booking_servives_fields" class="options_group">
	<?php
		woocommerce_wp_checkbox(
			array(
				'id'          => 'mwb_services_enable_checkbox',
				'label'       => __( 'Enable/Disable Services', 'mwb-wc-bk' ),
				'value'       => 'no',
				'description' => __( 'Enable if services are to be included in booking.', 'mwb-wc-bk' ),
			)
		);
		?>
		<p class="form-field">
			<label for="mwb_booking_services_select_search"><?php esc_html_e( 'Add Services', 'mwb-wc-bk' ); ?></label>
			<select id="mwb_booking_services_select_search" multiple ='multiple' name="mwb_booking_services_select[]" data-placeholder="<?php esc_html_e( 'Add the services you want to include in booking', 'mwb-wc-bk' ); ?>">
				<option value="<?php echo 'services'; ?>" selected="selected">Services</option>
			</select>
			<?php mwb_booking_help_tip( esc_html__( 'Add services you want to include in booking', 'mwb-wc-bk' ) ); ?>
		</p>
		<?php
		woocommerce_wp_select(
			array(
				'id'          => 'mwb_services_mandatory_check',
				'label'       => __( 'Services are', 'mwb-wc-bk' ),
				'value'       => '',
				'options'     => array(
					'mandatory'         => __( 'Mandatory', 'mwb-wc-bk' ),
					'customer_selected' => __( 'Selected by Customer', 'mwb-wc-bk' ),
				),
				'description' => __( 'If services are mandatory in booking, enable this option.', 'mwb-wc-bk' ),
				'desc_tip'    => true,
			)
		);
		?>
		<div id="mwb_booking_service_add" style="margin-bottom: 7px;">
			<button class=""><?php esc_html_e( 'Add New Service', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
</div>
