<?php
/**
 * Check Cost settings for the booking
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/templates/single-product/add-to-cart/form
 */

defined( 'ABSPATH' ) || exit;

global $product;
$product_meta = get_post_meta( $product->get_id() );

$unit_cost          = ! empty( $product_meta['mwb_booking_unit_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_input'] ) ) : '';
$unit_cost_multiply = ! empty( $product_meta['mwb_booking_unit_cost_multiply'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_multiply'] ) ) : '';
$base_cost          = ! empty( $product_meta['mwb_booking_base_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_input'] ) ) : '';
$base_cost_multiply = ! empty( $product_meta['mwb_booking_base_cost_multiply'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_multiply'] ) ) : '';
$extra_cost         = ! empty( $product_meta['mwb_booking_extra_cost_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_extra_cost_input'] ) ) : '';
$extra_cost_people  = ! empty( $product_meta['mwb_booking_extra_cost_people_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_extra_cost_people_input'] ) ) : '1';

$discount_type = ! empty( $product_meta['mwb_booking_cost_discount_type'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_cost_discount_type'] ) ) : '';

$monthly_discount = ! empty( $product_meta['mwb_booking_monthly_discount_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_monthly_discount_input'] ) ) : '';
$weekly_discount  = ! empty( $product_meta['mwb_booking_weekly_discount_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_weekly_discount_input'] ) ) : '';
$custom_discount  = ! empty( $product_meta['mwb_booking_custom_days_discount_input'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_custom_days_discount_input'] ) ) : '';
$custom_disc_days = ! empty( $product_meta['mwb_booking_custom_discount_days'] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_custom_discount_days'] ) ) : '';

$price = ! empty( $product_meta['_price'] ) ? sanitize_text_field( wp_unslash( $product_meta['_price'] ) ) : '';

// if ( ! empty( $unit_cost ) ) {
// 	if ( ! empty( $base_cost ) ) {
// 		$price = $unit_cost + $base_cost;
// 	}
// 	// if ( ! empty( $extra_cost ) ) {
// 	// 	echo 'ljhd';
// 	// }
// }





































