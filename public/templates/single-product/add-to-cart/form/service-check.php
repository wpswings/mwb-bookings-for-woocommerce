<?php
/**
 * Check Service settings for the booking.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/templates/single-product/add-to-cart/form
 */

defined( 'ABSPATH' ) || exit;

$global_func = Mwb_Booking_Global_Functions::get_global_instance();

global $product;
$product_data = array(
	'product_id' => $product->get_id(),
);

$product_meta    = get_post_meta( $product->get_id() );
$setting_options = get_option( 'mwb_booking_settings_options', $global_func->booking_settings_tab_default_global_options() );

$inc_service_enabled = ! empty( $setting_options['mwb_booking_setting_bo_inc_service_enable'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_inc_service_enable'] ) ) : 'no';
$show_service_cost   = ! empty( $setting_options['mwb_booking_setting_bo_service_cost'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_cost'] ) ) : 'no';
$show_service_desc   = ! empty( $setting_options['mwb_booking_setting_bo_service_desc'] ) ? sanitize_text_field( wp_unslash( $setting_options['mwb_booking_setting_bo_service_desc'] ) ) : 'no';

$service_enabled  = ! empty( $product_meta['mwb_services_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_services_enable_checkbox'][0] ) ) : 'no';
$enabled_services = ! empty( $product_meta['mwb_booking_services_select'][0] ) ? maybe_unserialize( sanitize_text_field( wp_unslash( $product_meta['mwb_booking_services_select'][0] ) ) ) : array();

$service_meta = array();

foreach ( $enabled_services as $service_id ) {
	$service      = get_term_meta( $service_id );
	$service_term = get_term( $service_id );

	if ( 'no' === $service['mwb_booking_ct_services_optional'][0] || empty( $service['mwb_booking_ct_services_optional'][0] ) ) {
		$service['term_obj']                 = $service_term;
		$service_meta['included_services'][] = $service;
	} else {
		$service['term_obj']                   = $service_term;
		$service_meta['additional_services'][] = $service;
	}
}


?>
<div id="mwb-wc-bk-service-section" class="mwb-wc-bk-form-section">
	<?php
	if ( 'yes' === $service_enabled ) {
		?>
	<div id="mwb-wc-bk-service-field">
		<?php
		if ( 'yes' === $inc_service_enabled ) {
			$style = 'display: block;';
		} else {
			$style = 'display: none;';
		}
		?>
		<div id="mwb-wc-bk-inc-service-field" style="<?php echo esc_html( $style ); ?>" >
			<?php if ( ! empty( $service_meta['included_services'] ) && is_array( $service_meta['included_services'] ) ) { ?>
				<label for=""><b><?php esc_html_e( 'Included Services', 'mwb-wc-bk' ); ?></b></label>
				<input type="hidden" id="mwb-wc-bk-service-input-hidden" class="mwb-wc-bk-form-input-hidden" data-hidden="<?php echo esc_html( htmlspecialchars( wp_json_encode( $service_meta ) ) ); ?>"> 
				<ul id="mwb-wc-bk-inc-service-list">
				<?php
				foreach ( $service_meta['included_services'] as $service_data ) {
					$service_price = ! empty( $service_data['mwb_ct_booking_service_cost'][0] ) ? (int) $service_data['mwb_ct_booking_service_cost'][0] : 0;
					if ( 'no' === $service_data['mwb_booking_ct_services_hidden'][0] || empty( $service_data['mwb_booking_ct_services_hidden'][0] ) ) {
						?>
					<li>
						<label for="mwb-wc-bk-inc-service-field-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>"><?php echo esc_html( $service_data['term_obj']->name ); ?></label>
							<?php
							if ( 'yes' === $show_service_cost ) {
								?>
						<span class="booking-service-price"><?php echo esc_html( sprintf( '%s %d', get_woocommerce_currency_symbol(), $service_price ) ); ?></span>
								<?php
							}
							if ( 'yes' === $service_data['mwb_booking_ct_services_has_quantity'][0] ) {
								?>
						<input type="number" name="inc-service-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" id="mwb-wc-bk-inc-service-quant-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" class="mwb-wc-bk-inc-service-quant service-input" data-id="<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" value="<?php echo ! empty( $service_data['mwb_booking_ct_services_min_quantity'][0] ) ? esc_html( $service_data['mwb_booking_ct_services_min_quantity'][0] ) : '0'; ?>" step="1" min="<?php echo ! empty( $service_data['mwb_booking_ct_services_min_quantity'][0] ) ? esc_html( $service_data['mwb_booking_ct_services_min_quantity'][0] ) : '0'; ?>" max="<?php echo ! empty( $service_data['mwb_booking_ct_services_max_quantity'][0] ) ? esc_html( $service_data['mwb_booking_ct_services_max_quantity'][0] ) : ''; ?>">	
								<?php
							} else {
								?>
						<input type="hidden" name="inc-service-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" class="mwb-wc-bk-inc-service-quant service-input" value="1" data-id="<?php echo esc_html( $service_data['term_obj']->term_id ); ?>">
								<?php
							}
							if ( 'yes' === $show_service_desc ) {
								?>
						<span class="booking-service-desc"><?php echo ! empty( $service_data['term_obj']->description ) ? esc_html( $service_data['term_obj']->description ) : esc_html__( 'No description provided' ); ?></span>
								<?php
							}
							?>
					</li>
						<?php
					}
				}
				?>
			</ul>
			<?php } ?>
		</div>
		<div id="mwb-wc-bk-add-service-field">
			<?php if ( ! empty( $service_meta['additional_services'] ) && is_array( $service_meta['additional_services'] ) ) { ?>
				<label for=""><b><?php esc_html_e( 'Additional Services', 'mwb-wc-bk' ); ?></b></label>
				<ul id="mwb-wc-bk-add-service-list">
				<?php
				foreach ( $service_meta['additional_services'] as $service_data ) {
					$service_price = ! empty( $service_data['mwb_ct_booking_service_cost'][0] ) ? (int) $service_data['mwb_ct_booking_service_cost'][0] : 0;
					if ( 'no' === $service_data['mwb_booking_ct_services_hidden'][0] || empty( $service_data['mwb_booking_ct_services_hidden'][0] ) ) {
						?>
					<li>
						<input type="checkbox" name="add-service-check-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" id="mwb-wc-bk-inc-service-field-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" class="mwb-wc-bk-form-input-services add_service_check">
						<label for="mwb-wc-bk-inc-service-field-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>"><?php echo esc_html( $service_data['term_obj']->name ); ?></label>
							<?php
							if ( 'yes' === $show_service_cost ) {
								?>
						<span class="booking-service-price"><?php echo esc_html( sprintf( '%s %d', get_woocommerce_currency_symbol(), $service_price ) ); ?></span>
								<?php
							}
							if ( 'yes' === $service_data['mwb_booking_ct_services_has_quantity'][0] ) {
								?>
						<input type="number" name="add-service-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" id="mwb-wc-bk-add-service-quant-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" class="mwb-wc-bk-add-service-quant service-input" data-id="<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" value="0" step="1" min="0" max="<?php echo ! empty( $service_data['mwb_booking_ct_services_max_quantity'][0] ) ? esc_html( $service_data['mwb_booking_ct_services_max_quantity'][0] ) : ''; ?>">	
								<?php
							} else {
								?>
						<input type="hidden" name="add-service-<?php echo esc_html( $service_data['term_obj']->term_id ); ?>" class="mwb-wc-bk-add-service-quant service-input" value="1" data-id="<?php echo esc_html( $service_data['term_obj']->term_id ); ?>">
								<?php
							}
							if ( 'yes' === $show_service_desc ) {
								?>
						<span class="booking-service-desc"><?php echo ! empty( $service_data['term_obj']->description ) ? esc_html( $service_data['term_obj']->description ) : esc_html__( 'No description provided' ); ?></span>
								<?php
							}
							?>
					</li>
						<?php
					}
				}
				?>
			</ul>
		<?php } ?>
		</div>
	</div>
			<?php
	}
	?>
</div>








