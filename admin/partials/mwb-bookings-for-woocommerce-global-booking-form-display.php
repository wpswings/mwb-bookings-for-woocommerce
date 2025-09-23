<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to list all the hooks and filter with their descriptions.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div>
  <div class="wps-form_view">
    <h1 class="wps-form_view-heading"> <?php esc_html_e('Additional Information', 'mwb-bookings-for-woocommerce'); ?></h1>
    <div class="wps-form_view-wrap">
      <div class="wps-form_view-wrap-in">
      </div>
      <div class="wps-form_view-btn">
        <button type="submit" disabled><?php echo esc_html__('Add to cart', 'mwb-bookings-for-woocommerce'); ?></button>
      </div>
    </div>
  </div>
</div>