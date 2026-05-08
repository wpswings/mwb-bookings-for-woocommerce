<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}

global $mbfw_mwb_mbfw_obj;

/**
 * Filter is for returning something.
 *
 * @since 1.0.0
 */
do_action( 'mwb_mbfw_license_notice_admin' );
$mbfw_active_tab   = isset( $_GET['mbfw_tab'] ) ? sanitize_key( $_GET['mbfw_tab'] ) : ( isset( $_GET['taxonomy'] ) ? 'mwb-bookings-for-woocommerce-configuration' : 'mwb-bookings-for-woocommerce-general' );// phpcs:ignore
$mbfw_default_tabs = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_default_tabs();
?>
<header>
	<?php
		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mwb_mbfw_settings_saved_notice' );
	?>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<div class="mwb-header-branding">
			<span class="mwb-header-badge"><?php esc_html_e( 'Free Active', 'mwb-bookings-for-woocommerce' ); ?></span>
			<h1 class="mwb-header-title">
				<?php
				$plugin_name = "WPS Bookings For WooCommerce";
				echo esc_html(
					str_replace(
						'-',
						' ',
						/**
						 * Filter is for returning something.
						 *
						 * @since 1.0.0
						 */
						apply_filters( 'mwb_mbfw_update_plugin_name_from_pro', $plugin_name )
					)
				);
				?>
			</h1>
		</div>
	</div>
</header>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<span class="mwb-navbar__version">v<?php echo esc_html( MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION ); ?></span>
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $mbfw_default_tabs ) && ! empty( $mbfw_default_tabs ) ) {
				foreach ( $mbfw_default_tabs as $mbfw_tab_key => $mbfw_default_tabs ) {

					$mbfw_tab_classes = 'mwb-link ';
					if ( ! empty( $mbfw_active_tab ) && $mbfw_active_tab === $mbfw_tab_key ) {
						$mbfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $mbfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_bookings_for_woocommerce_menu' ) . '&mbfw_tab=' . esc_attr( $mbfw_tab_key ) ); ?>" class="<?php echo esc_attr( $mbfw_tab_classes ); ?>"><?php echo esc_html( $mbfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<div class="mwb-dashboard-body">
		<section class="mwb-section">
			<div>
				<?php
				/**
				 * Filter is for returning something.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mwb_mbfw_before_general_settings_form' );
				// if submenu is directly clicked on woocommerce.
				if ( empty( $mbfw_active_tab ) ) {
					$mbfw_active_tab = 'mwb-bookings-for-woocommerce-general';
				}

				// Look for the path based on the tab id in the admin templates.
				$mbfw_default_tabs = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_default_tabs();

				// Per-tab highlight block content.
				$mbfw_tab_highlights = array(
					'mwb-bookings-for-woocommerce-overview'                      => array(
						'category'    => esc_html__( 'PLUGIN GUIDE', 'mwb-bookings-for-woocommerce' ),
						'title'       => esc_html__( 'Overview', 'mwb-bookings-for-woocommerce' ),
						'description' => esc_html__( 'Get a quick tour of all features and capabilities of WPS Bookings for WooCommerce.', 'mwb-bookings-for-woocommerce' ),
						'doc_url'     => 'https://docs.wpswings.com/bookings-for-woocommerce/',
					),
					'mwb-bookings-for-woocommerce-general'                       => array(
						'category'    => esc_html__( 'PLUGIN SETTINGS', 'mwb-bookings-for-woocommerce' ),
						'title'       => esc_html__( 'General Settings', 'mwb-bookings-for-woocommerce' ),
						'description' => esc_html__( 'Configure core plugin settings including enabling bookings and managing global preferences.', 'mwb-bookings-for-woocommerce' ),
						'doc_url'     => 'https://docs.wpswings.com/bookings-for-woocommerce/',
					),
					'mwb-bookings-for-woocommerce-configuration'                 => array(
						'category'    => esc_html__( 'BOOKING SETUP', 'mwb-bookings-for-woocommerce' ),
						'title'       => esc_html__( 'Configuration Settings', 'mwb-bookings-for-woocommerce' ),
						'description' => esc_html__( 'Set up booking forms, additional costs, people types, and other configuration options.', 'mwb-bookings-for-woocommerce' ),
						'doc_url'     => 'https://docs.wpswings.com/bookings-for-woocommerce/',
					),
					'mwb-bookings-for-woocommerce-booking-calendar-listing'      => array(
						'category'    => esc_html__( 'CALENDAR VIEW', 'mwb-bookings-for-woocommerce' ),
						'title'       => esc_html__( 'Bookings Calendar', 'mwb-bookings-for-woocommerce' ),
						'description' => esc_html__( 'View and manage all your bookings in an interactive calendar or list layout.', 'mwb-bookings-for-woocommerce' ),
						'doc_url'     => 'https://docs.wpswings.com/bookings-for-woocommerce/',
					),
					'mwb-bookings-for-woocommerce-booking-availability-settings' => array(
						'category'    => esc_html__( 'AVAILABILITY', 'mwb-bookings-for-woocommerce' ),
						'title'       => esc_html__( 'Availability Settings', 'mwb-bookings-for-woocommerce' ),
						'description' => esc_html__( 'Define global availability rules, blocked dates, and booking time windows.', 'mwb-bookings-for-woocommerce' ),
						'doc_url'     => 'https://docs.wpswings.com/bookings-for-woocommerce/',
					),
				);

				// Allow pro / other plugins to register highlight data for their tabs.
				$mbfw_tab_highlights = apply_filters( 'mwb_mbfw_tab_highlight_data', $mbfw_tab_highlights );

				// Resolve block data: use explicit entry if available, otherwise auto-generate from tab title.
				if ( isset( $mbfw_tab_highlights[ $mbfw_active_tab ] ) ) {
					$mbfw_block = $mbfw_tab_highlights[ $mbfw_active_tab ];
				} elseif ( isset( $mbfw_default_tabs[ $mbfw_active_tab ]['title'] ) ) {
					$mbfw_block = array(
						'category'    => esc_html__( 'SETTINGS', 'mwb-bookings-for-woocommerce' ),
						'title'       => $mbfw_default_tabs[ $mbfw_active_tab ]['title'],
						'description' => '',
						'doc_url'     => 'https://docs.wpswings.com/bookings-for-woocommerce/',
					);
				} else {
					$mbfw_block = null;
				}

				if ( $mbfw_block ) {
					?>
					<div class="wps-tab-highlight-block">
						<div class="wps-tab-highlight-block__left">
							<span class="wps-tab-highlight-block__category"><?php echo esc_html( $mbfw_block['category'] ); ?></span>
							<h2 class="wps-tab-highlight-block__title"><?php echo esc_html( $mbfw_block['title'] ); ?></h2>
							<?php if ( ! empty( $mbfw_block['description'] ) ) : ?>
								<p class="wps-tab-highlight-block__desc"><?php echo esc_html( $mbfw_block['description'] ); ?></p>
							<?php endif; ?>
						</div>
						<a href="<?php echo esc_url( $mbfw_block['doc_url'] ); ?>" target="_blank" class="wps-tab-highlight-block__btn">
							<?php esc_html_e( 'Read Documentation', 'mwb-bookings-for-woocommerce' ); ?>
						</a>
					</div>
					<?php
				}

				// Check if the key exists before accessing it.
				if ( isset( $mbfw_default_tabs[ $mbfw_active_tab ]['file_path'] ) ) {
					$mbfw_tab_content_path = $mbfw_default_tabs[ $mbfw_active_tab ]['file_path'];
					$mbfw_mwb_mbfw_obj->mwb_mbfw_plug_load_template( $mbfw_tab_content_path );
				} else {
					// Handle the case where the key does not exist.
					// You might want to set a default value or display an error message.
					$mbfw_tab_content_path = $mbfw_default_tabs['mwb-bookings-for-woocommerce-general']['file_path'];
					$mbfw_mwb_mbfw_obj->mwb_mbfw_plug_load_template( $mbfw_tab_content_path );
				}
				/**
				 * Filter is for returning something.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mwb_mbfw_after_general_settings_form' );
				?>
			</div>
		</section>

		<!-- Sidebar -->
		<aside class="wps-dashboard-sidebar">

			<!-- Need help -->
			<div class="wps-sidebar-card">
				<h3 class="wps-sidebar-card__title"><?php esc_html_e( 'Need help with this plugin?', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<a href="https://youtu.be/LDwy4ioMI1I?si=BI7TanVdCER-ORQB" target="_blank" class="wps-sidebar-card__link">
					<?php esc_html_e( 'Watch Video', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
				<a href="https://docs.wpswings.com/bookings-for-woocommerce/?utm_source=wpswings-bookings-pro&utm_medium=organic&utm_campaign=documentation" target="_blank" class="wps-sidebar-card__link">
					<?php esc_html_e( 'Documentation', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
				<a href="https://wpswings.com/contact-us/?utm_source=wpswings-bookings-org&utm_medium=bookings-org-backend&utm_campaign=contact-us" target="_blank" class="wps-sidebar-card__link">
					<?php esc_html_e( 'Support', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
			</div>

			<!-- Grow Your Store -->
			<div class="wps-sidebar-card">
				<div class="wps-sidebar-card__grow-header">
					<div>
						<h3 class="wps-sidebar-card__title"><?php esc_html_e( 'Grow Your Store With WP Swings', 'mwb-bookings-for-woocommerce' ); ?></h3>
						<p class="wps-sidebar-card__subtitle"><?php esc_html_e( 'Expert solutions to boost your store\'s performance.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
					<span class="wps-sidebar-card__star">&#9733;</span>
				</div>
				<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-bookings-services&utm_medium=bookings-org-backend&utm_campaign=woocommerce-services" target="_blank" class="wps-sidebar-card__service-link">
					<div class="wps-sidebar-card__service-icon wps-sidebar-card__service-icon--search">
						<span class="material-icons">search</span>
					</div>
					<div>
						<p class="wps-sidebar-card__service-name"><?php esc_html_e( 'SEO Services', 'mwb-bookings-for-woocommerce' ); ?></p>
						<p class="wps-sidebar-card__service-desc"><?php esc_html_e( 'Improve rankings & organic traffic', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
					<span class="material-icons wps-sidebar-card__chevron">chevron_right</span>
				</a>
				<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-bookings-services&utm_medium=bookings-org-backend&utm_campaign=woocommerce-services" target="_blank" class="wps-sidebar-card__service-link">
					<div class="wps-sidebar-card__service-icon wps-sidebar-card__service-icon--ads">
						<span class="material-icons">north_east</span>
					</div>
					<div>
						<p class="wps-sidebar-card__service-name"><?php esc_html_e( 'Google Ads Setup And G4 Setup', 'mwb-bookings-for-woocommerce' ); ?></p>
						<p class="wps-sidebar-card__service-desc"><?php esc_html_e( 'Run profitable ad campaigns', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
					<span class="material-icons wps-sidebar-card__chevron">chevron_right</span>
				</a>
				<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-bookings-services&utm_medium=bookings-org-backend&utm_campaign=woocommerce-services" target="_blank" class="wps-sidebar-card__service-link">
					<div class="wps-sidebar-card__service-icon wps-sidebar-card__service-icon--speed">
						<span class="material-icons">speed</span>
					</div>
					<div>
						<p class="wps-sidebar-card__service-name"><?php esc_html_e( 'Speed Optimization', 'mwb-bookings-for-woocommerce' ); ?></p>
						<p class="wps-sidebar-card__service-desc"><?php esc_html_e( 'Faster site, happier customers', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
					<span class="material-icons wps-sidebar-card__chevron">chevron_right</span>
				</a>
				<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-bookings-services&utm_medium=bookings-org-backend&utm_campaign=woocommerce-services" target="_blank" class="wps-sidebar-card__service-link">
					<div class="wps-sidebar-card__service-icon wps-sidebar-card__service-icon--dev">
						<span class="wps-woo-icon"></span>
					</div>
					<div>
						<p class="wps-sidebar-card__service-name"><?php esc_html_e( 'WooCommerce Development Services', 'mwb-bookings-for-woocommerce' ); ?></p>
						<p class="wps-sidebar-card__service-desc"><?php esc_html_e( 'Custom solution for your store needs', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
					<span class="material-icons wps-sidebar-card__chevron">chevron_right</span>
				</a>
				<a href="#" class="wps-sidebar-card__expert-btn" data-mbfw-open-expert-modal="true">
					<?php esc_html_e( 'Talk to an Expert', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
				<p class="wps-sidebar-card__services-by"><?php esc_html_e( 'Services by WP Swings', 'mwb-bookings-for-woocommerce' ); ?> &#128578;</p>
			</div>

			<!-- Still facing problems -->
			<div class="wps-sidebar-card">
				<h3 class="wps-sidebar-card__title"><?php esc_html_e( 'Still facing problems?', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-sidebar-card__subtitle"><?php esc_html_e( 'We are ready to resolve workflow, styling, and integration issues across your store setup.', 'mwb-bookings-for-woocommerce' ); ?></p>
				<a href="https://wpswings.com/contact-us/?utm_source=wpswings-bookings-org&utm_medium=bookings-org-backend&utm_campaign=contact-us" target="_blank" class="wps-sidebar-card__dark-btn">
					<?php esc_html_e( 'Contact Us', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
			</div>

			<!-- Explore more plugins -->
			<div class="wps-sidebar-card">
				<h3 class="wps-sidebar-card__title"><?php esc_html_e( 'Explore more plugins', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<p class="wps-sidebar-card__subtitle"><?php esc_html_e( 'Discover additional commerce and automation plugins from the same product family.', 'mwb-bookings-for-woocommerce' ); ?></p>
				<a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-bookings-org&utm_medium=bookings-org-backend&utm_campaign=woocommerce-plugins" target="_blank" class="wps-sidebar-card__outline-btn">
					<?php esc_html_e( 'View More Plugins', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
			</div>

		</aside>
	</div>
