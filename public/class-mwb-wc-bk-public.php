<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mwb_Wc_Bk_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance for the MWB_Woocommerce_Booking class
	 *
	 * @var obj
	 */
	public $mwb_booking;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// $this->mwb_booking = MWB_Woocommerce_Booking::get_booking_instance();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Wc_Bk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Wc_Bk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb-wc-bk-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'wp-jquery-ui-dialog' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Wc_Bk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Wc_Bk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-wc-bk-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'jquery-ui-dialog' );

		$args = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'mwb_wc_bk_public' ),
		);

		if ( is_product() ) {
			$args['product_settings'] = get_post_meta( get_the_id() );
			// echo '<pre>'; print_r( $args['product_settings'] ); echo '</pre>'; die("ok");
		}

		wp_localize_script(
			$this->plugin_name,
			'mwb_wc_bk_public',
			$args
		);

		// add_thickbox();

	}

	/**
	 * Add the form to the product type 'mwb_booking'.
	 *
	 * @return void
	 */
	public function mwb_include_booking_add_to_cart() {
		global $product;
		if ( $product && $product->is_type( 'mwb_booking' ) ) {

			wc_get_template( 'single-product/add-to-cart/mwb-booking.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
		}
	}

	/**
	 * Add the form fields to the Product of type 'mwb_booking'
	 *
	 * @return void
	 */
	public function mwb_booking_add_to_cart_form_fields() {
		global $product;
		$product_data = array(
			'product_id' => $product->get_id(),
		);
		// $this->mwb_booking = MWB_Woocommerce_Booking::get_booking_instance();
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			?>
			<div id="mwb-wc-bk-create-booking-form" product-data = "<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>" >
				<?php
					wc_get_template( 'single-product/add-to-cart/form/duration-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/dates-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/people-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
					wc_get_template( 'single-product/add-to-cart/form/service-check.php', array(), '', MWB_WC_BK_TEMPLATE_PATH );
				?>
			</div>
			<?php
		}
	}

	/**
	 * Ajax Handler for the Added form fields for the product.
	 *
	 * @return void
	 */
	public function mwb_wc_bk_update_add_to_cart() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'mwb_wc_bk_public' ) ) {
			die( 'Nonce value cannot be verified' );
		}
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$duration   = isset( $_POST['duration'] ) ? sanitize_text_field( wp_unslash( $_POST['duration'] ) ) : '';

		$product_meta  = get_post_meta( $product_id );
		$duration_cost = 0;

		$price       = ! empty( $product_meta['mwb_booking_unit_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_input'][0] ) ) : '';
		$added_costs = ! empty( $product_meta['mwb_booking_added_cost_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_added_cost_select'][0] ) : '';

		$duration_cost += ( $price * $duration );
		// echo '<pre>'; print_r( $duration_cost ); echo '</pre>';die( 'ok' );

		if ( is_array( $added_costs ) && ! empty( $added_costs ) ) {
			foreach ( $added_costs as $cost_id ) {
				// $cost_term      = get_term( $cost_id );
				$cost_term_meta = get_term_meta( $cost_id );
				// echo '<pre>'; print_r( $cost_term_meta ); echo '</pre>';die("ok");
				if ( ! empty( $cost_term_meta['mwb_booking_ct_costs_multiply_units'][0] ) && 'yes' === $cost_term_meta['mwb_booking_ct_costs_multiply_units'][0] ) {
					// die("ok");
					$cost_price     = ! empty( $cost_term_meta['mwb_booking_ct_costs_custom_price'][0] ) ? $cost_term_meta['mwb_booking_ct_costs_custom_price'][0] : 0;
					$duration_cost += ( $cost_price * $duration );
				}
			}
		}
		$product    = wc_get_product( $product_id );
		$price_html = wc_price( $duration_cost );
		echo wp_json_encode(
			array(
				'price_html' => $price_html,
				'success'    => true,
			)
		);
		wp_die();
	}

	/**
	 * Add the additional product data to the cart items
	 *
	 * @param array  $cart_item_data Array of any other cart item data for the product.
	 * @param number $product_id     ID of the product adding to the cart.
	 * @return array
	 */
	public function mwb_wc_bk_add_cart_item_data( $cart_item_data, $product_id ) {

		$product = wc_get_product( $product_id );
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			if ( ! isset( $cart_item_data['mwb_wc_bk_cart_data'] ) ) {
				$posted_data                      = $_REQUEST;
				$booking_data                     = $this->mwb_wc_bk_get_product_data( $posted_data );
				$cart_item_data['mwb_wc_bk_data'] = $booking_data;
			}
		}
		// echo '<pre>';
		// print_r( $cart_item_data );
		// print_r( $_REQUEST );
		// echo '</pre>';

		// die( "kjbh" );
		return $cart_item_data;
	}

	/**
	 * Function to get the Product's added fields Data.
	 *
	 * @param array $posted_data parameter.
	 * @return array
	 */
	public function mwb_wc_bk_get_product_data( $posted_data ) {

		$booking_data = array( 'duration' => 1 );
		$duration     = isset( $posted_data['duration'] ) ? $posted_data['duration'] : 1;

		$booking_data['duration'] = $duration;
		return $booking_data;
	}

	/**
	 * Change Price for Product (while add to cart) according to the form fields.
	 *
	 * @param array  $cart_item_data Data for the cart item's products.
	 * @param string $cart_item_key  Key for the cart items array.
	 * @return array
	 */
	public function mwb_wc_bk_add_cart_item( $cart_item_data, $cart_item_key ) {

		$product_id = isset( $cart_item_data['product_id'] ) ? $cart_item_data['product_id'] : 0;
		$_product   = wc_get_product( $product_id );
		if ( $_product && $_product->is_type( 'mwb_booking' ) ) {
			$product      = $cart_item_data['data'];
			$booking_data = $cart_item_data['mwb_wc_bk_data'];
			$price        = $this->get_booking_product_price( $product, $booking_data );
			$product->set_price( $price );
			$cart_item_data['data'] = $product;
		}
		return $cart_item_data;
	}

	/**
	 * Getting tthe updated product price according to the form fields
	 *
	 * @param object $product      Product object.
	 * @param array  $booking_data Cart item data for 'mwb_booking'.
	 * @return number
	 */
	public function get_booking_product_price( $product, $booking_data ) {
		$price    = $product->get_price();
		$duration = $booking_data['duration'];
		return $price * $duration;
	}

	/**
	 * Change Price for Product (while add to cart) according to the form fields using session data.
	 *
	 * @param array  $session_data  Cart Item Product Data stored in the session.
	 * @param array  $cart_item     Items in the cart.
	 * @param string $cart_item_key Key for the cart items array.
	 * @return array
	 */
	public function mwb_wc_bk_get_cart_item_from_session( $session_data, $cart_item, $cart_item_key ) {
		$product_id = isset( $cart_item['product_id'] ) ? $cart_item['product_id'] : 0;
		$_product   = wc_get_product( $product_id );
		if ( $_product && $_product->is_type( 'mwb_booking' ) ) {
			$product      = $session_data['data'];
			$booking_data = $session_data['mwb_wc_bk_data'];
			$price        = $this->get_booking_product_price( $product, $booking_data );
			$product->set_price( $price );
			$session_data['data'] = $product;
		}
		return $session_data;
	}

	/**
	 * Display the added product data in the cart.
	 *
	 * @param array $item_data      Array containing additional data for our cart item.
	 * @param array $cart_item_data Array of our cart item and its associated data.
	 * @return array
	 */
	public function mwb_wc_bk_get_item_data( $item_data, $cart_item_data ) {

		if ( empty( $cart_item_data['mwb_wc_bk_data'] ) ) {
			return $item_data;
		}

		$product_id = isset( $cart_item_data['product_id'] ) ? $cart_item_data['product_id'] : 0;
		$product    = wc_get_product( $product_id );
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			$booking_data      = $cart_item_data['mwb_wc_bk_data'];
			$booking_item_data = array();

			$booking_item_data['mwb_wc_bk_duration'] = array(
				'key'     => 'duration',
				'value'   => $booking_data['duration'],
				'display' => '',
			);

			$item_data = array_merge( $item_data, $booking_item_data );
		}
		// echo '<pre>';
		// // print_r( $cart_item_data );
		// print_r( $item_data );
		// echo '</pre>';
		// // die( "kjbh" );

		return $item_data;
	}

	/**
	 * Add custom added product data to the order.
	 *
	 * @param object $item          order line item object.
	 * @param string $cart_item_key the string containing our cart item key.
	 * @param array  $values        array containing all the data for our cart item, including our custom data.
	 * @param object $order         WC_Order instance containing the new order object.
	 * @return void
	 */
	public function mwb_wc_bk_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		if ( empty( $values['mwb_wc_bk_data'] ) ) {
			return;
		}

		$booking_data = array();
		if ( isset( $values['mwb_wc_bk_data'] ) && is_array( $values['mwb_wc_bk_data'] ) ) {
			$booking_data = $values['mwb_wc_bk_data'];
		}
		if ( ! empty( $booking_data ) ) {
			$item->add_meta_data( 'mwb_wc_bk_data', $booking_data, true );
			if ( isset( $booking_data['mwb_wc_bk_id'] ) ) {
				$item->add_meta_data( 'mwb_wc_bk_id', $booking_data['mwb_wc_bk_id'], true );
			}
		}
	}

	/**
	 * Preparing the Booking Order
	 *
	 * @param number $order_id   ID of the order created.
	 * @param array  $posted_data array for the posted data.
	 *
	 * @return void
	 */
	public function mwb_wc_bk_check_order_booking( $order_id, $posted_data = array() ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		$order_items = $order->get_items();
		if ( ! $order_items ) {
			return;
		}

		foreach ( $order_items as $order_item_id => $order_item ) {
			if ( $order_item->is_type( 'line_item' ) ) {
				$product = $order_item->get_product();
				if ( ! $product || ! ( $product->is_type( 'mwb_booking' ) ) ) {
					continue;
				}
				$args = array(
					'order_id'   => $order_id,
					'product_id' => $product->get_id(),
					'status'     => $order->get_status(),
					'user_id'    => $order->get_user_id(),
				);

				$booking_data = $order_item->get_meta( 'mwb_wc_bk_data' );
				$booking_id   = $order_item->get_meta( 'mwb_wc_bk_id' );
				if ( ! $booking_id && ! empty( $booking_data ) ) {
					$booking_id = $this->mwb_wc_bk_create_booking( $args );
					if ( $booking_id ) {
						$order_item->add_meta_data( 'mwb_wc_bk_id', $booking_id, true );
						$order_item->save_meta_data();
						$order->add_order_note( sprintf( __( 'A new booking <a href="%s">#%s</a> has been created from this order', 'mwb-wc-bk' ), admin_url( 'post.php?post=' . $booking_id . '&action=edit' ), $booking_id ) );
					}
				}
			}
		}
	}

	/**
	 * Fetching the Order ID after inserting the post.
	 *
	 * @param array $args Arguments.
	 *
	 * @return number $booking_id
	 */
	public function mwb_wc_bk_create_booking( $args ) {
		$title      = 'Booking for order #' . $args['order_id'];
		$booking_id = wp_insert_post(
			array(
				'post_type'  => 'mwb_cpt_booking',
				'post_title' => $title,
			)
		);
		update_post_meta( $booking_id, '_customer_user', $args['user_id'] );
		return $booking_id;
	}

	/**
	 * Calculation for the booking price
	 *
	 * @return void
	 */
	public function booking_price_cal() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'mwb_wc_bk_public' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		// echo '<pre>'; print_r( $_POST ); echo '</pre>';die("ok");
		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$people_total = ! empty( $_POST['people_total'] ) ? sanitize_text_field( wp_unslash( $_POST['people_total'] ) ) : array();

		$product_meta = get_post_meta( $product_id );

		$people_select       = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_people_select'][0] ) : '';
		$people_enable_check = ! empty( $product_meta['mwb_people_enable_checkbox'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_people_enable_checkbox'][0] ) ) : '';
		$people_type_check   = ! empty( $product_meta['mwb_enable_people_types'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_people_types'][0] ) ) : '';
		$added_costs         = ! empty( $product_meta['mwb_booking_added_cost_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_added_cost_select'][0] ) : '';

		$people          = array();
		$people_data     = array();
		$added_cost_data = array();

		$booking_people_cost = 0;
		$booking_added_cost  = 0;
		$booking_cost        = 0;

		if ( is_array( $people_select ) ) {
			foreach ( $people_select as $k => $v ) {
				$people_term      = get_term( $v );
				$people_term_meta = get_term_meta( $v );
				$people_name      = $people_term->name;
				$people[ $v ]     = ! empty( $_POST['people_count'][ $v ] ) ? sanitize_text_field( wp_unslash( $_POST['people_count'][ $v ] ) ) : '';

				$people_data[ $v ] = array(
					'name'         => $people_term->name,
					'term_id'      => $v,
					'people_count' => ! empty( $people[ $v ] ) ? $people[ $v ] : 0,
				);
				foreach ( $people_term_meta as $key => $value ) {
					$people_data[ $v ]['people_meta'][ $key ] = ! empty( $value[0] ) ? $value[0] : '';
				}
			}
		}
		// echo '<pre>'; print_r( $people_data ); echo '</pre>';
		$unit_cost          = ! empty( $product_meta['mwb_booking_unit_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_input'][0] ) ) : '';
		$unit_cost_multiply = ! empty( $product_meta['mwb_booking_unit_cost_multiply'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_unit_cost_multiply'][0] ) ) : '';
		$base_cost          = ! empty( $product_meta['mwb_booking_base_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_input'][0] ) ) : '';
		$base_cost_multiply = ! empty( $product_meta['mwb_booking_base_cost_multiply'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_base_cost_multiply'][0] ) ) : '';
		$extra_cost         = ! empty( $product_meta['mwb_booking_extra_cost_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_extra_cost_input'][0] ) ) : '';
		$extra_cost_people  = ! empty( $product_meta['mwb_booking_extra_cost_people_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_extra_cost_people_input'][0] ) ) : '1';

		$enabled_services = ! empty( $product_meta['mwb_booking_services_select'][0] ) ? maybe_unserialize( sanitize_text_field( wp_unslash( $product_meta['mwb_booking_services_select'][0] ) ) ) : '';

		$discount_type = ! empty( $product_meta['mwb_booking_cost_discount_type'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_cost_discount_type'][0] ) ) : '';

		$monthly_discount = ! empty( $product_meta['mwb_booking_monthly_discount_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_monthly_discount_input'][0] ) ) : '';
		$weekly_discount  = ! empty( $product_meta['mwb_booking_weekly_discount_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_weekly_discount_input'][0] ) ) : '';
		$custom_discount  = ! empty( $product_meta['mwb_booking_custom_days_discount_input'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_custom_days_discount_input'][0] ) ) : '';
		$custom_disc_days = ! empty( $product_meta['mwb_booking_custom_discount_days'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_booking_custom_discount_days'][0] ) ) : '';

		if ( is_array( $people_select ) && ! empty( $people_select ) ) {
			foreach ( $people_select as $id ) {
				if ( ! empty( $unit_cost_multiply ) && 'yes' === $unit_cost_multiply ) {
					if ( ! empty( $people_data[ $id ]['people_count'] ) ) {
						if ( isset( $people_data[ $id ]['people_meta'] ) ) {
							$booking_people_cost += ( isset( $people_data[ $id ]['people_meta']['mwb_ct_booking_people_unit_cost'] ) ? $people_data[ $id ]['people_meta']['mwb_ct_booking_people_unit_cost'] : $unit_cost ) * $people_data[ $id ]['people_count'];
						} else {
							$booking_people_cost += $unit_cost * $people_data[ $id ]['people_count'];
						}
					}
				}
				if ( ! empty( $base_cost_multiply ) && 'yes' === $base_cost_multiply ) {
					if ( $people_data[ $id ]['people_count'] ) {
						if ( isset( $people_data[ $id ]['people_meta'] ) ) {
							$booking_people_cost += ( isset( $people_data[ $id ]['people_meta']['mwb_ct_booking_people_base_cost'] ) ? $people_data[ $id ]['people_meta']['mwb_ct_booking_people_base_cost'] : $base_cost ) * $people_data[ $id ]['people_count'];
						} else {
							$booking_people_cost += $base_cost * $people_data[ $id ]['people_count'];
						}
					}
				}
			}
		}
		if ( empty( $unit_cost_multiply ) || 'no' === $unit_cost_multiply ) {

			$booking_people_cost += $unit_cost;

			if ( ! empty( $extra_cost ) ) {
				if ( ! empty( $people_total ) ) {
					if ( ! empty( $extra_cost_people ) ) {
						$booking_people_cost += $extra_cost * floor( $people_total / $extra_cost_people );
					} else {
						$booking_people_cost += $extra_cost;
					}
				}
			}
		}
		if ( empty( $base_cost_multiply ) || 'no' === $base_cost_multiply ) {
			$booking_people_cost += $base_cost;
		}

		if ( is_array( $added_costs ) && ! empty( $added_costs ) ) {
			foreach ( $added_costs as $cost_id ) {
				$cost_term      = get_term( $cost_id );
				$cost_term_meta = get_term_meta( $cost_id );

				$added_cost_data[ $cost_id ] = array(
					'name'    => $cost_term->name,
					'term_id' => $cost_id,
				);
				foreach ( $cost_term_meta as $k => $v ) {

					$added_cost_data[ $cost_id ]['cost_meta'][ $k ] = ! empty( $v[0] ) ? $v[0] : '';
				}
			}
		}
		// echo '<pre>'; print_r( $added_cost_data ); echo '</pre>';die('ok');
		if ( is_array( $added_costs ) && ! empty( $added_costs ) ) {
			foreach ( $added_costs as $cost_id ) {
				if ( ! empty( $people_total ) ) {
					// echo '<pre>'; print_r( $added_cost_data[ $cost_id ] ); echo '</pre>';
					if ( isset( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) && 'yes' === $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) {
						$booking_added_cost += ( ( isset( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] ) ? $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] : 0 ) * $people_total );
					} elseif ( empty( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) || 'no' === $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_multiply_people'] ) {
						// die('ok');
						$booking_added_cost += ( isset( $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] ) ? $added_cost_data[ $cost_id ]['cost_meta']['mwb_booking_ct_costs_custom_price'] : 0 );
					}
				}
			}
		}

		$booking_service_cost = 0;

		$enable_people_type  = ! empty( $product_meta['mwb_enable_people_types'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_people_types'][0] ) ) : '';
		if ( ! empty( $enabled_services ) && is_array( $enabled_services ) ) {
			foreach ( $enabled_services as $service_id ) {

				$service_term_meta = get_term_meta( $service_id );
				$service_cost      = ! empty( $service_term_meta['mwb_ct_booking_service_cost'][0] ) ? $service_term_meta['mwb_ct_booking_service_cost'][0] : 0;
				$if_optional       = ! empty( $service_term_meta['mwb_booking_ct_services_optional'][0] ) ? $service_term_meta['mwb_booking_ct_services_optional'][0] : 'no';
				$has_quantity      = ! empty( $service_term_meta['mwb_booking_ct_services_has_quantity'][0] ) ? $service_term_meta['mwb_booking_ct_services_has_quantity'][0] : 'no';
				// echo '<pre>'; print_r( $service_cost ); echo '</pre>';
				if ( 'yes' === $has_quantity ) {
					if ( 'yes' === $if_optional ) {
						$service_count = ! empty( $_POST['add_service_count'][ $service_id ] ) ? sanitize_text_field( wp_unslash( $_POST['add_service_count'][ $service_id ] ) ) : 1;
					} else {
						$service_count = ! empty( $_POST['inc_service_count'][ $service_id ] ) ? sanitize_text_field( wp_unslash( $_POST['inc_service_count'][ $service_id ] ) ) : 1;
					}
				} else {
					$service_count = 1;
				}
				// echo '<pre>'; print_r( $service_cost ); echo '</pre>';
				if ( 'yes' === $enable_people_type ) {
					if ( isset( $service_term_meta['mwb_booking_ct_services_multiply_people'][0] ) && ( 'yes' === $service_term_meta['mwb_booking_ct_services_multiply_people'][0] ) ) {
						if ( ! empty( $people_select ) ) {
							foreach ( $people_select as $people_id ) {
								$people_term           = get_term( $people_id );
								$service_people_cost   = ! empty( $service_term_meta[ 'mwb_ct_booking_service_cost_' . $people_term->slug ][0] ) ? $service_term_meta[ 'mwb_ct_booking_service_cost_' . $people_term->slug ][0] : $service_cost;
								$booking_service_cost += $service_count * $service_people_cost;
								$booking_service_cost += $people_data[ $people_id ]['people_count'] * $service_people_cost;
							}
						}
						// $service_people_cost = ! empty( $service_term_meta[''] );
					} else {
						$booking_service_cost += $service_count * $service_cost;
					}
				}
			}
		}

		$booking_cost = $booking_people_cost + $booking_added_cost + $booking_service_cost;
		// echo '<pre>'; print_r( $added_cost_data ); echo '</pre>';die("ok");
		// $id = $this->mwb_booking->get_booking_product_id();

		// $formdata = ! empty( $_POST[ 'formdata' ] ) ? $_POST[ 'formdata' ] : array();

		// $formatted = array();
		// parse_str( $formdata, $formatted );

		// $id = ! empty( $product->get_id() ) ? $product->get_id() : '1';
		// $product_meta  = get_post_meta( $product->get_id() );
		// $people_select = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? $product_meta['mwb_booking_people_select'][0] : '';

		// $people_total = ! empty( $_POST['people_total'] ) ? sanitize_text_field( wp_unslash( $_POST['people_total'] ) ) : array();
		// $arr          = array();
		// foreach ( $people_select as $id ) {
		// 	$arr[ $id ] = ! empty( $_POST[ $id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $id ] ) ) : '';
		// }
		// $arr['people_total'] = $people_total;

		$product    = wc_get_product( $product_id );
		$price_html = wc_price( $booking_cost );
		echo wp_json_encode(
			array(
				'price_html'           => $price_html,
				'success'              => true,
				'booking_people_cost'  => $booking_people_cost,
				'booking_service_cost' => $booking_service_cost,
			)
		);
		wp_die();
	}

	/**
	 * Calculation of Booking Service Cost.
	 *
	 * @return void
	 */
	public function booking_service_cal() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'mwb_wc_bk_public' ) ) {
			die( 'Nonce value cannot be verified' );
		}

		$product_id           = ! empty( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$booking_service_cost = 0;

		$product_meta = get_post_meta( $product_id );

		$enabled_services = ! empty( $product_meta['mwb_booking_services_select'][0] ) ? maybe_unserialize( sanitize_text_field( wp_unslash( $product_meta['mwb_booking_services_select'][0] ) ) ) : '';
		$people_select    = ! empty( $product_meta['mwb_booking_people_select'][0] ) ? maybe_unserialize( $product_meta['mwb_booking_people_select'][0] ) : '';

		// $people_enable_check = ! empty( $product_meta['mwb_people_enable_checkbox'][0] ) ? $product_meta['mwb_people_enable_checkbox'][0] : '';
		$enable_people_type  = ! empty( $product_meta['mwb_enable_people_types'][0] ) ? sanitize_text_field( wp_unslash( $product_meta['mwb_enable_people_types'][0] ) ) : '';
		if ( ! empty( $enabled_services ) && is_array( $enabled_services ) ) {
			foreach ( $enabled_services as $service_id ) {
				$service_term_meta = get_term_meta( $service_id );
				$service_cost      = ! empty( $service_term_meta['mwb_ct_booking_service_cost'] ) ? $service_term_meta['mwb_ct_booking_service_cost'] : 0;
				// echo '<pre>'; print_r( $service_term_meta ); echo '</pre>';die("ok");
				if ( 'yes' === $enable_people_type ) {
					if ( isset( $service_term_meta['mwb_booking_ct_services_multiply_people'][0] ) && ( 'yes' === $service_term_meta['mwb_booking_ct_services_multiply_people'][0] ) ) {
						if ( ! empty( $people_select ) ) {
							foreach ( $people_select as $people_id ) {
								$people_term         = get_term( $people_id );
								$service_people_cost = ! empty( $service_term_meta[ 'mwb_ct_booking_service_cost_' . $people_term->slug ][0] ) ? $service_term_meta[ 'mwb_ct_booking_service_cost_' . $people_term->slug ][0] : $service_cost;
							}
						}
						// $service_people_cost = ! empty( $service_term_meta[''] );
					}
				}
			}
		}
		echo wp_json_encode( $enabled_services );
		wp_die();
	}
}
