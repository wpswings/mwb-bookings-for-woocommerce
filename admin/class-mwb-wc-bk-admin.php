<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mwb_Wc_Bk_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb-wc-bk-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-wc-bk-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Include class for booking product type
	 */
	public function register_booking_product_type() {
		require_once MWB_WC_BK_BASEPATH . 'includes/class-mwb-wc-bk-product.php';
	}

	/**
	 * Add booking product option in products tab
	 *
	 * @param array $type Defining product type.
	 * @return $type
	 */
	public function add_mwb_booking_product_selector( $type ) {
		$type['mwb_booking'] = __( 'MWB Booking', 'mwb-wc-bk' );
		return $type;
	}
	/**
	 * Add virtual option for bookable product.
	 *
	 * @param array $options Contains the default virtual and downloadable options.
	 * @return array
	 */
	public function mwb_booking_virtual_product_options( $options ) {
		$options['virtual']['wrapper_class'] .= 'show_if_mwb_booking';
		return $options;
	}
	/**
	 * Add General Settings Tab for bookable product type
	 *
	 * @param array $tabs 
	 * @return array
	 */
	public function mwb_add_general_settings( $tabs ) {

		$tabs = array_merge(
			$tabs,
			array(
				'general_settings' => array(
					'label'    => 'General Settings',
					'target'   => 'mwb_product_general_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 10,
				),
				'cost'             => array(
					'label'    => 'Costs',
					'target'   => 'mwb_product_cost_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 20,
				),
				'availability'     => array(
					'label'    => 'Availability',
					'target'   => 'mwb_product_availability_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 30,
				),
				'people'           => array(
					'label'    => 'People',
					'target'   => 'mwb_product_people_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 40,
				),
				'services'         => array(
					'label'    => 'Services',
					'target'   => 'mwb_product_services_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 50,
				),
			)
		);
		return $tabs;
	}
	/**
	 * General Settings fields.
	 *
	 * @return void
	 */
	public function mwb_general_settings_fields() {
		?>
		<div id="mwb_product_general_data" class="panel woocommerce_options_panel show_if_mwb_booking">
			<div class="mwb_booking_setting_heading" >
				<h1><?php esc_html_e( 'General Settings', 'mwb-wc-bk' ); ?></h1>
			</div>
			<p class="form_fields">
				<label for="mwb_booking_unit"><?php esc_html_e( 'Booking Unit', 'mwb-wc-bk' ); ?></label>
				<select name="mwb_booking_unit" id="mwb_booking_unit" class="" style="width: auto; margin-right: 7px;">
					<option value="fixed"><?php esc_html_e( 'Fixed unit', 'mwb-wc-bk' ); ?></option>
					<option value="customer"><?php esc_html_e( 'Customer selected unit', 'mwb-wc-bk' ); ?></option>
				</select>
				<input type="number" name="_wc_booking_duration" id="_wc_booking_duration" value="1" step="1" min="1" style="margin-right: 7px; width: 4em;">
				<select name="mwb_booking_unit_duration" id="mwb_booking_unit_duration" class="" style="width: auto; margin-right: 7px;">
					<option value="month" ><?php esc_html_e( 'Month(s)', 'mwb-wc-bk' ); ?></option>
					<option value="day" ><?php esc_html_e( 'Day(s)', 'mwb-wc-bk' ); ?></option>
					<option value="hour" ><?php esc_html_e( 'Hour(s)', 'mwb-wc-bk' ); ?></option>
					<option value="minute"><?php esc_html_e( 'Minute(s)', 'mwb-wc-bk' ); ?></option>
				</select>
			</p>
			<div class="mwb_start_booking_from">
				<p class="form_fields">
					<label for="start_booking_date_from"><?php esc_html_e( 'Start Booking on date', 'mwb-wc-bk' ); ?></label>
					<select name="start_booking_date_from" id="start_booking_date_from">
						<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
						<option value="today"><?php esc_html_e( 'Today', 'mwb-wc-bk' ); ?></option>
						<option value="tomorrow"><?php esc_html_e( 'Tomorrow', 'mwb-wc-bk' ); ?></option>
						<option value="initially_available"><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
						<option value="custom_date"><?php esc_html_e( 'Custom Date', 'mwb-wc-bk' ); ?></option>
					</select>
					<label for="start_booking_time_from"><?php esc_html_e( 'Time:', 'mwb-wc-bk' ); ?></label>
					<select name="start_booking_time_from" id="start_booking_time_from">
						<option value="none"><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></option>
						<option value="initially_available"><?php esc_html_e( 'Initially Available', 'mwb-wc-bk' ); ?></option>
					</select>
				</p>
				<label for="mwb_booking_custom_date"><?php esc_html_e( 'Custom date to start booking', 'mwb-wc-bk' ); ?></label>
				<input id="mwb_booking_custom_date" type="text">
			</div>
			<div class="mwb_calendar_range">
				<?php
					woocommerce_wp_checkbox(
						array(
							'id'          => 'mwb_enable_range_picker',
							'label'       => __( 'Enable Calendar Range Picker', 'mwb-wc-bk' ),
							'value'       => 'yes',
							'description' => __( 'To select the start and end date on the calendar.', 'mwb-wc-bk' ),
						)
					);
				?>
			</div>
			<div class="mwb_full_day_select">
				<?php
					woocommerce_wp_checkbox(
						array(
							'id'          => 'mwb_full_day_booking',
							'label'       => __( 'Full Day Booking', 'mwb-wc-bk' ),
							'value'       => 'no',
							'description' => __( 'Booking for full day.', 'mwb-wc-bk' ),
						)
					);
				?>
			</div>
			<div class="mwb_admin_confirmation">
				<?php
					woocommerce_wp_checkbox(
						array(
							'id'          => 'mwb_admin_confirmation_required',
							'label'       => __( 'Confirmation Required', 'mwb-wc-bk' ),
							'value'       => 'no',
							'description' => __( 'Enable booking confirmation by the admin.', 'mwb-wc-bk' ),
						)
					);
				?>
			</div>
			<div class="mwb_booking_cancellation">
				<?php
					woocommerce_wp_checkbox(
						array(
							'id'          => 'mwb_allow_booking_cancellation',
							'label'       => __( 'Cancellation Allowed', 'mwb-wc-bk' ),
							'value'       => 'no',
							'description' => __( 'Allows user to cancel their booking.', 'mwb-wc-bk' ),
							'desc_tip'    => true,
						)
					);
					woocommerce_wp_text_input(
						array(
							'id'                => 'mwb_max_day_for_cancellation',
							'label'             => __( 'Max days to allow cancellation', 'mwb-wc-bk' ),
							'description'       => __( 'Maximum Day after which booking cancellation cannot be allowed.', 'mwb-wc-bk' ),
							'value'             => '',
							'desc_tip'          => true,
							'type'              => 'number',
							'style'             => 'width: auto; margin-right: 7px;',
							'custom_attributes' => array(
								'step' => '1',
								'min'  => '1',
							),
						)
					);
				?>
			</div>
		</div>	
		<?php
	}

}
