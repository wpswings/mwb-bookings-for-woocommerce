<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
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
 * @since      1.0.0
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mwb_Wc_Bk {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_Wc_Bk_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MWB_WC_BK_VERSION' ) ) {
			$this->version = MWB_WC_BK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mwb-wc-bk';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Wc_Bk_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Wc_Bk_i18n. Defines internationalization functionality.
	 * - Mwb_Wc_Bk_Admin. Defines all hooks for the admin area.
	 * - Mwb_Wc_Bk_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-wc-bk-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-wc-bk-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-wc-bk-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-wc-bk-public.php';

		/**
		 * The class responsible for defining global functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-booking-global-functions.php';

		/**
		 * The class responsible for defining Booking and Booking product.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-woocommerce-booking.php';

		$this->loader = new Mwb_Wc_Bk_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Wc_Bk_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mwb_Wc_Bk_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mwb_Wc_Bk_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// create new product type booking.
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'register_booking_product_type' );

		$this->loader->add_filter( 'product_type_selector', $plugin_admin, 'add_mwb_booking_product_selector', 10, 1 );

		$this->loader->add_filter( 'product_type_options', $plugin_admin, 'booking_virtual_product_options' );

		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'booking_add_product_data_tabs' );

		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'product_booking_fields' );	

		$this->loader->add_action( 'woocommerce_process_product_meta_mwb_booking', $plugin_admin, 'save_product_booking_fields' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'booking_admin_menu' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_add_booking_submenu' );

	//	$this->loader->add_action( 'init', $plugin_admin, 'booking_custom_post_type' );

		$this->loader->add_action( 'woocommerce_init', $plugin_admin, 'booking_custom_post_order_type' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'remove_meta_box_cpt', 90 );

		$this->loader->add_action( 'init', $plugin_admin, 'booking_register_taxonomy_services' );

		$this->loader->add_action( 'init', $plugin_admin, 'booking_register_taxonomy_people_type' );

		$this->loader->add_action( 'init', $plugin_admin, 'booking_register_taxonomy_cost' );

		$this->loader->add_action( 'mwb_ct_services_add_form_fields', $plugin_admin, 'add_custom_fields_ct_booking_services' );
		$this->loader->add_action( 'created_mwb_ct_services', $plugin_admin, 'save_custom_fields_ct_booking_services', 10, 2 );
		$this->loader->add_action( 'edited_mwb_ct_services', $plugin_admin, 'save_custom_fields_ct_booking_services', 10, 2 );
		$this->loader->add_action( 'mwb_ct_services_edit_form_fields', $plugin_admin, 'edit_custom_fields_ct_booking_services' );

		$this->loader->add_action( 'mwb_ct_people_type_add_form_fields', $plugin_admin, 'add_custom_fields_ct_booking_people' );
		$this->loader->add_action( 'created_mwb_ct_people_type', $plugin_admin, 'save_custom_fields_ct_booking_people', 10, 2 );
		$this->loader->add_action( 'edited_mwb_ct_people_type', $plugin_admin, 'save_custom_fields_ct_booking_people', 10, 2 );
		$this->loader->add_action( 'mwb_ct_people_type_edit_form_fields', $plugin_admin, 'edit_custom_fields_ct_booking_people' );

		$this->loader->add_action( 'mwb_ct_costs_add_form_fields', $plugin_admin, 'add_custom_fields_ct_booking_cost' );
		$this->loader->add_action( 'created_mwb_ct_costs', $plugin_admin, 'save_custom_fields_ct_booking_cost', 10, 2 );
		$this->loader->add_action( 'edited_mwb_ct_costs', $plugin_admin, 'save_custom_fields_ct_booking_cost', 10, 2 );
		$this->loader->add_action( 'mwb_ct_costs_edit_form_fields', $plugin_admin, 'edit_custom_fields_ct_booking_cost' );

		$this->loader->add_action( 'manage_edit-mwb_ct_services_columns', $plugin_admin, 'add_columns_ct_services' );
		$this->loader->add_filter( 'manage_mwb_ct_services_custom_column', $plugin_admin, 'manage_columns_ct_services', 10, 3 );

		$this->loader->add_action( 'manage_edit-mwb_ct_costs_columns', $plugin_admin, 'add_columns_ct_costs' );
		$this->loader->add_filter( 'manage_mwb_ct_costs_custom_column', $plugin_admin, 'manage_columns_ct_costs', 10, 3 );

		$this->loader->add_action( 'wp_ajax_selected_services_search', $plugin_admin, 'selected_services_search' );

		$this->loader->add_action( 'wp_ajax_selected_people_type_search', $plugin_admin, 'selected_people_type_search' );

		$this->loader->add_action( 'wp_ajax_selected_added_costs_search', $plugin_admin, 'selected_added_costs_search' );

		$this->loader->add_action( 'wp_ajax_dachicon_change_handler', $plugin_admin, 'dachicon_change_handler' );

		$this->loader->add_action( 'wp_ajax_add_global_availability_rule', $plugin_admin, 'add_global_availability_rule' );
		$this->loader->add_action( 'wp_ajax_delete_global_availability_rule', $plugin_admin, 'delete_global_availability_rule' );

		$this->loader->add_action( 'wp_ajax_add_global_cost_rule', $plugin_admin, 'add_global_cost_rule' );
		$this->loader->add_action( 'wp_ajax_delete_global_cost_rule', $plugin_admin, 'delete_global_cost_rule' );

		$this->loader->add_action( 'wp_ajax_create_booking_user_search', $plugin_admin, 'create_booking_user_search' );

		$this->loader->add_action( 'wp_ajax_create_booking_product_search', $plugin_admin, 'create_booking_product_search' );

		$this->loader->add_action( 'wp_ajax_create_booking_order_search', $plugin_admin, 'create_booking_order_search' );

		$this->loader->add_action( 'wp_ajax_create_booking_product_details', $plugin_admin, 'create_booking_product_details' );

		$this->loader->add_filter( 'manage_mwb_cpt_booking_posts_columns', $plugin_admin, 'mwb_wc_bk_booking_columns' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mwb_Wc_Bk_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'woocommerce_mwb_booking_add_to_cart', $plugin_public, 'mwb_include_booking_add_to_cart' );

		$this->loader->add_action( 'mwb_booking_add_to_cart_form_content', $plugin_public, 'mwb_booking_add_to_cart_form_fields' );

		$this->loader->add_action( 'wp_ajax_mwb_wc_bk_update_add_to_cart', $plugin_public, 'mwb_wc_bk_update_add_to_cart' );

		$this->loader->add_action( 'wp_ajax_nopriv_mwb_wc_bk_update_add_to_cart', $plugin_public, 'mwb_wc_bk_update_add_to_cart' );

		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'mwb_wc_bk_add_cart_item_data', 10, 2 );

		$this->loader->add_filter( 'woocommerce_add_cart_item', $plugin_public, 'mwb_wc_bk_add_cart_item', 10, 2 );

		$this->loader->add_filter( 'woocommerce_get_cart_item_from_session', $plugin_public, 'mwb_wc_bk_get_cart_item_from_session', 99, 3 );

		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'mwb_wc_bk_get_item_data', 10, 2 );

		$this->loader->add_filter( 'woocommerce_checkout_create_order_line_item', $plugin_public, 'mwb_wc_bk_checkout_create_order_line_item', 10, 4 );

		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_public, 'mwb_wc_bk_check_order_booking', 999, 2 );

		// $this->loader->add_filter( 'woocommerce_get_price_html', $plugin_public, 'change_on_sale_badge' );
		$this->loader->add_action( 'wp_ajax_booking_price_cal', $plugin_public, 'booking_price_cal' );

		$this->loader->add_action( 'wp_ajax_show_booking_total', $plugin_public, 'show_booking_total' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_Wc_Bk_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
