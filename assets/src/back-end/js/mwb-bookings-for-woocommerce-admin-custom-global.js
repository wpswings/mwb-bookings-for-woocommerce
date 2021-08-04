jQuery(function($) {
    $(document).on('change', '#mwb_mbfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            $('#mwb_mbfw_booking_count').removeAttr('disabled');
            $('#mwb_mbfw_maximum_booking_per_unit').attr('disabled', 'disabled');
        } else {
            $('#mwb_mbfw_booking_count').attr('disabled', 'disabled');
            $('#mwb_mbfw_maximum_booking_per_unit').removeAttr('disabled');
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
    if ( $('#mwb_bfwp_order_statuses_to_cancel').length > 0 ) {
        $('#mwb_bfwp_order_statuses_to_cancel').select2();
    }
    $(document).on('change', '#mwb_mbfw_cancellation_allowed', function(){
        if ( $(this).prop('checked') ) {
            $('#mwb_bfwp_order_statuses_to_cancel').removeAttr('disabled');
        } else {
            $('#mwb_bfwp_order_statuses_to_cancel').attr('disabled', 'disabled');
        }
    });
});