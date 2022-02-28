//code for datatable

jQuery(document).ready(function($) {
    var calendarEl = document.getElementById('wps-mbfw-booking-calendar');
    if ( calendarEl ) {
        var calendar = new FullCalendar.Calendar( calendarEl, {
            initialView: 'dayGridMonth',
            buttonText: {
                today : bfw_admin_param.full_cal_button_text.today,
                month : bfw_admin_param.full_cal_button_text.month,
                week  : bfw_admin_param.full_cal_button_text.week,
                day   : bfw_admin_param.full_cal_button_text.day,
                list  : bfw_admin_param.full_cal_button_text.list
            },
            events: {
                url: bfw_admin_param.ajaxurl,
                method: 'POST',
                extraParams: {
                    action: 'wps_bfw_get_all_events_date',
                    nonce: bfw_admin_param.nonce
                }
            },
        });
        calendar.render();
    }
});