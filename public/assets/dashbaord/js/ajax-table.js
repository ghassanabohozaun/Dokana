/**
 * Dokana Generic AJAX Table & Pagination Engine
 * Features:
 * - 100% In-Memory State (Zero URL pollution, no page numbers in address bar)
 * - Shimmer Skeleton Loading Animation (Zero Layout Shift)
 * - Seamless Filter & Pagination Binding
 * - Smart Auto-Fallback on row deletion (Auto previous page if emptied)
 * - Safe Race Condition Prevention (Request Aborting)
 * - Automatic Counter Badge & Metrics Synchronization
 */

(function (window, $) {
    'use strict';

    const DokanaTable = {
        currentPage: 1,
        activeRequest: null,
        container: '#table_data',
        loader: '.table-loader-overlay',
        filterForm: '.js-filter-form',

        init: function (options) {
            if (options) {
                if (options.container) this.container = options.container;
                if (options.loader) this.loader = options.loader;
                if (options.filterForm) this.filterForm = options.filterForm;
            }
            this.bindEvents();
        },

        lockTableGeometry: function ($table) {
            if (!$table.length) return;
            // Freeze each column header's exact current pixel width
            $table.find('thead th').each(function () {
                const w = $(this)[0].getBoundingClientRect().width;
                if (w > 0) {
                    $(this).css({
                        'width': w + 'px',
                        'min-width': w + 'px',
                        'max-width': w + 'px'
                    });
                }
            });
            $table.css('table-layout', 'fixed');
        },

        renderSkeleton: function ($container) {
            const $table = $container.find('table');
            if (!$table.length) return;

            // Lock header geometry so titles never move
            this.lockTableGeometry($table);

            const $tbody = $table.find('tbody');
            const colCount = $table.find('thead th').length || 6;
            
            // Count actual data rows currently visible (default to 10 if standard pagination)
            const visibleRows = $tbody.find('tr:not(.empty-state-row)').length;
            const rowCount = visibleRows > 0 ? visibleRows : 10;
            
            // Lock current tbody height to prevent ANY pagination jumping up/down
            const currentTbodyHeight = $tbody.outerHeight();
            if (currentTbodyHeight && currentTbodyHeight > 100) {
                $tbody.css('min-height', currentTbodyHeight + 'px');
            }

            // Temporarily disable pagination pointer events during transition
            $container.find('.pagination').addClass('pointer-events-none opacity-40');

            let skeletonHtml = '';
            for (let r = 0; r < rowCount; r++) {
                skeletonHtml += '<tr class="table-skeleton-row border-b border-slate-100 dark:border-slate-800/80">';
                for (let c = 0; c < colCount; c++) {
                    if (c === 0) {
                        // Iteration #
                        skeletonHtml += '<td class="text-center w-12"><div class="skeleton-shimmer h-3.5 w-4 rounded mx-auto"></div></td>';
                    } else if (c === 1) {
                        // Main name / Avatar column
                        skeletonHtml += '<td><div class="flex items-center gap-2.5"><div class="skeleton-shimmer h-8 w-8 rounded-xl shrink-0"></div><div class="space-y-1.5 flex-1 max-w-[140px]"><div class="skeleton-shimmer h-3.5 w-full rounded"></div><div class="skeleton-shimmer h-2.5 w-2/3 rounded block"></div></div></div></td>';
                    } else if (c === colCount - 1) {
                        // Actions column
                        skeletonHtml += '<td class="text-center w-24"><div class="inline-flex items-center justify-center gap-2"><div class="skeleton-shimmer h-7 w-7 rounded-lg"></div><div class="skeleton-shimmer h-7 w-7 rounded-lg"></div></div></td>';
                    } else if (c === colCount - 2) {
                        // Status or Switch column
                        skeletonHtml += '<td class="text-center"><div class="skeleton-shimmer h-5 w-14 rounded-full mx-auto"></div></td>';
                    } else {
                        // Data column (responsive flexible width)
                        const widthPcts = ['w-3/4 max-w-[100px]', 'w-1/2 max-w-[80px]', 'w-4/5 max-w-[120px]', 'w-2/3 max-w-[90px]'];
                        const widthClass = widthPcts[(r + c) % widthPcts.length];
                        skeletonHtml += '<td><div class="skeleton-shimmer h-3.5 ' + widthClass + ' rounded"></div></td>';
                    }
                }
                skeletonHtml += '</tr>';
            }

            $tbody.html(skeletonHtml);
        },

        fetchData: function (params) {
            params = params || {};
            const page = params.page !== undefined ? parseInt(params.page, 10) : this.currentPage;
            this.currentPage = page > 0 ? page : 1;

            const $container = $(this.container);
            if (!$container.length) return;

            const $form = $(this.filterForm);
            const actionUrl = $form.length && $form.attr('action') ? $form.attr('action') : window.location.pathname;

            // Abort previous request if still in flight
            if (this.activeRequest && this.activeRequest.readyState !== 4) {
                this.activeRequest.abort();
            }

            // Gather form parameters
            let formDataArr = [];
            if ($form.length) {
                formDataArr = $form.serializeArray().filter(item => item.value !== "" && item.name !== "page");
            }
            formDataArr.push({ name: 'page', value: this.currentPage });
            formDataArr.push({ name: '_ajax', value: '1' });

            const formData = $.param(formDataArr);
            const self = this;

            // Render Shimmer Skeleton
            self.renderSkeleton($container);
            $(self.loader).removeClass('hidden').addClass('active');

            this.activeRequest = $.ajax({
                url: actionUrl,
                data: formData,
                type: 'GET',
                dataType: 'html',
                success: function (response) {
                    $container.html(response);
                    $(self.loader).addClass('hidden').removeClass('active');

                    // Synchronize Badges and Counters
                    self.syncCounters($container);

                    // Re-trigger global re-hydration
                    $(document).trigger('table-hydrated', [$container]);
                },
                error: function (xhr, status) {
                    if (status !== 'abort') {
                        $(self.loader).addClass('hidden').removeClass('active');
                        if (window.PremiumToast) {
                            window.PremiumToast.error(window.DOKANA_I18N?.common?.something_went_wrong || "حدث خطأ أثناء تحميل البيانات");
                        }
                    }
                },
                complete: function () {
                    self.activeRequest = null;
                }
            });
        },

        syncCounters: function ($container) {
            // Find any module total count input
            const totalCountInputs = [
                'stores-total-count',
                'departments-total-count',
                'payment_entities-total-count',
                'bank_accounts-total-count',
                'store_withdrawals-total-count',
                'store_customers-total-count',
                'store_transactions-total-count',
                'store_suppliers-total-count',
                'store_supplier_invoices-total-count',
                'store_supplier_payments-total-count',
                'users-total-count',
                'roles-total-count'
            ];

            totalCountInputs.forEach(function (id) {
                const $input = $container.find('#' + id);
                if ($input.length) {
                    const totalVal = $input.val();
                    const moduleName = id.replace('-total-count', '');
                    
                    $('#' + moduleName + 'CountBadge').text(totalVal + ' سجل');
                    $('#total-count-badge').text(totalVal + ' سجل');
                    $('#' + moduleName + 'ChipCount').text(totalVal);
                }
            });

            // Update stats container metrics if available
            const $metrics = $container.find('#ajax-metrics-data');
            if ($metrics.length) {
                $('#ui_stats_total_payments').text($metrics.data('total-payments'));
                $('#ui_stats_total_debts').text($metrics.data('total-debts'));
                $('#ui_stats_net_balance').text($metrics.data('net-balance'));
                $('#ui_stats_total_count').text($metrics.data('total-count'));
                $('#ui_stats_total_customers_count').text($metrics.data('total-customers'));
                $('#ui_stats_total_creditor_balances').text($metrics.data('total-creditor'));
                $('#ui_stats_total_lifetime_debts').text($metrics.data('lifetime-debts'));
                $('#ui_stats_total_lifetime_payments').text($metrics.data('lifetime-payments'));
            }
        },

        bindEvents: function () {
            const self = this;

            // 1. Generic AJAX Pagination Click Handler (Without page numbers in URL)
            $(document).off('click.dokanaPagination', '.pagination a, #table_data .pagination a')
                       .on('click.dokanaPagination', '.pagination a, #table_data .pagination a', function (e) {
                e.preventDefault();
                const href = $(this).attr('href');
                if (!href || href === '#' || $(this).parent().hasClass('disabled') || $(this).parent().hasClass('active')) {
                    return;
                }

                // Extract page number from URL safely
                const match = href.match(/[?&]page=([0-9]+)/);
                const targetPage = match ? parseInt(match[1], 10) : 1;

                self.fetchData({ page: targetPage });
            });

            // 2. Smart Row Deletion & Fallback Handler
            $(document).off('record-deleted.dokanaTable ajax-form-deleted.dokanaTable')
                       .on('record-deleted.dokanaTable ajax-form-deleted.dokanaTable', function () {
                const $tbody = $(self.container).find('tbody');
                const remainingRows = $tbody.find('tr:not(.empty-state-row):not(.table-skeleton-row)').length;

                // If only 1 row was left and deleted, fallback to previous page
                if (remainingRows <= 1 && self.currentPage > 1) {
                    self.fetchData({ page: self.currentPage - 1 });
                } else {
                    self.fetchData({ page: self.currentPage });
                }
            });

            // 3. Details Control Modal Handler
            $(document).off('click.dokanaDetails', '.details-control')
                       .on('click.dokanaDetails', '.details-control', function () {
                const row = $(this).closest('tr');
                const detailsHtml = row.find('.row-details').html();
                $('#detailsModalBody, #modalBody').html(detailsHtml);
                $('#detailsModal').modal('show');
            });
        }
    };

    window.DokanaTable = DokanaTable;

    // Backward compatibility with legacy initIndexTable calls
    window.initIndexTable = function (options) {
        DokanaTable.init(options);
    };

    $(document).ready(function () {
        DokanaTable.init();
    });

})(window, jQuery);

