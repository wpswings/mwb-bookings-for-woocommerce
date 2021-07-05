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
        $(document).on('change', '.mwb_mbfw_people_number', function(){
            var number_of_people = $(this).val();
            console.log($('.mwb_booking_cart').serializeArray() );
            // retrieve_booking_total_ajax();
        });
        $(document).on('change', '.mwb-mbfw-additional-service-option', function(){
            if ( $(this).prop('checked') ) {
                console.log($(this).data('term-id'));
                retrieve_booking_total_ajax();
            } else {
                console.log('no');
            }
        });
        function retrieve_booking_total_ajax() {
            $.ajax({
                url    : mwb_mbfw_common_obj.ajax_url,
                method : 'post',
                data   : {
                    action    : 'mbfw_retrieve_booking_total_single_page',
                    form_data : $('.mwb_booking_cart').serializeArray(),
                    nonce     : mwb_mbfw_common_obj.nonce
                },
                success : function( msg ) {
                   console.log(msg);
                },
                error   : function() {
                    alert('error');
                }
            });
        }
    });
})( jQuery );
