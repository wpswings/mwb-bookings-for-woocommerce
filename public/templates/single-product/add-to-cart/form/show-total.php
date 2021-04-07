<?php
/**
 * Show Totals for the booking.
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

$global_func = Mwb_Booking_Global_Functions::get_global_instance();

$setting_options = get_option( 'mwb_booking_settings_options', $global_func->booking_settings_tab_default_global_options() );

$total_check = ! empty( $setting_options['mwb_booking_setting_bo_service_total'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_total'] ) ) : 'no';
?>

<div id="mwb-wc-bk-total-section" class="mwb-wc-bk-form-section">
	<?php
	if ( 'yes' === $total_check ) {
		$style = 'display: block;';
	} else {
		$style = 'display: none;';
	}
	?>
	<div id="mwb-wc-bk-total-fields" style="<?php echo esc_html( $style ); ?>" >

	</div>
</div>










