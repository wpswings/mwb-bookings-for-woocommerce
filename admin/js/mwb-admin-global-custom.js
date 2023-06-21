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

    if ($('#wps_mbfw_booking_type').val() == 'single_cal') {
        $(document).find('.mwb_mbfw_show_date_with_time_field').hide();
        $(document).find('.mwb_mbfw_daily_calendar_start_time_field').hide();
        $(document).find('.mwb_mbfw_daily_calendar_end_time_field').hide();
        $(document).find('.mwb_bfwp_choose_multiple_holiday_field').hide();
        $(document).find('.wps_bfwp_weekly_off_day_field').hide();
        $(document).find('.mwb_mbfw_rescheduling_allowed_field').hide();
        $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').hide();
        $(document).find('.wps_mbfw_set_availability_field').show();
        $(document).find('#wps_mbfw_add_fields_wrapper').show();
    } else {
        $(document).find('.mwb_mbfw_show_date_with_time_field').show();
        $(document).find('.mwb_mbfw_daily_calendar_start_time_field').show();
        $(document).find('.mwb_mbfw_daily_calendar_end_time_field').show();
        $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
        $(document).find('.wps_bfwp_weekly_off_day_field').show();
        $(document).find('.wps_mbfw_set_availability_field').hide();
        $(document).find('#wps_mbfw_add_fields_wrapper').hide();
        $(document).find('.mwb_mbfw_rescheduling_allowed_field').show();
        $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').show();
       
    }

    $(document).on('change', '#wps_mbfw_booking_type', function () {
        if ($(this).val() == 'single_cal') {
            $(document).find('.mwb_mbfw_show_date_with_time_field').hide();
            $(document).find('.mwb_mbfw_daily_calendar_start_time_field').hide();
            $(document).find('.mwb_mbfw_daily_calendar_end_time_field').hide();
            $(document).find('.mwb_bfwp_choose_multiple_holiday_field').hide();
            $(document).find('.wps_bfwp_weekly_off_day_field').hide();
            $(document).find('.mwb_mbfw_rescheduling_allowed_field').hide();
            $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').hide();
            $(document).find('.wps_mbfw_set_availability_field').show();
            $(document).find('#wps_mbfw_add_fields_wrapper').show();
        } else {
            $(document).find('.mwb_mbfw_show_date_with_time_field').show();
            $(document).find('.mwb_mbfw_daily_calendar_start_time_field').show();
            $(document).find('.mwb_mbfw_daily_calendar_end_time_field').show();
            $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
            $(document).find('.wps_bfwp_weekly_off_day_field').show();
            $(document).find('.wps_mbfw_set_availability_field').hide();
            $(document).find('#wps_mbfw_add_fields_wrapper').hide();
            $(document).find('.mwb_mbfw_rescheduling_allowed_field').show();
            $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').show();
           
        }
    });
    


    $('#wps_mbfw_set_availability').multiDatesPicker({
        dateFormat: "dd-mm-yy",
        minDate: new Date(),
    });



    $(document).on( 'click', '.wps_mbfw_add_fields_button', function(){
        var fieldsetId = $(document).find('.wps_mbfw_field_table').find('.wps_mbfw_field_wrap').last().attr('data-id');
        fieldsetId = fieldsetId?fieldsetId.replace(/[^0-9]/gi, ''):0;
        let mainId = Number(fieldsetId) + 1;
        var field_html = '<tr class="wps_mbfw_field_wrap" data-id="'+mainId+'"><td class="drag-icon"><i class="dashicons dashicons-move"></i></td><td class="form-field wps_mbfw_from_fields"><input type="text" class="wps_mbfw_field_from" style="" name="mbfw_fields['+mainId+'][_from]" id="from_fields_'+mainId+'" value="" placeholder=""></td><td class="form-field wps_mbfw_to_fields"><input type="text" class="wps_mbfw_field_to" style="" name="mbfw_fields['+mainId+'][_to]" id="to_fields_'+mainId+'"></td><td class="wps_mbfw_remove_row"><input type="button" name="wps_mbfw_remove_fields_button" class="wps_mbfw_remove_row_btn" value="Remove"></td></tr>';
        $(document).find('.wps_mbfw_field_body').append(field_html);
        $('.wps_mbfw_field_from').datetimepicker({
            format     : 'H:i',
            datepicker : false,
            
        });
        $('.wps_mbfw_field_to').datetimepicker({
            format     : 'H:i',
            datepicker : false,
            
        });
    });

    $(document).on("click", ".wps_mbfw_remove_row_btn", function(e){
        e.preventDefault();
        $(this).parents(".wps_mbfw_field_wrap").remove();
    });
    $('.wps_mbfw_field_from').datetimepicker({
        format     : 'H:i',
        datepicker : false,
		
    });
    $('.wps_mbfw_field_to').datetimepicker({
        format     : 'H:i',
        datepicker : false,
		
    });
});