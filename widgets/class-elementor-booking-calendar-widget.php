<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_Booking_Calendar_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'booking_calendar_widget';
	}

	public function get_title() {
		return __( 'Booking Calendar', 'your-plugin-textdomain' );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Booking Calendar Settings', 'your-plugin-textdomain' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'calendar_id',
			[
				'label'   => __( 'Booking Calendar ID', 'your-plugin-textdomain' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$this->end_controls_section();
	}

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
