<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Global Functions Class For MWB Booking Plugin
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
			'sunday'    => __( 'Sunday', 'mwb-wc-bk' ),
			'monday'    => __( 'Monday', 'mwb-wc-bk' ),
			'tuesday'   => __( 'Tuesday', 'mwb-wc-bk' ),
			'wednesday' => __( 'Wednesday', 'mwb-wc-bk' ),
			'thursday'  => __( 'Thursday', 'mwb-wc-bk' ),
			'friday'    => __( 'Friday', 'mwb-wc-bk' ),
			'saturday'  => __( 'Saturday', 'mwb-wc-bk' ),
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
			$arr[ $i ] = __( 'Week-', 'mwb-wc-bk' ) . $i;
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
			'jan' => __( 'January', 'mwb-wc-bk' ),
			'feb' => __( 'February', 'mwb-wc-bk' ),
			'mar' => __( 'March', 'mwb-wc-bk' ),
			'apr' => __( 'April', 'mwb-wc-bk' ),
			'may' => __( 'May', 'mwb-wc-bk' ),
			'jun' => __( 'June', 'mwb-wc-bk' ),
			'jul' => __( 'July', 'mwb-wc-bk' ),
			'aug' => __( 'August', 'mwb-wc-bk' ),
			'sep' => __( 'September', 'mwb-wc-bk' ),
			'oct' => __( 'October', 'mwb-wc-bk' ),
			'nov' => __( 'November', 'mwb-wc-bk' ),
			'dec' => __( 'December', 'mwb-wc-bk' ),
		);
		apply_filters( 'mwb_booking_months', $arr );
		return $arr;
	}
}

