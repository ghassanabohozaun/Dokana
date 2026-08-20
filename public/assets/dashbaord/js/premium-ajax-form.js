/**
 * Dokana Enterprise - Premium Generic AJAX Form & Modal Lifecycle Manager
 * Handles form submissions, validation errors, and universal modal state resets globally.
 */

$(document).ready(function () {
    // 1. Generic AJAX Form Submission Handler
    $("body").on("submit", "form.ajax-form", function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr("action");
        let method = form.attr("method") || "POST";
        let formData = new FormData(this);

        // UI Elements
        let saveBtn = form.find('button[type="submit"]');
        let spinner = saveBtn.find(".spinner_loading");

        // Custom Data Attributes
        let successAction = form.data("success-action") || "reload-table"; // 'reload-table' or 'redirect'
        let redirectUrl = form.data("redirect-url") || "";
        let tableId = form.data("table-id") || "#table_data";

        // Custom Messages with Global Fallbacks
        let successMsg =
            form.data("success-msg") ||
            (window.PremiumSettings
                ? window.PremiumSettings.messages.success
                : "Operation completed successfully.");
        let errorMsg =
            form.data("error-msg") ||
            (window.PremiumSettings
                ? window.PremiumSettings.messages.error
                : "An error occurred.");
        let validationMsg =
            form.data("validation-msg") ||
            (window.PremiumSettings
                ? window.PremiumSettings.messages.validation_error
                : "Please check the form for errors.");
        let accessDeniedMsg =
            form.data("access-denied-msg") ||
            (window.PremiumSettings
                ? window.PremiumSettings.messages.access_denied
                : "Access Denied.");

        // Auto-detect if form is inside a modal
        let modal = form.closest(".modal");
        let modalId = modal.length ? modal.attr("id") : null;

        // Reset previous errors before submission
        form.find(".error-text, .invalid-feedback, [class*='_error'], [id*='_error']").empty().text("");
        form.find(".form-input-modern, .premium-input, .form-control, .select2-selection, .premium-input-wrapper, input, select, textarea")
            .removeClass("is-invalid is-invalid-premium border-rose-500 border-danger");

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                saveBtn.prop("disabled", true);
                if (spinner.length) {
                    spinner.removeClass("d-none hidden");
                    saveBtn.find("i:not(.spinner_loading)").addClass("d-none hidden");
                }
            },
            success: function (response) {
                if (response.status) {
                    let finalMsg = response.message || successMsg;
                    if (typeof flasher !== "undefined") {
                        flasher.success(finalMsg);
                    } else if (typeof Swal !== "undefined") {
                        Swal.fire({
                            icon: "success",
                            title: window.PremiumSettings
                                ? window.PremiumSettings.messages.success
                                : "Success",
                            text: finalMsg,
                            timer: 2500,
                            showConfirmButton: false,
                        });
                    }

                    // Trigger custom success event for page-specific hooks
                    form.trigger("ajax-form-success", [response]);

                    // Close Modal if inside one
                    if (modalId) {
                        $("#" + modalId).modal("hide");
                    }

                    // Handle Table / Redirect Reload
                    if (successAction === "redirect" && redirectUrl) {
                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1200);
                    } else if (successAction === "reload-table") {
                        if (window.DokanaTable && typeof window.DokanaTable.fetchData === "function") {
                            window.DokanaTable.fetchData();
                        } else if ($(tableId).length) {
                            let $loader = $(".table-loader-overlay");
                            $.ajax({
                                url: window.location.href,
                                type: "GET",
                                beforeSend: function () {
                                    if ($loader.length) $loader.addClass("active");
                                    $(tableId).css("opacity", "0.6");
                                },
                                success: function (data) {
                                    $(tableId).html(data);
                                    $(tableId).css("opacity", "1");
                                    if ($loader.length) $loader.removeClass("active");
                                },
                                error: function () {
                                    if ($loader.length) $loader.removeClass("active");
                                    $(tableId).css("opacity", "1");
                                },
                            });
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    let finalError = response.message || errorMsg;
                    if (typeof flasher !== "undefined") {
                        flasher.error(finalError);
                    } else if (typeof Swal !== "undefined") {
                        Swal.fire({
                            icon: "error",
                            title: window.PremiumSettings
                                ? window.PremiumSettings.messages.error
                                : "Error",
                            text: finalError,
                        });
                    }
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        // Smart Key Mapping: name.ar -> name_ar, bank_accounts.0 -> bank_accounts_0
                        let errorKey = key.replace(/\./g, "_");

                        // Target error text spans (support all Dokana naming conventions)
                        let errorLabel = form.find(
                            "." + errorKey + "_error, #" + errorKey + "_error, #" + errorKey + "_error_edit, ." + key + "_error"
                        );
                        if (errorLabel.length) {
                            errorLabel.text(value[0]);
                        }

                        // Highlight inputs
                        let inputField = form.find(
                            '[name="' + key + '"], #' + errorKey + ", #" + errorKey + "_edit, #" + errorKey + "_create"
                        );
                        if (inputField.length) {
                            inputField.addClass("is-invalid is-invalid-premium border-rose-500");
                            inputField.closest(".premium-input-wrapper").addClass("is-invalid-premium border-rose-500");

                            // Select2 Highlight
                            if (inputField.hasClass("select2-hidden-accessible")) {
                                inputField
                                    .next(".select2-container")
                                    .find(".select2-selection")
                                    .addClass("is-invalid-premium border-rose-500");
                            }
                        }
                    });

                    // Toast message for first validation error
                    let firstErrorMsg = validationMsg;
                    if (errors && Object.keys(errors).length > 0) {
                        let firstKey = Object.keys(errors)[0];
                        firstErrorMsg = errors[firstKey][0];
                    }

                    if (typeof flasher !== "undefined") {
                        flasher.error(firstErrorMsg);
                    } else if (typeof Swal !== "undefined") {
                        Swal.fire({ icon: "error", title: validationMsg, text: firstErrorMsg });
                    }
                } else if (xhr.status === 403) {
                    if (typeof flasher !== "undefined") {
                        flasher.error(accessDeniedMsg);
                    } else if (typeof Swal !== "undefined") {
                        Swal.fire({ icon: "error", title: accessDeniedMsg });
                    }
                } else {
                    let serverMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : errorMsg;
                    if (typeof flasher !== "undefined") {
                        flasher.error(serverMsg);
                    } else if (typeof Swal !== "undefined") {
                        Swal.fire({ icon: "error", title: serverMsg });
                    }
                }
            },
            complete: function () {
                saveBtn.prop("disabled", false);
                if (spinner.length) {
                    spinner.addClass("d-none hidden");
                    saveBtn.find("i:not(.spinner_loading)").removeClass("d-none hidden");
                }
            },
        });
    });

    // 2. Real-time error clearing when user types or changes input
    $("body").on("input change", "form input, form select, form textarea", function () {
        let field = $(this);
        field.removeClass("is-invalid is-invalid-premium border-rose-500");
        field.closest(".premium-input-wrapper").removeClass("is-invalid-premium border-rose-500");
        if (field.hasClass("select2-hidden-accessible")) {
            field.next(".select2-container").find(".select2-selection").removeClass("is-invalid-premium border-rose-500");
        }

        let fieldName = field.attr("name") || field.attr("id");
        if (fieldName) {
            let errorKey = fieldName.replace(/\[/g, "_").replace(/\]/g, "").replace(/\./g, "_").replace(/_create|_edit/g, "");
            field.closest("form").find("." + errorKey + "_error, #" + errorKey + "_error, ." + fieldName + "_error").text("");
        }
    });

    /**
     * 3. Universal Centralized Modal Cleanup & Form Reset Lifecycle
     * Runs automatically on EVERY modal close in the entire platform.
     */
    function resetDokanaModal($modal) {
        if (!$modal || !$modal.length) return;

        // A. Clear all validation messages and red error highlights
        $modal.find(".error-text, .invalid-feedback, [class*='_error'], [id*='_error']").empty().text("");
        $modal.find(".form-input-modern, .premium-input, .form-control, .select2-selection, .premium-input-wrapper, input, select, textarea")
            .removeClass("is-invalid is-invalid-premium border-rose-500 border-danger");

        // B. Hide interactive balance/warning cards
        $modal.find("[id*='balance_info'], [class*='balance-info'], .exceeded-balance-warning, .alert-danger").addClass("hidden").removeClass("d-none");

        // C. Restore submit button and hide loading spinners
        let saveBtn = $modal.find('button[type="submit"]');
        if (saveBtn.length) {
            saveBtn.prop("disabled", false);
            saveBtn.find(".spinner_loading").addClass("d-none hidden");
            saveBtn.find("i:not(.spinner_loading)").removeClass("d-none hidden");
        }

        // D. Form-specific resets
        let form = $modal.find("form");
        if (form.length) {
            let modalId = ($modal.attr("id") || "").toLowerCase();
            let isEditModal = modalId.includes("edit") || (form.attr("id") || "").toLowerCase().includes("edit");

            if (!isEditModal) {
                // For CREATE Modals: Full form reset and Select2 reset
                form[0].reset();
                form.find('input[type="hidden"]:not([name="_token"]):not([name="_method"])').val("");

                // Reset Select2 fields in Create modal
                if (typeof $.fn.select2 !== "undefined") {
                    form.find("select.select2, select[data-toggle='select2']").each(function () {
                        let $select = $(this);
                        if ($select.hasClass("js-autocomplete")) {
                            $select.val(null).empty().trigger("change");
                        } else {
                            $select.val("").trigger("change.select2").trigger("change");
                        }
                    });
                }
            }
        }
    }

    // Attach to Bootstrap modal hidden events (covers backdrop clicks, ESC key, and programmatic close)
    $("body").on("hidden.bs.modal", ".modal", function () {
        resetDokanaModal($(this));
    });

    // Attach to manual close button triggers (X and Cancel buttons)
    $("body").on("click", "[data-dismiss='modal'], [data-bs-dismiss='modal'], .btn-close, .modal-close-btn", function () {
        let modal = $(this).closest(".modal");
        resetDokanaModal(modal);
    });

    // Clean errors before opening edit modals when clicking any edit button
    $("body").on("click", "[class*='edit'], [data-target*='edit'], [data-bs-target*='edit']", function () {
        let targetModalId = $(this).data("target") || $(this).data("bs-target");
        if (targetModalId && $(targetModalId).length) {
            resetDokanaModal($(targetModalId));
        }
    });
});
