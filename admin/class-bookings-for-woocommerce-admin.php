<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bookings_For_Woocommerce
 * @subpackage Bookings_For_Woocommerce/admin
 */
class Bookings_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since 2.0.0
	 * @var   string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 2.0.0
	 * @var   string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
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
	 * @since 2.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function bfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();

		if (isset($screen->id) && 'wp-swings_page_bookings_for_woocommerce_menu' === $screen->id ) {

			wp_enqueue_style('wps-mbfw-select2-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/bookings-for-woocommerce-select2.css', array(), time(), 'all');

			wp_enqueue_style('wps-mbfw-meterial-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all');
			wp_enqueue_style('wps-mbfw-meterial-css2', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all');
			wp_enqueue_style('wps-mbfw-meterial-lite', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all');

			wp_enqueue_style('wps-mbfw-meterial-icons-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all');
			wp_enqueue_style('wps-mbfw-admin-min-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/wps-admin.min.css', array(), $this->version, 'all');
			wp_enqueue_style('wps-datatable-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all');
			wp_enqueue_style( 'wps-admin-full-calendar-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/full-calendar/main.css', array(), '5.8.0', 'all' );
		}
		wp_enqueue_style( 'wps-mbfw-global-custom-css', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/wps-admin-global-custom.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 2.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function bfw_admin_enqueue_scripts( $hook ) {

		$wps_migrator_params =array(
			'ajaxurl'              => admin_url( 'admin-ajax.php' ),
			'nonce'                => wp_create_nonce( 'wps_bfw_popup_nonce' ),
			'wps_bfw_callback'     => 'wps_bfw_ajax_callbacks',

			'wps_post_meta_count'  => $this->wps_bfw_get_count( 'pending', 'count', 'post' ),
			'wps_pending_post_meta' => $this->wps_bfw_get_count( 'pending', 'result', 'post' ),

			'wps_term_meta_count' => $this->wps_bfw_get_count( 'pending', 'count', 'terms' ),
			'wps_pending_term_meta' => $this->wps_bfw_get_count( 'pending', 'result', 'terms' ),

			'wps_pending_user_count'=>$this->wps_bfw_get_count( 'pending', 'count', 'users' ),
			'wps_pending_user_meta'=>$this->wps_bfw_get_count( 'pending', 'result', 'users' ),
		);
		$wps_migrator_params = apply_filters('wps_bfw_migrator_params_array',$wps_migrator_params);
		
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'wp-swings_page_bookings_for_woocommerce_menu' === $screen->id ) {
			wp_enqueue_script('wps-mbfw-select2', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/bookings-for-woocommerce-select2.js', array( 'jquery' ), time(), false);
			
			wp_enqueue_script('wps-mbfw-metarial-js', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false);
			wp_enqueue_script('wps-mbfw-metarial-js2', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false);
			wp_enqueue_script('wps-mbfw-metarial-lite', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false);
			wp_enqueue_script('wps-mbfw-datatable', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/js/jquery.dataTables.min.js', array(), time(), false);
			wp_enqueue_script('wps-mbfw-datatable-btn', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/dataTables.buttons.min.js', array(), time(), false);
			wp_enqueue_script('wps-mbfw-datatable-btn-2', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/buttons.html5.min.js', array(), time(), false);
			wp_register_script($this->plugin_name . 'admin-js', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/bookings-for-woocommerce-admin.js', array( 'jquery', 'wps-mbfw-select2', 'wps-mbfw-metarial-js', 'wps-mbfw-metarial-js2', 'wps-mbfw-metarial-lite' ), $this->version, false);
			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'bfw_admin_param',
				array(
					'todays_date'               => current_time( 'Y-m-d' ),
					'ajaxurl'                   => admin_url('admin-ajax.php'),
					'nonce'                     => wp_create_nonce( 'wps_bfw_admin_nonce' ),
					'reloadurl'                 => admin_url('admin.php?page=bookings_for_woocommerce_menu'),
					'bfw_gen_tab_enable'       => get_option('bfw_radio_switch_demo'),
					'bfw_admin_param_location' => ( admin_url( 'admin.php' ) . '?page=bookings_for_woocommerce_menu&bfw_tab=bookings-for-woocommerce-general' ),
					'full_cal_button_text'      => array(
						'today' => __( 'Today', 'bookings-for-woocommerce' ),
						'month' => __( 'Month', 'bookings-for-woocommerce' ),
						'week'  => __( 'Week', 'bookings-for-woocommerce' ),
						'day'   => __( 'Day', 'bookings-for-woocommerce' ),
						'list'  => __( 'List', 'bookings-for-woocommerce' ),
					),
				)
			);
			wp_enqueue_script($this->plugin_name . 'admin-js');
			wp_enqueue_script('wps-mbfw-admin-min-js', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/wps-admin.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'wps-admin-full-calendar-js', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/full-calendar/main.js', array( 'jquery' ), '5.8.0', true );


			
			wp_enqueue_script( $this->plugin_name . '-popup-migrate', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/wps-bfw-migrator-popoup.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-swal-migrate', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/swal.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name . '-popup-migrate',
				'localised',
				$wps_migrator_params
			);
		}
		wp_enqueue_script( 'wps-mbfw-admin-custom-global-js', BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/wps-admin-global-custom.min.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Adding settings menu for Bookings For WooCommerce.
	 *
	 * @since 2.0.0
	 */
	public function bfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['wps-plugins'] ) ) {
			add_menu_page( 'WP Swings', 'WP Swings', 'manage_options', 'wps-plugins', array( $this, 'wps_plugins_listing_page' ), BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/wpswings_logo.png', 15);
			$bfw_menus = 
			//desc - Add Menu Page.
			apply_filters( 'wps_add_plugins_menus_array', array() );
			if ( is_array( $bfw_menus ) && ! empty( $bfw_menus ) ) {
				foreach ( $bfw_menus as $bfw_key => $bfw_value ) {
					add_submenu_page( 'wps-plugins', $bfw_value['name'], $bfw_value['name'], 'manage_options', $bfw_value['menu_link'], array( $bfw_value['instance'], $bfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since 2.0.0
	 */
	public function wps_bfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'wps-plugins', $submenu ) ) {
			if ( isset( $submenu['wps-plugins'][0] ) ) {
				unset($submenu['wps-plugins'][0]);
			}
		}
	}


	/**
	 * Bookings For WooCommerce bfw_admin_submenu_page.
	 *
	 * @since 2.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function bfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => __( 'Bookings For WooCommerce', 'bookings-for-woocommerce' ),
			'slug'      => 'bookings_for_woocommerce_menu',
			'menu_link' => 'bookings_for_woocommerce_menu',
			'instance'  => $this,
			'function'  => 'bfw_options_menu_html',
		);
		return $menus;
	}

	/**
	 * Bookings For WooCommerce wps_plugins_listing_page.
	 *
	 * @since 2.0.0
	 */
	public function wps_plugins_listing_page() {
		$active_marketplaces = 
		//desc - Add Menu Page.
		apply_filters( 'wps_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			include BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * Bookings For WooCommerce admin menu page.
	 *
	 * @since 2.0.0
	 */
	public function bfw_options_menu_html() {
		include_once BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/bookings-for-woocommerce-admin-dashboard.php';
	}

	/**
	 * Wps developer admin hooks listing.
	 *
	 * @return array
	 */
	public function wps_developer_admin_hooks_listing() {
		$admin_hooks = array();
		$val         = self::wps_developer_hooks_function( BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' );
		if ( ! empty( $val['hooks'] ) ) {
			$admin_hooks[] = $val['hooks'];
			unset($val['hooks']);
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js' !== $v && 'images' !== $v ) {
				$helo = self::wps_developer_hooks_function(BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' . $v . '/');
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
	public function wps_developer_public_hooks_listing() {
		$public_hooks = array();
		$val          = self::wps_developer_hooks_function( BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/' );
		if ( ! empty( $val['hooks'] ) ) {
			$public_hooks[] = $val['hooks'];
			unset($val['hooks']);
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js' !== $v && 'images' !== $v ) {
				$helo = self::wps_developer_hooks_function( BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/' . $v . '/' );
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
	public static function wps_developer_hooks_function( $path ) {
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
	 * Bookings For WooCommerce admin menu page.
	 *
	 * @since 2.0.0
	 * @param array $bfw_settings_general Settings fields.
	 */
	public function bfw_admin_general_settings_page( $bfw_settings_general ) {
		$bfw_settings_general = array(
			array(
				'title'       => __('Enable Plugin', 'bookings-for-woocommerce'),
				'type'        => 'radio-switch',
				'description' => __('Enable plugin.', 'bookings-for-woocommerce'),
				'id'          => 'wps_bfw_is_plugin_enable',
				'value'       => get_option( 'wps_bfw_is_plugin_enable' ),
				'class'       => 'wps_bfw_is_plugin_enable',
				'name'        => 'wps_bfw_is_plugin_enable',
			),
			array(
				'title'       => __( 'Enable Bookings', 'bookings-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable to start the bookings for customers else customers can not do the bookings.', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_booking_enable',
				'value'       => get_option( 'wps_bfw_is_booking_enable' ),
				'class'       => 'wps_bfw_is_booking_enable',
				'name'        => 'wps_bfw_is_booking_enable',
			)
		);
		$bfw_settings_general =
		//desc - General settings tab add html fields.
		apply_filters( 'bfw_general_settings_array_filter', $bfw_settings_general );
		$bfw_settings_general[] = array(
			'type'        => 'button',
			'id'          => 'wps_bfw_general_settings_save',
			'button_text' => __('Save Settings', 'bookings-for-woocommerce'),
			'class'       => 'wps_bfw_general_settings_save',
			'name'        => 'wps_bfw_general_settings_save',
		);
		return $bfw_settings_general;
	}

	/**
	 * Booking form settings.
	 *
	 * @param array $bfw_booking_form_array array containing booking form settings.
	 * @return array
	 */
	public function bfw_booking_form_settings_page( $bfw_booking_form_array ) {
		$bfw_booking_form_array = array(
			array(
				'title'       => __('Show Included Services', 'bookings-for-woocommerce'),
				'type'        => 'radio-switch',
				'description' => sprintf(
					/* translators:%s booking services link */
					__('Enable this to show %s on the booking form.', 'bookings-for-woocommerce'),
					'<a href="' . admin_url( 'edit-tags.php?taxonomy=wps_booking_service&post_type=product' ) . '" class="wps-bfwp-helper-link__admin" target="_blank">' . __( 'booking services', 'bookings-for-woocommerce' ) . '</a>'
				),
				'id'          => 'wps_bfw_is_show_included_service',
				'value'       => get_option( 'wps_bfw_is_show_included_service' ),
				'class'       => 'wps_bfw_is_show_included_service',
				'name'        => 'wps_bfw_is_show_included_service',
			),
			array(
				'title'       => __('Show Totals', 'bookings-for-woocommerce'),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to show booking total on varying services and quantity while booking.', 'bookings-for-woocommerce'),
				'id'          => 'wps_bfw_is_show_totals',
				'value'       => get_option( 'wps_bfw_is_show_totals' ),
				'class'       => 'wps_bfw_is_show_totals',
				'name'        => 'wps_bfw_is_show_totals'
			)
		);
		$bfw_booking_form_array =
		//desc - setting fields at booking form setting tab.
		apply_filters( 'bfw_booking_form_setting_array_filter', $bfw_booking_form_array );
		$bfw_booking_form_array[] = array(
			'type'        => 'button',
			'id'          => 'wps_bfw_booking_form_settings_save',
			'button_text' => __('Save Settings', 'bookings-for-woocommerce'),
			'class'       => 'wps_bfw_booking_form_settings_save',
			'name'        => 'wps_bfw_booking_form_settings_save',
		);
		return $bfw_booking_form_array;
	}

	/**
	 * Aavilability setting page.
	 *
	 * @param array $bfw_availability_settings array containing available settings.
	 * @return array
	 */
	public function bfw_add_availability_settings_page( $bfw_availability_settings ) {
		$bfw_availability_settings = array(
			array(
				'title'       => __( 'Daily Start Time', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_daily_start_time',
				'name'        => 'wps_bfw_daily_start_time',
				'class'       => 'wps_bfw_daily_start_time bfw_time_picker',
				'value'       => get_option( 'wps_bfw_daily_start_time' ),
				'type'        => 'time',
				'description' => __( 'Please choose daily start time, users will be able to book from this time.', 'bookings-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Daily End Time', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_daily_end_time',
				'name'        => 'wps_bfw_daily_end_time',
				'class'       => 'wps_bfw_daily_end_time bfw_time_picker',
				'value'       => get_option( 'wps_bfw_daily_end_time' ),
				'type'        => 'time',
				'description' => __( 'Please choose daily end time, bookings will be closed for users after this time.', 'bookings-for-woocommerce' ),
			)
		);
		$bfw_availability_settings =
		//desc - avilability setting fields array.
		apply_filters( 'wps_bfw_availability_setting_fields_array', $bfw_availability_settings );
		$bfw_availability_settings[] = array(
			'type'        => 'button',
			'id'          => 'wps_bfw_availability_settings_save',
			'button_text' => __('Save Settings', 'bookings-for-woocommerce'),
			'class'       => 'wps_bfw_availability_settings_save',
			'name'        => 'wps_bfw_availability_settings_save',
		);
		return $bfw_availability_settings;
	}


	/**
	 * Bookings For WooCommerce save tab settings.
	 *
	 * @since 2.0.0
	 */
	public function bfw_admin_save_tab_settings() {
		global $bfw_wps_bfw_obj;
		$wps_settings_save_progress = false;
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! isset( $_POST['wps_tabs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_tabs_nonce'] ) ), 'admin_save_data' ) ) {
			return;
		}
		if ( isset( $_POST['wps_bfw_general_settings_save'] ) ) {
			$wps_bfw_gen_flag     = false;
			$bfw_genaral_settings = 
			//desc - general setting tab fields.
			apply_filters( 'bfw_general_settings_array', array() );
			$wps_settings_save_progress = true;
		}
		if ( isset( $_POST['wps_bfw_booking_form_settings_save'] ) ) {
			$wps_bfw_gen_flag     = false;
			$bfw_genaral_settings = 
			//desc - booking setting tab fields.
			apply_filters( 'bfw_booking_form_settings_array', array() );
			$wps_settings_save_progress = true;
		}
		if ( isset( $_POST['wps_bfw_availability_settings_save'] ) ) {
			$wps_bfw_gen_flag     = false;
			$bfw_genaral_settings = 
			//desc - availability setting tab fields.
			apply_filters( 'bfw_availability_settings_array', array() );
			$wps_settings_save_progress = true;
		}
		if ( $wps_settings_save_progress ) {
			$bfw_button_index = array_search( 'submit', array_column( $bfw_genaral_settings, 'type' ), true );
			if ( empty( $bfw_button_index ) ) {
				$bfw_button_index = array_search( 'button', array_column( $bfw_genaral_settings, 'type' ), true );
			}
			if ( isset( $bfw_button_index ) && ( '' !== $bfw_button_index || ! $bfw_button_index ) ) {
				unset( $bfw_genaral_settings[ $bfw_button_index ] );
				if ( is_array( $bfw_genaral_settings ) && ! empty( $bfw_genaral_settings ) ) {
					foreach ( $bfw_genaral_settings as $bfw_genaral_setting ) {
						if ( isset( $bfw_genaral_setting['id'] ) && '' !== $bfw_genaral_setting['id'] ) {
							if ( 'availability_select' === $bfw_genaral_setting['type'] ) {
								$sub_tabs = $bfw_genaral_setting['sub_tabs'];
								foreach ( $sub_tabs as $bfw_sub_components ) {
									foreach ( $bfw_sub_components as $sub_components ) {
										if ( isset( $_POST[ $sub_components['id'] ] ) ) {
											update_option( $sub_components['id'], is_array( $_POST[ $sub_components['id'] ] ) ? map_deep( wp_unslash( $_POST[ $sub_components['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $sub_components['id'] ] ) ) );
										} else {
											update_option( $sub_components['id'], '' );
										}
									}
								}
							} else {
								if ( isset( $_POST[ $bfw_genaral_setting['id'] ] ) ) {
									update_option( $bfw_genaral_setting['id'], is_array( $_POST[ $bfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $bfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $bfw_genaral_setting['id'] ] ) ) );
								} else {
									update_option($bfw_genaral_setting['id'], '');
								}
							}
						} else {
							$wps_bfw_gen_flag = true;
						}
					}
				}
				if ( $wps_bfw_gen_flag ) {
					$wps_bfw_error_text = esc_html__( 'Id of some field is missing', 'bookings-for-woocommerce' );
					$bfw_wps_bfw_obj->wps_bfw_plug_admin_notice( $wps_bfw_error_text, 'error' );
				} else {
					$wps_bfw_error_text = esc_html__( 'Settings saved Successfully', 'bookings-for-woocommerce' );
					$bfw_wps_bfw_obj->wps_bfw_plug_admin_notice( $wps_bfw_error_text, 'success' );
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
	public function bfw_add_product_type_in_dropdown( $types ) {
		$types['wps_booking'] = __( 'Booking product', 'bookings-for-woocommerce' );
		return $types;	
	}

	/**
	 * Adding product data tabs at product editing page.
	 *
	 * @param array $tabs array containing tabs.
	 * @return array
	 */
	public function bfw_add_product_data_tabs( $tabs ) {
		$tabs['shipping']['class'][]       = 'hide_if_wps_booking';
		$tabs['attribute']['class'][]      = 'hide_if_wps_booking';
		$tabs['linked_product']['class'][] = 'hide_if_wps_booking';
		$tabs['advanced']['class'][]       = 'hide_if_wps_booking';

		$tabs = array_merge(
			$tabs,
			array(
				'general_settings' => array(
					'label'    => __( 'General Settings', 'bookings-for-woocommerce' ),
					'target'   => 'wps_booking_general_data',
					'class'    => array( 'show_if_wps_booking' ),
					'priority' => 10,
				),
				'cost'             => array(
					'label'    => __( 'Costs', 'bookings-for-woocommerce' ),
					'target'   => 'wps_booking_cost_data',
					'class'    => array( 'show_if_wps_booking' ),
					'priority' => 20,
				),
				'people'           => array(
					'label'    => __( 'People', 'bookings-for-woocommerce' ),
					'target'   => 'wps_booking_people_data',
					'class'    => array( 'show_if_wps_booking' ),
					'priority' => 30,
				),
				'services'         => array(
					'label'    => __( 'Services', 'bookings-for-woocommerce' ),
					'target'   => 'wps_booking_services_data',
					'class'    => array( 'show_if_wps_booking' ),
					'priority' => 40,
				),
				'availability'      => array(
					'label'    => __( 'Availability', 'bookings-for-woocommerce' ),
					'target'   => 'wps_booking_availability_data',
					'class'    => array( 'show_if_wps_booking' ),
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
	public function bfw_product_data_tabs_html() {
		$booking_criteria = get_post_meta( get_the_ID(), 'wps_bfw_booking_criteria', true );
		wp_nonce_field( 'wps_booking_product_meta', '_wps_nonce' );
		?>
		<div id="wps_booking_general_data" class="panel woocommerce_options_panel show_if_wps_booking">
			<p class="form-field wps_bfw_booking_criteria_field">
				<label for="wps_bfw_booking_criteria"><?php esc_html_e( 'Quantity', 'bookings-for-woocommerce' ); ?></label>
				<select name="wps_bfw_booking_criteria" id="wps_bfw_booking_criteria">
					<option value="customer_selected_unit" <?php selected( 'customer_selected_unit', $booking_criteria ); ?>><?php esc_html_e( 'Customers can choose', 'bookings-for-woocommerce' ); ?></option>
					<option value="fixed_unit" <?php selected( 'fixed_unit', $booking_criteria ); ?>><?php esc_html_e( 'Fixed unit', 'bookings-for-woocommerce' ); ?></option>
				</select>
				<input type="number" step="1" min="1" max="" style="width: 4em;" <?php echo esc_attr( ( ( 'customer_selected_unit' === $booking_criteria ) || empty( $booking_criteria ) ) ? 'disabled=disabled' : '' ); ?> id="wps_bfw_booking_count" name="wps_bfw_booking_count" value=<?php echo esc_attr( get_post_meta( get_the_ID(), 'wps_bfw_booking_count', true ) ); ?>>
				<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Please choose the booking criteria. if fixed please enter the fixed number, else if customers can choose please choose the maximum number a user can book.', 'bookings-for-woocommerce' ); ?>"></span>
			</p>
			<?php
			woocommerce_wp_text_input(
				array(
					'label'             => __( 'Max. Booking Per User', 'bookings-for-woocommerce' ),
					'id'                => 'wps_bfw_maximum_booking_per_unit',
					'value'             => get_post_meta( get_the_ID(), 'wps_bfw_maximum_booking_per_unit', true ),
					'description'       => __( 'Maximum quantity of this product/service a user can book.', 'bookings-for-woocommerce' ),
					'type'              => 'number',
					'desc_tip'          => true,
					'style'             => 'width:10em;',
					'custom_attributes' => ( 'fixed_unit' === $booking_criteria ) ? array( 'min' => 0, 'disabled' => 'disabled' ) : array( 'min' => 0 ) ,
				)
			);
			woocommerce_wp_select(
				array(
					'label'       => __( 'Booking Unit', 'bookings-for-woocommerce' ),
					'id'          => 'wps_bfw_booking_unit',
					'name'        => 'wps_bfw_booking_unit',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_booking_unit', true ),
					'desc_tip'    => true,
					'description' => __( 'Please select booking unit to consider while booking.', 'bookings-for-woocommerce' ),
					'options'     => array(
						'days'    => __( 'Day(s)', 'bookings-for-woocommerce' ),
						'hours'   => __( 'Hour(s)', 'bookings-for-woocommerce' ),
						'minutes' => __( 'Minute(s)', 'bookings-for-woocommerce' ),
					),
					'style'       => 'width:10em',
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'wps_bfw_enable_calendar',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_enable_calendar', true ),
					'label'       => __( 'Enable Dates Selection', 'bookings-for-woocommerce' ),
					'description' => __( 'Enable calendar at frontend for choosing dates while booking ( a calendar will be shown while booking ).', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'wps_bfw_enable_time_picker',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_enable_time_picker', true ),
					'label'       => __( 'Enable Time Selection', 'bookings-for-woocommerce' ),
					'description' => __( 'Enable time picker at frontend for choosing time while booking ( time picker will be enabled while booking ).', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'wps_bfw_admin_confirmation',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_admin_confirmation', true ),
					'label'       => __( 'Booking Confirmation', 'bookings-for-woocommerce' ),
					'description' => __( 'Booking confirmation required by admin.', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'wps_bfw_cancellation_allowed',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_cancellation_allowed', true ),
					'label'       => __( 'Cancellation Allowed', 'bookings-for-woocommerce' ),
					'description' => __( 'Cancellation will be allowed for users, after enabling this please select the order statuses from below multiselect field.', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			$order_statuses = wc_get_order_statuses();
			unset( $order_statuses['wc-completed'] );
			unset( $order_statuses['wc-cancelled'] );
			unset( $order_statuses['wc-refunded'] );
			unset( $order_statuses['wc-failed'] );
			$saved_statuses = get_post_meta( get_the_ID(), 'wps_bfwp_order_statuses_to_cancel', true );
			$this->bfw_multi_select_field_html(
				array(
					'label'       => __( 'Order statuses', 'bookings-for-woocommerce' ),
					'class'       => 'select short',
					'id'          => 'wps_bfwp_order_statuses_to_cancel',
					'name'        => 'wps_bfwp_order_statuses_to_cancel[]',
					'description' => __( 'Please select the desired order statuses at which the orders can be cancelled by users.', 'bookings-for-woocommerce' ),
					'value'       => is_array( $saved_statuses ) ? $saved_statuses : array(),
					'desc_tip'    => true,
					'options'     => $order_statuses,
					'custom_attr' => ( 'yes' !== get_post_meta( get_the_ID(), 'wps_bfw_cancellation_allowed', true ) ) ? array( 'disabled' => 'disabled' ) : array(),
				)
			);
			//desc - add meta fields in general settings meta section for products.
			do_action( 'wps_bfw_add_extra_field_product_gen_setting', get_the_ID() );
			?>
		</div>
		<div id="wps_booking_cost_data" class="panel woocommerce_options_panel show_if_wps_booking">
			<?php
			woocommerce_wp_text_input(
				array(
					'id'                => 'wps_bfw_booking_unit_cost',
					'value'             => get_post_meta( get_the_ID(), 'wps_bfw_booking_unit_cost', true ),
					'label'             => __( 'Unit Cost', 'bookings-for-woocommerce' ),
					'description'       => __( 'Enter unit cost.', 'bookings-for-woocommerce' ),
					'type'              => 'number',
					'desc_tip'          => true,
					'style'             => 'width:10em;',
					'custom_attributes' => array( 'min' => 0 ),
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'                => 'wps_bfw_is_booking_unit_cost_per_people',
					'value'             => get_post_meta( get_the_ID(), 'wps_bfw_is_booking_unit_cost_per_people', true ),
					'label'             => __( 'Unit Cost Per People', 'bookings-for-woocommerce' ),
					'description'       => __( 'Unit cost will be multiplied by number of people while booking.', 'bookings-for-woocommerce' ),
					'desc_tip'          => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'wps_bfw_booking_base_cost',
					'value'             => get_post_meta( get_the_ID(), 'wps_bfw_booking_base_cost', true ),
					'label'             => __( 'Base Cost', 'bookings-for-woocommerce' ),
					'description'       => __( 'Enter base cost.', 'bookings-for-woocommerce' ),
					'type'              => 'number',
					'desc_tip'          => true,
					'style'             => 'width:10em;',
					'custom_attributes' => array( 'min' => 0 ),
				)
			);
			woocommerce_wp_checkbox(
				array(
					'id'          => 'wps_bfw_is_booking_base_cost_per_people',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_is_booking_base_cost_per_people', true ),
					'label'       => __( 'Base Cost Per People', 'bookings-for-woocommerce' ),
					'description' => __( 'Base cost will be multiplied by number of people while booking.', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
			<p class="wps-mbfw-additional-notice">
				<?php
				printf(
					/* translators: %s taxonomy edit link */
					esc_html__( 'To Add Additional cost Please add Booking costs in the Booking costs taxonomy %s , and select Booking costs tags.', 'bookings-for-woocommerce' ),
					wp_kses_post( '<a href="' . admin_url( 'edit-tags.php?taxonomy=wps_booking_cost&post_type=product' ) . '" target="_blank">here</a>' )
				);
				?>
			</p>
			<?php
			//desc - add meta fields in booking cost meta section for products.
			do_action( 'wps_bfw_booking_costs_meta_section_add_fields', get_the_ID() );
			?>
		</div>
		<div id="wps_booking_people_data" class="panel woocommerce_options_panel show_if_wps_booking">
			<?php
			woocommerce_wp_checkbox(
				array(
					'label'       => __( 'Enable People Option', 'bookings-for-woocommerce' ),
					'id'          => 'wps_bfw_is_people_option',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_is_people_option', true ),
					'description' => __( 'People Option will be Visible While Booking.', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'wps_bfw_minimum_people_per_booking',
					'type'              => 'number',
					'value'             => get_post_meta( get_the_ID(), 'wps_bfw_minimum_people_per_booking', true ),
					'label'             => __( 'Minimum No. of People', 'bookings-for-woocommerce' ),
					'description'       => __( 'Minimum Number of People Per Booking', 'bookings-for-woocommerce' ),
					'desc_tip'          => true,
					'style'             => 'width:10em;',
					'custom_attributes' => array( 'min' => 0 ),
				)
			);
			woocommerce_wp_text_input(
				array(
					'id'                => 'wps_bfw_maximum_people_per_booking',
					'type'              => 'number',
					'value'             => get_post_meta( get_the_ID(), 'wps_bfw_maximum_people_per_booking', true ),
					'label'             => __( 'Maximum No. of People', 'bookings-for-woocommerce' ),
					'description'       => __( 'Maximum Number of People Per Booking', 'bookings-for-woocommerce' ),
					'desc_tip'          => true,
					'style'             => 'width:10em;',
					'custom_attributes' => array( 'min' => 0 ),
				)
			);
			//desc - add fields in people meta sections in product.
			do_action( 'wps_bfw_people_meta_section_add_fields', get_the_ID() );
			?>
		</div>
		<div id="wps_booking_services_data" class="panel woocommerce_options_panel show_if_wps_booking">
			<?php
			woocommerce_wp_checkbox(
				array(
					'label'       => __( 'Add Extra Services', 'bookings-for-woocommerce' ),
					'id'          => 'wps_bfw_is_add_extra_services',
					'value'       => get_post_meta( get_the_ID(), 'wps_bfw_is_add_extra_services', true ),
					'description' => __( 'Add Extra Services, will be chosen by Customer while Booking.', 'bookings-for-woocommerce' ),
					'desc_tip'    => true,
				)
			);
			?>
			<p class="wps-mbfw-additional-notice">
				<?php
				printf(
					/* translators: %s taxonomy edit link */
					esc_html__( 'To Add Additional Services Please add Booking Services in the Booking Services taxonomy %s and select Services taxonomy from tag.', 'bookings-for-woocommerce' ),
					wp_kses_post( '<a href="' . admin_url( 'edit-tags.php?taxonomy=wps_booking_service&post_type=product' ) . '" target="_blank">here</a>' )
				);
				?>
			</p>
		</div>
		<div id="wps_booking_availability_data" class="panel woocommerce_options_panel show_if_wps_booking">
			<?php
			//desc - add fields in booking availability meta section.
			do_action( 'wps_bfw_booking_availability_meta_tab_fields', get_the_ID() );
			?>
			<p class="wps-mbfw-additional-notice">
				<?php
				printf(
					/* translators:%s admin setting page link. */
					esc_html__( 'To Choose daily start time and end time please %s.', 'bookings-for-woocommerce' ),
					'<a href="' . esc_url( admin_url( 'admin.php?page=bookings_for_woocommerce_menu&bfw_tab=bookings-for-woocommerce-booking-availability-settings' ) ) . '" target="_blank">' . esc_html__( 'visit here', 'bookings-for-woocommerce' ) . '</a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Multiselect field html.
	 *
	 * @param array $field array containing fields for html input fields.
	 * @return void
	 */
	public function bfw_multi_select_field_html( $field ) {
		global $thepostid, $post, $woocommerce;
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		?>
		<p class="form-field <?php echo esc_attr( $field['id'] . '_field ' . $field['wrapper_class'] ); ?>">
			<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['label'] ); ?></label>
			<select id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" multiple="multiple"
			<?php
			if ( isset( $field['custom_attr'] ) && is_array( $field['custom_attr'] ) ) {
				foreach ( $field['custom_attr'] as $attr_key => $attr_val ) {
					echo esc_attr( $attr_key . '=' . $attr_val );
				}
			}
			?>
			>
				<?php
				foreach ( $field['options'] as $key => $value ) {
					?>
					<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ); //phpcs:ignore WordPress ?>><?php echo esc_html( $value ); ?></option>
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
	 * Save custom product meta boxes data.
	 *
	 * @param int    $id post id.
	 * @param object $post post object. 
	 * @return void
	 */
	public function bfw_save_custom_product_meta_boxes_data( $id, $post ) {
		$product = wc_get_product( $id );
		// var_dump($product);
		
		// var_dump($product->get_type());die;
		if ( $product && 'wps_booking' === $product->get_type() ) {
			if ( ! isset( $_POST['_wps_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wps_nonce'] ) ), 'wps_booking_product_meta' ) ) {
				return;
			}
			$product_meta_data = array(
				'wps_bfw_booking_criteria'                => array_key_exists( 'wps_bfw_booking_criteria', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_criteria'] ) ) : '',
				'wps_bfw_booking_count'                   => array_key_exists( 'wps_bfw_booking_count', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_count'] ) ) : '',
				'wps_bfw_booking_unit'                    => array_key_exists( 'wps_bfw_booking_unit', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_unit'] ) ) : '',
				'wps_bfw_enable_calendar'                 => array_key_exists( 'wps_bfw_enable_calendar', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_enable_calendar'] ) ) : '',
				'wps_bfw_enable_time_picker'              => array_key_exists( 'wps_bfw_enable_time_picker', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_enable_time_picker'] ) ) : '',
				'wps_bfw_max_bookings'                    => array_key_exists( 'wps_bfw_max_bookings', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_max_bookings'] ) ) : '',
				'wps_bfw_admin_confirmation'              => array_key_exists( 'wps_bfw_admin_confirmation', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_admin_confirmation'] ) ) : '',
				'wps_bfw_cancellation_allowed'            => array_key_exists( 'wps_bfw_cancellation_allowed', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_cancellation_allowed'] ) ) : '',
				'wps_bfw_booking_unit_cost'               => array_key_exists( 'wps_bfw_booking_unit_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_unit_cost'] ) ) : '',
				'_price'                                   => array_key_exists( 'wps_bfw_booking_unit_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_unit_cost'] ) ) : '',
				'wps_bfw_is_booking_unit_cost_per_people' => array_key_exists( 'wps_bfw_is_booking_unit_cost_per_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_booking_unit_cost_per_people'] ) ) : '',
				'wps_bfw_booking_base_cost'               => array_key_exists( 'wps_bfw_booking_base_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_base_cost'] ) ) : '',
				'wps_bfw_is_booking_base_cost_per_people' => array_key_exists( 'wps_bfw_is_booking_base_cost_per_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_booking_base_cost_per_people'] ) ) : '',
				'wps_bfw_is_people_option'                => array_key_exists( 'wps_bfw_is_people_option', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_people_option'] ) ) : '',
				'wps_bfw_minimum_people_per_booking'      => array_key_exists( 'wps_bfw_minimum_people_per_booking', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_minimum_people_per_booking'] ) ) : '',
				'wps_bfw_maximum_people_per_booking'      => array_key_exists( 'wps_bfw_maximum_people_per_booking', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_maximum_people_per_booking'] ) ) : '',
				'wps_bfw_is_add_extra_services'           => array_key_exists( 'wps_bfw_is_add_extra_services', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_add_extra_services'] ) ) : '',
				'wps_bfw_maximum_booking_per_unit'        => array_key_exists( 'wps_bfw_maximum_booking_per_unit', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_maximum_booking_per_unit'] ) ) : '',
				'wps_bfwp_order_statuses_to_cancel'        => array_key_exists( 'wps_bfwp_order_statuses_to_cancel', $_POST ) ? ( is_array( $_POST['wps_bfwp_order_statuses_to_cancel'] ) ? map_deep( wp_unslash( $_POST['wps_bfwp_order_statuses_to_cancel'] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST['wps_bfwp_order_statuses_to_cancel'] ) ) ) : array(),
			);
			$product_meta_data =
			//desc - save booking product meta data.
			apply_filters( 'wps_bfw_save_product_meta_data', $product_meta_data, $id );
			foreach ( $product_meta_data as $meta_key => $meta_value ) {
				update_post_meta( $id, $meta_key, $meta_value );
			}
			wp_set_object_terms( $id, array( 'booking' ), 'product_cat', true );
			wp_remove_object_terms( $id, 'uncategorized', 'product_cat' );
			//desc - after saving product custom meta data.
			do_action( 'wps_bfw_share_product_on_fb', $id );
		}
	}

	/**
	 * Adding extra field at custom wps_booking_cost taxonomy page.
	 *
	 * @return void
	 */
	public function bfw_adding_custom_fields_at_booking_cost_taxonomy_page() {
		wp_nonce_field( 'wps_edit_taxonomy_page', '_wps_nonce' );
		$fields = array(
			array(
				'id'          => 'wps_bfw_booking_cost',
				'type'        => 'number',
				'value'       => '',
				'label'       => __( 'Booking Cost', 'bookings-for-woocommerce' ),
				'description' => __( 'Please Add Booking cost here.', 'bookings-for-woocommerce' ),
				'style'       => 'width:10em;',
			),
			array(
				'label'       => __( 'Multiply by No. of People', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_booking_cost_multiply_people',
				'type'        => 'checkbox',
				'value'       => 'yes',
				'description' => __( 'Either to multiply by number of people.', 'bookings-for-woocommerce' ),
			),
			array(
				'label'       => __( 'Multiply by Duration', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_booking_cost_multiply_duration',
				'type'        => 'checkbox',
				'value'       => 'yes',
				'description' => __( 'Either to multiply by Duration of Booking.', 'bookings-for-woocommerce' ),
			),
		);
		$this->bfw_taxonomy_adding_fields_html( $fields );
	}

	/**
	 * Html fields for taxonomy edit page.
	 *
	 * @param array $fields array containing attributes of html fields.
	 * @return void
	 */
	public function bfw_taxonomy_adding_fields_html( $fields ) {
		foreach ( $fields as $field ) {
			?>
			<p class="form-field">
				<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_attr( isset( $field['label'] ) ? $field['label'] : '' ); ?></label>
				<input
				type="<?php echo esc_attr( $field['type'] ); ?>"
				class="<?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : 'short' ); ?>"
				style="<?php echo esc_attr( isset( $field['style'] ) ? $field['style'] : '' ); ?>"
				name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $field['id'] ); ?>"
				id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>"
				placeholder=""
				<?php
				if ( isset( $field['custom_attribute'] ) && is_array( $field['custom_attribute'] ) ) {
					$custom_attributes = $field['custom_attribute'];
					foreach ( $custom_attributes as $attr_name => $attr_val ) {
						echo esc_html( $attr_name . '=' . $attr_val );
					}
				}
				?>
				>
				<p class="description"><?php echo wp_kses_post( isset( $field['description'] ) ? $field['description'] : '' ); ?></p>
			</p>
			<?php
		}
	}

	/**
	 * Html fields for custom field at taxonomy edit page.
	 *
	 * @param array $term_fields_arr array containing various attributes of HTML input tag.
	 * @return void
	 */
	public function bfw_taxonomy_custom_fields_html( $term_fields_arr ) {
		foreach ( $term_fields_arr as $tag_arr ) {
			if ( isset( $tag_arr['term_id'] ) && isset( $tag_arr['id'] ) ) {
				?>
				<tr class="form-field">
					<th>
						<label for="<?php echo esc_attr( $tag_arr['id'] ); ?>"><?php echo wp_kses_post( isset( $tag_arr['label'] ) ?  $tag_arr['label'] : '' ); ?></label>
					</th>
					<td>
						<input
						name="<?php echo esc_attr( isset( $tag_arr['name'] ) ? $tag_arr['name'] : '' ); ?>"
						id="<?php echo esc_attr( $tag_arr['id'] ); ?>"
						type="<?php echo esc_attr( $tag_arr['type'] ); ?>"
						value="<?php echo esc_attr( ( 'checkbox' === $tag_arr['type'] ) ? 'yes' : get_term_meta( $tag_arr['term_id'], $tag_arr['id'], true ) ); ?>"
						<?php ( 'checkbox' === $tag_arr['type'] ) ? checked( get_term_meta( $tag_arr['term_id'], $tag_arr['id'], true ), 'yes' ) : ''; ?>
						<?php
						if ( isset( $tag_arr['custom_attribute'] ) && is_array( $tag_arr['custom_attribute'] ) ) {
							$custom_attributes = $tag_arr['custom_attribute'];
							foreach ( $custom_attributes as $attr_name => $attr_val ) {
								echo esc_html( $attr_name . '=' . $attr_val );
							}
						}
						?>
						style="<?php echo esc_attr( isset( $tag_arr['style'] ) ? $tag_arr['style'] : '' ); ?>"
						/>
						<p class="description"><?php echo wp_kses_post( isset( $tag_arr['description'] ) ? $tag_arr['description'] : '' ); ?></p>
					</td>
				</tr>
				<?php
			}
		}
	}

	/**
	 * Adding custom fields at edit page of wps_booking_cost taxonomy.
	 *
	 * @param object $term object of current term.
	 * @param string $taxonomy current taxonomy.
	 * @return void
	 */
	public function bfw_adding_custom_fields_at_booking_cost_taxonomy_edit_page( $term, $taxonomy ) {
		wp_nonce_field( 'wps_edit_taxonomy_page', '_wps_nonce' );
		$term_fields_arr = array(
			array(
				'id'          => 'wps_bfw_booking_cost',
				'name'        => 'wps_bfw_booking_cost',
				'label'       => __( 'Booking Cost', 'bookings-for-woocommerce' ),
				'type'        => 'text',
				'term_id'     => $term->term_id,
				'description' => __( 'Please Add booking cost here.', 'bookings-for-woocommerce' ),
				'style'       => 'width:10em;',
			),
			array(
				'id'          => 'wps_bfw_is_booking_cost_multiply_people',
				'name'        => 'wps_bfw_is_booking_cost_multiply_people',
				'label'       => __( 'Multiply by No. of People', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by number of people.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'wps_bfw_is_booking_cost_multiply_duration',
				'name'        => 'wps_bfw_is_booking_cost_multiply_duration',
				'label'       => __( 'Multiply by Duration of Booking', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by Duration of Booking.', 'bookings-for-woocommerce' ),
			),
		);
		$this->bfw_taxonomy_custom_fields_html( $term_fields_arr );
	}

	/**
	 * Saving custom field at wps_booking_cost taxonomy.
	 *
	 * @param int   $term_id current term id for custom taxonomy.
	 * @return void
	 */
	public function bfw_saving_custom_fields_at_booking_cost_taxonomy_page( $term_id ) {
		if ( ! isset( $_POST['_wps_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wps_nonce'] ) ), 'wps_edit_taxonomy_page' ) ) {
			return;
		}
		$term_meta_data = array(
			'wps_bfw_booking_cost'                      => array_key_exists( 'wps_bfw_booking_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_booking_cost'] ) ) : '',
			'wps_bfw_is_booking_cost_multiply_people'   => array_key_exists( 'wps_bfw_is_booking_cost_multiply_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_booking_cost_multiply_people'] ) ) : '',
			'wps_bfw_is_booking_cost_multiply_duration' => array_key_exists( 'wps_bfw_is_booking_cost_multiply_duration', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_booking_cost_multiply_duration'] ) ) : '',
		);
		foreach ( $term_meta_data as $term_meta_key => $term_meta_value ) {
			update_term_meta( $term_id, $term_meta_key, $term_meta_value );
		}
	}

	/**
	 * Adding custom column to wps_booking_cost taxonomy table.
	 *
	 * @param array $columns array containing columns.
	 * @return array
	 */
	public function bfw_adding_custom_column_booking_costs_taxonomy_table( $columns ) {
		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );
		$columns['cost']     = __( 'Cost', 'bookings-for-woocommerce' );
		$columns['people']   = __( 'People', 'bookings-for-woocommerce' );
		$columns['duration'] = __( 'Duration', 'bookings-for-woocommerce' );
		return $columns;
	}

	/**
	 * Add custom column data to wps_booking_cost taxonomy.
	 *
	 * @param string $content content of the column.
	 * @param string $column_name column name of the table.
	 * @param int    $term_id current term id.
	 * @return string
	 */
	public function bfw_adding_custom_column_data_booking_costs_taxonomy_table( $content, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'cost':
				$content = get_term_meta( $term_id, 'wps_bfw_booking_cost', true );
				break;
			case 'people':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_booking_cost_multiply_people', true ) );
				break;
			case 'duration':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_booking_cost_multiply_duration', true ) );
				break;
			default:
				break;
		}
		return $content;
	}

	/**
	 * Adding extra field at custom wps_booking_service taxonomy page.
	 *
	 * @return void
	 */
	public function bfw_adding_custom_fields_at_booking_service_taxonomy_page() {
		wp_nonce_field( 'wps_edit_taxonomy_page', '_wps_nonce' );
		$fields = array(
			array(
				'id'          => 'wps_bfw_service_cost',
				'type'        => 'number',
				'value'       => '',
				'label'       => __( 'Service Cost', 'bookings-for-woocommerce' ),
				'description' => __( 'Please Add service cost here.', 'bookings-for-woocommerce' ),
				'style'       => 'width:10em;',
			),
			array(
				'label'       => __( 'Multiply by Number of People', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_service_cost_multiply_people',
				'type'        => 'checkbox',
				'value'       => 'yes',
				'description' => __( 'Either to multiply by number of people.', 'bookings-for-woocommerce' ),
			),
			array(
				'label'       => __( 'Multiply by Booking Duration', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_service_cost_multiply_duration',
				'value'       => 'yes',
				'type'        => 'checkbox',
				'description' => __( 'Either to multiply by Booking Duration.', 'bookings-for-woocommerce' ),
			),
			array(
				'label'       => __( 'If Optional', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_service_optional',
				'value'       => 'yes',
				'type'        => 'checkbox',
				'description' => __( 'Either the Service is Optional.', 'bookings-for-woocommerce' ),
			),
			array(
				'label'       => __( 'If Hidden', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_service_hidden',
				'value'       => 'yes',
				'type'        => 'checkbox',
				'description' => __( 'Either the Service is Hidden.', 'bookings-for-woocommerce' ),
			),
			array(
				'label'       => __( 'If has Quantity', 'bookings-for-woocommerce' ),
				'id'          => 'wps_bfw_is_service_has_quantity',
				'value'       => 'yes',
				'type'        => 'checkbox',
				'description' => __( 'Either the service has quantity.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'               => 'wps_bfw_service_minimum_quantity',
				'type'             => 'number',
				'value'            => '',
				'label'            => __( 'Minimum Quantity', 'bookings-for-woocommerce' ),
				'description'      => __( 'Please Add Minimum Quantity of the Service Bookable.', 'bookings-for-woocommerce' ),
				'custom_attribute' => array( 'disabled' => 'disabled' ),
				'style'            => 'width:10em;',
			),
			array(
				'id'               => 'wps_bfw_service_maximum_quantity',
				'type'             => 'number',
				'value'            => '',
				'label'            => __( 'Maximum Quantity', 'bookings-for-woocommerce' ),
				'description'      => __( 'Please Add Maximum Quantity of the Service Bookable.', 'bookings-for-woocommerce' ),
				'custom_attribute' => array( 'disabled' => 'disabled' ),
				'style'            => 'width:10em;',
			),
		);
		$this->bfw_taxonomy_adding_fields_html( $fields );
	}

	/**
	 * Adding custom fields at edit page of wps_booking_service taxonomy.
	 *
	 * @param object $term object of current term.
	 * @param string $taxonomy current taxonomy.
	 * @return void
	 */
	public function bfw_adding_custom_fields_at_booking_service_taxonomy_edit_page( $term, $taxonomy ) {
		wp_nonce_field( 'wps_edit_taxonomy_page', '_wps_nonce' );
		$term_fields_arr = array(
			array(
				'id'          => 'wps_bfw_service_cost',
				'name'        => 'wps_bfw_service_cost',
				'label'       => __( 'Service Cost', 'bookings-for-woocommerce' ),
				'type'        => 'number',
				'term_id'     => $term->term_id,
				'description' => __( 'Please Add service cost here.', 'bookings-for-woocommerce' ),
				'style'       => 'width:10em;'
			),
			array(
				'id'          => 'wps_bfw_is_service_cost_multiply_people',
				'name'        => 'wps_bfw_is_service_cost_multiply_people',
				'label'       => __( 'Multiply by No. of People', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by number of people.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'wps_bfw_is_service_cost_multiply_duration',
				'name'        => 'wps_bfw_is_service_cost_multiply_duration',
				'label'       => __( 'Multiply by Booking Duration', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either to multiply by Duration of Booking.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'wps_bfw_is_service_optional',
				'name'        => 'wps_bfw_is_service_optional',
				'label'       => __( 'If Optional', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either the Service is Optional.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'wps_bfw_is_service_hidden',
				'name'        => 'wps_bfw_is_service_hidden',
				'label'       => __( 'If Hidden', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either the Service is Hidden.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'          => 'wps_bfw_is_service_has_quantity',
				'name'        => 'wps_bfw_is_service_has_quantity',
				'label'       => __( 'If has Quantity', 'bookings-for-woocommerce' ),
				'type'        => 'checkbox',
				'term_id'     => $term->term_id,
				'description' => __( 'Either the Service has Quantity.', 'bookings-for-woocommerce' ),
			),
			array(
				'id'               => 'wps_bfw_service_minimum_quantity',
				'name'             => 'wps_bfw_service_minimum_quantity',
				'label'            => __( 'Minimum Quantity', 'bookings-for-woocommerce' ),
				'type'             => 'number',
				'term_id'          => $term->term_id,
				'description'      => __( 'Please Add Minimum Quantity of the Service Bookable.', 'bookings-for-woocommerce' ),
				'custom_attribute' => ( 'yes' !== get_term_meta( $term->term_id, 'wps_bfw_is_service_has_quantity', true ) ) ? array( 'disabled' => 'disabled' ) : array(),
				'style'            => 'width:10em;',
			),
			array(
				'id'               => 'wps_bfw_service_maximum_quantity',
				'name'             => 'wps_bfw_service_maximum_quantity',
				'label'            => __( 'Maximum Quantity', 'bookings-for-woocommerce' ),
				'type'             => 'number',
				'term_id'          => $term->term_id,
				'description'      => __( 'Please Add Maximum Quantity.', 'bookings-for-woocommerce' ),
				'custom_attribute' => ( 'yes' !== get_term_meta( $term->term_id, 'wps_bfw_is_service_has_quantity', true ) ) ? array( 'disabled' => 'disabled' ) : array(),
				'style'            => 'width:10em;',
			),
		);
		$this->bfw_taxonomy_custom_fields_html( $term_fields_arr );
	}

	/**
	 * Saving custom field at wps_booking_cost taxonomy.
	 *
	 * @param int   $term_id current term id for custom taxonomy.
	 * @return void
	 */
	public function bfw_saving_custom_fields_at_booking_service_taxonomy_page( $term_id ) {
		if ( ! isset( $_POST['_wps_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wps_nonce'] ) ), 'wps_edit_taxonomy_page' ) ) {
			return;
		}
		$term_meta_data = array(
			'wps_bfw_service_cost'                      => array_key_exists( 'wps_bfw_service_cost', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_service_cost'] ) ) : '',
			'wps_bfw_is_service_cost_multiply_people'   => array_key_exists( 'wps_bfw_is_service_cost_multiply_people', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_service_cost_multiply_people'] ) ) : '',
			'wps_bfw_is_service_cost_multiply_duration' => array_key_exists( 'wps_bfw_is_service_cost_multiply_duration', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_service_cost_multiply_duration'] ) ) : '',
			'wps_bfw_is_service_optional'               => array_key_exists( 'wps_bfw_is_service_optional', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_service_optional'] ) ) : '',
			'wps_bfw_is_service_hidden'                 => array_key_exists( 'wps_bfw_is_service_hidden', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_service_hidden'] ) ) : '',
			'wps_bfw_is_service_has_quantity'           => array_key_exists( 'wps_bfw_is_service_has_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_is_service_has_quantity'] ) ) : '',
			'wps_bfw_service_minimum_quantity'          => array_key_exists( 'wps_bfw_service_minimum_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_service_minimum_quantity'] ) ) : '',
			'wps_bfw_service_maximum_quantity'          => array_key_exists( 'wps_bfw_service_maximum_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['wps_bfw_service_maximum_quantity'] ) ) : '',
		);

		foreach ( $term_meta_data as $term_meta_key => $term_meta_value ) {
			update_term_meta( $term_id, $term_meta_key, $term_meta_value );
		}
	}

	/**
	 * Adding custom column to wps_booking_service taxonomy table.
	 *
	 * @param array $columns array containing columns.
	 * @return array
	 */
	public function bfw_adding_custom_column_booking_services_taxonomy_table( $columns ) {
		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );
		$columns['cost']         = __( 'Cost', 'bookings-for-woocommerce' );
		$columns['people']       = __( 'People', 'bookings-for-woocommerce' );
		$columns['duration']     = __( 'Duration', 'bookings-for-woocommerce' );
		$columns['optional']     = __( 'Optional', 'bookings-for-woocommerce' );
		$columns['is_hidden']    = __( 'Hidden', 'bookings-for-woocommerce' );
		$columns['has_quantity'] = __( 'Quantity', 'bookings-for-woocommerce' );
		return $columns;
	}

	/**
	 * Add custom column data to wps_booking_service taxonomy.
	 *
	 * @param string $content content of the column.
	 * @param string $column_name column name of the table.
	 * @param int    $term_id current term id.
	 * @return string
	 */
	public function bfw_adding_custom_column_data_booking_services_taxonomy_table( $content, $column_name, $term_id ) {
		switch ( $column_name ) {
			case 'cost':
				$content = get_term_meta( $term_id, 'wps_bfw_service_cost', true );
				break;
			case 'people':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_service_cost_multiply_people', true ) );
				break;
			case 'duration':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_service_cost_multiply_duration', true ) );
				break;
			case 'optional':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_service_optional', true ) );
				break;
			case 'is_hidden':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_service_hidden', true ) );
				break;
			case 'has_quantity':
				$content = $this->wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( get_term_meta( $term_id, 'wps_bfw_is_service_has_quantity', true ) );
				break;
			default:
				break;
		}
		return $content;
	}

	/**
	 * Returm icons in custom taxonomy listing page.
	 *
	 * @param string $value string containing yes or no.
	 * @return string
	 */
	public function wps_bfw_load_icon_for_yesno_custom_taxonomy_listing( $value ) {
		if ( 'yes' === $value ) {
			return '<span class="dashicons dashicons-yes" title="' . esc_attr__( 'yes', 'bookings-for-woocommerce' ) . '"></span>';
		}
		return '<span class="dashicons dashicons-no-alt" title="' . esc_attr__( 'no', 'bookings-for-woocommerce' ) . '"></span>';
	}

	/**
	 * Add custom badge of booking at order listing page.
	 *
	 * @param string $column_name current table columnname.
	 * @param int    $order_id current order id.
	 * @return void
	 */
	public function bfw_add_label_for_booking_type( $column_name, $order_id ) {
		if ( 'order_number' === $column_name ) {
			$order = wc_get_order( $order_id );
			if ( 'booking' === $order->get_meta( 'wps_order_type', true ) ) {
				?>
				<span class="wps-mbfw-booking-product-order-listing" title="<?php esc_html_e( 'This order contains Booking Service/Product.', 'bookings-for-woocommerce' ); ?>">
					<?php esc_html_e( 'Booking Order', 'bookings-for-woocommerce' ); ?>
				</span>
				<?php
			}
		}
	}

	/**
	 * Adding filter on the order listing page.
	 *
	 * @return void
	 */
	public function bfw_add_filter_on_order_listing_page() {
		global $pagenow, $post_type;
		if ( 'shop_order' !== $post_type && 'edit.php' !== $pagenow ) {
			return;
		}
		$current = isset( $_GET['filter_booking'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_booking'] ) ) : ''; // phpcs:ignore WordPress.Security
		?>
		<select name="filter_booking">
			<option value="">
				<?php esc_html_e( 'choose filter..', 'bookings-for-woocommerce' ); ?>
			</option>
			<option value="booking" <?php selected( $current, 'booking' ); ?>>
				<?php esc_html_e( 'Filter by Booking', 'bookings-for-woocommerce' ); ?>
			</option>
		</select>
		<?php
	}

	/**
	 * Altering query to show the results from custom filter.
	 *
	 * @param object $query query for showing posts.
	 * @return void
	 */
	public function bfw_vary_query_to_list_only_booking_types( $query ) {
		global $pagenow;
		// phpcs:disable WordPress
		if ( $query->is_admin && 'edit.php' === $pagenow && isset( $_GET['filter_booking'] ) && '' !== sanitize_text_field( wp_unslash( $_GET['filter_booking'] ) ) && isset( $_GET['post_type'] ) && ( 'shop_order' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) ) ) {
			$meta_query = array(
				array(
					'meta_key' => 'wps_order_type',
					'value'    => sanitize_text_field( wp_unslash( $_GET['filter_booking'] ) ),
				)
			);
			$query->set( 'meta_query', $meta_query );
			$query->set( 'posts_per_page', 10 );
			$query->set( 'paged', ( get_query_var('paged') ? get_query_var('paged') : 1 ) );
		}
		// phpcs:enable WordPress
	}

	/**
	 * Hide custom line item key from order edit page.
	 *
	 * @param array $hidden_keys array containing keys to hide.
	 * @return array
	 */
	public function bfw_hide_order_item_meta_data( $hidden_keys ) {
		$custom_line_item_key_name = array(
			'_wps_bfw_people_number',
			'_wps_bfw_enable_calendar',
			'_wps_bfw_enable_time_picker',
			'_wps_bfw_service_and_count',
			'_wps_bfw_booking_extra_costs',
		);
		return
		//desc - hide custom line item meta fields.
		apply_filters( 'wps_bfw_hide_custom_line_item_meta_keys', array_merge( $hidden_keys, $custom_line_item_key_name ) );
	}

	/**
	 * Change the line item meta key.
	 *
	 * @param string $display_key key name to display.
	 * @param object $meta meta key object.
	 * @param object $item current item object.
	 * @return string
	 */
	public function bfw_change_line_item_meta_key_order_edit_page( $display_key, $meta, $item  ) {
		switch ( $display_key ) {
			case '_wps_bfwp_date_time_from':
				return __( 'From', 'bookings-for-woocommerce' );
			case '_wps_bfwp_date_time_to':
				return __( 'To', 'bookings-for-woocommerce' );
			default:
				break;
		}
		return $display_key;
	}

	/**
	 * Get all booking dates.
	 *
	 * @return void
	 */
	public function wps_bfw_get_all_events_date() {
		check_ajax_referer( 'wps_bfw_admin_nonce', 'nonce' );
		$orders     = wc_get_orders(
			array(
				'status'   => array( 'wc-processing', 'wc-on-hold', 'wc-pending' ),
				'limit'    => -1,
				'meta_key' => 'wps_order_type', // phpcs:ignore WordPress
				'meta_val' => 'booking',
			)
		);
		$all_events = array();
		foreach ( $orders as $order ) {
			$items = $order->get_items();
			foreach ( $items as $item ) {
				$date_time_from = $item->get_meta( '_wps_bfwp_date_time_from', true );
				$date_time_to   = $item->get_meta( '_wps_bfwp_date_time_to', true );
				$date_time_from = ( ! empty( $date_time_from ) ? $date_time_from : gmdate( 'd-m-Y H:i', $order->get_date_created() ) );
				$date_time_to   = ( ! empty( $date_time_to ) ? $date_time_to : gmdate( 'd-m-Y H:i', $order->get_date_created() ) );
				$all_events[]   = array(
					'title' => $item['name'],
					'start' => gmdate( 'Y-m-d', strtotime( $date_time_from ) ) . 'T' . gmdate( 'H:i', strtotime( $date_time_from ) ),
					'end'   => gmdate( 'Y-m-d', strtotime( $date_time_to ) ) . 'T' . gmdate( 'H:i', strtotime( $date_time_to ) ),
				);
			}
		}
		wp_send_json( $all_events );
		wp_die();
	}

	/**
	 * Migrate old plugin settings.
	 *
	 * @return void
	 * @version 1.0.0
	 */
	public function wps_bfw_migrate_settings_from_older_plugin() {
		if ( ! get_option( 'wps_bfw_plugin_setting_migrated' ) ) {
			include_once BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-bookings-for-woocommerce-activator.php';
			// Bookings_For_Woocommerce_Activator::wps_migrate_old_plugin_settings();
		}
	}
	/**
	 * Undocumented function
	 *
	 * @param string $type
	 * @param string $action
	 * @return void
	 */
	public function wps_bfw_get_count( $status = 'all', $action = 'count', $type = false ) {
		global $wpdb;

		if ( 'post' === $type ) {
			switch ( $status ) {
				case 'pending':
					$table = $wpdb->prefix . 'postmeta';
					if ( 'result' === $action ) {
						$sql = "SELECT DISTINCT (`post_id`) FROM `$table` WHERE `meta_key` LIKE '%mwb_mbfw%' ";
					} else {
						$sql = "SELECT (`post_id`) FROM `$table` WHERE `meta_key` LIKE '%mwb_mbfw%' ";
					}
					$sql = apply_filters( 'wps_bfw_sql_for_post_meta', $sql, $table, $action );
					break;
				default:
					$sql = false;
					break;
			}
			if ( empty( $sql ) ) {
				return 0;
			}
			$result = $wpdb->get_results( $sql, ARRAY_A ); // @codingStandardsIgnoreLine.

		} elseif ( 'terms' === $type ) {
			switch ( $status ) {
				case 'pending':
					$term_query = new WP_Term_Query();
					$term_args_cost = array( 
						'taxonomy' => 'mwb_booking_cost',
						'hide_empty' => false,
					);
					$term_cost = $term_query->query( $term_args_cost );
					
					$result = array();
					if ( ! empty( $term_cost ) ) {
						foreach( $term_cost as $index => $value ) {
							array_push( $result, array( 'term_id' => "$value->term_id" ) );
						}
					}
					$term_args = array(
						'taxonomy' => 'mwb_booking_service',
						'hide_empty' => false,
					);
			
					$terms = $term_query->query( $term_args );
			
					if (! empty($terms)) {
						foreach($terms as $index => $value) {
							array_push( $result, array( 'term_id' => "$value->term_id" ) );
						}
					}
					$result = apply_filters('wps_bfw_migrate_get_terms_id_array', $result );
					break;
				default:
					$result = false;
					break;
			}
		} elseif ( 'users' === $type ) {
			switch ( $status ) {
				case 'pending':
					$table = $wpdb->prefix . 'usermeta';
					if ( 'result' === $action ) {
						$sql = "SELECT DISTINCT (`user_id`) FROM `$table` WHERE (`meta_key` LIKE '%_woocommerce_persistent_cart_1%' OR `meta_key` LIKE '%meta-box-order_product%') AND `meta_value` LIKE '%mwb_%' ";
						
					} else {
						$sql = "SELECT (`user_id`) FROM `$table` WHERE ((`meta_key` LIKE '%_woocommerce_persistent_cart_1%' OR `meta_key` LIKE '%meta-box-order_product%') AND `meta_value` LIKE '%mwb_%') ";
					}
					break;

				default:
					$sql = false;
					break;
			}
			if ( empty( $sql ) ) {
				return 0;
			}
			$result = $wpdb->get_results( $sql, ARRAY_A );
		}


		if ( 'count' === $action ) {
			$result = ! empty( $result ) ? count( $result ) : 0;
		}

		return $result;
	}

	/**
	 * Ajax Call back.
	 */
	public function wps_bfw_ajax_callbacks() {
		check_ajax_referer( 'wps_bfw_popup_nonce', 'nonce' );
		$event = ! empty( $_POST['wps_event'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_event'] ) ) : '';
		if ( method_exists( $this, $event ) ) {
			$data = $this->$event( $_POST );
		} else {
			$data = esc_html__( 'method not found', 'bookings-for-woocommerce' );
		}
		echo wp_json_encode( $data );
		wp_die();
	}
	/**
	 * Function to migrate post meta.
	 *
	 * @param array $posted_data
	 * @return void
	 */
	public function wps_bfw_import_single_post( $posted_data = array() ) {
		$posts = ! empty( $posted_data['posts'] ) ? $posted_data['posts'] : array();
		if ( empty( $posts ) ) {
			return array();
		}

		// Remove this order from request.
		foreach ( $posts as $key => $post ) {
			$post_id = ! empty( $post['post_id'] ) ? $post['post_id'] : false;
			unset( $posts[ $key ] );
			break;
		}
		// Attempt for one order.
		// if ( ! empty( $post_id ) ) {
		// 	try {

		// 		$post_meta_keys = array(
		// 			'mwb_mbfw_booking_criteria'                => '',
		// 			'mwb_mbfw_maximum_booking_per_unit'        => '',
		// 			'mwb_mbfw_booking_unit'                    => '',
		// 			'mwb_mbfw_enable_calendar'                 => '',
		// 			'mwb_mbfw_enable_time_picker'              => '',
		// 			'mwb_mbfw_admin_confirmation'              => '',
		// 			'mwb_mbfw_cancellation_allowed'            => '',
		// 			'mwb_bfwp_order_statuses_to_cancel'        => '',
		// 			'mwb_mbfw_booking_unit_cost'               => '',
		// 			'mwb_mbfw_is_booking_unit_cost_per_people' => '',
		// 			'mwb_mbfw_booking_base_cost'               => '',
		// 			'mwb_mbfw_is_booking_base_cost_per_people' => '',
		// 			'mwb_mbfw_is_people_option'                => '',
		// 			'mwb_mbfw_minimum_people_per_booking'      => '',
		// 			'mwb_mbfw_maximum_people_per_booking'      => '',
		// 			'mwb_mbfw_is_add_extra_services'           => '',
		// 			'mwb_mbfw_max_bookings'                    => '',
		// 			'mwb_mbfw_booking_count'                   => '',
		// 		);
				// $post_meta_keys = apply_filters( 'wps_bfw_migrate_post_meta_array', $post_meta_keys );

		// 		foreach ( $post_meta_keys as $key => $meta_keys ) {

		// 			if ( ! empty( $post_id ) ) {
		// 				$value   = get_post_meta( $post_id, $key, true );
						// if ( 'mwb_mbfw_rescheduling_allowed' === $key ) {
						// 	$new_key = str_replace( 'mwb_', 'wps_', $key );
						// } elseif (str_contains($key,'mwb_mbfw_')) {
						// 	$new_key = str_replace( 'mwb_mbfw_', 'wps_bfw_', $key );
						// } else {
						// 	$new_key = str_replace( 'mwb_', 'wps_', $key );
						// }
		// 				if ( ! empty( get_post_meta( $post_id, $new_key, true ) ) ) {
		// 					continue;
		// 				}
		// 				update_post_meta($post_id,$new_key,$value);
		// 			}
		// 		}
		// 	} catch ( \Throwable $th ) {
		// 		wp_die( esc_html( $th->getMessage() ) );
		// 	}
		// }
		return compact( 'posts' );
	}
	/**
	 * Function to migrate term meta.
	 *
	 * @param array $posted_data
	 * @return void
	 */
	public function wps_bfw_import_single_term( $posted_data = array() ) {
		$terms = ! empty( $posted_data['terms'] ) ? $posted_data['terms'] : array();
		if ( empty( $terms ) ) {
			return array();
		}

		// Remove this order from request.
		foreach ( $terms as $key => $term ) {
			$term_id = ! empty( $term['term_id'] ) ? $term['term_id'] : false;
			unset( $terms[ $key ] );
			break;
		}
		// Attempt for one order.
		// if ( ! empty( $term_id ) ) {
		// 	try {

		// 		$term_meta_keys = array(
		// 			'mwb_mbfw_booking_cost'                      => '',
		// 			'mwb_mbfw_is_booking_cost_multiply_people'   => '',
		// 			'mwb_mbfw_is_booking_cost_multiply_duration' => '',
		// 			'mwb_mbfw_service_cost'                      => '',
		// 			'mwb_mbfw_is_service_cost_multiply_people'   => '',
		// 			'mwb_mbfw_is_service_cost_multiply_duration' => '',
		// 			'mwb_mbfw_is_service_optional' 				 => '',
		// 			'mwb_mbfw_is_service_hidden'                 => '',
		// 			'mwb_mbfw_is_service_has_quantity'           => '',
		// 			'mwb_mbfw_service_minimum_quantity'		     => '',
		// 			'mwb_mbfw_service_maximum_quantity'          => '',
		// 		);
		// $term_meta_keys = apply_filters( 'wps_bfw_migrate_term_meta_array', $term_meta_keys );

		// 		foreach ( $term_meta_keys as $key => $meta_keys ) {

		// 			if ( ! empty( $term_id ) ) {
		// 				$value   = get_term_meta( $term_id, $key, true );
		// 				if (str_contains($key,'mwb_mbfw_')) {
						// 	$new_key = str_replace( 'mwb_mbfw_', 'wps_bfw_', $key );
						// } else {
						// 	$new_key = str_replace( 'mwb_', 'wps_', $key );
						// }
		// 				if ( ! empty( get_term_meta( $term_id, $new_key, true ) ) || empty ( $value ) ) {
		// 					continue;
		// 				}
		// 				update_term_meta( $term_id, $new_key, $value );
		// 			}
		// 		}
		// 	} catch ( \Throwable $th ) {
		// 		wp_die( esc_html( $th->getMessage() ) );
		// 	}
		// }
		return compact( 'terms' );
	}

		/**
	 * Function to migrate term meta.
	 *
	 * @param array $posted_data
	 * @return void
	 */
	public function wps_bfw_import_single_user( $posted_data = array() ) {

		$users = ! empty( $posted_data['users'] ) ? $posted_data['users'] : array();
		if ( empty( $users ) ) {
			return array();
		}

		// Remove this order from request.
		foreach ( $users as $key => $user ) {
			$user_id = ! empty( $user['user_id'] ) ? $user['user_id'] : false;
			unset( $users[ $key ] );
			break;
		}
		// Attempt for one order.
		// if ( ! empty( $user_id ) ) {
		// 	try {

		// 		$user_meta_keys = array(
		// 			'_woocommerce_persistent_cart_1' => '',
		// 			'meta-box-order_product'         => '',
		// 		);
		// 		foreach ( $user_meta_keys as $meta_keys => $meta_val ) {

		// 			$value   = get_user_meta( $user_id, $meta_keys, true );
		// 			if ( '_woocommerce_persistent_cart_1' === $meta_keys ) {
		// 				if ( ! empty($value['cart'] ) ) {
		// 					foreach ( $value['cart'] as $key => $v ) {
		// 						if ( isset( $v['mwb_mbfw_booking_values'] ) ) {
		// 							$v['wps_bfw_booking_values'] = $v['mwb_mbfw_booking_values'];
		// 							unset( $v['mwb_mbfw_booking_values'] );
		// 							$value['cart'][$key] = $v;
		// 							update_user_meta( $user_id, $meta_keys, $value );
		// 						}
		// 					}
		// 				}
		// 			} else if ( 'meta-box-order_product' === $meta_keys ) {
		// 				if ( ! empty($value['side']) ) {
		// 					$a=explode(',',$value['side']);
		// 					foreach( $a as $key => $val) {
		// 						if ( str_contains($val, 'mwb_booking') ) { 
		// 							$new_val = str_replace( 'mwb', 'wps', $val );
		// 							$a[$key] = $new_val;
		// 						}
		// 					}
		// 					$str = implode( ',', $a );
		// 					$value['side'] = $str;
		// 				}
		// 				update_user_meta( $user_id, $meta_keys, $value );
		// 			}
					
		// 		}
		// 	} catch ( \Throwable $th ) {
		// 		wp_die( esc_html( $th->getMessage() ) );
		// 	}
		// }
		return compact( 'users' );
		
	}

	public static function wps_bfw_migrate_taxonomy_values(){
		// global $wpdb;
		
		// $post_table = $wpdb->prefix . 'term_taxonomy';
		
		// if ( $wpdb->query( $wpdb->prepare("SELECT * FROM %1s WHERE  `taxonomy` = 'mwb_booking_cost'", $post_table ) ) ) {
		// 	$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `taxonomy` = 'wps_booking_cost' 
		// 	WHERE  `taxonomy` = 'mwb_booking_cost'", $post_table ) );
		// }

		// if ( $wpdb->query( $wpdb->prepare("SELECT * FROM %1s WHERE  `taxonomy` = 'mwb_booking_service'", $post_table ) ) ) {
		// 	$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `taxonomy` = 'wps_booking_service' 
		// 	WHERE  `taxonomy` = 'mwb_booking_service'", $post_table ) );
		// }
		// do_action('wps_migrate_pro_taxonomy',$post_table);
		// $term_table = $wpdb->prefix . 'terms';
		// if ( $wpdb->query( $wpdb->prepare("SELECT * FROM %1s WHERE  `name` = 'mwb_booking'", $term_table ) ) ) {
		// 	$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `name` = 'wps_booking',`slug`='wps_booking'
		// 	WHERE  `name` = 'mwb_booking'", $term_table ) );
		// }
		return array();
		}
		/**
		 * Undocumented function
		 *
		 * @return void
		 */
		public static function wps_bfw_migrate_option_values(){
			// $wps_booking_old_settings_options = array(
			// 	'mwb_mbfw_is_plugin_enable'         => 'yes',
			// 	'mwb_mbfw_is_booking_enable'        => '',
			// 	'mwb_mbfw_is_show_included_service' => '',
			// 	'mwb_mbfw_is_show_totals'           => '',
			// 	'mwb_mbfw_daily_start_time'         => '05:24',
			// 	'mwb_mbfw_daily_end_time'           => '23:26',
			// );
			// foreach ( $wps_booking_old_settings_options as $key => $value ) {
			// 	$new_key = str_replace( 'mwb_mbfw_', 'wps_bfw_', $key );
	
			// 	if ( ! empty( get_option( $new_key ) ) ) {
			// 		continue;
			// 	}
	
			// 	$new_value = get_option( $key, $value );
			// 	update_option( $new_key, $new_value );
			// }
			// do_action('wps_bfw_migrate_pro_options_keys');
			return array();
		}
		/**
		 * Undocumented function migrate order itemmeta values
		 *
		 * @return void
		 */
		public static function wps_bfw_migrate_order_itemmeta_values(){
			// global $wpdb;
	
			// $key_like  = '_mwb';
			// $order_item_meta_table = $wpdb->prefix . 'woocommerce_order_itemmeta';
			// $sql = $wpdb->prepare(
			// 	"SELECT * FROM $order_item_meta_table WHERE meta_key LIKE %s;",
			// 	'%' . $wpdb->esc_like( $key_like ) . '%'
			// );
			// $result_keys = $wpdb->get_results($sql);
			// if ( ! empty ($result_keys) ) {
			// 	foreach ( $result_keys as $item_meta_row ) {
			// 		if ( str_contains( $item_meta_row->meta_key, '_mwb_mbfw') ) { 
			// 			$new_key = str_replace( '_mwb_mbfw', '_wps_bfw', $item_meta_row->meta_key );
			// 			$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `meta_key` = '%2s' 
			// 			WHERE `meta_id` = $item_meta_row->meta_id", $order_item_meta_table, $new_key ) );
			// 		} elseif ( str_contains( $item_meta_row->meta_key, '_mwb') ) {
			// 			$new_key = str_replace( '_mwb', '_wps', $item_meta_row->meta_key );
			// 			$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `meta_key` = '%2s' 
			// 			WHERE `meta_id` = $item_meta_row->meta_id", $order_item_meta_table, $new_key ) );
			// 		}
			// 	}
			// }
			return array();
		}
	/**
	 * Function to migrate sessions value.
	 *
	 * @return void
	 */
	public static function wps_bfw_migrate_sessions_values() {
		// global $wpdb;
	
		// $key_like  = 'mwb';
		// $woocommerce_sessions_table = $wpdb->prefix . 'woocommerce_sessions';
		// $sql = $wpdb->prepare(
		// 	"SELECT * FROM $woocommerce_sessions_table WHERE session_value LIKE %s;",
		// 	'%' . $wpdb->esc_like( $key_like ) . '%'
		// );
		// $result_keys = $wpdb->get_results( $sql );
		// var_dump($result_keys);
		// if ( ! empty ( $result_keys ) ) {
		// 	foreach ( $result_keys as $item_meta_row ) {
		// 		if ( str_contains($item_meta_row->session_value, 'mwb_mbfw_booking_values') ) { 
		// 			$new_val = str_replace( 'mwb_mbfw_booking_values', 'wps_bfw_booking_values', $item_meta_row->session_value );
		// 			$item_meta_row->session_value = $new_val;
		// 			$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `session_value` = %2s 
		// 			WHERE  `session_id` = %3s", $woocommerce_sessions_table, $item_meta_row->session_value, $item_meta_row->session_id ) );
		// 		}
		// 	}
		// }
		return array();
	}
	/**
	 * Undocumented function
	 *
	 * @param array $posted_data
	 * @return void
	 */
	public static function wps_bfw_import_single_shortcode( $posted_data = array() ) {
		$shortcodes = ! empty( $posted_data['shortcodes'] ) ? $posted_data['shortcodes'] : array();
		if ( empty( $shortcodes ) ) {
			return array();
		}

		// Remove this order from request.
		foreach ( $shortcodes as $key => $shortcode ) {
			$page_id = ! empty( $shortcode['ID'] ) ? $shortcode['ID'] : false;
			unset( $shortcodes[ $key ] );
			break;
		}
		// Attempt for one order.
		if ( ! empty( $page_id ) ) {
			try {

				$post = get_post( $page_id );
				$content = $post->post_content;

				$content = str_replace( 'MWB_', 'WPS_', $content );
				// $content = str_replace( 'mwb_', 'wps_', $content );
				$my_post = array(
					'ID'           => $page_id,
					'post_content' => $content,
				);
		
				wp_update_post( $my_post );

			} catch ( \Throwable $th ) {
				wp_die( esc_html( $th->getMessage() ) );
			}
		}
		return compact( 'shortcodes' );
	}
}
