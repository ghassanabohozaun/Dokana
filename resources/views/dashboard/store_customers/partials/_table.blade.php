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

<div class="overflow-x-auto custom-scrollbar">
    <table class="table-modern" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
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
                <th class="w-32 text-center">{!! __('general.actions') !!}</th>
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
                    <td class="text-center">
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

<!-- Pagination Footer -->
@if ($store_customers->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $store_customers->links() !!}
    </div>
@endif
