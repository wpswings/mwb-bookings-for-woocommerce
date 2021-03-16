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

		// echo '<pre>'; print_r( $product ); echo '</pre>';

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
	 * Check product availability
	 *
	 * @param [date] $start_date Start date of the booking
	 * @param [date] $end_date   End Date for the booking
	 * @return void
	 */
	public function check_product_availability( $start_date , $end_date) {

		$dates = $this->date_range( $start_date, $end_date, '+1 day', 'y-m-d' );
		foreach( $dates as $key => $value ) {
			
		}
		
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

	public function check_advance_booking_availability( $product_id ) {

		$prod_settings      = get_post_meta( $this->booking_product_id );
		$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
		$availability_count = get_option( 'mwb_global_availability_rules_count', 0 );

		$count = 0;
		$slots = get_post_meta( $product_id, 'mwb_booking_product_slots', true );
		// print_r($availability_count);

		while ( $count < $availability_count ) {	
			// print_r($slots);
			// die('123');
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
									// $arr[ $k ] = $rule_bookable;
									if ( $k == $date ) {
										foreach ( $slot as $key => $value ) {
											$slots[ $date ][ $key ] = $rule_bookable;
										}
									} else {
										continue;
									}
								}
							} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
								if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
									foreach ( $arr as $k => $v ) {
										// $arr[ $k ] = $rule_bookable;
										if ( $k === $date ) {
											$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
											$status = $rule_weekdays_book[ $day ];
											echo $status;
											if ( 'no-change' !== $status ) {
												foreach ( $slot as $key => $value ) {
													$slots[ $date ][ $key ] = $status;
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
									// $arr[ $k ] = $rule_bookable;
									if ( $k == $date ) {
										foreach ( $slot as $key => $value ) {
											$slots[ $date ][ $key ] = $rule_bookable;
										}
									} else {
										continue;
									}
								}
							} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
								if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
									foreach ( $arr as $k => $v ) {
										// $arr[ $k ] = $rule_bookable;
										if ( $k === $date ) {
											$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
											$status = $rule_weekdays_book[ $day ];
											echo $status;
											if ( 'no-change' !== $status ) {
												foreach ( $slot as $key => $value ) {
													$slots[ $date ][ $key ] = $status;
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
									// $arr[ $k ] = $rule_bookable;
									if ( $k == $date ) {
										foreach ( $slot as $key => $value ) {
											$slots[ $date ][ $key ] = $rule_bookable;
										}
									} else {
										continue;
									}
								}
							} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
								if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
									foreach ( $arr as $k => $v ) {
										// $arr[ $k ] = $rule_bookable;
										if ( $k === $date ) {
											$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
											$status = $rule_weekdays_book[ $day ];
											echo $status;
											if ( 'no-change' !== $status ) {
												foreach ( $slot as $key => $value ) {
													$slots[ $date ][ $key ] = $status;
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
									$slots[ $date ][ $k ] = $status;
								}
							} elseif ( ! empty( $rule_weekdays ) && 'on' === $rule_weekdays ) {
								if ( ! empty( $rule_weekdays_book ) && is_array( $rule_weekdays_book ) ) {
									$day    = lcfirst( gmdate( 'l', strtotime( $date ) ) );
									$status = $rule_weekdays_book[ $day ];
									if ( 'no-change' !== $status ) {
										foreach ( $slot as $key => $value ) {
											$slots[ $date ][ $key ] = $status;
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

		return $slots;
	}

	public function availability_numeric_monthdays ( $month ) {

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


}



