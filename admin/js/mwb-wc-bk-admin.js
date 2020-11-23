var count = 0;
jQuery(document).ready( function($) {
	initialize_select2();
	dashicons_ajax_change($);
	global_availability_rules($);
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

function global_availability_rules($) {
	// $.ajax({
	// 	url: mwb_booking_obj.ajaxurl,
	// 	type: 'POST',
	// 	data: {
	// 		'action'  : 'add_global_availability_rule_save',
	// 		'nonce'   : mwb_booking_obj.nonce,
	// 	},
	// 	success: function( data ) {
	// 		$( '.mwb_booking_global_availability_rules #mwb_global_availability_rules' ).append(data);
	// 	},
	// });
	jQuery('#mwb_global_availability_form').on('click', '#mwb_add_avialability_rule', function(e){
		//e.preventDefault();
		count++;
		alert(count);
		$.ajax({
			url: mwb_booking_obj.ajaxurl,
			type: 'POST',
			data: {
				'action'  : 'add_global_availability_rule',
				'nonce'   : mwb_booking_obj.nonce,
				'rule_count' : count,
			},
			success: function( data ) {
				$( '.mwb_booking_global_availability_rules #mwb_global_availability_rules' ).append(data);
			},
		});
	});
}
