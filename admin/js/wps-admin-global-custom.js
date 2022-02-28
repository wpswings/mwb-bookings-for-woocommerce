jQuery(function($) {
    $(document).on('change', '#wps_bfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            $('#wps_bfw_booking_count').removeAttr('disabled');
            $('#wps_bfw_maximum_booking_per_unit').attr('disabled', 'disabled');
        } else {
            $('#wps_bfw_booking_count').attr('disabled', 'disabled');
            $('#wps_bfw_maximum_booking_per_unit').removeAttr('disabled');
        }
    });
    $(document).on('change', '#wps_bfw_is_service_has_quantity', function(){
        if ( $(this).prop('checked') ) {
            $('#wps_bfw_service_minimum_quantity').removeAttr('disabled');
            $('#wps_bfw_service_maximum_quantity').removeAttr('disabled');
        } else {
            $('#wps_bfw_service_minimum_quantity').attr('disabled', 'disabled');
            $('#wps_bfw_service_maximum_quantity').attr('disabled', 'disabled');
        }
    });
    if ( $('#wps_bfwp_order_statuses_to_cancel').length > 0 ) {
        $('#wps_bfwp_order_statuses_to_cancel').select2();
    }
    $(document).on('change', '#wps_bfw_cancellation_allowed', function(){
        if ( $(this).prop('checked') ) {
            $('#wps_bfwp_order_statuses_to_cancel').removeAttr('disabled');
        } else {
            $('#wps_bfwp_order_statuses_to_cancel').attr('disabled', 'disabled');
        }
    });
});