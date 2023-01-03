<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 */
class Mwb_Bookings_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 2.0.0
	 * @var   Mwb_Bookings_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 2.0.0
	 * @var   string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 2.0.0
	 * @var   string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since 2.0.0
	 * @var   string    $mbfw_onboard    To initializsed the object of class onboard.
	 */
	protected $mbfw_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area,
	 * the public-facing side of the site and common side of the site.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		if ( defined( 'MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION' ) ) {

			$this->version = MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION;
		} else {

			$this->version = '3.0.5';
		}

		$this->plugin_name = 'bookings-for-woocommerce';

		$this->mwb_bookings_for_woocommerce_dependencies();
		$this->mwb_bookings_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->mwb_bookings_for_woocommerce_admin_hooks();
		} else {
			$this->mwb_bookings_for_woocommerce_public_hooks();
		}
		$this->mwb_bookings_for_woocommerce_common_hooks();

		$this->mwb_bookings_for_woocommerce_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Bookings_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Bookings_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Mwb_Bookings_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Mwb_Bookings_For_Woocommerce_Common. Defines all hooks for the common area.
	 * - Mwb_Bookings_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 2.0.0
	 */
	private function mwb_bookings_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-bookings-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-bookings-for-woocommerce-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-bookings-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . 'onboarding' ) && ! class_exists( 'Mwb_Bookings_For_Woocommerce_Onboarding_Steps' ) ) {
				include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-bookings-for-woocommerce-onboarding-steps.php';
			}

			if ( class_exists( 'Mwb_Bookings_For_Woocommerce_Onboarding_Steps' ) ) {
				$mbfw_onboard_steps = new Mwb_Bookings_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-bookings-for-woocommerce-public.php';

		}

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-mwb-bookings-for-woocommerce-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-mwb-bookings-for-woocommerce-common.php';

		$this->loader = new Mwb_Bookings_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Bookings_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 2.0.0
	 */
	private function mwb_bookings_for_woocommerce_locale() {

		$plugin_i18n = new Mwb_Bookings_For_Woocommerce_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Define the name of the hook to save admin notices for this plugin.
	 *
	 * @since 2.0.0
	 */
	private function mwb_saved_notice_hook_name() {
		$mwb_plugin_name                            = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
		$mwb_plugin_settings_saved_notice_hook_name = $mwb_plugin_name . '_settings_saved_notice';
		return $mwb_plugin_settings_saved_notice_hook_name;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function mwb_bookings_for_woocommerce_admin_hooks() {
		$mbfw_plugin_admin = new Mwb_Bookings_For_Woocommerce_Admin( $this->mbfw_get_plugin_name(), $this->mbfw_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $mbfw_plugin_admin, 'mbfw_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $mbfw_plugin_admin, 'mbfw_admin_enqueue_scripts' );

		// Add settings menu for Mwb Bookings For WooCommerce.
		$this->loader->add_action( 'admin_menu', $mbfw_plugin_admin, 'mbfw_options_page' );
		$this->loader->add_action( 'admin_menu', $mbfw_plugin_admin, 'mwb_mbfw_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'wps_add_plugins_menus_array', $mbfw_plugin_admin, 'mbfw_admin_submenu_page', 15 );
		$this->loader->add_filter( 'mbfw_general_settings_array', $mbfw_plugin_admin, 'mbfw_admin_general_settings_page', 10 );
		$this->loader->add_filter( 'mbfw_booking_form_settings_array', $mbfw_plugin_admin, 'mbfw_booking_form_settings_page', 10 );
		$this->loader->add_filter( 'mbfw_availability_settings_array', $mbfw_plugin_admin, 'mbfw_add_availability_settings_page' );

		// Saving tab settings.
		$this->loader->add_action( 'mwb_mbfw_settings_saved_notice', $mbfw_plugin_admin, 'mbfw_admin_save_tab_settings' );
		$this->loader->add_action( 'init', $mbfw_plugin_admin, 'mwb_mbfw_migrate_settings_from_older_plugin' );

		// Developer's Hook Listing.
		$this->loader->add_action( 'mbfw_developer_admin_hooks_array', $mbfw_plugin_admin, 'mwb_developer_admin_hooks_listing' );
		$this->loader->add_action( 'mbfw_developer_public_hooks_array', $mbfw_plugin_admin, 'mwb_developer_public_hooks_listing' );

		// taxonomy page hooks v3.0.0.
		$this->loader->add_action( 'all_admin_notices', $mbfw_plugin_admin, 'mwb_bfw_taxonomy_page_display_html' );
		$this->loader->add_action( 'admin_footer', $mbfw_plugin_admin, 'mwb_bfw_footer_custom_taxonomy_edit_page_callback' );
		$this->loader->add_action( 'parent_file', $mbfw_plugin_admin, 'prefix_highlight_taxonomy_parent_menu' );
		$this->loader->add_filter( 'submenu_file', $mbfw_plugin_admin, 'mwb_bfw_set_submenu_file_to_handle_menu_for_wp_pages', 10, 2 );

		if ( 'yes' === get_option( 'mwb_mbfw_is_plugin_enable' ) ) {
			$this->loader->add_filter( 'product_type_selector', $mbfw_plugin_admin, 'mbfw_add_product_type_in_dropdown', 10, 1 );
			$this->loader->add_filter( 'woocommerce_product_data_tabs', $mbfw_plugin_admin, 'mbfw_add_product_data_tabs' );
			$this->loader->add_action( 'woocommerce_process_product_meta', $mbfw_plugin_admin, 'mbfw_save_custom_product_meta_boxes_data', 100, 2 );
			$this->loader->add_action( 'woocommerce_product_data_panels', $mbfw_plugin_admin, 'mbfw_product_data_tabs_html' );
			// customising booking_costs taxonomy.
			$this->loader->add_action( 'mwb_booking_cost_add_form_fields', $mbfw_plugin_admin, 'mbfw_adding_custom_fields_at_booking_cost_taxonomy_page' );
			$this->loader->add_action( 'mwb_booking_cost_edit_form_fields', $mbfw_plugin_admin, 'mbfw_adding_custom_fields_at_booking_cost_taxonomy_edit_page', 10, 2 );
			$this->loader->add_action( 'created_mwb_booking_cost', $mbfw_plugin_admin, 'mbfw_saving_custom_fields_at_booking_cost_taxonomy_page' );
			$this->loader->add_action( 'edited_mwb_booking_cost', $mbfw_plugin_admin, 'mbfw_saving_custom_fields_at_booking_cost_taxonomy_page' );
			$this->loader->add_filter( 'manage_edit-mwb_booking_cost_columns', $mbfw_plugin_admin, 'mbfw_adding_custom_column_booking_costs_taxonomy_table' );
			$this->loader->add_filter( 'manage_mwb_booking_cost_custom_column', $mbfw_plugin_admin, 'mbfw_adding_custom_column_data_booking_costs_taxonomy_table', 10, 3 );
			// customising booking_services taxonomy.
			$this->loader->add_action( 'mwb_booking_service_add_form_fields', $mbfw_plugin_admin, 'mbfw_adding_custom_fields_at_booking_service_taxonomy_page' );
			$this->loader->add_action( 'mwb_booking_service_edit_form_fields', $mbfw_plugin_admin, 'mbfw_adding_custom_fields_at_booking_service_taxonomy_edit_page', 10, 2 );
			$this->loader->add_action( 'created_mwb_booking_service', $mbfw_plugin_admin, 'mbfw_saving_custom_fields_at_booking_service_taxonomy_page' );
			$this->loader->add_action( 'edited_mwb_booking_service', $mbfw_plugin_admin, 'mbfw_saving_custom_fields_at_booking_service_taxonomy_page' );
			$this->loader->add_filter( 'manage_edit-mwb_booking_service_columns', $mbfw_plugin_admin, 'mbfw_adding_custom_column_booking_services_taxonomy_table' );
			$this->loader->add_filter( 'manage_mwb_booking_service_custom_column', $mbfw_plugin_admin, 'mbfw_adding_custom_column_data_booking_services_taxonomy_table', 10, 3 );
			// customisation on order listing page.
			$this->loader->add_action( 'manage_shop_order_posts_custom_column', $mbfw_plugin_admin, 'mbfw_add_label_for_booking_type', 20, 2 );
			$this->loader->add_action( 'restrict_manage_posts', $mbfw_plugin_admin, 'mbfw_add_filter_on_order_listing_page' );
			$this->loader->add_action( 'pre_get_posts', $mbfw_plugin_admin, 'mbfw_vary_query_to_list_only_booking_types' );
			$this->loader->add_action( 'woocommerce_hidden_order_itemmeta', $mbfw_plugin_admin, 'mbfw_hide_order_item_meta_data' );
			$this->loader->add_filter( 'woocommerce_order_item_display_meta_key', $mbfw_plugin_admin, 'mbfw_change_line_item_meta_key_order_edit_page', 10, 3 );
		}
		$this->loader->add_action( 'wp_ajax_mwb_mbfw_get_all_events_date', $mbfw_plugin_admin, 'mwb_mbfw_get_all_events_date' );
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function mwb_bookings_for_woocommerce_common_hooks() {
		$mbfw_plugin_common = new Mwb_Bookings_For_Woocommerce_Common( $this->mbfw_get_plugin_name(), $this->mbfw_get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $mbfw_plugin_common, 'mbfw_common_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $mbfw_plugin_common, 'mbfw_common_enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $mbfw_plugin_common, 'mbfw_common_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $mbfw_plugin_common, 'mbfw_common_enqueue_scripts' );
		if ( 'yes' === get_option( 'mwb_mbfw_is_plugin_enable' ) ) {
			$this->loader->add_action( 'plugins_loaded', $mbfw_plugin_common, 'mbfw_registering_custom_product_type' );
			$this->loader->add_action( 'init', $mbfw_plugin_common, 'mbfw_custom_taxonomy_for_products' );

			$this->loader->add_action( 'mwb_booking_cost_pre_add_form', $mbfw_plugin_common, 'mbfw_booking_cost_add_description' );
			$this->loader->add_action( 'mwb_booking_service_pre_add_form', $mbfw_plugin_common, 'mbfw_booking_services_add_description' );

			$this->loader->add_action( 'admin_bar_menu', $mbfw_plugin_common, 'mbfw_add_admin_menu_custom_tab', 100 );
			$this->loader->add_action( 'wp_ajax_mbfw_retrieve_booking_total_single_page', $mbfw_plugin_common, 'mbfw_retrieve_booking_total_single_page' );
			$this->loader->add_action( 'wp_ajax_nopriv_mbfw_retrieve_booking_total_single_page', $mbfw_plugin_common, 'mbfw_retrieve_booking_total_single_page' );
			$this->loader->add_action( 'woocommerce_before_calculate_totals', $mbfw_plugin_common, 'mwb_mbfw_show_extra_charges_in_total' );
			$this->loader->add_action( 'woocommerce_new_order', $mbfw_plugin_common, 'mwb_bfwp_set_order_as_mwb_booking', 10, 2 );
			$this->loader->add_action( 'woocommerce_thankyou', $mbfw_plugin_common, 'mwb_bfwp_change_order_status' );
			$this->loader->add_filter( 'woocommerce_valid_order_statuses_for_cancel', $mbfw_plugin_common, 'mwb_mbfw_set_cancel_order_link_order_statuses', 10, 2 );
			$this->loader->add_action( 'woocommerce_order_item_meta_end', $mbfw_plugin_common, 'mbfw_show_booking_details_on_my_account_page_user', 10, 3 );
			$this->loader->add_filter( 'woocommerce_valid_order_statuses_for_order_again', $mbfw_plugin_common, 'mwb_mbfw_hide_reorder_button_my_account_orders' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function mwb_bookings_for_woocommerce_public_hooks() {
		$mbfw_plugin_public = new Mwb_Bookings_For_Woocommerce_Public( $this->mbfw_get_plugin_name(), $this->mbfw_get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $mbfw_plugin_public, 'mbfw_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $mbfw_plugin_public, 'mbfw_public_enqueue_scripts' );
		if ( 'yes' === get_option( 'mwb_mbfw_is_plugin_enable' ) ) {
			$this->loader->add_filter( 'woocommerce_product_class', $mbfw_plugin_public, 'mbfw_return_custom_product_class', 10, 2 );
			$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $mbfw_plugin_public, 'mbfw_add_custom_fields_before_add_to_cart_button', 20 );
			$this->loader->add_action( 'mwb_mbfw_booking_services_details_on_form', $mbfw_plugin_public, 'mwb_mbfw_show_additional_booking_services_details_on_form', 10, 2 );
			$this->loader->add_action( 'mwb_mbfw_number_of_people_while_booking_on_form', $mbfw_plugin_public, 'mwb_mbfw_show_people_while_booking', 10, 2 );
			$this->loader->add_filter( 'woocommerce_add_cart_item_data', $mbfw_plugin_public, 'mwb_mbfw_add_additional_data_in_cart', 100, 4 );
			$this->loader->add_filter( 'woocommerce_get_item_data', $mbfw_plugin_public, 'mwb_mbfw_show_additional_data_on_cart_and_checkout_page', 10, 2 );
			$this->loader->add_action( 'woocommerce_mwb_booking_add_to_cart', $mbfw_plugin_public, 'mwb_mbfw_load_single_page_template' );
			$this->loader->add_action( 'woocommerce_loop_add_to_cart_link', $mbfw_plugin_public, 'mwb_mbfw_show_readmore_button_on_archieve_page', 10, 2 );
			$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $mbfw_plugin_public, 'mwb_mbfw_add_custom_order_item_meta_data', 10, 4 );
			$this->loader->add_action( 'mwb_mbfw_add_calender_or_time_selector_for_booking', $mbfw_plugin_public, 'mwb_mbfw_show_date_time_selector_on_single_product_page', 10, 2 );
			$this->loader->add_filter( 'woocommerce_quantity_input_args', $mbfw_plugin_public, 'mwb_mbfw_set_max_quantity_to_be_booked_by_individual', 10, 2 );
			$this->loader->add_action('mwb_booking_before_add_to_cart_button', $mbfw_plugin_public, 'mwb_mbfw_show_location_on_map', 10, 1 );

		}
	}

	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function mwb_bookings_for_woocommerce_api_hooks() {
		$mbfw_plugin_api = new Mwb_Bookings_For_Woocommerce_Rest_Api( $this->mbfw_get_plugin_name(), $this->mbfw_get_version() );
		$this->loader->add_action( 'rest_api_init', $mbfw_plugin_api, 'mwb_mbfw_add_endpoint' );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 2.0.0
	 */
	public function mbfw_run() {
		$this->loader->mbfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  2.0.0
	 * @return string    The name of the plugin.
	 */
	public function mbfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  2.0.0
	 * @return Mwb_Bookings_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function mbfw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  2.0.0
	 * @return Mwb_Bookings_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function mbfw_get_onboard() {
		return $this->mbfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  2.0.0
	 * @return string    The version number of the plugin.
	 */
	public function mbfw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_mbfw_plug tabs.
	 *
	 * @return array An key=>value pair of Mwb Bookings For WooCommerce tabs.
	 */
	public function mwb_mbfw_plug_default_tabs() {
		$mbfw_default_tabs = array();

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-general'] = array(
			'title'     => esc_html__( 'General Settings', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-general',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-general.php',
		);

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-configuration'] = array(
			'title'     => esc_html__( 'Configuration Settings', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-configuration',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-configuration.php',
		);

		$mbfw_default_tabs =
		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'mwb_mbfw_plugin_standard_admin_settings_tabs', $mbfw_default_tabs );

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-booking-calendar-listing'] = array(
			'title'     => esc_html__( 'Bookings Calendar', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-booking-calendar-listing',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-booking-calendar-listing.php',
		);

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-overview'] = array(
			'title'     => esc_html__( 'Overview', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-overview',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-overview.php',
		);

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-developer'] = array(
			'title'     => esc_html__( 'Developer', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-developer',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-developer.php',
		);
		return $mbfw_default_tabs;
	}
	/**
	 * Predefined default wps_bfw_plug tabs.
	 *
	 * @return array An key=>value pair of Bookings For WooCommerce tabs.
	 */
	public function mwb_bfw_plug_config_sub_tabs() {
		$mbfw_default_tabs = array();

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-booking-form-settings'] = array(
			'title'     => esc_html__( 'Booking Form Settings', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-booking-form-settings',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-booking-form-settings.php',
		);

		$mbfw_default_tabs['mwb-bookings-for-woocommerce-booking-availability-settings'] = array(
			'title'     => esc_html__( 'Availability Settings', 'mwb-bookings-for-woocommerce' ),
			'name'      => 'mwb-bookings-for-woocommerce-booking-availability-settings',
			'file_path' => MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-booking-availability-settings.php',
		);

		$mbfw_default_tabs =
		/**
		 * Filter is for returning something.
		 *
		 * @since 1.0.0
		 */
		apply_filters( 'mwb_bfw_configuration_settings_sub_tabs', $mbfw_default_tabs );

		return $mbfw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since 2.0.0
	 * @param string $path   path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_mbfw_plug_load_template( $path, $params = array() ) {
		if ( file_exists( $path ) ) {
			include $path;
		} else {
			/* translators: %s: file path */
			$mbfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'mwb-bookings-for-woocommerce' ), $path );
			$this->mwb_mbfw_plug_admin_notice( $mbfw_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param string $mbfw_message Message to display.
	 * @param string $type        notice type, accepted values - error/update/update-nag.
	 * @since 2.0.0
	 */
	public static function mwb_mbfw_plug_admin_notice( $mbfw_message, $type = 'error' ) {

		$mbfw_classes = 'notice ';
		switch ( $type ) {
			case 'update':
				$mbfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$mbfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$mbfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$mbfw_classes .= 'notice-error is-dismissible';
		}

		$mbfw_notice  = '<div class="' . esc_attr( $mbfw_classes ) . '">';
		$mbfw_notice .= '<p>' . esc_html( $mbfw_message ) . '</p>';
		$mbfw_notice .= '</div>';

		echo wp_kses_post( $mbfw_notice );
	}

	/**
	 * Generate html components.
	 *
	 * @param string $mbfw_components html to display.
	 * @since 2.0.0
	 */
	public function mwb_mbfw_plug_generate_html( $mbfw_components = array() ) {
		if ( is_array( $mbfw_components ) && ! empty( $mbfw_components ) ) {
			foreach ( $mbfw_components as $mbfw_component ) {
				if ( ! empty( $mbfw_component['type'] ) && ! empty( $mbfw_component['id'] ) ) {
					switch ( $mbfw_component['type'] ) {
						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
							<div class="mwb-form-group mwb-mbfw-<?php echo esc_attr( $mbfw_component['type'] ); ?>" style="<?php echo esc_attr( isset( $mbfw_component['parent-style'] ) ? $mbfw_component['parent-style'] : '' ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<?php if ( 'number' !== $mbfw_component['type'] ) { ?>
													<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?></span>
												<?php } ?>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input
										class="mdc-text-field__input <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>" 
										name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $mbfw_component['id'] ); ?>"
										type="<?php echo esc_attr( $mbfw_component['type'] ); ?>"
										value="<?php echo ( isset( $mbfw_component['value'] ) ? esc_attr( $mbfw_component['value'] ) : '' ); ?>"
										placeholder="<?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?>"
										<?php
										if ( isset( $mbfw_component['custom_attribute'] ) ) {
											$custom_attributes = $mbfw_component['custom_attribute'];
											foreach ( $custom_attributes as $attr_key => $attr_val ) {
												echo esc_attr( $attr_key . '=' . $attr_val . ' ' );
											}
										}
										?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mbfw_component['description'] ) ? esc_attr( $mbfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'password':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input 
										class="mdc-text-field__input <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?> mwb-form__password" 
										name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $mbfw_component['id'] ); ?>"
										type="<?php echo esc_attr( $mbfw_component['type'] ); ?>"
										value="<?php echo ( isset( $mbfw_component['value'] ) ? esc_attr( $mbfw_component['value'] ) : '' ); ?>"
										placeholder="<?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?>"
										>
										<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing mwb-password-hidden" tabindex="0" role="button">visibility</i>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mbfw_component['description'] ) ? esc_attr( $mbfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'textarea':
							?>
							<div class="mwb-form-group" style="<?php echo esc_attr( isset( $mbfw_component['parent-style'] ) ? $mbfw_component['parent-style'] : '' ); ?>">
								<div class="mwb-form-group__label">
									<label class="mwb-form-label" for="<?php echo esc_attr( $mbfw_component['id'] ); ?>"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"      for="text-field-hero-input">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<span class="mdc-floating-label"><?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?></span>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<span class="mdc-text-field__resizer">
											<textarea class="mdc-text-field__input <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>" id="<?php echo esc_attr( $mbfw_component['id'] ); ?>" placeholder="<?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?>"><?php echo ( isset( $mbfw_component['value'] ) ? esc_textarea( $mbfw_component['value'] ) : '' ); ?></textarea>
										</span>
									</label>
								</div>
							</div>
								<?php
							break;

						case 'select':
						case 'multiselect':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label class="mwb-form-label" for="<?php echo esc_attr( $mbfw_component['id'] ); ?>"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<div class="mwb-form-select">
										<select id="<?php echo esc_attr( $mbfw_component['id'] ); ?>" name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?><?php echo ( 'multiselect' === $mbfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $mbfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
											<?php
											foreach ( $mbfw_component['options'] as $mbfw_key => $mbfw_val ) {
												?>
												<option value="<?php echo esc_attr( $mbfw_key ); ?>"
													<?php
													if ( is_array( $mbfw_component['value'] ) ) {
														selected( in_array( (string) $mbfw_key, $mbfw_component['value'], true ), true );
													} else {
														selected( $mbfw_component['value'], (string) $mbfw_key );
													}
													?>
													>
													<?php echo esc_html( $mbfw_val ); ?>
												</option>
												<?php
											}
											?>
										</select>
										<label class="mdl-textfield__label" for="<?php echo esc_attr( $mbfw_component['id'] ); ?>"><?php echo esc_html( $mbfw_component['description'] ); ?></label>
									</div>
								</div>
							</div>
								<?php
							break;

						case 'checkbox':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control mwb-pl-4">
									<div class="mdc-form-field">
										<div class="mdc-checkbox">
											<input 
											name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
											id="<?php echo esc_attr( $mbfw_component['id'] ); ?>"
											type="checkbox"
											class="mdc-checkbox__native-control <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>"
											value="<?php echo ( isset( $mbfw_component['value'] ) ? esc_attr( $mbfw_component['value'] ) : '' ); ?>"
											<?php checked( $mbfw_component['value'], '1' ); ?>
											/>
											<div class="mdc-checkbox__background">
												<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
													<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
												</svg>
												<div class="mdc-checkbox__mixedmark"></div>
											</div>
											<div class="mdc-checkbox__ripple"></div>
										</div>
										<label for="checkbox-1"><?php echo ( isset( $mbfw_component['description'] ) ? esc_attr( $mbfw_component['description'] ) : '' ); ?></label>
									</div>
								</div>
							</div>
								<?php
							break;

						case 'radio':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control mwb-pl-4">
									<div class="mwb-flex-col">
										<?php
										foreach ( $mbfw_component['options'] as $mbfw_radio_key => $mbfw_radio_val ) {
											?>
											<div class="mdc-form-field">
												<div class="mdc-radio">
													<input
													name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
													value="<?php echo esc_attr( $mbfw_radio_key ); ?>"
													type="radio"
													class="mdc-radio__native-control <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>"
													<?php checked( $mbfw_radio_key, $mbfw_component['value'] ); ?>
													>
													<div class="mdc-radio__background">
														<div class="mdc-radio__outer-circle"></div>
														<div class="mdc-radio__inner-circle"></div>
													</div>
													<div class="mdc-radio__ripple"></div>
												</div>
												<label for="radio-1"><?php echo esc_html( $mbfw_radio_val ); ?></label>
											</div>
											<?php
										}
										?>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'radio-switch':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<div>
										<div class="mdc-switch">
											<div class="mdc-switch__track"></div>
											<div class="mdc-switch__thumb-underlay">
												<div class="mdc-switch__thumb"></div>
												<input
												name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
												type="checkbox"
												id="<?php echo esc_html( $mbfw_component['id'] ); ?>"
												value="yes"
												class="mdc-switch__native-control <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>"
												role="switch" 
												aria-checked="<?php echo esc_html( 'yes' === $mbfw_component['value'] ) ? 'true' : 'false'; ?>"
												<?php checked( $mbfw_component['value'], 'yes' ); ?>
												>
											</div>
										</div>
									</div>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mbfw_component['description'] ) ? wp_kses_post( $mbfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;

						case 'button':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label"></div>
								<div class="mwb-form-group__control">
									<button class="mdc-button mdc-button--raised" name= "<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $mbfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>"><?php echo ( isset( $mbfw_component['button_text'] ) ? esc_html( $mbfw_component['button_text'] ) : '' ); ?></span>
									</button>
								</div>
							</div>

								<?php
							break;

						case 'multi':
							?>
							<div class="mwb-form-group mwb-mbfw-<?php echo esc_attr( $mbfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
									</div>
									<div class="mwb-form-group__control">
										<?php
										foreach ( $mbfw_component['value'] as $component ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input 
												class="mdc-text-field__input <?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( $component['type'] ); ?>"
												value="<?php echo ( isset( $mbfw_component['value'] ) ? esc_attr( $mbfw_component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $mbfw_component['placeholder'] ) ? esc_attr( $mbfw_component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'max=10 min=0' : '' ); ?>
												>
											</label>
										<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mbfw_component['description'] ) ? esc_attr( $mbfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'color':
						case 'date':
						case 'file':
						case 'time':
							?>
							<div class="mwb-form-group mwb-mbfw-<?php echo esc_attr( $mbfw_component['type'] ); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mbfw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label>
										<input 
										class="<?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>" 
										name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $mbfw_component['id'] ); ?>"
										type="<?php echo esc_attr( ( 'date' === $mbfw_component['type'] || 'time' === $mbfw_component['type'] ) ? 'text' : $mbfw_component['type'] ); ?>"
										value="<?php echo ( isset( $mbfw_component['value'] ) ? esc_attr( $mbfw_component['value'] ) : '' ); ?>"
										autocomplete="off"
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset( $mbfw_component['description'] ) ? esc_attr( $mbfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'submit':
							?>
							<tr valign="top">
								<td scope="row">
									<input type="submit" class="button button-primary" 
									name="<?php echo ( isset( $mbfw_component['name'] ) ? esc_html( $mbfw_component['name'] ) : esc_html( $mbfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $mbfw_component['id'] ); ?>"
									class="<?php echo ( isset( $mbfw_component['class'] ) ? esc_attr( $mbfw_component['class'] ) : '' ); ?>"
									value="<?php echo esc_attr( $mbfw_component['button_text'] ); ?>"
									/>
								</td>
							</tr>
								<?php
							break;
						case 'availability_select':
							?>
							<div class="mbfw-admin-suggestion-text"><?php esc_html_e( 'Availability by Days', 'mwb-bookings-for-woocommerce' ); ?></div>
							<?php
							$sub_tabs = $mbfw_component['sub_tabs'];
							foreach ( $sub_tabs as $title => $mbfw_sub_components ) {
								?>
								<div class="mwb-form-group">
									<div class="mwb-form-group__label">
										<div><?php echo esc_html( $title ); ?></div>
									</div>
									<div class="mwb-form-group__control">
										<div class="mbfw-avl-days-wrap">
											<?php foreach ( $mbfw_sub_components as $sub_components ) { ?>
												<div class="mbfw-avl-days">
													<label for="" class="mwb-form-label"><?php echo ( isset( $sub_components['label'] ) ? esc_html( $sub_components['label'] ) : '' ); ?></label>
													<div class="mbfw-avl-days-time">
														<input type="text" name="<?php echo esc_attr( isset( $sub_components['name'] ) ? $sub_components['name'] : '' ); ?>" id="<?php echo esc_attr( isset( $sub_components['id'] ) ? $sub_components['id'] : '' ); ?>" value="<?php echo esc_attr( isset( $sub_components['value'] ) ? $sub_components['value'] : '' ); ?>" class="<?php echo esc_attr( isset( $sub_components['class'] ) ? $sub_components['class'] : '' ); ?>" autocomplete="off">
														<span class="dashicons dashicons-clock"></span>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
								<?php
							}
							break;
						case 'full_calendar':
							?>
							<input id="<?php echo esc_attr( isset( $mbfw_component['id'] ) ? $mbfw_component['id'] : '' ); ?>" class="<?php echo esc_attr( isset( $mbfw_component['class'] ) ? $mbfw_component['class'] : '' ); ?>" type="text" autocomplete="off"/>
							<?php
							break;
						case 'heading':
							?>
							<div class="mbfw-admin-suggestion-text"><?php echo ( isset( $mbfw_component['title'] ) ? esc_html( $mbfw_component['title'] ) : '' ); ?></div>
							<?php
							break;
						default:
							break;
					}
				}
			}
		}
	}
}
