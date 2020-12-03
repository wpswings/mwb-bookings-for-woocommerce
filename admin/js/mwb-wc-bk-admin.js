var availability_count = 0;
var cost_count = 0;
jQuery(document).ready( function($) {
	initialize_select2();
	product_settings_js($);
	dashicons_ajax_change($);
	global_availability_rules($);
	global_cost_rules($);
});

function initialize_select2() {
	if( jQuery('#mwb_booking_not_allowed_days').length > 0 )
		jQuery('#mwb_booking_not_allowed_days').select2();
}

function product_settings_js($) {

	jQuery( '#mwb_booking_general_data' ).on( 'change', '#mwb_booking_unit_duration', function(){

		var duration = $(this).val();
		alert(duration);
		switch( duration ) {
			case 'hour':
				$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).show();
				$('#mwb_booking_general_data #mwb_start_booking_time').show();
				break;
			case 'month':
				break;
			case 'day':
				$( '#mwb_full_day_select' ).show();
				break;
			case 'minute':
				$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).show();
				$('#mwb_booking_general_data #mwb_start_booking_time').show();
				break;
		}

	} );
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

	if ( $( '#mwb_global_availability_form .mwb_global_availability_rule_weekdays' ).is( ':checked' ) ) {
		$( '#mwb_global_availability_form .mwb_global_availability_rule_bookable' ).parentsUntil( 'tr' ).hide();
	} else {
		jQuery( '#mwb_global_availability_form .mwb_global_availability_rule_weekdays_book' ).hide();
	}

	jQuery('#mwb_global_availability_form').on('click', '#mwb_add_avialability_rule', function(e){
		//e.preventDefault();
		if ( availability_count < parseInt(jQuery(this).attr('rule_count')) ) {
			availability_count= parseInt(jQuery(this).attr('rule_count'));
			availability_count++;
		} else {
			availability_count++;
		}
		
		alert( "count: " + availability_count );

		// 	var pattern = /[0-9]$/;
		$.ajax({
			url: mwb_booking_obj.ajaxurl,
			type: 'POST',
			data: {
				'action'  : 'add_global_availability_rule',
				'nonce'   : mwb_booking_obj.nonce,
				'rule_count' : availability_count,
			},
			success: function( data ) {
				$( '.mwb_booking_global_availability_rules #mwb_global_availability_rules' ).append(data);
			},
		});
	});

	jQuery('#mwb_global_availability_form .mwb_global_availability_rule_heading').on('click', 'label', function(e){
		var count = $(this).attr('data-id');
		$( '#mwb_global_availability_rule_' + count ).find('table').toggle();
	});

	jQuery('#mwb_global_availability_form').on('click', '#mwb_delete_avialability_rule', function(e){
		
		alert('pressed');
		var del_count = parseInt($(this).attr('rule_count'));
		alert(del_count);

		$.ajax({
			url  : mwb_booking_obj.ajaxurl,
			type : 'POST',
			data : {
				'action'    : 'delete_global_availability_rule',
				'nonce'     : mwb_booking_obj.nonce,
				'del_count' : del_count,
			},
			success: function( data ){
				$( '#mwb_global_availability_rule_' + del_count ).remove();
			}
		});
	});
}
function global_cost_rules($) {

	jQuery('#mwb_global_cost_form').on('click', '#mwb_add_cost_rule', function(e){
		//e.preventDefault();
		cost_count++;
		alert("count: " + cost_count);
		
		if ( $( '#mwb_global_cost_form #mwb_global_cost_rules div').length > 1 ) {
			var id = $( '.mwb_booking_global_cost_rules #mwb_global_cost_rules div' ).attr( "id" );
			alert("id" + id);
			var pattern = /[0-9]$/;
			var count_reset = id.match(pattern);
			//var count_reset = $( '#mwb_global_cost_form #mwb_global_cost_rules div' ).data('id');
			alert("count-reset: " + count_reset);
			if ( cost_count <= count_reset ) {
				cost_count = count_reset;
				cost_count++;
			}
		}
		alert("final-count: " + cost_count);
		$.ajax({
			url: mwb_booking_obj.ajaxurl,
			type: 'POST',
			data: {
				'action'  : 'add_global_cost_rule',
				'nonce'   : mwb_booking_obj.nonce,
				'rule_count' : cost_count,
			},
			success: function( data ) {
				$( '.mwb_booking_global_cost_rules #mwb_global_cost_rules' ).append(data);
			},
		});
	});
}
