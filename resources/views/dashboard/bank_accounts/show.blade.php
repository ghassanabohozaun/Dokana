@extends('layouts.dashboard.app')

@section('title', $title)

@section('content')
<div class="space-y-6">
    
    <!-- 1. Header & Breadcrumbs Toolbar -->
    <div class="flex items-center justify-between gap-4">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500">
            <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-home text-xs"></i>
                <span>{!! __('dashboard.home') !!}</span>
            </a>
            <span>/</span>
            <a href="{!! route('dashboard.bank-accounts.index') !!}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                {!! __('bank_accounts.bank_accounts') !!}
            </a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-200 font-bold">
                {!! $account->paymentEntity ? $account->paymentEntity->getTranslation('name', app()->getLocale()) : '' !!}
            </span>
        </nav>

        <!-- Back Button -->
        <a href="{!! route('dashboard.bank-accounts.index') !!}" class="btn-secondary-modern text-xs">
            <i class="fas fa-arrow-right text-xs"></i>
            <span>{!! __('general.back') !!}</span>
        </a>
    </div>

    <!-- 2. Profile Grid: Left Sidebar & Right Transactions Ledger (Exact Style of Customer Show) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Sidebar: Basic Info Card & Financial Summary Card (4 cols) -->
        <div class="lg:col-span-4 space-y-5">
            
            <!-- 1. Account Details Card -->
            <div class="dash-card p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('bank_accounts.account_details') !!}
                    </h3>
                </div>

                <!-- Entity Icon & Identity -->
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $account->account_type == 'wallet' ? 'bg-gradient-to-tr from-sky-500 to-blue-600' : ($account->account_type == 'cash' ? 'bg-gradient-to-tr from-emerald-500 to-teal-600' : 'bg-gradient-to-tr from-indigo-600 to-blue-600') }} text-white font-black text-lg shadow-sm">
                        <i class="fas {{ $account->account_type == 'wallet' ? 'fa-wallet' : ($account->account_type == 'cash' ? 'fa-money-bill-wave' : 'fa-university') }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate">
                            {!! $account->paymentEntity ? $account->paymentEntity->getTranslation('name', app()->getLocale()) : '' !!}
                        </h4>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            @if($account->is_default)
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    <i class="fas fa-star text-[9px] me-0.5"></i> افتراضي
                                </span>
                            @endif
                            <span class="badge-pill {{ $account->account_type == 'wallet' ? 'badge-pill-info' : 'badge-pill-primary' }} text-[10px]">
                                {!! $account->account_type == 'wallet' ? __('bank_accounts.type_wallet') : ($account->account_type == 'cash' ? 'نقدي' : __('bank_accounts.type_bank')) !!}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info List (Number, Holder, Store, IBAN, Date) -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-hashtag text-[11px] text-slate-400"></i>
                            رقم الحساب:
                        </span>
                        <span class="font-mono font-bold text-slate-700 dark:text-slate-200" dir="ltr">
                            {{ $account->account_number }}
                        </span>
                    </div>

                    @if($account->account_holder_name)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-user text-[11px] text-slate-400"></i>
                            اسم صاحب الحساب:
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 truncate max-w-[150px]">
                            {{ $account->account_holder_name }}
                        </span>
                    </div>
                    @endif

                    @if(isset($account->store))
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-store text-[11px] text-indigo-500"></i>
                            {!! __('stores.store') !!}:
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-200">
                            {{ $account->store->name }}
                        </span>
                    </div>
                    @endif

                    @if($account->iban)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-barcode text-[11px] text-slate-400"></i>
                            الآيبان IBAN:
                        </span>
                        <span class="font-mono text-[11px] text-slate-600 dark:text-slate-300 select-all" dir="ltr">
                            {{ $account->iban }}
                        </span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-calendar-alt text-[11px] text-slate-400"></i>
                            تاريخ التسجيل:
                        </span>
                        <span class="font-medium text-slate-600 dark:text-slate-400" dir="ltr">
                            {{ $account->created_at ? $account->created_at->format('Y-m-d') : '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. Financial Summary Card -->
            <div class="dash-card p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('store_customers.financial_summary') !!}
                    </h3>
                </div>

                <!-- Balance Highlight Box -->
                <div class="p-4 rounded-2xl {{ $currentBalance >= 0 ? 'bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40' : 'bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40' }} text-center">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">
                        الرصيد الفعلي المتوفر
                    </span>
                    <div class="font-mono font-black text-2xl {{ $currentBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}" dir="ltr">
                        {{ number_format($currentBalance, 2) }}
                        <span class="text-xs font-bold text-slate-400 ms-1">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    </div>
                    <span class="text-[11px] font-bold mt-1 inline-block {{ $currentBalance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        @if($currentBalance > 0)
                            <i class="fas fa-check-circle text-[10px]"></i> رصيد متوفر
                        @elseif($currentBalance < 0)
                            <i class="fas fa-exclamation-circle text-[10px]"></i> رصيد مكشوف / سالب
                        @else
                            رصيد صفري
                        @endif
                    </span>
                </div>

                <!-- Deposits & Withdrawals Sub-Boxes -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">{!! __('bank_accounts.total_deposits') !!}</span>
                        <span class="font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400 block" dir="ltr">
                            {{ number_format($totalDeposits, 2) }}
                        </span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">{!! __('bank_accounts.total_withdrawals') !!}</span>
                        <span class="font-mono font-bold text-sm text-rose-600 dark:text-rose-400 block" dir="ltr">
                            {{ number_format($totalWithdrawals, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Main Section: Transactions & Ledger Tabs Card (8 cols) -->
        <div class="lg:col-span-8">
            <div class="dash-card overflow-hidden">
                <!-- Segmented Tabs Header -->
                <div class="p-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80 flex items-center gap-2 overflow-x-auto">
                    <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-2xs" data-tab="deposits">
                        <i class="fas fa-arrow-down text-emerald-500 text-xs"></i>
                        <span>{!! __('bank_accounts.deposits_and_payments') !!}</span>
                    </button>

                    <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/40" data-tab="withdrawals">
                        <i class="fas fa-arrow-up text-rose-500 text-xs"></i>
                        <span>{!! __('bank_accounts.withdrawals') !!}</span>
                    </button>

                    <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/40" data-tab="adjustments">
                        <i class="fas fa-balance-scale text-amber-500 text-xs"></i>
                        <span>{!! __('bank_accounts.adjustments') !!}</span>
                    </button>
                </div>

                <!-- Tab Body Container with Smooth AJAX Loading -->
                <div class="relative min-h-[260px]" id="tabContentWrapper">
                    <div class="table-loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xs flex items-center justify-center z-10 hidden" id="tableLoader">
                        <span class="premium-loader"></span>
                    </div>

                    <div id="dynamicTabContent" data-ajax-container="true">
                        @include('dashboard.bank_accounts.partials._deposits_table')
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let currentTab = 'deposits';
        let tabCache = {
            'deposits': $('#dynamicTabContent').html()
        };

        function fetchTabData(tab, page = 1) {
            $('#tableLoader').removeClass('hidden');
            
            $.ajax({
                url: window.location.pathname + "?tab=" + tab + "&page=" + page,
                type: "GET",
                success: function(response) {
                    $('#dynamicTabContent').html(response);
                    tabCache[tab] = response;
                    $('#tableLoader').addClass('hidden');

                    // Smooth Scroll to Top of Ledger Card
                    const $viewport = $('#main-viewport');
                    const $tabWrapper = $('#tabContentWrapper');
                    if ($viewport.length && $tabWrapper.length) {
                        const currentScroll = $viewport.scrollTop();
                        const elemOffsetTop = $tabWrapper.offset().top;
                        const viewportOffsetTop = $viewport.offset().top;
                        const targetScroll = currentScroll + (elemOffsetTop - viewportOffsetTop) - 20;

                        $viewport.stop().animate({
                            scrollTop: Math.max(0, targetScroll)
                        }, 300);
                    }
                },
                error: function() {
                    $('#tableLoader').addClass('hidden');
                }
            });
        }

        // Tab Switching
        $('.tab-btn').on('click', function() {
            let $btn = $(this);
            currentTab = $btn.data('tab');

            $('.tab-btn').removeClass('bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-2xs')
                .addClass('text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/40');
            
            $btn.addClass('bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-2xs')
                .removeClass('text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/40');

            if (tabCache[currentTab]) {
                $('#dynamicTabContent').html(tabCache[currentTab]);
            } else {
                fetchTabData(currentTab, 1);
            }
        });

        // Tab Pagination Clicks
        $(document).on('click', '#dynamicTabContent .pagination a, #dynamicTabContent [role="navigation"] a', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (!href || href === '#' || $(this).parent().hasClass('disabled')) return;
            const match = href.match(/[?&]page=([0-9]+)/);
            const page = match ? parseInt(match[1], 10) : 1;
            fetchTabData(currentTab, page);
        });
    });
</script>
@endpush
