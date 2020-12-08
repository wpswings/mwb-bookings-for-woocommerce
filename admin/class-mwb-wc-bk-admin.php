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
	 * MWB Booking Global Availability rules array
	 *
	 * @var array
	 */
	public $global_availability_rule_arr = array();

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
		wp_enqueue_style( 'select2_css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-wc-bk-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name,
			'mwb_booking_obj',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax-nonce' ),
			)
		);

		wp_enqueue_script( 'select2_js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );

		// wp_enqueue_script( 'mwb_booking_select2', plugin_dir_url( __FILE__ ) . 'js/mwb_select2.js', array( 'jquery' ), $this->version, false );

		// wp_localize_script( 'mwb_booking_select2', 'ajax_url', admin_url( 'admin-ajax.php' ) );

		wp_enqueue_script( 'iconify', 'https://code.iconify.design/1/1.0.7/iconify.min.js', array(), '1.0.7', false );

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
			'mwb_booking_services_select'            => array( 'default' => array() ),
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
			'mwb_booking_not_allowed_days'           => array( 'default' => array() ),

		);
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

		// if( isset( $this->setting_fields ) ) {
		// 	echo "<pre>";
		// 	print_r( $this->setting_fields );
		// 	echo "</pre>";
		// 	die;
		// }
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
		apply_filters( 'mwb_booking_weekdays', $arr );
		return $arr;
	}

	/**
	 * Global Cost Calculation Symbols
	 *
	 * @return array arr
	 */
	public function mwb_booking_global_cost_cal() {
		$arr = array(
			'add'      => '+',
			'subtract' => '-',
			'multiply' => '*',
			'divide'   => '/',
		);
		apply_filters( 'mwb_booking_cost_cal_symbols', $arr );
		return $arr;
	}

	/**
	 * Global Cost Rule Conditions
	 *
	 * @return array arr Weekdays.
	 */
	public function global_cost_conditions() {

		// $the_query = new WP_Query(
		// 	array(
		// 		'post_type' => 'mwb_cpt_booking',
		// 		'tax_query' => array(
		// 			array(
		// 				'taxonomy' => 'mwb_ct_people_type',
		// 				'fields'   => 'all',
		// 			),
		// 		),
		// 	)
		// );

		$terms = get_terms(
			array(
				'taxonomy'   => 'mwb_ct_people_type',
				'hide_empty' => false,
			)
		);

		// if ( $the_query->have_posts() ) {
		// 	while ( $the_query->have_post() ) {
		// 		echo"hi";
		// 		echo "<pre>";
		// 		print_r( $the_query->the_title() );
		// 		echo "</pre>";
		// 		die;
		// 	}
		// }
		// echo "<pre>";
		// print_r( $terms );
		// echo "</pre>";

		$arr = array(
			'day'    => __( 'Day Range', 'mwb-wc-bk' ),
			'week'   => __( 'Week Range', 'mwb-wc-bk' ),
			'month'  => __( 'Months Range', 'mwb-wc-bk' ),
			'date'   => __( 'Date Range', 'mwb-wc-bk' ),
			'time'   => __( 'Time Range', 'mwb-wc-bk' ),
			'unit'   => __( 'Unit Range', 'mwb-wc-bk' ),
			'people' => array(
				'heading' => __( 'People Range', 'mwb-wc-bk' ),
			),
		);
		foreach ( $terms as $term ) {
			$term_id = $term->term_id;
			//print_r( $term_id );
			$arr['people'][ $term_id ] = $term->name;
		}
		// echo "<pre>";
		// print_r( $arr );
		// echo "</pre>";

		apply_filters( 'mwb_global_cost_condotions', $arr );
		return $arr;
	}

	/**
	 * Search Months
	 *
	 * @return array arr Weekdays.
	 */
	public function mwb_booking_months() {
		$arr = array(
			'jan' => __( 'January', 'mwb-wc-bk' ),
			'feb' => __( 'February', 'mwb-wc-bk' ),
			'mar' => __( 'March', 'mwb-wc-bk' ),
			'apr' => __( 'April', 'mwb-wc-bk' ),
			'may' => __( 'May', 'mwb-wc-bk' ),
			'jun' => __( 'June', 'mwb-wc-bk' ),
			'jul' => __( 'July', 'mwb-wc-bk' ),
			'aug' => __( 'August', 'mwb-wc-bk' ),
			'sep' => __( 'September', 'mwb-wc-bk' ),
			'oct' => __( 'October', 'mwb-wc-bk' ),
			'nov' => __( 'November', 'mwb-wc-bk' ),
			'dec' => __( 'December', 'mwb-wc-bk' ),
		);
		apply_filters( 'mwb_booking_months', $arr );
		return $arr;
	}


	/**
	 * Select2 search for services which are selected.
	 *
	 * @since    1.0.0
	 */
	public function selected_services_search() {
		$return = array();
		$args   = array(
			'search'     => ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
			'taxonomy'   => 'mwb_ct_people_type',
			'hide_empty' => false,
			'order_by'   => 'name',
		);
		$terms  = get_terms( $args );

		if ( ! empty( $terms ) && is_array( $terms ) && count( $terms ) ) {

			foreach ( $terms as $term ) {

				$term->name = ( mb_strlen( $term->name ) > 50 ) ? mb_substr( $term->name, 0, 49 ) . '...' : $term->name;

				$return[] = array( $term->term_id, $term->name );
			}
		}

		echo wp_json_encode( $return );

		wp_die();

	}

	/**
	 * Select2 search for services which are selected.
	 *
	 * @since    1.0.0
	 */
	public function create_booking_user_search() {

		$return    = array();
		$args      = array(
			'search'         => ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
			'search_columns' => array(
				'user_login',
				'user_email',
				'display_name',
			),
		);
		$get_users = new WP_User_Query( $args );
		$users     = $get_users->get_results();

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$user_name = $user->data->user_login;
				$user_name = ( mb_strlen( $user_name ) > 50 ) ? mb_substr( $user_name, 0, 49 ) . '...' : $user_name;

				$return[] = array( $user->ID, $user_name );
			}
		}

		echo wp_json_encode( $return );

		wp_die();

	}

	/**
	 * Select2 search for Product which are selected.
	 *
	 * @since    1.0.0
	 */
	public function create_booking_product_search() {
		$return   = array();
		$args     = array(
			's'                   => ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
			'post_type'           => array( 'product' ),
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => -1,
		);
		$products = new WP_Query( $args );

		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) {
				$products->the_post();
				$product_title = ( mb_strlen( $products->post->post_title ) > 50 ) ? mb_substr( $products->post->post_title, 0, 49 ) . '...' : $products->post->post_title;
				/**
				 * Check for post type as query sometimes returns posts even after mentioning post_type.
				 * As some plugins alter query which causes issues.
				 */
				$post_type = get_post_type( $products->post->ID );

				if ( 'product' !== $post_type ) {
					continue;
				}
				$product      = wc_get_product( $products->post->ID );
				$product_type = $product->get_type();

				$unsupported_product_types = array(
					'grouped',
					'external',
					'subscription',
					'variable-subscription',
					'subscription_variation',
				);

				if ( in_array( $product_type, $unsupported_product_types ) ) {
					continue;
				}
				$return[] = array( $products->post->ID, $product_title );
			}
		}
		echo wp_json_encode( $return );

		wp_die();

	}

	/**
	 * Select2 search for Product which are selected.
	 *
	 * @since    1.0.0
	 */
	public function create_booking_order_search() {
		$return = array();
		$loop   = new WP_Query(
			array(
				's'                   => ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
				'post_type'           => 'shop_order',
				'post_status'         => array_keys( wc_get_order_statuses() ),
				'posts_per_page'      => -1,
			)
		);

		// The WordPress post loop.
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();

				// The order ID.
				$order_id = $loop->post->ID;

				$order = wc_get_order( $order_id );

				if ( ! empty( $order ) ) {
					$return[] = array( $order->ID, '' );
				}
			}
		}
		echo wp_json_encode( $return );

		wp_die();

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
			'add_new_item'          => __( 'Add New Booking', 'mwb-wc-bk' ),
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
			'show_in_menu'       => true,
			'show_in_nav_menu'   => true,
			'show_in_admin_bar'  => true,
			'hierarchical'       => false,
			'capability_type'    => 'post',
			'capabilities'       => array(
				'create_posts' => false,
			),
			'show_in_rest'       => false,
			'supports'           => array( 'thumbnail' ),
			'taxonomies'         => array( 'mwb_ct_services', 'mwb_ct_people_type' ),
			'map_meta_cap'       => true,
			'query-var'          => true,
			'menu_icon'          => 'dashicons-calendar-alt',
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

	/**
	 * Settings tab Default Global Options
	 *
	 * @return array
	 */
	public function booking_settings_tab_default_global_options() {

		return array(
			'mwb_booking_setting_go_enable'          => 'yes',
			'mwb_booking_setting_go_complete_status' => '',
			'mwb_booking_setting_go_reject'          => '',
			'mwb_booking_setting_bo_service_enable'  => 'yes',
			'mwb_booking_setting_bo_service_cost'    => 'yes',
			'mwb_booking_setting_bo_service_desc'    => 'yes',
		);
	}

	/**
	 * "Bookings" Admin Menu.
	 *
	 * @return void
	 */
	public function booking_admin_menu() {

		// add_menu_page(
		// 	__( 'Bookings', 'mwb-wc-bk' ),
		// 	'Bookings',
		// 	'manage_options',
		// 	'edit.php?post_type=mwb_cpt_booking',
		// 	'',
		// 	'',
		// 	30
		// );

		// add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Services', 'mwb-wc-bk' ), 'Services', 'manage_options', 'edit-tags.php?taxonomy=mwb_ct_services&post_type=mwb_cpt_booking' );

		// add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'People Types', 'mwb-wc-bk' ), 'People Types', 'manage_options', 'edit-tags.php?taxonomy=mwb_ct_people_type&post_type=mwb_cpt_booking' );

		// add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Costs', 'mwb-wc-bk' ), 'Costs', 'manage_options', 'edit-tags.php?taxonomy=mwb_ct_costs&post_type=mwb_cpt_booking' );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Create Booking', 'mwb-wc-bk' ), 'Create Booking', 'manage_options', 'create-booking', array( $this, 'menu_page_create_booking' ) );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Global booking Settings', 'mwb-wc-bk' ), 'Settings', 'manage_options', 'global-settings', array( $this, 'menu_page_booking_settings' ) );

		add_submenu_page( 'edit.php?post_type=mwb_cpt_booking', __( 'Calendar', 'mwb-wc-bk' ), 'Calendar', 'manage_options', 'calendar', array( $this, 'menu_page_calendar' ) );
	}

	/**
	 * Remove the default add booking submenu page.
	 *
	 * @return void
	 */
	public function remove_add_booking_submenu() {
		if ( post_type_exists( 'mwb_cpt_booking' ) ) {
			remove_submenu_page( 'edit.php?post_type=mwb_cpt_booking', 'post-new.php?post_type=mwb_cpt_booking' );
		}
	}

	public function menu_page_create_booking() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/mwb_create_booking.php';
	}

	public function menu_page_booking_settings() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/mwb-wc-bk-admin-display.php';
	}

	public function menu_page_calendar() {
		echo 'calendar';
	}

	/**
	 * Adding the custom fields in our custom taxonomy "mwb_ct_services"
	 *
	 * @return void
	 */
	public function add_custom_fields_ct_booking_services() {
		?>
		<div class="form-field term-cost-wrap">
			<label for="mwb_ct_booking_service_cost"><?php esc_html_e( 'Service Cost', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_ct_booking_service_cost" class="postform" name="mwb_ct_booking_service_cost" />
			<p class="description"><?php esc_html_e( 'Enter the cost for the services to be included', 'mwb-wc-bk' ); ?></p>
		</div>
		<div class="form-field" id="mwb_booking_ct_services_custom_fields">
			<?php
				woocommerce_wp_checkbox(
					array(
						'id'          => 'mwb_booking_ct_services_multiply_units',
						'value'       => '',
						'description' => __( 'Multiply cost by booking units', 'mwb-wc-bk' ),
					)
				);
				woocommerce_wp_checkbox(
					array(
						'id'          => 'mwb_booking_ct_services_multiply_people',
						'value'       => '',
						'description' => __( 'Multiply cost by number of peoples per booking', 'mwb-wc-bk' ),
					)
				);
				woocommerce_wp_checkbox(
					array(
						'id'          => 'mwb_booking_ct_services_has_quantity',
						'value'       => '',
						'description' => __( 'If has quantity', 'mwb-wc-bk' ),
					)
				);
				woocommerce_wp_checkbox(
					array(
						'id'          => 'mwb_booking_ct_services_hidden',
						'value'       => '',
						'description' => __( 'If Hidden', 'mwb-wc-bk' ),
					)
				);
				woocommerce_wp_checkbox(
					array(
						'id'          => 'mwb_booking_ct_services_optional',
						'value'       => '',
						'description' => __( 'If Optional', 'mwb-wc-bk' ),
					)
				);
			?>
		</div>
		<div class="form-field term-has-quantity-checked-wrap">

			<label for="mwb_booking_ct_services_min_quantity"><?php esc_html_e( 'Minimum Quantity', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_booking_ct_services_min_quantity" class="postform" name="mwb_booking_ct_services_min_quantity" />
			<p class="description"><?php esc_html_e( 'Minimum Quantity if the service has quantity', 'mwb-wc-bk' ); ?></p>

			<label for="mwb_booking_ct_services_max_quantity"><?php esc_html_e( 'Maximum Quantity', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_booking_ct_services_max_quantity" class="postform" name="mwb_booking_ct_services_max_quantity" />
			<p class="description"><?php esc_html_e( 'Maximum Quantity if the service has quantity', 'mwb-wc-bk' ); ?></p>

		</div>
		<div class="form-field term-has-people-checked-wrap">
		<?php
			$booking_people_taxonomy_terms = get_terms(
				array(
					'taxonomy'   => 'mwb_ct_people_type',
					'hide_empty' => false,
				)
			);
		foreach ( $booking_people_taxonomy_terms as $term ) {
			$term_name = $term->slug;
			?>
			<label for="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>"><?php echo esc_html( 'Service Cost for ' . $term->name ); ?></label>
			<input type="number" id="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>"  class="postform" name="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>" />
			<p class="description"><?php esc_html_e( 'Enter the service cost for respective people type.', 'mwb-wc-bk' ); ?></p>
		<?php } ?>

		</div>
		<?php
	}

	/**
	 * Save the Custom fields in our custom taxonomy "mwb_ct_services"
	 *
	 * @param mixed $term_id Term ID to bbe saved.
	 * @param mixed $tt_id Term Taxonomy ID.
	 * @return void
	 */
	public function save_custom_fields_ct_booking_services( $term_id, $tt_id ) {

		update_term_meta( $term_id, 'mwb_ct_booking_service_cost', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_ct_booking_service_cost'] ) ? $_POST['mwb_ct_booking_service_cost'] : '' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_multiply_units', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_multiply_units'] ) ? $_POST['mwb_booking_ct_services_multiply_units'] : 'no' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_multiply_people', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_multiply_people'] ) ? $_POST['mwb_booking_ct_services_multiply_people'] : 'no' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_has_quantity', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_has_quantity'] ) ? $_POST['mwb_booking_ct_services_has_quantity'] : 'no' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_hidden', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_hidden'] ) ? $_POST['mwb_booking_ct_services_hidden'] : 'no' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_optional', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_optional'] ) ? $_POST['mwb_booking_ct_services_optional'] : 'no' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_min_quantity', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_min_quantity'] ) ? $_POST['mwb_booking_ct_services_min_quantity'] : '' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_services_max_quantity', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_services_max_quantity'] ) ? $_POST['mwb_booking_ct_services_max_quantity'] : '' ) ) ) );

		$booking_people_taxonomy_terms = get_terms(
			array(
				'taxonomy'   => 'mwb_ct_people_type',
				'hide_empty' => false,
			)
		);
		foreach ( $booking_people_taxonomy_terms as $term ) {
			$term_name = $term->slug;
			update_term_meta( $term_id, 'mwb_ct_booking_service_cost_' . $term_name, esc_attr( sanitize_text_field( wp_unslash( isset( $_POST[ 'mwb_ct_booking_service_cost_' . $term_name ] ) ? $_POST[ 'mwb_ct_booking_service_cost_' . $term_name ] : '' ) ) ) );
		}
	}

	/**
	 * Editing the custom fields in our custom taxonomy "mwb_ct_services"
	 *
	 * @param object $term Contains basic info of respective taxonomy term.
	 * @return void
	 */
	public function edit_custom_fields_ct_booking_services( $term ) {

		// print_r( $term );

		$service_cost          = get_term_meta( $term->term_id, 'mwb_ct_booking_service_cost', true );
		$multiply_unit_check   = get_term_meta( $term->term_id, 'mwb_booking_ct_services_multiply_units', true );
		$multiply_people_check = get_term_meta( $term->term_id, 'mwb_booking_ct_services_multiply_people', true );
		$has_quantity          = get_term_meta( $term->term_id, 'mwb_booking_ct_services_has_quantity', true );
		$if_hidden             = get_term_meta( $term->term_id, 'mwb_booking_ct_services_hidden', true );
		$if_optional           = get_term_meta( $term->term_id, 'mwb_booking_ct_services_optional', true );
		$min_quantity          = get_term_meta( $term->term_id, 'mwb_booking_ct_services_min_quantity', true );
		$max_quantity          = get_term_meta( $term->term_id, 'mwb_booking_ct_services_max_quantity', true );

		?>
		<tr class="form-field term-service-cost-wrap">
			<th><label for="mwb_ct_booking_service_cost"><?php esc_html_e( 'Service Cost', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_ct_booking_service_cost" name="mwb_ct_booking_service_cost" value="<?php echo esc_html( ! empty( $service_cost ) ? $service_cost : '' ); ?>" />
				<p class="description"><?php esc_html_e( 'Enter the cost for the services to be included', 'mwb-wc-bk' ); ?></p>
			</td>	
		</tr>
		<tr class="form-field term-custom-checks-wrap">
			<th><label for="mwb_booking_ct_services_multiply_units"><?php esc_html_e( 'Multiply cost by booking units', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_services_multiply_units" name="mwb_booking_ct_services_multiply_units" value="yes" <?php checked( 'yes', ! empty( $multiply_unit_check ) ? $multiply_unit_check : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Select to multiply the service cost by the number of booking units.', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-custom-checks-wrap">
			<th><label for="mwb_booking_ct_services_multiply_people"><?php esc_html_e( 'Multiply cost by number of peoples per booking', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_services_multiply_people" name="mwb_booking_ct_services_multiply_people" value="yes" <?php checked( 'yes', ! empty( $multiply_people_check ) ? $multiply_people_check : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Select to multiply the service cost by the number of people selected.', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-custom-checks-wrap">
			<th><label for="mwb_booking_ct_services_has_quantity"><?php esc_html_e( 'If has quantity', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_services_has_quantity" name="mwb_booking_ct_services_has_quantity" value="yes" <?php checked( 'yes', ! empty( $has_quantity ) ? $has_quantity : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Check if Quantity has to be included in the services', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-custom-checks-wrap">
			<th><label for="mwb_booking_ct_services_hidden"><?php esc_html_e( 'If Hidden', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_services_hidden" name="mwb_booking_ct_services_hidden" value="yes" <?php checked( 'yes', ! empty( $if_hidden ) ? $if_hidden : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Check if the service is hidden in booking to the user', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-custom-checks-wrap">
			<th><label for="mwb_booking_ct_services_optional"><?php esc_html_e( 'If Optional', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_services_optional" name="mwb_booking_ct_services_optional" value="yes" <?php checked( 'yes', ! empty( $if_optional ) ? $if_optional : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Check if the service is optional for the user', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-has-quantity-check-wrap">
			<th><label for="mwb_booking_ct_services_min_quantity"><?php esc_html_e( 'Min Quantity', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_booking_ct_services_min_quantity" name="mwb_booking_ct_services_min_quantity" value="<?php echo esc_html( ! empty( $min_quantity ) ? $min_quantity : '' ); ?>">
				<p class="description"><?php esc_html_e( 'Enter the minimum quantity of the service to be included', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-has-quantity-check-wrap">
			<th><label for="mwb_booking_ct_services_max_quantity"><?php esc_html_e( 'Max Quantity', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_booking_ct_services_max_quantity" name="mwb_booking_ct_services_max_quantity" value="<?php echo esc_html( ! empty( $max_quantity ) ? $max_quantity : '' ); ?>">
				<p class="description"><?php esc_html_e( 'Enter the maximum quantity of the service to be included', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<?php
		$booking_people_taxonomy_terms = get_terms(
			array(
				'taxonomy'   => 'mwb_ct_people_type',
				'hide_empty' => false,
			)
		);
		foreach ( $booking_people_taxonomy_terms as $t ) {
				$term_name = $t->slug;
			?>
		<tr class="form-field term-has-people-check-wrap">
			<th><label for="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>"><?php echo esc_html( 'Service Cost for ' . $t->name ); ?></label></th>
			<td>
				<input type="number" id="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>" name="mwb_ct_booking_service_cost_<?php echo esc_html( $term_name ); ?>" value="<?php echo esc_html( get_term_meta( $term->term_id, 'mwb_ct_booking_service_cost_' . $term_name, true ) ); ?>">
				<p class="description"><?php esc_html_e( 'Enter the service cost for respective people type.', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
			<?php
		}
	}

	/**
	 * Adding the custom fields in our custom taxonomy "mwb_ct_people_type"
	 *
	 * @return void
	 */
	public function add_custom_fields_ct_booking_people() {
		?>
		<div class="form-field term-people-cost-wrap">
			<label for="mwb_ct_booking_people_unit_cost"><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_ct_booking_people_unit_cost" class="postform" name="mwb_ct_booking_people_unit_cost" />
			<p class="description"><?php esc_html_e( 'Enter the unit cost for the people type.', 'mwb-wc-bk' ); ?></p>

			<label for="mwb_ct_booking_people_base_cost"><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_ct_booking_people_base_cost" class="postform" name="mwb_ct_booking_people_base_cost" />
			<p class="description"><?php esc_html_e( 'Enter the base cost for the people type.', 'mwb-wc-bk' ); ?></p>

		</div>
		<div class="form-field term-people-quantity-wrap">

			<label for="mwb_booking_ct_people_min_quantity"><?php esc_html_e( 'Minimum Quantity', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_booking_ct_people_min_quantity" class="postform" name="mwb_booking_ct_people_min_quantity" />
			<p class="description"><?php esc_html_e( 'Minimum Quantity of peoples allowed for respective people type', 'mwb-wc-bk' ); ?></p>

			<label for="mwb_booking_ct_people_max_quantity"><?php esc_html_e( 'Maximum Quantity', 'mwb-wc-bk' ); ?></label>
			<input type="number" id="mwb_booking_ct_people_max_quantity" class="postform" name="mwb_booking_ct_people_max_quantity" />
			<p class="description"><?php esc_html_e( 'Maximum Quantity of peoples allowed for respective people type', 'mwb-wc-bk' ); ?></p>

		</div>
		<?php
	}

	/**
	 * Save the Custom fields in our custom taxonomy "mwb_ct_people_type"
	 *
	 * @param mixed $term_id Term ID to bbe saved.
	 * @param mixed $tt_id Term Taxonomy ID.
	 * @return void
	 */
	public function save_custom_fields_ct_booking_people( $term_id, $tt_id ) {

		update_term_meta( $term_id, 'mwb_ct_booking_people_unit_cost', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_ct_booking_people_unit_cost'] ) ? $_POST['mwb_ct_booking_people_unit_cost'] : '' ) ) ) );

		update_term_meta( $term_id, 'mwb_ct_booking_people_base_cost', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_ct_booking_people_base_cost'] ) ? $_POST['mwb_ct_booking_people_base_cost'] : '' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_people_min_quantity', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_people_min_quantity'] ) ? $_POST['mwb_booking_ct_people_min_quantity'] : '' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_people_max_quantity', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_people_max_quantity'] ) ? $_POST['mwb_booking_ct_people_max_quantity'] : '' ) ) ) );
	}

	/**
	 * Editing the custom fields in our custom taxonomy "mwb_ct_people_type"
	 *
	 * @return void
	 */
	public function edit_custom_fields_ct_booking_people( $term ) {

		$people_unit_cost    = get_term_meta( $term->term_id, 'mwb_ct_booking_people_unit_cost', true );
		$people_base_cost    = get_term_meta( $term->term_id, 'mwb_ct_booking_people_base_cost', true );
		$people_max_qunatity = get_term_meta( $term->term_id, 'mwb_booking_ct_people_max_quantity', true );
		$people_min_qunatity = get_term_meta( $term->term_id, 'mwb_booking_ct_people_min_quantity', true );

		?>
		<tr class="form-field term-booking-fields-wrap">
			<th><label for="mwb_ct_booking_people_unit_cost"><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_ct_booking_people_unit_cost" name="mwb_ct_booking_people_unit_cost" value="<?php echo esc_html( ! empty( $people_unit_cost ) ? $people_unit_cost : '' ); ?>" />
				<p class="description"><?php esc_html_e( 'Enter the unit cost for the people type.', 'mwb-wc-bk' ); ?></p>
			</td>	
		</tr>
		<tr class="form-field term-booking-fields-wrap">
			<th><label for="mwb_ct_booking_people_base_cost"><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_ct_booking_people_base_cost" name="mwb_ct_booking_people_base_cost" value="<?php echo esc_html( ! empty( $people_base_cost ) ? $people_base_cost : '' ); ?>" />
				<p class="description"><?php esc_html_e( 'Enter the base cost for the people type.', 'mwb-wc-bk' ); ?></p>
			</td>	
		</tr>
		<tr class="form-field term-booking-fields-wrap">
			<th><label for="mwb_booking_ct_people_min_quantity"><?php esc_html_e( 'Min Qunatity', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_booking_ct_people_min_quantity" name="mwb_booking_ct_people_min_quantity" value="<?php echo esc_html( ! empty( $people_min_qunatity ) ? $people_min_qunatity : '' ); ?>" />
				<p class="description"><?php esc_html_e( 'Minimum Quantity of peoples allowed for respective people type.', 'mwb-wc-bk' ); ?></p>
			</td>	
		</tr>
		<tr class="form-field term-booking-fields-wrap">
			<th><label for="mwb_booking_ct_people_max_quantity"><?php esc_html_e( 'Max Quantity', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="number" id="mwb_booking_ct_people_max_quantity" name="mwb_booking_ct_people_max_quantity" value="<?php echo esc_html( ! empty( $people_max_qunatity ) ? $people_max_qunatity : '' ); ?>" />
				<p class="description"><?php esc_html_e( 'Maximum Quantity of peoples allowed for respective people type.', 'mwb-wc-bk' ); ?></p>
			</td>	
		</tr>
		<?php
	}

	/**
	 * Adding the custom fields in our custom taxonomy "mwb_ct_costs"
	 *
	 * @return void
	 */
	public function add_custom_fields_ct_booking_cost() {
		?>
		<div class="form-field term-extra-cost-wrap">
		<?php
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_booking_ct_costs_multiply_units',
					'value'       => '',
					'description' => __( 'Multiply cost by booking unit duration', 'mwb-wc-bk' ),
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_booking_ct_costs_multiply_people',
					'value'       => '',
					'description' => __( 'Multiply cost by the number of people', 'mwb-wc-bk' ),
				)
			);
		?>
		</div>
		<?php
	}

	/**
	 * Save the Custom fields in our custom taxonomy "mwb_ct_costs"
	 *
	 * @param mixed $term_id Term ID to bbe saved.
	 * @param mixed $tt_id Term Taxonomy ID.
	 * @return void
	 */
	public function save_custom_fields_ct_booking_cost( $term_id, $tt_id ) {

		update_term_meta( $term_id, 'mwb_booking_ct_costs_multiply_units', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_costs_multiply_units'] ) ? $_POST['mwb_booking_ct_costs_multiply_units'] : 'no' ) ) ) );

		update_term_meta( $term_id, 'mwb_booking_ct_costs_multiply_people', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_costs_multiply_people'] ) ? $_POST['mwb_booking_ct_costs_multiply_people'] : 'no' ) ) ) );

	}

	/**
	 * Editing the custom fields in our custom taxonomy "mwb_ct_cost"
	 *
	 * @return void
	 */
	public function edit_custom_fields_ct_booking_cost( $term ) {

		$multiply_unit   = get_term_meta( $term->term_id, 'mwb_booking_ct_costs_multiply_units', true );
		$multiply_people = get_term_meta( $term->term_id, 'mwb_booking_ct_costs_multiply_people', true );
		// echo '<pre>';
		// print_r( get_current_screen() );
		// echo '</pre>';
		?>
		<tr class="form-field term-extra-cost-wrap">
			<th><label for="mwb_booking_ct_costs_multiply_units"><?php esc_html_e( 'Multiply cost by booking units', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_costs_multiply_units" name="mwb_booking_ct_costs_multiply_units" value="yes" <?php checked( 'yes', ! empty( $multiply_unit ) ? $multiply_unit : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Select to multiply the extra added cost by the number of booking units.', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<tr class="form-field term-extra-cost-wrap">
			<th><label for="mwb_booking_ct_costs_multiply_people"><?php esc_html_e( 'Multiply cost by the number of people', 'mwb-wc-bk' ); ?></label></th>
			<td>
				<input type="checkbox" id="mwb_booking_ct_costs_multiply_people" name="mwb_booking_ct_costs_multiply_people" value="yes" <?php checked( 'yes', ! empty( $multiply_people ) ? $multiply_people : 'no' ); ?>>
				<p class="description"><?php esc_html_e( 'Select to multiply the extra added cost by the number of people.', 'mwb-wc-bk' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Adding custom columns of the "mwb_ct_services"
	 *
	 * @param array $columns taxonomy columns.
	 * @return $columns
	 */
	public function add_columns_ct_services( $columns ) {
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'name'            => __( 'Name', 'mwb-wc-bk' ),
			'description'     => __( 'Description', 'mwb-wc-bk' ),
			'cost'            => __( 'Cost', 'mwb-wc-bk' ),
			'multiply_units'  => '<span class="dashicons dashicons-money-alt"></span><p>' . mwb_booking_help_tip( esc_html__( 'Multiply by units', 'mwb-wc-bk' ) ) . '</p>',
			'multiply_people' => '<span class="dashicons dashicons-groups"></span>',
			'has_quantity'    => '<span class="dashicons dashicons-images-alt2"></span>',
			'if_hidden'       => '<span class="dashicons dashicons-hidden"></span>',
			'if_optional'     => '<span class="dashicons dashicons-editor-help"></span>',
		);
		return $columns;
	}

	/**
	 * Managing custom columns of the "mwb_ct_services".
	 *
	 * @param mixed  $out         Output.
	 * @param string $column_name Name of the Column.
	 * @param int    $term_id     Id of the term taxonomy.
	 * @return string
	 */
	public function manage_columns_ct_services( $out, $column_name, $term_id ) {

		switch ( $column_name ) {
			case 'cost':
				$price = get_term_meta( $term_id, 'mwb_ct_booking_service_cost', true );
				$out   = ! empty( $price ) ? $price : '-';
				break;
			case 'if_hidden':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_services_hidden', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
			case 'if_optional':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_services_optional', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
			case 'has_quantity':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_services_has_quantity', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
			case 'multiply_people':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_services_multiply_people', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
			case 'multiply_units':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_services_multiply_units', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
		}
		return $out;
	}

	/**
	 * Adding custom columns of the "mwb_ct_costs"
	 *
	 * @param array $columns taxonomy columns.
	 * @return $columns
	 */
	public function add_columns_ct_costs( $columns ) {
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'name'            => __( 'Name', 'mwb-wc-bk' ),
			'description'     => __( 'Description', 'mwb-wc-bk' ),
			'slug'            => __( 'Slug', 'mwb-wc-bk' ),
			'multiply_units'  => '<span class="dashicons dashicons-money-alt"></span><p>' . mwb_booking_help_tip( esc_html__( 'Multiply by units', 'mwb-wc-bk' ) ) . '</p>',
			'multiply_people' => '<span class="dashicons dashicons-groups"></span>',
		);
		return $columns;
	}

	/**
	 * Managing custom columns of the "mwb_ct_costs".
	 *
	 * @param mixed  $out         Output.
	 * @param string $column_name Name of the Column.
	 * @param int    $term_id     Id of the term taxonomy.
	 * @return string
	 */
	public function manage_columns_ct_costs( $out, $column_name, $term_id ) {

		switch ( $column_name ) {
			case 'multiply_people':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_costs_multiply_people', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
			case 'multiply_units':
				$option = get_term_meta( $term_id, 'mwb_booking_ct_costs_multiply_units', true );
				$out    = ( 'yes' === $option ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
				break;
		}
		return $out;
	}

	/**
	 * Custom Taxonomy terms table columns dashicons handler
	 *
	 * @return void
	 */
	public function dachicon_change_handler() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$class_name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$term_id       = isset( $_POST['term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['term_id'] ) ) : '';
		$term          = get_term( $term_id );
		$taxonomy      = $term->taxonomy;
		$class_name    = preg_replace( '/if_/i', '', $class_name );
		$taxonomy_slug = preg_replace( '/mwb_ct_/i', '', $taxonomy );
	//	echo 'mwb_booking_' . $taxonomy_slug . '_' . $class_name;
		$check = get_term_meta( $term_id, 'mwb_booking_ct_' . $taxonomy_slug . '_' . $class_name, true );

		if ( ! empty( $check ) ) {
			if ( 'yes' === $check ) {
				update_term_meta( $term_id, 'mwb_booking_ct_' . $taxonomy_slug . '_' . $class_name, 'no' );
			} else {
				update_term_meta( $term_id, 'mwb_booking_ct_' . $taxonomy_slug . '_' . $class_name, 'yes' );
			}
		}
		die;
	}

	/**
	 * Add Global Availability Rule Ajax Handler
	 *
	 * @return void
	 */
	public function add_global_availability_rule() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}
		$rule_count = ! empty( $_POST['rule_count'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_count'] ) ) : 0;

		?>

		<div id="mwb_global_availability_rule_<?php echo esc_html( $rule_count ); ?>" data-id="<?php echo esc_html( $rule_count ); ?>">
			<table class="form-table mwb_global_availability_rule_fields" >
				<tbody>
					<div class="mwb_global_availability_rule_heading">
						<h2>
						<label><?php echo esc_html( sprintf( 'Rule No- %u', $rule_count ) ); ?></label>
						<input type="hidden" name="mwb_availability_rule_count" value="<?php echo esc_html( $rule_count ); ?>" >
						<input type="checkbox" class="mwb_global_availability_rule_heading_switch" name="mwb_global_availability_rule_heading_switch[<?php echo esc_html( $rule_count - 1 ); ?>]" checked  >
						</h2>
					</div>
					<tr valign="top">
						<th scope="row" class="">
							<label><?php esc_attr_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text" class="mwb_global_availability_rule_name" name="mwb_global_availability_rule_name[<?php echo esc_html( $rule_count - 1 ); ?>]" >
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><?php esc_attr_e( 'Rule Type', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="radio" class="mwb_global_availability_rule_type_specific" name="mwb_global_availability_rule_type[<?php echo esc_html( $rule_count - 1 ); ?>]" value="specific" checked >
							<label><?php esc_attr_e( 'Specific Dates', 'mwb-wc-bk' ); ?></label><br>
							<input type="radio" class="mwb_global_availability_rule_type_generic" name="mwb_global_availability_rule_type[<?php echo esc_html( $rule_count - 1 ); ?>]" value="generic">
							<label><?php esc_attr_e( 'Generic Dates', 'mwb-wc-bk' ); ?></label><br>
						</td>
					</tr>
					<tr valign="top" class="range">
						<th scope="row" class="">
							<label><?php esc_attr_e( 'From', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<p>
								<input type="date" class="mwb_global_availability_rule_range_from" name="mwb_global_availability_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]" >
								<label><?php esc_attr_e( 'To', 'mwb-wc-bk' ); ?></label>
								<input type="date" class="mwb_global_availability_rule_range_to" name="mwb_global_availability_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]" >
							</p>
						</td>
					</tr>
					<tr valign="top" class="bookable">
						<th scope="row" class=""></th>
						<td class="forminp forminp-text">
							<p>
							<input type="radio" class="mwb_global_availability_rule_bookable" name="mwb_global_availability_rule_bookable[<?php echo esc_html( $rule_count - 1 ); ?>]" value="bookable" checked >
							<label><?php esc_attr_e( 'Bookable', 'mwb-wc-bk' ); ?>Bookable</label><br>
							<input type="radio" class="mwb_global_availability_rule_non_bookable" name="mwb_global_availability_rule_bookable[<?php echo esc_html( $rule_count - 1 ); ?>]" value="non-bookable" >
							<label><?php esc_attr_e( 'Non-Bookable', 'mwb-wc-bk' ); ?></label><br>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class=""></th>
						<td class="forminp forminp-text" >
							<p>
								<input type="checkbox" class="mwb_global_availability_rule_weekdays" name="mwb_global_availability_rule_weekdays[<?php echo esc_html( $rule_count - 1 ); ?>]"  >
								<?php esc_attr_e( 'Rules for weekdays', 'mwb-wc-bk' ); ?>
							</p>
						</td>
						<?php foreach ( $this->mwb_booking_search_weekdays() as $key => $values ) { ?>
							<td class="forminp forminp-text mwb_global_availability_rule_weekdays_book" style="display:none">
								<p><?php echo esc_html( $values ); ?></p>
								<input type="button" class="mwb_global_availability_rule_weekdays_book button" name="mwb_global_availability_rule_weekdays_book[<?php echo esc_html( $rule_count - 1 ); ?>][<?php echo esc_html( $key ); ?>]" value="bookable">
							</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
			<button type="button" id="mwb_delete_avialability_rule" class="button" rule_count="<?php echo esc_html( $rule_count ); ?>" ><?php esc_attr_e( 'Delete Rule', 'mwb-wc-bk' ); ?></button>
		</div>
			<?php
			wp_die();
	}

	/**
	 * Delete Global Availability Rule
	 *
	 * @return void
	 */
	public function delete_global_availability_rule() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$del_count = isset( $_POST['del_count'] ) ? sanitize_text_field( wp_unslash( $_POST['del_count'] ) ) : '';

		$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
		if ( ! empty( $del_count ) && $del_count > 0 ) {
			unset( $availability_rules['rule_switch'][ $del_count - 1 ] );
			unset( $availability_rules['rule_name'][ $del_count - 1 ] );
			unset( $availability_rules['rule_type'][ $del_count - 1 ] );
			unset( $availability_rules['rule_range_from'][ $del_count - 1 ] );
			unset( $availability_rules['rule_range_to'][ $del_count - 1 ] );
			unset( $availability_rules['rule_bookable'][ $del_count - 1 ] );
			unset( $availability_rules['rule_weekdays'][ $del_count - 1 ] );
			unset( $availability_rules['rule_weekdays_book'][ $del_count - 1 ] );

			$availability_rules['rule_switch']        = array_values( $availability_rules['rule_switch'] );
			$availability_rules['rule_name']          = array_values( $availability_rules['rule_name'] );
			$availability_rules['rule_type']          = array_values( $availability_rules['rule_type'] );
			$availability_rules['rule_range_from']    = array_values( $availability_rules['rule_range_from'] );
			$availability_rules['rule_range_to']      = array_values( $availability_rules['rule_range_to'] );
			$availability_rules['rule_bookable']      = array_values( $availability_rules['rule_bookable'] );
			$availability_rules['rule_weekdays']      = array_values( $availability_rules['rule_weekdays'] );
			$availability_rules['rule_weekdays_book'] = array_values( $availability_rules['rule_weekdays_book'] );

			// $count = count( $availability_rules['rule_switch'] );
			// for ( $c = 0; $c < $count; $c++ ) {
			// 	$inc = 1;

			// 	$availability_rules1['rule_switch'][ $inc ]     = $availability_rules['rule_switch'][ $c ];
			// 	$availability_rules1['rule_name'][ $inc ]       = $availability_rules['rule_name'][ $c ];
			// 	$availability_rules1['rule_type'][ $inc ]       = $availability_rules['rule_type'][ $c ];
			// 	$availability_rules1['rule_range_from'][ $inc ] = $availability_rules['rule_range_from'][ $c ];
			// 	$availability_rules1['rule_range_to'][ $inc ]   = $availability_rules['rule_range_to'][ $c ];
			// 	$inc++;
			// }
		}
		update_option( 'mwb_global_avialability_rules', $availability_rules );
	}

	/**
	 * Add Global Cost Rule Ajax Handler
	 *
	 * @return void
	 */
	public function add_global_cost_rule() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}
		$rule_count = ! empty( $_POST['rule_count'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_count'] ) ) : 0;

		?>

		<div id="mwb_global_cost_rule_<?php echo esc_html( $rule_count ); ?>" data-id="<?php echo esc_html( $rule_count ); ?>">
			<table class="form-table mwb_global_cost_rule_fields" >
				<tbody>
					<div class="mwb_global_cost_rule_heading">
						<h2>
						<label><?php echo esc_html( sprintf( 'Rule No- %u', $rule_count ) ); ?></label>
						<input type="hidden" name="mwb_cost_rule_count" value="<?php echo esc_html( $rule_count ); ?>" >
						<input type="checkbox" class="mwb_global_cost_rule_heading_switch" name="mwb_global_cost_rule_heading_switch[<?php echo esc_html( $rule_count - 1 ); ?>]" checked  >
						</h2>
					</div>
					<tr valign="top">
						<th scope="row" class="">
							<label><?php esc_html_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<input type="text" class="mwb_global_cost_rule_name" name="mwb_global_cost_rule_name[<?php echo esc_html( $rule_count - 1 ); ?>]" >
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><?php esc_html_e( 'Conditions', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<select name="mwb_global_cost_rule_condition[<?php echo esc_html( $rule_count - 1 ); ?>]" id="mwb_booking_global_cost_condition" class="mwb_booking_global_cost_condition" style="width: auto; margin-right: 7px;">
								<option value="" selected><?php esc_html_e( 'none', 'mwb-wc-bk' ); ?></option>
							<?php
							foreach ( $this->global_cost_conditions() as $k => $v ) {
								if ( ! is_array( $v ) ) {
									?>
									<option value="<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></option>
									<?php
								} else {
									foreach ( $v as $peoples => $people ) {
										?>
									<option value="<?php echo esc_html( $peoples ); ?>"><?php echo esc_html( $people ); ?></option>
										<?php
									}
								}
							}
							?>
							</select>
						</td>
						<td class="forminp forminp-text">
							<p>
								<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
								<input type="date" class="mwb_global_cost_rule_range_from" name="mwb_global_cost_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]" >
								<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
								<input type="date" class="mwb_global_cost_rule_range_to" name="mwb_global_cost_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]" >
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><?php esc_html_e( 'Base Cost', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<p>
								<select name="mwb_global_cost_rule_base_cal[<?php echo esc_html( $rule_count - 1 ); ?>]" id="mwb_global_cost_rule_base_cal" class="mwb_global_cost_rule_base_cal">
									<?php
									foreach ( $this->mwb_booking_global_cost_cal() as $k => $v ) {
										?>
										<option value="<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></option>
										<?php
									}
									?>
								</select>
								<input type="number" class="mwb_global_cost_rule_base_cost" name="mwb_global_cost_rule_base_cost[<?php echo esc_html( $rule_count - 1 ); ?>]" step="1" min="1" >
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="">
							<label><?php esc_html_e( 'Unit Cost', 'mwb-wc-bk' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<p>
								<select name="mwb_global_cost_rule_unit_cal[<?php echo esc_html( $rule_count - 1 ); ?>]" id="mwb_global_cost_rule_unit_cal" class="mwb_global_cost_rule_unit_cal">
									<?php
									foreach ( $this->mwb_booking_global_cost_cal() as $k => $v ) {
										?>
										<option value="<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v ); ?></option>
										<?php
									}
									?>
								</select>
								<input type="number" class="mwb_global_cost_rule_unit_cost" name="mwb_global_cost_rule_unit_cost[<?php echo esc_html( $rule_count - 1 ); ?>]" step="1" min="1" >
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<!-- <div id="mwb_delete_availability_rule_button"> -->
				<button type="button" id="mwb_delete_cost_rule" class="button" rule_count="<?php echo esc_html( $rule_count ); ?>" ><?php esc_html_e( 'Delete Rule', 'mwb-wc-bk' ); ?></button>
			<!-- </div> -->
		</div>

			<?php

			wp_die();
	}

	/**
	 * Delete Global Cost Rule
	 *
	 * @return void
	 */
	public function delete_global_cost_rule() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$del_count = isset( $_POST['del_count'] ) ? sanitize_text_field( wp_unslash( $_POST['del_count'] ) ) : '';

		$cost_rules = get_option( 'mwb_global_cost_rules', array() );
		if ( ! empty( $del_count ) && $del_count > 0 ) {
			unset( $cost_rules['rule_switch'][ $del_count - 1 ] );
			unset( $cost_rules['rule_name'][ $del_count - 1 ] );
			unset( $cost_rules['rule_condition'][ $del_count - 1 ] );
			unset( $cost_rules['rule_range_from'][ $del_count - 1 ] );
			unset( $cost_rules['rule_range_to'][ $del_count - 1 ] );
			unset( $cost_rules['rule_base_cal'][ $del_count - 1 ] );
			unset( $cost_rules['rule_base_cost'][ $del_count - 1 ] );
			unset( $cost_rules['rule_unit_cal'][ $del_count - 1 ] );
			unset( $cost_rules['rule_unit_cost'][ $del_count - 1 ] );

			$cost_rules['rule_switch']     = array_values( $cost_rules['rule_switch'] );
			$cost_rules['rule_name']       = array_values( $cost_rules['rule_name'] );
			$cost_rules['rule_condition']  = array_values( $cost_rules['rule_condition'] );
			$cost_rules['rule_range_from'] = array_values( $cost_rules['rule_range_from'] );
			$cost_rules['rule_range_to']   = array_values( $cost_rules['rule_range_to'] );
			$cost_rules['rule_base_cal']   = array_values( $cost_rules['rule_base_cal'] );
			$cost_rules['rule_base_cost']  = array_values( $cost_rules['rule_base_cost'] );
			$cost_rules['rule_unit_cal']   = array_values( $cost_rules['rule_unit_cal'] );
			$cost_rules['rule_unit_cost']  = array_values( $cost_rules['rule_unit_cost'] );

		}
		update_option( 'mwb_global_cost_rules', $cost_rules );
	}
}
