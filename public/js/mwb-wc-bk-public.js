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
	people_dialog($);
})

function mwb_wc_bk_add_to_cart_form_update($){
	$('.mwb-wc-bk-form-section').on('change' , 'input' , function(e){
		var product_data   = $('.mwb-wc-bk-form-section').attr('product-data');
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

	function people_dialog($) {

		$( "#mwb-wc-bk-create-booking-form #mwb-wc-bk-people-input" ).on( 'change', '#mwb-wc-bk-people-input', function() {
			$( "#mwb_membership_buy_now_modal_form" ).dialog( "open" );
		} );
		$( "#mwb_membership_buy_now_modal_form" ).dialog({
			modal    : true,
			autoOpen : false,
			show     : {effect: "blind", duration: 800},
			width    : 700,
		}); 
	}

}