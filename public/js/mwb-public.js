jQuery(document).ready(function($){
    jQuery('.mwb-mbfw-user-booking-meta-data-listing').slideUp();
    jQuery('.mwb-mbfw-ser-booking-toggler').on('click',function(){
        jQuery(this).toggleClass('booking-toggler-reverse');
        jQuery(this).siblings('.mwb-mbfw-user-booking-meta-data-listing').slideToggle('slow');
    })

    if( mwb_mbfw_public_obj.daily_start_time != '' && mwb_mbfw_public_obj.daily_end_time != '' ) {
        
        $('.mwb_mbfw_time_date_picker_frontend').datetimepicker({
            format  : 'd-m-Y H:i',
            minTime: mwb_mbfw_public_obj.daily_start_time,
            maxTime : mwb_mbfw_public_obj.daily_end_time + 1,
            // minTime : mwb_mbfw_common_obj.minTime
        });
    }
    var upcoming_holiday = mwb_mbfw_public_obj.upcoming_holiday;
    if( upcoming_holiday.length > 0 ){
        
			
        function disableSpecificDate(date) {
            
            // To disable specific day
            var dateArr = [String(date.getFullYear()), String(date.getMonth() + 1), String(date.getDate())];
            if (dateArr[1].length == 1) dateArr[1] = "0" + dateArr[1];
            if (dateArr[2].length == 1) dateArr[2] = "0" + dateArr[2];
            return upcoming_holiday.indexOf(dateArr.join("-")) == -1;
        }
        jQuery("#mwb-mbfw-booking-from-time").datetimepicker({
            beforeShowDay: function (date) {
                return [disableSpecificDate(date)];
            }
        });
        jQuery("#mwb-mbfw-booking-to-time").datetimepicker({
            beforeShowDay: function (date) {
                return [disableSpecificDate(date)];
            }
        });
        

    }
});