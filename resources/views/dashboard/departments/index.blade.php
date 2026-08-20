@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-6">
    <!-- 1. Header & Actions Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <i class="fas fa-home text-xs"></i>
                    <span>{!! __('dashboard.home') !!}</span>
                </a>
                <span>/</span>
                <span class="text-slate-700 dark:text-slate-200 font-bold">{!! __('departments.departments') !!}</span>
            </nav>

            <!-- Page Title & Counter Badge -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('departments.departments') !!}
                        </h1>
                        <span id="departmentsCountBadge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $departments->total() !!} {!! __('general.records') ?? 'سجل' !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @can('departments_create')
            <button type="button" data-toggle="modal" data-target="#createDepartmentModal"
                class="btn-primary-gradient">
                <i class="fas fa-plus text-xs"></i>
                <span>{!! __('departments.create_new_department') !!}</span>
            </button>
            @endcan
        </div>
    </div>

    <!-- 2. Search Filters Bar -->
    @include('dashboard.departments.partials._search')

    <!-- 3. Main Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.departments.partials._table')
        </div>
    </div>
</div>

<!-- Modals -->
@can('departments_create')
@include('dashboard.departments.modals.create')
@endcan

@can('departments_update')
@include('dashboard.departments.modals.edit')
@endcan

@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Global Select2 Initialization for Modals ONLY
            $('.modal .select2').each(function() {
                var $el = $(this);
                var parentModal = $el.closest('.modal');
                $el.select2({
                    dropdownParent: parentModal.length ? parentModal : $(document.body),
                    width: '100%',
                    placeholder: $el.attr('placeholder') || "{!! __('general.choose') !!}",
                });
            });

            // Filter Store Select2
            if ($('#filter_store_id').length) {
                $('#filter_store_id').select2({
                    width: '100%'
                });
            }
        });

        // Toggle Status via AJAX
        $(document).on('change', '.change_status', function(e) {
            var id = $(this).data('id');
            var statusSwitch = $(this).is(':checked') ? 1 : 0;
            var checkbox = $(this);

            $.ajax({
                url: "{{ route('dashboard.departments.change.status') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    let statusBadge = $('.department_status_' + data.data.id);
                    statusBadge.removeClass('badge-pill-danger badge-pill-success');
                    
                    if (data.data.status == 1) {
                        statusBadge.addClass('badge-pill-success').text("{!! __('general.enable') !!}");
                    } else {
                        statusBadge.addClass('badge-pill-danger').text("{!! __('general.disabled') !!}");
                    }

                    if (window.PremiumToast) {
                        if (data.status === true) {
                            window.PremiumToast.success("{!! __('general.change_status_success_message') !!}");
                        } else {
                            window.PremiumToast.error("{!! __('general.change_status_error_message') !!}");
                        }
                    }

                    // Trigger smooth table animation & re-fetch
                    if (window.DokanaTable && typeof window.DokanaTable.fetchData === 'function') {
                        window.DokanaTable.fetchData();
                    }
                },
                error: function(xhr) {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    if (window.PremiumToast) {
                        if (xhr.status === 403) {
                            window.PremiumToast.error("{!! __('dashboard.access_denied') !!}");
                        } else {
                            window.PremiumToast.error("{!! __('general.try_catch_error_message') !!}");
                        }
                    }
                }
            });
        });

        // Dynamically refresh Store Select Filter Options without full page refresh
        function refreshStoreFilterOptions() {
            let $select = $('#filter_store_id');
            if (!$select.length) return;

            let currentVal = $select.val();

            $.ajax({
                url: "{{ route('dashboard.stores.options') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status && response.data) {
                        let optionsHtml = '<option value="">{!! __('general.all_stores') !!}</option>';
                        response.data.forEach(function(store) {
                            let selectedAttr = (store.id == currentVal) ? 'selected' : '';
                            optionsHtml += `<option value="${store.id}" ${selectedAttr}>${store.name}</option>`;
                        });
                        $select.html(optionsHtml).trigger('change.select2');
                    }
                }
            });
        }

        // Hook into AJAX form success & deletion events
        $(document).on('ajax-form-success record-deleted', function() {
            refreshStoreFilterOptions();
        });
    </script>
@endpush
