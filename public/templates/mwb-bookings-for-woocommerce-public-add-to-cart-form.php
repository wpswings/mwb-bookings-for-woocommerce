<?php
/**
 * Provide a public area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$product_id = get_the_id();
do_action( 'mwb_booking_before_add_to_cart_form' );
do_action( 'mwb_mbfw_number_of_people_while_booking_on_form', $product_id, $product );
do_action( 'mwb_mbfw_booking_services_details_on_form', $product_id, $product );
do_action( 'mwb_booking_after_add_to_cart_form' );
