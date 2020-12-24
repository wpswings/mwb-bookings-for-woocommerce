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
}

