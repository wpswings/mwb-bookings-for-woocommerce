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
		
	// database error connection issue fixed.
	//if (mwb_mbfw_common_obj.is_single_cal == 'yes') {
		$(document).on('focusout blur keydown paste focus mousedown mouseover mouseout', '.mwb-mbfw-cart-page-data', function () {
			
			
			var form_data = new FormData( $('form.cart')[0] );
			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
		});
	//}
		$('#mwb-mbfw-booking-from-time, #mwb-mbfw-booking-to-time').on('keydown paste focus mousedown',function(e){
			
			e.preventDefault();
			

		});
		$('.mwb_mbfw_time_date_picker_frontend').datetimepicker({
			format  : 'd-m-Y H:00',
			minDate : mwb_mbfw_common_obj.minDate,
			// minTime : mwb_mbfw_common_obj.minTime
		});
		$('.mwb_mbfw_date_picker_frontend').datetimepicker({
			format     : 'd-m-Y',
			timepicker : false,
			minDate    : mwb_mbfw_common_obj.minDate,
		});
		
		
		$('#mwb_mbfw_choose_holiday').datepicker({
			dateFormat : 'dd-mm-yy',
			minDate: mwb_mbfw_common_obj.minDate,
			
		});
		$('.mwb_mbfw_time_picker_frontend').datetimepicker({
			format     : 'H:i',
			datepicker : false,
		});
		$('#mwb-mbfw-booking-from-time').on('change', function(){
			var from_time = $(this).val();
			var to_time   = $('#mwb-mbfw-booking-to-time').val();
			debugger;
			if ( from_time && to_time ) {
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$(this).val('');
				
					if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
						if (jQuery(jQuery('.flatpickr-calendar')[0]).hasClass('open')){
							jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
							jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
							$(this).val('');
						}
					}
					alert( mwb_mbfw_public_obj.wrong_order_date_2 );
				}
			}
		});
		$('#mwb-mbfw-booking-to-time').on('change', function(){
			var from_time = $('#mwb-mbfw-booking-from-time').val();
			var to_time   = $(this).val();
			debugger;
			if ( from_time && to_time ) {
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$('#mwb-mbfw-booking-to-time').val('');
					
					if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
						if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('open')){
							jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('open');
							jQuery(jQuery('.flatpickr-calendar')[1]).addClass('close');
							$(this).val('');
						}
					}
					alert( mwb_mbfw_public_obj.wrong_order_date_1 );
					
				}
			}
		});
		$('#mwb-mbfw-booking-to-time').on('click', function(){
			if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
				if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('close')){
					jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('close');
					jQuery(jQuery('.flatpickr-calendar')[1]).addClass('open')
				}
			}
		});
		$('#mwb-mbfw-booking-from-time').on('click', function(){
			if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
				if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('close')){
					jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('close');
					jQuery(jQuery('.flatpickr-calendar')[1]).addClass('open')
				}
			}
		});
    });

	// cancel order from my account page.
	jQuery(document).on('click', '#wps_bfw_cancel_order', function(){
		if (confirm(mwb_mbfw_common_obj.cancel_booking_order) == true) {
			
			var product_id = jQuery(this).attr('data-product');
			var order_id   = jQuery(this).attr('data-order');
			var data       = {
				'action'     : 'bfw_cancelled_booked_order',
				'nonce'      : mwb_mbfw_common_obj.nonce,
				'product_id' : product_id,
				'order_id'   : order_id,
			}
			
			jQuery.ajax({
				url     : mwb_mbfw_common_obj.ajax_url,
				method  : 'POST',
				data    : data,
				success : function( response ) {
					window.location.reload();
				}
			});
		}

		
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
				var str1 = msg;
				var str2 = "rror establishing a database connectio";
				if(str1.indexOf(str2) != -1){
					msg = '';
				}
				if( 'fail' == msg ) {
					alert( 'It looks like some dates are not available in between the dates choosen by you! , please select available dates!' );
					$('#mwb-mbfw-booking-from-time').val('');
					$('#mwb-mbfw-booking-to-time').val('');
				} else {

					$('.mwb-mbfw-total-area').html(msg);
				}
			}
		});
	}
}