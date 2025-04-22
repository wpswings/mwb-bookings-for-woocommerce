jQuery(document).ready(function($){
    jQuery('.mwb-mbfw-user-booking-meta-data-listing').slideUp();
    jQuery('.mwb-mbfw-ser-booking-toggler').on('click',function(){
        jQuery(this).toggleClass('booking-toggler-reverse');
        jQuery(this).siblings('.mwb-mbfw-user-booking-meta-data-listing').slideToggle('slow');
    })



    
    jQuery('.single_add_to_cart_button').on('click',function(e){
       
        dataaa =  jQuery('#wps_booking_single_calendar_form').val();
        if ( dataaa != '' || dataaa != undefined ) {
            $check_valid = dataaa.split(' ');
            if ( $check_valid.length == 1 ) {
                jQuery('#wps_booking_single_calendar_form').val('');
                alert( mwb_mbfw_public_obj.validation_message );
                e.preventDefault();
            }

            
        }
    })
  
   

    if( mwb_mbfw_public_obj.daily_start_time != '' && mwb_mbfw_public_obj.daily_end_time != '' ) {
        
        $('.mwb_mbfw_time_date_picker_frontend').datetimepicker({
            format  : 'd-m-Y H:00',
            minTime: mwb_mbfw_public_obj.daily_start_time,
            maxTime : mwb_mbfw_public_obj.daily_end_time + 1,
        });
    }
    jQuery('#wps_booking_single_calendar_form').on('blur',function(){
        
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
    var from_time = '';
    var to_time = '';
         from_time =  'mwb-mbfw-booking-from-time';
         to_time =  'mwb-mbfw-booking-to-time';
    const time_format =('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format )? false:true;
	const date_time_format = ( 'twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) ? "d-m-Y h:i K" : "d-m-Y H:i";

    if( is_pro_active != 'yes' ) {

        if( upcoming_holiday.length > 0 ){

            if ( jQuery('.wps_single_cal_hourly').length > 0 ) {
                

                flatpickr('#mwb-mbfw-booking-from-time', {  
                    enableTime: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                    dateFormat: date_time_format,
                    disableMobile: true,
                            
                    time_24hr: time_format,
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
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                    dateFormat: date_time_format,
                    time_24hr: time_format,
                    disableMobile: true,
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
            } else if ( mwb_mbfw_public_obj.mwb_mbfw_show_date_with_time == 'yes'){

                flatpickr('#'+from_time, {  
                    
                    enableTime: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                    dateFormat: date_time_format,
                    time_24hr: time_format,
                    disableMobile: true,
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
                    
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                    dateFormat: date_time_format,
                    time_24hr: time_format,
                    disableMobile: true,
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


            } else {
                flatpickr('#'+from_time, {  
                    disableMobile: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
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
        
                flatpickr('#'+to_time, {  
                    disableMobile: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
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
                

                flatpickr('#'+from_time, {  
                    enableTime: true,
                    
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                    dateFormat: date_time_format,
                    time_24hr: time_format,
                    disableMobile: true,
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
        
                flatpickr('#'+to_time, {  
                    
                    enableTime: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                        dateFormat: date_time_format,
                    disableMobile: true,
                    time_24hr: time_format,
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

                flatpickr('#'+from_time, {  
                    
                    enableTime: true,
                    disableMobile: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                        dateFormat: date_time_format,
                    time_24hr: time_format,
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
        
                flatpickr('#'+to_time, {  
                    enableTime: true,
                    
                    disableMobile: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                        dateFormat: date_time_format,
                    time_24hr: time_format,
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
                flatpickr('#'+from_time, {  
                    
                    disableMobile: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
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
        
                flatpickr('#'+to_time, {  
                    disableMobile: true,
                    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
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

        if (wps_available_slots != '') {
            
  
            flatpickr('#wps_booking_single_calendar_form', {  
                mode: "single",
                locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
                dateFormat: "Y-m-d",
                disableMobile: true,
                
                enable: available_dates ,
            
            
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    dObj = dayElem.dateObj;
                    // Convert the date string to match the format of availableDates and unavailableDates
                  var dateString = dObj.getFullYear() + '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + ("0" + dObj.getDate()).slice(-2);
            
                  var selected_date = moment(dateString).format('D-M-Y');
                  var date_array = selected_date.split("-");
                  
                  var date = date_array[0];
                  var month = date_array[1];
                  var year = date_array[2];
                  
                  
                  
                  if (month.length === 1) {
                      month = '0' + month;
                  }
                  if (date.length === 1) {
                    date = '0' + date;
                    }
                  var temp_date = date + '-' + month + '-' + year + ' ';
                  var temp_date1 = date + '-' + month + '-' + year ;

              if ( mwb_mbfw_public_obj.is_pro_active != ''){
                  var daywise_slot = [];
                  if (temp_date1 in bfwp_public_param.wps_daywise_slot_available) {
                      daywise_slot =(bfwp_public_param.wps_daywise_slot_available[temp_date1]);
                  }
                  
                  if( Object.keys(daywise_slot).length > 0  ) { 
                      var date_slots =Object.values(daywise_slot);
                  } else {
                      var date_slots = wps_available_slots;

                  }
              } else {
                  var date_slots = wps_available_slots;
              }
              var count =0;
            for(let i=0; i< date_slots.length; i++ ) { 
                var temp =  date_slots[i]._from + ' - ' + date_slots[i]._to;
                var temp_check = temp_date + temp;
                if (booking_unavailable.length > 0) {
                    
                    if (!booking_unavailable.includes(temp_check)) {
                        count ++;
                            
                        
                    }
                } else {
                    count ++;
                }
            }




                  year_current = (dayElem.dateObj.getFullYear()).toString();
                  month_current =dayElem.dateObj.getMonth()+1;
                  var dateString__ = ("0" + dObj.getDate()).slice(-2)+ '-' + ("0" + (dObj.getMonth() + 1)).slice(-2) + '-' + dObj.getFullYear() ;
                if (count > 0 ){
                    if (mwb_mbfw_public_obj.single_available_dates_till != '' ) {
                        var datas_till= mwb_mbfw_public_obj.single_available_dates_till.split('-');
                        month_till = datas_till[1];
                        year_till = datas_till[2];
                
                        if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {

                            if ( moment( mwb_mbfw_public_obj.single_available_dates_till, 'DD-MM-YYYY' ) >= moment( dateString__, 'DD-MM-YYYY' ) ) {
                                if(mwb_mbfw_public_obj.wps_mbfw_day_and_days_upto_togather_enabled){
                                    if ( '1970-01-01' != available_dates[0] ) {
                                        if (available_dates.includes(dateString)) {
                
                                            if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                                dayElem.classList.add("wps-unavailable-day");
                                                dayElem.classList.add("disabled-date");
                                            } else {
                                                dayElem.classList.add("wps-available-day");
                                                dayElem.classList.remove("flatpickr-disabled");
                                            }
                                        }
                                    } else {
                                        if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                            dayElem.classList.add("wps-unavailable-day");
                                            dayElem.classList.add("disabled-date");
                                        } else{
                                            dayElem.classList.add("wps-available-day");
                                            dayElem.classList.remove("flatpickr-disabled");
                                        }  
                                    }
                
                                } else {
                
                                    if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                            dayElem.classList.add("wps-unavailable-day");
                                            dayElem.classList.add("disabled-date");
                                        } else{
                                            dayElem.classList.add("wps-available-day");
                                            dayElem.classList.remove("flatpickr-disabled");
                
                                    }
                                }
                
                
                            } else {
                                    dayElem.classList.add("flatpickr-disabled");
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
                } else{
                    dayElem.classList.add("wps-unavailable-day");
                        
                    dayElem.classList.add("flatpickr-disabled");
                }
                  
            
                
                  month =dayElem.dateObj.getMonth()+1;

                  if ( mwb_mbfw_public_obj.is_pro_active != ''){
                    if ( bfwp_public_param.global_unaviable_month.includes(month.toString())){
                        if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
                            if ( bfwp_public_param.global_unaviable_day.includes( dObj.getDay().toString() ) || bfwp_public_param.upcoming_holiday.includes( dateString ) ){
                                dayElem.classList.add("wps-unavailable-day");
                                dayElem.classList.remove("wps-available-day");
                                dayElem.classList.add("flatpickr-disabled");
                            }
                        }
                        
                    } else if(bfwp_public_param.upcoming_holiday.includes( dateString )){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.remove("wps-available-day");
                        dayElem.classList.add("flatpickr-disabled");
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
                onChange: function(selectedDates, dateStr, instance) {

                    const selected = moment(selectedDates[0]);
                    const today = moment();
                    const today_date = today.format('HH:mm');

                    document.querySelectorAll(".flatpickr-day").forEach(day => {
                        if (day.classList.contains("custom-selected")) {
                        day.classList.remove("custom-selected");
                        day.classList.add("wps-available-day");
                        }
                      });
                
                     var $calendarContainer = $(instance.calendarContainer);
                    $calendarContainer.find('.custom-content').remove();
                    var selected_date = moment(dateStr).format('D-M-Y');
                    var date_array = selected_date.split("-");
                    
                    var date = date_array[0];
                    var month = date_array[1];
                    var year = date_array[2];
                    
                    
                    
                    if (month.length === 1) {
                        month = '0' + month;
                    }
                    if (date.length === 1) {
                        date = '0' + date;
                    }
                    var temp_date = date + '-' + month + '-' + year + ' ';
                    var temp_date1 = date + '-' + month + '-' + year ;

                    var html = '<div class="wps_cal_timeslot">\n\ ';

                if ( mwb_mbfw_public_obj.is_pro_active != ''){
                    var daywise_slot = [];
                    if (temp_date1 in bfwp_public_param.wps_daywise_slot_available) {
                        daywise_slot =(bfwp_public_param.wps_daywise_slot_available[temp_date1]);
                    }
                    
                    if( Object.keys(daywise_slot).length > 0  ) { 
                        var date_slots =Object.values(daywise_slot);
                    } else {
                        var date_slots = wps_available_slots;

                    }
                } else {
                    var date_slots = wps_available_slots;
                }
                    for(let i=0; i< date_slots.length; i++ ) { 
                        var start_time = date_slots[i]._from;
                        var end_time = date_slots[i]._to;
                        if ((selected.isSame(today, 'day')) &&
                            ! (moment(start_time,"HH:mm").isAfter(moment(today_date,"HH:mm")))){
                                continue;
                            }
                        

                        if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
                            start_time = moment(date_slots[i]._from, "HH:mm").format("h:mm A");
                            end_time = moment(date_slots[i]._to, "HH:mm").format("h:mm A");
                        }
                        var temp =  start_time + ' - ' + end_time;
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
                    $calendarContainer.find('.wps_cal_timeslot').remove();          // Create custom HTML using jQuery based on the selected date

                    // Append the custom HTML to the Flatpickr calendar container
                    $calendarContainer.append(html);
                    instance.open();
                    if (selectedDates.length > 0) {
                        const date = selectedDates[0]; // Get the first selected date
                        const formattedDateStr = instance.formatDate(date,  "F j, Y"); // Format the date

                        const dateStr = instance.formatDate(date,  "F j, Y");
                            const dayElement = instance.calendarContainer.querySelector(
                              `.flatpickr-day[aria-label="${dateStr}"]`
                            );
                            if (null != dayElement) {
                                dayElement.classList.remove("wps-available-day");

                              dayElement.classList.add("custom-selected");
                            }
                      } else {
                        console.log("No dates selected.");
                      }
                    jQuery('.wps_cal_timeslot button').on('click touchstart', function (e) {
                        e.preventDefault();

                        jQuery(".wps_cal_timeslot button.wps_timeslot_button").removeClass("wps_timeslot_button");
						jQuery(this).addClass('wps_timeslot_button');
                        jQuery("#wps_booking_single_calendar_form").val(temp_date + jQuery(this).html());
                        jQuery("#wps_booking_single_calendar_form").trigger('change');
						setTimeout(function(){ 
							instance.close();
						}, 1000);
                        
                        
                    });
            }
            });

            
        } else{
        flatpickr('#wps_booking_single_calendar_form', {  
        mode: "single",
        locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
                            firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
                        }, 
        dateFormat: "Y-m-d",
        disableMobile: true,
        
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

                    if ( moment( mwb_mbfw_public_obj.single_available_dates_till, 'DD-MM-YYYY' ) >= moment( dateString__, 'DD-MM-YYYY' ) ) {
                        if(mwb_mbfw_public_obj.wps_mbfw_day_and_days_upto_togather_enabled){
                            if ( '1970-01-01' != available_dates[0] ) {
                                if (available_dates.includes(dateString)) {
        
                                    if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                        dayElem.classList.add("wps-unavailable-day");
                                        dayElem.classList.add("disabled-date");
                                    } else {
                                        dayElem.classList.add("wps-available-day");
                                        dayElem.classList.remove("flatpickr-disabled");
                                    }
                                }
                            } else {
                                if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                    dayElem.classList.add("wps-unavailable-day");
                                    dayElem.classList.add("disabled-date");
                                } else{
                                    dayElem.classList.add("wps-available-day");
                                    dayElem.classList.remove("flatpickr-disabled");
                                }  
                            }
        
                        } else {
        
                            if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                    dayElem.classList.add("wps-unavailable-day");
                                    dayElem.classList.add("disabled-date");
                                } else{
                                    dayElem.classList.add("wps-available-day");
                                    dayElem.classList.remove("flatpickr-disabled");
        
                            }
                        }
        
        
                    } else {
                            dayElem.classList.add("flatpickr-disabled");
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
            if (bfwp_public_param.global_unaviable_month,includes(month.toString())){
                if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
                
                    if ( bfwp_public_param.global_unaviable_day.includes( dObj.getDay().toString() ) || bfwp_public_param.upcoming_holiday.includes( dateString ) ){
                        dayElem.classList.add("wps-unavailable-day");
                        dayElem.classList.remove("wps-available-day");
                        dayElem.classList.add("flatpickr-disabled");
                    }
                }
                
            } else if(bfwp_public_param.upcoming_holiday.includes( dateString )){
                dayElem.classList.add("wps-unavailable-day");
                dayElem.classList.remove("wps-available-day");
                dayElem.classList.add("flatpickr-disabled");
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
    } else {
   
       


    flatpickr('#wps_booking_single_calendar_form_', {  
        mode: "multiple",
    locale: {...flatpickr.l10ns[mwb_mbfw_public_obj.lang] , // Set language
        firstDayOfWeek: mwb_mbfw_public_obj.firstDayOf_Week,  // Set first day of the week
    }, 
    dateFormat: "Y-m-d",
    disableMobile: true,
    
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

            if ( moment( mwb_mbfw_public_obj.single_available_dates_till, 'DD-MM-YYYY' ) >= moment( dateString__, 'DD-MM-YYYY' ) ) {
                if(mwb_mbfw_public_obj.wps_mbfw_day_and_days_upto_togather_enabled){
                    if ( '1970-01-01' != available_dates[0] ) {
                        if (available_dates.includes(dateString)) {

                            if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                                dayElem.classList.add("wps-unavailable-day");
                                dayElem.classList.add("disabled-date");
                            } else {
                                dayElem.classList.add("wps-available-day");
                                dayElem.classList.remove("flatpickr-disabled");
                            }
                        } else {
                            dayElem.classList.add("wps-unavailable-day");
                            dayElem.classList.add("disabled-date");
                        }
                    } else {
                        if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                            dayElem.classList.add("wps-unavailable-day");
                            dayElem.classList.add("disabled-date");
                        } else{
                            dayElem.classList.add("wps-available-day");
                            dayElem.classList.remove("flatpickr-disabled");
                        }  
                    }

                } else {

                    if (mwb_mbfw_public_obj.single_unavailable_dates.includes(dateString)) {
                            dayElem.classList.add("wps-unavailable-day");
                            dayElem.classList.add("disabled-date");
                        } else{
                            dayElem.classList.add("wps-available-day");
                            dayElem.classList.remove("flatpickr-disabled");

                    }
                }


            } else {
                    dayElem.classList.add("flatpickr-disabled");
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
           
        } else {
            if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {

            dayElem.classList.add("wps-unavailable-day");
             
            dayElem.classList.add("flatpickr-disabled");
            }
        }
    }
      


      month =dayElem.dateObj.getMonth()+1;
     
      if ( mwb_mbfw_public_obj.is_pro_active != ''){
        if (bfwp_public_param.global_unaviable_month.includes(month.toString())){
            if ( moment( mwb_mbfw_public_obj.today_date, 'DD-MM-YYYY' ) <= moment( dateString__, 'DD-MM-YYYY' ) ) {
                if ( bfwp_public_param.global_unaviable_day.includes( dObj.getDay().toString() ) || bfwp_public_param.upcoming_holiday.includes( dateString ) ){
                    dayElem.classList.add("wps-unavailable-day");
                    dayElem.classList.remove("wps-available-day");
                    dayElem.classList.add("flatpickr-disabled");
                }
            }
            
        } else if(bfwp_public_param.upcoming_holiday.includes( dateString )){
            dayElem.classList.add("wps-unavailable-day");
            dayElem.classList.remove("wps-available-day");
            dayElem.classList.add("flatpickr-disabled");
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
      if (fp.selectedDates.some(date => fp.formatDate(date, "Y-m-d") === dateString)) {
        dayElem.classList.add("selected");
    } else {
        dayElem.classList.remove("selected");
    }

    },
    onChange: function(selectedDates, dateStr, instance) {
        console.log(selectedDates, dateStr, instance);

        // Reapply "selected" class for multiple mode
        document.querySelectorAll(".flatpickr-day").forEach(dayElem => {
            let currentDay = dayElem.getAttribute('aria-label');
            if (selectedDates.some(date => instance.formatDate(date, "F j, Y") === currentDay)) {
                dayElem.classList.add("selected");
            } else {
                dayElem.classList.remove("selected");
            }
        });
    }
    });

    }
    
});
