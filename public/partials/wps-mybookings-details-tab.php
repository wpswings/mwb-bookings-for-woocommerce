<?php

$table_headers = array(
    'order-id'        => esc_html__( 'Order ID', 'membership-for-woocommerce' ),
    'booking-date'    => esc_html__( 'Booking Date', 'membership-for-woocommerce' ),
    'booking-status'  => esc_html__( 'Booking Status', 'membership-for-woocommerce' ),
    'booking-total'   => esc_html__( 'Total', 'membership-for-woocommerce' ),
    'booking-actions' => esc_html__( 'Actions', 'membership-for-woocommerce' ),
);


$event_attendees_details = array();
		$customer = wp_get_current_user(); // do this when user is logged in.
		
		$customer_orders = get_posts(
			array(
				'numberposts' => -1,
				'meta_key' => '_customer_user',
				'orderby' => 'date',
				'order' => 'DESC',
				'meta_value' => get_current_user_id(),
                'post_status'       => array_keys( wc_get_order_statuses() ),
				'post_type' => 'shop_order',
				'fields' => 'ids',
			)
		);
    

?>

<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
    <thead>
        <tr>
            <?php foreach ( $table_headers as $column_id => $column_name ) : ?>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <?php
    if( ! empty( $customer_orders ) ) {

        foreach( $customer_orders as $key => $value ) {
   
           $_order = wc_get_order( $value ); 
           foreach ( $_order->get_items() as $item_id => $item ) { 
            $product = $item->get_product();
            if ( $product instanceof WC_Product && $product->is_type( 'mwb_booking' ) ) { 
                $date_time_from = $item->get_meta( '_mwb_bfwp_date_time_from', true );
                $date_time_to  = $item->get_meta( '_mwb_bfwp_date_time_to', true );
            ?>
                <tr>
                     <td><?php echo esc_html( $value );?></td>
                     <td><?php echo esc_html( $date_time_from ); esc_html_e( " To ", "mwb-bookings-for-woocommerce" ); echo esc_html( $date_time_to ); ?></td>
                     <td><?php echo esc_html( $_order->get_status() );?></td>
                     <td><?php echo esc_html( $_order->get_total() );?></td>
                     <td><a class="button" href="<?php echo esc_attr( get_site_url() );esc_html_e('/my-account/view-order/'); echo esc_attr( $value );?>  "><?php esc_html_e( 'View','mwb-bookings-for-woocommerce' ); ?></a></td>
                </tr>
            <?php
            }
           }
           
          
   
       } 
    } else{
        esc_html_e( 'No Bookings has been purchased yet.', 'mwb-bookings-for-woocommerce' );
    }
    ?>
</table>


