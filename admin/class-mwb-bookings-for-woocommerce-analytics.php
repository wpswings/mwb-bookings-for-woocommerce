<?php
/**
 * Analytics tab class for WPS Bookings for WooCommerce.
 *
 * Handles asset enqueueing, AJAX data endpoint, and booking data computation.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Mwb_Bookings_For_Woocommerce_Analytics
 */
class Mwb_Bookings_For_Woocommerce_Analytics {

	/**
	 * Analytics transient cache TTL in seconds (1 hour).
	 */
	const CACHE_TTL = 3600;

	/**
	 * Constructor — registers all hooks.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_wps_bfw_get_analytics_data', array( $this, 'ajax_get_analytics_data' ) );
		// Bust analytics cache whenever a booking order status changes.
		add_action( 'woocommerce_order_status_changed', array( $this, 'bust_analytics_cache' ), 10, 3 );
	}

	/**
	 * Delete all analytics transients when an order status changes.
	 * This ensures revenue figures reflect newly completed orders immediately.
	 *
	 * @param int    $order_id  Order ID.
	 * @param string $old_status Previous status.
	 * @param string $new_status New status.
	 */
	public function bust_analytics_cache( $order_id, $old_status, $new_status ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || 'booking' !== $order->get_meta( 'mwb_order_type', true ) ) {
			return;
		}
		global $wpdb;
		// Delete all wps_bfw_analytics_* transients.
		$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_wps_bfw_analytics_%',
				'_transient_timeout_wps_bfw_analytics_%'
			)
		);
	}

	/**
	 * Returns true when the Pro plugin is active.
	 *
	 * @return bool
	 */
	public static function is_pro_active() {
		return in_array(
			'bookings-for-woocommerce-pro/bookings-for-woocommerce-pro.php',
			get_option( 'active_plugins', array() ),
			true
		);
	}

	/**
	 * Enqueue CSS always on analytics tab; JS/Chart.js only on analytics tab.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_assets( $hook ) {
		// Only on the plugin's admin page.
		if ( false === strpos( $hook, 'mwb_bookings_for_woocommerce_menu' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$tab = isset( $_GET['mbfw_tab'] ) ? sanitize_key( $_GET['mbfw_tab'] ) : '';
		if ( 'mwb-bookings-for-woocommerce-analytics' !== $tab ) {
			return;
		}

		wp_enqueue_style(
			'wps-bfw-analytics-css',
			MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-bookings-analytics.css',
			array(),
			MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION
		);

		// Chart.js 4.x from CDN.
		wp_enqueue_script(
			'chartjs',
			'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js',
			array(),
			'4.4.3',
			true
		);

		wp_enqueue_script(
			'wps-bfw-analytics-js',
			MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-bookings-analytics.js',
			array( 'jquery', 'chartjs' ),
			MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION,
			true
		);

		// Pass data to JS.
		$wc_currency = get_woocommerce_currency_symbol();
		wp_localize_script(
			'wps-bfw-analytics-js',
			'wps_bfw_analytics',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'wps_bfw_analytics_nonce' ),
				'currency'  => html_entity_decode( $wc_currency ),
				'is_pro'    => self::is_pro_active() ? '1' : '0',
				'i18n'      => array(
					'loading'      => esc_html__( 'Loading…', 'mwb-bookings-for-woocommerce' ),
					'no_data'      => esc_html__( 'No data for selected period.', 'mwb-bookings-for-woocommerce' ),
					'error'        => esc_html__( 'Failed to load analytics data.', 'mwb-bookings-for-woocommerce' ),
					'export_error' => esc_html__( 'No data to export.', 'mwb-bookings-for-woocommerce' ),
				),
			)
		);
	}

	/**
	 * AJAX handler — validates nonce then returns JSON analytics data.
	 */
	public function ajax_get_analytics_data() {
		// Use soft nonce check so a failure returns JSON, not wp_die() HTML.
		if ( ! check_ajax_referer( 'wps_bfw_analytics_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed. Please reload the page.' ), 403 );
			return;
		}

		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => 'Permission denied.' ), 403 );
			return;
		}

		$date_from = isset( $_POST['date_from'] ) ? sanitize_text_field( wp_unslash( $_POST['date_from'] ) ) : gmdate( 'Y-m-01' );
		$date_to   = isset( $_POST['date_to'] ) ? sanitize_text_field( wp_unslash( $_POST['date_to'] ) ) : gmdate( 'Y-m-d' );

		// Basic date validation.
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date_from ) ) {
			$date_from = gmdate( 'Y-m-01' );
		}
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date_to ) ) {
			$date_to = gmdate( 'Y-m-d' );
		}

		try {
			$data = $this->get_analytics_data( $date_from, $date_to );
			// Flush any stray output before sending JSON.
			if ( ob_get_level() ) {
				ob_clean();
			}
			wp_send_json_success( $data );
		} catch ( \Throwable $e ) {
			wp_send_json_error( array( 'message' => 'Server error: ' . $e->getMessage() ) );
		}
	}

	/**
	 * Core analytics computation.
	 *
	 * @param string $date_from Y-m-d start date (inclusive).
	 * @param string $date_to   Y-m-d end date (inclusive).
	 * @return array
	 */
	public function get_analytics_data( $date_from, $date_to ) {
		$cache_key = 'wps_bfw_analytics_' . md5( $date_from . '_' . $date_to . '_' . ( self::is_pro_active() ? 'pro' : 'free' ) );
		$cached    = get_transient( $cache_key );
		if ( false !== $cached ) {
			return $cached;
		}

		// Fetch all booking orders in date range.
		// Use meta_key + meta_value (standard WP_Query args passed through by wc_get_orders).
		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'date_created' => $date_from . '...' . $date_to,
				'meta_key'     => 'mwb_order_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'   => 'booking', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'meta_compare' => '=',
			)
		);

		$total_bookings    = count( $orders );
		$total_revenue     = 0.0;
		$status_counts     = array();
		$revenue_by_product = array();
		$bookings_by_day   = array();
		$peak_days_count   = array( 'Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0 );
		$top_products      = array();

		// Pro data containers.
		$people_type_totals = array();
		$peak_hours         = array_fill( 0, 24, 0 );
		$occupancy_data     = array(); // product_id => array( booked, capacity ).

		foreach ( $orders as $order ) {
			$status = $order->get_status();
			$status_counts[ $status ] = ( $status_counts[ $status ] ?? 0 ) + 1;

			// Revenue from completed orders only.
			if ( 'completed' === $status ) {
				$total_revenue += (float) $order->get_total();
			}

			// Bookings over time (by day).
			$created_date  = $order->get_date_created();
			$day_key       = $created_date ? $created_date->date( 'Y-m-d' ) : null;
			$day_of_week   = $created_date ? $created_date->date( 'D' ) : null;

			if ( $day_key ) {
				$bookings_by_day[ $day_key ] = ( $bookings_by_day[ $day_key ] ?? 0 ) + 1;
			}
			if ( $day_of_week && array_key_exists( $day_of_week, $peak_days_count ) ) {
				$peak_days_count[ $day_of_week ]++;
			}

			// Per-product revenue.
			foreach ( $order->get_items() as $item ) {
				$product_id = $item->get_product_id();
				if ( ! $product_id ) {
					continue;
				}
				$product_name = $item->get_name();

				if ( ! isset( $revenue_by_product[ $product_id ] ) ) {
					$revenue_by_product[ $product_id ] = array(
						'name'     => $product_name,
						'revenue'  => 0.0,
						'bookings' => 0,
					);
				}
				if ( 'completed' === $status ) {
					// Booking products store price dynamically; item->get_total() may be 0
					// if the product's base price is 0 in the DB. Fall back to the full
					// order total (safe for booking orders which have one product per order).
					$item_total = (float) $item->get_total();
					if ( 0.0 === $item_total ) {
						$item_total = (float) $order->get_total();
					}
					$revenue_by_product[ $product_id ]['revenue'] += $item_total;
				}
				$revenue_by_product[ $product_id ]['bookings']++;

				// Pro: slot occupancy.
				if ( self::is_pro_active() ) {
					$capacity = (int) get_post_meta( $product_id, 'mwb_mbfw_booking_max_limit', true );
					if ( $capacity > 0 ) {
						if ( ! isset( $occupancy_data[ $product_id ] ) ) {
							$occupancy_data[ $product_id ] = array( 'booked' => 0, 'capacity' => $capacity, 'name' => $product_name );
						}
						$occupancy_data[ $product_id ]['booked']++;
					}
				}

				// Pro: peak hours from booking slot meta.
				if ( self::is_pro_active() ) {
					$slot = $item->get_meta( '_wps_booking_slot' );
					if ( $slot ) {
						// Slot format: "HH:MM - HH:MM" or "HH:MM".
						$parts = explode( ' - ', $slot );
						$time  = trim( $parts[0] );
						$hour  = (int) explode( ':', $time )[0];
						if ( $hour >= 0 && $hour < 24 ) {
							$peak_hours[ $hour ]++;
						}
					}
				}
			}

			// Pro: people type breakdown.
			if ( self::is_pro_active() ) {
				$people_types = $order->get_meta( 'bfwp_people_type_listings' );
				if ( is_array( $people_types ) ) {
					foreach ( $people_types as $type_id => $qty ) {
						$term = get_term( (int) $type_id, 'mwb_booking_people' );
						$label = ( $term && ! is_wp_error( $term ) ) ? $term->name : 'Type ' . $type_id;
						$people_type_totals[ $label ] = ( $people_type_totals[ $label ] ?? 0 ) + (int) $qty;
					}
				}
			}
		}

		// Compute cancellation rate.
		$cancelled_count    = $status_counts['cancelled'] ?? 0;
		$cancellation_rate  = $total_bookings > 0 ? round( ( $cancelled_count / $total_bookings ) * 100, 1 ) : 0;
		$pending_count      = ( $status_counts['pending'] ?? 0 ) + ( $status_counts['on-hold'] ?? 0 );

		// Sort bookings_by_day by date.
		ksort( $bookings_by_day );

		// Top products — sort by bookings desc, limit 10.
		usort(
			$revenue_by_product,
			function( $a, $b ) {
				return $b['bookings'] - $a['bookings'];
			}
		);
		$top_products = array_values( array_slice( $revenue_by_product, 0, 10 ) );

		// Revenue by product — top 8 for chart.
		$sorted_revenue = $revenue_by_product;
		usort(
			$sorted_revenue,
			function( $a, $b ) {
				return $b['revenue'] <=> $a['revenue'];
			}
		);
		$chart_product_revenue = array_slice( $sorted_revenue, 0, 8 );

		$result = array(
			'total_bookings'        => $total_bookings,
			'total_revenue'         => round( $total_revenue, 2 ),
			'cancellation_rate'     => $cancellation_rate,
			'pending_count'         => $pending_count,
			'status_counts'         => $status_counts,
			'revenue_by_product'    => $chart_product_revenue,
			'bookings_over_time'    => $bookings_by_day,
			'peak_days'             => $peak_days_count,
			'top_products'          => $top_products,
		);

		// Pro data.
		if ( self::is_pro_active() ) {
			// Average occupancy rate.
			$occ_rates = array();
			foreach ( $occupancy_data as $occ ) {
				if ( $occ['capacity'] > 0 ) {
					$occ_rates[] = ( $occ['booked'] / $occ['capacity'] ) * 100;
				}
			}
			$avg_occupancy = count( $occ_rates ) > 0 ? round( array_sum( $occ_rates ) / count( $occ_rates ), 1 ) : 0;

			// Average people per booking.
			$total_people = array_sum( $people_type_totals );
			$avg_people   = $total_bookings > 0 ? round( $total_people / $total_bookings, 1 ) : 0;

			// Peak hour.
			$peak_hour_index = array_search( max( $peak_hours ), $peak_hours, true );
			$peak_hour_label = sprintf( '%02d:00', $peak_hour_index );

			// Top people type.
			arsort( $people_type_totals );
			$top_people_type = count( $people_type_totals ) > 0 ? array_key_first( $people_type_totals ) : __( 'N/A', 'mwb-bookings-for-woocommerce' );

			// Occupancy chart data.
			$occ_labels  = array();
			$occ_values  = array();
			foreach ( array_slice( $occupancy_data, 0, 8 ) as $occ ) {
				$occ_labels[] = $occ['name'];
				$occ_values[] = $occ['capacity'] > 0 ? round( ( $occ['booked'] / $occ['capacity'] ) * 100, 1 ) : 0;
			}

			$result['pro'] = array(
				'avg_occupancy'       => $avg_occupancy,
				'avg_people'          => $avg_people,
				'peak_hour'           => $peak_hour_label,
				'top_people_type'     => $top_people_type,
				'peak_hours'          => $peak_hours,
				'people_type_totals'  => $people_type_totals,
				'occupancy_labels'    => $occ_labels,
				'occupancy_values'    => $occ_values,
			);
		}

		set_transient( $cache_key, $result, self::CACHE_TTL );
		return $result;
	}
}
