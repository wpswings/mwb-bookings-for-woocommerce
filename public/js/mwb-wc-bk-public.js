/**
 * Public Js File.
 */
var is_product = false;
if ( mwb_wc_bk_public.hasOwnProperty( 'mwb_booking_product_page' ) ) {
	var is_product = mwb_wc_bk_public.mwb_booking_product_page;
}

var unavailable_dates = mwb_wc_bk_public.unavailable_dates;

var start_date;
var end_date;
var day_difference;

jQuery(document).ready( function($) {

	jQuery('#mwb-wc-bk-create-booking-form').parent('form').addClass('mwb_booking_checkout_form');
	$(window).on('load', function(event) {
		if ( is_product ) {
			// $( '.mwb-wc-bk-form-input' ).trigger('change');
			$( '.mwb_booking_checkout_form' )[0].reset();
		}
	});

	start_date = $( '#mwb-wc-bk-start-date-input' ).val();
	end_date   = $( '#mwb-wc-bk-end-date-input' ).val();

	show_time_slots($);
	
	people_conditions($);
	booking_price_cal($);

	if( $('#booking-slots-data').length > 0  ){
		var unavail_dates = $('#booking-slots-data').attr('unavail_dates');
		unavail_dates = JSON.parse(unavail_dates);
		var slots = $('#booking-slots-data').attr('slots');
		slots = JSON.parse(slots);

		if ( mwb_wc_bk_public.hasOwnProperty( 'product_settings' ) ) {
			datepicker_check($, unavail_dates, slots);
		}
	}
	jQuery('#mwb-wc-bk-create-booking-form').parent('form').addClass('mwb_booking_checkout_form');
	jQuery('#mwb-wc-bk-service-field ul li label').on('mouseenter',function(){
		jQuery(this).parent('li').children('.booking-service-desc').addClass('booking-service-desc_extend');
	}).on('mouseleave',function () { 
		jQuery(this).parent('li').children('.booking-service-desc').removeClass('booking-service-desc_extend');
	});

});
console.log(mwb_wc_bk_public.product_settings);
function datepicker_check($, unavailable_dates, slots) {
	max_adv_input    = mwb_wc_bk_public.product_settings.mwb_advance_booking_max_input[0];
	max_adv_duration = mwb_wc_bk_public.product_settings.mwb_advance_booking_max_duration[0];
	min_adv_input    = mwb_wc_bk_public.product_settings.mwb_advance_booking_min_input[0];
	min_adv_duration = mwb_wc_bk_public.product_settings.mwb_advance_booking_min_duration[0];

	start_booking        = mwb_wc_bk_public.product_settings.mwb_start_booking_from[0];

	console.log( max_adv_input );
	max_dur   = max_adv_duration.match(/\b(\w)/g);
	min_dur   = min_adv_duration.match( /\b(\w)/g );

	if ( start_booking == 'today' ) {
		start_slot = new Date();

		end_slot   = "+" + max_adv_input + max_dur;
	} else if ( start_booking == 'tomorrow' ) {
		start_slot = '+1d';
		end_slot   = "+" + parseInt(max_adv_input) + max_dur;
	} else if ( start_booking == 'initially_available' ) {
		if ( min_adv_input != 0 ) {
			start_slot = "+" + min_adv_input + min_dur;
			end_slot   = "+" + max_adv_input + max_dur + ' +' + min_adv_input + min_dur;
		} else {
			start_slot = new Date();
			end_slot   = "+" + max_adv_input + max_dur;
		}	
	} else if ( start_booking == 'custom_date' ) {

		custom_start_booking = mwb_wc_bk_public.product_settings.mwb_start_booking_custom_date[0];

		custom_date_arr = custom_start_booking.split('-');
		start_slot = new Date( custom_date_arr[0], custom_date_arr[1] - 1, custom_date_arr[2] );
		if ( start_slot < new Date() ) {
			start_slot = new Date();
		}
		if ( max_adv_duration == 'day' ) {
			end_slot = new Date( start_slot.getTime() + max_adv_input * 24 * 60 * 60 * 1000 );
		} else if( max_adv_duration == 'month' ) {
			end_slot = new Date( custom_date_arr[0], custom_date_arr[1] - 1, custom_date_arr[2] );
			end_slot.setMonth( ( end_slot.getMonth() ) + parseInt(max_adv_input) );
		}
	}
	console.log( 'start: ' + start_slot + "   " + 'end:  ' + end_slot );
	if ( mwb_wc_bk_public.product_settings.mwb_booking_unit_duration[0] == 'day' ) {
	
		$( '#mwb-wc-bk-date-section' ).on( 'change', '#mwb-wc-bk-end-date-input', function(){
			end_date = $(this).val();
			start_date = $('#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
			if ( start_date && end_date ) {
				

				date1 = new Date( start_date );
				date2 = new Date( end_date );
				
				time_difference = date2.getTime() - date1.getTime();
				day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;
				var arr = cal_in_between_days( start_date, day_difference );
				var result = unvailable_date_range_check( arr, unavailable_dates );
				if ( result == false ) {
					$('#mwb-wc-bk-date-section .date-error').show();
					$('#mwb-wc-bk-date-section .date-error').text( '*In between dates are unavailable' );
					$( '#mwb-wc-bk-end-date-input' ).val('');

				} else {
					$('#mwb-wc-bk-date-section .date-error').hide();
				}
			}
		});
			
		$( '#mwb-wc-bk-duration-section' ).on( 'change', '#mwb-wc-bk-duration-input', function(){
			duration = $( this ).val();
			start_date = $('#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
			if ( start_date && duration ) {
				day_difference = duration;
				var arr = cal_in_between_days( start_date, day_difference );
				var result = unvailable_date_range_check( arr, unavailable_dates );
				if ( result == false ) {
					$('#mwb-wc-bk-date-section .date-error').show();
					$('#mwb-wc-bk-date-section .date-error').text( '*In between dates are unavailable' );
					$( '#mwb-wc-bk-start-date-input' ).val('');

				} else {
					$('#mwb-wc-bk-date-section .date-error').hide();
				}

			}
		} );
			
		$( '#mwb-wc-bk-date-section' ).on( 'change', '#mwb-wc-bk-start-date-input', function(){

			start_date = $(this).val();
			end_date = $('#mwb-wc-bk-date-section #mwb-wc-bk-end-date-input').val();
			duration = $( '#mwb-wc-bk-duration-section #mwb-wc-bk-duration-input' ).val();
			alert( duration );
			if ( start_date && end_date ) {

				date1 = new Date( start_date );
				date2 = new Date( end_date );
				
				time_difference = date2.getTime() - date1.getTime();
				day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;
				var arr = cal_in_between_days( start_date, day_difference );
				var result = unvailable_date_range_check( arr, unavailable_dates );
				if ( result == false ) {
					$('#mwb-wc-bk-date-section .date-error').show();
					$('#mwb-wc-bk-date-section .date-error').text( '*In between dates are unavailable' );
					$( '#mwb-wc-bk-end-date-input' ).val('');

				} else {
					$('#mwb-wc-bk-date-section .date-error').hide();
				}
			}

			if ( start_date && duration ) {
				day_difference = duration;
				var arr = cal_in_between_days( start_date, day_difference );
				var result = unvailable_date_range_check( arr, unavailable_dates );
				if ( result == false ) {
					$('#mwb-wc-bk-date-section .date-error').show();
					$('#mwb-wc-bk-date-section .date-error').text( '*In between dates are unavailable' );
					$( '#mwb-wc-bk-start-date-input' ).val('');

				} else {
					$('#mwb-wc-bk-date-section .date-error').hide();
				}
			}
		});

	}
	
	$( '#mwb-wc-bk-start-date-input' ).datepicker({
		dateFormat : "mm/dd/yy",
	
		maxDate: end_slot,
		
		minDate: start_slot,
		

		beforeShowDay: function(d) {
			var year = d.getFullYear();
           	var	month = ( "0" + ( d.getMonth() + 1 ) ).slice( -2 );
            var	day = ( "0" + ( d.getDate() ) ).slice( -2 );
			var formatted = year + '-' + month + '-' + day;
			if ( $.inArray( formatted, unavailable_dates ) != -1 ) {
				return [false, "","Un-available"];
			} else {
				return [true,"","Available"];
			}
		}
	});

	$( '#mwb-wc-bk-end-date-input' ).datepicker({
	
		dateFormat : "mm/dd/yy",

		maxDate: end_slot,
		minDate: start_slot,

		beforeShowDay: function(d) {
			var year = d.getFullYear();
           	var	month = ("0" + (d.getMonth() + 1)).slice(-2);
            var	day = ("0" + (d.getDate())).slice(-2);
			var formatted = year + '-' + month + '-' + day;

			if ( $.inArray( formatted, unavailable_dates ) != -1 ) {
				return [false, "","Un-available"];
			} else {
				return [true,"","Available"];
			}
		}
	});
}

function unvailable_date_range_check( arr, unavail_dates ) {

	for( i = 0; i < arr.length; i++ ) {
		var val = jQuery.inArray( arr[i], unavail_dates );
		if ( val == -1 ) {
			continue;
		} else {
			return false;
		}
	}
	return true;
}


function cal_in_between_days( start_date, day_difference ) {

	var date_arr = [];
	date1 = new Date( start_date );
	date_arr.push(date1.getFullYear() + '-' + ( "0" + ( date1.getMonth() + 1 ) ).slice( -2 ) + '-' + ( "0" + ( date1.getDate() ) ).slice( -2 ));
	for( var day = 1; day < day_difference; day++ ) {

		date1.setDate( date1.getDate() + 1 );

		val = date1.getFullYear() + '-' + ( "0" + ( date1.getMonth() + 1 ) ).slice( -2 ) + '-' + ( "0" + ( date1.getDate() ) ).slice( -2 );

		date_arr.push( val );
	}
	return date_arr;

}


function show_time_slots($) {
	$( '#mwb-wc-bk-create-booking-form' ).on( 'change', '#mwb-wc-bk-start-date-input', function() {

		var product_data = $('#mwb-wc-bk-create-booking-form').attr( 'product-data' );
		var product_data = JSON.parse( product_data );
		var product_id = product_data.product_id;
		var slots = $('#booking-slots-data').attr('slots');
		slots = JSON.parse( slots );

		var date = $(this).val();

		$.ajax({
			url      : mwb_wc_bk_public.ajaxurl,
			type     : 'POST',
			data     : {
				'action': 'mwb_time_slots_in_booking_form',
				'nonce' : mwb_wc_bk_public.nonce,
				'date'  : date,
				'id'    : product_id,
				'slots' : slots,
 			},
			success : function( response ) {
				$( '#mwb-wc-bk-time-section' ).html( response );
			}
		});

	} );
}

function people_conditions($) {

	$( '#mwb-wc-bk-people-section' ).on( 'click', "label[for='mwb-wc-bk-people-input-div']", function(){

		var div = $(this).siblings();
		if( 'mwb-wc-bk-people-input-div' === div.attr('id') ){
			div.toggle();
		}
	});

}

function booking_price_cal($) {
	var max_people = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div input[type=hidden]' ).attr('data-max');
	var min_people = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div input[type=hidden]' ).attr('data-min');

	var tot = 0;

	$( document ).on('change keyup keydown keypress', '.people-input', function(e) {

		if ( mwb_wc_bk_public.hasOwnProperty( 'product_settings' ) && mwb_wc_bk_public.product_settings.mwb_enable_people_types[0] == 'no' ) {
			total_input = $( this ).val();
		} else {
			const people_input = $( '.people-input' );
			const currentObj = $( this );
			var total_input = 0;
		
			var obj = currentObj.siblings( '.people-input-hidden' );
			var max_quant = obj.attr( 'data-max' );
			var min_quant = obj.attr( 'data-min' );

			currentObj.attr( 'min', 0 );
			currentObj.attr( 'max', max_quant );

			for ( var i = 0, len = people_input.length; i < len; i++ ) {
				total_input += parseInt( people_input[i].value );
			}
			if ( max_people ) {
				if( total_input > max_people ) {

					var val = parseInt( currentObj.val() );
					val -= 1;
					total_input--;
					currentObj.val( val );
				}
			}
		}

		
		
		$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-span').text( total_input + '-Peoples');

		$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-hidden').val( total_input );
		
		var product_data   = $('#mwb-wc-bk-create-booking-form').attr('product-data');
		var duration_input = $('#mwb-wc-bk-duration-input');
		if ( duration_input.length < 1 ){
			if( mwb_wc_bk_public.product_settings.hasOwnProperty( 'mwb_enable_range_picker' ) && 'yes' == mwb_wc_bk_public.product_settings.mwb_enable_range_picker[0] ) {
				
				start_date = $( '#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
				end_date   = $( '#mwb-wc-bk-date-section #mwb-wc-bk-end-date-input').val();

				date1 = new Date( start_date );
				date2 = new Date( end_date );
				
				time_difference = date2.getTime() - date1.getTime();
				day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;

				var duration = day_difference;
			}
		} else {
			var duration = duration_input.val();
		}
		
		product_data       = JSON.parse(product_data);
		var product_id     = product_data.product_id;
		var ajax_data = {
			'action'       : 'booking_price_cal',
			'nonce'        : mwb_wc_bk_public.nonce,
			'people_total' : total_input,
			'product_id'   : product_id,
			'duration'     : duration,
		}

		var people_count_obj = {};
		$( '#mwb-wc-bk-people-section .people-input' ).each(function() {
			var val = $(this).val();
			var id  = $(this).attr( 'data-id' );
			people_count_obj[id] = val;

		});
		Object.assign( ajax_data,{ 'people_count': people_count_obj });

		var inc_service_count = {};
		var add_service_count = {};
		$( '.mwb-wc-bk-inc-service-quant' ).each(function(){
			val = $(this).val();
			id  = $(this).attr( 'data-id' );
			inc_service_count[id] = val;
		}); 
		Object.assign( ajax_data, { 'inc_service_count': inc_service_count});

		$( '.mwb-wc-bk-add-service-quant' ).each(function(){
			var add_service_obj = $( this ).siblings( 'input[type=checkbox]' );
			if( add_service_obj.is( ':checked' ) ) {
				val = $(this).val();
				id  = $(this).attr( 'data-id' );
				add_service_count[id] = val;
			}
		});
		Object.assign( ajax_data, {'add_service_count': add_service_count} );
		console.log( ajax_data );

		$.ajax({
			url      : mwb_wc_bk_public.ajaxurl,
			type     : 'POST',
			data     : ajax_data,
			success : function( response ) {
				response = JSON.parse(response);
				console.log( response );
				var price_html = response.price_html ; 
				$('.price').html(price_html);
				total_cost     = response.booking_total_cost;
				base_cost      = response.booking_people_cost;
				service_cost   = response.booking_service_cost;
				added_cost_arr = response.indiv_added_cost_arr;
				show_total($, total_cost, base_cost, service_cost, added_cost_arr);
			},
		});
	});

	
	$( document ).on( 'submit', '.cart', function(e) {

		var people_total = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-hidden' ).val();
	
		if ( people_total <= 0 ) {
			$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).show();
			$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).text( "*Select at least 1 people" );
			e.preventDefault();
		} else {
			$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).hide();
			$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).text( '' );
		}

		if ( mwb_wc_bk_public.product_settings.hasOwnProperty( 'mwb_enable_range_picker' ) && 'yes' === mwb_wc_bk_public.product_settings.mwb_enable_range_picker[0] ) {

			start_date = $( '#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
			end_date   = $( '#mwb-wc-bk-date-section #mwb-wc-bk-end-date-input').val();

			if ( new Date( start_date ) > new Date( end_date ) ) {

				e.preventDefault();
				$('#mwb-wc-bk-date-section .date-error').show();
				$('#mwb-wc-bk-date-section .date-error').text( '*End Date should be more than Start Date' );
			} else {
				$('#mwb-wc-bk-date-section .date-error').hide();
			}
		}

		if ( mwb_wc_bk_public.product_settings.mwb_booking_unit_duration[0] != 'day' ) {

				start_date = $( '#mwb-wc-bk-start-date-input' ).val();
				var dura = $( '#mwb-wc-bk-duration-input' ).val();
				var time_slot = $( '#mwb-wc-bk-time-slot-input' ).val();
				start_date = new Date( start_date );
				var slots = $('#booking-slots-data').attr('slots');
				slots = JSON.parse(slots);
				var start_date = start_date.getFullYear() + '-' + ( "0" + ( start_date.getMonth() + 1 ) ).slice( -2 ) + '-' + ( "0" + ( start_date.getDate() ) ).slice( -2 );

				$.ajax({
					url      : mwb_wc_bk_public.ajaxurl,
					type     : 'POST',
					async    : false,
					data     : {
						'action'    : 'mwb_check_time_slot_availability',
						'nonce'     : mwb_wc_bk_public.nonce,
						'start_date': start_date,
						'duration'  : dura,
						'time_slot' : time_slot,
						'slots'     : slots,
					},
					success : function( response ) {
						response = JSON.parse( response );

						if ( response.status == true ) {
							jQuery('#mwb-wc-bk-date-section .date-error').hide();
							
						} else {
							jQuery('#mwb-wc-bk-date-section .date-error').show();
							jQuery('#mwb-wc-bk-date-section .date-error').text( '*In between slots are unavailable' );
							e.preventDefault();
						}

					}
				});
		}
		
	});
	
	$( '#mwb-wc-bk-service-section' ).on( 'change keyup keydown keypress', '.service-input', function(){

		price_cal_func($);
	});

	$( '#mwb-wc-bk-add-service-field' ).on( 'change keyup keydown keypress', '.add_service_check', function() {

		price_cal_func($);

	});

	$('.mwb-wc-bk-form-section #mwb-wc-bk-duration-div').on('change keyup keydown keypress' , 'input' , function(e){

		price_cal_func($);
		
	});

	$( '#mwb-wc-bk-start-date-field' ).on( 'change keyup keydown keypress', 'input', function(){

		price_cal_func($);
	
	} );

	$( '#mwb-wc-bk-end-date-field' ).on( 'change keyup keydown keypress', 'input', function(){

		price_cal_func($);
	
	} );
}



function price_cal_func($) {

	var product_data   = $('#mwb-wc-bk-create-booking-form').attr('product-data');
	var duration_input = $('#mwb-wc-bk-duration-input');
	if ( duration_input.length < 1 ){
		if( mwb_wc_bk_public.product_settings.hasOwnProperty( 'mwb_enable_range_picker' ) && 'yes' == mwb_wc_bk_public.product_settings.mwb_enable_range_picker[0] ) {
			start_date = $( '#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
			end_date   = $( '#mwb-wc-bk-date-section #mwb-wc-bk-end-date-input').val();

			date1 = new Date( start_date );
			date2 = new Date( end_date );
			
			time_difference = date2.getTime() - date1.getTime();
			day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;

			var duration = day_difference;
			if ( ! end_date || ! start_date ) {
				duration = 1;
			}

		}
	} else {
		var duration = duration_input.val();

	}
	product_data       = JSON.parse(product_data);
	var product_id     = product_data.product_id ;

	var ajax_data = {
		'action'       : 'booking_price_cal',
		'nonce'        : mwb_wc_bk_public.nonce,
		'product_id'   : product_id,
		'duration'     : duration,
	}

	var people_count_obj = {};
	var people_total = 0;
	$( '#mwb-wc-bk-people-section .people-input' ).each(function() {
		var val = $(this).val();
		var id  = $(this).attr( 'data-id' );
		people_count_obj[id] = val;
		people_total += parseInt( val );
	});
	Object.assign( ajax_data,{ 'people_total': people_total });
	Object.assign( ajax_data,{ 'people_count': people_count_obj });

	var inc_service_count = {};
	var add_service_count = {};
	$( '.mwb-wc-bk-inc-service-quant' ).each(function(){
		val = $(this).val();
		id  = $(this).attr( 'data-id' );
		inc_service_count[id] = val;
	});
	Object.assign( ajax_data, { 'inc_service_count': inc_service_count } );

	$( '.mwb-wc-bk-add-service-quant' ).each(function(){
		var add_service_obj = $( this ).siblings( 'input[type=checkbox]' );
		if( add_service_obj.is( ':checked' ) ) {
			val = $(this).val();
			id  = $(this).attr( 'data-id' );
			add_service_count[id] = val;
		}
	});
	Object.assign( ajax_data, {'add_service_count': add_service_count} );

	$.ajax({
		url      : mwb_wc_bk_public.ajaxurl,
		type     : 'POST',
		data     : ajax_data,
		success : function( response ) {
			response = JSON.parse(response);
			var price_html = response.price_html ; 
			$('.price').html(price_html);
			total_cost     = response.booking_total_cost;
			base_cost      = response.booking_people_cost;
			service_cost   = response.booking_service_cost;
			added_cost_arr = response.indiv_added_cost_arr;
			show_total($, total_cost, base_cost, service_cost, added_cost_arr);
		},
	});
}

function show_total($, total_cost, base_cost, service_cost, added_cost_arr) {
	
	var data = {
		'action'         : 'show_booking_total',
		'nonce'          : mwb_wc_bk_public.nonce,
		'total_cost'     : total_cost,
		'service_cost'   : service_cost,
		'base_cost'      : base_cost,
		'added_cost_arr' : added_cost_arr,
	}
	console.log( data );
	$.ajax({
		url     : mwb_wc_bk_public.ajaxurl,
		type    : 'POST',
		data    : data,
		success : function( response ) {
			$( '#mwb-wc-bk-total-fields' ).html( response );
		}
	});
}

