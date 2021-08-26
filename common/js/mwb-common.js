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
        $(document).on('change', 'form.cart :input', function(){
            var form_data = new FormData( $('form.cart')[0] );
			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
        });

		if ( $('.mwb_mbfw_time_date_picker_frontend').length > 0 ) {
			$('.mwb_mbfw_time_date_picker_frontend').daterangepicker({
				autoUpdateInput  : false,
				timePicker       : true,
				timePicker24Hour : true,
				showDropdowns    : true,
				autoApply        : true,
				locale           : {
					format: 'DD/MM/YYYY HH:mm'
				},
				opens            : 'center',
				minDate          : mwb_mbfw_common_obj.minDate,
			});
		}
		$('.mwb_mbfw_time_date_picker_frontend').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
			var form_data = new FormData( $('form.cart')[0] );
			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
		});
		if ( $('.mwb_mbfw_date_picker_frontend').length > 0 ) {
			$('.mwb_mbfw_date_picker_frontend').daterangepicker({
				autoUpdateInput : false,
				showDropdowns   : true,
				autoApply       : true,
				locale          : {
					format : 'DD/MM/YYYY HH:mm'
				},
				opens           : 'center',
				minDate         : mwb_mbfw_common_obj.minDate,
			});
		}
		$('.mwb_mbfw_date_picker_frontend').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
			var form_data = new FormData( $('form.cart')[0] );
			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
		});
		if ( $('.mwb_mbfw_time_picker_frontend').length > 0 ) {
			$('.mwb_mbfw_time_picker_frontend').daterangepicker({
				autoUpdateInput  : false,
				timePicker       : true,
				timePicker24Hour : true,
				showDropdowns    : true,
				autoApply        : true,
				locale           : {
					format : 'DD/MM/YYYY HH:mm'
				},
				opens            : 'center',
				minDate          : mwb_mbfw_common_obj.minDate,
				maxDate          : mwb_mbfw_common_obj.maxTime,
			});
		}
		$('.mwb_mbfw_time_picker_frontend').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
			var form_data = new FormData( $('form.cart')[0] );
			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
		});
		$('input[name="mwb_mbfw_booking_time"]').on('change',function(){
			var date_time = $(this).val().split('-');
			var from_date = moment( date_time[0], 'DD/MM/YYYY HH:mm ', true ).isValid();
			var to_date   = moment( date_time[1], ' DD/MM/YYYY HH:mm', true ).isValid();
			if ( ! from_date || ! to_date ) {
				$(this).val('');
				alert( mwb_mbfw_common_obj.date_time_format );
			}
		});
		$('#mwb-mbfw-single-booking-time-selector-from').on('keydown paste focus mousedown',function(e){
			e.preventDefault();
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