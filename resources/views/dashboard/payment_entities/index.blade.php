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
                <span class="text-slate-700 dark:text-slate-200 font-bold">{!! __('payment_entities.payment_entities') !!}</span>
            </nav>

            <!-- Page Title & Counter Badge -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                    <i class="fas fa-landmark"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('payment_entities.payment_entities') !!}
                        </h1>
                        <span id="payment_entitiesCountBadge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $entities->total() !!} {!! __('general.records') ?? 'سجل' !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @if(($isSuperAdmin ?? false) && auth()->user()->can('payment_entities_create'))
            <button type="button" data-toggle="modal" data-target="#createPaymentEntityModal"
                class="btn-primary-gradient">
                <i class="fas fa-plus text-xs"></i>
                <span>{!! __('payment_entities.create_new_payment_entity') !!}</span>
            </button>
            @endif
        </div>
    </div>

    <!-- 2. Search Filters Bar -->
    @include('dashboard.payment_entities.partials._search')

    <!-- 3. Main Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.payment_entities.partials._table')
        </div>
    </div>
</div>

<!-- Modals -->
@can('payment_entities_create')
@include('dashboard.payment_entities.modals.create')
@endcan

@can('payment_entities_update')
@include('dashboard.payment_entities.modals.edit')
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

            // Filter Select2
            $('#filter_type, #filter_status').each(function() {
                $(this).select2({
                    width: '100%'
                });
            });
        });

        // Toggle Status via AJAX
        $(document).on('change', '.change_status', function(e) {
            var id = $(this).data('id');
            var statusSwitch = $(this).is(':checked') ? 1 : 0;
            var checkbox = $(this);

            $.ajax({
                url: "{{ route('dashboard.payment-entities.change.status') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    let statusBadge = $('.entity_status_' + id);
                    statusBadge.removeClass('badge-pill-danger badge-pill-success');
                    
                    if (statusSwitch == 1) {
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
    </script>
@endpush
