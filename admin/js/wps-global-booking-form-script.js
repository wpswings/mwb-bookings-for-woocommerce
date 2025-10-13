(function ($) {
  $(document).ready(function () {
    // 🔹 Grab post ID from hidden input
    const postId = $("#post_ID").val();
    const previewKey = "wpsFormPreview_" + postId;
    const headingKey = "wpsFormHeading_" + postId;

    function generateField(label, type, optionsStr = "") {
      let slug = label.toLowerCase().replace(/\s+/g, "_");
      let inputHtml = "";

      switch (type) {
        case "email":
          inputHtml = `<input type="email" id="${slug}" name="${slug}">`;
          break;
        case "number":
          inputHtml = `<input type="number" id="${slug}" name="${slug}">`;
          break;
        case "textarea":
          inputHtml = `<textarea id="${slug}" name="${slug}"></textarea>`;
          break;
        case "date":
          inputHtml = `<input type="date" id="${slug}" name="${slug}">`;
          break;
        case "select":
          inputHtml = `<select id="${slug}" name="${slug}">
            ${optionsStr
              .split(",")
              .map((opt) => opt.trim())
              .filter(Boolean)
              .map(
                (opt) => `<option value="${opt.toLowerCase()}">${opt}</option>`
              )
              .join("")}
          </select>`;
          break;
        case "multiselect":
          const multiOptions = optionsStr
            .split(",")
            .map((opt) => opt.trim())
            .filter(Boolean);
          inputHtml = `<select id="${slug}" name="${slug}[]" multiple size="${
            multiOptions.length
          }">
            ${multiOptions
              .map(
                (opt) => `<option value="${opt.toLowerCase()}">${opt}</option>`
              )
              .join("")}
          </select>`;
          break;
        case "checkbox":
          inputHtml = `<div class="wps-inline-options">
            ${optionsStr
              .split(",")
              .map((opt) => opt.trim())
              .filter(Boolean)
              .map(
                (opt) => `
              <div>
                <input type="checkbox" id="${slug}_${opt}" name="${slug}[]" value="${opt.toLowerCase()}">
                <label for="${slug}_${opt}">${opt}</label>
              </div>`
              )
              .join("")}
          </div>`;
          break;
        case "radio":
          inputHtml = `<div class="wps-inline-options">
            ${optionsStr
              .split(",")
              .map((opt) => opt.trim())
              .filter(Boolean)
              .map(
                (opt) => `
              <div>
                <input type="radio" id="${slug}_${opt}" name="${slug}" value="${opt.toLowerCase()}">
                <label for="${slug}_${opt}">${opt}</label>
              </div>`
              )
              .join("")}
          </div>`;
          break;
        default:
          inputHtml = `<input type="text" id="${slug}" name="${slug}">`;
      }

      return `
        <div class="wps-form_view-group">
          <label for="${slug}">${label}:</label>
          <div class="wps-form-field">${inputHtml}</div>
        </div>`;
    }

    function updateFormHeading() {
      const title = $("#wps_calendar_form_heading").val().trim() || "Untitled Form";
      $(".wps-form_view-heading").text(title);
      localStorage.setItem(headingKey, title);
    }

    function rebuildFormPreview() {
      let $wrapper = $(".wps-form_view-wrap-in");
      $wrapper.empty();

      $("#wps-global-calendar-fields-table tbody tr").each(function () {
        let label = $(this).find("input[type=text]").val().trim() || "Field";
        let type = $(this).find("select").val() || "text";
        let options =
          $(this).find(".wps-global-calendar-options-input").val() || "";
        $wrapper.append(generateField(label, type, options));
      });

      localStorage.setItem(previewKey, $wrapper.html());
      updateFormHeading();
    }

    // Events
    $(document).on("click", "#wps-global-calendar-add-field", function (e) {
      e.preventDefault();
      rebuildFormPreview();
    });

    $(document).on(
      "change keyup",
      "#wps-global-calendar-fields-table input[type=text], #wps-global-calendar-fields-table select, #wps-global-calendar-fields-table .wps-global-calendar-options-input",
      function () {
        rebuildFormPreview();
      }
    );

    $(document).on("click", ".wps-remove-field", function (e) {
      e.preventDefault();
      $(this).closest("tr").remove();
      rebuildFormPreview();
    });

    $(document).on("keyup change", "#wps_calendar_form_heading", function () {
      updateFormHeading();
    });

    // 🔹 Make rows sortable
    $("#wps-global-calendar-fields-table tbody").sortable({
      handle: "td",
      placeholder: "ui-state-highlight",
      stop: function () {
        rebuildFormPreview();
      },
    });

    // Restore per-post preview + heading
    (function restore() {
      // const savedPreview = localStorage.getItem(previewKey);
      const savedHeading = localStorage.getItem(headingKey);
      let $wrapper = $(".wps-form_view-wrap-in");
      const savedPreview = $wrapper.html();

      const $rows = $("#wps-global-calendar-fields-table tbody tr");

      if ($rows.length > 0) {
        // Table already has rows → rebuild from table
        rebuildFormPreview();
      } else if (savedPreview) {
        // No rows, but localStorage has preview → restore from it
        $(".wps-form_view-wrap-in").html(savedPreview);
      } else {
        // Nothing to restore → just build empty preview
        rebuildFormPreview();
      }

      if (savedHeading) {
        $(".wps-form_view-heading").text(savedHeading);
      } else {
        updateFormHeading();
      }
    })();
  });
})(jQuery);
