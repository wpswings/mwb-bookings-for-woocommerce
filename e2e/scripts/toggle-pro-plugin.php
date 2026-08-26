<?php
// One-off CLI helper: activate/deactivate a plugin via WP's own plugin API so the
// serialized active_plugins option stays valid.
// Usage: php toggle-pro-plugin.php <activate|deactivate> [wp-load.php path] [plugin slug]
define('WP_USE_THEMES', false);
$wp_load_path = $argv[2] ?? getenv('WP_LOAD_PATH') ?: '/home/stuti-1219/Local Sites/book/app/public/wp-load.php';
require $wp_load_path;
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$slug   = $argv[3] ?? getenv('PRO_PLUGIN_SLUG') ?: 'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php';
$action = $argv[1] ?? '';

if ('deactivate' === $action) {
    deactivate_plugins([$slug]);
    echo is_plugin_active($slug) ? "FAILED: still active\n" : "OK: deactivated\n";
} elseif ('activate' === $action) {
    $result = activate_plugin($slug);
    if (is_wp_error($result)) {
        echo 'FAILED: ' . $result->get_error_message() . "\n";
    } else {
        echo is_plugin_active($slug) ? "OK: activated\n" : "FAILED: still inactive\n";
    }
} else {
    echo "Usage: php toggle-pro-plugin.php <activate|deactivate>\n";
    exit(1);
}
