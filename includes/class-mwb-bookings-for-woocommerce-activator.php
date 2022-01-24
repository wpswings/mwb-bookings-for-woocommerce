<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wpswings.com/
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
	 * Activator main method to update default values at plugin activation.
	 *
	 * @param boolean $network_wide either network activated or not.
	 * @since 2.0.0
	 * @return void
	 */
	public static function mwb_bookings_for_woocommerce_activate( $network_wide ) {
		global $wpdb;
		if ( is_multisite() && $network_wide ) {
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog($blog_id);
				( new self() )->mwb_bookings_for_woocommerce_update_default_value();
				restore_current_blog();
			}
			return;
		}
		( new self() )->mwb_bookings_for_woocommerce_update_default_value();
	}
	
	/**
	 * Update default value on plugin activation.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function mwb_bookings_for_woocommerce_update_default_value() {
		if ( ! get_option( 'mwb_mbfw_is_plugin_enable' ) ) {
			update_option( 'mwb_mbfw_is_plugin_enable', 'yes' );
			update_option( 'mwb_mbfw_is_booking_enable', 'yes' );
			update_option( 'mwb_mbfw_is_show_included_service', 'yes' );
			update_option( 'mwb_mbfw_is_show_totals', 'yes' );
			update_option( 'mwb_mbfw_daily_start_time', '05:24' );
			update_option( 'mwb_mbfw_daily_end_time', '23:26' );
		}
		if ( ! get_option( 'mwb_mbfw_plugin_setting_migrated' ) ) {
			( new self() )->mwb_migrate_old_plugin_settings();
		}
	}

	/**
	 * Migrate old plugin settings.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function mwb_migrate_old_plugin_settings() {
		$mwb_booking_old_settings = get_option( 'mwb_booking_settings_options' );
		if ( ! empty( $mwb_booking_old_settings ) && is_array( $mwb_booking_old_settings ) ) {
			update_option( 'mwb_mbfw_is_plugin_enable', 'yes' );
			update_option( 'mwb_mbfw_is_booking_enable', isset( $mwb_booking_old_settings['mwb_booking_setting_go_enable'] ) ? $mwb_booking_old_settings['mwb_booking_setting_go_enable'] : '' );
			update_option( 'mwb_mbfw_is_show_included_service', isset( $mwb_booking_old_settings['mwb_booking_setting_bo_inc_service_enable'] ) ? $mwb_booking_old_settings['mwb_booking_setting_bo_inc_service_enable'] : '' );
			update_option( 'mwb_mbfw_is_show_totals', isset( $mwb_booking_old_settings['mwb_booking_setting_bo_service_total'] ) ? $mwb_booking_old_settings['mwb_booking_setting_bo_service_total'] : '' );
			update_option( 'mwb_mbfw_daily_start_time', '05:24' );
			update_option( 'mwb_mbfw_daily_end_time', '23:26' );
		}
		update_option( 'mwb_mbfw_plugin_setting_migrated', 'yes' );
	}
}
