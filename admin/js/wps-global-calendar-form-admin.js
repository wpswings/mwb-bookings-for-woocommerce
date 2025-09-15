jQuery(document).ready(function($) {
    // Copy shortcode
    // debugger;
    $(document).on('click', '.wps-global-calendar-copy-btn', function() {
        let targetId = $(this).data('target');
        let $input = $('#' + targetId);
        $input.select();
        document.execCommand('copy');

        $(this).text('Copied!');
        setTimeout(() => $(this).text('Copy'), 1500);
    });
    
    // Show/hide options field
    $(document).on("change", ".wps-global-calendar-field-type", function () {
        let $row = $(this).closest("tr");
        let $optionsInput = $row.find(".wps-global-calendar-options-input");
        if (["select", "multiselect", "checkbox", "radio"].includes($(this).val())) {
            $optionsInput.show();
        } else {
            $optionsInput.hide();
        }
    });

    // Add new field row
    $('#wps-global-calendar-add-field').on('click', function(e) {debugger;
        e.preventDefault();
        let index = $("#wps-global-calendar-fields-table tbody tr").length; // count existing rows

        let fieldHTML = `
        <tr class="wps-global-calendar-field-row">
        		<td><img src="${mwb_mbfw_global_form_obj.wps_plugin_url}admin/image/drag.png" class="form-drag-icon" alt="drag-icon"></td>

                <td><input type="text" name="wps_global_calendar_fields[${index}][label]" placeholder="Field Label" /></td>
                <td><select name="wps_global_calendar_fields[${index}][type]" class="wps-global-calendar-field-type">
                    <option value="text">Text</option>
                    <option value="email">Email</option>
                    <option value="textarea">Textarea</option>
                    <option value="number">Number</option>
                    <option value="select">Select</option>
                    <option value="multiselect">Multiselect</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="radio">Radio</option>
                    <option value="date">Date</option>
                </select></td>
                <td><input type="text" name="wps_global_calendar_fields[${index}][options]" class="wps-global-calendar-options-input" placeholder="Comma separated options" style="display:none;" /></td>`;
                if(mwb_mbfw_global_form_obj.is_pro_active === 'yes'){

                    fieldHTML += `<td><label style="margin-left:10px;"><input type="checkbox" name="wps_global_calendar_fields[${index}][required]" value="1" />Required</label></td>`;
                }
               
                fieldHTML += `<td style="text-align:center;">
                    <button type="button" class="button wps-remove-field">Delete</button>
                </td>
            </tr>
        `;

        $("#wps-global-calendar-fields-table tbody").append(fieldHTML);

    });
        // Delete row
    $(document).on("click", ".wps-remove-field", function () {
        $(this).closest("tr").remove();
        // refreshRowIndexes();
    });
});
