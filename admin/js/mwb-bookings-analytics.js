/**
 * WPS Bookings Analytics JS
 *
 * Handles date-range filtering, AJAX data fetching, Chart.js rendering,
 * and CSV export for the Analytics admin tab.
 */
/* global Chart, wps_bfw_analytics */
(function ( $, Analytics ) {
	'use strict';

	var chartInstances = {};
	var currentData    = null;

	var COLORS = [
		'#4E73DF', '#1CC88A', '#36B9CC', '#F6C23E',
		'#E74A3B', '#8B5CF6', '#F97316', '#14B8A6',
		'#6366F1', '#EC4899', '#84CC16', '#EAB308',
	];

	/* ------------------------------------------------------------------ */
	/* Helpers                                                              */
	/* ------------------------------------------------------------------ */

	function formatCurrency( value ) {
		return Analytics.currency + parseFloat( value ).toFixed( 2 );
	}

	function formatPct( value ) {
		return parseFloat( value ).toFixed( 1 ) + '%';
	}

	function destroyChart( id ) {
		if ( chartInstances[ id ] ) {
			chartInstances[ id ].destroy();
			delete chartInstances[ id ];
		}
	}

	function makeChart( id, config ) {
		destroyChart( id );
		var ctx = document.getElementById( id );
		if ( ! ctx ) {
			return null;
		}
		if ( typeof Chart === 'undefined' ) {
			// eslint-disable-next-line no-console
			console.warn( 'WPS Analytics: Chart.js not loaded. Charts will not render.' );
			return null;
		}
		try {
			chartInstances[ id ] = new Chart( ctx, config );
		} catch ( e ) {
			// eslint-disable-next-line no-console
			console.error( 'WPS Analytics chart error for #' + id + ':', e );
			return null;
		}
		return chartInstances[ id ];
	}

	/* ------------------------------------------------------------------ */
	/* Date range presets                                                   */
	/* ------------------------------------------------------------------ */

	function pad( n ) {
		return String( n ).padStart( 2, '0' );
	}

	function dateStr( d ) {
		return d.getFullYear() + '-' + pad( d.getMonth() + 1 ) + '-' + pad( d.getDate() );
	}

	function getDateRange( preset ) {
		var now   = new Date();
		var today = dateStr( now );
		var from, to;

		switch ( preset ) {
			case 'today':
				from = today;
				to   = today;
				break;

			case 'this_week': {
				var day = now.getDay(); // 0=Sun
				var diff = now.getDate() - day + ( day === 0 ? -6 : 1 );
				var mon = new Date( now.setDate( diff ) );
				from = dateStr( mon );
				to   = today;
				break;
			}

			case 'last_30': {
				var d30 = new Date();
				d30.setDate( d30.getDate() - 29 );
				from = dateStr( d30 );
				to   = today;
				break;
			}

			case 'this_month': {
				from = now.getFullYear() + '-' + pad( now.getMonth() + 1 ) + '-01';
				to   = today;
				break;
			}

			case 'last_month': {
				var lm = new Date( now.getFullYear(), now.getMonth() - 1, 1 );
				var lme = new Date( now.getFullYear(), now.getMonth(), 0 );
				from = dateStr( lm );
				to   = dateStr( lme );
				break;
			}

			case 'this_year':
				from = now.getFullYear() + '-01-01';
				to   = today;
				break;

			case 'all_time':
				from = '2000-01-01';
				to   = today;
				break;

			default: // 'custom' — caller handles via inputs
				from = null;
				to   = null;
		}

		return { from: from, to: to };
	}

	/* ------------------------------------------------------------------ */
	/* Render functions                                                     */
	/* ------------------------------------------------------------------ */

	function renderStatCards( d ) {
		$( '#wps-stat-total-bookings' ).text( d.total_bookings );
		$( '#wps-stat-revenue' ).text( formatCurrency( d.total_revenue ) );
		$( '#wps-stat-cancellation' ).text( formatPct( d.cancellation_rate ) );
		$( '#wps-stat-pending' ).text( d.pending_count );
	}

	function renderStatusChart( d ) {
		var labels = [];
		var values = [];
		var colors = [];
		var statusColors = {
			completed:  '#1CC88A',
			processing: '#4E73DF',
			cancelled:  '#E74A3B',
			pending:    '#F6C23E',
			'on-hold':  '#36B9CC',
			refunded:   '#8B5CF6',
			failed:     '#F97316',
		};

		var i = 0;
		$.each( d.status_counts, function ( status, count ) {
			labels.push( status.charAt( 0 ).toUpperCase() + status.slice( 1 ).replace( '-', ' ' ) );
			values.push( count );
			colors.push( statusColors[ status ] || COLORS[ i % COLORS.length ] );
			i++;
		});

		makeChart( 'wps-chart-status', {
			type: 'doughnut',
			data: {
				labels: labels,
				datasets: [ {
					data:            values,
					backgroundColor: colors,
					borderWidth:     2,
					borderColor:     '#fff',
				} ],
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: { position: 'right', labels: { boxWidth: 12, font: { size: 12 } } },
				},
			},
		});
	}

	function renderProductRevenueChart( d ) {
		if ( ! d.revenue_by_product || ! d.revenue_by_product.length ) {
			return;
		}
		var labels = [];
		var values = [];
		$.each( d.revenue_by_product, function ( i, p ) {
			labels.push( p.name );
			values.push( p.revenue );
		});

		makeChart( 'wps-chart-product-revenue', {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [ {
					label:           'Revenue',
					data:            values,
					backgroundColor: COLORS.slice( 0, labels.length ),
					borderRadius:    4,
				} ],
			},
			options: {
				indexAxis:  'y',
				responsive: true,
				plugins: {
					legend: { display: false },
					tooltip: {
						callbacks: {
							label: function ( ctx ) {
								return ' ' + formatCurrency( ctx.parsed.x );
							},
						},
					},
				},
				scales: {
					x: {
						ticks: {
							callback: function ( v ) { return Analytics.currency + v; },
						},
					},
				},
			},
		});
	}

	function renderOverTimeChart( d ) {
		if ( ! d.bookings_over_time ) {
			return;
		}
		var labels = Object.keys( d.bookings_over_time );
		var values = Object.values( d.bookings_over_time );

		makeChart( 'wps-chart-over-time', {
			type: 'line',
			data: {
				labels: labels,
				datasets: [ {
					label:           'Bookings',
					data:            values,
					borderColor:     '#4E73DF',
					backgroundColor: 'rgba(78,115,223,.1)',
					fill:            true,
					tension:         0.35,
					pointRadius:     3,
					pointHoverRadius: 5,
				} ],
			},
			options: {
				responsive: true,
				plugins: { legend: { display: false } },
				scales: {
					x: { ticks: { maxTicksLimit: 12 } },
					y: { beginAtZero: true, ticks: { precision: 0 } },
				},
			},
		});
	}

	function renderPeakDaysChart( d ) {
		if ( ! d.peak_days ) {
			return;
		}
		var labels = Object.keys( d.peak_days );
		var values = Object.values( d.peak_days );
		var max    = Math.max.apply( null, values );

		var bgColors = values.map( function ( v ) {
			return v === max ? '#4E73DF' : 'rgba(78,115,223,.35)';
		});

		makeChart( 'wps-chart-peak-days', {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [ {
					label:           'Bookings',
					data:            values,
					backgroundColor: bgColors,
					borderRadius:    4,
				} ],
			},
			options: {
				responsive: true,
				plugins: { legend: { display: false } },
				scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
			},
		});
	}

	function renderTopProductsTable( d ) {
		var $tbody = $( '#wps-top-products-tbody' );
		$tbody.empty();

		if ( ! d.top_products || ! d.top_products.length ) {
			$tbody.append( '<tr><td colspan="3" class="wps-table-loading">' + Analytics.i18n.no_data + '</td></tr>' );
			return;
		}

		$.each( d.top_products, function ( i, p ) {
			var row = '<tr>' +
				'<td>' + $( '<span>' ).text( p.name ).html() + '</td>' +
				'<td>' + p.bookings + '</td>' +
				'<td class="wps-revenue-cell">' + formatCurrency( p.revenue ) + '</td>' +
				'</tr>';
			$tbody.append( row );
		});
	}

	/* --- Pro chart renders --- */

	function renderOccupancyChart( d ) {
		if ( ! d.pro || ! d.pro.occupancy_labels || ! d.pro.occupancy_labels.length ) {
			return;
		}
		makeChart( 'wps-chart-occupancy', {
			type: 'bar',
			data: {
				labels: d.pro.occupancy_labels,
				datasets: [ {
					label:           'Occupancy %',
					data:            d.pro.occupancy_values,
					backgroundColor: '#8B5CF6',
					borderRadius:    4,
				} ],
			},
			options: {
				responsive: true,
				plugins: { legend: { display: false } },
				scales: {
					y: {
						beginAtZero: true,
						max: 100,
						ticks: { callback: function ( v ) { return v + '%'; } },
					},
				},
			},
		});
	}

	function renderPeakHoursChart( d ) {
		if ( ! d.pro || ! d.pro.peak_hours ) {
			return;
		}
		var labels = [];
		var values = d.pro.peak_hours;
		for ( var h = 0; h < 24; h++ ) {
			labels.push( String( h ).padStart( 2, '0' ) + ':00' );
		}
		var max = Math.max.apply( null, values );
		var bgColors = values.map( function ( v ) {
			return v === max ? '#F97316' : 'rgba(249,115,22,.35)';
		});

		makeChart( 'wps-chart-peak-hours', {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [ {
					label:           'Bookings',
					data:            values,
					backgroundColor: bgColors,
					borderRadius:    3,
				} ],
			},
			options: {
				responsive: true,
				plugins: { legend: { display: false } },
				scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
			},
		});
	}

	function renderPeopleTypesChart( d ) {
		if ( ! d.pro || ! d.pro.people_type_totals ) {
			return;
		}
		var labels = Object.keys( d.pro.people_type_totals );
		var values = Object.values( d.pro.people_type_totals );
		if ( ! labels.length ) {
			return;
		}

		makeChart( 'wps-chart-people-types', {
			type: 'doughnut',
			data: {
				labels: labels,
				datasets: [ {
					data:            values,
					backgroundColor: COLORS.slice( 0, labels.length ),
					borderWidth:     2,
					borderColor:     '#fff',
				} ],
			},
			options: {
				responsive: true,
				plugins: {
					legend: { position: 'right', labels: { boxWidth: 12, font: { size: 12 } } },
				},
			},
		});
	}

	/* ------------------------------------------------------------------ */
	/* Master render                                                        */
	/* ------------------------------------------------------------------ */

	function render( d ) {
		currentData = d;
		renderStatCards( d );
		renderStatusChart( d );
		renderProductRevenueChart( d );
		renderOverTimeChart( d );
		renderPeakDaysChart( d );
		renderTopProductsTable( d );

		if ( Analytics.is_pro === '1' && d.pro ) {
			$( '#wps-stat-avg-occupancy' ).text( formatPct( d.pro.avg_occupancy ) );
			$( '#wps-stat-avg-people' ).text( d.pro.avg_people );
			$( '#wps-stat-peak-hour' ).text( d.pro.peak_hour );
			$( '#wps-stat-top-people' ).text( d.pro.top_people_type );

			renderOccupancyChart( d );
			renderPeakHoursChart( d );
			renderPeopleTypesChart( d );
		}
	}

	/* ------------------------------------------------------------------ */
	/* AJAX fetch                                                           */
	/* ------------------------------------------------------------------ */

	var SPINNER_HTML = '<span class="wps-analytics-spinner"></span> ' + Analytics.i18n.loading;

	function showLoading() {
		$( '#wps-analytics-content' ).hide();
		$( '#wps-analytics-loading' ).html( SPINNER_HTML ).show();
	}

	function hideLoading() {
		$( '#wps-analytics-loading' ).hide();
		$( '#wps-analytics-content' ).show();
	}

	function showError( msg ) {
		$( '#wps-analytics-loading' ).html( '<p style="color:#E74A3B;">' + msg + '</p>' ).show();
		$( '#wps-analytics-content' ).hide();
	}

	function fetchData( from, to ) {
		showLoading();

		$.ajax({
			url:      Analytics.ajax_url,
			type:     'POST',
			dataType: 'json',
			data: {
				action:    'wps_bfw_get_analytics_data',
				nonce:     Analytics.nonce,
				date_from: from,
				date_to:   to,
			},
			success: function ( response ) {
				if ( response && response.success ) {
					hideLoading();
					try {
						render( response.data );
					} catch ( e ) {
						// eslint-disable-next-line no-console
						console.error( 'WPS Analytics render error:', e );
						hideLoading();
					}
				} else {
					var msg = ( response && response.data && response.data.message )
						? response.data.message
						: Analytics.i18n.error;
					showError( msg );
				}
			},
			error: function ( xhr, status, err ) {
				// eslint-disable-next-line no-console
				console.error( 'WPS Analytics AJAX error:', status, err, xhr.responseText );
				// Try to parse a JSON error message from the response.
				var msg = Analytics.i18n.error;
				try {
					var parsed = JSON.parse( xhr.responseText );
					if ( parsed && parsed.data && parsed.data.message ) {
						msg = parsed.data.message;
					}
				} catch ( ignore ) {}
				showError( msg + ' (status: ' + xhr.status + ')' );
			},
		});
	}

	/* ------------------------------------------------------------------ */
	/* CSV export                                                           */
	/* ------------------------------------------------------------------ */

	function exportCSV() {
		if ( ! currentData || ! currentData.top_products || ! currentData.top_products.length ) {
			alert( Analytics.i18n.export_error );
			return;
		}

		var rows = [ [ 'Product', 'Bookings', 'Revenue (' + Analytics.currency + ')' ] ];
		$.each( currentData.top_products, function ( i, p ) {
			rows.push( [ p.name, p.bookings, p.revenue.toFixed( 2 ) ] );
		});

		var csv = rows.map( function ( r ) {
			return r.map( function ( cell ) {
				return '"' + String( cell ).replace( /"/g, '""' ) + '"';
			}).join( ',' );
		}).join( '\n' );

		var blob = new Blob( [ csv ], { type: 'text/csv;charset=utf-8;' } );
		var url  = URL.createObjectURL( blob );
		var a    = document.createElement( 'a' );
		a.href     = url;
		a.download = 'bookings-analytics.csv';
		document.body.appendChild( a );
		a.click();
		document.body.removeChild( a );
		URL.revokeObjectURL( url );
	}

	/* ------------------------------------------------------------------ */
	/* Init                                                                 */
	/* ------------------------------------------------------------------ */

	$( function () {
		if ( ! $( '.wps-analytics-wrap' ).length ) {
			return;
		}

		var $preset     = $( '#wps-analytics-preset' );
		var $fromInput  = $( '#wps-analytics-date-from' );
		var $toInput    = $( '#wps-analytics-date-to' );
		var $customRange = $( '.wps-custom-range' );

		function applyPreset( preset ) {
			if ( preset === 'custom' ) {
				$customRange.show();
				return;
			}
			$customRange.hide();
			var range = getDateRange( preset );
			if ( range.from && range.to ) {
				$fromInput.val( range.from );
				$toInput.val( range.to );
				fetchData( range.from, range.to );
			}
		}

		// Initialise with "this_month".
		applyPreset( $preset.val() );

		$preset.on( 'change', function () {
			applyPreset( $( this ).val() );
		});

		$( '#wps-analytics-filter-form' ).on( 'submit', function ( e ) {
			e.preventDefault();
			var from = $fromInput.val();
			var to   = $toInput.val();
			if ( from && to ) {
				fetchData( from, to );
			}
		});

		$( '#wps-btn-export-csv' ).on( 'click', function () {
			exportCSV();
		});
	});

})( jQuery, wps_bfw_analytics );
