<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Bookings_For_Woocommerce_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      2.0.0
	 * @package    Bookings_For_Woocommerce
	 * @subpackage Bookings_For_Woocommerce/includes
	 * @author     MakeWebBetter <makewebbetter.com>
	 */
	class Bookings_For_Woocommerce_Api_Process {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    2.0.0
		 */
		public function __construct() {

		}

		/**
		 * Define the function to process data for custom endpoint.
		 *
		 * @since    2.0.0
		 * @param   Array $bfw_request  data of requesting headers and other information.
		 * @return  Array $wps_bfw_rest_response    returns processed data and status of operations.
		 */
		public function wps_bfw_default_process( $bfw_request ) {
			$wps_bfw_rest_response = array();

			// Write your custom code here.

			$wps_bfw_rest_response['status'] = 200;
			$wps_bfw_rest_response['data'] = $bfw_request->get_headers();
			return $wps_bfw_rest_response;
		}
	}
}
