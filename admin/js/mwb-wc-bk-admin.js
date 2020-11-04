jQuery(document).ready( function($) {
	initialize_select2();
	dashicons_ajax_change($);
});

function initialize_select2() {
	if( jQuery('#mwb_booking_not_allowed_days').length > 0 )
		jQuery('#mwb_booking_not_allowed_days').select2();
}

function dashicons_ajax_change($) {
	jQuery( '.tags #the-list' ).on( 'click', 'tr td .dashicons', function() {

		if ( $( this ).hasClass( 'dashicons-yes' ) ) {
			$( this ).removeClass( 'dashicons-yes' ).addClass( 'dashicons-no-alt' );
		} else {
			$( this ).removeClass( 'dashicons-no-alt' ).addClass( 'dashicons-yes' );
		}
		var id = $( this ).parents('tr').attr('id');
		var term_id = id.substr( id.indexOf('-')+1, id.length );
		//alert ( term_id );
		var name = $( this ).parent().attr('class');
		name = name.substr( 0, name.indexOf(' ') );
		$.ajax({
			url  : mwb_booking_obj.ajaxurl,
			type : 'POST',
			data : {
				'action'  : 'dachicon_change_handler',
				'nonce'   : mwb_booking_obj.nonce,
				'name'    : name,
				'term_id' : term_id,
			},
			success: function( data ) {
				
			},
		});
	});
}
