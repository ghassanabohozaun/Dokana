/**
 * Premium Generic AJAX Form Handler
 * Handles form submissions globally (Modals or Pages)
 */

$(document).ready(function () {
    // Generic AJAX Form Submission Handler
    $("body").on("submit", "form.ajax-form", function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr("action");
        let method = form.attr("method") || "POST";
        let formData = new FormData(this);

        // UI Elements
        let saveBtn = form.find('button[type="submit"]');
        let spinner = saveBtn.find(".spinner_loading");
        let btnIcon = saveBtn.find("i");

        // Custom Data Attributes (Configuration via HTML)
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

        // Reset previous errors
        form.find(
            ".error-text, .premium-error-alert-chip, .error-message-premium strong",
        ).text("");
        form.find(
            ".premium-input, .form-control, .select2-selection, .premium-input-wrapper",
        )
            .css("border-color", "")
            .removeClass("is-invalid-premium");

        $.ajax({
            url: url,
            type: method, // POST with _method=PUT supported via FormData
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                saveBtn.prop("disabled", true);
                if (spinner.length) {
                    spinner.removeClass("d-none");
                    saveBtn.find("i:not(.spinner_loading)").addClass("d-none");
                }
            },
            success: function (response) {
                if (response.status) {
                    // Show success message (prefer backend message if provided)
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
                            timer: 3000,
                            showConfirmButton: false,
                        });
                    }

                    // Trigger custom success event for additional logic
                    form.trigger("ajax-form-success", [response]);

                    // Handle Modal Closure & Reset
                    if (modalId) {
                        $("#" + modalId).modal("hide");
                        form[0].reset();
                        // Reset Select2 if exists
                        if (typeof $.fn.select2 !== "undefined") {
                            form.find("select").each(function () {
                                if ($(this).hasClass("js-autocomplete")) {
                                    $(this).val(null).empty().trigger("change");
                                } else {
                                    $(this).trigger("change");
                                }
                            });
                        }
                    }

                    // Handle Success Actions
                    if (successAction === "redirect" && redirectUrl) {
                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1500);
                    } else if (successAction === "reload-table") {
                        if ($(tableId).length) {
                            let $loader = $(".table-loader-overlay");
                            $.ajax({
                                url: window.location.href,
                                type: "GET",
                                beforeSend: function () {
                                    if ($loader.length)
                                        $loader.addClass("active");
                                    $(tableId).css("opacity", "0.6");
                                },
                                success: function (data) {
                                    $(tableId).html(data);
                                    $(tableId).css("opacity", "1");
                                    if ($loader.length)
                                        $loader.removeClass("active");

                                    // Synchronize Browser URL with Actual Returned Page
                                    let activePageTxt = $(tableId)
                                        .find(
                                            ".pagination .active span, .pagination .active a",
                                        )
                                        .first()
                                        .text();
                                    let activePage = activePageTxt
                                        ? parseInt(activePageTxt)
                                        : 1; // If no pagination links, it must be page 1

                                    let urlParams = new URLSearchParams(
                                        window.location.search,
                                    );
                                    let urlPage =
                                        parseInt(urlParams.get("page")) || 1;

                                    if (activePage !== urlPage) {
                                        if (activePage === 1) {
                                            urlParams.delete("page");
                                        } else {
                                            urlParams.set("page", activePage);
                                        }

                                        let newUrl = window.location.pathname;
                                        let qs = urlParams.toString();
                                        if (qs) newUrl += "?" + qs;

                                        window.history.pushState(
                                            null,
                                            "",
                                            newUrl,
                                        );
                                    }
                                },
                                error: function () {
                                    if ($loader.length)
                                        $loader.removeClass("active");
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
                        // Smart Key Mapping: name.ar -> name_ar, name.en -> name_en
                        let errorKey = key.replace(/\./g, "_");

                        // Target error text spans (support multiple naming conventions)
                        let errorLabel = form.find(
                            "." +
                                errorKey +
                                "_error, #" +
                                errorKey +
                                "_error, #" +
                                errorKey +
                                "_error_edit",
                        );
                        if (errorLabel.length) {
                            errorLabel.text(value[0]);
                        }

                        // Highlight inputs (support multiple naming conventions)
                        let inputField = form.find(
                            '[name="' +
                                key +
                                '"], #' +
                                errorKey +
                                ", #" +
                                errorKey +
                                "_edit, #" +
                                errorKey +
                                "_create",
                        );
                        if (inputField.length) {
                            inputField.addClass("is-invalid-premium");

                            // Highlight the wrapper for a better glow effect
                            inputField
                                .closest(".premium-input-wrapper")
                                .addClass("is-invalid-premium");

                            // Support for Select2
                            if (
                                inputField.hasClass("select2-hidden-accessible")
                            ) {
                                inputField
                                    .next(".select2-container")
                                    .find(".select2-selection")
                                    .addClass("is-invalid-premium");
                            }
                        }
                    });

                    // Get the first validation error message to show in toaster
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
                    spinner.addClass("d-none");
                    saveBtn.find("i:not(.spinner_loading)").removeClass("d-none");
                }
            },
        });
    });

    // Auto-reset forms when modals are closed
    $("body").on("hidden.bs.modal", ".modal", function () {
        let form = $(this).find("form.ajax-form");
        if (form.length) {
            form[0].reset();

            // Reset FileInput if exists
            if (typeof $.fn.fileinput !== "undefined") {
                form.find('input[type="file"]').fileinput("clear");
            }

            // Reset Select2 if exists
            if (typeof $.fn.select2 !== "undefined") {
                form.find("select").each(function () {
                    if ($(this).hasClass("js-autocomplete")) {
                        $(this).val(null).empty().trigger("change");
                    } else {
                        $(this).trigger("change");
                    }
                });
            }

            form.find(
                ".error-text, .premium-error-alert-chip, .error-message-premium strong",
            ).text("");
            form.find(
                ".premium-input, .form-control, .select2-selection, .premium-input-wrapper",
            )
                .css("border-color", "")
                .removeClass("is-invalid-premium");

            // Reset Buttons (Spinner/Icons)
            let saveBtn = form.find('button[type="submit"]');
            saveBtn.prop("disabled", false);
            saveBtn.find(".spinner_loading").addClass("d-none");
            saveBtn.find("i:not(.spinner_loading)").removeClass("d-none");
        }
    });
});
