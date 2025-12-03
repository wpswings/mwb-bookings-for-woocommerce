jQuery(function ($) {
	jQuery(document).ready(function($) {
		var availableDates = mbfw_global_calendar_booking_ajax.available_days;
		var nonAvailableDates = mbfw_global_calendar_booking_ajax.non_available_days;
	
		// $('#available_days_picker').flatpickr({
		// 	mode: "multiple",
		// 	dateFormat: "Y-m-d",
		// 	allowInput: false,
		// 	defaultDate: availableDates
		// });
	
		// $('#non_available_days_picker').flatpickr({
		// 	mode: "multiple",
		// 	dateFormat: "Y-m-d",
		// 	allowInput: false,
		// 	defaultDate: nonAvailableDates
		// });



        const availablePicker = $('#available_days_picker').flatpickr({
            mode: "multiple",
            dateFormat: "Y-m-d",
            allowInput: false,
            defaultDate: availableDates,
                disable: nonAvailableDates,           // disable non-available dates

            onChange: function(selectedDates, dateStr, instance) {
                // Sync available dates
                availableDates = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));

                // Remove from non-available if overlapping
                        nonAvailablePicker.set('disable', availableDates);

            }
        });

        const nonAvailablePicker = $('#non_available_days_picker').flatpickr({
            mode: "multiple",
            dateFormat: "Y-m-d",
            allowInput: false,
            defaultDate: nonAvailableDates,
                disable: availableDates,           // disable non-available dates

            onChange: function(selectedDates, dateStr, instance) {
                // Sync non-available dates
                nonAvailableDates = selectedDates.map(d => instance.formatDate(d, "Y-m-d"));

                // Remove from available if overlapping
                        availablePicker.set('disable', nonAvailableDates);

            }
        });


            const $field = $('#wps_booking_limit_per_date');

            $field.on('input', function(){

                let val = $(this).val();

                // Remove non-numeric characters
                val = val.replace(/[^0-9]/g, '');

                // Remove leading zeros
                val = val.replace(/^0+(?!$)/, '');

                // Force value to be integer
                if (val === '') {
                    val = '';
                } else {
                    val = parseInt(val, 10);
                }

                $(this).val(val);
            });

            // Extra protection: prevent E/e and minus on keypress
            $field.on('keypress', function(e){

                // Block minus, plus, E/e (exponential), decimal
                if (e.key === '-' || 
                    e.key === '+' || 
                    e.key === 'e' || 
                    e.key === 'E' || 
                    e.key === '.' ) {
                    e.preventDefault();
                }
            });
	});


	$('#booking_default_price').on('keypress', function(e) {
        var charCode = e.which ? e.which : e.keyCode;
        var charStr = String.fromCharCode(charCode);

        // Allow only digits and a dot (.)
        if (!charStr.match(/[0-9.]/)) {
            e.preventDefault(); // Block input
        }

        // Prevent multiple dots
        if (charStr === '.' && $(this).val().includes('.')) {
            e.preventDefault();
        }
    });
$('#copy-ical-btn').on('click', function() {
    const link = $('#ical-export-link').attr('href'); // Get the URL from the anchor's href

    navigator.clipboard.writeText(link).then(function() {
        $('#ical-copy-msg').fadeIn(200).delay(1000).fadeOut(400);
    }).catch(function(err) {
        console.error('Clipboard copy failed:', err);
        alert('Failed to copy. Please copy manually.');
    });
});

});
		