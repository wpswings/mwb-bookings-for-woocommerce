
document.addEventListener('DOMContentLoaded', function () {
    const postId = (bookingCalendarData.postId);

    const calendarEl = document.getElementById(`booking-calendar-${postId}`);
    console.log(calendarEl);
    const statusEl = document.getElementById(`booking-status-${ postId}`);
    const today = new Date(); // now
    const baseUrl = bookingCalendarData.baseUrl;
    const defaultPrice = bookingCalendarData.defaultPrice;
    function getBookingUrl(date) {
        return `${baseUrl}?add-booking-to-cart=1&booking_date=${date}&booking_price=${defaultPrice};`;
    }
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: bookingCalendarData.events,
        selectable: true,
        dayCellDidMount: function(arg) {
    // arg.date is a JS Date for each cell at midnight
    if (arg.date < today.setHours(0,0,0,0)) {
        arg.el.style.filter = 'blur(2px)';
        arg.el.style.pointerEvents = 'none';
    }
    },
        dateClick: function(info) {
            const clickedDate = info.dateStr;
            const unavailableDates = bookingCalendarData.unavailableDates;
            const availableDates = bookingCalendarData.availableDates;

            if (unavailableDates.includes(clickedDate)) {
                alert("This date is unavailable for booking.");
                return;
            }

            if (!availableDates.includes(clickedDate)) {
                alert("This date is not available for booking.");
                return;
            }

            const url = getBookingUrl(clickedDate);
            window.location.href = url;
        },
        

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        }
    });

    calendar.render();
});
		