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

?>
<div class="mbfw-secion-wrap">
	<div class="mbfw-booking-calender-notice"><?php esc_html_e( 'List of all upcoming Bookings', 'mwb-bookings-for-woocommerce' ); ?></div>
	<div id="mwb-mbfw-booking-calendar"></div>
</div>
