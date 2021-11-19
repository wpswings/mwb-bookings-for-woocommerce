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
		if ( $('.mbfw_time_picker').length > 0 ) {
			$('.mbfw_time_picker').timepicker();
		}
        $(document).on('change', 'form.cart :input', function(){
            var form_data = new FormData( $('form.cart')[0] );
			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
        });
		$('#mwb-mbfw-booking-from-time, #mwb-mbfw-booking-to-time').on('keydown paste focus mousedown',function(e){
			e.preventDefault();
		});
		$('.mwb_mbfw_time_date_picker_frontend').datetimepicker({
			format  : 'd-m-Y H:i',
			minDate : mwb_mbfw_common_obj.minDate,
			minTime : mwb_mbfw_common_obj.minTime
		});
		$('.mwb_mbfw_date_picker_frontend').datetimepicker({
			format     : 'd-m-Y',
			timepicker : false,
			minDate    : mwb_mbfw_common_obj.minDate,
		});
		$('.mwb_mbfw_time_picker_frontend').datetimepicker({
			format     : 'H:i',
			datepicker : false,
		});
		$('#mwb-mbfw-booking-from-time').on('change', function(){
			var from_time = $(this).val();
			var to_time   = $('#mwb-mbfw-booking-to-time').val();
			if ( from_time && to_time ) {
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$(this).val('');
					alert( mwb_mbfw_public_obj.wrong_order_date );
				}
			}
		});
		$('#mwb-mbfw-booking-to-time').on('change', function(){
			var from_time = $('#mwb-mbfw-booking-from-time').val();
			var to_time   = $(this).val();
			if ( from_time && to_time ) {
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$(this).val('');
					alert( mwb_mbfw_public_obj.wrong_order_date );
				}
			}
		});
    });
})( jQuery );

function retrieve_booking_total_ajax( form_data ) {
	if ( $('.mwb-mbfw-total-area').length > 0 ) {
		form_data.append('action', 'mbfw_retrieve_booking_total_single_page');
		form_data.append('nonce', mwb_mbfw_common_obj.nonce);
		jQuery.ajax({
			url         : mwb_mbfw_common_obj.ajax_url,
			method      : 'post',
			data        : form_data,
			processData : false,
			contentType : false,
			success     : function( msg ) {
				$('.mwb-mbfw-total-area').html(msg);
			}
		});
	}
}