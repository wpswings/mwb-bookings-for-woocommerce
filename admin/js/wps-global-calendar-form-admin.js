jQuery(document).ready(function($) {
    
         var root = $(':root');
        root.css('--wps-primary-color', mwb_mbfw_global_form_obj.form_color );
    // Copy shortcode
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
        let $fieldType = $row.find(".wps_global_calendar_description");
        if (["select", "multiselect", "checkbox", "radio"].includes($(this).val())) {
            $optionsInput.show();
            $fieldType.hide();
        } else {
            $optionsInput.hide();
            $fieldType.show();
        }
    });

    // Add new field row
    $('#wps-global-calendar-add-field').on('click', function(e) {
        e.preventDefault();
        let index = $("#wps-global-calendar-fields-table tbody tr").length; // count existing rows

        let fieldHTML = `
        <tr class="wps-global-calendar-field-row">
        		<td><img src="${mwb_mbfw_global_form_obj.wps_plugin_url}admin/image/drag.png" class="form-drag-icon" alt="drag-icon"></td>

                <td><input type="text" name="wps_global_calendar_fields[${index}][label]" class="wps_global_input_form_field_name" placeholder="${mwb_mbfw_global_form_obj.Field_label}" /></td>
                <td><select name="wps_global_calendar_fields[${index}][type]" class="wps-global-calendar-field-type">
                    <option value="text">${mwb_mbfw_global_form_obj.Text}</option>
                    <option value="email">${mwb_mbfw_global_form_obj.Email}</option>
                    <option value="textarea">${mwb_mbfw_global_form_obj.Textarea}</option>
                    <option value="number">${mwb_mbfw_global_form_obj.Number}</option>
                    <option value="select">${mwb_mbfw_global_form_obj.Select}</option>
                    <option value="multiselect">${mwb_mbfw_global_form_obj.Multiselect}</option>
                    <option value="checkbox">${mwb_mbfw_global_form_obj.Checkbox}</option>
                    <option value="radio">${mwb_mbfw_global_form_obj.Radio}</option>
                    <option value="date">${mwb_mbfw_global_form_obj.Date}</option>
                </select></td>
                
                <td><span class="wps_global_calendar_description">-</span>
                <input type="text" name="wps_global_calendar_fields[${index}][options]" class="wps-global-calendar-options-input" placeholder="Comma separated options" style="display:none;" />
                </td>`;
                if(mwb_mbfw_global_form_obj.is_pro_active === 'yes'){
                    if(bfwp_admin_pro_global.licence_valid || bfwp_admin_pro_global.day_count > 0 ) {

                    fieldHTML += `<td><label style="margin-left:10px;"><input type="checkbox" name="wps_global_calendar_fields[${index}][required]" value="1" />${mwb_mbfw_global_form_obj.Required}</label></td>`;
                }}
               
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
    $('#post').on('submit', function(e) {
        var valid = true;
        var valid2 = true;
        var title = $('#title').val().trim();
        if (title === '') {
            e.preventDefault();
            alert('The Form title field cannot be empty.');
            $('#title').focus();
            return false;
        }
        
        var heading = $('#wps_calendar_form_heading').val().trim();
        if (heading === '') {
            e.preventDefault();
            alert('The heading field cannot be empty.');
            $('#wps_calendar_form_heading').focus();
            return false;
        }

        $('.wps_global_input_form_field_name').each(function() {
            if ($.trim($(this).val()) === '') {
                
                valid = false;
                $(this).css('border', '2px solid red'); // highlight empty
            } else {
                $(this).css('border', ''); // reset if filled
            }
        });

        $('.wps-global-calendar-options-input').each(function() {
            if ($.trim($(this).val()) === '') {                
                if($(this).is(':hidden')){
                    return;

                }
                valid2 = false;
                $(this).css('border', '2px solid red'); // highlight empty
            } else {
                $(this).css('border', ''); // reset if filled
            }
        });
        
        if (!valid) {
            e.preventDefault(); // stop form submit
            
            alert(mwb_mbfw_global_form_obj.field_empty_msg);
            return false;
        }

        
        if (!valid2) {
            e.preventDefault(); // stop form submit
            alert(mwb_mbfw_global_form_obj.option_empty_msg);
            return false;
        }
    });
     $('#wps_calendar_form_color').on('input', function() {
        root.css('--wps-primary-color', $(this).val() );
        });
});
