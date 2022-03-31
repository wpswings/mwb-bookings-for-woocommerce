jQuery(document).ready( function($) {

	const ajaxUrl  		 = localised.ajaxurl;
	const nonce    		 = localised.nonce;
	const action          = localised.wps_bfw_callback;

	const post_meta_count = localised.wps_post_meta_count;
	const pending_post_meta = localised.wps_pending_post_meta;

	const pending_term_meta = localised.wps_pending_term_meta;
	const term_meta_count = localised.wps_term_meta_count;

	const pending_user_count  = localised.wps_pending_user_count;
	const pending_user_meta = localised.wps_pending_user_meta;

	const pending_shortcode = localised.wps_pending_shortcode;
	const pending_shortcode_count = localised.wps_shortcode_pending_count;
	const pro_active = localised.wps_booking_pro_active;
	const pending_taxonomy = (localised.wps_pending_taxonomy);
	const pending_count  = localised.wps_pending_count;
	const pending_orders = localised.wps_pending_orders;
	const completed_orders = localised.wps_completed_orders;


	const searchHTML = '<style>input[type=number], select, numberarea{width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 6px; margin-bottom: 16px; resize: vertical;}input[type=submit]{background-color: #04AA6D; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer;}.container{border-radius: 5px; background-color: #f2f2f2; padding: 20px;}</style></head><div class="container"> <label for="ordername">Order Id</label> <input type="number" id="ordername" name="firstname" placeholder="Order ID to search.."></div>';

	/* Close Button Click */
	jQuery( document ).on( 'click','#wps_bfw_migration_button',function(e){
		e.preventDefault();
		Swal.fire({
			icon: 'warning',
			title: 'We Have got ' + post_meta_count + ' Booking post data!',
			text: 'Click to start import',
			footer: 'Please do not reload/close this page until prompted',
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:
			  '<i class="fa fa-thumbs-up"></i> Start',
			confirmButtonAriaLabel: 'Thumbs up',
			cancelButtonText:
			  '<i class="fa fa-thumbs-down">Cancel</i>',
			cancelButtonAriaLabel: 'Thumbs down'
		}).then((result) => {
			if (result.isConfirmed) {

				Swal.fire({
					title   : 'Bookings posts are being imported!',
					html    : 'Do not reload/close this tab.',
					footer  : '<span class="order-progress-report">' + post_meta_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
			
				startImportPosts( pending_post_meta );

			} else if (result.isDismissed) {
			  Swal.fire('Import Stopped', '', 'info');
			}
		})
	});

	const startImportPosts = ( posts ) => {
		

		var wps_event   = 'wps_bfw_import_single_post';
		var request = { action, wps_event, nonce, posts };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			posts = JSON.parse( response );
			
		})
		.then(
		function( posts ) {
			
			posts = JSON.parse( posts ).posts;
			if( ! jQuery.isEmptyObject(posts) ) {
				count = Object.keys(posts).length;
				jQuery('.order-progress-report').text( count + ' are left to import' );
				startImportPosts(posts);
			} else {

				Swal.fire({
					title   : 'Terms are being imported!',
					html    : 'Do not reload/close this tab.',
					footer  : '<span class="order-progress-report">' + post_meta_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				startImportTerms( pending_term_meta );
				
			}
		}, function(error) {
			console.error(error);
		});
	}

	const startImportTerms = ( terms ) => {
		var wps_event   = 'wps_bfw_import_single_term';
		var request = { action, wps_event, nonce, terms };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			terms = JSON.parse( response );
			
		})
		.then(
		function( terms ) {
			terms = JSON.parse( terms ).terms;
			if( ! jQuery.isEmptyObject(terms) ) {
				count = Object.keys(terms).length;
				jQuery('.order-progress-report').text( count + ' are left to import' );
				startImportTerms(terms);
			} else {
				// All orders imported!
				Swal.fire({
					title   : 'Users are being imported!',
					html    : 'Do not reload/close this tab.',
					footer  : '<span class="order-progress-report">' + post_meta_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				startImportUsers( pending_user_meta );
			}
		}, function(error) {
			console.error(error);
		});
	}

	const startImportUsers = ( users ) => {
		
		var wps_event   = 'wps_bfw_import_single_user';
		var request = { action, wps_event, nonce, users };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			users = JSON.parse( response );
		})
		.then(
		function( users ) {
			
			users = JSON.parse( users ).users;

			if( ! jQuery.isEmptyObject(users) ) {
				count = Object.keys(users).length;
				jQuery('.order-progress-report').text( count + ' are left to import' );
				startImportUsers(users);
			} else {
				// All orders imported!
				Swal.fire({
					title   : 'Taxonomies are being imported!',
					html    : 'Do not reload/close this tab.',
					// footer  : '<span class="order-progress-report">' + post_meta_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				startImportTaxonomys();

			}
		}, function(error) {
			console.error(error);
		});
	}
	const startImportTaxonomys = ( ) => {
		var wps_event   = 'wps_bfw_migrate_taxonomy_values';
		var request = { action, wps_event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			users = JSON.parse( response );
		})
		.then(
		function( users ) {
			users = JSON.parse( users ).users;
			if( ! jQuery.isEmptyObject(users) ) {
				Swal.fire({
					title   : 'Error occured',
				})
			} else {
				// All orders imported!
				Swal.fire({
					title   : 'options are being imported!',
					html    : 'Do not reload/close this tab.',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				startImportOptions();

			}
		}, function(error) {
			console.error(error);
		});
	}
	const startImportOptions = ( ) => {

		var wps_event   = 'wps_bfw_migrate_option_values';
		var request = { action, wps_event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			users = JSON.parse( response );
		})
		.then(
		function( users ) {
			users = JSON.parse( users ).users;
			if( ! jQuery.isEmptyObject(users) ) {
				Swal.fire({
					title   : 'Error occured',
				})
			} else {
				Swal.fire({
					title   : 'Orders are being imported!',
					html    : 'Do not reload/close this tab.',
					// footer  : '<span class="order-progress-report">' + post_meta_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				startImportOrderItemmeta();

			}
		}, function(error) {
			console.error(error);
		});
	}

	const startImportOrderItemmeta = ( ) => {
		var wps_event   = 'wps_bfw_migrate_order_itemmeta_values';
		var request = { action, wps_event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			users = JSON.parse( response );
		})
		.then(
		function( users ) {
			users = JSON.parse( users ).users;
			if( ! jQuery.isEmptyObject(users) ) {
				Swal.fire({
					title   : 'Error occured',
				})
			} else {
				Swal.fire({
					title   : 'Sessions are being imported!',
					html    : 'Do not reload/close this tab.',
					// footer  : '<span class="order-progress-report">' + post_meta_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
				startImportSession();
			}
		}, function(error) {
			console.error(error);
		});
	}
	const startImportSession = () => {

		var wps_event   = 'wps_bfw_migrate_sessions_values';
		var request = { action, wps_event, nonce };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			users = JSON.parse( response );
		})
		.then(
		function( users ) {
			users = JSON.parse( users ).users;
			if( ! jQuery.isEmptyObject(users) ) {
				Swal.fire({
					title   : 'Error occured',
				})
			} else { 
				// All orders imported!
				if (pro_active){
					Swal.fire({
						title   : 'Shortcode are being imported!',
						html    : 'Do not reload/close this tab.',
						footer  : '<span class="order-progress-report">' + pending_shortcode_count + ' are left to import',
						didOpen: () => {
							Swal.showLoading()
						}
					});
					startImportShortcode(pending_shortcode);
				} else {
					var wps_event   = 'wps_bfw_org_migration_complete';
					var request = { action, wps_event, nonce };
					jQuery.post( ajaxUrl , request ).done(function( response ){
						if(response){
							Swal.fire({
								title   : 'All Data are migrated successfully!',
							}).then(
								function(result){
									location.reload();
								}
							)
							
						}
						
					})

				}
			}
		}, function(error) {
			console.error(error);
		});
	}
	const startImportShortcode = ( shortcodes ) => {

		var wps_event   = 'wps_bfw_import_single_shortcode';
		var request = { action, wps_event, nonce, shortcodes };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			shortcodes = JSON.parse( response );
			
		})
		.then(
		function( shortcodes ) {
			shortcodes = JSON.parse( shortcodes ).shortcodes;

			if( ! jQuery.isEmptyObject(shortcodes) ) {
				count = Object.keys(shortcodes).length;
				jQuery('.order-progress-report').text( count + ' are left to import' );
				startImportShortcode(shortcodes);
			} else {
				var wps_event   = 'wps_bfw_pro_migration_complete';
				var request = { action, wps_event, nonce };
				jQuery.post( ajaxUrl , request ).done(function( response ){
					if(response){
						Swal.fire({
							title   : 'All Data are migrated successfully!',
						}).then(
							function(result){
								location.reload();
							}
						)
						
					}
					
				})

			}
		}, function(error) {
			console.error(error);
		});
	}
	// End of scripts.
});
