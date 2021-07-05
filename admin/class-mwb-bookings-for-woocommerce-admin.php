<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin
 */
class Mwb_Bookings_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function mbfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if (isset($screen->id) && 'makewebbetter_page_mwb_bookings_for_woocommerce_menu' === $screen->id ) {

			wp_enqueue_style('mwb-mbfw-select2-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/mwb-bookings-for-woocommerce-select2.css', array(), time(), 'all');

			wp_enqueue_style('mwb-mbfw-meterial-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all');
			wp_enqueue_style('mwb-mbfw-meterial-css2', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all');
			wp_enqueue_style('mwb-mbfw-meterial-lite', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all');

			wp_enqueue_style('mwb-mbfw-meterial-icons-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all');

			wp_enqueue_style($this->plugin_name . '-admin-global', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-bookings-for-woocommerce-admin-global.css', array( 'mwb-mbfw-meterial-icons-css' ), time(), 'all');

			wp_enqueue_style($this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-bookings-for-woocommerce-admin.scss', array(), $this->version, 'all');
			wp_enqueue_style('mwb-admin-min-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-admin.min.css', array(), $this->version, 'all');
			wp_enqueue_style('mwb-datatable-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all');
		}
		wp_enqueue_style( 'mwb-mbfw-global-custom-css', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-admin-global-custom.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function mbfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_mwb_bookings_for_woocommerce_menu' === $screen->id ) {
			wp_enqueue_script('mwb-mbfw-select2', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/mwb-bookings-for-woocommerce-select2.js', array( 'jquery' ), time(), false);
			
			wp_enqueue_script('mwb-mbfw-metarial-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mbfw-metarial-js2', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mbfw-metarial-lite', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mbfw-datatable', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/js/jquery.dataTables.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mbfw-datatable-btn', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/dataTables.buttons.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mbfw-datatable-btn-2', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/buttons.html5.min.js', array(), time(), false);
			wp_register_script($this->plugin_name . 'admin-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-bookings-for-woocommerce-admin.js', array( 'jquery', 'mwb-mbfw-select2', 'mwb-mbfw-metarial-js', 'mwb-mbfw-metarial-js2', 'mwb-mbfw-metarial-lite' ), $this->version, false);
			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'mbfw_admin_param',
				array(
					'ajaxurl'                   => admin_url('admin-ajax.php'),
					'reloadurl'                 => admin_url('admin.php?page=mwb_bookings_for_woocommerce_menu'),
					'mbfw_gen_tab_enable'       => get_option('mbfw_radio_switch_demo'),
					'mbfw_admin_param_location' => ( admin_url( 'admin.php' ) . '?page=mwb_bookings_for_woocommerce_menu&mbfw_tab=mwb-bookings-for-woocommerce-general' ),
				)
			);
			wp_enqueue_script($this->plugin_name . 'admin-js');
			wp_enqueue_script('mwb-admin-min-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-admin.min.js', array(), time(), false);
		}
		wp_enqueue_script( 'mwb-mbfw-admin-custom-global-js', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-admin-global-custom.min.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Adding settings menu for Mwb Bookings For WooCommerce.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( 'MakeWebBetter', 'MakeWebBetter', 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/MWB_Grey-01.svg', 15);
			$mbfw_menus = 
			//desc - filter for trial.
			apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $mbfw_menus ) && ! empty( $mbfw_menus ) ) {
				foreach ( $mbfw_menus as $mbfw_key => $mbfw_value ) {
					add_submenu_page( 'mwb-plugins', $mbfw_value['name'], $mbfw_value['name'], 'manage_options', $mbfw_value['menu_link'], array( $mbfw_value['instance'], $mbfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since 1.0.0
	 */
	public function mwb_mbfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset($submenu['mwb-plugins'][0]);
			}
		}
	}


	/**
	 * Mwb Bookings For WooCommerce mbfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function mbfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => __('Mwb Bookings For WooCommerce', 'mwb-bookings-for-woocommerce'),
			'slug'      => 'mwb_bookings_for_woocommerce_menu',
			'menu_link' => 'mwb_bookings_for_woocommerce_menu',
			'instance'  => $this,
			'function'  => 'mbfw_options_menu_html',
		);
		return $menus;
	}

	/**
	 * Mwb Bookings For WooCommerce mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = 
		//desc - filter for trial.
		apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			include MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * Mwb Bookings For WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_options_menu_html() {
		include_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-bookings-for-woocommerce-admin-dashboard.php';
	}

	/**
	 * Mwb developer admin hooks listing.
	 *
	 * @return void
	 */
	public function mwb_developer_admin_hooks_listing() {
		$admin_hooks = array();
		$val         = self::mwb_developer_hooks_function( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' );
		if ( ! empty( $val['hooks'] ) ) {
			$admin_hooks[] = $val['hooks'];
			unset($val['hooks']);
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js' !== $v && 'images' !== $v ) {
				$helo = self::mwb_developer_hooks_function(MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' . $v . '/');
				if ( ! empty( $helo['hooks'] ) ) {
					$admin_hooks[] = $helo['hooks'];
					unset( $helo['hooks'] );
				}
				if ( ! empty( $helo ) ) {
					$data[] = $helo;
				}
			}
		}
		return $admin_hooks;
	}

	/**
	 * Hooks for listing public hooks.
	 *
	 * @return array
	 */
	public function mwb_developer_public_hooks_listing() {
		$public_hooks = array();
		$val          = self::mwb_developer_hooks_function( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/' );
		if ( ! empty( $val['hooks'] ) ) {
			$public_hooks[] = $val['hooks'];
			unset($val['hooks']);
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js' !== $v && 'images' !== $v ) {
				$helo = self::mwb_developer_hooks_function( MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/' . $v . '/' );
				if ( ! empty( $helo['hooks'] ) ) {
					$public_hooks[] = $helo['hooks'];
					unset($helo['hooks']);
				}
				if ( ! empty( $helo ) ) {
					$data[] = $helo;
				}
			}
		}
		return $public_hooks;
	}

	/**
	 * Method to list coustom hooks.
	 *
	 * @param string $path path to the file to load custom Hooks.
	 * @return array
	 */
	public static function mwb_developer_hooks_function( $path ) {
		$all_hooks = array();
		$scan      = scandir( $path );
		$response  = array();
		foreach ( $scan as $file ) {
			if ( strpos( $file, '.php' ) ) {
				$myfile = file( $path . $file );
				foreach ( $myfile as $key => $lines ) {
					if ( preg_match( '/do_action/i', $lines ) && ! strpos( $lines, 'str_replace' ) && ! strpos( $lines, 'preg_match' ) ) {
						$all_hooks[ $key ]['action_hook'] = $lines;
						$all_hooks[ $key ]['desc']        = $myfile[ $key-1 ];
					}
					if ( preg_match( '/apply_filters/i', $lines ) && ! strpos( $lines, 'str_replace') && ! strpos( $lines, 'preg_match' ) ) {
						$all_hooks[ $key ]['filter_hook'] = $lines;
						$all_hooks[ $key ]['desc']        = $myfile[ $key-1 ];
					}
				}
			} elseif ( strpos( $file, '.' ) == '' && strpos( $file, '.' ) !== 0 ) { // phpcs:ignore
				$response['files'][] = $file;
			}
		}
		if ( ! empty( $all_hooks ) ) {
			$response['hooks'] = $all_hooks;
		}
		return $response;
	}

	/**
	 * Mwb Bookings For WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 * @param array $mbfw_settings_general Settings fields.
	 */
	public function mbfw_admin_general_settings_page( $mbfw_settings_general ) {
		$mbfw_settings_general = array(
			array(
				'title'       => __('Enable plugin', 'mwb-bookings-for-woocommerce'),
				'type'        => 'radio-switch',
				'description' => __('Enable plugin to start the functionality.', 'mwb-bookings-for-woocommerce'),
				'id'          => 'mwb_mbfw_is_plugin_enable',
				'value'       => get_option( 'mwb_mbfw_is_plugin_enable' ),
				'class'       => 'mwb_mbfw_is_plugin_enable',
				'name'        => 'mwb_mbfw_is_plugin_enable',
			),
			array(
				'title'       => __( 'Enable Bookings', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable to start the booking functionality.', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_booking_enable',
				'value'       => get_option( 'mwb_mbfw_is_booking_enable' ),
				'class'       => 'mwb_mbfw_is_booking_enable',
				'name'        => 'mwb_mbfw_is_booking_enable',
			),
			array(
				'type'        => 'button',
				'id'          => 'mwb_mbfw_general_settings_save',
				'button_text' => __('Save Settings', 'mwb-bookings-for-woocommerce'),
				'class'       => 'mwb_mbfw_general_settings_save',
				'name'        => 'mwb_mbfw_general_settings_save',
			),
		);
		return $mbfw_settings_general;
	}

	/**
	 * Booking form settings.
	 *
	 * @param array $mbfw_booking_form_array array containing booking form settings.
	 * @return array
	 */
	public function mbfw_booking_form_settings_page( $mbfw_booking_form_array ) {
		$mbfw_booking_form_array = array(
			array(
				'title'       => __('Show Included Services.', 'mwb-bookings-for-woocommerce'),
				'type'        => 'radio-switch',
				'description' => __('Enable This to Show Included Services on the Booking Form.', 'mwb-bookings-for-woocommerce'),
				'id'          => 'mwb_mbfw_is_show_included_service',
				'value'       => get_option( 'mwb_mbfw_is_show_included_service' ),
				'class'       => 'mwb_mbfw_is_show_included_service',
				'name'        => 'mwb_mbfw_is_show_included_service',
			),
			array(
				'title'       => __('Show Totals', 'mwb-bookings-for-woocommerce'),
				'type'        => 'radio-switch',
				'description' => __('Enable This to Show Total on the Booking Form.', 'mwb-bookings-for-woocommerce'),
				'id'          => 'mwb_mbfw_is_show_totals',
				'value'       => get_option( 'mwb_mbfw_is_show_totals' ),
				'class'       => 'mwb_mbfw_is_show_totals',
				'name'        => 'mwb_mbfw_is_show_totals'
			),
			array(
				'type'        => 'button',
				'id'          => 'mwb_mbfw_booking_form_settings_save',
				'button_text' => __('Save Settings', 'mwb-bookings-for-woocommerce'),
				'class'       => 'mwb_mbfw_booking_form_settings_save',
				'name'        => 'mwb_mbfw_booking_form_settings_save',
			),
		);
		return $mbfw_booking_form_array;
	}

	/**
	 * Aavilability setting page.
	 *
	 * @param array $mbfw_availability_settings array containing available settings.
	 * @return array
	 */
	public function mbfw_add_availability_settings_page( $mbfw_availability_settings ) {
		// $sub_available_setting  = array();
		// $day_available_settings = array(
		// 	'morning'   => __( 'Morning', 'mwb-booking-system-for-woocommerce' ),
		// 	'lunch_in'  => __( 'Lunch in', 'mwb-booking-system-for-woocommerce' ),
		// 	'lunch_out' => __( 'Lunch out', 'mwb-booking-system-for-woocommerce' ),
		// 	'night'     => __( 'Night', 'mwb-booking-system-for-woocommerce' ),
		// );
		// for ( $i = 0; $i < 7; $i++ ) {
		// 	$day                       = jddayofweek( $i, 1 );
		// 	$day_available_sub_setting = array();
		// 	foreach ( $day_available_settings as $day_time => $daytime_label ) {
		// 		$day_available_sub_setting[] = array(
		// 			'label'    => $daytime_label,
		// 			'id'       => 'mbfw_' . $day . '_' . $day_time,
		// 			'class'    => 'mbfw_' . $day . '_' . $day_time,
		// 			'name'     => 'mbfw_' . $day . '_' . $day_time,
		// 			'value'    => get_option( 'mbfw_' . $day . '_' . $day_time ),
		// 		);
		// 	}
		// 	$sub_available_setting[ $day ] = $day_available_sub_setting;
					
		// }
		// $mbfw_availability_settings = array(
		// 	array(
		// 		'type'     => 'availability_select',
		// 		'id'       => 'mbfw_booking_availability_per_day',
		// 		'class'    => 'mbfw_booking_availability_per_day',
		// 		'name'     => 'mbfw_booking_availability_per_day',
		// 		'sub_tabs' => $sub_available_setting,
		// 	),
		// 	array(
		// 		'type'        => 'button',
		// 		'id'          => 'mwb_mbfw_availability_settings_save',
		// 		'button_text' => __('Save Settings', 'mwb-bookings-for-woocommerce'),
		// 		'class'       => 'mwb_mbfw_availability_settings_save',
		// 		'name'        => 'mwb_mbfw_availability_settings_save',
		// 	)
		// );
		$mbfw_availability_settings = array(
			array(
				'title'       => __( 'Daily Start Time', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_daily_start_time',
				'name'        => 'mwb_mbfw_daily_start_time',
				'class'       => 'mwb_mbfw_daily_start_time',
				'value'       => get_option( 'mwb_mbfw_daily_start_time' ),
				'type'        => 'time',
				'description' => __( 'Please choose daily start time', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Daily End Time', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_daily_end_time',
				'name'        => 'mwb_mbfw_daily_end_time',
				'class'       => 'mwb_mbfw_daily_end_time',
				'value'       => get_option( 'mwb_mbfw_daily_end_time' ),
				'type'        => 'time',
				'description' => __( 'Please choose daily end time', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'mwb_mbfw_availability_settings_save',
				'button_text' => __('Save Settings', 'mwb-bookings-for-woocommerce'),
				'class'       => 'mwb_mbfw_availability_settings_save',
				'name'        => 'mwb_mbfw_availability_settings_save',
			)
		);
		return $mbfw_availability_settings;
	}

	/**
	 * Mwb Bookings For WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 * @param array $mbfw_settings_template Settings fields.
	 */
	public function mbfw_admin_template_settings_page( $mbfw_settings_template ) {
		$mbfw_settings_template = array(
		array(
		'title' => __('Text Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'text',
		'description'  => __('This is text field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_text_demo',
		'value' => '',
		'class' => 'mbfw-text-class',
		'placeholder' => __('Text Demo', 'mwb-bookings-for-woocommerce'),
		),
		array(
		'title' => __('Number Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'number',
		'description'  => __('This is number field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_number_demo',
		'value' => '',
		'class' => 'mbfw-number-class',
		'placeholder' => '',
		),
		array(
		'title' => __('Password Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'password',
		'description'  => __('This is password field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_password_demo',
		'value' => '',
		'class' => 'mbfw-password-class',
		'placeholder' => '',
		),
		array(
		'title' => __('Textarea Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'textarea',
		'description'  => __('This is textarea field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_textarea_demo',
		'value' => '',
		'class' => 'mbfw-textarea-class',
		'rows' => '5',
		'cols' => '10',
		'placeholder' => __('Textarea Demo', 'mwb-bookings-for-woocommerce'),
		),
		array(
		'title' => __('Select Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'select',
		'description'  => __('This is select field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_select_demo',
		'value' => '',
		'class' => 'mbfw-select-class',
		'placeholder' => __('Select Demo', 'mwb-bookings-for-woocommerce'),
		'options' => array(
					'' => __('Select option', 'mwb-bookings-for-woocommerce'),
					'INR' => __('Rs.', 'mwb-bookings-for-woocommerce'),
					'USD' => __('$', 'mwb-bookings-for-woocommerce'),
		),
		),
		array(
		'title' => __('Multiselect Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'multiselect',
		'description'  => __('This is multiselect field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_multiselect_demo',
		'value' => '',
		'class' => 'mbfw-multiselect-class mwb-defaut-multiselect',
		'placeholder' => '',
		'options' => array(
					'default' => __('Select currency code from options', 'mwb-bookings-for-woocommerce'),
					'INR' => __('Rs.', 'mwb-bookings-for-woocommerce'),
					'USD' => __('$', 'mwb-bookings-for-woocommerce'),
		),
		),
		array(
		'title' => __('Checkbox Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'checkbox',
		'description'  => __('This is checkbox field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_checkbox_demo',
		'value' => '',
		'class' => 'mbfw-checkbox-class',
		'placeholder' => __('Checkbox Demo', 'mwb-bookings-for-woocommerce'),
		),

		array(
		'title' => __('Radio Field Demo', 'mwb-bookings-for-woocommerce'),
		'type'  => 'radio',
		'description'  => __('This is radio field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_radio_demo',
		'value' => '',
		'class' => 'mbfw-radio-class',
		'placeholder' => __('Radio Demo', 'mwb-bookings-for-woocommerce'),
		'options' => array(
					'yes' => __('YES', 'mwb-bookings-for-woocommerce'),
					'no' => __('NO', 'mwb-bookings-for-woocommerce'),
		),
		),
		array(
		'title' => __('Enable', 'mwb-bookings-for-woocommerce'),
		'type'  => 'radio-switch',
		'description'  => __('This is switch field demo follow same structure for further use.', 'mwb-bookings-for-woocommerce'),
		'id'    => 'mbfw_radio_switch_demo',
		'value' => '',
		'class' => 'mbfw-radio-switch-class',
		'options' => array(
					'yes' => __('YES', 'mwb-bookings-for-woocommerce'),
					'no' => __('NO', 'mwb-bookings-for-woocommerce'),
		),
		),

		array(
		'type'  => 'button',
		'id'    => 'mbfw_button_demo',
		'button_text' => __('Button Demo', 'mwb-bookings-for-woocommerce'),
		'class' => 'mbfw-button-class',
		),
		);
		return $mbfw_settings_template;
	}

	/**
	 * Mwb Bookings For WooCommerce save tab settings.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_admin_save_tab_settings() {
		global $mbfw_mwb_mbfw_obj;
		$mwb_settings_save_progress = false;
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! isset( $_POST['mwb_tabs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_tabs_nonce'] ) ), 'admin_save_data' ) ) {
			return;
		}
		if ( isset( $_POST['mwb_mbfw_general_settings_save'] ) ) {
			$mwb_mbfw_gen_flag     = false;
			$mbfw_genaral_settings = 
			//desc - general settings fields.
			apply_filters( 'mbfw_general_settings_array', array() );
			$mwb_settings_save_progress = true;
		}
		if ( isset( $_POST['mwb_mbfw_booking_form_settings_save'] ) ) {
			$mwb_mbfw_gen_flag     = false;
			$mbfw_genaral_settings = 
			//desc - booking setting fields.
			apply_filters( 'mbfw_booking_form_settings_array', array() );
			$mwb_settings_save_progress = true;
		}
		if ( isset( $_POST['mwb_mbfw_availability_settings_save'] ) ) {
			$mwb_mbfw_gen_flag     = false;
			$mbfw_genaral_settings = 
			//desc - availability setting fields.
			apply_filters( 'mbfw_availability_settings_array', array() );
			$mwb_settings_save_progress = true;
		}
		if ( $mwb_settings_save_progress ) {
			$mbfw_button_index = array_search( 'submit', array_column( $mbfw_genaral_settings, 'type' ), true );
			if ( isset( $mbfw_button_index ) && ! $mbfw_button_index ) {
				$mbfw_button_index = array_search( 'button', array_column( $mbfw_genaral_settings, 'type' ), true );
			}
			if ( isset( $mbfw_button_index ) && ( '' !== $mbfw_button_index || ! $mbfw_button_index ) ) {
				unset( $mbfw_genaral_settings[ $mbfw_button_index ] );
				if ( is_array( $mbfw_genaral_settings ) && ! empty( $mbfw_genaral_settings ) ) {
					foreach ( $mbfw_genaral_settings as $mbfw_genaral_setting ) {
						if ( isset( $mbfw_genaral_setting['id'] ) && '' !== $mbfw_genaral_setting['id'] ) {
							if ( 'availability_select' === $mbfw_genaral_setting['type'] ) {
								$sub_tabs = $mbfw_genaral_setting['sub_tabs'];
								foreach ( $sub_tabs as $mbfw_sub_components ) {
									foreach ( $mbfw_sub_components as $sub_components ) {
										if ( isset( $_POST[ $sub_components['id'] ] ) ) {
											update_option( $sub_components['id'], is_array( $_POST[ $sub_components['id'] ] ) ? map_deep( wp_unslash( $_POST[ $sub_components['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $sub_components['id'] ] ) ) );
										} else {
											update_option( $sub_components['id'], '' );
										}
									}
								}
							} else {
								if ( isset( $_POST[ $mbfw_genaral_setting['id'] ] ) ) {
									update_option( $mbfw_genaral_setting['id'], is_array( $_POST[ $mbfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $mbfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $mbfw_genaral_setting['id'] ] ) ) );
								} else {
									update_option($mbfw_genaral_setting['id'], '');
								}
							}
						} else {
							$mwb_mbfw_gen_flag = true;
						}
					}
				}
				if ( $mwb_mbfw_gen_flag ) {
					$mwb_mbfw_error_text = esc_html__( 'Id of some field is missing', 'mwb-bookings-for-woocommerce' );
					$mbfw_mwb_mbfw_obj->mwb_mbfw_plug_admin_notice( $mwb_mbfw_error_text, 'error' );
				} else {
					$mwb_mbfw_error_text = esc_html__( 'Settings saved Successfully', 'mwb-bookings-for-woocommerce' );
					$mbfw_mwb_mbfw_obj->mwb_mbfw_plug_admin_notice( $mwb_mbfw_error_text, 'success' );
				}
			}
		}
	}

	/**
	 * Add product type in dropdown
	 *
	 * @param array $types array containing product types.
	 * @return array
	 */
	public function mbfw_add_product_type_in_dropdown( $types ) {
		$types['mwb_booking'] = __( 'Booking product', 'mwb-bookings-for-woocommerce' );
		return $types;	
	}

	/**
	 * Adding product data tabs at product editing page.
	 *
	 * @param array $tabs array containing tabs.
	 * @return array
	 */
	public function mbfw_add_product_data_tabs( $tabs ) {
		$tabs['shipping']['class'][]       = 'hide_if_mwb_booking';
		$tabs['attribute']['class'][]      = 'hide_if_mwb_booking';
		$tabs['linked_product']['class'][] = 'hide_if_mwb_booking';
		$tabs['advanced']['class'][]       = 'hide_if_mwb_booking';

		$tabs = array_merge(
			$tabs,
			array(
				'general_settings' => array(
					'label'    => __( 'General Settings', 'mwb-bookings-for-woocommerce' ),
					'target'   => 'mwb_booking_general_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 10,
				),
				'cost'             => array(
					'label'    => __( 'Costs', 'mwb-bookings-for-woocommerce' ),
					'target'   => 'mwb_booking_cost_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 20,
				),
				'people'           => array(
					'label'    => __( 'People', 'mwb-bookings-for-woocommerce' ),
					'target'   => 'mwb_booking_people_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 30,
				),
				'services'         => array(
					'label'    => __( 'Services', 'mwb-bookings-for-woocommerce' ),
					'target'   => 'mwb_booking_services_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 40,
				),
				'availability'      => array(
					'label'    => __( 'Availability', 'mwb-bookings-for-woocommerce' ),
					'target'   => 'mwb_booking_availability_data',
					'class'    => array( 'show_if_mwb_booking' ),
					'priority' => 50,
				),
			)
		);
		return $tabs;
	}

	/**
	 * Adding custom Product data tabs.
	 *
	 * @return void
	 */
	public function mbfw_product_data_tabs_html() {
		?>
		<div id="mwb_booking_general_data" class="panel woocommerce_options_panel show_if_mwb_booking">
			<?php
			woocommerce_wp_select(
				array(
					'id'            => 'mwb_mbfw_booking_criteria',
					'value'         => get_post_meta( get_the_ID(), 'mwb_mbfw_booking_criteria', true ),
					'label'         => __( 'Booking Type', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'      => true,
					'options'       => array(
						'customer_selected_unit' => __( 'Customer Selected Unit', 'mwb-bookings-for-woocommerce' ),
						'fixed_unit'             => __( 'Fixed Unit', 'mwb-bookings-for-woocommerce' ),
					),
					'description'   => __( 'Choose Booking unit, variable or fixed', 'mwb-bookings-for-woocommerce' )
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_mbfw_booking_count',
					// 'class'       => 'fixed_unit' === get_post_meta( get_the_ID(), 'mwb_mbfw_booking_criteria', true ) ? 'hide' : '',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_booking_count', true ),
					'label'       => __( 'Booking Count', 'mwb-bookings-for-woocommerce' ),
					'type'        => 'number',
					'description' => __( 'Enter Booking count', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_select(
				array(
					'id'            => 'mwb_mbfw_booking_unit',
					'value'         => get_post_meta( get_the_ID(), 'mwb_mbfw_booking_unit', true ),
					'label'         => __( 'Booking Unit', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'      => true,
					'options'       => array(
						'days'    => __( 'Day(s)', 'mwb-bookings-for-woocommerce' ),
						'hours'   => __( 'Hour(s)', 'mwb-bookings-for-woocommerce' ),
						'minutes' => __( 'Minute(s)', 'mwb-bookings-for-woocommerce' ),
					),
					'description'   => __( 'Choose Booking unit, days, Hours or minutes', 'mwb-bookings-for-woocommerce' )
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_mbfw_max_bookings',
					'type'        => 'number',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_max_bookings', true ),
					'label'       => __( 'Max Bookings', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Max Bookings per Unit', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_mbfw_admin_confirmation',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_admin_confirmation', true ),
					'label'       => __( 'Booking Confirmation', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Booking Confirmation Required by Admin', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_mbfw_cancellation_allowed',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_cancellation_allowed', true ),
					'label'       => __( 'Cancellation Allowed', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Cancellation will be allowed by Users', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
		</div>
		<div id="mwb_booking_cost_data" class="panel woocommerce_options_panel show_if_mwb_booking">
			<?php
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_mbfw_booking_unit_cost',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_booking_unit_cost', true ),
					'label'       => __( 'Unit Cost', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Enter Unit Cost', 'mwb-bookings-for-woocommerce' ),
					'type'        => 'number',
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_mbfw_is_booking_unit_cost_per_people',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_is_booking_unit_cost_per_people', true ),
					'label'       => __( 'Unit Cost Per People', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Unit Cost will be Multiplied by Number of People', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_mbfw_booking_base_cost',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_booking_base_cost', true ),
					'label'       => __( 'Base Cost', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Enter Base Cost', 'mwb-bookings-for-woocommerce' ),
					'type'        => 'number',
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'mwb_mbfw_is_booking_base_cost_per_people',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_is_booking_base_cost_per_people', true ),
					'label'       => __( 'Base Cost Per People', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Base Cost will be Multiplied by Number of People', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
			<p class="mwb-mbfw-additional-notice"><?php esc_html_e( 'To Add Additional cost Please add Booking costs in the meta section.', 'mwb-bookings-for-woocommerce' ); ?></p>
		</div>
		<div id="mwb_booking_people_data" class="panel woocommerce_options_panel show_if_mwb_booking">
			<?php
			woocommerce_wp_checkbox(
				array(
					'label'       => __( 'Enable People Option', 'mwb-bookings-for-woocommerce' ),
					'id'          => 'mwb_mbfw_is_people_option',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_is_people_option', true ),
					'description' => __( 'People Option will be Visible While Booking.', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_mbfw_minimum_people_per_booking',
					'type'        => 'number',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_minimum_people_per_booking', true ),
					'label'       => __( 'Minimum No. of People', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Minimum Number of People Per Booking', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'          => 'mwb_mbfw_maximum_people_per_booking',
					'type'        => 'number',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_maximum_people_per_booking', true ),
					'label'       => __( 'Maximum No. of People', 'mwb-bookings-for-woocommerce' ),
					'description' => __( 'Maximum Number of People Per Booking', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
		</div>
		<div id="mwb_booking_services_data" class="panel woocommerce_options_panel show_if_mwb_booking">
			<?php
			woocommerce_wp_checkbox(
				array(
					'label'       => __( 'Add Extra Services', 'mwb-bookings-for-woocommerce' ),
					'id'          => 'mwb_mbfw_is_add_extra_services',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_is_add_extra_services', true ),
					'description' => __( 'Add Extra Services, will be choosen by Customer while Booking.', 'mwb-bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
			<p class="mwb-mbfw-additional-notice"><?php esc_html_e( 'To Add Additional Services Please add Booking Services in the meta section.', 'mwb-bookings-for-woocommerce' ); ?></p>
		</div>
		<div id="mwb_booking_availability_data" class="panel woocommerce_options_panel show_if_mwb_booking">
			<?php
			woocommerce_wp_text_input(
				array(
					'label'       => __( 'Maximum Booking Per Unit', 'mwb-bookings-for-woocommerce' ),
					'id'          => 'mwb_mbfw_maximum_booking_per_unit',
					'value'       => get_post_meta( get_the_ID(), 'mwb_mbfw_maximum_booking_per_unit', true ),
					'description' => __( 'Maximum Bookings per Unit', 'mwb-bookings-for-woocommerce' ),
					'type'        => 'number',
					'desc_tip'    => true,
				)
			);
			?>
			<p class="mwb-mbfw-additional-notice">
				<?php
				$availability_setting_page_link = add_query_arg(
					array(
						'page'     => 'mwb_bookings_for_woocommerce_menu',
						'mbfw_tab' => 'mwb-bookings-for-woocommerce-booking-availability-settings',
					),
					admin_url( 'admin.php' )
				);
				printf(
					/* translators:%s admin setting page link. */
					esc_html__( 'To Choose daily start time and end time please %s.', 'mwb-bookings-for-woocommerce' ),
					'<a href="' . esc_url( $availability_setting_page_link ) . '" target="_blank">' . esc_html__( 'visit here', 'mwb-bookings-for-woocommerce' ) . '</a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Save custom product meta boxes data.
	 *
	 * @param int    $id post id.
	 * @param object $post post object. 
	 * @return void
	 */
	public function mbfw_save_custom_product_meta_boxes_data( $id, $post ) {
		$product = wc_get_product( $id );
		if ( $product && 'mwb_booking' === $product->get_type() ) {
			// phpcs:disable WordPress.Security.NonceVerification
			$product_meta_data = array(
				'mwb_mbfw_booking_criteria'                => array_key_exists( 'mwb_mbfw_booking_criteria', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_criteria'] ) ) : '',
				'mwb_mbfw_booking_count'                   => array_key_exists( 'mwb_mbfw_booking_count', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_count'] ) ) : '',
				'mwb_mbfw_booking_unit'                    => array_key_exists( 'mwb_mbfw_booking_unit', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_unit'] ) ) : '',
				'mwb_mbfw_max_bookings'                    => array_key_exists( 'mwb_mbfw_max_bookings', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_max_bookings'] ) ) : '',
				'mwb_mbfw_admin_confirmation'              => array_key_exists( 'mwb_mbfw_admin_confirmation', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_admin_confirmation'] ) ) : '',
				'mwb_mbfw_cancellation_allowed'            => array_key_exists( 'mwb_mbfw_cancellation_allowed', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_cancellation_allowed'] ) ) : '',
				'mwb_mbfw_booking_unit_cost'               => array_key_exists( 'mwb_mbfw_booking_unit_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_unit_cost'] ) ) : '',
				'mwb_mbfw_is_booking_unit_cost_per_people' => array_key_exists( 'mwb_mbfw_is_booking_unit_cost_per_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_booking_unit_cost_per_people'] ) ) : '',
				'mwb_mbfw_booking_base_cost'               => array_key_exists( 'mwb_mbfw_booking_base_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_base_cost'] ) ) : '',
				'mwb_mbfw_is_booking_base_cost_per_people' => array_key_exists( 'mwb_mbfw_is_booking_base_cost_per_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_booking_base_cost_per_people'] ) ) : '',
				'mwb_mbfw_is_people_option'                => array_key_exists( 'mwb_mbfw_is_people_option', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_people_option'] ) ) : '',
				'mwb_mbfw_minimum_people_per_booking'      => array_key_exists( 'mwb_mbfw_minimum_people_per_booking', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_minimum_people_per_booking'] ) ) : '',
				'mwb_mbfw_maximum_people_per_booking'      => array_key_exists( 'mwb_mbfw_maximum_people_per_booking', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_maximum_people_per_booking'] ) ) : '',
				'mwb_mbfw_is_add_extra_services'           => array_key_exists( 'mwb_mbfw_is_add_extra_services', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_add_extra_services'] ) ) : '',
				'mwb_mbfw_maximum_booking_per_unit'        => array_key_exists( 'mwb_mbfw_maximum_booking_per_unit', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_maximum_booking_per_unit'] ) ) : '',
			);
			// phpcs:enable WordPress.Security.NonceVerification
			foreach ( $product_meta_data as $meta_key => $meta_value ) {
				update_post_meta( $id, $meta_key, $meta_value );
			}
			$price = (int) $product_meta_data['mwb_mbfw_booking_base_cost'] + (int) $product_meta_data['mwb_mbfw_booking_unit_cost'];
			update_post_meta( $id, '_price', $price );
		}
	}

	/**
	 * Multiselect Html element.
	 *
	 * @param array $field array containing attributes and values of HTML fields for multiselect.
	 * @return void
	 */
	public function mbfw_multiselect_html_field( $field ) {
		global $thepostid, $post, $woocommerce;
		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['value']         = isset( $field['value'] ) && is_array( $field['value'] ) ? $field['value'] : array();
		$field['options']       = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : '';
		?>
		<p class="form-field <?php echo esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ); ?>">
			<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?></label>
			<select id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" multiple="multiple">
				<?php
				foreach ( $field['options'] as $key => $value ) {
					?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( in_array( $key, $field['value'], true ) ? 'selected="selected"' : '' ); ?>><?php echo esc_html( $value ); ?></option>
					<?php
				}
				?>
			</select>
			<?php
			if ( ! empty( $field['description'] ) ) {
				if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
					?>
					<span class="woocommerce-help-tip" data-tip="<?php echo esc_attr( $field['description'] ); ?>"></span>
					<?php
				} else {
					?>
					<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
					<?php
				}
			}
			?>
		</p>
		<?php
	}

	/**
	 * Adding extra field at custom mwb_booking_cost taxonomy page.
	 *
	 * @return void
	 */
	public function mbfw_adding_custom_fields_at_booking_cost_taxonomy_page() {
		woocommerce_wp_text_input(
			array(
				'id'          => 'mwb_mbfw_booking_cost',
				'type'        => 'number',
				'value'       => '',
				'label'       => __( 'Booking Cost', 'mwb-bookings-for-woocommerce' ),
				'description' => __( 'Please Add Booking cost here.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'Multiply by No. of People', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_booking_cost_multiply_people',
				'value'       => '',
				'description' => __( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'Multiply by Duration', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_booking_cost_multiply_duration',
				'value'       => '',
				'description' => __( 'Either to multiply by Duration of Booking.', 'mwb-bookings-for-woocommerce' ),
			)
		);
	}

	/**
	 * Html fields for custom field at taxonomy edit page.
	 *
	 * @param array $term_fields_arr array containing various attributes of HTML input tag.
	 * @return void
	 */
	public function mbfw_taxonomy_custom_fields_html( $term_fields_arr ) {
		foreach ( $term_fields_arr as $tag_arr ) {
			if ( isset( $tag_arr['term_id'] ) && isset( $tag_arr['id'] ) ) {
				?>
				<tr class="form-field">
					<th>
						<label for="<?php echo esc_attr( $tag_arr['id'] ); ?>"><?php echo wp_kses_post( isset( $tag_arr['label'] ) ?  $tag_arr['label'] : '' ); ?></label>
					</th>
					<td>
						<input name="<?php echo esc_attr( isset( $tag_arr['name'] ) ? $tag_arr['name'] : '' ); ?>" id="<?php echo esc_attr( $tag_arr['id'] ); ?>" type="<?php echo esc_attr( $tag_arr['type'] ); ?>" value="<?php echo esc_attr( ( 'checkbox' === $tag_arr['type'] ) ? 'yes' : get_term_meta( $tag_arr['term_id'], $tag_arr['id'], true ) ); ?>" <?php ( 'checkbox' === $tag_arr['type'] ) ? checked( get_term_meta( $tag_arr['term_id'], $tag_arr['id'], true ), 'yes' ) : ''; ?>/>
						<p class="description"><?php echo wp_kses_post( isset( $tag_arr['description'] ) ? $tag_arr['description'] : '' ); ?></p>
					</td>
				</tr>
				<?php
			}
		}
	}

	/**
	 * Adding custom fields at edit page of mwb_booking_cost taxonomy.
	 *
	 * @param object $term object of current term.
	 * @param string $taxonomy current taxonomy.
	 * @return void
	 */
	public function mbfw_adding_custom_fields_at_booking_cost_taxonomy_edit_page( $term, $taxonomy ) {
		$term_fields_arr = array(
			array(
				'id'          => 'mwb_mbfw_booking_cost',
				'name'        => 'mwb_mbfw_booking_cost',
				'label'       => __( 'Booking Cost', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'text',
				'term_id'     => $term->term_id,
				'description' => __( 'Please Add booking cost here.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_booking_cost_multiply_people',
				'name'        => 'mwb_mbfw_is_booking_cost_multiply_people',
				'label'       => __( 'Multiply by No. of People', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_booking_cost_multiply_duration',
				'name'        => 'mwb_mbfw_is_booking_cost_multiply_duration',
				'label'       => __( 'Multiply by Duration of Booking', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by Duration of Booking.', 'mwb-bookings-for-woocommerce' ),
			),
		);
		$this->mbfw_taxonomy_custom_fields_html( $term_fields_arr );
	}

	/**
	 * Saving custom field at mwb_booking_cost taxonomy.
	 *
	 * @param int   $term_id current term id for custom taxonomy.
	 * @return void
	 */
	public function mbfw_saving_custom_fields_at_booking_cost_taxonomy_page( $term_id ) {
		// phpcs:disable WordPress.Security.NonceVerification
		$term_meta_data = array(
			'mwb_mbfw_booking_cost'                      => array_key_exists( 'mwb_mbfw_booking_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_cost'] ) ) : '',
			'mwb_mbfw_is_booking_cost_multiply_people'   => array_key_exists( 'mwb_mbfw_is_booking_cost_multiply_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_booking_cost_multiply_people'] ) ) : '',
			'mwb_mbfw_is_booking_cost_multiply_duration' => array_key_exists( 'mwb_mbfw_is_booking_cost_multiply_duration', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_booking_cost_multiply_duration'] ) ) : '',
		);
		// phpcs:enable WordPress.Security.NonceVerification
		foreach ( $term_meta_data as $term_meta_key => $term_meta_value ) {
			update_term_meta( $term_id, $term_meta_key, $term_meta_value );
		}
	}

	/**
	 * Adding custom column to mwb_booking_cost taxonomy table.
	 *
	 * @param array $columns array containing columns.
	 * @return void
	 */
	public function mbfw_adding_custom_column_booking_costs_taxonomy_table( $columns ) {
		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );
		$columns['cost']     = __( 'Cost', 'mwb-bookings-for-woocommerce' );
		$columns['people']   = __( 'People', 'mwb-bookings-for-woocommerce' );
		$columns['duration'] = __( 'Duration', 'mwb-bookings-for-woocommerce' );
		return $columns;
	}

	/**
	 * Add custom column data to mwb_booking_cost taxonomy.
	 *
	 * @param string $content content of the column.
	 * @param string $column_name column name of the table.
	 * @param int    $term_id current term id.
	 * @return void
	 */
	public function mbfw_adding_custom_column_data_booking_costs_taxonomy_table( $content, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'cost':
				$content = get_term_meta( $term_id, 'mwb_mbfw_booking_cost', true );
				break;
			case 'people':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_booking_cost_multiply_people', true );
				break;
			case 'duration':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_booking_cost_multiply_duration', true );
				break;
			default:
				break;
		}
		return $content;
	}

	/**
	 * Adding extra field at custom mwb_booking_service taxonomy page.
	 *
	 * @return void
	 */
	public function mbfw_adding_custom_fields_at_booking_service_taxonomy_page() {
		woocommerce_wp_text_input(
			array(
				'id'          => 'mwb_mbfw_service_cost',
				'type'        => 'number',
				'value'       => '',
				'label'       => __( 'Service Cost', 'mwb-bookings-for-woocommerce' ),
				'description' => __( 'Please Add service cost here.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'Multiply by Number of People', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_service_cost_multiply_people',
				'value'       => '',
				'description' => __( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'Multiply by Booking Duration', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_service_cost_multiply_duration',
				'value'       => '',
				'description' => __( 'Either to multiply by Booking Duration.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'If Optional', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_service_optional',
				'value'       => '',
				'description' => __( 'Either the Service is Optional.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'If Hidden', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_service_hidden',
				'value'       => '',
				'description' => __( 'Either the Service is Hidden.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'label'       => __( 'If has Quantity', 'mwb-bookings-for-woocommerce' ),
				'id'          => 'mwb_mbfw_is_service_has_quantity',
				'value'       => '',
				'description' => __( 'Either the service has quantity.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => 'mwb_mbfw_service_minimum_quantity',
				'type'        => 'number',
				'value'       => '',
				'label'       => __( 'Minimum Quantity', 'mwb-bookings-for-woocommerce' ),
				'description' => __( 'Please Add Minimum Quantity of the Service Bookable.', 'mwb-bookings-for-woocommerce' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => 'mwb_mbfw_service_maximum_quantity',
				'type'        => 'number',
				'value'       => '',
				'label'       => __( 'Maximum Quantity', 'mwb-bookings-for-woocommerce' ),
				'description' => __( 'Please Add Maximum Quantity of the Service Bookable.', 'mwb-bookings-for-woocommerce' ),
			)
		);
	}

	/**
	 * Adding custom fields at edit page of mwb_booking_service taxonomy.
	 *
	 * @param object $term object of current term.
	 * @param string $taxonomy current taxonomy.
	 * @return void
	 */
	public function mbfw_adding_custom_fields_at_booking_service_taxonomy_edit_page( $term, $taxonomy ) {
		$term_fields_arr = array(
			array(
				'id'          => 'mwb_mbfw_service_cost',
				'name'        => 'mwb_mbfw_service_cost',
				'label'       => __( 'Service Cost', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'number',
				'term_id'     => $term->term_id,
				'description' => __( 'Please Add service cost here.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_service_cost_multiply_people',
				'name'        => 'mwb_mbfw_is_service_cost_multiply_people',
				'label'       => __( 'Multiply by No. of People', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_service_cost_multiply_duration',
				'name'        => 'mwb_mbfw_is_service_cost_multiply_duration',
				'label'       => __( 'Multiply by Booking Duration', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by Duration of Booking.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_service_optional',
				'name'        => 'mwb_mbfw_is_service_optional',
				'label'       => __( 'If Optional', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either the Service is Optional.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_service_hidden',
				'name'        => 'mwb_mbfw_is_service_hidden',
				'label'       => __( 'If Hidden', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either the Service is Hidden.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_is_service_has_quantity',
				'name'        => 'mwb_mbfw_is_service_has_quantity',
				'label'       => __( 'If has Quantity', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either the Service has Quantity.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_service_minimum_quantity',
				'name'        => 'mwb_mbfw_service_minimum_quantity',
				'label'       => __( 'Minimum Quantity', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'number',
				'term_id'     => $term->term_id,
				'description' => __( 'Please Add Minimum Quantity of the Service Bookable.', 'mwb-bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'mwb_mbfw_service_maximum_quantity',
				'name'        => 'mwb_mbfw_service_maximum_quantity',
				'label'       => __( 'Maximum Quantity', 'mwb-bookings-for-woocommerce' ),
				'type'        => 'number',
				'term_id'     => $term->term_id,
				'description' => __( 'Please Add Maximum Quantity.', 'mwb-bookings-for-woocommerce' ),
			),
		);
		$this->mbfw_taxonomy_custom_fields_html( $term_fields_arr );
	}

	/**
	 * Saving custom field at mwb_booking_cost taxonomy.
	 *
	 * @param int   $term_id current term id for custom taxonomy.
	 * @return void
	 */
	public function mbfw_saving_custom_fields_at_booking_service_taxonomy_page( $term_id ) {
		// phpcs:disable WordPress.Security.NonceVerification
		$term_meta_data = array(
			'mwb_mbfw_service_cost'                      => array_key_exists( 'mwb_mbfw_service_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_service_cost'] ) ) : '',
			'mwb_mbfw_is_service_cost_multiply_people'   => array_key_exists( 'mwb_mbfw_is_service_cost_multiply_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_service_cost_multiply_people'] ) ) : '',
			'mwb_mbfw_is_service_cost_multiply_duration' => array_key_exists( 'mwb_mbfw_is_service_cost_multiply_duration', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_service_cost_multiply_duration'] ) ) : '',
			'mwb_mbfw_is_service_optional'               => array_key_exists( 'mwb_mbfw_is_service_optional', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_service_optional'] ) ) : '',
			'mwb_mbfw_is_service_hidden'                 => array_key_exists( 'mwb_mbfw_is_service_hidden', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_service_hidden'] ) ) : '',
			'mwb_mbfw_is_service_has_quantity'           => array_key_exists( 'mwb_mbfw_is_service_has_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_is_service_has_quantity'] ) ) : '',
			'mwb_mbfw_service_minimum_quantity'          => array_key_exists( 'mwb_mbfw_service_minimum_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_service_minimum_quantity'] ) ) : '',
			'mwb_mbfw_service_maximum_quantity'          => array_key_exists( 'mwb_mbfw_service_maximum_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_service_maximum_quantity'] ) ) : '',
		);
		// phpcs:enable WordPress.Security.NonceVerification

		foreach ( $term_meta_data as $term_meta_key => $term_meta_value ) {
			update_term_meta( $term_id, $term_meta_key, $term_meta_value );
		}
	}

	/**
	 * Adding custom column to mwb_booking_service taxonomy table.
	 *
	 * @param array $columns array containing columns.
	 * @return void
	 */
	public function mbfw_adding_custom_column_booking_services_taxonomy_table( $columns ) {
		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );
		$columns['cost']         = __( 'Cost', 'mwb-bookings-for-woocommerce' );
		$columns['people']       = __( 'People', 'mwb-bookings-for-woocommerce' );
		$columns['duration']     = __( 'Duration', 'mwb-bookings-for-woocommerce' );
		$columns['optional']     = __( 'Optional', 'mwb-bookings-for-woocommerce' );
		$columns['is_hidden']    = __( 'Hidden', 'mwb-bookings-for-woocommerce' );
		$columns['has_quantity'] = __( 'Quantity', 'mwb-bookings-for-woocommerce' );
		return $columns;
	}

	/**
	 * Add custom column data to mwb_booking_service taxonomy.
	 *
	 * @param string $content content of the column.
	 * @param string $column_name column name of the table.
	 * @param int    $term_id current term id.
	 * @return void
	 */
	public function mbfw_adding_custom_column_data_booking_services_taxonomy_table( $content, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'cost':
				$content = get_term_meta( $term_id, 'mwb_mbfw_service_cost', true );
				break;
			case 'people':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_people', true );
				break;
			case 'duration':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_duration', true );
				break;
			case 'optional':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_service_optional', true );
				break;
			case 'is_hidden':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_service_hidden', true );
				break;
			case 'has_quantity':
				$content = get_term_meta( $term_id, 'mwb_mbfw_is_service_has_quantity', true );
				break;
			default:
				break;
		}
		return $content;
	}
}
