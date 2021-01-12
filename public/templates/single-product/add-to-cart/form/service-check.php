<?php

/**
 * Check Service settings for the booking.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/templates/single-product/add-to-cart/form
 */

defined( 'ABSPATH' ) || exit;


$global_func = Mwb_Booking_Global_Functions::get_global_instance();

global $product;

$product_meta = get_post_meta( $product->get_id() );

$product_data = array(
	'product_id' => $product->get_id(),
);

$setting_options = get_option( 'mwb_booking_settings_options' );
// echo '<pre>'; print_r( $setting_options ); echo '</pre>';

$booking_enabled     = ! empty( $setting_options['mwb_booking_setting_go_enable'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_go_enable'] ) ) : 'yes';
$inc_service_enabled = ! empty( $setting_options['mwb_booking_setting_bo_inc_service_enable'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_inc_service_enable'] ) ) : 'no';
$show_service_cost   = ! empty( $setting_options['mwb_booking_setting_bo_service_cost'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_cost'] ) ) : 'no';
$show_service_desc   = ! empty( $setting_options['mwb_booking_setting_bo_service_desc'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_desc'] ) ) : 'no';

$service_enabled   = ! empty( $product_meta['mwb_services_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_services_enable_checkbox'][0] ) ) : 'no';
$included_services = ! empty( $product_meta['mwb_booking_services_select'][0] ) ? maybe_unserialize( sanitize_text_field( wp_unslash( $product_meta['mwb_booking_services_select'][0] ) ) ) : array();
// echo '<pre>'; print_r( $included_services ); echo '</pre>';
$mandatory_check = ! empty( $product_meta['mwb_services_mandatory_check'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_services_mandatory_check'][0] ) ) : 'customer_selected';

$service_meta = array();

foreach ( $included_services as $service_id ) {
	$service      = get_term_meta( $service_id );
	$service_term = get_term( $service_id );
	// echo '<pre>'; print_r( $service_term ); echo '</pre>';
	if ( 'yes' === $service['mwb_booking_ct_services_optional'][0] ) {
		$service['term_obj']                 = $service_term;
		$service_meta['included_services'][] = $service;
	} else {
		$service['term_obj']                   = $service_term;
		$service_meta['additional_services'][] = $service;
	}
}
echo '<pre>'; print_r( $service_meta ); echo '</pre>';

?>
<div id="mwb-wc-bk-service-section" class="mwb-wc-bk-form-section">
	<?php
	if ( 'yes' === $booking_enabled && 'yes' === $service_enabled ) {
		// echo "1st";
		?>
	<div id="mwb-wc-bk-service-field">
		<?php
		if ( 'yes' === $inc_service_enabled ) {
			?>
		<div id="mwb-wc-bk-inc-service-field">
			<label for=""><?php esc_html_e( 'Included Services', '' ); ?></label>
			<input type="hidden" id="mwb-wc-bk-service-input-hidden" class="mwb-wc-bk-form-input-hidden" data-hidden="<?php echo esc_html( htmlspecialchars( wp_json_encode( $service_meta ) ) ); ?>"> 
			<?php
			foreach ( $service_meta['included_services'] as $service_data ) {
				// echo '<pre>'; print_r( $service_data ); echo '</pre>';
				?>
			<label for="mwb-wc-bk-inc-service-field-<?php echo esc_html( $service_data['term_obj']->id ); ?>"><?php echo esc_html( $service_data['term_obj']->name ); ?></label>
				<?php
				if ( 'yes' === $show_service_cost ) {
					$global_func->mwb_booking_help_tip( esc_html( sprintf( '$ %d', $service_data['mwb_ct_booking_service_cost'][0] ) ) );
				}
			}
			?>
		</div>
			<?php
		}
		?>
		<div id="mwb-wc-bk-add-service-field">
			<label for=""><?php esc_html_e( 'Additional Services', '' ); ?></label>
			<?php
			foreach ( $service_meta['additional_services'] as $service_data ) {
				// echo '<pre>'; print_r( $service_data ); echo '</pre>';
				$service_price = $service_data['mwb_ct_booking_service_cost'][0];
				if ( 'yes' === $service_data['mwb_booking_ct_services_multiply_units'] ) {
					$service_price = $product_meta['mwb_booking_unit_duration'][0] * $service_data['mwb_ct_booking_service_cost'][0];
				}
				if( 'yes' === $service_data['mwb_booking_ct_services_multiply_people'] ) {
					$service_price = $product_meta['mwb_booking_unit_duration'][0] * $service_data['mwb_ct_booking_service_cost'][0];
				}
				?>
			<input type="checkbox" id="mwb-wc-bk-inc-service-field-<?php echo esc_html( $service_data['term_obj']->id ); ?>" class="mwb-wc-bk-form-input-services">
			<label for="mwb-wc-bk-inc-service-field-<?php echo esc_html( $service_data['term_obj']->id ); ?>"><?php echo esc_html( $service_data['term_obj']->name ); ?></label>
				<?php
				if ( 'yes' === $show_service_cost ) {
					$global_func->mwb_booking_help_tip( esc_html( sprintf( '$ %d', $service_data['mwb_ct_booking_service_cost'][0] ) ) );
				}
			}
			?>
		</div>
	</div>
			<?php
	}
	?>
</div>








