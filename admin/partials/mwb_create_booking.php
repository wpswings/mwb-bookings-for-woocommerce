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

// $loop   = new WP_Query(
// 	array(
// 		'post_type'      => 'shop_order',
// 		'post_status'    => array_keys( wc_get_order_statuses() ),
// 		'posts_per_page' => -1,
// 	)
// );
// if ( $loop->have_posts() ) {
// 	while ( $loop->have_posts() ) {
// 		$loop->the_post();

// 		// The order ID.
// 		$order_id = $loop->post->ID;

// 		$order = wc_get_order( $order_id );

// 		if ( ! empty( $order ) ) {
// 			$return[] = array( $order->ID, '' );
// 		}
// 	}
// }
// print_r($return);

// if ( isset( $_POST['mwb_create_booking_submit_button'] ) ) {

// 	$user_id         = isset( $_POST['mwb_create_booking_user_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_user_select'] ) ) : '';
// 	$product         = isset( $_POST['mwb_create_booking_product_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_product_select'] ) ) : '';
// 	$assign_order    = isset( $_POST['mwb_create_booking_assign_order'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_assign_order'] ) ) : '';
// 	$order_to_assign = isset( $_POST['mwb_create_booking_order_select'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_create_booking_order_select'] ) ) : '';
// 	// $new_post = array(
// 	// 	'post_type'      => 'mwb_cpt_booking',
// 	// 	'post_title'     => '',
// 	// 	'post_date_gmt'  => gmdate( 'Y-m-d H:i:s' ),
// 	// 	'post_content'   => '',
// 	// 	'post_status'    => 'publish',
// 	// 	'comment_status' => 'closed',
// 	// 	'ping_status'    => 'closed',
// 	// );

// 	// $postid = wp_insert_post( $new_post );

// 	$create_booking = array(
// 		'user'         => $user_id,
// 		'product'      => $product,
// 		'assign_order' => $assign_order,
// 		'order'        => $order_to_assign,
// 	);
// 	print_r( $create_booking );
// }

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
								<option value="<?php //echo esc_html( $user_id ); ?>" selected="selected"><?php //echo esc_html( $user_id ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><h3><?php esc_html_e( 'Product', 'mwb-wc-bk' ); ?></h3></label>
						</th>
						<td class="forminp forminp-text">
							<select name="mwb_create_booking_product_select" id="mwb_create_booking_product_select" data-placeholder="<?php esc_html_e( 'Choose Product', 'mwb-wc-bk' ); ?>">
								<option value=""></option>
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
					<tr valign="top">
						<th scope="row" class="">
							<label><h3><?php esc_html_e( 'Details', 'mwb-wc-bk' ); ?></h3></label>
						</th>
						<td class="forminp forminp-text">
							<p>
								<label for=""><?php esc_html_e( 'Start Date', '' ); ?></label>
								<input type="date" name="mwb_create_booking_" data-placeholder="Start Date">
								<label for=""><?php esc_html_e( 'End Date', '' ); ?></label>
								<input type="date" data-placeholder="End Date">
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="mwb_create_booking_submit">
			<input type="submit" id="mwb_create_booking_submit_button" name="mwb_create_booking_submit_button" class="button-primary button" value="<?php esc_html_e( 'Create Booking', 'mwb-wc-bk' ); ?>">
		</div>
	</div>
</form>

