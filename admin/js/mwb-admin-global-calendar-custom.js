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
				});
		