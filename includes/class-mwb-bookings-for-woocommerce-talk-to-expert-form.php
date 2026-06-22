<?php
/**
 * Talk to an Expert HubSpot form handling.
 *
 * @package Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Mwb_Bookings_For_Woocommerce_Talk_To_Expert_Form' ) ) {
	return;
}

/**
 * Handle the Talk to an Expert custom form lifecycle.
 */
class Mwb_Bookings_For_Woocommerce_Talk_To_Expert_Form {

	/**
	 * Base URL for HubSpot submissions.
	 *
	 * @var string
	 */
	private $mwb_mbfw_base_url = 'https://api.hsforms.com/';

	/**
	 * HubSpot portal id.
	 *
	 * @var string
	 */
	private static $mwb_mbfw_talk_to_expert_portal_id = '25444144';

	/**
	 * HubSpot form id for the shared Talk to an Expert form.
	 *
	 * @var string
	 */
	private static $mwb_mbfw_talk_to_expert_form_id = 'eab973a7-5c65-4264-a31d-3b1b10b82c82';

	/**
	 * Plugin label sent with HubSpot submissions.
	 *
	 * @var string
	 */
	private static $mwb_mbfw_plugin_name_label = 'Bookings for WooCommerce';

	/**
	 * Current store name.
	 *
	 * @var string
	 */
	private static $mwb_mbfw_store_name = '';

	/**
	 * Current store URL.
	 *
	 * @var string
	 */
	private static $mwb_mbfw_store_url = '';

	/**
	 * Register hooks.
	 */
	public function __construct() {
		self::$mwb_mbfw_store_name = get_bloginfo( 'name' );
		self::$mwb_mbfw_store_url  = home_url();

		add_action( 'admin_footer', array( $this, 'mwb_mbfw_render_talk_to_expert_modal' ) );
		add_action( 'wp_ajax_mwb_mbfw_submit_talk_to_expert', array( $this, 'mwb_mbfw_submit_talk_to_expert' ) );
	}

	/**
	 * Check whether the plugin dashboard screen is active.
	 *
	 * @return bool
	 */
	private function mwb_mbfw_is_plugin_dashboard_screen() {
		if ( ! is_admin() ) {
			return false;
		}

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		return 'mwb_bookings_for_woocommerce_menu' === $page;
	}

	/**
	 * Render the Talk to an Expert modal.
	 */
	public function mwb_mbfw_render_talk_to_expert_modal() {
		if ( ! $this->mwb_mbfw_is_plugin_dashboard_screen() ) {
			return;
		}

		$field_values = $this->mwb_mbfw_get_default_field_values();
		$form_fields  = $this->mwb_mbfw_get_custom_form_fields();
		?>
		<div class="mbfw-expert-modal" hidden aria-hidden="true">
			<div class="mbfw-expert-modal__backdrop" data-mbfw-close-expert-modal="true"></div>
			<div class="mbfw-expert-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="mbfw-expert-modal-title">
				<div class="mbfw-expert-modal__header">
					<div class="mbfw-expert-modal__copy">
						<h2 id="mbfw-expert-modal-title"><?php esc_html_e( 'Talk to an Expert', 'mwb-bookings-for-woocommerce' ); ?></h2>
						<p><?php esc_html_e( 'Share your store goals and our team will reach out with the right next step.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
					<button type="button" class="mbfw-expert-modal__close" data-mbfw-close-expert-modal="true" aria-label="<?php esc_attr_e( 'Close expert form', 'mwb-bookings-for-woocommerce' ); ?>">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="mbfw-expert-modal__body">
					<form class="mbfw-expert-form" data-mbfw-expert-form="true" data-mbfw-expert-form-panel="true" novalidate>
						<div class="mbfw-expert-form__grid">
							<?php foreach ( $form_fields as $field_key => $field ) : ?>
								<?php $this->mwb_mbfw_render_form_field( $field_key, $field, $field_values ); ?>
							<?php endforeach; ?>
						</div>
						<div class="mbfw-expert-form__actions">
							<button
								type="submit"
								class="wps-sidebar-card__expert-btn mbfw-expert-form__submit"
								data-mbfw-expert-submit="true"
								data-mbfw-submit-label="<?php echo esc_attr__( 'Submit Request', 'mwb-bookings-for-woocommerce' ); ?>"
								data-mbfw-submit-loading-label="<?php echo esc_attr__( 'Sending...', 'mwb-bookings-for-woocommerce' ); ?>"
							>
								<?php esc_html_e( 'Submit Request', 'mwb-bookings-for-woocommerce' ); ?>
							</button>
						</div>
						<div class="mbfw-expert-form__status" data-mbfw-expert-state="true" hidden></div>
					</form>
					<div class="mbfw-expert-thank-you" data-mbfw-expert-thank-you="true" aria-hidden="true" hidden>
						<div class="mbfw-thank-you-card mbfw-thank-you-card--modal" aria-live="polite">
							<div class="mbfw-thank-you-card__icon" aria-hidden="true">&#10003;</div>
							<p class="mbfw-thank-you-card__eyebrow"><?php esc_html_e( 'Talk to an Expert', 'mwb-bookings-for-woocommerce' ); ?></p>
							<h2 class="mbfw-thank-you-card__title"><?php esc_html_e( 'Thank You', 'mwb-bookings-for-woocommerce' ); ?></h2>
							<p class="mbfw-thank-you-card__message" data-mbfw-expert-thank-you-message="true"><?php esc_html_e( 'Thank you for submitting your request.', 'mwb-bookings-for-woocommerce' ); ?></p>
							<p class="mbfw-thank-you-card__meta"><?php esc_html_e( 'Our team will review the details and contact you with the right next step. Redirecting you back to the dashboard in a moment.', 'mwb-bookings-for-woocommerce' ); ?></p>
							<p class="mbfw-thank-you-card__action">
								<button type="button" class="wps-sidebar-card__expert-btn" data-mbfw-close-expert-modal="true"><?php esc_html_e( 'Done', 'mwb-bookings-for-woocommerce' ); ?></button>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Build the custom form field definitions.
	 *
	 * @return array
	 */
	private function mwb_mbfw_get_custom_form_fields() {
		return array(
			'firstname' => array(
				'label'       => esc_html__( 'First Name', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'John', 'mwb-bookings-for-woocommerce' ),
				'required'    => false,
			),
			'lastname'  => array(
				'label'       => esc_html__( 'Last Name', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Doe', 'mwb-bookings-for-woocommerce' ),
				'required'    => false,
			),
			'email'      => array(
				'label'       => esc_html__( 'Work Email', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'email',
				'placeholder' => esc_html__( 'name@company.com', 'mwb-bookings-for-woocommerce' ),
				'required'    => true,
				'full_width'  => true,
			),
			'phone'      => array(
				'label'       => esc_html__( 'Contact Number', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'text',
				'placeholder' => esc_html__( '+1 000 000 0000', 'mwb-bookings-for-woocommerce' ),
				'required'    => false,
			),
			'what_services_do_you_need_help_with' => array(
				'label'       => esc_html__( 'What services do you need help with?', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox_group',
				'options'     => $this->mwb_mbfw_get_talk_to_expert_service_options(),
				'required'    => false,
				'full_width'  => true,
			),
			'budget'      => array(
				'label'       => esc_html__( 'Budget', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'select',
				'options'     => $this->mwb_mbfw_get_talk_to_expert_budget_options(),
				'required'    => false,
				'full_width'  => true,
			),
			'message'    => array(
				'label'       => esc_html__( 'What do you need help with?', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Share your goals, blockers, or the service you need.', 'mwb-bookings-for-woocommerce' ),
				'required'    => false,
				'full_width'  => true,
			),
		);
	}

	/**
	 * Render an individual form field.
	 *
	 * @param string $field_key Field key.
	 * @param array  $field Field config.
	 * @param array  $values Default values.
	 */
	private function mwb_mbfw_render_form_field( $field_key, $field, $values ) {
		$field_id    = 'mbfw-expert-' . $field_key;
		$field_value = isset( $values[ $field_key ] ) ? $values[ $field_key ] : '';
		$field_class = ! empty( $field['full_width'] ) ? ' mbfw-expert-form__field--full' : '';
		$field_class .= ' ' . sanitize_html_class( $field_key );
		?>
		<div class="mbfw-expert-form__field<?php echo esc_attr( $field_class ); ?>">
			<?php if ( 'checkbox_group' === $field['type'] ) : ?>
				<span class="mbfw-expert-form__label">
					<?php echo esc_html( $field['label'] ); ?>
					<?php if ( ! empty( $field['required'] ) ) : ?>
						<span class="mbfw-expert-form__required">*</span>
					<?php endif; ?>
				</span>
				<div id="<?php echo esc_attr( $field_id ); ?>" class="mbfw-expert-form__checkbox-group <?php echo esc_attr( $field_key ); ?>">
					<?php foreach ( $field['options'] as $option_value => $option_label ) : ?>
						<?php
						$option_id = $field_id . '-' . sanitize_title( $option_value );
						$checked   = is_array( $field_value ) && in_array( $option_value, $field_value, true );
						?>
						<label class="mbfw-expert-form__checkbox-label" for="<?php echo esc_attr( $option_id ); ?>">
							<input
								id="<?php echo esc_attr( $option_id ); ?>"
								name="<?php echo esc_attr( $field_key ); ?>[]"
								type="checkbox"
								class="mbfw-expert-form__checkbox <?php echo esc_attr( $field_key ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								<?php checked( $checked ); ?>
							/>
							<span class="mbfw-expert-form__checkbox-box"></span>
							<span><?php echo esc_html( $option_label ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<label class="mbfw-expert-form__label" for="<?php echo esc_attr( $field_id ); ?>">
					<?php echo esc_html( $field['label'] ); ?>
					<?php if ( ! empty( $field['required'] ) ) : ?>
						<span class="mbfw-expert-form__required">*</span>
					<?php endif; ?>
				</label>
				<?php if ( 'textarea' === $field['type'] ) : ?>
					<textarea
						id="<?php echo esc_attr( $field_id ); ?>"
						name="<?php echo esc_attr( $field_key ); ?>"
						class="mbfw-expert-form__control mbfw-expert-form__control--textarea <?php echo esc_attr( $field_key ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
						<?php echo ! empty( $field['required'] ) ? 'required' : ''; ?>
					><?php echo esc_textarea( $field_value ); ?></textarea>
				<?php elseif ( 'select' === $field['type'] ) : ?>
					<select
						id="<?php echo esc_attr( $field_id ); ?>"
						name="<?php echo esc_attr( $field_key ); ?>"
						class="mbfw-expert-form__control mbfw-expert-form__control--select <?php echo esc_attr( $field_key ); ?>"
						<?php echo ! empty( $field['required'] ) ? 'required' : ''; ?>
					>
						<?php foreach ( $field['options'] as $option_value => $option_label ) : ?>
							<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $field_value, $option_value ); ?> <?php echo empty( $option_value ) ? 'disabled' : ''; ?>>
								<?php echo esc_html( $option_label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<input
						id="<?php echo esc_attr( $field_id ); ?>"
						name="<?php echo esc_attr( $field_key ); ?>"
						type="<?php echo esc_attr( $field['type'] ); ?>"
						class="mbfw-expert-form__control <?php echo esc_attr( $field_key ); ?>"
						value="<?php echo esc_attr( $field_value ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
						<?php echo ! empty( $field['required'] ) ? 'required' : ''; ?>
					/>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get the list of available marketing services for the expert form.
	 *
	 * @return array
	 */
	private function mwb_mbfw_get_talk_to_expert_service_options() {
		return array(
			'seo_services'                      => esc_html__( 'SEO services', 'mwb-bookings-for-woocommerce' ),
			'google_ads_setup_and_ga4_setup'   => esc_html__( 'Google Ads Setup and GA4 setup', 'mwb-bookings-for-woocommerce' ),
			'speed_optimization'               => esc_html__( 'Speed Optimization', 'mwb-bookings-for-woocommerce' ),
			'woocommerce_development_services' => esc_html__( 'WooCommerce Development Services', 'mwb-bookings-for-woocommerce' ),
		);
	}

	/**
	 * Get available budget options for the expert form.
	 *
	 * @return array
	 */
	private function mwb_mbfw_get_talk_to_expert_budget_options() {
		return array(
			''            => 'Please Select',
			'500-1000'    => '$500 - $1000',
			'1001-5000'   => '$1001 - $5000',
			'5001-10000'  => '$5001 - $10000',
			'10001-15000' => '$10001 - $15000',
		);
	}

	/**
	 * Get default values from the current user and site context.
	 *
	 * @return array
	 */
	private function mwb_mbfw_get_default_field_values() {
		$current_user = wp_get_current_user();
		$first_name   = '';
		$last_name    = '';

		if ( ! empty( $current_user->first_name ) || ! empty( $current_user->last_name ) ) {
			$first_name = $current_user->first_name;
			$last_name  = $current_user->last_name;
		} elseif ( ! empty( $current_user->display_name ) ) {
			$name_parts = preg_split( '/\s+/', trim( $current_user->display_name ), 2 );
			$first_name = ! empty( $name_parts[0] ) ? $name_parts[0] : '';
			$last_name  = ! empty( $name_parts[1] ) ? $name_parts[1] : '';
		}

		return array(
			'firstname'                       => $first_name,
			'lastname'                        => $last_name,
			'email'                           => ! empty( $current_user->user_email ) ? $current_user->user_email : '',
			'phone'                           => '',
			'what_services_do_you_need_help_with' => array(),
			'budget'                          => '',
			'message'                         => '',
		);
	}

	/**
	 * AJAX handler for HubSpot form submission.
	 */
	public function mwb_mbfw_submit_talk_to_expert() {
		check_ajax_referer( 'mwb_mbfw_talk_to_expert_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'You are not allowed to submit this request.', 'mwb-bookings-for-woocommerce' ),
				),
				403
			);
		}

		$form_payload = isset( $_POST['form_data'] ) ? sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) : '';
		$form_data    = ! empty( $form_payload ) ? json_decode( $form_payload, true ) : array();

		if ( empty( $form_data ) || ! is_array( $form_data ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'The request could not be processed. Please try again.', 'mwb-bookings-for-woocommerce' ),
				),
				400
			);
		}

		$sanitized_data = $this->mwb_mbfw_sanitize_talk_to_expert_data( $form_data );

		if ( empty( $sanitized_data['email'] ) || ! is_email( $sanitized_data['email'] ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Please enter a valid email address.', 'mwb-bookings-for-woocommerce' ),
				),
				422
			);
		}

		$result = $this->mwb_mbfw_submit_hubspot_form(
			array_filter(
				array(
					$this->mwb_mbfw_prepare_hubspot_field( 'firstname', $sanitized_data['firstname'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'lastname', $sanitized_data['lastname'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'email', $sanitized_data['email'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'phone', $sanitized_data['phone'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'what_services_do_you_need_help_with', $sanitized_data['what_services_do_you_need_help_with'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'budget', $sanitized_data['budget'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'message', $sanitized_data['message'] ),
					$this->mwb_mbfw_prepare_hubspot_field( 'currency', $this->mwb_mbfw_get_store_currency() ),
					$this->mwb_mbfw_prepare_hubspot_field( 'org_plugin_name', self::$mwb_mbfw_plugin_name_label ),
					$this->mwb_mbfw_prepare_hubspot_field( 'company', self::$mwb_mbfw_store_name ),
					$this->mwb_mbfw_prepare_hubspot_field( 'website', self::$mwb_mbfw_store_url ),
					$this->mwb_mbfw_prepare_hubspot_field( 'country', $this->mwb_mbfw_get_store_country() ),
					$this->mwb_mbfw_prepare_hubspot_field( 'annualrevenue', $this->mwb_mbfw_get_store_annual_revenue() ),
				)
			)
		);

		if ( empty( $result['success'] ) ) {
			wp_send_json_error(
				array(
					'message' => $result['message'],
				),
				500
			);
		}

		wp_send_json_success(
			array(
				'message' => $result['message'],
			)
		);
	}

	/**
	 * Get the Talk to an Expert dashboard URL.
	 *
	 * @return string
	 */
	private function mwb_mbfw_get_talk_to_expert_dashboard_url() {
		return admin_url( 'admin.php?page=mwb_bookings_for_woocommerce_menu' );
	}

	/**
	 * Sanitize form data.
	 *
	 * @param array $form_data Raw request data.
	 * @return array
	 */
	private function mwb_mbfw_sanitize_talk_to_expert_data( $form_data ) {
		$allowed_services  = array_keys( $this->mwb_mbfw_get_talk_to_expert_service_options() );
		$allowed_budgets   = array_keys( $this->mwb_mbfw_get_talk_to_expert_budget_options() );
		$selected_services = isset( $form_data['what_services_do_you_need_help_with'] ) ? (array) $form_data['what_services_do_you_need_help_with'] : array();
		$selected_services = array_map( 'sanitize_text_field', $selected_services );
		$selected_services = array_values(
			array_filter(
				$selected_services,
				function ( $service ) use ( $allowed_services ) {
					return in_array( $service, $allowed_services, true );
				}
			)
		);
		$budget = isset( $form_data['budget'] ) ? sanitize_text_field( $form_data['budget'] ) : '';
		$budget = in_array( $budget, $allowed_budgets, true ) ? $budget : '';

		return array(
			'firstname'                       => isset( $form_data['firstname'] ) ? sanitize_text_field( $form_data['firstname'] ) : '',
			'lastname'                        => isset( $form_data['lastname'] ) ? sanitize_text_field( $form_data['lastname'] ) : '',
			'email'                           => isset( $form_data['email'] ) ? sanitize_email( $form_data['email'] ) : '',
			'phone'                           => isset( $form_data['phone'] ) ? sanitize_text_field( $form_data['phone'] ) : '',
			'what_services_do_you_need_help_with' => $selected_services,
			'budget'                          => isset( $form_data['budget'] ) ? $budget : '',
			'message'                         => isset( $form_data['message'] ) ? sanitize_textarea_field( $form_data['message'] ) : '',
		);
	}

	/**
	 * Prepare a HubSpot field payload.
	 *
	 * @param string       $field_name HubSpot field name.
	 * @param string|array $field_value Field value.
	 * @return array|null
	 */
	private function mwb_mbfw_prepare_hubspot_field( $field_name, $field_value ) {
		if ( is_array( $field_value ) ) {
			$field_value = implode( ';', $field_value );
		}

		if ( 'Please Select' === $field_value ) {
			return null;
		}

		if ( '' === $field_value || null === $field_value ) {
			return null;
		}

		return array(
			'name'  => $field_name,
			'value' => $field_value,
		);
	}

	/**
	 * Get the store currency code.
	 *
	 * @return string
	 */
	private function mwb_mbfw_get_store_currency() {
		if ( function_exists( 'get_woocommerce_currency' ) ) {
			return get_woocommerce_currency();
		}

		return '';
	}

	/**
	 * Get the store country label.
	 *
	 * @return string
	 */
	private function mwb_mbfw_get_store_country() {
		$default_country = get_option( 'woocommerce_default_country', '' );
		$country_parts   = explode( ':', $default_country );
		$country_code    = ! empty( $country_parts[0] ) ? $country_parts[0] : '';

		if ( empty( $country_code ) ) {
			return '';
		}

		if ( class_exists( 'WC_Countries' ) ) {
			$countries = new WC_Countries();
			if ( isset( $countries->countries[ $country_code ] ) ) {
				return $countries->countries[ $country_code ];
			}
		}

		return $country_code;
	}

	/**
	 * Get the store revenue for the last 12 months of paid orders.
	 *
	 * @return string
	 */
	private function mwb_mbfw_get_store_annual_revenue() {
		$stats_revenue = $this->mwb_mbfw_get_store_annual_revenue_from_stats();

		if ( null !== $stats_revenue ) {
			return $stats_revenue;
		}

		return $this->mwb_mbfw_get_store_annual_revenue_from_orders();
	}

	/**
	 * Get annual revenue from WooCommerce analytics order stats when available.
	 *
	 * @return string|null
	 */
	private function mwb_mbfw_get_store_annual_revenue_from_stats() {
		global $wpdb;

		if ( ! function_exists( 'wc_get_is_paid_statuses' ) || ! isset( $wpdb ) || empty( $wpdb->prefix ) ) {
			return null;
		}

		$table_name = esc_sql( $wpdb->prefix . 'wc_order_stats' );

		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) !== $table_name ) {
			return null;
		}

		$paid_statuses = array_map(
			function ( $status ) {
				return 0 === strpos( $status, 'wc-' ) ? $status : 'wc-' . $status;
			},
			(array) wc_get_is_paid_statuses()
		);

		if ( empty( $paid_statuses ) ) {
			return null;
		}

		$placeholders = implode( ', ', array_fill( 0, count( $paid_statuses ), '%s' ) );
		$cutoff_date  = gmdate( 'Y-m-d H:i:s', strtotime( '-12 months' ) );
		$query        = "
			SELECT COALESCE(SUM(total_sales), 0)
			FROM {$table_name}
			WHERE status IN ({$placeholders})
				AND parent_id = 0
				AND date_paid IS NOT NULL
				AND date_paid != '0000-00-00 00:00:00'
				AND date_paid >= %s
		";
		$revenue      = $wpdb->get_var( $wpdb->prepare( $query, array_merge( $paid_statuses, array( $cutoff_date ) ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( ! is_numeric( $revenue ) ) {
			return null;
		}

		return number_format( (float) $revenue, 2, '.', '' );
	}

	/**
	 * Fallback annual revenue using WooCommerce order queries.
	 *
	 * @return string
	 */
	private function mwb_mbfw_get_store_annual_revenue_from_orders() {
		if ( ! function_exists( 'wc_get_orders' ) || ! function_exists( 'wc_get_is_paid_statuses' ) ) {
			return '';
		}

		$orders = wc_get_orders(
			array(
				'status'    => array_values( (array) wc_get_is_paid_statuses() ),
				'type'      => 'shop_order',
				'limit'     => -1,
				'date_paid' => '>' . gmdate( 'Y-m-d H:i:s', strtotime( '-12 months' ) ),
				'return'    => 'objects',
			)
		);

		$total_revenue = 0.0;

		foreach ( $orders as $order ) {
			if ( is_object( $order ) && method_exists( $order, 'get_total' ) ) {
				$total_revenue += (float) $order->get_total();
			}
		}

		return number_format( $total_revenue, 2, '.', '' );
	}

	/**
	 * Submit the payload to HubSpot.
	 *
	 * @param array $fields Form fields.
	 * @return array
	 */
	private function mwb_mbfw_submit_hubspot_form( $fields ) {
		$fields = array_values( array_filter( $fields ) );

		$url     = $this->mwb_mbfw_base_url . 'submissions/v3/integration/submit/' . self::$mwb_mbfw_talk_to_expert_portal_id . '/' . self::$mwb_mbfw_talk_to_expert_form_id;
		$request = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(
				'Content-Type' => 'application/json',
			),
			'body'        => wp_json_encode(
				array(
					'fields'  => $fields,
					'context' => array(
						'pageUri'   => $this->mwb_mbfw_get_talk_to_expert_dashboard_url(),
						'pageName'  => self::$mwb_mbfw_store_name,
						'ipAddress' => $this->mwb_mbfw_get_client_ip(),
					),
				)
			),
			'cookies'     => array(),
		);

		$response = wp_remote_post( $url, $request );

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => esc_html__( 'The request could not be sent right now. Please try again shortly.', 'mwb-bookings-for-woocommerce' ),
			);
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$decoded     = json_decode( $body, true );

		if ( 200 === (int) $status_code ) {
			return array(
				'success' => true,
				'message' => $this->mwb_mbfw_get_success_message( $decoded ),
			);
		}

		return array(
			'success' => false,
			'message' => $this->mwb_mbfw_get_error_message( $decoded ),
		);
	}

	/**
	 * Get a readable success message from the HubSpot response.
	 *
	 * @param array $response Decoded API response.
	 * @return string
	 */
	private function mwb_mbfw_get_success_message( $response ) {
		if ( ! empty( $response['inlineMessage'] ) ) {
			$message = wp_strip_all_tags( $response['inlineMessage'] );
			$message = html_entity_decode( $message, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			$message = preg_replace( '/[\s\x{00A0}]+/u', ' ', $message );
			$message = trim( $message );

			if ( ! empty( $message ) ) {
				return $message;
			}
		}

		return esc_html__( 'Thank you for submitting your request. Our team will contact you soon.', 'mwb-bookings-for-woocommerce' );
	}

	/**
	 * Get a readable error message from the HubSpot response.
	 *
	 * @param array $response Decoded API response.
	 * @return string
	 */
	private function mwb_mbfw_get_error_message( $response ) {
		if ( ! empty( $response['errors'] ) && is_array( $response['errors'] ) ) {
			$first_error = reset( $response['errors'] );

			if ( ! empty( $first_error['message'] ) ) {
				return sanitize_text_field( $first_error['message'] );
			}
		}

		if ( ! empty( $response['message'] ) ) {
			return sanitize_text_field( $response['message'] );
		}

		return esc_html__( 'Something went wrong while submitting the form. Please try again.', 'mwb-bookings-for-woocommerce' );
	}

	/**
	 * Get the current client IP.
	 *
	 * @return string
	 */
	private function mwb_mbfw_get_client_ip() {
		$ipaddress = '';

		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		}

		return $ipaddress;
	}
}
