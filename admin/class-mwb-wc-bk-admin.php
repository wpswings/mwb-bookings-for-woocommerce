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
				// echo $key . "->";
				// print_r( $posted_data );
				// echo '<br>';
			} else {
				$posted_data = ! empty( $_POST[ $key ] ) ? $_POST[ $key ] : $value['default'];
				// echo $key. "->";
				// print_r( $posted_data );
				// echo '<br>';
			}
			//print_r( $_POST[ $key ] );
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
			'mwb_booking_extra_cost_input'           => array( 'default' => '' ),
			'mwb_booking_extra_cost_people_input'    => array( 'default' => '' ),
			'mwb_booking_cost_discount_type'      => array( 'default' => '' ),
			'mwb_booking_monthly_discount_input'     => array( 'default' => '' ),
			'mwb_booking_weekly_discount_input'      => array( 'default' => '' ),
			'mwb_booking_custom_days_discount_input' => array( 'default' => '' ),
			'mwb_booking_custom_discount_days'       => array( 'default' => '' ),
			'mwb_booking_added_cost_select'          => array( 'default' => array() ),
			'mwb_services_enable_checkbox'           => array( 'default' => 'no' ),
			'mwb_booking_services_select'            => array( 'default' => array() ),
			'mwb_services_mandatory_check'           => array( 'default' => 'customer_selected' ),
			'mwb_people_enable_checkbox'             => array( 'default' => 'no' ),
			'mwb_min_people_per_booking'             => array( 'default' => '' ),
			'mwb_max_people_per_booking'             => array( 'default' => '' ),
			'mwb_people_as_seperate_booking'         => array( 'default' => 'no' ),
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

		apply_filters( 'mwb_global_cost_condotions', $arr );
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
			'capability_type'    => 'shop_order',
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
	//	register_post_type( 'mwb_cpt_booking', $args );
		unregister_post_type( 'mwb_cpt_booking' );
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
		wc_register_order_type( 'mwb_cpt_booking', $args );
	}

	public function mwb_wc_bk_booking_columns( $columns ) {

		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'booking_title' => __( 'Booking Title', '' ),
			'services'      => __( 'Services', '' ),
			'people'        => __( 'People Type', '' ),
			'cost'          => __( 'Additional Cost', '' ),
			'from'          => __( 'From', '' ),
			'to'            => __( 'To', '' ),
		);
		return $columns;
	}

	public function mwb_wc_bk_manage_booking_columns( $columns, $post_id ) {

		switch ( $columns ) {
			case 'booking_title':
				echo 'title';
				// print_r( get_post_meta( $post_id ) );
				break;
			case 'services':
				echo 'services';
				break;
			case 'people':
				echo 'people';
				break;

		}
	}

	/**
	 * Remove meta boxes from the mwb_cpt_booking order type post edit screen
	 */
	public function remove_meta_box_cpt() {

		// $screen    = get_current_screen();
		// $screen_id = $screen ? $screen->id : '';
	//	remove_meta_box( 'postcustom', 'shop_order', 'normal' );
		remove_meta_box( 'woocommerce-order-downloads', 'mwb_cpt_booking', 'normal' );
		remove_meta_box( 'woocommerce-order-items', 'mwb_cpt_booking', 'normal' );

	}

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
			'mwb_booking_setting_go_enable'             => 'yes',
			'mwb_booking_setting_go_complete_status'    => '',
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
			// echo '<pre>'; print_r( $meta_data ); echo '</pre>';die("ijudsfh");
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
		echo '<pre>'; print_r( $events ); echo '</pre>';
		// $meta_data = get_post_meta( $post->ID, 'mwb_meta_data' );

		echo '<div id="mwb-wc-bk-calendar" ></div>';
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
				'post_status' => 'wc-completed',
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
				$date = gmdate( 'Y-m-d', strtotime( $meta_data['start_date'] ) );

				$arr['id']    = $b_id;
				$arr['title'] = $product->get_name();
				$arr['start'] = $date;
				if ( ! empty( $meta_data['end_date'] ) ) {
					$end_date   = gmdate( 'Y-m-d', strtotime( $meta_data['end_date'] ) );
					$arr['end'] = $end_date;
				} else {
					if ( ! empty( $meta_data['start_timestamp'] ) && ! empty( $meta_data['end_timestamp'] ) ) {
						$duration = $meta_data['end_timestamp'] - $meta_data['start_timestamp'];
						$duration = date( 'H:i:s', $duration );
						// date()
					}
				}
				// echo '<pre>'; print_r( $duration ); echo '</pre>';
				// if (  )

				$events[] = array(
					'id'    => $b_id,
					'title' => $product->get_name(),
					'start' => $date,
					// 'end'   => isset( $meta_data['end_date'] ) ? $meta_data['end_date'] : $meta_data['start_date'],
				);
			}
		}
		echo json_encode( $events );
		// echo 'khbsd';
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


}
