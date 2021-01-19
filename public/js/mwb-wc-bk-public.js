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
var ajaxurl = mwb_wc_bk_public.ajaxurl ; 
jQuery(document).ready( function($) {
	mwb_wc_bk_add_to_cart_form_update($);
	people_conditions($);
})

function mwb_wc_bk_add_to_cart_form_update($){
	$('.mwb-wc-bk-form-section #mwb-wc-bk-duration-div').on('change' , 'input' , function(e){
		var product_data   = $('#mwb-wc-bk-duration-section').attr('product-data');
		product_data       = JSON.parse(product_data);
		var product_id     = product_data.product_id ; 
		var duration_input = $('#mwb-wc-bk-duration-input') ; 
		var duration       = duration_input.val();
		var request_data   = {
			'product_id' : product_id,
			'duration'   : duration,
			'action'     : 'mwb_wc_bk_update_add_to_cart'
		}
		$.post( ajaxurl, request_data ).done(function( response ){
			response = JSON.parse(response);
			var price_html = response.price_html ; 
			$('.price').html(price_html);
		});
	});
}

function people_conditions($) {
	var people_count = 0;
	var max_people = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div input[type=hidden]' ).attr('data-max');
	var min_people = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div input[type=hidden]' ).attr('data-min');

	$( '#mwb-wc-bk-people-section' ).on( 'click', "label[for='mwb-wc-bk-people-input-div']", function(){
		// alert("working");
		// console.log( $(this).siblings() );
		var div = $(this).siblings();
		if( 'mwb-wc-bk-people-input-div' === div.attr('id') ){
			div.toggle();
		}
	});

	$( document ).on('change', '.people-input', function(e) {

		const people_input = $( '.people-input' );
		const currentObj = $( this );
		var total_input = 0;
		// console.log(currentObj);
		// console.log(people_input);

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
		var product_data   = $('#mwb-wc-bk-create-booking-form').attr('product-data');
		product_data       = JSON.parse(product_data);
		var product_id     = product_data.product_id ; 
		var ajax_data = {
			'action'       : 'booking_price_cal',
			'nonce'        : mwb_wc_bk_public.nonce,
			'people_total' : total_input,
			// 'formdata' : jQuery( '.cart' ).serialize()
			'product_id'   : product_id,
		}
		$( '#mwb-wc-bk-people-section .people-input' ).each(function() {
			// alert($(this).val());
			var val = $(this).val();
			var id  = $(this).attr( 'data-id' );
			var people_count_obj = {
				[id] : val,
			}
			Object.assign( ajax_data, people_count_obj );
		});
		// console.log( ajax_data );
		$.ajax({
			url      : mwb_wc_bk_public.ajaxurl,
			type     : 'POST',
			data     : ajax_data,
			success : function( response ) {
				response = JSON.parse(response);
				// console.log( response );
				// alert( "khbf" );
				var price_html = response.price_html ; 
				$('.price').html(price_html);
			},
		});
	});

	// $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-input' ).each(function() {
	// 	// alert($(this).val());
	// 	people_count += parseInt( $(this).val() );
		
	// 	// $(this).bind('keyup mouseup', function(){
	// 	// 	people_count += parseInt( $(this).val() );
	// 	// 		// alert(people_count);
	// 	// });
	// 	// $(this).bind('keydown mousedown', function(){
	// 	// 	people_count -= parseInt( $(this).val() );
	// 	// 	alert(people_count);
	// 	// });
	// 	// people_count =+ $(this).val();
	// });
	
	// $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div .people-input' ).each(function() {
	// 	$(this).bind('keyup mouseup', function(){
	// 		var value = $(this).val();
	// 		people_count += parseInt( value );
	// 		// people_count ++;
	// 			// alert(people_count);
	// 			console.log(people_count);
	// 	});
	// 	$(this).bind('keydown mousedown', function(){
	// 		var value = $(this).val();
	// 		people_count -= parseInt( value );
	// 		// people_count --;
	// 			// console.log(people_count);
	// 			console.log(people_count);
	// 	});
	// 	if( people_count > max_people ) {
	// 		$( this ).off( 'keyup mouseup' );
	// 	}
	// });
	
	// $( "#mwb-wc-bk-people-section" ).on( 'click', '#mwb-wc-bk-people-input-div', function() {
	// 	// alert("working");
	// 	// $(this).find( '.mwb-wc-bk-people-type-popup' ).toggle();
	// 	var min_people = $(this).find( 'input[type=hidden]' ).attr('data-min');
	// 	var max_people = $(this).find( 'input[type=hidden]' ).attr('data-max');
		
	// 	// alert( min_people + " " + max_people );
	// 	$(this).find( '.people-input' ).each(function() {
	// 		// alert($(this).val());
	// 		people_count += parseInt( $(this).val() );
	// 		$(this).bind('keyup mouseup', function(){
	// 			people_count += parseInt( $(this).val() );
	// 		});
	// 		$(this).bind('keydown mousedown', function(){
	// 			people_count -= parseInt( $(this).val() );
	// 		});
	// 		// people_count =+ $(this).val();
	// 	});
	// 	alert(people_count);
	// });
}

function price_update_ajax(){

	var input_div_obj = $( '#mwb-wc-bk-people-section #mwb-wc-bk-people-input-div' );
	var total_people  = input_div_obj.find( '#mwb-wc-bk-people-input-span' ).text();
}
