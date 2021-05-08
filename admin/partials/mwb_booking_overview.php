<?php  // @codingStandardsIgnoreLine
/**
 * Provide a admin area view for the plugin
 *
 * This file is used for the overview page of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    MWB_Bookings_For_WooCommerce
 * @subpackage MWB_Bookings_For_WooCommerce/admin/partials
 */

?>

<div class="booking-overview__wrapper">
	<div class="mwb-wc-bk-connect-links">
		<a href="<?php echo esc_url( 'https://docs.makewebbetter.com/mwb-bookings-for-woocommerce/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-doc' ); ?>"><?php esc_html_e( 'Documentation', 'mwb-bookings-for-woocommerce' ); ?></a>
		<span><?php echo esc_html( '|' ); ?></span>
		<a href="<?php echo esc_url( 'https://makewebbetter.com/submit-query/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-support ' ); ?>"><?php esc_html_e( 'Support', 'mwb-bookings-for-woocommerce' ); ?></a>
	</div>
	<div class="booking-overview__banner">
		<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/booking-banner.png' ); ?>" alt="Overview banner image">
	</div>
	<div class="booking-overview__content">
		<div class="booking-overview__content-description">
			<h2><?php esc_html_e( 'What is MWB Bookings for WooCommerce?', 'mwb-bookings-for-woocommerce' ); ?></h2>
			<p><?php esc_html_e( "The MWB Bookings for WooCommerce Plugin builds a booking system for your services using WooCommerce. Schedule appointments, Booking Items and Rooms, Reserve seats for classes, and so on. This plugin also allows you to organize your booking slots into days, hours and minutes. You'll need to set the value of the booking unit as per requirement when booking space, renting items, reserving courses or classes, or selling tour packages..", 'mwb-bookings-for-woocommerce' ); ?></p>
			<h3><?php esc_html_e( 'As a store owner, you can-', 'mwb-bookings-for-woocommerce' ); ?></h3>
			<ul class="booking-overview__features">
				<li><?php esc_html_e( 'Create unlimited bookable products with ease.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Easily access all bookings using the calendar listings.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Create booking slots for days, hours and minutes as required.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Reject bookings with a pending payment status automatically.', 'mwb-bookings-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Set a minimum and maximum booking time.', '' ); ?></li>
				<li><?php esc_html_e( 'During the booking process, easily inquire about the types of people.', 'mwb-bookings-for-woocommerce' ); ?></li>
			</ul>
		</div>
		<div class="booking-overview__keywords">
			<h2 class="booking-overview__keywords-title"><?php esc_html_e( 'The free plugin benefits include', 'mwb-bookings-for-woocommerce' ); ?></h2>
			<div class="booking-overview__keywords-item">
				<div class="booking-overview__keywords-card">
					<div class="booking-overview__keywords-image">
						<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/define.png' ); ?>" alt="Efficiently Assign image">
					</div>
					<div class="booking-overview__keywords-text">
						<h3 class="booking-overview__keywords-heading"><?php esc_html_e( 'Efficiently Assign Booking Units', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="booking-overview__keywords-description"><?php esc_html_e( 'Admin managers can easily set up booking units based on days, hours, minutes and fixed or customer selected durations for the bookable products available in their WooCommerce booking services store.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div class="booking-overview__keywords-item">
				<div class="booking-overview__keywords-card">
					<div class="booking-overview__keywords-image">
						<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/assign.png' ); ?>" alt="People type image">
					</div>
					<div class="booking-overview__keywords-text">
						<h3 class="booking-overview__keywords-heading"><?php esc_html_e( 'Easily Define People Types ', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="booking-overview__keywords-description"><?php esc_html_e( 'During the Woocommerce booking process, inquire about the types of people you’re offering bookings for. Admin managers can easily create their own people-type labels based on age, marital status, affiliation, etc. e.g. adults, children, senior-citizen, foreigners, etc', 'mwb-bookings-for-woocommerce' ) ?></p>
					</div>
				</div>
			</div>
			<div class="booking-overview__keywords-item">
				<div class="booking-overview__keywords-card">
					<div class="booking-overview__keywords-image">
						<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/customization.png' ); ?>" alt="Service Customization image">
					</div>
					<div class="booking-overview__keywords-text">
						<h3 class="booking-overview__keywords-heading"><?php esc_html_e( 'Service Customization Available ', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="booking-overview__keywords-description"><?php esc_html_e( 'Store managers can easily customize their booking services i.e. included and addon services as per their customer prerequisites. For instance- hotel rental booking includes parking services and offer addon meals services.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div class="booking-overview__keywords-item">
				<div class="booking-overview__keywords-card">
					<div class="booking-overview__keywords-image">
						<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/global.png' ); ?>" alt="Global Availability image">
					</div>
					<div class="booking-overview__keywords-text">
						<h3 class="booking-overview__keywords-heading"><?php esc_html_e( 'Effectively lay down Global Availability rules for Bookings ', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="booking-overview__keywords-description"><?php esc_html_e( 'Admin managers can easily set up global booking availability rules for all the booking products globally as per specific or generic dates for the customer planning to avail your bookable products.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div class="booking-overview__keywords-item">
				<div class="booking-overview__keywords-card">
					<div class="booking-overview__keywords-image">
						<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/additional.png' ); ?>" alt="Additional costs image">
					</div>
					<div class="booking-overview__keywords-text">
						<h3 class="booking-overview__keywords-heading"><?php esc_html_e( 'Smoothly underline additional costs depending on demand surge', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="booking-overview__keywords-description"><?php esc_html_e( 'Demand surge can directly affect the cost of bookable products. Admin managers can easily set up WooCommerce bookings based on additional surge costs when there is a high demand for certain bookable products in a specific region.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>
			<div class="booking-overview__keywords-item">
				<div class="booking-overview__keywords-card">
					<div class="booking-overview__keywords-image">
						<img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/images/max-booking.png' ); ?>" alt="Booking Limit image">
					</div>
					<div class="booking-overview__keywords-text">
						<h3 class="booking-overview__keywords-heading"><?php esc_html_e( 'Set-forth max booking limits with ease', 'mwb-bookings-for-woocommerce' ); ?></h3>	
						<p class="booking-overview__keywords-description"><?php esc_html_e( 'Store managers can also limit max bookings per unit allowed for a particular bookable product, to avoid being overbooked, manage booking, and track bookings effectively.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>
			</div>	
		</div>	
	</div>
</div>
