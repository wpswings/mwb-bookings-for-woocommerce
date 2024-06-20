<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for calendar bookings tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$order_status = array(
	'' => '--Select order status--',
	'wc-on-hold' => 'On Hold',
	'wc-pending' => 'Pending',
	'wc-processing' => 'Processing',
	'wc-completed' => 'Completed',

);

?>
<div class="mbfw-secion-wrap">
	<div class="mbfw-booking-calender-notice"><?php esc_html_e( 'List of all upcoming Bookings', 'mwb-bookings-for-woocommerce' ); ?></div>
	<div class="wps_main_wrapper">
			
		
		<div class="wps_sub_main_wrapper">
			<select name="wps_order_status" id="wps_order_status" class="wps_order_status_">
				<?php foreach ( $order_status as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php echo ( 'select'  == $value) ? 'selected' : ''; ?>>
					<?php echo esc_attr( $label ); ?>
					<?php endforeach; ?>
			</select>
		
			
		</div>		
		<input type="button" class="button" name="wps_mbfw_filter_calender" id="wps_mbfw_filter_calender" value="<?php esc_html_e( 'Filter', 'mwb-bookings-for-woocommerce' ); ?>">
		<input type="button" class="button" name="wps_mbfw_clear_calender" id="wps_mbfw_clear_calender" value="<?php esc_html_e( 'Clear', 'mwb-bookings-for-woocommerce' ); ?>">
		<?php wp_nonce_field( 'admin_calender_data', 'mwb_calender_nonce' ); ?>
		
	</div>
	<div id="mwb-mbfw-booking-calendar"></div>
</div>
