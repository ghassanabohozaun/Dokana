@extends('layouts.dashboard.app')

@section('title', $title)

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        
        <!-- Breadcrumbs -->
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-6 col-12">
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb premium-breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{!! route('dashboard.index') !!}"><i class="fas fa-home"></i> {!! __('dashboard.home') !!}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{!! route('dashboard.store-customers.index') !!}">{!! __('store_customers.store_customers') !!}</a>
                            </li>
                            <li class="breadcrumb-item active font-weight-bold">
                                {!! $store_customer->name !!}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12 text-md-right">
                <a href="{!! route('dashboard.store-customers.index') !!}" class="btn btn-premium-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i> {!! __('general.back') !!}
                </a>
            </div>
        </div>

        <div class="content-body">
            <!-- Top Banner -->
            <div class="profile-banner mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar-box mr-3 ml-3">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold" style="font-size: 26px;">
                                {!! $store_customer->name !!}
                            </h2>
                            <div class="d-flex flex-wrap" style="gap: 16px;">
                                <div class="profile-meta-item">
                                    <i class="fas fa-phone-alt text-success"></i>
                                    <span>{!! $store_customer->phone ?? __('general.not_found') !!}</span>
                                </div>
                                <div class="profile-meta-item">
                                    <i class="fas fa-store text-warning"></i>
                                    <span>{!! optional($store_customer->store)->name ?? __('roles.global_role') !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2 mt-md-0">
                        @if($store_customer->status)
                            <div class="profile-badge-status">
                                <i class="fas fa-check-circle"></i> {!! __('general.active') !!}
                            </div>
                        @else
                            <div class="profile-badge-status inactive">
                                <i class="fas fa-times-circle"></i> {!! __('general.disabled') !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="row">
                <!-- Left Sidebar: Financial Summary -->
                <div class="col-md-4 d-flex flex-column" id="left-profile-column">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <i class="fas fa-wallet"></i> {!! __('store_customers.financial_summary') !!}
                        </div>
                        <div class="profile-card-body">
                            <div class="row">
                                <div class="col-6 pr-2">
                                    <div class="finance-summary-block">
                                        <div class="finance-summary-title">{!! __('store_customers.total_payments') !!}</div>
                                        <div class="finance-summary-amount finance-payment-text">{!! number_format($store_customer->total_payments, 2) !!}</div>
                                    </div>
                                </div>
                                <div class="col-6 pl-2">
                                    <div class="finance-summary-block">
                                        <div class="finance-summary-title">{!! __('store_customers.total_debts') !!}</div>
                                        <div class="finance-summary-amount finance-debt-text">{!! number_format($store_customer->total_debts, 2) !!}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="finance-total-block">
                                <div class="finance-summary-title text-dark">{!! __('store_customers.current_balance') !!}</div>
                                <div class="finance-summary-amount {{ $store_customer->calculated_balance > 0 ? 'finance-debt-text' : ($store_customer->calculated_balance < 0 ? 'finance-payment-text' : 'text-secondary') }}">
                                    {!! number_format(abs($store_customer->calculated_balance), 2) !!}
                                    @if($store_customer->calculated_balance > 0)
                                        <span style="font-size: 14px; opacity: 0.8;">({!! __('store_transactions.debt') !!})</span>
                                    @elseif($store_customer->calculated_balance < 0)
                                        <span style="font-size: 14px; opacity: 0.8;">({!! __('store_transactions.payment') !!})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card flex-grow-1 mb-0 d-flex flex-column">
                        <div class="profile-card-header">
                            <i class="fas fa-info-circle"></i> {!! __('store_customers.customer_details') !!}
                        </div>
                        <div class="profile-card-body flex-grow-1">
                            <div class="details-grid">
                                <div class="detail-box">
                                    <div class="detail-box-label"><i class="fas fa-calendar-alt text-primary mr-1"></i> {!! __('general.created_at') !!}</div>
                                    <div class="detail-box-value">{!! $store_customer->created_at->format('Y-m-d') !!}</div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-box-label"><i class="fas fa-chart-line text-warning mr-1"></i> إجمالي الحركات</div>
                                    <div class="detail-box-value text-primary" style="font-size: 15px;">{!! $transactions->total() !!} حركة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content: Transactions -->
                <div class="col-md-8 mb-4 d-flex flex-column">
                    <div class="profile-card flex-grow-1 mb-0 d-flex flex-column" id="right-transactions-card">
                        <div class="profile-card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-list-alt"></i> {!! __('store_transactions.store_transactions') !!}
                            </div>
                            <!-- Add Transaction Button directly from profile -->
                            @can('store_transactions_create')
                                @if($store_customer->status == 1)
                                    <button type="button" class="btn btn-sm btn-premium-add shadow-pulse add_transaction_button"
                                        data-customer-id="{!! $store_customer->id !!}" 
                                        data-customer-name="{!! $store_customer->name !!}" 
                                        data-store-id="{!! $store_customer->store_id !!}" 
                                        data-store-name="{!! optional($store_customer->store)->name !!}">
                                        <i class="fas fa-plus"></i> {!! __('general.add') !!} {!! __('store_transactions.store_transaction') !!}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm" style="background-color: #f1f5f9; color: #94a3b8; border: 1px dashed #cbd5e1; cursor: not-allowed; font-weight: 600; border-radius: 8px; padding: 6px 16px;" disabled
                                        title="{!! __('store_transactions.customer_is_disabled') !!}">
                                        <i class="fas fa-lock mr-1"></i> {!! __('general.add') !!} {!! __('store_transactions.store_transaction') !!}
                                    </button>
                                @endif
                            @endcan
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-loader-container">
                                <div class="table-loader-overlay" id="tableLoader">
                                    <span class="premium-loader"></span>
                                </div>
                                <div id="table_data">
                                    @include('dashboard.store_customers.partials._transactions_table')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@can('store_transactions_create')
@include('dashboard.store_customers.modals.add_transaction')
@endcan

@endsection

@push('scripts')
<script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
<script>
    // Include the same add_transaction logic here to allow adding from profile page
    $(document).on('click', '.add_transaction_button', function() {
        let customerId = $(this).data('customer-id');
        let customerName = $(this).data('customer-name');
        let storeId = $(this).data('store-id');
        let storeName = $(this).data('store-name');

        $('#hidden_store_id_create').val(storeId || '');
        $('#hidden_store_customer_id_create').val(customerId);

        if ($('#visible_store_id_create').length) {
            $('#visible_store_id_create').val(storeName || "{!! __('roles.global_role') !!}");
        }
        $('#visible_store_customer_id_create').val(customerName);

        $('#transaction_amount_create').val('');
        $('#transaction_description_create').val('');
        $('#transaction_type_create').val('');
        $('.error-text').text('');

        $('#addCustomerTransactionModal').modal('show');
    });

    // When the form is successfully submitted (using ajax-form.js), reload the page to see updated details
    $(document).on('ajaxFormSuccess', '#add_customer_transaction_form', function() {
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    });

    $(document).ready(function() {
        if (typeof initIndexTable === "function") {
            initIndexTable();
        }
    });
</script>
@endpush
