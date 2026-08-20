<input type="hidden" id="store_transactions-total-count" value="{!! $store_transactions->total() !!}">

@if (isset($metrics))
    <div id="ajax-metrics-data" class="hidden"
        data-total-payments="{!! number_format($metrics['total_payments'] ?? 0, 2) !!}"
        data-total-debts="{!! number_format($metrics['total_debts'] ?? 0, 2) !!}"
        data-net-balance="{!! number_format($metrics['net_balance'] ?? 0, 2) !!}"
        data-total-count="{!! number_format($metrics['total_transactions_count'] ?? 0, 0) !!}">
    </div>
@endif

<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if (isset($stores))
                    <th>{!! __('stores.store') !!}</th>
                @endif
                <th>{!! __('store_customers.store_customer') !!}</th>
                <th class="text-center">{!! __('store_transactions.type') !!}</th>
                <th class="text-center">{!! __('store_transactions.amount') !!}</th>
                <th>{!! __('bank_accounts.bank_account') !!}</th>
                <th>{!! __('store_transactions.description') !!}</th>
                <th>{!! __('store_transactions.date') !!}</th>
                <th class="w-24 text-center">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($store_transactions as $store_transaction)
                <tr id="row{{ $store_transaction->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($store_transactions->currentPage() - 1) * $store_transactions->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($store_transaction->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $store_transaction->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Customer -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="min-w-0">
                                @if($store_transaction->customer)
                                    <a href="{!! route('dashboard.store-customers.show', $store_transaction->customer->id) !!}" 
                                       class="text-xs font-bold text-slate-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block truncate">
                                        {{ $store_transaction->customer->name }}
                                    </a>
                                    @if ($store_transaction->customer->phone)
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500 block" dir="ltr">
                                            {{ $store_transaction->customer->phone }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Type -->
                    <td class="text-center">
                        @if ($store_transaction->type === 'debt')
                            <span class="badge-pill badge-pill-danger text-[11px]">
                                <i class="fas fa-arrow-down text-[10px]"></i> {!! __('store_transactions.debt') !!}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-success text-[11px]">
                                <i class="fas fa-arrow-up text-[10px]"></i> {!! __('store_transactions.payment') !!}
                            </span>
                        @endif
                    </td>

                    <!-- Amount -->
                    <td class="text-center font-mono font-black text-xs {{ $store_transaction->type === 'debt' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" dir="ltr">
                        {{ number_format($store_transaction->amount, 2) }}
                    </td>

                    <!-- Bank Account (if payment) -->
                    <td>
                        @if ($store_transaction->type === 'payment' && $store_transaction->bankAccount)
                            @php
                                $entityName = (optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale())) ?: optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                $accountName = $store_transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $store_transaction->bankAccount->account_number;
                            @endphp
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-wallet text-xs text-emerald-500"></i>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[150px]" title="{{ $accountName }}">
                                    {{ $accountName }}
                                </span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Description -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[160px] block" title="{{ $store_transaction->description }}">
                            {{ $store_transaction->description ?: '—' }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $store_transaction->transaction_date ? $store_transaction->transaction_date->format('Y-m-d') : $store_transaction->created_at->format('Y-m-d') }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.store_transactions.parts.actions', ['store_transaction' => $store_transaction])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 9 : 8 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-exchange-alt text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('store_transactions.no_store_transactions_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('store_transactions.no_transactions_desc') !!}
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
    @forelse ($store_transactions as $store_transaction)
        @php
            $isDebt = $store_transaction->type === 'debt';
        @endphp
        <div id="mobile-row{{ $store_transaction->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Customer Name & Transaction Amount Badge -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $isDebt ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400' : 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' }} text-base font-bold shadow-xs">
                        <i class="fas {{ $isDebt ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    <div class="min-w-0">
                        @if($store_transaction->customer)
                            <a href="{!! route('dashboard.store-customers.show', $store_transaction->customer->id) !!}" 
                               class="text-sm font-bold text-slate-900 dark:text-white hover:text-indigo-600 truncate block">
                                {{ $store_transaction->customer->name }}
                            </a>
                            @if ($store_transaction->customer->phone)
                                <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 block" dir="ltr">
                                    {{ $store_transaction->customer->phone }}
                                </span>
                            @endif
                        @else
                            <span class="text-sm font-bold text-slate-400">—</span>
                        @endif
                    </div>
                </div>

                <!-- Type & Amount Badge -->
                <div class="shrink-0 text-end">
                    <span class="block font-mono text-sm font-black {{ $isDebt ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" dir="ltr">
                        {{ number_format($store_transaction->amount, 2) }}
                    </span>
                    @if ($isDebt)
                        <span class="inline-block text-[9px] font-bold text-rose-700 dark:text-rose-400 bg-rose-100 dark:bg-rose-950/80 px-1.5 rounded-full">سحب / دين</span>
                    @else
                        <span class="inline-block text-[9px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/80 px-1.5 rounded-full">سداد / قبض</span>
                    @endif
                </div>
            </div>

            <!-- Details: Bank Account & Description Box -->
            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 space-y-1.5 text-xs">
                @if ($store_transaction->type === 'payment' && $store_transaction->bankAccount)
                    @php
                        $entityName = (optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale())) ?: optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                        $accountName = $store_transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $store_transaction->bankAccount->account_number;
                    @endphp
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">جهة السداد:</span>
                        <div class="flex items-center gap-1 text-slate-700 dark:text-slate-200 font-bold">
                            <i class="fas fa-wallet text-emerald-500 text-[10px]"></i>
                            <span>{{ $accountName }}</span>
                        </div>
                    </div>
                @endif

                @if($store_transaction->description)
                    <div class="flex items-start justify-between gap-2 pt-1 border-t border-slate-200/50 dark:border-slate-700/40 text-[11px]">
                        <span class="text-slate-400 font-medium shrink-0">البيان:</span>
                        <span class="text-slate-600 dark:text-slate-300 truncate">{{ $store_transaction->description }}</span>
                    </div>
                @endif
            </div>

            <!-- Footer: Store / Date & Actions -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                    @if (isset($stores) && $store_transaction->store)
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-store text-[9px]"></i>
                            <span>{{ $store_transaction->store->name }}</span>
                        </span>
                        <span>•</span>
                    @endif
                    <span dir="ltr">
                        <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                        {{ $store_transaction->transaction_date ? $store_transaction->transaction_date->format('Y-m-d') : $store_transaction->created_at->format('Y-m-d') }}
                    </span>
                </div>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.store_transactions.parts.actions', ['store_transaction' => $store_transaction])
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('store_transactions.no_store_transactions_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('store_transactions.no_transactions_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($store_transactions->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $store_transactions->links('dashboard.includes.pagination') !!}
    </div>
@endif
