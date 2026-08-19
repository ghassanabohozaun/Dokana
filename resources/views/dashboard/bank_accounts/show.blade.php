@extends('layouts.dashboard.app')

@section('title', $title)

@section('content')
<div class="space-y-6">
    <!-- Top Header & Actions -->
    <div class="flex items-center justify-between gap-4">
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

        <div class="flex items-center gap-2">
            <a href="{!! route('dashboard.bank-accounts.index') !!}" class="btn-secondary-modern text-xs">
                <i class="fas fa-arrow-right text-xs"></i>
                <span>{!! __('general.back') !!}</span>
            </a>
        </div>
    </div>

    <!-- Hero Identity Banner Card -->
    <div class="dash-card p-6 relative overflow-hidden">
        <!-- Background Ambient Gradient Glow -->
        <div class="absolute -top-24 -end-24 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -start-24 w-72 h-72 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <!-- Icon Badge -->
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl {{ $account->account_type == 'wallet' ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 border border-sky-200/60 dark:border-sky-800/60' : ($account->account_type == 'cash' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-800/60') }} text-2xl shadow-sm">
                    <i class="fas {{ $account->account_type == 'wallet' ? 'fa-wallet' : ($account->account_type == 'cash' ? 'fa-money-bill-wave' : 'fa-university') }}"></i>
                </div>

                <!-- Titles & Meta Badges -->
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-lg md:text-xl font-bold text-slate-800 dark:text-white">
                            {!! $account->paymentEntity ? $account->paymentEntity->getTranslation('name', app()->getLocale()) : '' !!}
                        </h1>

                        @if($account->is_default)
                            <span class="badge-pill badge-pill-warning text-[11px]">
                                <i class="fas fa-star text-[10px] me-1"></i> {!! __('bank_accounts.is_default') !!}
                            </span>
                        @endif

                        <span class="badge-pill {{ $account->account_type == 'wallet' ? 'badge-pill-info' : 'badge-pill-primary' }} text-[11px]">
                            {!! $account->account_type == 'wallet' ? __('bank_accounts.type_wallet') : __('bank_accounts.type_bank') !!}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300" dir="ltr">
                            <i class="fas fa-hashtag text-[10px] text-slate-400"></i> {{ $account->account_number }}
                        </span>
                        <span>•</span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fas fa-user text-[10px] text-slate-400"></i> {{ $account->account_holder_name }}
                        </span>
                        @if(isset($account->store))
                            <span>•</span>
                            <span class="inline-flex items-center gap-1.5 font-medium text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-store text-[10px]"></i> {{ $account->store->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- IBAN Banner (if available) -->
            @if($account->iban)
            <div class="w-full md:w-auto p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">{!! __('bank_accounts.iban') !!}</span>
                <span class="text-xs font-mono font-bold text-slate-700 dark:text-slate-200 select-all" dir="ltr">
                    {{ $account->formatted_iban }}
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- Financial Stats Grid (3 Summary Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Current Balance -->
        <div class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{!! __('general.balance') !!}</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-xl {{ $currentBalance >= 0 ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400' }} text-xs">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="text-2xl font-black {{ $currentBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}" dir="ltr">
                {{ number_format($currentBalance, 2) }} <span class="text-xs font-semibold text-slate-400">₪</span>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">الرصيد الفعلي المتوفر في هذا الحساب</div>
        </div>

        <!-- Total Deposits -->
        <div class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{!! __('bank_accounts.total_deposits') !!}</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800 dark:text-white" dir="ltr">
                {{ number_format($totalDeposits, 2) }} <span class="text-xs font-semibold text-slate-400">₪</span>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">إجمالي الحركات المالية الواردة</div>
        </div>

        <!-- Total Withdrawals -->
        <div class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{!! __('bank_accounts.total_withdrawals') !!}</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-xs">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800 dark:text-white" dir="ltr">
                {{ number_format($totalWithdrawals, 2) }} <span class="text-xs font-semibold text-slate-400">₪</span>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">إجمالي السحوبات والمصروفات الصادرة</div>
        </div>
    </div>

    <!-- Interactive Activity Tabs Card -->
    <div class="dash-card overflow-hidden">
        <!-- Segmented Tab Header -->
        <div class="flex items-center border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80 p-2 gap-1.5 overflow-x-auto">
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
        <div class="relative min-h-[220px]" id="tabContentWrapper">
            <div class="table-loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xs flex items-center justify-center z-10 hidden" id="tableLoader">
                <div class="animate-spin rounded-full h-8 w-8 border-2 border-indigo-600 border-t-transparent"></div>
            </div>

            <div id="dynamicTabContent">
                @include('dashboard.bank_accounts.partials._deposits_table')
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
        $(document).on('click', '#dynamicTabContent .custom-pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchTabData(currentTab, page);
        });
    });
</script>
@endpush
