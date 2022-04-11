<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/admin/onboarding
 */

global $pagenow, $bfw_wps_bfw_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}
$wps_plugin_name                 = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
$wps_plugin_deactivation_id      = $wps_plugin_name . '-no_thanks_deactive';
$wps_plugin_onboarding_popup_id  = $wps_plugin_name . '-onboarding_popup';
$bfw_onboarding_form_deactivate = 
//desc - filter for trial.
apply_filters('wps_bfw_deactivation_form_fields', array());

?>
<?php if ( ! empty( $bfw_onboarding_form_deactivate ) ) : ?>
	<div id="<?php echo esc_attr( $wps_plugin_onboarding_popup_id ); ?>" class="mdc-dialog mdc-dialog--scrollable <? echo 
	//desc - filter for trial.
	apply_filters('wps_stand_dialog_classes', 'mwb-bookings-for-woocommerce' )?>">
		<div class="wps-mbfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="wps-mbfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="wps-mbfw-on-boarding-close-btn">
						<a href="#">
							<span class="mbfw-close-form material-icons wps-mbfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="wps-mbfw-on-boarding-heading mdc-dialog__title"></h3>
					<p class="wps-mbfw-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'mwb-bookings-for-woocommerce' ); ?></p>
					<form action="#" method="post" class="wps-mbfw-on-boarding-form">
						<?php 
						$bfw_onboarding_deactive_html = $bfw_wps_bfw_obj->wps_bfw_plug_generate_html( $bfw_onboarding_form_deactivate );
						echo esc_html( $bfw_onboarding_deactive_html );
						?>
						<div class="wps-mbfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="wps-mbfw-on-boarding-form-submit wps-mbfw-on-boarding-form-verify ">
								<input type="submit" class="wps-mbfw-on-boarding-submit wps-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="wps-mbfw-on-boarding-form-no_thanks">
								<a href="#" id="<?php echo esc_attr( $wps_plugin_deactivation_id ); ?>" class="<? echo 
								//desc - filter for trial.
								apply_filters('wps_stand_no_thank_classes', 'bookings-for-woocommerce-no_thanks' )?> mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'mwb-bookings-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
