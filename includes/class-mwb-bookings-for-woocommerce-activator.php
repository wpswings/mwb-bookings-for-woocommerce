<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */
class Mwb_Bookings_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function mwb_bookings_for_woocommerce_activate() {
		if ( ! get_option( 'mwb_mbfw_is_plugin_enable' ) ) {
			update_option( 'mwb_mbfw_is_plugin_enable', 'yes' );
			update_option( 'mwb_mbfw_is_booking_enable', 'yes' );
			update_option( 'mwb_mbfw_is_show_included_service', 'yes' );
			update_option( 'mwb_mbfw_is_show_totals', 'yes' );
			update_option( 'mwb_mbfw_daily_start_time', '05:24' );
			update_option( 'mwb_mbfw_daily_end_time', '23:26' );
		}
	}
}
