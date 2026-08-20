<input type="hidden" id="store_customers-total-count" value="{!! $store_customers->total() !!}">

@if (isset($metrics))
    <div id="ajax-metrics-data" class="hidden"
        data-total-customers="{!! number_format($metrics['total_customers_count'] ?? 0, 0) !!}"
        data-total-creditor="{!! number_format($metrics['total_creditor_balances'] ?? 0, 2) !!}"
        data-total-debts="{!! number_format($metrics['total_debts'] ?? 0, 2) !!}"
        data-net-balance="{!! number_format($metrics['net_balance'] ?? 0, 2) !!}"
        data-lifetime-debts="{!! number_format($metrics['total_lifetime_debts'] ?? 0, 2) !!}"
        data-lifetime-payments="{!! number_format($metrics['total_lifetime_payments'] ?? 0, 2) !!}">
    </div>
@endif

<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern" id="myTable">
        <thead>
            <tr>
                <th class="w-10 text-center">#</th>
                @if (isset($stores))
                    <th>{!! __('stores.store') !!}</th>
                @endif
                <th>{!! __('store_customers.name') !!}</th>
                <th>{!! __('store_customers.phone') !!}</th>
                <th class="text-center">{!! __('store_customers.total_debts') !!}</th>
                <th class="text-center">{!! __('store_customers.total_payments') !!}</th>
                <th class="text-center">{!! __('store_customers.current_balance') !!}</th>
                <th class="text-center">{!! __('store_customers.max_debt_limit') !!}</th>
                <th class="text-center">{!! __('general.status') !!}</th>
                <th>{!! __('store_customers.created_at') !!}</th>
                <th class="w-28 text-center sticky end-0 bg-slate-50 dark:bg-slate-800 z-10 border-s border-slate-200/80 dark:border-slate-700/80 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($store_customers as $store_customer)
                @php
                    $balance = $store_customer->calculated_balance;
                @endphp
                <tr id="row{{ $store_customer->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($store_customers->currentPage() - 1) * $store_customers->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($store_customer->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $store_customer->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Customer Name -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <a href="{!! route('dashboard.store-customers.show', $store_customer->id) !!}" 
                                       class="text-xs font-bold text-slate-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">
                                        {{ $store_customer->name }}
                                    </a>
                                    @if ($store_customer->is_walk_in)
                                        <span class="badge-pill badge-pill-info text-[9px]">
                                            زبون مباشر
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Phone -->
                    <td>
                        @if ($store_customer->phone)
                            <a href="tel:{{ $store_customer->phone }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" dir="ltr">
                                <i class="fas fa-phone-alt text-[10px] text-slate-400"></i>
                                <span>{{ $store_customer->phone }}</span>
                            </a>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Total Debts -->
                    <td class="text-center font-mono font-bold text-xs text-rose-600 dark:text-rose-400" dir="ltr">
                        {{ number_format($store_customer->total_debts ?? 0, 2) }}
                    </td>

                    <!-- Total Payments -->
                    <td class="text-center font-mono font-bold text-xs text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($store_customer->total_payments ?? 0, 2) }}
                    </td>

                    <!-- Current Balance -->
                    <td class="text-center">
                        @if ($balance > 0)
                            <span class="badge-pill badge-pill-danger font-mono text-xs" title="مدين (عليه)">
                                {{ number_format($balance, 2) }}
                            </span>
                        @elseif ($balance < 0)
                            <span class="badge-pill badge-pill-success font-mono text-xs" title="دائن (له)">
                                {{ number_format(abs($balance), 2) }} +
                            </span>
                        @else
                            <span class="badge-pill badge-pill-secondary text-xs font-bold">
                                0.00
                            </span>
                        @endif
                    </td>

                    <!-- Max Debt Limit -->
                    <td class="text-center">
                        @if ($store_customer->bypass_debt_limit)
                            <span class="badge-pill badge-pill-warning text-[10px]" title="{!! __('store_customers.bypass_debt_limit_desc') !!}">
                                {!! __('store_customers.bypass_debt_limit') !!}
                            </span>
                        @elseif ($store_customer->max_debt_limit && $store_customer->max_debt_limit > 0)
                            <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300" dir="ltr">
                                {{ number_format($store_customer->max_debt_limit, 2) }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="text-center">
                        @include('dashboard.store_customers.parts.status', ['store_customer' => $store_customer])
                    </td>

                    <!-- Created At -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $store_customer->created_at->format('Y-m-d') }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center sticky end-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs z-10 border-s border-slate-100 dark:border-slate-800 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">
                        @include('dashboard.store_customers.parts.actions', ['store_customer' => $store_customer])
                    </td>
                </tr>
            @empty
                <!-- Ultra-Premium Empty State -->
                <tr>
                    <td colspan="{{ isset($stores) ? 11 : 10 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-users text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('store_customers.no_store_customers_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('store_customers.no_customers_desc') !!}
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-3">
    @forelse ($store_customers as $store_customer)
        @php
            $balance = $store_customer->calculated_balance;
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$store_customer->phone);
        @endphp
        <div id="mobile-row{{ $store_customer->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Card Header: Identity & Status -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black text-sm shadow-sm shadow-indigo-500/20">
                        {{ mb_substr($store_customer->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <a href="{!! route('dashboard.store-customers.show', $store_customer->id) !!}" 
                               class="text-sm font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">
                                {{ $store_customer->name }}
                            </a>
                            @if ($store_customer->is_walk_in)
                                <span class="badge-pill badge-pill-info text-[9px] px-1.5 py-0.5">
                                    مباشر
                                </span>
                            @endif
                        </div>

                        <!-- Store Name (if multi-store) -->
                        @if (isset($stores) && $store_customer->store)
                            <div class="flex items-center gap-1 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                <i class="fas fa-store text-[10px]"></i>
                                <span class="truncate">{{ $store_customer->store->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="shrink-0">
                    @include('dashboard.store_customers.parts.status', ['store_customer' => $store_customer])
                </div>
            </div>

            <!-- Quick Contact & Date Bar -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800/80 text-xs">
                @if ($store_customer->phone)
                    <div class="flex items-center gap-1.5">
                        <!-- Direct Call -->
                        <a href="tel:{{ $store_customer->phone }}" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs hover:bg-indigo-100 transition-colors" dir="ltr">
                            <i class="fas fa-phone-alt text-[10px]"></i>
                            <span>{{ $store_customer->phone }}</span>
                        </a>

                        <!-- WhatsApp Action -->
                        @if ($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" 
                               class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition-colors shadow-2xs" 
                               title="مراسلة عبر واتساب">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                        @endif
                    </div>
                @else
                    <span class="text-xs text-slate-400 italic">بدون رقم هاتف</span>
                @endif

                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium" dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $store_customer->created_at->format('Y-m-d') }}
                </span>
            </div>

            <!-- Mini Financial Summary Matrix (3 Columns) -->
            <div class="grid grid-cols-3 gap-2 p-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 text-center">
                <!-- Current Balance -->
                <div class="space-y-0.5">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        {!! __('store_customers.current_balance') !!}
                    </span>
                    @if ($balance > 0)
                        <span class="block font-mono text-xs font-black text-rose-600 dark:text-rose-400">
                            {{ number_format($balance, 2) }}
                        </span>
                        <span class="inline-block text-[9px] font-bold text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-950/80 px-1.5 rounded-full">عليه</span>
                    @elseif ($balance < 0)
                        <span class="block font-mono text-xs font-black text-emerald-600 dark:text-emerald-400">
                            {{ number_format(abs($balance), 2) }} +
                        </span>
                        <span class="inline-block text-[9px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/80 px-1.5 rounded-full">له</span>
                    @else
                        <span class="block font-mono text-xs font-bold text-slate-600 dark:text-slate-300">0.00</span>
                        <span class="inline-block text-[9px] font-bold text-slate-500 bg-slate-200 dark:bg-slate-700 px-1.5 rounded-full">خالص</span>
                    @endif
                </div>

                <!-- Total Debts -->
                <div class="space-y-0.5 border-s border-slate-200 dark:border-slate-700/60 ps-1">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        {!! __('store_customers.total_debts') !!}
                    </span>
                    <span class="block font-mono text-xs font-bold text-slate-700 dark:text-slate-200" dir="ltr">
                        {{ number_format($store_customer->total_debts ?? 0, 2) }}
                    </span>
                </div>

                <!-- Total Payments -->
                <div class="space-y-0.5 border-s border-slate-200 dark:border-slate-700/60 ps-1">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        {!! __('store_customers.total_payments') !!}
                    </span>
                    <span class="block font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($store_customer->total_payments ?? 0, 2) }}
                    </span>
                </div>
            </div>

            <!-- Debt Limit & Actions Row -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
                <!-- Limit indicator -->
                <div class="text-[11px]">
                    @if ($store_customer->bypass_debt_limit)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40">
                            <i class="fas fa-infinity text-[9px]"></i>
                            <span>تخطي السقف</span>
                        </span>
                    @elseif ($store_customer->max_debt_limit && $store_customer->max_debt_limit > 0)
                        <span class="text-slate-500 dark:text-slate-400 text-[10px]">
                            السقف: <strong class="font-mono text-slate-700 dark:text-slate-200">{{ number_format($store_customer->max_debt_limit, 0) }}</strong>
                        </span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-1.5">
                    @include('dashboard.store_customers.parts.actions', ['store_customer' => $store_customer])
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-users"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('store_customers.no_store_customers_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('store_customers.no_customers_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($store_customers->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $store_customers->links('dashboard.includes.pagination') !!}
    </div>
@endif
