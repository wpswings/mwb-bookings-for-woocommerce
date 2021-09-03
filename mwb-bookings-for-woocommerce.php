<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://makewebbetter.com/
 * @since   1.0.0
 * @package Mwb_Bookings_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Mwb Bookings For WooCommerce
 * Plugin URI:        https://makewebbetter.com/product/mwb-bookings-for-woocommerce/
 * Description:       MWB Bookings for WooCommerce helps you stay focused while offering a better online booking system for your business growth. Just stop speculating and opt for the best one out.
 * Version:           2.0.1
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       mwb-bookings-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires at least:    4.6
 * Tested up to:         5.8
 * WC requires at least: 4.0.0
 * WC tested up to:      5.6.0
 * Requires PHP:         7.2
 * Stable tag:           2.0.1
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if (! defined('ABSPATH') ) {
	die;
}

if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true ) || ( is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins', array() ), true ) ) ) {

	/**
	 * Define plugin constants.
	 *
	 * @since 2.0.0
	 */
	function define_mwb_bookings_for_woocommerce_constants() {
		mwb_bookings_for_woocommerce_constants('MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION', '2.0.1');
		mwb_bookings_for_woocommerce_constants('MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path(__FILE__));
		mwb_bookings_for_woocommerce_constants('MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url(__FILE__));
		mwb_bookings_for_woocommerce_constants('MWB_BOOKINGS_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com');
		mwb_bookings_for_woocommerce_constants('MWB_BOOKINGS_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'Mwb Bookings For WooCommerce');
	}

	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param string $key   Key for contant.
	 * @param string $value value for contant.
	 * @since 2.0.0
	 */
	function mwb_bookings_for_woocommerce_constants( $key, $value ) {
		if (! defined($key) ) {
			define($key, $value);
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-mwb-bookings-for-woocommerce-activator.php
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function activate_mwb_bookings_for_woocommerce( $network_wide ) {
		include_once plugin_dir_path(__FILE__) . 'includes/class-mwb-bookings-for-woocommerce-activator.php';
		Mwb_Bookings_For_Woocommerce_Activator::mwb_bookings_for_woocommerce_activate( $network_wide );
		$mwb_mbfw_active_plugin = get_option('mwb_all_plugins_active', false );
		if ( is_array( $mwb_mbfw_active_plugin ) && ! empty( $mwb_mbfw_active_plugin ) ) {
			$mwb_mbfw_active_plugin['mwb-bookings-for-woocommerce'] = array(
				'plugin_name' => __( 'Mwb Bookings For WooCommerce', 'mwb-bookings-for-woocommerce' ),
				'active'      => '1',
			);
		} else {
			$mwb_mbfw_active_plugin                                 = array();
			$mwb_mbfw_active_plugin['mwb-bookings-for-woocommerce'] = array(
				'plugin_name' => __( 'Mwb Bookings For WooCommerce', 'mwb-bookings-for-woocommerce' ),
				'active'      => '1',
			);
		}
		update_option('mwb_all_plugins_active', $mwb_mbfw_active_plugin);
	}

	/**
	 * Will be used when new blog is created on multisite.
	 *
	 * @param object $new_site current blog object.
	 * @since 1.0.0
	 * @return void
	 */
	function mwb_bookings_for_woocommerce_on_new_blog_creation( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		if ( is_plugin_active_for_network( 'mwb-bookings-for-woocommerce/mwb-bookings-for-woocommerce.php' ) ) {
			$blog_id = isset( $new_site->blog_id ) ? $new_site->blog_id : '';
			switch_to_blog( $blog_id );
			include_once plugin_dir_path(__FILE__) . 'includes/class-mwb-bookings-for-woocommerce-activator.php';
			Mwb_Bookings_For_Woocommerce_Activator::mwb_bookings_for_woocommerce_update_default_value();
			restore_current_blog();
		}
	}
	add_action( 'wp_initialize_site', 'mwb_bookings_for_woocommerce_on_new_blog_creation' );

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-mwb-bookings-for-woocommerce-deactivator.php
	 */
	function deactivate_mwb_bookings_for_woocommerce() {
		include_once plugin_dir_path(__FILE__) . 'includes/class-mwb-bookings-for-woocommerce-deactivator.php';
		Mwb_Bookings_For_Woocommerce_Deactivator::mwb_bookings_for_woocommerce_deactivate();
		$mwb_mbfw_deactive_plugin = get_option('mwb_all_plugins_active', false);
		if (is_array($mwb_mbfw_deactive_plugin) && ! empty($mwb_mbfw_deactive_plugin) ) {
			foreach ( $mwb_mbfw_deactive_plugin as $mwb_mbfw_deactive_key => $mwb_mbfw_deactive ) {
				if ('mwb-bookings-for-woocommerce' === $mwb_mbfw_deactive_key ) {
					$mwb_mbfw_deactive_plugin[ $mwb_mbfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option('mwb_all_plugins_active', $mwb_mbfw_deactive_plugin);
	}
	register_activation_hook( __FILE__, 'activate_mwb_bookings_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_mwb_bookings_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-bookings-for-woocommerce.php';


	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since 2.0.0
	 */
	function run_mwb_bookings_for_woocommerce() {
		define_mwb_bookings_for_woocommerce_constants();
		$mbfw_plugin_standard = new Mwb_Bookings_For_Woocommerce();
		$mbfw_plugin_standard->mbfw_run();
		$GLOBALS['mbfw_mwb_mbfw_obj'] = $mbfw_plugin_standard;
	}
	run_mwb_bookings_for_woocommerce();
	// Add settings link on plugin page.
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'mwb_bookings_for_woocommerce_settings_link');

	/**
	 * Settings link.
	 *
	 * @since 2.0.0
	 * @param Array $links Settings link array.
	 */
	function mwb_bookings_for_woocommerce_settings_link( $links ) {
		$my_link = array(
			'<a href="' . admin_url('admin.php?page=mwb_bookings_for_woocommerce_menu') . '">' . __('Settings', 'mwb-bookings-for-woocommerce') . '</a>'
		);
		if ( ! in_array( 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php', get_option( 'active_plugins', array() ), true ) ) {
			$my_link[] = '<a href="https://makewebbetter.com/product/bookings-for-woocommerce-pro/?utm_source=MWB-bookings-site&utm_medium=MWB-site-backend&utm_campaign=MWB-bookings-gopro" target="_blank" id="mbfw-go-pro-link">' . __( 'Go Pro', 'mwb-bookings-for-woocommerce' ) . '</a>';
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
	function mwb_bookings_for_woocommerce_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
		if (strpos($plugin_file_name, basename(__FILE__)) ) {
			$links_array[] = '<a href="https://demo.makewebbetter.com/mwb-bookings-for-woocommerce/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-demo" target="_blank"><img src="' . esc_html(MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Demo.svg" class="mwb-info-img" alt="Demo image">' . __('Demo', 'mwb-bookings-for-woocommerce') . '</a>';
			$links_array[] = '<a href="https://docs.makewebbetter.com/mwb-bookings-for-woocommerce/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-doc" target="_blank"><img src="' . esc_html(MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Documentation.svg" class="mwb-info-img" alt="documentation image">' . __('Documentation', 'mwb-bookings-for-woocommerce') . '</a>';
			$links_array[] = '<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-support" target="_blank"><img src="' . esc_html(MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Support.svg" class="mwb-info-img" alt="support image">' . __('Support', 'mwb-bookings-for-woocommerce') . '</a>';
		}
		return $links_array;
	}
	add_filter('plugin_row_meta', 'mwb_bookings_for_woocommerce_custom_settings_at_plugin_tab', 10, 2);
} else {
	mwb_mbfw_dependency_checkup();
}

/**
 * Checking dependency for woocommerce plugin.
 *
 * @return void
 */
function mwb_mbfw_dependency_checkup() {
	add_action( 'admin_init', 'mwb_mbfw_deactivate_child_plugin' );
	add_action( 'admin_notices', 'mwb_mbfw_show_admin_notices' );
}
/**
 * Deactivating child plugin.
 *
 * @return void
 */
function mwb_mbfw_deactivate_child_plugin() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
/**
 * Showing admin notices.
 *
 * @return void
 */
function mwb_mbfw_show_admin_notices() {
	$mwb_mbfw_child_plugin  = __( 'Mwb Bookings For WooCommerce', 'mwb-bookings-for-woocommerce' );
	$mwb_mbfw_parent_plugin = __( 'WooCommerce', 'mwb-bookings-for-woocommerce' );
	echo '<div class="notice notice-error is-dismissible"><p>'
		/* translators: %s: dependency checks */
		. sprintf( esc_html__( '%1$s requires %2$s to function correctly. Please activate %2$s before activating %1$s. For now, the plugin has been deactivated.', 'mwb-bookings-for-woocommerce' ), '<strong>' . esc_html( $mwb_mbfw_child_plugin ) . '</strong>', '<strong>' . esc_html( $mwb_mbfw_parent_plugin ) . '</strong>' )
		. '</p></div>';
	if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
		unset( $_GET['activate'] ); //phpcs:ignore
	}
}
