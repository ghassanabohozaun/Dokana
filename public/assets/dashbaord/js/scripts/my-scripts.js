// Global Select2 and UI Initializers
$(document).ready(function () {

    // Flasher RTL Auto-Configuration
    // Reads the [dir] attribute from <html> - no Blade logic needed
    if (typeof flasher !== 'undefined') {
        var isRtl = document.documentElement.getAttribute('dir') === 'rtl'
                 || document.documentElement.getAttribute('data-textdirection') === 'rtl';
        if (isRtl) {
            flasher.use('flasher').renderOptions({
                rtl: true,
                position: 'top-left'
            });
        }
    }


    // 1. Select2 Global Defaults (Integrated from datepicker-initializer)
    if (typeof $.fn.select2 !== 'undefined') {
        const isRtl = $('html').attr('dir') === 'rtl' || $('html').attr('data-textdirection') === 'rtl';
        $.fn.select2.defaults.set("dir", isRtl ? "rtl" : "ltr");
        $.fn.select2.defaults.set("width", "100%");
        
        // Apply Arabic translations if available via the bridge
        if (isRtl && typeof window.PTC_I18N !== 'undefined' && window.PTC_I18N.select2) {
            $.fn.select2.defaults.set("language", window.PTC_I18N.select2);
        }
    }

    // 2. General Delete Button Click Handler (Existing Logic)
    // General Delete Button Click Handler
    $("body").on("click", ".delete-confirm", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var url = $(this).data("route");
        var modelString = $(this).data("model"); // Optional: Generic name for the item being deleted
        var deleteMessage =
            $(this).data("message") ||
            "Are you sure you want to delete this record?";
        var deleteTitle = $(this).data("title") || "Are you sure?";
        var deleteText =
            $(this).data("text") || "You won't be able to revert this!";
        var confirmButtonText =
            $(this).data("confirm-btn") || "Yes, delete it!";
        var cancelButtonText = $(this).data("cancel-btn") || "Cancel";
        var successTitle = $(this).data("success-title") || "Deleted!";
        var successText =
            $(this).data("success-text") || "Your file has been deleted.";

        swal({
            title: deleteTitle,
            text: deleteText,
            icon: "warning",
            buttons: {
                cancel: {
                    text: cancelButtonText,
                    value: null,
                    visible: true,
                    className: "btn-premium-cancel",
                    closeModal: true,
                },
                confirm: {
                    text: confirmButtonText,
                    value: true,
                    visible: true,
                    className: "btn-premium-save",
                    closeModal: false,
                },
            },
        }).then((isConfirm) => {
            if (isConfirm) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        id: id,
                        _method: "POST",
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    beforeSend: function() {
                        if ($('#loading-indicator').length) {
                            $('#loading-indicator').show();
                        }
                    },
                    success: function (data) {
                        if (data.status === true) {
                            swal({
                                title: successTitle,
                                text: successText,
                                icon: "success",
                                timer: 2000,
                                buttons: false,
                            });
                            // Reload table div or datatable gracefully
                            if (typeof window.fetch_data === 'function') {
                                window.fetch_data(window.currentPage || 1);
                            } else {
                                var targetTable = $('#table_data').length ? '#table_data' : ($('#myTable').length ? '#myTable' : null);
                                if (targetTable) {
                                    $.ajax({
                                        url: location.href,
                                        type: 'GET',
                                        beforeSend: function() {
                                            if ($('#loading-indicator').length) {
                                                $('#loading-indicator').show();
                                            }
                                        },
                                        success: function(responseHtml) {
                                            $(targetTable).html(responseHtml);
                                            
                                            // Synchronize Browser URL with Actual Returned Page
                                            let activePageTxt = $(targetTable).find('.pagination .active span, .pagination .active a').first().text();
                                            let activePage = activePageTxt ? parseInt(activePageTxt) : 1; // If no pagination links, it must be page 1
                                            
                                            let urlParams = new URLSearchParams(window.location.search);
                                            let urlPage = parseInt(urlParams.get('page')) || 1;
                                            
                                            if (activePage !== urlPage) {
                                                if (activePage === 1) {
                                                    urlParams.delete('page');
                                                } else {
                                                    urlParams.set('page', activePage);
                                                }
                                                
                                                let newUrl = window.location.pathname;
                                                let qs = urlParams.toString();
                                                if (qs) newUrl += '?' + qs;
                                                
                                                window.history.pushState(null, "", newUrl);
                                            }

                                            if ($('#loading-indicator').length) {
                                                $('#loading-indicator').hide();
                                            }
                                        },
                                        error: function() {
                                            if ($('#loading-indicator').length) {
                                                $('#loading-indicator').hide();
                                            }
                                        }
                                    });
                                } else {
                                    location.reload();
                                }
                            }
                            if ($('#loading-indicator').length) {
                                $('#loading-indicator').hide();
                            }
                            if (typeof swal.stopLoading === 'function') {
                                swal.stopLoading();
                            }
                        } else {
                            var lang = document.documentElement.lang || 'ar';
                            var errorTitle = window.PremiumSettings ? window.PremiumSettings.messages.error : (lang === 'ar' ? "خطأ" : "Error");
                            var okBtn = lang === 'ar' ? "حسناً" : "OK";
                            
                            swal({
                                title: errorTitle,
                                text: data.message || (lang === 'ar' ? "هناك مشكلة" : "Something went wrong"),
                                icon: "error",
                                button: okBtn
                            });
                        }
                    },
                    error: function (xhr) {
                        if ($('#loading-indicator').length) {
                            $('#loading-indicator').hide();
                        }
                        
                        // Check if server returned a JSON error message
                        var lang = document.documentElement.lang || 'ar';
                        var errorTitle = window.PremiumSettings ? window.PremiumSettings.messages.error : (lang === 'ar' ? "خطأ" : "Error");
                        var okBtn = lang === 'ar' ? "حسناً" : "OK";
                        var defaultErrorMsg = lang === 'ar' ? "حدث خطأ ما" : "Something went wrong";
                        var accessDeniedMsg = window.PremiumSettings ? window.PremiumSettings.messages.access_denied : (lang === 'ar' ? "تم الرفض" : "Access Denied");

                        var errorMessage = defaultErrorMsg;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        if (typeof swal.stopLoading === 'function') {
                            swal.stopLoading();
                        }

                        if (xhr.status === 403) {
                            swal({
                                title: accessDeniedMsg,
                                text: "",
                                icon: "error",
                                button: okBtn
                            });
                        } else {
                            swal({
                                title: errorTitle,
                                text: errorMessage,
                                icon: "error",
                                button: okBtn
                            });
                        }
                    },
                });
            }
        });
    });
});
