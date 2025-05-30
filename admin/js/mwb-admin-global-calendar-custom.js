jQuery(function ($) {
	jQuery(document).ready(function($) {
		var availableDates = mbfw_global_calendar_booking_ajax.available_days;
		var nonAvailableDates = mbfw_global_calendar_booking_ajax.non_available_days;
	
		$('#available_days_picker').flatpickr({
			mode: "multiple",
			dateFormat: "Y-m-d",
			allowInput: false,
			defaultDate: availableDates
		});
	
		$('#non_available_days_picker').flatpickr({
			mode: "multiple",
			dateFormat: "Y-m-d",
			allowInput: false,
			defaultDate: nonAvailableDates
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
});
		