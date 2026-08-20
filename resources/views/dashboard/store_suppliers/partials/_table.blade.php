<input type="hidden" id="store_suppliers-total-count" value="{!! $suppliers->total() !!}">

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
                <th>{!! __('store_suppliers.name') !!}</th>
                <th>{!! __('store_suppliers.mobile') !!}</th>
                <th class="text-center">{!! __('store_suppliers.total_invoices') !!}</th>
                <th class="text-center">{!! __('store_suppliers.total_paid') !!}</th>
                <th class="text-center">{!! __('store_suppliers.total_remaining') !!}</th>
                <th>{!! __('store_suppliers.bank_name') !!}</th>
                <th>{!! __('store_suppliers.date') !!}</th>
                <th class="w-24 text-center sticky end-0 bg-slate-50 dark:bg-slate-800 z-10 border-s border-slate-200/80 dark:border-slate-700/80 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">{!! __('general.actions') !!}</th>
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
                                <a href="{!! route('dashboard.store-suppliers.show', $supplier->id) !!}" class="text-xs font-bold text-slate-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block truncate">
                                    {{ $supplier->name }}
                                </a>
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
                    <td class="text-center sticky end-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs z-10 border-s border-slate-100 dark:border-slate-800 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">
                        @include('dashboard.store_suppliers.parts.actions', ['supplier' => $supplier])
                    </td>
                </tr>
            @empty
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
                            {!! __('store_suppliers.no_suppliers_found') !!}
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

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-3">
    @forelse ($suppliers as $supplier)
        @php
            $totalInvoices = $supplier->invoices_sum_total_amount ?: 0;
            $totalPaid = $supplier->invoices_sum_paid_amount ?: 0;
            $totalRemaining = $supplier->invoices_sum_remaining_amount ?: 0;
            $cleanMobile = preg_replace('/[^0-9]/', '', (string)$supplier->mobile);
        @endphp
        <div id="mobile-row{{ $supplier->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Avatar, Name & Store Tag -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-600 text-white font-black text-sm shadow-xs">
                        {{ mb_substr($supplier->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <a href="{!! route('dashboard.store-suppliers.show', $supplier->id) !!}" class="text-sm font-bold text-slate-900 dark:text-white hover:text-indigo-600 truncate block">
                            {{ $supplier->name }}
                        </a>
                        @if (isset($stores) && $supplier->store)
                            <div class="flex items-center gap-1 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                <span class="truncate">{{ $supplier->store->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Remaining Status Badge -->
                <div class="shrink-0">
                    @if ($totalRemaining > 0)
                        <span class="badge-pill badge-pill-danger text-[10px]">
                            متبقي: {{ number_format($totalRemaining, 0) }}
                        </span>
                    @else
                        <span class="badge-pill badge-pill-success text-[10px]">
                            خالص
                        </span>
                    @endif
                </div>
            </div>

            <!-- Contact Information Bar -->
            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 space-y-1.5 text-xs">
                @if($supplier->mobile)
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">الهاتف:</span>
                        <div class="flex items-center gap-1.5">
                            <a href="tel:{{ $supplier->mobile }}" class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-200 font-bold hover:text-indigo-600" dir="ltr">
                                <i class="fas fa-phone-alt text-[10px] text-slate-400"></i>
                                <span>{{ $supplier->mobile }}</span>
                            </a>
                            @if($cleanMobile)
                                <a href="https://wa.me/{{ $cleanMobile }}" target="_blank" class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 hover:bg-emerald-100 text-xs" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($supplier->bank_name || $supplier->account_number)
                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-200/50 dark:border-slate-700/40 text-[11px]">
                        <span class="text-slate-400 font-medium shrink-0">الحساب البنكي:</span>
                        <span class="text-slate-700 dark:text-slate-300 font-mono truncate" dir="ltr">
                            {{ $supplier->bank_name }} {{ $supplier->account_number ? '('.$supplier->account_number.')' : '' }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Mini Matrix: Invoices, Paid, Remaining -->
            <div class="grid grid-cols-3 gap-2 p-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 text-center">
                <!-- Total Invoices -->
                <div class="space-y-0.5">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">إجمالي الفواتير</span>
                    <span class="block font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400" dir="ltr">
                        {{ number_format($totalInvoices, 2) }}
                    </span>
                </div>

                <!-- Total Paid -->
                <div class="space-y-0.5 border-s border-slate-200 dark:border-slate-700/60 ps-1">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">المدفوع</span>
                    <span class="block font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400" dir="ltr">
                        {{ number_format($totalPaid, 2) }}
                    </span>
                </div>

                <!-- Total Remaining -->
                <div class="space-y-0.5 border-s border-slate-200 dark:border-slate-700/60 ps-1">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">المتبقي</span>
                    <span class="block font-mono text-xs font-black {{ $totalRemaining > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300' }}" dir="ltr">
                        {{ number_format($totalRemaining, 2) }}
                    </span>
                </div>
            </div>

            <!-- Footer: Date & Actions -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium" dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $supplier->created_at ? $supplier->created_at->format('Y-m-d') : '—' }}
                </span>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.store_suppliers.parts.actions', ['supplier' => $supplier])
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-truck-loading"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('store_suppliers.no_suppliers_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('store_suppliers.no_suppliers_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($suppliers->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $suppliers->links('dashboard.includes.pagination') !!}
    </div>
@endif
