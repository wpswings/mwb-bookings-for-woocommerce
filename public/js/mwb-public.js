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
});