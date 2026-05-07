(function($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $(document).ready(function() {
    const MDCText = mdc.textField.MDCTextField;
    const textField = [].map.call(
      document.querySelectorAll(".mdc-text-field"),
      function(el) {
        return new MDCText(el);
      }
    );
    const MDCRipple = mdc.ripple.MDCRipple;
    const buttonRipple = [].map.call(
      document.querySelectorAll(".mdc-button"),
      function(el) {
        return new MDCRipple(el);
      }
    );
    const MDCSwitch = mdc.switchControl.MDCSwitch;
    const switchControl = [].map.call(
      document.querySelectorAll(".mdc-switch"),
      function(el) {
        return new MDCSwitch(el);
      }
    );

    // Update hex display when color swatch input changes
    $(document).on('input', '.mwb-color-swatch-input', function() {
      var hexVal = $(this).val().toUpperCase();
      $('#' + $(this).attr('id') + '_hex_display').text(hexVal);
    });

    $(".mwb-password-hidden").click(function() {
      var cur_targetEl = $(this).siblings(".mwb-form__password");
      if (cur_targetEl.attr("type") == "text") {
        $(this).text('visibility');
        cur_targetEl.attr("type", "password");
      } else {
        $(this).text('visibility_off');
        cur_targetEl.attr("type", "text");
      }
    });

    const expertModal = document.querySelector(".mbfw-expert-modal");
    if (expertModal) {
      const expertForm = expertModal.querySelector("[data-mbfw-expert-form]");
      const expertFormPanel = expertModal.querySelector("[data-mbfw-expert-form-panel]");
      const expertState = expertModal.querySelector("[data-mbfw-expert-state]");
      const expertThankYou = expertModal.querySelector("[data-mbfw-expert-thank-you]");
      const expertThankYouMessage = expertModal.querySelector("[data-mbfw-expert-thank-you-message]");
      let expertRedirectTimeout = null;

      const clearExpertStatus = function() {
        if (!expertState) {
          return;
        }

        expertState.hidden = true;
        expertState.textContent = "";
        expertState.classList.remove("is-success", "is-error");
      };

      const setExpertStatus = function(type, message) {
        if (!expertState) {
          return;
        }

        expertState.hidden = false;
        expertState.textContent = message;
        expertState.classList.remove("is-success", "is-error");
        expertState.classList.add(type === "success" ? "is-success" : "is-error");
      };

      const clearExpertRedirect = function() {
        if (expertRedirectTimeout) {
          window.clearTimeout(expertRedirectTimeout);
          expertRedirectTimeout = null;
        }
      };

      const showExpertForm = function(clearRedirect = true) {
        if (clearRedirect) {
          clearExpertRedirect();
        }
        clearExpertStatus();
        if (expertForm) {
          expertForm.classList.remove("mbfw-expert-form--submitted");
        }
        if (expertFormPanel) {
          expertFormPanel.hidden = false;
        }
        if (expertThankYou) {
          expertThankYou.hidden = true;
          expertThankYou.setAttribute("aria-hidden", "true");
        }
        if (expertThankYouMessage) {
          expertThankYouMessage.textContent = "Thank you for submitting your request.";
        }
      };

      const showExpertThankYou = function(message) {
        clearExpertRedirect();
        clearExpertStatus();
        if (expertForm) {
          expertForm.classList.add("mbfw-expert-form--submitted");
        }
        if (expertFormPanel) {
          expertFormPanel.hidden = true;
        }
        if (expertThankYouMessage) {
          expertThankYouMessage.textContent = message || "Thank you for submitting your request.";
        }
        if (expertThankYou) {
          expertThankYou.hidden = false;
          expertThankYou.setAttribute("aria-hidden", "false");
        }
        expertRedirectTimeout = window.setTimeout(function() {
          window.location.href = mbfw_admin_param.reloadurl;
        }, 4000);
      };

      const openExpertModal = function() {
        showExpertForm();
        expertModal.hidden = false;
        expertModal.setAttribute("aria-hidden", "false");
        document.body.classList.add("mbfw-expert-modal-open");
      };

      const closeExpertModal = function() {
        expertModal.hidden = true;
        expertModal.setAttribute("aria-hidden", "true");
        document.body.classList.remove("mbfw-expert-modal-open");
        showExpertForm(false);
      };

      $(document)
        .off("click.mbfwExpertModalOpen", "[data-mbfw-open-expert-modal]")
        .on("click.mbfwExpertModalOpen", "[data-mbfw-open-expert-modal]", function(event) {
          event.preventDefault();
          openExpertModal();
        });

      $(document)
        .off("click.mbfwExpertModalClose", "[data-mbfw-close-expert-modal]")
        .on("click.mbfwExpertModalClose", "[data-mbfw-close-expert-modal]", function(event) {
          event.preventDefault();
          closeExpertModal();
        });

      $(document)
        .off("keydown.mbfwExpertModal")
        .on("keydown.mbfwExpertModal", function(event) {
          if (event.key === "Escape" && !expertModal.hidden) {
            closeExpertModal();
          }
        });

      $(document)
        .off("submit.mbfwExpertForm", "[data-mbfw-expert-form]")
        .on("submit.mbfwExpertForm", "[data-mbfw-expert-form]", function(event) {
          const formElement = this;
          const submitButton = formElement.querySelector("[data-mbfw-expert-submit]");
          const formData = new FormData(formElement);
          const payload = {};

          event.preventDefault();
          clearExpertStatus();

          formData.forEach(function(value, key) {
            const normalizedKey = key.replace(/\[\]$/, "");

            if (Object.prototype.hasOwnProperty.call(payload, normalizedKey)) {
              if (!Array.isArray(payload[normalizedKey])) {
                const currentValue = payload[normalizedKey];
                payload[normalizedKey] = [];
                payload[normalizedKey].push(currentValue);
              }
              payload[normalizedKey].push(value);
              return;
            }

            payload[normalizedKey] = value;
          });

          if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = submitButton.getAttribute("data-mbfw-submit-loading-label") || "Sending...";
          }

          $.ajax({
            url: mbfw_admin_param.ajaxurl,
            method: "POST",
            dataType: "json",
            data: {
              action: "mwb_mbfw_submit_talk_to_expert",
              nonce: mbfw_admin_param.talk_to_expert_nonce,
              form_data: JSON.stringify(payload),
            },
          })
            .done(function(response) {
              const message = response && response.data && response.data.message ? response.data.message : "Thank you for submitting your request.";

              if (response && response.success) {
                formElement.reset();
                showExpertThankYou(message);
                return;
              }

              setExpertStatus("error", message);
            })
            .fail(function(xhr) {
              const message = xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message
                ? xhr.responseJSON.data.message
                : "Something went wrong while submitting the form. Please try again.";

              setExpertStatus("error", message);
            })
            .always(function() {
              if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = submitButton.getAttribute("data-mbfw-submit-label") || "Submit Request";
              }
            });
        });
    }
  });

  $(window).load(function() {
    // add select2 for multiselect.
    if ($(document).find(".mwb-defaut-multiselect").length > 0) {
      $(document)
        .find(".mwb-defaut-multiselect")
        .select2();
    }
  });
})(jQuery);
