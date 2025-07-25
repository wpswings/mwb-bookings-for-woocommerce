jQuery(document).ready(function($) {

// plugin banner ajax.
	$(document).on( 'click', '#dismiss-banner', function(e){

		if ( 'yes' == mwb_mbfw_onboarding.is_pro_plugin_active ) {

			var data = {
				action    : 'wps_wpr_ajax_banner_action',
				wps_nonce : mwb_mbfw_onboarding.banner_nonce
			};
			$.ajax({
				url  : mwb_mbfw_onboarding.ajaxurl,
				type : "POST",
				data : data,
				success: function(response) {
                 

					window.location.reload();
				},
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                   
                }

			});
		} else {

			jQuery(document).find('.wps-offer-notice').hide();
		}
	});
    // End of scripts.
});