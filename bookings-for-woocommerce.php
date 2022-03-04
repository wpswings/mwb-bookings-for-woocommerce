<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://wpswings.com/
 * @since   1.0.0
 * @package Bookings_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Bookings For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/bookings-for-woocommerce/
 * Description:       WPS Bookings for WooCommerce helps you stay focused while offering a better online booking system for your business growth. Just stop speculating and opt for the best one out.
 * Version:           2.0.4
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-bookings-official&utm_medium=bookings-org-backend&utm_campaign=official
 * Text Domain:       bookings-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires at least:    5.0.0
 * Tested up to:         5.8.3
 * WC requires at least: 4.0.0
 * WC tested up to:      6.1.0
 * Requires PHP:         7.2
 * Stable tag:           2.0.4
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if (! defined('ABSPATH') ) {
	die;
}

if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true ) || ( is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins', array() ) ) ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php' ) ) {
			$plug = get_plugins();
		
			if ( isset( $plug[ 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php' ] ) ) {
				if ( $plug['bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php']['Version'] < '1.0.5' ) {
				
					unset( $_GET['activate'] );
					deactivate_plugins( plugin_basename( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php' ) );
					add_action( 'admin_notices', 'wps_bfw_show_pro_deactivate_notice' );
				}
		
			}
		}
	/**
	 * Define plugin constants.
	 *
	 * @since 2.0.0
	 */
	function define_bookings_for_woocommerce_constants() {
		bookings_for_woocommerce_constants('BOOKINGS_FOR_WOOCOMMERCE_VERSION', '2.0.4');
		bookings_for_woocommerce_constants('BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path(__FILE__));
		bookings_for_woocommerce_constants('BOOKINGS_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url(__FILE__));
		bookings_for_woocommerce_constants('BOOKINGS_FOR_WOOCOMMERCE_SERVER_URL', 'https://wpswings.com');
		bookings_for_woocommerce_constants('BOOKINGS_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'Bookings For WooCommerce');
	}

	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param string $key   Key for contant.
	 * @param string $value value for contant.
	 * @since 2.0.0
	 */
	function bookings_for_woocommerce_constants( $key, $value ) {
		if (! defined($key) ) {
			define($key, $value);
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-bookings-for-woocommerce-activator.php
	 *
	 * @param boolean $network_wide either network activated or not.
	 * @since 1.0.0
	 * @return void
	 */
	function activate_bookings_for_woocommerce( $network_wide ) {
		include_once plugin_dir_path(__FILE__) . 'includes/class-bookings-for-woocommerce-activator.php';
		Bookings_For_Woocommerce_Activator::bookings_for_woocommerce_activate( $network_wide );
		$wps_bfw_active_plugin = get_option('wps_all_plugins_active', false );
		if ( is_array( $wps_bfw_active_plugin ) && ! empty( $wps_bfw_active_plugin ) ) {
			$wps_bfw_active_plugin['bookings-for-woocommerce'] = array(
				'plugin_name' => __( 'Bookings For WooCommerce', 'bookings-for-woocommerce' ),
				'active'      => '1',
			);
		} else {
			$wps_bfw_active_plugin                                 = array();
			$wps_bfw_active_plugin['bookings-for-woocommerce'] = array(
				'plugin_name' => __( 'Bookings For WooCommerce', 'bookings-for-woocommerce' ),
				'active'      => '1',
			);
		}
		update_option('wps_all_plugins_active', $wps_bfw_active_plugin);
	}

	/**
	 * Will be used when new blog is created on multisite.
	 *
	 * @param object $new_site current blog object.
	 * @since 1.0.0
	 * @return void
	 */
	function bookings_for_woocommerce_on_new_blog_creation( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		if ( is_plugin_active_for_network( 'mwb-bookings-for-woocommerce/bookings-for-woocommerce.php' ) ) {
			$blog_id = isset( $new_site->blog_id ) ? $new_site->blog_id : '';
			switch_to_blog( $blog_id );
			include_once plugin_dir_path(__FILE__) . 'includes/class-bookings-for-woocommerce-activator.php';
			Bookings_For_Woocommerce_Activator::bookings_for_woocommerce_update_default_value();
			restore_current_blog();
		}
	}
	add_action( 'wp_initialize_site', 'bookings_for_woocommerce_on_new_blog_creation' );

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-bookings-for-woocommerce-deactivator.php
	 */
	function deactivate_bookings_for_woocommerce() {
		include_once plugin_dir_path(__FILE__) . 'includes/class-bookings-for-woocommerce-deactivator.php';
		Bookings_For_Woocommerce_Deactivator::bookings_for_woocommerce_deactivate();
		$wps_bfw_deactive_plugin = get_option('wps_all_plugins_active', false);
		if (is_array($wps_bfw_deactive_plugin) && ! empty($wps_bfw_deactive_plugin) ) {
			foreach ( $wps_bfw_deactive_plugin as $wps_bfw_deactive_key => $wps_bfw_deactive ) {
				if ('mwb-bookings-for-woocommerce' === $wps_bfw_deactive_key ) {
					$wps_bfw_deactive_plugin[ $wps_bfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option('wps_all_plugins_active', $wps_bfw_deactive_plugin);
	}
	register_activation_hook( __FILE__, 'activate_bookings_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_bookings_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-bookings-for-woocommerce.php';
	require plugin_dir_path( __FILE__ ) . 'includes/migrator/class-wps-bfw-data-handler.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *	
	 * @since 2.0.0
	 */
	function run_bookings_for_woocommerce() {
		define_bookings_for_woocommerce_constants();
		$bfw_plugin_standard = new Bookings_For_Woocommerce();
		$bfw_plugin_standard->bfw_run();
		$GLOBALS['bfw_wps_bfw_obj'] = $bfw_plugin_standard;

		$bfw_migrator = new Wps_Bfw_Data_Handler();
		// $bfw_migrator->wps_bfw_run_data_change_code();
	}
	run_bookings_for_woocommerce();
	// Add settings link on plugin page.
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bookings_for_woocommerce_settings_link');

	/**
	 * Settings link.
	 *
	 * @since 2.0.0
	 * @param array $links Settings link array.
	 */
	function bookings_for_woocommerce_settings_link( $links ) {
		$my_link = array(
			'<a href="' . admin_url('admin.php?page=bookings_for_woocommerce_menu') . '">' . __('Settings', 'bookings-for-woocommerce') . '</a>'
		);
		if ( ! in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', get_option( 'active_plugins', array() ), true ) ) {
			$my_link[] = '<a href="https://wpswings.com/product/bookings-for-woocommerce-pro/?utm_source=wpswings-bookings-pro&utm_medium=booikings-org-backend&utm_campaign=go-pro" target="_blank" id="mbfw-go-pro-link">' . __( 'Go Pro', 'bookings-for-woocommerce' ) . '</a>';
		}
		return array_merge($my_link, $links);
	}

	/**
	 * Adding custom setting links at the plugin activation list.
	 *
	 * @param  array  $links_array      array containing the links to plugin.
	 * @param  string $plugin_file_name plugin file name.
	 * @return array
	 */
	function bookings_for_woocommerce_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
		if (strpos($plugin_file_name, basename(__FILE__)) ) {
			$links_array[] = '<a href="https://demo.wpswings.com/bookings-for-woocommerce-pro/?utm_source=wpswings-bookings-demo&utm_medium=booikngs-org-backend&utm_campaign=demo" target="_blank"><img src="' . esc_html(BOOKINGS_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Demo.svg" class="wps-info-img" alt="Demo image">' . __('Demo', 'bookings-for-woocommerce') . '</a>';
			$links_array[] = '<a href="https://docs.wpswings.com/bookings-for-woocommerce/?utm_source=wpswings-bookings-doc&utm_medium=bookings-org-backend&utm_campaign=documentation" target="_blank"><img src="' . esc_html(BOOKINGS_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Documentation.svg" class="wps-info-img" alt="documentation image">' . __('Documentation', 'bookings-for-woocommerce') . '</a>';
			$links_array[] = '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-bookings-support&utm_medium=bookings-org-backend&utm_campaign=support" target="_blank"><img src="' . esc_html(BOOKINGS_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Support.svg" class="wps-info-img" alt="support image">' . __('Support', 'bookings-for-woocommerce') . '</a>';
		}
		return $links_array;
	}
	add_filter('plugin_row_meta', 'bookings_for_woocommerce_custom_settings_at_plugin_tab', 10, 2);
// Upgrade notice on plugin dashboard.
	add_action( 'admin_notices', 'bookings_for_woocommerce_dashboard_upgrade_notice' );
/**
 * Displays notice to upgrade to WP Swings on plugin dashboard.
 *
 * @return void
 */
function bookings_for_woocommerce_dashboard_upgrade_notice() {
	$screen = get_current_screen();
	if (isset($screen->id) && 'wp-swings_page_bookings_for_woocommerce_menu' === $screen->id ) {
		?>
		<tr class="plugin-update-tr active notice-warning notice-alt">
		<td colspan="4" class="plugin-update colspanchange">
			<div class="notice notice-success inline update-message notice-alt">
				<div class='wps-notice-title wps-notice-section'>
					<p><strong><?php esc_html_e( 'IMPORTANT NOTICE-', 'bookings-for-woocommerce' ); ?></strong></p>
				</div>
				<div class='wps-notice-content wps-notice-section'>
					<p><?php esc_html_e( 'From this update ', 'bookings-for-woocommerce' ); ?><strong><?php esc_html_e( 'Version 2.0.4', 'bookings-for-woocommerce' ); ?></strong><?php esc_html_e( ' onwards, the plugin and its support will be handled by ', 'bookings-for-woocommerce' ); ?><strong><?php esc_html_e( 'WP Swings', 'bookings-for-woocommerce' ); ?></strong>.</p>
					<p> <strong><?php esc_html_e( 'WP Swings', 'bookings-for-woocommerce' ); ?></strong> <?php esc_html_e( 'is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.', 'bookings-for-woocommerce' ); ?>
					<?php esc_html_e( 'Please connect with us for all setup, support, and update related queries without hesitation.', 'bookings-for-woocommerce' ); ?></p>
				</div>
			</div>
		</td>
	</tr>
	<style>
	.wps-notice-section > p:before {
		content: none;
	}
	</style>
	<?php
	
		}
	}
// Upgrade notice.
add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'bookings_for_woocommerce_upgrade_notice', 0, 3 );

/**
 * Displays notice to upgrade to WP Swings on plugin row.
 *
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @param array $plugin_data An array of plugin data.
 * @param string $status Status filter currently applied to the plugin list.
 */
function bookings_for_woocommerce_upgrade_notice( $plugin_file, $plugin_data, $status ) {
	?>
	<tr class="plugin-update-tr active notice-warning notice-alt">
		<td colspan="4" class="plugin-update colspanchange">
			<div class="notice notice-success inline update-message notice-alt">
				<div class='wps-notice-title wps-notice-section'>
				<p><strong><?php esc_html_e( 'IMPORTANT NOTICE-', 'bookings-for-woocommerce' ); ?></strong></p>
				</div>
				<div class='wps-notice-content wps-notice-section'>
					<p><?php esc_html_e( 'From this update ', 'bookings-for-woocommerce' ); ?><strong><?php esc_html_e( 'Version 2.0.4', 'bookings-for-woocommerce' ); ?></strong><?php esc_html_e( ' onwards, the plugin and its support will be handled by ', 'bookings-for-woocommerce' ); ?><strong><?php esc_html_e( 'WP Swings', 'bookings-for-woocommerce' ); ?></strong>.</p>
					<p> <strong><?php esc_html_e( 'WP Swings', 'bookings-for-woocommerce' ); ?></strong> <?php esc_html_e( 'is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.', 'bookings-for-woocommerce' ); ?>
					<?php esc_html_e( 'Please connect with us for all setup, support, and update related queries without hesitation.', 'bookings-for-woocommerce' ); ?></p>
				</div>
			</div>
		</td>
	</tr>
	<style>
	.wps-notice-section > p:before {
		content: none;
	}
	</style>
	<?php
}

} else {
	wps_bfw_dependency_checkup();
}

/**
 * Checking dependency for woocommerce plugin.
 *
 * @return void
 */
function wps_bfw_dependency_checkup() {
	add_action( 'admin_init', 'wps_bfw_deactivate_child_plugin' );
	add_action( 'admin_notices', 'wps_bfw_show_admin_notices' );
}
/**
 * Deactivating child plugin.
 *
 * @return void
 */
function wps_bfw_deactivate_child_plugin() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
/**
 * Showing admin notices.
 *
 * @return void
 */
function wps_bfw_show_admin_notices() {
	$wps_bfw_child_plugin  = __( 'Bookings For WooCommerce', 'bookings-for-woocommerce' );
	$wps_bfw_parent_plugin = __( 'WooCommerce', 'bookings-for-woocommerce' );
	echo '<div class="notice notice-error is-dismissible"><p>'
		/* translators: %s: dependency checks */
		. sprintf( esc_html__( '%1$s requires %2$s to function correctly. Please activate %2$s before activating %1$s. For now, the plugin has been deactivated.', 'bookings-for-woocommerce' ), '<strong>' . esc_html( $wps_bfw_child_plugin ) . '</strong>', '<strong>' . esc_html( $wps_bfw_parent_plugin ) . '</strong>' )
		. '</p></div>';
	if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
		unset( $_GET['activate'] ); //phpcs:ignore
	}
}
function wps_bfw_show_pro_deactivate_notice() {
	$wps_bfw_child_plugin  = __( 'Bookings For WooCommerce Pro', 'bookings-for-woocommerce' );
	$wps_bfw_parent_plugin = __( 'Bookings For WooCommerce', 'bookings-for-woocommerce' );
	$wps_bfw_pro_version = __( 'Version 1.0.5', 'bookings-for-woocommerce' );
	echo '<div class="notice notice-error is-dismissible"><p>'
	/* translators: %s: dependency checks */
	. sprintf( esc_html__( '%1$s requires %2$s %3$s and above to function correctly. Please update %2$s to %3$s before activating %1$s. For now, the plugin has been deactivated.', 'bookings-for-woocommerce' ), '<strong>' . esc_html( $wps_bfw_child_plugin ) . '</strong>', '<strong>' . esc_html( $wps_bfw_parent_plugin ) . '</strong>', '<strong>' . esc_html( $wps_bfw_pro_version ) . '</strong>' )
	. '</p></div>';
}
