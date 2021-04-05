<?php
/**
 * Listing of user's all booking on My Account Page under "All Bookings".
 */

if ( isset( $_GET['booking_id'] ) ) {

	$booking_id = sanitize_text_field( wp_unslash( $_GET['booking_id'] ) );
	$booking    = get_post( $booking_id );
	// echo '<pre>'; print_r( $booking ); echo '</pre>';
	$post_created   = $booking->post_date_gmt;
	$booking_meta   = get_post_meta( $booking_id, 'mwb_meta_data', true );
	$booking_status = get_post_meta( $booking_id, 'mwb_booking_status', true );

	$start_date = isset( $booking_meta['start_date'] ) ? $booking_meta['start_date'] : '-';
	$end_date   = isset( $booking_meta['end_date'] ) ? $booking_meta['end_date'] : $booking_meta['start_date'];

	$from       = isset( $booking_meta['start_timestamp'] ) ? gmdate( 'd-m-Y h:i:s a', $booking_meta['start_timestamp'] ) : '-';
	$to         = isset( $booking_meta['end_timestamp'] ) ? gmdate( 'd-m-Y h:i:s a', $booking_meta['end_timestamp'] ) : '-';
	$total      = isset( $booking_meta['total_cost'] ) ? $booking_meta['total_cost'] : '-';
	$order_id   = isset( $booking_meta['order_id'] ) ? $booking_meta['order_id'] : '-';
	$product_id = isset( $booking_meta['product_id'] ) ? $booking_meta['product_id'] : '-';

	$people_total = ! empty( $booking_meta['people_total'] ) ? $booking_meta['people_total'] : 0;
	$peoples      = ( ! empty( $booking_meta['people_count'] ) && is_array( $booking_meta['people_count'] ) ) ? $booking_meta['people_count'] : array();
	$inc_service  = ( ! empty( $booking_meta['inc_service'] ) && is_array( $booking_meta['inc_service'] ) ) ? $booking_meta['inc_service'] : array();
	$add_service  = ( ! empty( $booking_meta['add_service'] ) && is_array( $booking_meta['add_service'] ) ) ? $booking_meta['add_service'] : array();

	$_order     = wc_get_order( $order_id );
	$order_data = $_order->get_data();

	$_product = get_post( $product_id );
	// echo '<pre>'; print_r( $booking_meta ); echo '</pre>';

	$people_str = '';
	if ( ! empty( $people ) ) {
		foreach ( $peoples as $name => $count ) {
			$people_str .= $name . '-' . $count . ', ';
		}
		$people_str = substr( $people_str, 0, -2 );
	} else {
		$people_str = 'None  ';
	}
	$people_str = substr( $people_str, 0, -2 );

	$inc_services_str = '';
	if ( ! empty( $inc_service ) ) {
		foreach ( $inc_service as $name => $count ) {
			$inc_services_str .= $name . '-' . $count . ', ';
		}
	} else {
		$inc_services_str .= 'None  ';
	}
	$inc_services_str = substr( $inc_services_str, 0, -2 );

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

	<p><?php esc_html_e( 'Booking #' ); ?><mark class="booking-number"><?php echo esc_html( $booking_id ); ?> </mark><?php esc_html_e( 'was placed on' ); ?> <mark class="booking-date"><?php echo esc_html( gmdate( 'd-m-Y', strtotime( $post_created ) ) ); ?> </mark><?php esc_html_e( 'and is currently', '' ); ?> <mark class="booking-status"><?php echo esc_html( $booking_status ); ?></mark></p>
		<section class="woocommerce-order-details">
			<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Booking details', 'mwb-wc-bk' ); ?></h2>
			<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
				<thead>
					<tr>
						<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'mwb-wc-bk' ); ?></th>
						<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Details', 'mwb-wc-bk' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr class="woocommerce-table__line-item order_item">
						<td class="woocommerce-table__product-name product-name">
							<a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( $_product->post_title ); ?></a>
						</td>
						<td class="woocommerce-table__product-total product-total">
							<ul class="wc-item-meta">
								<li><strong class="wc-item-meta-label"><?php esc_html_e( 'From:  ', 'mwb-wc-bk' ); ?></strong><p><?php echo esc_html( $from ); ?></p></li>
								<li><strong class="wc-item-meta-label"><?php esc_html_e( 'To:  ', 'mwb-wc-bk' ); ?></strong><p><?php echo esc_html( $to ); ?></p></li>
								<li><strong class="wc-item-meta-label"><?php esc_html_e( 'People Total:  ', 'mwb-wc-bk' ); ?></strong><p><?php echo esc_html( $people_total ); ?></p></li>
								<li><strong class="wc-item-meta-label"><?php esc_html_e( 'People Types:  ', 'mwb-wc-bk' ); ?></strong><p><?php echo esc_html( $people_str ); ?></p></li>
								<li><strong class="wc-item-meta-label"><?php esc_html_e( 'Included Services:  ', 'mwb-wc-bk' ); ?></strong><p><?php echo esc_html( $inc_services_str ); ?></p></li>
								<li><strong class="wc-item-meta-label"><?php esc_html_e( 'Additional Services:  ', 'mwb-wc-bk' ); ?></strong><p><?php echo esc_html( $add_services_str ); ?></p></li>
							</ul>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th scope="row"><?php esc_html_e( 'Payment method:', 'mwb-wc-bk' ); ?></th>
						<td><?php echo esc_html( $order_data['payment_method'] ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Total:', 'mwb-wc-bk' ); ?></th>
						<td><?php echo wc_price( $total ); ?></td>
					</tr>
				</tfoot>
			</table>
		</section>
		<section class="woocommerce-customer-details">
			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Billing address', 'mwb-wc-bk' ); ?></h2>
			<address>
				<?php echo esc_html( $order_data['billing']['first_name'] ); ?><br><?php echo esc_html( $order_data['billing']['last_name'] ); ?><br><?php echo esc_html( $order_data['billing']['address_1'] ); ?><br><?php echo esc_html( $order_data['billing']['address_2'] ); ?><br><?php echo esc_html( $order_data['billing']['city'] ); ?><?php echo esc_html( $order_data['billing']['state'] ); ?><br><?php echo esc_html( $order_data['billing']['postcode'] ); ?><br><?php echo esc_html( $order_data['billing']['country'] ); ?>
					<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order_data['billing']['phone'] ); ?></p>	
					<p class="woocommerce-customer-details--email"><?php echo esc_html( $order_data['billing']['email'] ); ?></p>
			</address>
		</section>
	<?php
} else {

	$current_user_id = get_current_user_id();
	$bookings        = get_posts(
		array(
			'numberpost'  => -1,
			'post_type'   => 'mwb_cpt_booking',
			'post_status' => 'publish',
			'author'      => $current_user_id,
		)
	);
	?>
	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr"><?php esc_html_e( 'Booking', '' ); ?></span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-from"><span class="nobr"><?php esc_html_e( 'From', '' ); ?></span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-to"><span class="nobr"><?php esc_html_e( 'To', '' ); ?></span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr"><?php esc_html_e( 'Status', '' ); ?></span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr"><?php esc_html_e( 'Total', '' ); ?></span></th>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr"><?php esc_html_e( 'Actions', '' ); ?></span></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if ( ! empty( $bookings ) ) {
				// echo '<pre>'; print_r( $bookings ); echo '</pre>';die;
			foreach ( $bookings as $booking => $obj  ) {
				$booking_id     = $obj->ID;
				$booking_title  = $obj->post_title;
				$booking_meta   = get_post_meta( $booking_id, 'mwb_meta_data', true );
				$booking_status = get_post_meta( $booking_id, 'mwb_booking_status', true );

				$start_date = isset( $booking_meta['start_date'] ) ? $booking_meta['start_date'] : '-';
				$end_date   = isset( $booking_meta['end_date'] ) ? $booking_meta['end_date'] : $booking_meta['start_date'];

				$from     = isset( $booking_meta['start_timestamp'] ) ? gmdate( 'd-m-Y h:i:s a', $booking_meta['start_timestamp'] ) : '-';
				$to       = isset( $booking_meta['end_timestamp'] ) ? gmdate( 'd-m-Y h:i:s a', $booking_meta['end_timestamp'] ) : '-';
				$total    = isset( $booking_meta['total_cost'] ) ? $booking_meta['total_cost'] : '-';
				$order_id = isset( $booking_meta['order_id'] ) ? $booking_meta['order_id'] : '-';


				// echo '<pre>'; print_r( $booking_meta ); echo '</pre>';
				?>
			<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order">
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-booking-titile" data-title="Booking">
					<a href="?booking_id=<?php echo esc_html( $booking_id ); ?>"><?php echo esc_html( $booking_title ); ?></a>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-booking-from" data-title="From">
					<time datetime="<?php gmdate( 'y-m-d h:i:s a', strtotime( $from ) ); ?>"><?php echo esc_html( $from ); ?></time>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-booking-to" data-title="To">
					<time datetime="<?php gmdate( 'y-m-d h:i:s a', strtotime( $to ) ); ?>"><?php echo esc_html( $to ); ?></time>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-booking-status" data-title="Status">
					<?php echo esc_html( $booking_status ); ?>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-booking-total" data-title="Total">
					<?php echo wp_kses_post( get_woocommerce_currency_symbol() . ' ' . $total ); ?>
				</td>
				<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-booking-actions" data-title="Actions">
					<a href="?booking_id=<?php echo esc_html( $booking_id ); ?>" class="woocommerce-button button view"><?php esc_html_e( 'View Order', '' ); ?></a>
				</td>
			</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>

	<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
		<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="http://localhost:10038/my-account/orders/2/">Next</a>
	</div>
<?php } ?>
