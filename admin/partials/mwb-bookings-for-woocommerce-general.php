<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $mbfw_mwb_mbfw_obj;
$mbfw_genaral_settings = 
//desc - general settings tab fields.
apply_filters( 'mbfw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-mbfw-gen-section-form">
	<div class="mbfw-secion-wrap">
		<?php
		$mbfw_mwb_mbfw_obj->mwb_mbfw_plug_generate_html( $mbfw_genaral_settings );
		wp_nonce_field( 'admin_save_data', 'mwb_tabs_nonce' );
		//desc - after general setting fields.
		do_action( 'mwb_mbfw_after_general_setting_tab_fields' );
		?>
	</div>
</form>
