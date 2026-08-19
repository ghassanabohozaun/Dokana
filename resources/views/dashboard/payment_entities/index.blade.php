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
            <span class="text-slate-700 dark:text-slate-200 font-bold">{!! __('payment_entities.payment_entities') !!}</span>
        </nav>

        <!-- Action Buttons -->
        <div>
            @can('payment_entities_create')
            <button type="button" data-toggle="modal" data-target="#createPaymentEntityModal"
                class="btn-primary-gradient text-xs">
                <i class="fas fa-plus-circle text-xs"></i>
                <span>{!! __('payment_entities.create_new_payment_entity') !!}</span>
            </button>
            @endcan
        </div>
    </div>

    <!-- Search Filters Bar -->
    @include('dashboard.payment_entities.partials._search')

    <!-- Master Data Table Card -->
    <div class="dash-card overflow-hidden">
        <!-- Card Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                    <i class="fas fa-landmark"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">{!! __('payment_entities.payment_entities') !!}</h3>
                </div>
                <span id="payment_entitiesCountBadge" class="badge-pill badge-pill-info text-[10px]">{!! $entities->total() !!}</span>
            </div>
        </div>

        <!-- Card Body Table Container -->
        <div class="relative min-h-[150px]" id="table_data">
            <div class="table-loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xs flex items-center justify-center z-10 hidden" id="tableLoader">
                <div class="animate-spin rounded-full h-8 w-8 border-2 border-indigo-600 border-t-transparent"></div>
            </div>
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
