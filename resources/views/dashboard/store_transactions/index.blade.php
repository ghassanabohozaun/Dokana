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
                <span class="text-slate-700 dark:text-slate-200 font-bold">{!! $title !!}</span>
            </nav>

            <!-- Page Title & Counter Badge -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('store_transactions.store_transactions') !!}
                        </h1>
                        <span id="total-count-badge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $store_transactions->total() !!} {!! __('general.records') !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @can('store_transactions_create')
                <button type="button" class="btn-primary-gradient" data-toggle="modal" data-target="#createStoreTransactionModal">
                    <i class="fas fa-plus text-xs"></i>
                    <span>{!! __('store_transactions.create_new_store_transaction') !!}</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- 2. Real-Time Stats Overview -->
    @include('dashboard.store_transactions.partials._stats')

    <!-- 3. Search & Filter Bar -->
    @include('dashboard.store_transactions.partials._search')

    <!-- 4. Main Transactions Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.store_transactions.partials._table')
        </div>
    </div>

</div>

<!-- Modals -->
@can('store_transactions_create')
    @include('dashboard.store_transactions.modals.create')
@endcan

@can('store_transactions_update')
    @include('dashboard.store_transactions.modals.edit')
@endcan

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Scoped Select2 for Modal dropdowns
        if ($('#store_id_dept_create').length) {
            $('#store_id_dept_create').select2({
                dropdownParent: $('#createStoreTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_customer_id_create').length) {
            $('#store_customer_id_create').select2({
                dropdownParent: $('#createStoreTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_bank_account_id_create').length) {
            $('#store_bank_account_id_create').select2({
                dropdownParent: $('#createStoreTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_id_dept_edit').length) {
            $('#store_id_dept_edit').select2({
                dropdownParent: $('#updateStoreTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_customer_id_edit').length) {
            $('#store_customer_id_edit').select2({
                dropdownParent: $('#updateStoreTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_bank_account_id_edit').length) {
            $('#store_bank_account_id_edit').select2({
                dropdownParent: $('#updateStoreTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        // Sync Metrics from ajax response
        $(document).ajaxSuccess(function(event, xhr, settings) {
            if ($('#ajax-metrics-data').length) {
                let $m = $('#ajax-metrics-data');
                $('#ui_stats_total_payments').text($m.data('total-payments'));
                $('#ui_stats_total_debts').text($m.data('total-debts'));
                $('#ui_stats_net_balance').text($m.data('net-balance'));
                $('#ui_stats_total_count').text($m.data('total-count'));
            }
        });
    });
</script>
@endpush
