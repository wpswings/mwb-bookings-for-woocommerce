
document.addEventListener('DOMContentLoaded', function () {
    const postId         = bookingCalendarData.postId;
    const calendarEl     = document.getElementById(`booking-calendar-${postId}`);
    const hiddenDatesEl  = document.getElementById(`selected-dates-${postId}`);
    const submitBtn      = document.getElementById(`booking-submit-${postId}`);
    const chipsContainer = document.getElementById(`wps-gcal-chips-${postId}`);
    const priceRow       = document.getElementById(`wps-gcal-price-row-${postId}`);
    const priceBreakdown = document.getElementById(`wps-gcal-price-breakdown-${postId}`);
    const priceTotal     = document.getElementById(`wps-gcal-price-total-${postId}`);
    const limitInfo      = document.getElementById(`wps-gcal-limit-info-${postId}`);

    const today         = new Date();
    const baseUrl       = bookingCalendarData.baseUrl;
    const defaultPrice  = parseFloat(bookingCalendarData.defaultPrice) || 0;
    const currency      = bookingCalendarData.currencySymbol || '';
    const required      = bookingCalendarData.required_msg;
    const dateSelectMsg = bookingCalendarData.date_select_msg;
    const weeklyOffDays = bookingCalendarData.weeklyOffDays || [];

    today.setHours(0, 0, 0, 0);

    // Pre-process available/unavailable dates into plain arrays once.
    const unavailableDates = bookingCalendarData.unavailableDates || [];
    let availableDates;
    if (Array.isArray(bookingCalendarData.availableDates)) {
        availableDates = bookingCalendarData.availableDates;
    } else if (typeof bookingCalendarData.availableDates === 'object' && bookingCalendarData.availableDates !== null) {
        availableDates = Object.values(bookingCalendarData.availableDates);
    } else {
        availableDates = [];
    }

    let selectedDates = [];
    let calendarInstance = null; // set after FullCalendar init, used by removeDate

    // Show booking limit info line
    if (limitInfo && bookingCalendarData.orderLimitEnabled && bookingCalendarData.orderLimit > 0) {
        limitInfo.textContent = `Minimum 1 day \u00b7 Maximum ${bookingCalendarData.orderLimit} day(s) per booking`;
    }

    // Format "2026-09-07" -> "Sep 7"
    function formatChipDate(dateStr) {
        const parts = dateStr.split('-').map(Number);
        return new Date(parts[0], parts[1] - 1, parts[2]).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    // Append a dot element to a cell frame
    function addDotToCell(frameEl, type) {
        const dot = document.createElement('span');
        dot.className = 'wps-gcal-dot wps-gcal-dot-' + type;
        frameEl.appendChild(dot);
        return dot;
    }

    // Sync hidden input and price display
    function syncState() {
        if (hiddenDatesEl) {
            hiddenDatesEl.value = selectedDates.join(', ');
        }

        if (priceRow) {
            if (selectedDates.length > 0) {
                var total = parseFloat((selectedDates.length * defaultPrice).toFixed(2));
                priceRow.style.display = 'flex';
                if (priceBreakdown) {
                    priceBreakdown.textContent = currency + defaultPrice + ' \u00d7 ' + selectedDates.length + ' day(s)';
                }
                if (priceTotal) {
                    priceTotal.textContent = currency + total;
                }
            } else {
                priceRow.style.display = 'none';
            }
        }
    }

    // Add a chip tag for a selected date
    function addChip(dateStr) {
        if (!chipsContainer) return;
        const chip = document.createElement('span');
        chip.className = 'wps-gcal-chip';
        chip.dataset.date = dateStr;
        chip.innerHTML = formatChipDate(dateStr) + ' <button type="button" class="wps-gcal-chip-remove" aria-label="Remove">&times;</button>';
        chip.querySelector('.wps-gcal-chip-remove').addEventListener('click', function () {
            removeDate(dateStr);
        });
        chipsContainer.appendChild(chip);
    }

    // Remove a chip tag for a date
    function removeChip(dateStr) {
        if (!chipsContainer) return;
        const chip = chipsContainer.querySelector('[data-date="' + dateStr + '"]');
        if (chip) chip.remove();
    }

    // Fully deselect a date (from chip click, cell re-click, or list-view event click)
    function removeDate(dateStr) {
        selectedDates = selectedDates.filter(function (d) { return d !== dateStr; });
        removeChip(dateStr);

        // dayGrid view — reset cell class and dot
        const cellEl = calendarEl.querySelector('[data-date="' + dateStr + '"]');
        if (cellEl) {
            cellEl.classList.remove('wps-selected-date');
            const dot = cellEl.querySelector('.wps-gcal-dot');
            if (dot) {
                dot.className = availableDates.includes(dateStr)
                    ? 'wps-gcal-dot wps-gcal-dot-available'
                    : 'wps-gcal-dot wps-gcal-dot-unavailable';
            }
        }

        // list view — reset the FullCalendar event color back to original
        if (calendarInstance) {
            const ev = calendarInstance.getEventById('available_' + dateStr);
            if (ev) {
                var orig = (bookingCalendarData.events || []).find(function (e) { return e.start === dateStr; });
                ev.setProp('backgroundColor', orig ? orig.color : '');
                ev.setProp('borderColor',     orig ? orig.color : '');
                ev.setProp('textColor', '');
            }
        }

        syncState();
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        events: bookingCalendarData.events,
        selectable: true,

        dayCellDidMount: function (arg) {
            const d = arg.date;
            const cellDateStr = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            const cellDate    = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            const frame       = arg.el.querySelector('.fc-daygrid-day-frame');

            // Past dates — fade out, no interaction
            if (cellDate < today) {
                arg.el.classList.add('fc-disabled-date');
                return;
            }

            // Weekly off days — red number, not clickable
            if (weeklyOffDays.includes(cellDate.getDay())) {
                arg.el.style.pointerEvents = 'none';
                arg.el.classList.add('fc-weekly-off');
                const dayTop = arg.el.querySelector('.fc-daygrid-day-top');
                if (dayTop) dayTop.style.opacity = '1';
                return;
            }

            if (!frame) return;

            // Re-apply selected state after month navigation
            if (selectedDates.includes(cellDateStr)) {
                arg.el.classList.add('wps-selected-date');
                addDotToCell(frame, 'selected');
                return;
            }

            // Unavailable
            if (unavailableDates.includes(cellDateStr)) {
                arg.el.classList.add('fc-unavailable-date');
                addDotToCell(frame, 'unavailable');
                return;
            }

            // Available
            if (availableDates.includes(cellDateStr)) {
                arg.el.classList.add('fc-available-date');
                addDotToCell(frame, 'available');
            }
        },

        dateClick: function (info) {
            const d = info.date;
            const clickedDate = new Date(d.getFullYear(), d.getMonth(), d.getDate());

            if (clickedDate < today) {
                alert(bookingCalendarData.passed_dates_msg);
                return;
            }

            if (weeklyOffDays.includes(clickedDate.getDay())) {
                alert(bookingCalendarData.weekly_off_msg);
                return;
            }

            const clickedDateStr = info.dateStr;

            if (unavailableDates.includes(clickedDateStr) || !availableDates.includes(clickedDateStr)) {
                alert(bookingCalendarData.unavailable_msg);
                return;
            }

            if (selectedDates.includes(clickedDateStr)) {
                // Toggle off
                removeDate(clickedDateStr);
            } else {
                // Enforce booking limit
                if (
                    bookingCalendarData.orderLimitEnabled &&
                    bookingCalendarData.orderLimit > 0 &&
                    selectedDates.length >= bookingCalendarData.orderLimit
                ) {
                    alert(bookingCalendarData.order_limit_msg);
                    return;
                }

                // Toggle on
                selectedDates.push(clickedDateStr);
                info.dayEl.classList.add('wps-selected-date');

                var dot = info.dayEl.querySelector('.wps-gcal-dot');
                if (dot) {
                    dot.className = 'wps-gcal-dot wps-gcal-dot-selected';
                } else {
                    var cellFrame = info.dayEl.querySelector('.fc-daygrid-day-frame');
                    if (cellFrame) addDotToCell(cellFrame, 'selected');
                }

                addChip(clickedDateStr);
                syncState();
            }
        },

        // Handle clicks on events in list view
        eventClick: function (info) {
            // Only act in list view; dayGrid uses dateClick
            if (calendarInstance && calendarInstance.view.type !== 'listMonth') return;

            var event          = info.event;
            var clickedDateStr = event.startStr; // YYYY-MM-DD

            if (event.title === 'Unavailable') {
                alert(bookingCalendarData.unavailable_msg);
                info.jsEvent.preventDefault();
                return;
            }

            if (event.title !== 'Available') return;

            var parts       = clickedDateStr.split('-').map(Number);
            var clickedDate = new Date(parts[0], parts[1] - 1, parts[2]);

            if (clickedDate < today) {
                alert(bookingCalendarData.passed_dates_msg);
                info.jsEvent.preventDefault();
                return;
            }

            if (weeklyOffDays.includes(clickedDate.getDay())) {
                alert(bookingCalendarData.weekly_off_msg);
                info.jsEvent.preventDefault();
                return;
            }

            info.jsEvent.preventDefault();

            if (selectedDates.includes(clickedDateStr)) {
                removeDate(clickedDateStr);
            } else {
                if (
                    bookingCalendarData.orderLimitEnabled &&
                    bookingCalendarData.orderLimit > 0 &&
                    selectedDates.length >= bookingCalendarData.orderLimit
                ) {
                    alert(bookingCalendarData.order_limit_msg);
                    return;
                }

                selectedDates.push(clickedDateStr);
                event.setProp('backgroundColor', '#16a34a');
                event.setProp('borderColor', '#16a34a');
                event.setProp('textColor', '#fff');
                addChip(clickedDateStr);
                syncState();
            }
        },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        }
    });

    calendarInstance = calendar;
    calendar.render();

    // Submit handler
    if (!submitBtn) return;

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();

        // Scope to THIS calendar's form, not the first on the page
        const form = submitBtn.closest('.wps-global-calendar-form');
        const formData = new FormData(form);
        const entries  = [];
        formData.forEach(function (value, key) {
            entries.push({ name: key, value: value });
        });

        // Validate required fields
        var isValid = true;
        form.querySelectorAll('.error-msg').forEach(function (el) { el.remove(); });

        form.querySelectorAll('[required]').forEach(function (input) {
            var label = input.id ? form.querySelector('label[for="' + input.id + '"]') : null;
            if (!label && input.closest('label'))                             label = input.closest('label');
            if (!label && input.previousElementSibling && input.previousElementSibling.tagName === 'LABEL') label = input.previousElementSibling;

            if (input.type === 'radio' || input.type === 'checkbox') {
                var checked = Array.from(form.querySelectorAll('input[name="' + input.name + '"]')).some(function (i) { return i.checked; });
                if (!checked && label && !label.querySelector('.error-msg')) {
                    isValid = false;
                    label.insertAdjacentHTML('beforeend', '<span class="error-msg"> * ' + required + '</span>');
                }
            } else if (!input.value.trim() && label && !label.querySelector('.error-msg')) {
                isValid = false;
                label.insertAdjacentHTML('beforeend', '<span class="error-msg"> * ' + required + '</span>');
            }
        });

        form.querySelectorAll('input[type="email"]').forEach(function (input) {
            var label = input.id ? form.querySelector('label[for="' + input.id + '"]') : null;
            if (!label && input.closest('label'))                             label = input.closest('label');
            if (!label && input.previousElementSibling && input.previousElementSibling.tagName === 'LABEL') label = input.previousElementSibling;

            if (input.value.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) {
                isValid = false;
                if (label && !label.querySelector('.error-msg')) {
                    label.insertAdjacentHTML('beforeend', '<span class="error-msg"> * invalid email</span>');
                }
            }
        });

        if (!isValid) return;

        if (selectedDates.length === 0) {
            alert(dateSelectMsg);
            return;
        }

        var price = selectedDates.length * defaultPrice;
        var url   = baseUrl + '?add-booking-to-cart=1&booking_date=' + selectedDates.join(',') + '&booking_price=' + price + '&global_booking_form=' + JSON.stringify(entries) + '&global_calendar_id=' + postId + '&mwb_booking_nonce=' + encodeURIComponent(bookingCalendarData.addToCartNonce);
        window.location.href = url;
    });
});


jQuery(document).ready(function ($) {
    var root = $(':root');
    root.css('--wps-primary-color', bookingCalendarData.form_color);

    $('.wps_global_multiselect').select2({
        placeholder: 'Select options',
        allowClear: true
    });
});
