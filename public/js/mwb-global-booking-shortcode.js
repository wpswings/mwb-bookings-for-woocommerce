
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

    today.setHours(0, 0, 0, 0); // Set to midnight to ensure date-only comparison

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: bookingCalendarData.events,
        selectable: true,

        dayCellDidMount: function(arg) {
            const cellDate = new Date(arg.date);
            cellDate.setHours(0, 0, 0, 0); // Normalize cell date

            // Disable and blur past dates
            if (cellDate < today) {
                arg.el.style.filter = 'blur(2px)';
                arg.el.style.pointerEvents = 'none';
                arg.el.style.cursor = 'not-allowed';
                arg.el.classList.add('fc-disabled-date');
            }
        },

        dateClick: function(info) {
            const clickedDate = new Date(info.date);
            clickedDate.setHours(0, 0, 0, 0);

            // Prevent past date clicks
            if (clickedDate < today) {
                alert(bookingCalendarData.passed_dates_msg);
                return;
            }

            const clickedDateStr = info.dateStr;
            const unavailableDates = bookingCalendarData.unavailableDates;
            const availableDates = bookingCalendarData.availableDates;

            if (unavailableDates.includes(clickedDateStr)) {
                alert(bookingCalendarData.unavailable_msg);
                return;
            }

            if (!availableDates.includes(clickedDateStr)) {
               alert(bookingCalendarData.unavailable_msg);
                return;
            }

            const url = getBookingUrl(clickedDateStr);
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
		