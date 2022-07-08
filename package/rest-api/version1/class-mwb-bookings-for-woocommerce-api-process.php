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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Mwb_Bookings_For_Woocommerce_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      2.0.0
	 * @package    Mwb_Bookings_For_Woocommerce
	 * @subpackage Mwb_Bookings_For_Woocommerce/includes
	 */
	class Mwb_Bookings_For_Woocommerce_Api_Process {

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
		 * @param   Array $mbfw_request  data of requesting headers and other information.
		 * @return  Array $mwb_mbfw_rest_response    returns processed data and status of operations.
		 */
		public function mwb_mbfw_default_process( $mbfw_request ) {
			$mwb_mbfw_rest_response = array();

			// Write your custom code here.

			$mwb_mbfw_rest_response['status'] = 200;
			$mwb_mbfw_rest_response['data'] = $mbfw_request->get_headers();
			return $mwb_mbfw_rest_response;
		}
	}
}
