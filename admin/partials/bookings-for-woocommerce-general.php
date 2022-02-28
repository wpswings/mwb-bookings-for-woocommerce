<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $bfw_wps_bfw_obj;
$bfw_genaral_settings = 
//desc - general settings tab fields.
apply_filters( 'bfw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-mbfw-gen-section-form">
	<div class="mbfw-secion-wrap">
		<?php
		$bfw_wps_bfw_obj->wps_bfw_plug_generate_html( $bfw_genaral_settings );
		wp_nonce_field( 'admin_save_data', 'wps_tabs_nonce' );
		//desc - after general setting fields.
		do_action( 'wps_bfw_after_general_setting_tab_fields' );
		?>
	</div>
</form>
