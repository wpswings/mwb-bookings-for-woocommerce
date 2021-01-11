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

global $product;
$product_meta = get_post_meta( $product->get_id() );
$product_data = array(
	'product_id' => $product->get_id(),
);
$setting_options = get_option( 'mwb_booking_settings_options' );
// print_r( $setting_options );
// echo '<pre>'; print_r( $product_meta ); echo '</pre>';

$booking_enabled    = ! empty( $setting_options['mwb_booking_setting_go_enable'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_go_enable'] ) ) : 'yes';
$service_enabled_go = ! empty( $setting_options['mwb_booking_setting_bo_service_enable'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_enable'] ) ) : 'no';
$show_service_cost  = ! empty( $setting_options['mwb_booking_setting_bo_service_cost'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_cost'] ) ) : 'no';
$show_service_desc  = ! empty( $setting_options['mwb_booking_setting_bo_service_desc'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_desc'] ) ) : 'no';

$service_enabled_lo = ! empty( $product_meta['mwb_services_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_services_enable_checkbox'][0] ) ) : 'no';


?>
<div id="mwb-wc-bk-service-section" class="mwb-wc-bk-form-section">
	<?php
	if ( 'yes' === $booking_enabled && 'yes' === $service_enabled ) {
		if ( 'yes' === $service_enabled_lo ) {
			?>

			<?php
		}
	}
	?>
</div>








