// Global Select2 and UI Initializers
$(document).ready(function () {

    // 1. Select2 Global Defaults
    if (typeof $.fn.select2 !== 'undefined') {
        const isRtl = $('html').attr('dir') === 'rtl' || $('html').attr('data-textdirection') === 'rtl';
        $.fn.select2.defaults.set("dir", isRtl ? "rtl" : "ltr");
        $.fn.select2.defaults.set("width", "100%");
        
        if (typeof window.DOKANA_I18N !== 'undefined' && window.DOKANA_I18N.select2) {
            $.fn.select2.defaults.set("language", window.DOKANA_I18N.select2);
        }

        window.initGlobalSelect2 = function() {
            $('select.select2').not('.select2-hidden-accessible').each(function() {
                const $el = $(this);
                const $modal = $el.closest('.modal');
                $el.select2({
                    dropdownParent: $modal.length ? $modal : $(document.body),
                    width: '100%',
                    dir: isRtl ? 'rtl' : 'ltr'
                });
            });
        };

        window.initGlobalSelect2();

        $(document).ajaxComplete(function() {
            window.initGlobalSelect2();
        });
    }

});
