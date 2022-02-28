(function( $ ) {
	'use strict';

	/**
	 * All of the code for your common JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    $(document).ready(function(){
		if ( $('.bfw_time_picker').length > 0 ) {
			$('.bfw_time_picker').timepicker();
		}
        $(document).on('change', 'form.cart :input', function(){
            var form_data = new FormData( $('form.cart')[0] );
			if ( $('.wps_bfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
        });
		$('#wps-mbfw-booking-from-time, #wps-mbfw-booking-to-time').on('keydown paste focus mousedown',function(e){
			e.preventDefault();
		});
		$('.wps_bfw_time_date_picker_frontend').datetimepicker({
			format  : 'd-m-Y H:i',
			minDate : wps_bfw_common_obj.minDate,
			minTime : wps_bfw_common_obj.minTime
		});
		$('.wps_bfw_date_picker_frontend').datetimepicker({
			format     : 'd-m-Y',
			timepicker : false,
			minDate    : wps_bfw_common_obj.minDate,
		});
		$('.wps_bfw_time_picker_frontend').datetimepicker({
			format     : 'H:i',
			datepicker : false,
		});
		$('#wps-mbfw-booking-from-time').on('change', function(){
			var from_time = $(this).val();
			var to_time   = $('#wps-mbfw-booking-to-time').val();
			if ( from_time && to_time ) {
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$(this).val('');
					alert( wps_bfw_public_obj.wrong_order_date );
				}
			}
		});
		$('#wps-mbfw-booking-to-time').on('change', function(){
			var from_time = $('#wps-mbfw-booking-from-time').val();
			var to_time   = $(this).val();
			if ( from_time && to_time ) {
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$(this).val('');
					alert( wps_bfw_public_obj.wrong_order_date );
				}
			}
		});
    });
})( jQuery );

function retrieve_booking_total_ajax( form_data ) {
	if ( $('.wps-mbfw-total-area').length > 0 ) {
		form_data.append('action', 'bfw_retrieve_booking_total_single_page');
		form_data.append('nonce', wps_bfw_common_obj.nonce);
		jQuery.ajax({
			url         : wps_bfw_common_obj.ajax_url,
			method      : 'post',
			data        : form_data,
			processData : false,
			contentType : false,
			success     : function( msg ) {
				$('.wps-mbfw-total-area').html(msg);
			}
		});
	}
}