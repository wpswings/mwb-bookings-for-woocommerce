jQuery(document).ready( function($) {
	initialize_select2();
});

function initialize_select2() {
	if( jQuery('#mwb_booking_not_allowed_days').length > 0 )
		jQuery('#mwb_booking_not_allowed_days').select2();
}