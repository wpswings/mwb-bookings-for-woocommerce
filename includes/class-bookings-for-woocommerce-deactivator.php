<?php
/**
 * Fired during plugin deactivation
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/includes
 */
class Bookings_For_Woocommerce_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 2.0.0
	 */
	public static function bookings_for_woocommerce_deactivate() {
		if ( get_option( 'bfw_radio_reset_license', false ) ) {
			$license_key = get_option( 'wps_bfw_license_key', false );
			$api_params  = array(
				'slm_action'        => 'slm_deactivate',
				'secret_key'        => BOOKINGS_FOR_WOOCOMMERCE_SPECIAL_SECRET_KEY,
				'license_key'       => $license_key,
				'registered_domain' => isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
				'item_reference'    => rawurlencode( BOOKINGS_FOR_WOOCOMMERCE_ITEM_REFERENCE ),
				'product_reference' => 'MWBPK-2965',
			);
			$query       = esc_url_raw(add_query_arg($api_params, BOOKINGS_FOR_WOOCOMMERCE_LICENSE_SERVER_URL));
			$response    = wp_remote_get(
				$query,
				array(
				'timeout' => 20,
				'sslverify' => false,
				)
			);

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( isset( $license_data->result ) && 'success' === $license_data->result ) {
				   delete_option('wps_bfw_license_check');
				   delete_option('wps_bfw_license_key');
			}
		}

	}

}
