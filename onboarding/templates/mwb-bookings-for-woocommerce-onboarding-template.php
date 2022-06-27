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

global $mbfw_mwb_mbfw_obj;
$mbfw_onboarding_form_fields = 
/**
 * Filter is for returning something.
 *
 * @since 1.0.0
 */
apply_filters('mwb_mbfw_on_boarding_form_fields', array());
?>

<?php if ( ! empty( $mbfw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable
	<?php
	echo wp_kses_post(
	/**
	 * Filter is for returning something.
	 *
	 * @since 1.0.0
	 */
	apply_filters( 'mwb_stand_dialog_classes', 'mwb-bookings-for-woocommerce' ) );
	?>
	">
		<div class="mwb-mbfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-mbfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-mbfw-on-boarding-close-btn">
						<a href="#"><span class="mbfw-close-form material-icons mwb-mbfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>
					<h3 class="mwb-mbfw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to WP Swings', 'mwb-bookings-for-woocommerce' ); ?> </h3>
					<p class="mwb-mbfw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'mwb-bookings-for-woocommerce' ); ?></p>

					<form action="#" method="post" class="mwb-mbfw-on-boarding-form">
						<?php 
						$mbfw_onboarding_html = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_generate_html( $mbfw_onboarding_form_fields );
						echo esc_html( $mbfw_onboarding_html );
						?>
						<div class="mwb-mbfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-mbfw-on-boarding-form-submit mwb-mbfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-mbfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-mbfw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-mbfw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'mwb-bookings-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
