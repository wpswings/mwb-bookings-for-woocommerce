<?php
/**
 * Provide a public area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product_id = get_the_id();
do_action( 'mwb_booking_before_add_to_cart_form' );
?>
<form class="mwb_booking_cart" method="post" enctype='multipart/form-data'>
	<table cellspacing="0">
		<tbody>
			<tr>
				<th>
					<?php esc_html_e( 'Service', 'mwb-bookings-for-woocommerce' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Cost', 'mwb-bookings-for-woocommerce' ); ?>
				</th>
			</tr>
			<?php
			$mbfw_booking_service = get_the_terms( $product_id, 'mwb_booking_service' );
			$mbfw_booking_cost    = get_the_terms( $product_id, 'mwb_booking_cost' );
			if ( $mbfw_booking_cost && is_array( $mbfw_booking_cost ) ) {
				foreach ( $mbfw_booking_cost as $custom_term ) {
					?>
					<tr>
						<td><?php echo esc_html( $custom_term->name ); ?></td>
						<td><?php echo esc_html( get_term_meta( $custom_term->term_id, 'mwb_mbfw_booking_cost', true ) ); ?></td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
</form>
<?php do_action( 'mwb_booking_after_add_to_cart_form' ); ?>
