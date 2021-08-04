jQuery(document).ready(function($){
    // var pattern =/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/;
    // $('#mwb-mbfw-single-booking-date-selector-from').datepicker({
    //     dateFormat : 'dd-mm-yy',
	// 	minDate    : mwb_mbfw_public_obj.today_date,
    // }).on('change', function(){
    //     if ( ! date_validator() ) {
    //         $('#mwb-mbfw-single-booking-date-selector-to').val('');
    //         $(this).css('border', '1px solid red');
    //         alert(mwb_mbfw_public_obj.wrong_order_date);
    //     }
    //     show_error(this);
    // }).on('focus', function(){
    //     $(this).css('border', '');
    // });
    // $('#mwb-mbfw-single-booking-date-selector-to').datepicker({
    //     dateFormat : 'dd-mm-yy',
	// 	minDate    : mwb_mbfw_public_obj.today_date,
    // }).on('change', function(){
    //     if ( ! date_validator() ) {
    //         $(this).val('');
    //         $(this).css('border', '1px solid red');
    //         alert(mwb_mbfw_public_obj.wrong_order_date);
    //     }
    //     show_error(this);
    // }).on('focus', function(){
    //     $(this).css('border', '');
    // });

    // function show_error( self ) {
    //     if ( ! pattern.test( $(self).val() ) ) {
    //         $(self).val('');
    //         $(self).css('border', '1px solid red');
    //     }
    // }
    // function date_validator() {
    //     var from_date = $('#mwb-mbfw-single-booking-date-selector-from').val();
    //     var to_date   = $('#mwb-mbfw-single-booking-date-selector-to').val();
    //     var from_date_date = moment(from_date, 'DD-MM-YYYY');
    //     var to_date_date   = moment(to_date, 'DD-MM-YYYY');
    //     if ( from_date_date.isAfter( to_date_date ) ) {
    //         return false;
    //     }
    //     return true;
    // }
});
// jQuery(document).on('change', '#mwb-mbfw-single-booking-time-selector-from', function(){
//     alert();
// });