(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

})( jQuery );
// var ajaxurl = mwb_wc_bk_public.ajaxurl ; 
// var booking_total_cost = 0;
// var booking_people_cost = 0;
// var booking_added_cost = 0;
// var booking_service_cost = 0;
// var indiv_added_cost_arr = [];

var start_date;
var end_date;
jQuery(document).ready( function($) {

	start_date = $( '#mwb-wc-bk-start-date-input' ).val();
	end_date   = $( '#mwb-wc-bk-end-date-input' ).val();

	if ( mwb_wc_bk_public.hasOwnProperty( 'product_settings' ) ) {
		datepicker_check($);
	}
	// mwb_wc_bk_add_to_cart_form_update($);
	people_conditions($);
	// booking_service_conditions($);
	booking_price_cal($);
	// show_total($)

	console.log( mwb_wc_bk_public );
});

function datepicker_check($) {
	max_adv_input    = mwb_wc_bk_public.product_settings.mwb_advance_booking_max_input[0];
	max_adv_duration = mwb_wc_bk_public.product_settings.mwb_advance_booking_max_duration[0];
	min_adv_input    = mwb_wc_bk_public.product_settings.mwb_advance_booking_min_input[0];
	min_adv_duration = mwb_wc_bk_public.product_settings.mwb_advance_booking_min_duration[0];
	current_date     = mwb_wc_bk_public.current_date;
	// alert( min_adv_duration );
	max_dur   = max_adv_duration.match(/\b(\w)/g);
	min_dur   = min_adv_duration.match( /\b(\w)/g );
	// arr = current_date.split("-");
	// min_date_yr = arr[0];
	// if( 'day' == min_adv_duration ) {
	
	// 	min_date_mnth = arr[1];
	// 	min_date_day  = parseInt( arr[2] ) + parseInt( min_adv_input );
		
	// 	if( min_date_day > 30 && min_date_day != 31 ) {
	// 		mai_date_day %= 30;
	// 		min_date_mnth += ( mai_date_day / 30 );
	// 	} else if(  min_date_day > 31 ) {
	// 		mai_date_day %= 31;
	// 		min_date_mnth += ( mai_date_day / 31 );
	// 	}
		
	// } else if( 'month' == min_adv_duration ) {

	// 	min_date_mnth =  parseInt( arr[1] ) + parseInt( min_adv_input );
	// 	min_date_day = arr[2];

	// 	if ( min_date_mnth > 12 ) {
	// 		min_date_mnth %= 12;
	// 		min_date_yr += ( min_date_mnth / 12 );
	// 	}
	// }
	// alert( min_date_day + "-" + min_date_mnth);
	// console.log( new Date( min_date_yr, min_date_mnth, min_date_day ) );
	console.log( "+" + max_adv_input + max_dur );
	console.log( "+" + min_adv_input + min_dur );
	var not_allowed_days = mwb_wc_bk_public.not_allowed_days;
	
	// console.log( mwb_wc_bk_public.not_allowed_days );

	$( '#mwb-wc-bk-date-section' ).on( 'change', '#mwb-wc-bk-start-date-input', function(){

		start_date = $(this).val();
		$( '#mwb-wc-bk-date-section' ).on( 'change', '#mwb-wc-bk-end-date-input', function(){
			end_date = $(this).val();
		});
		if ( start_date && end_date ) {

			var arr = cal_booking_days( start_date, end_date );
			console.log( arr );
		}
	});
	$( '#mwb-wc-bk-date-section' ).on( 'change', '#mwb-wc-bk-end-date-input', function(){

		end_date = $(this).val();
		$( '#mwb-wc-bk-date-section' ).on( 'change', '#mwb-wc-bk-start-date-input', function(){

			start_date = $(this).val();
		});
		if ( start_date && end_date ) {

			var arr = cal_booking_days( start_date, end_date );
			console.log( arr );
		}
	});	
	
	// console.log( start_date + "  " + end_date );
	
	// e.preventDefault();
	$( '#mwb-wc-bk-start-date-input' ).datepicker({
		dateFormat : "mm/dd/yy",
		// firstDay: 0,
		maxDate: "+" + max_adv_input + max_dur,
		// minDate: new Date( min_date_yr, min_date_mnth - min_date_mnth, min_date_day ),
		minDate: "+" + min_adv_input + min_dur,
		// autoSize: true
		// numberOfMonths: [ 2, 3 ]

		// beforeShowDay: function(date){ 
		// 	var days = [1,2,3,4,5]; 
		// 	return [days.includes(date.getDay())];
		// }

		beforeShowDay: function(date) {
			var days = [0,1,2,3,4,5,6];
			for( var l = 0; l < not_allowed_days.length; l++ ) {
				switch( not_allowed_days[l] ) {
					case 'sunday':
						days.splice( $.inArray(0, days), 1 );
						break;
					case 'monday':
						days.splice( $.inArray(1, days), 1 );
						break;
					case 'tuesday':
						days.splice( $.inArray(2, days), 1 );
						break;
					case 'wednesday':
						days.splice( $.inArray(3, days), 1 );
						break;
					case 'thursday':
						days.splice( $.inArray(4, days), 1 );
						break;
					case 'friday':
						days.splice( $.inArray(5, days), 1 );
						break;
					case 'saturday':
						days.splice( $.inArray(6, days), 1 );
						break;
				}
				// var day = date.getDay();
				// return [(day != day_no)];
			}
			return [days.includes(date.getDay())];
		},
	});

	$( '#mwb-wc-bk-end-date-input' ).datepicker({
		dateFormat : "mm/dd/yy",

		maxDate: "+" + max_adv_input + max_dur,
		minDate: "+" + min_adv_input + min_dur,

		beforeShowDay: function(date) {
			var days = [0,1,2,3,4,5,6];
			for( var l = 0; l < not_allowed_days.length; l++ ) {
				switch( not_allowed_days[l] ) {
					case 'sunday':
						days.splice( $.inArray(0, days), 1 );
						break;
					case 'monday':
						days.splice( $.inArray(1, days), 1 );
						break;
					case 'tuesday':
						days.splice( $.inArray(2, days), 1 );
						break;
					case 'wednesday':
						days.splice( $.inArray(3, days), 1 );
						break;
					case 'thursday':
						days.splice( $.inArray(4, days), 1 );
						break;
					case 'friday':
						days.splice( $.inArray(5, days), 1 );
						break;
					case 'saturday':
						days.splice( $.inArray(6, days), 1 );
						break;
				}
			}
			return [days.includes(date.getDay())];
		},
	});
}


function cal_booking_days( start_date, end_date ) {

	// var tomorrow = new Date();
	var date_arr = [];

	date1 = new Date( start_date );
	date2 = new Date( end_date );
	
	time_difference = date2.getTime() - date1.getTime();
	day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;
	
	date_arr.push(date1.getDate() + '-' + ( date1.getMonth() + 1 ) + '-' + date1.getFullYear());
	for( var day = 1; day < day_difference; day++ ) {

		date1.setDate( date1.getDate() + day );
		val = date1.getDate() + '-' + ( date1.getMonth() + 1 ) + '-' + date1.getFullYear();

		date_arr.push( val );
	}
	// console.log( date_arr );
	return date_arr;
	// console.log( day_difference );


	// console.log( start_date + "  " + end_date );
	// console.log( date1 + "  " + date2 );
	// start_arr = start_date.split("-");
	// end_arr   = end_date.split( '-' );
	// var count_days = 0;
	
	// if ( start_arr[0] < end_arr[0] ) {
	// 	count_days = parseInt( end_arr[0] ) - parseInt( start_arr[0] );
	// 	if ( start_arr[1] < end_arr[1] ) {
	// 		count_days += ( parseInt( end_arr[1] ) - parseInt( start_arr[1] ) );
	// 	} else if( start_arr[1] > end_arr[1] ) {
	// 		count_days = parseInt( en )
	// 	}
	// }


}

// function unavailable( date ) {

// }

function mwb_wc_bk_add_to_cart_form_update($){
	$('.mwb-wc-bk-form-section #mwb-wc-bk-duration-div').on('change' , 'input' , function(e){
		var product_data   = $('#mwb-wc-bk-duration-section').attr('product-data');
		product_data       = JSON.parse(product_data);
		var product_id     = product_data.product_id ;
		var duration_input = $('#mwb-wc-bk-duration-input');
		var duration       = duration_input.val();
		var request_data   = {
			'product_id' : product_id,
			'duration'   : duration,
			'action'     : 'mwb_wc_bk_update_add_to_cart',
			'nonce'      : mwb_wc_bk_public.nonce,
		}
		// $.post( ajaxurl, request_data ).done(function( response ){
		// 	response = JSON.parse(response);
		// 	var price_html = response.price_html ; 
		// 	$('.price').html(price_html);
		// });
		// $.ajax({
		// 	url      : mwb_wc_bk_public.ajaxurl,
		// 	type     : 'POST',
		// 	data     : request_data,
		// 	success : function( response ) {
		// 		response = JSON.parse(response);
		// 		// console.log( response );
		// 		// alert( "khbf" );
		// 		var price_html = response.price_html ; 
		// 		$('.price').html(price_html);
		// 	},
		// });
	});
}

function people_conditions($) {

	$( '#mwb-wc-bk-people-section' ).on( 'click', "label[for='mwb-wc-bk-people-input-div']", function(){
		// alert("working");
		// console.log( $(this).siblings() );
		var div = $(this).siblings();
		if( 'mwb-wc-bk-people-input-div' === div.attr('id') ){
			div.toggle();
		}
	});

	// var people_total = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-hidden' ).val();
	// if ( people_total <= 0 ) {
	// 	$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).show();
	// 	$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).text( "Select atleast 1 people" );
	// 	// $( '.cart' ).submit(function(e){
	// 	// 	e.preventDefault();
	// 	// });
	// } else {
	// 	$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).css( "display", "none" );
	// 	$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-error' ).text( '' );
	// }

}

function booking_price_cal($) {
	// var people_count = 0;
	var max_people = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div input[type=hidden]' ).attr('data-max');
	var min_people = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div input[type=hidden]' ).attr('data-min');

	$( document ).on('change', '.people-input', function(e) {

		const people_input = $( '.people-input' );
		const currentObj = $( this );
		var total_input = 0;
		// console.log(currentObj);
		// console.log(people_input);

		var obj = currentObj.siblings( '.people-input-hidden' );
		// console.log( obj.attr( 'data-max' ) );
		var max_quant = obj.attr( 'data-max' );
		var min_quant = obj.attr( 'data-min' );

		// if ( max_quant > max_people ) {
		// 	max_quant = max_people;
		// }

		currentObj.attr( 'min', 0 );
		currentObj.attr( 'max', max_quant );
		// if( mwb_wc_bk_public.product_settings.mwb_booking_unit_cost_multiply[0] === 'yes' ) {
		// 	currentObj.attr( 'min', 0 );
		// 	currentObj.attr( 'max', max_quant );
		// } else {
		// 	currentObj.attr( 'min', 0 );
		// }

		for ( var i = 0, len = people_input.length; i < len; i++ ) {
			total_input += parseInt( people_input[i].value );
		}
		
		if( total_input > max_people ) {

			var val = parseInt( currentObj.val() );
			val -= 1;
			total_input--;
			currentObj.val( val );
		}
		// alert(total_input);
		$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-span').text( total_input + '-Peoples');
		$( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-hidden').val( total_input );
		
		var product_data   = $('#mwb-wc-bk-create-booking-form').attr('product-data');
		var duration_input = $('#mwb-wc-bk-duration-input');
		if ( duration_input.length < 1 ){
			// alert("no duration field");
			if( mwb_wc_bk_public.product_settings.hasOwnProperty( 'mwb_enable_range_picker' ) && 'yes' == mwb_wc_bk_public.product_settings.mwb_enable_range_picker[0] ) {
				start_date = $( '#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
				end_date   = $( '#mwb-wc-bk-date-section #mwb-wc-bk-end-date-input').val();

				date1 = new Date( start_date );
				date2 = new Date( end_date );
				
				time_difference = date2.getTime() - date1.getTime();
				day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;

				// alert( day_difference );
				var duration = day_difference;
			}
		} else {
			var duration = duration_input.val();
		}
		
		// alert( duration );
		product_data       = JSON.parse(product_data);
		var product_id     = product_data.product_id;
		var ajax_data = {
			'action'       : 'booking_price_cal',
			'nonce'        : mwb_wc_bk_public.nonce,
			'people_total' : total_input,
			// 'formdata' : jQuery( '.cart' ).serialize()
			'product_id'   : product_id,
			'duration'     : duration,
		}

		var people_count_obj = {};
		$( '#mwb-wc-bk-people-section .people-input' ).each(function() {
			// alert($(this).val());
			var val = $(this).val();
			var id  = $(this).attr( 'data-id' );
			// var people_count_obj = {
			// 	[id] : val,
			// }
			people_count_obj[id] = val;
			
			// obj.push({[id] : val});
			// Object.assign( obj,{ 'people_count': people_count_obj });
		});
		Object.assign( ajax_data,{ 'people_count': people_count_obj });
		// console.log(ajax_data);

		var inc_service_count = {};
		var add_service_count = {};
		$( '.mwb-wc-bk-inc-service-quant' ).each(function(){
			// alert($(this).val());
			val = $(this).val();
			id  = $(this).attr( 'data-id' );
			inc_service_count[id] = val;
		}); 
		Object.assign( ajax_data, { 'inc_service_count': inc_service_count});
		// console.log( ajax_data );

		$( '.mwb-wc-bk-add-service-quant' ).each(function(){
			// alert($(this).val());
			var add_service_obj = $( this ).siblings( 'input[type=checkbox]' );
			if( add_service_obj.is( ':checked' ) ) {
				val = $(this).val();
				id  = $(this).attr( 'data-id' );
				add_service_count[id] = val;
			}
		});
		Object.assign( ajax_data, {'add_service_count': add_service_count} );
		// Object.assign( ajax_data, add_service_count );
		console.log( ajax_data );

		$.ajax({
			url      : mwb_wc_bk_public.ajaxurl,
			type     : 'POST',
			data     : ajax_data,
			success : function( response ) {
				response = JSON.parse(response);
				console.log( response );
				// alert( "khbf" );
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
		alert("submit working");

		var people_total = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div #mwb-wc-bk-people-input-hidden' ).val();
		if ( people_total <= 0 ) {
			alert("people");
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
			// console.log( start_date );
			// console.log( end_date );
			// console.log( new Date( start_date ) );
			// console.log( new Date( end_date ) );
			if ( new Date( start_date ) > new Date( end_date ) ) {
				// alert( "more" );

				e.preventDefault();
				$('#mwb-wc-bk-date-section .date-error').show();
				$('#mwb-wc-bk-date-section .date-error').text( '*End Date should be more than Start Date' );
			} else {
				// alert( "less" );
				$('#mwb-wc-bk-date-section .date-error').hide();
			}
		}
		
	});
	

	$( '#mwb-wc-bk-service-section' ).on( 'click', '.service-input', function(){

		// $(this).each(function(){
		// alert($(this).val());

		price_cal_func($);
		// });
	});

	$( '#mwb-wc-bk-add-service-field' ).on( 'change', '.add_service_check', function() {
		// if( $(this).is( ':checked' ) ) {
		// 	// alert('checked');
		// } else {
		// 	// alert('not checked');
		// }
		// });
		price_cal_func($);

	});

	$('.mwb-wc-bk-form-section #mwb-wc-bk-duration-div').on('change' , 'input' , function(e){
		// alert('working');
		price_cal_func($);
		
	});

	$( '#mwb-wc-bk-start-date-field' ).on( 'change', 'input', function(){

		price_cal_func($);
	} );

}

function price_cal_func($) {

	var product_data   = $('#mwb-wc-bk-create-booking-form').attr('product-data');
	var duration_input = $('#mwb-wc-bk-duration-input');
	if ( duration_input.length < 1 ){
		// alert("no duration field");
		if( mwb_wc_bk_public.product_settings.hasOwnProperty( 'mwb_enable_range_picker' ) && 'yes' == mwb_wc_bk_public.product_settings.mwb_enable_range_picker[0] ) {
			start_date = $( '#mwb-wc-bk-date-section #mwb-wc-bk-start-date-input').val();
			end_date   = $( '#mwb-wc-bk-date-section #mwb-wc-bk-end-date-input').val();

			date1 = new Date( start_date );
			date2 = new Date( end_date );
			
			time_difference = date2.getTime() - date1.getTime();
			day_difference  = ( time_difference / ( 1000 * 3600 * 24 ) ) + 1;

			// alert( day_difference );
			var duration = day_difference;
		}
	} else {
		var duration = duration_input.val();
		// alert( mwb_wc_bk_public.product_settings.mwb_booking_unit_input[0] );
		// if( 1 !== mwb_wc_bk_public.product_settings.mwb_booking_unit_input[0] && 'day' == mwb_wc_bk_public.product_settings.mwb_booking_unit_duration[0] ){
		// 	// alert("okkbhj");
		// 	var units = mwb_wc_bk_public.product_settings.mwb_booking_unit_input[0];
		// 	var duration = duration / units;
		// }
	}
	product_data       = JSON.parse(product_data);
	var product_id     = product_data.product_id ;

	var ajax_data = {
		'action'       : 'booking_price_cal',
		'nonce'        : mwb_wc_bk_public.nonce,
		// 'people_total' : total_input,
		// 'formdata' : jQuery( '.cart' ).serialize()
		'product_id'   : product_id,
		'duration'     : duration,
	}

	var people_count_obj = {};
	var people_total = 0;
	$( '#mwb-wc-bk-people-section .people-input' ).each(function() {
		// alert($(this).val());
		var val = $(this).val();
		var id  = $(this).attr( 'data-id' );
		people_count_obj[id] = val;
		people_total += parseInt( val );
	});
	Object.assign( ajax_data,{ 'people_total': people_total });
	Object.assign( ajax_data,{ 'people_count': people_count_obj });
	// console.log( people_count_obj );

	var inc_service_count = {};
	var add_service_count = {};
	$( '.mwb-wc-bk-inc-service-quant' ).each(function(){
		// alert($(this).val());
		val = $(this).val();
		id  = $(this).attr( 'data-id' );
		inc_service_count[id] = val;
	});
	Object.assign( ajax_data, { 'inc_service_count': inc_service_count });

	$( '.mwb-wc-bk-add-service-quant' ).each(function(){
		// alert($(this).val());
		var add_service_obj = $( this ).siblings( 'input[type=checkbox]' );
		if( add_service_obj.is( ':checked' ) ) {
			// alert( 'checked' );
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
}

function show_total($, total_cost, base_cost, service_cost, added_cost_arr) {
	if ( 'yes' == mwb_wc_bk_public.global_settings.mwb_booking_setting_bo_service_total ) {
		alert("working");
		// if(  booking_total_cost > 0 ) {
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
					// console.log( response );
					$( '#mwb-wc-bk-total-fields' ).html( response );
				}
			});
		// }
	}
}
function booking_service_conditions($){

	// var input_div_obj = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div' );
	// var total_people  = input_div_obj.find( '#mwb-wc-bk-people-input-span' ).text();
	var product_data = $( '#mwb-wc-bk-create-booking-form' ).attr( 'product-data' );
	product_data       = JSON.parse(product_data);
	var product_id     = product_data.product_id ;
	// console.log( product_id );
	// $.ajax({
	// 	url      : mwb_wc_bk_public.ajaxurl,
	// 	type     : 'POST',
	// 	// dataType : 'json',
	// 	data     : {
	// 		'action'      : 'booking_service_cal',
	// 	    nonce        : mwb_wc_bk_public.nonce,
	// 		'product_id' : product_id,
	// 	},
	// 	success : function( response ) {
	// 		response = JSON.parse(response);
	// 		// console.log( response );
	// 		// alert( "khbf" );
	// 		// var price_html = response.price_html ; 
	// 		// $('.price').html(price_html);
	// 	},
	// });
}
