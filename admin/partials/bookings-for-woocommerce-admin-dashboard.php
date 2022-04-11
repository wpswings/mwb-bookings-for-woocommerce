<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/admin/partials
 */

if (! defined('ABSPATH') ) {
	exit(); // Exit if accessed directly.
}

global $bfw_wps_bfw_obj;
do_action( 'wps_bfw_license_notice_admin' );
$bfw_active_tab   = isset( $_GET['bfw_tab'] ) ? sanitize_key( $_GET['bfw_tab'] ) : 'bookings-for-woocommerce-general'; // phpcs:ignore
$bfw_default_tabs = $bfw_wps_bfw_obj->wps_bfw_plug_default_tabs();
?>
<header>
	<?php
		//desc - setting saved notice.
		do_action('wps_bfw_settings_saved_notice');
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title">
			<?php
			$plugin_name = $bfw_wps_bfw_obj->bfw_get_plugin_name();
			echo esc_attr(
				strtoupper(
					str_replace(
						'-',
						' ',
						//desc - update name for pro plugin.
						apply_filters( 'wps_bfw_update_plugin_name_from_pro', $plugin_name )
					)
				)
			);
			?>
		</h1>
		<a href="
		<?php
		$doc_link = 'https://docs.wpswings.com/bookings-for-woocommerce/?utm_source=wpswings-bookings-doc&utm_medium=bookings-org-backend&utm_campaign=documentation';
		echo esc_url(
			//desc - Documentation link for pro update.
			apply_filters( 'wps_bfw_update_doc_link', $doc_link )
		);
		?>
		" target="_blank" class="wps-link"><?php esc_html_e('Documentation', 'mwb-bookings-for-woocommerce'); ?></a>
		<span>|</span>
		<a href="
		<?php
		$query_link = 'https://wpswings.com/submit-query/?utm_source=wpswings-bookings-support&utm_medium=bookings-org-backend&utm_campaign=support';
		echo esc_url(
			//desc - Query link update.
			apply_filters( 'wps_bfw_update_query_link', $query_link )
		);
		?>
		" target="_blank" class="wps-link"><?php esc_html_e('Support', 'mwb-bookings-for-woocommerce'); ?></a>
	</div>
</header>
<main class="wps-main wps-bg-white wps-r-8">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if (is_array($bfw_default_tabs) && ! empty($bfw_default_tabs) ) {
				foreach ( $bfw_default_tabs as $bfw_tab_key => $bfw_default_tabs ) {

					$bfw_tab_classes = 'wps-link ';
					if (! empty($bfw_active_tab) && $bfw_active_tab === $bfw_tab_key ) {
						$bfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr($bfw_tab_key); ?>" href="<?php echo esc_url(admin_url('admin.php?page=bookings_for_woocommerce_menu') . '&bfw_tab=' . esc_attr($bfw_tab_key)); ?>" class="<?php echo esc_attr($bfw_tab_classes); ?>"><?php echo esc_html($bfw_default_tabs['title']); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<section class="wps-section">
		<div>
			<?php
			//desc - Before Setting Form.
			do_action('wps_bfw_before_general_settings_form');
			// if submenu is directly clicked on woocommerce.
			if ( empty( $bfw_active_tab ) ) {
				$bfw_active_tab = 'wps_bfw_plug_general';
			}
			require_once plugin_dir_path( __FILE__ ) . '/bookings-for-woocommerce-incompatible.php';

			// $old_pro_exists = false;
			// include_once ABSPATH . 'wp-admin/includes/plugin.php';
			// $plug           = get_plugins();
			// if ( isset( $plug['bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php'] ) ) {
			// 	if ( $plug['bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php']['Version'] >= '2.0.0' ) {
			// 		$old_pro_exists = true;
			// 	}
			// }
			// if ( $old_pro_exists ) {
			// 	if( ! get_option('wps_bfw_pro_migration_complete')){
			// 		$bfw_migration_button[] = array(
			// 			'type'        => 'button',
			// 			'id'          => 'wps_bfw_migration_button',
			// 			'button_text' => __('Migrate settings', 'mwb-bookings-for-woocommerce'),
			// 			'class'       => 'wps_bfw_availability_settings_save',
			// 			'name'        => 'wps_bfw_availability_settings_save',
			// 		);
			// 		$bfw_wps_bfw_obj->wps_bfw_plug_generate_html( $bfw_migration_button );
			// 	}
			// } else
			//  if(!get_option('wps_bfw_org_migration_complete')){
			// $bfw_migration_button[] = array(
			// 	'type'        => 'button',
			// 	'id'          => 'wps_bfw_migration_button',
			// 	'button_text' => __('Migrate settings', 'mwb-bookings-for-woocommerce'),
			// 	'class'       => 'wps_bfw_availability_settings_save',
			// 	'name'        => 'wps_bfw_availability_settings_save',
			// );
			// $bfw_wps_bfw_obj->wps_bfw_plug_generate_html( $bfw_migration_button );
			// }
			// look for the path based on the tab id in the admin templates.
			$bfw_default_tabs     = $bfw_wps_bfw_obj->wps_bfw_plug_default_tabs();
			$bfw_tab_content_path = $bfw_default_tabs[ $bfw_active_tab ]['file_path'];
			$bfw_wps_bfw_obj->wps_bfw_plug_load_template($bfw_tab_content_path);
			//desc - After Setting Form.
			do_action('wps_bfw_after_general_settings_form');
			?>
		</div>
	</section>
