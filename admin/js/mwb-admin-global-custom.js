jQuery(function ($) {
    jQuery('p.submit #submit').on('click', function (e) {
        var value = jQuery('#mwb_mbfw_booking_cost').val();
        if (value < 0) {
            
            alert('Booking cost should not be less than 0  !');
            e.preventDefault();
        }

    });

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
    $(document).on('change', '.woocommerce_options_panel #mwb_mbfw_booking_unit', function () {
        if ($('#wps_mbfw_booking_type').val() == 'single_cal') { 
            $(this).parent().parent().find('.mwb_mbfw_show_date_with_time_field').hide();
        } else {

            if( 'hour' == $(this).val() ) {
                $(this).parent().parent().find('.mwb_mbfw_show_date_with_time_field').hide();
            }
            if( 'day' == $(this).val() ) {
                $(this).parent().parent().find('.mwb_mbfw_show_date_with_time_field').show();
            }
        }
    });
    // $(document).on()
    $('#publish').on('click', function (e) {
        var start = $('#mwb_mbfw_daily_calendar_start_time').val();
        var end = $('#mwb_mbfw_daily_calendar_end_time').val();
        if (start != '' && end != '') {

           
            if (moment(start, 'DD-MM-YYYY HH:mm') >= moment(end, 'DD-MM-YYYY HH:mm')) {
                
                alert('Start time should be less than end time');
                e.preventDefault();
               
                
            }
        }
        let count = 0;
        $('.wps_mbfw_field_from').each(function (i, el) {

            let start_time = $(el).val();
            if ('' == start_time) {
                
                count += 1;
            }
            
        });
        $('.wps_mbfw_field_to').each(function (i, el) {
            let end_time = $(el).val();
            if ('' == end_time) {
               
                count += 1;
            }
        });
        if (count > 0) {
            alert('Time slot should not be empty!, please go to availability setting and set the correct slot in Single Calendar.');
            e.preventDefault();
            return false;
        }
    });

    if ($('#wps_mbfw_booking_type').val() == 'single_cal') {
        $(document).find('.mwb_mbfw_show_date_with_time_field').hide();
        $(document).find('.mwb_mbfw_daily_calendar_start_time_field').hide();
        $(document).find('.mwb_mbfw_daily_calendar_end_time_field').hide();
        $(document).find('.mwb_bfwp_choose_multiple_holiday_field').hide();
        $(document).find('.wps_bfwp_weekly_off_day_field').hide();
        $(document).find('.mwb_mbfw_rescheduling_allowed_field').hide();
        $(document).find('.mwb_mbfw_choose_holiday_field').hide();
        $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').hide();
        $(document).find('.wps_mbfw_set_availability_field').show();
        $(document).find('#wps_mbfw_add_fields_wrapper').show();
        $(document).find('.mbfw_notice').show();
        
    } else {
        if ('day' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 

            $(document).find('.mwb_mbfw_show_date_with_time_field').show();
        }
        $(document).find('.mwb_mbfw_daily_calendar_start_time_field').show();
        $(document).find('.mwb_mbfw_daily_calendar_end_time_field').show();
        $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
        $(document).find('.wps_bfwp_weekly_off_day_field').show();
        $(document).find('.mwb_mbfw_choose_holiday_field').show();
        $(document).find('.wps_mbfw_set_availability_field').hide();
        $(document).find('.mbfw_notice').hide();
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
            $(document).find('.mwb_mbfw_choose_holiday_field').hide();
            $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').hide();
            $(document).find('.wps_mbfw_set_availability_field').show();
            $(document).find('#wps_mbfw_add_fields_wrapper').show();
            $(document).find('.mbfw_notice').show();
        } else {
            if ('day' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 

                $(document).find('.mwb_mbfw_show_date_with_time_field').show();
            }
           
            $(document).find('.mwb_mbfw_daily_calendar_start_time_field').show();
            $(document).find('.mwb_mbfw_daily_calendar_end_time_field').show();
            $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
            $(document).find('.mwb_mbfw_choose_holiday_field').show();
            $(document).find('.wps_bfwp_weekly_off_day_field').show();
            $(document).find('.wps_mbfw_set_availability_field').hide();
            $(document).find('#wps_mbfw_add_fields_wrapper').hide();
            $(document).find('.mwb_mbfw_rescheduling_allowed_field').show();
            $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').show();
            $(document).find('.mbfw_notice').hide();
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
        check_time_slot();
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


    jQuery(document).on('change', '#mwb_mbfw_minimum_people_per_booking', function () {
        var min = jQuery(this).val();
        if (min > 0) {
            jQuery('#mwb_mbfw_maximum_people_per_booking').attr('min', min);
        }
    });
    jQuery(document).on('change', '#mwb_mbfw_maximum_people_per_booking', function () {
        var max = jQuery(this).val();
        if (max > 0) {
            jQuery('#mwb_mbfw_minimum_people_per_booking').attr('max', max);
        }
    });
   
    $('#mwb_mbfw_availability_settings_save').on('click', function (e) {
        
        var start = $('#mwb_mbfw_daily_start_time').val();
        var end = $('#mwb_mbfw_daily_end_time').val();
        if( start != '' && end != '') {

            if ( moment( start, 'DD-MM-YYYY HH:mm' ) >= moment( end, 'DD-MM-YYYY HH:mm' ) ) {
                
                alert('Start time should be less than end time');
                e.preventDefault();
                
            }
        }
        let day_array = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        for (let i = 0; i < day_array.length; i++){
            let morning = jQuery('#mbfw_' + day_array[i] + '_morning').val();
            let lunch_in = jQuery('#mbfw_' + day_array[i] + '_lunch_in').val();
            let lunch_out = jQuery('#mbfw_' + day_array[i] + '_lunch_out').val();
            let night = jQuery('#mbfw_' + day_array[i] + '_night').val();
            let temp = false;
            if (morning !== '') {
                if (lunch_in != '' && morning > lunch_in) {
                    temp = true;
                }
                if (lunch_out !== '' && morning > lunch_out) {
                    temp = true;
                }
                if (night !== '' && morning > night) {
                    temp = true;
                }
                
            }
            if (lunch_in !== '') {
               
                if (lunch_out !== '' && lunch_in > lunch_out) {
                    temp = true;
                }
                if (night !== '' && lunch_in > night) {
                    temp = true;
                }
                
            }
            if (lunch_out !== '') {
               
                if (night !== '' && lunch_out > night) {
                    temp = true;
                }
                
            }
            
           
           
            if ( temp === true ) {
                
                alert(' Timing in Wrong fromat! , it should be ----------------- morning < lunch in < lunch out < night');
                e.preventDefault();
                break; 
            }
    }
    });
    check_time_slot();
    function check_time_slot() {
        $('.wps_mbfw_field_from').change(function () {
            var start = $(this).val();
            var end   = $('#to_fields_' + $(this).attr('id').substr(-1)).val();
            
            if( start != '' && end != '') {
    
                start = parseInt( start.substr(0,2) );
                end = parseInt( end.substr(0,2) );
                   
                
                if ( moment( start, 'DD-MM-YYYY HH:mm' ) >= moment( end, 'DD-MM-YYYY HH:mm' ) ) {
                    
                    alert('Start time should be less than end time');
                    $(this).val('');
                   
                    
                }
            }
        });
    
        // console.log(jQuery('.wps_mbfw_field_to'));
        $('.wps_mbfw_field_to').change(function () {
            var start = $('#from_fields_' + $(this).attr('id').substr(-1)).val();
            var end = $(this).val();
            start = parseInt( start.substr(0,2) );
            end = parseInt( end.substr(0,2) );
            if( start != '' && end != '') {
                
                if ( moment( start, 'DD-MM-YYYY HH:mm' ) >= moment( end, 'DD-MM-YYYY HH:mm' ) ) {
                    
                    alert('Start time should be less than end time');
                    $(this).val('');
                    
                }
            }
        });
    
}

   
});