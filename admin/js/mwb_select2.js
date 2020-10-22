jQuery(document).ready( function($) {

	// Target product search.
	jQuery('#mwb_booking_not_allowed_days').select2({
        placeholder: 'Select weekdays',
  		// ajax:{
    	// 		url: ajaxurl,
    	// 		dataType: 'json',
    	// 		delay: 200,
    	// 		data: function (params) {
      	// 			return {
        // 				'search': params,
        // 				action: 'mwb_booking_search_weekdays'
      	// 			};
    	// 		},
    	// 		processResults: function( data ) {
		// 		var options = [];
		// 		if ( data ) 
		// 		{
		// 			$.each( data, function( index, text )
		// 			{
		// 				text[1]+='( #'+text[0]+')';
		// 				options.push( { id: text[0], text: text[1]  } );
		// 			});
		// 		}
		// 		return {
		// 			results:options
		// 		};
		// 	},
		// 	cache: true
		// },
		// minimumInputLength: 3 // The minimum of symbols to input before perform a search.
    });
});
