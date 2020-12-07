<?php
/**
 * Create Booking.
 *
 * This file is used to markup the admin-facing aspect of Create Booking for the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="mwb_create_booking">
	<div id="mwb_create_booking_heading">
		<h1><?php esc_html_e( 'Create Booking', '' ); ?></h1>
	</div>
	<div id="mwb_create_booking_form">
		<form action="">
			<div>
				<select name="mwb_create_booking_user_select" id="mwb_create_booking_user_select" data-placeholder="<?php esc_html_e( 'Guest', 'mwb-wc-bk' ); ?>"></select>
			</div>
		</form>
	</div>
</div>

