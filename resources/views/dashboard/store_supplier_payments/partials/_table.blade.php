<input type="hidden" id="store_supplier_payments-total-count" value="{!! $payments->total() !!}">

<div class="overflow-x-auto custom-scrollbar">
    <table class="table-modern" id="myTable">
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
                <th class="w-24 text-center">{!! __('general.actions') !!}</th>
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
                    <td class="text-center">
                        @include('dashboard.store_supplier_payments.parts.actions', ['payment' => $payment])
                    </td>
                </tr>
            @empty
                <!-- Ultra-Premium Empty State -->
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

<!-- Pagination Footer -->
@if ($payments->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $payments->links() !!}
    </div>
@endif
