<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if (! defined('ABSPATH') ) {
	exit(); // Exit if accessed directly.
}

global $mbfw_mwb_mbfw_obj;
do_action( 'mwb_mbfw_license_notice_admin' );
$mbfw_active_tab   = isset( $_GET['mbfw_tab'] ) ? sanitize_key( $_GET['mbfw_tab'] ) : 'mwb-bookings-for-woocommerce-general'; // phpcs:ignore
$mbfw_default_tabs = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_default_tabs();
?>
<header>
	<?php
		//desc - setting saved notice.
		do_action('mwb_mbfw_settings_saved_notice');
	?>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title">
			<?php
			$plugin_name = $mbfw_mwb_mbfw_obj->mbfw_get_plugin_name();
			echo esc_attr(
				strtoupper(
					str_replace(
						'-',
						' ',
						//desc - update name for pro plugin.
						apply_filters( 'mwb_mbfw_update_plugin_name_from_pro', $plugin_name )
					)
				)
			);
			?>
		</h1>
		<a href="
		<?php
		$doc_link = 'https://docs.makewebbetter.com/mwb-bookings-for-woocommerce/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-doc';
		echo esc_url(
			//desc - Documentation link for pro update.
			apply_filters( 'mwb_mbfw_update_doc_link', $doc_link )
		);
		?>
		" target="_blank" class="mwb-link"><?php esc_html_e('Documentation', 'mwb-bookings-for-woocommerce'); ?></a>
		<span>|</span>
		<a href="
		<?php
		$query_link = 'https://makewebbetter.com/submit-query/?utm_source=MWB-bookings-org&utm_medium=MWB-org-backend&utm_campaign=MWB-bookings-support';
		echo esc_url(
			//desc - Query link update.
			apply_filters( 'mwb_mbfw_update_query_link', $query_link )
		);
		?>
		" target="_blank" class="mwb-link"><?php esc_html_e('Support', 'mwb-bookings-for-woocommerce'); ?></a>
	</div>
</header>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if (is_array($mbfw_default_tabs) && ! empty($mbfw_default_tabs) ) {
				foreach ( $mbfw_default_tabs as $mbfw_tab_key => $mbfw_default_tabs ) {

					$mbfw_tab_classes = 'mwb-link ';
					if (! empty($mbfw_active_tab) && $mbfw_active_tab === $mbfw_tab_key ) {
						$mbfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr($mbfw_tab_key); ?>" href="<?php echo esc_url(admin_url('admin.php?page=mwb_bookings_for_woocommerce_menu') . '&mbfw_tab=' . esc_attr($mbfw_tab_key)); ?>" class="<?php echo esc_attr($mbfw_tab_classes); ?>"><?php echo esc_html($mbfw_default_tabs['title']); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<section class="mwb-section">
		<div>
			<?php
			//desc - Before Setting Form.
			do_action('mwb_mbfw_before_general_settings_form');
			// if submenu is directly clicked on woocommerce.
			if ( empty( $mbfw_active_tab ) ) {
				$mbfw_active_tab = 'mwb_mbfw_plug_general';
			}
			// look for the path based on the tab id in the admin templates.
			$mbfw_default_tabs     = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_default_tabs();
			$mbfw_tab_content_path = $mbfw_default_tabs[ $mbfw_active_tab ]['file_path'];
			$mbfw_mwb_mbfw_obj->mwb_mbfw_plug_load_template($mbfw_tab_content_path);
			//desc - After Setting Form.
			do_action('mwb_mbfw_after_general_settings_form');
			?>
		</div>
	</section>
