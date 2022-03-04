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
class Wps_Bfw_Data_Handler {

	/**
	 * Define the core functionality of the migrator.
	 *
	 * @since    1.0.4
	 */
	public function __construct() {
		if ( ! get_option( 'wps_bfw_plugin_setting_migrated' ) ) {
			$this->wps_migrate_plugin_option_keys();
			$this->wps_bfw_migrate_post_meta_keys();
			$this->wps_bfw_migrate_term_meta_keys();
			$this->wps_bfw_migrate_user_meta_values();
			$this->wps_bfw_migrate_order_item_meta_values();
			$this->wps_bfw_migrate_sessions_values();
			update_option( 'wps_bfw_plugin_setting_migrated', 'yes' );
		}
	}

	/**
	 * Migrate old plugin settings.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function wps_migrate_plugin_option_keys() {

		$wps_booking_old_settings_options = array(
			'mwb_mbfw_is_plugin_enable'         => 'yes',
			'mwb_mbfw_is_booking_enable'        => '',
			'mwb_mbfw_is_show_included_service' => '',
			'mwb_mbfw_is_show_totals'           => '',
			'mwb_mbfw_daily_start_time'         => '05:24',
			'mwb_mbfw_daily_end_time'           => '23:26',
			);
		foreach ( $wps_booking_old_settings_options as $key => $value ) {
			$new_key = str_replace( 'mwb_mbfw_', 'wps_bfw_', $key );

			if ( ! empty( get_option( $new_key ) ) ) {
				continue;
			}

			$new_value = get_option( $key, $value );
			update_option( $new_key, $new_value );
		}
	}
	/**
	 * To migrate the product meta keys.
	 *
	 * @return void
	 */
	public static function wps_bfw_migrate_post_meta_keys() {
		$wps_booking_old_post_meta_keys = array(
			'mwb_mbfw_booking_criteria'                => '',
			'mwb_mbfw_maximum_booking_per_unit'        => '',
			'mwb_mbfw_booking_unit'                    => '',
			'mwb_mbfw_enable_calendar'                 => '',
			'mwb_mbfw_enable_time_picker'              => '',
			'mwb_mbfw_admin_confirmation'              => '',
			'mwb_mbfw_cancellation_allowed'            => '',
			'mwb_bfwp_order_statuses_to_cancel'        => '',
			'mwb_mbfw_booking_unit_cost'               => '',
			'mwb_mbfw_is_booking_unit_cost_per_people' => '',
			'mwb_mbfw_booking_base_cost'               => '',
			'mwb_mbfw_is_booking_base_cost_per_people' => '',
			'mwb_mbfw_is_people_option'                => '',
			'mwb_mbfw_minimum_people_per_booking'      => '',
			'mwb_mbfw_maximum_people_per_booking'      => '',
			'mwb_mbfw_is_add_extra_services'           => '',
			'mwb_mbfw_max_bookings'                    => '',
			'mwb_mbfw_booking_count'                   => '',
		);

		foreach ( $wps_booking_old_post_meta_keys as $key => $meta_keys ) {
			$products = get_posts(
				array(
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids', // return only ids.
					'meta_key'    => $key, //phpcs:ignore
					'post_type'   => 'product',
					'order'       => 'ASC',
				)
			);

			if ( ! empty( $products ) && is_array( $products ) ) {
				foreach ( $products as $k => $product_id ) {
					$value   = get_post_meta( $product_id, $key, true );
					if ( 'mwb_bfwp_order_statuses_to_cancel' === $key ) {
						$new_key = str_replace( 'mwb_', 'wps_', $key );
					} else {
						$new_key = str_replace( 'mwb_mbfw_', 'wps_bfw_', $key );
					}
					if ( ! empty( get_post_meta( $product_id, $new_key, true ) ) ) {
						continue;
					}
					update_post_meta( $product_id, $new_key, $value );
				}
			}
		}
	}

	/**
	 * To migrate the term meta keys.
	 *
	 * @return void
	 */
	public static function wps_bfw_migrate_term_meta_keys() {
		$wps_booking_old_term_meta_keys = array(
			'mwb_mbfw_booking_cost'                      => '',
			'mwb_mbfw_is_booking_cost_multiply_people'   => '',
			'mwb_mbfw_is_booking_cost_multiply_duration' => '',
			'mwb_mbfw_service_cost'                      => '',
			'mwb_mbfw_is_service_cost_multiply_people'   => '',
			'mwb_mbfw_is_service_cost_multiply_duration' => '',
			'mwb_mbfw_is_service_optional' 				 => '',
			'mwb_mbfw_is_service_hidden'                 => '',
			'mwb_mbfw_is_service_has_quantity'           => '',
			'mwb_mbfw_service_minimum_quantity'		     => '',
			'mwb_mbfw_service_maximum_quantity'          => '',
		);
		$term_query = new WP_Term_Query();
		$term_args_cost = array( 
			'taxonomy' => 'mwb_booking_cost',
			'hide_empty' => false,
		);
		$term_cost = $term_query->query( $term_args_cost );
		
		$term_ids=array();
		if ( ! empty( $term_cost ) ) {
			foreach( $term_cost as $index => $value ) {
				array_push( $term_ids, $value->term_id );
			}
		}
		$term_args = array(
			'taxonomy' => 'mwb_booking_service',
			'hide_empty' => false,
		);

		$terms = $term_query->query( $term_args );

		if (! empty($terms)) {
			foreach($terms as $index => $value) {
				array_push($term_ids,$value->term_id);
			}
		}
		foreach ( $wps_booking_old_term_meta_keys as $key => $meta_keys ) {

			if ( ! empty( $term_ids ) && is_array( $term_ids ) ) {
				foreach ( $term_ids as $k => $term_id ) {
					$value   = get_term_meta( $term_id, $key, true );
						$new_key = str_replace( 'mwb_mbfw_', 'wps_bfw_', $key );
					if ( ! empty( get_term_meta( $term_id, $new_key, true ) ) ) {
						continue;
					}
					$value= get_term_meta( $term_id, $key, true );
					update_term_meta($term_id,$new_key,$value);
					
				}
			}
		}

		global $wpdb;
		
		$post_table = $wpdb->prefix . 'term_taxonomy';
		
		if ( $wpdb->query( $wpdb->prepare("SELECT * FROM %1s WHERE  `taxonomy` = 'mwb_booking_cost'", $post_table ) ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `taxonomy` = 'wps_booking_cost' 
			WHERE  `taxonomy` = 'mwb_booking_cost'", $post_table ) );
		}

		if ( $wpdb->query( $wpdb->prepare("SELECT * FROM %1s WHERE  `taxonomy` = 'mwb_booking_service'", $post_table ) ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `taxonomy` = 'wps_booking_service' 
			WHERE  `taxonomy` = 'mwb_booking_service'", $post_table ) );
		}

		$term_table = $wpdb->prefix . 'terms';
		if ( $wpdb->query( $wpdb->prepare("SELECT * FROM %1s WHERE  `name` = 'mwb_booking'", $term_table ) ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `name` = 'wps_booking',`slug`='wps_booking'
			WHERE  `name` = 'mwb_booking'", $term_table ) );
		}
	}
	
	/**
	 * Function to migrate user meta values.
	 *
	 * @return void
	 */
	public static function wps_bfw_migrate_user_meta_values() {
		$query_params = array(
			array('meta_key' => '_woocommerce_persistent_cart_1',),
			array('meta_key' => 'meta-box-order_product',)
		);
	
		foreach ( $query_params as $meta_keys ) {
			$users = get_users( $meta_keys);
			$user_ids= array();
			if ( ! empty( $users ) ) {
				foreach( $users as $user ) {
					array_push( $user_ids, $user->ID );
				}
			}
			
			if ( ! empty( $user_ids ) && is_array( $user_ids ) ) {
				foreach ( $user_ids as $k => $user_id ) {
					$value   = get_user_meta( $user_id, $meta_keys['meta_key'], true );
					if ( '_woocommerce_persistent_cart_1' === $meta_keys['meta_key'] ) {
						if ( ! empty($value['cart'] ) ) {
							foreach ( $value['cart'] as $key => $v ) {
								if ( isset( $v['mwb_mbfw_booking_values'] ) ) {
									$v['wps_bfw_booking_values'] = $v['mwb_mbfw_booking_values'];
									unset( $v['mwb_mbfw_booking_values'] );
									$value['cart'][$key] = $v;
									update_user_meta( $user_id, $meta_keys['meta_key'], $value );
								}
							}
						}
					} else if ( 'meta-box-order_product' === $meta_keys['meta_key'] ) {
						if ( ! empty($value['side']) ) {
							$a=explode(',',$value['side']);
							foreach( $a as $key => $val) {
								if ( str_contains($val, 'mwb') ) { 
									$new_val = str_replace( 'mwb', 'wps', $val );
									$a[$key] = $new_val;
								}
							}
							$str = implode( ',', $a );
							$value['side'] = $str;
						}
						update_user_meta( $user_id, $meta_keys['meta_key'], $value );
					}			
				}
			}
		}
	}

	/**
	 * function to migrate item meta keys.
	 *
	 * @return void
	 */
	public static function wps_bfw_migrate_order_item_meta_values() {
		global $wpdb;
	
		$key_like  = '_mwb';
		$order_item_meta_table = $wpdb->prefix . 'woocommerce_order_itemmeta';
		$sql = $wpdb->prepare(
			"SELECT * FROM $order_item_meta_table WHERE meta_key LIKE %s;",
			'%' . $wpdb->esc_like( $key_like ) . '%'
		);
		$result_keys = $wpdb->get_results($sql);
		if ( ! empty ($result_keys) ) {
			foreach ( $result_keys as $item_meta_row ) {
				if ( str_contains( $item_meta_row->meta_key, '_mwb_mbfw') ) { 
					$new_key = str_replace( '_mwb_mbfw', '_wps_bfw', $item_meta_row->meta_key );
					$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `meta_key` = '%2s' 
					WHERE `meta_id` = $item_meta_row->meta_id", $order_item_meta_table, $new_key ) );
				} elseif ( str_contains( $item_meta_row->meta_key, '_mwb') ) {
					$new_key = str_replace( '_mwb', '_wps', $item_meta_row->meta_key );
					$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `meta_key` = '%2s' 
					WHERE `meta_id` = $item_meta_row->meta_id", $order_item_meta_table, $new_key ) );
				}
			}
		}
	}

	/**
	 * Function to migrate sessions value.
	 *
	 * @return void
	 */
	public static function wps_bfw_migrate_sessions_values() {
		global $wpdb;
	
		$key_like  = 'mwb';
		$woocommerce_sessions_table = $wpdb->prefix . 'woocommerce_sessions';
		$sql = $wpdb->prepare(
			"SELECT * FROM $woocommerce_sessions_table WHERE session_value LIKE %s;",
			'%' . $wpdb->esc_like( $key_like ) . '%'
		);
		$result_keys = $wpdb->get_results( $sql );
		var_dump($result_keys);
		if ( ! empty ( $result_keys ) ) {
			foreach ( $result_keys as $item_meta_row ) {
				if ( str_contains($item_meta_row->session_value, 'mwb_mbfw_booking_values') ) { 
					$new_val = str_replace( 'mwb_mbfw_booking_values', 'wps_bfw_booking_values', $item_meta_row->session_value );
					$item_meta_row->session_value = $new_val;
					$wpdb->query( $wpdb->prepare( "UPDATE %1s SET `session_value` = %2s 
					WHERE  `session_id` = %3s", $woocommerce_sessions_table, $item_meta_row->session_value, $item_meta_row->session_id ) );
				}
			}
		}
	}

	// End of class.
}
