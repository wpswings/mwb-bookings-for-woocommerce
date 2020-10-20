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

/**
 * Function for adding description as a tooltip.
 *
 * @param   string $description        Tooltip message.
 *
 * @since    1.0.0
 */
function mwb_booking_help_tip( $description = '' ) {

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
