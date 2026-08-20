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
            <a href="{!! route('dashboard.store-customers.index') !!}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                {!! __('store_customers.store_customers') !!}
            </a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-200 font-bold">{!! $store_customer->name !!}</span>
        </nav>

        <!-- Back Button -->
        <a href="{!! route('dashboard.store-customers.index') !!}" class="btn-secondary-modern text-xs">
            <i class="fas fa-arrow-right text-xs"></i>
            <span>{!! __('general.back') !!}</span>
        </a>
    </div>

    <!-- 2. Profile Grid: Info & Financial Sidebars & Transactions Table -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Sidebar: Basic Info Card & Financial Summary Card (4 cols) -->
        <div class="lg:col-span-4 space-y-5">
            
            <!-- 1. Customer Basic Info Card -->
            <div class="dash-card p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('store_customers.customer_details') !!}
                    </h3>
                </div>

                <!-- Avatar & Identity -->
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white font-black text-lg shadow-sm">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate">
                            {!! $store_customer->name !!}
                        </h4>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            @if($store_customer->is_walk_in)
                                <span class="badge-pill badge-pill-info text-[10px]">زبون مباشر</span>
                            @endif
                            <span class="badge-pill {{ $store_customer->status ? 'badge-pill-success' : 'badge-pill-danger' }} text-[10px]">
                                {{ $store_customer->status ? __('general.enable') : __('general.disabled') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info List (Phone, Store, Date) -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    @if($store_customer->phone)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-phone-alt text-[11px] text-emerald-500"></i>
                            {!! __('store_customers.phone') !!}:
                        </span>
                        <a href="tel:{{ $store_customer->phone }}" class="font-bold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" dir="ltr">
                            {{ $store_customer->phone }}
                        </a>
                    </div>
                    @endif

                    @if($store_customer->store)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-store text-[11px] text-indigo-500"></i>
                            {!! __('stores.store') !!}:
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-200">
                            {{ $store_customer->store->name }}
                        </span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-calendar-alt text-[11px] text-slate-400"></i>
                            {!! __('store_customers.created_at') !!}:
                        </span>
                        <span class="font-medium text-slate-600 dark:text-slate-400" dir="ltr">
                            {{ $store_customer->created_at->format('Y-m-d') }}
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
                @php
                    $balance = $store_customer->calculated_balance;
                @endphp
                <div class="p-4 rounded-2xl {{ $balance > 0 ? 'bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40' : ($balance < 0 ? 'bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40' : 'bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80') }} text-center">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">
                        {!! __('store_customers.current_balance') !!}
                    </span>
                    <div class="font-mono font-black text-2xl {{ $balance > 0 ? 'text-rose-600 dark:text-rose-400' : ($balance < 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300') }}" dir="ltr">
                        {{ number_format(abs($balance), 2) }}
                        <span class="text-xs font-bold text-slate-400 ms-1">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    </div>
                    <span class="text-[11px] font-bold mt-1 inline-block {{ $balance > 0 ? 'text-rose-600' : ($balance < 0 ? 'text-emerald-600' : 'text-slate-400') }}">
                        @if($balance > 0)
                            <i class="fas fa-exclamation-circle text-[10px]"></i> مدين (مستحق عليه)
                        @elseif($balance < 0)
                            <i class="fas fa-check-circle text-[10px]"></i> دائن (رصيد لصالحه)
                        @else
                            خالص الرصيد
                        @endif
                    </span>
                </div>

                <!-- Debts & Payments Sub-Boxes -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">{!! __('store_customers.total_debts') !!}</span>
                        <span class="font-mono font-bold text-sm text-rose-600 dark:text-rose-400 block" dir="ltr">
                            {{ number_format($store_customer->total_debts ?? 0, 2) }}
                        </span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">{!! __('store_customers.total_payments') !!}</span>
                        <span class="font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400 block" dir="ltr">
                            {{ number_format($store_customer->total_payments ?? 0, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Debt Ceiling & Settings -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">{!! __('store_customers.max_debt_limit') !!}:</span>
                        <span class="font-mono font-bold text-slate-700 dark:text-slate-200" dir="ltr">
                            {{ $store_customer->max_debt_limit ? number_format($store_customer->max_debt_limit, 2) : __('general.unlimited') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">{!! __('store_customers.bypass_debt_limit') !!}:</span>
                        <span class="font-bold {{ $store_customer->bypass_debt_limit ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $store_customer->bypass_debt_limit ? __('general.yes') : __('general.no') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions History Card (8 cols) -->
        <div class="lg:col-span-8">
            <div class="dash-card overflow-hidden">
                <div class="p-4 px-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                            كشف الحساب وسجل الحركات
                        </h3>
                    </div>
                    <span class="badge-pill badge-pill-info text-[11px]">
                        {{ $transactions->total() }} حركة
                    </span>
                </div>

                <div id="transactions_table_container">
                    @include('dashboard.store_customers.partials._transactions_table', ['transactions' => $transactions, 'store_customer' => $store_customer])
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
