
document.addEventListener('DOMContentLoaded', function () {
    const postId = bookingCalendarData.postId;
    const calendarEl = document.getElementById(`booking-calendar-${postId}`);
    const selectedDatesEl = document.getElementById(`selected-dates-${postId}`); // textarea/hidden field
    const selectedCostEl = document.getElementById(`wps_global-selected-date-cost`); // textarea/hidden field

    const bookingDatesEl = document.getElementById(`selected-dates-${postId}`); // textarea/hidden field
    const submitBtn = document.getElementById(`booking-submit-${postId}`); // submit button
    const today = new Date();
    const baseUrl = bookingCalendarData.baseUrl;
    const defaultPrice = bookingCalendarData.defaultPrice;
    const required = bookingCalendarData.required_msg;
    const dateSelectMsg = bookingCalendarData.date_select_msg;

    today.setHours(0, 0, 0, 0);

    let selectedDates = []; // store all chosen dates

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: bookingCalendarData.events,
        selectable: true,

        dayCellDidMount: function(arg) {
            const cellDate = new Date(arg.date);
            cellDate.setHours(0, 0, 0, 0);

            // Disable past dates
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

            if (clickedDate < today) {
                alert(bookingCalendarData.passed_dates_msg);
                return;
            }

            const clickedDateStr = info.dateStr;
            const unavailableDates = bookingCalendarData.unavailableDates;
            const availableDates = bookingCalendarData.availableDates;

            if (unavailableDates.includes(clickedDateStr) || !availableDates.includes(clickedDateStr)) {
                alert(bookingCalendarData.unavailable_msg);
                return;
            }

            // Toggle date selection
            if (selectedDates.includes(clickedDateStr)) {
                // remove if already selected
                selectedDates = selectedDates.filter(d => d !== clickedDateStr);
                info.dayEl.style.backgroundColor = ''; // reset highlight
            } else {
                selectedDates.push(clickedDateStr);
                info.dayEl.style.backgroundColor = '#90EE90'; // highlight selected
            }

            // Update field/textarea
            selectedDatesEl.value = selectedDates.join(', ');
            bookingDatesEl.value = selectedDates.join(', ');
            selectedCostEl.innerText = defaultPrice +' X ' + selectedDates.length + ' = ' + (selectedDates.length * defaultPrice);
        },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        }
    });

    calendar.render();

    // Submit selected dates
    submitBtn.addEventListener('click', function(e) {
            // Get form element
        const form = document.querySelector('.wps-global-calendar-form');

        // Create FormData object (collects all fields automatically)
            const formData = new FormData(form);
            const entries = [];

            formData.forEach((value, key) => {

                 entries.push({ name: key, value: value });
            });

            e.preventDefault();


// ------------------------------ Validate required fields

    let isValid = true;

    // Remove old error messages
    form.querySelectorAll(".error-msg").forEach(el => el.remove());

    // Loop through required fields
    form.querySelectorAll("[required]").forEach(function(input) {
        let label = null;

        // Case 1: <label for="id">
        if (input.id) {
            label = form.querySelector(`label[for="${input.id}"]`);
        }

        // Case 2: input wrapped inside <label>
        if (!label && input.closest("label")) {
            label = input.closest("label");
        }

        // Case 3: label is just the previous sibling
        if (!label && input.previousElementSibling?.tagName === "LABEL") {
            label = input.previousElementSibling;
        }

        // Now validate
        if (input.type === "radio" || input.type === "checkbox") {
            const groupName = input.name;
            const groupInputs = form.querySelectorAll(`input[name="${groupName}"]`);
            const checked = Array.from(groupInputs).some(i => i.checked);

            if (!checked && label) {
                isValid = false;
                if (!label.querySelector(".error-msg")) {
                    label.insertAdjacentHTML(
                        "beforeend",
                        '<span class="error-msg"> * '+required+'</span>'
                    );
                }
            }
        } else {
            if (!input.value.trim() && label) {
                isValid = false;
                if (!label.querySelector(".error-msg")) {
                    label.insertAdjacentHTML(
                        "beforeend",
                        '<span class="error-msg"> * '+required+'</span>'
                    );
                }
            }
        }
    });
    form.querySelectorAll('input[type="email"]').forEach(function(input) {
    let label = null;

    // Case 1: <label for="id">
    if (input.id) {
        label = form.querySelector(`label[for="${input.id}"]`);
    }

    // Case 2: input wrapped inside <label>
    if (!label && input.closest("label")) {
        label = input.closest("label");
    }

    // Case 3: label is just the previous sibling
    if (!label && input.previousElementSibling?.tagName === "LABEL") {
        label = input.previousElementSibling;
    }

                // Email format validation
        if (input.type === "email" && input.value.trim() !== "") {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(input.value.trim())) {
                isValid = false;
                if (label && !label.querySelector(".error-msg")) {
                    label.insertAdjacentHTML(
                        "beforeend",
                        '<span class="error-msg"> * invalid email</span>'
                    );
                }
            }
        }
    });
    if (!isValid) {
        return; // stop submission until fixed
    }

// ------------------------------ End validation





        if (selectedDates.length === 0) {
            alert(dateSelectMsg);
            return;
        }
        var price= (selectedDates.length) * defaultPrice;
        // Build cart URL with multiple dates
        // e.g., pass them as comma-separated
        const url = `${baseUrl}?add-booking-to-cart=1&booking_date=${selectedDates.join(',')}&booking_price=${price}&global_booking_form=${JSON.stringify(entries)}`;

        window.location.href = url;
    });

    
});



        jQuery(document).ready(function($) {
                    var root = $(':root');
        root.css('--wps-primary-color', bookingCalendarData.form_color );

            $(".wps_global_multiselect").select2({
                placeholder: "Select options",
                allowClear: true
            });
        });
		