jQuery(function($) {
    function toggleServiceQuantityFields($toggle) {
        var hasQuantity = $toggle.prop('checked');
        var $minimumQuantity = $('#mwb_mbfw_service_minimum_quantity');
        var $maximumQuantity = $('#mwb_mbfw_service_maximum_quantity');
        var $quantityFields = $minimumQuantity
            .closest('tr, .mwb-tax-field, .mwb-edit-field')
            .add($maximumQuantity.closest('tr, .mwb-tax-field, .mwb-edit-field'));

        $minimumQuantity.prop('disabled', !hasQuantity);
        $maximumQuantity.prop('disabled', !hasQuantity);
        $quantityFields.toggle(hasQuantity);
    }

    function validateServiceQuantityFields($changedField) {
        var minimumValue = $('#mwb_mbfw_service_minimum_quantity').val();
        var maximumValue = $('#mwb_mbfw_service_maximum_quantity').val();
        var minimumQuantity = minimumValue === '' ? '' : parseFloat(minimumValue);
        var maximumQuantity = maximumValue === '' ? '' : parseFloat(maximumValue);

        if ( minimumQuantity !== '' && minimumQuantity < 0 ) {
            alert(mbfw_product_ajax.service_quantity_negative);
            $('#mwb_mbfw_service_minimum_quantity').val('');
            return;
        }

        if ( maximumQuantity !== '' && maximumQuantity < 0 ) {
            alert(mbfw_product_ajax.service_quantity_negative);
            $('#mwb_mbfw_service_maximum_quantity').val('');
            return;
        }

        if ( minimumQuantity !== '' && maximumQuantity !== '' && minimumQuantity > maximumQuantity ) {
            alert(mbfw_product_ajax.service_quantity_invalid_range);
            $changedField.val('');
        }
    }

    $(document).on('change', '#mwb_mbfw_booking_criteria', function(){
        if ( $(this).val() == 'fixed_unit' ) {
            $('#mwb_mbfw_booking_count').removeAttr('disabled');
            $('#mwb_mbfw_maximum_booking_per_unit').attr('disabled', 'disabled');
        } else {
            $('#mwb_mbfw_booking_count').attr('disabled', 'disabled');
            $('#mwb_mbfw_maximum_booking_per_unit').removeAttr('disabled');
        }
    });
    $(document).on('change', '#mwb_mbfw_is_service_has_quantity, #mwb-edit-qty', function(){
        toggleServiceQuantityFields($(this));
    });
    $(document).on('change blur', '#mwb_mbfw_service_minimum_quantity, #mwb_mbfw_service_maximum_quantity', function(){
        validateServiceQuantityFields($(this));
    });
    $('#mwb_mbfw_is_service_has_quantity, #mwb-edit-qty').each(function() {
        toggleServiceQuantityFields($(this));
    });
    if ( $('#mwb_bfwp_order_statuses_to_cancel').length > 0 ) {
        $('#mwb_bfwp_order_statuses_to_cancel').select2();
    }
    $(document).on('change', '#mwb_mbfw_cancellation_allowed', function(){
        if ( $(this).prop('checked') ) {
            $('#mwb_bfwp_order_statuses_to_cancel').removeAttr('disabled');
        } else {
            $('#mwb_bfwp_order_statuses_to_cancel').attr('disabled', 'disabled');
        }
    });
});
