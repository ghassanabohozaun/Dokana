// Global Select2 and UI Initializers
$(document).ready(function () {

    // 1. Select2 Global Defaults
    if (typeof $.fn.select2 !== 'undefined') {
        const isRtl = $('html').attr('dir') === 'rtl' || $('html').attr('data-textdirection') === 'rtl';
        $.fn.select2.defaults.set("dir", isRtl ? "rtl" : "ltr");
        $.fn.select2.defaults.set("width", "100%");
        
        if (isRtl && typeof window.DOKANA_I18N !== 'undefined' && window.DOKANA_I18N.select2) {
            $.fn.select2.defaults.set("language", window.DOKANA_I18N.select2);
        }
    }

});
