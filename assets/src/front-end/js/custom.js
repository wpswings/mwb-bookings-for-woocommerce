jQuery(document).ready(function($){
    $('#mwb-mbfw-single-booking-date-selector-from').datepicker({
        dateFormat : 'dd-mm-yy',
		minDate    : mwb_mbfw_public_obj.today_date,
    });
    $('#mwb-mbfw-single-booking-date-selector-to').datepicker({
        dateFormat : 'dd-mm-yy',
		minDate    : mwb_mbfw_public_obj.today_date,
    });
    if ( $('.mbfw_time_picker').length > 0 ) {
        $('.mbfw_time_picker').timepicker();
    }
});