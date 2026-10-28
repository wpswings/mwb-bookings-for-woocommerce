//code for datatable

jQuery(document).ready(function($) {
    // On phones let the month grid grow to its natural height; the default
    // width/height ratio leaves too little room and forces an inner scroll.
    function mbfwCalendarHeight() {
        return window.matchMedia( '(max-width: 768px)' ).matches ? 'auto' : undefined;
    }
    var calendarEl = document.getElementById('mwb-mbfw-booking-calendar');
    if ( calendarEl ) {
        var calendar = new FullCalendar.Calendar( calendarEl, {
            initialView: 'dayGridMonth',
            height: mbfwCalendarHeight(),
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
});