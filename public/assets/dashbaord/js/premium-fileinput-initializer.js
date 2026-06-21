/**
 * Premium FileInput Global Initializer
 * Centralizes the configuration and logic for all file inputs in the system.
 */

window.PremiumFileInput = {
    // Default Configuration
    defaults: {
        theme: 'fa5',
        language: $('html').attr('lang') || 'en',
        allowedFileTypes: ['image'],
        maxFileCount: 1,
        showCancel: false,
        showUpload: false,
        dropZoneEnabled: false,
        initialPreviewAsData: true,
        browseClass: "btn btn-sm btn-primary px-3",
        removeClass: "btn btn-danger",
        // These will be translated via global window.PTC_I18N if available
        browseLabel: "Choose File", 
        removeLabel: "Delete"
    },

    /**
     * Initialize a file input with premium styling and behavior
     * @param {string|jQuery} selector 
     * @param {object} customOptions 
     */
    init: function(selector, customOptions = {}) {
        let $el = $(selector);
        if (!$el.length) return;

        // Destroy if already initialized
        if ($el.data('fileinput')) {
            $el.fileinput('destroy');
        }

        // Merge defaults with custom options
        let options = $.extend(true, {}, this.defaults, customOptions);

        // Auto-detect translations from PTC_I18N if present
        if (window.PTC_I18N && window.PTC_I18N.fileinput) {
            options.browseLabel = window.PTC_I18N.fileinput.browseLabel;
            options.removeLabel = window.PTC_I18N.fileinput.removeLabel;
        }

        // Initialize
        $el.fileinput(options);

        // Handle the "Delete" signal logic automatically
        // If there's a hidden input with name="delete_[field_name]", update it
        let fieldName = $el.attr('name');
        let deleteInputId = '#delete_' + fieldName + '_edit';
        
        $el.on('fileclear', function() {
            if ($(deleteInputId).length) {
                $(deleteInputId).val(1);
            }
        }).on('change', function() {
            if ($(this).val() && $(deleteInputId).length) {
                $(deleteInputId).val(0);
            }
        });

        return $el;
    }
};

// Auto-initialize simple file inputs
$(document).ready(function() {
    $('.premium-fileinput').each(function() {
        window.PremiumFileInput.init(this);
    });
});
