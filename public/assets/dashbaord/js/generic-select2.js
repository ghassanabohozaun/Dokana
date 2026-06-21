/**
 * Generic Select2 Autocomplete Initialization
 *
 * @param {string} selector - The jQuery selector for the element (e.g. '#company_id_create')
 * @param {string} url - The AJAX endpoint URL
 * @param {string} placeholder - The placeholder text
 * @param {string} dropdownParent - Optional ID of the parent modal to fix z-index issues (e.g. '#createUserModal')
 */
function initGenericSelect2(selector, url, placeholder, dropdownParent = null) {
    const $select = typeof selector === "string" ? $(selector) : selector;
    const isSimple =
        $select.data("simple") === true || $select.data("simple") === "true";

    let config = {
        placeholder: placeholder,
        allowClear: false, // Disabled native clear to use our custom professional reset icon
        width: "100%",
        minimumInputLength: 0,
        ajax: {
            url: url,
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page,
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                // Support both structured {results: [], total_count: 0} and simple array responses
                let results = data.results ? data.results : data;
                let totalCount = data.total_count
                    ? data.total_count
                    : results.length;

                return {
                    results: results,
                    pagination: {
                        more: params.page * 30 < totalCount,
                    },
                };
            },
            cache: true,
        },
        language: {
            searching: function () {
                return window.PTC_I18N && window.PTC_I18N.select2
                    ? window.PTC_I18N.select2.searching()
                    : "Searching...";
            },
            noResults: function () {
                return window.PTC_I18N && window.PTC_I18N.select2
                    ? window.PTC_I18N.select2.noResults()
                    : "No results found";
            },
            errorLoading: function () {
                return window.PTC_I18N && window.PTC_I18N.select2
                    ? window.PTC_I18N.select2.errorLoading()
                    : "Error loading results";
            },
            inputTooShort: function (args) {
                return window.PTC_I18N && window.PTC_I18N.select2
                    ? window.PTC_I18N.select2.inputTooShort(args)
                    : "Please enter more characters";
            },
            inputTooLong: function (args) {
                return window.PTC_I18N && window.PTC_I18N.select2
                    ? window.PTC_I18N.select2.inputTooLong(args)
                    : "Please delete some characters";
            },
        },
    };

    if (!isSimple) {
        config.dropdownCssClass = "premium-ajax-dropdown";
        config.selectionCssClass = "premium-ajax-selection";
        config.templateResult = formatRepo;
        config.templateSelection = formatRepoSelection;
    }

    /**
     * Professional Template for Select2 Results
     */
    function formatRepo(repo) {
        if (repo.loading) return repo.text;

        // If it's a simple result without metadata, return text
        if (!repo.email && !repo.logo && !repo.initials) return repo.text;

        const isRtl =
            document.documentElement.getAttribute("dir") === "rtl" ||
            document.documentElement.getAttribute("data-textdirection") ===
                "rtl";

        // Custom Template logic
        let $container = $(
            "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'>" +
                (repo.logo && !repo.logo.includes("placeholder")
                    ? "<img src='" + repo.logo + "' />"
                    : "<div class='select2-result-avatar-initials' style='background-color:" +
                      repo.color +
                      "'>" +
                      repo.initials +
                      "</div>") +
                "</div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
                "</div>" +
                "</div>",
        );

        $container.find(".select2-result-repository__title").text(repo.text);

        let subText = "";
        if (repo.description)
            subText +=
                "<div class='select2-result-repository__desc_text'>" + repo.description + "</div>";
        if (repo.email)
            subText +=
                "<span><i class='la la-envelope'></i>" + repo.email + "</span>";
        if (repo.phone)
            subText +=
                "<span><i class='la la-phone'></i>" + repo.phone + "</span>";

        $container
            .find(".select2-result-repository__description")
            .html(subText);

        return $container;
    }

    function formatRepoSelection(repo) {
        if (!repo.email && !repo.logo && !repo.initials) {
            return repo.text || repo.id;
        }

        let avatarHtml = "";
        if (repo.logo && !repo.logo.includes("placeholder")) {
            avatarHtml =
                "<img src='" +
                repo.logo +
                "' class='select2-selection-avatar' />";
        } else if (repo.initials) {
            avatarHtml =
                "<div class='select2-selection-avatar-initials' style='background-color:" +
                repo.color +
                "'>" +
                repo.initials +
                "</div>";
        }

        if (avatarHtml) {
            return $(
                "<div class='select2-selected-repo'>" +
                    avatarHtml +
                    "<span class='select2-selected-repo__text'>" +
                    repo.text +
                    "</span>" +
                    "</div>",
            );
        }

        return repo.text || repo.id;
    }

    if (dropdownParent) {
        config.dropdownParent = $(dropdownParent);
    }

    const dir = document.documentElement.getAttribute("data-textdirection") || 
                document.documentElement.getAttribute("dir") || "ltr";
    config.dir = dir;

    // Initialize Select2
    $select.select2(config);

    // --- Professional Reset Logic ---
    const $wrapper = $select.closest(".premium-input-wrapper");
    if ($wrapper.length) {
        // Remove any existing reset button first
        $wrapper.find(".select2-custom-reset").remove();

        // Create the reset button as a span to avoid global <i> tag CSS overrides
        const $resetBtn = $(
            '<span class="la la-times select2-custom-reset"></span>',
        );
        $wrapper.append($resetBtn);

        // Function to toggle visibility
        const toggleReset = () => {
            if ($select.val()) {
                $resetBtn.addClass("visible");
            } else {
                $resetBtn.removeClass("visible");
            }
        };

        // Event listeners
        $select.on(
            "change.select2-reset select2:select.select2-reset select2:unselect.select2-reset select2:clear.select2-reset",
            toggleReset,
        );

        $resetBtn.on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $select.val(null).trigger("change");
        });

        // Initial check
        toggleReset();
    }
}

// Auto-initialize elements with .select2-autocomplete or .js-autocomplete class on page load
$(document).ready(function () {
    $(".select2-autocomplete, .js-autocomplete").each(function () {
        var $el = $(this);
        var url = $el.data("url");
        var placeholder = $el.data("placeholder") || "Select...";
        var parent = $el.data("parent");
        if (url) {
            initGenericSelect2($el, url, placeholder, parent);
        }
    });
});
