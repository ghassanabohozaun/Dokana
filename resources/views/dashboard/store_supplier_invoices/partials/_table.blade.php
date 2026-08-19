<input type="hidden" id="store_supplier_invoices-total-count" value="{!! $invoices->total() !!}">

<div class="overflow-x-auto custom-scrollbar">
    <table class="table-modern" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if (isset($stores))
                    <th>{!! __('stores.store') !!}</th>
                @endif
                <th>{!! __('store_supplier_invoices.invoice_number') !!}</th>
                <th>{!! __('store_supplier_invoices.supplier') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.total_amount') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.paid_amount') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.remaining_amount') !!}</th>
                <th class="text-center">{!! __('store_supplier_invoices.status') !!}</th>
                <th>{!! __('store_supplier_invoices.date') !!}</th>
                <th class="w-24 text-center">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($invoices as $invoice)
                <tr id="row{{ $invoice->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($invoice->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $invoice->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Invoice Number -->
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <span class="font-mono text-xs font-bold text-slate-800 dark:text-white" dir="ltr">
                                #{{ $invoice->invoice_number }}
                            </span>
                        </div>
                    </td>

                    <!-- Supplier -->
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 text-xs">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                {{ optional($invoice->supplier)->name ?: '—' }}
                            </span>
                        </div>
                    </td>

                    <!-- Total Amount -->
                    <td class="text-center font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400" dir="ltr">
                        {{ number_format($invoice->total_amount, 2) }}
                    </td>

                    <!-- Paid Amount -->
                    <td class="text-center font-mono font-bold text-xs text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($invoice->paid_amount, 2) }}
                    </td>

                    <!-- Remaining Amount -->
                    <td class="text-center">
                        @if ($invoice->remaining_amount > 0)
                            <span class="badge-pill badge-pill-danger font-mono text-xs">
                                {{ number_format($invoice->remaining_amount, 2) }}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-success text-xs font-bold">
                                0.00
                            </span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="text-center">
                        @if ($invoice->status === 'paid')
                            <span class="badge-pill badge-pill-success text-[11px]">
                                {!! __('store_supplier_invoices.paid') !!}
                            </span>
                        @elseif ($invoice->status === 'partially_paid')
                            <span class="badge-pill badge-pill-warning text-[11px]">
                                {!! __('store_supplier_invoices.partially_paid') !!}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-danger text-[11px]">
                                {!! __('store_supplier_invoices.unpaid') !!}
                            </span>
                        @endif
                    </td>

                    <!-- Invoice Date -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : $invoice->created_at->format('Y-m-d') }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.store_supplier_invoices.parts.actions', ['invoice' => $invoice])
                    </td>
                </tr>
            @empty
                <!-- Ultra-Premium Empty State -->
                <tr>
                    <td colspan="{{ isset($stores) ? 10 : 9 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-file-invoice-dollar text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('store_supplier_invoices.no_store_supplier_invoices_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('store_supplier_invoices.no_invoices_desc') !!}
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($invoices->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $invoices->links() !!}
    </div>
@endif
