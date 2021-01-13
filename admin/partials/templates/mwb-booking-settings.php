<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set the global options for settings
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}

if ( isset( $_POST['mwb_booking_settings_save'] ) ) {

	// Nonce verification.
	check_admin_referer( 'mwb_booking_global_options_setting_nonce', 'mwb_booking_nonce' );

	$mwb_booking_setting_options = array();

	$mwb_booking_setting_options['mwb_booking_setting_go_enable']             = ! empty( $_POST['mwb_booking_setting_go_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_go_enable'] ) ) : 'no';
	$mwb_booking_setting_options['mwb_booking_setting_go_complete_status']    = ! empty( $_POST['mwb_booking_setting_go_complete_status'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_go_complete_status'] ) ) : '';
	$mwb_booking_setting_options['mwb_booking_setting_go_reject']             = ! empty( $_POST['mwb_booking_setting_go_reject'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_go_reject'] ) ) : '';
	$mwb_booking_setting_options['mwb_booking_setting_bo_inc_service_enable'] = ! empty( $_POST['mwb_booking_setting_bo_inc_service_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_bo_inc_service_enable'] ) ) : 'no';
	$mwb_booking_setting_options['mwb_booking_setting_bo_service_cost']       = ! empty( $_POST['mwb_booking_setting_bo_service_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_bo_service_cost'] ) ) : 'no';
	$mwb_booking_setting_options['mwb_booking_setting_bo_service_desc']       = ! empty( $_POST['mwb_booking_setting_bo_service_desc'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_bo_service_desc'] ) ) : 'no';
	$mwb_booking_setting_options['mwb_booking_setting_bo_service_total']      = ! empty( $_POST['mwb_booking_setting_bo_service_total'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_booking_setting_bo_service_total'] ) ) : 'no';

	update_option( 'mwb_booking_settings_options', $mwb_booking_setting_options );
}

$mwb_booking_settings = get_option( 'mwb_booking_settings_options', $this->booking_settings_tab_default_global_options() );

?>
<!-- For Global options Setting -->
<form action="" method="POST">
	<div class="mwb_booking_global_options_setting_table woocommerce">
		<table class="form-table mwb_general_options_setting" >
			<tbody>

				<!-- Nonce field here. -->
				<?php
				wp_nonce_field( 'mwb_booking_global_options_setting_nonce', 'mwb_booking_nonce' );
				?>

				<!-- General options.-->
				<div id="mwb_booking_go_setting_name_heading">
					<h2><?php echo esc_html__( 'General Options', 'mwb-wc-bk' ); ?></h2>
				</div>

				<!-- General Options Fields start.-->
				<tr valign="top">

					<th scope="row" class="">
						<label for="mwb_booking_setting_go_enable_input"><?php esc_html_e( 'Enable/Disable Booking', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="checkbox" id="mwb_booking_setting_go_enable_input" name="mwb_booking_setting_go_enable" value="yes" <?php checked( 'yes', $mwb_booking_settings['mwb_booking_setting_go_enable'] ); ?> class="" >
						<p><?php esc_html_e( 'On and Off switch for Booking.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
				<tr valign="top">

					<th scope="row" class="">
						<label for="mwb_booking_setting_go_complete_input"><?php esc_html_e( 'Change Status to Complete after days', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="number" id="mwb_booking_setting_go_complete_input" name="mwb_booking_setting_go_complete_status" value="<?php echo esc_html( $mwb_booking_settings['mwb_booking_setting_go_complete_status'] ); ?>" class="" step="1" min="1">
						<p><?php esc_html_e( 'When this limit is reached, paid Bookings will be set to Completed automatically when the End Date exceeds the specified number of days.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
				<tr valign="top">

					<th scope="row" class="">
						<label for="mwb_booking_setting_go_reject_input"><?php esc_html_e( 'Reject Unpaid Booking after days', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="number" id="mwb_booking_setting_go_reject_input" name="mwb_booking_setting_go_reject" value="<?php echo esc_html( $mwb_booking_settings['mwb_booking_setting_go_reject'] ); ?>" class="" step="1" min="1">
						<p><?php esc_html_e( 'When this limit is reached, unpaid Bookings will be Rejected automatically when the End Date exceeds the specified number of days.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table mwb_booking_options_setting">
			<tbody>
				<!-- General options.-->
				<div id="mwb_booking_bo_setting_name_heading">
					<h2><?php echo esc_html__( 'Booking-Form Options', 'mwb-wc-bk' ); ?></h2>
				</div>
				<!-- General Options Fields start.-->
				<tr valign="top">

					<th scope="row" class="">
						<label for="mwb_booking_setting_bo_inc_service_enable_input"><?php esc_html_e( 'Show Included Services', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="checkbox" id="mwb_booking_setting_bo_inc_service_enable_input" name="mwb_booking_setting_bo_inc_service_enable" value="yes" <?php checked( 'yes', $mwb_booking_settings['mwb_booking_setting_bo_inc_service_enable'] ); ?> class="" >
						<p><?php esc_html_e( 'If enabled, included services are shown in the form.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
				<tr valign="top">

					<th scope="row" class="">
						<label for="mwb_booking_setting_bo_service_cost_input"><?php esc_html_e( 'Show Service Cost', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="checkbox" id="mwb_booking_setting_bo_service_cost_input" name="mwb_booking_setting_bo_service_cost" value="yes" <?php checked( 'yes', $mwb_booking_settings['mwb_booking_setting_bo_service_cost'] ); ?> class="" >
						<p><?php esc_html_e( 'If enabled, Service Cost is shown in the form.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="mwb_booking_setting_bo_service_desc_input"><?php esc_html_e( 'Show Service Description', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="checkbox" id="mwb_booking_setting_bo_service_desc_input" name="mwb_booking_setting_bo_service_desc" value="yes" <?php checked( 'yes', $mwb_booking_settings['mwb_booking_setting_bo_service_desc'] ); ?> class="" >
						<p><?php esc_html_e( 'If enabled, Service description is shown in the form.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
				<tr valign="top">

					<th scope="row" class="">
						<label for="mwb_booking_setting_bo_service_total_input"><?php esc_html_e( 'Show Totals', 'mwb-wc-bk' ); ?></label>
					</th>

					<td class="forminp forminp-text">
						<input type="checkbox" id="mwb_booking_setting_bo_service_total_input" name="mwb_booking_setting_bo_service_total" value="yes" <?php checked( 'yes', $mwb_booking_settings['mwb_booking_setting_bo_service_total'] ); ?> class="" >
						<p><?php esc_html_e( 'If enabled, totals are shown in the form.', 'mwb-wc-bk' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Save Settings -->
	<p class="submit">
		<input type="submit" value="<?php esc_html_e( 'Save Changes', 'mwb-wc-bk' ); ?>" class="button-primary woocommerce-save-button" name="mwb_booking_settings_save" id="mwb_booking_global_settings_save" >
	</p>
</form>
