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
    jQuery('#wps_booking_single_calendar_form').on('blur',function(){
        debugger;
        dataaa =  jQuery('#wps_booking_single_calendar_form').val();
        if ( dataaa != '' ) {
            
            if ( mwb_mbfw_public_obj.booking_slot_array_max_limit[dataaa] != undefined ) {
                length_limit = mwb_mbfw_public_obj.booking_slot_array_max_limit[dataaa];
                jQuery('.qty').attr('max',length_limit);
            }
        }
    })


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

          
            if ( jQuery('#wps_booking_single_calendar_form').val() != undefined ) {
                if (jQuery('#wps_booking_single_calendar_form').val() == '') {
                
                    jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
                } else {
                    jQuery('.cart .single_add_to_cart_button').prop('disabled', false);
                }
            }

            if ( jQuery('#wps_booking_single_calendar_form_').val() != undefined ) {
                if (jQuery('#wps_booking_single_calendar_form_').val() == '') {
                
                    jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
                } else {
                    jQuery('.cart .single_add_to_cart_button').prop('disabled', false);
                }
            }
        
            if ( jQuery('#mwb-mbfw-booking-from-time').val() != undefined ) {
                if (jQuery('#mwb-mbfw-booking-from-time').val() == '' || jQuery('#mwb-mbfw-booking-to-time').val() == '') {
                
                    jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
                } else {
                    jQuery('.cart .single_add_to_cart_button').prop('disabled', false);
                }
            }
           
        });
    }

    var upcoming_holiday = mwb_mbfw_public_obj.upcoming_holiday[0];
    var is_pro_active = mwb_mbfw_public_obj.is_pro_active
    var available_dates = mwb_mbfw_public_obj.single_available_dates;
    if( is_pro_active != 'yes' ) {

        if( upcoming_holiday.length > 0 ){

            if ( jQuery('.wps_single_cal_hourly').length > 0 ) {
                

                flatpickr('#mwb-mbfw-booking-from-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                      if (upcoming_holiday.includes(dateString)) {
                        dayElem.classList.add("disabled-date");
                      }
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                }); 
        
                flatpickr('#mwb-mbfw-booking-to-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                      if (upcoming_holiday.includes(dateString)) {
                        dayElem.classList.add("disabled-date");
                      }
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                }); 
            }else if ( mwb_mbfw_public_obj.mwb_mbfw_show_date_with_time == 'yes'){

                flatpickr('#mwb-mbfw-booking-from-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      if (upcoming_holiday.includes(dateString)) {
                        dayElem.classList.add("disabled-date");
                      }
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                      
                    
                    
                    },
                    
                }); 
        
                flatpickr('#mwb-mbfw-booking-to-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      if (upcoming_holiday.includes(dateString)) {
                        dayElem.classList.add("disabled-date");
                      }
                    if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                }); 


            }
             else{
                flatpickr('#mwb-mbfw-booking-from-time', {  
               
                    dateFormat: "d-m-Y",
                  
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                      if (upcoming_holiday.includes(dateString)) {
                        dayElem.classList.add("disabled-date");
                      }
                     
                    if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                      if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                        }else{
                            dayElem.classList.add("wps-unavailable-day");
                                dayElem.classList.add("disabled-date");
                        }
                    },
                }); 
        
                flatpickr('#mwb-mbfw-booking-to-time', {  
                       
                    dateFormat: "d-m-Y",
                  
                   
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                      if (upcoming_holiday.includes(dateString)) {
                        dayElem.classList.add("disabled-date");
                      }
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                }); 
            }
           
    
        } else{
            if ( jQuery('.wps_single_cal_hourly').length > 0 ) {
                

                flatpickr('#mwb-mbfw-booking-from-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                    
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                    
                }); 
        
                flatpickr('#mwb-mbfw-booking-to-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      

                    
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                }); 
            }else if ( mwb_mbfw_public_obj.mwb_mbfw_show_date_with_time == 'yes'){

                flatpickr('#mwb-mbfw-booking-from-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                    
                }); 
        
                flatpickr('#mwb-mbfw-booking-to-time', {  
                    enableTime: true,
                    dateFormat: "d-m-Y H:i",
                    time_24hr: true,
                    minTime: mwb_mbfw_public_obj.daily_start_time, 
                    maxTime: mwb_mbfw_public_obj.daily_end_time, 
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                    
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                }); 


            }
            else{
                flatpickr('#mwb-mbfw-booking-from-time', {  
               
                    dateFormat: "d-m-Y",
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                    
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                   
                }); 
        
                flatpickr('#mwb-mbfw-booking-to-time', {  
                       
                    dateFormat: "d-m-Y",
                  
                    onDayCreate: function(dObj, dStr, fp, dayElem){
                        
                        dObj = dayElem.dateObj;

                      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
                      
                      
                    
                      if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                        
                    }
                      
                      var date1 = new Date( mwb_mbfw_public_obj.today_date_check);
                      var date2 = new Date(dateString);
                      
                    
                    if (date1 <= date2) {
                    
                        dayElem.classList.add("wps-available-day");			
                    }else{
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    }
                    },
                    
                }); 
            }

        }
       
   
    }


    var wps_available_slots = mwb_mbfw_public_obj.wps_available_slots;
    var booking_unit = mwb_mbfw_public_obj.booking_unit;
    var booking_unavailable = mwb_mbfw_public_obj.booking_unavailable;
    if (mwb_mbfw_public_obj.single_unavailable_dates==''){
        mwb_mbfw_public_obj.single_unavailable_dates.push("1970-01-01");
    }
    if (booking_unit === 'hour') {
        $('#wps_booking_single_calendar_form').datetimepicker({
			format     : 'd-m-Y',
			timepicker : false,
            minDate: new Date(),
          
            beforeShowDay: function (date) {
                var formattedDate = jQuery.datepicker.formatDate('yy-mm-dd', date);
               
                

                var datas= formattedDate.split('-');
                month_current = datas[1];
                year_current = datas[0];
                var dateString__ =  datas[2]+ '-' + datas[1] + '-' + datas[0] ;




                var selected_date = moment(date).format('D-M-Y');
                var date_array = selected_date.split("-");
                
                var date5 = date_array[0];
                var month = date_array[1];
                var year = date_array[2];
           
              
                
                if (month.length === 1) {
                    month = '0' + month;
                }
                var temp_date = date5 + '-' + month + '-' + year + ' ';
                
                var int_all_slots = 0;
                for(let i=0; i< wps_available_slots.length; i++ ) { 
                    var temp =  wps_available_slots[i]._from + ' - ' + wps_available_slots[i]._to;
                    var temp_check = temp_date + temp;
                    if (booking_unavailable.length > 0) {
                        
                        if (booking_unavailable.includes(temp_check)) {
                         
                          int_all_slots++;                             
                            
                        }
                    }
                }

                if (int_all_slots ==  wps_available_slots.length ){
                  return [false];
                }


                
                month_current__ = parseInt(month_current);
                month_current__ = month_current__.toString();
                if ( mwb_mbfw_public_obj.is_pro_active != ''){
                    if (bfwp_public_param.global_unaviable_month.includes(month_current__)){
                        if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
                     
                        if ( bfwp_public_param.global_unaviable_day.includes( date.getDay().toString() ) ){
                            return [bfwp_public_param.global_unaviable_day.indexOf(formattedDate) > -1];
                           
                          }
                        }
                        
                    }
                  }
     
                if (mwb_mbfw_public_obj.single_available_dates_till != '' ) {
                    var datas_till= mwb_mbfw_public_obj.single_available_dates_till.split('-');
                    month_till = datas_till[1];
                    year_till = datas_till[2];
               
            
                    if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
                             
                    if ( parseInt( month_till ) >= parseInt( month_current ) ) {
                        if(parseInt( year_till ) >= parseInt( year_current )){
                        if ( parseInt( month_till ) == parseInt( month_current )  ) {
            
                            if ( moment( mwb_mbfw_public_obj.single_available_dates_till, 'DD-MM-YYYY' ) >= moment( dateString__, 'DD-MM-YYYY' ) ) {
                                if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString__)) {
                                   
                                    return [false];
                                } else{
                                    return [true];
                                    
                                }
                            }
                           
            
                        }else{
                            if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString__)) {
                                return [false];
                            } else{
                                return [true];
                            } 
                        }
                      
                      }
                    }
                }
                  }

                 
                


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
   
       



   
    flatpickr('#wps_booking_single_calendar_form_', {  
        mode: "multiple",
    dateFormat: "Y-m-d",
    
    enable: available_dates ,


    onDayCreate: function(dObj, dStr, fp, dayElem) {
        dObj = dayElem.dateObj;
        // Convert the date string to match the format of availableDates and unavailableDates
      var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);

      year_current = (dayElem.dateObj.getFullYear()).toString();
      month_current =dayElem.dateObj.getMonth()+1;
      var dateString__ = ("0" + dObj.getDate()).slice(-2)+ '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + dObj.getFullYear() ;
     


    


      
      if (mwb_mbfw_public_obj.single_available_dates_till != '' ) {
        var datas_till= mwb_mbfw_public_obj.single_available_dates_till.split('-');
        month_till = datas_till[1];
        year_till = datas_till[2];


        if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
                 
        if ( parseInt( month_till ) >= parseInt( month_current ) ) {
            if(parseInt( year_till ) >= parseInt( year_current )){
            if ( parseInt( month_till ) == parseInt( month_current )  ) {

                if ( moment( mwb_mbfw_public_obj.single_available_dates_till, 'DD-MM-YYYY' ) >= moment( dateString__, 'DD-MM-YYYY' ) ) {
                    if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.add("disabled-date");
                    } else{
                        dayElem.classList.add("wps-available-day");
                        dayElem.classList.remove("flatpickr-disabled");
                    }
                }
               

            }else{
                if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                    dayElem.classList.add("wps-unavailable-day");
                    dayElem.classList.add("disabled-date");
                } else{
                    dayElem.classList.add("wps-available-day");
                    dayElem.classList.remove("flatpickr-disabled");
                } 
            }
          
          }
        }
    }
      } else{
        if (available_dates.includes(dateString)) {
            if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                dayElem.classList.add("wps-unavailable-day");
             
                dayElem.classList.add("flatpickr-disabled");
            } else{
                dayElem.classList.add("wps-available-day");
            }
           
          }
      }
      

    
      month =dayElem.dateObj.getMonth()+1;
     
      if ( mwb_mbfw_public_obj.is_pro_active != ''){
        if (bfwp_public_param.global_unaviable_month.includes(month.toString())){
            if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
         
            if ( bfwp_public_param.global_unaviable_day.includes( dObj.getDay().toString() ) ){
                dayElem.classList.add("wps-unavailable-day");
                dayElem.classList.remove("wps-available-day");
                dayElem.classList.add("flatpickr-disabled");
              }
            }
            
        }
      }


      if ( month < 10 ) {
          month = 0+''+month;
      }
      current_date = dayElem.dateObj.getDate();
      if ( current_date < 10 ) {
          current_date = 0+''+current_date;
      }
      date_selected = year_current+'-'+month+'-'+current_date;
      var price = mwb_mbfw_public_obj.single_unavailable_prices[date_selected];
      if (price) {
        var tooltip = document.createElement('div');
        tooltip.className = 'wps_booking_tooltip';
        tooltip.textContent = 'Price: ' + price + '$';
        dayElem.appendChild(tooltip);                  
      }   


    },
      
    });

    }
    
});