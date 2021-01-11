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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
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

		wp_localize_script(
			$this->plugin_name,
			'mwb_wc_bk_public',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'mwb_wc_bk_public' ),
			)
		);

		add_thickbox();

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
		if ( $product && $product->is_type( 'mwb_booking' ) ) {
			?>
			<div id="mwb-wc-bk-create-booking-form">
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
		$product_id = sanitize_text_field( wp_unslash( $_POST['product_id'] ) );
		$duration   = sanitize_text_field( wp_unslash( $_POST['duration'] ) );
		$price      = get_post_meta( $product_id, 'mwb_booking_unit_cost_input', true );
		$price      = $price * $duration;
		$product    = wc_get_product( $product_id );
		$price_html = wc_price( $price );
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

		// echo '<pre>';
		// print_r( $cart_item_data );
		// echo '</pre>';
		// die( "kjbh" );

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
	 * Undocumented function
	 *
	 * @return void
	 */
	public function mwb_people_set() {
		$people_select = ! empty( $_POST['people'] ) ?  $_POST['people'] : array();
		if ( is_array( $people_select ) ) {
			foreach ( $people_select as $k => $v ) {
				$people_term = get_term( $v );
				$people_name = $people_term->name;
				?>
		<div>
			<label for=""><?php echo esc_html( $people_name ); ?></label>
			<input type="number" id="mwb_people_type_<?php echo esc_html( $people_name ); ?>" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" >
		</div>
				<?php
				wp_die();
				// echo '<pre>'; print_r( $people_term->name ); echo '</pre>';
			}
		}
	}
}
