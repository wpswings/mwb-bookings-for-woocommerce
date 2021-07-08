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
		wp_enqueue_style( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/css/mwb-public.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui', MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/jquery-ui-css/jquery-ui.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function mbfw_public_enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, MWB_BOOKINGS_FOR_WOOCOMMERCE_DIR_URL . 'public/js/mwb-public.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'jquery-ui-datepicker' );
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
			<div><?php esc_html_e( 'Included services', 'mwb-bookings-for-woocommerce' ); ?></div>
			<div class="mbfw-additionl-detail-listing-section__wrapper">
				<?php
				foreach ( $mbfw_booking_service as $custom_term ) {
					if ( 'yes' !== get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_hidden', true ) ) {
						?>
						<div class="mbfw-additionl-detail-listing-section">
							<?php if ( 'yes' === get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_optional', true ) ) { ?>
								<input type="checkbox" value="<?Php echo esc_attr( $custom_term->term_id ); ?>" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_option_checkbox[]" id="mwb-mbfw-service-option-checkbox-<?php echo esc_attr( $custom_term->term_id ); ?>" class="mwb-mbfw-additional-service-option" />
							<?php } ?>
							<span><?php echo esc_html( $custom_term->name ); ?></span>
						</div>
						<div class="mbfw-additionl-detail-listing-section">
							<?php echo wp_kses_post( wc_price( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_cost', true ) ) ); ?>
						</div>
						<div class="mbfw-additionl-detail-listing-section">
							<?php if ( get_term_meta( $custom_term->term_id, 'mwb_mbfw_is_service_has_quantity', true ) ) { ?>
								<input type="number" value="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ); ?>" data-term-id="<?php echo esc_attr( $custom_term->term_id ); ?>" name="mwb_mbfw_service_quantity[<?php echo esc_attr( $custom_term->term_id ); ?>]" min="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_minimum_quantity', true ) ); ?>" max="<?php echo esc_attr( get_term_meta( $custom_term->term_id, 'mwb_mbfw_service_maximum_quantity', true ) ); ?>" class="mwb-mbfw-additional-service-quantity" />
							<?php } ?>
						</div>
					<?php
					}
				}
				?>
			</div>
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
			<div class="mbfw-additionl-detail-listing-section__wrapper">
				<div class="mbfw-additionl-detail-listing-section">
					<?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?>
				</div>
				<div class="mbfw-additionl-detail-listing-section">
					<input type="number" name="mwb_mbfw_people_number" class="mwb_mbfw_people_number" id="mwb_mbfw_people_number" value="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?>" min="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?>" max="<?php echo esc_attr( get_post_meta( $product_id, 'mwb_mbfw_maximum_people_per_booking', true ) ); ?>" required>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Add date selector on single product listing page.
	 *
	 * @return void
	 */
	public function mwb_mbfw_show_date_time_selector_on_single_product_page( $product_id, $product ) {
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_enable_calender', true ) ) {
			?>
			<div class="mbfw-additionl-detail-listing-section__wrapper">
				<div class="mbfw-additionl-detail-listing-section">
					<label for="mwb-mbfw-single-booking-date-selector-from"><?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?></label>
					<input type="text" name="mwb_mbfw_booking_from_date" id="mwb-mbfw-single-booking-date-selector-from" autocomplete="off" required>
				</div>
				<div class="mbfw-additionl-detail-listing-section">
					<label for="mwb-mbfw-single-booking-date-selector-to"><?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?></label>
					<input type="text" name="mwb_mbfw_booking_to_date" id="mwb-mbfw-single-booking-date-selector-to" autocomplete="off" required>
				</div>
			</div>
			<?php
		}
		if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_enable_time_picker', true ) ) {
			?>
			<div class="mbfw-additionl-detail-listing-section__wrapper">
				<div class="mbfw-additionl-detail-listing-section">
					<label for="mwb-mbfw-single-booking-time-selector-from"><?php esc_html_e( 'From', 'mwb-bookings-for-woocommerce' ); ?></label>
					<input type="time" name="mwb_mbfw_booking_from_time" id="mwb-mbfw-single-booking-time-selector-from" required>
				</div>
				<div class="mbfw-additionl-detail-listing-section">
					<label for="mwb-mbfw-single-booking-time-selector-to"><?php esc_html_e( 'To', 'mwb-bookings-for-woocommerce' ); ?></label>
					<input type="time" name="mwb_mbfw_booking_to_time" id="mwb-mbfw-single-booking-time-selector-to" required>
				</div>
			</div>
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
			$cart_item_data['mwb_mbfw_people_number']           = array_key_exists( 'mwb_mbfw_people_number', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_people_number'] ) ) : '';
			$cart_item_data['mwb_mbfw_service_option_checkbox'] = array_key_exists( 'mwb_mbfw_service_option_checkbox', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_option_checkbox'] ), 'sanitize_text_field' ) : array();
			$cart_item_data['mwb_mbfw_service_quantity']        = array_key_exists( 'mwb_mbfw_service_quantity', $_POST ) ? map_deep( wp_unslash( $_POST['mwb_mbfw_service_quantity'] ), 'sanitize_text_field' ) : array();
			$cart_item_data['mwb_mbfw_enable_calendar']         = 'no';
			$cart_item_data['mwb_mbfw_enable_time_picker']      = 'no';
			if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_enable_calendar', true ) ) {
				$cart_item_data['mwb_mbfw_enable_calendar']   = 'yes';
				$cart_item_data['mwb_mbfw_booking_from_date'] = array_key_exists( 'mwb_mbfw_booking_from_date', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_from_date'] ) ) : '';
				$cart_item_data['mwb_mbfw_booking_to_date']   = array_key_exists( 'mwb_mbfw_booking_to_date', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_to_date'] ) ) : '';
			}
			if ( 'yes' === get_post_meta( $product_id, 'mwb_mbfw_enable_time_picker', true ) ) {
				$cart_item_data['mwb_mbfw_enable_time_picker'] = 'yes';
				$cart_item_data['mwb_mbfw_booking_from_time']  = array_key_exists( 'mwb_mbfw_booking_from_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_from_time'] ) ) : '';
				$cart_item_data['mwb_mbfw_booking_to_time']    = array_key_exists( 'mwb_mbfw_booking_to_time', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_to_time'] ) ) : '';
			}
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
		$terms            = get_the_terms( $cart_item['product_id'], 'mwb_booking_service' );
		$service_name     = array();
		$service_quantity = isset( $cart_item['mwb_mbfw_service_quantity'] ) ? $cart_item['mwb_mbfw_service_quantity'] : array();
		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( 'yes' !== get_term_meta( $term->term_id, 'mwb_mbfw_is_service_optional', true ) ) {
					$service_count  = array_key_exists( $term->term_id, $service_quantity ) ? $service_quantity[ $term->term_id ] : 1;
					$service_name[] = isset( $term->name ) ? $term->name . '( ' . $service_count . ' )' : __( 'not found', 'mwb-bookings-for-woocommerce' );
				}
			}
		}
		if ( isset( $cart_item['mwb_mbfw_service_option_checkbox'] ) ) {
			$selected_services = $cart_item['mwb_mbfw_service_option_checkbox'];
			if ( is_array( $selected_services ) ) {
				foreach ( $selected_services as $term_id ) {
					$term           = get_term( $term_id );
					$service_count  = array_key_exists( $term_id, $service_quantity ) ? $service_quantity[ $term_id ] : 1;
					$service_name[] = isset( $term->name ) ? $term->name . '( ' . $service_count . ' )' : __( 'not found', 'mwb-bookings-for-woocommerce' );
				}
			}
		}
		if ( $service_name ) {
			$other_data[] =  array(
				'name'    => _n( 'Service', 'Services', count( $service_name ), 'mwb-bookings-for-woocommerce' ),
				'display' => join( ', ', $service_name ),
			);
		}
		if ( isset( $cart_item['mwb_mbfw_booking_from_date'] ) && isset( $cart_item['mwb_mbfw_booking_to_date'] ) ) {
			$other_data[] = array(
				'name'    => __( 'From Date', 'mwb-bookings-for-woocommerce' ),
				'display' => wp_kses_post( $cart_item['mwb_mbfw_booking_from_date'] ),
			);
			$other_data[] = array(
				'name'    => __( 'To Date', 'mwb-bookings-for-woocommerce' ),
				'display' => wp_kses_post( $cart_item['mwb_mbfw_booking_to_date'] ),
			);
		}
		if ( isset( $cart_item['mwb_mbfw_booking_from_time'] ) && isset( $cart_item['mwb_mbfw_booking_to_time'] ) ) {
			$other_data[] = array(
				'name'    => __( 'From Time', 'mwb-bookings-for-woocommerce' ),
				'display' => wp_kses_post( $cart_item['mwb_mbfw_booking_from_time'] ),
			);
			$other_data[] = array(
				'name'    => __( 'To Time', 'mwb-bookings-for-woocommerce' ),
				'display' => wp_kses_post( $cart_item['mwb_mbfw_booking_to_time'] ),
			);
		}
		return $other_data;
	}

	/**
	 * Show read more button on archieve page.
	 *
	 * @param string $button string containing html of the add to cart button.
	 * @param object $product product object of the current looping item.
	 * @return string
	 */
	public function mwb_mbfw_show_readmore_button_on_archieve_page( $button, $product ) {
		if ( 'mwb_booking' === $product->get_type() ) {
			$button_text = __( 'View Details', 'mwb-bookings-for-woocommerce' );
			$button      = '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
		}
		return $button;
	}

	/**
	 * Remove Quanitity Field.
	 *
	 * @param boolean $return return true or false based on if quantity field is required.
	 * @param object  $product current product object.
	 * @return boolean
	 */
	public function mwb_mbfw_remove_quantity_field( $return, $product ) {
		// if ( 'mwb_booking' === $product->get_type() ) {
		// 	$return = true;
		// }
		return $return;
	}
	public function mwb_mbfw_set_max_quantity_to_be_booked_by_individual( $args, $product ) {
		if ( 'mwb_booking' === $product->get_type() ) {
			$args['max_value'] = get_post_meta( $product->get_id(), 'mwb_mbfw_maximum_booking_per_unit', true );
		}
		return $args;
	}


	/**
	 * Updating additional meta data with products in line items.
	 *
	 * @param object $item object containing the item details.
	 * @param string $cart_item_key string containing arbitrary key of cart items.
	 * @param array  $values array containing the values for the cart item key.
	 * @param object $order current order object.
	 * @return void
	 */
	public function mwb_mbfw_add_custom_order_item_meta_data( $item, $cart_item_key, $values, $order ) {
		$custom_values = $item->legacy_values;
		if ( 'mwb_booking' === $custom_values['data']->product_type ) {
			$line_item_meta                                = array();
			$line_item_meta['mwb_mbfw_people_number']      = isset( $custom_values['mwb_mbfw_people_number'] ) ? $custom_values['mwb_mbfw_people_number'] : 1;
			$line_item_meta['mwb_mbfw_enable_time_picker'] = 'no';
			$line_item_meta['mwb_mbfw_enable_calendar']    = 'no';
			$line_item_meta['mwb_mbfw_service_and_count']  = isset( $custom_values['mwb_mbfw_service_and_count'] ) ? $custom_values['mwb_mbfw_service_and_count'] : array();
			if ( 'yes' === $custom_values['mwb_mbfw_enable_calendar'] ) {
				$line_item_meta['mwb_mbfw_enable_calendar']   = 'yes';
				$line_item_meta['mwb_mbfw_booking_from_date'] = isset( $custom_values['mwb_mbfw_booking_from_date'] ) ? $custom_values['mwb_mbfw_booking_from_date'] : '';
				$line_item_meta['mwb_mbfw_booking_to_date']   = isset( $custom_values['mwb_mbfw_booking_to_date'] ) ? $custom_values['mwb_mbfw_booking_to_date'] : '';
			}
			if ( 'yes' === $custom_values['mwb_mbfw_enable_time_picker'] ) {
				$line_item_meta['mwb_mbfw_enable_time_picker'] = 'yes';
				$line_item_meta['mwb_mbfw_booking_from_time']  = isset( $custom_values['mwb_mbfw_booking_from_time'] ) ? $custom_values['mwb_mbfw_booking_from_time'] : '';
				$line_item_meta['mwb_mbfw_booking_to_time']    = isset( $custom_values['mwb_mbfw_booking_to_time'] ) ? $custom_values['mwb_mbfw_booking_to_time'] : '';
			}
			$terms = get_the_terms( $custom_values['product_id'], 'mwb_booking_cost' );
			if ( $terms && is_array( $terms ) ) {
				$term_ids = array();
				foreach ( $terms as $term ) {
					$term_ids[] = $term->term_id;
				}
				$line_item_meta['mwb_mbfw_booking_extra_costs'] = $term_ids;
			}
			foreach ( $line_item_meta as $meta_key => $meta_val ) {
				$item->update_meta_data( $meta_key, $meta_val );
			}
		}
	}
}