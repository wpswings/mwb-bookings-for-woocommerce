jQuery(document).ready(function($){
    jQuery('.mwb-mbfw-user-booking-meta-data-listing').slideUp();
    jQuery('.mwb-mbfw-ser-booking-toggler').on('click',function(){
        jQuery(this).toggleClass('booking-toggler-reverse');
        jQuery(this).siblings('.mwb-mbfw-user-booking-meta-data-listing').slideToggle('slow');
    })

    if( mwb_mbfw_public_obj.daily_start_time != '' && mwb_mbfw_public_obj.daily_end_time != '' ) {
        
        $('.mwb_mbfw_time_date_picker_frontend').datetimepicker({
            format  : 'd-m-Y H:00',
            minTime: mwb_mbfw_public_obj.daily_start_time,
            maxTime : mwb_mbfw_public_obj.daily_end_time + 1,
            // minTime : mwb_mbfw_common_obj.minTime
        });
    }

    var booking_product = mwb_mbfw_public_obj.booking_product;
    if (booking_product == 'yes') {
        
        jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
        jQuery(document).on('change', '.mwb_mbfw_time_date_picker_frontend', function () {
            if (jQuery('#mwb-mbfw-booking-from-time').val() == '' || jQuery('#mwb-mbfw-booking-to-time').val() == '') {
                
                jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
            } else {
                jQuery('.cart .single_add_to_cart_button').prop('disabled', false);
            }
        });
        $(document).on('focusout blur keydown paste focus mousedown mouseover mouseout', '.mwb-mbfw-cart-page-data', function () {
          
            if (jQuery('#wps_booking_single_calendar_form').val() == '') {
                
                jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
            } else {
                jQuery('.cart .single_add_to_cart_button').prop('disabled', false);
            }
        });
    }

    var upcoming_holiday = mwb_mbfw_public_obj.upcoming_holiday;
    var is_pro_active = mwb_mbfw_public_obj.is_pro_active
    var available_dates = mwb_mbfw_public_obj.single_available_dates;
    if( is_pro_active != 'yes' ) {

        if( upcoming_holiday.length > 0 ){
            
                
            function disableSpecificDate(date) {
                
                // To disable specific day
                var dateArr = [String(date.getFullYear()), String(date.getMonth() + 1), String(date.getDate())];
                if (dateArr[1].length == 1) dateArr[1] = "0" + dateArr[1];
                if (dateArr[2].length == 1) dateArr[2] = "0" + dateArr[2];
                return upcoming_holiday.indexOf(dateArr.join("-")) == -1;
            }
            jQuery("#mwb-mbfw-booking-from-time").datetimepicker({
                beforeShowDay: function (date) {
                    return [disableSpecificDate(date)];
                }
            });
            jQuery("#mwb-mbfw-booking-to-time").datetimepicker({
                beforeShowDay: function (date) {
                    return [disableSpecificDate(date)];
                }
            });
            
    
        }
    }


    var wps_available_slots = mwb_mbfw_public_obj.wps_available_slots;
    var booking_unit = mwb_mbfw_public_obj.booking_unit;
    var booking_unavailable = mwb_mbfw_public_obj.booking_unavailable;
    if (booking_unit === 'hour') {
        $('#wps_booking_single_calendar_form').datetimepicker({
			format     : 'd-m-Y',
			timepicker : false,
            minDate: new Date(),
            beforeShowDay: function (date) {
                var formattedDate = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [available_dates.indexOf(formattedDate) > -1];
            }
			
		});
        
        if (wps_available_slots != '') {
            
            
            jQuery("#wps_booking_single_calendar_form").datetimepicker({
                
                onSelectDate: function (ct,$i) {
                    var selected_date = moment(ct).format('D-M-Y');
                    var date_array = selected_date.split("-");
                    
                    var date = date_array[0];
                    var month = date_array[1];
                    var year = date_array[2];
                    
                    if (month.length === 1) {
                        month = '0' + month;
                    }
                    var temp_date = date + '-' + month + '-' + year + ' ';
                    var html = '<div class="wps_cal_timeslot">\n\ ';
                  
                    for(let i=0; i< wps_available_slots.length; i++ ) { 
                        var temp =  wps_available_slots[i]._from + ' - ' + wps_available_slots[i]._to;
                        var temp_check = temp_date + temp;
                        if (booking_unavailable.length > 0) {
                            
                            if (!booking_unavailable.includes(temp_check)) {
                                html += '\n\ <span><button>' + temp + '</button>\n\ </span>';
                                    
                                
                            }
                        } else {
                            html += '\n\ <span><button>' + temp + '</button>\n\ </span>';
                        }
                    }
                    html += '\n\  </div>'
                    jQuery('.wps_cal_timeslot').remove();
                        jQuery(".xdsoft_calendar")
                            .after(html);
        
                     
                    
                    jQuery('.wps_cal_timeslot button').on('click touchstart', function (e) {
                        e.preventDefault();
                    
                        jQuery(this).trigger('close.xdsoft');
                        jQuery("#wps_booking_single_calendar_form").val(temp_date + jQuery(this).html()); 
                        
                        
                    });
               
                },
            });
            
        }
    } else {
        
        $('#wps_booking_single_calendar_form').multiDatesPicker({
            dateFormat: "yy-mm-dd",
            minDate: new Date(),
            
        });
        
        $('#wps_booking_single_calendar_form').multiDatesPicker({
            dateFormat: "yy-mm-dd",
            minDate: new Date(),
            addDisabledDates: mwb_mbfw_public_obj.single_unavailable_dates,
            beforeShowDay: function (date) {
                var formattedDate = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [available_dates.indexOf(formattedDate) > -1];
            }
        });
    }
    
});