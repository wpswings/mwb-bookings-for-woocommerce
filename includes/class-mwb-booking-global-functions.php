<?php
/**
 * The Gobal functionality for the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global Functions Class For MWB Booking Plugin.
 *
 * This class defines the global functions to be used anywhere in the plugin.
 *
 * @since      1.0.0
 * @package    MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/includes
 * @author     MakeWebBetter <plugins@makewebbetter.com>
 */
class Mwb_Booking_Global_Functions {

	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	public static $instance;

	/**
	 * Undocumented function
	 */
	public function __construct() {

		self::$instance = $this;
	}

	/**
	 * Undocumented function
	 *
	 * @return obj
	 */
	public static function get_global_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Function for adding description as a tooltip.
	 *
	 * @param   string $description        Tooltip message.
	 *
	 * @since    1.0.0
	 */
	public function mwb_booking_help_tip( $description = '' ) {

		// Run only if description message is present.
		if ( ! empty( $description ) ) {

			$allowed_html = array(
				'span' => array(
					'class'    => array(),
					'data-tip' => array(),
				),
			);

			echo wp_kses( wc_help_tip( $description ), $allowed_html );
		}
	}

	/**
	 * Search weekdays
	 *
	 * @return array arr Weekdays.
	 */
	public function booking_search_weekdays() {
		$arr = array(
			'sunday'    => __( 'Sunday', 'mwb-bookings-for-woocommerce' ),
			'monday'    => __( 'Monday', 'mwb-bookings-for-woocommerce' ),
			'tuesday'   => __( 'Tuesday', 'mwb-bookings-for-woocommerce' ),
			'wednesday' => __( 'Wednesday', 'mwb-bookings-for-woocommerce' ),
			'thursday'  => __( 'Thursday', 'mwb-bookings-for-woocommerce' ),
			'friday'    => __( 'Friday', 'mwb-bookings-for-woocommerce' ),
			'saturday'  => __( 'Saturday', 'mwb-bookings-for-woocommerce' ),
		);
		apply_filters( 'mwb_booking_weekdays', $arr );
		return $arr;
	}

	/**
	 * Search weeks
	 *
	 * @return array arr Weekdays.
	 */
	public function booking_search_weeks() {

		for ( $i = 1; $i <= 52; $i++ ) {
			$arr[ $i ] = __( 'Week-', 'mwb-bookings-for-woocommerce' ) . $i;
		}
		apply_filters( 'mwb_booking_weeks', $arr );
		return $arr;
	}

	/**
	 * Global Cost Calculation Symbols
	 *
	 * @return array arr
	 */
	public function booking_global_cost_cal() {
		$arr = array(
			'add'      => '+',
			'subtract' => '-',
			'multiply' => '*',
			'divide'   => '/',
		);
		apply_filters( 'mwb_booking_cost_cal_symbols', $arr );
		return $arr;
	}

	/**
	 * Search Months
	 *
	 * @return array arr Weekdays.
	 */
	public function booking_months() {
		$arr = array(
			'jan' => __( 'January', 'mwb-bookings-for-woocommerce' ),
			'feb' => __( 'February', 'mwb-bookings-for-woocommerce' ),
			'mar' => __( 'March', 'mwb-bookings-for-woocommerce' ),
			'apr' => __( 'April', 'mwb-bookings-for-woocommerce' ),
			'may' => __( 'May', 'mwb-bookings-for-woocommerce' ),
			'jun' => __( 'June', 'mwb-bookings-for-woocommerce' ),
			'jul' => __( 'July', 'mwb-bookings-for-woocommerce' ),
			'aug' => __( 'August', 'mwb-bookings-for-woocommerce' ),
			'sep' => __( 'September', 'mwb-bookings-for-woocommerce' ),
			'oct' => __( 'October', 'mwb-bookings-for-woocommerce' ),
			'nov' => __( 'November', 'mwb-bookings-for-woocommerce' ),
			'dec' => __( 'December', 'mwb-bookings-for-woocommerce' ),
		);
		apply_filters( 'mwb_booking_months', $arr );
		return $arr;
	}

	/**
	 * Settings tab Default Global Options
	 *
	 * @return array
	 */
	public function booking_settings_tab_default_global_options() {

		return array(
			'mwb_booking_setting_go_enable'             => 'yes',
			'mwb_booking_setting_go_confirm_status'     => '',
			'mwb_booking_setting_go_reject'             => '',
			'mwb_booking_setting_bo_inc_service_enable' => 'yes',
			'mwb_booking_setting_bo_service_cost'       => 'yes',
			'mwb_booking_setting_bo_service_desc'       => 'yes',
			'mwb_booking_setting_bo_service_total'      => 'yes',
		);
	}
}

