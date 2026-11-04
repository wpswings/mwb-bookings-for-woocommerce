<?php
/**
 * One-off CLI helper: activate/deactivate a plugin via WP's own plugin API so the
 * serialized active_plugins option stays valid.
 *
 * Usage: php toggle-pro-plugin.php <activate|deactivate> [wp-load.php path] [plugin slug]
 *
 * @package Mwb_Bookings_For_Woocommerce
 */

if ( 'cli' !== PHP_SAPI ) {
	exit;
}

define( 'WP_USE_THEMES', false );

$mbfw_wp_load_path = isset( $argv[2] ) ? $argv[2] : getenv( 'WP_LOAD_PATH' );
if ( ! $mbfw_wp_load_path ) {
	$mbfw_wp_load_path = '/home/stuti-1219/Local Sites/book/app/public/wp-load.php';
}
require $mbfw_wp_load_path;
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$mbfw_slug = isset( $argv[3] ) ? $argv[3] : getenv( 'PRO_PLUGIN_SLUG' );
if ( ! $mbfw_slug ) {
	$mbfw_slug = 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php';
}
$mbfw_action = isset( $argv[1] ) ? $argv[1] : '';

if ( 'deactivate' === $mbfw_action ) {
	deactivate_plugins( array( $mbfw_slug ) );
	echo is_plugin_active( $mbfw_slug ) ? "FAILED: still active\n" : "OK: deactivated\n";
} elseif ( 'activate' === $mbfw_action ) {
	$mbfw_result = activate_plugin( $mbfw_slug );
	if ( is_wp_error( $mbfw_result ) ) {
		echo 'FAILED: ' . esc_html( $mbfw_result->get_error_message() ) . "\n";
	} else {
		echo is_plugin_active( $mbfw_slug ) ? "OK: activated\n" : "FAILED: still inactive\n";
	}
} else {
	echo "Usage: php toggle-pro-plugin.php <activate|deactivate>\n";
	exit( 1 );
}
