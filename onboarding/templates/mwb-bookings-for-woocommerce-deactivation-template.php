<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/onboarding
 */

global $pagenow, $mbfw_mwb_mbfw_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}
$mwb_plugin_name                 = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
$mwb_plugin_deactivation_id      = $mwb_plugin_name . '-no_thanks_deactive';
$mwb_plugin_onboarding_popup_id  = $mwb_plugin_name . '-onboarding_popup';
$mbfw_onboarding_form_deactivate = 
/**
 * Filter is for returning something.
 *
 * @since 1.0.0
 */
apply_filters('mwb_mbfw_deactivation_form_fields', array());

?>
<?php if ( ! empty( $mbfw_onboarding_form_deactivate ) ) : ?>
	<div id="<?php echo esc_attr( $mwb_plugin_onboarding_popup_id ); ?>" class="mdc-dialog mdc-dialog--scrollable <? echo 
	/**
	 * Filter is for returning something.
	 * @since 1.0.0
	 */
	apply_filters('mwb_stand_dialog_classes', 'mwb-bookings-for-woocommerce' )?>">
		<div class="mwb-mbfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-mbfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-mbfw-on-boarding-close-btn">
						<a href="#">
							<span class="mbfw-close-form material-icons mwb-mbfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="mwb-mbfw-on-boarding-heading mdc-dialog__title"></h3>
					<p class="mwb-mbfw-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'mwb-bookings-for-woocommerce' ); ?></p>
					<form action="#" method="post" class="mwb-mbfw-on-boarding-form">
						<?php 
						$mbfw_onboarding_deactive_html = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_generate_html( $mbfw_onboarding_form_deactivate );
						echo esc_html( $mbfw_onboarding_deactive_html );
						?>
						<div class="mwb-mbfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-mbfw-on-boarding-form-submit mwb-mbfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-mbfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-mbfw-on-boarding-form-no_thanks">
								<a href="#" id="<?php echo esc_attr( $mwb_plugin_deactivation_id ); ?>" class="<? echo 
								/**
								 * Filter is for returning something.
								 * @since 1.0.0
								 */
								apply_filters('mwb_stand_no_thank_classes', 'mwb-bookings-for-woocommerce-no_thanks' )?> mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'mwb-bookings-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
