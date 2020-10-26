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
	 * MWB Booking Fields
	 *
	 * @var array
	 */
	public $setting_fields = array();

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
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		register_activation_hook( __FILE__, array( $this, 'my_rewrite_flush' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb-wc-bk-admin.css', array(), $this->version, 'all' );
		//wp_enqueue_style( 'select2_css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-wc-bk-admin.js', array( 'jquery' ), $this->version, false );
		/*wp_enqueue_script( 'select2_js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'mwb_booking_select2', plugin_dir_url( __FILE__ ) . 'js/mwb_select2.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( 'mwb_booking_select2', 'ajax_url', admin_url( 'admin-ajax.php' ) );*/

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
	public function booking_virtual_product_options( $options ) {
		$options['virtual']['wrapper_class'] .= 'show_if_mwb_booking';
		return $options;
	}
	/**
	 * Add various Setting Tabs under Product settings for bookable product type
	 *
	 * @param array $tabs Product Panel Tabs.
	 * @return array
	 */
	public function booking_add_product_data_tabs( $tabs ) {

		$tabs = array_merge(
			$tabs,
			array(
				'general_settings' => array(
					'label'    => 'General Settings',
					'target'   => 'mwb_booking_general_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 10,
				),
				'cost'             => array(
					'label'    => 'Costs',
					'target'   => 'mwb_booking_cost_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 20,
				),
				'availability'     => array(
					'label'    => 'Availability',
					'target'   => 'mwb_booking_availability_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 30,
				),
				'people'           => array(
					'label'    => 'People',
					'target'   => 'mwb_booking_people_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 40,
				),
				'services'         => array(
					'label'    => 'Services',
					'target'   => 'mwb_booking_services_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 50,
				),
			)
		);
		return $tabs;
	}

	/**
	 * Installing on activation
	 *
	 * @return void
	 */
	public function install() {

		if ( ! get_term_by( 'slug', 'mwb_booking', 'product_type' ) ) {
			wp_insert_term( 'mwb_booking', 'product_type' );
		}
	}
	/**
	 * General Settings fields.
	 *
	 * @return void
	 */
	public function product_booking_fields() {

		global $post;
		$product    = wc_get_product( $post->ID );
		$product_id = $product->get_id();
		$this->set_prouduct_settings_fields( $product_id );

		include MWB_WC_BK_BASEPATH . 'admin/partials/product-booking-tabs/general-setting-fields-tab.php';
		include MWB_WC_BK_BASEPATH . 'admin/partials/product-booking-tabs/availability-fields-tab.php';
		include MWB_WC_BK_BASEPATH . 'admin/partials/product-booking-tabs/people-fields-tab.php';
		include MWB_WC_BK_BASEPATH . 'admin/partials/product-booking-tabs/services-fields-tab.php';
		include MWB_WC_BK_BASEPATH . 'admin/partials/product-booking-tabs/cost-fields-tab.php';

	}

	/**
	 * Save the booking fields
	 *
	 * @param [type] $post_id ID of the post.
	 * @return void
	 */
	public function save_product_booking_fields( $post_id ) {

		foreach ( $this->get_product_settings() as $key => $value ) {
			if ( is_array( $_POST[ $key ] ) ) {
				$posted_data = ! empty( $_POST[ $key ] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) ) : $value['default'];
			} else {
				$posted_data = ! empty( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : $value['default'];
			}

			update_post_meta( $post_id, $key, $posted_data );
		}
	}

	/**
	 * Define booking's default setting fields
	 *
	 * @return array
	 */
	public function get_product_settings() {
		return array(
			'mwb_booking_unit_select'                => array( 'default' => 'customer' ),
			'mwb_booking_unit_input'                 => array( 'default' => '1' ),
			'mwb_booking_unit_duration'              => array( 'default' => 'day' ),
			'mwb_start_booking_date'                 => array( 'default' => '' ),
			'mwb_start_booking_time'                 => array( 'default' => '' ),
			'mwb_start_booking_custom_date'          => array( 'default' => '' ),
			'mwb_enable_range_picker'                => array( 'default' => 'no' ),
			'mwb_full_day_booking'                   => array( 'default' => 'no' ),
			'mwb_admin_confirmation'                 => array( 'default' => 'no' ),
			'mwb_allow_booking_cancellation'         => array( 'default' => 'no' ),
			'mwb_max_days_for_cancellation'          => array( 'default' => '' ),
			'mwb_booking_unit_cost_input'            => array( 'default' => '' ),
			'mwb_booking_unit_cost_multiply'         => array( 'default' => 'no' ),
			'mwb_booking_base_cost_input'            => array( 'default' => '' ),
			'mwb_booking_base_cost_multiply'         => array( 'default' => 'no' ),
			'mwb_booking_monthly_discount_type'      => array( 'default' => '' ),
			'mwb_booking_monthly_discount_input'     => array( 'default' => '' ),
			'mwb_booking_weekly_discount_input'      => array( 'default' => '' ),
			'mwb_booking_custom_days_discount_input' => array( 'default' => '' ),
			'mwb_booking_custom_discount_days'       => array( 'default' => '' ),
			'mwb_services_enable_checkbox'           => array( 'default' => 'no' ),
			'mwb_booking_services_select'            => array( 'default' => '' ),
			'mwb_services_mandatory_check'           => array( 'default' => '' ),
			'mwb_people_enable_checkbox'             => array( 'default' => 'no' ),
			'mwb_min_people_per_booking'             => array( 'default' => '' ),
			'mwb_max_people_per_booking'             => array( 'default' => '' ),
			'mwb_people_as_seperate_booking'         => array( 'default' => 'no' ),
			'mwb_enable_people_types'                => array( 'default' => 'no' ),
			'mwb_max_bookings_per_unit'              => array( 'default' => '' ),
			'mwb_booking_start_time'                 => array( 'default' => '' ),
			'mwb_booking_end_time'                   => array( 'default' => '' ),
			'mwb_booking_buffer_input'               => array( 'default' => '' ),
			'mwb_booking_buffer_duration'            => array( 'default' => '' ),
			'mwb_advance_booking_max_input'          => array( 'default' => '' ),
			'mwb_advance_booking_max_duration'       => array( 'default' => '' ),
			'mwb_advance_booking_min_input'          => array( 'default' => '' ),
			'mwb_advance_booking_min_duration'       => array( 'default' => '' ),
			'mwb_booking_not_allowed_days'           => array( 'default' => '' ),

		);
	}

	/**
	 * Search weekdays
	 *
	 * @return array arr Weekdays.
	 */
	public function mwb_booking_search_weekdays() {
		$arr = array(
			'sunday'    => __( 'Sunday', 'mwb-wc-bk' ),
			'monday'    => __( 'Monday', 'mwb-wc-bk' ),
			'tuesday'   => __( 'Tuesday', 'mwb-wc-bk' ),
			'wednesday' => __( 'Wednesday', 'mwb-wc-bk' ),
			'thursday'  => __( 'Thursday', 'mwb-wc-bk' ),
			'friday'    => __( 'Friday', 'mwb-wc-bk' ),
			'saturday'  => __( 'Saturday', 'mwb-wc-bk' ),
		);
		return $arr;
	}

	/**
	 * Set booking's default settings fields
	 *
	 * @param [type] $product_id ID of the booking.
	 * @return void
	 */
	public function set_prouduct_settings_fields( $product_id ) {

		foreach ( $this->get_product_settings() as $key => $value ) {

			$data                         = get_post_meta( $product_id, $key, true );
			$this->setting_fields[ $key ] = ! empty( $data ) ? $data : $value['default'];
		}
	}

	/**
	 * Mwb Booking Durations
	 *
	 * @return array
	 */
	public function get_booking_duration_options() {
		return array(
			'month'  => __( 'Month(s)', 'mwb-wc-bk' ),
			'day'    => __( 'Day(s)', 'mwb-wc-bk' ),
			'hour'   => __( 'Hour(s)', 'mwb-wc-bk' ),
			'minute' => __( 'Minute(s)', 'mwb-wc-bk' ),
		);
	}

	/**
	 * Custom-Post Type Booking
	 *
	 * @return void
	 */
	public function booking_custom_post_type() {
		$labels = array(
			'name'                  => _x( 'Bookings', 'Post type general name', 'mwb-wc-bk' ),
			'singular_name'         => _x( 'Booking', 'Post type singular name', 'mwb-wc-bk' ),
			'menu_name'             => _x( 'Bookings', 'Admin Menu text', 'mwb-wc-bk' ),
			'add_new'               => _x( 'Add Booking', 'Booking', 'mwb-wc-bk' ),
			'add_new_item '         => __( 'Add New Booking', 'mwb-wc-bk' ),
			'edit_item'             => __( 'Edit Boooking', 'mwb-wc-bk' ),
			'new_item'              => __( 'New Booking', 'mwb-wc-bk' ),
			'name_admin_bar'        => _x( 'Bookings', 'Add new on toolbar', 'mwb-wc-bk' ),
			'view_item'             => __( 'View Bookings', 'mwb-wc-bk' ),
			'all_items'             => __( 'All Bookings', 'mwb-wc-bk' ),
			'search_items'          => __( 'Search Bookings', 'mwb-wc-bk' ),
			'not_found'             => __( 'No booking found', 'mwb-wc-bk' ),
			'not_found_in_trash'    => __( 'No bookings found in trash', 'mwb-wc-bk' ),
		//	'parent_items_colon'    => __( 'Parent Booking:', 'mwb-wc-bk' ),
			'archives'              => __( 'Archives', 'mwb-wc-bk' ),
			'attributes'            => __( 'Attributes', 'mwb-wc-bk' ),
			'insert_into_item'      => __( 'Insert into Product', 'mwb-wc-bk' ),
			'uploaded_to_this_item' => __( 'Upload to this Product', 'mwb-wc-bk' ),
			'featured_image'        => _x( 'Booking Cover Image', 'Overrides the featured image phrase for this post type', 'mwb-wc-bk' ),
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'description'        => __( 'Bookings are described here', 'mwb-wc-bk' ),
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'booking' ), // my custom slug.
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_nav_menu'   => true,
			'show_in_admin_bar'  => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'show_in_rest'       => false,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'taxonomies'         => array(),
			'map_meta_cap'       => true,
			'query-var'          => true,
		);
		register_post_type( 'mwb_cpt_booking', $args );
	}

	/**
	 * Rewrite Rules
	 *
	 * @return void
	 */
	public function my_rewrite_flush() {

		$this->booking_custom_post_type();
		$this->booking_register_taxonomy_services();
		$this->booking_register_taxonomy_people_type();

		flush_rewrite_rules();
	}

	/**
	 * Our Custom Taxonomy.
	 *
	 * @return void
	 */
	public function booking_register_taxonomy_services() {
		$labels = array(
			'name'              => _x( 'Services', 'taxonomy general name', 'mwb-wc-bk' ),
			'singular_name'     => _x( 'Service', 'taxonomy singular name', 'mwb-wc-bk' ),
			'search_items'      => __( 'Search Services', 'mwb-wc-bk' ),
			'all_items'         => __( 'All Services', 'mwb-wc-bk' ),
			'parent_item'       => __( 'Parent Service', 'mwb-wc-bk' ),
			'parent_item_colon' => __( 'Parent Service:', 'mwb-wc-bk' ),
			'edit_item'         => __( 'Edit Service', 'mwb-wc-bk' ),
			'view_item'         => __( 'View Service', 'mwb-wc-bk' ),
			'update_item'       => __( 'Update Service', 'mwb-wc-bk' ),
			'add_new_item'      => __( 'Add New Service', 'mwb-wc-bk' ),
			'new_item_name'     => __( 'New Service Name', 'mwb-wc-bk' ),
			'menu_name'         => __( 'Services', 'mwb-wc-bk' ),
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'description'       => 'Services or resources which are to be included in booking',
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'services' ),
		);
		register_taxonomy( 'mwb_ct_services', array( 'mwb_cpt_booking' ), $args );
	}

	/**
	 * Our Custom Taxonomy.
	 *
	 * @return void
	 */
	public function booking_register_taxonomy_people_type() {
		$labels = array(
			'name'              => _x( 'People Types', 'taxonomy general name', 'mwb-wc-bk' ),
			'singular_name'     => _x( 'People Type', 'taxonomy singular name', 'mwb-wc-bk' ),
			'search_items'      => __( 'Search People Types', 'mwb-wc-bk' ),
			'all_items'         => __( 'All People Types', 'mwb-wc-bk' ),
			'parent_item'       => __( 'Parent Type', 'mwb-wc-bk' ),
			'parent_item_colon' => __( 'Parent Type:', 'mwb-wc-bk' ),
			'edit_item'         => __( 'Edit People Type', 'mwb-wc-bk' ),
			'view_item'         => __( 'View People Type', 'mwb-wc-bk' ),
			'update_item'       => __( 'Update People Type', 'mwb-wc-bk' ),
			'add_new_item'      => __( 'Add New People Type', 'mwb-wc-bk' ),
			'new_item_name'     => __( 'New Type Name', 'mwb-wc-bk' ),
			'menu_name'         => __( 'People Types', 'mwb-wc-bk' ),
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'description'       => 'Types of Peoples which are to be included per booking',
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'people-types' ),
		);
		register_taxonomy( 'mwb_ct_people_type', array( 'mwb_cpt_booking' ), $args );
	}

	/**
	 * Our Custom Taxonomy.
	 *
	 * @return void
	 */
	public function booking_register_taxonomy_cost() {
		$labels = array(
			'name'              => _x( 'Additional Costs', 'taxonomy general name', 'mwb-wc-bk' ),
			'singular_name'     => _x( 'Cost', 'taxonomy singular name', 'mwb-wc-bk' ),
			'search_items'      => __( 'Search Costs', 'mwb-wc-bk' ),
			'all_items'         => __( 'All Costs', 'mwb-wc-bk' ),
			'parent_item'       => __( 'Parent Cost', 'mwb-wc-bk' ),
			'parent_item_colon' => __( 'Parent Cost:', 'mwb-wc-bk' ),
			'edit_item'         => __( 'Edit Cost', 'mwb-wc-bk' ),
			'view_item'         => __( 'View Costs', 'mwb-wc-bk' ),
			'update_item'       => __( 'Update Cost', 'mwb-wc-bk' ),
			'add_new_item'      => __( 'Add New Cost', 'mwb-wc-bk' ),
			'new_item_name'     => __( 'New Cost Name', 'mwb-wc-bk' ),
			'menu_name'         => __( 'Costs', 'mwb-wc-bk' ),
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'description'       => 'Additional Costs which are to be included in booking',
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'cost' ),
		);
		register_taxonomy( 'mwb_ct_costs', array( 'mwb_cpt_booking' ), $args );
	}

	public function booking_submenu_active() {

		global $parent_file, $post_type;

		if ( ! empty( $post_type ) && 'mwb_cpt_booking' === $post_type ) {
			;
		}
	}

	/**
	 * "Bookings" Admin Menu.
	 *
	 * @return void
	 */
	public function booking_admin_menu() {

		add_menu_page(
			__( 'Bookings', 'mwb-wc-bk' ),
			'Bookings',
			'manage_options',
			'edit.php?post_type=mwb_cpt_booking',
			'',
			'',
			30
		);

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Services', 'mwb-wc-bk' ), 'Services', 'manage_options', 'edit-tags.php?taxonomy=mwb_ct_services&post_type=mwb_cpt_booking', '' );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'People Types', 'mwb-wc-bk' ), 'People Types', 'manage_options', 'edit-tags.php?taxonomy=mwb_ct_people_type&post_type=mwb_cpt_booking', '' );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Costs', 'mwb-wc-bk' ), 'Costs', 'manage_options', 'edit-tags.php?taxonomy=mwb_ct_costs&post_type=mwb_cpt_booking', '' );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Create Booking', 'mwb-wc-bk' ), 'Create Booking', 'manage_options', 'create-booking', array( $this, 'menu_page_create_booking' ) );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Global booking Settings', 'mwb-wc-bk' ), 'Settings', 'manage_options', 'global-settings', array( $this, 'menu_page_booking_settings' ) );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Calendar', 'mwb-wc-bk' ), 'Calendar', 'manage_options', '', '' );
	}

	public function menu_page_create_booking() {
		echo 'Test booking';
	}

	public function menu_page_booking_settings() {
		echo 'settings';
	}

}
