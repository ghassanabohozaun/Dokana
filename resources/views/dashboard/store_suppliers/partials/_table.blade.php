<input type="hidden" id="store_suppliers-total-count" value="{!! $suppliers->total() !!}">

<div class="overflow-x-auto custom-scrollbar">
    <table class="table-modern" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if (isset($stores))
                    <th>{!! __('stores.store') !!}</th>
                @endif
                <th>{!! __('store_suppliers.name') !!}</th>
                <th>{!! __('store_suppliers.mobile') !!}</th>
                <th class="text-center">{!! __('store_suppliers.total_invoices') !!}</th>
                <th class="text-center">{!! __('store_suppliers.total_paid') !!}</th>
                <th class="text-center">{!! __('store_suppliers.total_remaining') !!}</th>
                <th>{!! __('store_suppliers.bank_name') !!}</th>
                <th>{!! __('store_suppliers.date') !!}</th>
                <th class="w-24 text-center">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($suppliers as $supplier)
                @php
                    $totalInvoices = $supplier->invoices_sum_total_amount ?: 0;
                    $totalPaid = $supplier->invoices_sum_paid_amount ?: 0;
                    $totalRemaining = $supplier->invoices_sum_remaining_amount ?: 0;
                @endphp
                <tr id="row{{ $supplier->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($supplier->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $supplier->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Supplier Name & Email -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                    {{ $supplier->name }}
                                </span>
                                @if ($supplier->email)
                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 block truncate">
                                        {{ $supplier->email }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Mobile -->
                    <td>
                        <a href="tel:{{ $supplier->mobile }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" dir="ltr">
                            <i class="fas fa-phone-alt text-[10px] text-slate-400"></i>
                            <span>{{ $supplier->mobile }}</span>
                        </a>
                    </td>

                    <!-- Total Invoices -->
                    <td class="text-center font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400" dir="ltr">
                        {{ number_format($totalInvoices, 2) }}
                    </td>

                    <!-- Total Paid -->
                    <td class="text-center font-mono font-bold text-xs text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($totalPaid, 2) }}
                    </td>

                    <!-- Total Remaining -->
                    <td class="text-center">
                        @if ($totalRemaining > 0)
                            <span class="badge-pill badge-pill-danger font-mono text-xs">
                                {{ number_format($totalRemaining, 2) }}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-success text-xs font-bold">
                                0.00
                            </span>
                        @endif
                    </td>

                    <!-- Bank / Account -->
                    <td>
                        @if ($supplier->bank_name || $supplier->account_number)
                            <div class="text-xs">
                                <span class="font-bold text-slate-700 dark:text-slate-200 block">
                                    {{ $supplier->bank_name ?: '—' }}
                                </span>
                                @if ($supplier->account_number)
                                    <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 block" dir="ltr">
                                        {{ $supplier->account_number }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $supplier->created_at->format('Y-m-d') }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.store_suppliers.parts.actions', ['supplier' => $supplier])
                    </td>
                </tr>
            @empty
                <!-- Ultra-Premium Empty State -->
                <tr>
                    <td colspan="{{ isset($stores) ? 10 : 9 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-truck-loading text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('store_suppliers.no_store_suppliers_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('store_suppliers.no_suppliers_desc') !!}
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($suppliers->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $suppliers->links() !!}
    </div>
@endif
