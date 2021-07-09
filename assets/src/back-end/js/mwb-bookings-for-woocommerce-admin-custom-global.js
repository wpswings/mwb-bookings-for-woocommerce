jQuery(document).ready(function($) {
    $(document).on('change', '#mwb_mbfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            $('#mwb_mbfw_booking_count').removeAttr('disabled');
        } else {
            $('#mwb_mbfw_booking_count').attr('disabled', 'disabled');
        }
    });
    $(document).on('change', '#mwb_mbfw_booking_unit', function(){
        if ( $(this).val() == 'days' ) {
            $('#mwb_mbfw_enable_calendar').removeAttr('disabled');
            $('#mwb_mbfw_enable_time_picker').attr('disabled', 'disabled');
        } else {
            $('#mwb_mbfw_enable_calendar').attr('disabled', 'disabled');
            $('#mwb_mbfw_enable_time_picker').removeAttr('disabled');
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
});