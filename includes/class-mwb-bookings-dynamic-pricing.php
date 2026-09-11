<?php
/**
 * Dynamic Pricing feature for mwb_booking products.
 *
 * Allows per-product rules that mark up the General Cost on specific dates
 * using either a percentage or a fixed amount.
 *
 * Admin UI   : injects a rule builder into the Cost tab via the
 *              mwb_mbfw_booking_costs_meta_section_add_fields action.
 * Save       : hooks mwb_mbfw_save_product_meta_data to sanitise and persist rules.
 * Cart price : hooks mwb_mbfw_vary_product_unit_price (applied after all
 *              existing per-day / global-rule filters, before people multiply).
 * AJAX price : hooks mbfw_ajax_load_total_booking_charge_individually to adjust
 *              the already-computed general_cost entry in the charges breakdown.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/includes
 * @since      3.12.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Class Mwb_Bookings_Dynamic_Pricing
 */
class Mwb_Bookings_Dynamic_Pricing {

	/**
	 * Constructor — registers all hooks.
	 * Admin-only hooks are guarded with is_admin(); price hooks run everywhere.
	 */
	public function __construct() {
		if ( is_admin() ) {
			// Render the UI at the bottom of the Cost tab panel.
			add_action( 'mwb_mbfw_booking_costs_meta_section_add_fields', array( $this, 'render_ui' ) );

			// Piggyback on the existing save filter to persist our meta.
			add_filter( 'mwb_mbfw_save_product_meta_data', array( $this, 'save_meta' ), 10, 2 );

			// Load flatpickr + our JS on the product edit screen.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		}

		// Price adjustment hooks — must fire on frontend, AJAX, and REST too.
		add_filter( 'mwb_mbfw_vary_product_unit_price', array( $this, 'apply_to_cart_unit_price' ), 10, 6 );
		add_filter( 'mbfw_ajax_load_total_booking_charge_individually', array( $this, 'apply_to_ajax_charges' ), 10, 5 );
	}

	// =========================================================================
	// Admin UI
	// =========================================================================

	/**
	 * Render the Enable Dynamic Pricing checkbox and repeatable rule rows
	 * inside the Cost tab. Hidden when General Cost itself is hidden.
	 *
	 * @param int $product_id Current product ID.
	 */
	public function render_ui( $product_id ) {
		$hide_general = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_general_cost_hide', true );
		$enabled      = wps_booking_get_meta_data( $product_id, '_dynamic_pricing_enabled', true );
		$rules        = get_post_meta( $product_id, '_dynamic_pricing_rules', true );
		if ( ! is_array( $rules ) ) {
			$rules = array();
		}

		// When General Cost is hidden, the whole section is irrelevant.
		$section_style = ( 'yes' === $hide_general ) ? 'display:none;' : '';
		?>
		<div id="mwb-dp-section" style="<?php echo esc_attr( $section_style ); ?>">
			<hr style="border-top:1px solid #eee; margin:12px 0 6px;">
			<?php
			woocommerce_wp_checkbox( array(
				'id'          => '_dynamic_pricing_enabled',
				'value'       => $enabled,
				'label'       => __( 'Enable Dynamic Pricing', 'mwb-bookings-for-woocommerce' ),
				'description' => __( 'Apply a percentage or fixed markup to General Cost on specific dates.', 'mwb-bookings-for-woocommerce' ),
				'desc_tip'    => true,
			) );
			?>
			<div id="mwb-dp-rules-wrap" style="<?php echo ( 'yes' === $enabled ) ? '' : 'display:none;'; ?>padding:0 12px 12px;">
				<div id="mwb-dp-rules-list">
					<?php foreach ( $rules as $i => $rule ) : ?>
						<?php $this->render_rule_row( $i, $rule ); ?>
					<?php endforeach; ?>
				</div>
				<button type="button" id="mwb-dp-add-rule" class="button" style="margin-top:10px;">
					<?php esc_html_e( '+ Add Rule', 'mwb-bookings-for-woocommerce' ); ?>
				</button>
			</div>
		</div>

		<?php /* Template row — cloned by JS; __IDX__ is replaced with the real index. */ ?>
		<script type="text/html" id="mwb-dp-rule-template">
			<?php $this->render_rule_row( '__IDX__', array() ); ?>
		</script>
		<?php
	}

	/**
	 * Output a single dynamic pricing rule row.
	 *
	 * @param int|string $index Row index (integer) or JS placeholder string.
	 * @param array      $rule  Saved rule data: {dates[], type, value}.
	 */
	private function render_rule_row( $index, array $rule ) {
		$dates_str = isset( $rule['dates'] ) ? implode( ', ', (array) $rule['dates'] ) : '';
		$type      = isset( $rule['type'] ) ? $rule['type'] : 'percentage';
		$value     = isset( $rule['value'] ) ? $rule['value'] : '';

		$pct_hint = ( 'percentage' === $type ) ? '(1–100 %)' : '';
		$val_min  = ( 'percentage' === $type ) ? '1' : '0';
		$val_max  = ( 'percentage' === $type ) ? '100' : '';
		?>
		<div class="mwb-dp-rule" style="margin-top:8px; padding:8px; background:#f9f9f9; border:1px solid #e2e2e2;">
			<table style="border-collapse:collapse;">
				<thead>
					<tr>
						<th style="text-align:left; padding:0 12px 4px 0; font-weight:600; white-space:nowrap;">
							<?php esc_html_e( 'Dates (YYYY-MM-DD)', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
						<th style="text-align:left; padding:0 12px 4px 0; font-weight:600; white-space:nowrap;">
							<?php esc_html_e( 'Price Type', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
						<th style="text-align:left; padding:0 12px 4px 0; font-weight:600; white-space:nowrap;">
							<?php esc_html_e( 'Value', 'mwb-bookings-for-woocommerce' ); ?>
						</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding:0 12px 0 0; vertical-align:top;">
							<input
								type="text"
								name="_dynamic_pricing_rules[<?php echo esc_attr( $index ); ?>][dates]"
								class="mwb-dp-dates"
								placeholder="<?php esc_attr_e( '2026-12-25, 2027-01-01', 'mwb-bookings-for-woocommerce' ); ?>"
								value="<?php echo esc_attr( $dates_str ); ?>"
								style="width:220px;"
							/>
						</td>
						<td style="padding:0 12px 0 0; vertical-align:top;">
							<select
								name="_dynamic_pricing_rules[<?php echo esc_attr( $index ); ?>][type]"
								class="mwb-dp-type"
								style="width:130px;"
							>
								<option value="percentage" <?php selected( $type, 'percentage' ); ?>>
									<?php esc_html_e( 'Percentage', 'mwb-bookings-for-woocommerce' ); ?>
								</option>
								<option value="fixed" <?php selected( $type, 'fixed' ); ?>>
									<?php esc_html_e( 'Fixed', 'mwb-bookings-for-woocommerce' ); ?>
								</option>
							</select>
						</td>
						<td style="padding:0 12px 0 0; vertical-align:top;">
							<input
								type="number"
								name="_dynamic_pricing_rules[<?php echo esc_attr( $index ); ?>][value]"
								class="mwb-dp-value"
								value="<?php echo esc_attr( $value ); ?>"
								min="<?php echo esc_attr( $val_min ); ?>"
								<?php if ( $val_max ) { echo 'max="' . esc_attr( $val_max ) . '"'; } ?>
								step="0.01"
								style="width:80px;"
							/>
							<span class="mwb-dp-value-hint" style="font-size:11px; color:#666; margin-left:4px;">
								<?php echo esc_html( $pct_hint ); ?>
							</span>
						</td>
						<td style="vertical-align:top;">
							<button type="button" class="button mwb-dp-remove-rule">
								<?php esc_html_e( 'Remove', 'mwb-bookings-for-woocommerce' ); ?>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	// =========================================================================
	// Save
	// =========================================================================

	/**
	 * Sanitize and persist dynamic pricing meta on product save.
	 *
	 * Adds _dynamic_pricing_enabled to the $product_meta_data array so it is
	 * saved by the existing save loop. Saves _dynamic_pricing_rules separately
	 * (it is a serialised array, not a flat string).
	 *
	 * @param array $product_meta_data Flat key→value array built by the save handler.
	 * @param int   $product_id        Product being saved.
	 * @return array
	 */
	public function save_meta( $product_meta_data, $product_id ) {
		// Checkbox: absent from POST when unchecked → treat as empty string ('').
		$product_meta_data['_dynamic_pricing_enabled'] = isset( $_POST['_dynamic_pricing_enabled'] ) ? 'yes' : '';

		// Rules array.
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		// (nonce already verified by mbfw_save_custom_product_meta_boxes_data before this filter runs)
		$raw_rules = isset( $_POST['_dynamic_pricing_rules'] ) && is_array( $_POST['_dynamic_pricing_rules'] )
			? wp_unslash( $_POST['_dynamic_pricing_rules'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			: array();
		// phpcs:enable

		$sanitized = array();
		foreach ( $raw_rules as $rule ) {
			// Dates arrive as a comma-separated string from the flatpickr input.
			$dates_raw = isset( $rule['dates'] ) ? sanitize_text_field( wp_unslash( $rule['dates'] ) ) : '';
			$dates     = array_map( 'trim', explode( ',', $dates_raw ) );
			// Keep only tokens that look like a valid Y-m-d date.
			$dates = array_values( array_filter( $dates, function ( $d ) {
				$ts = strtotime( $d );
				return $ts && gmdate( 'Y-m-d', $ts ) === $d;
			} ) );

			// Skip rules that have no valid dates — require at least one.
			if ( empty( $dates ) ) {
				continue;
			}

			$type  = ( isset( $rule['type'] ) && 'fixed' === $rule['type'] ) ? 'fixed' : 'percentage';
			$value = isset( $rule['value'] ) ? (float) $rule['value'] : 0;

			// Server-side range enforcement (mirrors client-side min/max).
			if ( 'percentage' === $type ) {
				$value = max( 1.0, min( 100.0, $value ) );
			} else {
				$value = max( 0.0, $value );
			}

			$sanitized[] = array(
				'dates' => $dates,
				'type'  => $type,
				'value' => $value,
			);
		}

		update_post_meta( $product_id, '_dynamic_pricing_rules', $sanitized );

		return $product_meta_data;
	}

	// =========================================================================
	// Price Calculation
	// =========================================================================

	/**
	 * Find the first rule whose `dates` array contains any of the customer's
	 * booking dates (after normalising both sides to Y-m-d).
	 *
	 * @param int   $product_id    Product ID.
	 * @param array $booking_dates Raw date strings in any parseable format.
	 * @return array|null Matching rule or null when nothing applies.
	 */
	private function get_matching_rule( $product_id, array $booking_dates ) {
		if ( 'yes' !== wps_booking_get_meta_data( $product_id, '_dynamic_pricing_enabled', true ) ) {
			return null;
		}

		$rules = get_post_meta( $product_id, '_dynamic_pricing_rules', true );
		if ( empty( $rules ) || ! is_array( $rules ) ) {
			return null;
		}

		if ( empty( $booking_dates ) ) {
			return null;
		}

		// Normalise all incoming dates to Y-m-d for a reliable string comparison.
		$normalised = array();
		foreach ( $booking_dates as $d ) {
			$ts = strtotime( (string) $d );
			if ( $ts ) {
				$normalised[] = gmdate( 'Y-m-d', $ts );
			}
		}
		$normalised = array_unique( $normalised );

		foreach ( $rules as $rule ) {
			if ( empty( $rule['dates'] ) ) {
				continue;
			}
			foreach ( $normalised as $booking_date ) {
				if ( in_array( $booking_date, $rule['dates'], true ) ) {
					return $rule;
				}
			}
		}

		return null;
	}

	/**
	 * Hook: mwb_mbfw_vary_product_unit_price   (cart and checkout)
	 *
	 * At this point $unit_price = per_unit_cost × unit_count, after all
	 * existing modifiers (per-day pricing rules, global-rule filters).
	 *
	 * For single-calendar day bookings (multi-date selection), each selected
	 * date is priced independently: only dates that match a rule are marked up.
	 * For all other booking types, the first matching rule is applied to the
	 * entire unit price.
	 *
	 * Percentage formula: per_unit + per_unit × (value/100)
	 * Fixed formula:      per_unit + value         (value is per booking unit)
	 *
	 * @param float  $unit_price       Computed total unit cost (per-unit × units).
	 * @param array  $custom_cart_data Booking values stored in cart item.
	 * @param object $cart_object      WC Cart instance.
	 * @param array  $cart             Cart item array.
	 * @param array  $booking_dates    Single-cal date array (may be empty for dual-cal).
	 * @param float  $unit             Number of booking units (days or hours).
	 * @return float Adjusted total unit cost.
	 */
	public function apply_to_cart_unit_price( $unit_price, $custom_cart_data, $cart_object, $cart, $booking_dates, $unit ) {
		$product_id = isset( $cart['product_id'] ) ? (int) $cart['product_id'] : 0;
		if ( ! $product_id ) {
			return $unit_price;
		}

		$booking_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
		$booking_unit = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true );

		// Single-calendar day bookings: price each selected date independently.
		// Use $booking_dates directly (the filter arg) — it contains exactly the N
		// selected dates and count == $unit, avoiding extra entries that
		// extract_cart_dates() could pull in from other cart-data fields.
		if ( 'single_cal' === $booking_type && 'day' === $booking_unit && ! empty( $booking_dates ) ) {
			$selected = is_array( $booking_dates ) ? $booking_dates : array( $booking_dates );
			return $this->calc_per_date_cart_price( $product_id, $selected, $unit_price, $unit );
		}

		// Dual-calendar day bookings: expand the from→to range into individual days
		// so that dynamic-priced dates inside the range are each priced on their own
		// rather than applying one rule (or no rule) to the entire block.
		if ( 'dual_cal' === $booking_type && 'day' === $booking_unit && $unit > 0 ) {
			$from    = isset( $custom_cart_data['date_time_from'] ) ? (string) $custom_cart_data['date_time_from'] : '';
			$from_ts = $from ? strtotime( $from ) : 0;
			if ( $from_ts ) {
				$range = $this->expand_date_range( $from_ts, (int) $unit );
				if ( ! empty( $range ) ) {
					return $this->calc_per_date_cart_price( $product_id, $range, $unit_price, $unit );
				}
			}
		}

		// Dual-calendar hour bookings: split the time range into calendar-day segments,
		// price each segment at that day's rate, and sum the adjusted hour costs.
		if ( 'dual_cal' === $booking_type && 'hour' === $booking_unit && $unit > 0 ) {
			$from    = isset( $custom_cart_data['date_time_from'] ) ? (string) $custom_cart_data['date_time_from'] : '';
			$from_ts = $from ? strtotime( $from ) : 0;
			if ( $from_ts ) {
				$to_ts = $from_ts + (int) round( $unit * 3600 );
				return $this->calc_per_hour_cart_price( $product_id, $from_ts, $to_ts, $unit_price, $unit );
			}
		}

		$dates = $this->extract_cart_dates( $custom_cart_data, $booking_dates );

		// All other booking types: apply one matched rule to the entire price.
		$rule = $this->get_matching_rule( $product_id, $dates );
		if ( ! $rule ) {
			return $unit_price;
		}

		$units = ( $unit > 0 ) ? (float) $unit : 1;

		if ( 'percentage' === $rule['type'] ) {
			return $unit_price + $unit_price * ( (float) $rule['value'] / 100 );
		}

		return $unit_price + (float) $rule['value'] * $units;
	}

	/**
	 * Price each selected date individually for single-calendar day bookings.
	 *
	 * Dates that match a rule get the markup; dates with no matching rule keep
	 * their plain per-unit cost. The sum of all per-date costs is returned.
	 *
	 * @param int   $product_id Product ID.
	 * @param array $dates      Selected date strings (one per day).
	 * @param float $unit_price Total unit cost before dynamic pricing (per-unit × units).
	 * @param float $unit       Number of booking units (= number of selected dates).
	 * @return float Adjusted total.
	 */
	private function calc_per_date_cart_price( $product_id, $dates, $unit_price, $unit ) {
		$units    = ( $unit > 0 ) ? (float) $unit : 1;
		$per_unit = $unit_price / $units;
		$total    = 0.0;

		foreach ( $dates as $date ) {
			$rule = $this->get_matching_rule( $product_id, array( $date ) );
			if ( ! $rule ) {
				$total += $per_unit;
			} elseif ( 'percentage' === $rule['type'] ) {
				$total += $per_unit + $per_unit * ( (float) $rule['value'] / 100 );
			} else {
				$total += $per_unit + (float) $rule['value'];
			}
		}

		return $total;
	}

	/**
	 * Hook: mbfw_ajax_load_total_booking_charge_individually  (single-product page AJAX preview)
	 *
	 * $charges['general_cost']['value'] at this point = per_unit_price × units
	 * [× people_number when "unit cost per people" is enabled].
	 *
	 * For single-calendar day bookings, each date is priced independently so
	 * that only the matching dates receive a markup.
	 *
	 * For all other booking types, a single matched rule is applied to the
	 * entire general_cost value.
	 *
	 * @param array $charges       Price breakdown: keyed charge objects with 'title' and 'value'.
	 * @param int   $product_id    Product being previewed.
	 * @param array $booking_dates All booking dates collected by the caller.
	 * @param float $unit          Number of booking units.
	 * @param int   $people_number Number of people selected.
	 * @return array
	 */
	public function apply_to_ajax_charges( $charges, $product_id, $booking_dates = array(), $unit = 1, $people_number = 1 ) {
		if ( empty( $charges['general_cost'] ) ) {
			return $charges;
		}

		$dates        = is_array( $booking_dates ) ? $booking_dates : array();
		$booking_type = wps_booking_get_meta_data( $product_id, 'wps_mbfw_booking_type', true );
		$booking_unit = wps_booking_get_meta_data( $product_id, 'mwb_mbfw_booking_unit', true );

		// Single-calendar day bookings: price each selected date independently.
		if ( 'single_cal' === $booking_type && 'day' === $booking_unit && ! empty( $dates ) ) {
			return $this->apply_per_date_ajax_charges( $charges, (int) $product_id, $dates, $unit, (int) $people_number );
		}

		// Dual-calendar day bookings: expand the from-date + unit count into a full
		// date list so each day in the range is priced on its own.
		if ( 'dual_cal' === $booking_type && 'day' === $booking_unit && ! empty( $dates ) && $unit > 0 ) {
			// Find the earliest parseable timestamp across all provided date strings.
			$from_ts = null;
			foreach ( $dates as $d ) {
				$ts = strtotime( (string) $d );
				if ( $ts && ( null === $from_ts || $ts < $from_ts ) ) {
					$from_ts = $ts;
				}
			}
			if ( $from_ts ) {
				$range = $this->expand_date_range( $from_ts, (int) $unit );
				if ( ! empty( $range ) ) {
					return $this->apply_per_date_ajax_charges( $charges, (int) $product_id, $range, $unit, (int) $people_number );
				}
			}
		}

		// Dual-calendar hour bookings: split the time range into calendar-day segments,
		// price each segment's hours at that day's rate, and sum the results.
		// Use the maximum timestamp in $dates to recover the start time-of-day
		// (dp_booking_dates contains both the bare date and the full datetime string;
		// the one with the actual time component always parses to a later timestamp).
		if ( 'dual_cal' === $booking_type && 'hour' === $booking_unit && ! empty( $dates ) && $unit > 0 ) {
			$from_ts = null;
			foreach ( $dates as $d ) {
				$ts = strtotime( (string) $d );
				if ( $ts && ( null === $from_ts || $ts > $from_ts ) ) {
					$from_ts = $ts;
				}
			}
			if ( $from_ts ) {
				$to_ts = $from_ts + (int) round( $unit * 3600 );
				return $this->apply_per_hour_ajax_charges( $charges, (int) $product_id, $from_ts, $to_ts, $unit, (int) $people_number );
			}
		}

		// All other booking types: apply one matched rule to the entire general_cost.
		$rule = $this->get_matching_rule( (int) $product_id, $dates );
		if ( ! $rule ) {
			return $charges;
		}

		$current = (float) $charges['general_cost']['value'];

		if ( 'percentage' === $rule['type'] ) {
			$charges['general_cost']['value'] = $current + $current * ( (float) $rule['value'] / 100 );
		} else {
			$is_per_people = 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_booking_unit_cost_per_people', true );
			$units         = ( $unit > 0 ) ? (float) $unit : 1;
			$multiplier    = $units * ( $is_per_people ? max( 1, (int) $people_number ) : 1 );
			$charges['general_cost']['value'] = $current + (float) $rule['value'] * $multiplier;
		}

		return $charges;
	}

	/**
	 * Apply per-date dynamic pricing to AJAX charges for single-cal day bookings.
	 *
	 * Recovers per-unit cost from the pre-computed total, prices each date
	 * individually, then re-applies the people multiplier to the adjusted sum.
	 *
	 * @param array $charges       Price breakdown array.
	 * @param int   $product_id    Product ID.
	 * @param array $dates         Selected date strings (one per day).
	 * @param float $unit          Number of units (= number of selected dates).
	 * @param int   $people_number Number of people.
	 * @return array Updated charges.
	 */
	private function apply_per_date_ajax_charges( $charges, $product_id, $dates, $unit, $people_number ) {
		$is_per_people = 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_booking_unit_cost_per_people', true );
		$units         = ( $unit > 0 ) ? (float) $unit : 1;
		$people        = $is_per_people ? max( 1, (int) $people_number ) : 1;

		// Recover per-unit cost (before people multiplication) from the stored total.
		$per_unit = (float) $charges['general_cost']['value'] / ( $units * $people );
		$total    = 0.0;

		foreach ( $dates as $date ) {
			$rule = $this->get_matching_rule( $product_id, array( $date ) );
			if ( ! $rule ) {
				$total += $per_unit;
			} elseif ( 'percentage' === $rule['type'] ) {
				$total += $per_unit + $per_unit * ( (float) $rule['value'] / 100 );
			} else {
				$total += $per_unit + (float) $rule['value'];
			}
		}

		$charges['general_cost']['value'] = $total * $people;
		return $charges;
	}

	/**
	 * Price a dual-cal hour booking day by day for the cart/checkout context.
	 *
	 * Iterates through each calendar day covered by the booking, computes how
	 * many hours of that day are inside the booking window, looks up a dynamic
	 * pricing rule for that specific date, and accumulates the adjusted cost.
	 *
	 * @param int   $product_id  Product ID.
	 * @param int   $from_ts     Booking start as a Unix timestamp (includes time of day).
	 * @param int   $to_ts       Booking end as a Unix timestamp.
	 * @param float $unit_price  Pre-dynamic total unit cost (per_hour × hours).
	 * @param float $unit        Total booking hours.
	 * @return float Adjusted total.
	 */
	private function calc_per_hour_cart_price( $product_id, $from_ts, $to_ts, $unit_price, $unit ) {
		$units    = ( $unit > 0 ) ? (float) $unit : 1;
		$per_hour = $unit_price / $units;
		$total    = 0.0;

		$day_ts = (int) strtotime( 'midnight', (int) $from_ts );
		while ( $day_ts < $to_ts ) {
			$next_day_ts  = (int) strtotime( '+1 day', $day_ts );
			$day_start    = max( $from_ts, $day_ts );
			$day_end      = min( $to_ts, $next_day_ts );
			$hours_on_day = ( $day_end - $day_start ) / 3600.0;

			if ( $hours_on_day > 0 ) {
				$date = gmdate( 'Y-m-d', $day_ts );
				$rule = $this->get_matching_rule( $product_id, array( $date ) );
				if ( ! $rule ) {
					$total += $hours_on_day * $per_hour;
				} elseif ( 'percentage' === $rule['type'] ) {
					$total += $hours_on_day * $per_hour * ( 1 + (float) $rule['value'] / 100 );
				} else {
					// Fixed is per booking unit (hour); add it to the per-hour base.
					$total += $hours_on_day * ( $per_hour + (float) $rule['value'] );
				}
			}

			$day_ts = $next_day_ts;
		}

		return $total;
	}

	/**
	 * Apply per-day dynamic pricing to AJAX charges for dual-cal hour bookings.
	 *
	 * @param array $charges       Price breakdown array.
	 * @param int   $product_id    Product ID.
	 * @param int   $from_ts       Booking start as a Unix timestamp (includes time of day).
	 * @param int   $to_ts         Booking end as a Unix timestamp.
	 * @param float $unit          Total booking hours.
	 * @param int   $people_number Number of people.
	 * @return array Updated charges.
	 */
	private function apply_per_hour_ajax_charges( $charges, $product_id, $from_ts, $to_ts, $unit, $people_number ) {
		$is_per_people = 'yes' === wps_booking_get_meta_data( $product_id, 'mwb_mbfw_is_booking_unit_cost_per_people', true );
		$units         = ( $unit > 0 ) ? (float) $unit : 1;
		$people        = $is_per_people ? max( 1, (int) $people_number ) : 1;
		$per_hour      = (float) $charges['general_cost']['value'] / ( $units * $people );
		$total         = 0.0;

		$day_ts = (int) strtotime( 'midnight', (int) $from_ts );
		while ( $day_ts < $to_ts ) {
			$next_day_ts  = (int) strtotime( '+1 day', $day_ts );
			$day_start    = max( $from_ts, $day_ts );
			$day_end      = min( $to_ts, $next_day_ts );
			$hours_on_day = ( $day_end - $day_start ) / 3600.0;

			if ( $hours_on_day > 0 ) {
				$date = gmdate( 'Y-m-d', $day_ts );
				$rule = $this->get_matching_rule( $product_id, array( $date ) );
				if ( ! $rule ) {
					$total += $hours_on_day * $per_hour;
				} elseif ( 'percentage' === $rule['type'] ) {
					$total += $hours_on_day * $per_hour * ( 1 + (float) $rule['value'] / 100 );
				} else {
					$total += $hours_on_day * ( $per_hour + (float) $rule['value'] );
				}
			}

			$day_ts = $next_day_ts;
		}

		$charges['general_cost']['value'] = $total * $people;
		return $charges;
	}

	/**
	 * Generate an ordered array of Y-m-d strings starting from $from_ts.
	 * Used to expand a dual-cal day range into individual priceable days.
	 *
	 * @param int $from_ts Unix timestamp of the first day (inclusive).
	 * @param int $count   Number of consecutive days to generate.
	 * @return string[]
	 */
	private function expand_date_range( $from_ts, $count ) {
		if ( ! $from_ts || $count <= 0 ) {
			return array();
		}
		$dates = array();
		for ( $i = 0; $i < $count; $i++ ) {
			$dates[] = gmdate( 'Y-m-d', strtotime( '+' . $i . ' days', (int) $from_ts ) );
		}
		return $dates;
	}

	/**
	 * Assemble all booking dates from the cart item data into a flat array
	 * so the rule matcher has a complete picture regardless of booking type.
	 *
	 * @param array        $custom_cart_data Cart item booking values.
	 * @param array|string $booking_dates    Dates from single-cal picker (may be empty).
	 * @return array
	 */
	private function extract_cart_dates( $custom_cart_data, $booking_dates ) {
		$dates = array();

		// Single-calendar multi-day: stored as "Date1 | Date2 | …"
		if ( ! empty( $custom_cart_data['single_cal_booking_dates'] ) ) {
			foreach ( explode( ' | ', $custom_cart_data['single_cal_booking_dates'] ) as $d ) {
				$dates[] = trim( $d );
			}
		}

		// Dual-calendar / date-range: from and to values.
		if ( ! empty( $custom_cart_data['date_time_from'] ) ) {
			$dates[] = $custom_cart_data['date_time_from'];
		}
		if ( ! empty( $custom_cart_data['date_time_to'] ) ) {
			$dates[] = $custom_cart_data['date_time_to'];
		}

		// Single-cal hour type stores these separately.
		if ( ! empty( $custom_cart_data['single_cal_date_time_from'] ) ) {
			$dates[] = $custom_cart_data['single_cal_date_time_from'];
		}

		// $booking_dates parameter: populated for single_cal day bookings.
		if ( ! empty( $booking_dates ) ) {
			$dates = array_merge( $dates, is_array( $booking_dates ) ? $booking_dates : array( $booking_dates ) );
		}

		return array_values( array_unique( array_filter( $dates ) ) );
	}

	// =========================================================================
	// Assets
	// =========================================================================

	/**
	 * Load flatpickr (not queued for product pages by default) and our rule-builder
	 * JS on the WooCommerce product edit screen.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_assets( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen || 'product' !== $screen->id ) {
			return;
		}

		// Flatpickr is bundled with the plugin; only needs registering here.
		wp_enqueue_style(
			'mwb-flatpickr-css',
			MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/flatpickr/dist/flatpickr.min.css',
			array(),
			MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION
		);
		wp_enqueue_script(
			'mwb-flatpickr-js',
			MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/flatpickr/dist/flatpickr.min.js',
			array(),
			MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION,
			true
		);

		wp_enqueue_script(
			'mwb-dp-admin-js',
			MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-bookings-dynamic-pricing.js',
			array( 'jquery', 'mwb-flatpickr-js' ),
			MWB_BOOKINGS_FOR_WOOCOMMERCE_VERSION,
			true
		);
	}
}
