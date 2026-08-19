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
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('store_customers.store_customers') !!}
                        </h1>
                        <span id="total-count-badge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $store_customers->total() !!} {!! __('general.records') !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @can('store_customers_create')
                <button type="button" class="btn-primary-gradient" data-toggle="modal" data-target="#createStoreCustomerModal">
                    <i class="fas fa-plus text-xs"></i>
                    <span>{!! __('store_customers.create_new_store_customer') !!}</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- 2. Real-Time Stats Overview -->
    @include('dashboard.store_customers.partials._stats')

    <!-- 3. Search & Filter Bar -->
    @include('dashboard.store_customers.partials._search')

    <!-- 4. Main Customers Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.store_customers.partials._table')
        </div>
    </div>

</div>

<!-- Modals -->
@can('store_customers_create')
    @include('dashboard.store_customers.modals.create')
@endcan

@can('store_customers_update')
    @include('dashboard.store_customers.modals.edit')
@endcan

@can('store_transactions_create')
    @include('dashboard.store_customers.modals.add_transaction')
@endcan

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Scoped Select2 for Modal dropdowns
        if ($('#store_id_dept_create').length) {
            $('#store_id_dept_create').select2({
                dropdownParent: $('#createStoreCustomerModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_id_dept_edit').length) {
            $('#store_id_dept_edit').select2({
                dropdownParent: $('#updateStoreCustomerModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#transaction_store_bank_account_id_create').length) {
            $('#transaction_store_bank_account_id_create').select2({
                dropdownParent: $('#addCustomerTransactionModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        // Open Add Transaction Modal on Click
        $(document).on('click', '.add_transaction_button', function(e) {
            e.preventDefault();
            let customerId = $(this).data('customer-id');
            let customerName = $(this).data('customer-name');
            let storeId = $(this).data('store-id');

            $('#hidden_store_id_create').val(storeId || '');
            $('#hidden_store_customer_id_create').val(customerId);
            $('#visible_store_customer_id_create').val(customerName);

            $('#transaction_amount_create').val('');
            $('#transaction_description_create').val('');
            $('#transaction_type_create').val('').trigger('change');
            
            $('#add_customer_transaction_form').find('.error-text').text('');
            $('#add_customer_transaction_form').find('.form-input-modern').removeClass('border-rose-500');

            $('#addCustomerTransactionModal').modal('show');
        });

        // Sync Metrics from ajax response
        $(document).ajaxSuccess(function(event, xhr, settings) {
            if ($('#ajax-metrics-data').length) {
                let $m = $('#ajax-metrics-data');
                $('#ui_stats_total_customers_count').text($m.data('total-customers'));
                $('#ui_stats_total_creditor_balances').text($m.data('total-creditor'));
                $('#ui_stats_total_debts').text($m.data('total-debts'));
                $('#ui_stats_net_balance').text($m.data('net-balance'));
                $('#ui_stats_total_lifetime_debts').text($m.data('lifetime-debts'));
                $('#ui_stats_total_lifetime_payments').text($m.data('lifetime-payments'));
            }
        });
    });
</script>
@endpush
