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

global $bfw_wps_bfw_obj;
$bfw_onboarding_form_fields = 
//desc - filter for trial.
apply_filters('wps_bfw_on_boarding_form_fields', array());
?>

<?php if ( ! empty( $bfw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable
	<?php
	echo wp_kses_post(
	//desc - filter for trial.
	apply_filters( 'wps_stand_dialog_classes', 'bookings-for-woocommerce' ) );
	?>
	">
		<div class="wps-mbfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="wps-mbfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="wps-mbfw-on-boarding-close-btn">
						<a href="#"><span class="mbfw-close-form material-icons wps-mbfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>
					<h3 class="wps-mbfw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to WP Swings', 'bookings-for-woocommerce' ); ?> </h3>
					<p class="wps-mbfw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'bookings-for-woocommerce' ); ?></p>

					<form action="#" method="post" class="wps-mbfw-on-boarding-form">
						<?php 
						$bfw_onboarding_html = $bfw_wps_bfw_obj->wps_bfw_plug_generate_html( $bfw_onboarding_form_fields );
						echo esc_html( $bfw_onboarding_html );
						?>
						<div class="wps-mbfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="wps-mbfw-on-boarding-form-submit wps-mbfw-on-boarding-form-verify ">
								<input type="submit" class="wps-mbfw-on-boarding-submit wps-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="wps-mbfw-on-boarding-form-no_thanks">
								<a href="#" class="wps-mbfw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'bookings-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
