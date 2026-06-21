window.initIndexTable = function (options = {}) {
    const settings = $.extend({
        container: "#table_data",
        loader: ".table-loader-overlay",
        pagination: ".pagination a",
        detailsControl: ".details-control",
        detailsModal: "#detailsModal",
        detailsModalBody: "#modalBody",
    }, options);

    const $loader = $(settings.loader);

    // 1. AJAX Pagination Handler
    $(document).off("click", settings.pagination).on("click", settings.pagination, function (e) {
        e.preventDefault();
        const href = $(this).attr("href");
        if (!href || href === "#") return;

        let ajaxUrl = href;
        if (ajaxUrl.indexOf('?') === -1) {
            ajaxUrl += '?_ajax=1';
        } else {
            ajaxUrl += '&_ajax=1';
        }

        $.ajax({
            url: ajaxUrl,
            beforeSend: function () {
                $loader.addClass("active");
                $(settings.container).css("opacity", "0.6");
            },
            success: function (data) {
                $(settings.container).html(data);
                $(settings.container).css("opacity", "1");
                $loader.removeClass("active");

                // Update total count badges if they exist
                ['contracts', 'properties', 'customers', 'maintenances', 'guarantors', 'users', 'roles', 'departments', 'property_types', 'property_statuses', 'bank_accounts', 'companies'].forEach(module => {
                    const total = $(`#${module}-total-count`).val();
                    if (total !== undefined) {
                        $(`#${module}CountBadge`).text(total);
                        $(`#${module}ChipCount`).text(total);
                    }
                });
                
                // Update URL without refresh
                window.history.pushState(null, "", href);
            },
            error: function() {
                $loader.removeClass("active");
                $(settings.container).css("opacity", "1");
            }
        });
    });

    // 2. Details Modal Logic
    $(document).off("click", settings.detailsControl).on("click", settings.detailsControl, function () {
        const row = $(this).closest("tr");
        const detailsHtml = row.find(".row-details").html();
        $(settings.detailsModalBody).html(detailsHtml);
        $(settings.detailsModal).modal("show");
    });

    // 3. Handle Back/Forward Browser Buttons
    window.onpopstate = function() {
        // If we want to support AJAX back navigation, we'd need a more complex state management
        // For now, reloading is a safe fallback
        location.reload(); 
    };
};
