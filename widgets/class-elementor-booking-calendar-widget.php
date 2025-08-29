<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Booking Calendar Widget Class.
 *
 * @since      1.0.0
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/widgets
 */
class Elementor_Booking_Calendar_Widget extends \Elementor\Widget_Base {

	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'booking_calendar_widget';
	}

	/**
	 * Get the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Booking Calendar', 'mwb-bookings-for-woocommerce' );
	}

	/**
	 * Get the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-calendar';
	}

	/**
	 * Get the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * This method is used to define the controls that will be available in the Elementor editor.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Booking Calendar Settings', 'mwb-bookings-for-woocommerce' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'calendar_id',
			[
				'label'   => __( 'Booking Calendar ID', 'mwb-bookings-for-woocommerce' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * This method is used to render the widget output on the frontend of the site.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id = (int) $settings['calendar_id'];

		if ( ! $id ) {
			echo '<p>Please enter a valid Booking Calendar ID.</p>';
			return;
		}

		echo do_shortcode( '[bookable_booking_calendar id="' . esc_attr( $id ) . '"]' );
	}
}
