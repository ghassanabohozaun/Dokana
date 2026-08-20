@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- ========================================== -->
    <!-- 1. ENTERPRISE WELCOME HEADER CARD          -->
    <!-- ========================================== -->
    <div class="dash-card p-5 sm:p-6 relative overflow-hidden bg-gradient-to-br from-white via-slate-50/70 to-indigo-50/25 dark:from-slate-900 dark:via-slate-900/90 dark:to-indigo-950/20 border border-slate-200/80 dark:border-slate-800">
        <!-- Subtle Decorative Background Glow -->
        <div class="absolute -top-16 -end-16 w-56 h-56 rounded-full bg-indigo-500/5 dark:bg-indigo-500/10 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -start-16 w-56 h-56 rounded-full bg-sky-500/5 dark:bg-sky-500/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- User Identity & Welcome -->
            <div class="flex items-center gap-3.5 sm:gap-4">
                <div class="relative flex h-12 w-12 sm:h-14 sm:w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-500 text-white font-black text-xl shadow-md shadow-indigo-500/20">
                    {{ mb_substr(user()->name, 0, 1) }}
                    <span class="absolute -bottom-1 -end-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900" title="{!! __('dashboard.active_session') !!}">
                        <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                    </span>
                </div>
                <div>
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>{!! greeting() !!}</span>
                        <span class="text-indigo-600 dark:text-indigo-400 font-black">{!! user()->name !!}</span>
                        <span class="inline-block hover:rotate-12 transition-transform cursor-default">👋</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1.5 flex-wrap">
                        <span class="font-bold text-slate-700 dark:text-slate-200">
                            {{ auth()->user()->store ? auth()->user()->store->name : (setting()->site_name ?? __('dashboard.dashboard')) }}
                        </span>
                        <span class="text-slate-300 dark:text-slate-600">•</span>
                        <span>{!! __('dashboard.overview_of_performance') !!}</span>
                    </p>
                </div>
            </div>

            <!-- Date & Badges -->
            <div class="flex items-center gap-2.5 self-start md:self-auto flex-wrap text-xs">
                <!-- Localized Date Pill -->
                <div class="inline-flex items-center gap-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 px-3.5 py-2 font-semibold text-slate-700 dark:text-slate-200 shadow-2xs">
                    <i class="fas fa-calendar-alt text-indigo-500 text-xs"></i>
                    <span>{{ date('l, d F Y') }}</span>
                </div>

                <!-- Store / Role Badge -->
                <div class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-900/60 px-3 py-2 font-bold text-indigo-700 dark:text-indigo-300">
                    <i class="fas fa-shield-alt text-xs text-indigo-500"></i>
                    <span>{{ user()->role ? user()->role->name : __('dashboard.admin') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. SPEED-DIAL QUICK ACTION BAR             -->
    <!-- ========================================== -->
    <div class="flex items-center gap-2.5 overflow-x-auto custom-scrollbar pb-1">
        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider shrink-0 me-1">
            <i class="fas fa-bolt text-amber-500 me-1"></i> {!! __('dashboard.quick_actions') !!}:
        </span>

        @can('store_transactions_create')
            <a href="{!! route('dashboard.store-transactions.index') !!}" class="btn-secondary-modern text-xs shrink-0 py-2 px-3">
                <i class="fas fa-exchange-alt text-indigo-500 text-[11px]"></i>
                <span>{!! __('dashboard.new_transaction') !!}</span>
            </a>
        @endcan

        @can('store_customers_create')
            <a href="{!! route('dashboard.store-customers.index') !!}" class="btn-secondary-modern text-xs shrink-0 py-2 px-3">
                <i class="fas fa-user-plus text-sky-500 text-[11px]"></i>
                <span>{!! __('dashboard.add_customer') !!}</span>
            </a>
        @endcan

        @can('store_supplier_payments_create')
            <a href="{!! route('dashboard.store-supplier-payments.index') !!}" class="btn-secondary-modern text-xs shrink-0 py-2 px-3">
                <i class="fas fa-receipt text-emerald-500 text-[11px]"></i>
                <span>{!! __('dashboard.add_supplier_payment') !!}</span>
            </a>
        @endcan

        @can('store_withdrawals_create')
            <a href="{!! route('dashboard.store-withdrawals.index') !!}" class="btn-secondary-modern text-xs shrink-0 py-2 px-3">
                <i class="fas fa-hand-holding-usd text-rose-500 text-[11px]"></i>
                <span>{!! __('dashboard.add_withdrawal') !!}</span>
            </a>
        @endcan

        @if($isSuperAdmin)
            @can('stores_create')
                <a href="{!! route('dashboard.stores.index') !!}" class="btn-primary-gradient text-xs shrink-0 py-2 px-3">
                    <i class="fas fa-plus text-[10px]"></i>
                    <span>{!! __('dashboard.add_new_store') !!}</span>
                </a>
            @endcan
        @endif
    </div>

    <!-- ========================================== -->
    <!-- 3. DYNAMIC METRIC KPI CARDS GRID           -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        @if ($isSuperAdmin)
            <!-- Super Admin Card 1: Stores Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.stores_count') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['stores_count'] !!}</h3>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="badge-pill badge-pill-success text-[10px]">
                                {!! $stats['active_stores_count'] !!} {!! __('dashboard.active_stores') !!}
                            </span>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-80"></div>
            </div>

            <!-- Super Admin Card 2: Users Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.system_users') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['users_count'] !!}</h3>
                        <p class="text-[11px] font-semibold text-purple-600 dark:text-purple-400 mt-1.5">
                            <i class="fas fa-shield-alt text-[10px] me-1"></i> {!! __('dashboard.secure_system') !!}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-users-cog"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-500 opacity-80"></div>
            </div>

            <!-- Super Admin Card 3: Customers Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.system_customers') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['customers_count'] !!}</h3>
                        <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mt-1.5">
                            <i class="fas fa-user-check text-[10px] me-1"></i> عبر جميع الفروع
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tag"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-80"></div>
            </div>

            <!-- Super Admin Card 4: Total Debts -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.total_debts') }}</p>
                        <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-400 mt-1" dir="ltr">{!! number_format($stats['total_debt'], 2) !!}</h3>
                        <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1.5">
                            <i class="fas fa-exclamation-triangle text-[10px] me-1"></i> إجمالي ديون المنصة
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-amber-500 opacity-80"></div>
            </div>
        @else
            <!-- Store Owner Card 1: Store Liquidity (Total Balances) -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.store_liquidity') }}</p>
                        <h3 class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 mt-1" dir="ltr">{!! number_format($stats['store_total_balance'], 2) !!}</h3>
                        <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-1.5">
                            <i class="fas fa-wallet text-[10px] text-indigo-500 me-1"></i> في كافة الصناديق والمحافظ
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-indigo-500 to-blue-500 opacity-80"></div>
            </div>

            <!-- Store Owner Card 2: Today Collections -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.today_collections') }}</p>
                        <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1" dir="ltr">{!! number_format($stats['today_collections'], 2) !!}</h3>
                        <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mt-1.5">
                            <i class="fas fa-arrow-down text-[10px] me-1"></i> سداد ديون ومقبوضات اليوم
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-80"></div>
            </div>

            <!-- Store Owner Card 3: Today Debts (New Credit) -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.today_debts') }}</p>
                        <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-400 mt-1" dir="ltr">{!! number_format($stats['today_debts'], 2) !!}</h3>
                        <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1.5">
                            <i class="fas fa-arrow-up text-[10px] me-1"></i> مبيعات آجلة مسجلة اليوم
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-amber-500 opacity-80"></div>
            </div>

            <!-- Store Owner Card 4: Net Financial Position -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.net_financial_position') }}</p>
                        <h3 class="text-2xl font-extrabold {{ $stats['net_balance'] >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400' }} mt-1" dir="ltr">
                            {!! number_format($stats['net_balance'], 2) !!}
                        </h3>
                        <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-1.5">
                            (ديون الزبائن - مستحقات الموردين)
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500 opacity-80"></div>
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- 4. ANALYTICAL CHARTS INTELLIGENCE GRID     -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Main Area Chart: Cashflow Analysis (8 cols) -->
        <div class="lg:col-span-8 dash-card p-5 md:p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                            {!! __('dashboard.financial_trend') !!}
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            مقارنة حركة الديون والتحصيلات والمصروفات خلال آخر 7 أيام
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs font-semibold">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span> ديون
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span> تحصيلات
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span> مصروفات
                    </span>
                </div>
            </div>

            <div id="dashboard-trend-chart" class="w-full" style="min-height: 320px;"></div>
        </div>

        <!-- Donut Chart: Liquidity Breakdown (4 cols) -->
        <div class="lg:col-span-4 dash-card p-5 md:p-6 space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                            {!! __('dashboard.liquidity_breakdown') !!}
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            توزيع السيولة النقدية في الخزائن والمحافظ
                        </p>
                    </div>
                </div>

                <div id="dashboard-liquidity-donut" class="w-full flex items-center justify-center py-4" style="min-height: 220px;"></div>
            </div>

            <!-- Liquidity Legend Summary -->
            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-slate-800 text-center text-xs">
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="text-[10px] text-slate-400 block mb-0.5">{!! __('dashboard.cash_box') !!}</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white text-[11px]" dir="ltr">{{ number_format($liquidityBreakdown['cash'], 0) }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="text-[10px] text-slate-400 block mb-0.5">{!! __('dashboard.electronic_wallets') !!}</span>
                    <span class="font-mono font-bold text-sky-600 dark:text-sky-400 text-[11px]" dir="ltr">{{ number_format($liquidityBreakdown['wallet'], 0) }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                    <span class="text-[10px] text-slate-400 block mb-0.5">{!! __('dashboard.bank_accounts') !!}</span>
                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 text-[11px]" dir="ltr">{{ number_format($liquidityBreakdown['bank'], 0) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- 5. REAL-TIME FEEDS & SMART TABLES GRID     -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Card 1: Debt Aging & Risk Radar (مراقبة أعمار الديون) -->
        <div class="dash-card overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-xs">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('dashboard.debt_aging_monitor') !!}
                    </h4>
                </div>
                <a href="{!! route('dashboard.store-customers.index') !!}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    {!! __('dashboard.view_all') !!}
                </a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800/60 max-h-[360px] overflow-y-auto custom-scrollbar">
                @forelse ($topDebtors as $debtor)
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $debtor->phone ?? '');
                        if (str_starts_with($cleanPhone, '05')) {
                            $waPhone = '970' . substr($cleanPhone, 1);
                        } elseif (str_starts_with($cleanPhone, '5')) {
                            $waPhone = '970' . $cleanPhone;
                        } else {
                            $waPhone = $cleanPhone;
                        }
                    @endphp
                    <div class="p-3.5 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 font-bold text-xs">
                                {{ mb_substr($debtor->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <a href="{!! route('dashboard.store-customers.show', $debtor->id) !!}" class="text-xs font-bold text-slate-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 block truncate">
                                    {{ $debtor->name }}
                                </a>
                                <span class="text-[11px] text-slate-400 font-mono block truncate" dir="ltr">
                                    {{ $debtor->phone ?: '—' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="font-mono font-black text-xs text-rose-600 dark:text-rose-400" dir="ltr">
                                {{ number_format($debtor->balance, 2) }}
                            </span>
                            @if($debtor->phone)
                                <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode('مرحباً أخي ' . $debtor->name . '، نود تذكيرك برصيدك المستحق: ' . number_format($debtor->balance, 2) . ' شيكل') }}" target="_blank" class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 hover:bg-emerald-100 transition-colors shadow-2xs" title="{!! __('dashboard.whatsapp_remind') !!}">
                                    <i class="fab fa-whatsapp text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        <i class="fas fa-check-circle text-2xl mb-2 text-emerald-500 block"></i>
                        لا توجد ديون مستحقة متأخرة
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Card 2: Live Activity Feed (أحدث الحركات الحية) -->
        <div class="dash-card overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs">
                        <i class="fas fa-history"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('dashboard.recent_live_transactions') !!}
                    </h4>
                </div>
                <a href="{!! route('dashboard.store-transactions.index') !!}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    {!! __('dashboard.view_all') !!}
                </a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800/60 max-h-[360px] overflow-y-auto custom-scrollbar">
                @forelse ($recentTransactions as $tx)
                    <div class="p-3.5 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $tx->type === 'payment' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' }} text-xs">
                                <i class="fas {{ $tx->type === 'payment' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                    {{ optional($tx->customer)->name ?: 'حركة عامة' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono" dir="ltr">
                                    {{ $tx->transaction_date ? $tx->transaction_date->format('Y-m-d H:i') : $tx->created_at->format('Y-m-d H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="text-end shrink-0">
                            <span class="font-mono font-black text-xs {{ $tx->type === 'payment' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} block" dir="ltr">
                                {{ $tx->type === 'payment' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                            </span>
                            <span class="badge-pill {{ $tx->type === 'payment' ? 'badge-pill-success' : 'badge-pill-danger' }} text-[9px] py-0 px-1.5">
                                {{ $tx->type === 'payment' ? 'تحصيل' : 'دين' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        <i class="fas fa-exchange-alt text-2xl mb-2 opacity-40 block"></i>
                        {!! __('dashboard.no_recent_transactions') !!}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Card 3: Pending Supplier Invoices OR Super Admin Recent Stores -->
        <div class="dash-card overflow-hidden">
            @if ($isSuperAdmin)
                <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs">
                            <i class="fas fa-store"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                            {!! __('dashboard.recent_joined_stores') !!}
                        </h4>
                    </div>
                    <a href="{!! route('dashboard.stores.index') !!}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                        {!! __('dashboard.view_all') !!}
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/60 max-h-[360px] overflow-y-auto custom-scrollbar">
                    @forelse ($recentStores as $store)
                        <div class="p-3.5 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 font-bold text-xs">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                        {{ $store->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $store->customers_count }} زبون • {{ $store->users_count }} مستخدم
                                    </span>
                                </div>
                            </div>

                            <span class="badge-pill {{ $store->status ? 'badge-pill-success' : 'badge-pill-danger' }} text-[10px] shrink-0">
                                {{ $store->status ? __('general.enable') : __('general.disabled') }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-xs">
                            {!! __('dashboard.no_stores') !!}
                        </div>
                    @endforelse
                </div>
            @else
                <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-xs">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                            {!! __('dashboard.recent_supplier_invoices') !!}
                        </h4>
                    </div>
                    <a href="{!! route('dashboard.store-supplier-invoices.index') !!}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                        {!! __('dashboard.view_all') !!}
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/60 max-h-[360px] overflow-y-auto custom-scrollbar">
                    @forelse ($recentSupplierInvoices as $inv)
                        <div class="p-3.5 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 font-bold text-xs">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                        {{ optional($inv->supplier)->name }}
                                    </span>
                                    <span class="font-mono text-[10px] text-indigo-600 dark:text-indigo-400" dir="ltr">
                                        #{{ $inv->invoice_number }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-end shrink-0">
                                <span class="font-mono font-black text-xs text-rose-600 dark:text-rose-400 block" dir="ltr">
                                    {{ number_format($inv->remaining_amount, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400">مستحق للمورد</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-xs">
                            <i class="fas fa-check-circle text-2xl mb-2 text-emerald-500 block"></i>
                            {!! __('dashboard.no_pending_invoices') !!}
                        </div>
                    @endforelse
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#94a3b8' : '#64748b';
            const gridColor = isDarkMode ? '#1e293b' : '#f1f5f9';

            const isRTL = document.documentElement.getAttribute('dir') === 'rtl';

            // 1. Cash Flow Multi-Series Trend Area Chart
            const trendChartOptions = {
                chart: {
                    type: 'area',
                    height: 320,
                    parentHeightOffset: 0,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Tajawal, Manrope, sans-serif'
                },
                series: [
                    {
                        name: "{!! __('dashboard.new_debts_label') !!}",
                        data: @json($chartDebts)
                    },
                    {
                        name: "{!! __('dashboard.collections_label') !!}",
                        data: @json($chartPayments)
                    },
                    {
                        name: "{!! __('dashboard.expenses_label') !!}",
                        data: @json($chartWithdrawals)
                    }
                ],
                colors: ['#f43f5e', '#10b981', '#f59e0b'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: @json($chartDates),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        rotate: 0,
                        hideOverlappingLabels: true,
                        trim: true,
                        style: {
                            colors: textColor,
                            fontSize: '11px',
                            fontFamily: 'Tajawal, Manrope, sans-serif'
                        }
                    }
                },
                yaxis: {
                    opposite: isRTL,
                    labels: {
                        offsetX: isRTL ? -8 : 0,
                        formatter: function(val) {
                            return val >= 1000 ? (val / 1000).toFixed(1) + 'k' : val;
                        },
                        style: {
                            colors: textColor,
                            fontSize: '11px',
                            fontFamily: 'Tajawal, Manrope, sans-serif'
                        }
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light'
                },
                responsive: [
                    {
                        breakpoint: 640,
                        options: {
                            chart: {
                                height: 260
                            },
                            xaxis: {
                                labels: {
                                    style: {
                                        fontSize: '9px'
                                    }
                                }
                            },
                            yaxis: {
                                opposite: isRTL,
                                labels: {
                                    offsetX: isRTL ? -4 : 0,
                                    style: {
                                        fontSize: '9px'
                                    }
                                }
                            },
                            grid: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    bottom: 0,
                                    top: 0
                                }
                            }
                        }
                    }
                ]
            };

            const trendEl = document.querySelector("#dashboard-trend-chart");
            if (trendEl) {
                const trendChart = new ApexCharts(trendEl, trendChartOptions);
                trendChart.render();
            }

            // 2. Liquidity Distribution Donut Chart
            const donutSeries = [
                {{ (float) $liquidityBreakdown['cash'] }},
                {{ (float) $liquidityBreakdown['wallet'] }},
                {{ (float) $liquidityBreakdown['bank'] }}
            ];

            const donutChartOptions = {
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Tajawal, Manrope, sans-serif'
                },
                series: donutSeries.every(v => v === 0) ? [1] : donutSeries,
                labels: donutSeries.every(v => v === 0) ? ['لا توجد أرصدة'] : ["{!! __('dashboard.cash_box') !!}", "{!! __('dashboard.electronic_wallets') !!}", "{!! __('dashboard.bank_accounts') !!}"],
                colors: donutSeries.every(v => v === 0) ? ['#cbd5e1'] : ['#10b981', '#0ea5e9', '#6366f1'],
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'إجمالي السيولة',
                                    fontSize: '11px',
                                    color: textColor,
                                    formatter: function () {
                                        const sum = donutSeries.reduce((a, b) => a + b, 0);
                                        return sum.toLocaleString() + ' ₪';
                                    }
                                }
                            }
                        }
                    }
                },
                legend: { show: false },
                stroke: { show: false },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light'
                }
            };

            const donutEl = document.querySelector("#dashboard-liquidity-donut");
            if (donutEl) {
                const donutChart = new ApexCharts(donutEl, donutChartOptions);
                donutChart.render();
            }
        });
    </script>
@endpush
