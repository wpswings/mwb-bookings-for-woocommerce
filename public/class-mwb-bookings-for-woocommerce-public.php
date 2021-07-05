<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace mwb_bookings_for_woocommerce_public.
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/public
 */
class Mwb_Bookings_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function mbfw_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/css/mwb-bookings-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-bookings-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'mbfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}
	/**
	 * Adding custom fields before add to cart button.
	 *
	 * @return void
	 */
	public function mbfw_add_custom_fields_before_add_to_cart_button() {
		global $product;
		if ( is_object( $product ) && 'mwb_booking' === $product->get_type() ) {
			require_once MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_PATH . 'public/templates/mwb-bookings-for-woocommerce-public-add-to-cart-form.php';
		}
	}
	/**
	 * Loading single product template for our custom product type.
	 *
	 * @return void
	 */
	public function mwb_mbfw_load_single_page_template() {
		do_action( 'woocommerce_simple_add_to_cart' );
	}
	/**
	 * Return class name for custom product type.
	 *
	 * @param string $classname extended class name to return.
	 * @param string $product_type custom product name. 
	 * @return string
	 */
	public function mbfw_return_custom_product_class( $classname, $product_type ) {
		if ( 'mwb_booking' === $product_type ) {
			$classname = 'WC_Product_Mwb_Booking';
		}
		return $classname;
	}

	/**
	 * Show additional booking costs on form added from custom taxonomy.
	 *
	 * @param int    $product_id current product id.
	 * @param object $product current looping product object.
	 * @return void
	 */
	public function mwb_mbfw_show_additional_booking_costs_on_form( $product_id, $product ) {
		$mbfw_booking_cost = get_the_terms( $product_id, 'mwb_booking_cost' );
		if ( $mbfw_booking_cost && is_array( $mbfw_booking_cost ) ) {
			?>
			<div><?php esc_html_e( 'Additional Costs', 'mwb-bookings-for-woocommerce' ); ?></div>
			<table cellspacing="0">
				<tbody>
				<?php
				foreach ( $mbfw_booking_cost as $custom_term ) {
					?>
					<tr>
						<td><?php echo esc_html( $custom_term->name ); ?></td>
						<td><?php echo esc_html( get_term_meta( $custom_term->term_id, 'mwb_mbfw_booking_cost', true ) ); ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}
	}

	/**
	 * Show additional booking services on form.
	 *
	 * @param int    $product_id current product id.
	 * @param object $product current product object from the loop.
	 * @return void
	 */
	public function mwb_mbfw_show_additional_booking_services_details_on_form( $product_id, $product ) {
		$mbfw_booking_service = get_the_terms( $product_id, 'mwb_booking_service' );
		if ( $mbfw_booking_service && is_array( $mbfw_booking_service ) ) {
			?>
			<table cellspacing="0">
				<tbody>
					<?php
					foreach ( $mbfw_booking_service as $custom_term ) {
						if ( 'yes' !== get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_hidden', true ) ) {
							?>
							<div><?php esc_html_e( 'Included services', 'mwb-bookings-for-woocommerce' ); ?></div>
							<tr>
								<td>
									<?php if ( 'yes' === get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_optional', true ) ) { ?>
										<input type="checkbox" value="<?Php echo esc_attr( $custom_term->term_id ); ?>" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_option_checkbox[]" id="mwb-mbfw-service-option-checkbox-<?php echo esc_attr( $custom_term->term_id ); ?>" class="mwb-mbfw-additional-service-option" />
									<?php } ?>
									<span><?php echo esc_html( $custom_term->name ); ?></span>
								</td>
								<td><?php echo wp_kses_post( wc_price( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_cost', true ) ) ); ?></td>
								<?php if ( get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_has_quantity', true ) ) { ?>
									<td><input type="number" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_quantity[<?php echo esc_attr( $custom_term->term_id ); ?>]" min="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ); ?>" max="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_maximum_quantity', true ) ); ?>" class="mwb-mbfw-additional-service-quantity"/></td>
								<?php } ?>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
			<?php
		}
	}

	/**
	 * Show People while booking.
	 *
	 * @param int    $product_id current product id.
	 * @param object $product current product object in the loop.
	 * @return void
	 */
	public function mwb_mbfw_show_people_while_booking( $product_id, $product ) {
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_is_people_option', true ) ) {
			?>
			<table>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?></td>
						<td>
							<input type="number" name="mwb_mbfw_people_number" class="mwb_mbfw_people_number" id="mwb_mbfw_people_number" value="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?>" min="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?>" max="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_maximum_people_per_booking', true ) ); ?>" required>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}
	/**
	 * Add additional data in the cart.
	 *
	 * @param array $cart_item_data array containing cart items.
	 * @param int   $product_id product id of the added prouct.
	 * @param int   $variation_id variation product id.
	 * @param int   $quantity quantity of the product.
	 * @return array
	 */
	public function mwb_mbfw_add_additional_data_in_cart( $cart_item_data, $product_id, $variation_id, $quantity ) {
		$product = wc_get_product( $product_id );
		if ( is_object( $product ) && 'mwb_booking' === $product->get_type() ) {
			// phpcs:disable:WordPress.Security.NonceVerification
			$cart_item_data['mwb_mbfw_people_number']    = array_key_exists( 'mwb_mbfw_people_number', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_people_number'] ) ) : '';
			$cart_item_data['mwb_mbfw_service_option']   = array_key_exists( 'mwb_mbfw_service_option_checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_option_checkbox'] ), 'sanitize_text_field' ) : '';
			$cart_item_data['mwb_mbfw_service_quantity'] = array_key_exists( 'mwb_mbfw_service_quantity', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_service_quantity'] ) ) : '';
			// phpcs:enable:WordPress.Security.NonceVerification
		}
		return $cart_item_data;
	}
	/**
	 * Show addiditional data on cart and checkout page.
	 *
	 * @param array $other_data array containing other data.
	 * @param array $cart_item array containing cart items.
	 * @return void
	 */
	public function mwb_mbfw_show_additional_data_on_cart_and_checkout_page( $other_data, $cart_item ) {
		if ( isset ( $cart_item['mwb_mbfw_people_number'] ) && $cart_item['mwb_mbfw_people_number'] ) {
			$other_data[] =  array(
				'name'    => _n( 'People', 'Peoples', $cart_item['mwb_mbfw_people_number'], 'mwb-bookings-for-woocommerce' ),
				'display' => wp_kses_post( $cart_item['mwb_mbfw_people_number'] ),
			);
		}
		if ( isset( $cart_item['mwb_mbfw_service_option'] ) ) {
			$selected_services = $cart_item['mwb_mbfw_service_option'];
			if ( is_array( $selected_services ) ) {
				$service_name = array();
				foreach ( $selected_services as $service ) {
					$term           = get_term( $service );
					$service_name[] = isset( $term->name ) ? $term->name : __( 'not found', 'mwb-bookings-for-woocommerce' );
				}
				if ( $service_name ) {
					$other_data[] =  array(
						'name'    => _n( 'Service', 'Services', count( $service_name ) ),
						'display' => join( ',', $service_name ),
					);
				}
			}
		}
		return $other_data;
	}

}
