<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{!! __('store_supplier_payments.payment_date') !!}</th>
                <th class="text-center">{!! __('store_supplier_payments.amount') !!}</th>
                <th>{!! __('bank_accounts.bank_account') !!}</th>
                <th>{!! __('store_supplier_invoices.invoice_number') !!}</th>
                <th class="hidden sm:table-cell">{!! __('departments.created_by') !!}</th>
                <th>{!! __('store_supplier_payments.notes') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($payments as $payment)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300" dir="ltr">
                            {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '—' }}
                        </span>
                    </td>

                    <!-- Amount -->
                    <td class="text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/60" dir="ltr">
                            <i class="fas fa-arrow-up text-[10px]"></i>
                            {{ number_format($payment->amount, 2) }}
                        </span>
                    </td>

                    <!-- Bank Account -->
                    <td>
                        @if($payment->bankAccount)
                            @php
                                $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                $accountName = $payment->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $payment->bankAccount->account_number;
                            @endphp
                            <span class="text-xs font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <i class="fas fa-wallet text-emerald-500 text-xs"></i>
                                {{ $accountName }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Invoice -->
                    <td>
                        @if($payment->invoice)
                            <span class="inline-flex items-center gap-1 font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400" dir="ltr">
                                #{{ $payment->invoice->invoice_number }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">{!! __('store_suppliers.general_payment') !!}</span>
                        @endif
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($payment->createdBy)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i>
                                {{ $payment->createdBy->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Notes -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[150px] block" title="{{ $payment->notes }}">
                            {{ $payment->notes ?: '—' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 text-lg mb-2">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h6 class="text-xs font-bold text-slate-600 dark:text-slate-400">{!! __('store_suppliers.no_payments_found') !!}</h6>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-2.5">
    @forelse ($payments as $payment)
        <div class="p-3.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs space-y-2.5">
            <!-- Header: Amount & Bank -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 text-xs">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-white">{!! __('store_suppliers.supplier_payments_tab') !!}</span>
                </div>

                <span class="font-mono text-sm font-black text-emerald-600 dark:text-emerald-400 shrink-0" dir="ltr">
                    {{ number_format($payment->amount, 2) }}
                </span>
            </div>

            <!-- Bank Account & Invoice Info -->
            <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 text-xs space-y-1">
                @if($payment->bankAccount)
                    @php
                        $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                        $accountName = $payment->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $payment->bankAccount->account_number;
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-medium">{!! __('store_suppliers.vault_or_account') !!}:</span>
                        <span class="font-bold text-slate-700 dark:text-slate-200">{{ $accountName }}</span>
                    </div>
                @endif

                @if($payment->invoice)
                    <div class="flex items-center justify-between pt-1 border-t border-slate-200/40 dark:border-slate-700/40">
                        <span class="text-[10px] text-slate-400 font-medium">{!! __('store_suppliers.invoice') !!}:</span>
                        <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400" dir="ltr">#{{ $payment->invoice->invoice_number }}</span>
                    </div>
                @endif

                @if($payment->notes)
                    <div class="pt-1 border-t border-slate-200/40 dark:border-slate-700/40">
                        <p class="text-[11px] text-slate-600 dark:text-slate-300 font-medium">{{ $payment->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Footer: Date -->
            <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 pt-1 border-t border-slate-100 dark:border-slate-800">
                <span class="font-mono text-[10px]">#{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</span>
                <span dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '—' }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-slate-400 text-xs">
            <i class="fas fa-receipt text-2xl mb-2 block opacity-40"></i>
            {!! __('store_suppliers.no_payments_found') !!}
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
