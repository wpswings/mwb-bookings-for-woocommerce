<?php
/**
 * Analytics tab template.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_pro = Mwb_Bookings_For_Woocommerce_Analytics::is_pro_active();

// Default date range: this month.
$default_from = gmdate( 'Y-m-01' );
$default_to   = gmdate( 'Y-m-d' );
?>

<div class="wps-analytics-wrap">

	<!-- ===================== Filter bar ===================== -->
	<form id="wps-analytics-filter-form">
		<div class="wps-analytics-filter-bar">

			<label for="wps-analytics-preset"><?php esc_html_e( 'Period:', 'mwb-bookings-for-woocommerce' ); ?></label>
			<select id="wps-analytics-preset" name="preset">
				<option value="this_month"><?php esc_html_e( 'This Month', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="today"><?php esc_html_e( 'Today', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="this_week"><?php esc_html_e( 'This Week', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="last_30"><?php esc_html_e( 'Last 30 Days', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="last_month"><?php esc_html_e( 'Last Month', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="this_year"><?php esc_html_e( 'This Year', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="all_time"><?php esc_html_e( 'All Time', 'mwb-bookings-for-woocommerce' ); ?></option>
				<option value="custom"><?php esc_html_e( 'Custom Range', 'mwb-bookings-for-woocommerce' ); ?></option>
			</select>

			<span class="wps-custom-range" style="display:none;">
				<label for="wps-analytics-date-from"><?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?></label>
				<input type="date" id="wps-analytics-date-from" name="date_from"
					value="<?php echo esc_attr( $default_from ); ?>">
				<span><?php esc_html_e( 'to', 'mwb-bookings-for-woocommerce' ); ?></span>
				<input type="date" id="wps-analytics-date-to" name="date_to"
					value="<?php echo esc_attr( $default_to ); ?>">
				<button type="submit" class="wps-btn-apply">
					<?php esc_html_e( 'Apply', 'mwb-bookings-for-woocommerce' ); ?>
				</button>
			</span>

			<?php if ( $is_pro ) : ?>
				<button type="button" id="wps-btn-export-csv" class="wps-btn-export">
					&#8595; <?php esc_html_e( 'Export CSV', 'mwb-bookings-for-woocommerce' ); ?>
				</button>
			<?php endif; ?>

		</div>
	</form>

	<!-- ===================== Loading state ===================== -->
	<div id="wps-analytics-loading" class="wps-analytics-loading">
		<span class="wps-analytics-spinner"></span>
		<?php esc_html_e( 'Loading analytics data…', 'mwb-bookings-for-woocommerce' ); ?>
	</div>

	<!-- ===================== Main content ===================== -->
	<div id="wps-analytics-content" style="display:none;">

		<!-- Stat cards -->
		<div class="wps-stat-cards">

			<div class="wps-stat-card wps-stat-card--primary">
				<div class="wps-stat-card__icon">&#128197;</div>
				<div class="wps-stat-card__body">
					<div class="wps-stat-card__value" id="wps-stat-total-bookings">—</div>
					<div class="wps-stat-card__label"><?php esc_html_e( 'Total Bookings', 'mwb-bookings-for-woocommerce' ); ?></div>
				</div>
			</div>

			<div class="wps-stat-card wps-stat-card--success">
				<div class="wps-stat-card__icon">&#128176;</div>
				<div class="wps-stat-card__body">
					<div class="wps-stat-card__value" id="wps-stat-revenue">—</div>
					<div class="wps-stat-card__label"><?php esc_html_e( 'Revenue (Completed)', 'mwb-bookings-for-woocommerce' ); ?></div>
				</div>
			</div>

			<div class="wps-stat-card wps-stat-card--danger">
				<div class="wps-stat-card__icon">&#10060;</div>
				<div class="wps-stat-card__body">
					<div class="wps-stat-card__value" id="wps-stat-cancellation">—</div>
					<div class="wps-stat-card__label"><?php esc_html_e( 'Cancellation Rate', 'mwb-bookings-for-woocommerce' ); ?></div>
				</div>
			</div>

			<div class="wps-stat-card wps-stat-card--warning">
				<div class="wps-stat-card__icon">&#9200;</div>
				<div class="wps-stat-card__body">
					<div class="wps-stat-card__value" id="wps-stat-pending">—</div>
					<div class="wps-stat-card__label"><?php esc_html_e( 'Pending Action', 'mwb-bookings-for-woocommerce' ); ?></div>
				</div>
			</div>

		</div><!-- /.wps-stat-cards -->

		<!-- Charts row 1: status doughnut + product revenue -->
		<div class="wps-chart-row">

			<div class="wps-chart-box">
				<h3><?php esc_html_e( 'Bookings by Status', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<canvas id="wps-chart-status"></canvas>
			</div>

			<div class="wps-chart-box">
				<h3><?php esc_html_e( 'Revenue by Product', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<canvas id="wps-chart-product-revenue"></canvas>
			</div>

		</div><!-- /.wps-chart-row -->

		<!-- Charts row 2: bookings over time + peak days -->
		<div class="wps-chart-row">

			<div class="wps-chart-box">
				<h3><?php esc_html_e( 'Bookings Over Time', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<canvas id="wps-chart-over-time"></canvas>
			</div>

			<div class="wps-chart-box">
				<h3><?php esc_html_e( 'Peak Days of Week', 'mwb-bookings-for-woocommerce' ); ?></h3>
				<canvas id="wps-chart-peak-days"></canvas>
			</div>

		</div><!-- /.wps-chart-row -->

		<!-- Top products table -->
		<div class="wps-analytics-table-wrap">
			<h3><?php esc_html_e( 'Top Booking Products', 'mwb-bookings-for-woocommerce' ); ?></h3>
			<table class="wps-analytics-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Product', 'mwb-bookings-for-woocommerce' ); ?></th>
						<th><?php esc_html_e( 'Bookings', 'mwb-bookings-for-woocommerce' ); ?></th>
						<th><?php esc_html_e( 'Revenue', 'mwb-bookings-for-woocommerce' ); ?></th>
					</tr>
				</thead>
				<tbody id="wps-top-products-tbody">
					<tr>
						<td colspan="3" class="wps-table-loading">
							<?php esc_html_e( 'Loading…', 'mwb-bookings-for-woocommerce' ); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- /.wps-analytics-table-wrap -->

		<?php if ( $is_pro ) : ?>
		<!-- ===================== Pro section ===================== -->
		<div class="wps-analytics-pro-section">

			<p class="wps-pro-section-title">
				<span class="wps-pro-badge">&#9733; <?php esc_html_e( 'Pro Insights', 'mwb-bookings-for-woocommerce' ); ?></span>
			</p>

			<!-- Pro stat cards -->
			<div class="wps-stat-cards">

				<div class="wps-stat-card wps-stat-card--info">
					<div class="wps-stat-card__icon">&#128200;</div>
					<div class="wps-stat-card__body">
						<div class="wps-stat-card__value" id="wps-stat-avg-occupancy">—</div>
						<div class="wps-stat-card__label"><?php esc_html_e( 'Avg Occupancy Rate', 'mwb-bookings-for-woocommerce' ); ?></div>
					</div>
				</div>

				<div class="wps-stat-card wps-stat-card--purple">
					<div class="wps-stat-card__icon">&#128101;</div>
					<div class="wps-stat-card__body">
						<div class="wps-stat-card__value" id="wps-stat-avg-people">—</div>
						<div class="wps-stat-card__label"><?php esc_html_e( 'Avg People / Booking', 'mwb-bookings-for-woocommerce' ); ?></div>
					</div>
				</div>

				<div class="wps-stat-card wps-stat-card--orange">
					<div class="wps-stat-card__icon">&#128336;</div>
					<div class="wps-stat-card__body">
						<div class="wps-stat-card__value" id="wps-stat-peak-hour">—</div>
						<div class="wps-stat-card__label"><?php esc_html_e( 'Peak Hour', 'mwb-bookings-for-woocommerce' ); ?></div>
					</div>
				</div>

				<div class="wps-stat-card wps-stat-card--teal">
					<div class="wps-stat-card__icon">&#127775;</div>
					<div class="wps-stat-card__body">
						<div class="wps-stat-card__value" id="wps-stat-top-people">—</div>
						<div class="wps-stat-card__label"><?php esc_html_e( 'Top People Type', 'mwb-bookings-for-woocommerce' ); ?></div>
					</div>
				</div>

			</div><!-- /.wps-stat-cards (pro) -->

			<!-- Pro charts row 1 -->
			<div class="wps-chart-row">

				<div class="wps-chart-box">
					<h3><?php esc_html_e( 'Slot Occupancy by Product', 'mwb-bookings-for-woocommerce' ); ?></h3>
					<canvas id="wps-chart-occupancy"></canvas>
				</div>

				<div class="wps-chart-box">
					<h3><?php esc_html_e( 'Peak Hours', 'mwb-bookings-for-woocommerce' ); ?></h3>
					<canvas id="wps-chart-peak-hours"></canvas>
				</div>

			</div><!-- /.wps-chart-row (pro) -->

			<!-- Pro charts row 2 -->
			<div class="wps-chart-row">

				<div class="wps-chart-box">
					<h3><?php esc_html_e( 'People Types Breakdown', 'mwb-bookings-for-woocommerce' ); ?></h3>
					<canvas id="wps-chart-people-types"></canvas>
				</div>

			</div><!-- /.wps-chart-row (pro row 2) -->

		</div><!-- /.wps-analytics-pro-section -->
		<?php endif; ?>

	</div><!-- /#wps-analytics-content -->

</div><!-- /.wps-analytics-wrap -->
