(function( $ ) {
	'use strict';

	/**
	 * All of the code for your common JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    $(document).ready(function(){
		if ( $('.mbfw_time_picker').length > 0 ) {
			$('.mbfw_time_picker').timepicker();
		}
		
		$(document).on('change', 'form.cart  :input', function(e){
            var form_data = new FormData( $('form.cart')[0] );
			if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
				for (let [key, value] of form_data.entries()) {
					if (key === 'wps_booking_single_calendar_form' && key != null ) {
					form_data.set('wps_booking_single_calendar_form', convertTimeFormat(value));
					} else if (key === 'mwb_mbfw_booking_to_time' && key != null ) {
						// console.log(value)
						if( ( '' != value ) ){

							form_data.set('mwb_mbfw_booking_to_time', convertTimeFormatDual(value));
						} else{
							form_data.set('mwb_mbfw_booking_to_time', '');
						}
					} else if (key === 'mwb_mbfw_booking_from_time' && key != null ) {
					
						form_data.set('mwb_mbfw_booking_from_time', convertTimeFormatDual(value));
					}

				}
			}

			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
		});
		

		$(document).on('focusout blur keydown paste focus mousedown mouseover mouseout', '.mwb-mbfw-cart-page-data', function () {
			
			var form_data = new FormData( $('form.cart')[0] );
			if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
				
				for (let [key, value] of form_data.entries()) {
					if (key === 'wps_booking_single_calendar_form' && key != null ) {
					form_data.set('wps_booking_single_calendar_form', convertTimeFormat(value));
					}  else if (key === 'mwb_mbfw_booking_to_time' && key != null ) {
						// console.log(value)
						// if( ( null != value ) ){
					
						form_data.set('mwb_mbfw_booking_to_time', convertTimeFormatDual(value));
					// }else{
					// 	form_data.set('mwb_mbfw_booking_to_time', '');
					// }
					} else if (key === 'mwb_mbfw_booking_from_time' && key != null ) {
						
						form_data.set('mwb_mbfw_booking_from_time', convertTimeFormatDual(value));
					}
				}
			}

			if ( $('.mwb_mbfw_booking_product_id').val() ) {
				retrieve_booking_total_ajax( form_data );
			}
		});

		$('#mwb-mbfw-booking-from-time, #mwb-mbfw-booking-to-time').on('keydown paste focus mousedown',function(e){
			
			e.preventDefault();
			

		});
		$('.mwb_mbfw_time_date_picker_frontend').datetimepicker({
			format  : 'd-m-Y H:00',
			minDate : mwb_mbfw_common_obj.minDate,
		});
		$('.mwb_mbfw_date_picker_frontend').datetimepicker({
			format     : 'd-m-Y',
			timepicker : false,
			minDate    : mwb_mbfw_common_obj.minDate,
		});
		
		
		$('#mwb_mbfw_choose_holiday').datepicker({
			dateFormat : 'dd-mm-yy',
			minDate: mwb_mbfw_common_obj.minDate,
			
		});
		$('.mwb_mbfw_time_picker_frontend').datetimepicker({
			format     : 'H:i',
			datepicker : false,
		});
		$('#mwb-mbfw-booking-from-time').on('change', function(){

			var from_time = $(this).val();
			var to_time   = $('#mwb-mbfw-booking-to-time').val();	

			if ( from_time == '' ) {
				return;
			}
			var date_array = from_time.split(" ");
			var date = date_array[0]; var flag = 0;
			if ( isToday(date) ) {
				if ( mwb_mbfw_public_obj.is_pro_active != ''){
					var upcoming_holiday = bfwp_public_param.upcoming_holiday;
					if( upcoming_holiday.length > 0 ) {
						if(isDateInArray(date, upcoming_holiday)) {
							if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
									jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
									jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
									$('#mwb-mbfw-booking-from-time').val('');
									return;
							}
						} else {
							if ( from_time && to_time ) {
								if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
									from_time = convertTimeFormatDual(from_time);
									to_time = convertTimeFormatDual(to_time);
								}

								if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
									$(this).val('');
								
									if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
										if (jQuery(jQuery('.flatpickr-calendar')[0]).hasClass('open')){
											jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
											jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
											$(this).val('');
											alert( mwb_mbfw_public_obj.wrong_order_date_2 );
										}
									}
									
								}
							}
						}
					}
				} else {
					var upcoming_holiday = mwb_mbfw_public_obj.upcoming_holiday;
					if( upcoming_holiday.length > 0 ) {
						if(isDateInArray(date, upcoming_holiday)) {
							if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
									jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
									jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
									$('#mwb-mbfw-booking-from-time').val('');
									return;
							}
						} else {
							if ( from_time && to_time ) {
								if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
									from_time = convertTimeFormatDual(from_time);
									to_time = convertTimeFormatDual(to_time);
								}

								if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
									$(this).val('');
								
									if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
										if (jQuery(jQuery('.flatpickr-calendar')[0]).hasClass('open')){
											jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
											jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
											$(this).val('');
											alert( mwb_mbfw_public_obj.wrong_order_date_2 );
										}
									}
									
								}
							}
						}
					}
				}
			} else {
			
			if ( from_time && to_time ) {
				if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
					from_time = convertTimeFormatDual(from_time);
					to_time = convertTimeFormatDual(to_time);
				}
				if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
					$(this).val('');
				
					if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
						if (jQuery(jQuery('.flatpickr-calendar')[0]).hasClass('open')){
							jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
							jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
							$(this).val('');
							alert( mwb_mbfw_public_obj.wrong_order_date_2 );
						}
					}
					
				}
			}}
		});
		$('#mwb-mbfw-booking-to-time').on('change', function(){

			var from_time = $('#mwb-mbfw-booking-from-time').val();
			var to_time   = $(this).val();

			var date_array = to_time.split(" ");
			var date = date_array[0];
			if ( isToday(date) ) {
				if ( mwb_mbfw_public_obj.is_pro_active != ''){

					var upcoming_holiday = bfwp_public_param.upcoming_holiday;
					if( upcoming_holiday.length > 0 ) {
						if(isDateInArray(date, upcoming_holiday)) {
							if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
									jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
									jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
									$('#mwb-mbfw-booking-to-time').val('');
							}
						} else {
							if ( from_time && to_time ) {
								if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
									from_time = convertTimeFormatDual(from_time);
									to_time = convertTimeFormatDual(to_time);
								}
								if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
									$('#mwb-mbfw-booking-to-time').val('');

									if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
										if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('open')){
											jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('open');
											jQuery(jQuery('.flatpickr-calendar')[1]).addClass('close');
											$(this).val('');
											alert( mwb_mbfw_public_obj.wrong_order_date_1 );
										}
									}
									
									
								}
							}
						}
					}
				} else{
				var upcoming_holiday = mwb_mbfw_public_obj.upcoming_holiday;
					if( upcoming_holiday.length > 0 ) {
						if(isDateInArray(date, upcoming_holiday)) {
							if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
									jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('open');
									jQuery(jQuery('.flatpickr-calendar')[0]).addClass('close');
									$('#mwb-mbfw-booking-to-time').val('');
							}
						} else {
							if ( from_time && to_time ) {
								if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
									from_time = convertTimeFormatDual(from_time);
									to_time = convertTimeFormatDual(to_time);
								}
								if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
									$('#mwb-mbfw-booking-to-time').val('');

									if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
										if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('open')){
											jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('open');
											jQuery(jQuery('.flatpickr-calendar')[1]).addClass('close');
											$(this).val('');
											alert( mwb_mbfw_public_obj.wrong_order_date_1 );
										}
									}
									
									
								}
							}
						}
					}
				}
			}else {

				if ( from_time && to_time ) {
					if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
						from_time = convertTimeFormatDual(from_time);
						to_time = convertTimeFormatDual(to_time);
					}
					if ( moment( from_time, 'DD-MM-YYYY HH:mm' ) >= moment( to_time, 'DD-MM-YYYY HH:mm' ) ) {
						$('#mwb-mbfw-booking-to-time').val('');
						
						if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
							if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('open')){
								jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('open');
								jQuery(jQuery('.flatpickr-calendar')[1]).addClass('close');
								$(this).val('');
								alert( mwb_mbfw_public_obj.wrong_order_date_1 );
							}
						}
						
						
					}
				}
			}
		});
		$('#mwb-mbfw-booking-to-time').on('click', function(){
			if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
				if (jQuery(jQuery('.flatpickr-calendar')[1]).hasClass('close')){
					jQuery(jQuery('.flatpickr-calendar')[1]).removeClass('close');
					jQuery(jQuery('.flatpickr-calendar')[1]).addClass('open')
				}
			}
		});
		$('#mwb-mbfw-booking-from-time').on('click', function(){
			;
			if (jQuery(jQuery('.flatpickr-calendar')).length > 1 ) {
				if (jQuery(jQuery('.flatpickr-calendar')[0]).hasClass('close')){
					jQuery(jQuery('.flatpickr-calendar')[0]).removeClass('close');
					jQuery(jQuery('.flatpickr-calendar')[0]).addClass('open')
				}
			}
		});
    });

	// cancel order from my account page.
	jQuery(document).on('click', '#wps_bfw_cancel_order', function(){
		if (confirm(mwb_mbfw_common_obj.cancel_booking_order) == true) {
			
			var product_id = jQuery(this).attr('data-product');
			var order_id   = jQuery(this).attr('data-order');
			var data       = {
				'action'     : 'bfw_cancelled_booked_order',
				'nonce'      : mwb_mbfw_common_obj.nonce,
				'product_id' : product_id,
				'order_id'   : order_id,
			}
			
			jQuery.ajax({
				url     : mwb_mbfw_common_obj.ajax_url,
				method  : 'POST',
				data    : data,
				success : function( response ) {
					window.location.reload();
				}
			});
		}

		
	});

	jQuery('#wps_booking_single_calendar_form').on('blur',function(){
		var calendar_dataa =  jQuery('#wps_booking_single_calendar_form').val();

		var bodyClasses = $('body').attr('class');

		// Use a regular expression to find the product ID in the body class
		// var productIDMatch = bodyClasses.match(/postid-(\d+)/);
		var prod_id = jQuery('.mwb_mbfw_booking_product_id').val();
		if (prod_id) {
			var productId = prod_id;
	
			// You can use productId here for further processing
		} else {
			console.log('Product ID not found.');
		}


		jQuery.ajax({
			method: 'POST',
			url: mwb_mbfw_common_obj.ajax_url,
			data: {
				nonce: mwb_mbfw_common_obj.nonce,
				action: 'mbfw_get_cart_data',
				product_id: productId,
				slot_selected : calendar_dataa,
				slot_left:mwb_mbfw_public_obj.booking_slot_array_max_limit
			},
			success: function(msg) {
				if ('undefined'!= msg){

					if('no'==msg){
						jQuery('.cart .single_add_to_cart_button').prop('disabled', false);
					} else if (msg <= 0) {
						jQuery('.cart .single_add_to_cart_button').prop('disabled', true);
					} else{
						
						jQuery('.qty').attr('max',msg);
						jQuery('.cart .single_add_to_cart_button').prop('disabled', false);

					}
				}
						
					
				
			},
		});
	});

})( jQuery );
function convertTimeFormat(input) {
    // Extract date and time using regex
    let match = input.match(/^(\d{1,2}-\d{2}-\d{4}) (\d{1,2}:\d{2} [APM]{2}) - (\d{1,2}:\d{2} [APM]{2})$/);
    if (!match) return input;

    let date = match[1]; // Extract the date
    let startTime = moment(match[2], "h:mm A").format("HH:mm"); // Convert start time to 24-hour format
    let endTime = moment(match[3], "h:mm A").format("HH:mm"); // Convert end time to 24-hour format

    return `${date} ${startTime} - ${endTime}`;
}

function convertTimeFormatDual(input) {
	input_val = input;
	// if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {

		currentLang = mwb_mbfw_public_obj.lang;


		// Replace localized AM/PM markers with standard AM/PM
		var map = timeChangeLanguage(currentLang) || timeChangeLanguage('default'); // Use default map if lang not found
		input = input.replace(new RegExp(Object.keys(map).join("|"), "g"), function(match) {
			return map[match];
		});

	// }
    // Extract date and time using regex
    let match = input.match(/^(\d{1,2}-\d{2}-\d{4}) (\d{1,2}:\d{2} [APM]{2})$/);
    if (!match) return input;

	let date = match[1]; // Extract the date

	let startTime = moment(match[2], "hh:mm A").format("HH:mm"); // Convert start time to 24-hour format

    return `${date} ${startTime}`;
}

function isToday(dateTimeStr) {
    // Parse the input using the known format
    const inputDate = moment(dateTimeStr, "DD-MM-YYYY");

    // Compare only the date (ignores time)
    return inputDate.isSame(moment(), 'day');
}

function isDateInArray(dateStr, dateArray) {
    // Convert input date to the array's format: YYYY-MM-DD
    const formatted = moment(dateStr, "DD-MM-YYYY").format("YYYY-MM-DD");

    return dateArray.includes(formatted);
}

function retrieve_booking_total_ajax( form_data ) {
	
	var condition = true;
	data_from = jQuery('#mwb-mbfw-booking-from-time').val();
	data_to =jQuery('#mwb-mbfw-booking-to-time').val();
	
	if ('twelve_hour' == mwb_mbfw_public_obj.wps_diaplay_time_format ) {
		data_from = (undefined == data_from ) ? undefined : convertTimeFormatDual( data_from );
		data_to = (undefined == data_to ) ? undefined :convertTimeFormatDual( data_to );
	}

	if ( data_from != undefined && data_to != undefined ){	
		var datesBetween = getDatesBetween(data_from, data_to);
		for (let index = 0; index < datesBetween.length; index++) {
			var originalDate = datesBetween[index];
			var upcoming_holiday = mwb_mbfw_public_obj.upcoming_holiday[0];
			var formattedDate = convertDateFormat(originalDate);
			
			if (upcoming_holiday.includes(formattedDate)) {
				condition = false;
			}
		}
	}
	if ( $('.mwb-mbfw-total-area').length > 0 && condition == true ) {

		form_data.append('action', 'mbfw_retrieve_booking_total_single_page');
		form_data.append('nonce', mwb_mbfw_common_obj.nonce);
		jQuery.ajax({
			url         : mwb_mbfw_common_obj.ajax_url,
			method      : 'post',
			data        : form_data,
			processData : false,
			contentType : false,
			success     : function( msg ) {
			
				var str1 = msg;
			
				var str2 = "rror establishing a database connectio";
				if(str1.indexOf(str2) != -1){
					msg = '';
				}
				if (msg == 'fail'){
					if ( $('#alert_msg_client').val() == undefined){
						jQuery('.mwb-mbfw-cart-page-data').append('<span id="alert_msg_client" style="color:red">'+mwb_mbfw_common_obj.holiday_alert+'</span>')		
						$('#mwb-mbfw-booking-to-time').val('');
						return;
					}
				} else{
					if ( $('#alert_msg_client').val() != undefined){

						setTimeout(function(){ 
							$('#alert_msg_client').remove();
						}, 4000);
					
					}
				}
				$('.mwb-mbfw-total-area').html(msg);

			}
		});
	} else{
		
		if ( condition == false ){
			if ( $('#alert_msg_client').val() == undefined){
				jQuery('.mwb-mbfw-cart-page-data').append('<span id="alert_msg_client" style="color:red">'+mwb_mbfw_common_obj.holiday_alert+'</span>')		
				$('#mwb-mbfw-booking-to-time').val('');
			}
		}
	}
}


function getDatesBetween(startDate, endDate) {
    var dates = [];
    var currentDate = parseDate(startDate);
    endDate = parseDate(endDate);
    
    while (currentDate <= endDate) {
        dates.push(formatDate(currentDate));
        currentDate.setDate(currentDate.getDate() + 1);
    }
    
    return dates;
}

function parseDate(dateString) {
    var parts = dateString.split("-");
    return new Date(parts[2], parts[1] - 1, parts[0]);
}

function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();
    return pad(day) + "-" + pad(month) + "-" + year;
}

function pad(number) {
    return number < 10 ? "0" + number : number;
}

function convertDateFormat(dateString) {
    var parts = dateString.split("-");
    return parts[2] + "-" + parts[1] + "-" + parts[0];
}

function timeChangeLanguage( lang ){
	const amPmMap = {
		'default': { 'AM': 'AM', 'PM': 'PM' },
		'ar': { 'ص': 'AM', 'م': 'PM' }, // Arabic
		'at': { 'AM': 'AM', 'PM': 'PM' }, // Austria (uses similar to English)
		'az': { 'AM': 'AM', 'PM': 'PM' }, // Azerbaijani
		'be': { 'AM': 'AM', 'PM': 'PM' }, // Belarusian
		'bg': { 'AM': 'AM', 'PM': 'PM' }, // Bulgarian
		'bn': { 'AM': 'AM', 'PM': 'PM' }, // Bengali
		'bs': { 'AM': 'AM', 'PM': 'PM' }, // Bosnian
		'cat': { 'AM': 'AM', 'PM': 'PM' }, // Catalan
		'cs': { 'AM': 'AM', 'PM': 'PM' }, // Czech
		'cy': { 'AM': 'AM', 'PM': 'PM' }, // Welsh
		'da': { 'AM': 'AM', 'PM': 'PM' }, // Danish
		'de': { 'AM': 'AM', 'PM': 'PM' }, // German
		'eo': { 'AM': 'AM', 'PM': 'PM' }, // Esperanto
		'es': { 'AM': 'AM', 'PM': 'PM' }, // Spanish
		'et': { 'AM': 'AM', 'PM': 'PM' }, // Estonian
		'fa': { 'AM': 'AM', 'PM': 'PM' }, // Persian
		'fi': { 'AM': 'AM', 'PM': 'PM' }, // Finnish
		'fr': { 'AM': 'AM', 'PM': 'PM' }, // French
		'gr': { 'AM': 'AM', 'PM': 'PM' }, // Greek
		'he': { 'AM': 'AM', 'PM': 'PM' }, // Hebrew
		'hi': { 'पूर्वाह्न': 'AM', 'अपराह्न': 'PM' }, // Hindi
		'hr': { 'AM': 'AM', 'PM': 'PM' }, // Croatian
		'hu': { 'AM': 'AM', 'PM': 'PM' }, // Hungarian
		'id': { 'AM': 'AM', 'PM': 'PM' }, // Indonesian
		'is': { 'AM': 'AM', 'PM': 'PM' }, // Icelandic
		'it': { 'AM': 'AM', 'PM': 'PM' }, // Italian
		'ja': { '午前': 'AM', '午後': 'PM' }, // Japanese
		'ka': { 'AM': 'AM', 'PM': 'PM' }, // Georgian
		'km': { 'AM': 'AM', 'PM': 'PM' }, // Khmer
		'ko': { 'AM': 'AM', 'PM': 'PM' }, // Korean
		'kz': { 'AM': 'AM', 'PM': 'PM' }, // Kazakh
		'lt': { 'AM': 'AM', 'PM': 'PM' }, // Lithuanian
		'lv': { 'AM': 'AM', 'PM': 'PM' }, // Latvian
		'mk': { 'AM': 'AM', 'PM': 'PM' }, // Macedonian
		'mn': { 'AM': 'AM', 'PM': 'PM' }, // Mongolian
		'my': { 'AM': 'AM', 'PM': 'PM' }, // Burmese
		'nl': { 'AM': 'AM', 'PM': 'PM' }, // Dutch
		'no': { 'AM': 'AM', 'PM': 'PM' }, // Norwegian
		'pa': { 'AM': 'AM', 'PM': 'PM' }, // Punjabi
		'pl': { 'AM': 'AM', 'PM': 'PM' }, // Polish
		'pt': { 'AM': 'AM', 'PM': 'PM' }, // Portuguese
		'ro': { 'AM': 'AM', 'PM': 'PM' }, // Romanian
		'ru': { 'AM': 'AM', 'PM': 'PM' }, // Russian
		'si': { 'AM': 'AM', 'PM': 'PM' }, // Sinhala
		'sk': { 'AM': 'AM', 'PM': 'PM' }, // Slovak
		'sl': { 'AM': 'AM', 'PM': 'PM' }, // Slovenian
		'sq': { 'AM': 'AM', 'PM': 'PM' }, // Albanian
		'sr': { 'AM': 'AM', 'PM': 'PM' }, // Serbian
		'sv': { 'AM': 'AM', 'PM': 'PM' }, // Swedish
		'th': { 'AM': 'AM', 'PM': 'PM' }, // Thai
		'tr': { 'AM': 'AM', 'PM': 'PM' }, // Turkish
		'uk': { 'AM': 'AM', 'PM': 'PM' }, // Ukrainian
		'uz': { 'AM': 'AM', 'PM': 'PM' }, // Uzbek
		'vn': { 'AM': 'AM', 'PM': 'PM' }, // Vietnamese
		'zh': { 'AM': 'AM', 'PM': 'PM' }, // Chinese (Simplified)
	};
	 return amPmMap[lang];
	
}