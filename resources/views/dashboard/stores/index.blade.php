@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-5">
    <!-- Top Header & Action Bar -->
    <div class="flex items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500">
            <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-home text-xs"></i>
                <span>{!! __('dashboard.home') !!}</span>
            </a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-200 font-bold">{!! __('stores.stores') !!}</span>
        </nav>

        <!-- Action Buttons -->
        <div>
            @can('stores_create')
            <button type="button" data-toggle="modal" data-target="#addStoreModal"
                class="btn-primary-gradient text-xs">
                <i class="fas fa-plus-circle text-xs"></i>
                <span>{!! __('stores.create_new_store') !!}</span>
            </button>
            @endcan
        </div>
    </div>

    <!-- Search Filters Bar -->
    @include('dashboard.stores.partials._search')

    <!-- Master Data Table Card -->
    <div class="dash-card overflow-hidden">
        <!-- Card Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">{!! __('stores.stores') !!}</h3>
                </div>
                <span id="storesCountBadge" class="badge-pill badge-pill-info text-[10px]">{!! $stores->total() !!}</span>
            </div>
        </div>

        <!-- Card Body Table Container -->
        <div class="relative min-h-[150px]" id="table_data">
            <div class="table-loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xs flex items-center justify-center z-10 hidden" id="tableLoader">
                <div class="animate-spin rounded-full h-8 w-8 border-2 border-indigo-600 border-t-transparent"></div>
            </div>
            @include('dashboard.stores.partials._table')
        </div>
    </div>
</div>

<!-- Modals -->
@can('stores_create')
@include('dashboard.stores.modals.create')
@endcan

@can('stores_update')
@include('dashboard.stores.modals.edit')
@endcan

@include('dashboard.stores.modals.details')

@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if (typeof initIndexTable === "function") {
                initIndexTable({
                    detailsModal: "#detailsStoreModal",
                    detailsModalBody: "#detailsStoreModalBody"
                });
            }

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

            // Filter Store Select2 (preserves 'كل الدكاكين' default value)
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
                url: "{{ route('dashboard.stores.status') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    let statusBadge = $('.store_status_' + data.data.id);
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

                    // Dynamically refresh Store Select Filter Options
                    if (typeof refreshStoreFilterOptions === 'function') {
                        refreshStoreFilterOptions();
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
