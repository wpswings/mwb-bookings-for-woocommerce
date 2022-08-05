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
$mfw_default_tabs = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_default_tabs();
$mfw_tab_key = '';
?>
<header>
	<?php

	/**
	 * Action to save setting.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mwb_mbfw_settings_saved_notice' );
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( __( 'WP Swings' ) ); ?></h1>
	</div>
</header>
<main class="wps-main wps-bg-white wps-r-8">
	<section class="wps-section">
		<div>
			<?php

			/**
			 * Action before common setting form.
			 *
			 * @since 1.0.0
			 */
			do_action( 'wps_mfw_before_common_settings_form' );

			/**
			 * Filter for setting.
			 *
			 * @since 1.0.0
			 */
			$mfw_genaral_settings = apply_filters(
				'mbfw_home_settings_array',
				array(
					array(
						'title' => __( 'Enable Tracking', 'mwb-bookings-for-woocommerce' ),
						'type'  => 'radio-switch',
						'id'    => 'mbfw_enable_tracking',
						'value' => get_option( 'mbfw_enable_tracking' ),
						'class' => 'mbfw-radio-switch-class',
						'options' => array(
							'yes' => __( 'YES', 'mwb-bookings-for-woocommerce' ),
							'no' => __( 'NO', 'mwb-bookings-for-woocommerce' ),
						),
					),
					array(
						'type'  => 'button',
						'id'    => 'mbfw_button_demo',
						'button_text' => __( 'Save', 'mwb-bookings-for-woocommerce' ),
						'class' => 'mbfw-button-class',
					),
				)
			);
			?>
			<form action="" method="POST" class="wps-mbfw-gen-section-form">
				<div class="mbfw-secion-wrap">
					<?php
					$mfw_general_html = $mbfw_mwb_mbfw_obj->mwb_mbfw_plug_generate_html( $mfw_genaral_settings );
					echo esc_html( $mfw_general_html );
					wp_nonce_field( 'admin_save_data', 'mwb_tabs_nonce' );
					?>
				</div>
			</form>
			<?php

			/**
			 * Action before common setting form.
			 *
			 * @since 1.0.0
			 */
			do_action( 'wps_mfw_before_common_settings_form' );
			$all_plugins = get_plugins();
			?>
		</div>
	</section>
	<style type="text/css">
		.cards {
			   display: flex;
			   flex-wrap: wrap;
			   padding: 20px 40px;
		}
		.card {
			flex: 1 0 518px;
			box-sizing: border-box;
			margin: 1rem 3.25em;
			text-align: center;
		}

	</style>
	<div class="centered">
		<section class="cards">
			<?php foreach ( get_plugins() as $key => $value ) : ?>
				<?php if ( 'WP Swings' === $value['Author'] ) : ?>
					<article class="card">
						<div class="container">
							<h4><b><?php echo esc_html( $value['Name'] ); ?></b></h4> 
							<p><?php echo esc_html( $value['Version'] ); ?></p> 
							<p><?php echo wp_kses_post( $value['Description'] ); ?></p>
						</div>
					</article>
				<?php endif; ?>
			<?php endforeach; ?>
		</section>
	</div>
