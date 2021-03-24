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
	 * Global Functions for the plugin
	 *
	 * @var object
	 */
	public $global_func;

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

		$this->global_func = Mwb_Booking_Global_Functions::get_global_instance();

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
		wp_enqueue_style( 'js-calendar-css', plugin_dir_url( __FILE__ ) . 'fullcalendar-5.5.0/lib/main.css', array(), '5.5.0', 'all' );

		wp_enqueue_style( 'wp-jquery-ui-dialog' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select' ) );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-wc-bk-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'js-calendar', plugin_dir_url( __FILE__ ) . 'fullcalendar-5.5.0/lib/main.js', array(), '5.5.0', false );
		$current_screen = get_current_screen();
		// $current_screen_id = $curent_screen->id;
		wp_localize_script(
			$this->plugin_name,
			'mwb_booking_obj',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'ajax-nonce' ),
				'screen_id' => $current_screen->id,
			)
		);

		// wp_enqueue_script( 'calendar-my-js', plugin_dir_path( __FILE__ ) . 'js/calendar.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'select2_js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );

		// wp_enqueue_script( 'mwb_booking_select2', plugin_dir_url( __FILE__ ) . 'js/mwb_select2.js', array( 'jquery' ), $this->version, false );

		// wp_localize_script( 'mwb_booking_select2', 'ajax_url', admin_url( 'admin-ajax.php' ) );

		wp_enqueue_script( 'iconify', 'https://code.iconify.design/1/1.0.7/iconify.min.js', array(), '1.0.7', false );

		add_thickbox();

		wp_enqueue_script( 'jquery-ui-dialog' );

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
				$posted_data = ! empty( $_POST[ $key ] ) ? $_POST[ $key ] : $value['default'];

			}
			update_post_meta( $post_id, $key, $posted_data );
		}
		$product = wc_get_product( $post_id );
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			$product->set_regular_price( '' );
			$product->set_sale_price( '' );
			$product->set_manage_stock( false );
			$product->set_stock_status( 'instock' );
			$base_price = get_post_meta( $product->get_id(), 'mwb_booking_base_cost_input', true );
			$unit_price = get_post_meta( $product->get_id(), 'mwb_booking_unit_cost_input', true );
			$price      = $base_price + $unit_price;
			if ( $price ) {
				update_post_meta( $product->get_id(), '_price', $price );
			}
			// $this->mwb_booking_slot_management( $post_id, $product );
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
			'mwb_start_booking_from'                 => array( 'default' => 'none' ),
			// 'mwb_start_booking_time'                 => array( 'default' => '' ),
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
			'mwb_booking_extra_cost_input'           => array( 'default' => '' ),
			'mwb_booking_extra_cost_people_input'    => array( 'default' => '' ),
			'mwb_booking_cost_discount_type'         => array( 'default' => '' ),
			'mwb_booking_monthly_discount_input'     => array( 'default' => '' ),
			'mwb_booking_weekly_discount_input'      => array( 'default' => '' ),
			'mwb_booking_custom_days_discount_input' => array( 'default' => '' ),
			'mwb_booking_custom_discount_days'       => array( 'default' => '' ),
			'mwb_booking_added_cost_select'          => array( 'default' => array() ),
			'mwb_services_enable_checkbox'           => array( 'default' => 'no' ),
			'mwb_booking_services_select'            => array( 'default' => array() ),
			// 'mwb_services_mandatory_check'           => array( 'default' => 'customer_selected' ),
			'mwb_people_enable_checkbox'             => array( 'default' => 'no' ),
			'mwb_min_people_per_booking'             => array( 'default' => '' ),
			'mwb_max_people_per_booking'             => array( 'default' => '' ),
			// 'mwb_people_as_seperate_booking'         => array( 'default' => 'no' ),
			'mwb_enable_people_types'                => array( 'default' => 'no' ),
			'mwb_booking_people_select'              => array( 'default' => array() ),
			'mwb_max_bookings_per_unit'              => array( 'default' => '' ),
			'mwb_booking_min_duration'               => array( 'default' => '' ),
			'mwb_booking_max_duration'               => array( 'default' => '' ),
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

		$arr = apply_filters( 'mwb_global_cost_condotions', $arr, $terms );
		return $arr;
	}

	/**
	 * Select2 search for Added Costs which are selected.
	 *
	 * @return void
	 */
	public function selected_added_costs_search() {

		$return = array();
		$args   = array(
			'search'     => ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
			'taxonomy'   => 'mwb_ct_costs',
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
	public function selected_services_search() {

		$return = array();
		$args   = array(
			'search'     => ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
			'taxonomy'   => 'mwb_ct_services',
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
	 * Select2 search for people_type which are selected.
	 *
	 * @since    1.0.0
	 */
	public function selected_people_type_search() {

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

		$return      = array();
		$search_term = ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
		$args        = array(
			'search'         => '*' . $search_term . '*',
			'search_columns' => array(
				'user_login',
				'user_email',
				'display_name',
			),
		);
		$get_users   = new WP_User_Query( $args );
		$users       = $get_users->get_results();

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

		$return   = array();
		$order_id = ! empty( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
		$loop     = new WP_Query(
			array(
				'post__in'       => array( $order_id ),
				'post_type'      => 'shop_order',
				'post_status'    => array( 'completed' ),
				'posts_per_page' => -1,
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
					$return[] = array( $order->ID, $loop->post->post_title );
				}
			}
		}
		echo wp_json_encode( $return );

		wp_die();

	}
	/**
	 * Product Details for create booking.
	 *
	 * @since    1.0.0
	 */
	public function create_booking_product_details() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$product_id      = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$product_meta    = get_post_meta( $product_id );
		$global_settings = get_option( 'mwb_booking_settings_options' );

		?>
		<th scope="row" class="">
			<label><h3><?php esc_html_e( 'Details', 'mwb-wc-bk' ); ?></h3></label>
		</th>
		<?php
		// if ( ! empty( $global_settings['mwb_booking_setting_go_enable'] ) && 'yes' === $global_settings['mwb_booking_setting_go_enable'] ) {
		if ( ! empty( $product_meta['mwb_booking_unit_select'][0] ) && 'fixed' === $product_meta['mwb_booking_unit_select'][0] ) {
			?>
			<td>
				<div id="mwb_create_booking_start_date">
					<label for=""><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label>
					<p><?php echo esc_html( printf( '%d-%s', $product_meta['mwb_booking_unit_input'][0], $product_meta['mwb_booking_unit_duration'][0] ) ); ?></p>
				</div>
			</td>
			<?php
		} elseif ( ! empty( $product_meta['mwb_booking_unit_select'][0] ) && 'customer' === $product_meta['mwb_booking_unit_select'][0] ) {
			if ( ! empty( $product_meta['mwb_booking_unit_duration'][0] ) && 'day' === $product_meta['mwb_booking_unit_duration'][0] && ! empty( $product_meta['mwb_enable_range_picker'] ) && 'yes' === $product_meta['mwb_enable_range_picker'][0] ) {
				?>
				<td>
					<div id="mwb_create_booking_start_date_field">
						<label for=""><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label>
						<input type="date" id="mwb_create_booking_start_date">
						<label for=""><?php esc_html_e( 'End Date', 'mwb-wc-bk' ); ?></label>
						<input type="date" id="mwb_create_booking_end_date">
					</div>
				</td>
					<?php
			} elseif ( ! empty( $product_meta['mwb_booking_unit_duration'][0] ) && ( 'hour' == $product_meta['mwb_booking_unit_duration'][0] || 'minute' == $product_meta['mwb_booking_unit_duration'][0] ) ) {
				?>
				<td>
					<div id="mwb_create_booking_start_date_field">
						<label for="mwb_create_booking_start_date"><?php esc_html_e( 'Start Date', 'mwb-wc-bk' ); ?></label>
						<input type="date" id="mwb_create_booking_start_date">
					</div>
					<div id="mwb_create_booking_start_time_field">
						<label for=""><?php esc_html__( 'Start Time', 'mwb-wc-bk' ); ?></label>
						<select name="mwb_create_booking_start_time" id="mwb_create_booking_start_time"></select>
					</div>
				</td>
				<?php
			} elseif ( ! empty( $product_meta['mwb_booking_unit_duration'][0] ) && 'month' == $product_meta['mwb_booking_unit_duration'][0] ) {
				?>
				<td>
					<div id="mwb_create_booking_start_month_field">
						<label for=""><?php esc_html_e( 'Start Month', 'mwb-wc-bk' ); ?></label>
						<input type="month" id="mwb_create_booking_start_month">
					</div>
				</td>
				<?php
			}
		}
		?>
		<!-- <pre>
			<?php // print_r( $product_meta ); ?>
		</pre> -->
		<?php
		// }
		wp_die();
		//echo wp_json_encode( $product_meta );
	}

	/**
	 * Mwb Booking Durations
	 *
	 * @return array
	 */
	public function get_booking_duration_options() {
		return apply_filters(
			'mwb_wc_bk_duration_options',
			array(
				// 'month'  => __( 'Month(s)', 'mwb-wc-bk' ),
				'day'    => __( 'Day(s)', 'mwb-wc-bk' ),
				'hour'   => __( 'Hour(s)', 'mwb-wc-bk' ),
				'minute' => __( 'Minute(s)', 'mwb-wc-bk' ),
			)
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
			'supports'           => false,
			'taxonomies'         => array( 'mwb_ct_services', 'mwb_ct_people_type' ),
			'map_meta_cap'       => true,
			'query-var'          => true,
			'menu_icon'          => 'dashicons-calendar-alt',
			//'add_order_meta_boxes' => true,
		);
		register_post_type( 'mwb_cpt_booking', $args );
		// unregister_post_type( 'mwb_cpt_booking' );
	}
	/**
	 * Custom-Post Order Type Booking
	 *
	 * @return void
	 */
	public function booking_custom_post_order_type() {
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
			'labels'                           => $labels,
			'public'                           => true,
			'description'                      => __( 'Bookings are described here', 'mwb-wc-bk' ),
			'has_archive'                      => true,
			'rewrite'                          => array( 'slug' => 'booking' ), // my custom slug.
			// 'publicly_queryable'               => false,
			// 'show_ui'                          => true,
			// 'show_in_menu'                     => true,
			// 'show_in_nav_menu'                 => true,
			// 'show_in_admin_bar'                => true,
			'hierarchical'                     => false,
			'capability_type'                  => 'shop_order', // important.
			'capabilities'                     => array(
				'create_posts' => false,
			),
			'show_in_rest'                     => false,
			'supports'                         => false,
			'taxonomies'                       => array( 'mwb_ct_services', 'mwb_ct_people_type', 'mwb_ct_costs' ),
			// 'map_meta_cap'                     => true,
			// 'query-var'                        => true,
			'menu_icon'                        => 'dashicons-calendar-alt',
			'exclude_from_orders_screen'       => true,
			'add_order_meta_boxes'             => true,
			'exclude_from_order_count'         => true,
			'exclude_from_order_views'         => true,
			'exclude_from_order_reports'       => true,
			'exclude_from_order_sales_reports' => true,
			'class_name'                       => 'WC_Order',
		);
		// wc_register_order_type( 'mwb_cpt_booking', $args );
		// unregister_post_type( 'mwb_cpt_booking' );
	}

	// public function mwb_wc_bk_booking_columns( $columns ) {

	// 	$columns = array(
	// 		'cb'            => '<input type="checkbox" />',
	// 		'booking_title' => __( 'Booking Title', '' ),
	// 		'services'      => __( 'Services', '' ),
	// 		'people'        => __( 'People Type', '' ),
	// 		'cost'          => __( 'Additional Cost', '' ),
	// 		'from'          => __( 'From', '' ),
	// 		'to'            => __( 'To', '' ),
	// 	);
	// 	return $columns;
	// }

	// public function mwb_wc_bk_manage_booking_columns( $columns, $post_id ) {

	// 	switch ( $columns ) {
	// 		case 'booking_title':
	// 			echo 'title';
	// 			// print_r( get_post_meta( $post_id ) );
	// 			break;
	// 		case 'services':
	// 			echo 'services';
	// 			break;
	// 		case 'people':
	// 			echo 'people';
	// 			break;

	// 	}
	// }

	/**
	 * Remove meta boxes from the mwb_cpt_booking order type post edit screen
	 */
	public function remove_meta_box_cpt() {

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		// echo '<pre>'; print_r( $wc_order_types ); echo '</pre>';die('order');
		// echo '<pre>'; print_r( $screen ); echo '</pre>';die("jk");
		// remove_meta_box( 'postcustom', 'mwb_cpt_booking', 'advanced' );
		// remove_meta_box( 'woocommerce-order-downloads', 'mwb_cpt_booking', 'advanced' );
		// remove_meta_box( 'woocommerce-order-items', 'mwb_cpt_booking', 'advanced' );
		// $order_type_object = get_post_type_object( 'mwb_cpt_booking' );
		// add_meta_box( 'woocommerce-order-data', sprintf( __( '%s data', 'woocommerce' ), $order_type_object->labels->singular_name ), 'WC_Meta_Box_Order_Data::output', 'mwb_cpt_booking', 'normal', 'high' );

		remove_meta_box( 'postexcerpt', 'mwb_cpt_booking', 'normal' ); // Excerpt box.
		remove_meta_box( 'commentstatusdiv', 'mwb_cpt_booking', 'normal' ); // Comment status box.
		remove_meta_box( 'commentsdiv', 'mwb_cpt_booking', 'normal' ); // Comment box.
		remove_meta_box( 'submitdiv', 'mwb_cpt_booking', 'side' );
		remove_meta_box( 'slugdiv', 'mwb_cpt_booking', 'normal' );
		remove_meta_box( 'mwb_ct_costsdiv', 'mwb_cpt_booking', 'side' );
		remove_meta_box( 'mwb_ct_people_typediv', 'mwb_cpt_booking', 'side' );
		remove_meta_box( 'mwb_ct_servicesdiv', 'mwb_cpt_booking', 'side' );

		add_meta_box( 'mwb-wc-bk-booking-data', 'Booking Order', array( $this, 'mwb_booking_order_data_callback' ), 'mwb_cpt_booking' );
		add_meta_box( 'mwb-wc-bk-booking-status', 'Booking Actions', array( $this, 'mwb_booking_status_callback' ), 'mwb_cpt_booking', 'side' );
		add_meta_box( 'mwb-wc-bk-booking-details', 'Booking Details', array( $this, 'mwb_booking_details_callback' ), 'mwb_cpt_booking', 'normal' );

	}

	public function mwb_booking_remove_support() {

		remove_post_type_support( 'mwb_cpt_booking', 'title' );
		remove_post_type_support( 'mwb_cpt_booking', 'editor' );

	}

	public function mwb_booking_details_callback() {
		global $post;
		$booking_data = get_post_meta( $post->ID, 'mwb_meta_data', true );
		echo '<pre>'; print_r( $booking_data ); echo '</pre>';
		$from = isset( $booking_data['start_timestamp'] ) ? gmdate( 'Y-m-d h:i:s a', $booking_data['start_timestamp'] ) : '-';
		$to   = isset( $booking_data['end_timestamp'] ) ? gmdate( 'Y-m-d h:i:s a', $booking_data['end_timestamp'] ) : '-';
		// $dur  = 
		$people_total = ! empty( $booking_data['people_total'] ) ? $booking_data['people_total'] : 0;
		$peoples      = ( ! empty( $booking_data['people_count'] ) && is_array( $booking_data['people_count'] ) ) ? $booking_data['people_count'] : array();
		$inc_service  = ( ! empty( $booking_data['inc_service'] ) && is_array( $booking_data['inc_service'] ) ) ? $booking_data['inc_service'] : array();
		$add_service  = ( ! empty( $booking_data['add_service'] ) && is_array( $booking_data['add_service'] ) ) ? $booking_data['add_service'] : array();
		$total_cost   = ! empty( $booking_data['total_cost'] ) ? $booking_data['total_cost'] : 0;
		// ob_start();
		?>
		<table>
			<tr>
				<th><?php esc_html_e( 'From:', '' ); ?></th>
				<td><?php echo esc_html( $from ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'To:', '' ); ?></th>
				<td><?php echo esc_html( $to ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Total People', '' ); ?></th>
				<td><?php echo esc_html( $people_total ); ?></td>
			</tr>
			<?php if ( ! empty( $peoples ) && is_array( $peoples ) ) { ?>
				<tr>
					<th><?php esc_html_e( 'Peoples:', '' ); ?></th>
					<?php
					$people_str = '';
					foreach ( $peoples as $name => $count ) {
						$people_str .= $name . '-' . $count . ', ';
					}
					$people_str = substr( $people_str, 0, -2 );
					?>
					<td><?php echo esc_html( $people_str ); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<th><?php esc_html_e( 'Included Services:', '' ); ?></th>
				<?php
				$inc_services_str = '';
				if ( ! empty( $inc_service ) && is_array( $inc_service ) ) {
					foreach ( $inc_service as $name => $count ) {
						$inc_services_str .= $name . '-' . $count . ', ';
					}
				} else {
					$inc_services_str .= 'None  ';
				}
				$inc_services_str = substr( $inc_services_str, 0, -2 );
				?>
				<td><?php echo esc_html( $inc_services_str ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Additional Services:', '' ); ?></th>
				<?php
				$add_services_str = '';
				if ( ! empty( $add_service ) && is_array( $add_service ) ) {
					foreach ( $add_service as $name => $count ) {
						$add_services_str .= $name . '-' . $count . ', ';
					}
				} else {
					$add_services_str .= 'None  ';
				}
				$add_services_str = substr( $add_services_str, 0, -2 );
				?>
				<td><?php echo esc_html( $add_services_str ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Total:', '' ); ?></th>
				<td><?php echo wc_price( esc_html( $total_cost ) ); ?></td>
			</tr>
		</table>

		<div class="mwb_booking_refund" booking_id="<?php echo esc_html( $post->ID ); ?>" >
			<input type="button" class="button refund-items mwb_booking_refund_btn" value="<?php esc_html_e( 'Refund Booking', 'mwb_wc_bk' ); ?>">
		</div>
		<?php
		// $content = ob_get_clean();
	}

	/**
	 * Manage Booking Refund.
	 * Ajax Handler
	 *
	 * @return void
	 */
	public function mwb_wc_bk_refund_booking() {

		$response = array(
			'success' => false,
			'msg'     => __( 'There is some issue with refund, Please check the order', '' ),
		);

		check_ajax_referer( 'ajax-nonce', 'nonce' );

		$booking_id = isset( $_POST['booking_id'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_id'] ) ) : '';

		if ( ! empty( $booking_id ) ) {
			$booking_meta = get_post_meta( $booking_id, 'mwb_meta_data', true );

			$order_id      = $booking_meta['order_id'];
			$refund_amount = $booking_meta['total_cost'];
			$order         = wc_get_order( $order_id );

			if ( 'refunded' !== $order->get_status() ) {

				$reason         = esc_html__( 'Booking Refund.', 'mwb_wc_bk' );
				$payment_method = $order->get_payment_method();

				if ( 'cod' === $payment_method ) {
					$refund = wc_create_refund(
						array(
							'amount'   => $refund_amount,
							'reason'   => $reason,
							'order_id' => $order_id,
						)
					);
				} else {
					$gateways           = WC()->payment_gateways->get_available_payment_gateways();
					$payment_method_obj = $gateways[ $payment_method ];
					$refund             = $payment_method_obj->process_refund( $order_id, $refund_amount, $reason );
				}

				if ( $refund && ! is_wp_error( $refund ) ) {

					$response['success'] = true;
					$response['msg']     = 'Sucess';
				}
			}
		}
		echo wp_json_encode( $response );
		// echo $booking_id;
		wp_die();
	}

	public function mwb_booking_status_callback() {
		global $post;
		$statuses     = $this->mwb_booking_status();
		$meta         = get_post_meta( $post->ID, 'mwb_meta_data', true );
		$order_id     = $meta['order_id'];
		$order        = wc_get_order( $order_id );
		$order_status = $order->get_status();
		echo '<pre>'; print_r( $order_status ); echo '</pre>';
		// if ( 'pending' == $order_status ) {
		// 	$new_status = 'pending';
		// } else

		// switch ( $order_status ) {
		// 	case 'pending':
		// 		$new_status = 'pending';
		// 		break;
		// 	case 'processing':
		// 		$new_status = 'pending';
		// 		break;
		// 	case 'on-hold':
		// 		$new_status = 'on-hold';
		// 		break;
		// 	case 'completed':
		// 		$new_status = 'completed';
		// 		break;
		// 	case 'cancelled':
		// 		$new_status = 'expired';
		// 		break;
		// 	case 'failed':
		// 		$new_status = 'expired';
		// 		break;
		// 	case 'refunded':
		// 		$new_status = 'refunded';
		// 		break;
		// 	default:
		// 		$new_status = 'pending';
		// 		break;
		// }
		// update_post_meta( $post->ID, 'mwb_booking_status', $new_status );
		$current_status = get_post_meta( $post->ID, 'mwb_booking_status', true );
		$current_status = ! empty( $current_status ) ? $current_status : 'pending';
		?>
		<div class="mwb_booking_cpt_actions">
			<div class="mwb_booking_cpt_status">
				<label for="mwb_booking_status_select"><?php esc_html_e( 'Booking Status:', '' ); ?></label>
				<select name="mwb_booking_status_select" id="mwb_booking_status_select">
					<?php foreach ( $statuses as $status => $val ) { ?>
					<option value="<?php echo esc_html( $status ); ?>" <?php selected( $status, $current_status, true ); ?>><?php echo esc_html( $val ); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mwb_bookimg_refund" >
				<!-- <label for="mwb_booking_action_select"><?php // esc_html_e( 'Booking Actions:', '' ); ?></label>
				<select name="mwb_booking_action_select" id="mwb_booking_action_select">
					<option value="send_email"><?php // esc_html_e( 'Send Email based on actions' ); ?></option>
				</select> -->

			</div>
			<div class="mwb_booking_cpt_submit">
				<input type="submit" name="save" id="publish" class="button button-primary button-large" value="<?php esc_html_e( 'Update', '' ); ?>" >
			</div>
		</div>
		<?php
	}

	public function mwb_booking_order_data_callback() {
		global $post;
		$meta   = get_post_meta( $post->ID, 'mwb_meta_data', true );
		$status = get_post_meta( $post->ID, 'mwb_booking_status', true );
		// echo '<pre>'; print_r( $post ); echo '</pre>';
		// echo '<pre>'; print_r( $meta ); echo '</pre>';

		$order_id       = isset( $meta['order_id'] ) ? $meta['order_id'] : '';
		$customer_id    = wc_get_order( $order_id )->get_data()['customer_id'];
		$user           = get_user_by( 'ID', $customer_id );
		// echo '<pre>'; print_r( $user->user_login ); echo '</pre>';
		$product_id     = isset( $meta['product_id'] ) ? $meta['product_id'] : '';
		$product_name   = get_post( $product_id )->post_name;
		$status         = isset( $status ) ? $status : 'pending';
		$created_date   = isset( $post->post_date ) ? gmdate( 'Y-m-d', strtotime( $post->post_date ) ) : '';
		$created_time_h = isset( $post->post_date ) ? gmdate( 'H', strtotime( $post->post_date ) ) : '';
		$created_time_m = isset( $post->post_date ) ? gmdate( 'i', strtotime( $post->post_date ) ) : '';

		// $statuses = get_post_stati();
		// echo '<pre>'; print_r( $statuses ); echo '</pre>';

		?>
		<div class="panel woocommerce-order-data" >
			<h1 class="woocommerce-order-data__heading"><?php echo esc_html( $post->post_title . '  ' ); ?><span><a href="javascript:void(0)"><?php echo esc_html( $status ); ?></a></span></h1>
			<div class=mwb_booking_data_column_container>
				<div class="mwb_booking_data_column">
					<h3><?php esc_html_e( 'General data', '' ); ?></h3>
					<p class="form-field form-field-wide" >
						<label for=""><?php esc_html_e( 'Booking Created', 'mwb-wc-bk' ); ?></label>
						<input type="text" id="" value="<?php echo esc_html( $created_date ); ?>" >
						<span><?php esc_html_e( '@', 'mwb-wc-bk' ); ?></span>
						<input type="number" id="" value="<?php echo esc_html( $created_time_h ); ?>">
						<span><?php esc_html_e( ':', 'mwb-wc-bk' ); ?></span>
						<input type="number" id="" value="<?php echo esc_html( $created_time_m ); ?>">
					</p>
					<p>
						<label for=""><?php esc_html_e( 'Booking Product', 'mwb-wc-bk' ); ?><a href="<?php echo esc_url( get_edit_post_link( $product_id ) ); ?>" target="__blank"><?php esc_html_e( 'View Product ->', '' ); ?></a></label>
						<select name="mwb_booking_product_select" id="mwb_booking_product_select" >
							<option value="<?php echo esc_html( $product_id ); ?>" ><?php echo esc_html( $product_name ); ?></option>
						</select>
					</p>
					<p>
						<label for="mwb_booking_order_select"><?php esc_html_e( 'Booking Order', 'mwb-wc-bk' ); ?><a href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>" target="__blank"><?php esc_html_e( 'View Order ->', '' ); ?></a></label>
						<select name="mwb_booking_order_select" id="mwb_booking_order_select" >
							<option value="<?php echo esc_html( $order_id ); ?>" ><?php echo esc_html( sprintf( '#%d - Order', $order_id ) ); ?></option>
						</select>
					</p>
					<p>
						<label for=""><?php esc_html_e( 'Customer', 'mwb-wc-bk' ); ?><a href="<?php echo esc_url( get_edit_user_link( $customer_id ) ); ?>" target="__blank"><?php esc_html_e( 'View Customer ->', '' ); ?></a></label>
						<select name="mwb_booking_user_select" id="mwb_booking_user_select" >
							<option value="<?php echo esc_html( $order_id ); ?>" ><?php echo esc_html( sprintf( '%1s (#%d - %2s)', $user->user_login, $order_id, $user->user_email ) ); ?></option>
						</select>
					</p>
				</div>

				<div>
					<h3><?php esc_html_e( 'Booking Data', '' ); ?></h3>
					<div class="">
						<p>
							<label for=""><?php  ?></label>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * Booking edit page meta save function
	 *
	 * @param [int] $post_id ID of the booking.
	 * @return void
	 */
	public function mwb_booking_save_post( $post_id ) {

		if ( ! isset( $_POST['save'] ) ) {
			return;
		}

		$post = get_post( $post_id );

		if ( 'mwb_cpt_booking' !== $post->post_type ) {
			return;
		}
		// echo '<pre>'; print_r( $post_meta ); echo '</pre>';die('jdfbvnk');
		$post_meta = get_post_meta( $post_id, 'mwb_meta_data', true );
		$order_id  = isset( $post_meta['order_id'] ) ? $post_meta['order_id'] : '';

		$from = get_post_meta( $post_id, 'mwb_booking_status', true );
		$to   = ! empty( $_POST['mwb_booking_status_select'] ) ? $_POST['mwb_booking_status_select'] : 'pending';

		update_post_meta( $post_id, 'mwb_booking_status', $to );
		do_action( 'mwb_booking_status_' . $to, $post_id, $order_id );

		// echo '<pre>'; print_r( 'mwb_booking_status_' . $from . '_to_' . $to ); echo '</pre>';
		do_action( 'mwb_booking_status_' . $from . '_to_' . $to, $post_id, $order_id );
		// switch ( $status ) {
		// 	case 'expired':
		// 		do_action( 'mwb_booking_send_email_expired', $status, $post_id );
		// 		break;
		// 	case 'confirmed':
		// 		do_action( 'mwb_booking_send_email_confirmed', $status, $post_id );
		// 		break;
		// 	case 'confirmation':
		// 		do_action( 'mwb_booking_send_email_confirmation', $status, $post_id );
		// 		break;
		// 	case 'completed':
		// 		do_action( 'mwb_booking_send_email_completed', $status, $post_id );
		// 		break;
		// 	case 'refunded':
		// 		do_action( 'mwb_booking_send_email_refunded', $status, $post_id );
		// 		break;
		// 	case 'pending':
		// 		do_action( 'mwb_booking_send_email_pending', $status, $post_id );
		// 		break;
		// }
	}

	// public function khbsdk() {
	// 	remove_post_type_support( 'mwb_cpt_booking', 'postcustom' );
	// }

	/**
	 * Rewrite Rules
	 *
	 * @return void
	 */
	public function my_rewrite_flush() {

		$this->booking_custom_post_order_type();
		$this->booking_register_taxonomy_services();
		$this->booking_register_taxonomy_people_type();
		$this->booking_register_taxonomy_cost();

		flush_rewrite_rules();
	}

	/**
	 * Function for action achedular to schedule statuses.
	 *
	 * @return void
	 */
	public function mwb_booking_schedule_status() {

		$settings     = get_option( 'mwb_booking_settings_options' );
		$confirm_days = ! empty( $settings['mwb_booking_setting_go_confirm_status'] ) ? $settings['mwb_booking_setting_go_confirm_status'] : 0;
		$cancel_days  = ! empty( $settings['mwb_booking_setting_go_reject'] ) ? $settings['mwb_booking_setting_go_reject'] : 0;

		// echo '<pre>'; print_r( $settings ); echo '</pre>';

		$args = array(
			'numberposts' => -1,
			'post_type'   => 'mwb_cpt_booking',
			'post_status' => 'publish',
			'meta_query'  => array(
				array(
					'relation' => 'AND',
					array(
						'key'     => 'mwb_booking_status',
						'compare' => 'EXISTS',
					),
					array(
						'key'     => 'mwb_booking_status',
						'value'   => 'expired',
						'compare' => '!=',
					),
				),
			),
		);
		// echo '<pre>'; print_r( $args ); echo '</pre>';
		$bookings = get_posts( $args );
		if ( ! empty( $bookings ) && is_array( $bookings ) ) {
			foreach ( $bookings as $booking => $value ) {
				$booking_id     = $value->ID;
				$booking_meta   = get_post_meta( $booking_id, 'mwb_meta_data', true );
				$booking_status = get_post_meta( $booking_id, 'mwb_booking_status', true );
				$product_id     = isset( $booking_meta['product_id'] ) ? $booking_meta['product_id'] : '';
				$order_id       = isset( $booking_meta['order_id'] ) ? $booking_meta['order_id'] : '';
				if ( empty( $product_id ) || empty( $order_id ) ) {
					continue;
				}
				$product_meta = get_post_meta( $product_id );

				$end_timestamp = $booking_meta['end_timestamp'];
				$order         = wc_get_order( $order_id );
				$order_data    = $order->get_data();
				$order_status  = $order_data['status'];
				$order_created = $order_data['date_created']->getTimestamp();

				$confirmation_required = ! empty( $product_meta['mwb_admin_confirmation'][0] ) ? $product_meta['mwb_admin_confirmation'][0] : 'no';

				if ( 'yes' === $confirmation_required && 'completed' === $order_status ) {

					// $booking_status = 'confirmation';
					update_post_meta( $booking_id, 'mwb_booking_status', 'confirmation' );
				} elseif ( 'no' === $confirmation_required && 'completed' === $order_status ) {
					// $booking_status = 'complete';
					update_post_meta( $booking_id, 'mwb_booking_status', 'confirmed' );
				}
				if ( time() > $end_timestamp ) {
					update_post_meta( $booking_id, 'mwb_booking_status', 'expired' );
				}

				if ( 'pending' === $order_status ) {
					if ( time() > ( $order_created + ( $cancel_days * 24 * 3600 ) ) ) {
						update_post_meta( $booking_id, 'mwb_booking_status', 'expired' );
					}
				}
				if ( 'confirmation' === $booking_status ) {
					if ( time() > ( $confirm_days * 24 * 3600 ) ) {
						update_post_meta( $booking_id, 'mwb_booking_status', 'confirmed' );
					}
				}
				// echo '<pre>'; print_r( $booking_meta ); echo '</pre>';
				// echo '<pre>'; print_r( $order_data ); echo '</pre>';
				// echo '<pre>'; print_r( $product_meta ); echo '</pre>';
			}
			// die('defvkj');	
		}
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
			'show_admin_column' => false,
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
			'show_admin_column' => false,
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
			'show_admin_column' => false,
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
			'mwb_booking_setting_go_enable'             => 'yes',
			'mwb_booking_setting_go_confirm_status'     => '',
			'mwb_booking_setting_go_reject'             => '',
			'mwb_booking_setting_bo_inc_service_enable' => 'yes',
			'mwb_booking_setting_bo_service_cost'       => 'yes',
			'mwb_booking_setting_bo_service_desc'       => 'no',
		);
	}

	/**
	 * "Bookings" Admin Menu.
	 *
	 * @return void
	 */
	public function booking_admin_menu() {

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

	/**
	 * Create Booking Page
	 *
	 * @return void
	 */
	public function menu_page_create_booking() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/mwb_create_booking.php';
	}

	/**
	 * Global settings for the plugin
	 *
	 * @return void
	 */
	public function menu_page_booking_settings() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/mwb-wc-bk-admin-display.php';
	}

	/**
	 * Calendar
	 *
	 * @return void
	 */
	public function menu_page_calendar() {

		global $woocommerce, $post, $order;

		// $order_id = $post->ID;
		// $order = new WC_Order( $order_id );

		// echo '<pre>'; print_r( $order->get_order_number() ); echo '</pre>';
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'mwb_cpt_booking',
			'post_status' => 'wc-completed',
		);
		$bookings = get_posts( $args );
		// echo '<pre>'; print_r( $bookings ); echo '</pre>';die("bookings");
		$events = array();
		foreach ( $bookings as $booking => $booking_obj ) {
			$b_id      = $booking_obj->ID;
			// $meta_data = get_post_meta( $b_id );
			$book      = get_post( $b_id );

			$meta_data = get_post_meta( $b_id, 'mwb_meta_data', true );
			// echo '<pre>'; print_r( $meta_data ); echo '</pre>';
			$id = 0;$product = '';
			if ( isset( $meta_data['product_id'] ) ) {
				$id      = $meta_data['product_id'];
				$product = wc_get_product( $meta_data['product_id'] );
			} else {
				continue;
			}
			// $th = wc_get_order( $b_id );
			// echo '<pre>'; print_r( $th->get_meta_data( 'mwb_meta_data' ) ); echo '</pre>';die("ijudsfh");
			// $date = str_replace( '/', '-', $meta_data['start_date'] );
			// $date = date_create( $date );
			// $date = date_format( $date, 'Y/-/d' );
			// $originalDate = "2010-03-21";
			$date = gmdate( 'Y-m-d', strtotime( $meta_data['start_date'] ) );
			$events[] = array(
				'id'    => $b_id,
				'title' => $product->get_name(),
				'start' => $date,
				// 'end'   => isset( $meta_data['end_date'] ) ? $meta_data['end_date'] : $meta_data['start_date'],
			);
		}
		// echo '<pre>'; print_r( $events ); echo '</pre>';
		// $meta_data = get_post_meta( $post->ID, 'mwb_meta_data' );

		echo '<div id="mwb-wc-bk-calendar" ></div><div id="calendar_event_popup"></div>';
	}

	/**
	 * Set events
	 *
	 * @return void
	 */
	public function mwb_wc_bk_get_events() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		if ( isset( $_POST['events'] ) ) {
			$args = array(
				'numberposts' => -1,
				'post_type'   => 'mwb_cpt_booking',
				'post_status' => 'publish',
			);
			$bookings = get_posts( $args );
			$events   = array();
			foreach ( $bookings as $booking => $booking_obj ) {
				$b_id = $booking_obj->ID;
				$book = get_post( $b_id );

				$meta_data = get_post_meta( $b_id, 'mwb_meta_data', true );
				$id        = 0;
				$product   = '';
				if ( isset( $meta_data['product_id'] ) ) {
					$id      = $meta_data['product_id'];
					$product = wc_get_product( $id );
				} else {
					continue;
				}
				// echo '<pre>'; print_r( $meta_data ); echo '</pre>';
				// die("ijudsfh");

				// $date = str_replace( '/', '-', $meta_data['start_date'] );
				// $date = date_create( $date );
				// $date = date_format( $date, 'Y/m/d' );
				$start_date = gmdate( 'Y-m-d H:i:s', $meta_data['start_timestamp'] );
				$end_date   = gmdate( 'Y-m-d H:i:s', $meta_data['end_timestamp'] );

				$arr = array();

				$arr['id']    = $b_id;
				$arr['title'] = sprintf( '#%d  %s', $b_id, $product->get_name() );
				$arr['start'] = $start_date;
				$arr['end']   = $end_date;

				// if ( gmdate( 'Y-m-d', $meta_data['start_timestamp'] ) == gmdate( 'Y-m-d', $meta_data['end_timestamp'] ) ) {
				// 	$arr['group_id'] = 999;
				// }

				// if ( ! empty( $meta_data['end_date'] ) ) {
				// 	$end_date   = gmdate( 'Y-m-d h:i:s a', $meta_data['end_timestamp'] );
				// 	$arr['end'] = $end_date;
				// } else {
				// 	if ( ! empty( $meta_data['start_timestamp'] ) && ! empty( $meta_data['end_timestamp'] ) ) {
				// 		$duration = $meta_data['end_timestamp'] - $meta_data['start_timestamp'];
				// 		$duration = date( 'H:i:s', $duration );
				// 		// date()
				// 	}
				// }
				// echo '<pre>'; print_r( $duration ); echo '</pre>';
				// if (  )

				// $events[] = array(
				// 	'id'    => $b_id,
				// 	'title' => sprintf( '#%d  %s', $b_id, $product->get_name() ),
				// 	'start' => $start_date,
				// 	// 'end'   => isset( $meta_data['end_date'] ) ? $meta_data['end_date'] : $meta_data['start_date'],
				// 	'end'   => $end_date,
				// );
				$events[] = $arr;
			}
		}
		echo wp_json_encode( $events );
		// echo 'khbsd';
		wp_die();
	}

	/**
	 * Show calendar event details on its click.
	 * Ajax handler
	 *
	 * @return void
	 */
	public function mwb_calendar_event_details_popup() {

		check_ajax_referer( 'ajax-nonce', 'nonce' );

		if ( ! isset( $_POST ) ) {
			die;
		}

		$booking_id = $_POST['booking_id'];
		$booking    = get_post( $booking_id );
		// $meta = get_post_meta( $booking_id, 'mwb_meta_data', true );

		$booking_data = get_post_meta( $booking_id, 'mwb_meta_data', true );
		// echo '<pre>'; print_r( $booking_data ); echo '</pre>';die('dfbv');

		$from = isset( $booking_data['start_timestamp'] ) ? gmdate( 'Y-m-d h:i:s a', $booking_data['start_timestamp'] ) : '-';
		$to   = isset( $booking_data['end_timestamp'] ) ? gmdate( 'Y-m-d h:i:s a', $booking_data['end_timestamp'] ) : '-';
		// $dur  = 
		$people_total = ! empty( $booking_data['people_total'] ) ? $booking_data['people_count'] : 0;
		$peoples      = ( ! empty( $booking_data['people_count'] ) && is_array( $booking_data['people_count'] ) ) ? $booking_data['people_count'] : array();
		$inc_service  = ( ! empty( $booking_data['inc_service'] ) && is_array( $booking_data['inc_service'] ) ) ? $booking_data['inc_service'] : array();
		$add_service  = ( ! empty( $booking_data['add_service'] ) && is_array( $booking_data['add_service'] ) ) ? $booking_data['add_service'] : array();
		$total_cost   = ! empty( $booking_data['total_cost'] ) ? $booking_data['total_cost'] : 0;
		$order_id     = ! empty( $booking_data['order_id'] ) ? $booking_data['order_id'] : 0;
		$product_id   = ! empty( $booking_data['product_id'] ) ? $booking_data['product_id'] : 0;
		$prod         = get_post( $product_id );
		$user_id      = $booking->post_author;
		ob_start();
		?>
		<table>
			<tr>
				<th><?php esc_html_e( 'Order', 'mwb-wc-bk' ); ?></th>
				<td><a href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>"><?php echo esc_html( sprintf( '#%d', $order_id ) ); ?></a></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Product', 'mwb-wc-bk' ); ?></th>
				<td><a href="<?php echo esc_url( get_edit_post_link( $product_id ) ); ?>"><?php echo esc_html( sprintf( '#%d %s', $product_id, $prod->post_title ) ); ?></a></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'User', 'mwb-wc-bk' ); ?></th>
				<td><a href="<?php echo esc_url( get_edit_user_link( $user_id ) ); ?>"><?php echo esc_html( sprintf( '#%d', $user_id ) ); ?></a></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'From:', 'mwb-wc-bk' ); ?></th>
				<td><?php echo esc_html( $from ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'To:', 'mwb-wc-bk' ); ?></th>
				<td><?php echo esc_html( $to ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Peoples:', 'mwb-wc-bk' ); ?></th>
				<?php
				if ( ! empty( $peoples ) && is_array( $peoples ) ) {
					foreach ( $peoples as $name => $count ) {
						?>
						<td><?php echo esc_html( $name . '-' . $count . '  ' ); ?></td>
						<?php
					}
				} else {
					?>
					<td><?php echo esc_html( $people_total ); ?></td>
					<?php
				}
				?>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Included Services:', 'mwb-wc-bk' ); ?></th>
				<?php
				if ( ! empty( $inc_service ) && is_array( $inc_service ) ) {
					foreach ( $inc_service as $name => $count ) {
						?>
						<td><?php echo esc_html( $name . '-' . $count . '  ' ); ?></td>
						<?php
					}
				} else {
					?>
					<td><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></td>
					<?php
				}
				?>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Additional Services:', 'mwb-wc-bk' ); ?></th>
				<?php
				if ( ! empty( $add_service ) && is_array( $add_service ) ) {
					foreach ( $add_service as $name => $count ) {
						?>
						<td><?php echo esc_html( $name . '-' . $count . '  ' ); ?></td>
						<?php
					}
				} else {
					?>
					<td><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></td>
					<?php
				}
				?>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Total:', 'mwb-wc-bk' ); ?></th>
				<td><?php echo wc_price( esc_html( $total_cost ) ); ?></td>
			</tr>
		</table>
		<?php
		$content = ob_get_clean();
		echo wp_kses_post( wpautop( wptexturize( $content ) ) . PHP_EOL );
		wp_die();
	}

	/**
	 * Adding the custom fields in our custom taxonomy "mwb_ct_services"
	 *
	 * @return void
	 */
	public function add_custom_fields_ct_booking_services() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/ct-add-fields/ct-services-add-fields.php';
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

		require_once plugin_dir_path( __FILE__ ) . 'partials/ct-edit-fields/ct-services-edit-fields.php';
	}

	/**
	 * Adding the custom fields in our custom taxonomy "mwb_ct_people_type"
	 *
	 * @return void
	 */
	public function add_custom_fields_ct_booking_people() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/ct-add-fields/ct-people-type-add-fields.php';
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

		require_once plugin_dir_path( __FILE__ ) . 'partials/ct-edit-fields/ct-people-type-edit-fields.php';
	}

	/**
	 * Adding the custom fields in our custom taxonomy "mwb_ct_costs"
	 *
	 * @return void
	 */
	public function add_custom_fields_ct_booking_cost() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/ct-add-fields/ct-costs-add-fields.php';
	}

	/**
	 * Save the Custom fields in our custom taxonomy "mwb_ct_costs"
	 *
	 * @param mixed $term_id Term ID to be saved.
	 * @param mixed $tt_id Term Taxonomy ID.
	 * @return void
	 */
	public function save_custom_fields_ct_booking_cost( $term_id, $tt_id ) {

		update_term_meta( $term_id, 'mwb_booking_ct_costs_multiply_units', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_costs_multiply_units'] ) ? $_POST['mwb_booking_ct_costs_multiply_units'] : 'no' ) ) ) );
		update_term_meta( $term_id, 'mwb_booking_ct_costs_multiply_people', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_costs_multiply_people'] ) ? $_POST['mwb_booking_ct_costs_multiply_people'] : 'no' ) ) ) );
		update_term_meta( $term_id, 'mwb_booking_ct_costs_custom_price', esc_attr( sanitize_text_field( wp_unslash( isset( $_POST['mwb_booking_ct_costs_custom_price'] ) ? $_POST['mwb_booking_ct_costs_custom_price'] : 0 ) ) ) );

	}

	/**
	 * Editing the custom fields in our custom taxonomy "mwb_ct_cost"
	 *
	 * @return void
	 */
	public function edit_custom_fields_ct_booking_cost( $term ) {

		require_once plugin_dir_path( __FILE__ ) . 'partials/ct-edit-fields/ct-costs-edit-fields.php';
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
			'multiply_units'  => '<span class="dashicons dashicons-money-alt"></span><p>' . $this->global_func->mwb_booking_help_tip( esc_html__( 'Multiply by units', 'mwb-wc-bk' ) ) . '</p>',
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
			'multiply_units'  => '<span class="dashicons dashicons-money-alt"></span><p>' . $this->global_func->mwb_booking_help_tip( esc_html__( 'Multiply by units', 'mwb-wc-bk' ) ) . '</p>',
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

		require_once plugin_dir_path( __FILE__ ) . 'partials/ajax-templates/mwb-global-availability-ajax.php';
		wp_die();
	}

	/**
	 * Delete Global Availability Rule Ajax Handler
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

		}
		update_option( 'mwb_global_avialability_rules', $availability_rules );
	}

	/**
	 * Add Global Cost Rule Ajax Handler
	 *
	 * @return void
	 */
	public function add_global_cost_rule() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/ajax-templates/mwb-global-cost-ajax.php';
		wp_die();
	}

	/**
	 * Delete Global Cost Rule Ajax Handler
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

	/**
	 * @param array $emails
	 *
	 * @return array
	 */
	public function register_email( $emails ) {

		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-completed-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-cancelled-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-confirmation-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-confirmed-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-expired-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-pending-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-new-booking.php';
		require_once MWB_WC_BK_BASEPATH . 'includes/emails/class-mwb-email-refunded-booking.php';

		$emails['WC_Customer_Completed_Booking']    = new WC_Booking_Completed();
		$emails['WC_Customer_Cancelled_Booking']    = new WC_Booking_Cancelled();
		$emails['WC_Customer_Confirmation_Booking'] = new WC_Booking_Confirmation_Required();
		$emails['WC_Customer_Confirmed_Booking']    = new WC_Booking_Confirmed();
		$emails['WC_Customer_Expired_Booking']      = new WC_Booking_Pending();
		$emails['WC_Customer_On_Hold_Booking']      = new WC_Booking_Expired();
		$emails['WC_Customer_New_Booking']          = new WC_Booking_New();
		$emails['WC_Customer_Refunded_Booking']     = new WC_Booking_Refunded();
		return $emails;

	}

	// /**
	//  * Function to set gmail as SMTP
	//  *
	//  * @param [type] $phpmailer is the object of PHPmailer class.
	//  */
	// public function phpmailer_gmail_setup( $phpmailer ) {
	// 	$phpmailer->isSMTP();
	// 	$phpmailer->Host       = 'smtp.gmail.com';
	// 	$phpmailer->SMTPAuth   = true; // Ask it to use authenticate using the Username and Password properties.
	// 	$phpmailer->Port       = 25;
	// 	$phpmailer->Username   = 'brijmohan11.1996@gmail.com';
	// 	$phpmailer->Password   = '';
	// 	$phpmailer->SMTPSecure = 'tls'; // Choose 'ssl' for SMTPS on port 465, or 'tls' for SMTP+STARTTLS on port 25 or 587.

	// }
	// // add_action( 'phpmailer_init', 'phpmailer_gmail_setup' );

	/**
	 * Defining Booking Statuses
	 * confirmation required, confirmed, expired, payment complete, pending payment, cancelled
	 *
	 * @return array
	 */
	public function mwb_booking_status() {

		$booking_status = array(
			'confirmation' => __( 'Confirmation Required', 'mwb-wc-bk' ),
			'confirmed'    => __( 'Confirmed', 'mwb-wc-bk' ),
			'expired'      => __( 'Expired', 'mwb-wc-bk' ),
			'completed'    => __( 'Payment Completed', 'mwb-wc-bk' ),
			'pending'      => __( 'Pending Payment', 'mwb-wc-bk' ),
			'cancelled'    => __( 'Cancelled', 'mwb-wc-bk' ),
			'refunded'     => __( 'Refunded', 'mwb-wc-bk' ),
		);
		return $booking_status;
	}

	/**
	 * Add 'Booking Complete' post status.
	 */
	public function mwb_booking_custom_post_status() {

		// echo '<pre>'; print_r( $_GET['post'] ); echo '</pre>';die("post");
		// if ( isset( $_GET['post'] ) ) {
		// 	$p = get_post( sanitize_text_field( wp_unslash( $_GET['post'] ) ) );
		// 	if ( 'mwb_cpt_booking' === $p->post_type ) {
				$statuses = $this->mwb_booking_status();

				$status = array(
					/* translators: %s: Context */
					'confirmation' => _nx_noop( 'Confirmation required (%s)', 'Confirmation required (%s)', 'Booking status', 'mwb-wc-bk' ),
					/* translators: %s: Context */
					'confirmed'    => _nx_noop( 'Confirmed (%s)', 'Confirmed (%s)', 'Booking status', 'mwb-wc-bk' ),
					/* translators: %s: Context */
					'expired'      => _nx_noop( 'Expired (%s)', 'Expired (%s)', 'Booking status', 'mwb-wc-bk' ),
					/* translators: %s: Context */
					'completed'    => _nx_noop( 'Completed (%s)', 'Completed (%s)', 'Booking status', 'mwb-wc-bk' ),
					/* translators: %s: Context */
					'pending'      => _nx_noop( 'Pending Payment (%s)', 'Pending Payment (%s)', 'Booking status', 'mwb-wc-bk' ),
					/* translators: %s: Context */
					'on-hold'      => _nx_noop( 'On Hold (%s)', 'On Hold (%s)', 'Booking status', 'mwb-wc-bk' ),
				);
				foreach ( $statuses as $status => $label ) {
					register_post_status(
						$status,
						array(
							'label'                     => $label,
							'public'                    => true,
							'exclude_from_search'       => false,
							'show_in_admin_all_list'    => true,
							'show_in_admin_status_list' => true,
							// 'label_count'               => _n_noop( 'Awaiting shipment (%s)', 'Awaiting shipment (%s)' )
						)
					);
				}
		// 	}
		// }
	}

	public function add_to_post_status_dropdown() {
		global $post;
		if ( $post->post_type != 'mwb_cpt_booking' ) {
			return false;
		}
		$status = ( $post->post_status == 'completed' ) ? "jQuery( '#post-status-display' ).text( 'Completed' ); jQuery( 
		'select[name=\"post_status\"]' ).val('completed');" : '';
		echo "<script>
		jQuery(document).ready( function() {
		jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"completed\">Completed</option>' );
		" . esc_html( $status ) . "
		});
		</script>";
		$status = ( $post->post_status == 'expired' ) ? "jQuery( '#post-status-display' ).text( 'Expired' ); jQuery( 
			'select[name=\"post_status\"]' ).val('expired');" : '';
			echo "<script>
			jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"expired\">Expired</option>' );
			" . esc_html( $status ) . "
			});
			</script>";
		$status = ( $post->post_status == 'confirmation' ) ? "jQuery( '#post-status-display' ).text( 'Confirmation Required' ); jQuery( 
			'select[name=\"post_status\"]' ).val('confirmation');" : '';
			echo "<script>
			jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"confirmation\">Confirmation Required</option>' );
			" . esc_html( $status ) . "
			});
			</script>";
		$status = ( $post->post_status == 'pending' ) ? "jQuery( '#post-status-display' ).text( 'Pending Payment' ); jQuery( 
			'select[name=\"post_status\"]' ).val('pending');" : '';
			echo "<script>
			jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"pending\">Pending Payment</option>' );
			" . esc_html( $status ) . "
			});
			</script>";
		$status = ( $post->post_status == 'on-hold' ) ? "jQuery( '#post-status-display' ).text( 'On Hold' ); jQuery( 
			'select[name=\"post_status\"]' ).val('on-hold');" : '';
			echo "<script>
			jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"on-hold\">On Hold</option>' );
			" . esc_html( $status ) . "
			});
			</script>";
		$status = ( $post->post_status == 'confirmed' ) ? "jQuery( '#post-status-display' ).text( 'Confirmed' ); jQuery( 
			'select[name=\"post_status\"]' ).val('confirmed');" : '';
			echo "<script>
			jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"confirmed\">Confirmed</option>' );
			" . esc_html( $status ) . "
			});
			</script>";
	}

	/**
	 * Show Booking Statuses on the order page
	 *
	 * @param [type] $order_status Array of all the statuses.
	 * @return array
	 */
	public function mwb_booking_show_status( $order_status ) {

		// echo '<pre>'; print_r( $order_status ); echo '</pre>'; die("status");
		// if ( isset( $_GET['post'] ) ) {
		// 	$p = get_post( sanitize_text_field( wp_unslash( $_GET['post'] ) ) );
		// 	if ( 'mwb_cpt_booking' === $p->post_type ) {
				$order_status['confirmation'] = esc_html__( 'Confirmation Required', 'mwb-wc-bk' );
				$order_status['confirmed']    = esc_html__( 'Comfirmed', 'mwb-wc-bk' );
				$order_status['expired']      = esc_html__( 'Expired', 'mwb-wc-bk' );
		// 	}
		// }

		// echo '<pre>'; print_r( wc_get_order_types( 'view-orders' ) ); echo '</pre>';
		return $order_status;
	}

	/**
	 * Change Booking Statuses according to the changed order statuses.
	 *
	 * @param [int]    $order_id Id if the current order.
	 * @param [string] $from Previous order status.
	 * @param [string] $to   Changed order status.
	 * @param [object] $obj  Order Object.
	 *
	 * @return void
	 */
	public function mwb_booking_change_order( $order_id, $from, $to, $obj ) {

		// echo '<pre>'; print_r( $id ); echo '</pre>';
		// echo '<pre>'; print_r( $from ); echo '</pre>';
		// echo '<pre>'; print_r( $to ); echo '</pre>';
		// echo '<pre>'; print_r( $obj ); echo '</pre>';die("kjkj");

		$booking_id = get_post_meta( $order_id, 'mwb_booking_id', true );

		// $order        = wc_get_order( $id );
		// $order_status = $order->get_status();
		// echo '<pre>'; print_r( $order_status ); echo '</pre>';

		switch ( $to ) {
			case 'pending':
				$new_status = 'pending';
				break;
			case 'processing':
				$new_status = 'pending';
				break;
			case 'on-hold':
				$new_status = 'pending';
				break;
			case 'completed':
				$new_status = 'completed';
				break;
			case 'cancelled':
				$new_status = 'cancelled';
				break;
			case 'failed':
				$new_status = 'cancelled';
				break;
			case 'refunded':
				$new_status = 'refunded';
				break;
			default:
				$new_status = 'pending';
				break;
		}
		update_post_meta( $booking_id, 'mwb_booking_status', $new_status );

		do_action( 'mwb_booking_status_' . $new_status, $booking_id, $order_id );

		if ( 'yes' === get_post_meta( $booking_id, 'trigger_admin_email', true ) ) {
			do_action( 'mwb_booking_created', $booking_id, $order_id );
			update_post_meta( $booking_id, 'trigger_admin_email', 'no' );
		}

		// print_r($booking_id);
		// die('123');

	}

	// public function mwb_booking_set_new_status( $id, $new_status ) {
	// 	echo '<pre>'; print_r( $id ); echo '</pre>';
	// 	echo '<pre>'; print_r( $new_status ); echo '</pre>';die("new");
	// }


	/**
	 * Showing Booking Listing Custom Columns
	 *
	 * @param [array] $columns Array of all the columns.
	 * @return array
	 */
	public function mwb_booking_show_custom_columns( $columns ) {

		// $date_val = $columns['date'];

		// Unset date index.
		unset( $columns['date'] );

		$columns['from']   = __( 'From', 'mwb-wc-bk' );
		$columns['to']     = __( 'To', 'mwb-wc-bk' );
		$columns['status'] = __( 'Status', 'mwb-wc-bk' );
		$columns['view']   = __( 'View', 'mwb-wc-bk' );

		return $columns;
	}

	/**
	 * Managing Booking Listing Custom Columns
	 *
	 * @param [string] $column Column Name.
	 * @param [int]    $post_id ID of the current booking.
	 *
	 * @return void
	 */
	public function mwb_booking_manage_custom_columns( $column, $post_id ) {

		$booking_meta   = get_post_meta( $post_id, 'mwb_meta_data', true );
		// echo '<pre>'; print_r( $booking_meta ); echo '</pre>';
		$booking_status = get_post_meta( $post_id, 'mwb_booking_status', true );
		switch ( $column ) {
			case 'from':
				if ( isset( $booking_meta['start_date'] ) ) {
					$start_date = $booking_meta['start_date'];
					$from       = gmdate( 'd-m-Y, h:i a', $booking_meta['start_timestamp'] );
					echo esc_html( $from );
				} else {
					echo '-';
				}
				break;
			case 'to':
				if ( isset( $booking_meta['end_timestamp'] ) ) {
					$end_timestamp = $booking_meta['end_timestamp'];
					$to            = gmdate( 'd-m-Y, h:i a', $end_timestamp );
					echo esc_html( $to );
				} else {
					echo '-';
				}
				break;
			case 'status':
				?>
				<a href="javascript:void(0)"><?php echo esc_html( ucwords( $booking_status ) ); ?></a>
				<?php
				break;
			case 'view':
				?>
				<?php /*translators: %d: Booking details */ ?>
				<a href="admin-ajax.php?action=get_booking_details&post_id=<?php echo esc_html( $post_id ); ?>&nonce=<?php echo esc_html( wp_create_nonce( 'mwb-wc-bk-nonce' ) ); ?>" title="<?php echo esc_html( sprintf( __( 'Booking Details #%d', '' ), $post_id ) ); ?>" class="thickbox" ><img src="<?php echo esc_url( MWB_WC_BK_BASEURL . 'admin/resources/icons/eye-icon.svg' ); ?>" alt=""></a>
				<?php
		}
	}

	/**
	 * Show Booking Details on the view icon click( Booking listing Page ).
	 *
	 * @return void
	 */
	public function get_booking_details() {
		// echo "hbf";
		// $nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
		// if ( ! wp_verify_nonce( $nonce, 'mwb-wc-bk-nonce' ) ) {
		// 	die( 'Nonce value cannot be verified' );
		// }
		check_ajax_referer( 'mwb-wc-bk-nonce', 'nonce' );

		$post_id = isset( $_GET['post_id'] ) ? sanitize_text_field( wp_unslash( $_GET['post_id'] ) ) : '';

		if ( ! empty( $post_id ) ) {
			// $booking_order = get_post( $post_id );
			$booking_data = get_post_meta( $post_id, 'mwb_meta_data', true );

			// echo '<pre>'; print_r( $booking_data ); echo '</pre>';
			$from = isset( $booking_data['start_timestamp'] ) ? gmdate( 'Y-m-d h:i:s a', $booking_data['start_timestamp'] ) : '-';
			$to   = isset( $booking_data['end_timestamp'] ) ? gmdate( 'Y-m-d h:i:s a', $booking_data['end_timestamp'] ) : '-';
			// $dur  = 
			$people_total = ! empty( $booking_data['people_total'] ) ? $booking_data['people_count'] : 0;
			$peoples     = ( ! empty( $booking_data['people_count'] ) && is_array( $booking_data['people_count'] ) ) ? $booking_data['people_count'] : array();
			$inc_service = ( ! empty( $booking_data['inc_service'] ) && is_array( $booking_data['inc_service'] ) ) ? $booking_data['inc_service'] : array();
			$add_service = ( ! empty( $booking_data['add_service'] ) && is_array( $booking_data['add_service'] ) ) ? $booking_data['add_service'] : array();
			$total_cost  = ! empty( $booking_data['total_cost'] ) ? $booking_data['total_cost'] : 0;
			ob_start();
			?>
			<table>
				<tr>
					<th><?php esc_html_e( 'From:', 'mwb-wc-bk' ); ?></th>
					<td><?php echo esc_html( $from ); ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'To:', 'mwb-wc-bk' ); ?></th>
					<td><?php echo esc_html( $to ); ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Peoples:', 'mwb-wc-bk' ); ?></th>
					<?php
					if ( ! empty( $peoples ) && is_array( $peoples ) ) {
						foreach ( $peoples as $name => $count ) {
							?>
							<td><?php echo esc_html( $name . '-' . $count . '  ' ); ?></td>
							<?php
						}
					} else {
						?>
						<td><?php echo esc_html( $people_total ); ?></td>
						<?php
					}
					?>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Included Services:', 'mwb-wc-bk' ); ?></th>
					<?php
					if ( ! empty( $inc_service ) && is_array( $inc_service ) ) {
						foreach ( $inc_service as $name => $count ) {
							?>
							<td><?php echo esc_html( $name . '-' . $count . '  ' ); ?></td>
							<?php
						}
					} else {
						?>
						<td><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></td>
						<?php
					}
					?>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Additional Services:', 'mwb-wc-bk' ); ?></th>
					<?php
					if ( ! empty( $add_service ) && is_array( $add_service ) ) {
						foreach ( $add_service as $name => $count ) {
							?>
							<td><?php echo esc_html( $name . '-' . $count . '  ' ); ?></td>
							<?php
						}
					} else {
						?>
						<td><?php esc_html_e( 'None', 'mwb-wc-bk' ); ?></td>
						<?php
					}
					?>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Total:', 'mwb-wc-bk' ); ?></th>
					<td><?php echo wc_price( esc_html( $total_cost ) ); ?></td>
				</tr>
			</table>
			<?php
			$content = ob_get_clean();
		}
		echo wp_kses_post( wpautop( wptexturize( $content ) ) . PHP_EOL );
		wp_die();
		// wp_send_json( $content );
	}



}
