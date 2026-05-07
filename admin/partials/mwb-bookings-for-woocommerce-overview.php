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

/**
 * Filter is for returning something.
 *
 * @since 1.0.0
 */
do_action( 'mwb_mbfw_overview_content_top' );
?>
<div class="wps-overview-wrap">

	<!-- Hero Section -->
	<div class="wps-overview-hero">
		<div class="wps-overview-hero__icon">
			<span class="material-icons">event_available</span>
		</div>
		<p class="wps-overview-hero__label"><?php esc_html_e( 'OVERVIEW', 'mwb-bookings-for-woocommerce' ); ?></p>
		<h1 class="wps-overview-hero__title"><?php esc_html_e( 'WPS Bookings For WooCommerce', 'mwb-bookings-for-woocommerce' ); ?></h1>
		<p class="wps-overview-hero__desc">
			<?php esc_html_e( 'Transform your WooCommerce products into bookable services — appointments, rentals, events, and more — all managed from one powerful dashboard.', 'mwb-bookings-for-woocommerce' ); ?>
		</p>
	</div>

	<!-- Features Section -->
	<div class="wps-overview-features">
		<h2 class="wps-overview-features__title"><?php esc_html_e( 'Top Features of this plugin', 'mwb-bookings-for-woocommerce' ); ?></h2>
		<div class="wps-overview-features__grid">

			<div class="wps-overview-feature-card">
				<div class="wps-overview-feature-card__icon">
					<span class="material-icons">tune</span>
				</div>
				<h3 class="wps-overview-feature-card__title"><?php esc_html_e( 'Adaptable Bookings', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-overview-feature-card__desc">
					<?php esc_html_e( 'Customers can personalise their booking criteria to fit their budget, event, and needs — all without making a phone call.', 'mwb-bookings-for-woocommerce' ); ?>
				</p>
			</div>

			<div class="wps-overview-feature-card">
				<div class="wps-overview-feature-card__icon">
					<span class="material-icons">date_range</span>
				</div>
				<h3 class="wps-overview-feature-card__title"><?php esc_html_e( 'Availability Settings', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-overview-feature-card__desc">
					<?php esc_html_e( 'Quickly define booking availability windows and deadlines by adjusting the timeframe in the Availability Settings tab.', 'mwb-bookings-for-woocommerce' ); ?>
				</p>
			</div>

			<div class="wps-overview-feature-card">
				<div class="wps-overview-feature-card__icon">
					<span class="material-icons">calendar_month</span>
				</div>
				<h3 class="wps-overview-feature-card__title"><?php esc_html_e( 'Booking Calendar View', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-overview-feature-card__desc">
					<?php esc_html_e( 'Use the Calendar display listing to build and monitor daily or monthly performance and adjust bookings with ease.', 'mwb-bookings-for-woocommerce' ); ?>
				</p>
			</div>

			<div class="wps-overview-feature-card">
				<div class="wps-overview-feature-card__icon">
					<span class="material-icons">attach_money</span>
				</div>
				<h3 class="wps-overview-feature-card__title"><?php esc_html_e( 'Demand-Based Pricing', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-overview-feature-card__desc">
					<?php esc_html_e( 'Set additional costs on bookable products when demand surges, giving you full control over dynamic pricing.', 'mwb-bookings-for-woocommerce' ); ?>
				</p>
			</div>

			<div class="wps-overview-feature-card">
				<div class="wps-overview-feature-card__icon">
					<span class="material-icons">format_list_numbered</span>
				</div>
				<h3 class="wps-overview-feature-card__title"><?php esc_html_e( 'Booking Limits Per Day', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-overview-feature-card__desc">
					<?php esc_html_e( 'Prevent overbooking by setting a maximum number of bookings per user per day for any bookable product.', 'mwb-bookings-for-woocommerce' ); ?>
				</p>
			</div>

			<div class="wps-overview-feature-card">
				<div class="wps-overview-feature-card__icon">
					<span class="material-icons">cancel</span>
				</div>
				<h3 class="wps-overview-feature-card__title"><?php esc_html_e( 'Easy Booking Cancellation', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-overview-feature-card__desc">
					<?php esc_html_e( 'Enable cancellations effortlessly by turning on the Cancellation Allowed toggle in your booking product settings.', 'mwb-bookings-for-woocommerce' ); ?>
				</p>
			</div>

		</div>
	</div>

	<!-- Footer CTA -->
	<div class="wps-overview-cta">
		<div class="wps-overview-cta__text">
			<p class="wps-overview-cta__heading"><?php esc_html_e( 'Facing issues?', 'mwb-bookings-for-woocommerce' ); ?></p>
			<p class="wps-overview-cta__sub"><?php esc_html_e( 'We are ready to resolve your problems.', 'mwb-bookings-for-woocommerce' ); ?></p>
		</div>
		<div class="wps-overview-cta__actions">
			<a href="https://wpswings.com/submit-query/" target="_blank" class="wps-overview-cta__btn"><?php esc_html_e( 'Contact us!', 'mwb-bookings-for-woocommerce' ); ?></a>
			<a href="https://demo.wpswings.com/bookings-for-woocommerce-pro/" target="_blank" class="wps-overview-cta__btn"><?php esc_html_e( 'Demo', 'mwb-bookings-for-woocommerce' ); ?></a>
			<a href="https://wpswings.com/woocommerce-plugins/" target="_blank" class="wps-overview-cta__btn"><?php esc_html_e( 'Support', 'mwb-bookings-for-woocommerce' ); ?></a>
		</div>
	</div>

</div>
