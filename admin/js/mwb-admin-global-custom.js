jQuery(function($) {
    $(document).on('change', '#mwb_mbfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            $('#mwb_mbfw_booking_count').removeAttr('disabled');
        } else {
            $('#mwb_mbfw_booking_count').attr('disabled', 'disabled');
        }
    });
    $(document).on('change', '#mwb_mbfw_is_service_has_quantity', function(){
        if ( $(this).prop('checked') ) {
            $('#mwb_mbfw_service_minimum_quantity').removeAttr('disabled');
            $('#mwb_mbfw_service_maximum_quantity').removeAttr('disabled');
        } else {
            $('#mwb_mbfw_service_minimum_quantity').attr('disabled', 'disabled');
            $('#mwb_mbfw_service_maximum_quantity').attr('disabled', 'disabled');
        }
    });
    if ( $('.mbfw_time_picker').length > 0 ) {
        $('.mbfw_time_picker').timepicker();
    }
});