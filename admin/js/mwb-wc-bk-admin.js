var availability_count = 0;
var cost_count = 0;
var booking_events;
var screen_id = mwb_booking_obj.screen_id;
jQuery(document).ready( function($) {
	availability_not_allowed_days_select2($);
	selected_added_cost_select2($);
	selected_services_select2($);
	selected_people_type_select2($);

	check_product_type($);
	
	dashicons_ajax_change($);
	global_availability_rules($);
	global_cost_rules($);
	create_booking_user_select2($);
	create_booking_product_select2($);
	create_booking_order_select2($);
	create_booking_product_details($);
	booking_product_select2($);
	booking_order_select2($);
	booking_user_select2($);
	ct_custom_fields($);

	if ( screen_id == 'mwb_cpt_booking_page_calendar' ) {
		get_events($);
		render_calendar( $, booking_events );
	}
	jQuery(document).on('click', '.booking-overview__help-icon', function() {
		jQuery('.booking-overview__help').toggleClass('booking-help__out');
	
	});
	
});

function check_product_type($) {
	jQuery( '#post #woocommerce-product-data' ).on('change', '#product-type', function(e){
		var product_type = jQuery(this).val();
		if ( product_type == 'mwb_booking' ) {

			product_general_settings_js($);
			product_availability_settings($);
			product_cost_settings_js($);
			product_people_settings_js($);
			product_services_settings_js($);
		}
	});
	var product_type = jQuery( '#post #woocommerce-product-data #product-type' ).val();
	if ( product_type == 'mwb_booking' ) {
	
		product_general_settings_js($);
		product_availability_settings($);
		product_cost_settings_js($);
		product_people_settings_js($);
		product_services_settings_js($);
	}
}

function get_events($) {

	$.ajax({
		url: mwb_booking_obj.ajaxurl,
		type: 'POST',

		data: {
			'action'  : 'mwb_wc_bk_get_events',
			'nonce'   : mwb_booking_obj.nonce,
			'events'  : 'events',
		},
		success: function( data ) {

			booking_events = data;
			console.log( booking_events );
		},
		async: false
	});
}

function availability_not_allowed_days_select2($) {
	if( $('#mwb_booking_not_allowed_days').length > 0 )
		$('#mwb_booking_not_allowed_days').select2();
}

function selected_added_cost_select2($) {
	if( ! jQuery('#mwb_booking_added_cost_select_search').length > 0 ) {
		return;
	}
	jQuery('#mwb_booking_added_cost_select_search').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'selected_added_costs_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='(#'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function selected_services_select2($) {
	if( ! jQuery('#mwb_booking_services_select_search').length > 0 ) {
		return;
	}
	jQuery('#mwb_booking_services_select_search').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'selected_services_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data )
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='(#'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function selected_people_type_select2($) {
	if( ! jQuery('#mwb_booking_people_select_search').length > 0 ) {
		return;
	}
	jQuery('#mwb_booking_people_select_search').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'selected_people_type_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='(#'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function create_booking_user_select2($) {
	if( ! jQuery('#mwb_create_booking_user_select').length > 0 ) {
		return;
	}
	jQuery('#mwb_create_booking_user_select').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'create_booking_user_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='( #'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function create_booking_product_select2($) {
	if( ! jQuery('#mwb_create_booking_product_select').length > 0 ) {
		return;
	}
	jQuery('#mwb_create_booking_product_select').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'create_booking_product_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='( #'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}
function create_booking_order_select2($) {
	if( ! jQuery('#mwb_create_booking_order_select').length > 0 ) {
		return;
	}
	jQuery('#mwb_create_booking_order_select').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'create_booking_order_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='(#'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function booking_order_select2($) {
	if( ! jQuery('#mwb_booking_order_select').length > 0 ) {
		return;
	}
	jQuery('#mwb_booking_order_select').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'create_booking_order_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='(#'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function booking_product_select2($) {
	if( ! jQuery('#mwb_booking_product_select').length > 0 ) {
		return;
	}
	jQuery('#mwb_booking_product_select').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'create_booking_product_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='( #'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}

function booking_user_select2($) {
	if( ! jQuery('#mwb_booking_user_select').length > 0 ) {
		return;
	}
	jQuery('#mwb_booking_user_select').select2({
		ajax:{
			  url: mwb_booking_obj.ajaxurl,
			  dataType: 'json',
			  delay: 200,
			  data: function (params) {
					return {
					  q: params.term,
					  action: 'create_booking_user_search'
					};
			  },
			  processResults: function( data ) {
			  var options = [];
			  if ( data ) 
			  {
				  $.each( data, function( index, text )
				  {
					  text[1]+='( #'+text[0]+')';
					  options.push( { id: text[0], text: text[1] } );
				  });
			  }
			  return {
				  results:options
			  };
		  },
		  cache: true
	  },
	  minimumInputLength: 3 // The minimum of symbols to input before perform a search.
  });
}


function product_general_settings_js($) {

	$( '#mwb_booking_unit_input' ).attr( 'required', true );

	var allow_cancellation_check = $( '#mwb_booking_general_data #mwb_allow_booking_cancellation' ).is(':checked');
	if ( allow_cancellation_check ) {
		$ ( '#mwb_booking_general_data #mwb_booking_cancellation_days' ).show();
		$ ( '#mwb_booking_general_data #mwb_max_days_for_cancellation' ).prop( 'disabled', false ).attr( 'required', true );
	} else {
		$ ( '#mwb_booking_general_data #mwb_booking_cancellation_days' ).hide();
		$ ( '#mwb_booking_general_data #mwb_max_days_for_cancellation' ).prop( 'disabled', true );
	}

	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_allow_booking_cancellation', function(){
		var allow_cancellation_check = $(this).is(':checked');
		if( allow_cancellation_check ) {
			$ ( '#mwb_booking_general_data #mwb_booking_cancellation_days' ).show();
			$ ( '#mwb_booking_general_data #mwb_max_days_for_cancellation' ).prop( 'disabled', false ).attr( 'required', true );
		} else {
			$ ( '#mwb_booking_general_data #mwb_booking_cancellation_days' ).hide();
			$ ( '#mwb_booking_general_data #mwb_max_days_for_cancellation' ).prop( 'disabled', true );
		}
	});
	var duration = $( '#mwb_booking_general_data #mwb_booking_unit_duration' ).val();
	switch( duration ) {
		case 'hour':
			$( '#mwb_full_day_select' ).hide();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
			break;
		case 'month':
			$( '#mwb_full_day_select' ).hide();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
			break;
		case 'day':
			$( '#mwb_full_day_select' ).show();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
			break;
		case 'minute':
			$( '#mwb_full_day_select' ).hide();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', 60 ).attr( 'min', 0 ).attr( 'step', 15 );				
			break;
	}
	var duration_input = $( '#mwb_booking_general_data #mwb_booking_unit_input' ).val();

	$( '#mwb_booking_general_data #mwb_booking_unit input' ).bind( 'keyup mouseup', function(){
		var duration = $( '#mwb_booking_general_data #mwb_booking_unit_duration' ).val();
		var unit_select = $( '#mwb_booking_general_data #mwb_booking_unit_select' ).val();
		if( $(this).val() == 1 && duration == 'day' && unit_select == 'customer' ) {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
			$('#mwb_booking_general_data #mwb_calendar_range').show();

			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).show();
			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', false );
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).show();
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', false );
		} else {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
			$('#mwb_booking_general_data #mwb_calendar_range').hide();

			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).hide();
			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', true );
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).hide();
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', true );
		}
	});

	var start_booking_from = $( '#mwb_booking_general_data #mwb_start_booking_date' ).val();
	if( start_booking_from == 'custom_date' ) {
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', false).attr( 'required', true );
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).show();
	} else {
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', true);
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).hide();
	}
	var unit_select = $( '#mwb_booking_general_data #mwb_booking_unit_select' ).val();
	if ( duration == 'day' && unit_select == 'customer' && duration_input == 1 ) {
		$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
		$('#mwb_booking_general_data #mwb_calendar_range').show();

		$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).show();
		$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', false );
		$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).show();
		$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', false );
	} else {
		$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
		$('#mwb_booking_general_data #mwb_calendar_range').hide();

		$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).hide();
		$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', true );
		$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).hide();
		$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', true );
	}
	if ( unit_select == 'fixed' ) {
		$( '#mwb_booking_min_duration' ).closest( 'p' ).hide();
		$( '#mwb_booking_min_duration' ).prop( 'disabled', true );

		$( '#mwb_booking_max_duration' ).closest( 'p' ).hide();
		$( '#mwb_booking_max_duration' ).prop( 'disabled', true );
	} else {
		$( '#mwb_booking_min_duration' ).closest( 'p' ).show();
		$( '#mwb_booking_min_duration' ).prop( 'disabled', false );

		$( '#mwb_booking_max_duration' ).closest( 'p' ).show();
		$( '#mwb_booking_max_duration' ).prop( 'disabled', false );
	}
	
	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_booking_unit_select', function(){
		var duration = $( '#mwb_booking_general_data #mwb_booking_unit_duration' ).val();
		var duration_input = $( '#mwb_booking_general_data #mwb_booking_unit_input' ).val();
		if ( duration == 'day' && $(this).val() == 'customer' && duration_input == 1 ) {
			
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
			$('#mwb_booking_general_data #mwb_calendar_range').show();

			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).show();
			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', false );
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).show();
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', false );

		} else {
			
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
			$('#mwb_booking_general_data #mwb_calendar_range').hide();

			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).hide();
			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', true );
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).hide();
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', true );

		}

		if ( $(this).val() == 'fixed' ) {
			$( '#mwb_booking_min_duration' ).closest( 'p' ).hide();
			$( '#mwb_booking_min_duration' ).prop( 'disabled', true );

			$( '#mwb_booking_max_duration' ).closest( 'p' ).hide();
			$( '#mwb_booking_max_duration' ).prop( 'disabled', true );
		} else {
			$( '#mwb_booking_min_duration' ).closest( 'p' ).show();
			$( '#mwb_booking_min_duration' ).prop( 'disabled', false );
			
			$( '#mwb_booking_max_duration' ).closest( 'p' ).show();
			$( '#mwb_booking_max_duration' ).prop( 'disabled', false );
		}

	});

	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_booking_unit_duration', function(){
		var duration = $(this).val();
		var unit_select = $( '#mwb_booking_general_data #mwb_booking_unit_select' ).val()
		var duration_input = $( '#mwb_booking_general_data #mwb_booking_unit_input' ).val();
		if ( duration == 'day' && unit_select == 'customer' && duration_input == 1 ) {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
			$('#mwb_booking_general_data #mwb_calendar_range').show();

			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).show();
			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', false );
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).show();
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', false );
		} else {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
			$('#mwb_booking_general_data #mwb_calendar_range').hide();

			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).closest( 'li' ).hide();
			$( '#mwb_booking_discount_type_field input[value="weekly_discount"]' ).prop( 'disabled', true );
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).closest( 'li' ).hide();
			$( '#mwb_booking_discount_type_field input[value="monthly_discount"]' ).prop( 'disabled', true );
		}
		
		switch( duration ) {
			case 'hour':
				$( '#mwb_full_day_select' ).hide();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
				break;
			case 'month':
				$( '#mwb_full_day_select' ).hide();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
				break;
			case 'day':
				$( '#mwb_full_day_select' ).show();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
				break;
			case 'minute':
				$( '#mwb_full_day_select' ).hide();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', 60 ).attr( 'min', 0 ).attr( 'step', 15 );				
				break;
		}
	});
	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_start_booking_date', function(){
		if( $(this).val() == 'custom_date' ) {
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', false).attr( 'required', true );
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).show();
		} else {
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', true);
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).hide();
		}
	});
}

function product_availability_settings($) {

	$('#mwb_booking_start_time').attr( 'required', true );
	$('#mwb_advance_booking_max_input').attr( 'required', true );

	var start_time = $('#mwb_booking_start_time').val();
	var end_time   = $('#mwb_booking_end_time').val();

	if ( start_time > end_time ) {
		jQuery( '#mwb_booking_time_notice' ).text( ' *End time should be less than start time' );
		jQuery( '#mwb_booking_time_notice' ).css( 'color', 'red' );
		jQuery( this ).val('');

	} else {
		jQuery( '#mwb_booking_time_notice' ).text( '' );
	}

	$( '#mwb_booking_availability_data' ).on( 'change', '#mwb_booking_end_time', function(e){

		var start_t = jQuery('#mwb_booking_start_time').val();

		var end = jQuery(this).val();
		if ( start_t >= end ) {
			jQuery( '#mwb_booking_time_notice' ).text( ' *End time should be less than start time' );
			jQuery( '#mwb_booking_time_notice' ).css( 'color', 'red' );
			jQuery( this ).val('');

		} else {
			jQuery( '#mwb_booking_time_notice' ).text( '' );
		}
	} );
	$( '#mwb_booking_availability_data' ).on( 'change', '#mwb_booking_start_time', function($){

		var end_t = jQuery('#mwb_booking_end_time').val();

		var start = jQuery(this).val();
		if ( start >= end_t ) {
			jQuery( '#mwb_booking_time_notice' ).text( ' *End time should be less than start time' );
			jQuery( '#mwb_booking_time_notice' ).css( 'color', 'red' );
			jQuery( this ).val('');

		} else {
			jQuery( '#mwb_booking_time_notice' ).text( '' );
		}
	
	} );
}

function product_cost_settings_js($) {

	$( '#mwb_booking_unit_cost_input' ).attr( 'required', true );

	var unit_cost_check = $( '#mwb_booking_cost_data .unit-cost #mwb_booking_unit_cost_multiply' )
	if( unit_cost_check.is( ':checked' ) ) {
		$( '#mwb_booking_cost_data .extra-cost' ).hide();
		$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_input' ).prop( 'disabled', true );
		$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_people_input' ).prop( 'disabled', true );
	} else {
		$( '#mwb_booking_cost_data .extra-cost' ).show();
		$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_input' ).prop( 'disabled', false );
		$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_people_input' ).prop( 'disabled', false );
	}
	$( '#mwb_booking_cost_data ' ).on( 'change', '#mwb_booking_unit_cost_multiply', function() {
		if( $(this).is( ':checked' )) {
			$( '#mwb_booking_cost_data .extra-cost' ).hide();
			$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_input' ).prop( 'disabled', true );
			$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_people_input' ).prop( 'disabled', true );
		} else {
			$( '#mwb_booking_cost_data .extra-cost' ).show();
			$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_input' ).prop( 'disabled', false );
			$( '#mwb_booking_cost_data .extra-cost #mwb_booking_extra_cost_people_input' ).prop( 'disabled', false );
		}
	});

	$( '#mwb_booking_cost_data .mwb_discount_type').each(function() {
		if( $(this).is(':checked') ) {
			var discount_type = $(this).val();
			switch (discount_type) {
				case 'none':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'weekly_discount':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'monthly_discount':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'custom_discount':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).show();
				break;
				default:
					break;
			}
		}
	});

	$( '#mwb_booking_cost_data').on( 'click', '.mwb_discount_type', function(){
		if ( $(this).is(':checked') ) {
			var discount_type = $(this).val();
			switch (discount_type) {
				case 'none':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'weekly_discount':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'monthly_discount':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'custom_discount':
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).show();
				break;
				default:
					break;
			}
		}
	});
	
}

function product_people_settings_js($) {

	var enable_people = $( '#mwb_booking_people_data #mwb_people_enable_checkbox')
	if( enable_people.is(':checked') ) {
		$( '#mwb_booking_people_data #mwb_min_people_per_booking').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_max_people_per_booking').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_people_as_seperate_booking').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_enable_people_types').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_people_type_add button a').attr( 'href', 'edit-tags.php?taxonomy=mwb_ct_people_type&post_type=mwb_cpt_booking' );
		$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', false );
	} else {
		$( '#mwb_booking_people_data #mwb_min_people_per_booking').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_max_people_per_booking').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_people_as_seperate_booking').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_enable_people_types').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_people_type_add button a').removeAttr( 'href' );
		$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', true );
	}

	$( '#mwb_booking_people_data' ).on( 'change', '#mwb_people_enable_checkbox', function(){
		if( $(this).is(':checked') ) {
			$( '#mwb_booking_people_data #mwb_min_people_per_booking').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_max_people_per_booking').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_people_as_seperate_booking').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_enable_people_types').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_people_type_add button a').attr( 'href', 'edit-tags.php?taxonomy=mwb_ct_people_type&post_type=mwb_cpt_booking' );
			$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', false );
		} else {
			$( '#mwb_booking_people_data #mwb_min_people_per_booking').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_max_people_per_booking').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_people_as_seperate_booking').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_enable_people_types').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_people_type_add button a').removeAttr( 'href' );
			$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', true );	
		}
	});

	var enable_people_type = $( '#mwb_booking_people_data #mwb_enable_people_types' );
	if( enable_people_type.is(':checked') ) {
		$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', false );
		$( '#mwb_booking_people_data #mwb_add_people_type_select').show();
	} else {
		$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', true );
		$( '#mwb_booking_people_data #mwb_add_people_type_select').hide();
	}

	$( '#mwb_booking_people_data' ).on( 'change', '#mwb_enable_people_types', function(){
		if( $(this).is(':checked') ) {
			$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', false );
			$( '#mwb_booking_people_data #mwb_add_people_type_select').show();
		} else {
			$( '#mwb_booking_people_data #mwb_people_type_add button').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_add_people_type_select select').prop( 'disabled', true );
			$( '#mwb_booking_people_data #mwb_add_people_type_select').hide();
		}
	});
}

function product_services_settings_js($) {

	var enable_services = $( '#mwb_booking_services_data #mwb_services_enable_checkbox' );
	if( enable_services.is(':checked') ) {
		$( '#mwb_booking_services_data #mwb_booking_services_select_search').prop( 'disabled', false );
		$( '#mwb_booking_services_data #mwb_services_mandatory_check').prop( 'disabled', false );
		$( '#mwb_booking_services_data #mwb_booking_service_add button').prop( 'disabled', false );
		$( '#mwb_booking_services_data #mwb_booking_service_add button a').attr( 'href', 'edit-tags.php?taxonomy=mwb_ct_services&post_type=mwb_cpt_booking' );
	} else {
		$( '#mwb_booking_services_data #mwb_booking_services_select_search').prop( 'disabled', true );
		$( '#mwb_booking_services_data #mwb_services_mandatory_check').prop( 'disabled', true );
		$( '#mwb_booking_services_data #mwb_booking_service_add button').prop( 'disabled', true );
		$( '#mwb_booking_services_data #mwb_booking_service_add button a').removeAttr( 'href' );
	}

	$( '#mwb_booking_services_data' ).on( 'change', '#mwb_services_enable_checkbox', function(){
		if( $(this).is(':checked') ) {
			$( '#mwb_booking_services_data #mwb_booking_services_select_search').prop( 'disabled', false );
			$( '#mwb_booking_services_data #mwb_services_mandatory_check').prop( 'disabled', false );
			$( '#mwb_booking_services_data #mwb_booking_service_add button').prop( 'disabled', false );
			$( '#mwb_booking_services_data #mwb_booking_service_add button a').attr( 'href', 'edit-tags.php?taxonomy=mwb_ct_services&post_type=mwb_cpt_booking' );
		} else {
			$( '#mwb_booking_services_data #mwb_booking_services_select_search').prop( 'disabled', true );
			$( '#mwb_booking_services_data #mwb_services_mandatory_check').prop( 'disabled', true );
			$( '#mwb_booking_services_data #mwb_booking_service_add button').prop( 'disabled', true );
			$( '#mwb_booking_services_data #mwb_booking_service_add button a').removeAttr( 'href' );
		}
	});
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

	$( '#mwb_global_availability_form .mwb_global_availability_rule_weekdays' ).each(function() {
		if ( $(this).is( ':checked' ) ) {
			$(this).closest( '.mwb_global_availability_rule_fields' ).find('.bookable').hide();
			$(this).closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').show();
		} else {
			$(this).closest( '.mwb_global_availability_rule_fields' ).find('.bookable').show();
			$(this).closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').hide();
		}

		$(this).closest( '.mwb_global_availability_rule_fields' ).find( '.mwb_global_availability_rule_weekdays_book_button' ).each( function() {
			if( $(this).val() == 'bookable' ) {
				$(this).css('color', 'green');
			} else if( $(this).val() == 'non-bookable' ) {
				$(this).css('color', 'red');
			} else if( $(this).val() == 'no-change' ) {
				$(this).css('color', 'grey');
			}
			$( this ).on( 'click', function() {

				var book = $(this).val();

				switch ( book ) {
					case 'bookable':
						$(this).val('non-bookable');
						$(this).siblings().val('non-bookable');
						$(this).css('color', 'red');
						break;
					case 'non-bookable':
						$(this).val('no-change');
						$(this).siblings().val('no-change');
						$(this).css('color', 'grey');
						break;
					case 'no-change':
						$(this).val('bookable');
						$(this).siblings().val('bookable');
						$(this).css('color', 'green');
						break;
				}
			} );
		});
		$(this).closest( '.mwb_global_availability_rule_fields' ).find( '.mwb_global_availability_rule_type' ).each(function(){
			if ( $(this).is(":checked") ) {
				var rule_type_check = $(this).val();
				switch( rule_type_check ) {
					case 'generic':
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').hide();
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').show();
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', true)});
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', false)});
						break;
					case 'specific':
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').show();
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').hide();
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', false)});
						$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', true)});
						break;
				}
			}
		});
		$(this).closest( '.mwb_global_availability_rule_fields' ).find( '.mwb_global_availability_rule_type' ).on( 'change', function(){
			var rule_type = $(this).val();
			switch( rule_type ) {
				case 'generic':
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').hide();
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').show();
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', true)});
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', false)});
					break;
				case 'specific':
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').show();
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').hide();
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', false)});
					$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', true)});
					break;
			}
		});
	});

	var c = jQuery( '#mwb_add_avialability_rule' ).attr( 'rule_count' );
	if ( parseInt(c) > 0 ){
		jQuery( '#mwb_global_availability_form .mwb_add_new_rule_text' ).slideUp( 200 );
	} else {
		jQuery( '#mwb_global_availability_form .mwb_add_new_rule_text' ).slideDown( 200 );
	}
	
	jQuery('#mwb_global_availability_form').on('click', '#mwb_add_avialability_rule', function(e){

		jQuery( '#mwb_global_availability_form .mwb_add_new_rule_text' ).slideUp( 200 );

		if ( availability_count < parseInt(jQuery(this).attr('rule_count')) ) {
			availability_count = parseInt(jQuery(this).attr('rule_count'));
			availability_count++;
			// alert('if working');
		} else {
			availability_count++;
			// alert( 'else working' );
		}

		$.ajax({
			url: mwb_booking_obj.ajaxurl,
			type: 'POST',
			data: {
				'action'  : 'add_global_availability_rule',
				'nonce'   : mwb_booking_obj.nonce,
				'rule_count' : availability_count,
			},
			success: function( data ) {
				
				console.log( data );
				$( '.mwb_booking_global_availability_rules #mwb_global_availability_rules' ).append(data);

				jQuery('.mwb_booking_global_availability_rules .mwb_global_availability_rule_heading').each( function() {
					$(this).on('click', 'label', function(e){
						$(this).closest('.mwb-availability-rules__table').find( '.mwb_global_availability_toggle' ).slideToggle(200);
					});
				} );

				$( '#mwb_global_availability_form .mwb_global_availability_rule_weekdays' ).each(function() {

					$(this).closest( '.mwb_global_availability_rule_fields' ).find( '.mwb_global_availability_rule_weekdays_book_button' ).each( function() {
						if( $(this).val() == 'bookable' ) {
							$(this).css('color', 'green');
						} else if( $(this).val() == 'non-bookable' ) {
							$(this).css('color', 'red');
						} else if( $(this).val() == 'no-change' ) {
							$(this).css('color', 'grey');
						}
						$( this ).on( 'click', function() {
			
							var book = $(this).val();
			
							switch ( book ) {
								case 'bookable':
									$(this).val('non-bookable');
									$(this).siblings().val('non-bookable');
									$(this).css('color', 'red');
									break;
								case 'non-bookable':
									$(this).val('no-change');
									$(this).siblings().val('no-change');
									$(this).css('color', 'grey');
									break;
								case 'no-change':
									$(this).val('bookable');
									$(this).siblings().val('bookable');
									$(this).css('color', 'green');
									break;
							}
						} );
					});
					$(this).closest( '.mwb_global_availability_rule_fields' ).find( '.mwb_global_availability_rule_type' ).each(function(){
						if ( $(this).is(":checked") ) {
							var rule_type_check = $(this).val();
							switch( rule_type_check ) {
								case 'generic':
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').hide();
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').show();
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', true)});
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', false)});
									break;
								case 'specific':
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').show();
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').hide();
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', false)});
									$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', true)});
									break;
							}
						}
					});
					$(this).closest( '.mwb_global_availability_rule_fields' ).find( '.mwb_global_availability_rule_type' ).on( 'change', function(){
						var rule_type = $(this).val();
						switch( rule_type ) {
							case 'generic':
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').hide();
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').show();
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', true)});
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', false)});
								break;
							case 'specific':
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific').show();
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic').hide();
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.specific input').each(function(){$(this).prop('disabled', false)});
								$(this).closest( '.mwb_global_availability_rule_fields' ).find('.generic select').each(function(){$(this).prop('disabled', true)});
								break;
						}
					});
				});
			},
		});
	});

	$( '#mwb_global_availability_form').on( 'change', ".mwb_global_availability_rule_weekdays" , function(){
		var weekdays_rule_check = $(this).is(':checked');
		var check_obj = $( this );
		if( weekdays_rule_check ) {
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.bookable').slideUp(200);
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').slideDown(200);
			
		} else {
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.bookable').slideDown(200);
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').slideUp(200);
		}	
	});


	jQuery('.mwb_booking_global_availability_rules .mwb_global_availability_rule_heading').each( function() {
		$(this).on('click', 'label', function(e){
		
			$(this).closest('.mwb-availability-rules__table').find( '.mwb_global_availability_toggle' ).slideToggle(200);

		});
	} );

	jQuery('#mwb_global_availability_form').on('click', '#mwb_delete_avialability_rule', function(e){
		
		var c = jQuery( '#mwb_add_avialability_rule' ).attr( 'rule_count' );
		if ( parseInt(c) > 0 ){
			jQuery( '#mwb_global_availability_form .mwb_add_new_rule_text' ).slideUp( 200 );
		} else {
			jQuery( '#mwb_global_availability_form .mwb_add_new_rule_text' ).slideDown( 200 );
		}
		var del_count = parseInt($(this).attr('rule_count'));

		if($(this).hasClass('ajax-added')) {
			$( '#mwb_global_availability_rule_' + del_count ).remove();
			return;
		}

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
				// var rule_count = $( '#mwb_delete_avialability_rule' ).attr( 'rule_count' );
				// $( '#mwb_delete_avialability_rule' ).attr( 'rule_count', (rule_count - 1) );
			}
		});
	});
}

function global_cost_conditions(obj, $) {
	var condition = obj.val();
	switch (condition) {
		case 'day':
			obj.closest('.mwb_global_cost_rule_fields').find('.days').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", false)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", true)});
			break;
		case 'date':
			obj.closest('.mwb_global_cost_rule_fields').find('.days').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", false)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", true)});
			break;
		case 'month':
			obj.closest('.mwb_global_cost_rule_fields').find('.days').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", false)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", true)});
			break;
		case 'week':
			obj.closest('.mwb_global_cost_rule_fields').find('.days').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", false)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", true)});
			break;
		case 'time':
			obj.closest('.mwb_global_cost_rule_fields').find('.days').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", false)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", true)});
			break;
		case 'unit':
			obj.closest('.mwb_global_cost_rule_fields').find('.days').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", false)});
			break;
		default:
			obj.closest('.mwb_global_cost_rule_fields').find('.days').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.date').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.months').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.time').hide();
			obj.closest('.mwb_global_cost_rule_fields').find('.unit').show();
			obj.closest('.mwb_global_cost_rule_fields').find('.days select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.date input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.months select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.weeks select').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.time input').each(function(){$(this).prop("disabled", true)});
			obj.closest('.mwb_global_cost_rule_fields').find('.unit input').each(function(){$(this).prop("disabled", false)});
			break;

	}
}

function global_cost_rules($) {

	$( '#mwb_global_cost_form .mwb_booking_global_cost_condition' ).each(function(){
		var obj = $(this);
		global_cost_conditions(obj, $);
		$(this).on('change', function(){
			global_cost_conditions($(this), $);
		});

	});

	jQuery('#mwb_global_cost_form').on('click', '#mwb_add_cost_rule', function(e){
	
		if ( cost_count < parseInt(jQuery(this).attr('rule_count')) ) {
			cost_count= parseInt(jQuery(this).attr('rule_count'));
			cost_count++;
		} else {
			cost_count++;
		}
		
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
				$( '#mwb_global_cost_form .mwb_booking_global_cost_condition' ).each(function(){
					var obj = $(this);
					global_cost_conditions(obj, $);
					$(this).on('change', function(){
						global_cost_conditions($(this), $);
					});
				});
			},
		});
	});
	$('#mwb_global_cost_form').on('click', '#mwb_delete_cost_rule', function(e){
		
		var del_count = parseInt($(this).attr('rule_count'));

		$.ajax({
			url  : mwb_booking_obj.ajaxurl,
			type : 'POST',
			data : {
				'action'    : 'delete_global_cost_rule',
				'nonce'     : mwb_booking_obj.nonce,
				'del_count' : del_count,
			},
			success: function( data ){
				$( '#mwb_global_cost_rule_' + del_count ).remove();
			}
		});
	});

}

function ct_custom_fields($){

	var has_quantity    = $( '#mwb_booking_ct_services_has_quantity' );
	var multiply_people = $( '#mwb_booking_ct_services_multiply_people' );
	if( has_quantity.is(":checked") ) {
		$( '#mwb_booking_ct_services_min_quantity' ).prop( 'disabled', false );
		$( '#mwb_booking_ct_services_max_quantity' ).prop( 'disabled', false );
	} else {
		$( '#mwb_booking_ct_services_min_quantity' ).prop( 'disabled', true );
		$( '#mwb_booking_ct_services_max_quantity' ).prop( 'disabled', true );
	}
	if( multiply_people.is( ":checked" ) ) {
		$( '.mwb_ct_service_multiply_people' ).each( function() {
			$(this).prop( 'disabled', false );
		});
	} else {
		$( '.mwb_ct_service_multiply_people' ).each( function() {
			$(this).prop( 'disabled', true );
		});
	}
	$( document ).on( 'change', '#mwb_booking_ct_services_has_quantity', function(){
		if( $(this).is(":checked") ) {
			$( '#mwb_booking_ct_services_min_quantity' ).prop( 'disabled', false );
			$( '#mwb_booking_ct_services_max_quantity' ).prop( 'disabled', false );
		} else {
			$( '#mwb_booking_ct_services_min_quantity' ).prop( 'disabled', true );
			$( '#mwb_booking_ct_services_max_quantity' ).prop( 'disabled', true );
		}
	});
	$( document ).on( 'change', '#mwb_booking_ct_services_multiply_people', function(){
		if( $(this).is( ":checked" ) ) {
			$( '.mwb_ct_service_multiply_people' ).each( function() {
				$(this).prop( 'disabled', false );
			});
		} else {
			$( '.mwb_ct_service_multiply_people' ).each( function() {
				$(this).prop( 'disabled', true );
			});
		}
	});
}

function create_booking_product_details($) {

	$('#mwb_create_booking_form').on( 'change', '#mwb_create_booking_product_select', function() {
		var product_id = $(this).val();
		$.ajax({
			url  : mwb_booking_obj.ajaxurl,
			type : 'POST',
			data : {
				'action'     : 'create_booking_product_details',
				'nonce'      : mwb_booking_obj.nonce,
				'product_id' : product_id,
			},
			success: function( data ) {
				$( '#mwb_create_booking_form' ).find( '.product_ajax' ).html( data );
			}
		});
	});
}
function render_calendar($ , booking_events) {

	var booking_events = JSON.parse(booking_events);

	var calendarEl = document.getElementById('mwb-wc-bk-calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {

		initialView: 'dayGridMonth',
	//  initialView: 'timeGridWeek',
	//  timeZone: 'UTC',

		headerToolbar: {
			left  : 'prev,next today',
			center: 'title',
			right : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
		},
		
		navLinks: true, // can click day/week names to navigate views
    	editable: true,
     	selectable: true,
     	selectMirror: true,
     	nowIndicator: true,
		events : booking_events,

		eventClick: function( info ) {
			$.ajax({
				url  : mwb_booking_obj.ajaxurl,
				type : 'POST',
				data : {
					'action'     : 'mwb_calendar_event_details_popup',
					'nonce'      : mwb_booking_obj.nonce,
					'booking_id' : info.event.id,
				},
				success: function( data ) {
					console.log( data );
					$('#calendar_event_popup').dialog({
						title:info.event.title,
						autoOpen: false,
						modal: true,
						width: 400,
						height: 500
					});
					$('#calendar_event_popup').html(data);
					$('#calendar_event_popup').dialog( 'open' );

				}
			});
		},
		// events: [
		// 	{
		// 	  title: 'All Day Event',
		// 	  start: '2021-02-01',
		// 	},
		// 	{
		// 	  title: 'Long Event',
		// 	  start: '2021-02-07',
		// 	  end: '2021-02-10'
		// 	},
		// 	{
		// 	  groupId: 999,
		// 	  title: 'Repeating Event',
		// 	  start: '2021-02-09T16:00:00'
		// 	},
		// 	{
		// 	  groupId: 999,
		// 	  title: 'Repeating Event',
		// 	  start: '2021-02-16T16:00:00'
		// 	},
		// 	{
		// 	  title: 'Conference',
		// 	  start: '2021-02-11',
		// 	  end: '2021-02-13'
		// 	},
		// 	{
		// 	  title: 'Meeting',
		// 	  start: '2021-02-12T10:30:00',
		// 	  end: '2021-02-12T12:30:00'
		// 	},
		// 	{
		// 	  title: 'Lunch',
		// 	  start: '2021-02-12T12:00:00'
		// 	},
		// 	{
		// 	  title: 'Meeting',
		// 	  start: '2021-02-12T14:30:00'
		// 	},
		// 	{
		// 	  title: 'Happy Hour',
		// 	  start: '2021-02-12T17:30:00'
		// 	},
		// 	{
		// 	  title: 'Dinner',
		// 	  start: '2021-02-12T20:00:00'
		// 	},
		// 	{
		// 	  title: 'Birthday Party',
		// 	  start: '2021-02-13T07:00:00'
		// 	},
		// 	{
		// 	  title: 'Click for Google',
		// 	  url: 'http://google.com/',
		// 	  start: '2021-02-28'
		// 	}
		//   ],

		
		// events: mwb_booking_obj.ajaxurl,
		
		
		// dateClick  : function() {
		// 	alert('a day has been clicked!');
		// }
		  
		// views: {
		// 	dayGridMonth: { // name of view
		// 	//   titleFormat: { month: '2-digit', day: '2-digit', year: 'numeric' }
		// 	  // other view-specific options here
		// 	}
		// }

	});
	
	calendar.on('dateClick', function(info) {
		console.log('clicked on ' + info.dateStr);
	  });
	calendar.render();
}
