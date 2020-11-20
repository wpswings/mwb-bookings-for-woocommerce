<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set the global rules for Availability
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

if ( isset( $_POST['mwb_booking_global_availability_rules_save'] ) ) {
	echo 'hi';
}
?>

<!-- For Global options Setting -->
<form id="mwb_global_availability_form" action="" method="POST">
	<div class="mwb_booking_global_availability_rules">
		<div id="mwb_global_availability_rules">
			<?php
				$str = ! empty( $this->global_availability_rule_arr ) ? implode( '', $this->global_availability_rule_arr ) : 'empty';
				echo $str;
				?>
		</div>
		<div id="mwb_global_availability_button">
			<button type="button" id="mwb_add_avialability_rule" class="button"><?php esc_html_e( 'Add New Avialability Rule', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
	<!-- Save Settings -->
	<p class="submit">
		<input type="submit" value="<?php esc_html_e( 'Save Changes', 'mwb-wc-bk' ); ?>" class="button-primary woocommerce-save-button" name="mwb_booking_global_availability_rules_save" id="mwb_booking_global_availability_rules_save" >
	</p>
</form>
