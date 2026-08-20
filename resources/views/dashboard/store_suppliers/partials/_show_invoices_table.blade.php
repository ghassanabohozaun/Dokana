<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{!! __('store_supplier_invoices.invoice_number') !!}</th>
                <th>{!! __('store_supplier_invoices.invoice_date') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.total_amount') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.paid_amount') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.remaining_amount') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.status') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($invoices as $invoice)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                        </span>
                    </td>

                    <!-- Invoice # -->
                    <td>
                        <span class="inline-flex items-center gap-1 font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400" dir="ltr">
                            #{{ $invoice->invoice_number }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300" dir="ltr">
                            {{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '—' }}
                        </span>
                    </td>

                    <!-- Total Amount -->
                    <td class="text-center font-mono font-black text-xs text-slate-800 dark:text-white" dir="ltr">
                        {{ number_format($invoice->total_amount, 2) }}
                    </td>

                    <!-- Paid Amount -->
                    <td class="text-center font-mono font-bold text-xs text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($invoice->paid_amount, 2) }}
                    </td>

                    <!-- Remaining Amount -->
                    <td class="text-center font-mono font-black text-xs {{ $invoice->remaining_amount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400' }}" dir="ltr">
                        {{ number_format($invoice->remaining_amount, 2) }}
                    </td>

                    <!-- Status -->
                    <td class="text-center">
                        @if($invoice->remaining_amount <= 0)
                            <span class="badge-pill badge-pill-success text-[10px]">
                                <i class="fas fa-check-circle text-[9px] me-0.5"></i> {!! __('store_suppliers.paid') !!}
                            </span>
                        @elseif($invoice->paid_amount > 0)
                            <span class="badge-pill badge-pill-warning text-[10px]">
                                <i class="fas fa-adjust text-[9px] me-0.5"></i> {!! __('store_suppliers.partially_paid') !!}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-danger text-[10px]">
                                <i class="fas fa-times-circle text-[9px] me-0.5"></i> {!! __('store_suppliers.unpaid') !!}
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 text-lg mb-2">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h6 class="text-xs font-bold text-slate-600 dark:text-slate-400">{!! __('store_suppliers.no_invoices_found') !!}</h6>
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
    @forelse ($invoices as $invoice)
        <div class="p-3.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs space-y-2.5">
            <!-- Header: Invoice # & Status -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400 text-xs">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <span class="font-mono font-bold text-xs text-slate-900 dark:text-white" dir="ltr">
                        #{{ $invoice->invoice_number }}
                    </span>
                </div>

                @if($invoice->remaining_amount <= 0)
                    <span class="badge-pill badge-pill-success text-[10px]">{!! __('store_suppliers.paid') !!}</span>
                @elseif($invoice->paid_amount > 0)
                    <span class="badge-pill badge-pill-warning text-[10px]">{!! __('store_suppliers.partially_paid') !!}</span>
                @else
                    <span class="badge-pill badge-pill-danger text-[10px]">{!! __('store_suppliers.unpaid') !!}</span>
                @endif
            </div>

            <!-- Financial Matrix (Total / Paid / Remaining) -->
            <div class="grid grid-cols-3 gap-2 p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 text-center text-xs">
                <div>
                    <span class="text-[10px] text-slate-400 block mb-0.5">{!! __('store_supplier_invoices.total_amount') !!}:</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white" dir="ltr">{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block mb-0.5">{!! __('store_supplier_invoices.paid_amount') !!}:</span>
                    <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400" dir="ltr">{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block mb-0.5">{!! __('store_supplier_invoices.remaining_amount') !!}:</span>
                    <span class="font-mono font-bold {{ $invoice->remaining_amount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400' }}" dir="ltr">{{ number_format($invoice->remaining_amount, 2) }}</span>
                </div>
            </div>

            <!-- Footer: Date -->
            <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 pt-1 border-t border-slate-100 dark:border-slate-800">
                <span class="font-mono text-[10px]">#{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</span>
                <span dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : '—' }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-slate-400 text-xs">
            <i class="fas fa-file-invoice text-2xl mb-2 block opacity-40"></i>
            {!! __('store_suppliers.no_invoices_found') !!}
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($invoices->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $invoices->links('dashboard.includes.pagination') !!}
    </div>
@endif
