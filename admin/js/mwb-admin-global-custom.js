jQuery(document).ready(function($) {
    $(document).on('change', '#mwb_mbfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            $('#mwb_mbfw_booking_count').show();
        } else {
            $('#mwb_mbfw_booking_count').hide();
        }
    });
});