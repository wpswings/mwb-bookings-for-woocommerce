<?php
/**
 * Availability Check Class.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Availability Class For MWB Booking Plugin.
 *
 * This class defines the global functions to be used anywhere in the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MWB_Woocommerce_Booking_Availability {

	/**
	 * Instance of the Availability class.
	 *
	 * @var [type]
	 */
	public static $availability_instance;

	/**
	 * Defining a Booking product.
	 *
	 * @var [type]
	 */
	public $booking_product;

	/**
	 * ID of the Booking Product.
	 *
	 * @var [int]
	 */
	public $booking_product_id;

	/**
	 * Custructor Function.
	 */
	private function __construct() {

		global $product;

		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			$this->booking_product    = $product;
			$this->booking_product_id = $product->get_id();
		}

		self::$availability_instance = $this;
	}

	/**
	 * Get the Availability Instance
	 *
	 * @return obj
	 */
	public static function get_availability_instance() {

		if ( null === self::$availability_instance ) {
			self::$availability_instance = new self();
		}

		return self::$availability_instance;
	}

	/**
	 * Calculate dates between start and end date.
	 *
	 * @param [date] $first Start Date.
	 * @param [date] $last Last Date.
	 * @param string $step Step to increment the date.
	 * @param string $output_format DAte format.
	 * @return array
	 */
	public function availability_date_range( $first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

		$dates   = array();
		$current = strtotime( $first );
		$last    = strtotime( $last );

		while ( $current <= $last ) {
			$dates[ gmdate( $output_format, $current ) ] = array();

			$current = strtotime( $step, $current );
		}

		return $dates;
	}

	/**
	 * Set Global Availability Rules on the Slots
	 *
	 * @param int   $product_id ID of the product.
	 * @param array $slots      Array of the slots.
	 *
	 * @return array
	 */
	public function check_product_global_availability( $product_id, $slots ) {

		$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
		$availability_count = get_option( 'mwb_global_availability_rules_count', 0 );

		$count = 0;

		while ( $count < $availability_count ) {
			if ( ! empty( $slots ) ) {
				foreach ( $slots as $date => $slot ) {
					$rule_switch        = ! empty( $availability_rules['rule_switch'][ $count ] ) ? $availability_rules['rule_switch'][ $count ] : 'off';
					$rule_type          = ! empty( $availability_rules['rule_type'][ $count ] ) ? $availability_rules['rule_type'][ $count ] : '';
					$from               = ! empty( $availability_rules['rule_range_from'][ $count ] ) ? $availability_rules['rule_range_from'][ $count ] : '';
					$to                 = ! empty( $availability_rules['rule_range_to'][ $count ] ) ? $availability_rules['rule_range_to'][ $count ] : '';
					$rule_bookable      = ! empty( $availability_rules['rule_bookable'][ $count ] ) ? $availability_rules['rule_bookable'][ $count ] : '';
					$rule_weekdays      = ! empty( $availability_rules['rule_weekdays'][ $count ] ) ? $availability_rules['rule_weekdays'][ $count ] : '';
					$rule_weekdays_book = ! empty( $availability_rules['rule_weekdays_book'][ $count ] ) ? $availability_rules['rule_weekdays_book'][ $count ] : array();

					if ( ! empty( $rule_switch ) && 'on' === $rule_switch ) {
						if ( ! empty( $rule_type ) && 'specific' === $rule_type ) {
							$from = gmdate( 'Y-m-d', strtotime( $from ) );
							$to   = gmdate( 'Y-m-d', strtotime( $to ) );
							if ( array_key_exists( $from, $slots ) && array_key_exists( $to, $slots ) ) {
								$arr = $this->availability_date_range( $from, $to, '+1 day', 'Y-m-d' );
								if ( ! empty( $rule_weekdays ) && 'off' === $rule_weekdays && ! empty( $rule_bookable ) ) {
									foreach ( $arr as $k => $v ) {

										if ( $k === $date ) {
											foreach ( $slot as $key => $value ) {
												$slots[ $date ][ $key ]['book'] = $rule_bookable;
											}
										} else {
											continue;
										}
									}
								} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
									if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
										foreach ( $arr as $k => $v ) {

											if ( $k === $date ) {
												$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
												$status = $rule_weekdays_book[ $day ];
												if ( 'no-change' !== $status ) {
													foreach ( $slot as $key => $value ) {
														$slots[ $date ][ $key ]['book'] = $status;
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							} elseif ( array_key_exists( $from, $slots ) && ! array_key_exists( $to, $slots ) ) {
								$to  = array_key_last( $slots );
								$arr = $this->availability_date_range( $from, $to, '+1 day', 'Y-m-d' );
								if ( ! empty( $rule_weekdays ) && 'off' === $rule_weekdays && ! empty( $rule_bookable ) ) {
									foreach ( $arr as $k => $v ) {

										if ( $k === $date ) {
											foreach ( $slot as $key => $value ) {
												$slots[ $date ][ $key ]['book'] = $rule_bookable;
											}
										} else {
											continue;
										}
									}
								} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
									if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
										foreach ( $arr as $k => $v ) {

											if ( $k === $date ) {
												$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
												$status = $rule_weekdays_book[ $day ];
												if ( 'no-change' !== $status ) {
													foreach ( $slot as $key => $value ) {
														$slots[ $date ][ $key ]['book'] = $status;
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							} elseif ( ! array_key_exists( $from, $slots ) && array_key_exists( $to, $slots ) ) {
								$from = array_key_first( $slots );
								$arr  = $this->availability_date_range( $from, $to, '+1 day', 'Y-m-d' );
								if ( ! empty( $rule_weekdays ) && 'off' === $rule_weekdays && ! empty( $rule_bookable ) ) {
									foreach ( $arr as $k => $v ) {

										if ( $k === $date ) {
											foreach ( $slot as $key => $value ) {
												$slots[ $date ][ $key ]['book'] = $rule_bookable;
											}
										} else {
											continue;
										}
									}
								} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
									if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
										foreach ( $arr as $k => $v ) {

											if ( $k === $date ) {
												$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
												$status = $rule_weekdays_book[ $day ];
												if ( 'no-change' !== $status ) {
													foreach ( $slot as $key => $value ) {
														$slots[ $date ][ $key ]['book'] = $status;
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							}
						} elseif ( ! empty( $rule_type ) && 'generic' === $rule_type ) {
							$date_month = lcfirst( gmdate( 'M', strtotime( $date ) ) );
							$date_month = $this->availability_numeric_monthdays( $date_month );
							$from       = $this->availability_numeric_monthdays( $from );
							$to         = $this->availability_numeric_monthdays( $to );
							if ( $date_month >= $from && $date_month <= $to ) {
								if ( ! empty( $rule_weekdays ) && 'off' === $rule_weekdays && ! empty( $rule_bookable ) ) {
									$status = $rule_bookable;
									foreach ( $slot as $k => $v ) {
										$slots[ $date ][ $k ]['book'] = $status;
									}
								} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
									if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
										$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
										$status = $rule_weekdays_book[ $day ];
										if ( 'no-change' !== $status ) {
											foreach ( $slot as $key => $value ) {
												$slots[ $date ][ $key ]['book'] = $status;
											}
										}
									}
								}
							}
						}
					}
				}
				update_post_meta( $product_id, 'mwb_booking_product_slots', $slots );
				$count++;
			}
		}

		return $slots;
	}

	/**
	 * Set neumeric value to the months.
	 *
	 * @param [string] $month three letter textual representation of the month.
	 * @return $numeric_month
	 */
	public function availability_numeric_monthdays( $month ) {

		$numeric_month = 0;

		switch ( $month ) {
			case 'jan':
				$numeric_month = 1;
				break;
			case 'feb':
				$numeric_month = 2;
				break;
			case 'mar':
				$numeric_month = 3;
				break;
			case 'apr':
				$numeric_month = 4;
				break;
			case 'may':
				$numeric_month = 5;
				break;
			case 'jun':
				$numeric_month = 6;
				break;
			case 'jul':
				$numeric_month = 7;
				break;
			case 'aug':
				$numeric_month = 8;
				break;
			case 'sep':
				$numeric_month = 9;
				break;
			case 'oct':
				$numeric_month = 10;
				break;
			case 'nov':
				$numeric_month = 11;
				break;
			case 'dec':
				$numeric_month = 12;
				break;
		}
		return $numeric_month;
	}

	/**
	 * Check the product availability according to the product settings
	 *
	 * @param [int]   $product_id ID of the product.
	 * @param [array] $slots      Array of the slots.
	 *
	 * @return array
	 */
	public function check_product_setting_availability( $product_id, $slots ) {

		$prod_settings = get_post_meta( $product_id );

		$not_allowed_days = ! empty( $prod_settings['mwb_booking_not_allowed_days'][0] ) ? maybe_unserialize( $prod_settings['mwb_booking_not_allowed_days'][0] ) : array();

		if ( ! empty( $slots ) ) {
			foreach ( $slots as $date => $slot ) {
				$day = lcfirst( gmdate( 'l', strtotime( $date ) ) );
				if ( in_array( $day, $not_allowed_days, true ) ) {
					foreach ( $slot as $key => $value ) {
						$slots[ $date ][ $key ]['book'] = 'non-bookable';
					}
				}
			}
		}
		return $slots;
	}

	/**
	 * Check the availability for the created bookings
	 * Exclude the Expired Booking or Cancelled Bookings
	 *
	 * @param int   $product_id ID of the product.
	 * @param array $slots      Array of the slots.
	 *
	 *  @return array
	 */
	public function manage_avaialability_acc_to_created_bookings( $product_id, $slots ) {

		$args = array(
			'numberposts' => -1,
			'post_type'   => 'mwb_cpt_booking',
			'post_status' => 'publish',
			'meta_query'  => array(    // @codingStandardsIgnoreLine
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
					array(
						'key'     => 'mwb_booking_status',
						'value'   => 'cancelled',
						'compare' => '!=',
					),
				),
			),
		);

		$bookings     = get_posts( $args );
		$max_bookings = get_post_meta( $product_id, 'mwb_max_bookings_per_unit', true );

		foreach ( $bookings as $booking => $obj ) {

			$booking_id   = $obj->ID;
			$booking_meta = get_post_meta( $booking_id, 'mwb_meta_data', true );
			$p_id         = $booking_meta['product_id'];
			$start_date   = gmdate( 'Y-m-d', strtotime( $booking_meta['start_date'] ) );
			$start_time   = gmdate( 'H:i:s', $booking_meta['start_timestamp'] );
			$end_date     = isset( $booking_meta['end_date'] ) ? gmdate( 'Y-m-d', strtotime( $booking_meta['end_date'] ) ) : gmdate( 'Y-m-d', $booking_meta['end_timestamp'] );
			$end_time     = gmdate( 'H:i:s', $booking_meta['end_timestamp'] );
			$duration     = $booking_meta['duration'];
			$unit_dur     = $booking_meta['unit_dur'];

			$unit_dur_input = $booking_meta['unit_dur_input'];

			if ( $p_id === $product_id ) {
				if ( array_key_exists( $start_date, $slots ) ) {
					foreach ( $slots as $date => $slot ) {
						$date_arr = $this->availability_date_range( $start_date, $end_date, '+1 day', 'Y-m-d' );
						if ( array_key_exists( $date, $date_arr ) ) {
							if ( 'day' === $unit_dur ) {
								foreach ( $slot as $k => $v ) {
									$slots[ $date ][ $k ]['booking_count'] += 1;
								}
							} elseif ( 'hour' === $unit_dur || 'minute' === $unit_dur ) {
								foreach ( $slot as $k => $v ) {
									$arr = $this->extract_time_slot( $start_time, $end_time, $date, $unit_dur, $unit_dur_input );
									if ( in_array( $k, $arr ) ) {    // @codingStandardsIgnoreLine
										$slots[ $date ][ $k ]['booking_count'] += 1;
									}
								}
							}
						}
					}
				}
			}

			foreach ( $slots as $date => $slot ) {
				foreach ( $slot as $k => $v ) {
					if ( $max_bookings <= $v['booking_count'] ) {
						$slots[ $date ][ $k ]['book'] = 'non-bookable';
					}
				}
			}
		}

		return $slots;
	}

	/**
	 * Prepare another array of the slots to compare the slots array.
	 *
	 * @param [type] $start_time Start Time of the booking.
	 * @param [type] $end_time   End time of the booking.
	 * @param [type] $date       Date of the booking.
	 * @param [type] $unit_dur   Unit Duration of the Booking unit.
	 * @param [type] $unit_dur_input Unit duration input of the booking unit.
	 * @return array
	 */
	public function extract_time_slot( $start_time, $end_time, $date, $unit_dur, $unit_dur_input ) {

		$arr = array();

		$start = strtotime( $start_time, strtotime( $date ) );
		$end   = strtotime( '+' . $unit_dur_input . ' ' . $unit_dur, $start );

		while ( $end <= strtotime( $end_time, strtotime( $date ) ) ) {

			$arr[] = gmdate( 'H:i:s', $start ) . '-' . gmdate( 'H:i:s', $end );
			$start = $end;
			$end   = strtotime( '+' . $unit_dur_input . ' ' . $unit_dur, $start );
		}
		return $arr;
	}

	/**
	 * Fetches the unavailable dates from the slots array
	 *
	 * @param array $slot_arr Array of the slots.
	 *
	 * @return array
	 */
	public function fetch_unavailable_dates( $slot_arr ) {

		$unavail_dates = array();
		if ( ! empty( $slot_arr ) && is_array( $slot_arr ) ) {
			foreach ( $slot_arr as $date => $slot ) {
				$count = count( $slot );
				foreach ( $slot as $k => $v ) {
					if ( 'non-bookable' === $v['book'] ) {
						$count--;
					}
				}
				if ( 0 === $count ) {
					$unavail_dates[] = $date;
				}
			}
		}
		return $unavail_dates;
	}
}



