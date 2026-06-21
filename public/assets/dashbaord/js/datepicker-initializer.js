/**
 * GLOBAL DATEPICKER INITIALIZER (Bootstrap Datepicker)
 * Unified System Standard for PTC System
 */

$(document).ready(function () {
    const isRtl = $('html').attr('dir') === 'rtl' || $('html').attr('data-textdirection') === 'rtl' || $('html').attr('lang') === 'ar';
    const currentLoc = ($('html').attr('lang') === 'ar' || isRtl) ? 'ar' : 'en';

    /**
     * Core Initialization Function for PTC Dashboard UI
     * @param {string|HTMLElement} container - Optional scope for initialization
     */
    window.initPTCUI = function (container) {

        const $scope = container ? $(container) : $(document);

        // 1. Standard Date Pickers (.ptc-datepicker)
        $scope.find(".ptc-datepicker").not('.dp-initialized').each(function () {
            const $el = $(this);
            $el.datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                language: currentLoc,
                rtl: isRtl,
                orientation: "bottom auto",
                templates: {
                    leftArrow: isRtl ? '&raquo;' : '&laquo;',
                    rightArrow: isRtl ? '&laquo;' : '&raquo;'
                }
            }).addClass('dp-initialized');
        });

        // 2. Month-Only Pickers (.ptc-monthpicker)
        $scope.find(".ptc-monthpicker").not('.dp-initialized').each(function () {
            const $el = $(this);
            $el.datepicker({
                format: "yyyy-mm",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true,
                language: currentLoc,
                rtl: isRtl,
                orientation: "bottom auto",
                templates: {
                    leftArrow: isRtl ? '&raquo;' : '&laquo;',
                    rightArrow: isRtl ? '&laquo;' : '&raquo;'
                }
            }).on('changeDate', function(e) {
                // Compatibility for Hidden Month/Year Filters
                if ($el.hasClass('js-month-year-filter')) {
                    const date = e.date;
                    if (date) {
                        const year = date.getFullYear();
                        const month = (date.getMonth() + 1).toString().padStart(2, '0');
                        $('#filter_year').val(year);
                        $('#filter_month').val(month);
                    } else {
                        $('#filter_year').val('');
                        $('#filter_month').val('');
                    }
                    // Trigger change for any listeners
                    $('#filter_year, #filter_month').trigger('change');
                }
            }).addClass('dp-initialized');
        });
    };

    // Auto-run on load
    initPTCUI();


    /**
     * Robust Modal Support: 
     * Bootstrap modals can sometimes block interaction or have z-index issues.
     * We re-init pickers when a modal is shown and fix focus issues.
     */
    $(document).on('shown.bs.modal', function (e) {
        initPTCUI(e.target);
    });

    // Handle Bootstrap Focus Trap for Modal
    $(document).on('focusin.modal', function(e) {
        if ($(e.target).closest('.datepicker').length) {
            e.stopImmediatePropagation();
        }
    });
});
