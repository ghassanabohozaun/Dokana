$(document).ready(function() {
     /** 
     * PTC Advanced Filtering System (Indestructible Version)
     * Uses MutationObserver to fight against external scripts trying to hide the UI.
     */
    
    // Debounce function to prevent constant AJAX calls
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    function initGeographicCascade() {
        // Listen for both variations of governorate field names (with and without 'r')
        $('body').on('change', 'select[name="governoate_id"], select[name="governorate_id"]', function() {
            const $govSelect = $(this);
            const $form = $govSelect.closest('form');
            const $citySelect = $form.find('select[name="city_id"]');
            const govId = $govSelect.val();

            if (!$citySelect.length) return;

            // Clear cities if no governorate is selected
            if (!govId) {
                $citySelect.html('<option value="">' + ($citySelect.find('option:first').text() || 'Show All') + '</option>').trigger('change');
                return;
            }

            // Show loading state
            const $cityWrapper = $citySelect.closest('.mb-3');
            $cityWrapper.addClass('ptc-select-loader');

            $.ajax({
                url: '/dashboard/governorates/get/all/cities',
                type: 'GET',
                data: { id: govId },
                dataType: 'json',
                success: function(response) {
                    if (response.status && response.data) {
                        let options = '<option value="">' + ($citySelect.find('option:first').text() || 'Show All') + '</option>';
                        
                        response.data.forEach(function(city) {
                            // Support for Spatie Translatable (if name is object or string)
                            let cityName = city.name;
                            if (typeof cityName === 'object') {
                                const locale = $('html').attr('lang') || 'ar';
                                cityName = cityName[locale] || cityName['ar'] || cityName['en'];
                            }
                            options += `<option value="${city.id}">${cityName}</option>`;
                        });

                        $citySelect.html(options).trigger('change');
                    }
                },
                complete: function() {
                    $cityWrapper.removeClass('ptc-select-loader');
                }
            });
        });
    }

    function initPropertyFiltersCascade() {
        $('body').on('change', 'select[name="company_id"]', function() {
            const companyId = $(this).val();
            const $form = $(this).closest('form, .card-body');
            const $typeSelect = $form.find('select[name="property_type_id"]');
            const $statusSelect = $form.find('select[name="property_status_id"]');
            const $ownerSelect = $form.find('select[name="owner_id[]"], select[name="owner_id"]');

            if (!$typeSelect.length && !$statusSelect.length && !$ownerSelect.length) return;

            // Show loading state
            if ($typeSelect.length) $typeSelect.closest('.premium-input-wrapper, .form-group').addClass('ptc-select-loader');
            if ($statusSelect.length) $statusSelect.closest('.premium-input-wrapper, .form-group').addClass('ptc-select-loader');
            if ($ownerSelect.length) $ownerSelect.closest('.premium-input-wrapper, .form-group').addClass('ptc-select-loader');

            $.ajax({
                url: '/dashboard/properties/filters-by-company',
                type: 'GET',
                data: { company_id: companyId },
                dataType: 'json',
                success: function(response) {
                    if (response.types && $typeSelect.length) {
                        let typeOptions = '<option value="">' + ($typeSelect.find('option:first').text() || 'All') + '</option>';
                        response.types.forEach(function(type) {
                            typeOptions += `<option value="${type.id}">${type.name}</option>`;
                        });
                        $typeSelect.html(typeOptions);
                        if ($typeSelect.hasClass('select2-hidden-accessible') || $typeSelect.hasClass('js-select2') || $typeSelect.hasClass('select2')) {
                            $typeSelect.trigger('change.select2');
                        }
                    }

                    if (response.statuses && $statusSelect.length) {
                        let statusOptions = '<option value="">' + ($statusSelect.find('option:first').text() || 'All') + '</option>';
                        response.statuses.forEach(function(status) {
                            statusOptions += `<option value="${status.id}">${status.name}</option>`;
                        });
                        $statusSelect.html(statusOptions);
                        if ($statusSelect.hasClass('select2-hidden-accessible') || $statusSelect.hasClass('js-select2') || $statusSelect.hasClass('select2')) {
                            $statusSelect.trigger('change.select2');
                        }
                    }
                },
                complete: function() {
                    if ($typeSelect.length) $typeSelect.closest('.premium-input-wrapper, .form-group').removeClass('ptc-select-loader');
                    if ($statusSelect.length) $statusSelect.closest('.premium-input-wrapper, .form-group').removeClass('ptc-select-loader');
                }
            });

            if ($ownerSelect.length) {
                $.ajax({
                    url: '/dashboard/owners-by-company',
                    type: 'GET',
                    data: { company_id: companyId },
                    dataType: 'json',
                    success: function(response) {
                        let ownerOptions = '';
                        // Usually owner filter has no 'All' option, it's just multiple select
                        if (Array.isArray(response)) {
                            response.forEach(function(owner) {
                                ownerOptions += `<option value="${owner.id}">${owner.name}</option>`;
                            });
                        } else if (response.data) {
                             response.data.forEach(function(owner) {
                                ownerOptions += `<option value="${owner.id}">${owner.name}</option>`;
                            });
                        }
                        $ownerSelect.html(ownerOptions);
                        if ($ownerSelect.hasClass('select2-hidden-accessible') || $ownerSelect.hasClass('js-select2') || $ownerSelect.hasClass('select2')) {
                            $ownerSelect.trigger('change.select2');
                        }
                    },
                    complete: function() {
                        $ownerSelect.closest('.premium-input-wrapper, .form-group').removeClass('ptc-select-loader');
                    }
                });
            }
        });
    }

    function initFilterSystem() {
        const $chips = $('.js-filter-chip');
        const $panels = $('.ptc-query-panel');

        initGeographicCascade();
        initPropertyFiltersCascade();

        const closeAll = () => {
            $panels.removeClass('ptc-show').attr('data-is-open', 'false');
            $chips.removeClass('popover-open');
        };

        // MutationObserver to prevent external hiding
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes') {
                    const target = mutation.target;
                    const $target = $(target);
                    if ($target.attr('data-is-open') === 'true' && !$target.hasClass('ptc-show')) {
                        $target.addClass('ptc-show');
                    }
                }
            });
        });

        $panels.each(function() {
            observer.observe(this, { attributes: true, attributeFilter: ['class', 'style'] });
        });

        $chips.off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $chip = $(this);
            const targetId = $chip.data('filter-target');
            const $panel = $('#' + targetId);
            const isOpen = $panel.attr('data-is-open') === 'true';

            if (!isOpen) {
                closeAll();
                $panel.addClass('ptc-show').attr('data-is-open', 'true');
                $chip.addClass('popover-open');

                const $select = $panel.find('.js-select2');
                if ($select.length && !$select.hasClass("select2-hidden-accessible")) {
                    const dir = document.documentElement.getAttribute("data-textdirection") || 
                              document.documentElement.getAttribute("dir") || "ltr";
                              
                    $select.select2({
                        dropdownParent: $panel,
                        width: '100%',
                        dir: dir,
                        placeholder: $select.data('placeholder') || $select.attr('placeholder') || 'Select...'
                    });
                }
                
                setTimeout(() => {
                    const $firstInput = $panel.find('input, select').filter(':visible').first();
                    if ($firstInput.length && !$firstInput.hasClass('ptc-datepicker') && !$firstInput.hasClass('ptc-monthpicker')) {
                        $firstInput.focus();
                    }
                }, 100);
            } else {
                closeAll();
            }
        });

        // Handle Enter key inside panels to trigger apply button
        $panels.off('keypress').on('keypress', 'input', function(e) {
            if (e.which === 13) { // 13 is Enter
                e.preventDefault();
                $(this).closest('.ptc-query-panel').find('.js-apply-filter').trigger('click');
            }
        });

        $panels.off('click').on('click', function(e) {
            e.stopPropagation();
        });

        $(document).off('click.filterSystem').on('click.filterSystem', function(e) {
            if (!$(e.target).closest('.js-filter-chip').length && 
                !$(e.target).closest('.ptc-query-panel').length &&
                !$(e.target).closest('.select2-container').length) {
                closeAll();
            }
        });

        $('.js-apply-filter').off('click').on('click', function(e) {
            e.preventDefault();
            const $panel = $(this).closest('.ptc-query-panel');
            const targetId = $panel.attr('id');
            const $chip = $('.js-filter-chip[data-filter-target="' + targetId + '"]');
            const $form = $chip.closest('form');
            
            let hasValue = false;
            $panel.find('input, select').each(function() {
                const val = $(this).val();
                if (val && val !== "" && (Array.isArray(val) ? val.length > 0 : true)) {
                    hasValue = true;
                }
            });

            $chip.toggleClass('active', hasValue);
            closeAll();
            $form.trigger('submit');
        });

        $('.js-reset-btn').off('click').on('click', function(e) {
            e.preventDefault();
            const $form = $(this).closest('form');
            $form[0].reset();
            $form.find('.js-select2').val(null).trigger('change');
            $form.find('.js-filter-chip').removeClass('active');
            closeAll();
            $form.trigger('submit');
        });

        // Handle Manual Clear for Select2 inside popovers
        $('.js-clear-select2').off('click').on('click', function(e) {
            e.preventDefault();
            const target = $(this).data('target');
            if (target && $(target).length) {
                $(target).val(null).trigger('change');
            }
        });
        
        // Auto-initialize Select2 Autocomplete fields in the filter
        $('.js-autocomplete').each(function() {
            const $el = $(this);
            if (typeof initGenericSelect2 === "function") {
                initGenericSelect2(
                    $el, 
                    $el.data('url'), 
                    $el.data('placeholder'), 
                    $el.data('parent')
                );
            }
        });

    }

    initFilterSystem();

    $(document).on('submit', '.js-filter-form', function(e, extraData) {
        e.preventDefault();
        const $form = $(this);
        const actionUrl = $form.attr('action') || window.location.pathname;
        const targetContainer = $form.data('container') || '#table_data';
        const targetLoader = $form.data('loader') || '.table-loader-overlay';
        
        // Serialize form and filter empty values
        let formDataArr = $form.serializeArray().filter(item => item.value !== "");
        
        // If extraData contains a page (from a refresh/fetch_data call), add it
        if (extraData && extraData.page) {
            formDataArr.push({ name: 'page', value: extraData.page });
        }

        const formData = formDataArr.map(item => encodeURIComponent(item.name) + '=' + encodeURIComponent(item.value)).join('&');
        
        // Construct visual URL for pushState
        const fullUrl = formData ? (actionUrl + (actionUrl.includes('?') ? '&' : '?') + formData) : actionUrl;

        // Construct AJAX URL to avoid BFCache caching the AJAX response as the full page
        const ajaxUrl = actionUrl + (actionUrl.includes('?') ? '&' : '?') + '_ajax=1';

        $.ajax({
            url: ajaxUrl,
            data: formData,
            type: 'GET',
            beforeSend: function() {
                $(targetLoader).addClass('active');
                $(targetContainer).css('opacity', '0.6');
            },
            success: function(response) {
                $(targetContainer).html(response);
                $(targetContainer).css('opacity', '1');
                $(targetLoader).removeClass('active');

                // Update total count badges if they exist
                ['contracts', 'properties', 'customers', 'maintenances', 'guarantors', 'users', 'roles', 'departments', 'property_types', 'property_statuses', 'bank_accounts', 'companies'].forEach(module => {
                    const total = $(`#${module}-total-count`).val();
                    if (total !== undefined) {
                        $(`#${module}CountBadge`).text(total);
                        $(`#${module}ChipCount`).text(total);
                    }
                });
                
                // Update URL without refresh
                window.history.pushState(null, "", fullUrl);

                if (typeof window.initTablePlugins === 'function') {
                    window.initTablePlugins(targetContainer);
                }
                initFilterSystem();
            },
            error: function() {
                $(targetLoader).removeClass('active');
                $(targetContainer).css('opacity', '1');
            }
        });
    });
});
