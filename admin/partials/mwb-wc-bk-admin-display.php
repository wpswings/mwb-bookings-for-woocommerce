<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$default_tab             = 'settings';
$default_tab             = apply_filters( 'mwb_booking_open_default_tab', $$default_tab );
$mwb_booking_active_tabs = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab;

do_action( 'mwb_booking_tab_active' );

?>

<div class="wrap woocommerce" id="mwb_booking_setting_wrapper">
	<div class="mwb_booking_setting_title">
		<h1><?php echo esc_html( apply_filters( 'mwb_booking_heading', esc_html__( 'Booking For Woocommerce', 'mwb-wc-bk' ) ) ); ?></h1>

		<span class="mwb_booking_setting_title_version">
		<?php
			esc_html_e( 'v', 'mwb-wc-bk' );
			echo esc_html( MWB_WC_BK_VERSION );
		?>
		</span>
	</div>
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">

		<a class="nav-tab <?php echo esc_html( 'settings' === $mwb_booking_active_tabs ? 'nav-tab-active' : '' ); ?>" href="?post_type=mwb_cpt_booking&page=global-settings&tab=settings"><?php esc_html_e( 'Settings', 'mwb-wc-bk' ); ?></a>

		<a class="nav-tab <?php echo esc_html( 'global-availability-rules' === $mwb_booking_active_tabs ? 'nav-tab-active' : '' ); ?>" href="?post_type=mwb_cpt_booking&page=global-settings&tab=global-availability-rules"><?php esc_html_e( 'Global Availability Rules', 'mwb-wc-bk' ); ?></a>

		<a class="nav-tab <?php echo esc_html( 'global-cost-rules' === $mwb_booking_active_tabs ? 'nav-tab-active' : '' ); ?>" href="?post_type=mwb_cpt_booking&page=global-settings&tab=global-cost-rules"><?php esc_html_e( 'Global Cost Rules', 'mwb-wc-bk' ); ?></a>
	</nav>
</div>
	<?php
	if ( 'settings' === $mwb_booking_active_tabs ) {
		include_once plugin_dir_path( __FILE__ ) . 'templates/mwb-booking-settings.php';

	} elseif ( 'global-availability-rules' === $mwb_booking_active_tabs ) {
		include_once plugin_dir_path( __FILE__ ) . 'templates/mwb-booking-global-availability.php';

	} elseif ( 'global-cost-rules' === $mwb_booking_active_tabs ) {
		include_once plugin_dir_path( __FILE__ ) . 'templates/mwb-booking-global-cost.php';

	}

