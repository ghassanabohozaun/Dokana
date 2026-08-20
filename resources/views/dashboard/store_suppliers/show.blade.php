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
            <a href="{!! route('dashboard.store-suppliers.index') !!}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                {!! __('store_suppliers.store_suppliers') !!}
            </a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-200 font-bold">{!! $supplier->name !!}</span>
        </nav>

        <!-- Back Button -->
        <a href="{!! route('dashboard.store-suppliers.index') !!}" class="btn-secondary-modern text-xs">
            <i class="fas fa-arrow-right text-xs"></i>
            <span>{!! __('general.back') !!}</span>
        </a>
    </div>

    <!-- 2. Profile Grid: Left Sidebar & Right Ledger (Same Layout as Customer Show) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Sidebar: Basic Info Card & Financial Summary Card (4 cols) -->
        <div class="lg:col-span-4 space-y-5">
            
            <!-- 1. Supplier Basic Details Card -->
            <div class="dash-card p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-xs">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('store_suppliers.supplier_details') !!}
                    </h3>
                </div>

                <!-- Avatar & Identity -->
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white font-black text-lg shadow-sm">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate">
                            {!! $supplier->name !!}
                        </h4>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="badge-pill {{ $supplier->status ? 'badge-pill-success' : 'badge-pill-danger' }} text-[10px]">
                                {{ $supplier->status ? __('general.enable') : __('general.disabled') }}
                            </span>
                            @if($supplier->store)
                                <span class="badge-pill badge-pill-info text-[10px]">
                                    <i class="fas fa-store text-[9px] me-0.5"></i> {{ $supplier->store->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Direct Contact Buttons (Call & WhatsApp) -->
                @if($supplier->mobile)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $supplier->mobile);
                    if (str_starts_with($cleanPhone, '05')) {
                        $waPhone = '970' . substr($cleanPhone, 1);
                    } elseif (str_starts_with($cleanPhone, '5')) {
                        $waPhone = '970' . $cleanPhone;
                    } else {
                        $waPhone = $cleanPhone;
                    }
                @endphp
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="tel:{{ $supplier->mobile }}" class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-950/40 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all shadow-2xs">
                        <i class="fas fa-phone-alt text-emerald-500 text-[11px]"></i>
                        <span>{!! __('store_suppliers.call') !!}</span>
                    </a>
                    <a href="https://wa.me/{{ $waPhone }}" target="_blank" class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-950/40 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all shadow-2xs">
                        <i class="fab fa-whatsapp text-emerald-500 text-sm"></i>
                        <span>{!! __('store_suppliers.whatsapp') !!}</span>
                    </a>
                </div>
                @endif

                <!-- Info List -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    @if($supplier->mobile)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-phone text-[11px] text-slate-400"></i>
                            {!! __('store_suppliers.mobile') !!}:
                        </span>
                        <span class="font-mono font-bold text-slate-700 dark:text-slate-200" dir="ltr">
                            {{ $supplier->mobile }}
                        </span>
                    </div>
                    @endif

                    @if($supplier->bank_name || $supplier->account_number)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-university text-[11px] text-slate-400"></i>
                            {!! __('bank_accounts.bank_account') !!}:
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-200 truncate max-w-[160px]" dir="ltr">
                            {{ $supplier->bank_name ?: '' }} {{ $supplier->account_number ? '(' . $supplier->account_number . ')' : '' }}
                        </span>
                    </div>
                    @endif

                    @if($supplier->email)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-envelope text-[11px] text-slate-400"></i>
                            {!! __('store_suppliers.email') !!}:
                        </span>
                        <span class="font-medium text-slate-700 dark:text-slate-200 truncate max-w-[160px]">
                            {{ $supplier->email }}
                        </span>
                    </div>
                    @endif

                    @if($supplier->address)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-[11px] text-slate-400"></i>
                            {!! __('store_suppliers.address') !!}:
                        </span>
                        <span class="font-medium text-slate-700 dark:text-slate-200 truncate max-w-[160px]">
                            {{ $supplier->address }}
                        </span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fas fa-calendar-alt text-[11px] text-slate-400"></i>
                            {!! __('store_suppliers.date') !!}:
                        </span>
                        <span class="font-medium text-slate-600 dark:text-slate-400" dir="ltr">
                            {{ $supplier->created_at ? $supplier->created_at->format('Y-m-d') : '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. Financial Summary Card -->
            <div class="dash-card p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('store_customers.financial_summary') !!}
                    </h3>
                </div>

                <!-- Remaining Balance Highlight Box -->
                @php
                    $remaining = $supplier->invoices_sum_remaining_amount ?? 0;
                    $totalInvoices = $supplier->invoices_sum_total_amount ?? 0;
                    $totalPaid = $supplier->invoices_sum_paid_amount ?? 0;
                @endphp
                <div class="p-4 rounded-2xl {{ $remaining > 0 ? 'bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200/80 dark:border-rose-900/40' : 'bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-900/40' }} text-center">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">
                        {!! __('store_suppliers.remaining_due') !!}
                    </span>
                    <div class="font-mono font-black text-2xl {{ $remaining > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" dir="ltr">
                        {{ number_format($remaining, 2) }}
                        <span class="text-xs font-bold text-slate-400 ms-1">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    </div>
                    <span class="text-[11px] font-bold mt-1 inline-block {{ $remaining > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        @if($remaining > 0)
                            <i class="fas fa-exclamation-circle text-[10px]"></i> {!! __('store_suppliers.due_for_payment') !!}
                        @else
                            <i class="fas fa-check-circle text-[10px]"></i> {!! __('store_suppliers.fully_settled') !!}
                        @endif
                    </span>
                </div>

                <!-- Invoices & Paid Sub-Boxes -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">{!! __('store_suppliers.total_purchases') !!}</span>
                        <span class="font-mono font-bold text-sm text-slate-800 dark:text-white block" dir="ltr">
                            {{ number_format($totalInvoices, 2) }}
                        </span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-0.5">{!! __('store_suppliers.total_payments_made') !!}</span>
                        <span class="font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400 block" dir="ltr">
                            {{ number_format($totalPaid, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Main Section: Invoices & Payments Ledger Tabs Card (8 cols) -->
        <div class="lg:col-span-8">
            <div class="dash-card overflow-hidden">
                <!-- Segmented Tabs Header -->
                <div class="p-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80 flex items-center justify-between gap-2 overflow-x-auto">
                    <div class="flex items-center gap-2">
                        <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-2xs" data-tab="invoices">
                            <i class="fas fa-file-invoice-dollar text-indigo-500 text-xs"></i>
                            <span>{!! __('store_suppliers.supplier_invoices_tab') !!}</span>
                        </button>

                        <button type="button" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/40" data-tab="payments">
                            <i class="fas fa-receipt text-emerald-500 text-xs"></i>
                            <span>{!! __('store_suppliers.supplier_payments_tab') !!}</span>
                        </button>
                    </div>

                    @can('store_supplier_invoices_create')
                        <a href="{!! route('dashboard.store-supplier-invoices.index') !!}" class="btn-primary-modern text-xs hidden sm:inline-flex items-center gap-1.5 py-2 px-3">
                            <i class="fas fa-plus text-[10px]"></i>
                            <span>{!! __('store_suppliers.add_invoice') !!}</span>
                        </a>
                    @endcan
                </div>

                <!-- Tab Body Container with Smooth AJAX Loading -->
                <div class="relative min-h-[260px]" id="tabContentWrapper">
                    <div class="table-loader-overlay absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xs flex items-center justify-center z-10 hidden" id="tableLoader">
                        <span class="premium-loader"></span>
                    </div>

                    <div id="dynamicTabContent" data-ajax-container="true">
                        @include('dashboard.store_suppliers.partials._show_invoices_table', ['invoices' => $invoices, 'supplier' => $supplier])
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
        let currentTab = 'invoices';
        let tabCache = {
            'invoices': $('#dynamicTabContent').html()
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
