<?php
/**
 * The file that defines the core plugin api class
 *
 * A class definition that includes api's endpoints and functions used across the plugin
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/package/rest-api/version1
 */

/**
 * The core plugin  api class.
 *
 * This is used to define internationalization, api-specific hooks, and
 * endpoints for plugin.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/package/rest-api/version1
 */
class Mwb_Bookings_For_Woocommerce_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin api.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the merthods, and set the hooks for the api and
	 *
	 * @since    2.0.0
	 * @param   string $plugin_name    Name of the plugin.
	 * @param   string $version        Version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	/**
	 * Define endpoints for the plugin.
	 *
	 * Uses the Mwb_Bookings_For_Woocommerce_Rest_Api class in order to create the endpoint
	 * with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function mwb_mbfw_add_endpoint() {
		register_rest_route(
			'mbfw-route/v1',
			'/mbfw-dummy-data/',
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'mwb_mbfw_default_callback' ),
				'permission_callback' => array( $this, 'mwb_mbfw_default_permission_check' ),
			)
		);
	}


	/**
	 * Begins validation process of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $result   return rest response to server from where the endpoint hits.
	 * @since    2.0.0
	 */
	public function mwb_mbfw_default_permission_check( $request ) {

		// Add rest api validation for each request.
		$result = true;
		return $result;
	}


	/**
	 * Begins execution of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $mwb_mbfw_response   return rest response to server from where the endpoint hits.
	 * @since    2.0.0
	 */
	public function mwb_mbfw_default_callback( $request ) {

		require_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'package/rest-api/version1/class-mwb-bookings-for-woocommerce-api-process.php';
		$mwb_mbfw_api_obj     = new Mwb_Bookings_For_Woocommerce_Api_Process();
		$mwb_mbfw_resultsdata = $mwb_mbfw_api_obj->mwb_mbfw_default_process( $request );
		if ( is_array( $mwb_mbfw_resultsdata ) && isset( $mwb_mbfw_resultsdata['status'] ) && 200 == $mwb_mbfw_resultsdata['status'] ) {
			unset( $mwb_mbfw_resultsdata['status'] );
			$mwb_mbfw_response = new WP_REST_Response( $mwb_mbfw_resultsdata, 200 );
		} else {
			$mwb_mbfw_response = new WP_Error( $mwb_mbfw_resultsdata );
		}
		return $mwb_mbfw_response;
	}
}
