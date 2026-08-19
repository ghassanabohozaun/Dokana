@extends('layouts.dashboard.app')

@section('title', $title)

@section('content')
<div class="space-y-6">
    <!-- Top Navigation & Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500">
            <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-home text-xs"></i>
                <span>{!! __('dashboard.home') !!}</span>
            </a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-200 font-bold">{!! $title !!}</span>
        </nav>

        <!-- Action Button -->
        @can('store_withdrawals_create')
            <button type="button" class="btn-primary-gradient text-xs" data-toggle="modal" data-target="#createStoreWithdrawalModal">
                <i class="fas fa-plus-circle text-xs"></i>
                <span>{!! __('store_withdrawals.create_new_store_withdrawal') !!}</span>
            </button>
        @endcan
    </div>

    <!-- Search & Filters Toolbar -->
    @include('dashboard.store_withdrawals.partials._search')

    <!-- Main Table Card -->
    <div class="dash-card overflow-hidden">
        <!-- Card Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-xs">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                    {!! __('store_withdrawals.store_withdrawals_list') !!}
                </h3>
                <span id="total-count-badge" class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/60">
                    {!! $withdrawals->total() !!} {!! __('general.records') ?? 'سجل' !!}
                </span>
            </div>
        </div>

        <!-- Table Container with Smooth AJAX Loader -->
        <div class="relative min-h-[300px]">
            <div class="table-loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xs flex items-center justify-center z-10 hidden" id="tableLoader">
                <div class="animate-spin rounded-full h-8 w-8 border-2 border-indigo-600 border-t-transparent"></div>
            </div>

            <div id="table_data">
                @include('dashboard.store_withdrawals.partials._table')
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@can('store_withdrawals_create')
    @include('dashboard.store_withdrawals.modals.create')
@endcan

@can('store_withdrawals_update')
    @include('dashboard.store_withdrawals.modals.edit')
@endcan

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 in Filter Toolbar
        $('.js-filter-form .select2').select2({
            width: '100%',
            dir: $('html').attr('dir') || 'rtl'
        });

        // Initialize Select2 in Modals
        $('#createStoreWithdrawalModal .select2').select2({
            dropdownParent: $('#createStoreWithdrawalModal'),
            width: '100%',
            dir: $('html').attr('dir') || 'rtl'
        });

        $('#updateStoreWithdrawalModal .select2').select2({
            dropdownParent: $('#updateStoreWithdrawalModal'),
            width: '100%',
            dir: $('html').attr('dir') || 'rtl'
        });

        // Live Search on input
        let searchTimeout;
        $('input[name="keyword"]').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                fetch_data(1);
            }, 300);
        });

        // Auto filter on select change
        $('#filter_store_id, #filter_store_bank_account_id, input[name="specific_date"]').on('change', function() {
            fetch_data(1);
        });

        // Reset Filter
        $('.js-reset-btn').on('click', function() {
            $('input[name="keyword"]').val('');
            $('input[name="specific_date"]').val('');
            $('#filter_store_id').val('').trigger('change.select2');
            $('#filter_store_bank_account_id').val('').trigger('change.select2');
            fetch_data(1);
        });

        // Reset Form Validation Errors on Modal Close
        $('#createStoreWithdrawalModal, #updateStoreWithdrawalModal').on('hidden.bs.modal', function() {
            $(this).find('.error-text').text('');
            $(this).find('.form-input-modern').removeClass('border-rose-500');
        });
    });

    function fetch_data(page) {
        $('#tableLoader').removeClass('hidden');
        let keyword = $('input[name="keyword"]').val() || '';
        let store_id = $('#filter_store_id').val() || '';
        let store_bank_account_id = $('#filter_store_bank_account_id').val() || '';
        let specific_date = $('input[name="specific_date"]').val() || '';

        $.ajax({
            url: "{!! route('dashboard.store-withdrawals.index') !!}?page=" + page,
            data: {
                keyword: keyword,
                store_id: store_id,
                store_bank_account_id: store_bank_account_id,
                specific_date: specific_date
            },
            success: function(data) {
                $('#table_data').html(data);
                let totalCount = $('#store_withdrawals-total-count').val();
                if (totalCount !== undefined) {
                    $('#total-count-badge').text(totalCount + ' {!! __('general.records') ?? 'سجل' !!}');
                }
                $('#tableLoader').addClass('hidden');
            },
            error: function() {
                $('#tableLoader').addClass('hidden');
            }
        });
    }

    $(document).on('click', '#table_data .pagination a', function(event) {
        event.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    // Handle AJAX Success Custom Event (reload-table)
    $(document).on('ajax-form-success', function(e, data) {
        if(data.action === 'reload-table') {
            fetch_data(1);
        }
    });
</script>
@endpush
