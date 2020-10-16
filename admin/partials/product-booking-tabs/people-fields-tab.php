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
					'value'       => 'no',
					'description' => __( 'Enable if extra peoples are to be included per booking.', 'mwb-wc-bk' ),
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_min_people_per_booking',
					'label'             => __( 'Min People per booking', 'mwb-wc-bk' ),
					'description'       => __( 'Minimum number of peoples allowed per bookings.', 'mwb-wc-bk' ),
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
			woocommerce_wp_text_input(
				array(
					'id'                => 'mwb_max_people_per_booking',
					'label'             => __( 'Max People per booking', 'mwb-wc-bk' ),
					'description'       => __( 'Maximum number of peoples allowed per bookings.', 'mwb-wc-bk' ),
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
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_people_as_seperate_booking',
					'label'       => __( 'Allow Peoples as seperate booking', 'mwb-wc-bk' ),
					'value'       => 'no',
					'description' => __( 'Check if peoples are to be counted as seperate bookings.', 'mwb-wc-bk' ),
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_enable_people_types',
					'label'       => __( 'Enable people types', 'mwb-wc-bk' ),
					'value'       => 'no',
					'description' => __( 'If people types are to be created.', 'mwb-wc-bk' ),
				)
			);
			?>
	</div>
	<div id="mwb_enabled_people_type" class="options_group">
		<div id="mwb_people_type_heading">
			<h3><?php esc_html_e( 'People Types', 'mwb-wc-bk' ); ?></h3>
		</div>
		<div id="mwb_people_type_add" style="margin-bottom: 7px;">
			<button class="btn"><?php esc_html_e( 'Add New Type', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
</div>
