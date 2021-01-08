var availability_count = 0;
var cost_count = 0;
jQuery(document).ready( function($) {
	availability_not_allowed_days_select2($);
	selected_added_cost_select2($);
	selected_services_select2($);
	selected_people_type_select2($);
	product_general_settings_js($);
	product_cost_settings_js($);
	product_people_settings_js($);
	product_services_settings_js($)
	dashicons_ajax_change($);
	global_availability_rules($);
	global_cost_rules($);
	create_booking_user_select2($);
	create_booking_product_select2($);
	create_booking_order_select2($);
	create_booking_product_details($);
});

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

function product_general_settings_js($) {

	var allow_cancellation_check = $( '#mwb_booking_general_data #mwb_allow_booking_cancellation' ).is(':checked');
	// alert( allow_cancellation_check );
	if ( allow_cancellation_check ) {
		$ ( '#mwb_booking_general_data #mwb_booking_cancellation_days' ).show();
		$ ( '#mwb_booking_general_data #mwb_max_days_for_cancellation' ).prop( 'disabled', false ).attr( 'required', true );
	} else {
		$ ( '#mwb_booking_general_data #mwb_booking_cancellation_days' ).hide();
		$ ( '#mwb_booking_general_data #mwb_max_days_for_cancellation' ).prop( 'disabled', true );
	}

	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_allow_booking_cancellation', function(){
		var allow_cancellation_check = $(this).is(':checked');
		// alert( allow_cancellation_check );
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
			$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).show();
			$('#mwb_booking_general_data #mwb_start_booking_time').show();
			$( '#mwb_full_day_select' ).hide();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
			break;
		case 'month':
			$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).hide();
			$('#mwb_booking_general_data #mwb_start_booking_time').hide();
			$( '#mwb_full_day_select' ).hide();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
			break;
		case 'day':
			$( '#mwb_full_day_select' ).show();
			$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).hide();
			$('#mwb_booking_general_data #mwb_start_booking_time').hide()
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
			break;
		case 'minute':
			$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).show();
			$('#mwb_booking_general_data #mwb_start_booking_time').show();
			$( '#mwb_full_day_select' ).hide();
			$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', 60 ).attr( 'min', 0 ).attr( 'step', 15 );				
			break;
	}

	var duration_input = $( '#mwb_booking_general_data #mwb_booking_unit_input' ).val();

	$( '#mwb_booking_general_data #mwb_booking_unit input' ).bind( 'keyup mouseup', function(){
		var duration = $( '#mwb_booking_general_data #mwb_booking_unit_duration' ).val();
		if( $(this).val() == 1 && duration == 'day' ) {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
			$('#mwb_booking_general_data #mwb_calendar_range').show();
		} else {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
			$('#mwb_booking_general_data #mwb_calendar_range').hide();
		}
	});

	var start_booking_from = $( '#mwb_booking_general_data #mwb_start_booking_date' ).val();
	if( start_booking_from == 'custom_date' ) {
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', false);
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).show();
	} else {
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', true);
		$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).hide();
	}

	var unit_select = $( '#mwb_booking_general_data #mwb_booking_unit_select' ).val();
	if ( duration == 'day' && unit_select == 'customer' && duration_input == 1 ) {
		$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
		$('#mwb_booking_general_data #mwb_calendar_range').show();
	} else {
		$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
		$('#mwb_booking_general_data #mwb_calendar_range').hide();
	}
	
	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_booking_unit_select', function(){
		var duration = $( '#mwb_booking_general_data #mwb_booking_unit_duration' ).val();
		var duration_input = $( '#mwb_booking_general_data #mwb_booking_unit_input' ).val();
		if ( duration == 'day' && $(this).val() == 'customer' && duration_input == 1 ) {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
			$('#mwb_booking_general_data #mwb_calendar_range').show();
			alert(duration + " " + unit_select);
		} else {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
			$('#mwb_booking_general_data #mwb_calendar_range').hide();
			alert(duration + " " + unit_select);
		}
	});

	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_booking_unit_duration', function(){
		var duration = $(this).val();
		var unit_select = $( '#mwb_booking_general_data #mwb_booking_unit_select' ).val()
		if ( duration == 'day' && unit_select == 'customer' && duration_input == 1 ) {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', false );
			$('#mwb_booking_general_data #mwb_calendar_range').show();
			alert(duration + " " + unit_select);
		} else {
			$( '#mwb_booking_general_data #mwb_enable_range_picker' ).prop( 'disabled', true );
			$('#mwb_booking_general_data #mwb_calendar_range').hide();
			alert(duration + " " + unit_select);
		}
		
		switch( duration ) {
			case 'hour':
				$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).show();
				$('#mwb_booking_general_data #mwb_start_booking_time').show();
				$( '#mwb_full_day_select' ).hide();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
				break;
			case 'month':
				$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).hide();
				$('#mwb_booking_general_data #mwb_start_booking_time').hide();
				$( '#mwb_full_day_select' ).hide();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
				break;
			case 'day':
				$( '#mwb_full_day_select' ).show();
				$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).hide();
				$('#mwb_booking_general_data #mwb_start_booking_time').hide()
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', '' ).attr( 'min', 1 ).attr( 'step', 1 );	
				break;
			case 'minute':
				$( '#mwb_start_booking_from p label[for=mwb_start_booking_time]' ).show();
				$('#mwb_booking_general_data #mwb_start_booking_time').show();
				$( '#mwb_full_day_select' ).hide();
				$('#mwb_booking_general_data #mwb_booking_unit_input').attr( 'max', 60 ).attr( 'min', 0 ).attr( 'step', 15 );				
				break;
		}
	});
	$( '#mwb_booking_general_data' ).on( 'change', '#mwb_start_booking_date', function(){
		if( $(this).val() == 'custom_date' ) {
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', false);
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).show();
		} else {
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date' ).prop('disabled', true);
			$( '#mwb_booking_general_data #mwb_start_booking_custom_date_field' ).hide();
		}
	});
}

function product_cost_settings_js($) {

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
					// alert('week');
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'monthly_discount':
					// alert('month');
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'custom_discount':
					// alert('custom');
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
					// alert('week');
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'monthly_discount':
					// alert('month');
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_input' ).prop( 'disabled', false );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_days_discount_input' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_days' ).prop( 'disabled', true );
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_weekly_discount_field' ).hide();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_monthly_discount_field' ).show();
					$(this).closest('#mwb_booking_cost_data').find( '#mwb_booking_custom_discount_field' ).hide();
					break;
				case 'custom_discount':
					// alert('custom');
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

	$( '#mwb_global_availability_form .mwb_global_availability_rule_weekdays' ).each(function() {
		if ( $(this).is( ':checked' ) ) {
			//alert( 'checked' );
			$(this).closest( '.mwb_global_availability_rule_fields' ).find('.bookable').hide();
			$(this).closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').show();
		} else {
			//alert( 'un-checked' );
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
	
	
	jQuery('#mwb_global_availability_form').on('click', '#mwb_add_avialability_rule', function(e){
		if ( availability_count < parseInt(jQuery(this).attr('rule_count')) ) {
			availability_count = parseInt(jQuery(this).attr('rule_count'));
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
						alert("hi");
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
		alert(weekdays_rule_check);
		if( weekdays_rule_check ) {
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.bookable').hide();
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').show();
			
		} else {
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.bookable').show();
			check_obj.closest( '.mwb_global_availability_rule_fields' ).find('.mwb_global_availability_rule_weekdays_book').hide();
		}	
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

function global_cost_conditions(obj, $) {
	var condition = obj.val();
	alert(condition);
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
		
		alert( "count: " + cost_count );

		// 	var pattern = /[0-9]$/;
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
		
		alert('pressed');
		var del_count = parseInt($(this).attr('rule_count'));
		alert(del_count);

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

function create_booking_product_details($) {
	// var form = $('#mwb_create_booking_form');
	// var selected_option = form.find( '#mwb_create_booking_product_select' );

	$('#mwb_create_booking_form').on( 'change', '#mwb_create_booking_product_select', function() {
		//alert( $(this).val() );
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
				console.log( data );
				$( '#mwb_create_booking_form' ).find( '.product_ajax' ).html( data );
			}
		});
	});
}
