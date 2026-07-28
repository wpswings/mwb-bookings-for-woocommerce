jQuery(function ($) {
    function toggleServiceQuantityFields($toggle) {
        var hasQuantity = $toggle.prop('checked');
        var $minimumQuantity = $('#mwb_mbfw_service_minimum_quantity');
        var $maximumQuantity = $('#mwb_mbfw_service_maximum_quantity');
        var $quantityFields = $minimumQuantity
            .closest('tr, .mwb-tax-field, .mwb-edit-field')
            .add($maximumQuantity.closest('tr, .mwb-tax-field, .mwb-edit-field'));

        $minimumQuantity.prop('disabled', !hasQuantity);
        $maximumQuantity.prop('disabled', !hasQuantity);
        $quantityFields.toggle(hasQuantity);
    }

    function validateServiceQuantityFields($changedField) {
        var minimumValue = $('#mwb_mbfw_service_minimum_quantity').val();
        var maximumValue = $('#mwb_mbfw_service_maximum_quantity').val();
        var minimumQuantity = minimumValue === '' ? '' : parseFloat(minimumValue);
        var maximumQuantity = maximumValue === '' ? '' : parseFloat(maximumValue);

        if ( minimumQuantity !== '' && minimumQuantity < 0 ) {
            alert(mbfw_product_ajax.service_quantity_negative);
            $('#mwb_mbfw_service_minimum_quantity').val('');
            return;
        }

        if ( maximumQuantity !== '' && maximumQuantity < 0 ) {
            alert(mbfw_product_ajax.service_quantity_negative);
            $('#mwb_mbfw_service_maximum_quantity').val('');
            return;
        }

        if ( minimumQuantity !== '' && maximumQuantity !== '' && minimumQuantity > maximumQuantity ) {
            alert(mbfw_product_ajax.service_quantity_invalid_range);
            $changedField.val('');
        }
    }

    if ( $('body').hasClass('post-type-wps_global_booking') ) {
        $('<style>', {
            text: '#edit-slug-box,#view-post-btn,.post-preview{display:none !important;}'
        }).appendTo('head');
    }

    
    
    jQuery('.inventory_tab').on('click', function (e) {
        //jQuery('.stock_fields').show();
        jQuery('._manage_stock_field').show();
        
    });

    jQuery('p.submit #submit').on('click', function (e) {
        var value = jQuery('#mwb_mbfw_booking_cost').val();
        if (value < 0) {
            
            alert(mbfw_product_ajax.alert_booking);
            e.preventDefault();
        }
    });

    $(document).on('change', '#mwb_mbfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            jQuery('#mwb_mbfw_booking_count').show();
        } else {
            jQuery('#mwb_mbfw_booking_count').hide();
        }
    });

   


    $(document).on('change', '#mwb_mbfw_is_service_has_quantity, #mwb-edit-qty', function(){
        toggleServiceQuantityFields($(this));
    });
    $(document).on('change blur', '#mwb_mbfw_service_minimum_quantity, #mwb_mbfw_service_maximum_quantity', function(){
        validateServiceQuantityFields($(this));
    });
    $('#mwb_mbfw_is_service_has_quantity, #mwb-edit-qty').each(function() {
        toggleServiceQuantityFields($(this));
    });
    if ( $('#mwb_bfwp_order_statuses_to_cancel').length > 0 ) {
        $('#mwb_bfwp_order_statuses_to_cancel').select2({
            width: '100%'
        });
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


    $("#mwb_mbfw_daily_calendar_start_time").blur(function(){
       
        var start = $('#mwb_mbfw_daily_calendar_start_time').val();
      
        let m = start.match(/^(\d+)[ :,](\d+)$/);
        if (m) {
             s_hour=parseInt(m[1]);
            s_minute =parseInt(m[2]);
        }
        var end = $('#mwb_mbfw_daily_calendar_end_time').val();
        let m_e = end.match(/^(\d+)[ :,](\d+)$/);
        if (m_e) {
             e_hour=parseInt(m_e[1]);
            e_minute =parseInt(m_e[2]);
        }
        if (start != '' && end != '') {

           
            if ( s_hour >= e_hour) {
                
                alert(mbfw_product_ajax.start_date_validate_booking);
                $('#mwb_mbfw_daily_calendar_start_time').val('');
                e.preventDefault();  
            }
        }
    });

    $("#mwb_mbfw_daily_calendar_end_time").blur(function(){
      
        var start = $('#mwb_mbfw_daily_calendar_start_time').val();
      
        let m = start.match(/^(\d+)[ :,](\d+)$/);
        if (m) {
             s_hour=parseInt(m[1]);
            s_minute =parseInt(m[2]);
        }
        var end = $('#mwb_mbfw_daily_calendar_end_time').val();
        let m_e = end.match(/^(\d+)[ :,](\d+)$/);
        if (m_e) {
             e_hour=parseInt(m_e[1]);
            e_minute =parseInt(m_e[2]);
        }
        if (start != '' && end != '') {

           
            if ( s_hour >= e_hour) {
                
                alert(mbfw_product_ajax.end_date_validate_booking);
                $('#mwb_mbfw_daily_calendar_end_time').val('');
                e.preventDefault();  
            }
        }
    });
     

    var booking_criteria = jQuery('#mwb_mbfw_booking_criteria').val();

    if ( booking_criteria == 'fixed_unit' ) {
        jQuery('#mwb_mbfw_booking_count').show();
    } else {
        jQuery('#mwb_mbfw_booking_count').hide();
    }

    if( 'hour' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val() ) {
        $('.woocommerce_options_panel #mwb_mbfw_booking_unit').parent().parent().find('.mwb_mbfw_show_date_with_time_field').hide();
    }
    $(document).on('change', '.woocommerce_options_panel #mwb_mbfw_booking_unit', function () {
        if ($('#wps_mbfw_booking_type').val() == 'single_cal') { 
            $(this).parent().parent().find('.mwb_mbfw_show_date_with_time_field').hide();
            if( 'hour' == $(this).val() ) {
                $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').show();
            }
            if( 'day' == $(this).val() ) {
                $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').hide();
            }
        } else {
            $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').hide();

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
        $(document).find('.wps_day_based_pricing_wrapper').hide();
        $(document).find('.mwb_mbfw_daily_calendar_end_time_field').hide();
        $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
        $(document).find('.wps_bfwp_weekly_off_day_field').hide();
        $(document).find('.mwb_mbfw_rescheduling_allowed_field').hide();
        $(document).find('.mwb_mbfw_choose_holiday_field').hide();
        $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').hide();
        $(document).find('.wps_mbfw_set_availability_field').show();
        $(document).find('#wps_mbfw_add_fields_wrapper').show();
        $(document).find('.mbfw_notice').show();
        $(document).find('.wps_mbfw_night_slots_enabled_field').show();
        $(document).find('.mwb_mbfw_booking_time_fromat_field').show();
        $(document).find('.wps_mbfw_day_and_days_upto_togather_enabled_field').show();
        $(document).find('.Slots_tab').show();
        $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').show();
                

        if ('hour' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 
            $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').show();
            $(document).find('.mwb_brfwp_enable_for_single_date_booking_only_field').hide();
        } else{
            $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').hide();
            $(document).find('.mwb_brfwp_enable_for_single_date_booking_only_field').show();
        }
    } else {
        if ('day' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 

            $(document).find('.mwb_mbfw_show_date_with_time_field').show();
        }
        if ('hour' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 
            $(document).find('.mwb_mbfw_booking_time_fromat_field').show();

        }
        $(document).find('.mwb_mbfw_daily_calendar_start_time_field').show();
        $(document).find('.mwb_mbfw_daily_calendar_end_time_field').show();
        $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
        $(document).find('.wps_bfwp_weekly_off_day_field').show();
        $(document).find('.mwb_mbfw_choose_holiday_field').show();
        $(document).find('.wps_mbfw_set_availability_field').hide();
        $(document).find('.wps_mbfw_set_availability_upto_field').hide();
        
        $(document).find('.mbfw_notice').hide();
        $(document).find('#wps_mbfw_add_fields_wrapper').hide();
        $(document).find('.mwb_mbfw_rescheduling_allowed_field').show();
        $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').show();
        $(document).find('.wps_mbfw_night_slots_enabled_field').hide();
        $(document).find('.wps_mbfw_day_and_days_upto_togather_enabled_field').hide();
        $(document).find('.Slots_tab').hide();
        $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').hide();
        $(document).find('.wps_day_based_pricing_wrapper').show();
        $(document).find('.mwb_brfwp_enable_for_single_date_booking_only_field').hide();
       
    }

    $(document).on('change', '#wps_mbfw_booking_type', function () {
        if ($(this).val() == 'single_cal') {
            $(document).find('.wps_day_based_pricing_wrapper').hide();

            $(document).find('.mwb_mbfw_show_date_with_time_field').hide();
            $(document).find('.mwb_mbfw_daily_calendar_start_time_field').hide();
            $(document).find('.mwb_mbfw_daily_calendar_end_time_field').hide();
            $(document).find('.mwb_bfwp_choose_multiple_holiday_field').show();
            $(document).find('.wps_bfwp_weekly_off_day_field').hide();
            $(document).find('.mwb_mbfw_rescheduling_allowed_field').hide();
            $(document).find('.mwb_mbfw_choose_holiday_field').hide();
            $(document).find('.mwb_bfwp_order_statuses_to_reschedule_field').hide();
            $(document).find('.wps_mbfw_set_availability_upto_field').show();
            $(document).find('.wps_mbfw_set_availability_field').show();
            $(document).find('#wps_mbfw_add_fields_wrapper').show();
            $(document).find('.mbfw_notice').show();
            $(document).find('.wps_mbfw_night_slots_enabled_field').show();
            $(document).find('.mwb_mbfw_booking_time_fromat_field').show();
            $(document).find('.wps_mbfw_day_and_days_upto_togather_enabled_field').show();
            $(document).find('.Slots_tab').show();
            // $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').show();
            

            if ('hour' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 
                $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').show();
                $(document).find('.mwb_brfwp_enable_for_single_date_booking_only_field').hide();
    
            } else {
                $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').show();
                $(document).find('.mwb_brfwp_enable_for_single_date_booking_only_field').show();
            }
        } else {
            if ('day' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 

                $(document).find('.mwb_mbfw_show_date_with_time_field').show();
            }
            if ('hour' == $('.woocommerce_options_panel #mwb_mbfw_booking_unit').val()) { 
                $(document).find('.mwb_mbfw_booking_time_fromat_field').show();
    
            }
            $(document).find('.wps_day_based_pricing_wrapper').show();
             $(document).find('.mwb_brfwp_enable_for_single_date_booking_only_field').hide();

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
            $(document).find('.wps_mbfw_set_availability_upto_field').hide();
            $(document).find('.wps_mbfw_night_slots_enabled_field').hide();
            $(document).find('.mwb_mbfw_booking_time_fromat_field').hide();
            $(document).find('.wps_mbfw_day_and_days_upto_togather_enabled_field').hide();
            $(document).find('.Slots_tab').hide();
            $(document).find('.mwb_mbfw_booking_hide_or_diable_slot_field').hide();


        }
    });
    


    $('#wps_mbfw_set_availability').multiDatesPicker({
        dateFormat: "dd-mm-yy",
        minDate: new Date(),
    });
    $('#wps_mbfw_set_availability_upto').datepicker({
        dateFormat: "dd-mm-yy",
        minDate: new Date(),
        
    });
    $(document).on('click', '#wps_mbfw_set_availability_upto', function(){
      
        const dateInput = document.getElementById("wps_mbfw_set_availability_upto");
        const clearButton = document.getElementById("clearDateButton");
        if ( jQuery('#availability_clear').val() == undefined){
            jQuery('.wps_mbfw_set_availability_upto_field ').append('<input type="button" value="Clear" id="availability_clear"> ');
        
        }
    });
    $(document).on('click', '#availability_clear', function(){
      
        jQuery('#wps_mbfw_set_availability_upto').val('');
     });

    $.datepicker._selectDateOverload = $.datepicker._selectDate;
    $.datepicker._selectDate = function (id, dateStr) {
        var target = $(id);
        var inst = this._getInst(target[0]);
        inst.inline = true;
        $.datepicker._selectDateOverload(id, dateStr);
        inst.inline = false;
        if (target[0].multiDatesPicker != null) {
            target[0].multiDatesPicker.changed = false;
        } else {
            target.multiDatesPicker.changed = false;
        }
             this._updateDatepicker(inst);
    };
    


    $(document).on( 'click', '.wps_mbfw_add_fields_button', function(){
        var fieldsetId = $(document).find('.wps_mbfw_field_table').find('.wps_mbfw_field_wrap').last().attr('data-id');
        fieldsetId = fieldsetId?fieldsetId.replace(/[^0-9]/gi, ''):0;
        let mainId = Number(fieldsetId) + 1;
        var field_html = '<tr class="wps_mbfw_field_wrap" data-id="'+mainId+'"><td class="drag-icon"><i class="dashicons dashicons-move"></i></td><td class="form-field wps_mbfw_from_fields"><input type="text" class="wps_mbfw_field_from" style="" name="mbfw_fields['+mainId+'][_from]" id="from_fields_'+mainId+'" value="" placeholder=""></td><td class="form-field wps_mbfw_to_fields"><input type="text" class="wps_mbfw_field_to" style="" name="mbfw_fields['+mainId+'][_to]" id="to_fields_'+mainId+'"></td><td class="wps_mbfw_remove_row"><input type="button" name="wps_mbfw_remove_fields_button" class="wps_mbfw_remove_row_btn" value="Remove"></td></tr>';
        $(document).find('.wps_mbfw_field_body').append(field_html);
        $('.wps_mbfw_field_from').datetimepicker({
            format     : 'H:i',
            step       :  30,
            datepicker : false,
            
        });
        $('.wps_mbfw_field_to').datetimepicker({
            format     : 'H:i',
            step       :  30,
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
        step       :  30,
        datepicker : false,
		
    });
    $('.wps_mbfw_field_to').datetimepicker({
        format     : 'H:i',
        step       :  30,
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


        start__ = start.replace(':','.');
        end__ = end.replace(':','.');
            // start__ = parseInt( start.substr(0,2) );
            // end__ = parseInt( end.substr(0,2) );
            
            if( start != '' && end != '') {
                if ( start >= end ) {
                    
                    alert('Start time should be less than end time');
                    $('#mwb_mbfw_daily_start_time').val('');
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

            if($('#wps_mbfw_night_slots_enabled').is(":checked")){
                return;
            }

            var start = $(this).val();
            var end   = $('#to_fields_' + $(this).attr('id').split('_').pop()).val();
            start = start.replace(':','.');
            end = end.replace(':','.');
            if( start != '' && end != '') {
          
                if ( start >= end ) {   
                    alert('Start time should be less than end time');
                    $(this).val('');
                   
                    
                }
            }
        });
    

        $('.wps_mbfw_field_to').change(function () {
            if($('#wps_mbfw_night_slots_enabled').is(":checked")){
                return;
            }

            var start = $('#from_fields_' + $(this).attr('id').split('_').pop()).val();
            var end = $(this).val();
            start = start.replace(':','.');
            end = end.replace(':','.');
            if( start != '' && end != '') {

                if ( start >= end ) {
                    
                    alert('Start time should be less than end time');
                    $(this).val('');
                    
                }
            }
        });
    
}

    // Airbnb iCal Sync Now button.
    $(document).on('click', '#wps_sync_airbnb_now', function () {
        var $btn    = $(this);
        var $status = $('#wps_airbnb_sync_status');
        var url     = $('#wps_airbnb_ical_url').val();
        var pid     = $btn.data('product-id');
        var nonce   = $('#_mwb_nonce').val();

        if (!url) {
            $status.text(mbfw_product_ajax.airbnb_url_required || 'Please enter an Airbnb iCal URL first.').css('color', 'red');
            return;
        }

        $btn.prop('disabled', true);
        $status.text(mbfw_product_ajax.airbnb_syncing || 'Syncing…').css('color', '#888');

        $.post(ajaxurl, {
            action:      'wps_sync_product_airbnb_calendar',
            product_id:  pid,
            ical_url:    url,
            _mwb_nonce:  nonce
        }, function (res) {
            if (res.success) {
                $status.text(res.data.message + ' (' + res.data.dates.length + ' dates)').css('color', 'green');
            } else {
                $status.text(res.data.message || 'Sync failed.').css('color', 'red');
            }
        }).fail(function () {
            $status.text('Request failed.').css('color', 'red');
        }).always(function () {
            $btn.prop('disabled', false);
        });
    });


});
