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

if ( isset( $_POST['mwb_create_booking_submit_button'] ) ) {

	$user_id            = isset( $_POST['mwb_create_booking_user_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_user_select'] ) ) : '';
	$product_id         = isset( $_POST['mwb_create_booking_product_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_product_select'] ) ) : '';
	$assign_order       = isset( $_POST['mwb_create_booking_assign_order'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_assign_order'] ) ) : '';
	$order_id_to_assign = isset( $_POST['mwb_create_booking_order_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_order_select'] ) ) : '';

	$create_booking = array(
		'user_id'      => $user_id,
		'product_id'   => $product_id,
		'assign_order' => $assign_order,
		'order_id'     => $order_id_to_assign,
	);

}

?>
<form id="mwb_create_booking_form" method="POST" action="">
	<div id="mwb_create_booking">
		<div id="mwb_create_booking_heading">
			<h1><?php esc_html_e( 'Create Booking', 'mwb-wc-bk' ); ?></h1>
		</div>
		<div id="mwb_create_booking_fields">
			<table>
				<tbody>
					<tr valign="top">
						<th scope="row" class="">
							<label><h3><?php esc_html_e( 'User', 'mwb-wc-bk' ); ?></h3></label>
						</th>
						<td class="forminp forminp-text">
							<select name="mwb_create_booking_user_select" id="mwb_create_booking_user_select" data-placeholder="<?php esc_html_e( 'Guest', 'mwb-wc-bk' ); ?>">
								<option value="<?php // echo esc_html( $create_booking['user_id'] ); ?>" selected="selected"><?php //echo esc_html( $create_booking['user_id'] ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><h3><?php esc_html_e( 'Product', 'mwb-wc-bk' ); ?></h3></label>
						</th>
						<td class="forminp forminp-text">
							<select name="mwb_create_booking_product_select" id="mwb_create_booking_product_select" data-placeholder="<?php esc_html_e( 'Choose Product', 'mwb-wc-bk' ); ?>">
								<option value="" ></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><h3><?php esc_html_e( 'Order', 'mwb-wc-bk' ); ?></h3></label>
						</th>
						<td class="forminp forminp-text">
							<input type="radio" class="mwb_create_booking_assign_to_order" name="mwb_create_booking_assign_order" value="assign_to_existing_order" <?php //checked( 'specific', $mwb_availability_rule_type[ $count ] ); ?> >
							<label><?php esc_html_e( 'Assign to an existing order', 'mwb-wc-bk' ); ?></label><br>
							<input type="radio" class="mwb_create_booking_assign_new_order" name="mwb_create_booking_assign_order" value="assign_new_order" <?php // checked( 'generic', $mwb_availability_rule_type[ $count ] ); ?>>
							<label><?php esc_html_e( 'Create New Order', 'mwb-wc-bk' ); ?></label><br>
							</td>
						<td class="forminp forminp-text">
							<select name="mwb_create_booking_order_select" id="mwb_create_booking_order_select" data-placeholder="<?php esc_html_e( 'Order', 'mwb-wc-bk' ); ?>">
								<option value=""></option>
							</select>
						</td>
					</tr>
					<tr valign="top" class="product_ajax">
						<th scope="row" class="">
							<label><h3><?php esc_html_e( 'Details', 'mwb-wc-bk' ); ?></h3></label>
						</th>
						<td class="forminp forminp-text">
							<p><?php esc_html_e( 'Select the Product above to see the details', '' ); ?></p>
						</td>
							<?php
								$product_meta    = get_post_meta( $product_id );
								$global_settings = get_option( 'mwb_booking_settings_options' );
							?>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="mwb_create_booking_submit">
			<input type="submit" id="mwb_create_booking_submit_button" name="mwb_create_booking_submit_button" class="button-primary button" value="<?php esc_html_e( 'Create Booking', 'mwb-wc-bk' ); ?>">
		</div>
	</div>
</form>

