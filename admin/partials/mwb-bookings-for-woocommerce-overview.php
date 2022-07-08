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
<div class="mwb-overview__wrapper">
	<div class="mwb-overview__banner">
		<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL ); ?>admin/image/banner-image.png" alt="Overview banner image">
	</div>
	<div class="mwb-overview__content">
		<div class="mwb-overview__content-description">
			<h2><?php esc_html_e( 'What is Bookings for WooCommerce?', 'mwb-bookings-for-woocommerce' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'Bookings for WooCommerce is a sophisticated plugin that lets online retailers create an online booking system and easily transform their merchandise into online booking solutions and make them accessible to their customers for a particular time frame.
					This plugin is simple yet powerful because it is a one-stop medium to offer online booking solutions for nearly all types of businesses. You can use it to book a hotel room, rent a product, reserve a course or class, sell a tour package online, buy an event ticket,
					schedule an appointment, and many other things.',
					'mwb-bookings-for-woocommerce'
				);
				?>
			</p>
			<h3><?php esc_html_e( 'As a Store Owner, you can-', 'mwb-bookings-for-woocommerce' ); ?></h3>
			<ul class="mwb-overview__features">
				<li><?php esc_html_e( 'With ease, create an unlimited number of online booking solutions.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Define the maximum number of bookings per day or per user with ease.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Can offer easy booking management parameters from the backend i.e. admin area.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Confirm the booking requests made by the customers.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Easy cancellation is allowed for booking orders.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Can easily define their offered WooCommerce booking services and additional costs.', 'mwb-bookings-for-woocommerce' ); ?></li>
			</ul>
		</div>
		<h2> <?php esc_html_e( 'The Free Plugin Benefits include-', 'mwb-bookings-for-woocommerce' ); ?></h2>
		<div class="mwb-overview__keywords">
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Adaptable-Bookings.png' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Adaptable Bookings', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e('Customers can personalize their booking criteria to fit their budget, event, and needs. You may make bookings without having to make a phone call, allowing you to do it from the convenience of your own home offering all WooCommerce easy booking services.', 'mwb-bookings-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Availability-Settings-to-Offer-Bookings.png' ); ?>" alt="Workflow image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Availability Settings to Offer Bookings', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e('Admin managers may quickly specify booking availability for the day, as well as the exact deadline for booking online. They only need to change the timeframe in the Availability tab of the plugin General Settings area to perform easy booking management.', 'mwb-bookings-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Ease-of-Setting-a-Booking-Calendar.png' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Ease of Setting a Booking Calendar', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e('Merchants can use the WooCommerce Calendar Booking display listing to build and monitor how their day or month is performing. They can also adjust current bookings or availability with response to easy booking management.', 'mwb-bookings-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Subtly-Stress-Additional-Costs-On-Demand-Surge.png' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Subtly Stress Additional Costs On Demand Surge', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e('An increase in demand can have a direct impact on the price of bookable products. When there is a significant demand for certain online booking solutions in a given location, admin administrators can easily set up WooCommerce bookings based on additional expenses.', 'mwb-bookings-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Set-Forth-Booking-Limits-For-Day.png' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Set-Forth Booking Limits For Day', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e('To avoid being overbooked, store managers can set a maximum number of bookings per user for bookable products, as well as provide easy booking management and effective tracking.', 'mwb-bookings-for-woocommerce' );
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/Easy-Booking-Cancellation-&-Confirmations.png' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Easy Booking Cancellation', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'Admin managers can now effortlessly offer easy booking cancellation for the booking requests by the customers making use of the plugin features. 
								In order to utilize the feature, the administrator needs to turn ON the toggle for Cancellation Allowed settings available in the specified booking
								product setting and specify the Order Statuses for the same.',
								'mwb-bookings-for-woocommerce'
							);
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
