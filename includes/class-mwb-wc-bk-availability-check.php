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

    public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
    
        while( $current <= $last ) {
    
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
    
        return $dates;
    }

    public function check_advance_booking_availability( $start_date, $end_date ) {

        $prod_settings = get_post_meta( $this->booking_product_id );

        

    }

}



