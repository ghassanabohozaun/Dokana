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
                                <a href="{!! route('dashboard.bank-accounts.index') !!}">{!! __('bank_accounts.bank_accounts') !!}</a>
                            </li>
                            <li class="breadcrumb-item active font-weight-bold">
                                {!! $account->paymentEntity ? $account->paymentEntity->getTranslation('name', app()->getLocale()) : '' !!}
                                {!! $account->account_type == 'bank' ? ' - ' . $account->account_number : '' !!}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12 text-md-right">
                <a href="{!! route('dashboard.bank-accounts.index') !!}" class="btn btn-premium-secondary shadow-sm">
                    <i class="fas fa-arrow-right ml-1"></i> {!! __('general.back') !!}
                </a>
            </div>
        </div>

        <div class="content-body">
            <!-- Top Banner -->
            <div class="profile-banner mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #334155 100%); box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.4);">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar-box mr-3 ml-3">
                            @if($account->account_type == 'wallet')
                                <i class="fas fa-wallet text-white"></i>
                            @else
                                <i class="fas fa-university text-white"></i>
                            @endif
                        </div>
                        <div>
                            <h2 class="mb-1 text-white font-weight-bold" style="font-size: 26px;">
                                {!! $account->paymentEntity ? $account->paymentEntity->getTranslation('name', app()->getLocale()) : '' !!}
                            </h2>
                            <div class="d-flex flex-wrap" style="gap: 16px;">
                                @if($account->account_number)
                                <div class="profile-meta-item">
                                    <i class="fas fa-hashtag text-info"></i>
                                    <span dir="ltr">{!! $account->account_number !!}</span>
                                </div>
                                @endif
                                <div class="profile-meta-item">
                                    <i class="fas fa-user text-success"></i>
                                    <span>{!! $account->account_holder_name !!}</span>
                                </div>
                                @if(isset($account->store))
                                <div class="profile-meta-item">
                                    <i class="fas fa-store text-warning"></i>
                                    <span>{!! $account->store->name !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2 mt-md-0">
                        @if($account->is_default)
                            <div class="profile-badge-status" style="background-color: rgba(255, 193, 7, 0.2); color: #ffc107; border-color: rgba(255, 193, 7, 0.3);">
                                <i class="fas fa-star"></i> {!! __('bank_accounts.is_default') !!}
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
                                        <div class="finance-summary-title">{!! __('bank_accounts.total_deposits') !!}</div>
                                        <div class="finance-summary-amount finance-payment-text">{!! number_format($totalDeposits, 2) !!}</div>
                                    </div>
                                </div>
                                <div class="col-6 pl-2">
                                    <div class="finance-summary-block">
                                        <div class="finance-summary-title">{!! __('bank_accounts.total_withdrawals') !!}</div>
                                        <div class="finance-summary-amount finance-debt-text">{!! number_format($totalWithdrawals, 2) !!}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="finance-total-block">
                                <div class="finance-summary-title text-dark">{!! __('general.balance') !!}</div>
                                <div class="finance-summary-amount {{ $currentBalance >= 0 ? 'finance-payment-text' : 'finance-debt-text' }}">
                                    {!! number_format(abs($currentBalance), 2) !!} <small>₪</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card flex-grow-1 mb-0 d-flex flex-column">
                        <div class="profile-card-header">
                            <i class="fas fa-info-circle"></i> {!! __('bank_accounts.account_details') !!}
                        </div>
                        <div class="profile-card-body flex-grow-1">
                            <div class="details-grid">
                                <div class="detail-box">
                                    <div class="detail-box-label"><i class="fas fa-calendar-alt text-primary mr-1"></i> {!! __('general.created_at') !!}</div>
                                    <div class="detail-box-value" dir="ltr">{!! $account->created_at->format('Y-m-d') !!}</div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-box-label"><i class="fas fa-user-plus text-warning mr-1"></i> {!! __('departments.created_by') !!}</div>
                                    <div class="detail-box-value text-primary">{!! $account->creator->name ?? '---' !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content: Transactions -->
                <div class="col-md-8 mb-4 d-flex flex-column">
                    <div class="profile-card flex-grow-1 mb-0 d-flex flex-column" id="right-transactions-card">
                        <div class="profile-card-header p-0 border-bottom">
                            <ul class="nav nav-tabs nav-justified w-100 m-0 border-0" id="accountTabs" role="tablist" style="border-radius: 12px 12px 0 0; overflow: hidden;">
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold py-3 active" id="deposits-tab" data-toggle="tab" href="#deposits" role="tab" aria-controls="deposits" aria-selected="true" data-tab="deposits" style="border:none; border-right: 1px solid #e2e8f0; border-radius: 0;">
                                        <i class="fas fa-arrow-down text-success mr-1"></i> {!! __('bank_accounts.deposits_and_payments') !!}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold py-3" id="withdrawals-tab" data-toggle="tab" href="#withdrawals" role="tab" aria-controls="withdrawals" aria-selected="false" data-tab="withdrawals" style="border:none; border-radius: 0;">
                                        <i class="fas fa-arrow-up text-danger mr-1"></i> {!! __('bank_accounts.withdrawals') !!}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-loader-container mt-3" style="min-height: 300px;">
                                <div class="table-loader-overlay" id="tableLoader">
                                    <span class="premium-loader"></span>
                                </div>
                                <div class="tab-content" id="accountTabsContent">
                                    <div class="tab-pane show active" id="deposits" role="tabpanel" aria-labelledby="deposits-tab">
                                        <div id="deposits_table_data">
                                            @include('dashboard.bank_accounts.partials._deposits_table')
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="withdrawals" role="tabpanel" aria-labelledby="withdrawals-tab">
                                        <div id="withdrawals_table_data"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle tab change and pagination
    let currentTab = 'deposits';
    
    // Function to fetch data
    function fetchTabData(page = 1) {
        $('#tableLoader').css('display', 'flex').hide().fadeIn(200);
        
        $.ajax({
            url: window.location.pathname + "?tab=" + currentTab + "&page=" + page,
            type: "GET",
            success: function(response) {
                if (currentTab === 'deposits') {
                    $('#deposits_table_data').html(response);
                } else {
                    $('#withdrawals_table_data').html(response);
                }
                $('#tableLoader').fadeOut(200);
            },
            error: function() {
                $('#tableLoader').fadeOut(200);
            }
        });
    }

    // When clicking on a tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        currentTab = $(e.target).data('tab');
        
        // Check if content is empty before fetching
        if (currentTab === 'withdrawals' && $.trim($('#withdrawals_table_data').html()) === '') {
            fetchTabData(1);
        } else if (currentTab === 'deposits' && $.trim($('#deposits_table_data').html()) === '') {
            fetchTabData(1);
        }
    });

    // Handle pagination clicks within the tabs
    $(document).on('click', '.custom-pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchTabData(page);
    });
</script>
@endpush
