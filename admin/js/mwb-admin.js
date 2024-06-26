//code for datatable
jQuery(document).ready(function($) {
   
    var calendarEl = document.getElementById('mwb-mbfw-booking-calendar');
    if ( calendarEl ) {
        var calendar = new FullCalendar.Calendar( calendarEl, {
            initialView: 'dayGridMonth',
            buttonText: {
                today : mbfw_admin_param.full_cal_button_text.today,
                month : mbfw_admin_param.full_cal_button_text.month,
                week  : mbfw_admin_param.full_cal_button_text.week,
                day   : mbfw_admin_param.full_cal_button_text.day,
                list  : mbfw_admin_param.full_cal_button_text.list
            },
            events: {
                url: mbfw_admin_param.ajaxurl,
                method: 'POST',
                extraParams: {
                    action: 'mwb_mbfw_get_all_events_date',
                    nonce: mbfw_admin_param.nonce
                }
            },
        });
        calendar.render();
    }

    
    $(document).on('click', '#wps_mbfw_clear_calender', function(){
        var calendarEl = document.getElementById('mwb-mbfw-booking-calendar');
        jQuery('#wps_order_status').val('');
   
        if ( calendarEl ) {
            var calendar = new FullCalendar.Calendar( calendarEl, {
                initialView: 'dayGridMonth',
                buttonText: {
                    today : mbfw_admin_param.full_cal_button_text.today,
                    month : mbfw_admin_param.full_cal_button_text.month,
                    week  : mbfw_admin_param.full_cal_button_text.week,
                    day   : mbfw_admin_param.full_cal_button_text.day,
                    list  : mbfw_admin_param.full_cal_button_text.list
                },
                events: {
                    url: mbfw_admin_param.ajaxurl,
                    method: 'POST',
                    extraParams: {
                        action: 'mwb_mbfw_get_all_events_date',
                        nonce: mbfw_admin_param.nonce,
                      
                    }
                },
            });
            calendar.render();
        }
    });

    $(document).on('click', '#wps_mbfw_filter_calender', function(){
        var calendarEl = document.getElementById('mwb-mbfw-booking-calendar');
        var status= jQuery('#wps_order_status').val();
        if ( calendarEl ) {
            var calendar = new FullCalendar.Calendar( calendarEl, {
                initialView: 'dayGridMonth',
                buttonText: {
                    today : mbfw_admin_param.full_cal_button_text.today,
                    month : mbfw_admin_param.full_cal_button_text.month,
                    week  : mbfw_admin_param.full_cal_button_text.week,
                    day   : mbfw_admin_param.full_cal_button_text.day,
                    list  : mbfw_admin_param.full_cal_button_text.list
                },
                events: {
                    url: mbfw_admin_param.ajaxurl,
                    method: 'POST',
                    extraParams: {
                        action: 'mwb_mbfw_get_all_events_date',
                        nonce: mbfw_admin_param.nonce,
                        status:status,
                    }
                },
            });
            calendar.render();
        }
    });



});