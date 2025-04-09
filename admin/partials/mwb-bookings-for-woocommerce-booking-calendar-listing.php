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
$active_plugins = get_option( 'active_plugins' );

if ( in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', $active_plugins ) ) {
	$is_pro_active = 'yes';
} else {
	$is_pro_active = 'no';
}

	// Whitelist iframe and some safe attributes.
	$allowed_tags = array(
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
			'loading'         => true,
			'style'           => true,
			'scrolling'       => true,
		),
	);

	if ( 'yes' == $is_pro_active && 'yes' == get_option( 'wps_bfwp_enable_google_cal_booking' ) && ! empty( get_option( 'wps_bfwp_google_cal_iframe' ) ) ) { ?>
	<div><?php echo wp_kses( get_option( 'wps_bfwp_google_cal_iframe' ), $allowed_tags ); ?> </div>
		<?php
	} else {
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
					<option value="<?php echo esc_attr( $value ); ?>" <?php echo ( 'select' == $value ) ? 'selected' : ''; ?>>
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
	<?php } ?>
