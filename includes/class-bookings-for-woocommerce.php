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
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/includes
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
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/includes
 */
class Bookings_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 2.0.0
	 * @var   Bookings_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @var   string    $bfw_onboard    To initializsed the object of class onboard.
	 */
	protected $bfw_onboard;

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

		if (defined('BOOKINGS_FOR_WOOCOMMERCE_VERSION') ) {

			$this->version = BOOKINGS_FOR_WOOCOMMERCE_VERSION;
		} else {

			$this->version = '3.0.0';
		}

		$this->plugin_name = 'bookings-for-woocommerce';

		$this->bookings_for_woocommerce_dependencies();
		$this->bookings_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->bookings_for_woocommerce_admin_hooks();
		} else {
			$this->bookings_for_woocommerce_public_hooks();
		}
		$this->bookings_for_woocommerce_common_hooks();

		$this->bookings_for_woocommerce_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bookings_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Bookings_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Bookings_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Bookings_For_Woocommerce_Common. Defines all hooks for the common area.
	 * - Bookings_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 2.0.0
	 */
	private function bookings_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bookings-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bookings-for-woocommerce-i18n.php';

		if (is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			include_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-bookings-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if (is_dir(plugin_dir_path(dirname(__FILE__)) . 'onboarding') && ! class_exists('Bookings_For_Woocommerce_Onboarding_Steps') ) {
				include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bookings-for-woocommerce-onboarding-steps.php';
			}

			if (class_exists('Bookings_For_Woocommerce_Onboarding_Steps') ) {
				$bfw_onboard_steps = new Bookings_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			include_once plugin_dir_path(dirname(__FILE__)) . 'public/class-bookings-for-woocommerce-public.php';

		}

		include_once plugin_dir_path(dirname(__FILE__)) . 'package/rest-api/class-bookings-for-woocommerce-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path(dirname(__FILE__)) . 'common/class-bookings-for-woocommerce-common.php';
		
		$this->loader = new Bookings_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bookings_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 2.0.0
	 */
	private function bookings_for_woocommerce_locale() {

		$plugin_i18n = new Bookings_For_Woocommerce_I18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

	}

	/**
	 * Define the name of the hook to save admin notices for this plugin.
	 *
	 * @since 2.0.0
	 */
	private function wps_saved_notice_hook_name() {
		$wps_plugin_name                            = ! empty(explode('/', plugin_basename(__FILE__))) ? explode('/', plugin_basename(__FILE__))[0] : '';
		$wps_plugin_settings_saved_notice_hook_name = $wps_plugin_name . '_settings_saved_notice';
		return $wps_plugin_settings_saved_notice_hook_name;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function bookings_for_woocommerce_admin_hooks() {
		$bfw_plugin_admin = new Bookings_For_Woocommerce_Admin($this->bfw_get_plugin_name(), $this->bfw_get_version());

		$this->loader->add_action('admin_enqueue_scripts', $bfw_plugin_admin, 'bfw_admin_enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $bfw_plugin_admin, 'bfw_admin_enqueue_scripts');

		// Add settings menu for Bookings For WooCommerce.
		$this->loader->add_action('admin_menu', $bfw_plugin_admin, 'bfw_options_page');
		$this->loader->add_action('admin_menu', $bfw_plugin_admin, 'wps_bfw_remove_default_submenu', 50);

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter('wps_add_plugins_menus_array', $bfw_plugin_admin, 'bfw_admin_submenu_page', 15);
		$this->loader->add_filter('bfw_general_settings_array', $bfw_plugin_admin, 'bfw_admin_general_settings_page', 10);
		$this->loader->add_filter('bfw_booking_form_settings_array', $bfw_plugin_admin, 'bfw_booking_form_settings_page', 10);
		$this->loader->add_filter( 'bfw_availability_settings_array', $bfw_plugin_admin, 'bfw_add_availability_settings_page' );


		// Saving tab settings.
		$this->loader->add_action('wps_bfw_settings_saved_notice', $bfw_plugin_admin, 'bfw_admin_save_tab_settings');
		$this->loader->add_action( 'init', $bfw_plugin_admin, 'wps_bfw_migrate_settings_from_older_plugin' );

		//Developer's Hook Listing.
		$this->loader->add_action('bfw_developer_admin_hooks_array', $bfw_plugin_admin, 'wps_developer_admin_hooks_listing');
		$this->loader->add_action('bfw_developer_public_hooks_array', $bfw_plugin_admin, 'wps_developer_public_hooks_listing');
		$this->loader->add_action( 'wp_ajax_wps_bfw_ajax_callbacks', $bfw_plugin_admin, 'wps_bfw_ajax_callbacks' );

		if ( 'yes' === get_option( 'wps_bfw_is_plugin_enable' ) ) {
			$this->loader->add_filter( 'product_type_selector', $bfw_plugin_admin, 'bfw_add_product_type_in_dropdown', 10, 1 );
			$this->loader->add_filter( 'woocommerce_product_data_tabs', $bfw_plugin_admin, 'bfw_add_product_data_tabs' );
			$this->loader->add_action( 'woocommerce_process_product_meta', $bfw_plugin_admin, 'bfw_save_custom_product_meta_boxes_data', 100, 2 );
			$this->loader->add_action( 'woocommerce_product_data_panels', $bfw_plugin_admin, 'bfw_product_data_tabs_html' );
			// customising booking_costs taxonomy.
			$this->loader->add_action( 'wps_booking_cost_add_form_fields', $bfw_plugin_admin, 'bfw_adding_custom_fields_at_booking_cost_taxonomy_page' );
			$this->loader->add_action( 'wps_booking_cost_edit_form_fields', $bfw_plugin_admin, 'bfw_adding_custom_fields_at_booking_cost_taxonomy_edit_page', 10, 2 );
			$this->loader->add_action( 'created_wps_booking_cost', $bfw_plugin_admin, 'bfw_saving_custom_fields_at_booking_cost_taxonomy_page' );
			$this->loader->add_action( 'edited_wps_booking_cost', $bfw_plugin_admin, 'bfw_saving_custom_fields_at_booking_cost_taxonomy_page' );
			$this->loader->add_filter( 'manage_edit-wps_booking_cost_columns', $bfw_plugin_admin, 'bfw_adding_custom_column_booking_costs_taxonomy_table' );
			$this->loader->add_filter( 'manage_wps_booking_cost_custom_column', $bfw_plugin_admin, 'bfw_adding_custom_column_data_booking_costs_taxonomy_table', 10, 3 );
			// customising booking_services taxonomy.
			$this->loader->add_action( 'wps_booking_service_add_form_fields', $bfw_plugin_admin, 'bfw_adding_custom_fields_at_booking_service_taxonomy_page' );
			$this->loader->add_action( 'wps_booking_service_edit_form_fields', $bfw_plugin_admin, 'bfw_adding_custom_fields_at_booking_service_taxonomy_edit_page', 10, 2 );
			$this->loader->add_action( 'created_wps_booking_service', $bfw_plugin_admin, 'bfw_saving_custom_fields_at_booking_service_taxonomy_page' );
			$this->loader->add_action( 'edited_wps_booking_service', $bfw_plugin_admin, 'bfw_saving_custom_fields_at_booking_service_taxonomy_page' );
			$this->loader->add_filter( 'manage_edit-wps_booking_service_columns', $bfw_plugin_admin, 'bfw_adding_custom_column_booking_services_taxonomy_table' );
			$this->loader->add_filter( 'manage_wps_booking_service_custom_column', $bfw_plugin_admin, 'bfw_adding_custom_column_data_booking_services_taxonomy_table', 10, 3 );
			// customisation on order listing page.
			$this->loader->add_action( 'manage_shop_order_posts_custom_column', $bfw_plugin_admin, 'bfw_add_label_for_booking_type', 20, 2 );
			$this->loader->add_action( 'restrict_manage_posts', $bfw_plugin_admin, 'bfw_add_filter_on_order_listing_page' );
			$this->loader->add_action( 'pre_get_posts', $bfw_plugin_admin, 'bfw_vary_query_to_list_only_booking_types' );
			$this->loader->add_action( 'woocommerce_hidden_order_itemmeta', $bfw_plugin_admin, 'bfw_hide_order_item_meta_data' );
			$this->loader->add_filter( 'woocommerce_order_item_display_meta_key', $bfw_plugin_admin, 'bfw_change_line_item_meta_key_order_edit_page', 10, 3 );
		}
		$this->loader->add_action( 'wp_ajax_wps_bfw_get_all_events_date', $bfw_plugin_admin, 'wps_bfw_get_all_events_date' );
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function bookings_for_woocommerce_common_hooks() {
		$bfw_plugin_common = new Bookings_For_Woocommerce_Common($this->bfw_get_plugin_name(), $this->bfw_get_version());
		$this->loader->add_action( 'wp_enqueue_scripts', $bfw_plugin_common, 'bfw_common_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $bfw_plugin_common, 'bfw_common_enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $bfw_plugin_common, 'bfw_common_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $bfw_plugin_common, 'bfw_common_enqueue_scripts' );
		if ( 'yes' === get_option( 'wps_bfw_is_plugin_enable' ) ) {
			$this->loader->add_action( 'plugins_loaded', $bfw_plugin_common, 'bfw_registering_custom_product_type' );
			$this->loader->add_action( 'init', $bfw_plugin_common, 'bfw_custom_taxonomy_for_products' );
			$this->loader->add_action( 'admin_bar_menu', $bfw_plugin_common, 'bfw_add_admin_menu_custom_tab', 100 );
			$this->loader->add_action( 'wp_ajax_bfw_retrieve_booking_total_single_page', $bfw_plugin_common, 'bfw_retrieve_booking_total_single_page' );
			$this->loader->add_action( 'wp_ajax_nopriv_bfw_retrieve_booking_total_single_page', $bfw_plugin_common, 'bfw_retrieve_booking_total_single_page' );
			$this->loader->add_action( 'woocommerce_before_calculate_totals', $bfw_plugin_common, 'wps_bfw_show_extra_charges_in_total' );
			$this->loader->add_action( 'woocommerce_new_order', $bfw_plugin_common, 'wps_bfwp_set_order_as_wps_booking', 10, 2 );
			$this->loader->add_action( 'woocommerce_thankyou', $bfw_plugin_common, 'wps_bfwp_change_order_status' );
			$this->loader->add_filter( 'woocommerce_valid_order_statuses_for_cancel', $bfw_plugin_common, 'wps_bfw_set_cancel_order_link_order_statuses', 10, 2 );
			$this->loader->add_action( 'woocommerce_order_item_meta_end', $bfw_plugin_common, 'bfw_show_booking_details_on_my_account_page_user', 10, 3 );
			$this->loader->add_filter( 'woocommerce_valid_order_statuses_for_order_again', $bfw_plugin_common, 'wps_bfw_hide_reorder_button_my_account_orders' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function bookings_for_woocommerce_public_hooks() {
		$bfw_plugin_public = new Bookings_For_Woocommerce_Public($this->bfw_get_plugin_name(), $this->bfw_get_version());
		$this->loader->add_action('wp_enqueue_scripts', $bfw_plugin_public, 'bfw_public_enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $bfw_plugin_public, 'bfw_public_enqueue_scripts');
		if ( 'yes' === get_option( 'wps_bfw_is_plugin_enable' ) ) {
			$this->loader->add_filter( 'woocommerce_product_class', $bfw_plugin_public, 'bfw_return_custom_product_class', 10, 2 );
			$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $bfw_plugin_public, 'bfw_add_custom_fields_before_add_to_cart_button', 20 );
			$this->loader->add_action( 'wps_bfw_booking_services_details_on_form', $bfw_plugin_public, 'wps_bfw_show_additional_booking_services_details_on_form', 10, 2 );
			$this->loader->add_action( 'wps_bfw_number_of_people_while_booking_on_form', $bfw_plugin_public, 'wps_bfw_show_people_while_booking', 10, 2 );
			$this->loader->add_filter( 'woocommerce_add_cart_item_data', $bfw_plugin_public, 'wps_bfw_add_additional_data_in_cart', 100, 4 );
			$this->loader->add_filter( 'woocommerce_get_item_data', $bfw_plugin_public, 'wps_bfw_show_additional_data_on_cart_and_checkout_page', 10, 2 );
			$this->loader->add_action( 'woocommerce_wps_booking_add_to_cart', $bfw_plugin_public, 'wps_bfw_load_single_page_template' );
			$this->loader->add_action( 'woocommerce_loop_add_to_cart_link', $bfw_plugin_public, 'wps_bfw_show_readmore_button_on_archieve_page', 10, 2 );
			$this->loader->add_action( 'woocommerce_checkout_create_order_line_item' , $bfw_plugin_public, 'wps_bfw_add_custom_order_item_meta_data', 10, 4 );
			$this->loader->add_action( 'wps_bfw_add_calender_or_time_selector_for_booking', $bfw_plugin_public, 'wps_bfw_show_date_time_selector_on_single_product_page', 10, 2 );
			$this->loader->add_filter( 'woocommerce_quantity_input_args', $bfw_plugin_public, 'wps_bfw_set_max_quantity_to_be_booked_by_individual', 10, 2 );
		}
	}

	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since 2.0.0
	 */
	private function bookings_for_woocommerce_api_hooks() {
		$bfw_plugin_api = new Bookings_For_Woocommerce_Rest_Api($this->bfw_get_plugin_name(), $this->bfw_get_version());
		$this->loader->add_action('rest_api_init', $bfw_plugin_api, 'wps_bfw_add_endpoint');
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 2.0.0
	 */
	public function bfw_run() {
		$this->loader->bfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  2.0.0
	 * @return string    The name of the plugin.
	 */
	public function bfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  2.0.0
	 * @return Bookings_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function bfw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  2.0.0
	 * @return Bookings_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function bfw_get_onboard() {
		return $this->bfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  2.0.0
	 * @return string    The version number of the plugin.
	 */
	public function bfw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default wps_bfw_plug tabs.
	 *
	 * @return array An key=>value pair of Bookings For WooCommerce tabs.
	 */
	public function wps_bfw_plug_default_tabs() {
		$bfw_default_tabs = array();

		$bfw_default_tabs['bookings-for-woocommerce-general'] = array(
			'title'       => esc_html__('General Settings', 'bookings-for-woocommerce'),
			'name'        => 'bookings-for-woocommerce-general',
			'file_path'   => BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-general.php'
		);

		$bfw_default_tabs['bookings-for-woocommerce-booking-form-settings'] = array(
			'title'       => esc_html__( 'Booking Form Settings', 'bookings-for-woocommerce' ),
			'name'        => 'bookings-for-woocommerce-booking-form-settings',
			'file_path'   =>  BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-booking-form-settings.php'
		);

		$bfw_default_tabs['bookings-for-woocommerce-booking-availability-settings'] = array(
			'title'       => esc_html__( 'Availability Settings', 'bookings-for-woocommerce' ),
			'name'        => 'bookings-for-woocommerce-booking-availability-settings',
			'file_path'   =>  BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-booking-availability-settings.php'
		);

		$bfw_default_tabs['bookings-for-woocommerce-booking-calendar-listing'] = array(
			'title'       => esc_html__( 'Bookings Calendar', 'bookings-for-woocommerce' ),
			'name'        => 'bookings-for-woocommerce-booking-calendar-listing',
			'file_path'   =>  BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-booking-calendar-listing.php'
		);

		$bfw_default_tabs = 
		//desc - add admin setting tabs.
		apply_filters('wps_bfw_plugin_standard_admin_settings_tabs', $bfw_default_tabs);

		$bfw_default_tabs['bookings-for-woocommerce-overview'] = array(
			'title'       => esc_html__('Overview', 'bookings-for-woocommerce'),
			'name'        => 'bookings-for-woocommerce-overview',
			'file_path'   => BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-overview.php'
		);
		
		$bfw_default_tabs['bookings-for-woocommerce-developer'] = array(
			'title'       => esc_html__('Developer', 'bookings-for-woocommerce'),
			'name'        => 'bookings-for-woocommerce-developer',
			'file_path'   => BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-developer.php'
		);
		return $bfw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since 2.0.0
	 * @param string $path   path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function wps_bfw_plug_load_template( $path, $params = array() ) {
		if (file_exists($path) ) {
			include $path;
		} else {
			/* translators: %s: file path */
			$bfw_notice = sprintf(esc_html__('Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'bookings-for-woocommerce'), $path);
			$this->wps_bfw_plug_admin_notice($bfw_notice, 'error');
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param string $bfw_message Message to display.
	 * @param string $type        notice type, accepted values - error/update/update-nag.
	 * @since 2.0.0
	 */
	public static function wps_bfw_plug_admin_notice( $bfw_message, $type = 'error' ) {

		$bfw_classes = 'notice ';
		switch ( $type ) {
			case 'update':
				$bfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$bfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$bfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$bfw_classes .= 'notice-error is-dismissible';
		}

		$bfw_notice  = '<div class="' . esc_attr( $bfw_classes ) . '">';
		$bfw_notice .= '<p>' . esc_html( $bfw_message ) . '</p>';
		$bfw_notice .= '</div>';

		echo wp_kses_post($bfw_notice);
	}

	/**
	 * Generate html components.
	 *
	 * @param string $bfw_components html to display.
	 * @since 2.0.0
	 */
	public function wps_bfw_plug_generate_html( $bfw_components = array() ) {
		if (is_array($bfw_components) && ! empty($bfw_components) ) {
			foreach ( $bfw_components as $bfw_component ) {
				if (! empty($bfw_component['type']) && ! empty($bfw_component['id']) ) {
					switch ( $bfw_component['type'] ) {
						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
							<div class="wps-form-group wps-mbfw-<?php echo esc_attr($bfw_component['type']); ?>" style="<?php echo esc_attr( isset( $bfw_component['parent-style'] ) ? $bfw_component['parent-style'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr($bfw_component['id']); ?>" class="wps-form-label"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<?php if ('number' !== $bfw_component['type'] ) { ?>
													<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset($bfw_component['placeholder']) ? esc_attr($bfw_component['placeholder']) : '' ); ?></span>
												<?php } ?>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input
										class="mdc-text-field__input <?php echo ( isset( $bfw_component['class'] ) ? esc_attr( $bfw_component['class'] ) : '' ); ?>" 
										name="<?php echo ( isset( $bfw_component['name'] ) ? esc_html( $bfw_component['name'] ) : esc_html( $bfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $bfw_component['id'] ); ?>"
										type="<?php echo esc_attr( $bfw_component['type'] ); ?>"
										value="<?php echo ( isset( $bfw_component['value'] ) ? esc_attr( $bfw_component['value'] ) : '' ); ?>"
										placeholder="<?php echo ( isset( $bfw_component['placeholder'] ) ? esc_attr( $bfw_component['placeholder'] ) : '' ); ?>"
										<?php
										if ( isset( $bfw_component['custom_attribute'] ) ) {
											$custom_attributes = $bfw_component['custom_attribute'];
											foreach ( $custom_attributes as $attr_key => $attr_val ) {
												echo esc_attr( $attr_key . '=' . $attr_val . ' ' );
											}
										}
										?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset($bfw_component['description']) ? esc_attr($bfw_component['description']) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'password':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr($bfw_component['id']); ?>" class="wps-form-label"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input 
										class="mdc-text-field__input <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?> wps-form__password" 
										name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
										id="<?php echo esc_attr($bfw_component['id']); ?>"
										type="<?php echo esc_attr($bfw_component['type']); ?>"
										value="<?php echo ( isset($bfw_component['value']) ? esc_attr($bfw_component['value']) : '' ); ?>"
										placeholder="<?php echo ( isset($bfw_component['placeholder']) ? esc_attr($bfw_component['placeholder']) : '' ); ?>"
										>
										<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing wps-password-hidden" tabindex="0" role="button">visibility</i>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset($bfw_component['description']) ? esc_attr($bfw_component['description']) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'textarea':
							?>
							<div class="wps-form-group" style="<?php echo esc_attr( isset( $bfw_component['parent-style'] ) ? $bfw_component['parent-style'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label class="wps-form-label" for="<?php echo esc_attr($bfw_component['id']); ?>"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"      for="text-field-hero-input">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<span class="mdc-floating-label"><?php echo ( isset($bfw_component['placeholder']) ? esc_attr($bfw_component['placeholder']) : '' ); ?></span>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<span class="mdc-text-field__resizer">
											<textarea class="mdc-text-field__input <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>" id="<?php echo esc_attr($bfw_component['id']); ?>" placeholder="<?php echo ( isset($bfw_component['placeholder']) ? esc_attr($bfw_component['placeholder']) : '' ); ?>"><?php echo ( isset($bfw_component['value']) ? esc_textarea($bfw_component['value']) : '' ); ?></textarea>
										</span>
									</label>
								</div>
							</div>
								<?php
							break;

						case 'select':
						case 'multiselect':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label class="wps-form-label" for="<?php echo esc_attr($bfw_component['id']); ?>"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<div class="wps-form-select">
										<select id="<?php echo esc_attr($bfw_component['id']); ?>" name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?><?php echo ( 'multiselect' === $bfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr($bfw_component['id']); ?>" class="mdl-textfield__input <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>" <?php echo 'multiselect' === $bfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
											<?php
											foreach ( $bfw_component['options'] as $bfw_key => $bfw_val ) {
												?>
												<option value="<?php echo esc_attr($bfw_key); ?>"
													<?php
													if ( is_array( $bfw_component['value'] ) ) {
														selected( in_array((string) $bfw_key, $bfw_component['value'], true ), true );
													} else {
														selected( $bfw_component['value'], (string) $bfw_key );
													}
													?>
													>
													<?php echo esc_html($bfw_val); ?>
												</option>
												<?php
											}
											?>
										</select>
										<label class="mdl-textfield__label" for="<?php echo esc_attr($bfw_component['id']); ?>"><?php echo esc_html($bfw_component['description']); ?><?php echo ( isset($bfw_component['description']) ? esc_attr($bfw_component['description']) : '' ); ?></label>
									</div>
								</div>
							</div>
								<?php
							break;

						case 'checkbox':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr($bfw_component['id']); ?>" class="wps-form-label"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control wps-pl-4">
									<div class="mdc-form-field">
										<div class="mdc-checkbox">
											<input 
											name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
											id="<?php echo esc_attr($bfw_component['id']); ?>"
											type="checkbox"
											class="mdc-checkbox__native-control <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>"
											value="<?php echo ( isset($bfw_component['value']) ? esc_attr($bfw_component['value']) : '' ); ?>"
											<?php checked($bfw_component['value'], '1'); ?>
											/>
											<div class="mdc-checkbox__background">
												<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
													<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
												</svg>
												<div class="mdc-checkbox__mixedmark"></div>
											</div>
											<div class="mdc-checkbox__ripple"></div>
										</div>
										<label for="checkbox-1"><?php echo ( isset($bfw_component['description']) ? esc_attr($bfw_component['description']) : '' ); ?></label>
									</div>
								</div>
							</div>
								<?php
							break;

						case 'radio':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr($bfw_component['id']); ?>" class="wps-form-label"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control wps-pl-4">
									<div class="wps-flex-col">
										<?php
										foreach ( $bfw_component['options'] as $bfw_radio_key => $bfw_radio_val ) {
											?>
											<div class="mdc-form-field">
												<div class="mdc-radio">
													<input
													name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
													value="<?php echo esc_attr($bfw_radio_key); ?>"
													type="radio"
													class="mdc-radio__native-control <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>"
													<?php checked($bfw_radio_key, $bfw_component['value']); ?>
													>
													<div class="mdc-radio__background">
														<div class="mdc-radio__outer-circle"></div>
														<div class="mdc-radio__inner-circle"></div>
													</div>
													<div class="mdc-radio__ripple"></div>
												</div>
												<label for="radio-1"><?php echo esc_html($bfw_radio_val); ?></label>
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
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="" class="wps-form-label"><?php echo ( isset($bfw_component['title'] ) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<div>
										<div class="mdc-switch">
											<div class="mdc-switch__track"></div>
											<div class="mdc-switch__thumb-underlay">
												<div class="mdc-switch__thumb"></div>
												<input
												name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
												type="checkbox"
												id="<?php echo esc_html($bfw_component['id']); ?>"
												value="yes"
												class="mdc-switch__native-control <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>"
												role="switch" 
												aria-checked="<?php echo esc_html( 'yes' === $bfw_component['value'] ) ? 'true' : 'false'; ?>"
												<?php checked( $bfw_component['value'], 'yes'); ?>
												>
											</div>
										</div>
									</div>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $bfw_component['description'] ) ? wp_kses_post( $bfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;

						case 'button':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label"></div>
								<div class="wps-form-group__control">
									<button class="mdc-button mdc-button--raised" name= "<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
										id="<?php echo esc_attr($bfw_component['id']); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>"><?php echo ( isset($bfw_component['button_text']) ? esc_html($bfw_component['button_text']) : '' ); ?></span>
									</button>
								</div>
							</div>

								<?php
							break;

						case 'multi':
							?>
							<div class="wps-form-group wps-mbfw-<?php echo esc_attr($bfw_component['type']); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr($bfw_component['id']); ?>" class="wps-form-label"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
									</div>
									<div class="wps-form-group__control">
										<?php
										foreach ( $bfw_component['value'] as $component ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ('number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset($bfw_component['placeholder']) ? esc_attr($bfw_component['placeholder']) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input 
												class="mdc-text-field__input <?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>" 
												name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
												id="<?php echo esc_attr($component['id']); ?>"
												type="<?php echo esc_attr($component['type']); ?>"
												value="<?php echo ( isset($bfw_component['value']) ? esc_attr($bfw_component['value']) : '' ); ?>"
												placeholder="<?php echo ( isset($bfw_component['placeholder']) ? esc_attr($bfw_component['placeholder']) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'max=10 min=0' : ''); ?>
												>
											</label>
										<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset($bfw_component['description']) ? esc_attr($bfw_component['description']) : '' ); ?></div>
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
							<div class="wps-form-group wps-mbfw-<?php echo esc_attr($bfw_component['type']); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr($bfw_component['id']); ?>" class="wps-form-label"><?php echo ( isset($bfw_component['title']) ? esc_html($bfw_component['title']) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label>
										<input 
										class="<?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>" 
										name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
										id="<?php echo esc_attr($bfw_component['id']); ?>"
										type="<?php echo esc_attr( ( 'date' === $bfw_component['type'] || 'time' === $bfw_component['type'] ) ? 'text' : $bfw_component['type']); ?>"
										value="<?php echo ( isset($bfw_component['value']) ? esc_attr($bfw_component['value']) : '' ); ?>"
										autocomplete="off"
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset($bfw_component['description']) ? esc_attr($bfw_component['description']) : '' ); ?></div>
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
									name="<?php echo ( isset($bfw_component['name']) ? esc_html($bfw_component['name']) : esc_html($bfw_component['id']) ); ?>"
									id="<?php echo esc_attr($bfw_component['id']); ?>"
									class="<?php echo ( isset($bfw_component['class']) ? esc_attr($bfw_component['class']) : '' ); ?>"
									value="<?php echo esc_attr($bfw_component['button_text']); ?>"
									/>
								</td>
							</tr>
								<?php
							break;
						case 'availability_select':
							?>
							<div class="mbfw-admin-suggestion-text"><?php esc_html_e( 'Availability by Days', 'bookings-for-woocommerce' ); ?></div>
							<?php
							$sub_tabs = $bfw_component['sub_tabs'];
							foreach ( $sub_tabs as $title => $bfw_sub_components ) {
								?>
								<div class="wps-form-group">
									<div class="wps-form-group__label">
										<div><?php echo esc_html( $title ); ?></div>
									</div>
									<div class="wps-form-group__control">
										<div class="mbfw-avl-days-wrap">
											<?php foreach ( $bfw_sub_components as $sub_components ) { ?>
												<div class="mbfw-avl-days">
													<label for="" class="wps-form-label"><?php echo ( isset( $sub_components['label'] ) ? esc_html( $sub_components['label'] ) : '' ); ?></label>
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
							<input id="<?php echo esc_attr( isset( $bfw_component['id'] ) ? $bfw_component['id'] : '' ); ?>" class="<?php echo esc_attr( isset( $bfw_component['class'] ) ? $bfw_component['class'] : '' ); ?>" type="text" autocomplete="off"/>
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
