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
    $('#mwb_mbfw_daily_calendar_end_time').datetimepicker({
        format     : 'H:i',
        datepicker : false,
		
    });
    $('#mwb_mbfw_daily_calendar_start_time').datetimepicker({
        format     : 'H:i',
        datepicker : false,
		
    });
});


jQuery(document).ready(function($){
    if( 'hour' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val() ) {
        $('.woocommerce_options_panel #mwb_mbfw_booking_unit').parent().parent().find('.mwb_mbfw_show_date_with_time_field').hide();
    }
    $(document).on('change', '.woocommerce_options_panel #mwb_mbfw_booking_unit', function() {
        if( 'hour' == $(this).val() ) {
            $(this).parent().parent().find('.mwb_mbfw_show_date_with_time_field').hide();
        }
        if( 'day' == $(this).val() ) {
            $(this).parent().parent().find('.mwb_mbfw_show_date_with_time_field').show();
        }
    });
    // $(document).on()
    $('#publish').on('click', function(e){
        var start = $('#mwb_mbfw_daily_calendar_start_time').val();
        var end = $('#mwb_mbfw_daily_calendar_end_time').val();
        if( start != '' && end != '') {

            start = parseInt( start.substr(0,2) );
            end = parseInt( end.substr(0,2) );
           
            
            if( start >= end ){
                alert('Start time should be less than end time');
                e.preventDefault();
            }
        }
    });
});