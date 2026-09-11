/**
 * Dynamic Pricing — admin rule builder for mwb_booking products.
 *
 * Responsibilities:
 *  1. Show / hide the whole Dynamic Pricing section when "Hide General Cost" is toggled.
 *  2. Show / hide the rules list when "Enable Dynamic Pricing" is toggled.
 *  3. Add / remove repeatable rule rows.
 *  4. Initialise flatpickr (multiple-date mode) on each dates input.
 *  5. Enforce client-side validation: percentage 1–100, fixed ≥ 0.
 *  6. Update min/max and the hint text when Price Type changes.
 *
 * @package Mwb_Bookings_For_Woocommerce
 * @since   3.12.1
 */

/* global flatpickr */
(function ($) {
	'use strict';

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Initialise flatpickr in multiple-date mode on a single <input>.
	 * No-ops if flatpickr is unavailable or the input is already initialised.
	 *
	 * @param {HTMLInputElement} input
	 */
	function initDatepicker(input) {
		if (typeof flatpickr === 'undefined' || input._flatpickr) {
			return;
		}
		flatpickr(input, {
			mode: 'multiple',
			dateFormat: 'Y-m-d',
			conjunction: ', ', // Stored in the input exactly as the PHP save expects.
		});
	}

	/**
	 * Sync the value input's min/max attributes and hint text to match the
	 * currently selected price type in the same rule row.
	 *
	 * @param {jQuery} $rule  The .mwb-dp-rule wrapper element.
	 */
	function syncValueConstraints($rule) {
		var type  = $rule.find('.mwb-dp-type').val();
		var $val  = $rule.find('.mwb-dp-value');
		var $hint = $rule.find('.mwb-dp-value-hint');

		if (type === 'percentage') {
			$val.attr('min', '1').attr('max', '100');
			$hint.text('(1–100 %)');
		} else {
			$val.attr('min', '0').removeAttr('max');
			$hint.text('');
		}
	}

	/**
	 * Re-index all rule rows so that their `name` attributes stay sequential
	 * after a row is removed.  Replaces _dynamic_pricing_rules[N] with the
	 * correct zero-based index.
	 */
	function reIndex() {
		$('#mwb-dp-rules-list .mwb-dp-rule').each(function (idx) {
			$(this).find('[name]').each(function () {
				var updated = $(this).attr('name').replace(
					/_dynamic_pricing_rules\[[^\]]+\]/,
					'_dynamic_pricing_rules[' + idx + ']'
				);
				$(this).attr('name', updated);
			});
		});
	}

	// -------------------------------------------------------------------------
	// Section / rules visibility
	// -------------------------------------------------------------------------

	/**
	 * Show the Dynamic Pricing section only when General Cost is not hidden.
	 */
	function syncSectionVisibility() {
		if ($('#mwb_mbfw_booking_general_cost_hide').is(':checked')) {
			$('#mwb-dp-section').hide();
		} else {
			$('#mwb-dp-section').show();
		}
	}

	/**
	 * Show the rules block only when the "Enable Dynamic Pricing" checkbox is on.
	 */
	function syncRulesVisibility() {
		if ($('#_dynamic_pricing_enabled').is(':checked')) {
			$('#mwb-dp-rules-wrap').show();
		} else {
			$('#mwb-dp-rules-wrap').hide();
		}
	}

	// -------------------------------------------------------------------------
	// DOM Ready
	// -------------------------------------------------------------------------

	$(function () {

		// Initialise flatpickr on any rule rows already rendered by PHP.
		$('#mwb-dp-rules-list .mwb-dp-dates').each(function () {
			initDatepicker(this);
		});

		// Apply initial visibility.
		syncSectionVisibility();
		syncRulesVisibility();

		// "Hide General Cost" toggled → show/hide entire dynamic pricing section.
		$(document).on('change', '#mwb_mbfw_booking_general_cost_hide', syncSectionVisibility);

		// "Enable Dynamic Pricing" toggled → show/hide the rules list.
		$(document).on('change', '#_dynamic_pricing_enabled', syncRulesVisibility);

		// Price Type changed → update min/max and hint on the same row.
		$(document).on('change', '.mwb-dp-type', function () {
			syncValueConstraints($(this).closest('.mwb-dp-rule'));
		});

		// "Add Rule" — clone the template, stamp the index, append, then wire up.
		$(document).on('click', '#mwb-dp-add-rule', function () {
			var template = document.getElementById('mwb-dp-rule-template');
			if (!template) {
				return;
			}

			// Next sequential index = current row count.
			var idx  = $('#mwb-dp-rules-list .mwb-dp-rule').length;
			var html = template.innerHTML.replace(/__IDX__/g, String(idx));
			var $row = $(html);

			$('#mwb-dp-rules-list').append($row);

			// Wire up flatpickr on the new dates input.
			$row.find('.mwb-dp-dates').each(function () {
				initDatepicker(this);
			});

			// Ensure the new row's value field has correct constraints.
			syncValueConstraints($row);
		});

		// "Remove" — delete the row, then re-index remaining rows.
		$(document).on('click', '.mwb-dp-remove-rule', function () {
			$(this).closest('.mwb-dp-rule').remove();
			reIndex();
		});

		// Client-side value validation on blur (mirrors server-side clamping).
		$(document).on('blur', '.mwb-dp-value', function () {
			var $rule = $(this).closest('.mwb-dp-rule');
			var type  = $rule.find('.mwb-dp-type').val();
			var val   = parseFloat($(this).val());

			if (isNaN(val)) {
				return;
			}

			if (type === 'percentage') {
				if (val < 1)   { $(this).val(1); }
				if (val > 100) { $(this).val(100); }
			} else {
				if (val < 0) { $(this).val(0); }
			}
		});
	});

}(jQuery));
