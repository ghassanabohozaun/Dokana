<input type="hidden" id="store_supplier_payments-total-count" value="{!! $payments->total() !!}">

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
                <th>{!! __('store_supplier_payments.supplier') !!}</th>
                <th>{!! __('store_supplier_payments.invoice') !!}</th>
                <th>{!! __('store_supplier_payments.bank_account') !!}</th>
                <th class="text-center">{!! __('store_supplier_payments.amount') !!}</th>
                <th>{!! __('store_supplier_payments.date') !!}</th>
                <th>{!! __('store_supplier_payments.notes') !!}</th>
                <th class="w-24 text-center sticky end-0 bg-slate-50 dark:bg-slate-800 z-10 border-s border-slate-200/80 dark:border-slate-700/80 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($payments as $payment)
                <tr id="row{{ $payment->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($payment->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $payment->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Supplier -->
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                {{ optional($payment->supplier)->name ?: '—' }}
                            </span>
                        </div>
                    </td>

                    <!-- Invoice -->
                    <td>
                        @if ($payment->invoice)
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-file-invoice text-xs text-slate-400"></i>
                                <span class="font-mono text-xs font-semibold text-slate-700 dark:text-slate-300" dir="ltr">
                                    #{{ $payment->invoice->invoice_number }}
                                </span>
                            </div>
                        @else
                            <span class="badge-pill badge-pill-info text-[10px]">
                                {!! __('general.general_payment') ?? 'دفعة على الحساب' !!}
                            </span>
                        @endif
                    </td>

                    <!-- Bank Account / Wallet -->
                    <td>
                        @if ($payment->bankAccount)
                            @php
                                $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                        {{ $entityName }}
                                    </span>
                                    @if ($payment->bankAccount->account_type !== 'cash')
                                        <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 block" dir="ltr">
                                            {{ $payment->bankAccount->account_number }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Amount -->
                    <td class="text-center">
                        <span class="font-mono font-black text-xs text-emerald-600 dark:text-emerald-400" dir="ltr">
                            {{ number_format($payment->amount, 2) }}
                        </span>
                    </td>

                    <!-- Payment Date -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') }}
                        </span>
                    </td>

                    <!-- Notes -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[160px] block" title="{{ $payment->notes }}">
                            {{ $payment->notes ?: '—' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center sticky end-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs z-10 border-s border-slate-100 dark:border-slate-800 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">
                        @include('dashboard.store_supplier_payments.parts.actions', ['payment' => $payment])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 9 : 8 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-money-check-alt text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('store_supplier_payments.no_store_supplier_payments_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('store_supplier_payments.no_payments_desc') !!}
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
    @forelse ($payments as $payment)
        <div id="mobile-row{{ $payment->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Supplier Name & Amount Badge -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-base font-bold shadow-xs">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                            {{ optional($payment->supplier)->name ?: '—' }}
                        </h3>
                        <div class="flex items-center gap-1.5 text-[11px] mt-0.5">
                            @if ($payment->invoice)
                                <span class="text-slate-500 dark:text-slate-400 font-mono" dir="ltr">
                                    <i class="fas fa-file-invoice text-[9px]"></i> #{{ $payment->invoice->invoice_number }}
                                </span>
                            @else
                                <span class="badge-pill badge-pill-info text-[9px] px-1.5 py-0.2">
                                    دفعة على الحساب
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Amount -->
                <div class="shrink-0 text-end">
                    <span class="block font-mono text-sm font-black text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($payment->amount, 2) }}
                    </span>
                    <span class="text-[10px] text-slate-400">سند صرف</span>
                </div>
            </div>

            <!-- Details: Bank Account & Notes -->
            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 space-y-1.5 text-xs">
                @if ($payment->bankAccount)
                    @php
                        $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                    @endphp
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">طريقة الصرف:</span>
                        <div class="flex items-center gap-1 text-slate-700 dark:text-slate-200 font-bold">
                            <i class="fas fa-wallet text-emerald-500 text-[10px]"></i>
                            <span>{{ $entityName }}</span>
                        </div>
                    </div>
                @endif

                @if($payment->notes)
                    <div class="flex items-start justify-between gap-2 pt-1 border-t border-slate-200/50 dark:border-slate-700/40 text-[11px]">
                        <span class="text-slate-400 font-medium shrink-0">ملاحظات:</span>
                        <span class="text-slate-600 dark:text-slate-300 truncate">{{ $payment->notes }}</span>
                    </div>
                @endif
            </div>

            <!-- Footer: Date, Store & Actions -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                    @if (isset($stores) && $payment->store)
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-store text-[9px]"></i>
                            <span>{{ $payment->store->name }}</span>
                        </span>
                        <span>•</span>
                    @endif
                    <span dir="ltr">
                        <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') }}
                    </span>
                </div>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.store_supplier_payments.parts.actions', ['payment' => $payment])
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('store_supplier_payments.no_store_supplier_payments_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('store_supplier_payments.no_payments_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($payments->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $payments->links('dashboard.includes.pagination') !!}
    </div>
@endif
